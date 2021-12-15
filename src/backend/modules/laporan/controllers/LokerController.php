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
use common\models\TujuanKunjungan;
use common\models\LocationLibrary;
use common\models\Locations;
use common\models\Members;
use common\models\MemberPerpanjangan;
use common\models\Users;
use common\models\JenisKelamin;
use common\models\Departments;
use common\models\Propinsi;
use common\models\Collectioncategorys;
use common\models\Collectionsources;
use common\models\Collectionmedias;
use common\models\MasterJenisIdentitas;
use common\models\MasterRangeUmur;
use common\models\Masterkelasbesar;
use common\models\Kabupaten;
use common\models\Partners;
use common\models\JenisAnggota;
use common\models\Jenisdenda;
use common\models\Jenispelanggaran;
use common\models\Catalogs;
use common\models\MasterLoker;

class LokerController extends \yii\web\Controller
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
    public function actionLaporanSangsiPelanggaranLoker()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('laporan-sangsi-pelanggaran-loker',[
            'model' => $model,
            ]);
    }
    

    public function actionLoadFilterKriteria($kriteria)
{
        if ($kriteria == 'PublishLocation')
        {
            $sql = 'SELECT * FROM catalogs';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','PublishLocation');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }

        else if ($kriteria == 'no_anggota')
        {
            $sql = "SELECT test
                    FROM(
                    SELECT test 
                    FROM (
                    SELECT CONCAT(members.MemberNo, ' - ', members.Fullname) AS test
                    FROM 
                    members
                    LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                    ) member
                    UNION ALL
                    SELECT test 
                    FROM (
                    SELECT CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama) AS test
                    FROM 
                    memberguesses
                    LEFT JOIN members ON memberguesses.NoAnggota = members.MemberNo
                    ) memberguesses) test GROUP BY test";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'test','test');
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
            $sql = 'SELECT * FROM catalogs';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','Publisher');
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

        else if ($kriteria == 'tujuan')
        {
            $sql = 'SELECT * FROM users';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID',
                function($model) {
                    return $model['username'].' - '.$model['Fullname'];
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

        else if ($kriteria == 'tujuan2')
        {
            $sql = 'SELECT * FROM users';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID',
                function($model) {
                    return $model['username'].' - '.$model['Fullname'];
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

        else if ($kriteria == 'no_loker')
        {
            $sql = 'SELECT * FROM master_loker';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID',
                function($model) {
                    return $model['No'].' - '.$model['Name'];
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
        else
        {
            $contentOptions = null;
        }
        return $contentOptions;
        
    }

     
    public function actionLoadSelecterLoker($i)
    {
        return $this->renderPartial('select-loker',['i'=>$i]);
    }
    public function actionLoadSelecterSangsiPelanggaranLoker($i)
    {
        return $this->renderPartial('select-sangsi-pelanggaran-loker',['i'=>$i]);
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
            elseif ($tampilkan == 'export-excel')
            {            
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export').'">';
                echo "<iframe>";
            }
            elseif ($tampilkan == 'export-word')
            {            
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-word').'">';
                echo "<iframe>";
            }
            elseif ($tampilkan == 'export-pdf')
            {            
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-pdf').'">';
                echo "<iframe>";
            }
        elseif ($tampilkan == 'laporan-periodik-data')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-periodik-data').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );  
        }
            elseif ($tampilkan == 'export-excel-data')
            {            
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-data').'">';
                echo "<iframe>";
            }
            elseif ($tampilkan == 'export-word-data')
            {            
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-word-data').'">';
                echo "<iframe>";
            }
            elseif ($tampilkan == 'export-pdf-data')
            {            
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-pdf-data').'">';
                echo "<iframe>";
            }

        elseif ($tampilkan == 'laporan-sangsi-pelanggaran-loker-frekuensi')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-sangsi-pelanggaran-loker-frekuensi').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );  
        }
            elseif ($tampilkan == 'export-excel')
            {            
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-sangsi-pelanggaran-loker-frekuensi').'">';
                echo "<iframe>";
            }
            elseif ($tampilkan == 'export-word')
            {            
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-word-sangsi-pelanggaran-loker-frekuensi').'">';
                echo "<iframe>";
            }
            elseif ($tampilkan == 'export-pdf')
            {            
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-pdf-sangsi-pelanggaran-loker-frekuensi').'">';
                echo "<iframe>";
            }
        elseif ($tampilkan == 'laporan-sangsi-pelanggaran-loker-data')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-laporan-sangsi-pelanggaran-loker-data').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );  
        }
            elseif ($tampilkan == 'export-excel-data-sangsi-pelanggaran')
            { 
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-sangsi-pelanggaran-loker-data').'">';
                echo "<iframe>";
            }
            elseif ($tampilkan == 'export-word-data-sangsi-pelanggaran')
            { 
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-word-sangsi-pelanggaran-loker-data').'">';
                echo "<iframe>";
            }
            elseif ($tampilkan == 'export-pdf-data-sangsi-pelanggaran')
            {
                echo '<iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('export-pdf-sangsi-pelanggaran-loker-data').'">';
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
                $periode_format = "'%d-%M-%Y'";
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = "'%M-%Y'";
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = "'%Y'";
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

            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }

            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.LocationLibrary_ID = "'.$value.'" ';
                    }
                }
            }

                
           $sql = "SELECT master_loker.No AS NoLoker,
                    DATE_FORMAT(lockers.tanggal_pinjam,".$periode_format.") AS TglPinjam,
                    DATE_FORMAT(lockers.tanggal_kembali,".$periode_format.") AS TglDikembalikan,
                    location_library.Name AS LokasiPerpustakaan,
                    lockers.no_member AS NoAnggota,
                    members.FullName AS NamaAnggota,
                    lockers.jenis_jaminan AS Jaminan,
                    (SELECT users.FullName FROM users WHERE lockers.CreateBy = users.ID) AS PetugasPeminjaman,
                    (SELECT users.FullName FROM users WHERE lockers.UpdateBy = users.ID) AS PetugasPengembalian 
                    FROM lockers 
                    LEFT JOIN members ON lockers.no_member = members.MemberNo 
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if(sizeof($inValue)==1){
        $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
        $sql .= ' ORDER BY lockers.tanggal_pinjam';

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
                $periode_format = "'%d-%M-%Y'";
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = "'%M-%Y'";
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = "'%Y'";
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

            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }

            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.LocationLibrary_ID = "'.$value.'" ';
                    }
                }
            }

                
           $sql = "SELECT master_loker.No AS NoLoker,
                    DATE_FORMAT(lockers.tanggal_pinjam,".$periode_format.") AS TglPinjam,
                    DATE_FORMAT(lockers.tanggal_kembali,".$periode_format.") AS TglDikembalikan,
                    location_library.Name AS LokasiPerpustakaan,
                    lockers.no_member AS NoAnggota,
                    members.FullName AS NamaAnggota,
                    lockers.jenis_jaminan AS Jaminan,
                    (SELECT users.FullName FROM users WHERE lockers.CreateBy = users.ID) AS PetugasPeminjaman,
                    (SELECT users.FullName FROM users WHERE lockers.UpdateBy = users.ID) AS PetugasPengembalian 
                    FROM lockers 
                    LEFT JOIN members ON lockers.no_member = members.MemberNo 
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if(sizeof($inValue)==1){
        $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
        $sql .= ' ORDER BY lockers.tanggal_pinjam';

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
                <th colspan="10">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="10">'.yii::t('app','Peminjaman Loker').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Tanggal Pinjam').'</th>
                <th>'.yii::t('app','Tanggal Dikembalikan').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Nomor Loker').'</th>
                <th>'.yii::t('app','Nomor Anggota / Kunjungan').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Jaminan').'</th>
                <th>'.yii::t('app','Petugas Peminjaman').'</th>
                <th>'.yii::t('app','Petugas Pengembalian').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['TglPinjam'].'</td>
                    <td>'.$data['TglDikembalikan'].'</td>
                    <td>'.$data['LokasiPerpustakaan'].'</td>
                    <td>'.$data['NoLoker'].'</td>
                    <td>'.$data['NoAnggota'].'</td>
                    <td>'.$data['NamaAnggota'].'</td>
                    <td>'.$data['Jaminan'].'</td>
                    <td>'.$data['PetugasPeminjaman'].'</td>
                    <td>'.$data['PetugasPengembalian'].'</td>
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
                $periode_format = "'%d-%M-%Y'";
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = "'%M-%Y'";
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = "'%Y'";
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

            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }
        
            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.LocationLibrary_ID = "'.$value.'" ';
                    }
                }
            }

                
           $sql = "SELECT master_loker.No AS NoLoker,
                    DATE_FORMAT(lockers.tanggal_pinjam,".$periode_format.") AS TglPinjam,
                    DATE_FORMAT(lockers.tanggal_kembali,".$periode_format.") AS TglDikembalikan,
                    location_library.Name AS LokasiPerpustakaan,
                    lockers.no_member AS NoAnggota,
                    members.FullName AS NamaAnggota,
                    lockers.jenis_jaminan AS Jaminan,
                    (SELECT users.FullName FROM users WHERE lockers.CreateBy = users.ID) AS PetugasPeminjaman,
                    (SELECT users.FullName FROM users WHERE lockers.UpdateBy = users.ID) AS PetugasPengembalian 
                    FROM lockers 
                    LEFT JOIN members ON lockers.no_member = members.MemberNo 
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if(sizeof($inValue)==1){
        $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
        $sql .= ' ORDER BY lockers.tanggal_pinjam';

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
        $data[] = array('no'=> $no++,'TglPinjam'=> $model['TglPinjam'], 'TglDikembalikan'=>$model['TglDikembalikan'], 'LokasiPerpustakaan'=>$model['LokasiPerpustakaan'], 'NoAnggota'=>$model['NoAnggota'], 'NamaAnggota'=>$model['NamaAnggota']
                         , 'Jaminan'=>$model['Jaminan'], 'PetugasPeminjaman'=>$model['PetugasPeminjaman'], 'PetugasPengembalian'=>$model['PetugasPengembalian'], 'NoLoker'=>$model['NoLoker'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'peminjaman_loker'=> yii::t('app','Peminjaman Loker'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_pinjam'=> yii::t('app','Tanggal Pinjam'),
        'tanggal_dikembalikan'=> yii::t('app','Tanggal Dikembalikan'),
        'lokasi_perpustakaan'=> yii::t('app','Lokasi Perpustakaan'),
        'nomor_loker'=> yii::t('app','Nomor Loker'),
        'nomor_anggotakunjungan'=> yii::t('app','Nomor Anggota / Kunjungan'),
        'nama'=> yii::t('app','Nama'),
        'jaminan'=> yii::t('app','Jaminan'),
        'petugas_peminjaman'=> yii::t('app','Petugas Peminjaman'),
        'petugas_pengembalian'=> yii::t('app','Petugas Pengembalian'),
        );
// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/loker/laporan-loker-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-loker-data.ods');
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
                $periode_format = "'%d-%M-%Y'";
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = "'%M-%Y'";
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = "'%Y'";
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
            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }

            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.LocationLibrary_ID = "'.$value.'" ';
                    }
                }
            }

                
           $sql = "SELECT master_loker.No AS NoLoker,
                    DATE_FORMAT(lockers.tanggal_pinjam,".$periode_format.") AS TglPinjam,
                    DATE_FORMAT(lockers.tanggal_kembali,".$periode_format.") AS TglDikembalikan,
                    location_library.Name AS LokasiPerpustakaan,
                    lockers.no_member AS NoAnggota,
                    members.FullName AS NamaAnggota,
                    lockers.jenis_jaminan AS Jaminan,
                    (SELECT users.FullName FROM users WHERE lockers.CreateBy = users.ID) AS PetugasPeminjaman,
                    (SELECT users.FullName FROM users WHERE lockers.UpdateBy = users.ID) AS PetugasPengembalian 
                    FROM lockers 
                    LEFT JOIN members ON lockers.no_member = members.MemberNo 
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if(sizeof($inValue)==1){
        $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
        $sql .= ' ORDER BY lockers.tanggal_pinjam';

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Data.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="10">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="10">'.yii::t('app','Peminjaman Loker').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Tanggal Pinjam').'</th>
                <th>'.yii::t('app','Tanggal Dikembalikan').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Nomor Loker').'</th>
                <th>'.yii::t('app','Nomor Anggota / Kunjungan').'</th>
                <th>'.yii::t('app','Nama').' </th>
                <th>'.yii::t('app','Jaminan').'</th>
                <th>'.yii::t('app','Petugas Peminjaman').'</th>
                <th>'.yii::t('app','Petugas Pengembalian').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['TglPinjam'].'</td>
                    <td>'.$data['TglDikembalikan'].'</td>
                    <td>'.$data['LokasiPerpustakaan'].'</td>
                    <td>'.$data['NoLoker'].'</td>
                    <td>'.$data['NoAnggota'].'</td>
                    <td>'.$data['NamaAnggota'].'</td>
                    <td>'.$data['Jaminan'].'</td>
                    <td>'.$data['PetugasPeminjaman'].'</td>
                    <td>'.$data['PetugasPengembalian'].'</td>
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
                $periode_format = "'%d-%M-%Y'";
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = "'%M-%Y'";
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = "'%Y'";
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

            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }
        
            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.LocationLibrary_ID = "'.$value.'" ';
                    }
                }
            }

                
           $sql = "SELECT master_loker.No AS NoLoker,
                    DATE_FORMAT(lockers.tanggal_pinjam,".$periode_format.") AS TglPinjam,
                    DATE_FORMAT(lockers.tanggal_kembali,".$periode_format.") AS TglDikembalikan,
                    location_library.Name AS LokasiPerpustakaan,
                    lockers.no_member AS NoAnggota,
                    members.FullName AS NamaAnggota,
                    lockers.jenis_jaminan AS Jaminan,
                    (SELECT users.FullName FROM users WHERE lockers.CreateBy = users.ID) AS PetugasPeminjaman,
                    (SELECT users.FullName FROM users WHERE lockers.UpdateBy = users.ID) AS PetugasPengembalian 
                    FROM lockers 
                    LEFT JOIN members ON lockers.no_member = members.MemberNo 
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if(sizeof($inValue)==1){
        $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
        $sql .= ' ORDER BY lockers.tanggal_pinjam';

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

public function actionRenderLaporanSangsiPelanggaranLokerData() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').$_POST['from_date'].' s/d '.$_POST['to_date'];
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
                $andValue .= " AND CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(memberguesses.NoAnggota, ' - ', memberguesses.Nama)
                 ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama)
                END LIKE '%".$value."%' ";
                }
            }
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        }

        $sql = "SELECT CONCAT(DATE_FORMAT(lockers.tanggal_pinjam,'%d-%M-%Y'), '<br/>' ,DATE_FORMAT(lockers.tanggal_pinjam,'%H:%i:%s')) AS TglPinjam,
                CONCAT(DATE_FORMAT(lockers.tanggal_kembali,'%d-%M-%Y'), '<br/>' ,DATE_FORMAT(lockers.tanggal_kembali,'%H:%i:%s')) AS TglDikembalikan,
                location_library.Name AS LokasiPerpustakaan,
                master_loker.No AS NoLoker,
                master_pelanggaran_locker.Denda AS JumlahDenda,
                members.MemberNo AS NoAnggota,
                members.FullName AS NamaAnggota,
                (SELECT FullName FROM users WHERE users.ID = lockers.CreateBy) AS PetugasPeminjaman,
                (SELECT FullName FROM users WHERE users.ID = lockers.UpdateBy) AS PetugasPengembalian,
                lockers.jenis_jaminan AS jaminan  
                FROM lockers 
                LEFT JOIN master_pelanggaran_locker ON lockers.id_pelanggaran_locker = master_pelanggaran_locker.ID 
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                LEFT JOIN location_library ON locations.LocationLibrary_ID = location_library.ID 
                LEFT JOIN members ON lockers.no_member = members.MemberNo
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                INNER JOIN users ON lockers.CreateBy = users.ID 
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' GROUP BY lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ';

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

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
            'content' => $this->renderPartial('pdf-view-laporan-sangsi-pelanggaran-loker-data', $content),
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=> $header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px; ">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }

