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
// use common\models\Catalogs;
// use common\models\LocationLibrary;
use common\models\Locations;
// use common\models\Collectionsources;
// use common\models\Partners;
//use common\models\Currency;
use common\models\Users;
// use common\models\Collectionrules;
// use common\models\Worksheets;
// use common\models\Collectionmedias;
use common\models\MasterKelasBesar;
use common\models\VLapKriteriaKatalog;


class KatalogController extends \yii\web\Controller
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

public function actionKinerjaUser()
    {

    	$model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('kinerja-user',[
        	'model' => $model,
        	]);
    }

public function actionKatalogPerkriteria()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('katalog-perkriteria',[
            'model' => $model,
            ]);
    }    

    /**
     * [actionloadFilterKriteria description]
     * @param  [type] $kriteria [description]
     * @return [type]           [description]
     */

public function actionLoadFilterKriteria($kriteria)
    {
        // if ($kriteria !== 'currency' && $kriteria !== 'no_klas' && $kriteria !== 'no_panggil' && $kriteria !== 'createdate' && $kriteria !== 'harga' && $kriteria !== '' ) 
        // {
        //     $options = ArrayHelper::map(VLapKriteriaKatalog::find()->where(['kriteria'=>$kriteria])->all(),'id_dtl_kriteria','dtl_kriteria');
        //     $options[0] = " ---Semua---";
        //     asort($options);
        //     $options = array_filter($options);
        //     $contentOptions = Html::dropDownList( $kriteria.'[]',
        //         'selected option',  
        //         $options, 
        //         ['class' => 'select2 col-sm-6',]
        //     );
        // } 
        // else       
        if ($kriteria == 'kataloger')
        {
            $sql = 'SELECT users.ID AS ID, CONCAT(users.username, " - ", users.Fullname) AS kataloger FROM users ';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','kataloger');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }  
        else if ($kriteria == 'location')
        {
            $sql = 'SELECT * FROM locations';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','Name');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'no_klas')
        {
            $sql = 'SELECT *, SUBSTR(master_kelas_besar.kdKelas,1,1) AS test FROM master_kelas_besar';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'test','namakelas');
            $options[null] = yii::t('app',' ---Semua---');
            $options[XI] = " Lainnya";
            asort($options);
            // echo '<pre>';print_r($options);echo '</pre>';die;
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'kriteria')
        {

            $options = ['0' => yii::t('app','Cantuman (katalog) Dibuat'),'1' => yii::t('app','Cantuman (katalog) Dimuktahirkan'),'2' => yii::t('app','Cantuman (katalog) Dihapus')];
           
            $options2 = \yii\helpers\ArrayHelper::merge([""=>yii::t('app',' ---Semua---')],$options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else
        {
            $contentOptions = null;
        }
        return $contentOptions;
        
    }


    /**
     * [actionLoadSelecterKriteria description]
     * @param  [type] $i [description]
     * @return [type]    [description]
     */
public function actionLoadSelecterKinerjaUser($i)
    {
        return $this->renderPartial('select-kinerja-user',['i'=>$i]);
    }


public function actionShowPdf($tampilkan)
    {
      
        // session_start();
        $_SESSION['Array_POST_Filter'] = $_POST;


        if ($tampilkan == 'katalog-perkriteria-frekuensi') 
        {
            echo (count(array_filter($_POST['kriterias'])) != '' ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-katalog-perkriteria-frekuensi').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );
        } 

        if ($tampilkan == 'katalog-perkriteria-data') 
        {
            echo (count(array_filter($_POST['kriterias'])) != '' ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-katalog-perkriteria-data').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."')</script>"
            );
        } 
        if ($tampilkan == 'katalog-kinerja-user') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-kinerja-user-frekuensi').'">';
            echo "<iframe>";
        }
        if ($tampilkan == 'katalog-kinerja-user-data') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-kinerja-user-data').'">';
            echo "<iframe>";
        }
        

    }


public function actionRenderPdfKatalogPerkriteriaData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            $cont = "pdf-view-katalog-perkriteria-data";
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT callnumber AS NoPanggil,
                    BIBID, 
                    author AS Pengarang, 
                    Title AS Judul, 
                    publisher AS Penerbitan, 
                    PhysicalDescription AS Deskripsifisik,
                    PUBLISHYEAR AS PUBLISHYEAR, 
                    SUBJECT AS subjek, 
                    users.username AS UserName,
                    DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y %H:%i%s') AS CreateDate,
                    (SELECT  COUNT(catalogs.ID) FROM catalogs WHERE catalogs.CreateDate ".$sqlPeriode. $andValue.") AS katalog
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY catalogs.CreateDate LIMIT 1000";
            }         
             
            if (implode($_POST['kriterias']) == 'subjek' || implode($_POST['kriterias']) == 'no_klas') {
            $cont = "pdf-view-katalog-perkriteria-subject-klass-data";
            if (implode($_POST['kriterias']) == 'no_klas') {
            foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(catalogs.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
            }
            $sql = "SELECT DATE_FORMAT(catalogs.CreateDate,'%d-%M-%Y %h:%i:%s') Periode, 
                    (SELECT SUBSTR(catalog_ruas.Value,(INSTR(catalog_ruas.Value,' ')+1)) FROM catalog_ruas WHERE catalog_ruas.CatalogId = catalogs.ID AND catalog_ruas.Tag = 082) AS klas, 
                    namakelas AS NamaKriteria, 
                    catalogs.Subject AS subj,
                    BIBID AS BIBID, 
                    title AS Judul,
                    Author AS Pengarang, 
                    Publisher AS publisher,
                    catalogs.PhysicalDescription AS deskripsi_fisik,
                    (SELECT COUNT(collections.Catalog_id) FROM collections WHERE collections.Catalog_id = catalogs.ID) AS jml_eks,
                    (SELECT COUNT(catalogs.ID) FROM catalogs WHERE DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') ".$sqlPeriode. $andValue.") AS katalog
                    FROM catalogs 
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID 
                    LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(catalogs.DeweyNo,1,1) 
                    WHERE DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d')
                    ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= 'GROUP BY catalogs.CreateDate , Judul, BIBID ORDER BY DATE_FORMAT(catalogs.CreateDate,"%Y-%m-%d") DESC LIMIT 1000';
            }

            if (implode($_POST['kriterias']) == 'bahan_pustaka') {
                $sql = "SELECT a.Periode AS Periode,
                        a.Periode2 AS Tahun2, 
                        w.name AS BahanPustaka, 
                        a.jumlah AS Jumlah 
                        FROM 
                        (
                        SELECT DATE_FORMAT(CreateDate,'%d-%m-%Y') AS Periode, 
                        DATE_FORMAT(CreateDate,'%Y-%m-%d') Periode2,
                        worksheet_id,
                        COUNT(worksheet_id) jumlah 
                        FROM catalogs 
                        WHERE catalogs.CreateDate ";      
                $sql .= $sqlPeriode;
                $sql .= " GROUP BY DATE_FORMAT(CreateDate,'%d-%m-%Y'), DATE_FORMAT(CreateDate,'%Y-%m-%d'), worksheet_id 
                    ORDER BY DATE_FORMAT(CreateDate,'%Y-%m-%d') DESC, DATE_FORMAT(CreateDate,'%d-%m-%Y'),worksheet_id) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id 
                    ORDER BY Periode2 DESC";
                }

            if (implode($_POST['kriterias']) == 'judul') {
            $cont = "pdf-view-katalog-perkriteria-judul-data";
                $sql = "SELECT ".$periode_format.",
                        catalogs.Subject AS NamaKriteria,
                        ControlNumber AS NoPanggil,
                        BIBID AS BIBID,
                        catalogs.Title AS Judul,
                        Author AS Pengarang,
                        ISBN AS ISbn,
                        PublishLocation,
                        Publisher AS publisher, 
                        PublishYear,
                        (SELECT COUNT(catalogs.ID) FROM catalogs WHERE DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') ".$sqlPeriode. $andValue.") AS katalog 
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";      
                $sql .= $sqlPeriode;
                $sql .= " ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC LIMIT 1000";
                }

            // print_r($sql);
            // die;

        
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($value).' ';
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan;  
       // $content['isi_berdasarkan'] = $isi_kriteria;
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
            'content' => $this->renderPartial($cont, $content),
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

public function actionExportExcelKatalogPerkriteriaData()
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
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT callnumber AS NoPanggil,
                    BIBID, 
                    author AS Pengarang, 
                    Title AS Judul, 
                    publisher AS Penerbitan, 
                    PhysicalDescription AS Deskripsifisik,
                    PUBLISHYEAR AS PUBLISHYEAR, 
                    SUBJECT AS subjek, 
                    users.username AS UserName,
                    DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y %H:%i%s') AS CreateDate 
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY catalogs.CreateDate";
            }

            if (implode($_POST['kriterias']) == 'subjek' || implode($_POST['kriterias']) == 'no_klas') {
            if (implode($_POST['kriterias']) == 'no_klas') {
            foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(catalogs.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
            }
            $sql = "SELECT DATE_FORMAT(catalogs.CreateDate,'%d-%M-%Y %h:%i:%s') Periode, 
                    (SELECT SUBSTR(catalog_ruas.Value,(INSTR(catalog_ruas.Value,' ')+1)) FROM catalog_ruas WHERE catalog_ruas.CatalogId = catalogs.ID AND catalog_ruas.Tag = 082) AS klas, 
                    namakelas AS NamaKriteria, 
                    catalogs.Subject AS subj,
                    BIBID AS BIBID, 
                    title AS Judul,
                    Author AS Pengarang, 
                    Publisher AS publisher,
                    catalogs.PhysicalDescription AS deskripsi_fisik,
                    (SELECT COUNT(collections.Catalog_id) FROM collections WHERE collections.Catalog_id = catalogs.ID) AS jml_eks
                    FROM catalogs 
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID 
                    LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(catalogs.DeweyNo,1,1) 
                    WHERE DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d')
                    ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= 'GROUP BY catalogs.CreateDate , Judul, BIBID ORDER BY DATE_FORMAT(catalogs.CreateDate,"%Y-%m-%d") DESC';
            }

            if (implode($_POST['kriterias']) == 'bahan_pustaka') {
                $sql = "SELECT a.Periode AS Periode,
                        a.Periode2 AS Tahun2, 
                        w.name AS BahanPustaka, 
                        a.jumlah AS Jumlah 
                        FROM 
                        (
                        SELECT DATE_FORMAT(CreateDate,'%d-%m-%Y') AS Periode, 
                        DATE_FORMAT(CreateDate,'%Y-%m-%d') Periode2,
                        worksheet_id,
                        COUNT(worksheet_id) jumlah 
                        FROM catalogs 
                        WHERE catalogs.CreateDate ";      
                $sql .= $sqlPeriode;
                $sql .= " GROUP BY DATE_FORMAT(CreateDate,'%d-%m-%Y'), DATE_FORMAT(CreateDate,'%Y-%m-%d'), worksheet_id 
                    ORDER BY DATE_FORMAT(CreateDate,'%Y-%m-%d') DESC, DATE_FORMAT(CreateDate,'%d-%m-%Y'),worksheet_id) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id 
                    ORDER BY Periode2 DESC";
                }

            if (implode($_POST['kriterias']) == 'judul') {
                $sql = "SELECT ".$periode_format.",
                        catalogs.Subject AS NamaKriteria,
                        ControlNumber AS NoPanggil,
                        BIBID AS BIBID,
                        catalogs.Title AS Judul,
                        Author AS Pengarang,
                        ISBN AS ISbn,
                        PublishLocation,
                        Publisher AS publisher, 
                        PublishYear 
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";      
                $sql .= $sqlPeriode;
                $sql .= " ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
            }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

    $filename = 'Laporan_Periodik_Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="11">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="11">'.yii::t('app','Katalog Perkriteria').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="11">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    if (implode($_POST["kriterias"]) == "kataloger"){
    echo '<table border="1" align="center">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Nomer Panggil').'</th>
                <th>'.yii::t('app','BIB ID').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Tahun Terbit').'</th>
                <th>'.yii::t('app','Deskripsi Fisik').'</th>
                <th>'.yii::t('app','Subjek').'</th>
                <th>'.yii::t('app','Username').'</th>
                <th>'.yii::t('app','Tanggal Dibuat').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>';
                if (implode($_POST["kriterias"]) != "subjek")
                {
                  echo '<td>'.$data['NoPanggil'].'</td>';
                }echo '
                    <td>'.$data['BIBID'].'</td>
                    <td>'.$data['Pengarang'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['Penerbitan'].'</td>
                    <td>'.$data['Deskripsifisik'].'</td>
                    <td>'.$data['PUBLISHYEAR'].'</td>
                    <td>'.$data['subjek'].'</td>
                    <td>'.$data['UserName'].'</td>
                    <td>'.$data['CreateDate'].'</td>
                </tr>
            ';
        $no++;
        endforeach;        
    echo '</table>';
}elseif (implode($_POST["kriterias"]) == 'subjek' || implode($_POST["kriterias"]) == 'no_klas'){
echo '<table border="1" align="center">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>';
                if (implode($_POST["kriterias"]) != 'subjek') {echo '<th>'.yii::t('app','Klas DCC').'</th>';
                }echo'
                <th>'.yii::t('app','Subjek').'</th>
                <th>'.yii::t('app','BIB-ID').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Deskripsi FIsik').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
    ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>';
                    if (implode($_POST["kriterias"]) != 'subjek') {echo '<td>'.$data['klas'].'</td>';
                    }echo'
                    <td>'.$data['subj'].'</td>
                    <td>'.$data['BIBID'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['publisher'].'</td>
                    <td>'.$data['deskripsi_fisik'].'</td>
                    <td>'.$data['jml_eks'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';}
    elseif (implode($_POST["kriterias"]) == 'judul'){
    echo '<table border="1" align="center">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode').'</th>
                <th>'.yii::t('app','Control Number').'</th>
                <th>'.yii::t('app','BIB-ID').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
            </tr>
    ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['NoPanggil'].'</td>
                    <td>'.$data['BIBID'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['Pengarang'].'</td>
                    <td>'.$data['publisher'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';
} 

}

