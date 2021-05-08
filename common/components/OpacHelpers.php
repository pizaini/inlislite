<?php

/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package helpers
 * @version 1.0.0
 * @author Rico <rico.ulul@gmail.com>
 */

namespace common\components;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collections;
use common\models\Logsdownload;
use common\models\LogsdownloadArticle;
use common\models\Collectionloanitems;
use common\components\SirkulasiHelpers;
use yii\db\Expression;
use common\models\Opaclogs;
use common\models\OpaclogsKeyword;
use common\models\Refferenceitems;
use \DateTime;

class OpacHelpers 
{
    
    public static function getIP(){
        $ip = getenv('HTTP_CLIENT_IP')?:
                getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                getenv('HTTP_FORWARDED_FOR')?:
                getenv('HTTP_FORWARDED')?:
                getHostByName(getHostName())?:
                getenv('REMOTE_ADDR');
        return $ip;
    }
    public static function jumlahBooking($noAnggota){
        $dateNow = new DateTime("now");
        $time=$dateNow->format("Y-m-d H:i:s");
        if (is_null($noAnggota)) {
            return 0;
        } else
        {
            $sql = "select count(1)  " .
                   " FROM collections col " .
                   " LEFT JOIN catalogs cat on cat.id= col.Catalog_id" .
                   " where col.BookingMemberID = '" . $noAnggota . "' and BookingExpiredDate  > '".$time."' ";

            $result = Yii::$app->db->createCommand($sql)->queryScalar();

            return $result;

        }
    }

    public static function cekBooking($noAnggota,$colID){
        $err=[];
        $locationLoan=[];
        $colCat=[];

        $jmlBookMaks = Yii::$app->config->get('JumlahBookingMaksimal');
        $bookExp = Yii::$app->config->get('BookingExpired');
        $sqlCol="select Location_Library_id from collections where ID='".$colID."'";
        $Location_Library_id = Yii::$app->db->createCommand($sqlCol)->queryScalar();
        $sql_col="select * from collections where id = '".$colID."'";
        $collections = Yii::$app->db->createCommand($sql_col)->queryAll();
        $sql_collectionCategory="SELECT CategoryLoan_id FROM memberloanauthorizecategory ml left join members m on ml.Member_id = m.id where m.MemberNo ='".$noAnggota."'";
        
        $collectionsCategory=Yii::$app->db->createCommand($sql_collectionCategory)->queryAll();
         foreach ($collectionsCategory as $key => $tmp) {
            array_push($colCat, $tmp['CategoryLoan_id']);
        }

        //cek jumlahBooking
        $jmlBooking=self::jumlahBooking($noAnggota);
        //status member
        $sql = "select StatusAnggota_id,EndDate " .
            " FROM members  " .
            " where MemberNo = '" . $noAnggota."'";
        $statusMember = Yii::$app->db->createCommand($sql)->queryAll();
        $dateNow=new \DateTime("now");
        $dateBook=new \DateTime($statusMember[0]['EndDate']);


        //cek pinjam
        $sqlLoanLoc="SELECT LocationLoan_id FROM memberloanauthorizelocation
                    left join members on members.ID = memberloanauthorizelocation.Member_id
                    where members.MemberNo = '".$noAnggota."'";
        $loanLoc=Yii::$app->db->createCommand($sqlLoanLoc)->queryAll();

        foreach ($loanLoc as $key => $loclib) {
            array_push($locationLoan, $loclib['LocationLoan_id']);
        }
        //generate error msg
        if($jmlBooking>=$jmlBookMaks)array_push($err, 'Jumlah Booking Lebih Dari '.$jmlBookMaks.' item');
        if($statusMember[0]['StatusAnggota_id']!=3)array_push($err, 'Anggota Belum Aktif');
        if(!in_array($Location_Library_id,$locationLoan))array_push($err, 'Anda Tidak Mempunyai Akses Peminjaman Di Lokasi Ini');
        if($dateNow>$dateBook)array_push($err, 'Status Keanggotaan Anda Sudah Kadalaursa');
        if(!in_array($collections[0]['Category_id'], $colCat))array_push($err, 'Anda Tidak Mempunyai Akses Peminjaman Koleksi Ini');

        return $err;
    }

    public static function isHoliday($date){
        $isSaturdayHoliday = Yii::$app->config->get('IsSaturdayHoliday'); 
        $isSundayHoliday = Yii::$app->config->get('IsSundayHoliday');

        if (date('l', strtotime($date))==="Saturday" && $isSaturdayHoliday==="True") return true;
        if (date('l', strtotime($date))==="Sunday" && $isSundayHoliday==="True") return true;

        $sql = "SELECT count(Dates) FROM holidays WHERE Dates = '" . $date . "'";
        $result = Yii::$app->db->createCommand($sql)->queryScalar();

        if ($result)
        {
            return true;
        }
        
        return false;
    }

    public static function getIndonesianDays($tgl="now"){   
        $date = new DateTime($tgl);
        $week = $date->format("w");
        $days = Array ("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");

        return $days[$week];
    }

