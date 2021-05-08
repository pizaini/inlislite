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



class BacaDitempatController extends \yii\web\Controller
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

public function actionBerdasarkanKoleksi()
    {

    	$model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('berdasarkan-koleksi',[
        	'model' => $model,
        	]);
    }

public function actionKoleksiSeringBacaDitempat()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('koleksi-sering-baca-ditempat',[
            'model' => $model,
            ]);
    }

public function actionAnggotaSeringBacaDitempat()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('anggota-sering-baca-ditempat',[
            'model' => $model,
            ]);
    }  

public function actionNonAnggotaSeringBacaDitempat()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('non-anggota-sering-baca-ditempat',[
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
        if ($kriteria == 'PublishLocation')
        {
            $sql = 'SELECT SPLIT_STR(catalogs.PublishLocation,":", 1) AS selecter FROM catalogs GROUP BY selecter';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'selecter','selecter');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        else if ($kriteria == 'Publisher')
        {
            $sql = 'SELECT SPLIT_STR(catalogs.Publisher,",", 1) AS selecter FROM catalogs GROUP BY selecter';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'selecter','selecter');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        else if ($kriteria == 'Subject')
        {
            $sql = 'SELECT * FROM catalogs';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','Subject');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        else if ($kriteria == 'PublishYear')
        {
            $sql = 'SELECT * FROM catalogs';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','PublishYear');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        else if ($kriteria == 'location_library')
        {
            $options =  ArrayHelper::map(LocationLibrary::find()->orderBy('ID')->asArray()->all(),'ID',
                function($model) {
                    return $model['Name'];
                });
            array_unshift( $options, yii::t('app',' ---Semua---'));

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        else if ($kriteria == 'locations')
        {
            $options =  ArrayHelper::map(Locations::find()->orderBy('ID')->asArray()->all(),'ID',
                function($model) {
                    return $model['Name'];
                });

            //array_push( $options, "---Semua---");
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
            //var_dump($options2);
        }

        else if ($kriteria == 'collectionsources')
        {
            $options =  ArrayHelper::map(Collectionsources::find()->orderBy('ID')->asArray()->all(),'ID',
                function($model) {
                    return $model['Name'];
                });

            //array_push( $options, "---Semua---");
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
            //var_dump($options2);
        }

        else if ($kriteria == 'partners')
        {
            $options =  ArrayHelper::map(Partners::find()->orderBy('ID')->asArray()->all(),'ID',
                function($model) {
                    return $model['Name'];
                });

            //array_push( $options, "---Semua---");
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
            //var_dump($options2);
        }

        else if ($kriteria == 'currency')
        {
            $sql = 'SELECT * FROM currency';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'Currency',
                function($model) {
                    return $model['Currency'].' - '.$model['Description'];
                });
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        else if ($kriteria == 'harga')
        {

            $contentOptions = '<div class="input-group">'.
            Html::textInput($kriteria.'[]',$value = null,
                ['class' => 'form-control col-sm-4','style' => 'width: 100%;','type'=>'number']
                ).
            '<center class="input-group-addon"> s/d </center>'.
            Html::textInput('to'.$kriteria.'[]',$value = null,
                ['class' => 'form-control col-sm-4','style' => 'width: 100%;','type'=>'number']
                ).'</div>';
        }

        else if ($kriteria == 'collectioncategorys')
        {
            $sql = 'SELECT * FROM collectioncategorys';
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

        else if ($kriteria == 'collectionrules')
        {
            $sql = 'SELECT * FROM collectionrules';
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

        else if ($kriteria == 'worksheets')
        {
            $sql = 'SELECT * FROM worksheets';
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

        else if ($kriteria == 'collectionmedias')
        {
            $sql = 'SELECT * FROM collectionmedias';
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

        // else if ($kriteria == 'no_klas')
        // {
        //     $options = ArrayHelper::map(Masterkelasbesar::find()->where(['kriteria'=>$kriteria])->all(),'ID','namakelas');            
        //     $options[0] = " ---Semua---";
        //     asort($options);

        //     $contentOptions = '<div class="input-group">'.Html::dropDownList(  $kriteria.'[]',
        //         'selected option', $options, 
        //         ['class' => 'select2','style' => 'width: 100%;']
        //         ).'<center class="input-group-addon"> s/d </center>'.Html::dropDownList(  'to'.$kriteria.'[]',
        //         'selected option',  
        //         $options, 
        //         ['class' => 'select2','style' => 'width: 100%;']
        //         ).'</div>';
        // }

        else if ($kriteria == 'no_klas')
        {
            $sql = 'SELECT *, SUBSTR(master_kelas_besar.kdKelas,1,1) AS test FROM master_kelas_besar';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'test','namakelas');
            $options[null] = yii::t('app',' ---Semua---');
            $options[0] = " 000 - Karya Umum ";
            asort($options);
            $contentOptions = '<div class="input-group">'.Html::dropDownList(  $kriteria.'[]',
                'selected option', $options, 
                ['class' => 'select2','style' => 'width: 100%;']
                ).'<center class="input-group-addon"> s/d </center>'.Html::dropDownList(  'to'.$kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2','style' => 'width: 100%;']
                ).'</div>';
        }


        else if ($kriteria == 'no_panggil')
        {
            $options = ['dimulai_dengan' => 'Dimulai Dengan','tepat' => 'Tepat','diakhiri_dengan' => 'Diakhiri Dengan','salah_satu_isi' => 'Salah Satu Isi'];
            $options = array_filter($options);

            $contentOptions = '<div class="input-group">'.Html::dropDownList('ini'.$kriteria.'[]',
                'selected option', $options, 
                ['class' => 'select2','style' => 'width: 100%;']
                ).'<div class="input-group-addon"> : </div>'.Html::textInput($kriteria.'[]',$value = null,
                ['class' => 'form-control col-sm-4','style' => 'width: 400px;']
                ).'</div>';
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
public function actionLoadSelecterBerdasarkanKoleksi($i)
    {
        return $this->renderPartial('select-berdasarkan-koleksi',['i'=>$i]);
    }


public function actionShowPdf($tampilkan)
    {
      
        // session_start();
        $_SESSION['Array_POST_Filter'] = $_POST;

        if ($tampilkan == 'sering-baca-ditempat-frekuensi') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('sering-baca-ditempat-frekuensi').'">';
            echo "<iframe>";
        }

        if ($tampilkan == 'sering-baca-ditempat-data') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('sering-baca-ditempat-data').'">';
            echo "<iframe>";
        }

        if ($tampilkan == 'berdasarkan-koleksi-frekuensi') 
        {
            echo (count(array_filter($_POST['kriterias'])) != '' ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-berdasarkan-koleksi-frekuensi').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );
        } 
           
        if ($tampilkan == 'berdasarkan-koleksi-data') 
        {
            echo (count(array_filter($_POST['kriterias'])) != '' ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-berdasarkan-koleksi-data').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );
        }

        if ($tampilkan == 'anggota-sering-baca-ditempat-frekuensi') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('anggota-sering-baca-ditempat-frekuensi').'">';
            echo "<iframe>";
        }
        if ($tampilkan == 'anggota-sering-baca-ditempat-data') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('anggota-sering-baca-ditempat-data').'">';
            echo "<iframe>";
        }  
        if ($tampilkan == 'non-anggota-sering-baca-ditempat-frekuensi') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('non-anggota-sering-baca-ditempat-frekuensi').'">';
            echo "<iframe>";
        }
        if ($tampilkan == 'non-anggota-sering-baca-ditempat-data') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('non-anggota-sering-baca-ditempat-data').'">';
            echo "<iframe>";
        }     

    }

public function actionSeringBacaDitempatData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(bacaditempat.CreateDate),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(bacaditempat.CreateDate),";
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
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY MONTH(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY YEAR(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
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
            'content' => $this->renderPartial('pdf-sering-baca-ditempat-data', $content),
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

public function actionExportExcelSeringBacaData()
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
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(bacaditempat.CreateDate),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(bacaditempat.CreateDate),";
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
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY MONTH(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY YEAR(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
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
                <th colspan="6">'.yii::t('app','Laporan Deatil Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Koleksi Sering Dibaca di Tempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tgl. Baca').'</th>
                <th>'.yii::t('app','Nomer Induk').'</th>
                <th>'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Nomor Anggota / Kunjungan').'</th>
                <th>'.yii::t('app','Nama').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tgl_baca'].'</td>
                    <td>'.$data['NoInduk'].'</td>
                    <td>'.$data['DataBib'].'</td>
                    <td>'.$data['NoAnggota'].'</td>
                    <td>'.$data['nama'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtSeringBacaData()
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
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(bacaditempat.CreateDate),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(bacaditempat.CreateDate),";
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
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
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
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY MONTH(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
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
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY YEAR(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
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
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
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
        'koleksi_seringdibacaditempat'=> yii::t('app','Koleksi Sering Dibaca di Tempat'),
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
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-sering-baca-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Baca-ditempat-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordSeringBacaData()
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
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(bacaditempat.CreateDate),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(bacaditempat.CreateDate),";
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
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY MONTH(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY YEAR(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
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
                <th colspan="6">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Koleksi Sering Dibaca di Tempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tgl. Baca').'</th>
                <th>'.yii::t('app','Nomer Induk').'</th>
                <th>'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Nomor Anggota / Kunjungan').'</th>
                <th>'.yii::t('app','Nama').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tgl_baca'].'</td>
                    <td>'.$data['NoInduk'].'</td>
                    <td>'.$data['DataBib'].'</td>
                    <td>'.$data['NoAnggota'].'</td>
                    <td>'.$data['nama'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfSeringBacaData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Harian');
                $group_by = "DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'),";
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $group_by = "MONTH(bacaditempat.CreateDate),";
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y %H:%i:%s") tgl_baca';
                $periode = yii::t('app','Tahunan');
                $group_by = "YEAR(bacaditempat.CreateDate),";
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
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY MONTH(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, a.Periode ASC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.Jumlah),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID =
                    collections.Catalog_id WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY YEAR(bacaditempat.CreateDate), catalogs.ID ORDER BY Jumlah
                    DESC LIMIT ".$inValue.") a";
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    NoInduk AS NoInduk, 
                    catalogs.Title AS cat_titl,
                    a.Jumlah AS a_jum,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, 
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS nama 
                    FROM bacaditempat 
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, catalogs.ID AS m_id, COUNT(bacaditempat.collection_id) AS Jumlah FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." GROUP BY ".$group_by." catalogs.ID 
                    ORDER BY Jumlah DESC) a ON a.m_id = catalogs.ID
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             $sql .= " ORDER BY a_jum DESC, cat_titl ASC LIMIT ";
             $sql .= $data2['unt_limit'];
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
        $content = $this->renderPartial('pdf-sering-baca-ditempat-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

    }

public function actionRenderBerdasarkanKoleksiData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $join='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
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
        // if (isset($_POST['PublishLocation'])) {
        //     foreach ($_POST['PublishLocation'] as $key => $value) {
        //         if ($value != "0" ) {
        //             $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
        //             }
        //         }
        //     $join = 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
        //     $group = ',PublishLocation';
        //     }

            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
                }
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
                }
            }           

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }

        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
        }



           $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan, 
                    locations.Name AS LokasiRuang, 
                    NoInduk AS NoInduk, 
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS Nama 
                    FROM bacaditempat 
                    INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    LEFT JOIN location_library ON collections.Location_Library_id = location_library.ID 
                    LEFT JOIN locations ON collections.Location_id = locations.ID ";


        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY bacaditempat.CreateDate';
            /*echo"<pre>";
            print_r($_POST);
                        echo" isi dari sql ".$sql;
            echo"</pre>";
            die;*/

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
         
        //     $Berdasarkan .= ' (' .implode($_POST[implode($_POST['kriterias'])]). ')';
        // }

        // $Berdasarkan = implode(' dan ', $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
        //     $test = collectioncategorys::findOne(implode($_POST[implode($_POST['kriterias'])]))->Name; 
        //     $Berdasarkan .= ' (' .$test. ')';
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
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
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
            'marginRight' => 0,
            'marginLeft' => 0,
            'content' => $this->renderPartial('pdf-berdasarkan-koleksi-data', $content),
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

public function actionExportExcelDataBerdasarkanKoleksi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $join='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
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
        // if (isset($_POST['PublishLocation'])) {
        //     foreach ($_POST['PublishLocation'] as $key => $value) {
        //         if ($value != "0" ) {
        //             $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
        //             }
        //         }
        //     $join = 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
        //     $group = ',PublishLocation';
        //     }

            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
                }
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
                }
            }           

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }
            
        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
        }



           $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan, 
                    locations.Name AS LokasiRuang, 
                    NoInduk AS NoInduk, 
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS Nama 
                    FROM bacaditempat 
                    INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    LEFT JOIN location_library ON collections.Location_Library_id = location_library.ID 
                    LEFT JOIN locations ON collections.Location_id = locations.ID ";
        
        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY bacaditempat.CreateDate';

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
                <th colspan="8">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Koleksi Baca di Tempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Baca').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Nomor Induk').'</th>
                <th>'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Nomor Anggota / Kunjungan').'</th>
                <th>'.yii::t('app','Nama').'Nama</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tgl_baca'].'</td>
                    <td>'.$data['LokasiPerpustakaan'].'</td>
                    <td>'.$data['LokasiRuang'].'</td>
                    <td>'.$data['NoInduk'].'</td>
                    <td>'.$data['DataBib'].'</td>
                    <td>'.$data['NoAnggota'].'</td>
                    <td>'.$data['Nama'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtBerdasarkanKoleksiData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $join='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
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
        // if (isset($_POST['PublishLocation'])) {
        //     foreach ($_POST['PublishLocation'] as $key => $value) {
        //         if ($value != "0" ) {
        //             $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
        //             }
        //         }
        //     $join = 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
        //     $group = ',PublishLocation';
        //     }

            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
                }
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
                }
            }           

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }

        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
        }



           $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan, 
                    locations.Name AS LokasiRuang, 
                    NoInduk AS NoInduk, 
                    catalogs.Title AS data,
                    CASE 
                    WHEN worksheets.ID <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 
                     THEN CONCAT('',catalogs.Edition) 
                     ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('',EDISISERIAL) ELSE '' END) 
                    END AS data2,
                    catalogs.PublishLocation AS data3,
                    catalogs.Publisher AS data4,
                    catalogs.PublishYear AS data5,
                    catalogs.Subject AS data6,
                    catalogs.DeweyNo AS data7,
                    members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS Nama 
                    FROM bacaditempat 
                    INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    LEFT JOIN location_library ON collections.Location_Library_id = location_library.ID 
                    LEFT JOIN locations ON collections.Location_id = locations.ID ";
        
        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY bacaditempat.CreateDate';

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');


    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'LokasiPerpustakaan'=>$model['LokasiPerpustakaan'], 'LokasiRuang'=>$model['LokasiRuang'], 'NoInduk'=>$model['NoInduk']
                        , 'data'=>$model['data'], 'data2'=>$model['data2'], 'data3'=>$model['data3'], 'data4'=>$model['data4'], 'data5'=>$model['data5'], 'data6'=>$model['data6'], 'data7'=>$model['data7'] , 'NoAnggota'=>$model['NoAnggota'], 'Nama'=>$model['Nama'] );

    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'koleksit_bacaditempat'=> yii::t('app','Koleksi Baca di Tempat'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_baca'=> yii::t('app','Tanggal Baca'),
        'lokasi_perpustakaan'=> yii::t('app','Lokasi Perpustakaan'),
        'lokasi_ruang'=> yii::t('app','Lokasi Ruang'),
        'nomor_induk'=> yii::t('app','Nomor Induk'),
        'data_bibliografis'=> yii::t('app','Data Bibliografis'),
        'nomor_anggotakunjungan'=> yii::t('app','Nomor Anggota / Kunjungan'),
        'nama'=> yii::t('app','Nama'),
        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-berdasarkan-koleksi-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-baca-ditempat-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordDataBerdasarkanKoleksi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $join='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
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
        // if (isset($_POST['PublishLocation'])) {
        //     foreach ($_POST['PublishLocation'] as $key => $value) {
        //         if ($value != "0" ) {
        //             $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
        //             }
        //         }
        //     $join = 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
        //     $group = ',PublishLocation';
        //     }

            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
                }
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
                }
            }           

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }

        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
        }



           $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan, 
                    locations.Name AS LokasiRuang, 
                    NoInduk AS NoInduk, 
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS Nama 
                    FROM bacaditempat 
                    INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    LEFT JOIN location_library ON collections.Location_Library_id = location_library.ID 
                    LEFT JOIN locations ON collections.Location_id = locations.ID ";
        
        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY bacaditempat.CreateDate';

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
    echo '<table border="0" align="center" width="700"> 
            <tr>
                <th colspan="8">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Koleksi Baca di Tempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Baca').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Nomor Induk').'</th>
                <th>'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Nomor Anggota / Kunjungan').'</th>
                <th>'.yii::t('app','Nama').'Nama</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tgl_baca'].'</td>
                    <td>'.$data['LokasiPerpustakaan'].'</td>
                    <td>'.$data['LokasiRuang'].'</td>
                    <td>'.$data['NoInduk'].'</td>
                    <td>'.$data['DataBib'].'</td>
                    <td>'.$data['NoAnggota'].'</td>
                    <td>'.$data['Nama'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfDataBerdasarkanKoleksi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $join='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") tgl_baca';
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
        // if (isset($_POST['PublishLocation'])) {
        //     foreach ($_POST['PublishLocation'] as $key => $value) {
        //         if ($value != "0" ) {
        //             $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
        //             }
        //         }
        //     $join = 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
        //     $group = ',PublishLocation';
        //     }

            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
                }
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
                }
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
                }
            }           

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }

        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
        }



           $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan, 
                    locations.Name AS LokasiRuang, 
                    NoInduk AS NoInduk, 
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib, members.MemberNo AS NoAnggota, 
                    (CASE WHEN bacaditempat.Member_id IS NULL THEN NoPengunjung ELSE members.FullName END) AS Nama 
                    FROM bacaditempat 
                    INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                    LEFT JOIN members ON bacaditempat.Member_id = members.ID 
                    LEFT JOIN location_library ON collections.Location_Library_id = location_library.ID 
                    LEFT JOIN locations ON collections.Location_id = locations.ID ";


        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' ORDER BY bacaditempat.CreateDate';
            /*echo"<pre>";
            print_r($_POST);
                        echo" isi dari sql ".$sql;
            echo"</pre>";
            die;*/

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
         
        //     $Berdasarkan .= ' (' .implode($_POST[implode($_POST['kriterias'])]). ')';
        // }

        // $Berdasarkan = implode(' dan ', $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
        //     $test = collectioncategorys::findOne(implode($_POST[implode($_POST['kriterias'])]))->Name; 
        //     $Berdasarkan .= ' (' .$test. ')';
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
        $content = $this->renderPartial('pdf-berdasarkan-koleksi-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');
}