public function actionExportExcelOdtKatalogPerkriteriaData()
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
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT callnumber AS NoPanggil,
                    BIBID, 
                    author AS Pengarang, 
                    Title AS Judul, 
                    publisher AS Penerbitan, 
                    PhysicalDescription AS Deskripsifisik,
                    PUBLISHYEAR AS PUBLISHYEAR, 
                    SUBJECT AS subjek, 
                    users.username AS UserName,
                    DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y %H:%i%s') AS CreateDate 
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY catalogs.CreateDate";
            }

            if (implode($_POST['kriterias']) == 'subjek' || implode($_POST['kriterias']) == 'no_klas') {
            if (implode($_POST['kriterias']) == 'no_klas') {
            foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(catalogs.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
            }
            $sql = "SELECT DATE_FORMAT(catalogs.CreateDate,'%d-%M-%Y %h:%i:%s') Periode, 
                    (SELECT SUBSTR(catalog_ruas.Value,(INSTR(catalog_ruas.Value,' ')+1)) FROM catalog_ruas WHERE catalog_ruas.CatalogId = catalogs.ID AND catalog_ruas.Tag = 082) AS klas, 
                    namakelas AS NamaKriteria, 
                    catalogs.Subject AS subj,
                    BIBID AS BIBID, 
                    title AS Judul,
                    Author AS Pengarang, 
                    Publisher AS publisher,
                    catalogs.PhysicalDescription AS deskripsi_fisik,
                    (SELECT COUNT(collections.Catalog_id) FROM collections WHERE collections.Catalog_id = catalogs.ID) AS jml_eks
                    FROM catalogs 
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID 
                    LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(catalogs.DeweyNo,1,1) 
                    WHERE DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d')
                    ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= 'GROUP BY catalogs.CreateDate , Judul, BIBID ORDER BY DATE_FORMAT(catalogs.CreateDate,"%Y-%m-%d") DESC';
            }

            if (implode($_POST['kriterias']) == 'judul') {
                $sql = "SELECT ".$periode_format.",
                        catalogs.Subject AS NamaKriteria,
                        ControlNumber AS NoPanggil,
                        BIBID AS BIBID,
                        catalogs.Title AS Judul,
                        Author AS Pengarang,
                        ISBN AS ISbn,
                        PublishLocation,
                        Publisher AS publisher, 
                        PublishYear 
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";      
                $sql .= $sqlPeriode;
                $sql .= " ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
            }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = $inValue;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

    if (implode($_POST['kriterias']) == 'no_klas') {$sub = 'Kelas Besar';}else {$sub = 'Subjek';}

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 
            'BIBID'=>$model['BIBID'], 'Penerbitan'=>$model['Penerbitan'], 'Deskripsifisik'=>$model['Deskripsifisik'], 'PUBLISHYEAR'=>$model['PUBLISHYEAR'], 'subjek'=>$model['subjek'], 'UserName'=>$model['UserName'], 'CreateDate'=>$model['CreateDate'],
            'Judul'=>$model['Judul'], 'subj'=>$model['subj'], 'Pengarang'=>$model['Pengarang'], 'publisher'=>$model['publisher'], 'deskripsi_fisik'=>$model['deskripsi_fisik'], 'jml_eks'=>$model['jml_eks'], 'klas'=>$model['klas'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'sub'=>yii::t('app',$sub),
        );

    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'katalog_perkriteria'=> yii::t('app','Katalog Perkriteria'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'nomer_panggil'=> yii::t('app','Nomer Panggil'),
        'bib_id'=> yii::t('app','BIB ID'),
        'pengarang'=> yii::t('app','Pengarang'),
        'judul'=> yii::t('app','Judul'),
        'penerbit'=> yii::t('app','Penerbit'),
        'tahun_terbit'=> yii::t('app','Tahun Terbit'),
        'deskripsi_fisik'=> yii::t('app','Deskripsi Fisik'),
        'subjek'=> yii::t('app','Subjek'),
        'username'=> yii::t('app','Username'),
        'create_date'=> yii::t('app','Create Date'),
        'tanggal'=> yii::t('app','Tanggal'),
        'klas_ddc'=> yii::t('app','Klas DCC'),
        'jumlah_eksemplar'=> yii::t('app','Jumlah Eksemplar'),
        'control_number'=> yii::t('app','Control Number'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    // $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-sering-baca.ods'; 

    if (implode($_POST['kriterias']) == 'kataloger') {
        $template = Yii::getAlias('@uploaded_files').'/templates/laporan/katalog/laporan-katalog-perkriteria-kataloger-data.ods'; 
    }elseif (implode($_POST['kriterias']) == 'subjek'){
        $template = Yii::getAlias('@uploaded_files').'/templates/laporan/katalog/laporan-katalog-perkriteria-subjek.ods';
    }elseif (implode($_POST['kriterias']) == 'no_klas'){
        $template = Yii::getAlias('@uploaded_files').'/templates/laporan/katalog/laporan-katalog-perkriteria-klasDCC-data.ods'; 
    }else{
        $template = Yii::getAlias('@uploaded_files').'/templates/laporan/katalog/laporan-katalog-perkriteria-data.ods'; 
    }

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-katalog-perkriteria-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordKatalogPerkriteriaData()
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
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT callnumber AS NoPanggil,
                    BIBID, 
                    author AS Pengarang, 
                    Title AS Judul, 
                    publisher AS Penerbitan, 
                    PhysicalDescription AS Deskripsifisik,
                    PUBLISHYEAR AS PUBLISHYEAR, 
                    SUBJECT AS subjek, 
                    users.username AS UserName,
                    DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y %H:%i%s') AS CreateDate 
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY catalogs.CreateDate";
            }

            if (implode($_POST['kriterias']) == 'subjek' || implode($_POST['kriterias']) == 'no_klas') {
            if (implode($_POST['kriterias']) == 'no_klas') {
            foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(catalogs.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
            }
            $sql = "SELECT DATE_FORMAT(catalogs.CreateDate,'%d-%M-%Y %h:%i:%s') Periode, 
                    (SELECT SUBSTR(catalog_ruas.Value,(INSTR(catalog_ruas.Value,' ')+1)) FROM catalog_ruas WHERE catalog_ruas.CatalogId = catalogs.ID AND catalog_ruas.Tag = 082) AS klas, 
                    namakelas AS NamaKriteria, 
                    catalogs.Subject AS subj,
                    BIBID AS BIBID, 
                    title AS Judul,
                    Author AS Pengarang, 
                    Publisher AS publisher,
                    catalogs.PhysicalDescription AS deskripsi_fisik,
                    (SELECT COUNT(collections.Catalog_id) FROM collections WHERE collections.Catalog_id = catalogs.ID) AS jml_eks
                    FROM catalogs 
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID 
                    LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(catalogs.DeweyNo,1,1) 
                    WHERE DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d')
                    ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= 'GROUP BY catalogs.CreateDate , Judul, BIBID ORDER BY DATE_FORMAT(catalogs.CreateDate,"%Y-%m-%d") DESC';
            }

            if (implode($_POST['kriterias']) == 'bahan_pustaka') {
                $sql = "SELECT a.Periode AS Periode,
                        a.Periode2 AS Tahun2, 
                        w.name AS BahanPustaka, 
                        a.jumlah AS Jumlah 
                        FROM 
                        (
                        SELECT DATE_FORMAT(CreateDate,'%d-%m-%Y') AS Periode, 
                        DATE_FORMAT(CreateDate,'%Y-%m-%d') Periode2,
                        worksheet_id,
                        COUNT(worksheet_id) jumlah 
                        FROM catalogs 
                        WHERE catalogs.CreateDate ";      
                $sql .= $sqlPeriode;
                $sql .= " GROUP BY DATE_FORMAT(CreateDate,'%d-%m-%Y'), DATE_FORMAT(CreateDate,'%Y-%m-%d'), worksheet_id 
                    ORDER BY DATE_FORMAT(CreateDate,'%Y-%m-%d') DESC, DATE_FORMAT(CreateDate,'%d-%m-%Y'),worksheet_id) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id 
                    ORDER BY Periode2 DESC";
                }

            if (implode($_POST['kriterias']) == 'judul') {
                $sql = "SELECT ".$periode_format.",
                        catalogs.Subject AS NamaKriteria,
                        ControlNumber AS NoPanggil,
                        BIBID AS BIBID,
                        catalogs.Title AS Judul,
                        Author AS Pengarang,
                        ISBN AS ISbn,
                        PublishLocation,
                        Publisher AS publisher, 
                        PublishYear 
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    }
                }

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
    echo '<table border="0" align="center" width="800"> 
            <tr>
                <th colspan="11">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="11">'.yii::t('app','Katalog Perkriteria').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="11">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    if (implode($_POST["kriterias"]) == "kataloger"){
    echo '<table border="1" align="center" width="100%">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Nomer Panggil').'</th>
                <th>'.yii::t('app','BIB ID').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Tahun Terbit').'</th>
                <th>'.yii::t('app','Deskripsi Fisik').'</th>
                <th>'.yii::t('app','Subjek').'</th>
                <th>'.yii::t('app','Username').'</th>
                <th>'.yii::t('app','Tanggal Dibuat').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>';
                if (implode($_POST["kriterias"]) != "subjek")
                {
                  echo '<td>'.$data['NoPanggil'].'</td>';
                }echo '
                    <td>'.$data['BIBID'].'</td>
                    <td>'.$data['Pengarang'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['Penerbitan'].'</td>
                    <td>'.$data['Deskripsifisik'].'</td>
                    <td>'.$data['PUBLISHYEAR'].'</td>
                    <td>'.$data['subjek'].'</td>
                    <td>'.$data['UserName'].'</td>
                    <td>'.$data['CreateDate'].'</td>
                </tr>
            ';
        $no++;
        endforeach;        
    echo '</table>';
}elseif (implode($_POST["kriterias"]) == 'subjek' || implode($_POST["kriterias"]) == 'no_klas'){
echo '<table border="1" align="center" width="100%">';
    echo '
            <tr>
                <th>No.</th>
                <th>Tanggal</th>';
                if (implode($_POST["kriterias"]) != 'subjek') {echo '<th>'.yii::t('app','Klas DCC').'</th>';
                }echo'
                <th>'.yii::t('app','Subjek').'</th>
                <th>'.yii::t('app','BIB-ID').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Deskripsi FIsik').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
    ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>';
                    if (implode($_POST["kriterias"]) != 'subjek') {echo '<td>'.$data['klas'].'</td>';
                    }echo'
                    <td>'.$data['subj'].'</td>
                    <td>'.$data['BIBID'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['publisher'].'</td>
                    <td>'.$data['deskripsi_fisik'].'</td>
                    <td>'.$data['jml_eks'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';
}
    elseif (implode($_POST["kriterias"]) == 'judul'){
echo '<table border="1" align="center">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode').'</th>
                <th>'.yii::t('app','Control Number').'</th>
                <th>'.yii::t('app','BIB-ID').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
            </tr>
    ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['NoPanggil'].'</td>
                    <td>'.$data['BIBID'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['Pengarang'].'</td>
                    <td>'.$data['publisher'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';
} 

}

