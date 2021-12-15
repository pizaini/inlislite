<?php

namespace backend\modules\laporan\controllers;


use Yii;
use yii\helpers\Url;
//Widget
use yii\widgets\MaskedInput;
use kartik\widgets\Select2;
use kartik\mpdf\Pdf;
use kartik\date\DatePicker;

//Helpers
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

//Models
use common\models\Currency;
use common\models\VLapKriteriaKoleksi;
//Models
use common\models\TujuanKunjungan;
use common\models\LocationLibrary;
use common\models\Locations;
use common\models\Members;
use common\models\MemberPerpanjangan;
use common\models\Users;
use common\models\JenisKelamin;
use common\models\Departments;
use common\models\Propinsi;
use common\models\VLapKriteriaAnggota;
use common\models\Collectioncategorys;
use common\models\MasterJenisIdentitas;
use common\models\MasterRangeUmur;
use common\models\Kabupaten;
use common\models\Catalogs;
use common\models\Collectionsources;
use common\models\Partners;
use common\models\Collectionrules;
use common\models\Worksheets;
use common\models\Collectionmedias;
use common\models\Masterkelasbesar;



class ArtikelController extends \yii\web\Controller
{
    /**
     * [actionIndex description]
     * @return [type] [description]
     */
public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * [actionKatalogPerkriteria description]
     * @return [type] [description]
     */


public function actionArtikelLogdownload()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('artikel-logdownload',[
            'model' => $model,
            ]);
    }

    /**
     * [actionloadFilterKriteria description]
     * @param  [type] $kriteria [description]
     * @return [type]           [description]
     */


//BacaDitempatController
// public function actionLoadFilterKriteria($kriteria)
//     {
        
        
//     }


    /**
     * [actionLoadSelecterKriteria description]
     * @param  [type] $i [description]
     * @return [type]    [description]
     */
public function actionLoadSelecterBerdasarkanKoleksi($i)
    {
        return $this->renderPartial('select-berdasarkan-koleksi',['i'=>$i]);
    }


public function actionShowPdf($tampilkan)
    {
      
        // session_start();
        $_SESSION['Array_POST_Filter'] = $_POST;

        if ($tampilkan == 'artikel-logdownload-frekuensi') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('artikel-logdownload-frekuensi').'">';
            echo "<iframe>";
        }

        if ($tampilkan == 'artikel-logdownload-data') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('artikel-logdownload-data').'">';
            echo "<iframe>";
        }
  

    }

public function actionArtikelLogdownloadData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload_article.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload_article.waktu),";
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
            if ($_POST['periode'] == "harian"){
            //$sql2 = "SELECT IFNULL(SUM(a.Jumlah),(SELECT COUNT(logsdownload_article.id) FROM logsdownload_article)) AS unt_limit 
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 
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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
             // $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY MONTH(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY YEAR(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                        -- INNER JOIN (SELECT DATE_FORMAT(logsdownload_article.waktu,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(logsdownload_article.id) AS Jumlah 
                        -- FROM logsdownload_article 
                        -- LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                        -- LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                        -- LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                        -- WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                        -- ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID
                    WHERE DATE(logsdownload_article.waktu)";  
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }

        
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql2'] = $sql2; 
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
            'content' => $this->renderPartial('pdf-artikel-logdownload-data', $content),
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

public function actionExportExcelArtikelLogdownloadData()
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
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload_article.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload_article.waktu),";
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
            if ($_POST['periode'] == "harian"){
            //$sql2 = "SELECT IFNULL(SUM(a.Jumlah),(SELECT COUNT(logsdownload_article.id) FROM logsdownload_article)) AS unt_limit 
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
             // $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY MONTH(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY YEAR(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                        -- INNER JOIN (SELECT DATE_FORMAT(logsdownload_article.waktu,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(logsdownload_article.id) AS Jumlah 
                        -- FROM logsdownload_article 
                        -- LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                        -- LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                        -- LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                        -- WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                        -- ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }

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
                <th colspan="4">'.yii::t('app','Artikel Log Download').' '.$periode2.'</th>
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

