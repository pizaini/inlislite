<?php

namespace common\components;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collections;
use common\models\Collectionloanitems;


use \DateTime;

class SirkulasiHelpers 
{
    

   /**
    * [loadModelKoleksi description]
    * @param  [type] $nomorBarcode [description]
    * @return [type]               [description]
    */
   public static function loadModelKoleksi($nomorBarcode) {

            $model = \common\models\Collections::findOne(['NomorBarcode' => $nomorBarcode]);
            if ($model === null)
                throw new \yii\web\HttpException(404, 'Koleksi tersebut tidak terdapat dalam database.');
            else{
                if ($model->Status_id != "1") 
                throw new \yii\web\HttpException(404, 'Koleksi sedang tidak tersedia, periksa kembali nomor barcode.');
            }
            return $model;

    }


    /**
    * [loadModelKoleksi description]
    * @param  [type] $nomorBarcode [description]
    * @return [type]               [description]
    */
   public static function loadModelKoleksiByBarcode($nomorBarcode) {

            $model = \common\models\Collections::findOne(['NomorBarcode' => $nomorBarcode]);
            if ($model === null){
                throw new \yii\web\HttpException(404, 'Koleksi tersebut tidak terdapat dalam database.');
            }
            

            return $model;

    }

    /**
     * [validatePelanggaran description]
     * @param  [string] $noAnggota [description]
     * @return [date]            [description]
     */
    public static function validatePelanggaran($noAnggota)
    {
        // JenisDenda_id = 5 (SUSPEND)
        $sql = "SELECT DATE_FORMAT(DATE_ADD(pelanggaran.CreateDate,INTERVAL JumlahSuspend DAY) ,'%Y-%m-%d')AS BolehPinjam " .
            " FROM pelanggaran" .
            " INNER JOIN members ON pelanggaran.Member_id = members.id" .
            " WHERE members.MemberNo = '" . $noAnggota . "' AND jumlahsuspend > 0".
            " ORDER BY BolehPinjam DESC";

        $result = Yii::$app->db->createCommand($sql)->queryScalar();
        if (!$result)
        { 
            // Tidak ada pelanggaran yang di suspend.
            $result = date('Y-m-d');
        }
        return $result;
    }