    public static function SetBookingTime($date){

        $hariini=self::getIndonesianDays();
        $sqlJambuka = "select jam_buka from master_jam_buka where hari =  '" . $hariini . "'";
        $sqlJamtutup = "select jam_tutup from master_jam_buka where hari = '" . $hariini . "'";
        $jambuka = Yii::$app->db->createCommand($sqlJambuka)->queryScalar();
        $jamtutup = Yii::$app->db->createCommand($sqlJamtutup)->queryScalar();
        $tambahJam= explode(":",$date);
        $jamTutup1= explode(":",$jamtutup);
        $jamBuka1= explode(":",$jambuka);
        $dateBook = new \DateTime('now');
        $datetutup = new \DateTime('now');
        $dateBuka = new \DateTime('now');
        $dateNow= new \DateTime('now');
        $dateBuka->setTime($jamBuka1[0], $jamBuka1[1],$jamBuka1[2]);
        $datetutup->setTime($jamTutup1[0], $jamTutup1[1],$jamTutup1[2]);
        $dateBook->modify("+".$tambahJam[0]." hours +".$tambahJam[1]." minutes +".$tambahJam[2]." seconds");

        $intervalJambukaTutup=$datetutup->getTimestamp() - $dateBuka->getTimestamp();
        $intervalBooking= $dateBook ->getTimestamp() - $dateNow->getTimestamp();

        $interval='';
        //saat booking belum jam buka
        if ( $dateNow < $dateBuka) {
            $interval='';
            $next=true;
            $hariSelanjutnya=$dateBuka;
            $hariSelanjutnya=self::nextOperationalDay();
            if ($intervalJambukaTutup > $intervalBooking) {
                $hariSelanjutnya->modify("+".$tambahJam[0]." hours +".$tambahJam[1]." minutes +".$tambahJam[2]." seconds");
            } else {
                while ($next == true) {
                    //akhir booking udah jam tutup
                    if ($intervalJambukaTutup < $intervalBooking) {
                        $hariSelanjutnya=self::nextOperationalDay($hariSelanjutnya->modify("+ 1 days ")->format("d-m-Y H:i:s"));
                        $intervalBooking -= $intervalJambukaTutup;
                        if ($intervalJambukaTutup > $intervalBooking) {
                            $hariSelanjutnya->modify("+ ".$intervalBooking." seconds ")->format("d-m-Y H:i:s");
                            $next=false;
                        }
                    } else {
                        //akhir booking aman
                        $hariSelanjutnya=self::nextOperationalDay($hariSelanjutnya->modify("+ 1 days ")->format("d-m-Y H:i:s"));
                        $hariSelanjutnya->modify("+ ".$intervalBooking." seconds ")->format("d-m-Y H:i:s");
                        $next=false;
                    }
                }
            }
            return $hariSelanjutnya;

        } elseif ($dateNow > $datetutup) {
            //echo "udah tutup kaka";die;
            //saat booking "udah jam tutup";
            $hariSelanjutnya=self::nextOperationalDay();
            $interval='';
            $next=true;
            $hariSelanjutnya=$dateBuka;
            while ($next == true) {
                //akhir booking udah jam tutup
                if ($intervalJambukaTutup < $intervalBooking) {
                    $hariSelanjutnya=self::nextOperationalDay($hariSelanjutnya->modify("+ 1 days ")->format("d-m-Y H:i:s"));
                    if ($intervalJambukaTutup > $intervalBooking) {
                        $hariSelanjutnya->modify("+ ".$intervalBooking." seconds ")->format("d-m-Y H:i:s");
                        $next=false;
                    }
                    $intervalBooking -= $intervalJambukaTutup;
                } else {
                    //akhir booking aman
                    $hariSelanjutnya=self::nextOperationalDay($hariSelanjutnya->modify("+ 1 days ")->format("d-m-Y H:i:s"));
                    $hariSelanjutnya->modify("+ ".$intervalBooking." seconds ")->format("d-m-Y H:i:s");
                    $next=false;
                }                
            }

            return $hariSelanjutnya;
        } else
        {//saat bookingjam operasional
          if ($dateBook > $datetutup) {
            //akhir booking lebih dari jam tutup (next day)
                $interval = $datetutup->diff($dateBook);
                $hariSelanjutnya=self::nextOperationalDay();
                $nextBookingTime=$hariSelanjutnya;
                $nextBookingTime=self::nextOperationalDay($nextBookingTime->modify("+ 1 days ")->format("d-m-Y H:i:s"));
                $nextBookingTime->modify("+".$interval->format('%H')." hours +".$interval->format('%i')." minutes +".$interval->format('%s')." seconds");

                return $nextBookingTime;                
            } else {
                //echo "waktu akhir booking aman";
                $isHoliday=self::isHoliday($dateBook->format("Y-m-d"));
                if ($isHoliday) {

                    $dateBook=self::nextOperationalDay();
                    $dateBook->modify("+".$tambahJam[0]." hours +".$tambahJam[1]." minutes +".$tambahJam[2]." seconds");
                }
                return $dateBook;
            }   
        }
    }

    public static function nextOperationalDay($date = 'now'){
        $i=1;
        $date=new \DateTime($date);
        $nextDay=  $date;
        $isHoliday = true;
        $isSaturdayHoliday = Yii::$app->config->get('IsSaturdayHoliday'); 
        $isSundayHoliday = Yii::$app->config->get('IsSundayHoliday');
        $hariini=self::getIndonesianDays();
        $sqlJambuka = "select jam_buka from master_jam_buka where hari =  '" . $hariini . "'";
        $jambuka = Yii::$app->db->createCommand($sqlJambuka)->queryScalar();
        $jamBuka1= explode(":",$jambuka);
        //buat cek ada hari libur ga, kalau ada hari libur +1 hari
        while ($isHoliday) {
            if ($i != 1) {
                $nextDay->modify("+1 days");
            }
            $isHoliday=self::isHoliday($nextDay->format("Y-m-d"));
            $i++;
        }
        //buat cek jam buka pada hari operational selanjutnya
        $hariNextOperationalDay=self::getIndonesianDays($nextDay->format("Y-m-d"));
        $sqlJambuka = "select jam_buka from master_jam_buka where hari =  '" . $hariNextOperationalDay . "'";
        $jambuka = Yii::$app->db->createCommand($sqlJambuka)->queryScalar();
        $jamBuka1= explode(":",$jambuka);
        $nextDay->setTime($jamBuka1[0], $jamBuka1[1],$jamBuka1[2]);
        return $nextDay;
    }