public function actionAnggotaSeringBacaDitempatData() 
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

   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    catalogs.Title AS cat_titl,
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                    }

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

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
        $content['inValue'] = $inValue;
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
            'content' => $this->renderPartial('pdf-anggota-sering-baca-ditempat-data', $content),
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

public function actionExportExcelAnggotaSeringBacaDitempatData()
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
   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    catalogs.Title AS cat_titl,
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
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
                <th colspan="8">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Anggota Sering Baca Ditempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama Anggota').'</th>
                <th>'.yii::t('app','Nomor Induk').'</th>
                <th colspan="3">'.yii::t('app','Data Bibliografis').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: middle;">'.$no.'</td>
                    <td style="vertical-align: middle;">'.$data['Periode'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_anggota'].'</td>
                    <td style="vertical-align: middle;">'.$data['Nama'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_induk'].'</td>
                    <td colspan="3" style="text-align: left;">'.$data['DataBib'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtAnggotaSeringBacaDitempatData()
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

        $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    catalogs.Title AS cat_titl,
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT(catalogs.Title,'') AS data, 
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
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT(catalogs.Title,'') AS data, 
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
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT(catalogs.Title,'') AS data, 
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
                    FROM bacaditempat 
                    INNER JOIN (SELECT members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                    }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'data'=>$model['data'], 'data2'=>$model['data2'], 'data3'=>$model['data3'], 'data4'=>$model['data4'], 'data5'=>$model['data5'], 'data6'=>$model['data6'], 'data7'=>$model['data7'], 'no_induk'=>$model['no_induk'], 
            'no_anggota'=>$model['no_anggota'], 'Nama'=>$model['Nama']);
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'inValue'=>$inValue, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'anggota_seringbacaditempat'=> yii::t('app','Anggota Sering Baca Ditempat'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal'=> yii::t('app','Tanggal'),
        'nomor_anggota'=> yii::t('app','Nomor Anggota'),
        'nama_anggota'=> yii::t('app','Nama Anggota'),
        'nomor_induk'=> yii::t('app','Nomor Induk'),
        'data_bibliografis'=> yii::t('app','Data Bibliografis'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-anggota-sering-baca-ditempat-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-baca_ditempat-anggota-sering-meminjam-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordAnggotaSeringBacaDitempatData()
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
   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    catalogs.Title AS cat_titl,
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                    }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="8">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Anggota Sering Baca Ditempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama Anggota').'</th>
                <th>'.yii::t('app','Nomor Induk').'</th>
                <th colspan="3">'.yii::t('app','Data Bibliografis').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: middle;">'.$no.'</td>
                    <td style="vertical-align: middle;">'.$data['Periode'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_anggota'].'</td>
                    <td style="vertical-align: middle;">'.$data['Nama'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_induk'].'</td>
                    <td colspan="3" style="text-align: left;">'.$data['DataBib'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfAnggotaSeringBacaDitempatData() 
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

   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    catalogs.Title AS cat_titl,
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT bacaditempat.ID AS aID, COUNT(bacaditempat.collection_id) AS jum_eks FROM bacaditempat LEFT JOIN members
                    ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id =
                    catalogs.ID WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL GROUP BY bacaditempat.Member_id ORDER BY jum_eks DESC LIMIT 
                    ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT members.MemberNo AS no_anggota, members.ID AS m_id,
                    members.fullname AS Nama, COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS
                    jum_jud FROM bacaditempat LEFT JOIN members ON members.ID = bacaditempat.Member_id LEFT JOIN collections ON
                    collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.Member_id IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC) a ON a.m_id = bacaditempat.Member_id
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
            $sql .= $sqlPeriode;
            $sql .= ' AND bacaditempat.Member_id IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
            $sql .= $data2['unt_limit'];
                    }

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

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
        $content['inValue'] = $inValue;
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
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-anggota-sering-baca-ditempat-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

    }

