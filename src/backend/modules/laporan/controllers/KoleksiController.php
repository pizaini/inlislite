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
use common\models\Catalogs;
use common\models\LocationLibrary;
use common\models\Locations;
use common\models\Collectionsources;
use common\models\Partners;
use common\models\Currency;
use common\models\Members;
use common\models\Users;
use common\models\Collectioncategorys;
use common\models\Collectionrules;
use common\models\Worksheets;
use common\models\Collectionmedias;
// use common\models\MasterKelasBesar;
use common\models\VLapKriteriaKoleksi;


class KoleksiController extends \yii\web\Controller
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
     * [actionKoleksiPerkriteria description]
     * @return [type] [description]
     */
    public function actionPeriodik()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('koleksi-periodik',[
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
            $sql = 'SELECT BINARY catalogs.PublishLocation AS selecter FROM catalogs GROUP BY selecter';
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
        
        else if ($kriteria == 'PublishYear')
        {
            $sql = 'SELECT DISTINCT(catalogs.PublishYear) AS PublishY FROM catalogs';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'PublishY','PublishY');
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
            $sql = 'SELECT * FROM location_library';
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
        else if ($kriteria == 'locations')
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
        else if ($kriteria == 'collectionsources')
        {
            $sql = 'SELECT * FROM collectionsources';
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
        else if ($kriteria == 'partners')
        {
            $sql = 'SELECT * FROM partners';
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
        else if ($kriteria == 'currency')
        {
            $options =  ArrayHelper::map(Currency::find()->orderBy('Sort_ID')->asArray()->all(),'Currency',
                function($model) {
                    return $model['Currency'].' - '.$model['Description'];
                });
            array_unshift( $options, $options[0] = yii::t('app','---Semua---'));

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
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
            $options[0] = $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'kataloger')
        {
            $options =  ArrayHelper::map(Users::find()->orderBy('ID')->asArray()->all(),'ID',
                function($model) {
                    return $model['username'];
                });

            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>$options[0] = yii::t('app',' ---Semua---')],$options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'worksheets')
        {
            $sql = 'SELECT * FROM worksheets';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','Name');
            $options[0] = $options[0] = yii::t('app',' ---Semua---');
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
        else if ($kriteria == 'Subject')
        {
            $sql = 'SELECT DISTINCT(catalogs.Subject) AS Subject, catalogs.ID FROM catalogs GROUP BY SUBJECT';
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
        else if ($kriteria == 'no_klas')
        {
            $sql = 'SELECT *, SUBSTR(master_kelas_besar.kdKelas,1,1) AS test FROM master_kelas_besar';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'test','namakelas');
            $options[null] = yii::t('app','---Semua---');
            $options[XI] = yii::t('app',' Lainnya');
            asort($options);
            // echo '<pre>';print_r($options);echo '</pre>';die;
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
            $options = ['dimulai_dengan' => yii::t('app','Dimulai Dengan'),'tepat' => yii::t('app','Tepat'),'diakhiri_dengan' => yii::t('app','Diakhiri Dengan'),'salah_satu_isi' => yii::t('app','Salah Satu Isi')];
            $options = array_filter($options);

            $contentOptions = '<div class="input-group">'.Html::dropDownList('ini'.$kriteria.'[]',
                'selected option', $options, 
                ['class' => 'select2','style' => 'width: 100%;']
                ).'<div class="input-group-addon"> : </div>'.Html::textInput($kriteria.'[]',$value = null,
                ['class' => 'form-control col-sm-4','style' => 'width: 400px;']
                ).'</div>';
        }    
        else if ($kriteria == 'createby')
        {
            $sql = 'SELECT * FROM users';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','username');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'data_entry')
        {
            $contentOptions = DatePicker::widget([
                'name' => $kriteria.'[]', 
                'type' => DatePicker::TYPE_RANGE,
                'value' => date('d-m-Y'),
                'name2' => 'to'.$kriteria.'[]', 
                'value2' => date('d-m-Y'),
                'separator' => 's/d',
                'options' => ['placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Date')]
                ]);
        }
        else if ($kriteria == 'createdate')
        {
            $contentOptions = DatePicker::widget([
                'name' => $kriteria.'[]', 
                'type' => DatePicker::TYPE_RANGE,
                'value' => date('d-m-Y'),
                'name2' => 'to'.$kriteria.'[]', 
                'value2' => date('d-m-Y'),
                'separator' => 's/d',
                'options' => ['placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Date')]
                ]);
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
        else if ($kriteria == 'petugas')
        {
            $sql = 'SELECT *, CONCAT(users.username, " - ", users.Fullname) AS test FROM users';
            $data = yii::$app->db->createCommand($sql)->queryAll();
            $options =  ArrayHelper::map($data,'ID','test');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'AnggotaPengusul')
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
    public function actionLoadSelecterKriteria($i)
    {
        return $this->renderAjax('select-kriteria',['i'=>$i]);
    }
    public function actionLoadSelecterKriteriaUsulan($i)
    {
        return $this->renderAjax('select-kriteria-usulan',['i'=>$i]);
    }



    /**
     * [actionShowPdf description]
     * @return [type] [description]
     */
    public function actionShowPdf($tampilkan)
    {
      
        // session_start();
        $_SESSION['Array_POST_Filter'] = $_POST;

        // echo "<pre>";
        // // var_dump($_POST);
        // echo 'adalah'.count(array_filter($_POST['kriterias']));
        // echo "</pre>";

        // print_r(count(array_filter($_POST['kriterias'])));
        // print_r(isset($_POST['kota_terbit']));
        // echo 'Okeee'.$_POST['periode'];
        
        if ($tampilkan == 'frekuensi') 
        {
            if (count(array_filter($_POST['kriterias'])) != 0) {
                echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf').'">';
                echo "<iframe>";
            } else {
                echo "<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>";
            }
            
        } 
        else if ($tampilkan == 'data')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-data').'">'."<iframe>" 
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );
        }
        else if ($tampilkan == 'dataBukuInduk')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-data-buku-induk').'">'."<iframe>" 
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );
            // echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-data-buku-induk').'">';
            // echo "<iframe>";
        }
        else if ($tampilkan == 'dataAccessionList')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-data-accession-list').'">'."<iframe>" 
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );
            // echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-data-accession-list').'">';
            // echo "<iframe>";
        }
        else if ($tampilkan == 'dataUcapanTerimakasih')
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-ucapan-terimakasih').'">'."<iframe>";
            // echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-data-accession-list').'">';
            // echo "<iframe>";
        }
        else if ($tampilkan == 'frekuensiUsulan')
        {

            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-frekuensi-usulan-koleksi').'">'."<iframe>" 
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );
        }
        else if ($tampilkan == 'dataUsulan')
        {

            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-data-usulan-koleksi').'">'."<iframe>" 
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );
        }
        else if ($tampilkan == 'frekuensiKinerja')
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-frekuensi-kinerja-user').'">'."<iframe>" ;
        }
        else if ($tampilkan == 'dataKinerja')
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-pdf-data-kinerja-user').'">'."<iframe>" ;
        }
    }



    /**
     * [actionRenderPdfData description]
     * @return [type] [description]
     */
    public function actionRenderPdfData() 
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
                $periode2 = 'periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2 = 'periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2 = 'periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND worksheets.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {                        
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                        }
                    }
                }
            }

            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek = 'collections.CreateDate AS Subjek';
        }

        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        $sql = "SELECT collections.NoInduk AS NoInduk, 
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
                catalogs.DeweyNo AS data7,  
                collections.CallNumber as NomorPanggil,
                collections.TanggalPengadaan AS TanggalPengadaan, 
                collectionsources.Name as SumberPerolehan,
                worksheets.Name as JenisBahan,
                collectionmedias.Name as JenisMedia, 
                collectioncategorys.Name as Kategori,
                collectionrules.Name as JenisAkses,Currency,
                Price as Harga, 
                NomorBarcode,RFID 
                FROM collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                left JOIN users ON users.ID = collections.CreateBy
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                AND DATE(collections.TanggalPengadaan) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY DATE_FORMAT(collections.TanggalPengadaan,'%Y-%m-%d') DESC";

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] = $this->getRealNameKriteria($value);
        }
        $Berdasarkan = implode(yii::t('app',' dan '),$Berdasarkan);

        // if (count($_POST['kriterias']) == 1) {
        //     $Berdasarkan .= ' '.implode($_POST[implode($_POST['kriterias'])]);
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
            'content' => $this->renderPartial('pdf-view-koleksi-tampilkan-data', $content),
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
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

public function actionExportExcelKoleksiPeriodikData()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
      //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = 'Harian';
                $periode2 = 'periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = 'Bulanan';
                $periode2 = 'periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = 'Tahunan';
                $periode2 = 'periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

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
                    $andValue .= ' AND PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND worksheets.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {                        
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                        }
                    }
                }
            }
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek = 'collections.CreateDate AS Subjek';
        }

        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        $sql = "SELECT collections.NoInduk AS NoInduk, 
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
                catalogs.DeweyNo AS data7,  
                collections.CallNumber as NomorPanggil,
                collections.TanggalPengadaan AS TanggalPengadaan, 
                collectionsources.Name as SumberPerolehan,
                worksheets.Name as JenisBahan,
                collectionmedias.Name as JenisMedia, 
                collectioncategorys.Name as Kategori,
                collectionrules.Name as JenisAkses,Currency,
                Price as Harga, 
                NomorBarcode,RFID 
                FROM collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                LEFT JOIN users ON users.ID = collections.CreateBy
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                AND DATE(collections.TanggalPengadaan) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY DATE_FORMAT(collections.TanggalPengadaan,'%Y-%m-%d') DESC";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $filename = 'Laporan_Periodik_Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="13">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="13">'.yii::t('app','Pengadaan Koleksi').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="13">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Nomer Induk').'</th>
                <th>'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Nomer Panggil').'</th>
                <th>'.yii::t('app','Tanggal Pengadaan').'</th>
                <th>'.yii::t('app','Sumber Perolehan').'</th>
                <th>'.yii::t('app','Jenis Bahan').'</th>
                <th>'.yii::t('app','Bentuk Fisik').'</th>
                <th>'.yii::t('app','Kategori').'</th>
                <th>'.yii::t('app','Jenis Akses').'</th>
                <th>'.yii::t('app','Harga').'</th>
                <th>'.yii::t('app','Nomer Barcode').'</th>
                <th>'.yii::t('app','Nomer RFID').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['NomorBarcode'].'</td>
                    <td>'.$data['data'], $data['data2'], $data['data3'], $data['data4'], $data['data5'], $data['data6'], $data['data7'].'</td>
                    <td>'.$data['NomorPanggil'].'</td>
                    <td>'.$data['TanggalPengadaan'].'</td>
                    <td>'.$data['SumberPerolehan'].'</td>
                    <td>'.$data['JenisBahan'].'</td>
                    <td>'.$data['JenisMedia'].'</td>
                    <td>'.$data['Kategori'].'</td>
                    <td>'.$data['JenisAkses'].'</td>
                    <td>'.$data['Harga'].'</td>
                    <td>'.$data['NomorBarcode'].'</td>
                    <td>'.$data['RFID'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtKoleksiPeriodikData()
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
                $periode2 = 'periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2 = 'periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2 = 'periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= ' AND PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND worksheets.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {                        
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                        }
                    }
                }
            }
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek = 'collections.CreateDate AS Subjek';
        }

        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        $sql = "SELECT collections.NoInduk AS NoInduk, 
                NoInduk AS NoInduk, 
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
                collections.CallNumber as NomorPanggil,
                collections.TanggalPengadaan AS TanggalPengadaan, 
                collectionsources.Name as SumberPerolehan,
                worksheets.Name as JenisBahan,
                collectionmedias.Name as JenisMedia, 
                collectioncategorys.Name as Kategori,
                collectionrules.Name as JenisAkses,Currency,
                Price as Harga, 
                NomorBarcode,RFID 
                FROM collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                LEFT JOIN users ON users.ID = collections.CreateBy
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                AND DATE(collections.TanggalPengadaan) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY DATE_FORMAT(collections.TanggalPengadaan,'%Y-%m-%d') DESC";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'NoInduk'=> $model['NoInduk'], 'data'=>$model['data'], 'data2'=>$model['data2'], 'data3'=>$model['data3']
                         , 'data4'=>$model['data4'], 'data5'=>$model['data5'], 'data6'=>$model['data6'], 'data7'=>$model['data7'], 'NomorPanggil'=>$model['NomorPanggil']
                         , 'TanggalPengadaan'=>$model['TanggalPengadaan'], 'SumberPerolehan'=>$model['SumberPerolehan'], 'JenisBahan'=>$model['JenisBahan'], 'JenisMedia'=>$model['JenisMedia']
                         , 'Kategori'=>$model['Kategori'], 'JenisAkses'=>$model['JenisAkses'], 'Harga'=>$model['Harga'], 'NomorBarcode'=>$model['NomorBarcode'], 'RFID'=>$model['RFID'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );

    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'pengadaan_koleksi'=> yii::t('app','Pengadaan Koleksi'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'nomor_induk'=> yii::t('app','Nomor Induk'),
        'data_bibliografis'=> yii::t('app','Data Bibliografis'),
        'nomor_panggil'=> yii::t('app','Nomor Panggil'),
        'tanggal_pengadaan'=> yii::t('app','Tanggal Pengadaan'),
        'sumber_perolehan'=> yii::t('app','Sumber Perolehan'),
        'jenis_bahan'=> yii::t('app','Jenis Bahan'),
        'bentuk_fisik'=> yii::t('app','Bentuk Fisik'),
        'kategori'=> yii::t('app','Kategori'),
        'jenis_akses'=> yii::t('app','Jenis Akses'),
        'harga'=> yii::t('app','Harga'),
        'nomor_barcode'=> yii::t('app','Nomor Barcode'),
        'nomor_RFID'=> yii::t('app','Nomor RFID'),
        );

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-koleksi-periodik-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-koleksi-periodik-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordKoleksiPeriodikData()
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
                $periode2 = 'periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2 = 'periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2 = 'periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= ' AND PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND worksheets.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {                        
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                        }
                    }
                }
            }
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek = 'collections.CreateDate AS Subjek';
        }

        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        $sql = "SELECT collections.NoInduk AS NoInduk, 
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
                catalogs.DeweyNo AS data7,  
                collections.CallNumber as NomorPanggil,
                collections.TanggalPengadaan AS TanggalPengadaan, 
                collectionsources.Name as SumberPerolehan,
                worksheets.Name as JenisBahan,
                collectionmedias.Name as JenisMedia, 
                collectioncategorys.Name as Kategori,
                collectionrules.Name as JenisAkses,Currency,
                Price as Harga, 
                NomorBarcode,RFID 
                FROM collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                LEFT JOIN users ON users.ID = collections.CreateBy
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                AND DATE(collections.TanggalPengadaan) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY DATE_FORMAT(collections.TanggalPengadaan,'%Y-%m-%d') DESC";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="13">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="13">'.yii::t('app','Pengadaan Koleksi').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="13">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr style="margin-left: 10px; margin-right: 10px;">
                <th>No.</th>
                <th>'.yii::t('app','Nomer Induk').'</th>
                <th>'.yii::t('app','Data Bibliografis').'</th>
                <th>'.yii::t('app','Nomer Panggil').'</th>
                <th>'.yii::t('app','Tanggal Pengadaan').'</th>
                <th>'.yii::t('app','Sumber Perolehan').'</th>
                <th>'.yii::t('app','Jenis Bahan').'</th>
                <th>'.yii::t('app','Bentuk Fisik').'</th>
                <th>'.yii::t('app','Kategori').'</th>
                <th>'.yii::t('app','Jenis Akses').'</th>
                <th>'.yii::t('app','Harga').'</th>
                <th>'.yii::t('app','Nomer Barcode').'</th>
                <th>'.yii::t('app','Nomer RFID').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['NoInduk'].'</td>
                    <td>'.$data['data'], $data['data2'], $data['data3'], $data['data4'], $data['data5'], $data['data6'], $data['data7'].'</td>
                    <td>'.$data['NomorPanggil'].'</td>
                    <td>'.$data['TanggalPengadaan'].'</td>
                    <td>'.$data['SumberPerolehan'].'</td>
                    <td>'.$data['JenisBahan'].'</td>
                    <td>'.$data['JenisMedia'].'</td>
                    <td>'.$data['Kategori'].'</td>
                    <td>'.$data['JenisAkses'].'</td>
                    <td>'.$data['Harga'].'</td>
                    <td>'.$data['NomorBarcode'].'</td>
                    <td>'.$data['RFID'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}