    public static function logsDownload($id,$userid,$isLKD='0'){
        $model = new Logsdownload;
        $model->User_id = $userid;
        $model->ip = self::getIP();
        $model->catalogfilesID = $id;
        $model->isLKD = $isLKD;
        $model->waktu = new Expression('NOW()');
        $model->save(false);
    }

    public static function logsDownloadArticle($id,$userid,$isLKD='0'){
        $model = new LogsdownloadArticle;
        $model->User_id = $userid;
        $model->ip = self::getIP();
        $model->articlefilesID = $id;
        // $model->isLKD = $isLKD;
        $model->waktu = new Expression('NOW()');
        $model->save(false);
    }

    public static function opacLogs($logs){

        switch ($logs['jenis_pencarian']) {
            case 'pencarianSederhana':
                    $model = new Opaclogs;
                    $model->User_id = $logs['User_id'];
                    $model->ip = $logs['ip'];
                    $model->jenis_pencarian = 'pencarianSederhana';
                    $model->keyword =$logs['keyword'];
                    $model->jenis_bahan = $logs['jenis_bahan'];
                    $model->waktu = new Expression('NOW()');
                    $model->url = $logs['url'];
                    $model->isLKD = $logs['isLKD'];
                    $model->save();
                    $opaclogsID = $model->getPrimaryKey();

                    $modellogs = new OpaclogsKeyword;
                    $modellogs->OpaclogsId = $opaclogsID;
                    $modellogs->Field = $logs['Field'];
                    $modellogs->Keyword =  $logs['keyword'];
                    $modellogs->save();
                break;
            case 'pencarianLanjut':

                    $model = new Opaclogs;
                    $model->User_id = $logs['User_id'];
                    $model->ip = $logs['ip'];
                    $model->jenis_pencarian = "pencarianLanjut";
                    $model->keyword = $logs['keyword'];
                    $model->Target_Pembaca =$logs['Target_Pembaca'];
                    $model->Bahasa = $logs['Bahasa'];
                    $model->Bentuk_Karya = $logs['Bentuk_Karya'];
                    $model->jenis_bahan = $logs['jenis_bahan'];
                    $model->waktu = new Expression('NOW()');
                    $model->url = $logs['url'];
                    $model->isLKD = $logs['isLKD'];
                    $model->save();
                    $opaclogsID = $model->getPrimaryKey();


                    for ($i = 0; $i < sizeof($katakunci2); $i++) {
                    $modellogs = new OpaclogsKeyword;
                    $modellogs->OpaclogsId = $opaclogsID;
                    $modellogs->Field = $logs['tag'][$i];
                    $modellogs->Keyword =  $logs['katakunci2'][$i];
                    $modellogs->save();
                    }  
                break;
            case 'browse':
                    $model = new Opaclogs;
                    $model->User_id = $logs['User_id'];
                    $model->ip = $logs['ip'];
                    $model->jenis_pencarian = 'browse';
                    $model->keyword = $logs['keyword'];
                    $model->waktu = new Expression('NOW()');
                    $model->isLKD = $logs['isLKD'];
                    $model->url = $logs['url'];
                    $model->save();


                    $opaclogsID = $model->getPrimaryKey();
                    $modellogs = new OpaclogsKeyword;
                    $modellogs->OpaclogsId = $opaclogsID;
                    $modellogs->Field = $logs['findByID'];
                    $modellogs->Keyword =  $logs['query'];
                    $modellogs->save();

                    $modellogs = new OpaclogsKeyword;
                    $modellogs->OpaclogsId = $opaclogsID;
                    $modellogs->Field = $logs['tagID'];
                    $modellogs->Keyword =  $logs['query2'];
                    $modellogs->save();
                break;
        }
    }

    public static function clearTemp(){
        $lastClear = Yii::$app->config->get('lastClearTemporary');
        return $lastClear;
    }

    public static function sortWorksheets($array){
        
        usort($array, function($a, $b) {
            return $a['NoUrut'] - $b['NoUrut'];
        });

        return $array;
    }
	
	public static function getDigitalCollectionArticleDir($id){
		$KontenDigital = Yii::$app->db->createCommand( "SELECT * FROM serial_articlefiles` WHERE serial_articlefiles.Articles_id = ".$id."")->queryall();
		foreach ($KontenDigital as $key => $value) {
			$datas = Yii::$app->db->createCommand("
					SELECT `serial_articlefiles`.`ID`, `serial_articlefiles`.`FileURL`, `serial_articlefiles`.`FileFlash`, 
					`serial_articlefiles`.`isPublish`,  `worksheets`.`id`, `worksheets`.`name`,
					(SELECT  SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2))  AS FormatFile,       
					(SELECT  SUBSTRING(FileFlash,(LENGTH(FileFlash)-LOCATE('.',REVERSE(FileURL)))+2))  AS FormatFileFlash 
					FROM `serial_articlefiles` 
					LEFT JOIN `serial_articles` ON `serial_articles`.`id` = `serial_articlefiles`.`Articles_id` 
					LEFT JOIN `catalogs` ON `catalogs`.`ID` = `serial_articles`.`Catalog_id` 
					LEFT JOIN `worksheets` ON `worksheets`.`ID` = `catalogs`.`Worksheet_id` 
					WHERE `serial_articlefiles`.`ID` = ".$value['ID'].";
				")->queryAll();
                
                if($value['FileFlash']!='' && $value['FileFlash'] != NULL){
                    $wName=$datas[0]['name'];
                    $file=$datas[0]['FileFlash'];
                    $format=$datas[0]['FormatFileFlash'];
                    $addPath=$wName.'/';
                    $realpath =  'dokumen_isi/'.$addPath.'/';
                    $KontenDigital[$key]['path']=$realpath;
                } else
                {
                    $wName=$datas[0]['name'];
                    $file=$datas[0]['FileURL'];
                    $format=$datas[0]['FormatFile'];
                    $addPath=$wName.'/';
                    $realpath = 'dokumen_isi/'.$addPath;;
                    $KontenDigital[$key]['path']=$realpath;
                }
            }
        return $KontenDigital;
	}