public function actionNonAnggotaSeringBacaDitempatData() 
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

   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    bacaditempat.NoPengunjung AS no_pengunjung, 
                    memberguesses.Nama AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y') AS Periode, bacaditempat.NoPengunjung AS no_pengunjung, COUNT(bacaditempat.collection_id) AS jum_eks
                    FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL
                    GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC ) 
                    a on a.no_pengunjung = bacaditempat.NoPengunjung AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%d-%M-%Y')
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= $subjek;
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
        $sql .= $sqlPeriode;
        $sql .= ' AND bacaditempat.NoPengunjung IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
        $sql .= $data2['unt_limit'];
                    }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";
                    
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    bacaditempat.NoPengunjung AS no_pengunjung, 
                    memberguesses.Nama AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y') AS Periode, bacaditempat.NoPengunjung AS no_pengunjung, COUNT(bacaditempat.collection_id) AS jum_eks
                    FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL
                    GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC ) 
                    a on a.no_pengunjung = bacaditempat.NoPengunjung AND a.Periode = DATE_FORMAT(bacaditempat.CreateDate,'%M-%Y')
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= $subjek;
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
        $sql .= $sqlPeriode;
        $sql .= ' AND bacaditempat.NoPengunjung IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
        $sql .= $data2['unt_limit'];
                    }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    bacaditempat.NoPengunjung AS no_pengunjung, 
                    memberguesses.Nama AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT bacaditempat.NoPengunjung AS no_pengunjung, COUNT(bacaditempat.collection_id) AS jum_eks
                    FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC ) a on a.no_pengunjung = bacaditempat.NoPengunjung
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= $subjek;
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
        $sql .= $sqlPeriode;
        $sql .= ' AND bacaditempat.NoPengunjung IS NOT NULL ORDER BY jum_eks DESC, Nama ASC LIMIT ';
        $sql .= $data2['unt_limit'];
                    }

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

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
        $content['inValue'] = $inValue;
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
            'content' => $this->renderPartial('pdf-non-anggota-sering-baca-ditempat-data', $content),
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

