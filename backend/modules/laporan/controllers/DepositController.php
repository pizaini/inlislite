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
use common\models\DepositKodeWilayah;


class DepositController extends \yii\web\Controller
{
    /**
     * [actionIndex description]
     * @return [type] [description]
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPerGroup()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('per-group',[
            'model' => $model,
            ]);
    }

    public function actionJenisKoleksi()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('jenis-koleksi',[
            'model' => $model,
            ]);
    }

    public function actionWajibSerah()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('wajib-serah',[
            'model' => $model,
            ]);
    }

    public function actionWajibSerahDetail()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('wajib-serah-detail',[
            'model' => $model,
            ]);
    }

    public function actionPenerbit()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        // return $this->redirect([Url::to('render-laporan-deposit-penerbit'), 'model' => $model]);
        // return $this->redirect([Url::to('show-pdf'), 'tampilkan' => 'penerbit']);
        return $this->render('penerbit',[
            'model' => $model,
            ]);
    }

    public function actionPenerbitWilayah()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        // return $this->redirect([Url::to('render-laporan-deposit-penerbit'), 'model' => $model]);
        // return $this->redirect([Url::to('show-pdf'), 'tampilkan' => 'penerbit']);
        return $this->render('penerbit-wilayah',[
            'model' => $model,
            ]);
    }

    public function actionTerimaKasih()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        // return $this->redirect([Url::to('render-laporan-deposit-penerbit'), 'model' => $model]);
        // return $this->redirect([Url::to('show-pdf'), 'tampilkan' => 'penerbit']);
        return $this->render('terima-kasih',[
            'model' => $model,
            ]);
    }
    public function actionCardex()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);
        return $this->render('cardex',[
            'model' => $model,
            ]);
    }
    
    public function actionSerial()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);
        return $this->render('serial',[
            'model' => $model,
            ]);
    }

    public function actionAset()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);
        return $this->render('aset',[
            'model' => $model,
            ]);
    }
    

public function actionLoadFilterKriteria($kriteria)
{
        if ($kriteria == 'group')
        {
            $sql = 'SELECT * FROM deposit_group_ws';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 

            $options = ArrayHelper::map($data,'id_group',
                function($model) {
                    return $model['group_name'];
                });
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        else if ($kriteria == 'users')
        {  
            $sql = 'SELECT * FROM users';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 

            $options = ArrayHelper::map($data,'ID',
                function($model) {
                    return $model['username'];
                });
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );

        }

        else if ($kriteria == 'catalogs')
        {  
            $sql = 'SELECT * FROM catalogs';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 

            $options = ArrayHelper::map($data,'ID',
                function($model) {
                    return $model['Title'];
                });
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );

        }

        else if ($kriteria == 'wilayah')
        {  
            $sql = 'SELECT * FROM deposit_kode_wilayah';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 

            $options = ArrayHelper::map($data,'ID',
                function($model) {
                    return $model['kode_wilayah']. ' - ' .$model['nama_wilayah'];
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
        if ($tampilkan == 'laporan-deposit-group')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-group').'">';
            echo "<ifrRrame>";
        }elseif ($tampilkan == 'laporan-deposit-jenis-koleksi')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-jenis-koleksi').'">';
            echo "<ifrRrame>";

        }elseif ($tampilkan == 'laporan-deposit-wajib-serah')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-wajib-serah').'">';
            echo "<ifrRrame>";

        }elseif ($tampilkan == 'laporan-deposit-wajib-serah-detail')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-wajib-serah-detail').'">';
            echo "<ifrRrame>";

        }elseif ($tampilkan == 'laporan-deposit-penerbit')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-penerbit').'">';
            echo "<ifrRrame>";

        }elseif ($tampilkan == 'laporan-deposit-penerbit-wilayah')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-penerbit-wilayah').'">';
            echo "<ifrRrame>";

        }elseif ($tampilkan == 'laporan-deposit-terima-kasih')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-terima-kasih').'">';
            echo "<ifrRrame>";

        }elseif ($tampilkan == 'laporan-deposit-cardex')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-cardex').'">';
            echo "<ifrRrame>";

        }elseif ($tampilkan == 'laporan-deposit-serial')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-serial').'">';
            echo "<ifrRrame>";

        }elseif ($tampilkan == 'laporan-deposit-aset')
        {
            // echo '<pre>';print_r($_POST['kriterias']);die;
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-deposit-aset').'">';
            echo "<ifrRrame>";

        }

        // if ($tampilkan == 'sms-terkirim-frekuensi')
        // {            
        //     echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-sms-terkirim-frekuensi').'">';
        //     echo "<iframe>";
        // }
        // if ($tampilkan == 'sms-terkirim-data')
        // {            
        //     echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-sms-terkirim-data').'">';
        //     echo "<iframe>";
        // }
        
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
public function actionRenderLaporanDepositGroup() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

            if (isset($_POST['group'])) {
            foreach ($_POST['group'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= "WHERE deposit_group_ws.`id_group` = '".$value."' ";
                    }
                }
            }     
        
            $sql = "SELECT deposit_group_ws.`group_name` AS nama_group,
                        deposit_ws.`nama_penerbit` AS nama_penerbit, 
                        CASE 
                            WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != '' 
                               THEN deposit_ws.`alamat1` 
                            WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != '' 
                               THEN deposit_ws.`alamat2`
                               ELSE deposit_ws.`alamat3`
                        END AS alamat,
                        deposit_ws.`kabupaten` AS kota
                        FROM deposit_group_ws 
                        INNER JOIN deposit_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws` 
                        ".$andValue." ";        
        

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = $this->getRealNameKriteria('group');

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
            'content' => $this->renderPartial('pdf-view-laporan-deposit-group', $content),
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

public function actionExportExcelDepositGroup()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

            if (isset($_POST['group'])) {
            foreach ($_POST['group'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= "WHERE deposit_group_ws.`id_group` = '".$value."' ";
                    }
                }
            }     
        
            $sql = "SELECT deposit_group_ws.`group_name` AS nama_group,
                        deposit_ws.`nama_penerbit` AS nama_penerbit, 
                        CASE 
                            WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != '' 
                               THEN deposit_ws.`alamat1` 
                            WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != '' 
                               THEN deposit_ws.`alamat2`
                               ELSE deposit_ws.`alamat3`
                        END AS alamat,
                        deposit_ws.`kabupaten` AS kota
                        FROM deposit_group_ws 
                        INNER JOIN deposit_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws` 
                        ".$andValue." ";        
        

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $filename = 'Laporan_Deposit_Group.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="5">'.yii::t('app','Daftar Penerbit per Group').'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nama Group').'</th>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat Penerbit').'</th>
                <th>'.yii::t('app','Kota').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['nama_group'].'</td>
                    <td>'.$data['nama_penerbit'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kota'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositGroup()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

            if (isset($_POST['group'])) {
            foreach ($_POST['group'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= "WHERE deposit_group_ws.`id_group` = '".$value."' ";
                    }
                }
            }     
        
            $sql = "SELECT deposit_group_ws.`group_name` AS nama_group,
                        deposit_ws.`nama_penerbit` AS nama_penerbit, 
                        CASE 
                            WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != '' 
                               THEN deposit_ws.`alamat1` 
                            WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != '' 
                               THEN deposit_ws.`alamat2`
                               ELSE deposit_ws.`alamat3`
                        END AS alamat,
                        deposit_ws.`kabupaten` AS kota
                        FROM deposit_group_ws 
                        INNER JOIN deposit_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws` 
                        ".$andValue." "; 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'nama_group'=> $model['nama_group'], 'nama_penerbit'=>$model['nama_penerbit'], 'alamat'=>$model['alamat'], 'kota'=>$model['kota']);
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
        'nama_group'=> yii::t('app','Nama Group'),
        'nama_penerbit'=> yii::t('app','Nama Penerbit'),
        'alamat_penerbit'=> yii::t('app','Alamat Penerbit'),     
        'kota'=> yii::t('app','Kota'),     
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-group.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-OPAC-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositGroup()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

            if (isset($_POST['group'])) {
            foreach ($_POST['group'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= "WHERE deposit_group_ws.`id_group` = '".$value."' ";
                    }
                }
            }     
        
            $sql = "SELECT deposit_group_ws.`group_name` AS nama_group,
                        deposit_ws.`nama_penerbit` AS nama_penerbit, 
                        CASE 
                            WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != '' 
                               THEN deposit_ws.`alamat1` 
                            WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != '' 
                               THEN deposit_ws.`alamat2`
                               ELSE deposit_ws.`alamat3`
                        END AS alamat,
                        deposit_ws.`kabupaten` AS kota
                        FROM deposit_group_ws 
                        INNER JOIN deposit_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws` 
                        ".$andValue." ";        

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="5">'.yii::t('app','Daftar Penerbit per Group').'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nama Group').'</th>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat Penerbit').'</th>
                <th>'.yii::t('app','Kota').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['nama_group'].'</td>
                    <td>'.$data['nama_penerbit'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kota'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}


public function actionExportPdfDepositGroup()
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

            if (isset($_POST['group'])) {
            foreach ($_POST['group'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= "WHERE deposit_group_ws.`id_group` = '".$value."' ";
                    }
                }
            }     
        
            $sql = "SELECT deposit_group_ws.`group_name` AS nama_group,
                        deposit_ws.`nama_penerbit` AS nama_penerbit, 
                        CASE 
                            WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != '' 
                               THEN deposit_ws.`alamat1` 
                            WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != '' 
                               THEN deposit_ws.`alamat2`
                               ELSE deposit_ws.`alamat3`
                        END AS alamat,
                        deposit_ws.`kabupaten` AS kota
                        FROM deposit_group_ws 
                        INNER JOIN deposit_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws` 
                        ".$andValue." ";            

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
        $content = $this->renderPartial('pdf-view-laporan-deposit-group', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Deposit_group.pdf', 'D');

    }

//=====================================================================================================================================================


public function actionRenderLaporanDepositJenisKoleksi() 
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
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = " collections.`CreateDate` BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            else 
            {
                $periode = null;
            }
        }
  
            if (isset($_POST['users'])) {
                foreach ($_POST['users'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                    }
                }
            }  

            $sql = "SELECT collectionmedias.ID ,collectionmedias.`Name` AS jenis_koleksi, 
                    collectionmedias.`KodeBahanPustaka` AS kode,  
                    (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`Catalog_id`= catalogs.`ID` ".$andValue."AND".$sqlPeriode.") AS jml_jud,
                    (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`Catalog_id` = catalogs.`ID` ".$andValue."AND".$sqlPeriode." ) AS jum_eks
                    FROM collectionmedias 
                    LEFT JOIN worksheets ON worksheets.`ID` = collectionmedias.`Worksheet_id`
                    LEFT JOIN catalogs ON catalogs.`Worksheet_id` = worksheets.`ID`
                    LEFT JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN users ON users.`ID` = collections.`CreateBy`
                    GROUP BY collectionmedias.`Name`";   

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
            'content' => $this->renderPartial('pdf-view-deposit-jenis-koleksi', $content),
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

public function actionExportExcelDepositJenisKoleksi()
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
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = " collections.`CreateDate` BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            else 
            {
                $periode = null;
            }
        }
  
            if (isset($_POST['users'])) {
                foreach ($_POST['users'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                    }
                }
            }  

            $sql = "SELECT collectionmedias.ID ,collectionmedias.`Name` AS jenis_koleksi, 
                    collectionmedias.`KodeBahanPustaka` AS kode,  
                    (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`Catalog_id`= catalogs.`ID` ".$andValue."AND".$sqlPeriode.") AS jml_jud,
                    (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`Catalog_id` = catalogs.`ID` ".$andValue."AND".$sqlPeriode." ) AS jum_eks
                    FROM collectionmedias 
                    LEFT JOIN worksheets ON worksheets.`ID` = collectionmedias.`Worksheet_id`
                    LEFT JOIN catalogs ON catalogs.`Worksheet_id` = worksheets.`ID`
                    LEFT JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN users ON users.`ID` = collections.`CreateBy`
                    GROUP BY collectionmedias.`Name`";  

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $filename = 'Laporan_Deposit_jenis_koleksi.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="5">'.yii::t('app','DAFTAR JUMLAH KOLEKSI PER JENIS KARYA CETAK YANG DITERIMA').'</th>
            </tr>
             <tr>
                <th colspan="5">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Jenis Koleksi').'</th>
                <th>'.yii::t('app','Kode').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['jenis_koleksi'].'</td>
                    <td>'.$data['kode'].'</td>
                    <td>'.$data['jml_jud'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositJenisKoleksi()
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
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = " collections.`CreateDate` BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            else 
            {
                $periode = null;
            }
        }
  
            if (isset($_POST['users'])) {
                foreach ($_POST['users'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                    }
                }
            }  

            $sql = "SELECT collectionmedias.ID ,collectionmedias.`Name` AS jenis_koleksi, 
                    collectionmedias.`KodeBahanPustaka` AS kode,  
                    (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`Catalog_id`= catalogs.`ID` ".$andValue."AND".$sqlPeriode.") AS jml_jud,
                    (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`Catalog_id` = catalogs.`ID` ".$andValue."AND".$sqlPeriode." ) AS jum_eks
                    FROM collectionmedias 
                    LEFT JOIN worksheets ON worksheets.`ID` = collectionmedias.`Worksheet_id`
                    LEFT JOIN catalogs ON catalogs.`Worksheet_id` = worksheets.`ID`
                    LEFT JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN users ON users.`ID` = collections.`CreateBy`
                    GROUP BY collectionmedias.`Name`";   

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'jenis_koleksi'=> $model['jenis_koleksi'], 'kode'=>$model['kode'], 'jml_jud'=>$model['jml_jud'], 'jum_eks'=>$model['jum_eks'] );
            // $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'daftar_jumlah_koleksi'=> yii::t('app','DAFTAR JUMLAH KOLEKSI PER JENIS KARYA CETAK YANG DITERIMA'),   
        'jenis_koleksi'=> yii::t('app','Jenis Koleksi'),   
        'kode'=> yii::t('app','Kode'),   
        'jumlah_judul'=> yii::t('app','Jumlah Judul'),   
        'jumlah_eksemplar'=> yii::t('app','Jumlah Eksemplar'),   
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-jenis-koleksi.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-deposit-jenis-koleksi.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositJenisKoleksi()
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
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = " collections.`CreateDate` BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            else 
            {
                $periode = null;
            }
        }
  
            if (isset($_POST['users'])) {
                foreach ($_POST['users'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                    }
                }
            }  

            $sql = "SELECT collectionmedias.ID ,collectionmedias.`Name` AS jenis_koleksi, 
                    collectionmedias.`KodeBahanPustaka` AS kode,  
                    (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`Catalog_id`= catalogs.`ID` ".$andValue."AND".$sqlPeriode.") AS jml_jud,
                    (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`Catalog_id` = catalogs.`ID` ".$andValue."AND".$sqlPeriode." ) AS jum_eks
                    FROM collectionmedias 
                    LEFT JOIN worksheets ON worksheets.`ID` = collectionmedias.`Worksheet_id`
                    LEFT JOIN catalogs ON catalogs.`Worksheet_id` = worksheets.`ID`
                    LEFT JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN users ON users.`ID` = collections.`CreateBy`
                    GROUP BY collectionmedias.`Name`";  

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
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
                <th colspan="5">'.yii::t('app','DAFTAR JUMLAH KOLEKSI PER JENIS KARYA CETAK YANG DITERIMA').'</th>
            </tr>
             <tr>
                <th colspan="5">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Jenis Koleksi').'</th>
                <th>'.yii::t('app','Kode').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['jenis_koleksi'].'</td>
                    <td>'.$data['kode'].'</td>
                    <td>'.$data['jml_jud'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}


public function actionExportPdfDepositJenisKoleksi() 
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
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = " collections.`CreateDate` BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            else 
            {
                $periode = null;
            }
        }
  
            if (isset($_POST['users'])) {
                foreach ($_POST['users'] as $key => $value) {
                    if ($value != "0" ) {
                        $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                    }
                }
            }  

            $sql = "SELECT collectionmedias.ID ,collectionmedias.`Name` AS jenis_koleksi, 
                    collectionmedias.`KodeBahanPustaka` AS kode,  
                    (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`Catalog_id`= catalogs.`ID` ".$andValue."AND".$sqlPeriode.") AS jml_jud,
                    (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`Catalog_id` = catalogs.`ID` ".$andValue."AND".$sqlPeriode." ) AS jum_eks
                    FROM collectionmedias 
                    LEFT JOIN worksheets ON worksheets.`ID` = collectionmedias.`Worksheet_id`
                    LEFT JOIN catalogs ON catalogs.`Worksheet_id` = worksheets.`ID`
                    LEFT JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN users ON users.`ID` = collections.`CreateBy`
                    GROUP BY collectionmedias.`Name`";  

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
        $content = $this->renderPartial('pdf-view-deposit-jenis-koleksi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Deposit_Jenis_koleksi.pdf', 'D');

    }

//=====================================================================================================================================================

public function actionRenderLaporanDepositWajibSerah() 
{

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
    $sqlPeriode = " WHERE DATE(deposit_ws.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
      //Untuk Header Laporan berdasarkan Periode yng dipilih

        $sql = "SELECT deposit_ws.`nama_penerbit` AS penertbit, 
                worksheets.`Name` AS jenis_koleksi, 
                collectionmedias.`KodeBahanPustaka` AS kode,
                (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jml_jud,
                (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jum_eks
                FROM deposit_ws
                LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                ";   
        $sql .= $sqlPeriode;
        $sql .= "AND collections.`deposit_ws_ID` IS NOT NULL";

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
        'content' => $this->renderPartial('pdf-view-deposit-wajib-serah', $content),
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

public function actionExportExcelDepositWajibSerah()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
        $sqlPeriode = " WHERE DATE(deposit_ws.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
          //Untuk Header Laporan berdasarkan Periode yng dipilih

            $sql = "SELECT deposit_ws.`nama_penerbit` AS penertbit, 
                    worksheets.`Name` AS jenis_koleksi, 
                    collectionmedias.`KodeBahanPustaka` AS kode,
                    (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jml_jud,
                    (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jum_eks
                    FROM deposit_ws
                    LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                    LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                    LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                    LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                    ";   
            $sql .= $sqlPeriode;
            $sql .= "AND collections.`deposit_ws_ID` IS NOT NULL";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $filename = 'Laporan_Deposit_Wajib_serah.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="6">'.yii::t('app','Daftar Jumlah koleksi per Wajib Serah').'</th>
            </tr>
             <tr>
                <th colspan="6">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Jenis Koleksi').'</th>
                <th>'.yii::t('app','Kode').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['penertbit'].'</td>
                    <td>'.$data['jenis_koleksi'].'</td>
                    <td>'.$data['kode'].'</td>
                    <td>'.$data['jml_jud'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositWajibSerah()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
        $sqlPeriode = " WHERE DATE(deposit_ws.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
          //Untuk Header Laporan berdasarkan Periode yng dipilih

            $sql = "SELECT deposit_ws.`nama_penerbit` AS penertbit, 
                    worksheets.`Name` AS jenis_koleksi, 
                    collectionmedias.`KodeBahanPustaka` AS kode,
                    (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jml_jud,
                    (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jum_eks
                    FROM deposit_ws
                    LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                    LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                    LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                    LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                    ";   
            $sql .= $sqlPeriode;
            $sql .= "AND collections.`deposit_ws_ID` IS NOT NULL";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'penertbit'=> $model['penertbit'],'jenis_koleksi'=> $model['jenis_koleksi'], 'kode'=>$model['kode'], 'jml_jud'=>$model['jml_jud'], 'jum_eks'=>$model['jum_eks'] );
            // $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'daftar_jumlah_koleksi'=> yii::t('app','Daftar Jumlah koleksi per Wajib Serah'),   
        'penerbit'=> yii::t('app','Penerbit'),   
        'jenis_koleksi'=> yii::t('app','Jenis Koleksi'),   
        'kode'=> yii::t('app','Kode'),   
        'jumlah_judul'=> yii::t('app','Jumlah Judul'),   
        'jumlah_eksemplar'=> yii::t('app','Jumlah Eksemplar'),   
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-wajib-serah.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-deposit-jenis-koleksi.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositWajibSerah()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
        $sqlPeriode = " WHERE DATE(deposit_ws.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
          //Untuk Header Laporan berdasarkan Periode yng dipilih

            $sql = "SELECT deposit_ws.`nama_penerbit` AS penertbit, 
                    worksheets.`Name` AS jenis_koleksi, 
                    collectionmedias.`KodeBahanPustaka` AS kode,
                    (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jml_jud,
                    (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jum_eks
                    FROM deposit_ws
                    LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                    LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                    LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                    LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                    ";   
            $sql .= $sqlPeriode;
            $sql .= "AND collections.`deposit_ws_ID` IS NOT NULL";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Deposit_wajib_serah.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="6">'.yii::t('app','Daftar Jumlah koleksi per Wajib Serah').'</th>
            </tr>
             <tr>
                <th colspan="6">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Jenis Koleksi').'</th>
                <th>'.yii::t('app','Kode').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['penertbit'].'</td>
                    <td>'.$data['jenis_koleksi'].'</td>
                    <td>'.$data['kode'].'</td>
                    <td>'.$data['jml_jud'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportPdfDepositWajibSerah() 
{

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
    $sqlPeriode = " WHERE DATE(deposit_ws.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
      //Untuk Header Laporan berdasarkan Periode yng dipilih

        $sql = "SELECT deposit_ws.`nama_penerbit` AS penertbit, 
                worksheets.`Name` AS jenis_koleksi, 
                collectionmedias.`KodeBahanPustaka` AS kode,
                (SELECT COUNT(DISTINCT collections.`Catalog_id`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jml_jud,
                (SELECT COUNT(collections.`ID`) FROM collections WHERE collections.`deposit_ws_ID` = deposit_ws.`ID`) AS jum_eks
                FROM deposit_ws
                LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                ";   
        $sql .= $sqlPeriode;
        $sql .= "AND collections.`deposit_ws_ID` IS NOT NULL";

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
    $content = $this->renderPartial('pdf-view-deposit-wajib-serah', $content);
    if ($content_kop['kop']) {
    $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
    }else{
    $pdf->SetHTMLHeader();
    }
    $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
    $pdf->WriteHtml($content);
    echo $pdf->Output('Laporan_Deposit_Wajib_serah.pdf', 'D');

}

//=====================================================================================================================================================

public function actionRenderLaporanDepositWajibSerahDetail() 
{

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;
    $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
    if ($_POST['kategori_tgl'] == 'dibuat') {
        $sqlPeriode = " WHERE DATE(collections.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }else{
        $sqlPeriode = " WHERE DATE(collections.`TanggalPengadaan`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }

        if (isset($_POST['users'])) {
            foreach ($_POST['users'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        } 
      //Untuk Header Laporan berdasarkan Periode yng dipilih

        $sql = "SELECT deposit_ws.`nama_penerbit` AS penertbit,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 deposit_ws.`kabupaten` AS kota,
                 deposit_ws.`email` AS email,
                 worksheets.`Name` AS jenis_koleksi,
                 collectionmedias.`KodeBahanPustaka` AS kode,
                 catalogs.`Title` AS judul,
                 catalogs.`Author` AS pengarang,
                 catalogs.`ISBN` AS isbn,
                 collections.`NomorDeposit` AS no_deposit,
                 catalogs.`Edition` AS edisi,
                 collections.`EDISISERIAL` AS eds_serial,
                 collections.`TANGGAL_TERBIT_EDISI_SERIAL` AS tgl_eds_serial,
                 collections.`ThnTerbitDeposit` AS thn_terbit,
                 collections.`TanggalPengadaan` AS thn_penerimaan,
                 collections.`CreateDate` AS tgl_buat
                 FROM deposit_ws
                 LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                 LEFT JOIN users ON collections.`CreateBy` = users.`ID`
                 LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                 LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                 LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                ";   
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        // $sql .= "AND collections.`deposit_ws_ID` IS NOT NULL";

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
        'content' => $this->renderPartial('pdf-view-deposit-wajib-serah-detail', $content),
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

public function actionExportExcelDepositWajibSerahDetail()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;
    $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
    if ($_POST['kategori_tgl'] == 'dibuat') {
        $sqlPeriode = " WHERE DATE(collections.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }else{
        $sqlPeriode = " WHERE DATE(collections.`TanggalPengadaan`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }

        if (isset($_POST['users'])) {
            foreach ($_POST['users'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        } 
      //Untuk Header Laporan berdasarkan Periode yng dipilih

        $sql = "SELECT deposit_ws.`nama_penerbit` AS penertbit,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 deposit_ws.`kabupaten` AS kota,
                 deposit_ws.`email` AS email,
                 worksheets.`Name` AS jenis_koleksi,
                 collectionmedias.`KodeBahanPustaka` AS kode,
                 catalogs.`Title` AS judul,
                 catalogs.`Author` AS pengarang,
                 catalogs.`ISBN` AS isbn,
                 collections.`NomorDeposit` AS no_deposit,
                 catalogs.`Edition` AS edisi,
                 collections.`EDISISERIAL` AS eds_serial,
                 collections.`TANGGAL_TERBIT_EDISI_SERIAL` AS tgl_eds_serial,
                 collections.`ThnTerbitDeposit` AS thn_terbit,
                 collections.`TanggalPengadaan` AS thn_penerimaan,
                 collections.`CreateDate` AS tgl_buat
                 FROM deposit_ws
                 LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                 LEFT JOIN users ON collections.`CreateBy` = users.`ID`
                 LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                 LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                 LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                ";   
        $sql .= $sqlPeriode;
        $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $filename = 'Laporan_Deposit_Wajib_Serah_Detail.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="17">'.yii::t('app','Daftar Jumlah koleksi per Wajib Serah Detail').'</th>
            </tr>
             <tr>
                <th colspan="17">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Kota Terbit').'</th>
                <th>'.yii::t('app','Email').'</th>
                <th>'.yii::t('app','Jenis Koleksi').'</th>
                <th>'.yii::t('app','Kode Jenis').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','ISBN').'</th>
                <th>'.yii::t('app','Nomor Deposit').'</th>
                <th>'.yii::t('app','Edisi').'</th>
                <th>'.yii::t('app','Edisi Serial').'</th>
                <th>'.yii::t('app','Tanggal Edisi').'</th>
                <th>'.yii::t('app','Tahun Terbit').'</th>
                <th>'.yii::t('app','Tanggal Terima').'</th>
                <th>'.yii::t('app','Tanggal Buat').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['penertbit'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kota'].'</td>
                    <td>'.$data['email'].'</td>
                    <td>'.$data['jenis_koleksi'].'</td>
                    <td>'.$data['kode'].'</td>
                    <td>'.$data['judul'].'</td>
                    <td>'.$data['pengarang'].'</td>
                    <td>'.$data['isbn'].'</td>
                    <td>'.$data['no_deposit'].'</td>
                    <td>'.$data['edisi'].'</td>
                    <td>'.$data['eds_serial'].'</td>
                    <td>'.$data['tgl_eds_serial'].'</td>
                    <td>'.$data['thn_terbit'].'</td>
                    <td>'.$data['thn_penerimaan'].'</td>
                    <td>'.$data['tgl_buat'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositWajibSerahDetail()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;
    $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
    if ($_POST['kategori_tgl'] == 'dibuat') {
        $sqlPeriode = " WHERE DATE(collections.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }else{
        $sqlPeriode = " WHERE DATE(collections.`TanggalPengadaan`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }

        if (isset($_POST['users'])) {
            foreach ($_POST['users'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        } 
      //Untuk Header Laporan berdasarkan Periode yng dipilih

        $sql = "SELECT deposit_ws.`nama_penerbit` AS penerbit,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 deposit_ws.`kabupaten` AS kota,
                 deposit_ws.`email` AS email,
                 worksheets.`Name` AS jenis_koleksi,
                 collectionmedias.`KodeBahanPustaka` AS kode,
                 catalogs.`Title` AS judul,
                 catalogs.`Author` AS pengarang,
                 catalogs.`ISBN` AS isbn,
                 collections.`NomorDeposit` AS no_deposit,
                 catalogs.`Edition` AS edisi,
                 collections.`EDISISERIAL` AS eds_serial,
                 collections.`TANGGAL_TERBIT_EDISI_SERIAL` AS tgl_eds_serial,
                 collections.`ThnTerbitDeposit` AS thn_terbit,
                 collections.`TanggalPengadaan` AS thn_penerimaan,
                 collections.`CreateDate` AS tgl_buat
                 FROM deposit_ws
                 LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                 LEFT JOIN users ON collections.`CreateBy` = users.`ID`
                 LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                 LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                 LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                ";   
        $sql .= $sqlPeriode;
        $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'penerbit'=> $model['penerbit'],'alamat'=> $model['alamat'], 'kota'=>$model['kota'], 'email'=>$model['email'], 'jenis_koleksi'=>$model['jenis_koleksi'],
                            'kode'=> $model['kode'],'judul'=> $model['judul'], 'pengarang'=>$model['pengarang'], 'isbn'=>$model['isbn'], 'no_deposit'=>$model['no_deposit'], 
                            'edisi'=>$model['edisi'], 'eds_serial'=>$model['eds_serial'], 'tgl_eds_serial'=>$model['tgl_eds_serial'], 'thn_terbit'=>$model['thn_terbit'], 'thn_penerimaan'=>$model['thn_penerimaan'], 'tgl_buat'=>$model['tgl_buat'] );
            // $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'daftar_jumlah_koleksi'=> yii::t('app','Daftar Jumlah koleksi per Wajib Serah Detail'),   
        'penerbit'=> yii::t('app','Nama Penerbit'),   
        'alamat'=> yii::t('app','Alamat'),   
        'kota_terbit'=> yii::t('app','Kota Terbit'),   
        'email'=> yii::t('app','Email'),   
        'jenis_koleksi'=> yii::t('app','Jenis Koleksi'),   
        'kode_jenis'=> yii::t('app','Kode Jenis'),   
        'judul'=> yii::t('app','Judul'),   
        'pengarang'=> yii::t('app','Pengarang'),   
        'isbn'=> yii::t('app','ISBN'),   
        'no_deposit'=> yii::t('app','Nomor Deposit'),   
        'edisi'=> yii::t('app','Edisi'),   
        'edisi_serial'=> yii::t('app','Edisi Serial'),   
        'tgl_edisi'=> yii::t('app','Tanggal Edisi'),   
        'thn_terbit'=> yii::t('app','Tahun Terbit'),   
        'tgl_terima'=> yii::t('app','Tanggal Terima'),   
        'tgl_buat'=> yii::t('app','Tanggal Buat'),    
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-wajib-serah-detail.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-deposit-jenis-koleksi.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositWajibSerahDetail()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;
    $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
    if ($_POST['kategori_tgl'] == 'dibuat') {
        $sqlPeriode = " WHERE DATE(collections.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }else{
        $sqlPeriode = " WHERE DATE(collections.`TanggalPengadaan`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }

        if (isset($_POST['users'])) {
            foreach ($_POST['users'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        } 
      //Untuk Header Laporan berdasarkan Periode yng dipilih

        $sql = "SELECT deposit_ws.`nama_penerbit` AS penertbit,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 deposit_ws.`kabupaten` AS kota,
                 deposit_ws.`email` AS email,
                 worksheets.`Name` AS jenis_koleksi,
                 collectionmedias.`KodeBahanPustaka` AS kode,
                 catalogs.`Title` AS judul,
                 catalogs.`Author` AS pengarang,
                 catalogs.`ISBN` AS isbn,
                 collections.`NomorDeposit` AS no_deposit,
                 catalogs.`Edition` AS edisi,
                 collections.`EDISISERIAL` AS eds_serial,
                 collections.`TANGGAL_TERBIT_EDISI_SERIAL` AS tgl_eds_serial,
                 collections.`ThnTerbitDeposit` AS thn_terbit,
                 collections.`TanggalPengadaan` AS thn_penerimaan,
                 collections.`CreateDate` AS tgl_buat
                 FROM deposit_ws
                 LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                 LEFT JOIN users ON collections.`CreateBy` = users.`ID`
                 LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                 LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                 LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                ";   
        $sql .= $sqlPeriode;
        $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Deposit_wajib_serah.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="17">'.yii::t('app','Daftar Jumlah koleksi per Wajib Serah').'</th>
            </tr>
             <tr>
                <th colspan="17">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Kota Terbit').'</th>
                <th>'.yii::t('app','Email').'</th>
                <th>'.yii::t('app','Jenis Koleksi').'</th>
                <th>'.yii::t('app','Kode Jenis').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','ISBN').'</th>
                <th>'.yii::t('app','Nomor Deposit').'</th>
                <th>'.yii::t('app','Edisi').'</th>
                <th>'.yii::t('app','Edisi Serial').'</th>
                <th>'.yii::t('app','Tanggal Edisi').'</th>
                <th>'.yii::t('app','Tahun Terbit').'</th>
                <th>'.yii::t('app','Tanggal Terima').'</th>
                <th>'.yii::t('app','Tanggal Buat').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['penertbit'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kota'].'</td>
                    <td>'.$data['email'].'</td>
                    <td>'.$data['jenis_koleksi'].'</td>
                    <td>'.$data['kode'].'</td>
                    <td>'.$data['judul'].'</td>
                    <td>'.$data['pengarang'].'</td>
                    <td>'.$data['isbn'].'</td>
                    <td>'.$data['no_deposit'].'</td>
                    <td>'.$data['edisi'].'</td>
                    <td>'.$data['eds_serial'].'</td>
                    <td>'.$data['tgl_eds_serial'].'</td>
                    <td>'.$data['thn_terbit'].'</td>
                    <td>'.$data['thn_penerimaan'].'</td>
                    <td>'.$data['tgl_buat'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportPdfDepositWajibSerahDetail() 
{

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;
    $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
    if ($_POST['kategori_tgl'] == 'dibuat') {
        $sqlPeriode = " WHERE DATE(collections.`CreateDate`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }else{
        $sqlPeriode = " WHERE DATE(collections.`TanggalPengadaan`) BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
    }

        if (isset($_POST['users'])) {
            foreach ($_POST['users'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        } 
      //Untuk Header Laporan berdasarkan Periode yng dipilih

        $sql = "SELECT deposit_ws.`nama_penerbit` AS penertbit,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 deposit_ws.`kabupaten` AS kota,
                 deposit_ws.`email` AS email,
                 worksheets.`Name` AS jenis_koleksi,
                 collectionmedias.`KodeBahanPustaka` AS kode,
                 catalogs.`Title` AS judul,
                 catalogs.`Author` AS pengarang,
                 catalogs.`ISBN` AS isbn,
                 collections.`NomorDeposit` AS no_deposit,
                 catalogs.`Edition` AS edisi,
                 collections.`EDISISERIAL` AS eds_serial,
                 collections.`TANGGAL_TERBIT_EDISI_SERIAL` AS tgl_eds_serial,
                 collections.`ThnTerbitDeposit` AS thn_terbit,
                 collections.`TanggalPengadaan` AS thn_penerimaan,
                 collections.`CreateDate` AS tgl_buat
                 FROM deposit_ws
                 LEFT JOIN collections ON collections.`deposit_ws_ID` = deposit_ws.`ID`
                 LEFT JOIN users ON collections.`CreateBy` = users.`ID`
                 LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                 LEFT JOIN worksheets ON worksheets.ID = catalogs.`Worksheet_id`
                 LEFT JOIN collectionmedias ON collectionmedias.`Worksheet_id` = worksheets.`ID`
                ";   
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
        'options' => [
        'title' => 'Laporan Frekuensi',
        'subject' => 'Perpustakaan Nasional Republik Indonesia'],
        ]);

    $pdf = $pdf->api; // fetches mpdf api
    $content = $this->renderPartial('pdf-view-deposit-wajib-serah-detail', $content);
    if ($content_kop['kop']) {
    $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
    }else{
    $pdf->SetHTMLHeader();
    }
    $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
    $pdf->WriteHtml($content);
    echo $pdf->Output('Laporan_Deposit_Wajib_serah_Detail.pdf', 'D');

}

//=====================================================================================================================================================

public function actionRenderLaporanDepositPenerbit() 
{

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                END AS alamat,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                ";   
        // $sql .= "AND collections.`deposit_ws_ID` IS NOT NULL";

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
        'content' => $this->renderPartial('pdf-view-deposit-penerbit', $content),
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

public function actionExportExcelDepositPenerbit()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                END AS alamat,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                "; 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $filename = 'Laporan_Deposit_Wajib_Serah_Detail.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="4">'.yii::t('app','Daftar Penerbit Seluruh Indonesia').'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Kota Terbit').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['nama_pen'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kota'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositPenerbit()
{
    // $model = Opaclogs::find()->All();

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                END AS alamat,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                "; 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'nama_pen'=> $model['nama_pen'],'alamat'=> $model['alamat'], 'kota'=>$model['kota']);
            // $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'daftar_jumlah_koleksi'=> yii::t('app','Daftar Jumlah koleksi per Wajib Serah Detail'),   
        'penerbit'=> yii::t('app','Nama Penerbit'),   
        'alamat'=> yii::t('app','Alamat'),   
        'kota_terbit'=> yii::t('app','Kota Terbit'),    
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-penerbit.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-deposit-jenis-koleksi.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositPenerbit()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                END AS alamat,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                "; 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Deposit_wajib_serah.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="4">'.yii::t('app','Daftar Penerbit Seluruh Indonesia').'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Kota Terbit').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['nama_pen'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kota'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportPdfDepositPenerbit() 
{

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    // print_r($_POST['users']);die;

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                END AS alamat,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                "; 

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
        'options' => [
        'title' => 'Laporan Frekuensi',
        'subject' => 'Perpustakaan Nasional Republik Indonesia'],
        ]);

    $pdf = $pdf->api; // fetches mpdf api
    $content = $this->renderPartial('pdf-view-deposit-penerbit', $content);
    if ($content_kop['kop']) {
    $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
    }else{
    $pdf->SetHTMLHeader();
    }
    $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
    $pdf->WriteHtml($content);
    echo $pdf->Output('Laporan_Deposit_Penerbit.pdf', 'D');

}

//=====================================================================================================================================================

public function actionRenderLaporanDepositPenerbitWilayah() 
{

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';

    // print_r($_POST['wilayah']);die;

        if (isset($_POST['wilayah'])) {
        foreach ($_POST['wilayah'] as $key => $value) {
            $Value[] .= "'".$value."'";
            if (!in_array('0',$_POST['wilayah'])) {
                $test = DepositKodeWilayah::find()->where(['deposit_kode_wilayah.ID'=>$_POST['wilayah']])->asArray()->All();
                $groupValue = array();
                    foreach ($test as $t => $tval) {
                        $groupValue[$t] = $tval['kode_wilayah'].' - '.$tval['nama_wilayah'];
                    }
                $VALUE['wilayah'] = $groupValue;
                }else{$VALUE['wilayah'] = array('Semua');}
            }
         if ($value != "0" ) { $andValue .= ' WHERE deposit_ws.ID_deposit_kode_wilayah = '.addslashes($value).' ';}
        }

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 CASE
                 WHEN deposit_ws.`no_telp1` != NULL OR deposit_ws.`no_telp1` != ''
                 THEN deposit_ws.`no_telp1`
                 WHEN deposit_ws.`no_telp2` != NULL OR deposit_ws.`no_telp2` != ''
                 THEN deposit_ws.`no_telp2`
                 ELSE deposit_ws.`no_telp3`
                 END AS tlp,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                LEFT JOIN deposit_kode_wilayah ON deposit_kode_wilayah.`ID` = deposit_ws.`ID_deposit_kode_wilayah`
                ";   
        $sql .= $andValue;

    $data = Yii::$app->db->createCommand($sql)->queryAll(); 

    $Berdasarkan = array();
    foreach ($VALUE as $key => $value) {
        $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
    }
    $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // echo"<pre>";
        // print_r($VALUE);
        // // print_r($VALUE);
        // // print_r($Berdasarkan);
        // echo"</pre>";
        // die;

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
        'content' => $this->renderPartial('pdf-view-deposit-penerbit-wilayah', $content),
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

public function actionExportExcelDepositPenerbitWilayah()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';

    // print_r($_POST['wilayah']);die;
        if (isset($_POST['wilayah'])) {
        foreach ($_POST['wilayah'] as $key => $value) {
            $Value[] .= "'".$value."'";
            if (!in_array('0',$_POST['wilayah'])) {
                $test = DepositKodeWilayah::find()->where(['deposit_kode_wilayah.ID'=>$_POST['wilayah']])->asArray()->All();
                $groupValue = array();
                    foreach ($test as $t => $tval) {
                        $groupValue[$t] = $tval['kode_wilayah'].' - '.$tval['nama_wilayah'];
                    }
                $VALUE['wilayah'] = $groupValue;
                }else{$VALUE['wilayah'] = array('Semua');}
            }
         if ($value != "0" ) { $andValue .= ' WHERE deposit_ws.ID_deposit_kode_wilayah = '.addslashes($value).' ';}
        }

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 CASE
                 WHEN deposit_ws.`no_telp1` != NULL OR deposit_ws.`no_telp1` != ''
                 THEN deposit_ws.`no_telp1`
                 WHEN deposit_ws.`no_telp2` != NULL OR deposit_ws.`no_telp2` != ''
                 THEN deposit_ws.`no_telp2`
                 ELSE deposit_ws.`no_telp3`
                 END AS tlp,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                LEFT JOIN deposit_kode_wilayah ON deposit_kode_wilayah.`ID` = deposit_ws.`ID_deposit_kode_wilayah`
                ";   
        $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $Berdasarkan = array();
    foreach ($VALUE as $key => $value) {
        $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
    }
    $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);


    $filename = 'Laporan_Deposit_Penerbit_wilayah.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="5">'.yii::t('app','Daftar Penerbit Wilayah ').$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Kota Terbit').'</th>
                <th>'.yii::t('app','Telpon').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['nama_pen'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kota'].'</td>
                    <td>'.$data['tlp'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositPenerbitWilayah()
{
    // $model = Opaclogs::find()->All();

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';

    // print_r($_POST['wilayah']);die;
        if (isset($_POST['wilayah'])) {
        foreach ($_POST['wilayah'] as $key => $value) {
            $Value[] .= "'".$value."'";
            if (!in_array('0',$_POST['wilayah'])) {
                $test = DepositKodeWilayah::find()->where(['deposit_kode_wilayah.ID'=>$_POST['wilayah']])->asArray()->All();
                $groupValue = array();
                    foreach ($test as $t => $tval) {
                        $groupValue[$t] = $tval['kode_wilayah'].' - '.$tval['nama_wilayah'];
                    }
                $VALUE['wilayah'] = $groupValue;
                }else{$VALUE['wilayah'] = array('Semua');}
            }
         if ($value != "0" ) { $andValue .= ' WHERE deposit_ws.ID_deposit_kode_wilayah = '.addslashes($value).' ';}
        }

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 CASE
                 WHEN deposit_ws.`no_telp1` != NULL OR deposit_ws.`no_telp1` != ''
                 THEN deposit_ws.`no_telp1`
                 WHEN deposit_ws.`no_telp2` != NULL OR deposit_ws.`no_telp2` != ''
                 THEN deposit_ws.`no_telp2`
                 ELSE deposit_ws.`no_telp3`
                 END AS tlp,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                LEFT JOIN deposit_kode_wilayah ON deposit_kode_wilayah.`ID` = deposit_ws.`ID_deposit_kode_wilayah`
                ";   
        $sql .= $andValue; 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

    $Berdasarkan = array();
    foreach ($VALUE as $key => $value) {
        $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
    }
    $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'nama_pen'=> $model['nama_pen'],'alamat'=> $model['alamat'], 'kota'=>$model['kota'], 'tlp'=>$model['tlp']);
            // $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'periode2'=>$periode2,
        'Berdasarkan'=>$Berdasarkan, 
        );
    $detail2[] = array(
        'daftar_jumlah_koleksi'=> yii::t('app','Daftar Penerbit Wilayah '),   
        'penerbit'=> yii::t('app','Nama Penerbit'),   
        'alamat'=> yii::t('app','Alamat'),   
        'kota_terbit'=> yii::t('app','Kota Terbit'),    
        'tlp'=> yii::t('app','Telpon'),    
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-penerbit-wilayah.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-deposit-jenis-koleksi.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositPenerbitWilayah()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';

    // print_r($_POST['wilayah']);die;
        if (isset($_POST['wilayah'])) {
        foreach ($_POST['wilayah'] as $key => $value) {
            $Value[] .= "'".$value."'";
            if (!in_array('0',$_POST['wilayah'])) {
                $test = DepositKodeWilayah::find()->where(['deposit_kode_wilayah.ID'=>$_POST['wilayah']])->asArray()->All();
                $groupValue = array();
                    foreach ($test as $t => $tval) {
                        $groupValue[$t] = $tval['kode_wilayah'].' - '.$tval['nama_wilayah'];
                    }
                $VALUE['wilayah'] = $groupValue;
                }else{$VALUE['wilayah'] = array('Semua');}
            }
         if ($value != "0" ) { $andValue .= ' WHERE deposit_ws.ID_deposit_kode_wilayah = '.addslashes($value).' ';}
        }

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 CASE
                 WHEN deposit_ws.`no_telp1` != NULL OR deposit_ws.`no_telp1` != ''
                 THEN deposit_ws.`no_telp1`
                 WHEN deposit_ws.`no_telp2` != NULL OR deposit_ws.`no_telp2` != ''
                 THEN deposit_ws.`no_telp2`
                 ELSE deposit_ws.`no_telp3`
                 END AS tlp,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                LEFT JOIN deposit_kode_wilayah ON deposit_kode_wilayah.`ID` = deposit_ws.`ID_deposit_kode_wilayah`
                ";   
        $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Deposit_Penerbit_wilayah.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="5">'.yii::t('app','Daftar Penerbit Wilayah ').$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Kota Terbit').'</th>
                <th>'.yii::t('app','Telpon').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['nama_pen'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kota'].'</td>
                    <td>'.$data['tlp'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportPdfDepositPenerbitWilayah() 
{

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';

    // print_r($_POST['wilayah']);die;
        if (isset($_POST['wilayah'])) {
        foreach ($_POST['wilayah'] as $key => $value) {
            $Value[] .= "'".$value."'";
            if (!in_array('0',$_POST['wilayah'])) {
                $test = DepositKodeWilayah::find()->where(['deposit_kode_wilayah.ID'=>$_POST['wilayah']])->asArray()->All();
                $groupValue = array();
                    foreach ($test as $t => $tval) {
                        $groupValue[$t] = $tval['kode_wilayah'].' - '.$tval['nama_wilayah'];
                    }
                $VALUE['wilayah'] = $groupValue;
                }else{$VALUE['wilayah'] = array('Semua');}
            }
         if ($value != "0" ) { $andValue .= ' WHERE deposit_ws.ID_deposit_kode_wilayah = '.addslashes($value).' ';}
        }

        $sql = "SELECT deposit_ws.`nama_penerbit` AS nama_pen,
                 CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                 END AS alamat,
                 CASE
                 WHEN deposit_ws.`no_telp1` != NULL OR deposit_ws.`no_telp1` != ''
                 THEN deposit_ws.`no_telp1`
                 WHEN deposit_ws.`no_telp2` != NULL OR deposit_ws.`no_telp2` != ''
                 THEN deposit_ws.`no_telp2`
                 ELSE deposit_ws.`no_telp3`
                 END AS tlp,
                deposit_ws.`kabupaten` AS kota
                FROM deposit_ws
                LEFT JOIN deposit_kode_wilayah ON deposit_kode_wilayah.`ID` = deposit_ws.`ID_deposit_kode_wilayah`
                ";   
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
        'options' => [
        'title' => 'Laporan Frekuensi',
        'subject' => 'Perpustakaan Nasional Republik Indonesia'],
        ]);

    $pdf = $pdf->api; // fetches mpdf api
    $content = $this->renderPartial('pdf-view-deposit-penerbit-wilayah', $content);
    if ($content_kop['kop']) {
    $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
    }else{
    $pdf->SetHTMLHeader();
    }
    $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
    $pdf->WriteHtml($content);
    echo $pdf->Output('Laporan_Deposit_Penerbit_Wilayah.pdf', 'D');

}

//=====================================================================================================================================================

public function actionRenderLaporanDepositTerimaKasih() 
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

    // print_r($_POST['jenis_pengirim']);die;

            if (isset($_POST['jenis_pengirim'])) {

            if ($_POST['jenis_pengirim'] != "" ) {
                $andValue .= " AND letter.TYPE_OF_DELIVERY  = '".$_POST['jenis_pengirim']."' ";
                }

            switch ($_POST['jenis_pengirim']) {
            case 'DL':
                $VALUE['jenis_pengirim'] = yii::t('app','Datang Langsung');
                break;
            
            case 'P':
                $VALUE['jenis_pengirim'] = yii::t('app','POS');
                break;

            default:
                $VALUE['jenis_pengirim'] = yii::t('app','Semua');
                break;
            }
        }


        $sql = "SELECT letter.LETTER_NUMBER_UT AS no_surat, 
                letter_detail.PUBLISHER AS penerbit, 
                letter_detail.PUBLISHER_ADDRESS AS almt_penerbit, 
                letter_detail.TITLE AS judul, 
                letter_detail.QUANTITY AS quantity, 
                letter_detail.COPY AS copy,
                letter.TYPE_OF_DELIVERY AS jns_pengirim
                FROM letter_detail
                LEFT JOIN letter ON letter.ID = letter_detail.LETTER_ID
                WHERE DATE(letter.CreateDate)
                ";   
        $sql .= $sqlPeriode;
        $sql .= $andValue;

    $data = Yii::$app->db->createCommand($sql)->queryAll(); 

    $Berdasarkan = array();
    foreach ($VALUE as $key => $value) {
        $Berdasarkan[] .= $this->getRealNameKriteria($key).' ('.$value.')';
    }
    $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // echo"<pre>";
        // // print_r($VALUE);
        // // // print_r($VALUE);
        // print_r($Berdasarkan);
        // echo"</pre>";
        // die;

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
        'content' => $this->renderPartial('pdf-view-deposit-terima-kasih', $content),
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

public function actionExportExcelDepositTerimaKasih()
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

// print_r($_POST['jenis_pengirim']);die;

        if (isset($_POST['jenis_pengirim'])) {

        if ($_POST['jenis_pengirim'] != "" ) {
            $andValue .= " AND letter.TYPE_OF_DELIVERY  = '".$_POST['jenis_pengirim']."' ";
            }

        switch ($_POST['jenis_pengirim']) {
        case 'DL':
            $VALUE['jenis_pengirim'] = yii::t('app','Datang Langsung');
            break;
        
        case 'P':
            $VALUE['jenis_pengirim'] = yii::t('app','POS');
            break;

        default:
            $VALUE['jenis_pengirim'] = yii::t('app','Semua');
            break;
        }
    }


    $sql = "SELECT letter.LETTER_NUMBER_UT AS no_surat, 
            letter_detail.PUBLISHER AS penerbit, 
            letter_detail.PUBLISHER_ADDRESS AS almt_penerbit, 
            letter_detail.TITLE AS judul, 
            letter_detail.QUANTITY AS quantity, 
            letter_detail.COPY AS copy,
            letter.TYPE_OF_DELIVERY AS jns_pengirim
            FROM letter_detail
            LEFT JOIN letter ON letter.ID = letter_detail.LETTER_ID
            WHERE DATE(letter.CreateDate)
            ";   
    $sql .= $sqlPeriode;
    $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $Berdasarkan = array();
    foreach ($VALUE as $key => $value) {
        $Berdasarkan[] .= $this->getRealNameKriteria($key).' ('.$value.')';
    }
    $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);


    $filename = 'Laporan_Deposit_Terima_Kasih.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="8">'.yii::t('app','Daftar Pengiriman Penerbit dan Pengusaha Rekaman Surat UT').$Berdasarkan.'</th>
            </tr>
            <tr>
                <th colspan="8">'.$Berdasarkan.'</th>
            </tr>
            <tr>
                <th colspan="8">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nomor Surat').'</th>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat Penerbit').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Copy').'</th>
                <th>'.yii::t('app','Jenis Pengiriman').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['no_surat'].'</td>
                    <td>'.$data['penerbit'].'</td>
                    <td>'.$data['almt_penerbit'].'</td>
                    <td>'.$data['judul'].'</td>
                    <td>'.$data['quantity'].'</td>
                    <td>'.$data['copy'].'</td>
                    <td>'.$data['jns_pengirim'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositTerimaKasih()
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

// print_r($_POST['jenis_pengirim']);die;

        if (isset($_POST['jenis_pengirim'])) {

        if ($_POST['jenis_pengirim'] != "" ) {
            $andValue .= " AND letter.TYPE_OF_DELIVERY  = '".$_POST['jenis_pengirim']."' ";
            }

        switch ($_POST['jenis_pengirim']) {
        case 'DL':
            $VALUE['jenis_pengirim'] = yii::t('app','Datang Langsung');
            break;
        
        case 'P':
            $VALUE['jenis_pengirim'] = yii::t('app','POS');
            break;

        default:
            $VALUE['jenis_pengirim'] = yii::t('app','Semua');
            break;
        }
    }


    $sql = "SELECT letter.LETTER_NUMBER_UT AS no_surat, 
            letter_detail.PUBLISHER AS penerbit, 
            letter_detail.PUBLISHER_ADDRESS AS almt_penerbit, 
            letter_detail.TITLE AS judul, 
            letter_detail.QUANTITY AS quantity, 
            letter_detail.COPY AS copy,
            letter.TYPE_OF_DELIVERY AS jns_pengirim
            FROM letter_detail
            LEFT JOIN letter ON letter.ID = letter_detail.LETTER_ID
            WHERE DATE(letter.CreateDate)
            ";   
    $sql .= $sqlPeriode;
    $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

    $Berdasarkan = array();
    foreach ($VALUE as $key => $value) {
        $Berdasarkan[] .= $this->getRealNameKriteria($key).' ('.$value.')';
    }
    $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'no_surat'=> $model['no_surat'],'penerbit'=> $model['penerbit'], 'almt_penerbit'=>$model['almt_penerbit'], 'judul'=>$model['judul'],
                         'quantity'=>$model['quantity'],'copy'=>$model['copy'],'jns_pengirim'=>$model['jns_pengirim']);
            // $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'periode2'=>$periode2,
        'Berdasarkan'=>$Berdasarkan, 
        );
    $detail2[] = array(
        'surat_UT'=> yii::t('app','Daftar Pengiriman Penerbit dan Pengusaha Rekaman Surat UT'),   
        'no_surat'=> yii::t('app','Nomor Surat'),   
        'penerbit'=> yii::t('app','Nama Penerbit'),   
        'alamat'=> yii::t('app','Alamat Penerbit'),   
        'judul'=> yii::t('app','Judul'),   
        'jum_judul'=> yii::t('app','Jumlah Judul'),    
        'jum_copy'=> yii::t('app','Jumlah Copy'),    
        'pengiriman'=> yii::t('app','Jenis Pengiriman'),    
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-terima-kasih.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-deposit-jenis-koleksi.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositTerimaKasih()
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

// print_r($_POST['jenis_pengirim']);die;

        if (isset($_POST['jenis_pengirim'])) {

        if ($_POST['jenis_pengirim'] != "" ) {
            $andValue .= " AND letter.TYPE_OF_DELIVERY  = '".$_POST['jenis_pengirim']."' ";
            }

        switch ($_POST['jenis_pengirim']) {
        case 'DL':
            $VALUE['jenis_pengirim'] = yii::t('app','Datang Langsung');
            break;
        
        case 'P':
            $VALUE['jenis_pengirim'] = yii::t('app','POS');
            break;

        default:
            $VALUE['jenis_pengirim'] = yii::t('app','Semua');
            break;
        }
    }


    $sql = "SELECT letter.LETTER_NUMBER_UT AS no_surat, 
            letter_detail.PUBLISHER AS penerbit, 
            letter_detail.PUBLISHER_ADDRESS AS almt_penerbit, 
            letter_detail.TITLE AS judul, 
            letter_detail.QUANTITY AS quantity, 
            letter_detail.COPY AS copy,
            letter.TYPE_OF_DELIVERY AS jns_pengirim
            FROM letter_detail
            LEFT JOIN letter ON letter.ID = letter_detail.LETTER_ID
            WHERE DATE(letter.CreateDate)
            ";   
    $sql .= $sqlPeriode;
    $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Deposit_Terima_Kasih.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="8">'.yii::t('app','Daftar Pengiriman Penerbit dan Pengusaha Rekaman Surat UT').$Berdasarkan.'</th>
            </tr>
            <tr>
                <th colspan="8">'.$Berdasarkan.'</th>
            </tr>
            <tr>
                <th colspan="8">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Nomor Surat').'</th>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Alamat Penerbit').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Copy').'</th>
                <th>'.yii::t('app','Jenis Pengiriman').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['no_surat'].'</td>
                    <td>'.$data['penerbit'].'</td>
                    <td>'.$data['almt_penerbit'].'</td>
                    <td>'.$data['judul'].'</td>
                    <td>'.$data['quantity'].'</td>
                    <td>'.$data['copy'].'</td>
                    <td>'.$data['jns_pengirim'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportPdfDepositTerimaKasih() 
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

// print_r($_POST['jenis_pengirim']);die;

        if (isset($_POST['jenis_pengirim'])) {

        if ($_POST['jenis_pengirim'] != "" ) {
            $andValue .= " AND letter.TYPE_OF_DELIVERY  = '".$_POST['jenis_pengirim']."' ";
            }

        switch ($_POST['jenis_pengirim']) {
        case 'DL':
            $VALUE['jenis_pengirim'] = yii::t('app','Datang Langsung');
            break;
        
        case 'P':
            $VALUE['jenis_pengirim'] = yii::t('app','POS');
            break;

        default:
            $VALUE['jenis_pengirim'] = yii::t('app','Semua');
            break;
        }
    }


    $sql = "SELECT letter.LETTER_NUMBER_UT AS no_surat, 
            letter_detail.PUBLISHER AS penerbit, 
            letter_detail.PUBLISHER_ADDRESS AS almt_penerbit, 
            letter_detail.TITLE AS judul, 
            letter_detail.QUANTITY AS quantity, 
            letter_detail.COPY AS copy,
            letter.TYPE_OF_DELIVERY AS jns_pengirim
            FROM letter_detail
            LEFT JOIN letter ON letter.ID = letter_detail.LETTER_ID
            WHERE DATE(letter.CreateDate)
            ";   
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
        'options' => [
        'title' => 'Laporan Frekuensi',
        'subject' => 'Perpustakaan Nasional Republik Indonesia'],
        ]);

    $pdf = $pdf->api; // fetches mpdf api
    $content = $this->renderPartial('pdf-view-deposit-terima-kasih', $content);
    if ($content_kop['kop']) {
    $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
    }else{
    $pdf->SetHTMLHeader();
    }
    $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
    $pdf->WriteHtml($content);
    echo $pdf->Output('Laporan_Deposit_Terima_Kasih.pdf', 'D');

}

//=====================================================================================================================================================

public function actionRenderLaporanDepositCardex() 
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
            else 
            {
                $periode = null;
            }
        }

    // print_r($_POST);die;

        if (isset($_POST['catalogs'])) {
            foreach ($_POST['catalogs'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.`Catalog_id`  = '".$value."' ";
                }
            }
        }


        $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
                collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
                catalogs.`Publisher` AS penerbit, 
                CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                END AS alamat,
                collections.`TanggalPengadaan` AS tgl_penerimaan, COUNT(collections.`CreateDate`) AS jum_eks
                FROM collections
                LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
                WHERE DATE(collections.`CreateDate`) ";   
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= 'GROUP BY DATE(collections.`CreateDate`), collections.`EDISISERIAL`';

    $data = Yii::$app->db->createCommand($sql)->queryAll(); 

    // $Berdasarkan = array();
    // foreach ($VALUE as $key => $value) {
    //     $Berdasarkan[] .= $this->getRealNameKriteria($key).' ('.$value.')';
    // }
    // $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // echo"<pre>";
        // // print_r($VALUE);
        // // // print_r($VALUE);
        // print_r($Berdasarkan);
        // echo"</pre>";
        // die;

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
        'content' => $this->renderPartial('pdf-view-deposit-cardex', $content),
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

public function actionExportExcelDepositCardex()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';
    

    if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            else 
            {
                $periode = null;
            }
        }

    // print_r($_POST);die;

        if (isset($_POST['catalogs'])) {
            foreach ($_POST['catalogs'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.`Catalog_id`  = '".$value."' ";
                }
            }
        }


// print_r($_POST['jenis_pengirim']);die;


    $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
                collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
                catalogs.`Publisher` AS penerbit, 
                CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                END AS alamat,
                collections.`TanggalPengadaan` AS tgl_penerimaan, COUNT(collections.`CreateDate`) AS jum_eks
                FROM collections
                LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
                WHERE DATE(collections.`CreateDate`) ";   
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= 'GROUP BY DATE(collections.`CreateDate`), collections.`EDISISERIAL`';  

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $filename = 'Laporan_Deposit_Cardex.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="8">'.yii::t('app','Laporan Cardex ').$_POST['periode'].'</th>
            </tr>
            <tr>
                <th colspan="8">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Edisi Serial').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Tanggal Terima').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tanggal'].'</td>
                    <td>'.$data['eds_serial'].'</td>
                    <td>'.$data['judul'].'</td>
                    <td>'.$data['penerbit'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['tgl_penerimaan'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositCardex()
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
            else 
            {
                $periode = null;
            }
        }

    // print_r($_POST);die;

        if (isset($_POST['catalogs'])) {
            foreach ($_POST['catalogs'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.`Catalog_id`  = '".$value."' ";
                }
            }
        }


    $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
            collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
            catalogs.`Publisher` AS penerbit, 
            CASE
             WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
             THEN deposit_ws.`alamat1`
             WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
             THEN deposit_ws.`alamat2`
             ELSE deposit_ws.`alamat3`
            END AS alamat,
            collections.`TanggalPengadaan` AS tgl_penerimaan, COUNT(collections.`CreateDate`) AS jum_eks
            FROM collections
            LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            WHERE DATE(collections.`CreateDate`) ";   
    $sql .= $sqlPeriode;
    $sql .= $andValue;
    $sql .= 'GROUP BY DATE(collections.`CreateDate`), collections.`EDISISERIAL`';  

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 


    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'tanggal'=> $model['tanggal'],'eds_serial'=> $model['eds_serial'], 'judul'=>$model['judul'], 'penerbit'=>$model['penerbit'],
                         'alamat'=>$model['alamat'],'tgl_penerimaan'=>$model['tgl_penerimaan'],'jum_eks'=>$model['jum_eks']);
            // $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'periode2'=>$periode2,
        'Berdasarkan'=>$Berdasarkan, 
        );
    $detail2[] = array(
        'cardex'=> yii::t('app','Laporan Cardex'),   
        'tgl'=> yii::t('app','Tanggal'),   
        'nmr_edisi_serial'=> yii::t('app','Nomor Edisi Serial'),   
        'judul'=> yii::t('app','Judul'),   
        'penerbit'=> yii::t('app','Penerbit'),   
        'alamat'=> yii::t('app','Alamat'),    
        'tgl_terima'=> yii::t('app','Tanggal Terima'),    
        'jmlh_eks'=> yii::t('app','Jumlah Eksemplar'),    
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-cardex.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-deposit-jenis-koleksi.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositCardex()
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
            else 
            {
                $periode = null;
            }
        }

    // print_r($_POST);die;

        if (isset($_POST['catalogs'])) {
            foreach ($_POST['catalogs'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.`Catalog_id`  = '".$value."' ";
                }
            }
        }


// print_r($_POST['jenis_pengirim']);die;


    $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
            collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
            catalogs.`Publisher` AS penerbit, 
            CASE
             WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
             THEN deposit_ws.`alamat1`
             WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
             THEN deposit_ws.`alamat2`
             ELSE deposit_ws.`alamat3`
            END AS alamat,
            collections.`TanggalPengadaan` AS tgl_penerimaan, COUNT(collections.`CreateDate`) AS jum_eks
            FROM collections
            LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            WHERE DATE(collections.`CreateDate`) ";   
    $sql .= $sqlPeriode;
    $sql .= $andValue;
    $sql .= 'GROUP BY DATE(collections.`CreateDate`), collections.`EDISISERIAL`'; 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Deposit_Terima_Kasih.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="8">'.yii::t('app','Laporan Cardex ').$_POST['periode'].'</th>
            </tr>
            <tr>
                <th colspan="8">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Edisi Serial').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Tanggal Terima').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tanggal'].'</td>
                    <td>'.$data['eds_serial'].'</td>
                    <td>'.$data['judul'].'</td>
                    <td>'.$data['penerbit'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['tgl_penerimaan'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportPdfDepositCardex() 
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
        else 
        {
            $periode = null;
        }
    }

    // print_r($_POST);die;

    if (isset($_POST['catalogs'])) {
        foreach ($_POST['catalogs'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND collections.`Catalog_id`  = '".$value."' ";
            }
        }
    }


// print_r($_POST['jenis_pengirim']);die;


    $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
            collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
            catalogs.`Publisher` AS penerbit, 
            CASE
             WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
             THEN deposit_ws.`alamat1`
             WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
             THEN deposit_ws.`alamat2`
             ELSE deposit_ws.`alamat3`
            END AS alamat,
            collections.`TanggalPengadaan` AS tgl_penerimaan, COUNT(collections.`CreateDate`) AS jum_eks
            FROM collections
            LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            WHERE DATE(collections.`CreateDate`) ";   
    $sql .= $sqlPeriode;
    $sql .= $andValue;
    $sql .= 'GROUP BY DATE(collections.`CreateDate`), collections.`EDISISERIAL`'; 

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
        'options' => [
        'title' => 'Laporan Frekuensi',
        'subject' => 'Perpustakaan Nasional Republik Indonesia'],
        ]);

    $pdf = $pdf->api; // fetches mpdf api
    $content = $this->renderPartial('pdf-view-deposit-cardex', $content);
    if ($content_kop['kop']) {
    $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
    }else{
    $pdf->SetHTMLHeader();
    }
    $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
    $pdf->WriteHtml($content);
    echo $pdf->Output('Laporan_Deposit_Cardex.pdf', 'D');

}

//=====================================================================================================================================================

public function actionRenderLaporanDepositSerial() 
{

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';
        $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['periode']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['periode']))."' ";


    // print_r($_POST['jenis_pengirim']);die;


        $sql = "SELECT
                temp.*
                FROM
                (SELECT 
                'Non Anggota IKAPI' AS nama,
                deposit_ws.`jenis_penerbit` AS jenis_penerbit,
                MONTHNAME(collections.`CreateDate`) AS bulan,
                COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
                COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
                COUNT(collections.`ID`) AS jum_eks,
                (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
                FROM collections
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
                LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
                LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
                WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '2' GROUP BY MONTH(collections.CreateDate)

                UNION ALL
                SELECT 
                'Anggota IKAPI' AS nama,
                deposit_ws.`jenis_penerbit` AS jenis_penerbit,
                MONTHNAME(collections.`CreateDate`) AS bulan,
                COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
                COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
                COUNT(collections.`ID`) AS jum_eks,
                (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
                FROM collections
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
                LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
                LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
                WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '1' GROUP BY MONTH(collections.CreateDate)

                UNION ALL
                SELECT 
                'Anggota SPS' AS nama,
                deposit_ws.`jenis_penerbit` AS jenis_penerbit,
                MONTHNAME(collections.`CreateDate`) AS bulan,
                COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
                COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
                COUNT(collections.`ID`) AS jum_eks,
                (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
                FROM collections
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
                LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
                LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
                WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '3' GROUP BY MONTH(collections.CreateDate)

                UNION ALL
                SELECT 
                'Non Anggota SPS' AS nama,
                deposit_ws.`jenis_penerbit` AS jenis_penerbit,
                MONTHNAME(collections.`CreateDate`) AS bulan,
                COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
                COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
                COUNT(collections.`ID`) AS jum_eks,
                (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
                FROM collections
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
                LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
                LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
                WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '4' GROUP BY MONTH(collections.CreateDate)

                UNION ALL
                SELECT 
                'Anggota ASIRI' AS nama,
                deposit_ws.`jenis_penerbit` AS jenis_penerbit,
                MONTHNAME(collections.`CreateDate`) AS bulan,
                COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
                COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
                COUNT(collections.`ID`) AS jum_eks,
                (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
                FROM collections
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
                LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
                LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
                WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '5' GROUP BY MONTH(collections.CreateDate)

                UNION ALL
                SELECT 
                'Non Anggota ASIRI' AS nama,
                deposit_ws.`jenis_penerbit` AS jenis_penerbit,
                MONTHNAME(collections.`CreateDate`) AS bulan,
                COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
                COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
                COUNT(collections.`ID`) AS jum_eks,
                (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
                FROM collections
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
                LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
                LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
                WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '6' GROUP BY MONTH(collections.CreateDate)
                ) temp ORDER BY temp.bulan, temp.nama
                ";   

    $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        // echo"<pre>";
        // // print_r($VALUE);
        // // // print_r($VALUE);
        // print_r($Berdasarkan);
        // echo"</pre>";
        // die;

    $content['LaporanKriteria'] = ""; 
    $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
    $content['TableLaporan'] = $data; 
    $content['LaporanPeriode'] = $periode;
    $content['LaporanPeriode2'] = $_POST['periode'];
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
        'content' => $this->renderPartial('pdf-view-deposit-serial', $content),
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

public function actionExportExcelDepositSerial()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';
    $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['periode']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['periode']))."' ";


// print_r($_POST['jenis_pengirim']);die;


    $sql = "SELECT
            temp.*
            FROM
            (SELECT 
            'Non Anggota IKAPI' AS nama,
            deposit_ws.`jenis_penerbit` AS jenis_penerbit,
            MONTHNAME(collections.`CreateDate`) AS bulan,
            COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
            COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
            COUNT(collections.`ID`) AS jum_eks,
            (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
            FROM collections
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
            LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
            WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '2' GROUP BY MONTH(collections.CreateDate)

            UNION ALL
            SELECT 
            'Anggota IKAPI' AS nama,
            deposit_ws.`jenis_penerbit` AS jenis_penerbit,
            MONTHNAME(collections.`CreateDate`) AS bulan,
            COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
            COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
            COUNT(collections.`ID`) AS jum_eks,
            (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
            FROM collections
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
            LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
            WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '1' GROUP BY MONTH(collections.CreateDate)

            UNION ALL
            SELECT 
            'Anggota SPS' AS nama,
            deposit_ws.`jenis_penerbit` AS jenis_penerbit,
            MONTHNAME(collections.`CreateDate`) AS bulan,
            COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
            COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
            COUNT(collections.`ID`) AS jum_eks,
            (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
            FROM collections
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
            LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
            WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '3' GROUP BY MONTH(collections.CreateDate)

            UNION ALL
            SELECT 
            'Non Anggota SPS' AS nama,
            deposit_ws.`jenis_penerbit` AS jenis_penerbit,
            MONTHNAME(collections.`CreateDate`) AS bulan,
            COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
            COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
            COUNT(collections.`ID`) AS jum_eks,
            (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
            FROM collections
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
            LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
            WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '4' GROUP BY MONTH(collections.CreateDate)

            UNION ALL
            SELECT 
            'Anggota ASIRI' AS nama,
            deposit_ws.`jenis_penerbit` AS jenis_penerbit,
            MONTHNAME(collections.`CreateDate`) AS bulan,
            COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
            COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
            COUNT(collections.`ID`) AS jum_eks,
            (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
            FROM collections
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
            LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
            WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '5' GROUP BY MONTH(collections.CreateDate)

            UNION ALL
            SELECT 
            'Non Anggota ASIRI' AS nama,
            deposit_ws.`jenis_penerbit` AS jenis_penerbit,
            MONTHNAME(collections.`CreateDate`) AS bulan,
            COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
            COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
            COUNT(collections.`ID`) AS jum_eks,
            (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
            FROM collections
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
            LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
            WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '6' GROUP BY MONTH(collections.CreateDate)
            ) temp ORDER BY temp.bulan, temp.nama
            ";   

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $filename = 'Laporan_Deposit_Serial.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="8">'.yii::t('app','Penerbit Surat Kabar Anggota dan Non Anggota SPS Seluruh Indonesia yang melaksanakan UU. No. 4 Th. 1990 pada tahun ').$_POST['periode'].'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Periode').'</th>
                <th>'.yii::t('app','Jenis Penerbit').'</th>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Jumlah Penerbit').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
                <th>'.yii::t('app','Jumlah Bulanan').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['bulan'].'</td>
                    <td>'.$data['jenis_penerbit'].'</td>
                    <td>'.$data['nama'].'</td>
                    <td>'.$data['jum_penerbit'].'</td>
                    <td>'.$data['jum_judul'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                    <td>'.$data['tambah'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositSerial()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';
    $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['periode']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['periode']))."' ";


// print_r($_POST['jenis_pengirim']);die;


    $sql = "SELECT
    temp.*
    FROM
    (SELECT 
    'Non Anggota IKAPI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '2' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Anggota IKAPI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '1' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Anggota SPS' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '3' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Non Anggota SPS' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '4' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Anggota ASIRI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '5' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Non Anggota ASIRI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '6' GROUP BY MONTH(collections.CreateDate)
    ) temp ORDER BY temp.bulan, temp.nama
    ";   

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $_POST['periode'];


    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'bulan'=> $model['bulan'],'jenis_penerbit'=> $model['jenis_penerbit'], 'nama'=>$model['nama'], 'jum_penerbit'=>$model['jum_penerbit'],
                         'jum_judul'=>$model['jum_judul'],'jum_eks'=>$model['jum_eks'],'tambah'=>$model['tambah']);
            // $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'periode2'=>$periode2,
        'Berdasarkan'=>$Berdasarkan, 
        );
    $detail2[] = array(
        'laporan_serial'=> yii::t('app','Penerbit Surat Kabar Anggota dan Non Anggota SPS Seluruh Indonesia yang melaksanakan UU. No. 4 Th. 1990 pada tahun '),   
        'periode'=> yii::t('app','Periode'),   
        'jensi_penerbit'=> yii::t('app','Jenis Penerbit'),   
        'nama'=> yii::t('app','Nama Penerbit'),   
        'jum_penerbit'=> yii::t('app','Jumlah Penerbit'),   
        'jum_judul'=> yii::t('app','Jumlah Judul'),    
        'jum_eks'=> yii::t('app','Jumlah Eksemplar'),    
        'jum_perbulan'=> yii::t('app','Jumlah Perbulan'),    
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-serial.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-deposit-serial.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositSerial()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';
    $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['periode']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['periode']))."' ";


// print_r($_POST['jenis_pengirim']);die;


    $sql = "SELECT
    temp.*
    FROM
    (SELECT 
    'Non Anggota IKAPI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '2' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Anggota IKAPI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '1' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Anggota SPS' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '3' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Non Anggota SPS' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '4' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Anggota ASIRI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '5' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Non Anggota ASIRI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '6' GROUP BY MONTH(collections.CreateDate)
    ) temp ORDER BY temp.bulan, temp.nama
    ";   

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Deposit_Serial.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="8">'.yii::t('app','Penerbit Surat Kabar Anggota dan Non Anggota SPS Seluruh Indonesia yang melaksanakan UU. No. 4 Th. 1990 pada tahun ').$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Periode').'</th>
                <th>'.yii::t('app','Jenis Penerbit').'</th>
                <th>'.yii::t('app','Nama Penerbit').'</th>
                <th>'.yii::t('app','Jumlah Penerbit').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
                <th>'.yii::t('app','Jumlah Perbulan').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['bulan'].'</td>
                    <td>'.$data['jenis_penerbit'].'</td>
                    <td>'.$data['nama'].'</td>
                    <td>'.$data['jum_penerbit'].'</td>
                    <td>'.$data['jum_judul'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                    <td>'.$data['tambah'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportPdfDepositSerial() 
{

    $_POST =  $_SESSION['Array_POST_Filter'];
    $andValue = '';
    $sqlPeriode = '';
    $subjek = '';
    $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['periode']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['periode']))."' ";


// print_r($_POST['jenis_pengirim']);die;


    $sql = "SELECT
    temp.*
    FROM
    (SELECT 
    'Non Anggota IKAPI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '2' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Anggota IKAPI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '1' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Anggota SPS' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '3' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Non Anggota SPS' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '4' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Anggota ASIRI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '5' GROUP BY MONTH(collections.CreateDate)

    UNION ALL
    SELECT 
    'Non Anggota ASIRI' AS nama,
    deposit_ws.`jenis_penerbit` AS jenis_penerbit,
    MONTHNAME(collections.`CreateDate`) AS bulan,
    COUNT(deposit_ws.`nama_penerbit`) AS jum_penerbit,
    COUNT(DISTINCT collections.Catalog_id) AS jum_judul,
    COUNT(collections.`ID`) AS jum_eks,
    (COUNT(deposit_ws.`nama_penerbit`)+COUNT(DISTINCT collections.Catalog_id)+COUNT(collections.`ID`)) AS tambah
    FROM collections
    LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
    LEFT JOIN deposit_group_ws ON deposit_group_ws.`id_group` = deposit_ws.`id_group_deposit_group_ws`
    LEFT JOIN deposit_kelompok_penerbit ON deposit_kelompok_penerbit.ID = deposit_ws.`id_deposit_kelompok_penerbit_ws`
    WHERE collections.`CreateDate` ".$sqlPeriode." AND deposit_kelompok_penerbit.ID = '6' GROUP BY MONTH(collections.CreateDate)
    ) temp ORDER BY temp.bulan, temp.nama
    ";

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
        'options' => [
        'title' => 'Laporan Frekuensi',
        'subject' => 'Perpustakaan Nasional Republik Indonesia'],
        ]);

    $pdf = $pdf->api; // fetches mpdf api
    $content = $this->renderPartial('pdf-view-deposit-serial', $content);
    if ($content_kop['kop']) {
    $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
    }else{
    $pdf->SetHTMLHeader();
    }
    $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
    $pdf->WriteHtml($content);
    echo $pdf->Output('Laporan_Deposit_Serial.pdf', 'D');

}

//=====================================================================================================================================================

public function actionRenderLaporanDepositAset() 
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
            else 
            {
                $periode = null;
            }
        }

        // print_r($_POST);die;

        if (isset($_POST['users'])) {
            foreach ($_POST['users'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= "AND collections.`CreateBy` = '".$value."' ";
                }
            }
        }


        $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
                collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
                CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
                ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
                ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
                ,' ',catalogs.PublishYear
                ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
                ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
                ,'<br/>',worksheets.name,'</div>'
                ) AS DataBib,
                catalogs.`Publisher` AS penerbit, 
                CASE
                 WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
                 THEN deposit_ws.`alamat1`
                 WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
                 THEN deposit_ws.`alamat2`
                 ELSE deposit_ws.`alamat3`
                END AS alamat,
                DATE_FORMAT(collections.`TanggalPengadaan`,'%d-%m-%Y') AS tgl_penerimaan, 
                temp.hitung AS jumlah_eks,
                collections.`Currency` AS mata_uang,
                collections.`Price` AS harga,
                deposit_taksiran_harga.`cover` AS kulit_muka_buku,
                deposit_taksiran_harga.`muka_buku` AS finishing_kulit_muka_bku,
                deposit_taksiran_harga.`hard_cover` AS bentuk_finishing_hard_cover,
                deposit_taksiran_harga.`penjilidan` AS punggung_buku,
                deposit_taksiran_harga.`jumlah_halaman` AS jum_halaman,
                deposit_taksiran_harga.`jenis_kertas_buku` AS jenis_kerts_buku,
                deposit_taksiran_harga.`ukuran_buku` AS ukuran_bku,
                deposit_taksiran_harga.`kondisi_buku` AS kondsi_bku,
                deposit_taksiran_harga.`kondisi_usang` AS kondsi_usang,
                deposit_taksiran_harga.`full_color` AS fullclor
                FROM collections
                LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
                LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
                LEFT JOIN worksheets ON worksheets.id = catalogs.Worksheet_id
                LEFT JOIN deposit_taksiran_harga ON deposit_taksiran_harga.`ID_collections` = collections.`ID`
                LEFT JOIN (SELECT collections.`NomorDeposit` AS nmr_dep, collections.`Catalog_id` AS cat_id, COUNT(collections.`CreateDate`)AS hitung FROM collections GROUP BY collections.`Catalog_id`, DATE(collections.`CreateDate`)) AS temp ON temp.cat_id = collections.`Catalog_id` AND temp.nmr_dep = collections.`NomorDeposit` 
                WHERE DATE(collections.`CreateDate`) ";   
        $sql .= $sqlPeriode;
        $sql .= $andValue;

    $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        // echo"<pre>";
        // // print_r($VALUE);
        // // // print_r($VALUE);
        // print_r($Berdasarkan);
        // echo"</pre>";
        // die;

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
        'content' => $this->renderPartial('pdf-view-deposit-asset', $content),
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

public function actionExportExcelDepositAset()
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
        else 
        {
            $periode = null;
        }
    }

    // print_r($_POST);die;

    if (isset($_POST['users'])) {
        foreach ($_POST['users'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= "AND collections.`CreateBy` = '".$value."' ";
            }
        }
    }


    $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
            collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
            CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
            ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
            ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
            ,' ',catalogs.PublishYear
            ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
            ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
            ,'<br/>',worksheets.name,'</div>'
            ) AS DataBib,
            catalogs.`Publisher` AS penerbit, 
            CASE
             WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
             THEN deposit_ws.`alamat1`
             WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
             THEN deposit_ws.`alamat2`
             ELSE deposit_ws.`alamat3`
            END AS alamat,
            DATE_FORMAT(collections.`TanggalPengadaan`,'%d-%m-%Y') AS tgl_penerimaan, 
            temp.hitung AS jumlah_eks,
            collections.`Currency` AS mata_uang,
            collections.`Price` AS harga,
            deposit_taksiran_harga.`cover` AS kulit_muka_buku,
            deposit_taksiran_harga.`muka_buku` AS finishing_kulit_muka_bku,
            deposit_taksiran_harga.`hard_cover` AS bentuk_finishing_hard_cover,
            deposit_taksiran_harga.`penjilidan` AS punggung_buku,
            deposit_taksiran_harga.`jumlah_halaman` AS jum_halaman,
            deposit_taksiran_harga.`jenis_kertas_buku` AS jenis_kerts_buku,
            deposit_taksiran_harga.`ukuran_buku` AS ukuran_bku,
            deposit_taksiran_harga.`kondisi_buku` AS kondsi_bku,
            deposit_taksiran_harga.`kondisi_usang` AS kondsi_usang,
            deposit_taksiran_harga.`full_color` AS fullclor
            FROM collections
            LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN worksheets ON worksheets.id = catalogs.Worksheet_id
            LEFT JOIN deposit_taksiran_harga ON deposit_taksiran_harga.`ID_collections` = collections.`ID`
            LEFT JOIN (SELECT collections.`NomorDeposit` AS nmr_dep, collections.`Catalog_id` AS cat_id, COUNT(collections.`CreateDate`)AS hitung FROM collections GROUP BY collections.`Catalog_id`, DATE(collections.`CreateDate`)) AS temp ON temp.cat_id = collections.`Catalog_id` AND temp.nmr_dep = collections.`NomorDeposit` 
            WHERE DATE(collections.`CreateDate`) ";   
    $sql .= $sqlPeriode;
    $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $filename = 'Laporan_Deposit_Aset.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="19">'.yii::t('app','Laporan Aset  ').$_POST['periode'].'</th>
            </tr>
            <tr>
                <th colspan="19">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Tanggal Penerimaan').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
                <th>'.yii::t('app','Mata Uang').'</th>
                <th>'.yii::t('app','Harga').'</th>
                <th>'.yii::t('app','Kuit Muka Buku (cover)').'</th>
                <th>'.yii::t('app','Finishing Kulit Muka Buku').'</th>
                <th>'.yii::t('app','Bentuk Finishing Hard Cover').'</th>
                <th>'.yii::t('app','Punggung Buku Penjilidan').'</th>
                <th>'.yii::t('app','Jumlah Halaman').'</th>
                <th>'.yii::t('app','Jenis Kertas Buku').'</th>
                <th>'.yii::t('app','Ukuran Buku').'</th>
                <th>'.yii::t('app','Kondisi Buku').'</th>
                <th>'.yii::t('app','Kondisi Usang').'</th>
                <th>'.yii::t('app','Full Color').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tanggal'].'</td>
                    <td>'.$data['judul'].'</td>
                    <td>'.$data['penerbit'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['tgl_penerimaan'].'</td>
                    <td>'.$data['jumlah_eks'].'</td>
                    <td>'.$data['mata_uang'].'</td>
                    <td>'.$data['harga'].'</td>
                    <td>'.$data['kulit_muka_buku'].'</td>
                    <td>'.$data['finishing_kulit_muka_bku'].'</td>
                    <td>'.$data['bentuk_finishing_hard_cover'].'</td>
                    <td>'.$data['punggung_buku'].'</td>
                    <td>'.$data['jum_halaman'].'</td>
                    <td>'.$data['jenis_kerts_buku'].'</td>
                    <td>'.$data['ukuran_bku'].'</td>
                    <td>'.$data['kondsi_bku'].'</td>
                    <td>'.$data['kondsi_usang'].'</td>
                    <td>'.$data['fullclor'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDepositAset()
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
        else 
        {
            $periode = null;
        }
    }

    // print_r($_POST);die;

    if (isset($_POST['users'])) {
        foreach ($_POST['users'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= "AND collections.`CreateBy` = '".$value."' ";
            }
        }
    }


    $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
            collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
            CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
            ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
            ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
            ,' ',catalogs.PublishYear
            ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
            ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
            ,'<br/>',worksheets.name,'</div>'
            ) AS DataBib,
            catalogs.`Publisher` AS penerbit, 
            CASE
             WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
             THEN deposit_ws.`alamat1`
             WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
             THEN deposit_ws.`alamat2`
             ELSE deposit_ws.`alamat3`
            END AS alamat,
            DATE_FORMAT(collections.`TanggalPengadaan`,'%d-%m-%Y') AS tgl_penerimaan, 
            temp.hitung AS jumlah_eks,
            collections.`Currency` AS mata_uang,
            collections.`Price` AS harga,
            deposit_taksiran_harga.`cover` AS kulit_muka_buku,
            deposit_taksiran_harga.`muka_buku` AS finishing_kulit_muka_bku,
            deposit_taksiran_harga.`hard_cover` AS bentuk_finishing_hard_cover,
            deposit_taksiran_harga.`penjilidan` AS punggung_buku,
            deposit_taksiran_harga.`jumlah_halaman` AS jum_halaman,
            deposit_taksiran_harga.`jenis_kertas_buku` AS jenis_kerts_buku,
            deposit_taksiran_harga.`ukuran_buku` AS ukuran_bku,
            deposit_taksiran_harga.`kondisi_buku` AS kondsi_bku,
            deposit_taksiran_harga.`kondisi_usang` AS kondsi_usang,
            deposit_taksiran_harga.`full_color` AS fullclor
            FROM collections
            LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN worksheets ON worksheets.id = catalogs.Worksheet_id
            LEFT JOIN deposit_taksiran_harga ON deposit_taksiran_harga.`ID_collections` = collections.`ID`
            LEFT JOIN (SELECT collections.`NomorDeposit` AS nmr_dep, collections.`Catalog_id` AS cat_id, COUNT(collections.`CreateDate`)AS hitung FROM collections GROUP BY collections.`Catalog_id`, DATE(collections.`CreateDate`)) AS temp ON temp.cat_id = collections.`Catalog_id` AND temp.nmr_dep = collections.`NomorDeposit` 
            WHERE DATE(collections.`CreateDate`) ";   
    $sql .= $sqlPeriode;
    $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;


    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'tanggal'=> $model['tanggal'],'judul'=> $model['judul'], 'penerbit'=>$model['penerbit'], 'alamat'=>$model['alamat'],
                         'tgl_penerimaan'=>$model['tgl_penerimaan'],'jumlah_eks'=>$model['jumlah_eks'],'mata_uang'=>$model['mata_uang'],
                         'harga'=>$model['harga'],'kulit_muka_buku'=>$model['kulit_muka_buku'],'finishing_kulit_muka_bku'=>$model['finishing_kulit_muka_bku'],'bentuk_finishing_hard_cover'=>$model['bentuk_finishing_hard_cover'],
                         'punggung_buku'=>$model['punggung_buku'],'jum_halaman'=>$model['jum_halaman'],'jenis_kerts_buku'=>$model['jenis_kerts_buku'],
                         'ukuran_bku'=>$model['ukuran_bku'],'kondsi_bku'=>$model['kondsi_bku'],'kondsi_usang'=>$model['kondsi_usang'],'fullclor'=>$model['fullclor']);
            // $jum_sms = $jum_sms + $model['jum_sms'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'periode2'=>$periode2,
        'Berdasarkan'=>$Berdasarkan, 
        );
    $detail2[] = array(
        'surat_UT'=> yii::t('app','Laporan Aset'),   
        'tgl'=> yii::t('app','Tanggal'),   
        'judul'=> yii::t('app','Judul'),   
        'penerbit'=> yii::t('app','Penerbit'),   
        'alamat'=> yii::t('app','Alamat'),   
        'tgl_penerimaan'=> yii::t('app','Tanggal Penerimaan'),    
        'jum_eks'=> yii::t('app','Jumlah Eksemplar'),    
        'mta_uang'=> yii::t('app','Mata Uang'),    
        'harga'=> yii::t('app','Harga'),   
        'kulit_muka_bku'=> yii::t('app','Kulit Muka Buku (cover)'),   
        'fnish_kulit_ka_bku'=> yii::t('app','Finishing Kulit Muka Buku'),   
        'fnhs_hard_cover'=> yii::t('app','Bentuk Finishing Hard Cover'),   
        'pungng_penjilidan'=> yii::t('app','Punggung Buku Penjilidan'),   
        'jml_hal'=> yii::t('app','Jumlah Halaman'),   
        'jns_kertas_bku'=> yii::t('app','Jenis Kertas Buku'),   
        'ukran_bku'=> yii::t('app','Ukuran Buku'),   
        'kndisi_bku'=> yii::t('app','Kondisi Buku'),   
        'kndisi_usang'=> yii::t('app','Kondisi Usang'),   
        'full_clor'=> yii::t('app','Full Color'),   
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/deposit/laporan-deposit-aset.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-deposit-Aset.ods');
    // !Open Office Calc Area


}

public function actionExportWordDepositAset()
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
        else 
        {
            $periode = null;
        }
    }

    // print_r($_POST);die;

    if (isset($_POST['users'])) {
        foreach ($_POST['users'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= "AND collections.`CreateBy` = '".$value."' ";
            }
        }
    }


    $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
            collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
            CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
            ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
            ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
            ,' ',catalogs.PublishYear
            ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
            ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
            ,'<br/>',worksheets.name,'</div>'
            ) AS DataBib,
            catalogs.`Publisher` AS penerbit, 
            CASE
             WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
             THEN deposit_ws.`alamat1`
             WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
             THEN deposit_ws.`alamat2`
             ELSE deposit_ws.`alamat3`
            END AS alamat,
            DATE_FORMAT(collections.`TanggalPengadaan`,'%d-%m-%Y') AS tgl_penerimaan, 
            temp.hitung AS jumlah_eks,
            collections.`Currency` AS mata_uang,
            collections.`Price` AS harga,
            deposit_taksiran_harga.`cover` AS kulit_muka_buku,
            deposit_taksiran_harga.`muka_buku` AS finishing_kulit_muka_bku,
            deposit_taksiran_harga.`hard_cover` AS bentuk_finishing_hard_cover,
            deposit_taksiran_harga.`penjilidan` AS punggung_buku,
            deposit_taksiran_harga.`jumlah_halaman` AS jum_halaman,
            deposit_taksiran_harga.`jenis_kertas_buku` AS jenis_kerts_buku,
            deposit_taksiran_harga.`ukuran_buku` AS ukuran_bku,
            deposit_taksiran_harga.`kondisi_buku` AS kondsi_bku,
            deposit_taksiran_harga.`kondisi_usang` AS kondsi_usang,
            deposit_taksiran_harga.`full_color` AS fullclor
            FROM collections
            LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN worksheets ON worksheets.id = catalogs.Worksheet_id
            LEFT JOIN deposit_taksiran_harga ON deposit_taksiran_harga.`ID_collections` = collections.`ID`
            LEFT JOIN (SELECT collections.`NomorDeposit` AS nmr_dep, collections.`Catalog_id` AS cat_id, COUNT(collections.`CreateDate`)AS hitung FROM collections GROUP BY collections.`Catalog_id`, DATE(collections.`CreateDate`)) AS temp ON temp.cat_id = collections.`Catalog_id` AND temp.nmr_dep = collections.`NomorDeposit` 
            WHERE DATE(collections.`CreateDate`) ";   
    $sql .= $sqlPeriode;
    $sql .= $andValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

// $headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Deposit_Aset.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="19">'.yii::t('app','Laporan Aset  ').$_POST['periode'].'</th>
            </tr>
            <tr>
                <th colspan="19">'.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Tanggal Penerimaan').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
                <th>'.yii::t('app','Mata Uang').'</th>
                <th>'.yii::t('app','Harga').'</th>
                <th>'.yii::t('app','Kuit Muka Buku (cover)').'</th>
                <th>'.yii::t('app','Finishing Kulit Muka Buku').'</th>
                <th>'.yii::t('app','Bentuk Finishing Hard Cover').'</th>
                <th>'.yii::t('app','Punggung Buku Penjilidan').'</th>
                <th>'.yii::t('app','Jumlah Halaman').'</th>
                <th>'.yii::t('app','Jenis Kertas Buku').'</th>
                <th>'.yii::t('app','Ukuran Buku').'</th>
                <th>'.yii::t('app','Kondisi Buku').'</th>
                <th>'.yii::t('app','Kondisi Usang').'</th>
                <th>'.yii::t('app','Full Color').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPesan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tanggal'].'</td>
                    <td>'.$data['judul'].'</td>
                    <td>'.$data['penerbit'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['tgl_penerimaan'].'</td>
                    <td>'.$data['jumlah_eks'].'</td>
                    <td>'.$data['mata_uang'].'</td>
                    <td>'.$data['harga'].'</td>
                    <td>'.$data['kulit_muka_buku'].'</td>
                    <td>'.$data['finishing_kulit_muka_bku'].'</td>
                    <td>'.$data['bentuk_finishing_hard_cover'].'</td>
                    <td>'.$data['punggung_buku'].'</td>
                    <td>'.$data['jum_halaman'].'</td>
                    <td>'.$data['jenis_kerts_buku'].'</td>
                    <td>'.$data['ukuran_bku'].'</td>
                    <td>'.$data['kondsi_bku'].'</td>
                    <td>'.$data['kondsi_usang'].'</td>
                    <td>'.$data['fullclor'].'</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportPdfDepositAset() 
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
        else 
        {
            $periode = null;
        }
    }

    // print_r($_POST);die;

    if (isset($_POST['users'])) {
        foreach ($_POST['users'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= "AND collections.`CreateBy` = '".$value."' ";
            }
        }
    }


    $sql = "SELECT DATE_FORMAT(collections.`CreateDate`,'%d-%m-%Y') AS tanggal, 
            collections.`EDISISERIAL` AS eds_serial, catalogs.`Title` AS judul, 
            CONCAT('<div style=width:300px >','<b>',catalogs.Title,'</b>','<br/>'
            ,(CASE WHEN worksheets.ID <> 4 AND catalogs.edition IS NOT NULL AND NOT LENGTH(catalogs.edition) = 0 THEN CONCAT('<br/>',catalogs.edition) ELSE '' END)
            ,'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher
            ,' ',catalogs.PublishYear
            ,(CASE WHEN catalogs.PhysicalDescription IS NOT NULL THEN CONCAT('<br/>',catalogs.PhysicalDescription) ELSE '' END)
            ,(CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END)
            ,'<br/>',worksheets.name,'</div>'
            ) AS DataBib,
            catalogs.`Publisher` AS penerbit, 
            CASE
             WHEN deposit_ws.`alamat1` != NULL OR deposit_ws.`alamat1` != ''
             THEN deposit_ws.`alamat1`
             WHEN deposit_ws.`alamat2` != NULL OR deposit_ws.`alamat2` != ''
             THEN deposit_ws.`alamat2`
             ELSE deposit_ws.`alamat3`
            END AS alamat,
            DATE_FORMAT(collections.`TanggalPengadaan`,'%d-%m-%Y') AS tgl_penerimaan, 
            temp.hitung AS jumlah_eks,
            collections.`Currency` AS mata_uang,
            collections.`Price` AS harga,
            deposit_taksiran_harga.`cover` AS kulit_muka_buku,
            deposit_taksiran_harga.`muka_buku` AS finishing_kulit_muka_bku,
            deposit_taksiran_harga.`hard_cover` AS bentuk_finishing_hard_cover,
            deposit_taksiran_harga.`penjilidan` AS punggung_buku,
            deposit_taksiran_harga.`jumlah_halaman` AS jum_halaman,
            deposit_taksiran_harga.`jenis_kertas_buku` AS jenis_kerts_buku,
            deposit_taksiran_harga.`ukuran_buku` AS ukuran_bku,
            deposit_taksiran_harga.`kondisi_buku` AS kondsi_bku,
            deposit_taksiran_harga.`kondisi_usang` AS kondsi_usang,
            deposit_taksiran_harga.`full_color` AS fullclor
            FROM collections
            LEFT JOIN catalogs ON catalogs.`ID` = collections.`Catalog_id`
            LEFT JOIN deposit_ws ON deposit_ws.`ID` = collections.`deposit_ws_ID`
            LEFT JOIN worksheets ON worksheets.id = catalogs.Worksheet_id
            LEFT JOIN deposit_taksiran_harga ON deposit_taksiran_harga.`ID_collections` = collections.`ID`
            LEFT JOIN (SELECT collections.`NomorDeposit` AS nmr_dep, collections.`Catalog_id` AS cat_id, COUNT(collections.`CreateDate`)AS hitung FROM collections GROUP BY collections.`Catalog_id`, DATE(collections.`CreateDate`)) AS temp ON temp.cat_id = collections.`Catalog_id` AND temp.nmr_dep = collections.`NomorDeposit` 
            WHERE DATE(collections.`CreateDate`) ";   
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
        'options' => [
        'title' => 'Laporan Frekuensi',
        'subject' => 'Perpustakaan Nasional Republik Indonesia'],
        ]);

    $pdf = $pdf->api; // fetches mpdf api
    $content = $this->renderPartial('pdf-view-deposit-asset', $content);
    if ($content_kop['kop']) {
    $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
    }else{
    $pdf->SetHTMLHeader();
    }
    $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
    $pdf->WriteHtml($content);
    echo $pdf->Output('Laporan_Deposit_Terima_Kasih.pdf', 'D');

}

// ////////////////////////////////batas get_real_name///////////////////////////////////////////////// //
public function getRealNameKriteria($kriterias)
    {
        if ($kriterias == 'group') 
        {
            $name = 'Group Penerbit';
        } 
        elseif ($kriterias == 'peminjaman') 
        {
            $name = 'Tanggal Peminjaman';
        }
        elseif ($kriterias == 'jatuh_tempo') 
        {
            $name = 'Tanggal Jatuh Tempo';
        }
        elseif ($kriterias == 'jenis_pengirim') 
        {
            $name = 'Jenis Pengiririman';
        }    
        else 
        {
            $name = ' ';
        }
        
        return $name;

    }
}
