<?php

namespace backend\modules\laporan\controllers;


use Yii;
use yii\helpers\Url;
//Widget
use kartik\widgets\Select2;
use kartik\mpdf\Pdf;
use kartik\date\DatePicker;

//Helpers
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

//Models
use common\models\Opaclogs;


class OpacController extends \yii\web\Controller
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
    public function actionOpacLogdownload()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('opac-logdonwload',[
            'model' => $model,
            ]);
    }
    

    public function actionLoadFilterKriteria($kriteria)
{
        if ($kriteria == 'ip')
        {
            $sql = 'SELECT opaclogs.ip AS ip FROM opaclogs group by opaclogs.ip';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ip','ip');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        elseif ($kriteria == 'jenis_pencarian')
        {
            $sql = 'SELECT opaclogs.jenis_pencarian AS jenis_pencarian FROM opaclogs GROUP BY opaclogs.jenis_pencarian ';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'jenis_pencarian','jenis_pencarian');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        elseif ($kriteria == 'ruas_pencarian')
        {
            $sql = "SELECT TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) AS ruas_pencarian FROM opaclogs";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ruas_pencarian','ruas_pencarian');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        elseif ($kriteria == 'keyword')
        {
            $sql = "SELECT
                    CASE 
                      WHEN (opaclogs.keyword REGEXP '=') THEN TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1))
                      ELSE ''
                    END AS keyword
                    FROM opaclogs";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'keyword','keyword');
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        // elseif ($kriteria == 'kata_kunci')
        // {
        //     $sql = 'SELECT * FROM opaclogs';
        //     $data = Yii::$app->db->createCommand($sql)->queryAll(); 
        //     $options = ArrayHelper::map($data,'keyword','keyword');
        //     $options[0] = " ---Semua---";
        //     asort($options);
        //     $options = array_filter($options);

        //     $contentOptions = Html::dropDownList( $kriteria.'[]',
        //         'selected option',  
        //         $options, 
        //         ['class' => 'select2 col-sm-6',]
        //         );
        // }

          
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
        }
        elseif ($tampilkan == 'laporan-periodik-data')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-periodik-data').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );  
        }
        elseif ($tampilkan == 'export-excel')
        {            
            echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export').'">';
            echo "<iframe>";
        }
        elseif ($tampilkan == 'export-excel-data')
        {            
            echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-data').'">';
            echo "<iframe>";
        }
        elseif ($tampilkan == 'export-word')
        {            
            echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-word').'">';
            echo "<iframe>";
        }
        elseif ($tampilkan == 'export-word-data')
        {            
            echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-word-data').'">';
            echo "<iframe>";
        }
        elseif ($tampilkan == 'export-pdf')
        {            
            echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-pdf').'">';
            echo "<iframe>";
        }
        elseif ($tampilkan == 'export-pdf-data')
        {            
            echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-pdf-data').'">';
            echo "<iframe>";
        }
        elseif ($tampilkan == 'opac-logdownload-frekuensi') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('opac-logdownload-frekuensi').'">';
            echo "<iframe>";
        }
        elseif ($tampilkan == 'opac-logdownload-data') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('opac-logdownload-data').'">';
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
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
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

                
           $sql = "SELECT opaclogs.waktu TglAkses,
                    /*REPLACE(opaclogs.waktu,' ','<br/>') TglAkses */
                    opaclogs.IP AS ip, 
                    opaclogs.jenis_pencarian AS JenisPencarian, 
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) AS RuasPencarian, 
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) AS KataKunci, 
                    (SELECT COUNT(opaclogs.ID) FROM opaclogs WHERE DATE(opaclogs.waktu) ".$sqlPeriode. $andValue.") AS opac,
                    CASE 
                     WHEN (SELECT COUNT(opaclogs.ID) FROM opaclogs WHERE DATE(opaclogs.waktu) ".$sqlPeriode. $andValue.") > 200 THEN 'banyak'
                    END AS danger
                    FROM opaclogs WHERE DATE(opaclogs.waktu) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY opaclogs.waktu LIMIT 6000';

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