public function actionExportPdfKoleksiPeriodikData()
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
                $periode2 = 'periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2 = 'periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2 = 'periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= ' AND PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND worksheets.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {                        
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                        }
                    }
                }
            }
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.substr(addslashes($value),0,1).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.substr(addslashes($value),0,1).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek = 'collections.CreateDate AS Subjek';
        }

        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.addslashes($value).'" ';
                }
            }
        } 

        $sql = "SELECT collections.NoInduk AS NoInduk, 
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
                catalogs.DeweyNo AS data7,  
                collections.CallNumber as NomorPanggil,
                collections.TanggalPengadaan AS TanggalPengadaan, 
                collectionsources.Name as SumberPerolehan,
                worksheets.Name as JenisBahan,
                collectionmedias.Name as JenisMedia, 
                collectioncategorys.Name as Kategori,
                collectionrules.Name as JenisAkses,Currency,
                Price as Harga, 
                NomorBarcode,RFID 
                FROM collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                LEFT JOIN users ON users.ID = collections.CreateBy
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                AND DATE(collections.TanggalPengadaan) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY DATE_FORMAT(collections.TanggalPengadaan,'%Y-%m-%d') DESC";

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] = $this->getRealNameKriteria($value);
        }
        $Berdasarkan = implode(yii::t('app',' dan '),$Berdasarkan);

        // if (count($_POST['kriterias']) == 1) {
        //     $Berdasarkan .= ' '.implode($_POST[implode($_POST['kriterias'])]);
        // }

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan; 

        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            // $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 233; width: 100%;" >'];
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
        $content = $this->renderPartial('pdf-view-koleksi-tampilkan-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

    }


    /**
     * [actionMpdfDemo1 generate data to pdf with MPDF Controller]
     * @return [pdf] [pdf to show on page]
     */
    public function actionRenderPdf() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';
        $andQuery='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                if (!in_array('0',$_POST['PublishLocation'])) {
                    $andValue .= ' AND catalogs.PublishLocation = "'.addslashes($value).'" ';
                    $VALUE['PublishLocation'] = $_POST['PublishLocation'];
                    }else{$VALUE['PublishLocation'] = array('Semua');}
                }
            $subjek[] = 'catalogs.PublishLocation';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if (!in_array('0',$_POST['Publisher'])) {
                    $andValue .= " AND catalogs.Publisher = '".addslashes($value)."' ";
                    $VALUE['Publisher'] = $_POST['Publisher'];
                    }else{$VALUE['Publisher'] = array('Semua');}
                }
            $subjek[] = 'catalogs.Publisher';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if (!in_array('0',$_POST['PublishYear'])) {
                    $andValue .= " AND catalogs.PublishYear = '".addslashes($value)."' ";
                    $VALUE['PublishYear'] = $_POST['PublishYear'];
                    }else{$VALUE['PublishYear'] = array('Semua');}
                }
            $subjek[] = 'catalogs.PublishYear';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                // print_r(sizeof($_POST['location_library']));
                    if (!in_array('0',$_POST['location_library'])) {
                        $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                        $test = LocationLibrary::find()->where(['in', 'ID', $_POST['location_library']])->asArray()->All();
                        $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                         $VALUE['location_library'] = $groupValue;
                    }else{$VALUE['location_library'] = array('Semua');}
            }
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
            $subjek[] = 'location_library.Name';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if (!in_array('0',$_POST['locations'])) {
                    sizeof($_POST['locations']) > 1 ? $andValue = " AND collections.Location_Id IN (".implode(',',$_POST['locations']).")" : $andValue = " AND collections.Location_Id = '".addslashes($value)."'";
                    $test = Locations::find()->where(['in', 'ID', $_POST['locations']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                         $VALUE['locations'] = $groupValue;
                    }else{$VALUE['locations'] = array('Semua');}
                }
            $join .= 'INNER JOIN locations ON collections.Location_Id = locations.ID ';
            $subjek[] = 'locations.Name';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if (!in_array('0',$_POST['collectionsources'])) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    $test = Collectionsources::find()->where(['in', 'ID', $_POST['collectionsources']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                         $VALUE['collectionsources'] = $groupValue;
                    }else{$VALUE['collectionsources'] = array('Semua');}
                }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
            $subjek[] = 'collectionsources.Name';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if (!in_array('0',$_POST['partners'])) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    $test = Partners::find()->where(['in', 'ID', $_POST['partners']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                         $VALUE['partners'] = $groupValue;
                    }else{$VALUE['partners'] = array('Semua');}
                }
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
            $subjek[] = 'partners.Name';
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if (!in_array('0',$_POST['currency'])) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    $test = Currency::find()->where(['in', 'Currency', $_POST['currency']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Currency'].' - '.$tval['Description'];
                        }
                    $VALUE['currency'] = $groupValue;
                    }else{$VALUE['currency'] = array('Semua');}
                }
            $group .= ', currency.Currency';
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
            $subjek = 'currency.Description AS Subjek';
            } 

            if (isset($_POST['harga'])) {
                foreach ($_POST['harga'] as $key => $value) {
                    foreach ($_POST['toharga'] as $key => $toValue) {
                        if ($value != "0" ) {
                            $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                            $groupValue['harga'] = ($value == '' ? '0' : $value).' - '.($toValue == '' ? '0' : $toValue);
                            $VALUE['harga'] = $groupValue;
                        }
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
                }
            $subjek[] = 'collections.Price';
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if (!in_array('0',$_POST['collectioncategorys'])) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    $test = Collectioncategorys::find()->where(['in', 'ID', $_POST['collectioncategorys']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['collectioncategorys'] = $groupValue;
                    }else{$VALUE['collectioncategorys'] = array('Semua');}
                }
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
            $subjek[] = 'collectioncategorys.Name';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if (!in_array('0',$_POST['collectionrules'])) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    $test = Collectionrules::find()->where(['in', 'ID', $_POST['collectionrules']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['collectionrules'] = $groupValue;
                    }else{$VALUE['collectionrules'] = array('Semua');}
                }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
            $subjek[] = 'collectionrules.Name';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if (!in_array('0',$_POST['worksheets'])) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    $test = Worksheets::find()->where(['in', 'ID', $_POST['worksheets']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['worksheets'] = $groupValue;
                    }else{$VALUE['worksheets'] = array('Semua');}
                }
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
            $subjek[] = 'worksheets.Name';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if (!in_array('0',$_POST['collectionmedias'])) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    $test = Collectionmedias::find()->where(['in', 'ID', $_POST['collectionmedias']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['collectionmedias'] = $groupValue;
                    }else{$VALUE['collectionmedias'] = array('Semua');}
                }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
            $subjek[] = 'collectionmedias.Name';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if (!in_array('0',$_POST['Subject'])) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    $test = Catalogs::find()->where(['in', 'ID', $_POST['Subject']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Subject'];
                        }
                    $VALUE['Subject'] = $groupValue;
                    }else{$VALUE['Subject'] = array('Semua');}
                }
            $subjek[] = 'catalogs.Subject';
            }           

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {                        
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            $groupValue['no_klas'] = '---semua---';
                            $VALUE['no_klas'] = $groupValue;
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                                $groupValue['no_klas'] = 'Lainnya';
                                $VALUE['no_klas'] = $groupValue;
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                                $groupValue['no_klas'] = 'Lainnya';
                                $VALUE['no_klas'] = $groupValue;
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                                $groupValue['no_klas'] = 'Lainnya';
                                $VALUE['no_klas'] = $groupValue;
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                                $groupValue['no_klas'] = $value.'00'.' - '.$tovalue.'00';
                                $VALUE['no_klas'] = $groupValue;
                        }
                    }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            $subjek[] = 'IFNULL(master_kelas_besar.namakelas,"Lainnya")';
                }
            } 

        if (isset($_POST['no_panggil'])) {  
        echo '<pre>';print_r($_POST['inino_panggil']);
            if (sizeof($_POST['inino_panggil']) > 1) {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    $groupValue = array();
                    $andValue = '';
                    foreach ($_POST['inino_panggil'] as $Ivalue) {
                        switch ($Ivalue) {
                        case 'dimulai_dengan':
                            $andValue = " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                            $groupValue['no_klas'] .= 'dimulai dengan= '. $value.' ';
                            $VALUE['no_panggil'] = $groupValue;
                            break;
                        case 'salah_satu_isi':
                            $andValue = " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                            $groupValue['no_klas'] .= 'salah satu isi= '. $value.' ';
                            $VALUE['no_panggil'] = $groupValue;
                            break;
                        case 'tepat':
                            $andValue = " AND catalogs.CallNumber = '".addslashes($value)."' ";
                            $groupValue['no_klas'] .= 'tepat= '. $value.' ';
                            $VALUE['no_panggil'] = $groupValue;
                            break;
                        case 'diakhiri_dengan':
                            $andValue = " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                            $groupValue['no_klas'] .= 'diakhiri dengan= '. $value.' ';
                            $VALUE['no_panggil'] = $groupValue;
                            break;
                        default:
                            $VALUE['no_panggil'] = null;
                            break;
                        }
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    $groupValue['no_klas'] = 'dimulai dengan= '. $value;
                    $VALUE['no_panggil'] = $groupValue;
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    $groupValue['no_klas'] = 'Salah Satu Isi= '. $value;
                    $VALUE['no_panggil'] = $groupValue;
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    $groupValue['no_klas'] = 'Tepat= '. $value;
                    $VALUE['no_panggil'] = $groupValue;
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    $groupValue['no_klas'] = 'Diakhiri dengan= '. $value;
                    $VALUE['no_panggil'] = $groupValue;
                    }
                }
            }
            $subjek[] = 'catalogs.CallNumber';
        }

        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                foreach ($_POST['todata_entry'] as $key => $toValue) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    $groupValue['data_entry'] = $value.' - '.$toValue;
                    $VALUE['data_entry'] = $groupValue;
                }
            }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek[] = 'collections.CreateDate';
        }

        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                    $test = Users::find()->where(['in', 'ID', $_POST['petugas']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Fullname'];
                        }
                    $VALUE['petugas'] = $groupValue;
                    }else{$VALUE['petugas'] = array('Semua');}
            }
        $join .= 'INNER JOIN users ON users.ID = collections.CreateBy ';
        $subjek[] = 'users.username';
        }



          $sql = "SELECT ".$periode_format.",                
                    COUNT(collections.ID) AS CountEksemplar,
                    COUNT(DISTINCT catalogs.ID) AS JumlahJudul,
                    CONCAT(".implode(', \', \',',$subjek).") AS Subjek
                    FROM
                    collections 
                    left JOIN catalogs ON collections.Catalog_id = catalogs.ID
                    ";


        $sql .= $join;
        $sql .= 'WHERE DATE(collections.TanggalPengadaan) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue; 
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY collections.TanggalPengadaan, Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(collections.TanggalPengadaan), Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(collections.TanggalPengadaan), Subjek";
                }
        $sql .= $group;
        $sql .= " ORDER BY Periode DESC, Subjek";

        //$sql .= $group;
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

            // echo"<pre>";
            // print_r($subjek);
            // print_r($VALUE);
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
            'content' => $this->renderPartial('pdf-view-koleksi', $content),
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