public function actionExportPdfKatalogPerkriteriaData()
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            $cont = "pdf-view-katalog-perkriteria-data";
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT callnumber AS NoPanggil,
                    BIBID, 
                    author AS Pengarang, 
                    Title AS Judul, 
                    publisher AS Penerbitan, 
                    PhysicalDescription AS Deskripsifisik,
                    PUBLISHYEAR AS PUBLISHYEAR, 
                    SUBJECT AS subjek, 
                    users.username AS UserName,
                    DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y %H:%i%s') AS CreateDate 
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY catalogs.CreateDate";
            }

            if (implode($_POST['kriterias']) == 'subjek' || implode($_POST['kriterias']) == 'no_klas') {
            $cont = "pdf-view-katalog-perkriteria-subject-klass-data";
            if (implode($_POST['kriterias']) == 'no_klas') {
            foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(catalogs.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
            }
            $sql = "SELECT DATE_FORMAT(catalogs.CreateDate,'%d-%M-%Y %h:%i:%s') Periode, 
                    (SELECT SUBSTR(catalog_ruas.Value,(INSTR(catalog_ruas.Value,' ')+1)) FROM catalog_ruas WHERE catalog_ruas.CatalogId = catalogs.ID AND catalog_ruas.Tag = 082) AS klas, 
                    namakelas AS NamaKriteria, 
                    catalogs.Subject AS subj,
                    BIBID AS BIBID, 
                    title AS Judul,
                    Author AS Pengarang, 
                    Publisher AS publisher,
                    catalogs.PhysicalDescription AS deskripsi_fisik,
                    (SELECT COUNT(collections.Catalog_id) FROM collections WHERE collections.Catalog_id = catalogs.ID) AS jml_eks
                    FROM catalogs 
                    LEFT JOIN collections ON collections.Catalog_id = catalogs.ID 
                    LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(catalogs.DeweyNo,1,1) 
                    WHERE DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d')
                    ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= 'GROUP BY catalogs.CreateDate , Judul, BIBID ORDER BY DATE_FORMAT(catalogs.CreateDate,"%Y-%m-%d") DESC';
            }

            if (implode($_POST['kriterias']) == 'bahan_pustaka') {
                $sql = "SELECT a.Periode AS Periode,
                        a.Periode2 AS Tahun2, 
                        w.name AS BahanPustaka, 
                        a.jumlah AS Jumlah 
                        FROM 
                        (
                        SELECT DATE_FORMAT(CreateDate,'%d-%m-%Y') AS Periode, 
                        DATE_FORMAT(CreateDate,'%Y-%m-%d') Periode2,
                        worksheet_id,
                        COUNT(worksheet_id) jumlah 
                        FROM catalogs 
                        WHERE catalogs.CreateDate ";      
                $sql .= $sqlPeriode;
                $sql .= " GROUP BY DATE_FORMAT(CreateDate,'%d-%m-%Y'), DATE_FORMAT(CreateDate,'%Y-%m-%d'), worksheet_id 
                    ORDER BY DATE_FORMAT(CreateDate,'%Y-%m-%d') DESC, DATE_FORMAT(CreateDate,'%d-%m-%Y'),worksheet_id) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id 
                    ORDER BY Periode2 DESC";
                }

            if (implode($_POST['kriterias']) == 'judul') {
            $cont = "pdf-view-katalog-perkriteria-judul-data";
                $sql = "SELECT ".$periode_format.",
                        catalogs.Subject AS NamaKriteria,
                        ControlNumber AS NoPanggil,
                        BIBID AS BIBID,
                        catalogs.Title AS Judul,
                        Author AS Pengarang,
                        ISBN AS ISbn,
                        PublishLocation,
                        Publisher AS publisher, 
                        PublishYear 
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    }
                }
            // print_r($sql);
            // die;

        
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($value).' ';
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan;  
       // $content['isi_berdasarkan'] = $isi_kriteria;
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
        $content = $this->renderPartial($cont, $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

    }