    public static function getDigitalCollectionDir($id){

        $sqlKontendigital = "CALL showKontenDigital(" . $id . "); ";
        $KontenDigital = Yii::$app->db->createCommand( $sqlKontendigital)->queryall();

            foreach ($KontenDigital as $key => $value) {
                $datas = Yii::$app->db->createCommand("SELECT `catalogfiles`.`id`, `catalogfiles`.`FileURL`, `catalogfiles`.`FileFlash`, `catalogfiles`.`isPublish`, `worksheets`.`id`, `worksheets`.`name`,(SELECT  SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2))  as FormatFile,       (SELECT  SUBSTRING(FileFlash,(LENGTH(FileFlash)-LOCATE('.',REVERSE(FileURL)))+2))  as FormatFileFlash FROM `catalogfiles` LEFT JOIN `catalogs` ON `catalogs`.`ID` = `catalogfiles`.`Catalog_id` LEFT JOIN `worksheets` ON `worksheets`.`ID` = `catalogs`.`Worksheet_id` WHERE `catalogfiles`.`id`=".$value['ID'].";")->queryAll();
                
                if($value['FileFlash']!='' && $value['FileFlash'] != NULL){
                    $wName=$datas[0]['name'];
                    $file=$datas[0]['FileFlash'];
                    $format=$datas[0]['FormatFileFlash'];
                    $addPath=$wName.'/'.str_replace(".rar","",str_replace(".zip","",$datas[0]['FileURL']));
                    $realpath =  'dokumen_isi/'.$addPath.'/'.$file;;
                    $KontenDigital[$key]['path']=$realpath;
                } else
                {
                    $wName=$datas[0]['name'];
                    $file=$datas[0]['FileURL'];
                    $format=$datas[0]['FormatFile'];
                    $addPath=$wName.'/'.$datas[0]['FileURL'];
                    $realpath = 'dokumen_isi/'.$addPath;;
                    $KontenDigital[$key]['path']=$realpath;
                }
            }
        return $KontenDigital;
    }