public function actionExportExcelKoleksiPeriodikFrekuensi()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';
        $andQuery='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%d-%M-%Y") Periode';
                $periode = 'Harian';
                $periode2= 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%M-%Y") Periode';
                $periode = 'Bulanan';
                $periode2= 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%Y") Periode';
                $periode = 'Tahunan';
                $periode2= 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                if (!in_array('0',$_POST['PublishLocation'])) {
                    $andValue .= ' AND catalogs.PublishLocation = "'.addslashes($value).'" ';
                    $VALUE['PublishLocation'] = $_POST['PublishLocation'];
                    }else{$VALUE['PublishLocation'] = array('Semua');}
                }
            $subjek[] = 'catalogs.PublishLocation';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if (!in_array('0',$_POST['Publisher'])) {
                    $andValue .= " AND catalogs.Publisher = '".addslashes($value)."' ";
                    $VALUE['Publisher'] = $_POST['Publisher'];
                    }else{$VALUE['Publisher'] = array('Semua');}
                }
            $subjek[] = 'catalogs.Publisher';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if (!in_array('0',$_POST['PublishYear'])) {
                    $andValue .= " AND catalogs.PublishYear = '".addslashes($value)."' ";
                    $VALUE['PublishYear'] = $_POST['PublishYear'];
                    }else{$VALUE['PublishYear'] = array('Semua');}
                }
            $subjek[] = 'catalogs.PublishYear';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                // print_r(sizeof($_POST['location_library']));
                    if (!in_array('0',$_POST['location_library'])) {
                        $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                        $test = LocationLibrary::find()->where(['in', 'ID', $_POST['location_library']])->asArray()->All();
                        $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                         $VALUE['location_library'] = $groupValue;
                    }else{$VALUE['location_library'] = array('Semua');}
            }
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
            $subjek[] = 'location_library.Name';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if (!in_array('0',$_POST['locations'])) {
                    sizeof($_POST['locations']) > 1 ? $andValue = " AND collections.Location_Id IN (".implode(',',$_POST['locations']).")" : $andValue = " AND collections.Location_Id = '".addslashes($value)."'";
                    $test = Locations::find()->where(['in', 'ID', $_POST['locations']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                         $VALUE['locations'] = $groupValue;
                    }else{$VALUE['locations'] = array('Semua');}
                }
            $join .= 'INNER JOIN locations ON collections.Location_Id = locations.ID ';
            $subjek[] = 'locations.Name';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if (!in_array('0',$_POST['collectionsources'])) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    $test = Collectionsources::find()->where(['in', 'ID', $_POST['collectionsources']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                         $VALUE['collectionsources'] = $groupValue;
                    }else{$VALUE['collectionsources'] = array('Semua');}
                }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
            $subjek[] = 'collectionsources.Name';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if (!in_array('0',$_POST['partners'])) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    $test = Partners::find()->where(['in', 'ID', $_POST['partners']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                         $VALUE['partners'] = $groupValue;
                    }else{$VALUE['partners'] = array('Semua');}
                }
            $join .= 'INNER JOIN partners ON collections.Partner_Id = partners.ID ';
            $subjek[] = 'partners.Name';
            } 

            if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if (!in_array('0',$_POST['currency'])) {
                    $andValue .= " AND collections.Currency = '".addslashes($value)."' ";
                    $test = Currency::find()->where(['in', 'Currency', $_POST['currency']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Currency'].' - '.$tval['Description'];
                        }
                    $VALUE['currency'] = $groupValue;
                    }else{$VALUE['currency'] = array('Semua');}
                }
            $group .= ', currency.Currency';
            $join .= 'INNER JOIN currency ON collections.Currency = currency.Currency ';
            $subjek = 'currency.Description AS Subjek';
            } 

            if (isset($_POST['harga'])) {
                foreach ($_POST['harga'] as $key => $value) {
                    foreach ($_POST['toharga'] as $key => $toValue) {
                        if ($value != "0" ) {
                            $andValue .= " AND collections.Price BETWEEN '".addslashes($value)."' ";
                            $groupValue['harga'] = ($value == '' ? '0' : $value).' - '.($toValue == '' ? '0' : $toValue);
                            $VALUE['harga'] = $groupValue;
                        }
                    }
                }
            } 
            if (isset($_POST['toharga'])) {
                foreach ($_POST['toharga'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND '".addslashes($value)."' ";
                    }
                }
            $subjek[] = 'collections.Price';
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if (!in_array('0',$_POST['collectioncategorys'])) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    $test = Collectioncategorys::find()->where(['in', 'ID', $_POST['collectioncategorys']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['collectioncategorys'] = $groupValue;
                    }else{$VALUE['collectioncategorys'] = array('Semua');}
                }
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
            $subjek[] = 'collectioncategorys.Name';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if (!in_array('0',$_POST['collectionrules'])) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    $test = Collectionrules::find()->where(['in', 'ID', $_POST['collectionrules']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['collectionrules'] = $groupValue;
                    }else{$VALUE['collectionrules'] = array('Semua');}
                }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
            $subjek[] = 'collectionrules.Name';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if (!in_array('0',$_POST['worksheets'])) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    $test = Worksheets::find()->where(['in', 'ID', $_POST['worksheets']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['worksheets'] = $groupValue;
                    }else{$VALUE['worksheets'] = array('Semua');}
                }
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
            $subjek[] = 'worksheets.Name';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if (!in_array('0',$_POST['collectionmedias'])) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    $test = Collectionmedias::find()->where(['in', 'ID', $_POST['collectionmedias']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['collectionmedias'] = $groupValue;
                    }else{$VALUE['collectionmedias'] = array('Semua');}
                }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
            $subjek[] = 'collectionmedias.Name';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if (!in_array('0',$_POST['Subject'])) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    $test = Catalogs::find()->where(['in', 'ID', $_POST['Subject']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Subject'];
                        }
                    $VALUE['Subject'] = $groupValue;
                    }else{$VALUE['Subject'] = array('Semua');}
                }
            $subjek[] = 'catalogs.Subject';
            }           

            if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {                        
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            $groupValue['no_klas'] = '---semua---';
                            $VALUE['no_klas'] = $groupValue;
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                                $groupValue['no_klas'] = 'Lainnya';
                                $VALUE['no_klas'] = $groupValue;
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                                $groupValue['no_klas'] = 'Lainnya';
                                $VALUE['no_klas'] = $groupValue;
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                                $groupValue['no_klas'] = 'Lainnya';
                                $VALUE['no_klas'] = $groupValue;
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                                $groupValue['no_klas'] = $value.'00'.' - '.$tovalue.'00';
                                $VALUE['no_klas'] = $groupValue;
                        }
                    }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            $subjek[] = 'IFNULL(master_kelas_besar.namakelas,"Lainnya")';
                }
            } 

        if (isset($_POST['no_panggil'])) {  
        echo '<pre>';print_r($_POST['inino_panggil']);
            if (sizeof($_POST['inino_panggil']) > 1) {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    $groupValue = array();
                    $andValue = '';
                    foreach ($_POST['inino_panggil'] as $Ivalue) {
                        switch ($Ivalue) {
                        case 'dimulai_dengan':
                            $andValue = " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                            $groupValue['no_klas'] .= 'dimulai dengan= '. $value.' ';
                            $VALUE['no_panggil'] = $groupValue;
                            break;
                        case 'salah_satu_isi':
                            $andValue = " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                            $groupValue['no_klas'] .= 'salah satu isi= '. $value.' ';
                            $VALUE['no_panggil'] = $groupValue;
                            break;
                        case 'tepat':
                            $andValue = " AND catalogs.CallNumber = '".addslashes($value)."' ";
                            $groupValue['no_klas'] .= 'tepat= '. $value.' ';
                            $VALUE['no_panggil'] = $groupValue;
                            break;
                        case 'diakhiri_dengan':
                            $andValue = " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                            $groupValue['no_klas'] .= 'diakhiri dengan= '. $value.' ';
                            $VALUE['no_panggil'] = $groupValue;
                            break;
                        default:
                            $VALUE['no_panggil'] = null;
                            break;
                        }
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'dimulai_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '".addslashes($value)."%' ";
                    $groupValue['no_klas'] = 'dimulai dengan= '. $value;
                    $VALUE['no_panggil'] = $groupValue;
                    }
                }
            } 
            if (implode($_POST['inino_panggil']) == 'salah_satu_isi') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."%' ";
                    $VALUE['no_panggil'] = $_POST['no_panggil'];
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'tepat') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber = '".addslashes($value)."' ";
                    $VALUE['no_panggil'] = $_POST['no_panggil'];
                    }
                }
            }
            if (implode($_POST['inino_panggil']) == 'diakhiri_dengan') {
                foreach ($_POST['no_panggil'] as $key => $value) {
                    if ($value != "0" ) {
                    $andValue .= " AND catalogs.CallNumber LIKE '%".addslashes($value)."' ";
                    $VALUE['no_panggil'] = $_POST['no_panggil'];
                    }
                }
            }
            $subjek[] = 'catalogs.CallNumber';
        }

        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                foreach ($_POST['todata_entry'] as $key => $toValue) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                    $groupValue['data_entry'] = $value.' - '.$toValue;
                    $VALUE['data_entry'] = $groupValue;
                }
            }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek[] = 'collections.CreateDate';
        }

        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                    $test = Users::find()->where(['in', 'ID', $_POST['petugas']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Fullname'];
                        }
                    $VALUE['petugas'] = $groupValue;
                    }else{$VALUE['petugas'] = array('Semua');}
            }
        $join .= 'INNER JOIN users ON users.ID = collections.CreateBy ';
        $subjek[] = 'users.username';
        }



          $sql = "SELECT ".$periode_format.",                
                    COUNT(collections.ID) AS CountEksemplar,
                    COUNT(DISTINCT catalogs.ID) AS JumlahJudul,
                    CONCAT(".implode(', \', \',',$subjek).") AS Subjek
                    FROM
                    collections 
                    left JOIN catalogs ON collections.Catalog_id = catalogs.ID
                    ";
        $sql .= $join;
        $sql .= 'WHERE DATE(collections.TanggalPengadaan) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue; 
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY collections.TanggalPengadaan, Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(collections.TanggalPengadaan), Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(collections.TanggalPengadaan), Subjek";
                }
		$sql .= $group;
        $sql .= " ORDER BY Periode DESC, Subjek";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    
    $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

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
                <th colspan="5">'.yii::t('app','Pengadaan Koleksi').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Kategori').'</th>
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahEksemplar = 0;
        $JumlahJudul = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Subjek'].'</td>
                    <td>'.$data['JumlahJudul'].'</td>
                    <td>'.$data['CountEksemplar'].'</td>
                </tr>
            ';
                        $JumlahEksemplar = $JumlahEksemplar + $data['CountEksemplar'];
                        $JumlahJudul = $JumlahJudul + $data['JumlahJudul'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="3" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahJudul.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahEksemplar.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtKoleksiPeriodikFrekuensi()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';
        $andQuery='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= " AND catalogs.PublishLocation LIKE '%".addslashes($value)."%' ";
                    }
                }
            $subjek = 'SPLIT_STR(catalogs.PublishLocation,":", 1) AS Subjek';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'catalogs.Publisher AS Subjek';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.PublishYear = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'catalogs.PublishYear AS Subjek';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
                }
            $subjek = 'location_library.Name AS Subjek';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Location_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN locations ON collections.Location_Id = locations.ID ';
                }
            $subjek = 'locations.Name AS Subjek';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            $subjek = 'collectionsources.Name AS Subjek';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
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
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            $subjek = 'collectioncategorys.Name AS Subjek';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            $subjek = 'collectionrules.Name AS Subjek';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
                }
            $subjek = 'worksheets.Name AS Subjek';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            $subjek = 'collectionmedias.Name AS Subjek';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
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
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                        }
                    }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            $subjek = 'IFNULL(master_kelas_besar.namakelas,"Lainnya") AS Subjek';
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
            $subjek = 'catalogs.CallNumber AS Subjek';
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek = 'collections.CreateDate AS Subjek';
        }
        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            $join .= 'INNER JOIN users ON users.ID = collections.CreateBy ';
            }
        $subjek = 'users.username AS Subjek';
        }



           $sql = "SELECT ".$periode_format.",                
                    COUNT(collections.ID) AS CountEksemplar,
                    COUNT(DISTINCT catalogs.ID) AS JumlahJudul,
                    ".$subjek."
                    FROM
                    collections 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID
                    ";
        $sql .= $join;
        $sql .= 'WHERE DATE(collections.TanggalPengadaan) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue; 
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY collections.TanggalPengadaan, Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(collections.TanggalPengadaan), Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(collections.TanggalPengadaan), Subjek";
                }
        $sql .= $group;
        $sql .= " ORDER BY Periode DESC, Subjek";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Subjek'=>$model['Subjek'], 'CountEksemplar'=>$model['CountEksemplar'], 'JumlahJudul'=>$model['JumlahJudul'] );
            $CountEksemplar = $CountEksemplar + $model['CountEksemplar'];
            $JumlahJudul = $JumlahJudul + $model['JumlahJudul'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>yii::t('app',$Berdasarkan), 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalCountEksemplar'=>$CountEksemplar,
        'TotalJumlahJudul'=>$JumlahJudul,
        );

    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'pengadaan_koleksi'=> yii::t('app','Pengadaan Koleksi'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal'=> yii::t('app','Tanggal'),
        'jumlah_judul'=> yii::t('app','Jumlah Judul'),
        'jumlah_eksemplar'=> yii::t('app','Jumlah Eksemplar'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
if (sizeof($_POST['kriterias']) == 1) {
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-koleksi-periodik.ods'; 
}else{
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-koleksi-periodik_no_subjek.ods'; 
}

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-koleksi-periodik-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordKoleksiPeriodikFrekuensi()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';
        $andQuery='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%d-%M-%Y") Periode';
                $periode = 'Harian';
                $periode2= 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%M-%Y") Periode';
                $periode = 'Bulanan';
                $periode2= 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%Y") Periode';
                $periode = 'Tahunan';
                $periode2= 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= " AND catalogs.PublishLocation LIKE '%".addslashes($value)."%' ";
                    }
                }
            $subjek = 'SPLIT_STR(catalogs.PublishLocation,":", 1) AS Subjek';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'catalogs.Publisher AS Subjek';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.PublishYear = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'catalogs.PublishYear AS Subjek';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
                }
            $subjek = 'location_library.Name AS Subjek';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Location_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN locations ON collections.Location_Id = locations.ID ';
                }
            $subjek = 'locations.Name AS Subjek';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            $subjek = 'collectionsources.Name AS Subjek';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
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
                }
            $subjek = 'collections.Price AS Subjek';
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            $subjek = 'collectioncategorys.Name AS Subjek';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            $subjek = 'collectionrules.Name AS Subjek';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
                }
            $subjek = 'worksheets.Name AS Subjek';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            $subjek = 'collectionmedias.Name AS Subjek';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
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
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                        }
                    }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            $subjek = 'IFNULL(master_kelas_besar.namakelas,"Lainnya") AS Subjek';
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
            $subjek = 'catalogs.CallNumber AS Subjek';
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek = 'collections.CreateDate AS Subjek';
        }
        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            $join .= 'INNER JOIN users ON users.ID = collections.CreateBy ';
            }
        $subjek = 'users.username AS Subjek';
        }

           $sql = "SELECT ".$periode_format.",                
                    COUNT(collections.ID) AS CountEksemplar,
                    COUNT(DISTINCT catalogs.ID) AS JumlahJudul,
                    ".$subjek."
                    FROM
                    collections 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID
                    ";
        $sql .= $join;
        $sql .= 'WHERE DATE(collections.TanggalPengadaan) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue; 
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY collections.TanggalPengadaan, Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(collections.TanggalPengadaan), Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(collections.TanggalPengadaan), Subjek";
                }
        $sql .= $group;
        $sql .= " ORDER BY Periode DESC, Subjek";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

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
    echo '<table border="0" align="center" width="700"> 
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="4"';} else {echo 'colspan="5"';}echo '>'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="4"';} else {echo 'colspan="5"';}echo '>'.yii::t('app','Pengadaan Koleksi').' '.$periode2.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="4"';} else {echo 'colspan="5"';}echo '>'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr style="margin-right: 10px; margin-left: 10px;">
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<th>'.yii::t('app',$Berdasarkan).'</th>'; }
    echo'
                <th>'.yii::t('app','Jumlah Judul').'</th>
                <th>'.yii::t('app','Jumlah Eksemplar').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahEksemplar = 0;
        $JumlahJudul = 0;
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
                    <td>'.$data['JumlahJudul'].'</td>
                    <td>'.$data['CountEksemplar'].'</td>
                </tr>
            ';
                        $JumlahEksemplar = $JumlahEksemplar + $data['CountEksemplar'];
                        $JumlahJudul = $JumlahJudul + $data['JumlahJudul'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahJudul.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahEksemplar.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfKoleksiPeriodikFrekuensi()
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $group='';
        $join='';
        $subjek='';
        $andQuery='';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%d-%M-%Y") Periode';
                $periode = 'Harian';
                $periode2= 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%M-%Y") Periode';
                $periode = 'Bulanan';
                $periode2= 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(collections.TanggalPengadaan,"%Y") Periode';
                $periode = 'Tahunan';
                $periode2= 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= " AND catalogs.PublishLocation LIKE '%".addslashes($value)."%' ";
                    }
                }
            $subjek = 'SPLIT_STR(catalogs.PublishLocation,":", 1) AS Subjek';
            }

            if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.ID = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'catalogs.Publisher AS Subjek';
            }

            if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.PublishYear = '".addslashes($value)."' ";
                    }
                }
            $subjek = 'catalogs.PublishYear AS Subjek';
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND location_library.ID = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN location_library ON collections.Location_Library_Id = location_library.ID ';
                }
            $subjek = 'location_library.Name AS Subjek';
            } 

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Location_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN locations ON collections.Location_Id = locations.ID ';
                }
            $subjek = 'locations.Name AS Subjek';
            } 

            if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Source_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionsources ON collections.Source_Id = collectionsources.ID ';
                }
            $subjek = 'collectionsources.Name AS Subjek';
            } 

            if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Partner_Id = '".addslashes($value)."' ";
                    }
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
                }
            $subjek = 'collections.Price AS Subjek';
            } 

            if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Category_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectioncategorys ON collections.Category_Id = collectioncategorys.ID ';
                }
            $subjek = 'collectioncategorys.Name AS Subjek';
            }  

            if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND collections.Rule_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionrules ON collections.Rule_Id = collectionrules.ID ';
                }
            $subjek = 'collectionrules.Name AS Subjek';
            } 

            if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN worksheets ON catalogs.Worksheet_Id = worksheets.ID ';
                }
            $subjek = 'worksheets.Name AS Subjek';
            }  

            if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Worksheet_Id = '".addslashes($value)."' ";
                    }
            $join .= 'INNER JOIN collectionmedias ON collections.Media_Id = collectionmedias.ID ';
                }
            $subjek = 'collectionmedias.Name AS Subjek';
            }

            if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND catalogs.Subject = '".addslashes($value)."' ";
                    }
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
                    foreach ($_POST['tono_klas'] as $key => $tovalue) {                        
                        if ($value == "" || $tovalue == "" ) {
                            $andValue .= '';
                            }
                        else if ($value == "XI" && $tovalue != "XI" && $tovalue != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($tovalue,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($tovalue == "XI" && $value != "XI" && $value != "") {
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" OR (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL)) ';
                            }
                        else if ($value == "XI" || $tovalue == "XI") {
                                $andValue .= ' AND (SUBSTRING(catalogs.DeweyNo,1,1) NOT REGEXP "^-?[0-9]+$" OR catalogs.DeweyNo IS NULL) ';
                            }
                        else{
                                $andValue .= ' AND (SUBSTR(catalogs.DeweyNo,1,1) BETWEEN '.substr($value,0,1).' AND '.substr($tovalue,0,1).' AND SUBSTRING(catalogs.DeweyNo,1,1) REGEXP "^-?[0-9]+$") ';
                        }
                    }
            $join .= 'LEFT JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) ';
            $subjek = 'IFNULL(master_kelas_besar.namakelas,"Lainnya") AS Subjek';
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
            $subjek = 'catalogs.CallNumber AS Subjek';
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND DATE(collections.CreateDate) BETWEEN '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= ", CONCAT('".date("d-m-Y", strtotime( $value ) )."', ' - ', ";
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND '".date("Y-m-d", strtotime( $value ) )."' ";
                }
        $andQuery .= " '".date("d-m-Y", strtotime( $value ) )."') AS subjek";
            }
            $subjek = 'collections.CreateDate AS Subjek';
        }
        if (isset($_POST['petugas'])) {
            foreach ($_POST['petugas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND users.ID = "'.addslashes($value).'" ';
                }
            $join .= 'INNER JOIN users ON users.ID = collections.CreateBy ';
            }
        $subjek = 'users.username AS Subjek';
        }

           $sql = "SELECT ".$periode_format.",                
                    COUNT(collections.ID) AS CountEksemplar,
                    COUNT(DISTINCT catalogs.ID) AS JumlahJudul,
                    ".$subjek."
                    FROM
                    collections 
                    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID
                    ";
        $sql .= $join;
        $sql .= 'WHERE DATE(collections.TanggalPengadaan) ';
        $sql .= $sqlPeriode;
        $sql .= $andValue; 
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY collections.TanggalPengadaan, Subjek";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(collections.TanggalPengadaan), Subjek";
                } else {
                    $sql .= " GROUP BY YEAR(collections.TanggalPengadaan), Subjek";
                }
        $sql .= $group;
        $sql .= " ORDER BY Periode DESC, Subjek";

        //$sql .= $group;
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
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
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            // $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
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
        $content = $this->renderPartial('pdf-view-koleksi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }


    /////// Koleksi Buku Induk Area
    /**
     * [actionBukuInduk description]
     * @return [type] [description]
     */
    public function actionBukuInduk()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('koleksi-buku-induk',[
            'model' => $model,
            ]);
    }



    /**
     * [actionRenderPdfDataBukuIduk description]
     * @return [type] [description]
     */
    public function actionRenderPdfDataBukuInduk() 
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
                $periode2 = 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2 = 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2 = 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                }
            }
        } 
        if (isset($_POST['tono_klas'])) {
            foreach ($_POST['tono_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                }
            }
        } 
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(collections.CreateDate) BETWEEN "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = " SELECT collections.ID AS ID,
                collections.NoInduk AS no_induk,
                catalogs.Title AS Judul,
                catalogs.Author AS author,
                catalogs.PublishLocation AS TempatTerbit,
                catalogs.Publisher AS Penerbit,
                catalogs.PublishYear AS TahunTerbit,
                (CASE WHEN EDISISERIAL IS NOT NULL THEN EdisiSerial ELSE Edition END) AS Edisi,
                collections.Currency AS currency,
                catalogs.DeweyNo AS NoKelas,
                (SELECT catalogs.ISBN FROM catalogs WHERE collections.Catalog_id = catalogs.ID) AS i,
                collections.TanggalPengadaan AS tanggalpengadaan,
                worksheets.Name AS JenisBahan,
                partners.Name AS Partner,
                collectionmedias.Name AS BentukFisik,
                catalogs.PhysicalDescription AS deskripsi,
                collectionsources.name AS JenisSumber,
                collectioncategorys.Name AS Kategori,
                collections.Price AS Price,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '022' LIMIT 1) AS issn,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '020' LIMIT 1) AS isbn,
                master_kelas_besar.kdKelas AS klass
                FROM collections
                LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                LEFT JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                LEFT JOIN partners ON collections.Partner_ID = partners.ID 
                LEFT JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID
                LEFT JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                LEFT JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                LEFT JOIN master_kelas_besar ON SUBSTRING(catalogs.DeweyNo, 1, 1) = SUBSTRING(master_kelas_besar.kdKelas, 1, 1) 
                WHERE DATE(collections.TanggalPengadaan) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY collections.ID, issn, isbn";
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] = $this->getRealNameKriteria($value);
        }
        $Berdasarkan = implode(yii::t('app',' dan '),$Berdasarkan);

        // if (count($_POST['kriterias']) == 1) {
        //     $Berdasarkan .= ' '.implode($_POST[implode($_POST['kriterias'])]);
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
            'content' => $this->renderPartial('pdf-view-koleksi-tampilkan-data-buku-induk', $content),
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
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