public function actionRenderKinerjaUserData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di buat) ');
                break;

            case '1':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di mutakhirkan) ');
                break;

            case '2':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di hapus) ');
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }


           $sql = "SELECT DATE_FORMAT(modelhistory.date,'%d-%M-%Y %h:%i:%s') Periode, 
                   CONCAT(users.UserName, ' / <br />', users.Fullname) AS Kataloger, 
                   modelhistory.field_id AS f_id, modelhistory.type AS kriteria, 
                   CASE 
                    WHEN modelhistory.type = '0' THEN 'Entri'
                    WHEN modelhistory.type = '1' THEN 'Koreksi' 
                    ELSE 'Hapus' 
                   END AS nama_kriteria, 
                   modelhistory.field_id AS id_record, 
                    CASE 
                     WHEN modelhistory.type = '0' THEN CONCAT(modelhistory.field_name, ':', modelhistory.new_value) 
                     ELSE
                    CONCAT(
                    CASE
                     WHEN modelhistory.field_name = 'PublishYear' THEN REPLACE(modelhistory.field_name,'PublishYear','Tahun Terbit')
                     WHEN modelhistory.field_name = 'Title' THEN REPLACE(modelhistory.field_name,'Title','Judul')
                     WHEN modelhistory.field_name = 'Publisher' THEN REPLACE(modelhistory.field_name,'Publisher','Penerbit')
                     WHEN modelhistory.field_name = 'PhysicalDescription' THEN REPLACE(modelhistory.field_name,'PhysicalDescription','Deskripsi Fisik')
                     ELSE REPLACE(modelhistory.field_name,'PublishLocation','Tempat Terbit')
                    END
                    , ':', IFNULL(REPLACE(modelhistory.old_value, '   ',' '),'(kosong)'), '-->', IFNULL(LTRIM(modelhistory.new_value), '(kosong)')) 
                   END AS actions,
                   (SELECT COUNT(modelhistory.id) FROM modelhistory WHERE DATE(modelhistory.date) ".$sqlPeriode. $andValue." AND modelhistory.table = 'catalogs') AS k_user
                   FROM modelhistory 
                   LEFT JOIN users ON modelhistory.user_id = users.ID 
                   LEFT JOIN catalogs ON catalogs.ID = modelhistory.field_id 
                   WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC LIMIT 1000";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

        if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';} 


        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['a'] = $a; 
        $content['dan'] = $dan;
        $content['DetailFilter'] = $DetailFilter;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" />'];
            $set = 55;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'marginTop' => $set,
            'marginRight' => 0,
            'marginLeft' => 0,
            'content' => $this->renderPartial('pdf-view-katalog-kinerja-user-data', $content),
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

public function actionExportExcelKinerjaUserData()
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
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di buat) ');
                break;

            case '1':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di mutakhirkan) ');
                break;

            case '2':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di hapus) ');
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }

            $sql = "SELECT DATE_FORMAT(modelhistory.date,'%d-%M-%Y %h:%i:%s') Periode, 
                   CONCAT(users.UserName, ' / <br />', users.Fullname) AS Kataloger, 
                   modelhistory.field_id AS f_id, modelhistory.type AS kriteria, 
                   CASE 
                    WHEN modelhistory.type = '0' THEN 'Entri'
                    WHEN modelhistory.type = '1' THEN 'Koreksi' 
                    ELSE 'Hapus' 
                   END AS nama_kriteria, 
                   modelhistory.field_id AS id_record, 
                    CASE 
                     WHEN modelhistory.type = '0' THEN CONCAT(modelhistory.field_name, ':', modelhistory.new_value) 
                     ELSE
                    CONCAT(
                    CASE
                     WHEN modelhistory.field_name = 'PublishYear' THEN REPLACE(modelhistory.field_name,'PublishYear','Tahun Terbit')
                     WHEN modelhistory.field_name = 'Title' THEN REPLACE(modelhistory.field_name,'Title','Judul')
                     WHEN modelhistory.field_name = 'Publisher' THEN REPLACE(modelhistory.field_name,'Publisher','Penerbit')
                     WHEN modelhistory.field_name = 'PhysicalDescription' THEN REPLACE(modelhistory.field_name,'PhysicalDescription','Deskripsi Fisik')
                     ELSE REPLACE(modelhistory.field_name,'PublishLocation','Tempat Terbit')
                    END
                    , ':', IFNULL(REPLACE(modelhistory.old_value, '   ',' '),'(kosong)'), '->', IFNULL(LTRIM(modelhistory.new_value), '(kosong)'))
                   END AS actions
                   FROM modelhistory 
                   LEFT JOIN users ON modelhistory.user_id = users.ID 
                   LEFT JOIN catalogs ON catalogs.ID = modelhistory.field_id 
                   WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $periode2 = $periode2;
    $format_hari = $periode;
    
        if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';} 

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;
// $test = self::getRealNameKriteria($kriterias);

    $filename = 'Laporan_kinerja_user_data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="6">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Kinerja User').' '.$periode2.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th style="vertical-align: center;">No.</th>
                <th style="vertical-align: center;">'.yii::t('app','Tanggal').'</th>
                <th style="vertical-align: center;">Username / <br /> Full name</th>
                <th style="vertical-align: center;">'.yii::t('app','Jenis Aktifitas').'</th>
                <th style="vertical-align: center;">'.yii::t('app','ID Data').'</th>
                <th style="vertical-align: center;">'.yii::t('app','Deskripsi').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: center;">'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Kataloger'].'</td>
                    <td style="vertical-align: center;">'.$data['nama_kriteria'].'</td>
                    <td style="vertical-align: center;">'.$data['id_record'].'</td>
                    <td align="left">'.$data['actions'].'</td>
                </tr>
            ';
         $no++;
        endforeach; 
    echo '</table>';

}

public function actionExportExcelOdtKinerjaUserData()
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
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di buat) ');
                break;

            case '1':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di mutakhirkan) ');
                break;

            case '2':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di hapus) ');
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }


           $sql = "SELECT DATE_FORMAT(modelhistory.date,'%d-%M-%Y %h:%i:%s') Periode,
                users.UserName AS Kataloger, 
                users.Fullname AS Kataloger1, 
                modelhistory.field_id AS f_id, 
                modelhistory.type AS kriteria, 
                CASE 
                 WHEN modelhistory.type = '0' THEN 'Entri'
                 WHEN modelhistory.type = '1' THEN 'Koreksi' 
                 ELSE 'Hapus' 
                END AS nama_kriteria, 
                modelhistory.field_id AS id_record, 
                CASE 
                 WHEN modelhistory.type = '0' THEN CONCAT(modelhistory.field_name, ':', modelhistory.new_value) 
                 ELSE
                CONCAT(
                CASE
                 WHEN modelhistory.field_name = 'PublishYear' THEN REPLACE(modelhistory.field_name,'PublishYear','Tahun Terbit')
                 WHEN modelhistory.field_name = 'Title' THEN REPLACE(modelhistory.field_name,'Title','Judul')
                 WHEN modelhistory.field_name = 'Publisher' THEN REPLACE(modelhistory.field_name,'Publisher','Penerbit')
                 WHEN modelhistory.field_name = 'PhysicalDescription' THEN REPLACE(modelhistory.field_name,'PhysicalDescription','Deskripsi Fisik')
                 ELSE REPLACE(modelhistory.field_name,'PublishLocation','Tempat Terbit')
                END
                , ':', IFNULL(REPLACE(modelhistory.old_value, '   ',' '),'(kosong)'), '->', IFNULL(LTRIM(modelhistory.new_value), '(kosong)')) 
                END AS actions
                FROM modelhistory 
                LEFT JOIN users ON modelhistory.user_id = users.ID 
                LEFT JOIN catalogs ON catalogs.ID = modelhistory.field_id 
                WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';} 


    $DetailFilterKriteria = $DetailFilter['kriteria'];
    $DetailFilterKataloger = $DetailFilter['kataloger'];

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area
    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Kataloger'=>$model['Kataloger'], 'Kataloger1'=>$model['Kataloger1'], 'f_id'=>$model['f_id'], 'kriteria'=>$model['kriteria'], 'nama_kriteria'=>$model['nama_kriteria'], 
                        'id_record'=>$model['id_record'], 'actions'=>$model['actions'], 'title'=>$model['title'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'a'=>$a,
        'dan'=>$dan,
        'DetailFilterKriteria'=>$DetailFilterKriteria,
        'DetailFilterKataloger'=>$DetailFilterKataloger,
        );

    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'kinerja_user'=> yii::t('app','Kinerja User'),
        'tanggal'=> yii::t('app','Tanggal'),
        'deskripsi'=> yii::t('app','Deskripsi'),
        'jenis_aktifitas'=> yii::t('app','Jenis Aktifitas'),
        'id_data'=> yii::t('app','ID Data'),
        );
    
// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/katalog/laporan-katalog-kinerja-user-data.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'Laporan_kinerja_user_data.ods');
    // !Open Office Calc Area


}

public function actionExportWordKinerjaUserData()
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
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');'';
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');'';
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = ' (Kriteria di buat) ';
                break;

            case '1':
                $DetailFilter['kriteria'] = ' (Kriteria di mutakhirkan) ';
                break;

            case '2':
                $DetailFilter['kriteria'] = ' (Kriteria di hapus) ';
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }

            $sql = "SELECT DATE_FORMAT(modelhistory.date,'%d-%M-%Y %h:%i:%s') Periode, 
                   CONCAT(users.UserName, ' / <br />', users.Fullname) AS Kataloger, 
                   modelhistory.field_id AS f_id, modelhistory.type AS kriteria, 
                   CASE 
                    WHEN modelhistory.type = '0' THEN 'Entri'
                    WHEN modelhistory.type = '1' THEN 'Koreksi' 
                    ELSE 'Hapus' 
                   END AS nama_kriteria, 
                   modelhistory.field_id AS id_record, 
                   CASE 
                    WHEN modelhistory.type = '0' THEN CONCAT(modelhistory.field_name, ':', modelhistory.new_value) 
                    ELSE
                    CONCAT(
                    CASE
                     WHEN modelhistory.field_name = 'PublishYear' THEN REPLACE(modelhistory.field_name,'PublishYear','Tahun Terbit')
                     WHEN modelhistory.field_name = 'Title' THEN REPLACE(modelhistory.field_name,'Title','Judul')
                     WHEN modelhistory.field_name = 'Publisher' THEN REPLACE(modelhistory.field_name,'Publisher','Penerbit')
                     WHEN modelhistory.field_name = 'PhysicalDescription' THEN REPLACE(modelhistory.field_name,'PhysicalDescription','Deskripsi Fisik')
                     ELSE REPLACE(modelhistory.field_name,'PublishLocation','Tempat Terbit')
                    END
                    , ':', IFNULL(REPLACE(modelhistory.old_value, '   ',' '),'(kosong)'), '->', IFNULL(LTRIM(modelhistory.new_value), '(kosong)')) 
                   END AS actions
                   FROM modelhistory 
                   LEFT JOIN users ON modelhistory.user_id = users.ID 
                   LEFT JOIN catalogs ON catalogs.ID = modelhistory.field_id 
                   WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $periode2 = $periode2;
    $format_hari = $periode;
    
        if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';} 

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;
// $test = self::getRealNameKriteria($kriterias);

    $type = $_GET['type'];
    $filename = 'Laporan_kinerja_user_data.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
               <p align="center"> <b>'.yii::t('app','Laporan Detail Data').' '.$format_hari.' </b></p>
               <p align="center"> <b>'.yii::t('app','Kinerja User').' '.$periode2.' </b></p>
            ';
    echo '</table>';
    if ($type == 'odt') {
    echo '<table border="0" align="center" width="700"> ';
    }else{echo '<table border="1" align="center" width="700"> ';}
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>Username / Full Name</th>
                <th>'.yii::t('app','Jenis Aktifitas').'</th>
                <th>'.yii::t('app','ID Data').'</th>
                <th>'.yii::t('app','Deskripsi').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Kataloger'].'</td>
                    <td>'.$data['nama_kriteria'].'</td>
                    <td>'.$data['id_record'].'</td>
                    <td>'.$data['actions'].'</td>
                </tr>
            ';
         $no++;
        endforeach; 
    echo '</table>';

}

public function actionExportPdfKinerjaUserData()
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = ' (Kriteria di buat) ';
                break;

            case '1':
                $DetailFilter['kriteria'] = ' (Kriteria di mutakhirkan) ';
                break;

            case '2':
                $DetailFilter['kriteria'] = ' (Kriteria di hapus) ';
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }


           $sql = "SELECT DATE_FORMAT(modelhistory.date,'%d-%M-%Y %h:%i:%s') Periode, 
                   CONCAT(users.UserName, ' / <br />', users.Fullname) AS Kataloger, 
                   modelhistory.field_id AS f_id, modelhistory.type AS kriteria, 
                   CASE 
                    WHEN modelhistory.type = '0' THEN 'Entri'
                    WHEN modelhistory.type = '1' THEN 'Koreksi' 
                    ELSE 'Hapus' 
                   END AS nama_kriteria, 
                   modelhistory.field_id AS id_record, 
                   CASE 
                     WHEN modelhistory.type = '0' THEN CONCAT(modelhistory.field_name, ':', modelhistory.new_value) 
                     ELSE
                    CONCAT(
                    CASE
                     WHEN modelhistory.field_name = 'PublishYear' THEN REPLACE(modelhistory.field_name,'PublishYear','Tahun Terbit')
                     WHEN modelhistory.field_name = 'Title' THEN REPLACE(modelhistory.field_name,'Title','Judul')
                     WHEN modelhistory.field_name = 'Publisher' THEN REPLACE(modelhistory.field_name,'Publisher','Penerbit')
                     WHEN modelhistory.field_name = 'PhysicalDescription' THEN REPLACE(modelhistory.field_name,'PhysicalDescription','Deskripsi Fisik')
                     ELSE REPLACE(modelhistory.field_name,'PublishLocation','Tempat Terbit')
                    END
                   , ':', IFNULL(REPLACE(modelhistory.old_value, '   ',' '),'(kosong)'), '->', IFNULL(LTRIM(modelhistory.new_value), '(kosong)'))
                   END AS actions
                   FROM modelhistory 
                   LEFT JOIN users ON modelhistory.user_id = users.ID 
                   LEFT JOIN catalogs ON catalogs.ID = modelhistory.field_id 
                   WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

        if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';} 


        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['a'] = $a; 
        $content['dan'] = $dan;
        $content['DetailFilter'] = $DetailFilter;
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" />'];
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
        $content = $this->renderPartial('pdf-view-katalog-kinerja-user-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_kinerja_user_data.pdf', 'D');

    }


// /////////////////////////////////batas view_data dgn view_vrekuensi//////////////////////////////////// //     