public function actionExportExcelNonAnggotaSeringBacaDitempatData()
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
   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    bacaditempat.NoPengunjung AS no_pengunjung, 
                    memberguesses.Nama AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT bacaditempat.NoPengunjung AS no_pengunjung, COUNT(bacaditempat.collection_id) AS jum_eks
                    FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC ) a on a.no_pengunjung = bacaditempat.NoPengunjung
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= $subjek;
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
        $sql .= $sqlPeriode;
        $sql .= ' AND bacaditempat.NoPengunjung IS NOT NULL ORDER BY jum_eks DESC LIMIT ';
        $sql .= $data2['unt_limit'];

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
                <th colspan="8">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Non Anggota Sering Baca Ditempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Pengunjung').'</th>
                <th>'.yii::t('app','Nama Pengunjung').'</th>
                <th>'.yii::t('app','Nomor Induk').'</th>
                <th colspan="3">'.yii::t('app','Data Bibliografis').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: middle;">'.$no.'</td>
                    <td style="vertical-align: middle;">'.$data['Periode'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_pengunjung'].'</td>
                    <td style="vertical-align: middle;">'.$data['Nama'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_induk'].'</td>
                    <td colspan="3" style="text-align: left;">'.$data['DataBib'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtNonAnggotaSeringBacaDitempatData()
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

            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";} 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    bacaditempat.NoPengunjung AS no_pengunjung, 
                    memberguesses.Nama AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT(catalogs.Title,'') AS data, 
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
                    FROM bacaditempat 
                    INNER JOIN (SELECT bacaditempat.NoPengunjung AS no_pengunjung, COUNT(bacaditempat.collection_id) AS jum_eks
                    FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC ) a on a.no_pengunjung = bacaditempat.NoPengunjung
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= $subjek;
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
        $sql .= $sqlPeriode;
        $sql .= ' AND bacaditempat.NoPengunjung IS NOT NULL ORDER BY jum_eks DESC LIMIT ';
        $sql .= $data2['unt_limit'];

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'data'=>$model['data'], 'data2'=>$model['data2'], 'data3'=>$model['data3'], 'data4'=>$model['data4'], 'data5'=>$model['data5'], 'data6'=>$model['data6'], 'data7'=>$model['data7'], 'no_induk'=>$model['no_induk'], 
            'no_pengunjung'=>$model['no_pengunjung'], 'Nama'=>$model['Nama']);
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'inValue'=>$inValue, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'non_anggotaseringbacaditempat'=> yii::t('app','Non Anggota Sering Baca Ditempat'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal'=> yii::t('app','Tanggal'),
        'nomor_anggota'=> yii::t('app','Nomor Anggota'),
        'nama_anggota'=> yii::t('app','Nama Anggota'),
        'nomor_induk'=> yii::t('app','Nomor Induk'),
        'data_bibliografis'=> yii::t('app','Data Bibliografis'),
        );
// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-non-anggota-sering-baca-ditempat-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-baca_ditempat-non-anggota-sering-meminjam-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordNonAnggotaSeringBacaDitempatData()
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
   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";} 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    bacaditempat.NoPengunjung AS no_pengunjung, 
                    memberguesses.Nama AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT bacaditempat.NoPengunjung AS no_pengunjung, COUNT(bacaditempat.collection_id) AS jum_eks
                    FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC ) a on a.no_pengunjung = bacaditempat.NoPengunjung
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= $subjek;
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
        $sql .= $sqlPeriode;
        $sql .= ' AND bacaditempat.NoPengunjung IS NOT NULL ORDER BY jum_eks DESC LIMIT ';
        $sql .= $data2['unt_limit'];

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="8">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Non Anggota Sering Baca Ditempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="8">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Pengunjung').'</th>
                <th>'.yii::t('app','Nama Pengunjung').'</th>
                <th>'.yii::t('app','Nomor Induk').'</th>
                <th colspan="3">'.yii::t('app','Data Bibliografis').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: middle;">'.$no.'</td>
                    <td style="vertical-align: middle;">'.$data['Periode'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_pengunjung'].'</td>
                    <td style="vertical-align: middle;">'.$data['Nama'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_induk'].'</td>
                    <td colspan="3" style="text-align: left;">'.$data['DataBib'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfNonAnggotaSeringBacaDitempatData() 
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

   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}
            else{
            $sql2 = "SELECT IFNULL(SUM(a.jum_eks),0) AS unt_limit FROM (SELECT COUNT(bacaditempat.collection_id) AS jum_eks, COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                     LEFT JOIN collections ON collections.ID = bacaditempat.collection_id LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                     WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC LIMIT 
                     ".$inValue.") a";}

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT bacaditempat.CreateDate AS Periode, 
                    bacaditempat.NoPengunjung AS no_pengunjung, 
                    memberguesses.Nama AS Nama,
                    collections.NoInduk AS no_induk,
                    a.jum_eks AS jum_eks,
                    collections.Catalog_id AS cat_id,
                    CONCAT('<b>',catalogs.Title,'</b>','<br/>',(CASE WHEN catalogs.Worksheet_id <> 4 AND catalogs.Edition IS NOT NULL AND NOT LENGTH(catalogs.Edition) = 0 THEN CONCAT('<br/>',catalogs.Edition) ELSE (CASE WHEN EDISISERIAL IS NOT NULL THEN CONCAT('<br/>',EDISISERIAL) ELSE '' END) END),'<br/>',catalogs.PublishLocation,' ',catalogs.Publisher,' ',catalogs.PublishYear,'<br/>',IFNULL(catalogs.Subject,''),'<br/>',IFNULL(catalogs.DeweyNo,'')) AS DataBib
                    FROM bacaditempat 
                    INNER JOIN (SELECT bacaditempat.NoPengunjung AS no_pengunjung, COUNT(bacaditempat.collection_id) AS jum_eks
                    FROM bacaditempat LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID WHERE
                    DATE(bacaditempat.CreateDate) ".$sqlPeriode." AND bacaditempat.NoPengunjung IS NOT NULL
                    GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC ) a on a.no_pengunjung = bacaditempat.NoPengunjung
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID "; 
            $sql .= $subjek;
            $sql .= "WHERE DATE(bacaditempat.CreateDate) "; 
        
        $sql .= $sqlPeriode;
        $sql .= ' AND bacaditempat.NoPengunjung IS NOT NULL ORDER BY jum_eks DESC LIMIT ';
        $sql .= $data2['unt_limit'];

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

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
        $content['inValue'] = $inValue;
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
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-non-anggota-sering-baca-ditempat-data', $content);
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

public function actionRenderBerdasarkanKoleksiFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }


            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.PublishLocation LIKE '%".addslashes($value)."%' ";
                    }
            $group .= ', catalogs.PublishLocation';
                }
            $subjek = 'catalogs.PublishLocation AS Subjek';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Publisher LIKE "%'.addslashes($value).'%" ';
                    }
            $group .= ', catalogs.Publisher';
                }
            $subjek = 'catalogs.Publisher AS Subjek';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.PublishYear';
                }
            $subjek = 'catalogs.PublishYear AS Subjek';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
            $group .= ', location_library.Name';
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
                }
            $subjek = 'location_library.Name AS Subjek';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
            $group .= ', locations.Name';
            $join .= 'INNER JOIN locations ON bacaditempat.Location_Id = locations.ID ';
                }
            $subjek = 'locations.Name AS Subjek';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionsources.Name';
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            $subjek = 'collectionsources.Name AS Subjek';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $group .= ', partners.Name';
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            $subjek = 'partners.Name AS Subjek';
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $group .= ', currency.Currency';
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            $subjek = 'currency.Description AS Subjek';
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
            $group .= ', collections.Price';
                }
            $subjek = 'collections.Price AS Subjek';
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectioncategorys.Name';
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            $subjek = 'collectioncategorys.Name AS Subjek';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionrules.Name';
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            $subjek = 'collectionrules.Name AS Subjek';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', worksheets.Name';
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
                }
            $subjek = 'worksheets.Name AS Subjek';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionmedias.Name';
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            $subjek = 'collectionmedias.Name AS Subjek';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.Subject';
                }
            $subjek = 'catalogs.Subject AS Subjek';
            }           

            // if (isset($_POST['no_klas'])) {
            // foreach ($_POST['no_klas'] as $key => $value) {
            //     if ($value != "0" ) {
            //         $kelas = Masterkelasbesar::findOne(['id' => $value]);
            //         $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) BETWEEN "'.$kelas->kdKelas.'" AND "'.$kelas->kdKelas.'" ';
            //     }
            // $join .= 'INNER JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            //     }
            // }
            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            $subjek = 'IFNULL(master_kelas_besar.namakelas, "") AS Subjek';
            }
            
        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
            $subjek = 'catalogs.CallNumber AS Subjek';
        }



           $sql = "SELECT ".$periode_format.",
                    COUNT(*) AS CountEksemplar, 
                    ".$subjek.",
                    COUNT(DISTINCT bacaditempat.ID) AS JumlahAnggota,
                    (SELECT COUNT(DISTINCT bacaditempat.ID) FROM bacaditempat WHERE DATE(bacaditempat.CreateDate) ".$sqlPeriode.") AS opac
                    FROM bacaditempat
                    INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID ";


        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'),Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate),Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate),Subjek";
                }
        $sql .= $group;
        $sql .= " ORDER BY DATE_FORMAT(bacaditempat.CreateDate,'%Y-%m-%d') ASC";
            /*echo"<pre>";
            print_r($_POST);
                        echo" isi dari sql ".$sql;
            echo"</pre>";
            die;*/

        //$sql .= $group;
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
         
        //     $Berdasarkan .= ' (' .implode($_POST[implode($_POST['kriterias'])]). ')';
        // }

        // $Berdasarkan = implode(' dan ', $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
        //     $test = collectioncategorys::findOne(implode($_POST[implode($_POST['kriterias'])]))->Name; 
        //     $Berdasarkan .= ' (' .$test. ')';
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
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
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
            'content' => $this->renderPartial('pdf-berdasarkan-koleksi-frekuensi', $content),
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

public function actionExportBerdasarkanKoleksiFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }


            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.PublishLocation LIKE '%".addslashes($value)."%' ";
                    }
            $group .= ', catalogs.PublishLocation';
                }
            $subjek = 'catalogs.PublishLocation AS Subjek';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Publisher LIKE "%'.addslashes($value).'%" ';
                    }
            $group .= ', catalogs.Publisher';
                }
            $subjek = 'catalogs.Publisher AS Subjek';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.PublishYear';
                }
            $subjek = 'catalogs.PublishYear AS Subjek';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
            $group .= ', location_library.Name';
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
                }
            $subjek = 'location_library.Name AS Subjek';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
            $group .= ', locations.Name';
            $join .= 'INNER JOIN locations ON bacaditempat.Location_Id = locations.ID ';
                }
            $subjek = 'locations.Name AS Subjek';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionsources.Name';
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            $subjek = 'collectionsources.Name AS Subjek';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $group .= ', partners.Name';
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            $subjek = 'partners.Name AS Subjek';
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $group .= ', currency.Currency';
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            $subjek = 'currency.Description AS Subjek';
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
            $group .= ', collections.Price';
                }
            $subjek = 'collections.Price AS Subjek';
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectioncategorys.Name';
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            $subjek = 'collectioncategorys.Name AS Subjek';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionrules.Name';
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            $subjek = 'collectionrules.Name AS Subjek';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', worksheets.Name';
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
                }
            $subjek = 'worksheets.Name AS Subjek';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionmedias.Name';
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            $subjek = 'collectionmedias.Name AS Subjek';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.Subject';
                }
            $subjek = 'catalogs.Subject AS Subjek';
            }           

            // if (isset($_POST['no_klas'])) {
            // foreach ($_POST['no_klas'] as $key => $value) {
            //     if ($value != "0" ) {
            //         $kelas = Masterkelasbesar::findOne(['id' => $value]);
            //         $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) BETWEEN "'.$kelas->kdKelas.'" AND "'.$kelas->kdKelas.'" ';
            //     }
            // $join .= 'INNER JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            //     }
            // }

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            $subjek = 'IFNULL(master_kelas_besar.namakelas, "") AS Subjek';
            }

        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
            $subjek = 'catalogs.CallNumber AS Subjek';
        }



        $sql = "SELECT ".$periode_format.",
                COUNT(*) AS CountEksemplar, 
                ".$subjek.",
                COUNT(DISTINCT bacaditempat.ID) AS JumlahAnggota
                FROM bacaditempat
                INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID ";  

        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate), Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate), Subjek";
                }
        $sql .= $group;
        $sql .= " ORDER BY DATE_FORMAT(bacaditempat.CreateDate,'%Y-%m-%d') ASC";

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
                <th colspan="4">'.yii::t('app','Koleksi Baca di Tempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal').'</th>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<th>'.yii::t('app',$Berdasarkan).'</th>'; }
    echo'
                <th>'.yii::t('app','Jumlah Anggota').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahAnggota = 0;
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
                    <td>'.$data['JumlahAnggota'].'</td>
                </tr>
            ';
                        $JumlahAnggota = $JumlahAnggota + $data['JumlahAnggota'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahAnggota.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtBerdasarkanKoleksi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }


            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.PublishLocation LIKE '%".addslashes($value)."%' ";
                    }
            $group .= ', catalogs.PublishLocation';
                }
            $subjek = 'catalogs.PublishLocation AS Subjek';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Publisher LIKE "%'.addslashes($value).'%" ';
                    }
            $group .= ', catalogs.Publisher';
                }
            $subjek = 'catalogs.Publisher AS Subjek';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.PublishYear';
                }
            $subjek = 'catalogs.PublishYear AS Subjek';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
            $group .= ', location_library.Name';
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
                }
            $subjek = 'location_library.Name AS Subjek';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
            $group .= ', locations.Name';
            $join .= 'INNER JOIN locations ON bacaditempat.Location_Id = locations.ID ';
                }
            $subjek = 'locations.Name AS Subjek';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionsources.Name';
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            $subjek = 'collectionsources.Name AS Subjek';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $group .= ', partners.Name';
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            $subjek = 'partners.Name AS Subjek';
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $group .= ', currency.Currency';
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            $subjek = 'currency.Description AS Subjek';
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
            $group .= ', collections.Price';
                }
            $subjek = 'collections.Price AS Subjek';
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectioncategorys.Name';
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            $subjek = 'collectioncategorys.Name AS Subjek';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionrules.Name';
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            $subjek = 'collectionrules.Name AS Subjek';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', worksheets.Name';
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
                }
            $subjek = 'worksheets.Name AS Subjek';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionmedias.Name';
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            $subjek = 'collectionmedias.Name AS Subjek';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.Subject';
                }
            $subjek = 'catalogs.Subject AS Subjek';
            }           

            // if (isset($_POST['no_klas'])) {
            // foreach ($_POST['no_klas'] as $key => $value) {
            //     if ($value != "0" ) {
            //         $kelas = Masterkelasbesar::findOne(['id' => $value]);
            //         $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) BETWEEN "'.$kelas->kdKelas.'" AND "'.$kelas->kdKelas.'" ';
            //     }
            // $join .= 'INNER JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            //     }
            // }

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            $subjek = 'IFNULL(master_kelas_besar.namakelas, "") AS Subjek';
            }

        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
            $subjek = 'catalogs.CallNumber AS Subjek';
        }



        $sql = "SELECT ".$periode_format.",
                COUNT(*) AS CountEksemplar, 
                ".$subjek.",
                COUNT(DISTINCT bacaditempat.ID) AS JumlahAnggota
                FROM bacaditempat
                INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID ";  

        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate), Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate), Subjek";
                }
        $sql .= $group;
        $sql .= " ORDER BY DATE_FORMAT(bacaditempat.CreateDate,'%Y-%m-%d') ASC";

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
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Subjek'=>$model['Subjek'], 'JumlahAnggota'=>$model['JumlahAnggota'] );
            $JumlahAnggota = $JumlahAnggota + $model['JumlahAnggota'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlahAnggota'=>$JumlahAnggota,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'koleksi_bacaditempat'=> yii::t('app','Koleksi Baca di Tempat'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal'=> yii::t('app','Tanggal'),
        'jumlah_anggota'=> yii::t('app','Jumlah Anggota'),        
        );
// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
if (sizeof($_POST['kriterias']) == 1) {
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-berdasarkan-koleksi.ods'; 
}else{
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-berdasarkan-koleksi_no_subjek.ods'; 
}

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-baca-ditempat-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordBerdasarkanKoleksiFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }


            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.PublishLocation LIKE '%".addslashes($value)."%' ";
                    }
            $group .= ', catalogs.PublishLocation';
                }
            $subjek = 'catalogs.PublishLocation AS Subjek';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Publisher LIKE "%'.addslashes($value).'%" ';
                    }
            $group .= ', catalogs.Publisher';
                }
            $subjek = 'catalogs.Publisher AS Subjek';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.PublishYear';
                }
            $subjek = 'catalogs.PublishYear AS Subjek';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
            $group .= ', location_library.Name';
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
                }
            $subjek = 'location_library.Name AS Subjek';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
            $group .= ', locations.Name';
            $join .= 'INNER JOIN locations ON bacaditempat.Location_Id = locations.ID ';
                }
            $subjek = 'locations.Name AS Subjek';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionsources.Name';
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            $subjek = 'collectionsources.Name AS Subjek';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $group .= ', partners.Name';
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            $subjek = 'partners.Name AS Subjek';
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $group .= ', currency.Currency';
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            $subjek = 'currency.Description AS Subjek';
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
            $group .= ', collections.Price';
                }
            $subjek = 'collections.Price AS Subjek';
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectioncategorys.Name';
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            $subjek = 'collectioncategorys.Name AS Subjek';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionrules.Name';
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            $subjek = 'collectionrules.Name AS Subjek';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', worksheets.Name';
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
                }
            $subjek = 'worksheets.Name AS Subjek';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionmedias.Name';
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            $subjek = 'collectionmedias.Name AS Subjek';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.Subject';
                }
            $subjek = 'catalogs.Subject AS Subjek';
            }           

            // if (isset($_POST['no_klas'])) {
            // foreach ($_POST['no_klas'] as $key => $value) {
            //     if ($value != "0" ) {
            //         $kelas = Masterkelasbesar::findOne(['id' => $value]);
            //         $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) BETWEEN "'.$kelas->kdKelas.'" AND "'.$kelas->kdKelas.'" ';
            //     }
            // $join .= 'INNER JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            //     }
            // }

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            $subjek = 'IFNULL(master_kelas_besar.namakelas, "") AS Subjek';
            }

        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
            $subjek = 'catalogs.CallNumber AS Subjek';
        }



        $sql = "SELECT ".$periode_format.",
                COUNT(*) AS CountEksemplar, 
                ".$subjek.",
                COUNT(DISTINCT bacaditempat.ID) AS JumlahAnggota
                FROM bacaditempat
                INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID ";  

        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate), Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate), Subjek";
                }
        $sql .= $group;
        $sql .= " ORDER BY DATE_FORMAT(bacaditempat.CreateDate,'%Y-%m-%d') ASC";

        $model = Yii::$app->db->createCommand($sql)->queryAll();

    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

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
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="3"';} else {echo 'colspan="4"';}echo '>'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="3"';} else {echo 'colspan="4"';}echo '>'.yii::t('app','Koleksi Baca di Tempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="3"';} else {echo 'colspan="4"';}echo '>'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center" style="width: 100%;">
            <tr>
                <th>No.</td>
                <th>'.yii::t('app','Tanggal').'</th>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<th>'.yii::t('app',$Berdasarkan).'</th>'; }
    echo'
                <th>'.yii::t('app','Jumlah Anggota').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahAnggota = 0;
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
                    <td>'.$data['JumlahAnggota'].'</td>
                </tr>
            ';
                        $JumlahAnggota = $JumlahAnggota + $data['JumlahAnggota'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahAnggota.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfBerdasarkanKoleksiFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }


            if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.PublishLocation LIKE '%".addslashes($value)."%' ";
                    }
            $group .= ', catalogs.PublishLocation';
                }
            $subjek = 'catalogs.PublishLocation AS Subjek';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Publisher LIKE "%'.addslashes($value).'%" ';
                    }
            $group .= ', catalogs.Publisher';
                }
            $subjek = 'catalogs.Publisher AS Subjek';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.PublishYear';
                }
            $subjek = 'catalogs.PublishYear AS Subjek';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
            $group .= ', location_library.Name';
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
                }
            $subjek = 'location_library.Name AS Subjek';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND bacaditempat.Location_Id = '".addslashes($value)."' ";
                    }
            $group .= ', locations.Name';
            $join .= 'INNER JOIN locations ON bacaditempat.Location_Id = locations.ID ';
                }
            $subjek = 'locations.Name AS Subjek';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionsources.Name';
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            $subjek = 'collectionsources.Name AS Subjek';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
            $group .= ', partners.Name';
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
                }
            $subjek = 'partners.Name AS Subjek';
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    }
            $group .= ', currency.Currency';
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
                }
            $subjek = 'currency.Description AS Subjek';
            } 

            if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
            $group .= ', collections.Price';
                }
            $subjek = 'collections.Price AS Subjek';
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectioncategorys.Name';
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            $subjek = 'collectioncategorys.Name AS Subjek';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionrules.Name';
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            $subjek = 'collectionrules.Name AS Subjek';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', worksheets.Name';
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
                }
            $subjek = 'worksheets.Name AS Subjek';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $group .= ', collectionmedias.Name';
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            $subjek = 'collectionmedias.Name AS Subjek';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
            $group .= ', catalogs.Subject';
                }
            $subjek = 'catalogs.Subject AS Subjek';
            }           

            // if (isset($_POST['no_klas'])) {
            // foreach ($_POST['no_klas'] as $key => $value) {
            //     if ($value != "0" ) {
            //         $kelas = Masterkelasbesar::findOne(['id' => $value]);
            //         $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) BETWEEN "'.$kelas->kdKelas.'" AND "'.$kelas->kdKelas.'" ';
            //     }
            // $join .= 'INNER JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            //     }
            // }

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            $subjek = 'IFNULL(master_kelas_besar.namakelas, "") AS Subjek';
            }

        if (isset($_POST['no_panggil'])) {            
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    }
                }
            }
            $subjek = 'catalogs.CallNumber AS Subjek';
        }



           $sql = "SELECT ".$periode_format.",
                    COUNT(*) AS CountEksemplar, 
                    ".$subjek.",
                    COUNT(DISTINCT bacaditempat.ID) AS JumlahAnggota 
                    FROM bacaditempat
                    INNER JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID ";


        $sql .= $join;
        $sql .= 'WHERE DATE(bacaditempat.CreateDate) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate), Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate), Subjek";
                }
        $sql .= $group;
        $sql .= " ORDER BY DATE_FORMAT(bacaditempat.CreateDate,'%Y-%m-%d') ASC";
            /*echo"<pre>";
            print_r($_POST);
                        echo" isi dari sql ".$sql;
            echo"</pre>";
            die;*/

        //$sql .= $group;
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
         
        //     $Berdasarkan .= ' (' .implode($_POST[implode($_POST['kriterias'])]). ')';
        // }

        // $Berdasarkan = implode(' dan ', $Berdasarkan);

        // if (count($_POST['kriterias']) == 1 && implode($_POST[implode($_POST['kriterias'])]) !== "0") {
        //     $test = collectioncategorys::findOne(implode($_POST[implode($_POST['kriterias'])]))->Name; 
        //     $Berdasarkan .= ' (' .$test. ')';
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
        $content = $this->renderPartial('pdf-berdasarkan-koleksi-frekuensi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }


public function actionSeringBacaDitempatFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
                    COUNT(bacaditempat.collection_id) AS Jumlah,
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
                    FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate), ids ORDER BY Jumlah DESC, Periode ASC, DATA LIMIT ";
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
            'content' => $this->renderPartial('pdf-sering-baca-ditempat-frekuensi', $content),
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

