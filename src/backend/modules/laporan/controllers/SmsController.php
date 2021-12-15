<?php

namespace backend\modules\laporan\controllers;


use Yii;
use yii\helpers\Url;
use yii\web\Response;
//Widget
//use kartik\widgets\Select2;
use kartik\mpdf\Pdf;
use kartik\date\DatePicker;

//Helpers
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


class SmsController extends \yii\web\Controller
{
    /**
     * [actionIndex description]
     * @return [type] [description]
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLaporanPeriodik()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('laporan-periodik',[
            'model' => $model,
            ]);
    }

    public function actionSmsTerkirim()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('sms-terkirim',[
            'model' => $model,
            ]);
    }
    

public function actionLoadFilterKriteria($kriteria)
{
        if ($kriteria == 'peminjaman')
        {
            $contentOptions = DatePicker::widget([
                'name' => $kriteria.'[]', 
                'type' => DatePicker::TYPE_RANGE,
                'value' => date('d-m-Y'),
                'name2' => 'to'.$kriteria.'[]', 
                'value2' => date('d-m-Y'),
                'separator' => 's/d',
                'options' => ['placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Date')],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                    'autoclose'=>true,
                    'class' => 'datepicker',
                ]
                ]);
        }

        else if ($kriteria == 'no_anggota')
        {  
            $sql = 'SELECT * FROM members';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 

            $options = ArrayHelper::map($data,'ID',
                function($model) {
                    return $model['MemberNo'].' - '.$model['Fullname'];
                });
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );

        }

        else if ($kriteria == 'jatuh_tempo')
        {
            $contentOptions = DatePicker::widget([
                'name' => $kriteria.'[]', 
                'type' => DatePicker::TYPE_RANGE,
                'value' => date('d-m-Y'),
                'name2' => 'to'.$kriteria.'[]', 
                'value2' => date('d-m-Y'),
                'separator' => 's/d',
                'options' => ['placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Date')],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                    'autoclose'=>true,
                    'class' => 'datepicker',
                ]
            ]);
        }

        else
        {
            $contentOptions = null;
        }
        return $contentOptions;
        
    }

     
    public function actionLoadSelecterLaporanPeriodik($i)
    {
        return $this->renderPartial('select-laporan-periodik',['i'=>$i]);
    }

    public function actionShowPdf($tampilkan)
    {
      
        // session_start();
        $_SESSION['Array_POST_Filter'] = $_POST;

        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";

        // print_r(count(array_filter($_POST['kriterias'])));
        // print_r(isset($_POST['kota_terbit']));
        if ($tampilkan == 'laporan-periodik-frekuensi')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-periodik-frekuensi').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );  
        }elseif ($tampilkan == 'laporan-periodik-data')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-periodik-data').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );  
        }
        if ($tampilkan == 'sms-terkirim-frekuensi')
        {            
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-sms-terkirim-frekuensi').'">';
            echo "<iframe>";
        }
        if ($tampilkan == 'sms-terkirim-data')
        {            
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-sms-terkirim-data').'">';
            echo "<iframe>";
        }
        
    }

public function actionRenderLaporanPeriodikData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;
            if (isset($_POST['ip'])) {
            foreach ($_POST['ip'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.IP = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['jenis_pencarian'])) {
            foreach ($_POST['jenis_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.jenis_pencarian = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['ruas_pencarian'])) {
            foreach ($_POST['ruas_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['keyword'])) {
            foreach ($_POST['keyword'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) = '".addslashes($value)."' ";
                    }
                }
            }

                
           $sql = "SELECT sentitems.SendingDateTime AS tgl_kirim,
                    sentitems.DestinationNumber AS no_hp,
                    CASE
                     WHEN sentitems.Status != 'SendingError' THEN 'pesan terkirim'
                     ELSE 'pesan gagal terkirim'
                    END AS status_kirim,
                    members.MemberNo AS no_anggota,
                    members.Fullname AS nama,
                    collections.NoInduk AS no_induk,
                    '' AS data_bib,
                    collectionloanitems.LoanDate AS tgl_pinjam,
                    collectionloanitems.DueDate AS tgl_jatuh_tempo,
                    collectionloanitems.ActualReturn AS tgl_kembali
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    INNER JOIN collectionloans ON collectionloans.Member_id = members.ID
                    INNER JOIN collectionloanitems ON collectionloanitems.CollectionLoan_id = collectionloans.ID 
                    WHERE DATE(sentitems.SendingDateTime) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY sentitems.SendingDateTime';
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
         
        //     $Berdasarkan .= ' (' .implode($_POST[implode($_POST['kriterias'])]). ')';
        // }

        // $Berdasarkan = '';
        // foreach ($_POST['kriterias'] as $key => $value) {
        //     $Berdasarkan .= $this->getRealNameKriteria($value).' ';
        // }

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 233; width: 100%;" >'];
            $set = 60;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'content' => $this->renderPartial('pdf-view-laporan-periodik-data', $content),
            'options' => [
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=> $header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }

public function actionExportExcelData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

// var_dump($periode_format);
// die;

            if (isset($_POST['peminjaman'])) {
            foreach ($_POST['peminjaman'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(collectionloanitems.LoanDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['topeminjaman'])) {
                foreach ($_POST['topeminjaman'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }

            if (isset($_POST['jatuh_tempo'])) {
            foreach ($_POST['jatuh_tempo'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(collectionloanitems.DueDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['tojatuh_tempo'])) {
                foreach ($_POST['tojatuh_tempo'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }   
             
            if (isset($_POST['no_anggota'])) {
            foreach ($_POST['no_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND members.ID = '".addslashes($value)."' ";
                    }
                }
            }  

            
            $sql = "SELECT sentitems.SendingDateTime AS tgl_kirim,
                    sentitems.DestinationNumber AS no_hp,
                    CASE
                     WHEN sentitems.Status != 'SendingError' THEN 'pesan terkirim'
                     ELSE 'pesan gagal terkirim'
                    END AS status_kirim,
                    members.MemberNo AS no_anggota,
                    members.Fullname AS nama,
                    collections.NoInduk AS no_induk,
                    '' AS data_bib,
                    collectionloanitems.LoanDate AS tgl_pinjam,
                    collectionloanitems.DueDate AS tgl_jatuh_tempo,
                    collectionloanitems.ActualReturn AS tgl_kembali
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    INNER JOIN collectionloans ON collectionloans.Member_id = members.ID
                    INNER JOIN collectionloanitems ON collectionloanitems.CollectionLoan_id = collectionloans.ID 
                    WHERE DATE(sentitems.SendingDateTime) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY sentitems.SendingDateTime';
        $data = Yii::$app->db->createCommand($sql)->queryAll();

    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $filename = 'Laporan_Periodik_Frekuensi.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="10">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="10">'.yii::t('app','Pengiriman SMS Otomatis').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="10">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Pengiriman').'</th>
                <th>'.yii::t('app','Nomor Handphone').'</th>
                <th>'.yii::t('app','Status Pengiririman').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Nomor Induk').'</th>
                <th>'.yii::t('app','Tanggal Peminjaman').'</th>
                <th>'.yii::t('app','Tanggal Jatuh Tempo').'</th>
                <th>'.yii::t('app','Tanggal Dikembalikan').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tgl_kirim'].'</td>
                    <td>'.$data['no_hp'].'</td>
                    <td>'.$data['status_kirim'].'</td>
                    <td>'.$data['no_anggota'].'</td>
                    <td>'.$data['nama'].'</td>
                    <td>'.$data['no_induk'].'</td>
                    <td>'.$data['tgl_pinjam'].'</td>
                    <td>'.$data['tgl_jatuh_tempo'].'</td>
                    <td>'.$data['tgl_kembali'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
    echo '</table>';

}

public function actionExportExcelOdtData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

// var_dump($periode_format);
// die;

            if (isset($_POST['peminjaman'])) {
            foreach ($_POST['peminjaman'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(collectionloanitems.LoanDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['topeminjaman'])) {
                foreach ($_POST['topeminjaman'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }

            if (isset($_POST['jatuh_tempo'])) {
            foreach ($_POST['jatuh_tempo'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(collectionloanitems.DueDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['tojatuh_tempo'])) {
                foreach ($_POST['tojatuh_tempo'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }   
             
            if (isset($_POST['no_anggota'])) {
            foreach ($_POST['no_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND members.ID = '".addslashes($value)."' ";
                    }
                }
            }  

            
            $sql = "SELECT sentitems.SendingDateTime AS tgl_kirim,
                    sentitems.DestinationNumber AS no_hp,
                    CASE
                     WHEN sentitems.Status != 'SendingError' THEN 'pesan terkirim'
                     ELSE 'pesan gagal terkirim'
                    END AS status_kirim,
                    members.MemberNo AS no_anggota,
                    members.Fullname AS nama,
                    collections.NoInduk AS no_induk,
                    '' AS data_bib,
                    collectionloanitems.LoanDate AS tgl_pinjam,
                    collectionloanitems.DueDate AS tgl_jatuh_tempo,
                    collectionloanitems.ActualReturn AS tgl_kembali
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    INNER JOIN collectionloans ON collectionloans.Member_id = members.ID
                    INNER JOIN collectionloanitems ON collectionloanitems.CollectionLoan_id = collectionloans.ID 
                    WHERE DATE(sentitems.SendingDateTime) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY sentitems.SendingDateTime';

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'tgl_kirim'=> $model['tgl_kirim'], 'no_hp'=>$model['no_hp'], 'status_kirim'=>$model['status_kirim'], 'no_anggota'=>$model['no_anggota'], 'nama'=>$model['nama'], 
                        'no_induk'=>$model['no_induk'], 'tgl_pinjam'=>$model['tgl_pinjam'], 'tgl_jatuh_tempo'=>$model['tgl_jatuh_tempo'], 'tgl_kembali'=>$model['tgl_kembali'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'pengiriman_SMSotomatis'=> yii::t('app','Pengiriman SMS Otomatis'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_pengiriman'=> yii::t('app','Tanggal Pengiriman'),
        'nomor_handphone'=> yii::t('app','Nomor Handphone'),
        'status_pengiririman'=> yii::t('app','Status Pengiririman'),
        'nomor_anggota'=> yii::t('app','Nomor Anggota'),
        'nama'=> yii::t('app','Nama'),
        'nomor_induk'=> yii::t('app','Nomor Induk'),
        'tanggal_peminjaman'=> yii::t('app','Tanggal Peminjaman'),
        'tanggal_jatuhtempo'=> yii::t('app','Tanggal Jatuh Tempo'),
        'tanggal_dikembalikan'=> yii::t('app','Tanggal Dikembalikan'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/sms/laporan-sms-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-sms-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

// var_dump($periode_format);
// die;

            if (isset($_POST['peminjaman'])) {
            foreach ($_POST['peminjaman'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(collectionloanitems.LoanDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['topeminjaman'])) {
                foreach ($_POST['topeminjaman'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }

            if (isset($_POST['jatuh_tempo'])) {
            foreach ($_POST['jatuh_tempo'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(collectionloanitems.DueDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['tojatuh_tempo'])) {
                foreach ($_POST['tojatuh_tempo'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }   
             
            if (isset($_POST['no_anggota'])) {
            foreach ($_POST['no_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND members.ID = '".addslashes($value)."' ";
                    }
                }
            }  

            
            $sql = "SELECT sentitems.SendingDateTime AS tgl_kirim,
                    sentitems.DestinationNumber AS no_hp,
                    CASE
                     WHEN sentitems.Status != 'SendingError' THEN 'pesan terkirim'
                     ELSE 'pesan gagal terkirim'
                    END AS status_kirim,
                    members.MemberNo AS no_anggota,
                    members.Fullname AS nama,
                    collections.NoInduk AS no_induk,
                    '' AS data_bib,
                    collectionloanitems.LoanDate AS tgl_pinjam,
                    collectionloanitems.DueDate AS tgl_jatuh_tempo,
                    collectionloanitems.ActualReturn AS tgl_kembali
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    INNER JOIN collectionloans ON collectionloans.Member_id = members.ID
                    INNER JOIN collectionloanitems ON collectionloanitems.CollectionLoan_id = collectionloans.ID 
                    WHERE DATE(sentitems.SendingDateTime) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY sentitems.SendingDateTime';
        $data = Yii::$app->db->createCommand($sql)->queryAll();

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Data.'.$type;
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="10">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="10">'.yii::t('app','Pengiriman SMS Otomatis').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="10">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Pengiriman').'</th>
                <th>'.yii::t('app','Nomor Handphone').'</th>
                <th>'.yii::t('app','Status Pengiririman').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Nomor Induk').'</th>
                <th>'.yii::t('app','Tanggal Peminjaman').'</th>
                <th>'.yii::t('app','Tanggal Jatuh Tempo').'</th>
                <th>'.yii::t('app','Tanggal Dikembalikan').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tgl_kirim'].'</td>
                    <td>'.$data['no_hp'].'</td>
                    <td>'.$data['status_kirim'].'</td>
                    <td>'.$data['no_anggota'].'</td>
                    <td>'.$data['nama'].'</td>
                    <td>'.$data['no_induk'].'</td>
                    <td>'.$data['tgl_pinjam'].'</td>
                    <td>'.$data['tgl_jatuh_tempo'].'</td>
                    <td>'.$data['tgl_kembali'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
    echo '</table>';

}

public function actionExportPdfData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;
            if (isset($_POST['ip'])) {
            foreach ($_POST['ip'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.IP = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['jenis_pencarian'])) {
            foreach ($_POST['jenis_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.jenis_pencarian = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['ruas_pencarian'])) {
            foreach ($_POST['ruas_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['keyword'])) {
            foreach ($_POST['keyword'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) = '".addslashes($value)."' ";
                    }
                }
            }

                
           $sql = "SELECT sentitems.SendingDateTime AS tgl_kirim,
                    sentitems.DestinationNumber AS no_hp,
                    CASE
                     WHEN sentitems.Status != 'SendingError' THEN 'pesan terkirim'
                     ELSE 'pesan gagal terkirim'
                    END AS status_kirim,
                    members.MemberNo AS no_anggota,
                    members.Fullname AS nama,
                    collections.NoInduk AS no_induk,
                    '' AS data_bib,
                    collectionloanitems.LoanDate AS tgl_pinjam,
                    collectionloanitems.DueDate AS tgl_jatuh_tempo,
                    collectionloanitems.ActualReturn AS tgl_kembali
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    INNER JOIN collectionloans ON collectionloans.Member_id = members.ID
                    INNER JOIN collectionloanitems ON collectionloanitems.CollectionLoan_id = collectionloans.ID 
                    WHERE DATE(sentitems.SendingDateTime) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY sentitems.SendingDateTime';
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
         
        //     $Berdasarkan .= ' (' .implode($_POST[implode($_POST['kriterias'])]). ')';
        // }

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] = $Berdasarkan;
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            $set = 60;
        } else {
            $set = 10;
        }
// print_r(Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png"));
// die;

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-view-laporan-periodik-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');
        
    }

public function actionRenderSmsTerkirimData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }
  
            if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }         
        
            $sql = "SELECT outbox.SendingDateTime as Periode,members.*, outbox.TextDecoded AS teks
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] = $Berdasarkan;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
            $set = 55;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'content' => $this->renderPartial('pdf-view-sms-terkirim-data', $content),
            'options' => [
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=> $header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }

public function actionExportExcelSmsTerkirimData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

            if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }         
        
            $sql = "SELECT outbox.SendingDateTime as Periode,members.*, outbox.TextDecoded AS teks
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $filename = 'Laporan_SMS_Terkirim_Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="4">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','SMS Terkirim').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan').' '.yii::t('app','Nama Anggota').'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Pengiriman').'</th>
                <th>'.yii::t('app','Nama Anggota').'</th>
                <th>'.yii::t('app','Nomor Handphone').'</th>
                <th>'.yii::t('app','Isi Pesan').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Fullname'].'</td>
                    <td>'.$data['NoHp'].'</td>
                    <td>'.$data['teks'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportExcelOdtSmsTerkirimData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

            if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }         
        
            $sql = "SELECT outbox.SendingDateTime as tgl_kirim,members.*, outbox.TextDecoded AS teks
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'tgl_kirim'=> $model['tgl_kirim'], 'Fullname'=>$model['Fullname'], 'NoHp'=>$model['NoHp'], 'teks'=>$model['teks'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'SMS_terkirim'=> yii::t('app','SMS Terkirim'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_pengiriman'=> yii::t('app','Tanggal Pengiriman'),
        'nama_anggota'=> yii::t('app','Nama Anggota'),
        'nomor_handphone'=> yii::t('app','Nomor Handphone'),
        'isi_pesan'=> yii::t('app','Isi Pesan'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/sms/laporan-sms-terkirim-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-sms-terkirim-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordSmsTerkirimData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }
            
            if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }         
        
            $sql = "SELECT outbox.SendingDateTime as tgl_kirim,members.*, outbox.TextDecoded AS teks
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="4">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','SMS Terkirim').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan').' '.yii::t('app','Nama Anggota').'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Pengiriman').'</th>
                <th>'.yii::t('app','Nama Anggota').'</th>
                <th>'.yii::t('app','Nomor Handphone').'</th>
                <th>'.yii::t('app','Isi Pesan').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tgl_kirim'].'</td>
                    <td>'.$data['Fullname'].'</td>
                    <td>'.$data['NoHp'].'</td>
                    <td>'.$data['teks'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportPdfSmsTerkirimData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }
  
            if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }         
        
            $sql = "SELECT outbox.SendingDateTime as Periode,members.*, outbox.TextDecoded AS teks
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] = $Berdasarkan;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
            $set = 55;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'options' => [
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-view-sms-terkirim-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }

// /////////////////////////////////batas view_data dgn view_vrekuensi//////////////////////////////////// //     
public function actionRenderLaporanPeriodikFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;


            if (isset($_POST['peminjaman'])) {
            foreach ($_POST['peminjaman'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['topeminjaman'])) {
                foreach ($_POST['topeminjaman'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }

            if (isset($_POST['jatuh_tempo'])) {
            foreach ($_POST['jatuh_tempo'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['tojatuh_tempo'])) {
                foreach ($_POST['tojatuh_tempo'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }   
             
            if (isset($_POST['no_anggota'])) {
            foreach ($_POST['no_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND members.ID = '".addslashes($value)."' ";
                    }
                }
            }  

            
            $sql = "SELECT ".$periode_format.", 
                    COUNT(sentitems.DestinationNumber) AS jum_anggota, 
                    COUNT(collections.ID) AS jumlah_koleksi, 
                    COUNT(IF(sentitems.Status != 'SendingError',0,NULL)) sukses_send,
                    COUNT(IF(sentitems.Status = 'SendingError',0,NULL)) gagal_send
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    WHERE DATE(sentitems.SendingDateTime) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(sentitems.SendingDateTime,'%d-%m-%Y') ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                }

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
         
        //     $Berdasarkan .= ' (' .implode($_POST[implode($_POST['kriterias'])]). ')';
        // }

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] = $Berdasarkan;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
            $set = 55;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'content' => $this->renderPartial('pdf-view-laporan-periodik-frekuensi', $content),
            'options' => [
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=> $header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }

public function actionExportExcel()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

// var_dump($periode_format);
// die;

            if (isset($_POST['peminjaman'])) {
            foreach ($_POST['peminjaman'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['topeminjaman'])) {
                foreach ($_POST['topeminjaman'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }

            if (isset($_POST['jatuh_tempo'])) {
            foreach ($_POST['jatuh_tempo'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['tojatuh_tempo'])) {
                foreach ($_POST['tojatuh_tempo'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }   
             
            if (isset($_POST['no_anggota'])) {
            foreach ($_POST['no_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND members.ID = '".addslashes($value)."' ";
                    }
                }
            }  

            
            $sql = "SELECT ".$periode_format.", 
                    COUNT(sentitems.DestinationNumber) AS jum_anggota, 
                    COUNT(collections.ID) AS jumlah_koleksi, 
                    COUNT(IF(sentitems.Status != 'SendingError',0,NULL)) sukses_send,
                    COUNT(IF(sentitems.Status = 'SendingError',0,NULL)) gagal_send
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    WHERE DATE(sentitems.SendingDateTime) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(sentitems.SendingDateTime,'%d-%m-%Y') ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $filename = 'Laporan_Periodik_Frekuensi.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="6">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Pengiriman SMS Otomatis').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Pengiriman').'</th>
                <th>'.yii::t('app','Jumlah Anggota').'</th>
                <th>'.yii::t('app','Jumlah Koleksi').'</th>
                <th>'.yii::t('app','Jumlah Pesan Terkirim').'</th>
                <th>'.yii::t('app','Jumlah Pesan Gagal Terkirim').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahAnggota = 0;
        $JumlahKoleksi = 0;
        $JumlahSukses = 0;
        $JumlahGagal = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['jum_anggota'].'</td>
                    <td>'.$data['jumlah_koleksi'].'</td>
                    <td>'.$data['sukses_send'].'</td>
                    <td>'.$data['gagal_send'].'</td>
                </tr>
            ';
                        $JumlahAnggota = $JumlahAnggota + $data['jum_anggota'];
                        $JumlahKoleksi = $JumlahKoleksi + $data['jumlah_koleksi'];
                        $JumlahSukses = $JumlahSukses + $data['sukses_send'];
                        $JumlahGagal = $JumlahGagal + $data['gagal_send'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="2" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahAnggota.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahKoleksi.'
                        </td
                        <td style="font-weight: bold;">
                            '.$JumlahSukses.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahGagal.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdt()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

// var_dump($periode_format);
// die;

            if (isset($_POST['peminjaman'])) {
            foreach ($_POST['peminjaman'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['topeminjaman'])) {
                foreach ($_POST['topeminjaman'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }

            if (isset($_POST['jatuh_tempo'])) {
            foreach ($_POST['jatuh_tempo'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['tojatuh_tempo'])) {
                foreach ($_POST['tojatuh_tempo'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }   
             
            if (isset($_POST['no_anggota'])) {
            foreach ($_POST['no_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND members.ID = '".addslashes($value)."' ";
                    }
                }
            }  

            
            $sql = "SELECT ".$periode_format.", 
                    COUNT(sentitems.DestinationNumber) AS jum_anggota, 
                    COUNT(collections.ID) AS jumlah_koleksi, 
                    COUNT(IF(sentitems.Status != 'SendingError',0,NULL)) sukses_send,
                    COUNT(IF(sentitems.Status = 'SendingError',0,NULL)) gagal_send
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    WHERE DATE(sentitems.SendingDateTime) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(sentitems.SendingDateTime,'%d-%m-%Y') ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');



    // Open Office Calc Area
    $menu = 'Pemanfaatan Opac';

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'jum_anggota'=>$model['jum_anggota'], 'jumlah_koleksi'=>$model['jumlah_koleksi'], 'sukses_send'=>$model['sukses_send'], 'gagal_send'=>$model['gagal_send'] );
            $JumlahAnggota = $JumlahAnggota + $model['jum_anggota'];
            $JumlahKoleksi = $JumlahKoleksi + $model['jumlah_koleksi'];
            $JumlahSukses = $JumlahSukses + $model['sukses_send'];
            $JumlahGagal = $JumlahGagal + $model['gagal_send'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'Totaljum_anggota'=>$jum_anggota,
        'Totaljumlah_koleksi'=>$jumlah_koleksi,
        'Totalsukses_send'=>$sukses_send,
        'Totalgagal_send'=>$gagal_send,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'pengiriman_SMSotomatis'=> yii::t('app','Pengiriman SMS Otomatis'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_pengiriman'=> yii::t('app','Tanggal Pengiriman'),
        'jumlah_anggota'=> yii::t('app','Jumlah Anggota'),
        'jumlah_koleksi'=> yii::t('app','Jumlah Koleksi'),     
        'jumlah_pesanterkirim'=> yii::t('app','Jumlah Pesan Terkirim'),     
        'jumlah_pesangagalterkirim'=> yii::t('app','Jumlah Pesan Gagal Terkirim'),     
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/sms/laporan-sms-frekuensi.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-OPAC-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWord()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

// var_dump($periode_format);
// die;

            if (isset($_POST['peminjaman'])) {
            foreach ($_POST['peminjaman'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['topeminjaman'])) {
                foreach ($_POST['topeminjaman'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }

            if (isset($_POST['jatuh_tempo'])) {
            foreach ($_POST['jatuh_tempo'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['tojatuh_tempo'])) {
                foreach ($_POST['tojatuh_tempo'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }   
             
            if (isset($_POST['no_anggota'])) {
            foreach ($_POST['no_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND members.ID = '".addslashes($value)."' ";
                    }
                }
            }  

            
            $sql = "SELECT ".$periode_format.", 
                    COUNT(sentitems.DestinationNumber) AS jum_anggota, 
                    COUNT(collections.ID) AS jumlah_koleksi, 
                    COUNT(IF(sentitems.Status != 'SendingError',0,NULL)) sukses_send,
                    COUNT(IF(sentitems.Status = 'SendingError',0,NULL)) gagal_send
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    WHERE DATE(sentitems.SendingDateTime) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(sentitems.SendingDateTime,'%d-%m-%Y') ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="6">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Pengiriman SMS Otomatis').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Pengiriman').'</th>
                <th>'.yii::t('app','Jumlah Anggota').'</th>
                <th>'.yii::t('app','Jumlah Koleksi').'</th>
                <th>'.yii::t('app','Jumlah Pesan Terkirim').'</th>
                <th>'.yii::t('app','Jumlah Pesan Gagal Terkirim').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahAnggota = 0;
        $JumlahKoleksi = 0;
        $JumlahSukses = 0;
        $JumlahGagal = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['jum_anggota'].'</td>
                    <td>'.$data['jumlah_koleksi'].'</td>
                    <td>'.$data['sukses_send'].'</td>
                    <td>'.$data['gagal_send'].'</td>
                </tr>
            ';
                        $JumlahAnggota = $JumlahAnggota + $data['jum_anggota'];
                        $JumlahKoleksi = $JumlahKoleksi + $data['jumlah_koleksi'];
                        $JumlahSukses = $JumlahSukses + $data['sukses_send'];
                        $JumlahGagal = $JumlahGagal + $data['gagal_send'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="2" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahAnggota.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahKoleksi.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahSukses.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahGagal.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}


public function actionExportPdf()
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(sentitems.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;


            if (isset($_POST['peminjaman'])) {
            foreach ($_POST['peminjaman'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['topeminjaman'])) {
                foreach ($_POST['topeminjaman'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }

            if (isset($_POST['jatuh_tempo'])) {
            foreach ($_POST['jatuh_tempo'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " OR DATE(sentitems.SendingDateTime) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            } 
            if (isset($_POST['tojatuh_tempo'])) {
                foreach ($_POST['tojatuh_tempo'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                    }
                }
            }   
             
            if (isset($_POST['no_anggota'])) {
            foreach ($_POST['no_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND members.ID = '".addslashes($value)."' ";
                    }
                }
            }  

            
            $sql = "SELECT ".$periode_format.", 
                    COUNT(sentitems.DestinationNumber) AS jum_anggota, 
                    COUNT(collections.ID) AS jumlah_koleksi, 
                    COUNT(IF(sentitems.Status != 'SendingError',0,NULL)) sukses_send,
                    COUNT(IF(sentitems.Status = 'SendingError',0,NULL)) gagal_send
                    FROM 
                    sentitems
                    INNER JOIN members ON members.Phone = sentitems.DestinationNumber
                    INNER JOIN collections ON collections.BookingMemberID = members.MemberNo
                    WHERE DATE(sentitems.SendingDateTime) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(sentitems.SendingDateTime,'%d-%m-%Y') ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(sentitems.SendingDateTime) ORDER BY DATE_FORMAT(sentitems.SendingDateTime,'%Y-%m-%d') DESC";
                }

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
         
        //     $Berdasarkan .= ' (' .implode($_POST[implode($_POST['kriterias'])]). ')';
        // }

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] = $Berdasarkan;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
            $set = 55;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'options' => [
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-view-laporan-periodik-frekuensi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }

public function actionRenderSmsTerkirimFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }
  
            if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }  


            $sql = "SELECT ".$periode_format.",members.* , COUNT(outbox.ID) as jum_sms
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(outbox.SendingDateTime,'%d-%m-%Y'),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                }

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] = $Berdasarkan;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
            $set = 55;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'content' => $this->renderPartial('pdf-view-sms-terkirim-frekuensi', $content),
            'options' => [
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=> $header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }

public function actionExportExcelSmsTerkirimFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

            if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }  


            $sql = "SELECT ".$periode_format.",members.* , COUNT(outbox.ID) as jum_sms
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(outbox.SendingDateTime,'%d-%m-%Y'),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                }


    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $filename = 'Laporan_SMS_Terkirim_Frekuensi.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="4">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','SMS Terkirim').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan').' '.yii::t('app','Nama Anggota').' </th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Pengiriman').'</th>
                <th>'.yii::t('app','Nama Anggota').'</th>
                <th>'.yii::t('app','Jumlah Pesan Terkirim').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Fullname'].'</td>
                    <td>'.$data['jum_sms'].'</td>
                </tr>
            ';
                        $JumlahPesan = $JumlahPesan + $data['jum_sms'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahPesan.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtSmsTerkirimFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }  


            $sql = "SELECT ".$periode_format.",members.* , COUNT(outbox.ID) as jum_sms
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(outbox.SendingDateTime,'%d-%m-%Y'),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Fullname'=>$model['Fullname'], 'jum_sms'=>$model['jum_sms'] );
            $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlahSms'=>$jum_sms,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'SMS_terkirim'=> yii::t('app','SMS Terkirim'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'nama_anggota'=> yii::t('app','Nama Anggota'),
        'tanggal_pengiriman'=> yii::t('app','Tanggal Pengiriman'),
        'nama_anggota'=> yii::t('app','Nama Anggota'),
        'jumlah_pesanterkirim'=> yii::t('app','Jumlah Pesan Terkirim'),     
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/sms/laporan-sms-terkirim-frekuensi.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-sms-terkirim-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordSmsTerkirimFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }
            
            if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }  


            $sql = "SELECT ".$periode_format.",members.* , COUNT(outbox.ID) as jum_sms
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(outbox.SendingDateTime,'%d-%m-%Y'),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="4">'.yii::t('app','Tanggal Pengiriman').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','SMS Terkirim').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan').' '.yii::t('app','Nama Anggota').'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Pengiriman').'</th>
                <th>'.yii::t('app','Nama Anggota').'</th>
                <th>'.yii::t('app','Jumlah Pesan Terkirim').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Fullname'].'</td>
                    <td>'.$data['jum_sms'].'</td>
                </tr>
            ';
                        $JumlahPesan = $JumlahPesan + $data['jum_sms'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahPesan.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}


public function actionExportPdfSmsTerkirimFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(outbox.SendingDateTime,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }
  
            if (isset($_POST['no_anggota'])) {
                foreach ($_POST['no_anggota'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                    }
                }
            }  


            $sql = "SELECT ".$periode_format.",members.* , COUNT(outbox.ID) as jum_sms
                    FROM 
                    outbox
                    INNER JOIN members ON members.NoHp = outbox.DestinationNumber
                    WHERE DATE(outbox.SendingDateTime) ";  
     
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(outbox.SendingDateTime,'%d-%m-%Y'),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(outbox.SendingDateTime),members.ID ORDER BY DATE_FORMAT(outbox.SendingDateTime,'%Y-%m-%d') DESC";
                }

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] = $Berdasarkan;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
            $set = 55;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'options' => [
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-view-sms-terkirim-frekuensi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }
// ////////////////////////////////batas get_real_name///////////////////////////////////////////////// //
public function getRealNameKriteria($kriterias)
    {
        if ($kriterias == 'no_anggota') 
        {
            $name = 'Penginput Data';
        } 
        elseif ($kriterias == 'peminjaman') 
        {
            $name = 'Tanggal Peminjaman';
        }
        elseif ($kriterias == 'jatuh_tempo') 
        {
            $name = 'Tanggal Jatuh Tempo';
        }    
        else 
        {
            $name = ' ';
        }
        
        return $name;

    }
}