public function actionRenderPdfKatalogPerkriteriaFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 =yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT ".$periode_kataloger.",
                    users.username AS Kataloger,
                    COUNT(Title) AS Jumlah 
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'),catalogs.CreateBy ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                }      
            }

            if (isset($_POST['location'])) {
            foreach ($_POST['location'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }

            $sql = " SELECT locations.Name AS Kataloger,
                    COUNT(catalogs.`ID`) AS Jumlah,
                    ".$periode_location."
                    FROM catalogs
                    INNER JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN locations ON locations.`ID` = collections.`Location_id`
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                }   
            }

            if (implode($_POST['kriterias']) == 'bahan_pustaka') {
                $sql = "SELECT a.Periode AS Periode,
                        a.Periode2 AS Tahun2, 
                        w.name AS BahanPustaka, 
                        a.jumlah AS Jumlah 
                        FROM 
                        (
                        SELECT ".$periode_bahan_pustaka.",
                        DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') Periode2,
                        worksheet_id,
                        COUNT(worksheet_id) jumlah 
                        FROM catalogs 
                        LEFT JOIN worksheets ON worksheets.ID = catalogs.Worksheet_id
                        WHERE catalogs.CreateDate ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'), worksheet_id 
                    ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC, DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),worksheet_id) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } 
                }
 

             if (implode($_POST['kriterias']) == 'subjek' || isset($_POST['no_klas'])) {
                if (implode($_POST['kriterias']) == 'no_klas') {
                foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(cat.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR cat.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(cat.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
                }

                $sql = "SELECT
                        ".$periode_no_klass.",
                        IFNULL(master_kelas_besar.namakelas,'Lainnya') AS kelas, 
                        cat.Subject AS sub,
                        COUNT(cat.Title) AS CountJudul, 
                        SUM(test.test_count) AS Jumlah 
                        FROM catalogs cat 
                        LEFT JOIN (SELECT catalogs.ID AS test_id, COUNT(collections.Catalog_id) AS test_count 
                        FROM catalogs LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                        GROUP BY catalogs.ID) test ON test.test_id = cat.ID
                        LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(cat.DeweyNo,1,1)
                        WHERE DATE_FORMAT(cat.CreateDate,'%Y-%m-%d') ";
                $sql .= $sqlPeriode;
                $sql.= $andValue;
                if ($_POST['periode'] == "harian"){ 
                      $sql.= "GROUP BY DATE_FORMAT(cat.CreateDate,'%Y-%m-%d'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";    
                } elseif ($_POST['periode'] == "bulanan") {
                      $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%M-%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                } else{
                       $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                }
            }

            // print_r($a);
            // die;

            if (implode($_POST['kriterias']) == 'judul') {
                $sql = "SELECT catalogs.Title AS Kataloger, 
                        COUNT(catalogs.Title) AS Jumlah,
                        ".$periode_judul."
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y') ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } 
                }

        
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($value);
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan;  
       // $content['isi_berdasarkan'] = $isi_kriteria;
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
            'content' => $this->renderPartial('pdf-view-katalog-perkriteria-frekuensi', $content),
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

public function actionExportExcelKatalogPerkriteriaFrekuensi()
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
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 =yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT ".$periode_kataloger.",
                    users.username AS Kataloger,
                    COUNT(Title) AS Jumlah 
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'),catalogs.CreateBy ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                }      
            }

            if (isset($_POST['location'])) {
            foreach ($_POST['location'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Location_id = "'.addslashes($value).'" ';
                }
            }
            $sql = " SELECT locations.Name AS Kataloger,
                    COUNT(catalogs.`ID`) AS Jumlah,
                    ".$periode_location."
                    FROM catalogs
                    INNER JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN locations ON locations.`ID` = collections.`Location_id`
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                }   
            }
            if (implode($_POST['kriterias']) == 'bahan_pustaka') {
                $sql = "SELECT a.Periode AS Periode,
                        a.Periode2 AS Tahun2, 
                        w.name AS BahanPustaka, 
                        a.jumlah AS Jumlah 
                        FROM 
                        (
                        SELECT ".$periode_bahan_pustaka.",
                        DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') Periode2,
                        worksheet_id,
                        COUNT(worksheet_id) jumlah 
                        FROM catalogs 
                        LEFT JOIN worksheets ON worksheets.ID = catalogs.Worksheet_id
                        WHERE catalogs.CreateDate ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'), worksheet_id 
                    ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC, DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),worksheet_id) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } 
                }

             if (implode($_POST['kriterias']) == 'subjek' || isset($_POST['no_klas'])) {
                if (implode($_POST['kriterias']) == 'no_klas') {
                foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(cat.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR cat.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(cat.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
                }

                $sql = "SELECT
                        ".$periode_no_klass.",
                        IFNULL(master_kelas_besar.namakelas,'Lainnya') AS kelas, 
                        cat.Subject AS sub,
                        COUNT(cat.Title) AS CountJudul, 
                        SUM(test.test_count) AS Jumlah 
                        FROM catalogs cat 
                        LEFT JOIN (SELECT catalogs.ID AS test_id, COUNT(collections.Catalog_id) AS test_count 
                        FROM catalogs LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                        GROUP BY catalogs.ID) test ON test.test_id = cat.ID
                        LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(cat.DeweyNo,1,1)
                        WHERE DATE_FORMAT(cat.CreateDate,'%Y-%m-%d') ";
                $sql .= $sqlPeriode;
                $sql.= $andValue;
                if ($_POST['periode'] == "harian"){ 
                      $sql.= "GROUP BY DATE_FORMAT(cat.CreateDate,'%Y-%m-%d'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";    
                } elseif ($_POST['periode'] == "bulanan") {
                      $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%M-%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                } else{
                       $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                }
            }

            // print_r($a);
            // die;

            if (implode($_POST['kriterias']) == 'judul') {
                $sql = "SELECT catalogs.Title AS Kataloger, 
                        COUNT(catalogs.Title) AS Jumlah,
                        ".$periode_judul."
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y') ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } 
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
    if (implode($_POST["kriterias"]) == "bahan_pustaka" || implode($_POST["kriterias"]) == "kataloger") {
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="4">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Katalog Perkriteria').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            ';
    }
if (implode($_POST["kriterias"]) == "bahan_pustaka"){
    echo '<table border="1" align="center">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Bahan Pustaka').'</th>
                <th>'.yii::t('app','Jumlah').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['BahanPustaka'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';
}elseif (implode($_POST["kriterias"]) == "kataloger"){
echo '<table border="1" align="center">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode').'</th>
                <th>'.yii::t('app','Kataloger').'</th>
                <th>'.yii::t('app','Jumlah').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Kataloger'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';
} elseif (implode($_POST['kriterias']) == 'subjek' ||  implode($_POST['kriterias']) == 'no_klas'){
$subjek = implode($_POST["kriterias"]) == 'subjek';
echo '<table border="0" align="center"> 
            <tr>
                <th colspan="'; if($subjek){echo "6";}else{echo "5";} echo'">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="'; if($subjek){echo "6";}else{echo "5";} echo'">'.yii::t('app','Katalog Perkriteria').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="'; if($subjek){echo "6";}else{echo "5";} echo'">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            ';
echo '<table border="1" align="center">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode').'</th>';
                if ($subjek) {echo '<th>'.yii::t('app','Subjek').'</th>';
                }echo'
                <th>'.yii::t('app','Kelas Besar').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        $CountJudul = 0;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>';
                    if ($subjek) {echo '<th>'.$data['sub'].'</th>';
                    }echo'
                    <td>'.$data['kelas'].'</td>
                    <td>'.$data['CountJudul'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $CountJudul = $CountJudul + $data['CountJudul'];
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td '; if($subjek){echo "colspan='4'";}else{echo "colspan='3'";}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$CountJudul.'
                        </td><td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';
} elseif ((implode($_POST['kriterias']) == 'judul') || (implode($_POST['kriterias']) == 'location')) {
echo '<table border="0" align="center"> 
            <tr>
                <th colspan="6">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Katalog Perkriteria').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            ';
echo '<table border="1" align="center">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode').'</th>
                <th colspan="3">'.yii::t('app',$Berdasarkan).'</th>
                <th>'.yii::t('app','Jumlah').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td colspan="3">'.$data['Kataloger'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="5" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';
}

}