public function actionExportExcelOdtArtikelLogdownloadData()
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
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload_article.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload_article.waktu),";
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
            if ($_POST['periode'] == "harian"){
            //$sql2 = "SELECT IFNULL(SUM(a.Jumlah),(SELECT COUNT(logsdownload_article.id) FROM logsdownload_article)) AS unt_limit 
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
             // $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY MONTH(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY YEAR(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                        -- INNER JOIN (SELECT DATE_FORMAT(logsdownload_article.waktu,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(logsdownload_article.id) AS Jumlah 
                        -- FROM logsdownload_article 
                        -- LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                        -- LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                        -- LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                        -- WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                        -- ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }

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
        'koleksi_artikellogdownload'=> yii::t('app','Artikel Log Download'),
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
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/artikel/laporan-baca-artikel-logdownload-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Baca-ditempat-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordArtikelLogdownloadData()
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
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload_article.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload_article.waktu),";
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
            if ($_POST['periode'] == "harian"){
            //$sql2 = "SELECT IFNULL(SUM(a.Jumlah),(SELECT COUNT(logsdownload_article.id) FROM logsdownload_article)) AS unt_limit 
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 
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
                            FROM logsdownload_article 
                            LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                            LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                            LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                            LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                            WHERE DATE(logsdownload_article.waktu)";     
            }else{
                $sql = "SELECT ".$periode_format.",
                            catalogs.Title AS cat_titl,
                            CONCAT('<b>',catalogs.Title,'</b>','&#10;
                            ',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('&#10;
                            ',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('&#10;
                            ',EDISISERIAL) ELSE '' END) END),'&#10;
                            ',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'&#10;
                            ',IFNULL(catalogs.Subject,''),'
                            ',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                            members.FullName AS nama 
                            FROM logsdownload_article 
                            LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                            LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                            LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                            LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                            WHERE DATE(logsdownload_article.waktu)";      
            }    
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
             // $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY MONTH(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 
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
                            FROM logsdownload_article 
                            LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                            LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                            LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                            LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                            WHERE DATE(logsdownload_article.waktu)";     
            }else{
                $sql = "SELECT ".$periode_format.",
                            catalogs.Title AS cat_titl,
                            CONCAT('<b>',catalogs.Title,'</b>','&#10;
                            ',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('&#10;
                            ',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('&#10;
                            ',EDISISERIAL) ELSE '' END) END),'&#10;
                            ',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'&#10;
                            ',IFNULL(catalogs.Subject,''),'
                            ',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                            members.FullName AS nama 
                            FROM logsdownload_article 
                            LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                            LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                            LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                            LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                            WHERE DATE(logsdownload_article.waktu)";      
            }    
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY YEAR(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 
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
                            FROM logsdownload_article 
                            LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                            LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                            LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                            LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                            WHERE DATE(logsdownload_article.waktu)";     
            }else{
                $sql = "SELECT ".$periode_format.",
                            catalogs.Title AS cat_titl,
                            CONCAT('<b>',catalogs.Title,'</b>','&#10;
                            ',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('&#10;
                            ',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('&#10;
                            ',EDISISERIAL) ELSE '' END) END),'&#10;
                            ',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'&#10;
                            ',IFNULL(catalogs.Subject,''),'
                            ',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                            members.FullName AS nama 
                            FROM logsdownload_article 
                            LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                            LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                            LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                            LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                            WHERE DATE(logsdownload_article.waktu)";      
            }
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }

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
                <th colspan="4">'.yii::t('app','Artikel Log Download').' '.$periode2.'</th>
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

public function actionExportPdfArtikelLogdownloadData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(logsdownload_article.waktu),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(logsdownload_article.waktu),";
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
            if ($_POST['periode'] == "harian"){
            //$sql2 = "SELECT IFNULL(SUM(a.Jumlah),(SELECT COUNT(logsdownload_article.id) FROM logsdownload_article)) AS unt_limit 
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 
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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
             // $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY MONTH(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu)";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit 
            FROM (SELECT COUNT(logsdownload_article.id) AS Jumlah 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                    WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY YEAR(logsdownload_article.waktu), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue."
                ) a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                    LEFT JOIN members ON logsdownload_article.`User_id` = members.ID 
                        -- INNER JOIN (SELECT DATE_FORMAT(logsdownload_article.waktu,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(logsdownload_article.id) AS Jumlah 
                        -- FROM logsdownload_article 
                        -- LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                        -- LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                        -- LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id` 
                        -- WHERE DATE(logsdownload_article.waktu) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                        -- ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID
                    WHERE DATE(logsdownload_article.waktu)";  
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY tgl_baca ASC, cat_titl ASC LIMIT ";
             $sql .= $inValue;
                    }

        
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
        $content = $this->renderPartial('pdf-artikel-logdownload-data', $content);
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

public function actionArtikelLogdownloadFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%Y") AS Periode';
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
                    COUNT(logsdownload_article.id) AS Jumlah,
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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id`
                    WHERE DATE(logsdownload_article.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
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
            'content' => $this->renderPartial('pdf-artikel-logdownload-frekuensi', $content),
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

public function actionExportExcelArtikelLogdownloadFrekuensi()
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
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%Y") AS Periode';
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
                    COUNT(logsdownload_article.id) AS Jumlah,
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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id`
                    WHERE DATE(logsdownload_article.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
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
                <th colspan="6">'.yii::t('app','Artikel Log Download').' '.$periode2.'</th>
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

public function actionExportExcelOdtArtikelLogdownloadFrekuensi()
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
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%Y") AS Periode';
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
                    COUNT(logsdownload_article.id) AS Jumlah,
                    catalogs.ID AS ids,
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
                     catalogs.DeweyNo AS data7 
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id`
                    WHERE DATE(logsdownload_article.waktu)
                    "; 

             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
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
        'koleksi_artikellogdownload'=> yii::t('app','Artikel Log Download'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal'=> yii::t('app','Tanggal'),
        'data_bibliografis'=> yii::t('app','Data Bibliografis'),
        'jumlah_pembaca'=> yii::t('app','Jumlah Pembaca'),      
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/artikel/laporan-artikel-logdownload.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Baca-ditempat-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordArtikelLogdownloadFrekuensi()
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
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%Y") AS Periode';
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
                        COUNT(logsdownload_article.id) AS Jumlah,
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
                        FROM logsdownload_article 
                        LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                        LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                        LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id`
                        WHERE DATE(logsdownload_article.waktu) ";  
            }else{
                $sql = "SELECT ".$periode_format.",
                        COUNT(logsdownload_article.id) AS Jumlah,
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
                        FROM logsdownload_article 
                        LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                        LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                        LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id`
                        WHERE DATE(logsdownload_article.waktu) ";    
            }  
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
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
                <th colspan="5">'.yii::t('app','Artikel Log Download').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
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

public function actionExportPdfArtikelLogdownloadFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(logsdownload_article.waktu,"%Y") AS Periode';
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
                    COUNT(logsdownload_article.id) AS Jumlah,
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
                    FROM logsdownload_article 
                    LEFT JOIN serial_articlefiles ON serial_articlefiles.ID = logsdownload_article.`articlefilesID`
                    LEFT JOIN serial_articles ON serial_articles.id = serial_articlefiles.`Articles_id`
                    LEFT JOIN catalogs ON catalogs.`ID` = serial_articles.`Catalog_id`
                    WHERE DATE(logsdownload_article.waktu) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(logsdownload_article.waktu,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(logsdownload_article.waktu), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
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
        $content = $this->renderPartial('pdf-artikel-logdownload-frekuensi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }


public function getRealNameKriteria($kriterias)
    {
        if ($kriterias == 'PublishLocation') 
        {
            $name = 'Kota Terbit';
        }
        elseif ($kriterias == 'Publisher') 
        {
            $name = 'Nama Penerbit';
        }
        elseif ($kriterias == 'PublishYear') 
        {
            $name = 'Tahun Terbit';
        }
        elseif ($kriterias == 'location_library') 
        {
            $name = 'Lokasi Perpustakaan';
        }
        elseif ($kriterias == 'locations') 
        {
            $name = 'Lokasi';
        }
        elseif ($kriterias == 'collectionsources') 
        {
            $name = 'Jenis Sumber Perolehan';
        }
        elseif ($kriterias == 'partners') 
        {
            $name = 'Nama Sumber/Rekanan Perolehan';
        }
        elseif ($kriterias == 'currency') 
        {
            $name = 'Mata Uang';
        }
        elseif ($kriterias == 'harga') 
        {
            $name = 'Harga';
        }
        elseif ($kriterias == 'collectioncategorys') 
        {
            $name = 'Kategori';
        }
        elseif ($kriterias == 'collectionrules') 
        {
            $name = 'Jenis Akses';
        }
        elseif ($kriterias == 'worksheets') 
        {
            $name = 'Jenis Bahan';
        }
        elseif ($kriterias == 'collectionmedias') 
        {
            $name = 'Bentuk Fisik';
        } 
        elseif ($kriterias == 'Subject') 
        {
            $name = 'Subjek';
        }
        elseif ($kriterias == 'no_klas') 
        {
            $name = 'Nomer Kelas';
        } elseif ($kriterias == 'no_panggil') 
        {
            $name = 'Nomer Panggil';
        }
        else 
        {
            $name = ' ';
        }
        
        return $name;

    }
}