    public static function facedOpac($type,$request,$jenis ='opac'){
        $Jenistemp = ($jenis=='article') ? 'tempCariArticle' : 'tempCariOpac';

        switch ($type) {
            case 'Author':
                $maxfaced= (($jenis==='opac') ? Yii::$app->config->get('FacedAuthorMax') : (($jenis==='LKD') ? Yii::$app->config->get('FacedAuthorMaxLKD') : Yii::$app->config->get('FacedAuthorMaxArticle')));

                $sql='SELECT Author,COUNT(1) jml FROM '.$Jenistemp.' WHERE';

                foreach($request as $key => $value) {
                    switch ($key){
                        case 'fAuthor' :
                            $sql .= $value ? " Author LIKE CONCAT('%',CONCAT('".$request['fAuthor']."','%')) AND " : " 1=1 AND ";
                            break;
                        case 'fPublisher' :
                            $sql .= $value ? " publisher ='".$request['fPublisher']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishLoc' :
                            $sql .= $value ? " PublishLocation ='".$request['fPublishLoc']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishYear' :
                            $sql .= $value ? " PublishYear ='".$request['fPublishYear']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fSubject' :
                            $sql .= $value ? " SUBJECT ='".$request['fSubject']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fBahasa' :
                            $sql .= $value ? " bahasa ='".$request['fBahasa']."' " : " 1=1 ";
                            break;
                    }
                }

                $sql .= " GROUP BY Author ORDER BY jml DESC LIMIT 0,".$maxfaced."; ";

                break;
            case 'bahasa':
                $maxfaced= (($jenis==='opac') ? Yii::$app->config->get('FacedBahasaMax') : (($jenis==='LKD') ? Yii::$app->config->get('FacedBahasaMaxLKD') : Yii::$app->config->get('FacedBahasaMaxArticle')));

                $sql='SELECT bahasa,COUNT(1) jml FROM '.$Jenistemp.' Where ';

                foreach($request as $key => $value) {
                    switch ($key){
                        case 'fAuthor' :
                            $sql .= $value ? " Author LIKE CONCAT('%',CONCAT('".$request['fAuthor']."','%')) AND " : " 1=1 AND ";
                            break;
                        case 'fPublisher' :
                            $sql .= $value ? " publisher ='".$request['fPublisher']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishLoc' :
                            $sql .= $value ? " PublishLocation ='".$request['fPublishLoc']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishYear' :
                            $sql .= $value ? " PublishYear ='".$request['fPublishYear']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fSubject' :
                            $sql .= $value ? " SUBJECT ='".$request['fSubject']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fBahasa' :
                            $sql .= $value ? " bahasa ='".$request['fBahasa']."' " : " 1=1 ";
                            break;
                    }
                }

                $sql .= " GROUP BY bahasa ORDER BY jml DESC LIMIT 0,".$maxfaced."; ";


                break;
            case 'Publisher':
                $maxfaced= (($jenis==='opac') ? Yii::$app->config->get('FacedPublisherMax') : (($jenis==='LKD') ? Yii::$app->config->get('FacedPublisherMaxLKD') : Yii::$app->config->get('FacedPublisherMaxArticle')));

                $sql='SELECT Publisher,COUNT(1) jml FROM '.$Jenistemp.' Where ';

                foreach($request as $key => $value) {
                    switch ($key){
                        case 'fAuthor' :
                            $sql .= $value ? " Author LIKE CONCAT('%',CONCAT('".$request['fAuthor']."','%')) AND " : " 1=1 AND ";
                            break;
                        case 'fPublisher' :
                            $sql .= $value ? " publisher ='".$request['fPublisher']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishLoc' :
                            $sql .= $value ? " PublishLocation ='".$request['fPublishLoc']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishYear' :
                            $sql .= $value ? " PublishYear ='".$request['fPublishYear']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fSubject' :
                            $sql .= $value ? " SUBJECT ='".$request['fSubject']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fBahasa' :
                            $sql .= $value ? " bahasa ='".$request['fBahasa']."' " : " 1=1 ";
                            break;
                    }
                }

                $sql .= " GROUP BY Publisher ORDER BY jml DESC LIMIT 0,".$maxfaced."; ";
                break;
            case 'PublishLocation':
                $maxfaced= (($jenis==='opac') ? Yii::$app->config->get('FacedPublishLocationMax') : (($jenis==='LKD') ? Yii::$app->config->get('FacedPublishLocationMaxLKD') : Yii::$app->config->get('FacedPublishLocationMaxArticle')));


                $sql='SELECT PublishLocation,COUNT(1) jml FROM '.$Jenistemp.' Where ';

                foreach($request as $key => $value) {
                    switch ($key){
                        case 'fAuthor' :
                            $sql .= $value ? " Author LIKE CONCAT('%',CONCAT('".$request['fAuthor']."','%')) AND " : " 1=1 AND ";
                            break;
                        case 'fPublisher' :
                            $sql .= $value ? " publisher ='".$request['fPublisher']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishLoc' :
                            $sql .= $value ? " PublishLocation ='".$request['fPublishLoc']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishYear' :
                            $sql .= $value ? " PublishYear ='".$request['fPublishYear']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fSubject' :
                            $sql .= $value ? " SUBJECT ='".$request['fSubject']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fBahasa' :
                            $sql .= $value ? " bahasa ='".$request['fBahasa']."' " : " 1=1 ";
                            break;
                    }
                }

                $sql .= " GROUP BY PublishLocation ORDER BY jml DESC LIMIT 0,".$maxfaced."; ";
                break;
            case 'PublishYear':
                $maxfaced= (($jenis==='opac') ? Yii::$app->config->get('FacedPublishYearMax') : (($jenis==='LKD') ? Yii::$app->config->get('FacedPublishYearMaxLKD') : Yii::$app->config->get('FacedPublishYearMaxArticle')));
                $sql='SELECT PublishYear,COUNT(1) jml FROM '.$Jenistemp.' Where ';

                foreach($request as $key => $value) {
                    switch ($key){
                        case 'fAuthor' :
                            $sql .= $value ? " Author LIKE CONCAT('%',CONCAT('".$request['fAuthor']."','%')) AND " : " 1=1 AND ";
                            break;
                        case 'fPublisher' :
                            $sql .= $value ? " publisher ='".$request['fPublisher']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishLoc' :
                            $sql .= $value ? " PublishLocation ='".$request['fPublishLoc']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishYear' :
                            $sql .= $value ? " PublishYear ='".$request['fPublishYear']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fSubject' :
                            $sql .= $value ? " SUBJECT ='".$request['fSubject']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fBahasa' :
                            $sql .= $value ? " bahasa ='".$request['fBahasa']."' " : " 1=1 ";
                            break;
                    }
                }

                $sql .= " GROUP BY PublishYear ORDER BY jml DESC LIMIT 0,".$maxfaced."; ";
                break;
            case 'SUBJECT':
                $maxfaced= (($jenis==='opac') ? Yii::$app->config->get('FacedSubjectMax') : (($jenis==='LKD') ? Yii::$app->config->get('FacedSubjectMaxLKD') : Yii::$app->config->get('FacedSubjectMaxArticle')));

                $sql='SELECT COALESCE(SUBJECT,\'-\') SUBJECT,COUNT(1) jml FROM '.$Jenistemp.' Where ';

                foreach($request as $key => $value) {
                    switch ($key){
                        case 'fAuthor' :
                            $sql .= $value ? " Author LIKE CONCAT('%',CONCAT('".$request['fAuthor']."','%')) AND " : " 1=1 AND ";
                            break;
                        case 'fPublisher' :
                            $sql .= $value ? " publisher ='".$request['fPublisher']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishLoc' :
                            $sql .= $value ? " PublishLocation ='".$request['fPublishLoc']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fPublishYear' :
                            $sql .= $value ? " PublishYear ='".$request['fPublishYear']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fSubject' :
                            $sql .= $value ? " SUBJECT ='".$request['fSubject']."'  AND " : " 1=1 AND ";
                            break;
                        case 'fBahasa' :
                            $sql .= $value ? " bahasa ='".$request['fBahasa']."' " : " 1=1 ";
                            break;
                    }
                }

                $sql .= " GROUP BY SUBJECT ORDER BY jml DESC LIMIT 0,".$maxfaced."; ";
                break;

        }
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        return $result;
    }