public function actionExportSangsiPelanggaranLokerData()
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
                $periode2= yii::t('app','Periode').$_POST['from_date'].' s/d '.$_POST['to_date'];
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
                $andValue .= " AND CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(memberguesses.NoAnggota, ' - ', memberguesses.Nama)
                 ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama)
                END LIKE '%".$value."%' ";
                }
            }
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        }

        $sql = "SELECT lockers.tanggal_pinjam AS TglPinjam,
                lockers.tanggal_kembali AS TglDikembalikan, 
                location_library.Name AS LokasiPerpustakaan,
                master_loker.No AS NoLoker,
                master_pelanggaran_locker.Denda AS JumlahDenda,
                members.MemberNo AS NoAnggota,
                members.FullName AS NamaAnggota,
                (SELECT FullName FROM users WHERE users.ID = lockers.CreateBy) AS PetugasPeminjaman,
                (SELECT FullName FROM users WHERE users.ID = lockers.UpdateBy) AS PetugasPengembalian,
                lockers.jenis_jaminan AS jaminan 
                FROM lockers 
                LEFT JOIN master_pelanggaran_locker ON lockers.id_pelanggaran_locker = master_pelanggaran_locker.ID 
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                LEFT JOIN location_library ON locations.LocationLibrary_ID = location_library.ID 
                LEFT JOIN members ON lockers.no_member = members.MemberNo
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                INNER JOIN users ON lockers.CreateBy = users.ID 
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' GROUP BY lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ';

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
                <th colspan="10">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="10">'.yii::t('app','Sangsi Pelanggaran Peminjaman Loker').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Tanggal Pinjam').'</th>
                <th>'.yii::t('app','Tanggal Dikembalikan').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Nomor Loker').'</th>
                <th>'.yii::t('app','Nomor Anggota / Pengunjung').'</th>
                <th>'.yii::t('app','Nama Anggota / Pengunjung').'</th>
                <th>'.yii::t('app','Jaminan').'</th>
                <th>'.yii::t('app','Petugas Peminjaman').'</th>
                <th>'.yii::t('app','Petugas Pengembalian').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['TglPinjam'].'</td>
                    <td>'.$data['TglDikembalikan'].'</td>
                    <td>'.$data['LokasiPerpustakaan'].'</td>
                    <td>'.$data['NoLoker'].'</td>
                    <td>'.$data['NoAnggota'].'</td>
                    <td>'.$data['NamaAnggota'].'</td>
                    <td>'.$data['jaminan'].'</td>
                    <td>'.$data['PetugasPeminjaman'].'</td>
                    <td>'.$data['PetugasPengembalian'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtDataSangsiPelanggaran()
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
                $periode2= yii::t('app','Periode').$_POST['from_date'].' s/d '.$_POST['to_date'];
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
                $andValue .= " AND CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(memberguesses.NoAnggota, ' - ', memberguesses.Nama)
                 ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama)
                END LIKE '%".$value."%' ";
                }
            }
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        }

        $sql = "SELECT DATE_FORMAT(lockers.tanggal_pinjam,'%d-%M-%Y') AS TglPinjam,
                DATE_FORMAT(lockers.tanggal_pinjam,'%H:%i:%s') AS TglPinjam2,
                DATE_FORMAT(lockers.tanggal_kembali,'%d-%M-%Y') AS TglDikembalikan,
                DATE_FORMAT(lockers.tanggal_kembali,'%H:%i:%s')  AS TglDikembalikan2,
                location_library.Name AS LokasiPerpustakaan,
                master_loker.No AS NoLoker,
                master_pelanggaran_locker.Denda AS JumlahDenda,
                members.MemberNo AS NoAnggota,
                members.FullName AS NamaAnggota,
                (SELECT FullName FROM users WHERE users.ID = lockers.CreateBy) AS PetugasPeminjaman,
                (SELECT FullName FROM users WHERE users.ID = lockers.UpdateBy) AS PetugasPengembalian,
                lockers.jenis_jaminan AS jaminan 
                FROM lockers 
                LEFT JOIN master_pelanggaran_locker ON lockers.id_pelanggaran_locker = master_pelanggaran_locker.ID 
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                LEFT JOIN location_library ON locations.LocationLibrary_ID = location_library.ID 
                LEFT JOIN members ON lockers.no_member = members.MemberNo
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                INNER JOIN users ON lockers.CreateBy = users.ID 
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' GROUP BY lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ';

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
        $data[] = array('no'=> $no++,'TglPinjam'=> $model['TglPinjam'],'TglPinjam2'=> $model['TglPinjam2'], 'TglDikembalikan'=>$model['TglDikembalikan'],'TglDikembalikan2'=>$model['TglDikembalikan2'], 'LokasiPerpustakaan'=>$model['LokasiPerpustakaan'], 'NoLoker'=>$model['NoLoker'], 'JumlahDenda'=>$model['JumlahDenda']
                        , 'NoAnggota'=>$model['NoAnggota'], 'NamaAnggota'=>$model['NamaAnggota'], 'PetugasPeminjaman'=>$model['PetugasPeminjaman'], 'PetugasPengembalian'=>$model['PetugasPengembalian'], 'jaminan'=>$model['jaminan'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'sangsi_pelanggaranpeminjamanloker'=> yii::t('app','Sangsi Pelanggaran Peminjaman Loker'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_pinjam'=> yii::t('app','Tanggal Pinjam'),
        'tanggal_dikembalikan'=> yii::t('app','Tanggal Dikembalikan'),
        'lokasi_perpustakaan'=> yii::t('app','Lokasi Perpustakaan'),
        'nomor_loker'=> yii::t('app','Nomor Loker'),
        'nomor_anggotapengunjung'=> yii::t('app','Nomor Anggota / Pengunjung'),
        'nama_anggotapengunjung'=> yii::t('app','Nama Anggota / Pengunjung'),
        'jaminan'=> yii::t('app','Jaminan'),
        'petugas_peminjaman'=> yii::t('app','Petugas Peminjaman'),
        'petugas_pengembalian'=> yii::t('app','Petugas Pengembalian'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/loker/laporan-loker-sangsi-pelanggaran-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-sangsi-pelanggaran-loker-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordSangsiPelanggaranLokerData()
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
                $periode2= yii::t('app','Periode').$_POST['from_date'].' s/d '.$_POST['to_date'];
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
                $andValue .= " AND CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(memberguesses.NoAnggota, ' - ', memberguesses.Nama)
                 ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama)
                END LIKE '%".$value."%' ";
                }
            }
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        }

        $sql = "SELECT lockers.tanggal_pinjam AS TglPinjam,
                lockers.tanggal_kembali AS TglDikembalikan, 
                location_library.Name AS LokasiPerpustakaan,
                master_loker.No AS NoLoker,
                master_pelanggaran_locker.Denda AS JumlahDenda,
                members.MemberNo AS NoAnggota,
                members.FullName AS NamaAnggota,
                (SELECT FullName FROM users WHERE users.ID = lockers.CreateBy) AS PetugasPeminjaman,
                (SELECT FullName FROM users WHERE users.ID = lockers.UpdateBy) AS PetugasPengembalian,
                lockers.jenis_jaminan AS jaminan 
                FROM lockers 
                LEFT JOIN master_pelanggaran_locker ON lockers.id_pelanggaran_locker = master_pelanggaran_locker.ID 
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                LEFT JOIN location_library ON locations.LocationLibrary_ID = location_library.ID 
                LEFT JOIN members ON lockers.no_member = members.MemberNo
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                INNER JOIN users ON lockers.CreateBy = users.ID 
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' GROUP BY lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ';

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Data.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    header("orientation: Landscape");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="10">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="10">'.yii::t('app','Sangsi Pelanggaran Peminjaman Loker').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Tanggal Pinjam').'</th>
                <th>'.yii::t('app','Tanggal Dikembalikan').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Nomor Loker').'</th>
                <th>'.yii::t('app','Nomor Anggota / Pengunjung').'</th>
                <th>'.yii::t('app','Nama Anggota / Pengunjung').'</th>
                <th>'.yii::t('app','Jaminan').'</th>
                <th>'.yii::t('app','Petugas Peminjaman').'</th>
                <th>'.yii::t('app','Petugas Pengembalian').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['TglPinjam'].'</td>
                    <td>'.$data['TglDikembalikan'].'</td>
                    <td>'.$data['LokasiPerpustakaan'].'</td>
                    <td>'.$data['NoLoker'].'</td>
                    <td>'.$data['NoAnggota'].'</td>
                    <td>'.$data['NamaAnggota'].'</td>
                    <td>'.$data['jaminan'].'</td>
                    <td>'.$data['PetugasPeminjaman'].'</td>
                    <td>'.$data['PetugasPengembalian'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfSangsiPelanggaranLokerData()
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').$_POST['from_date'].' s/d '.$_POST['to_date'];
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
                $andValue .= " AND CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(memberguesses.NoAnggota, ' - ', memberguesses.Nama)
                 ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama)
                END LIKE '%".$value."%' ";
                }
            }
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        }

        $sql = "SELECT lockers.tanggal_pinjam AS TglPinjam,
                lockers.tanggal_kembali AS TglDikembalikan, 
                location_library.Name AS LokasiPerpustakaan,
                master_loker.No AS NoLoker,
                master_pelanggaran_locker.Denda AS JumlahDenda,
                members.MemberNo AS NoAnggota,
                members.FullName AS NamaAnggota,
                (SELECT FullName FROM users WHERE users.ID = lockers.CreateBy) AS PetugasPeminjaman,
                (SELECT FullName FROM users WHERE users.ID = lockers.UpdateBy) AS PetugasPengembalian,
                lockers.jenis_jaminan AS jaminan
                FROM lockers 
                LEFT JOIN master_pelanggaran_locker ON lockers.id_pelanggaran_locker = master_pelanggaran_locker.ID 
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                LEFT JOIN location_library ON locations.LocationLibrary_ID = location_library.ID 
                LEFT JOIN members ON lockers.no_member = members.MemberNo
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                INNER JOIN users ON lockers.CreateBy = users.ID 
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        $sql .= ' GROUP BY lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ';

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan;
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
        $content = $this->renderPartial('pdf-view-laporan-sangsi-pelanggaran-loker-data', $content);
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") AS PeriodePinjam';
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

            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }        

            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.LocationLibrary_ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.ID = '".addslashes($value)."' ";
                    }
                }
            }                   
            
            $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan, 
                    locations.Name AS nama_ruang,
                    COUNT(lockers.loker_id) AS JumlahLoker, 
                    COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam 
                    FROM lockers  
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if(sizeof($inValue)==1){
        $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
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

public function actionExport()
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") AS PeriodePinjam';
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
            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }

            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.LocationLibrary_ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.ID = '".addslashes($value)."' ";
                    }
                }
            }           

            
            $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan, 
                    locations.Name AS nama_ruang,
                    COUNT(lockers.loker_id) AS JumlahLoker, 
                    COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam 
                    FROM lockers  
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";  

    $sql .= $sqlPeriode ; 
    $sql .= $andValue ; 
    if(sizeof($inValue)==1){
    $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
    if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
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
                <th colspan="6">'.yii::t('app','Peminjaman Loker').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Tanggal Pinjam').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Jumlah Loker').'</th>
                <th>'.yii::t('app','Jumlah Peminjaman').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahLoker = 0;
        $JumlahPeminjam = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['PeriodePinjam'].'</td>
                    <td>'.$data['LokasiPerpustakaan'].'</td>
                    <td>'.$data['nama_ruang'].'</td>
                    <td>'.$data['JumlahLoker'].'</td>
                    <td>'.$data['JumlahPeminjam'].'</td>
                </tr>
            ';
                        $JumlahLoker = $JumlahLoker + $data['JumlahLoker'];
                        $JumlahPeminjam = $JumlahPeminjam + $data['JumlahPeminjam'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahLoker.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahPeminjam.'
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") AS PeriodePinjam';
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
            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }

            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.LocationLibrary_ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.ID = '".addslashes($value)."' ";
                    }
                }
            }           

            
            $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan,
                    locations.Name AS nama_ruang,
                    COUNT(lockers.loker_id) AS JumlahLoker, 
                    COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam 
                    FROM lockers  
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if(sizeof($inValue)==1){
        $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
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
        $data[] = array('no'=> $no++,'PeriodePinjam'=> $model['PeriodePinjam'], 'LokasiPerpustakaan'=>$model['LokasiPerpustakaan'], 'nama_ruang'=>$model['nama_ruang'], 'JumlahLoker'=>$model['JumlahLoker'], 'JumlahPeminjam'=>$model['JumlahPeminjam'] );
            $JumlahJumlahLoker = $JumlahJumlahLoker + $model['JumlahLoker'];
            $JumlahJumlahPeminjam = $JumlahJumlahPeminjam + $model['JumlahPeminjam'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlahLoker'=>$JumlahJumlahLoker,
        'TotalJumlahPeminjam'=>$JumlahJumlahPeminjam,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'peminjaman_loker'=> yii::t('app','Peminjaman Loker'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_pinjam'=> yii::t('app','Tanggal Pinjam'),
        'lokasi_perpustakaan'=> yii::t('app','Lokasi Perpustakaan'),
        'lokasi_ruang'=> yii::t('app','Lokasi Ruang'),
        'jumlah_loker'=> yii::t('app','Jumlah Loker'),
        'jumlah_peminjaman'=> yii::t('app','Jumlah Peminjaman'),       
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/loker/laporan-loker-frekuensi.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-periodik-frekuensi.ods');
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") AS PeriodePinjam';
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
            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }

            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.LocationLibrary_ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.ID = '".addslashes($value)."' ";
                    }
                }
            }           

            
            $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan, 
                    locations.Name AS nama_ruang,
                    COUNT(lockers.loker_id) AS JumlahLoker, 
                    COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam 
                    FROM lockers  
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";  

    $sql .= $sqlPeriode ; 
    $sql .= $andValue ; 
    if(sizeof($inValue)==1){
    $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
    if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
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


    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center" width="700"> 
            <tr>
                <th colspan="6">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="6">'.yii::t('app','Peminjaman Loker').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Tanggal Pinjam').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Jumlah Loker').'</th>
                <th>'.yii::t('app','Jumlah Peminjaman').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahLoker = 0;
        $JumlahPeminjam = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['PeriodePinjam'].'</td>
                    <td>'.$data['LokasiPerpustakaan'].'</td>
                    <td>'.$data['nama_ruang'].'</td>
                    <td>'.$data['JumlahLoker'].'</td>
                    <td>'.$data['JumlahPeminjam'].'</td>
                </tr>
            ';
                        $JumlahLoker = $JumlahLoker + $data['JumlahLoker'];
                        $JumlahPeminjam = $JumlahPeminjam + $data['JumlahPeminjam'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahLoker.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahPeminjam.'
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") AS PeriodePinjam';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") AS PeriodePinjam';
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
            $inValue = array();
            if (isset($_POST['belum_dikembalikan']) == true) {
                    
                        $inValue[] =  'IS NULL';
                    
                }    
            if (isset($_POST['sudah_dikembalikan']) == true) {
                    
                        $inValue[]=  'IS NOT NULL';
                    
                }

            if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND lockers.CreateBy = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['location_library'])) {
            foreach ($_POST['location_library'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.LocationLibrary_ID = '".addslashes($value)."' ";
                    }
                }
            }

            if (isset($_POST['locations'])) {
            foreach ($_POST['locations'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= " AND locations.ID = '".addslashes($value)."' ";
                    }
                }
            }           

            
            $sql = "SELECT ".$periode_format.",
                    location_library.Name AS LokasiPerpustakaan, 
                    locations.Name AS nama_ruang, 
                    COUNT(lockers.loker_id) AS JumlahLoker, 
                    COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam 
                    FROM lockers  
                    LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                    LEFT JOIN locations ON master_loker.locations_id = locations.ID 
                    LEFT JOIN location_library ON locations.LocationLibrary_id = location_library.ID 
                    WHERE DATE(lockers.tanggal_pinjam) ";        
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if(sizeof($inValue)==1){
        $sql .= 'AND lockers.tanggal_kembali '.implode($inValue);}
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam), location_library.ID ORDER BY lockers.tanggal_pinjam ASC";
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