public function actionExportExcelOdtKatalogPerkriteriaFrekuensi()
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
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 =yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT ".$periode_kataloger.",
                    users.username AS Kataloger,
                    COUNT(Title) AS Jumlah 
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'),catalogs.CreateBy ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                }      
            }

            if (isset($_POST['location'])) {
            foreach ($_POST['location'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Location_id = "'.addslashes($value).'" ';
                }
            }
            $sql = " SELECT locations.Name AS Kataloger,
                    COUNT(catalogs.`ID`) AS Jumlah,
                    ".$periode_location."
                    FROM catalogs
                    INNER JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN locations ON locations.`ID` = collections.`Location_id`
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                }   
            }

            if (implode($_POST['kriterias']) == 'bahan_pustaka') {
                $sql = "SELECT a.Periode AS Periode,
                        a.Periode2 AS Tahun2, 
                        w.name AS Kataloger, 
                        a.jumlah AS Jumlah 
                        FROM 
                        (
                        SELECT ".$periode_bahan_pustaka.",
                        DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') Periode2,
                        worksheet_id,
                        COUNT(worksheet_id) jumlah 
                        FROM catalogs 
                        LEFT JOIN worksheets ON worksheets.ID = catalogs.Worksheet_id
                        WHERE catalogs.CreateDate ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'), worksheet_id 
                    ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC, DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),worksheet_id) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } 
                }

             if (implode($_POST['kriterias']) == 'subjek' || isset($_POST['no_klas'])) {
                if (implode($_POST['kriterias']) == 'no_klas') {
                foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(cat.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR cat.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(cat.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
                }

                $sql = "SELECT
                        ".$periode_no_klass.",
                        IFNULL(master_kelas_besar.namakelas,'Lainnya') AS kelas, 
                        cat.Subject AS sub,
                        COUNT(cat.Title) AS CountJudul, 
                        SUM(test.test_count) AS Jumlah 
                        FROM catalogs cat 
                        LEFT JOIN (SELECT catalogs.ID AS test_id, COUNT(collections.Catalog_id) AS test_count 
                        FROM catalogs LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                        GROUP BY catalogs.ID) test ON test.test_id = cat.ID
                        LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(cat.DeweyNo,1,1)
                        WHERE DATE_FORMAT(cat.CreateDate,'%Y-%m-%d') ";
                $sql .= $sqlPeriode;
                $sql.= $andValue;
                if ($_POST['periode'] == "harian"){ 
                      $sql.= "GROUP BY DATE_FORMAT(cat.CreateDate,'%Y-%m-%d'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";    
                } elseif ($_POST['periode'] == "bulanan") {
                      $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%M-%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                } else{
                       $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                }
            }

            // print_r($a);
            // die;

            if (implode($_POST['kriterias']) == 'judul') {
                $sql = "SELECT catalogs.Title AS Kataloger, 
                        COUNT(catalogs.Title) AS Jumlah,
                        ".$periode_judul."
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";     
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y') ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } 
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = $inValue;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'CountJudul'=>$model['CountJudul'], 'Kataloger'=>$model['Kataloger'], 'Jumlah'=>$model['Jumlah'], 'kelas'=>$model['kelas'], 'sub'=>$model['sub'] );
        $JumlahCountJudul = $JumlahCountJudul + $model['CountJudul'];
        $Jumlah = $Jumlah + $model['Jumlah'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>yii::t('app',$Berdasarkan), 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlahCountJudul'=>$JumlahCountJudul,
        'TotalJumlah'=>$Jumlah,
        );

    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'katalog_perkriteria'=> yii::t('app','Katalog Perkriteria'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'subjek'=> yii::t('app','Subjek'),
        'kelas_besar'=> yii::t('app','Kelas Besar'),
        'jumlah_judul'=> yii::t('app','Jumlah Judul'),
        'jumlah'=> yii::t('app','Jumlah'),
        'jumlah_eksemplar'=> yii::t('app','Jumlah Eksemplar'),
        'tanggal'=> yii::t('app','Tanggal'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    // $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-sering-baca.ods'; 

    if (implode($_POST['kriterias']) == 'no_klas') {
        $template = Yii::getAlias('@uploaded_files').'/templates/laporan/katalog/laporan-katalog-perkriteria-kelasDCC.ods'; 
    }elseif(implode($_POST['kriterias']) == 'subjek'){
        $template = Yii::getAlias('@uploaded_files').'/templates/laporan/katalog/laporan-katalog-perkriteria-subjek-frekuensi.ods'; 
    }
    else{
        $template = Yii::getAlias('@uploaded_files').'/templates/laporan/katalog/laporan-katalog-perkriteria.ods'; 
    }

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-katalog-perkriteria-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordKatalogPerkriteriaFrekuensi()
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
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 =yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT ".$periode_kataloger.",
                    users.username AS Kataloger,
                    COUNT(Title) AS Jumlah 
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'),catalogs.CreateBy ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                }      
            }

            if (isset($_POST['location'])) {
            foreach ($_POST['location'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Location_id = "'.addslashes($value).'" ';
                }
            }
            $sql = " SELECT locations.Name AS Kataloger,
                    COUNT(catalogs.`ID`) AS Jumlah,
                    ".$periode_location."
                    FROM catalogs
                    INNER JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN locations ON locations.`ID` = collections.`Location_id`
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                }   
            }

            if (implode($_POST['kriterias']) == 'bahan_pustaka') {
                $sql = "SELECT a.Periode AS Periode,
                        a.Periode2 AS Tahun2, 
                        w.name AS BahanPustaka, 
                        a.jumlah AS Jumlah 
                        FROM 
                        (
                        SELECT ".$periode_bahan_pustaka.",
                        DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') Periode2,
                        worksheet_id,
                        COUNT(worksheet_id) jumlah 
                        FROM catalogs 
                        LEFT JOIN worksheets ON worksheets.ID = catalogs.Worksheet_id
                        WHERE catalogs.CreateDate ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'), worksheet_id 
                    ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC, DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),worksheet_id) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } 
                }

             if (implode($_POST['kriterias']) == 'subjek' || isset($_POST['no_klas'])) {
                if (implode($_POST['kriterias']) == 'no_klas') {
                foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(cat.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR cat.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(cat.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
                }

                $sql = "SELECT
                        ".$periode_no_klass.",
                        IFNULL(master_kelas_besar.namakelas,'Lainnya') AS kelas, 
                        cat.Subject AS sub, 
                        COUNT(cat.Title) AS CountJudul, 
                        SUM(test.test_count) AS Jumlah 
                        FROM catalogs cat 
                        LEFT JOIN (SELECT catalogs.ID AS test_id, COUNT(collections.Catalog_id) AS test_count 
                        FROM catalogs LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                        GROUP BY catalogs.ID) test ON test.test_id = cat.ID
                        LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(cat.DeweyNo,1,1)
                        WHERE DATE_FORMAT(cat.CreateDate,'%Y-%m-%d') ";
                $sql .= $sqlPeriode;
                $sql.= $andValue;
                if ($_POST['periode'] == "harian"){ 
                      $sql.= "GROUP BY DATE_FORMAT(cat.CreateDate,'%Y-%m-%d'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";    
                } elseif ($_POST['periode'] == "bulanan") {
                      $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%M-%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                } else{
                       $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                }
            }

            // print_r($a);
            // die;

            if (implode($_POST['kriterias']) == 'judul') {
                $sql = "SELECT catalogs.Title AS Kataloger, 
                        COUNT(catalogs.Title) AS Jumlah,
                        ".$periode_judul."
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";   
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y') ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } 
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

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
if (implode($_POST["kriterias"]) == "bahan_pustaka"){
    echo '<table border="0" align="center"  width="100%"> 
            <tr>
                <th colspan="4">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Katalog Perkriteria').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center" >';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode').'</th>
                <th>'.yii::t('app','Bahan Pustaka').'</th>
                <th>'.yii::t('app','Jumlah').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['BahanPustaka'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';
}elseif (implode($_POST["kriterias"]) == "kataloger"){
    echo '<table border="0" align="center"  width="100%"> 
            <tr>
                <th colspan="4">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Katalog Perkriteria').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
echo '<table border="1" align="center" >';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode').'</th>
                <th>'.yii::t('app','Kataloger').'</th>
                <th>'.yii::t('app','Jumlah').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Kataloger'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';
} elseif (implode($_POST["kriterias"]) == "subjek" || implode($_POST["kriterias"]) == 'no_klas'){
    echo '<table border="0" align="center" width="100%"> 
            <tr>
                <th colspan="';if(implode($_POST["kriterias"]) == "subjek"){echo "6";}else{echo "5";}echo '">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="';if(implode($_POST["kriterias"]) == "subjek"){echo "6";}else{echo "5";}echo '">'.yii::t('app','Katalog Perkriteria').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="';if(implode($_POST["kriterias"]) == "subjek"){echo "6";}else{echo "5";}echo '">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
echo '<table border="1" align="center" >';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode').'</th>';
                if(implode($_POST["kriterias"]) == "subjek"){echo '<th>Subjek</th>';}
                echo'
                <th>'.yii::t('app','Kelas Besar').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        $CountJudul = 0;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>';
                    if(implode($_POST["kriterias"]) == "subjek"){echo '<th>'.$data['sub'].'</th>';}
                    echo'
                    <td>'.$data['kelas'].'</td>
                    <td>'.$data['CountJudul'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $CountJudul = $CountJudul + $data['CountJudul'];
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="';if(implode($_POST["kriterias"]) == "subjek"){echo "4";}else{echo "3";}echo '" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$CountJudul.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';
} else {
echo '<table border="0" align="center" width="900"> 
            <tr>
                <th colspan="4">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Katalog Perkriteria').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
echo '<table border="1" align="center" width="600">';
    echo '
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode').'</th>
                <th>'.yii::t('app',$Berdasarkan).'</th>
                <th>'.yii::t('app','Jumlah').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Kataloger'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';
}

}
public function actionExportPdfKatalogPerkriteriaFrekuensi()
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%d-%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%M-%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_kataloger = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_location = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_bahan_pustaka = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_subjek = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_judul = 'DATE_FORMAT(catalogs.CreateDate,"%Y") Periode';
                $periode_no_klass = 'DATE_FORMAT(cat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
            $sql = "SELECT ".$periode_kataloger.",
                    users.username AS Kataloger,
                    COUNT(Title) AS Jumlah 
                    FROM catalogs 
                    INNER JOIN users ON catalogs.CreateBy = users.ID 
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'),catalogs.CreateBy ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), users.username ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC,catalogs.CreateBy";
                }      
            }

            if (isset($_POST['location'])) {
            foreach ($_POST['location'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Location_id = "'.addslashes($value).'" ';
                }
            }
            $sql = " SELECT locations.Name AS Kataloger,
                    COUNT(catalogs.`ID`) AS Jumlah,
                    ".$periode_location."
                    FROM catalogs
                    INNER JOIN collections ON collections.`Catalog_id` = catalogs.`ID`
                    LEFT JOIN locations ON locations.`ID` = collections.`Location_id`
                    WHERE catalogs.CreateDate ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate),collections.Location_id ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                }   
            }

            if (implode($_POST['kriterias']) == 'bahan_pustaka') {
                $sql = "SELECT a.Periode AS Periode,
                        a.Periode2 AS Tahun2, 
                        w.name AS BahanPustaka, 
                        a.jumlah AS Jumlah 
                        FROM 
                        (
                        SELECT ".$periode_bahan_pustaka.",
                        DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') Periode2,
                        worksheet_id,
                        COUNT(worksheet_id) jumlah 
                        FROM catalogs 
                        LEFT JOIN worksheets ON worksheets.ID = catalogs.Worksheet_id
                        WHERE catalogs.CreateDate ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'), DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d'), worksheet_id 
                    ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC, DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y'),worksheet_id) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), worksheets.Name ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC) a 
                    INNER JOIN worksheets w ON a. worksheet_id = w.id";
                } 
                }

             if (implode($_POST['kriterias']) == 'subjek' || isset($_POST['no_klas'])) {
                if (implode($_POST['kriterias']) == 'no_klas') {
                foreach ($_POST['no_klas'] as $key => $value) {
                    if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND (SUBSTRING(cat.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR cat.DeweyNo IS NULL) ';
                            }
                    else {
                        $andValue .= " AND SUBSTRING(cat.DeweyNo,1,1) =  '".addslashes($value)."' ";
                    }
                }
                }

                $sql = "SELECT
                        ".$periode_no_klass.",
                        IFNULL(master_kelas_besar.namakelas,'Lainnya') AS kelas,  
                        COUNT(cat.Title) AS CountJudul, 
                        SUM(test.test_count) AS Jumlah 
                        FROM catalogs cat 
                        LEFT JOIN (SELECT catalogs.ID AS test_id, COUNT(collections.Catalog_id) AS test_count 
                        FROM catalogs LEFT JOIN collections ON collections.Catalog_id = catalogs.ID
                        GROUP BY catalogs.ID) test ON test.test_id = cat.ID
                        LEFT JOIN master_kelas_besar ON SUBSTRING(master_kelas_besar.kdKelas,1,1) = SUBSTRING(cat.DeweyNo,1,1)
                        WHERE DATE_FORMAT(cat.CreateDate,'%Y-%m-%d') ";
                $sql .= $sqlPeriode;
                $sql.= $andValue;
                if ($_POST['periode'] == "harian"){ 
                      $sql.= "GROUP BY DATE_FORMAT(cat.CreateDate,'%Y-%m-%d'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";    
                } elseif ($_POST['periode'] == "bulanan") {
                      $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%M-%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                } else{
                       $sql .= "GROUP BY kelas, DATE_FORMAT(cat.CreateDate,'%Y'),SUBSTRING(master_kelas_besar.kdKelas,1,1) ";
                }
            }

            // print_r($a);
            // die;

            if (implode($_POST['kriterias']) == 'judul') {
                $sql = "SELECT catalogs.Title AS Kataloger, 
                        COUNT(catalogs.Title) AS Jumlah,
                        ".$periode_judul."
                        FROM catalogs 
                        WHERE DATE(catalogs.CreateDate) ";      
                $sql .= $sqlPeriode;
                if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(catalogs.CreateDate,'%d-%m-%Y') ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                    } else {
                    $sql .= " GROUP BY YEAR(catalogs.CreateDate), catalogs.Title ORDER BY DATE_FORMAT(catalogs.CreateDate,'%Y-%m-%d') DESC";
                } 
                }

        
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($value);
        }
        $Berdasarkan = implode(' dan ', $Berdasarkan);

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan;  
       // $content['isi_berdasarkan'] = $isi_kriteria;
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
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
        $content = $this->renderPartial('pdf-view-katalog-perkriteria-frekuensi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');
    }

public function actionRenderKinerjaUserFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di buat) ');
                break;

            case '1':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di mutakhirkan) ');
                break;

            case '2':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di hapus) ');
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }


           $sql = "SELECT ".$periode_format.",
                    users.UserName AS Kataloger,
                    COUNT(modelhistory.ID) AS Jumlah 
                    FROM modelhistory 
                    INNER JOIN users ON modelhistory.user_id = users.ID 
                    WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY modelhistory.date DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

        if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';} 


        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['a'] = $a; 
        $content['dan'] = $dan;
        $content['DetailFilter'] = $DetailFilter;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content['kop']) {
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" />'];
            $set = 55;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'marginTop' => $set,
            'marginRight' => 0,
            'marginLeft' => 0,
            'content' => $this->renderPartial('pdf-view-katalog-kinerja-user-frekuensi', $content),
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

public function actionExportExcelKinerjaUserFrekuensi()
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
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di buat) ');
                break;

            case '1':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di mutakhirkan) ');
                break;

            case '2':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di hapus) ');
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }


           $sql = "SELECT ".$periode_format.",
                    users.UserName AS Kataloger,
                    COUNT(modelhistory.ID) AS Jumlah 
                    FROM modelhistory 
                    INNER JOIN users ON modelhistory.user_id = users.ID 
                    WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY modelhistory.date DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $periode2 = $periode2;
    $format_hari = $periode;
    
        if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';} 

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
                <th colspan="4">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Kinerja User Peminjaman').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.$a.' '.$DetailFilter['kataloger'].' '.$dan.' '.$DetailFilter['kriteria'].'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Kataloger').'</th>
                <th>'.yii::t('app','Jumlah').'</th>
            </tr>
            ';
        $no = 1;
        $TotalJumlah = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Kataloger'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $TotalJumlah = $TotalJumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$TotalJumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtKinerjaUserFrekuensi()
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
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di buat) ');
                break;

            case '1':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di mutakhirkan) ');
                break;

            case '2':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di hapus) ');
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }


           $sql = "SELECT ".$periode_format.",
                    users.UserName AS Kataloger,
                    COUNT(modelhistory.ID) AS Jumlah 
                    FROM modelhistory 
                    INNER JOIN users ON modelhistory.user_id = users.ID 
                    WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY modelhistory.date DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';}


    $DetailFilterKriteria = $DetailFilter['kriteria'];
    $DetailFilterKataloger = $DetailFilter['kataloger'];

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area
    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Kataloger'=>$model['Kataloger'], 'Jumlah'=>$model['Jumlah'] );
            $TotalJumlah = $TotalJumlah + $model['Jumlah'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>yii::t('app',$Berdasarkan), 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlah'=>$TotalJumlah,
        'a'=>$a,
        'dan'=>$dan,
        'DetailFilterKriteria'=>$DetailFilterKriteria,
        'DetailFilterKataloger'=>$DetailFilterKataloger,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'kinerja_user'=> yii::t('app','Kinerja User'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal'=> yii::t('app','Tanggal'),
        'jumlah'=> yii::t('app','Jumlah'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/katalog/laporan-katalog-kinerja-user.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);
    
    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-katalog-kinerja-user-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordKinerjaUserFrekuensi()
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
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di buat) ');
                break;

            case '1':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di mutakhirkan) ');
                break;

            case '2':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di hapus) ');
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }


           $sql = "SELECT ".$periode_format.",
                    users.UserName AS Kataloger,
                    COUNT(modelhistory.ID) AS Jumlah 
                    FROM modelhistory 
                    INNER JOIN users ON modelhistory.user_id = users.ID 
                    WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY modelhistory.date DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

        if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = 'Berdasarkan';
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = 'dan';
        }else{ $dan = '';}

    $periode2 = $periode2;
    $format_hari = $periode;
    // $Berdasarkan = array();
    //     foreach ($_POST['kriterias'] as $key => $value) {
    //         $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
    //     }
    //     $Berdasarkan = implode(' dan ', $Berdasarkan);

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;
// $test = self::getRealNameKriteria($kriterias);

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
     echo '<table border="0" align="center"> 
               <p align="center"> <b>'.yii::t('app','Laporan Frekuensi').' '.$format_hari.' </b></p>
               <p align="center"> <b>'.yii::t('app','Kinerja User').' '.$periode2.' </b></p>
               <p align="center"> <b>'.$a.' '.$DetailFilter['kataloger'].' '.$dan.' '.$DetailFilter['kriteria'].'</b></p>
            ';
    echo '</table>';

    if ($type == 'odt') {
    echo '<table border="0" align="center" width="700"> ';
    }else{echo '<table border="1" align="center" width="700"> ';}
        echo '
            <tr style="margin-right: 50px; margin-left: 50px;">
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Kataloger').'</th>
                <th>'.yii::t('app','Jumlah').'</th>
            </tr>
            ';
        $no = 1;
        $TotalJumlah = 0;
        if ($type == 'odt') {
        echo '<table border="0" align="center"> ';
        }
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Kataloger'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $TotalJumlah = $TotalJumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$TotalJumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfKinerjaUserFrekuensi()
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','periode ').$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','periode ').date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode ').$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            if (isset($_POST['kataloger'])) {
            foreach ($_POST['kataloger'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND modelhistory.user_id  = '".$value."' ";
                    $DetailFilter['kataloger'] = "Kataloger "; 
                    }else{
                        $DetailFilter['kataloger'] = null;
                    }
                }
            }

            if (isset($_POST['kriteria'])) {
            foreach ($_POST['kriteria'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= " AND modelhistory.type = '".$value."' ";
                    }
                }
            } 

            switch (implode($_POST['kriteria'])) {
            case '0':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di buat) ');
                break;

            case '1':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di mutakhirkan) ');
                break;

            case '2':
                $DetailFilter['kriteria'] = yii::t('app',' (Kriteria di hapus) ');
                break;
            
            default:
                $DetailFilter['kriteria'] = null;
                break;
            }


           $sql = "SELECT ".$periode_format.",
                    users.UserName AS Kataloger,
                    COUNT(modelhistory.ID) AS Jumlah 
                    FROM modelhistory 
                    INNER JOIN users ON modelhistory.user_id = users.ID 
                    WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'catalogs'";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY modelhistory.date DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), users.UserName ORDER BY modelhistory.date DESC";
                } 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

        if(implode($_POST['kataloger']) != '0' || implode($_POST['kriteria']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(implode($_POST['kataloger']) != '0' && implode($_POST['kriteria']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';} 


        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['a'] = $a; 
        $content['dan'] = $dan;
        $content['DetailFilter'] = $DetailFilter;
        $content['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            // $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" />'];
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
        $content = $this->renderPartial('pdf-view-katalog-kinerja-user-frekuensi', $content);
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
        if ($kriterias == 'kataloger') 
        {
            $name = 'Kataloger';
        } 
        elseif ($kriterias == 'kriteria') 
        {
            $name = 'Kriteria';
        }
        elseif ($kriterias ==    'PublishYear') 
        {
            $name = 'Tahun Terbit';
        }
        elseif ($kriterias == 'bahan_pustaka') 
        {
            $name = 'Bahan Pustaka';
        }
        elseif ($kriterias == 'location') 
        {
            $name = 'Lokasi';
        }
        elseif ($kriterias == 'judul') 
        {
            $name = 'Judul';
        }
        elseif ($kriterias == 'location_library') 
        {
            $name = 'Lokasi Perpustakaan';
        }
        elseif ($kriterias == 'locations') 
        {
            $name = 'Lokasi';
        }
        elseif ($kriterias == 'subjek') 
        {
            $name = 'Subjek';
        }
        elseif ($kriterias == 'no_klas') 
        {
            $name = 'Kelas DDC';
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
        elseif ($kriterias == 'kategori') 
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
        else 
        {
            $name = ' ';
        }
        
        return $name;

    }
}