    public static function facedGenerator($data,$type){


        foreach ($data as $key => $value) {
            if (!$value[$type] || $value[$type]=='-' || $value[$type] == '--') {
                unset($data[$key]);
            }
        }
        $data = array_values($data);
        $temp = $data;
        $temp2 = array();

        //clean delimeter ;
        //save item after delimeteri into new variable

        foreach ($data as $key => $value) {
            $exp = explode(";", $value[$type]);
            if (sizeof($exp) != 1) {
                foreach ($exp as $key2 => $value2) {
                    if ($key2!=0) {
                        array_push($temp2,array($type=>$value2,"jml"=>1));
                        $temp[$key][$type]=$exp[0];
                    }
                }            
            }          
        }

        foreach ($temp as $key => $value) {
            foreach ($temp2 as $key2 => $value2) {
                if ($value[$type]===$value2[$type]) {
                    $temp[$key]['jml']+=1;
                }
            }            
        }
        //sort faced dari jumlah faced terbanyak
        //thx to anonymous function
        // usort($temp, function($a, $b) {
        //     return $b['jml'] - $a['jml'];
        // });

        return $temp;
    }

    public static function getPublisher($id){

         $publisher = Yii::$app->db->createCommand(" select Publikasi from catalogs where  ID =".$id)->queryall();

         return $publisher[0]['Publikasi'];
    }
    
    public static function maskedStatus($status){

        switch (strtolower($status)) {
            case 'rusak':
            case 'hilang':
            case 'dalam perbaikan':

                $status = 'Tidak Tersedia';

                break;
        }
        return $status;
    }

    public static function columnExist($tname,$cname){
        preg_match("/dbname=([^;]*)/", \Yii::$app->db->dsn,$dbname);
        // $dbname = substr(\Yii::$app->db->dsn, strpos(\Yii::$app->db->dsn, "dbname=") + 7); 
        $cekColumn= Yii::$app->db->createCommand("
            SELECT * 
            FROM information_schema.COLUMNS 
            WHERE 
                TABLE_SCHEMA = '".$dbname[1]."' 
            AND TABLE_NAME = '".$tname."' 
            AND COLUMN_NAME = '".$cname."'
        ")->execute();

        return $cekColumn;

    }

    public static function columnDataType($tname,$cname,$dtype){
        preg_match("/dbname=([^;]*)/", \Yii::$app->db->dsn,$dbname);
        // $dbname = substr(\Yii::$app->db->dsn, strpos(\Yii::$app->db->dsn, "dbname=") + 7); 
        $cekColumn= Yii::$app->db->createCommand("
            SELECT * 
            FROM information_schema.COLUMNS 
            WHERE 
                TABLE_SCHEMA = '".$dbname[1]."' 
            AND TABLE_NAME = '".$tname."' 
            AND COLUMN_NAME = '".$cname."'
            AND DATA_TYPE = '".$dtype."'
        ")->execute();

        return $cekColumn;

    }

    public static function ConstraintExist($tname,$consname,$constype){
        preg_match("/dbname=([^;]*)/", \Yii::$app->db->dsn,$dbname);
        $cekColumn= Yii::$app->db->createCommand("
            SELECT * 
            FROM information_schema.TABLE_CONSTRAINTS  
            WHERE 
                TABLE_SCHEMA = '".$dbname[1]."' 
            AND TABLE_NAME = '".$tname."' 
            AND CONSTRAINT_NAME = '".$consname."'
            AND CONSTRAINT_TYPE = '".$constype."'
        ")->execute();
        //self::print__r($cekColumn);
        return $cekColumn;

    }

    public static function tableExist($tname){
        preg_match("/dbname=([^;]*)/", \Yii::$app->db->dsn,$dbname);
        // $dbname = substr(\Yii::$app->db->dsn, strpos(\Yii::$app->db->dsn, "dbname=") + 7);
        $cekTable= Yii::$app->db->createCommand("
            SELECT * 
            FROM information_schema.COLUMNS 
            WHERE 
                TABLE_SCHEMA = '".$dbname[1]."' 
            AND TABLE_NAME = '".$tname."' 
        ")->execute();

        return $cekTable;

    }
	
	public static function getTaginfo($catalogID,$tag,$ruas){
		
    	$con = Yii::$app->db;
    	$query = 	"SELECT cr.`CatalogId` ,cr.`Tag`, cs.`SubRuas`, cs.value FROM catalog_ruas cr 
    					LEFT JOIN catalog_subruas cs ON cr.`ID`= cs.`RuasID`
    					WHERE 
    						cr.`Tag` IN (".$tag.") AND 
    						cs.`SubRuas` = '".$ruas."' AND
    						cr.`CatalogId` =".$catalogID;
    						

    	$model= $con->createCommand($query)->queryall();   
    	return $model;
    	}
    	public static function jenisBahanRDA($model){
    		
    	$data;	
    	foreach ($model as $key => $value) {
            if (sizeof($model) == 1) {
                $data.= "[".$value['value']."]";
            } elseif (sizeof($model)==$key+1) {
                $data.= $value['value']."]";
            } elseif ($key==0) {
                 $data.= "[".$value['value'].",";
            } 
            else {
                $data.= $value['value'].",";
            }
            //echo "keynya ".$key;
        }
    	return $data;
    }

    public static function sqlDetailOpac($param="ALL",$catalogID){
        $sql;
        switch ($param) {
            case 'JUDUL':
                $sql = "Select (SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (245) GROUP BY CR.CatalogId) AS JUDUL FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'JUDUL_SERAGAM':
                $sql = "Select (SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (240) GROUP BY CR.CatalogId) AS JUDUL_SERAGAM FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'PENGARANG':
                $sql="Select (SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR ' ') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid,
                              CASE
                                WHEN CS.SUBRUAS = 'e'
                                THEN CONCAT ( '(' , CS.Value , ') |' )
                                ELSE CS.Value
                              END AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (100,110,111,700,710,711,800,810,811)
                            AND CS.Subruas    IN ('a', 'd' ,'e')
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z
                ) AS PENGARANG FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'EDISI':
                $sql="Select (


                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (250)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS EDISI FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'PERNYATAAN_SERI':
                $sql="Select (


                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (490)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS PERNYATAAN_SERI FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'PENERBITAN':
                $sql="Select                 (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR ' ') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (260,264)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS PENERBITAN FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'DESKRIPSI_FISIK':
                $sql="Select                 (
                SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, 
                          CASE
                            WHEN CS.SUBRUAS = '3'
                            THEN CONCAT ( '[' , CS.Value , ']' )
                            ELSE CS.Value
                          END AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (300)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS DESKRIPSI_FISIK FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'KONTEN':
                $sql="Select                (
                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (336)
                        and CS.SubRuas <> 2 
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z
                ) AS KONTEN FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'MEDIA':
                $sql="Select (
                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (337)
                        and CS.SubRuas <> 2 
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS MEDIA FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'PENYIMPANAN_MEDIA':
                $sql="Select (
                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (338)
                        and CS.SubRuas <> 2 
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS PENYIMPANAN_MEDIA FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'INFORMASI_TEKNIS':
                $sql="Select (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (538)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS INFORMASI_TEKNIS FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'ISBN':
                $sql="Select (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (020)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS ISBN FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'ISSN':
                $sql="Select (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (022)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS ISSN FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'ISMN':
                $sql="Select (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (024)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS ISMN FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'SUBJEK':
                $sql="Select (
                SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid,
                            CASE
                            WHEN CS.SubRuas <> 'a'
                            THEN
                             CONCAT( (SELECT DISTINCT(fd.Delimiter)
                              FROM fields f
                              LEFT JOIN fielddatas fd
                              ON fd.Field_id = f.ID
                              WHERE f.Tag    =CR.TAG
                              AND fd.Code    = CS.SubRuas
                              AND Format_ID  =1
                              ), CS.Value)
                            ELSE CS.Value
                          END AS VAL1

                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (600,610,611,650,651)
                        GROUP BY 
                          CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS SUBJEK FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'ABSTRAK':
                $sql="Select (


                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (520)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z



                ) AS ABSTRAK FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'CATATAN':
                $sql="Select (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (500,501,502,504,505,533,550)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z

                ) AS CATATAN FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'BAHASA':
                $sql="Select (SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,36,3) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 5) AS BAHASA FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'BENTUK_KARYA':
                $sql="Select (SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,34,1) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 17) AS BENTUK_KARYA FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'TARGET_PEMBACA':
                $sql="Select (SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,23,1) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 2) AS TARGET_PEMBACA FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
            case 'LOKASI_AKSES_ONLINE':
                $sql="Select (

                  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                  FROM
                    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                    FROM
                      (SELECT *
                      FROM (
                        (SELECT CS.sequence,
                          CS.Ruasid, CS.Value AS VAL1
                        FROM catalog_subruas CS
                        LEFT JOIN catalog_ruas CR
                        ON CS.RuasID       = CR.ID
                        WHERE CR.CatalogId = ".$catalogID."
                        AND CR.Tag        IN (856)
                        GROUP BY CS.Ruasid,
                          CS.SUBRUAS,
                          CS.Value,
                          CS.Sequence
                        )) X
                      ORDER BY ruasid,
                        sequence
                      ) Y
                    GROUP BY Ruasid
                    ) Z


                ) AS LOKASI_AKSES_ONLINE FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1";
                break;
                