public function actionExportExcelBukuIndukData()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2 = 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2 = 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2 = 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                }
            }
        } 
        if (isset($_POST['tono_klas'])) {
            foreach ($_POST['tono_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                }
            }
        } 
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(collections.CreateDate) BETWEEN "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = " SELECT collections.ID AS ID,
                collections.NoInduk AS no_induk,
                catalogs.Title AS Judul,
                catalogs.Author AS author,
                catalogs.PublishLocation AS TempatTerbit,
                catalogs.Publisher AS Penerbit,
                catalogs.PublishYear AS TahunTerbit,
                (CASE WHEN EDISISERIAL IS NOT NULL THEN EdisiSerial ELSE Edition END) AS Edisi,
                collections.Currency AS currency,
                catalogs.DeweyNo AS NoKelas,
                (SELECT catalogs.ISBN FROM catalogs WHERE collections.Catalog_id = catalogs.ID) AS i,
                collections.TanggalPengadaan AS tanggalpengadaan,
                worksheets.Name AS JenisBahan,
                partners.Name AS Partner,
                collectionmedias.Name AS BentukFisik,
                catalogs.PhysicalDescription AS deskripsi,
                collectionsources.name AS JenisSumber,
                collectioncategorys.Name AS Kategori,
                collections.Price AS Price,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '022' LIMIT 1) AS issn,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '020' LIMIT 1) AS isbn,
                master_kelas_besar.kdKelas AS klass
                FROM collections
                LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                LEFT JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                LEFT JOIN partners ON collections.Partner_ID = partners.ID 
                LEFT JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID
                LEFT JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                LEFT JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                LEFT JOIN master_kelas_besar ON SUBSTRING(catalogs.DeweyNo, 1, 1) = SUBSTRING(master_kelas_besar.kdKelas, 1, 1) 
                WHERE DATE(collections.TanggalPengadaan) ";
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY collections.ID, issn, isbn";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;
// $test = self::getRealNameKriteria($kriterias);

    $filename = 'Laporan_Periodik_Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="18">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="18">'.yii::t('app','Buku Induk Perpustakaan').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="18">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Pengadaan').'</th>
                <th>'.yii::t('app','Nomer Induk').'</th>
                <th>'.yii::t('app','Jenis Bahan').'</th>
                <th>'.yii::t('app','Bentuk Fisik').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','Edisi').'</th>
                <th>'.yii::t('app','Tempat Terbit').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Tahun Terbit').'</th>
                <th>'.yii::t('app','Deskripsi Fisik').'</th>
                <th>'.yii::t('app','Jenis Sumber Perolehan').'</th>
                <th>'.yii::t('app','Nama Sumber Perolehan').'</th>
                <th>'.yii::t('app','Kategori').'</th>
                <th>'.yii::t('app','ISBN').'</th>
                <th>'.yii::t('app','ISSN').'</th>
                <th>'.yii::t('app','Harga').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tanggalpengadaan'].'</td>
                    <td>'.$data['no_induk'].'</td>
                    <td>'.$data['JenisBahan'].'</td>
                    <td>'.$data['BentukFisik'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['author'].'</td>
                    <td>'.$data['Edisi'].'</td>
                    <td>'.$data['TempatTerbit'].'</td>
                    <td>'.$data['Penerbit'].'</td>
                    <td>'.$data['TahunTerbit'].'</td>
                    <td>'.$data['deskripsi'].'</td>
                    <td>'.$data['JenisSumber'].'</td>
                    <td>'.$data['Partner'].'</td>
                    <td>'.$data['Kategori'].'</td>
                    <td>'.$data['isbn'].'</td>
                    <td>'.$data['issn'].'</td>
                    <td>'.$data['Currency'].' - '.$data['Price']. '</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