    /**
     * [isMemberStatus description]
     * @param  [type]  $noAnggota [description]
     * @param  [type]  $status    [description]
     * @return boolean            [description]
     */
    public static function isMemberStatus($noAnggota,$status)
    {
        // 5 Suspend,
        // 3 Active
            $sql = "SELECT StatusAnggota_id FROM members WHERE MemberNo = '" .$noAnggota. "'";
            $result = Yii::$app->db->createCommand($sql)->queryScalar();
            if (!$result)
            { 
                return false;
            }else
            {
                if($result == $status) 
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
    }

 
    public static function isMemberExpired($noAnggota)
    {
        $sql = "SELECT * FROM members WHERE MemberNo = '" .$noAnggota. "' and EndDate >= '" . date('Y-m-d') . "'";
        $result = Yii::$app->db->createCommand($sql)->queryScalar();
        if (!$result)
        { 
                // Tidak ada pelanggaran yang di suspend.
            $result = false;
        }else{
            $result = true;
        }
        return $result;

    }

    public static function isUserHasAccess($userID)
    {
        

        $sql = "SELECT * FROM users INNER JOIN userloclibforloan ON userloclibforloan.User_id=users.ID WHERE ID = " .$userID.  " and IsActive = 1";
        $result = Yii::$app->db->createCommand($sql)->queryScalar();

        return $result;
    }

    public static function isUserSuperAdmin($userID)
    {
        

        // $sql = "SELECT * FROM users INNER JOIN userloclibforloan ON userloclibforloan.User_id=users.ID WHERE ID = " .$userID.  " and IsActive = 1";
        $sql = "SELECT auth_assignment.`item_name`
                FROM auth_assignment
                WHERE LCASE(auth_assignment.`user_id`) = " .$userID.  " ORDER BY
                CASE 
                      WHEN auth_assignment.`item_name` = 'superadmin' THEN 1
                      ELSE 2
                END";
        $result = Yii::$app->db->createCommand($sql)->queryScalar();

        return $result;
    }

    public static function isMemberCanReturnOnLocation($NomorBarcode, $locationLibrary)
    {
        $sql = "SELECT memberloanauthorizelocation.LocationLoan_id
                FROM collectionloanitems
                INNER JOIN collections ON collections.ID = collectionloanitems.Collection_id
                INNER JOIN collectionloans ON collectionloans.ID = collectionloanitems.CollectionLoan_id
                INNER JOIN members ON members.ID = collectionloans.Member_id
                INNER JOIN memberloanauthorizelocation ON memberloanauthorizelocation.Member_id = members.ID
                WHERE collections.NomorBarcode = '" .$NomorBarcode. "' AND memberloanauthorizelocation.LocationLoan_id = " .$locationLibrary. " AND collectionloanitems.LoanStatus = 'Loan' " ;
        $result = Yii::$app->db->createCommand($sql)->queryScalar();
        if (!empty($result)) {
        return true;
        }
    }
    public static function isMemberCanLoanOnLocation($memberNo, $userId)
    {
        $sql = "";
        $sql2 = "";
        $locLibUser=array(); //data lokasi yang petugas boleh cek

        // Ambil data lokasi yang petugas boleh cek
        $sql = "SELECT * FROM users" .
        " INNER JOIN userloclibforloan ON userloclibforloan.User_id=users.ID" .
        " WHERE ID = " . $userId ." and IsActive = 1";

        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if(!is_null($result)){
            
            foreach($result as $row )
            {
                array_push($locLibUser,$row["LocLib_id"]); //data lokasi yang petugas boleh cek
            }

            // Ambil data lokasi yang anggota boleh melakukan peminjaman
            $sql2 = "SELECT m.MemberNo as MemberNo, ml.LocationLoan_id as LocationLoan_id" .
            " FROM memberloanauthorizelocation ml INNER JOIN members m ON (ml.Member_id = m.ID) " .
            " WHERE m.MemberNo = '" .$memberNo. "'" .
            " and m.StatusAnggota_id = 3" . //'ACTIVE'
            " and m.EndDate >= '" . date('Y-m-d') . "'";

            $resultSQL2 = Yii::$app->db->createCommand($sql2)->queryAll(); //data lokasi yang anggota boleh melakukan peminjaman
            if(!is_null($resultSQL2)){  // jika ada lokasi anggota yang boleh meminjam 

               foreach($resultSQL2 as $row)
               {
                    if (in_array($row["LocationLoan_id"], $locLibUser) && $row["LocationLoan_id"] == Yii::$app->location->get() )
                    {
                        return true;
                    }
               }
               return false;
                
            }
            else
            {
                return false;
            }
                   
        }
        else
        {
            return false;
        }
    }

    public static function isMemberCanLoanOnLocationMandiri($memberNo, $userId)
    {
        $sql = "";
        $sql2 = "";
        $locLibUser=array(); //data lokasi yang petugas boleh cek

        // Ambil data lokasi yang petugas boleh cek
        $sql = "SELECT * FROM users" .
        " INNER JOIN userloclibforloan ON userloclibforloan.User_id=users.ID" .
        " WHERE ID = " . $userId ." and IsActive = 1";

        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if(!is_null($result)){
            
            foreach($result as $row )
            {
                array_push($locLibUser,$row["LocLib_id"]); //data lokasi yang petugas boleh cek
            }

            // Ambil data lokasi yang anggota boleh melakukan peminjaman
            $sql2 = "SELECT m.MemberNo as MemberNo, ml.LocationLoan_id as LocationLoan_id" .
            " FROM memberloanauthorizelocation ml INNER JOIN members m ON (ml.Member_id = m.ID) " .
            " WHERE m.MemberNo = '" .$memberNo. "'" .
            " and m.StatusAnggota_id = 3" . //'ACTIVE'
            " and m.EndDate >= '" . date('Y-m-d') . "'";

            $resultSQL2 = Yii::$app->db->createCommand($sql2)->queryAll(); //data lokasi yang anggota boleh melakukan peminjaman
            if(!is_null($resultSQL2)){  // jika ada lokasi anggota yang boleh meminjam 

               foreach($resultSQL2 as $row)
               {
                    if (in_array($row["LocationLoan_id"], $locLibUser) && $row["LocationLoan_id"] == Yii::$app->request->cookies->getValue('location_detail_peminjamanmandiri')['ID'] )
                    {
                        return true;
                    }
               }
               return false;
                
            }
            else
            {
                return false;
            }
                   
        }
        else
        {
            return false;
        }
    }

    public static function suspendAnggota($jumlah)
    {
        $jml = 0;
        $sql = "select SuspendMember from kelompok_pelanggaran where Jumlah = " . $jumlah;
        
        $result = Yii::$app->db->createCommand($sql)->queryScalar();
        if (!$result)
        {
            $jml = $result;
        }
        else
        {
            $jml = 0;
        }
        return $jml;
    }

    public static function jumlahPelanggaranAnggota($memberNo)
    {
        $jml = 0;
        $sql = "SELECT COUNT(*) AS JumlahPelanggaran FROM collectionloanitems cli" .
        " INNER JOIN pelanggaran p ON (cli.ID = p.CollectionLoanItem_id)" .
        " INNER JOIN collectionloans cl ON (cli.CollectionLoan_id = cl.id)" .
        " INNER JOIN members m ON (cl.Member_id = m.ID)" .
        " WHERE m.MemberNo ='" . $memberNo . "'"; 
        
        $result = Yii::$app->db->createCommand($sql)->queryScalar();
        if (!$result)
        {
            $jml = $result;
        }
        else
        {
            $jml = 0;
        }
        return $jml;
    }

    public static function getWarningLoanDueDay($collectionID, $memberNo)
    {
        //Peraturan Peminjaman (Tanggal)
        $sql = "SELECT DaySuspend,DendaPerTenor,WarningLoanDueDay FROM peraturan_peminjaman_tanggal" .
            " WHERE DATE(SYSDATE()) BETWEEN TanggalAwal AND TanggalAkhir";

        $result = Yii::$app->db->createCommand($sql)->queryAll();
      
        if (!empty($result))
        {
            $daySuspend = $result[0]["DaySuspend"];
            $dendaPerTenor = $result[0]["DendaPerTenor"];
            if ($daySuspend > 0 || $dendaPerTenor > 0)
            {
                return $result[0]["WarningLoanDueDay"];
            }
        }

        //Peraturan Peminjaman (Hari)
       if (!empty($result))
        {
            $sql = "SELECT DaySuspend,DendaPerTenor,WarningLoanDueDay FROM peraturan_peminjaman_hari" .
                " WHERE DayIndex = IF(DAYOFWEEK(SYSDATE()) = 1, 7, DAYOFWEEK(SYSDATE()) - 1)";

           $result = Yii::$app->db->createCommand($sql)->queryAll();

            if (!empty($result))
            {
                $daySuspend = $result[0]["DaySuspend"];
                $dendaPerTenor = $result[0]["DendaPerTenor"];
                if ($daySuspend > 0 || $dendaPerTenor > 0)
                {
                    return $result[0]["WarningLoanDueDay"];
                }
            }
        }

        //Jenis Anggota
        $sqlAnggota = "SELECT DaySuspend,DendaPerTenor,WarningLoanDueDay FROM members" .
            " INNER JOIN jenis_anggota ON members.JenisAnggota_id = jenis_anggota.ID" .
            " WHERE members.MemberNo = '" . $memberNo . "'";

        $resultAnggota = Yii::$app->db->createCommand($sqlAnggota)->queryAll();
        if (!empty($resultAnggota))
        {
                $daySuspend = $resultAnggota[0]["DaySuspend"];
                $dendaPerTenor = $resultAnggota[0]["DendaPerTenor"];
                if ($daySuspend > 0 || $dendaPerTenor > 0)
                {
                    return $resultAnggota[0]["WarningLoanDueDay"];
                }
        }

        //Jenis Bahan
        $sql = "SELECT DaySuspend,DendaPerTenor,WarningLoanDueDay FROM collections" .
            " INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID" .
            " INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID" .
            " WHERE collections.ID = " . $collectionID;

        $resultJenisBahan = Yii::$app->db->createCommand($sql)->queryAll();

        if (!empty($resultJenisBahan))
        {
                $daySuspend = $resultJenisBahan[0]["DaySuspend"];
                $dendaPerTenor = $resultJenisBahan[0]["DendaPerTenor"];
                if ($daySuspend > 0 || $resultJenisBahan > 0)
                {
                    return $resultJenisBahan[0]["WarningLoanDueDay"];
                }
        }

        return -1;
    }


    public static function lateDays($actualReturn, $dueDate)
    {
        $isSaturdayHoliday = Yii::$app->config->get('IsSaturdayHoliday'); 
        $isSundayHoliday = Yii::$app->config->get('IsSundayHoliday'); 


        $ts = date_diff(date_create($dueDate),date_create($actualReturn));
        (int)$selisih = $ts->days;
        (int)$days = $ts->format("%R%a ");

        $tanggalYangDitambah = ($actualReturn >= $dueDate) ? $dueDate : $actualReturn;
 
        /////////////////////////////////////////////////////////////////////////////////
        $sun = 0;
        $sat = 0;

        for($i=1;$i<=$selisih;$i++)
        {
            if (date('l', strtotime($tanggalYangDitambah. ' + '.$i.' days'))=="Saturday" && $isSaturdayHoliday=="True")
            {
                $sat++;
                // $selisih++;
            }


            if (date('l', strtotime($tanggalYangDitambah. ' + '.$i.' days'))=="Sunday" && $isSundayHoliday=="True")
            {
                $sun++;
                // $selisih++;
            }  
        }

        // $checkDateLibur =  \common\components\Helpers::addDayswithdate($dueDate,$sun + $sat);

        $countJumlahLibur = self::JumlahLiburMasaPinjam($dueDate, $actualReturn);



        if ($days >= 0 ) {
            $totalJumlahLibur = $days - ($sun + $sat) - $countJumlahLibur;
            return '+'.$totalJumlahLibur;
        }
        else
        {
            $totalJumlahLibur = $days + ($sun + $sat) + $countJumlahLibur;
            return $totalJumlahLibur;
        }
        // $date2 = strtotime($date. ' +'.$days.' days');
        // return  date("Y-m-d", $date2);

        /////////////////////////////////////////////////////////////////////////////////
 

    }


    public static function IsMemberCanLoanOnItem($memberNo, $nomorBarcode)
    {

        $sql = "SELECT Category_id FROM collections WHERE NomorBarcode = '" . $nomorBarcode . "'";
        $result = Yii::$app->db->createCommand($sql)->queryAll();


        $category = $result[0]["Category_id"];

        $sql2 = "SELECT m.MemberNo as MemberNo, ml.CategoryLoan_id as CategoryLoan_id" .
        " FROM memberloanauthorizecategory ml INNER JOIN members m ON (ml.Member_id = m.ID) " .
        " WHERE m.MemberNo = '" . $memberNo . "'" .
                " and m.StatusAnggota_id = 3" .//'ACTIVE'
                " and m.EndDate >= '" .date('Y-m-d'). "'";

                $result2 = Yii::$app->db->createCommand($sql2)->queryAll();

                if (!is_null($result2))
                {
                    foreach($result2 as $row)
                    {
                        if ($row["CategoryLoan_id"] == $category)
                        {
                            return true;
                        }
                    }
                    return false;
                }
                else
                {
                    return false;
                }
    }

    public static function getMaksJumlahPeminjaman($memberID, $collectionID)
    {
           $countBukuYgDipinjam = 0;
           // $countBukuYgBolehDipinjam = Yii::$app->config->get('MaksJumlahPeminjaman'); 
           $memberDetail = \common\models\Members::findOne($memberID);
           //$countBukuYgBolehDipinjam = $memberDetail->jenisAnggota->MaxPinjamKoleksi;
           
           // $countBukuYgBolehDipinjam = Self::cekBanyakPinjam($collectionID, $memberDetail->MemberNo);
           $countBukuYgBolehDipinjam = Self::cekValueHirarkiSirkulasi($collectionID, $memberDetail->MemberNo, 'MaxPinjamKoleksi');


           $sql = "SELECT COUNT(CollectionLoan_id) jumlah FROM collectionloanitems WHERE member_id = '" .trim($memberID). "' AND LoanStatus = 'Loan'";
           $result = Yii::$app->db->createCommand($sql)->queryAll();
           if (!is_null($result))
            {
                    foreach($result as $row)
                    {
                        $countBukuYgDipinjam = $row["jumlah"];
                    }

            }
              
           $maksJumlahPeminjaman = $countBukuYgBolehDipinjam - $countBukuYgDipinjam;

           return $maksJumlahPeminjaman;

    }

    
    

    /**
     * \
     * @param  [type] $memberNo     [description]
     * @param  [type] $loanDate     [description]
     * @param  [type] $nomorBarcode [description]
     * @return [type]               [description]
     */
    public static function getTanggalKembali($memberNo, $loanDate, $nomorBarcode)
    {
        $result=date('Y-m-d');
        $sun = 0;
        $sat = 0;

        $collections =  \common\models\Collections::findOne(['NomorBarcode' => $nomorBarcode]);
        $collectionID=$collections->ID;

        $maxLoanDays = self::cekLamaPinjam($collectionID, $memberNo);

        if ($maxLoanDays > 0)
        {

            
            $isSaturdayHoliday = Yii::$app->config->get('IsSaturdayHoliday'); 
            $isSundayHoliday = Yii::$app->config->get('IsSundayHoliday'); 

            $returnDate =  \common\components\Helpers::addDayswithdate($loanDate,$maxLoanDays);
            $checkDate = $returnDate;

            //(int)$sun = 0; 
        
            $d1=new DateTime($checkDate);
            $d2=new DateTime($loanDate);
            $diff=$d2->diff($d1);
            (int)$days = $diff->days;

          

            for($i=1;$i<=$days;$i++)
            {
                // echo date("Y-m-d", strtotime($loanDate. ' + '.$i.' days'));
                if (date('l', strtotime($loanDate. ' + '.$i.' days'))=="Saturday" && $isSaturdayHoliday=="True"){
                    $days++;
                    $sat++;
                }


                if (date('l', strtotime($loanDate. ' + '.$i.' days'))=="Sunday" && $isSundayHoliday=="True")
                {
                    $days++;
                    $sun++;
                }  
                          
                // $tgl =  date('d-m-Y', strtotime($loanDate. ' + '.$i.' days'));        

                // if($sat != 0){
                //     $hari = $i + $sat;
                //     if (date('l', strtotime($loanDate. ' + '.$hari.' days'))=="Sunday" && $isSundayHoliday=="True")
                //                     {
                //                         $sun++;
                //                     }
                // }else{
                //    if (date('l', strtotime($loanDate. ' + '.$i.' days'))=="Sunday" && $isSundayHoliday=="True")
                //     {
                //         $sun++;
                //     } 
                // }

            }
            // echo $tgl;
            // die;

            $checkDateLibur =  \common\components\Helpers::addDayswithdate($checkDate,$sun + $sat);

            $countJumlahLibur = self::JumlahLiburMasaPinjam($loanDate, $checkDateLibur);

            $totalJumlahLibur = $maxLoanDays + $countJumlahLibur + $sun + $sat;
           
           
            
            $result =  \common\components\Helpers::addDayswithdate($loanDate,$totalJumlahLibur);
            /*$checkDate = $returnDate;
            $result = \common\components\Helpers::addDayswithdate($checkDate,5);*/

            //Tambahkan hari jika waktu pengembalian bertepatan di hari sabtu dan minggu
            if (date('l', strtotime($result))=="Saturday" && $isSaturdayHoliday=="True" &&  $isSundayHoliday=="True")
            {

             $result = date(strtotime($result. ' + 2 days'));
             $result = date("Y-m-d", $result);
            }
            elseif (date('l', strtotime($result))=="Sunday" && $isSundayHoliday=="True" || date('l', strtotime($result))=="Saturday" && $isSaturdayHoliday=="True")
            {

             $result = date(strtotime($result. ' + 1 days'));
             $result = date("Y-m-d", $result);
            }
            //Tambahkan hari jika waktu pengembalian bertepatan di hari sabtu dan minggu


        }

        return $result;
    }














////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * [getTanggalKembaliPerpanjangan description]
     * @param  [type] $memberNo     [description]
     * @param  [type] $loanDate     [description]
     * @param  [type] $nomorBarcode [description]
     * @return [type]               [description]
     */
    public static function getTanggalKembaliPerpanjangan($memberNo, $loanDate, $nomorBarcode)
    {
        $result=date('Y-m-d');
        $sun = 0;
        $sat = 0;

        $collections =  \common\models\Collections::findOne(['NomorBarcode' => $nomorBarcode]);
        $collectionID=$collections->ID;

        $maxLoanDays = Self::cekLamaPinjamPerpanjangan($collectionID, $memberNo);

        if ($maxLoanDays > 0)
        {

            
            $isSaturdayHoliday = Yii::$app->config->get('IsSaturdayHoliday'); 
            $isSundayHoliday = Yii::$app->config->get('IsSundayHoliday'); 

            $returnDate =  \common\components\Helpers::addDayswithdate($loanDate,$maxLoanDays);
            $checkDate = $returnDate;

            //(int)$sun = 0; 
        
            $d1=new DateTime($checkDate);
            $d2=new DateTime($loanDate);
            $diff=$d2->diff($d1);
            (int)$days = $diff->days;

          

            for($i=1;$i<=$days;$i++)
            {
                if (date('l', strtotime($loanDate. ' + '.$i.' days'))=="Saturday" && $isSaturdayHoliday=="True"){
                    $sat++;
                    $days++;
                }


                if (date('l', strtotime($loanDate. ' + '.$i.' days'))=="Sunday" && $isSundayHoliday=="True")
                {
                    $sun++;
                    $days++;
                }  


                // if (date('l', strtotime($loanDate. ' + '.$i.' days'))=="Saturday" && $isSaturdayHoliday=="True"){
                //      $sat++;
                // }

                // if($sat != 0){
                //     $hari = $i + $sat;
                //     if (date('l', strtotime($loanDate. ' + '.$hari.' days'))=="Sunday" && $isSundayHoliday=="True")
                //                     {
                //                         $sun++;
                //                     }
                // }else{
                //    if (date('l', strtotime($loanDate. ' + '.$i.' days'))=="Sunday" && $isSundayHoliday=="True")
                //     {
                //         $sun++;
                //     } 
                // }

            }

            $checkDateLibur =  \common\components\Helpers::addDayswithdate($checkDate,$sun + $sat);

            $countJumlahLibur = Self::jumlahLiburMasaPinjamPerpanjangan($loanDate, $checkDateLibur);

            $totalJumlahLibur = $maxLoanDays + $countJumlahLibur + $sun + $sat;
           
           
            
            $result =  \common\components\Helpers::addDayswithdate($loanDate,$totalJumlahLibur);
            /*$checkDate = $returnDate;
            $result = \common\components\Helpers::addDayswithdate($checkDate,5);*/

            //Tambahkan hari jika waktu pengembalian bertepatan di hari sabtu dan minggu
            if (date('l', strtotime($result))=="Saturday" && $isSaturdayHoliday=="True" &&  $isSundayHoliday=="True")
            {

             $result = date(strtotime($result. ' + 2 days'));
             $result = date("Y-m-d", $result);
            }
            elseif (date('l', strtotime($result))=="Sunday" && $isSundayHoliday=="True" || date('l', strtotime($result))=="Saturday" && $isSaturdayHoliday=="True")
            {

             $result = date(strtotime($result. ' + 1 days'));
             $result = date("Y-m-d", $result);
            }
            //Tambahkan hari jika waktu pengembalian bertepatan di hari sabtu dan minggu


        }

        return $result;
    }





    /**
     * [cekLamaPinjamPerpanjangan description]
     * @param  [type] $collectionID [description]
     * @param  [type] $memberNo     [description]
     * @return [type]               [description]
     */
    public static function cekLamaPinjamPerpanjangan($collectionID, $memberNo)
    {
        $result = 0;
    
        //Peraturan Peminjaman (Hari)
        $sqlHari = "SELECT DayPerpanjang FROM peraturan_peminjaman_hari" .
                " WHERE DayIndex = IF(DAYOFWEEK(SYSDATE()) = 1, 7, DAYOFWEEK(SYSDATE()) - 1)";

        $resultHari = Yii::$app->db->createCommand($sqlHari)->queryScalar();
        if ($resultHari)
        {
            $result = $resultHari;
            return $result;
        }
    
        //Peraturan Peminjaman (Tanggal)
        $sqlTgl = "SELECT DayPerpanjang FROM peraturan_peminjaman_tanggal" .
            " WHERE DATE(SYSDATE()) BETWEEN TanggalAwal AND TanggalAkhir";

        $resultTgl = Yii::$app->db->createCommand($sqlTgl)->queryScalar();


        if($resultTgl)
        {
            $result = $resultTgl;
            return $result;
        }

        //Jenis Bahan
        
            $sqlJenisBahan = "SELECT DayPerpanjang FROM collections" .
                " INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID" .
                " INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID" .
                " WHERE collections.ID = " . $collectionID;

            $resultJenisBahan = Yii::$app->db->createCommand($sqlJenisBahan)->queryScalar();

        if($resultJenisBahan)
        {
            $result =  $resultJenisBahan;
            return $result;
            
        }

        //Jenis Anggota
        
        $sqlJenisAnggota = "SELECT DayPerpanjang FROM members" .
                " INNER JOIN jenis_anggota ON members.JenisAnggota_id = jenis_anggota.ID" .
                " WHERE members.MemberNo = '" . $memberNo ."'";

        $resultJenisAnggota = Yii::$app->db->createCommand($sqlJenisAnggota)->queryScalar();
       
        if ($resultJenisAnggota)
        {
            $result = $resultJenisAnggota;
            return $result;
        }
       
       return $result;

         
           
    }





    /**
     * [jumlahLiburMasaPinjam description]
     * @param  [type] $tglPinjam  [description]
     * @param  [type] $tglKembali [description]
     * @return [type]             [description]
     */
    public static function jumlahLiburMasaPinjamPerpanjangan($tglPinjam, $tglKembali)
    {
        $sql = "SELECT count(Dates) FROM holidays WHERE DATE_FORMAT(Dates,'%Y-%m-%d') BETWEEN '" . $tglPinjam . "' AND '" . $tglKembali . "'";

        $result = Yii::$app->db->createCommand($sql)->queryScalar();

        if ($result > 0)
        {
            $jml = $result;
        }
        else
        {
            $jml = 0;
        }
        return $jml;
        
    }



    /**
     * [getMaksJumlahPerpanjangan description]
     * @param  [type] $memberID [description]
     * @return [type]           [description]
     */
    public static function getMaksJumlahPerpanjangan($memberID,$colID)
    {
           $countBukuYgDipinjam = 0;
           // $countBukuYgBolehDipinjam = Yii::$app->config->get('MaksJumlahPeminjaman'); 
           $memberDetail = \common\models\Members::findOne($memberID);
           //$countBukuYgBolehDipinjam = $memberDetail->jenisAnggota->CountPerpanjang;
           
           /////////////////////////////////
           $countBukuYgBolehDipinjam = Self::cekValueHirarkiSirkulasi($colID, $memberDetail->MemberNo, 'CountPerpanjang');
           /////////////////////////////////

           // $sql = "SELECT COUNT(CollectionLoan_id) jumlah FROM collectionloanitems WHERE member_id = '" .trim($memberID). "' AND LoanStatus = 'Loan'";
           $sql = "SELECT COUNT(collectionloanextends.id) jumlah from collectionloanextends 
           JOIN collectionloanitems ON collectionloanitems.ID = collectionloanextends.CollectionLoanItem_id
            WHERE collectionloanitems.member_id = '" .trim($memberID). "' AND collectionloanitems.LoanStatus = 'Loan' AND collectionloanitems.Collection_id ='".$colID."'";
           $result = Yii::$app->db->createCommand($sql)->queryAll();
           if (!is_null($result))
            {
                    foreach($result as $row)
                    {
                        $countBukuYgDipinjam = $row["jumlah"];
                    }

            }
              
           $maksJumlahPeminjaman = $countBukuYgBolehDipinjam - $countBukuYgDipinjam;

           return $maksJumlahPeminjaman;

    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////














    public static function jumlahLiburMasaPinjam($tglPinjam, $tglKembali)
    {
        $sql = "SELECT count(Dates) FROM holidays WHERE DATE_FORMAT(Dates,'%Y-%m-%d') BETWEEN '" . $tglPinjam . "' AND '" . $tglKembali . "'";

        $result = Yii::$app->db->createCommand($sql)->queryScalar();

        if ($result > 0)
        {
            $jml = $result;
        }
        else
        {
            $jml = 0;
        }
        return $jml;
        
    }

    public static function cekLamaPinjam($collectionID, $memberNo)
    {
        $result = 0;
    
         //Peraturan Peminjaman (Hari)
        $sqlHari = "SELECT MaxLoanDays FROM peraturan_peminjaman_hari" .
                " WHERE DayIndex = IF(DAYOFWEEK(SYSDATE()) = 1, 7, DAYOFWEEK(SYSDATE()) - 1)";

        $resultHari = Yii::$app->db->createCommand($sqlHari)->queryScalar();
        if ($resultHari)
        {
            $result = $resultHari;
            return $result;
        }


        //Peraturan Peminjaman (Tanggal)
        $sqlTgl = "SELECT MaxLoanDays FROM peraturan_peminjaman_tanggal" .
            " WHERE DATE(SYSDATE()) BETWEEN TanggalAwal AND TanggalAkhir";

        $resultTgl = Yii::$app->db->createCommand($sqlTgl)->queryScalar();


        if($resultTgl)
        {
            $result = $resultTgl;
            return $result;
        }

        //Jenis Bahan
        $sqlJenisBahan = "SELECT MaxLoanDays FROM collections" .
        " INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID" .
        " INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID" .
        " WHERE collections.ID = " . $collectionID;

        $resultJenisBahan = Yii::$app->db->createCommand($sqlJenisBahan)->queryScalar();

        if($resultJenisBahan)
        {
            $result =  $resultJenisBahan;
            return $result;
            
        }
        
        //Jenis Anggota
        $sqlJenisAnggota = "SELECT MaxLoanDays FROM members" .
                " INNER JOIN jenis_anggota ON members.JenisAnggota_id = jenis_anggota.ID" .
                " WHERE members.MemberNo = '" . $memberNo ."'";

        $resultJenisAnggota = Yii::$app->db->createCommand($sqlJenisAnggota)->queryScalar();
       
        if ($resultJenisAnggota)
        {
            $result = $resultJenisAnggota;
            return $result;
        }

       
       return $result;

         
           
    }


    // public static function cekBanyakPinjam($collectionID, $memberNo)
    public static function cekValueHirarkiSirkulasi($collectionID, $memberNo, $fieldName)
    {
        $result = 0;
    
         //Peraturan Peminjaman (Hari)
        $sqlHari = "SELECT ".$fieldName." FROM peraturan_peminjaman_hari" .
                " WHERE DayIndex = IF(DAYOFWEEK(SYSDATE()) = 1, 7, DAYOFWEEK(SYSDATE()) - 1)";

        $resultHari = Yii::$app->db->createCommand($sqlHari)->queryScalar();
        if ($resultHari)
        {
            $result = $resultHari;
            return $result;
        }


        //Peraturan Peminjaman (Tanggal)
        $sqlTgl = "SELECT ".$fieldName." FROM peraturan_peminjaman_tanggal" .
            " WHERE DATE(SYSDATE()) BETWEEN TanggalAwal AND TanggalAkhir";

        $resultTgl = Yii::$app->db->createCommand($sqlTgl)->queryScalar();


        if($resultTgl)
        {
            $result = $resultTgl;
            return $result;
        }

        //Jenis Bahan
        $sqlJenisBahan = "SELECT ".$fieldName." FROM collections" .
        " INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID" .
        " INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID" .
        " WHERE collections.ID = " . $collectionID;

        $resultJenisBahan = Yii::$app->db->createCommand($sqlJenisBahan)->queryScalar();

        if($resultJenisBahan)
        {
            $result =  $resultJenisBahan;
            return $result;
            
        }
        
        //Jenis Anggota
        $sqlJenisAnggota = "SELECT ".$fieldName." FROM members" .
                " INNER JOIN jenis_anggota ON members.JenisAnggota_id = jenis_anggota.ID" .
                " WHERE members.MemberNo = '" . $memberNo ."'";

        $resultJenisAnggota = Yii::$app->db->createCommand($sqlJenisAnggota)->queryScalar();
       
        if ($resultJenisAnggota)
        {
            $result = $resultJenisAnggota;
            return $result;
        }

       
       return $result;

         
           
    }



    

    /**
     * Generate New ID Sirkulasi
     * @param  datenow $createDate Now date(Y-m-d)
     * @return string  $no           [New Number ID]
     */
    public static function generateNewID($createDate)
    {
        $location = $_SESSION['location'];
        $maxID = self::getMaxID($createDate);
        if (isset($maxID) || $maxID != false) {
                $tambah = ($maxID + 1);
                $rest = substr($tambah, -5);
                $tanggaldepan = date("ymd", strtotime($createDate));
                $potongtanggal = substr($tanggaldepan, -6);
                $batas = 10000;
                $jumlah = ($batas + $rest);
                $jumlahtotal = $potongtanggal . $jumlah;
                $no = substr_replace($jumlahtotal, '0', 6, 1);

            } else {
                $rest = 1;
                $tanggaldepan = date("ymd", strtotime($createDate));
                $potongtanggal = substr($tanggaldepan, -6);
                $batas = 10000;
                $jumlah = ($batas + $rest);
                $jumlahtotal = $potongtanggal . $jumlah;
                $no = substr_replace($jumlahtotal, '0', 6, 1);

            }
        return $location.$no;
    }

    protected static function getMaxID($createDate)
    {
        $location = $_SESSION['location'];
        $date = $location.date("ymd", strtotime($createDate));
        $sql = "SELECT MAX(id) FROM collectionloans where id LIKE '" . $date. "%'";
        $result = Yii::$app->db->createCommand($sql)->queryScalar();
        return $result; 
    }


    /**
    * [loadModelKoleksi description]
    * @param  [type] $nomorBarcode [description]
    * @return [type]               [description]
    */
   public static function loadModelCollectionLoanItems($nomorBarcode) {

        $language = Yii::$app->db->createCommand('select settingparameters.Value from settingparameters where settingparameters.Name = "language"')->queryAll();
        $sql = "SELECT cli.ID as CollectionLoanItem_Id, cli.CollectionLoan_id, cli.Collection_id," .
                " c.NomorBarcode, c.RFID, cli.Member_id, mem.Fullname, mem.MemberNo," .
                " cat.Title, cat.Author, cat.Publisher," .
                " cli.LoanDate, cli.DueDate, cli.ActualReturn, cli.LateDays" .
                " FROM collectionloanitems cli" .
                " LEFT JOIN collections c ON cli.Collection_id = c.ID" .
                " LEFT JOIN catalogs cat ON c.Catalog_id = cat.id" .
                " LEFT JOIN members mem ON cli.Member_id = mem.ID" .
                " WHERE c.NomorBarcode ='" .$nomorBarcode. "'".
                " AND cli.LoanStatus = 'Loan' ";

        $result = Yii::$app->db->createCommand($sql)->queryAll();
        if (!empty($result))
        {
              return $result;
        }else{
            if ($language[0]['Value'] == 'en') {
                throw new \yii\web\HttpException(404, yii::t('app','The collection is not in the database'));
            }else{
                throw new \yii\web\HttpException(404, yii::t('app','Koleksi tersebut tidak terdapat dalam database'));
            }
        }
           
            

    }






    public static function getStokOpnameDetail($noAnggota)
    {
        $sql = "SELECT * FROM members WHERE MemberNo = '" .$noAnggota. "' and EndDate >= '" . date('Y-m-d') . "'";
        $result = Yii::$app->db->createCommand($sql)->queryScalar();
        if (!$result)
        { 
                // Tidak ada pelanggaran yang di suspend.
            $result = false;
        }else{
            $result = true;
        }
        return $result;

    }


	
	

	public static function searchArrayByKeyAndValue($array, $key, $value)
	{	
		$results = array();
		Self::search_r($array, $key, $value, $results);
		return $results;
	}

	public static function search_r($array, $key, $value, &$results)
	{
		if (!is_array($array)) {
			return;
		}

		if (isset($array[$key]) && $array[$key] == $value) {
			$results[] = $array;
		}

		foreach ($array as $subarray) {
			Self::search_r($subarray, $key, $value, $results);
		}
	}


    public static function loadModelKoleksiPengiriman($BIBID) {

            $model = Yii::$app->db->createCommand('
                SELECT COUNT(collections.Catalog_id) AS jumlahEksemplar, catalogs.BIBID, catalogs.Title, catalogs.PublishYear, catalogs.CallNumber, collections.NoInduk, collections.NomorBarcode
                FROM collections 
                INNER JOIN catalogs ON catalogs.ID = collections.Catalog_id
                WHERE BIBID = "'.$BIBID.'"')->queryOne();

            if(count($model['BIBID']) > 0){
                return $model;
            }else{
                throw new \yii\web\HttpException(404, 'Koleksi tersebut tidak terdapat dalam database.');
            }
            
    }

    public static function loadModelPengirimanKoleksi($NomorBarcode){
        $model = Yii::$app->db->createCommand('
                SELECT collections.ID AS CollectionID, COUNT(collections.ID) AS jumlahEksemplar, catalogs.Title, catalogs.PublishYear, catalogs.CallNumber, collections.NoInduk, collections.NomorBarcode
                FROM collections 
                INNER JOIN catalogs ON catalogs.ID = collections.Catalog_id
                WHERE collections.NomorBarcode = "'.$NomorBarcode.'"')->queryOne();

        if(count($model['NomorBarcode']) > 0){
            return $model;
        }else{
            throw new \yii\web\HttpException(404, 'Koleksi tersebut tidak terdapat dalam database.');
        }
    }
}