public function actionExportData()
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
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
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

        $sql = "SELECT opaclogs.waktu Periode,
                    /*REPLACE(opaclogs.waktu,' ','<br/>') Periode */
                    opaclogs.IP AS ip,
                    opaclogs.jenis_pencarian AS JenisPencarian,
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) AS RuasPencarian,
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) AS KataKunci 
                    FROM opaclogs 
                    WHERE DATE(opaclogs.waktu) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY opaclogs.waktu';

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $filename = 'Laporan_Periodik_Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="6">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Pemanfaatan OPAC').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Akses').'</th>
                <th>'.yii::t('app','IP Address').'</th>
                <th>'.yii::t('app','Jenis Pencarian').'</th>
                <th>'.yii::t('app','Ruas Pencarian').'</th>
                <th>'.yii::t('app','Kata Kunci').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['ip'].'</td>
                    <td>'.$data['JenisPencarian'].'</td>
                    <td>'.$data['RuasPencarian'].'</td>
                    <td>'.$data['KataKunci'].'</td>
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
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
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

            if (isset($_POST['ip'])) {
            foreach ($_POST['ip'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.IP = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.IP AS Subjek';
            }

            if (isset($_POST['jenis_pencarian'])) {
            foreach ($_POST['jenis_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.jenis_pencarian = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.jenis_pencarian AS Subjek';
            }

            if (isset($_POST['ruas_pencarian'])) {
            foreach ($_POST['ruas_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",1)) AS Subjek';
            }

            if (isset($_POST['keyword'])) {
            foreach ($_POST['keyword'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",-1)) AS Subjek';
            } 

    $sql = "SELECT opaclogs.waktu Periode,
                    /*REPLACE(opaclogs.waktu,' ','<br/>') Periode */
                    opaclogs.IP AS ip,
                    opaclogs.jenis_pencarian AS JenisPencarian,
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) AS RuasPencarian,
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) AS KataKunci 
                    FROM opaclogs 
                    WHERE DATE(opaclogs.waktu) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY opaclogs.waktu ';

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
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'ip'=>$model['ip'], 'JenisPencarian'=>$model['JenisPencarian'], 'RuasPencarian'=>$model['RuasPencarian'], 'KataKunci'=>$model['KataKunci'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'pemanfaatan_OPAC'=> yii::t('app','Pemanfaatan OPAC'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_akses'=> yii::t('app','Tanggal Akses'),
        'IP_address'=> yii::t('app','IP Address'),
        'jenis_pencarian'=> yii::t('app','Jenis Pencarian'),
        'ruas_pencarian'=> yii::t('app','Ruas Pencarian'),
        'kata_kunci'=> yii::t('app','Kata Kunci'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/opac/laporan-OPAC-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-OPAC-data.ods');
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
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
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

        $sql = "SELECT opaclogs.waktu Periode,
                    /*REPLACE(opaclogs.waktu,' ','<br/>') Periode */
                    opaclogs.IP AS ip,
                    opaclogs.jenis_pencarian AS JenisPencarian,
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) AS RuasPencarian,
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) AS KataKunci 
                    FROM opaclogs 
                    WHERE DATE(opaclogs.waktu) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY opaclogs.waktu';

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Data.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center" width="100%"> 
            <tr>
                <th colspan="6">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Pemanfaatan OPAC').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    if ($type == 'doc') {
    echo '<table border="1" align="center">
            <tr>
                <th style="margin-right: 10px; margin-left: 10px;">No.</th>
                <th style="margin-right: 20px; margin-left: 20px;">'.yii::t('app','Tanggal Akses').'</th>
                <th style="margin-right: 20px; margin-left: 20px;">'.yii::t('app','IP Address').'</th>
                <th style="margin-right: 20px; margin-left: 20px;">'.yii::t('app','Jenis Pencarian').'</th>
                <th style="margin-right: 20px; margin-left: 20px;">'.yii::t('app','Ruas Pencarian').'</th>
                <th style="margin-right: 20px; margin-left: 20px;">'.yii::t('app','Kata Kunci').'</th>
            </tr>
            ';
    }else{
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Akses').'</th>
                <th>'.yii::t('app','IP Address').'</th>
                <th>'.yii::t('app','Jenis Pencarian').'</th>
                <th>'.yii::t('app','Ruas Pencarian').'</th>
                <th>'.yii::t('app','Kata Kunci').'</th>
            </tr>
            ';        
    }
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['ip'].'</td>
                    <td>'.$data['JenisPencarian'].'</td>
                    <td>'.$data['RuasPencarian'].'</td>
                    <td>'.$data['KataKunci'].'</td>
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
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") TglAkses';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($sqlPeriode);
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

            
            $sql = "SELECT opaclogs.waktu TglAkses,
                    /*REPLACE(opaclogs.waktu,' ','<br/>') TglAkses */
                    opaclogs.IP AS ip,
                    opaclogs.jenis_pencarian AS JenisPencarian,
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) AS RuasPencarian,
                    TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) AS KataKunci 
                    FROM opaclogs 
                    WHERE DATE(opaclogs.waktu) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY opaclogs.waktu';

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
    

public function actionOpacLogdownloadData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            $inValue = $_POST['rank'];
            $sql = "SELECT ".$periode_format.",
                    catalogs.Title AS cat_titl,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>
                    ',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>
                    ',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>
                    ',EDISISERIAL) ELSE '' END) END),'<br/>
                    ',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>
                    ',IFNULL(catalogs.Subject,''),'
                    ',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.FullName AS nama 
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    LEFT JOIN members ON logsdownload.User_id = members.ID 
                    WHERE DATE(logsdownload.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;

        
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        //$content['Berdasarkan'] =  $Berdasarkan;  
        $content['inValue'] = $inValue;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
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
            'content' => $this->renderPartial('pdf-opac-logdownload-data', $content),
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=> $header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }


public function actionExportExcelOpacLogdownloadData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            $inValue = $_POST['rank'];
            $sql = "SELECT ".$periode_format.",
                    catalogs.Title AS cat_titl,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>
                    ',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>
                    ',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>
                    ',EDISISERIAL) ELSE '' END) END),'<br/>
                    ',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>
                    ',IFNULL(catalogs.Subject,''),'
                    ',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.FullName AS nama 
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    LEFT JOIN members ON logsdownload.User_id = members.ID 
                    WHERE DATE(logsdownload.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $filename = 'Laporan_Periodik_Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="4">'.yii::t('app','Laporan Deatil Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','OPAC Log Download').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Baca').'</th>
                <th>'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Nama').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tgl_baca'].'</td>
                    <td>'.$data['DataBib'].'</td>
                    <td>'.$data['nama'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}


public function actionExportExcelOdtOpacLogdownloadData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;
            $inValue = $_POST['rank'];
            $sql = "SELECT ".$periode_format.",
                    catalogs.Title AS cat_titl,
                    CONCAT('',catalogs.Title,'','') AS data, 
                    (CASE 
                     WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 
                     THEN CONCAT('',catalogs.Edition) 
                     ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('',EDISISERIAL) ELSE '' END) 
                    END) AS data2, 
                     CONCAT('',catalogs.PublishLocation,' ') AS data3, 
                     catalogs.Publisher AS data4, 
                     CONCAT(' ',catalogs.PublishYear,'') AS data5, 
                     CONCAT(catalogs.Subject, '') AS data6, 
                     catalogs.DeweyNo AS data7,
                    members.FullName AS nama
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    LEFT JOIN members ON logsdownload.User_id = members.ID 
                    WHERE DATE(logsdownload.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = $inValue;

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'tgl_baca'=> $model['tgl_baca'],'NoInduk'=> $model['NoInduk'], 'data'=>$model['data'], 'data2'=>$model['data2'], 'data3'=>$model['data3']
                         , 'data4'=>$model['data4'], 'data5'=>$model['data5'], 'data6'=>$model['data6'], 'data7'=>$model['data7'], 'NoAnggota'=>$model['NoAnggota'], 'nama'=>$model['nama'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'koleksi_opaclogdownload'=> yii::t('app','OPAC Log Download'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal_baca'=> yii::t('app','Tanggal Baca'),
        'nomor_induk'=> yii::t('app','Nomor Induk'),
        'data_bibliografis'=> yii::t('app','Data Bibliografis'),
        'nomor_anggotakunjungan'=> yii::t('app','Nomor Anggota / Kunjungan'),
        'nama'=> yii::t('app','Nama'),
        
        );
// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS 
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/opac/laporan-opac-logdownload-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Baca-ditempat-data.ods');
    // !Open Office Calc Area


}


public function actionExportWordOpacLogdownloadData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            $inValue = $_POST['rank'];
            if($_GET['type'] != 'odt'){
                $sql = "SELECT ".$periode_format.",
                    catalogs.Title AS cat_titl,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>
                    ',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>
                    ',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>
                    ',EDISISERIAL) ELSE '' END) END),'<br/>
                    ',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>
                    ',IFNULL(catalogs.Subject,''),'
                    ',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.FullName AS nama 
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    LEFT JOIN members ON logsdownload.User_id = members.ID 
                    WHERE DATE(logsdownload.waktu) ";     
            }else{
                $sql = "SELECT ".$periode_format.",
                    catalogs.Title AS cat_titl,
                    CONCAT('<b>',catalogs.Title,'</b>','&#10
                    ',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('&#10
                    ',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('&#10
                    ',EDISISERIAL) ELSE '' END) END),'&#10
                    ',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'&#10
                    ',IFNULL(catalogs.Subject,''),'
                    ',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.FullName AS nama 
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    LEFT JOIN members ON logsdownload.User_id = members.ID 
                    WHERE DATE(logsdownload.waktu) ";     
            }       
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Data.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center" width="700"> 
            <tr>
                <th colspan="4">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','OPAC Log Download').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tgl. Baca').'</th>
                <th>'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Nama').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tgl_baca'].'</td>
                    <td style="text-align: left;">'.$data['DataBib'].'</td>
                    <td>'.$data['nama'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}


public function actionExportPdfOpacLogdownloadData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload.waktu),";
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            $inValue = $_POST['rank'];
            $sql = "SELECT ".$periode_format.",
                    catalogs.Title AS cat_titl,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>
                    ',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>
                    ',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>
                    ',EDISISERIAL) ELSE '' END) END),'<br/>
                    ',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>
                    ',IFNULL(catalogs.Subject,''),'
                    ',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.FullName AS nama 
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    LEFT JOIN members ON logsdownload.User_id = members.ID 
                    WHERE DATE(logsdownload.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;

        
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        //$content['Berdasarkan'] =  $Berdasarkan;  
        $content['inValue'] = $inValue;
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
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
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-opac-logdownload-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

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
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%Y") Periode';
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
            $subjek = 'opaclogs.IP AS Subjek';
            }

            if (isset($_POST['jenis_pencarian'])) {
            foreach ($_POST['jenis_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.jenis_pencarian = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.jenis_pencarian AS Subjek';
            }

            if (isset($_POST['ruas_pencarian'])) {
            foreach ($_POST['ruas_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",1)) AS Subjek';
            }

            if (isset($_POST['keyword'])) {
            foreach ($_POST['keyword'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",-1)) AS Subjek';
            }          
    

            // if ($a = sizeof($_POST['kriterias'])!=1) {
            //     $subjek .= 'opaclogs.IP AS Subjek';
            //     }
            // $a = sizeof($_POST['kriterias']);
            // print_r($a);
            // die;
        $sql = "SELECT ".$periode_format.",
                ".$subjek.",
                COUNT(DISTINCT opaclogs.ip) AS JumlahTerminalKomputer, 
                COUNT(*) AS JumlahPencarian,
                (SELECT COUNT(opaclogs.ID) FROM opaclogs WHERE DATE(opaclogs.waktu) ".$sqlPeriode.$andValue.") AS opac,
                (SELECT COUNT(DISTINCT opaclogs.ip) FROM opaclogs WHERE DATE(opaclogs.waktu) ".$sqlPeriode.$andValue.") AS opac2
                FROM opaclogs  
                WHERE DATE(opaclogs.waktu) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(opaclogs.waktu,'%d-%m-%Y'), opaclogs.ip ORDER BY opaclogs.waktu LIMIT 200";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu LIMIT 200";
                } else {
                    $sql .= " GROUP BY YEAR(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu LIMIT 200";
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

        // print_r($TableLaporan['opac']);
        // die;
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

public function actionExport()
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
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%Y") Periode';
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

            if (isset($_POST['ip'])) {
            foreach ($_POST['ip'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.IP = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.IP AS Subjek';
            }

            if (isset($_POST['jenis_pencarian'])) {
            foreach ($_POST['jenis_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.jenis_pencarian = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.jenis_pencarian AS Subjek';
            }

            if (isset($_POST['ruas_pencarian'])) {
            foreach ($_POST['ruas_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",1)) AS Subjek';
            }

            if (isset($_POST['keyword'])) {
            foreach ($_POST['keyword'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",-1)) AS Subjek';
            } 

    $sql = "SELECT ".$periode_format.",
            ".$subjek.",
            COUNT(DISTINCT opaclogs.ip) AS JumlahTerminalKomputer, 
            COUNT(*) AS JumlahPencarian 
            FROM opaclogs  
            WHERE DATE(opaclogs.waktu) ";  

    $sql .= $sqlPeriode ; 
    $sql .= $andValue ; 

    if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(opaclogs.waktu,'%d-%m-%Y'), opaclogs.ip ORDER BY opaclogs.waktu ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu";
                } else {
                    $sql .= " GROUP BY YEAR(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;
// $test = self::getRealNameKriteria($kriterias);

    $filename = 'Laporan_Periodik_Frekuensi.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="5">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Pemanfaatan OPAC').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<th>'.yii::t('app',$Berdasarkan).'</th>'; }
    echo'
                <th>'.yii::t('app','Jumlah Terminal Komputer').'</th>
                <th>'.yii::t('app','Jumlah Pencarian').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahTerminalKomputer = 0;
        $JumlahPencarian = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<td>'.$data['Subjek'].'</td>'; }
    echo'
                    <td>'.$data['JumlahTerminalKomputer'].'</td>
                    <td>'.$data['JumlahPencarian'].'</td>
                </tr>
            ';
                        $JumlahTerminalKomputer = $JumlahTerminalKomputer + $data['JumlahTerminalKomputer'];
                        $JumlahPencarian = $JumlahPencarian + $data['JumlahPencarian'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahTerminalKomputer.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahPencarian.'
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
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%Y") Periode';
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

            if (isset($_POST['ip'])) {
            foreach ($_POST['ip'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.IP = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.IP AS Subjek';
            }

            if (isset($_POST['jenis_pencarian'])) {
            foreach ($_POST['jenis_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.jenis_pencarian = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.jenis_pencarian AS Subjek';
            }

            if (isset($_POST['ruas_pencarian'])) {
            foreach ($_POST['ruas_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",1)) AS Subjek';
            }

            if (isset($_POST['keyword'])) {
            foreach ($_POST['keyword'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",-1)) AS Subjek';
            } 

    $sql = "SELECT ".$periode_format.",
            ".$subjek.",
            COUNT(DISTINCT opaclogs.ip) AS JumlahTerminalKomputer, 
            COUNT(*) AS JumlahPencarian 
            FROM opaclogs  
            WHERE DATE(opaclogs.waktu) ";  

    $sql .= $sqlPeriode ; 
    $sql .= $andValue ; 

    if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(opaclogs.waktu,'%d-%m-%Y'), opaclogs.ip ORDER BY opaclogs.waktu";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu";
                } else {
                    $sql .= " GROUP BY YEAR(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area
    $menu = 'Pemanfaatan Opac';

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Subjek'=>$model['Subjek'], 'JumlahTerminalKomputer'=>$model['JumlahTerminalKomputer'], 'JumlahPencarian'=>$model['JumlahPencarian'] );
            $JumlahTerminalKomputer = $JumlahTerminalKomputer + $model['JumlahTerminalKomputer'];
            $JumlahPencarian = $JumlahPencarian + $model['JumlahPencarian'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlahTerminalKomputer'=>$JumlahTerminalKomputer,
        'TotalJumlahPencarian'=>$JumlahPencarian,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'pemanfaatan_OPAC'=> yii::t('app','Pemanfaatan OPAC'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal'=> yii::t('app','Tanggal'),
        'jumlah_terminalkomputer'=> yii::t('app','Jumlah Terminal Komputer'),
        'jumlah_pencarian'=> yii::t('app','Jumlah Pencarian'),     
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
if (sizeof($_POST['kriterias']) == 1) {
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/opac/laporan-OPAC-frekuensi.ods'; 
}else{
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/opac/laporan-OPAC-frekuensi_no_subjek.ods'; 
}

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
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%Y") Periode';
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

            if (isset($_POST['ip'])) {
            foreach ($_POST['ip'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.IP = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.IP AS Subjek';
            }

            if (isset($_POST['jenis_pencarian'])) {
            foreach ($_POST['jenis_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.jenis_pencarian = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.jenis_pencarian AS Subjek';
            }

            if (isset($_POST['ruas_pencarian'])) {
            foreach ($_POST['ruas_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",1)) AS Subjek';
            }

            if (isset($_POST['keyword'])) {
            foreach ($_POST['keyword'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",-1)) AS Subjek';
            } 

    $sql = "SELECT ".$periode_format.",
            ".$subjek.",
            COUNT(DISTINCT opaclogs.ip) AS JumlahTerminalKomputer, 
            COUNT(*) AS JumlahPencarian 
            FROM opaclogs  
            WHERE DATE(opaclogs.waktu) ";  

    $sql .= $sqlPeriode ; 
    $sql .= $andValue ; 

    if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(opaclogs.waktu,'%d-%m-%Y'), opaclogs.ip ORDER BY opaclogs.waktu";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu";
                } else {
                    $sql .= " GROUP BY YEAR(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $filename = 'Laporan_Periodik_Frekuensi.doc';
    header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="5">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Pemanfaatan OPAC').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<th>'.yii::t('app',$Berdasarkan).'</th>'; }
    echo'
                <th>'.yii::t('app','Jumlah Terminal Komputer').'</th>
                <th>'.yii::t('app','Jumlah Pencarian').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahTerminalKomputer = 0;
        $JumlahPencarian = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
            ';
            if (sizeof($_POST["kriterias"]) !=1) {
            }else
            { echo '<td>'.$data['Subjek'].'</td>'; }
            echo'
                    <td>'.$data['JumlahTerminalKomputer'].'</td>
                    <td>'.$data['JumlahPencarian'].'</td>
                </tr>
            ';
                        $JumlahTerminalKomputer = $JumlahTerminalKomputer + $data['JumlahTerminalKomputer'];
                        $JumlahPencarian = $JumlahPencarian + $data['JumlahPencarian'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahTerminalKomputer.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahPencarian.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdf()
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
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(opaclogs.waktu,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($sqlPeriode);
        // die;

            if (isset($_POST['ip'])) {
            foreach ($_POST['ip'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.IP = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.IP AS Subjek';
            }

            if (isset($_POST['jenis_pencarian'])) {
            foreach ($_POST['jenis_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND opaclogs.jenis_pencarian = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'opaclogs.jenis_pencarian AS Subjek';
            }

            if (isset($_POST['ruas_pencarian'])) {
            foreach ($_POST['ruas_pencarian'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",1)) AS Subjek';
            }

            if (isset($_POST['keyword'])) {
            foreach ($_POST['keyword'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND TRIM(SUBSTRING_INDEX(opaclogs.keyword,'=',-1)) = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'TRIM(SUBSTRING_INDEX(opaclogs.keyword,"=",-1)) AS Subjek';
            }           

            
            $sql = "SELECT ".$periode_format.",
                    ".$subjek.",
                    COUNT(DISTINCT opaclogs.ip) AS JumlahTerminalKomputer, 
                    COUNT(*) AS JumlahPencarian 
                    FROM opaclogs  
                    WHERE DATE(opaclogs.waktu) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(opaclogs.waktu,'%d-%m-%Y'), opaclogs.ip ORDER BY opaclogs.waktu";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu";
                } else {
                    $sql .= " GROUP BY YEAR(opaclogs.waktu), opaclogs.ip ORDER BY opaclogs.waktu";
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
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            $set = 55;
        } else {
            $set = 10;
        }
// print_r(Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png"));
// die;

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

public function actionOpacLogdownloadFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%Y") AS Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    COUNT(logsdownload.id) AS Jumlah,
                    catalogs.ID AS ids,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>') AS data,
                    (CASE 
                      WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 
                      THEN CONCAT('<br/>',catalogs.Edition) 
                      ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) 
                    END) AS data2,
                    CONCAT('<br/>',catalogs.PublishLocation,' ') AS data3,
                    catalogs.Publisher AS data4,
                    CONCAT(' ',catalogs.PublishYear,'<br/>') AS data5,
                    CONCAT(catalogs.Subject, '<br/>') AS data6,
                    catalogs.DeweyNo AS data7
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    WHERE DATE(logsdownload.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= 'AND logsdownload.isLKD = "0"';
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                }
             $sql .= $inValue;        


        $data = Yii::$app->db->createCommand($sql)->queryAll(); 


        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        //$content['Berdasarkan'] =  $Berdasarkan;  
        $content['inValue'] = $inValue;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
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
            'content' => $this->renderPartial('pdf-opac-logdownload-frekuensi', $content),
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=> $header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }

public function actionExportExcelOpacLogdownloadFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%Y") AS Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    COUNT(logsdownload.id) AS Jumlah,
                    catalogs.ID AS ids,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>') AS data,
                    (CASE 
                      WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 
                      THEN CONCAT('<br/>',catalogs.Edition) 
                      ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) 
                    END) AS data2,
                    CONCAT('<br/>',catalogs.PublishLocation,' ') AS data3,
                    catalogs.Publisher AS data4,
                    CONCAT(' ',catalogs.PublishYear,'<br/>') AS data5,
                    CONCAT(catalogs.Subject, '<br/>') AS data6,
                    catalogs.DeweyNo AS data7
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    WHERE DATE(logsdownload.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= 'AND logsdownload.isLKD = "0"';
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                }
             $sql .= $inValue;             

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

$headers = Yii::getAlias('@webroot','/teeeesst');
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
                <th colspan="6">'.yii::t('app','OPAC Log Download').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal').'</th>
                <th colspan="3">'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Jumlah Pembaca').'</th>
            </tr>
            ';
        $no = 1;
        $totalJumlahExemplar = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: middle;">'.$no.'</td>
                    <td style="vertical-align: middle;">'.$data['Periode'].'</td>
                    <td colspan="3" style="text-align: left;">'.$data['data'], $data['data2'], $data['data3'], $data['data4'], $data['data5'], $data['data6'], $data['data7'].'</td>
                    <td style="vertical-align: middle;">'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $totalJumlahExemplar = $totalJumlahExemplar + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="5" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahExemplar.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtOpacLogdownloadFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%Y") AS Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    COUNT(logsdownload.id) AS Jumlah,
                    catalogs.ID AS ids,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>') AS data,
                    (CASE 
                      WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 
                      THEN CONCAT('<br/>',catalogs.Edition) 
                      ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) 
                    END) AS data2,
                    CONCAT('<br/>',catalogs.PublishLocation,' ') AS data3,
                    catalogs.Publisher AS data4,
                    CONCAT(' ',catalogs.PublishYear,'<br/>') AS data5,
                    CONCAT(catalogs.Subject, '<br/>') AS data6,
                    catalogs.DeweyNo AS data7
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    WHERE DATE(logsdownload.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= 'AND logsdownload.isLKD = "0"';
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                }
             $sql .= $inValue;  

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = $inValue;

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Frekuensi'=>$model['Frekuensi'], 'data'=>$model['data'], 'data2'=>$model['data2'], 'data3'=>$model['data3']
                         , 'data4'=>$model['data4'], 'data5'=>$model['data5'], 'data6'=>$model['data6'], 'data7'=>$model['data7'] , 'Jumlah'=>$model['Jumlah'] );
        $JumlahFrekuensi = $JumlahFrekuensi + $model['Frekuensi'];
        $Jumlah = $Jumlah + $model['Jumlah'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlahFrekuensi'=>$JumlahFrekuensi,
        'TotalJumlah'=>$Jumlah,
        'sql'=>$sql,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'koleksi_opaclogdownload'=> yii::t('app','OPAC Log Download'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal'=> yii::t('app','Tanggal'),
        'data_bibliografis'=> yii::t('app','Data Bibliografis'),
        'jumlah_pembaca'=> yii::t('app','Jumlah Pembaca'),      
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/opac/laporan-OPAC-logdownload.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Baca-ditempat-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordOpacLogdownloadFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%Y") AS Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            $inValue = $_POST['rank'];
            if($_GET['type'] != 'odt'){
                $sql = "SELECT ".$periode_format.",
                        COUNT(logsdownload.id) AS Jumlah,
                        catalogs.ID AS ids,
                        CONCAT('<b>',catalogs.Title,'</b>','<br/>') AS data,
                        (CASE 
                          WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 
                          THEN CONCAT('<br/>',catalogs.Edition) 
                          ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) 
                        END) AS data2,
                        CONCAT('<br/>',catalogs.PublishLocation,' ') AS data3,
                        catalogs.Publisher AS data4,
                        CONCAT(' ',catalogs.PublishYear,'<br/>') AS data5,
                        CONCAT(catalogs.Subject, '<br/>') AS data6,
                        catalogs.DeweyNo AS data7
                        FROM logsdownload
                        LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                        LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                        LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                        WHERE DATE(logsdownload.waktu) ";  
            }else{
                $sql = "SELECT ".$periode_format.",
                        COUNT(logsdownload.id) AS Jumlah,
                        catalogs.ID AS ids,
                        CONCAT('<b>',catalogs.Title,'</b>','&#10;') AS data,
                        (CASE 
                          WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 
                          THEN CONCAT('&#10;',catalogs.Edition) 
                          ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('&#10;',EDISISERIAL) ELSE '' END) 
                        END) AS data2,
                        CONCAT('&#10;',catalogs.PublishLocation,' ') AS data3,
                        catalogs.Publisher AS data4,
                        CONCAT(' ',catalogs.PublishYear,'&#10;') AS data5,
                        CONCAT(catalogs.Subject, '&#10;') AS data6,
                        catalogs.DeweyNo AS data7
                        FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    WHERE DATE(logsdownload.waktu) ";    
            }  
             $sql .= $sqlPeriode;
             $sql .= 'AND logsdownload.isLKD = "0"';
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                }
             $sql .= $inValue;             
   

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center" width="700"> 
            <tr>
                <th colspan="5">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','OPAC Log Download').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Jumlah Pembaca').'</th>
            </tr>
            ';
        $no = 1;
        $totalJumlahExemplar = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td style="text-align: left;">'.$data['data'], $data['data2'], $data['data3'], $data['data4'], $data['data5'], $data['data6'], $data['data7'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $totalJumlahExemplar = $totalJumlahExemplar + $data['Jumlah'];
                        $no++;
        endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahExemplar.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfOpacLogdownloadFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload.waktu,"%Y") AS Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    COUNT(logsdownload.id) AS Jumlah,
                    catalogs.ID AS ids,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>') AS data,
                    (CASE 
                      WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 
                      THEN CONCAT('<br/>',catalogs.Edition) 
                      ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) 
                    END) AS data2,
                    CONCAT('<br/>',catalogs.PublishLocation,' ') AS data3,
                    catalogs.Publisher AS data4,
                    CONCAT(' ',catalogs.PublishYear,'<br/>') AS data5,
                    CONCAT(catalogs.Subject, '<br/>') AS data6,
                    catalogs.DeweyNo AS data7
                    FROM logsdownload
                    LEFT JOIN catalogfiles ON catalogfiles.ID = logsdownload.catalogfilesID
                    LEFT JOIN catalogs ON catalogs.ID = catalogfiles.Catalog_id
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                    WHERE DATE(logsdownload.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= 'AND logsdownload.isLKD = "0"';
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                }
             $sql .= $inValue;        

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 


        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        //$content['Berdasarkan'] =  $Berdasarkan;  
        $content['inValue'] = $inValue;
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            $set = 60;
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
        $content = $this->renderPartial('pdf-opac-logdownload-frekuensi', $content);
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
        if ($kriterias == 'ip') 
        {
            $name = 'IP Address';
        } 
        elseif ($kriterias == 'petugas_perpanjangan') 
        {
            $name = 'Petugas Perpanjangan';
        }
        elseif ($kriterias == 'jenis_pencarian') 
        {
            $name = 'Jenis Pencarian';
        }
        elseif ($kriterias == 'ruas_pencarian') 
        {
            $name = 'Ruas Pencarian';
        }
        elseif ($kriterias == 'keyword') 
        {
            $name = 'Kata Kunci';
        }    
        else 
        {
            $name = '';
        }
        
        return $name;

    }
}