public function actionExportExcelOdtBukuIndukData()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2 = 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = yii::t('app','Bulanan');
                $periode2 = 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = yii::t('app','Tahunan');
                $periode2 = 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                }
            }
        } 
        if (isset($_POST['tono_klas'])) {
            foreach ($_POST['tono_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                }
            }
        } 
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(collections.CreateDate) BETWEEN "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = " SELECT collections.ID AS ID,
                collections.NoInduk AS NoInduk,
                catalogs.Title AS Judul,
                catalogs.Author AS author,
                catalogs.PublishLocation AS TempatTerbit,
                catalogs.Publisher AS Penerbit,
                catalogs.PublishYear AS TahunTerbit,
                (CASE WHEN EDISISERIAL IS NOT NULL THEN EdisiSerial ELSE Edition END) AS Edisi,
                collections.Currency AS currency,
                catalogs.DeweyNo AS NoKelas,
                (SELECT catalogs.ISBN FROM catalogs WHERE collections.Catalog_id = catalogs.ID) AS i,
                collections.TanggalPengadaan AS TanggalPengadaan,
                worksheets.Name AS JenisBahan,
                partners.Name AS Partner,
                collectionmedias.Name AS BentukFisik,
                catalogs.PhysicalDescription AS deskripsi,
                collectionsources.name AS JenisSumber,
                collectioncategorys.Name AS Kategori,
                collections.Price AS Price,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '022' LIMIT 1) AS issn,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '020' LIMIT 1) AS isbn,
                master_kelas_besar.kdKelas AS klass
                FROM collections
                LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                LEFT JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                LEFT JOIN partners ON collections.Partner_ID = partners.ID 
                LEFT JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID
                LEFT JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                LEFT JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                LEFT JOIN master_kelas_besar ON SUBSTRING(catalogs.DeweyNo, 1, 1) = SUBSTRING(master_kelas_besar.kdKelas, 1, 1) 
                WHERE DATE(collections.TanggalPengadaan) ";
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY collections.ID, issn, isbn";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');


    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'TanggalPengadaan'=> $model['TanggalPengadaan'], 'NoInduk'=>$model['NoInduk'], 'JenisBahan'=>$model['JenisBahan'], 'BentukFisik'=>$model['BentukFisik'], 'Judul'=>$model['Judul']
                         , 'Pengarang'=>$model['Pengarang'], 'Edisi'=>$model['Edisi'], 'TempatTerbit'=>$model['TempatTerbit'], 'Penerbit'=>$model['Penerbit'], 'TahunTerbit'=>$model['TahunTerbit'], 'deskripsi'=>$model['deskripsi'], 'JenisSumber'=>$model['JenisSumber']
                         , 'Partner'=>$model['Partner'], 'Kategori'=>$model['Kategori'], 'isbn'=>$model['isbn'], 'issn'=>$model['issn'], 'Currency'=>$model['Currency'], 'Price'=>$model['Price'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );

    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'buku_induk'=> yii::t('app','Buku Induk Perpustakaan'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_pengadaan'=> yii::t('app','Tanggal Pengadaan'),
        'nomor_induk'=> yii::t('app','Nomor Induk'),
        'jenis_bahan'=> yii::t('app','Jenis Bahan'),
        'bentuk_fisik'=> yii::t('app','Bentuk Fisik'),
        'judul'=> yii::t('app','Judul'),
        'pengarang'=> yii::t('app','Pengarang'),
        'edisi'=> yii::t('app','Edisi'),
        'tempat_terbit'=> yii::t('app','Tempat Terbit'),
        'penerbit'=> yii::t('app','Penerbit'),
        'tahun_terbit'=> yii::t('app','Tahun Terbit'),
        'deskripsi_fisik'=> yii::t('app','Deskripsi Fisik'),
        'jenis_sumber_perolehan'=> yii::t('app','Jenis Sumber Perolehan'),
        'nama_sumber_perolehan'=> yii::t('app','Nama Sumber Perolehan'),
        'kategori'=> yii::t('app','Kategori'),
        'kategori'=> yii::t('app','ISBN'),
        'ISSN'=> yii::t('app','ISSN'),
        'harga'=> yii::t('app','Harga'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-koleksi-buku-induk-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-buku-induk.ods');
    // !Open Office Calc Area


}

public function actionExportWordBukuIndukData()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = 'Harian';
                $periode2 = 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = 'Bulanan';
                $periode2 = 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = 'Tahunan';
                $periode2 = 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                }
            }
        } 
        if (isset($_POST['tono_klas'])) {
            foreach ($_POST['tono_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                }
            }
        } 
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(collections.CreateDate) BETWEEN "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = " SELECT collections.ID AS ID,
                collections.NoInduk AS no_induk,
                catalogs.Title AS Judul,
                catalogs.Author AS author,
                catalogs.PublishLocation AS TempatTerbit,
                catalogs.Publisher AS Penerbit,
                catalogs.PublishYear AS TahunTerbit,
                (CASE WHEN EDISISERIAL IS NOT NULL THEN EdisiSerial ELSE Edition END) AS Edisi,
                collections.Currency AS currency,
                catalogs.DeweyNo AS NoKelas,
                (SELECT catalogs.ISBN FROM catalogs WHERE collections.Catalog_id = catalogs.ID) AS i,
                collections.TanggalPengadaan AS tanggalpengadaan,
                worksheets.Name AS JenisBahan,
                partners.Name AS Partner,
                collectionmedias.Name AS BentukFisik,
                catalogs.PhysicalDescription AS deskripsi,
                collectionsources.name AS JenisSumber,
                collectioncategorys.Name AS Kategori,
                collections.Price AS Price,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '022' LIMIT 1) AS issn,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '020' LIMIT 1) AS isbn,
                master_kelas_besar.kdKelas AS klass
                FROM collections
                LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                LEFT JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                LEFT JOIN partners ON collections.Partner_ID = partners.ID 
                LEFT JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID
                LEFT JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                LEFT JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                LEFT JOIN master_kelas_besar ON SUBSTRING(catalogs.DeweyNo, 1, 1) = SUBSTRING(master_kelas_besar.kdKelas, 1, 1) 
                WHERE DATE(collections.TanggalPengadaan) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY collections.ID, issn, isbn";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

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
            <tr>
                <th colspan="18">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="18">'.yii::t('app','Buku Induk Perpustakaan').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="18">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Pengadaan').'</th>
                <th>'.yii::t('app','Nomer Induk').'</th>
                <th>'.yii::t('app','Jenis Bahan').'</th>
                <th>'.yii::t('app','Bentuk Fisik').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','Edisi').'</th>
                <th>'.yii::t('app','Tempat Terbit').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
                <th>'.yii::t('app','Tahun Terbit').'</th>
                <th>'.yii::t('app','Deskripsi Fisik').'</th>
                <th>'.yii::t('app','Jenis Sumber Perolehan').'</th>
                <th>'.yii::t('app','Nama Sumber Perolehan').'</th>
                <th>'.yii::t('app','Kategori').'</th>
                <th>'.yii::t('app','ISBN').'</th>
                <th>'.yii::t('app','ISSN').'</th>
                <th>'.yii::t('app','Harga').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['tanggalpengadaan'].'</td>
                    <td>'.$data['no_induk'].'</td>
                    <td>'.$data['JenisBahan'].'</td>
                    <td>'.$data['BentukFisik'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['author'].'</td>
                    <td>'.$data['Edisi'].'</td>
                    <td>'.$data['TempatTerbit'].'</td>
                    <td>'.$data['Penerbit'].'</td>
                    <td>'.$data['TahunTerbit'].'</td>
                    <td>'.$data['deskripsi'].'</td>
                    <td>'.$data['JenisSumber'].'</td>
                    <td>'.$data['Partner'].'</td>
                    <td>'.$data['Kategori'].'</td>
                    <td>'.$data['isbn'].'</td>
                    <td>'.$data['issn'].'</td>
                    <td>'.$data['Currency'].' - '.$data['Price']. '</td>
                </tr>
            ';
                        $no++;
                    endforeach;        
    echo '</table>';

}

    public function actionExportPdfBukuIndukData() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = 'Harian';
                $periode2 = 'Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode = 'Bulanan';
                $periode2 = 'Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode = 'Tahunan';
                $periode2 = 'Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                }
            }
        } 
        if (isset($_POST['tono_klas'])) {
            foreach ($_POST['tono_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                }
            }
        } 
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(collections.CreateDate) BETWEEN "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }  

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = " SELECT collections.ID AS ID,
                collections.NoInduk AS no_induk,
                catalogs.Title AS Judul,
                catalogs.Author AS author,
                catalogs.PublishLocation AS TempatTerbit,
                catalogs.Publisher AS Penerbit,
                catalogs.PublishYear AS TahunTerbit,
                (CASE WHEN EDISISERIAL IS NOT NULL THEN EdisiSerial ELSE Edition END) AS Edisi,
                collections.Currency AS currency,
                catalogs.DeweyNo AS NoKelas,
                (SELECT catalogs.ISBN FROM catalogs WHERE collections.Catalog_id = catalogs.ID) AS i,
                collections.TanggalPengadaan AS tanggalpengadaan,
                worksheets.Name AS JenisBahan,
                partners.Name AS Partner,
                collectionmedias.Name AS BentukFisik,
                catalogs.PhysicalDescription AS deskripsi,
                collectionsources.name AS JenisSumber,
                collectioncategorys.Name AS Kategori,
                collections.Price AS Price,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '022' LIMIT 1) AS issn,
                (SELECT SPLIT_STR(catalog_ruas.Value,' ',2) FROM catalog_ruas WHERE catalog_ruas.CatalogId = collections.Catalog_id AND catalog_ruas.Tag = '020' LIMIT 1) AS isbn,
                master_kelas_besar.kdKelas AS klass
                FROM collections
                LEFT JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                LEFT JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                LEFT JOIN partners ON collections.Partner_ID = partners.ID 
                LEFT JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID
                LEFT JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                LEFT JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                LEFT JOIN master_kelas_besar ON SUBSTRING(catalogs.DeweyNo, 1, 1) = SUBSTRING(master_kelas_besar.kdKelas, 1, 1) 
                WHERE DATE(collections.TanggalPengadaan) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY collections.ID, issn, isbn";
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] = $this->getRealNameKriteria($value);
        }
        $Berdasarkan = implode(yii::t('app',' dan '),$Berdasarkan);

        // if (count($_POST['kriterias']) == 1) {
        //     $Berdasarkan .= ' '.implode($_POST[implode($_POST['kriterias'])]);
        // }

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan; 
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            // $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 233; width: 100%;" >'];
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
            'title' => 'Laporan Frekuensi',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-view-koleksi-tampilkan-data-buku-induk', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

}

    /////// Koleksi AccessionList Area
    /**
     * [actionAccessionList description]
     * @return [type] [description]
     */
    public function actionAccessionList()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('koleksi-accession-list',[
            'model' => $model,
            ]);
    }




    /**
     * [actionRenderPdfDataBukuIduk description]
     * @return [type] [description]
     */
    public function actionRenderPdfDataAccessionList() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $accesion = "CONCAT( '<b>',SUBSTRING_INDEX(catalogs.Title,' ',1),'</b>', SUBSTRING( Title,
                    LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1),
                    (CASE WHEN collections.EDISISERIAL = ''
                     THEN collections.EDISISERIAL
                     ELSE IFNULL(CONCAT(' -- ',(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END)), ' ')
                    END)
                    ,CONCAT(' -- ', PublishLocation), Publisher,' ', PublishYear, GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '', CONCAT('','<br />','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',NoInduk) ) SEPARATOR '') )";

        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian').' <br/>Periode '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan').' <br/>Periode '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan').' <br/>Periode '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = "SELECT 
                Title AS judul, 
                (CASE
                 WHEN LENGTH(CONCAT( '',SUBSTRING_INDEX(catalogs.Title,' ',1),'', SUBSTRING( Title,
                LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1),(CASE WHEN collections.EDISISERIAL = '' THEN collections.EDISISERIAL
                 ELSE IFNULL(CONCAT(' -- ',(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END)), ' ')
                 END)
                ,CONCAT(' -- ', PublishLocation),' ', Publisher,' ', PublishYear, GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '',
                CONCAT('',' ',NoInduk) ) SEPARATOR '') )) >= 136 THEN 
                 CONCAT(SUBSTRING(".$accesion.",1,135),'<br />','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                 SUBSTRING(".$accesion.",136))
                 ELSE ".$accesion."
                 END) AS AccessionList
                FROM 
                collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                WHERE DATE(collections.TanggalPengadaan) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY title,author,publishlocation,publisher,publishyear ORDER BY collections.TanggalPengadaan";

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 
        

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] = $this->getRealNameKriteria($value);
        }
        $Berdasarkan = implode(yii::t('app',' dan '),$Berdasarkan);

        // if (count($_POST['kriterias']) == 1) {
        //     $Berdasarkan .= ' '.implode($_POST[implode($_POST['kriterias'])]);
        // }

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan; 
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
            'content' => $this->renderPartial('pdf-view-koleksi-tampilkan-data-accession-lists', $content),
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
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