public function actionRenderLaporanSangsiPelanggaranLokerFrekuensi() 
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") Periode';
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
        $andValue .= " AND 
                CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname)
                 ELSE CONCAT(members.MemberNo, ' - ', members.Fullname)
                END LIKE '%".$value."%' ";
                }
            }
        $subjek = "CASE 
                     WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname) 
                     ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama) 
                    END AS Subjek ";
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        $subjek = 'location_library.Name AS Subjek';
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        $subjek = 'master_loker.Name AS Subjek';
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }  

        $sql = "SELECT ".$periode_format.",
                COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam,
                lockers.no_member AS noMemb,
                lockers.denda AS TotalDenda,
                ".$subjek."
                FROM
                lockers
                LEFT JOIN members ON lockers.no_member = members.MemberNo 
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_ID
                LEFT JOIN users ON users.ID = lockers.CreateBy
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                }
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan; 
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
            'content' => $this->renderPartial('pdf-view-laporan-sangsi-pelanggaran-loker-frekuensi', $content),
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=> $header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Halaman {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();
        
    }

public function actionExportSangsiPelanggaranLokerFrekuensi()
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") Periode';
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

        if (isset($_POST['no_anggota'])) {
        foreach ($_POST['no_anggota'] as $key => $value) {
            if ($value != "0" ) {
        $andValue .= " AND 
                CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname)
                 ELSE CONCAT(members.MemberNo, ' - ', members.Fullname)
                END LIKE '%".$value."%' ";
                }
            }
        $subjek = "CASE 
                     WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname) 
                     ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama) 
                    END AS Subjek ";
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        $subjek = 'location_library.Name AS Subjek';
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        $subjek = 'master_loker.Name AS Subjek';
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }  

        $sql = "SELECT ".$periode_format.",
                COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam,
                lockers.no_member AS noMemb,
                lockers.denda AS TotalDenda,
                ".$subjek."
                FROM
                lockers
                LEFT JOIN members ON lockers.no_member = members.MemberNo 
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_ID
                LEFT JOIN users ON users.ID = lockers.CreateBy
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
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
                <th colspan="4">'.yii::t('app','Sangsi Pelanggaran Peminjaman Loker').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Tanggal Peminjaman').'</th>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<th>'.yii::t('app',$Berdasarkan).'</th>'; }
    echo'
                <th>'.yii::t('app','Jumlah Peminjaman').'</th>
                <th>'.yii::t('app','Total Denda').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPeminjam = 0;
        $TotalDenda = 0;
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
                    <td>'.$data['JumlahPeminjam'].'</td>
                    <td>'.$data['TotalDenda'].'</td>
                </tr>
            ';
                        $JumlahPeminjam = $JumlahPeminjam + $data['JumlahPeminjam'];
                        $TotalDenda = $TotalDenda + $data['TotalDenda'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahPeminjam.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$TotalDenda.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtSangsiPelanggaran()
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") Periode';
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

        if (isset($_POST['no_anggota'])) {
        foreach ($_POST['no_anggota'] as $key => $value) {
            if ($value != "0" ) {
        $andValue .= " AND 
                CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname)
                 ELSE CONCAT(members.MemberNo, ' - ', members.Fullname)
                END LIKE '%".$value."%' ";
                }
            }
        $subjek = "CASE 
                     WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname) 
                     ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama) 
                    END AS Subjek ";
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        $subjek = 'location_library.Name AS Subjek';
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        $subjek = 'master_loker.Name AS Subjek';
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }  

        $sql = "SELECT ".$periode_format.",
                COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam,
                lockers.no_member AS noMemb,
                lockers.denda AS TotalDenda,
                ".$subjek."
                FROM
                lockers
                LEFT JOIN members ON lockers.no_member = members.MemberNo 
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_ID
                LEFT JOIN users ON users.ID = lockers.CreateBy
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
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
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Subjek'=>$model['Subjek'], 'JumlahPeminjam'=>$model['JumlahPeminjam'], 'TotalDenda'=>$model['TotalDenda'] );
            $JumlahPeminjam = $JumlahPeminjam + $model['JumlahPeminjam'];
            $TotalDenda = $TotalDenda + $model['TotalDenda'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'TotalJumlahPeminjam'=>$JumlahPeminjam,
        'TotalDenda'=>$TotalDenda,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'sangsi_pelanggaranpeminjamanloker'=> yii::t('app','Sangsi Pelanggaran Peminjaman Loker'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_peminjaman'=> yii::t('app','Tanggal Peminjaman'),
        'jumlah_peminjaman'=> yii::t('app','Jumlah Peminjaman'),
        'total_denda'=> yii::t('app','Total Denda'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
if (sizeof($_POST['kriterias']) == 1) {
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/loker/laporan-loker-sangsi-pelanggaran-frekuensi.ods'; 
}else{
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/loker/laporan-loker-sangsi-pelanggaran-frekuensi_no_subjek.ods'; 
}

    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-sangsi-pelanggaran-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordSangsiPelanggaranLokerFrekuensi()
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") Periode';
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

        if (isset($_POST['no_anggota'])) {
        foreach ($_POST['no_anggota'] as $key => $value) {
            if ($value != "0" ) {
        $andValue .= " AND 
                CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname)
                 ELSE CONCAT(members.MemberNo, ' - ', members.Fullname)
                END LIKE '%".$value."%' ";
                }
            }
        $subjek = "CASE 
                     WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname) 
                     ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama) 
                    END AS Subjek ";
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        $subjek = 'location_library.Name AS Subjek';
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        $subjek = 'master_loker.Name AS Subjek';
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }  

        $sql = "SELECT ".$periode_format.",
                COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam,
                lockers.no_member AS noMemb,
                lockers.denda AS TotalDenda,
                ".$subjek."
                FROM
                lockers
                LEFT JOIN members ON lockers.no_member = members.MemberNo 
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_ID
                LEFT JOIN users ON users.ID = lockers.CreateBy
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
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
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="4"';} else {echo 'colspan="5"';}echo '>'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="4"';} else {echo 'colspan="5"';}echo '>'.yii::t('app','Sangsi Pelanggaran Peminjaman Loker').' '.$periode2.'</th>
            </tr>
            <tr>
                <th ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="4"';} else {echo 'colspan="5"';}echo '>'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Peminjaman').'</th>
    ';
    if (sizeof($_POST["kriterias"]) !=1) {
    }else
    { echo '<th>'.yii::t('app',$Berdasarkan).'</th>'; }
    echo'
                <th>'.yii::t('app','Jumlah Peminjaman').'</th>
                <th>'.yii::t('app','Total Denda').'</th>
            </tr>
            ';
        $no = 1;
        $JumlahPeminjam = 0;
        $TotalDenda = 0;
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
                    <td>'.$data['JumlahPeminjam'].'</td>
                    <td>'.$data['TotalDenda'].'</td>
                </tr>
            ';
                        $JumlahPeminjam = $JumlahPeminjam + $data['JumlahPeminjam'];
                        $TotalDenda = $TotalDenda + $data['TotalDenda'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td ';if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}echo ' style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$JumlahPeminjam.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$TotalDenda.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfSangsiPelanggaranLokerFrekuensi()
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
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(lockers.tanggal_pinjam,"%Y") Periode';
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
        $andValue .= " AND 
                CASE
                 WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname)
                 ELSE CONCAT(members.MemberNo, ' - ', members.Fullname)
                END LIKE '%".$value."%' ";
                }
            }
        $subjek = "CASE 
                     WHEN memberguesses.NoPengunjung IS NULL THEN CONCAT(members.MemberNo, ' - ', members.Fullname) 
                     ELSE CONCAT(memberguesses.NoPengunjung, ' - ', memberguesses.Nama) 
                    END AS Subjek ";
        }

        if (isset($_POST['location_library'])) {
        foreach ($_POST['location_library'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND locations.LocationLibrary_ID = '".$value."' ";
                }
            }
        $subjek = 'location_library.Name AS Subjek';
        }

        if (isset($_POST['no_loker'])) {
        foreach ($_POST['no_loker'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.Loker_id = '".$value."' ";
                }
            }
        $subjek = 'master_loker.Name AS Subjek';
        }

        if (isset($_POST['tujuan'])) {
        foreach ($_POST['tujuan'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.CreateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }

        if (isset($_POST['tujuan2'])) {
        foreach ($_POST['tujuan2'] as $key => $value) {
            if ($value != "0" ) {
                $andValue .= " AND lockers.UpdateBy  =  '".$value."' ";
                }
            }
        $subjek = 'users.username AS Subjek';
        }  

        $sql = "SELECT ".$periode_format.",
                COUNT(DISTINCT lockers.no_member) AS JumlahPeminjam,
                lockers.no_member AS noMemb,
                lockers.denda AS TotalDenda,
                ".$subjek."
                FROM
                lockers
                LEFT JOIN members ON lockers.no_member = members.MemberNo 
                LEFT JOIN memberguesses ON memberguesses.NoAnggota = members.MemberNo
                LEFT JOIN master_loker ON lockers.loker_id = master_loker.ID 
                LEFT JOIN locations ON master_loker.locations_id = locations.ID
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_ID
                LEFT JOIN users ON users.ID = lockers.CreateBy
                WHERE DATE(lockers.tanggal_pinjam) ";
        
        $sql .= $sqlPeriode;
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(lockers.tanggal_pinjam,'%d-%m-%Y'),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                } else {
                    $sql .= " GROUP BY YEAR(lockers.tanggal_pinjam),lockers.No_pinjaman ORDER BY lockers.tanggal_pinjam ASC";
                }
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= '' .yii::t('app',$this->getRealNameKriteria($value)).'';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan; 
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
        $content = $this->renderPartial('pdf-view-laporan-sangsi-pelanggaran-loker-frekuensi', $content);
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
            $name = 'No Anggota';
        } 
        elseif ($kriterias == 'petugas_perpanjangan') 
        {
            $name = 'Petugas Perpanjangan';
        }
        elseif ($kriterias == 'Kelas_dcc') 
        {
            $name = 'Kelas DCC';
        }
        elseif ($kriterias == 'nama_institusi') 
        {
            $name = 'Nama Institusi';
        }
        elseif ($kriterias == 'penginput_data') 
        {
            $name = 'Penginput Data';
        }
        elseif ($kriterias == 'PublishYear') 
        {
            $name = 'Tahun Terbit';
        }
        elseif ($kriterias == 'locations') 
        {
            $name = 'Lokasi Ruang Perpustakaan';
        }
        elseif ($kriterias == 'jenis_identitas') 
        {
            $name = 'Jenis Identitas';
        }
        elseif ($kriterias == 'no_loker') 
        {
            $name = 'Nomor Loker';
        }
        elseif ($kriterias == 'kabupaten') 
        {
            $name = 'Kabupaten/Kota Sesuai Identitas';
        }elseif ($kriterias == 'kabupaten2') 
        {
            $name = 'Kabupaten/Kota Sesuai Tempat Tinggal';
        }
        elseif ($kriterias == 'propinsi') 
        {
            $name = 'Propinsi Sesuai Identitas';
        }elseif ($kriterias == 'propinsi2') 
        {
            $name = 'Propinsi Sesuai Tempat Tinggal';
        }
        elseif ($kriterias == 'unit_kerja') 
        {
            $name = 'Unit Kerja';
        }
        elseif ($kriterias == 'location_library') 
        {
            $name = 'Lokasi Perpustakaan';
        }
        elseif ($kriterias == 'subjek') 
        {
            $name = 'Subjek';
        }
        elseif ($kriterias == 'jenis_sumber') 
        {
            $name = 'Nama Sumber';
        }
        elseif ($kriterias == 'Partners') 
        {
            $name = 'Nama Sumber';
        } 
        elseif ($kriterias == 'kategori_koleksi') 
        {
            $name = 'Kategori';
        } 
        elseif ($kriterias == 'bentuk_fisik') 
        {
            $name = 'Bentuk Fisik';
        } 
        elseif ($kriterias == 'Pendidikan') 
        {
            $name = 'Pendidikan';
        }
        elseif ($kriterias == 'lokasi_pinjam') 
        {
            $name = 'Lokasi Pinjam';
        }
        elseif ($kriterias == 'tujuan') 
        {
            $name = 'Penginput Data Peminjaman';
        }
        elseif ($kriterias == 'tujuan2') 
        {
            $name = 'Penginput Data Pengembalian';
        }
        elseif ($kriterias == 'Status_Anggota') 
        {
            $name = 'Status Anggota';
        }
        elseif ($kriterias == 'Jenis_Anggota') 
        {
            $name = 'Jenis Anggota';
        }
        elseif ($kriterias == 'jenis_kelamin') 
        {
            $name = 'Jenis Kelamin';
        }
        elseif ($kriterias == 'Kelas') 
        {
            $name = 'Kelas';
        }
        elseif ($kriterias == 'jenis_anggota') 
        {
            $name = 'Jenis Anggota';
        }
        elseif ($kriterias == 'Fakultas') 
        {
            $name = 'Fakultas';
        }
        elseif ($kriterias == 'Jurusan') 
        {
            $name = 'Jurusan';
        }
        elseif ($kriterias == 'peminjam_terbanyak') 
        {
            $name = 'Peminjam';
        }
        elseif ($kriterias == 'departments') 
        {
            $name = 'Unit Kerja';
        }
        elseif ($kriterias == 'range_umur') 
        {
            $name = 'Kelompok Umur';
        }
        elseif ($kriterias == 'ruang_perpus') 
        {
            $name = 'Ruang Perpustakaan';
        }
        elseif ($kriterias == 'lokasi_perpus') 
        {
            $name = 'Lokasi Perpustakaan';
        }
        else 
        {
            $name = ' ';
        }
        
        return $name;

    }
}