            default:
                $sql ="

                    SELECT  C.Worksheet_id,
                    (SELECT C.CoverURL FROM catalogs C WHERE C.ID = ".$catalogID.") AS CoverURL , 
                    (SELECT W.name FROM worksheets W WHERE W.ID = C.worksheet_id) AS JENIS_BAHAN,
                    (SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (245) GROUP BY CR.CatalogId) AS JUDUL,
                    (SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (240) GROUP BY CR.CatalogId) AS JUDUL_SERAGAM,
                    (

                         SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                          FROM
                            (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR ' ') AS RTRIM1
                            FROM
                              (SELECT *
                              FROM (
                                (SELECT CS.sequence,
                                  CS.Ruasid,
                                  CASE
                                    WHEN CS.SUBRUAS = 'e'
                                    THEN CONCAT ( '(' , CS.Value , ') |' )
                                    ELSE CS.Value
                                  END AS VAL1
                                FROM catalog_subruas CS
                                LEFT JOIN catalog_ruas CR
                                ON CS.RuasID       = CR.ID
                                WHERE CR.CatalogId = ".$catalogID."
                                AND CR.Tag        IN (100,110,111,700,710,711,800,810,811)
                                AND CS.Subruas    IN ('a', 'd' ,'e')
                                GROUP BY CS.Ruasid,
                                  CS.SUBRUAS,
                                  CS.Value,
                                  CS.Sequence
                                )) X
                              ORDER BY ruasid,
                                sequence
                              ) Y
                            GROUP BY Ruasid
                            ) Z
                    ) AS PENGARANG,
                    (


                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (250)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z


                    ) AS EDISI,
                    (


                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (490)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z


                    ) AS PERNYATAAN_SERI,
                    (

                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (260,264)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z

                    ) AS PENERBITAN,
                    (
                     SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, 
                              CASE
                                WHEN CS.SUBRUAS = '3'
                                THEN CONCAT ( '[' , CS.Value , ']' )
                                ELSE CS.Value
                              END AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (300)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z

                    ) AS DESKRIPSI_FISIK,
                    (
                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (336)
                            and CS.SubRuas <> 2 
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z
                    ) AS KONTEN,
                    (
                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (337)
                            and CS.SubRuas <> 2 
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z

                    ) AS MEDIA,
                    (
                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (338)
                            and CS.SubRuas <> 2 
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z


                    ) AS PENYIMPANAN_MEDIA,
                    (

                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (538)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z


                    ) AS INFORMASI_TEKNIS,
                    (

                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (020)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z

                    ) AS ISBN,
                    (

                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (022)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z

                    ) AS ISSN,
                    (

                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (024)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z

                    ) AS ISMN,
                    (
                     SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid,
                                CASE
                                WHEN CS.SubRuas <> 'a'
                                THEN
                                 CONCAT( (SELECT DISTINCT(fd.Delimiter)
                                  FROM fields f
                                  LEFT JOIN fielddatas fd
                                  ON fd.Field_id = f.ID
                                  WHERE f.Tag    =CR.TAG
                                  AND fd.Code    = CS.SubRuas
                                  AND Format_ID  =1
                                  ), CS.Value)
                                ELSE CS.Value
                              END AS VAL1

                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (600,610,611,650,651)
                            GROUP BY 
                              CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z


                    ) AS SUBJEK,
                    (


                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (520)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z



                    ) AS ABSTRAK,
                    (

                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (500,501,502,504,505,533,550)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z

                    ) AS CATATAN,

                     (SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,36,3) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 5) AS BAHASA,
                     (SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,34,1) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 17) AS BENTUK_KARYA,
                     (SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,23,1) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 2) AS TARGET_PEMBACA,
                     (

                      SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
                      FROM
                        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
                        FROM
                          (SELECT *
                          FROM (
                            (SELECT CS.sequence,
                              CS.Ruasid, CS.Value AS VAL1
                            FROM catalog_subruas CS
                            LEFT JOIN catalog_ruas CR
                            ON CS.RuasID       = CR.ID
                            WHERE CR.CatalogId = ".$catalogID."
                            AND CR.Tag        IN (856)
                            GROUP BY CS.Ruasid,
                              CS.SUBRUAS,
                              CS.Value,
                              CS.Sequence
                            )) X
                          ORDER BY ruasid,
                            sequence
                          ) Y
                        GROUP BY Ruasid
                        ) Z


                    ) AS LOKASI_AKSES_ONLINE
                    FROM catalogs C WHERE C.ID = ".$catalogID." AND isopac = 1
                ";
                break;
        }
        $detailOpac = Yii::$app->db->createCommand($sql)->queryScalar();

        return $detailOpac;
    }

    public static function highlight($text, $words) {
        preg_match_all('~\w+~', $words, $m);
        if(!$m)
            return $text;
        //$re = '~\\b(' . implode('|', $m[0]) . ')\\b~';
        $re = '~\\b(' . implode('|', $m[0]) . ')\\b~i';
        //OpacHelpers::print__r($re); die;
        return preg_replace($re, '<b style="background-color:yellow">$0</b>', $text);
    }


    public static function print__r($data){
        echo "<pre>";
        print_r($data);
        die;

    }

    public static function AdvanceSearchQuery($req,$keyword){
        $sql=$keyword;
        foreach ($req as $key => $value){

            $queryAdd='';
           switch ($value){
               case 'fAuthor'       :   $queryAdd = '';
                                        break;
               case 'fPublisher'    :   $queryAdd = '';
                                        break;
               case 'fPublishLoc'   :   $queryAdd = '';
                                        break;
               case 'fPublishYear'  :   $queryAdd = '';
                                        break;
               case 'fSubject'      :   $queryAdd = '';
                                        break;
               case 'fBahasa'       :   $queryAdd = '';
                                        break;
               default              :   $queryAdd = '';
                                        break;

           }
        }

    }

    public static function facedtype($type ='opac'){

        switch ($type){

            case 'opac'     :   return Yii::$app->config->get('opacFacedType') == 'db' ? 'db' : 'php';
                                break;
            case 'lkd'      :   return Yii::$app->config->get('articleFacedType') == 'db' ? 'db' : 'php';
                                break;
            case 'article'  :   return Yii::$app->config->get('articleFacedType') == 'db' ? 'db' : 'php';
                                break;
            default :           return 'php';
                                break;
        }
    }


    public static function articleSearch($req,$type='count',$limit=0){
        $sql= $type == 'count' ? "select count(1) from tempCariArticle where " : "select * from tempCariArticle where ";


        foreach ($req as $key => $value){
            switch ($key){
                case 'fAuthor'       :   $sql.= $value=='' ? ' 1=1 AND ' : "author  = '".$value."' And ";
                    break;
                case 'fPublisher'    :   $sql.= $value=='' ? ' 1=1 AND ' : "publisher = '".$value."' And ";
                    break;
                case 'fPublishLoc'   :   $sql.= $value=='' ? ' 1=1 AND ' : "PublishLocation = '".$value."' And ";
                    break;
                case 'fPublishYear'  :   $sql.= $value=='' ?  ' 1=1 AND ' : "PublishYear = '".$value."' And ";
                    break;
                case 'fSubject'      :   $sql.= $value=='' ? ' 1=1 AND ' : "SUBJECT = '".$value."' And ";
                    break;
                case 'fBahasa'       :   $sql.= $value=='' ? ' 1=1 AND ' : "bahasa = '".$value."' And ";
                    break;
                default              :   $sql .= ' 1=1 AND ';
                    break;

            }

        }
        $sql.= ' 1=1 ';
        return $type== count ? Yii::$app->db->createCommand($sql)->queryScalar() : Yii::$app->db->createCommand($sql)->queryAll();


    }




}