public function actionExportExcelAccesionListData()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $accesion = "CONCAT( '<b>',SUBSTRING_INDEX(catalogs.Title,' ',1),'</b>', SUBSTRING( Title,
                    LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1),
                    (CASE WHEN collections.EDISISERIAL = ''
                     THEN collections.EDISISERIAL
                     ELSE IFNULL(CONCAT(' -- ',(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END)), ' ')
                    END)
                    ,CONCAT(' -- ', PublishLocation), Publisher,' ', PublishYear, GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '', CONCAT('','<br />','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',NoInduk) ) SEPARATOR '') )";

        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian').' <br/>'.yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan').' <br/>'.yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan').' <br/>'.yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = "SELECT 
                Title AS judul, 
                CONCAT(
                (CASE
                 WHEN LENGTH(CONCAT( '',SUBSTRING_INDEX(catalogs.Title,' ',1),'', SUBSTRING( Title,
                LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1),(CASE WHEN collections.EDISISERIAL = '' THEN collections.EDISISERIAL
                 ELSE IFNULL(CONCAT(' -- ',(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END)), ' ')
                 END)
                ,CONCAT(' -- ', PublishLocation),' ', Publisher,' ', PublishYear, GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '',
                CONCAT('',' ',NoInduk) ) SEPARATOR '') )) >= 136 THEN
                 CONCAT(SUBSTRING(".$accesion.",1,126),'<br>','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',SUBSTRING(".$accesion.",127))
                 ELSE ".$accesion."
                 END)
                ) AS AccessionList
                FROM 
                collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                WHERE DATE(collections.TanggalPengadaan) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY title,author,publishlocation,publisher,publishyear ORDER BY collections.TanggalPengadaan";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $filename = 'Laporan_Accession_List_Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="7">Accession List ('.yii::t('app','Daftar Koleksi Tambahan').') '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="7">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr>
                    <td style="vertical-align: top;">'.$no.'</td>
                    <td colspan="7">'.$data['AccessionList'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtAcessionListData()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $accesion = "CONCAT( '<b>',SUBSTRING_INDEX(catalogs.Title,' ',1),'</b>', SUBSTRING( Title,
                    LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1),
                    (CASE WHEN collections.EDISISERIAL = ''
                     THEN collections.EDISISERIAL
                     ELSE IFNULL(CONCAT(' -- ',(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END)), ' ')
                    END)
                    ,CONCAT(' -- ', PublishLocation), Publisher,' ', PublishYear, GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '', CONCAT('','<br />','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',NoInduk) ) SEPARATOR '') )";

        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2 = ' '.yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan ');
                $periode2 = ' '.yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan ');
                $periode2 = ' '.yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = "SELECT 
                Title AS judul, 
                SUBSTRING_INDEX(catalogs.Title,' ',1) AS data,
                CONCAT(SUBSTRING( Title, LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1), ' -- ') AS data2,
                CONCAT(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END,' -- ') AS data3,
                PublishLocation AS data4,
                Publisher AS data5,
                PublishYear AS data6,
                GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '', CONCAT('</n>','','',NoInduk) ) SEPARATOR '') AS data7
                FROM 
                collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                WHERE DATE(collections.TanggalPengadaan) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY title,author,publishlocation,publisher,publishyear ORDER BY collections.TanggalPengadaan";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'data'=> $model['data'],'data2'=> $model['data2'],'data3'=> $model['data3'],'data4'=> $model['data4'],'data5'=> $model['data5'],'data6'=> $model['data6'],'data7'=> $model['data7'] );
    endforeach;

    $detail[] = array(
        'Berdasarkan'=>yii::t('app',$Berdasarkan), 
        'format_hari'=>$format_hari, 
        'periode'=>$periode,
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'daftar_koleksi_tambahan'=> yii::t('app','Daftar Koleksi Tambahan'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-koleksi-acession-list-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-koleksi-acession-list.ods');
    // !Open Office Calc Area


}

public function actionExportWordAccesionListData()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $accesion = "CONCAT( '<b>',SUBSTRING_INDEX(catalogs.Title,' ',1),'</b>', SUBSTRING( Title,
                    LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1),
                    (CASE WHEN collections.EDISISERIAL = ''
                     THEN collections.EDISISERIAL
                     ELSE IFNULL(CONCAT(' -- ',(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END)), ' ')
                    END)
                    ,CONCAT(' -- ', PublishLocation), Publisher,' ', PublishYear, GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '', CONCAT('','<br />','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',NoInduk) ) SEPARATOR '') )";

        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian').' <br/>'.yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan').' <br/>'.yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan').' <br/>'.yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = "SELECT 
                Title AS judul, 
                CONCAT(
                (CASE
                 WHEN LENGTH(CONCAT( '',SUBSTRING_INDEX(catalogs.Title,' ',1),'', SUBSTRING( Title,
                LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1),(CASE WHEN collections.EDISISERIAL = '' THEN collections.EDISISERIAL
                 ELSE IFNULL(CONCAT(' -- ',(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END)), ' ')
                 END)
                ,CONCAT(' -- ', PublishLocation),' ', Publisher,' ', PublishYear, GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '',
                CONCAT('',' ',NoInduk) ) SEPARATOR '') )) >= 136 THEN 
                 CONCAT(SUBSTRING(".$accesion.",1,126),'<br>','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',SUBSTRING(".$accesion.",127))
                 ELSE ".$accesion."
                 END)
                ) AS AccessionList
                FROM 
                collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                WHERE DATE(collections.TanggalPengadaan) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY title,author,publishlocation,publisher,publishyear ORDER BY collections.TanggalPengadaan";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $type = $_GET['type'];
    $filename = 'Laporan_Accession_List_Data.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
                <p align="center"> <b>Accession List ('.yii::t('app','Daftar Koleksi Tambahan').') '.$format_hari.' <br />'.yii::t('app','Berdasarkan').' '.yii::t('app',$Berdasarkan).' </b></p>
            ';
    echo '</table>';
        $no = 1;
    echo '<table border="0" align="center"> ';
        foreach($model as $data):
        echo '
                <tr align="left">
                    <td style="vertical-align: top;">'.$no.'</td>
                    <td colspan="6">'.$data['AccessionList'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfAccesionListData()
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $accesion = "CONCAT( '<b>',SUBSTRING_INDEX(catalogs.Title,' ',1),'</b>', SUBSTRING( Title,
                    LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1),
                    (CASE WHEN collections.EDISISERIAL = ''
                     THEN collections.EDISISERIAL
                     ELSE IFNULL(CONCAT(' -- ',(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END)), ' ')
                    END)
                    ,CONCAT(' -- ', PublishLocation), Publisher,' ', PublishYear, GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '', CONCAT('','<br />','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',NoInduk) ) SEPARATOR '') )";

        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian').' <br/>'.yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Bulanan').' <br/>'.yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode = yii::t('app','Tahunan').' <br/>'.yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

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
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Library_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Location_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Source_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Partner_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['currency'])) {
            foreach ($_POST['currency'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collections.Currency = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectioncategorys'])) {
            foreach ($_POST['collectioncategorys'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Category_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionrules'])) {
            foreach ($_POST['collectionrules'] as $key => $value) {
                if ($value != "0"  ) {
                    $andValue .= ' AND Rule_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['worksheets'])) {
            foreach ($_POST['worksheets'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.Worksheet_id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['collectionmedias'])) {
            foreach ($_POST['collectionmedias'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Media_Id = "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['Subject'])) {
            foreach ($_POST['Subject'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Subject = "'.addslashes($value).'" ';
                }
            }
        } 


        if (isset($_POST['no_klas'])) {
            foreach ($_POST['no_klas'] as $key => $value) {
                if ($value != "" ) {
                    $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) >= "'.substr($value,0,1).'" ';
                    }
                }
            } 
            if (isset($_POST['tono_klas'])) {
                foreach ($_POST['tono_klas'] as $key => $value) {
                    if ($value != "" ) {
                        $andValue .= ' AND SUBSTR(catalogs.DeweyNo,1,1) <= "'.substr($value,0,1).'" ';
                    }
                }
            }
            // End No Klas

        if (isset($_POST['no_panggil'])) {
            foreach ($_POST['no_panggil'] as $key => $value) {
                if ($value != "0" ) {

                    if ($_POST['pilihNoPanggil'][$key] == "dimulai_dengan") 
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'%" ';
                    } 
                    else if ($_POST['pilihNoPanggil'][$key] == "diakhiri_dengan")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'" ';
                    }
                    else if ($_POST['pilihNoPanggil'][$key] == "salah_satu_isi")
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "%'.addslashes(substr($value,0,1)).'%" ';
                    }
                    else
                    {
                        $andValue .= ' AND collections.CallNumber LIKE "'.addslashes(substr($value,0,1)).'" ';
                    } 
                }
            }
        } 

        if (isset($_POST['createdate'])) {
            foreach ($_POST['createdate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['tocreatedate'])) {
            foreach ($_POST['tocreatedate'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(collections.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 

        if (isset($_POST['createby'])) {
            foreach ($_POST['createby'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  collections.CreateBy = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['harga'])) {
            foreach ($_POST['harga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price >= "'.$value.'" ';
                }
            }
        } 

        if (isset($_POST['toharga'])) {
            foreach ($_POST['toharga'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Price <= "'.$value.'" ';
                }
            }
        } 

        $sql = "SELECT 
                Title AS judul, 
                CONCAT(
                (CASE
                 WHEN LENGTH(CONCAT( '',SUBSTRING_INDEX(catalogs.Title,' ',1),'', SUBSTRING( Title,
                LENGTH(SUBSTRING_INDEX(catalogs.Title,' ',1))+1),(CASE WHEN collections.EDISISERIAL = '' THEN collections.EDISISERIAL
                 ELSE IFNULL(CONCAT(' -- ',(CASE WHEN collections.EDISISERIAL IS NOT NULL THEN collections.EDISISERIAL ELSE catalogs.Edition END)), ' ')
                 END)
                ,CONCAT(' -- ', PublishLocation),' ', Publisher,' ', PublishYear, GROUP_CONCAT( IF(NoInduk IS NULL OR NoInduk = '', '',
                CONCAT('',' ',NoInduk) ) SEPARATOR '') )) >= 136 THEN 
                 CONCAT(SUBSTRING(".$accesion.",1,136),'<br />','&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',SUBSTRING(".$accesion.",137))
                 ELSE ".$accesion."
                 END)
                ) AS AccessionList
                FROM 
                collections 
                INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID 
                INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID 
                INNER JOIN collectionsources ON collections.Source_ID = collectionsources.ID 
                INNER JOIN collectionmedias ON collections.Media_ID = collectionmedias.ID 
                INNER JOIN collectioncategorys ON collections.Category_ID = collectioncategorys.ID 
                INNER JOIN collectionrules ON collections.Rule_ID = collectionrules.ID 
                WHERE DATE(collections.TanggalPengadaan) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " GROUP BY title,author,publishlocation,publisher,publishyear ORDER BY collections.TanggalPengadaan";

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 
        

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] = $this->getRealNameKriteria($value);
        }
        $Berdasarkan = implode(yii::t('app',' dan '),$Berdasarkan);

        // if (count($_POST['kriterias']) == 1) {
        //     $Berdasarkan .= ' '.implode($_POST[implode($_POST['kriterias'])]);
        // }

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan; 
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            // $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
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
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-view-koleksi-tampilkan-data-accession-lists', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

    }

    /**
     * [actionUcapanTerimakasih description]
     * @return [type] [description]
     */
    public function actionUcapanTerimakasih()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('koleksi-ucapan-terimakasih',[
            'model' => $model,
            ]);
    }

    /**
     * [actionRenderPdfUcapanTerimakasih description]
     * @return [type] [description]
     */
    public function actionRenderPdfUcapanTerimakasih() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue1 = '';
        $andValue2 = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        //
        // $periode = $_POST['perolehan_date'];
        $periode = date("d-m-Y", strtotime($_POST['perolehan_date']) );

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue1 .= " collectionsources.ID = '".$value."' ";
                    }
                }
            } 
        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue2 .= " partners.ID = '".$value."' ";
                    }
                }
            }  

        if (implode($_POST['collectionsources'])  == '0' && implode($_POST['partners']) == '0') {
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id ";
        }else if (implode($_POST['collectionsources'])  != '0' && implode($_POST['partners']) != '0'){
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ".$andValue1." and ".$andValue2." ";
        }else{
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ";
        $sql .= $andValue1;
        $sql .= $andValue2;
        }

// print_r($periode);
// die;

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['sql'] = $sql; 
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
            'content' => $this->renderPartial('pdf-view-koleksi-tampilkan-ucapanterimakasih', $content),
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
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

public function actionExportExcelUcapanTerimaKasih()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue1 = '';
        $andValue2 = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        //
        // $periode = $_POST['perolehan_date'];
        $periode = date("d-m-Y", strtotime($_POST['perolehan_date']) );

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue1 .= " collectionsources.ID = '".$value."' ";
                    }
                }
            } 
        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue2 .= " partners.ID = '".$value."' ";
                    }
                }
            }  

        if (implode($_POST['collectionsources'])  == '0' && implode($_POST['partners']) == '0') {
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id ";
        }else if (implode($_POST['collectionsources'])  != '0' && implode($_POST['partners']) != '0'){
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ".$andValue1." and ".$andValue2." ";
        }else{
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ";
        $sql .= $andValue1;
        $sql .= $andValue2;
        }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    // $Berdasarkan = array();
    //     foreach ($_POST['kriterias'] as $key => $value) {
    //         $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
    //     }
    //     $Berdasarkan = implode(' dan ', $Berdasarkan);

    $filename = 'Laporan_Periodik_Data.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <td width="100px">'.yii::t('app','Nomor').' </td>
                <td>:</td>
            </tr>
            <tr>
                <td>'.yii::t('app','Perihal').' </td>
                <td>: '.yii::t('app','Ucapan Terima Kasih').'</td>
            </tr>
            <tr>
            </tr>
            <tr>
            </tr>
            <tr>
                <td>'.yii::t('app','Dengan Hormat,').' </td>
            </tr>
            <tr>
                <td colspan="3">'.yii::t('app','Melalui surat ini kami informasikan bahwa sumbangan koleksi berupa').' : </td>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['pengarang'].'</td>
                    <td>'.$data['penerbit'].'</td>
                </tr>
            ';
        $no++;
        endforeach;

        echo '<table border="0" align="center"> 
            <tr>
            </tr>
            <tr>
                <td colspan="3">'.yii::t('app','Telah kami terima dalam keadaan baik. Sumbangan tersebut sangat bermanfaat bagi kami.').'</td>
            </tr>
            <tr>
                <td colspan="3">'.yii::t('app','Atas partisipasi dan perhatiannya kami ucapkan terima kasih.').'</td>
            </tr>
            <tr>
            </tr>
            <tr>
            </tr>
            <tr>
            </tr>
            <tr>
                <td>Jakarta, ' .$periode.'</td>
            </tr>
            ';
        
    echo '</table>';

}

public function actionExportExcelOdtUcapanTerimaKasih()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue1 = '';
        $andValue2 = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        //
        // $periode = $_POST['perolehan_date'];
        $periode = date("d-m-Y", strtotime($_POST['perolehan_date']) );

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue1 .= " collectionsources.ID = '".$value."' ";
                    }
                }
            } 
        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue2 .= " partners.ID = '".$value."' ";
                    }
                }
            }  

        if (implode($_POST['collectionsources'])  == '0' && implode($_POST['partners']) == '0') {
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id ";
        }else if (implode($_POST['collectionsources'])  != '0' && implode($_POST['partners']) != '0'){
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ".$andValue1." and ".$andValue2." ";
        }else{
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ";
        $sql .= $andValue1;
        $sql .= $andValue2;
        }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $format_hari = $periode;

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Judul'=> $model['Judul'], 'pengarang'=>$model['pengarang'], 'penerbit'=>$model['penerbit'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode'=>$periode,
        );
    $detail2[] = array(
        'nomor'=> yii::t('app','Nomor'),
        'perihal'=> yii::t('app','Perihal'),
        'ucapan_terima_kasih'=> yii::t('app','Ucapan Terima Kasih'),
        'dengan_hormat'=> yii::t('app','Dengan Hormat,'),
        'melalui_surat_ini'=> yii::t('app','Melalui surat ini kami informasikan bahwa sumbangan koleksi berupa'),
        'judul'=> yii::t('app','Judul'),
        'pengarang'=> yii::t('app','pengarang'),
        'penerbitan'=> yii::t('app','Penerbitan'),
        'telah_kami_terima'=> yii::t('app','Telah kami terima dalam keadaan baik. Sumbangan tersebut sangat bermanfaat bagi kami.'),
        'atas_partisipasi'=> yii::t('app','Atas partisipasi dan perhatiannya kami ucapkan terima kasih.'),
        
        );
// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-koleksi-ucapan-terima-kasih.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-ucapan-terimakasih.ods');
    // !Open Office Calc Area


}

public function actionExportWordUcapanTerimaKasih()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue1 = '';
        $andValue2 = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        //
        // $periode = $_POST['perolehan_date'];
        $periode = date("d-m-Y", strtotime($_POST['perolehan_date']) );

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue1 .= " collectionsources.ID = '".$value."' ";
                    }
                }
            } 
        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue2 .= " partners.ID = '".$value."' ";
                    }
                }
            }  

        if (implode($_POST['collectionsources'])  == '0' && implode($_POST['partners']) == '0') {
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id ";
        }else if (implode($_POST['collectionsources'])  != '0' && implode($_POST['partners']) != '0'){
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ".$andValue1." and ".$andValue2." ";
        }else{
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ";
        $sql .= $andValue1;
        $sql .= $andValue2;
        }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    // $Berdasarkan = array();
    //     foreach ($_POST['kriterias'] as $key => $value) {
    //         $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
    //     }
    //     $Berdasarkan = implode(' dan ', $Berdasarkan);

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '

                <p>'.yii::t('app','Nomor').' :</p>
                <p>'.yii::t('app','Perihal').' : '.yii::t('app','Ucapan Terima Kasih').'</p>


                <p>'.yii::t('app','Dengan Hormat,').'<br>'.yii::t('app','Melalui surat ini kami informasikan bahwa sumbangan koleksi berupa').' : </p>




        ';
     if ($type != "doc") {
        echo '<table border="0">';
     }else{echo '<table border="1">';}
        '<tr>
                <th>No.</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Pengarang').'</th>
                <th>'.yii::t('app','Penerbit').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['pengarang'].'</td>
                    <td>'.$data['penerbit'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        echo '</table>';

        echo '

                <p>'.yii::t('app','Telah kami terima dalam keadaan baik. Sumbangan tersebut sangat bermanfaat bagi kami.').'<br />'.yii::t('app','Atas partisipasi dan perhatiannya kami ucapkan terima kasih.').'<br /><br /><br /></p>




                <p>Jakarta, ' .$periode.'</p>

            ';
        

}

public function actionExportPdfUcapanTerimaKasih()
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue1 = '';
        $andValue2 = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        //
        $periode = $_POST['perolehan_date'];

        if (isset($_POST['collectionsources'])) {
            foreach ($_POST['collectionsources'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue1 .= " collectionsources.ID = '".$value."' ";
                    }
                }
            } 
        if (isset($_POST['partners'])) {
            foreach ($_POST['partners'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue2 .= " partners.ID = '".$value."' ";
                    }
                }
            }  

        if (implode($_POST['collectionsources'])  == '0' && implode($_POST['partners']) == '0') {
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id ";
        }else if (implode($_POST['collectionsources'])  != '0' && implode($_POST['partners']) != '0'){
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ".$andValue1." and ".$andValue2." ";
        }else{
            $sql = "SELECT catalogs.Title AS Judul, 
                    catalogs.Author AS pengarang, 
                    catalogs.Publisher AS penerbit 
                    FROM catalogs 
                    INNER JOIN collections ON collections.Catalog_id = catalogs.ID 
                    INNER JOIN collectionsources ON collectionsources.ID = collections.Source_id
                    INNER JOIN partners ON partners.ID = collections.Partner_id 
                    where ";
        $sql .= $andValue1;
        $sql .= $andValue2;
        }


        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['sql'] = $sql; 
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            // $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
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
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-view-koleksi-tampilkan-ucapanterimakasih', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

    }

    // Laporan Usulan Koleksi
    
    /**
     * [actionUcapanTerimakasih description]
     * @return [type] [description]
     */
    public function actionUsulanKoleksi()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('koleksi-usulan-koleksi',[
            'model' => $model,
            ]);
    }

    /**
     * [actionRenderPdfFrekuensiUsulanKoleksi description]
     * @return [type] [description]
     */
    public function actionRenderPdfFrekuensiUsulanKoleksi() 
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
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'CONCAT(members.MemberNo, ' - ', members.Fullname) AS Subjek';
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.PublishLocation AS Subjek';
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.Publisher AS Subjek';
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishYear = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'requestcatalog.PublishYear AS Subjek';
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode,
        DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode2, 
        ".$subjek.",
        COUNT(DISTINCT Title) AS CountJudul 
        FROM requestcatalog   
        INNER JOIN members ON requestcatalog.MemberID = members.ID 
        WHERE DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;

        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(requestcatalog.DateRequest,'%d-%m-%Y') ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                }

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        // Untuk Sub Judul Berdasarkan
        // if (count($_POST['kriterias']) == 1) {
        //     $kriteria = implode($_POST['kriterias']);
        //     $value = implode($_POST[$kriteria]);
        //     // $Berdasarkan = $this->getRealNameKriteria($kriteria).' : '.$value;
        //     $Berdasarkan = $this->getRealNameKriteria($kriteria);
        // } else {
        //     $Berdasarkan = '';
        //     foreach ($_POST['kriterias'] as $key => $value) {
        //         ($key != 1 ? null : $Berdasarkan .= ' dan ');
        //         $Berdasarkan .= $this->getRealNameKriteria($value);
        //     }
        // }

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        $content['LaporanKriteria'] = ""; 
        $content['Berdasarkan'] = $Berdasarkan; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
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
            'content' => $this->renderPartial('pdf-view-koleksi-frekuensi-usulan', $content),
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
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

public function actionExportExcelUsulanKoleksiFrekuensi()
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
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'CONCAT(members.MemberNo, ' - ', members.Fullname) AS Subjek';
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.PublishLocation AS Subjek';
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.Publisher AS Subjek';
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishYear = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'requestcatalog.PublishYear AS Subjek';
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode,
        DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode2, 
        ".$subjek.",
        COUNT(DISTINCT Title) AS CountJudul 
        FROM requestcatalog   
        INNER JOIN members ON requestcatalog.MemberID = members.ID 
        WHERE DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;

        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(requestcatalog.DateRequest,'%d-%m-%Y') ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

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
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="3"';} else {echo 'colspan="4"';}echo '>'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="3"';} else {echo 'colspan="4"';}echo '>'.yii::t('app','Usulan Koleksi').' '.$periode2.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="3"';} else {echo 'colspan="4"';}echo '>'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Periode Pengadaan').'</th>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<th>'.yii::t('app',$Berdasarkan).'</th>'; }
    echo'
                <th>'.yii::t('app','Jumlah Judul Diusulkan').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
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
                    <td>'.$data['CountJudul'].'</td>
                </tr>
            ';
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtUsulanKoleksiFrekuensi()
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
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'CONCAT(members.MemberNo, ' - ', members.Fullname) AS Subjek';
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.PublishLocation AS Subjek';
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.Publisher AS Subjek';
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishYear = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'requestcatalog.PublishYear AS Subjek';
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode,
        DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode2, 
        ".$subjek.",
        COUNT(DISTINCT Title) AS CountJudul 
        FROM requestcatalog   
        INNER JOIN members ON requestcatalog.MemberID = members.ID 
        WHERE DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;

        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(requestcatalog.DateRequest,'%d-%m-%Y') ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Subjek'=>$model['Subjek'], 'CountJudul'=>$model['CountJudul'] );
            $CountJudul = $CountJudul + $model['CountJudul'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>yii::t('app',$Berdasarkan), 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalCountJudul'=>$CountJudul,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'usulan_koleksi'=> yii::t('app','Usulan Koleksi'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_pengadaan'=> yii::t('app','Tanggal Pengadaan'),
        'jumlah_judul_diusulkan'=> yii::t('app','Jumlah Judul Diusulkan'),
        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
if (sizeof($_POST['kriterias']) == 1) {
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-koleksi-usulan-koleksi-frekuensi.ods'; 
}else{
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-koleksi-usulan-koleksi-frekuensi_no_subjek.ods'; 
}

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-usulan-koleksi-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordUsulanKoleksiFrekuensi()
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
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'CONCAT(members.MemberNo, ' - ', members.Fullname) AS Subjek';
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.PublishLocation AS Subjek';
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.Publisher AS Subjek';
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishYear = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'requestcatalog.PublishYear AS Subjek';
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode,
        DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode2, 
        ".$subjek.",
        COUNT(DISTINCT Title) AS CountJudul 
        FROM requestcatalog   
        INNER JOIN members ON requestcatalog.MemberID = members.ID 
        WHERE DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;

        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(requestcatalog.DateRequest,'%d-%m-%Y') ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

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
    echo '<table border="0" align="center" width="700"> 
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="3"';} else {echo 'colspan="4"';}echo '>'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="3"';} else {echo 'colspan="4"';}echo '>'.yii::t('app','Usulan Koleksi').' '.$periode2.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="3"';} else {echo 'colspan="4"';}echo '>'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Pengadaan').'</th>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<th>'.yii::t('app',$Berdasarkan).'</th>'; }
    echo'
                <th>'.yii::t('app','Jumlah Judul Diusulkan').'</th>
            </tr>
            ';
        $no = 1;
        $Jumlah = 0;
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
                    <td>'.$data['CountJudul'].'</td>
                </tr>
            ';
                        $Jumlah = $Jumlah + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$Jumlah.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfUsulanKoleksiFrekuensi()
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
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'CONCAT(members.MemberNo, ' - ', members.Fullname) AS Subjek';
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.PublishLocation AS Subjek';
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        $subjek = 'requestcatalog.Publisher AS Subjek';
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND requestcatalog.PublishYear = "'.addslashes($value).'" ';
                }
            }
        $subjek = 'requestcatalog.PublishYear AS Subjek';
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode,
        DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS Periode2, 
        ".$subjek.",
        COUNT(DISTINCT Title) AS CountJudul 
        FROM requestcatalog   
        INNER JOIN members ON requestcatalog.MemberID = members.ID 
        WHERE DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;

        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(requestcatalog.DateRequest,'%d-%m-%Y') ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC ";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(requestcatalog.DateRequest) ORDER BY DATE_FORMAT(requestcatalog.DateRequest,'%Y-%m-%d') DESC";
                }

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        // Untuk Sub Judul Berdasarkan
        // if (count($_POST['kriterias']) == 1) {
        //     $kriteria = implode($_POST['kriterias']);
        //     $value = implode($_POST[$kriteria]);
        //     // $Berdasarkan = $this->getRealNameKriteria($kriteria).' : '.$value;
        //     $Berdasarkan = $this->getRealNameKriteria($kriteria);
        // } else {
        //     $Berdasarkan = '';
        //     foreach ($_POST['kriterias'] as $key => $value) {
        //         ($key != 1 ? null : $Berdasarkan .= ' dan ');
        //         $Berdasarkan .= $this->getRealNameKriteria($value);
        //     }
        // }

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        $content['LaporanKriteria'] = ""; 
        $content['Berdasarkan'] = $Berdasarkan; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            // $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
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
        $content = $this->renderPartial('pdf-view-koleksi-frekuensi-usulan', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }


    /**
     * [actionRenderPdfDataUsulanKoleksi description]
     * @return [type] [description]
     */
    public function actionRenderPdfDataUsulanKoleksi() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS TanggalPengusulan, 
                Title as Judul, 
                CONCAT(PublishLocation,' ',Publisher,' ',PublishLocation) as Penerbitan,
                members.FullName as Anggota, 
                (CASE WHEN Status IS NULL THEN 'Usulan' ELSE Status END) as StatusUsulan 
                FROM requestcatalog 
                INNER JOIN members ON requestcatalog.MemberID = members.ID AND 
                DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY requestcatalog.DateRequest ";

        // Untuk Sub Judul Berdasarkan
        // if (count($_POST['kriterias']) == 1) {
        //     $kriteria = implode($_POST['kriterias']);
        //     $value = implode($_POST[$kriteria]);
        //     // $Berdasarkan = $this->getRealNameKriteria($kriteria).' : '.$value;
        //     $Berdasarkan = $this->getRealNameKriteria($kriteria);
        // } else {
        //     $Berdasarkan = '';
        //     foreach ($_POST['kriterias'] as $key => $value) {
        //         ($key != 1 ? null : $Berdasarkan .= ' dan ');
        //         $Berdasarkan .= $this->getRealNameKriteria($value);
        //     }
        // }
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);


        $content['LaporanKriteria'] = ""; 
        $content['Berdasarkan'] = $Berdasarkan; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
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
            'content' => $this->renderPartial('pdf-view-koleksi-data-usulan', $content),
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
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