public function actionExportExcelSeringBacaFrekuensi()
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
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
                    COUNT(bacaditempat.collection_id) AS Jumlah,
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
                    FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), ids ORDER BY Jumlah DESC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate), ids ORDER BY Jumlah DESC LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate), ids ORDER BY Jumlah DESC LIMIT ";
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
                <th colspan="6">'.yii::t('app','Koleksi Sering Dibaca di Tempat').' '.$periode2.'</th>
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

public function actionExportExcelOdtSeringBacaFrekuensi()
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
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
                    COUNT(bacaditempat.Member_id) AS Jumlah,
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
                    FROM bacaditempat
                    LEFT JOIN collections ON bacaditempat.Collection_id = collections.ID 
                    LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                    WHERE DATE(bacaditempat.CreateDate) "; 

             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), ids ORDER BY Jumlah DESC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate), ids ORDER BY Jumlah DESC LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate), ids  ORDER BY Jumlah DESC LIMIT ";
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
        'koleksi_seringdibacaditempat'=> yii::t('app','Koleksi Sering Dibaca di Tempat'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal'=> yii::t('app','Tanggal'),
        'data_bibliografis'=> yii::t('app','Data Bibliografis'),
        'jumlah_pembaca'=> yii::t('app','Jumlah Pembaca'),      
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-sering-baca.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Baca-ditempat-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordSeringBacaFrekuensi()
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
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
                    COUNT(bacaditempat.Member_id) AS Jumlah,
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
                    FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ";

            $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), ids ORDER BY Jumlah DESC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate), ids ORDER BY Jumlah DESC LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate), ids ORDER BY Jumlah DESC LIMIT ";
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
                <th colspan="5">'.yii::t('app','Koleksi Sering Dibaca di Tempat').' '.$periode2.'</th>
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
                <th colspan="3">'.yii::t('app','Data Bibliografis').'</th>
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

public function actionExportPdfSeringBacaFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
                    COUNT(bacaditempat.collection_id) AS Jumlah,
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
                    FROM bacaditempat 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), ids ORDER BY Jumlah DESC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(bacaditempat.CreateDate), ids ORDER BY Jumlah DESC LIMIT ";
                } else {
                    $sql .= " GROUP BY YEAR(bacaditempat.CreateDate), ids ORDER BY Jumlah DESC LIMIT ";
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
        $content = $this->renderPartial('pdf-sering-baca-ditempat-frekuensi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }

public function actionAnggotaSeringBacaDitempatFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
       

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    COUNT(bacaditempat.collection_id) AS jum_eks,
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud
                    FROM bacaditempat 
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id  ORDER BY jum_eks DESC, Nama ASC LIMIT ";
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
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'content' => $this->renderPartial('pdf-anggota-sering-baca-ditempat-frekuensi', $content),
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

public function actionExportExcelAnggotaSeringBacaDitempatFrekuensi()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
       

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    COUNT(bacaditempat.collection_id) AS jum_eks,
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud
                    FROM bacaditempat 
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id  ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                }
             $sql .= $inValue;  

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

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
                <th colspan="6">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Anggota Sering Baca Ditempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama Anggota').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar Dibaca').'</th>
                <th>'.yii::t('app','Jumlah Judul Dibaca').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahJudul = 0;
        $JumlahEksemplar = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['no_anggota'].'</td>
                    <td>'.$data['Nama'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                    <td>'.$data['jum_jud'].'</td>
                </tr>
            ';
                        $JumlahEksemplar = $JumlahEksemplar + $data['jum_eks'];
                        $JumlahJudul = $JumlahJudul + $data['jum_jud'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahEksemplar.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahJudul.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtAnggotaSeringBacaDitempatFrekuensi()
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
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    COUNT(bacaditempat.collection_id) AS jum_eks,
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud
                    FROM bacaditempat 
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id  ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                }
             $sql .= $inValue; 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++, 'Periode'=>$model['Periode'],'no_anggota'=>$model['no_anggota'], 'Nama'=>$model['Nama'], 'jum_eks'=>$model['jum_eks'], 'jum_jud'=>$model['jum_jud'] );
            $JumlahJudul = $JumlahJudul + $model['jum_jud'];
            $JumlahEksemplar = $JumlahEksemplar + $model['jum_eks'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'inValue'=>$inValue, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlahJudul'=>$JumlahJudul,
        'TotalJumlahEksemplar'=>$JumlahEksemplar,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'anggota_seringbacaditempat'=> yii::t('app','Anggota Sering Baca Ditempat'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal'=> yii::t('app','Tanggal'),
        'nomor_anggota'=> yii::t('app','Nomor Anggota'),
        'nama_anggota'=> yii::t('app','Nama Anggota'),
        'jumlah_eksemplardibaca'=> yii::t('app','Jumlah Eksemplar Dibaca'),
        'jumlah_juduldibaca'=> yii::t('app','Jumlah Judul Dibaca'),        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-anggota-sering-baca-ditempat-frekuensi.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-baca_ditempat-anggota-sering-baca-ditempat-frekuensi.ods');
    // !Open Office Calc Area
}

public function actionExportWordAnggotaSeringBacaDitempatFrekuensi()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
       

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    COUNT(bacaditempat.collection_id) AS jum_eks,
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud
                    FROM bacaditempat 
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id  ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                }
             $sql .= $inValue;  

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;
// $test = self::getRealNameKriteria($kriterias);

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="6">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Anggota Sering Baca Ditempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr style="margin-left: 10px; margin-right: 10px; ">
                <th>No.</th>
                <th'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama Anggota').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar Dibaca').'</th>
                <th>'.yii::t('app','Jumlah Judul Dibaca').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahJudul = 0;
        $JumlahEksemplar = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['no_anggota'].'</td>
                    <td>'.$data['Nama'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                    <td>'.$data['jum_jud'].'</td>
                </tr>
            ';
                        $JumlahJudul = $JumlahJudul + $data['jum_jud'];
                        $JumlahEksemplar = $JumlahEksemplar + $data['jum_eks'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahEksemplar.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahJudul.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfAnggotaSeringBacaDitempatFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
       

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    members.MemberNo AS no_anggota, 
                    members.fullname AS Nama,
                    COUNT(bacaditempat.collection_id) AS jum_eks,
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud
                    FROM bacaditempat 
                    LEFT JOIN members ON members.ID = bacaditempat.Member_id 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.Member_id ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.Member_id IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.Member_id  ORDER BY jum_eks DESC, Nama ASC LIMIT ";
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
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'options' => [
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-anggota-sering-baca-ditempat-frekuensi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }

public function actionNonAnggotaSeringBacaDitempatFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
       

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    bacaditempat.NoPengunjung AS no_pengunjung,
                    memberguesses.Nama AS Nama, 
                    COUNT(bacaditempat.collection_id) AS jum_eks, 
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud 
                    FROM bacaditempat 
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
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
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'content' => $this->renderPartial('pdf-non-anggota-sering-baca-ditempat-frekuensi', $content),
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

public function actionExportExcelNonAnggotaSeringBacaDitempatFrekuensi()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
       

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    bacaditempat.NoPengunjung AS no_pengunjung,
                    memberguesses.Nama AS Nama, 
                    COUNT(bacaditempat.collection_id) AS jum_eks, 
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud 
                    FROM bacaditempat 
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                }
             $sql .= $inValue;  

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

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
                <th colspan="6">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Non Anggota Sering Baca Ditempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'Periode</th>
                <th>'.yii::t('app','Nomor Pengunjung').'</th>
                <th>'.yii::t('app','Nama Pengunjung').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar Dibaca').'</th>
                <th>'.yii::t('app','Jumlah Judul Dibaca').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahJudul = 0;
        $JumlahEksemplar = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['no_anggota'].'</td>
                    <td>'.$data['Nama'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                    <td>'.$data['jum_jud'].'</td>
                </tr>
            ';
                        $JumlahEksemplar = $JumlahEksemplar + $data['jum_eks'];
                        $JumlahJudul = $JumlahJudul + $data['jum_jud'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahEksemplar.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahJudul.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtNonAnggotaSeringBacaDitempatFrekuensi()
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
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
                    bacaditempat.NoPengunjung AS no_pengunjung,
                    memberguesses.Nama AS Nama, 
                    COUNT(bacaditempat.collection_id) AS jum_eks, 
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud 
                    FROM bacaditempat 
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                }
             $sql .= $inValue;  

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++, 'Periode'=>$model['Periode'],'no_pengunjung'=>$model['no_pengunjung'], 'Nama'=>$model['Nama'], 'jum_eks'=>$model['jum_eks'], 'jum_jud'=>$model['jum_jud'] );
            $JumlahJudul = $JumlahJudul + $model['jum_jud'];
            $JumlahEksemplar = $JumlahEksemplar + $model['jum_eks'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'inValue'=>$inValue, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlahJudul'=>$JumlahJudul,
        'TotalJumlahEksemplar'=>$JumlahEksemplar,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'non_anggotaseringbacaditempat'=> yii::t('app','Non Anggota Sering Baca Ditempat'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal'=> yii::t('app','Tanggal'),
        'nomor_anggota'=> yii::t('app','Nomor Anggota'),
        'nama_anggota'=> yii::t('app','Nama Anggota'),
        'jumlah_eksemplardibaca'=> yii::t('app','Jumlah Eksemplar Dibaca'),
        'jumlah_juduldibaca'=> yii::t('app','Jumlah Judul Dibaca'),        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/baca_ditempat/laporan-baca_ditempat-non-anggota-sering-baca-ditempat-frekuensi.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-baca_ditempat-non-anggota-sering-baca-ditempat-frekuensi.ods');
    // !Open Office Calc Area
}

public function actionExportWordNonAnggotaSeringBacaDitempatFrekuensi()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
       

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    bacaditempat.NoPengunjung AS no_pengunjung,
                    memberguesses.Nama AS Nama, 
                    COUNT(bacaditempat.collection_id) AS jum_eks, 
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud 
                    FROM bacaditempat 
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                }
             $sql .= $inValue; 

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;
// $test = self::getRealNameKriteria($kriterias);

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="6">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Non Anggota Sering Baca Ditempat').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr style="margin-left: 10px; margin-right: 10px; ">
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Pengunjung').'</th>
                <th>'.yii::t('app','Nama Pengunjung').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar Dibaca').'</th>
                <th>'.yii::t('app','Jumlah Judul Dibaca').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahJudul = 0;
        $JumlahEksemplar = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['no_pengunjung'].'</td>
                    <td>'.$data['Nama'].'</td>
                    <td>'.$data['jum_eks'].'</td>
                    <td>'.$data['jum_jud'].'</td>
                </tr>
            ';
                        $JumlahJudul = $JumlahJudul + $data['jum_jud'];
                        $JumlahEksemplar = $JumlahEksemplar + $data['jum_eks'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahEksemplar.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahJudul.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfNonAnggotaSeringBacaDitempatFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(bacaditempat.CreateDate,"%Y") AS Periode';
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
       

            $inValue = $_POST['rank'];

            $sql = "SELECT ".$periode_format.",
                    bacaditempat.NoPengunjung AS no_pengunjung,
                    memberguesses.Nama AS Nama, 
                    COUNT(bacaditempat.collection_id) AS jum_eks, 
                    COUNT(DISTINCT(collections.Catalog_id)) AS jum_jud 
                    FROM bacaditempat 
                    LEFT JOIN memberguesses ON memberguesses.NoPengunjung = bacaditempat.NoPengunjung 
                    LEFT JOIN collections ON collections.ID = bacaditempat.collection_id 
                    LEFT JOIN catalogs ON catalogs.ID = collections.Catalog_id = catalogs.ID 
                    WHERE DATE(bacaditempat.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= $andValue;
             if ($_POST['periode'] == "harian"){
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%d-%m-%Y'), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY MONTH(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
                } else {
                    $sql .= " AND bacaditempat.NoPengunjung IS NOT NULL GROUP BY YEAR(bacaditempat.CreateDate), bacaditempat.NoPengunjung ORDER BY jum_eks DESC, Nama ASC LIMIT ";
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
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'options' => [
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-non-anggota-sering-baca-ditempat-frekuensi', $content);
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