public function actionExportExcelUsulanKoleksiData()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan'); 
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS TanggalPengusulan, 
                Title as Judul, 
                CONCAT(PublishLocation,' ',Publisher,' ',PublishLocation) as Penerbitan,
                members.FullName as Anggota, 
                (CASE WHEN Status IS NULL THEN 'Usulan' ELSE Status END) as StatusUsulan 
                FROM requestcatalog 
                INNER JOIN members ON requestcatalog.MemberID = members.ID AND 
                DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY requestcatalog.DateRequest ";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), yii::t('app',$Berdasarkan));

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
                <th colspan="6">'.yii::t('app','Usulan Koleksi').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbitan').'</th>
                <th>'.yii::t('app','Anggota Pengusul').'</th>
                <th>'.yii::t('app','Status Usulan').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['TanggalPengusulan'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['Penerbitan'].'</td>
                    <td>'.$data['Anggota'].'</td>
                    <td>'.$data['StatusUsulan'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtUsulanKoleksiData()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan'); 
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS TanggalPengusulan, 
                Title as Judul, 
                CONCAT(PublishLocation,' ',Publisher,' ',PublishLocation) as Penerbitan,
                members.FullName as Anggota, 
                (CASE WHEN Status IS NULL THEN 'Usulan' ELSE Status END) as StatusUsulan 
                FROM requestcatalog 
                INNER JOIN members ON requestcatalog.MemberID = members.ID AND 
                DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY requestcatalog.DateRequest ";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), yii::t('app',$Berdasarkan));

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'TanggalPengusulan'=> $model['TanggalPengusulan'], 'Judul'=>$model['Judul'], 'Penerbitan'=>$model['Penerbitan'], 'Anggota'=>$model['Anggota'], 'StatusUsulan'=>$model['StatusUsulan'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'usulan_koleksi'=> yii::t('app','Usulan Koleksi'),
        'berdasarkan'=> yii::t('app','berdasarkan'),
        'tanggal'=> yii::t('app','Tanggal'),
        'judul'=> yii::t('app','Judul'),
        'penerbitan'=> yii::t('app','Penerbitan'),
        'anggota_pengusul'=> yii::t('app','Anggota Pengusul'),
        'status_usulan'=> yii::t('app','Status Usulan'),
        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-koleksi-usulan-koleksi.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);
    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-koleksi-usulan-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordUsulanKoleksiData()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan'); 
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS TanggalPengusulan, 
                Title as Judul, 
                CONCAT(PublishLocation,' ',Publisher,' ',PublishLocation) as Penerbitan,
                members.FullName as Anggota, 
                (CASE WHEN Status IS NULL THEN 'Usulan' ELSE Status END) as StatusUsulan 
                FROM requestcatalog 
                INNER JOIN members ON requestcatalog.MemberID = members.ID AND 
                DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY requestcatalog.DateRequest ";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), yii::t('app',$Berdasarkan));

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
                <th colspan="6">'.yii::t('app','Usulan Koleksi').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr style="margin-right: 10px; margin-left: 10px;">
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Judul').'</th>
                <th>'.yii::t('app','Penerbitan').'</th>
                <th>'.yii::t('app','Anggota Pengusul').'</th>
                <th>'.yii::t('app','Status Usulan').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['TanggalPengusulan'].'</td>
                    <td>'.$data['Judul'].'</td>
                    <td>'.$data['Penerbitan'].'</td>
                    <td>'.$data['Anggota'].'</td>
                    <td>'.$data['StatusUsulan'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfUsulanKoleksiData()
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['AnggotaPengusul'])) {
            foreach ($_POST['AnggotaPengusul'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND MemberID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['PublishLocation'])) {
            foreach ($_POST['PublishLocation'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND catalogs.PublishLocation LIKE "%'.addslashes($value).'%" ';
                }
            }
        } 

        if (isset($_POST['Publisher'])) {
            foreach ($_POST['Publisher'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND Publisher LIKE "%'.addslashes($value).'%" ';
                }
            }
        }   

        if (isset($_POST['PublishYear'])) {
            foreach ($_POST['PublishYear'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND PublishYear = "'.addslashes($value).'" ';
                }
            }
        } 



        $sql = "SELECT DATE_FORMAT(requestcatalog.DateRequest,'".$periode_format."') AS TanggalPengusulan, 
                Title as Judul, 
                CONCAT(PublishLocation,' ',Publisher,' ',PublishLocation) as Penerbitan,
                members.FullName as Anggota, 
                (CASE WHEN Status IS NULL THEN 'Usulan' ELSE Status END) as StatusUsulan 
                FROM requestcatalog 
                INNER JOIN members ON requestcatalog.MemberID = members.ID AND 
                DATE(requestcatalog.DateRequest) ";

        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= " ORDER BY requestcatalog.DateRequest ";

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), yii::t('app',$Berdasarkan));


        $content['LaporanKriteria'] = ""; 
        $content['Berdasarkan'] = $Berdasarkan; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            // $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
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
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-view-koleksi-data-usulan', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

}

    // Laporan Koleksi Kinerja user
    
    /**
     * [actionKinerjaUser description]
     * @return [type] [description]
     */
    public function actionKinerjaUser()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('koleksi-kinerja-user',[
            'model' => $model,
            ]);
    }

    /**
     * [actionRenderPdfFrekuensiKinerjaUser description]
     * @return [type] [description]
     */
    public function actionRenderPdfFrekuensiKinerjaUser() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];   
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

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
        
        if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
                break;
        }

        $sql = " SELECT DATE_FORMAT(modelhistory.date,'".$periode_format."') AS Periode,
                UserName as Kataloger,
                COUNT(modelhistory.ID) AS Jumlah 
                FROM modelhistory 
                INNER JOIN users ON modelhistory.user_id = users.ID
                WHERE DATE(modelhistory.date) ";

        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'collections' ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } 

        if(sizeof($DetailFilter['kataloger']) != '' || ($_POST['Action']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(sizeof($DetailFilter['kataloger']) != '' && ($_POST['Action']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';}

        // print_r($dan);
        // die;

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['DetailFilter'] = $DetailFilter; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['a'] = $a; 
        $content['dan'] = $dan; 
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
            'content' => $this->renderPartial('pdf-view-koleksi-frekuensi-kinerja', $content),
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
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

public function actionExportExcelKinerjaUserFrekuensi()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];   
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

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
        
        if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
                break;
        }

        $sql = " SELECT DATE_FORMAT(modelhistory.date,'".$periode_format."') AS Periode,
                UserName as Kataloger,
                COUNT(modelhistory.ID) AS Jumlah 
                FROM modelhistory 
                INNER JOIN users ON modelhistory.user_id = users.ID
                WHERE DATE(modelhistory.date) ";

        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'collections' ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } 

        if(sizeof($DetailFilter['kataloger']) != '' || ($_POST['Action']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(sizeof($DetailFilter['kataloger']) != '' && ($_POST['Action']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';}

        // print_r($dan);
        // die;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $periode2 = $periode2;
    $format_hari = $periode; 

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
                <th colspan="4">'.yii::t('app','Kinerja User').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="4">'.$a.' '.$DetailFilter['action'].' '.$dan.' '.$DetailFilter['kataloger'].'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr style="margin-right: 20px; margin-left: 20px;">
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
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

}

public function actionExportExcelOdtKinerjaUserFrekuensi()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];   
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

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
        
        if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
                break;
        }

        $sql = " SELECT DATE_FORMAT(modelhistory.date,'".$periode_format."') AS Periode,
                UserName as Kataloger,
                COUNT(modelhistory.ID) AS Jumlah 
                FROM modelhistory 
                INNER JOIN users ON modelhistory.user_id = users.ID
                WHERE DATE(modelhistory.date) ";

        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'collections' ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } 

        if(sizeof($DetailFilter['kataloger']) != '' || ($_POST['Action']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(sizeof($DetailFilter['kataloger']) != '' && ($_POST['Action']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';}

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $DetailFilterKriteria = $DetailFilter['action'];
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
        'tanggal'=> yii::t('app','Tanggal'),
        'jumlah'=> yii::t('app','Jumlah'),
        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-katalog-kinerja-user.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-koleksi-kinerja-user-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordKinerjaUserFrekuensi()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];   
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

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
        
        if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
                break;
        }

        $sql = " SELECT DATE_FORMAT(modelhistory.date,'".$periode_format."') AS Periode,
                UserName as Kataloger,
                COUNT(modelhistory.ID) AS Jumlah 
                FROM modelhistory 
                INNER JOIN users ON modelhistory.user_id = users.ID
                WHERE DATE(modelhistory.date) ";

        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'collections' ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } 

        if(sizeof($DetailFilter['kataloger']) != '' || ($_POST['Action']) != ''){
            $a = 'Berdasarkan';
        }else{ $a = '';}

        if(sizeof($DetailFilter['kataloger']) != '' && ($_POST['Action']) != ''){
            $dan = 'dan';
        }else{ $dan = '';}

        // print_r($dan);
        // die;

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $periode2 = $periode2;
    $format_hari = $periode; 

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");

    echo '<table border="0" align="center"> 
                <p align="center"> <b>'.yii::t('app','Laporan Frekuensi').' '.$format_hari.' </b></p>
                <p align="center"> <b>'.yii::t('app','Kinerja User').' '.$periode2.' </b></p>
                <p align="center"> <b>'.$a.' '.$DetailFilter['action'].' '.$dan.' '.$DetailFilter['kataloger'].'</b></p>
            ';
    echo '</table>';
        
    if ($type == 'odt') {
    echo '<table border="0" align="center" width="700"> ';
    }else{echo '<table border="1" align="center" width="700"> ';}
        echo '
                <tr>
                    <th>No.</th>
                    <th>'.yii::t('app','Tanggal').'</th>
                    <th>'.yii::t('app','Kataloger').'</th>
                    <th>'.yii::t('app','Jumlah').'</th>
                </tr>
            '; 
    $no = 1;
    $Jumlah = 0;
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
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];   
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

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
        
        if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
                break;
        }

        $sql = " SELECT DATE_FORMAT(modelhistory.date,'".$periode_format."') AS Periode,
                UserName as Kataloger,
                COUNT(modelhistory.ID) AS Jumlah 
                FROM modelhistory 
                INNER JOIN users ON modelhistory.user_id = users.ID
                WHERE DATE(modelhistory.date) ";

        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'collections' ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(modelhistory.date,'%d-%m-%Y'), DATE_FORMAT(modelhistory.date,'%Y-%m-%d') ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } else {
                    $sql .= " GROUP BY YEAR(modelhistory.date), Kataloger ORDER BY DATE_FORMAT(modelhistory.date,'%Y-%m-%d') DESC";
                } 

        if(sizeof($DetailFilter['kataloger']) != '' || ($_POST['Action']) != ''){
            $a = yii::t('app','Berdasarkan');
        }else{ $a = '';}

        if(sizeof($DetailFilter['kataloger']) != '' && ($_POST['Action']) != ''){
            $dan = yii::t('app','dan');
        }else{ $dan = '';}

        // print_r($dan);
        // die;

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['DetailFilter'] = $DetailFilter; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['a'] = $a; 
        $content['dan'] = $dan; 
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            // $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >'];
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
        $content = $this->renderPartial('pdf-view-koleksi-frekuensi-kinerja', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

}

    /**
     * [actionRenderPdfDataUsulanKoleksi description]
     * @return [type] [description]
     */
    public function actionRenderPdfDataKinerjaUser() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

        if (isset($_POST['periode'])) 
        {
            
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

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
        
        if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
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
                   END AS actions 
                   FROM modelhistory 
                   LEFT JOIN users ON modelhistory.user_id = users.ID 
                   LEFT JOIN catalogs ON catalogs.ID = modelhistory.field_id 
                   WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'collections'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC";

        // print_r($dan);
        // die;

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['DetailFilter'] = $DetailFilter;
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
            'content' => $this->renderPartial('pdf-view-koleksi-data-kinerja', $content),
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
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
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
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

            if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
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
                   END AS actions 
                   FROM modelhistory 
                   LEFT JOIN users ON modelhistory.user_id = users.ID 
                   LEFT JOIN catalogs ON catalogs.ID = modelhistory.field_id 
                   WHERE DATE(modelhistory.date) ";
        
        $sql .= $sqlPeriode;
        $sql .= " AND modelhistory.table = 'collections'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $periode2 = $periode2;
    $format_hari = $periode;

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;
// $test = self::getRealNameKriteria($kriterias);

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
                <th style="vertical-align: center;">'.yii::t('app','Jenis Aktifitas').'Jenis Aktifitas</th>
                <th style="vertical-align: center;">'.yii::t('app','ID Data').'</th>
                <th style="vertical-align: center;">'.yii::t('app','Deskripsi').'Deskripsi</th>
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
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
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

            if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
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
        $sql .= " AND modelhistory.table = 'collections'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC";

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

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
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'kinerja_user'=> yii::t('app','Kinerja User'),
        'tanggal'=> yii::t('app','Tanggal'),
        'jenis_aktifitas'=> yii::t('app','Jenis Aktifitas'),
        'id_data'=> yii::t('app','ID Data'),
        'deskripsi'=> yii::t('app','Deskripsi'),
        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/koleksi/laporan-katalog-kinerja-user-data.ods'; 

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-kinerja-user-data.ods');
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
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
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

            if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
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
        $sql .= " AND modelhistory.table = 'collections'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC";

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
    // header("Content-type: application/vnd-ms-excel");
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

        if (isset($_POST['periode'])) 
        {
            
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                // $dateObj = DateTime::createFromFormat('!m', $_POST['fromBulan']);
                $periode_format = 'DATE_FORMAT(modelhistory.date,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

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
        
        if ($_POST['Action'] != '') {
                $andValue .= ' AND modelhistory.type = "'.$_POST['Action'].'" ';
            } 

            switch ($_POST['Action']) {
            case '0':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dibuat) ');
                break;
            
            case '1':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dimutakhirkan) ');
                break;

            case '2':
                $DetailFilter['action'] = yii::t('app',' (Koleksi dihapus) ');
                break;
            
            default:
                $DetailFilter['action'] = null;
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
        $sql .= " AND modelhistory.table = 'collections'";
        $sql .= $andValue ." ORDER BY modelhistory.date DESC";

        // print_r($dan);
        // die;

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['DetailFilter'] = $DetailFilter;
        $content_kop['kop'] =  isset($_POST['kop']); 

        if ($content_kop['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
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
            'title' => 'Laporan Data Kinerja User',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=> $header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-view-koleksi-data-kinerja', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

    }


    /**
     * [getRealNameKriteria description]
     * @param  [type] $kriterias [description]
     * @return [type]            [description]
     */
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
        elseif ($kriterias == 'collectioncategorys') 
        {
            $name = 'Kategori';
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
        elseif ($kriterias == 'petugas') 
        {
            $name = 'Kataloger';
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
            $name = 'Jenis Media';
        }
        elseif ($kriterias == 'Subject') 
        {
            $name = 'Subjek';
        }
        elseif ($kriterias == 'no_klas') 
        {
            $name = 'No. Klas';
        }
        elseif ($kriterias == 'no_panggil') 
        {
            $name = 'No. Panggil';
        }
        elseif ($kriterias == 'createdate') 
        {
            $name = 'Tanggal Entri';
        }
        elseif ($kriterias == 'createby') 
        {
            $name = 'Kataloger';
        }
        elseif ($kriterias == 'data_entry') 
        {
            $name = 'Tanggal Entri Data';
        }
        elseif ($kriterias == 'AnggotaPengusul') 
        {
            $name = 'Anggota Pengusul';
        }
        else 
        {
            $name = ' ';
        }
        
        return $name;

    }
}
