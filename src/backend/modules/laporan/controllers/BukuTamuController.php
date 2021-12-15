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
use common\models\VLapKriteriaAnggota;
use common\models\Collectioncategorys;
use common\models\MasterJenisIdentitas;
use common\models\MasterRangeUmur;
use common\models\Kabupaten;

class BukuTamuController extends \yii\web\Controller
{
    /**
     * [actionIndex description]
     * @return [type] [description]
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionKunjunganPeriodik()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('kunjungan-periodik',[
            'model' => $model,
            ]);
    }

    public function actionKunjunganKhususAnggota()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('kunjungan-khusus-anggota',[
            'model' => $model,
            ]);
    }

    public function actionAnggotaSeringBerkunjung()
    {

        $model = array();

        unset($_SESSION['Array_POST_Filter']);

        return $this->render('anggota-sering-berkunjung',[
            'model' => $model,
            ]);
    }

    public function actionLoadFilterKriteriaAnggota($kriteria)
    {
        if ($kriteria !== 'range_umur' && $kriteria !== 'jenis_kelamin' && $kriteria !== 'propinsi' && $kriteria !== '' 
            && $kriteria !== 'propinsi2' && $kriteria !== 'lokasi_pinjam' && $kriteria !== 'kategori_koleksi' && $kriteria !== 'jenis_identitas' && $kriteria !=='kabupaten' && $kriteria !=='kabupaten2' && $kriteria !=='data_entry' 
            && $kriteria !== 'nama_institusi' && $kriteria !== 'program_studi' && $kriteria !== 'nomer_anggota' && $kriteria !== 'kecamatan' && $kriteria !== 'kelurahan'
            ) 
        {   
            $options = ArrayHelper::map(VLapKriteriaAnggota::find()->where(['kriteria'=>$kriteria])->all(),'id_dtl_anggota','dtl_anggota'); 
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);
            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
            );
        } 
        else if ($kriteria == 'range_umur')
        {
            $options =  ArrayHelper::map(MasterRangeUmur::find()->orderBy('id')->asArray()->all(),'id','Keterangan');
            $options = array_filter($options);

            array_unshift( $options, "---Semua Range---");

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        } 
        else if ($kriteria == 'jenis_kelamin')
        {
            $options =  ArrayHelper::map(JenisKelamin::find()->orderBy('ID')->asArray()->all(),'ID',
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
        else if ($kriteria == 'propinsi')
        {
            $options =  ArrayHelper::map(Propinsi::find()->orderBy('NamaPropinsi')->asArray()->all(),'NamaPropinsi',
                function($model) {
                    return $model['NamaPropinsi'];
                });
            array_unshift( $options, yii::t('app',' ---Semua---'));

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'propinsi2')
        {
            $options =  ArrayHelper::map(Propinsi::find()->orderBy('NamaPropinsi')->asArray()->all(),'NamaPropinsi',
                function($model) {
                    return $model['NamaPropinsi'];
                });
            array_unshift( $options, yii::t('app',' ---Semua---'));

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'lokasi_pinjam')
        {
            $sql = "SELECT * FROM location_library";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','Name');
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'program_studi')
        {
            $sql = "SELECT * FROM master_program_studi";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'id','Nama');
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'nomer_anggota')
        {
            $sql = "SELECT ID, CONCAT(members.MemberNo, ' - ', members.Fullname) AS Name FROM members";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','Name');
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'kategori_koleksi')
        {
            $options =  ArrayHelper::map(Collectioncategorys::find()->orderBy('ID')->asArray()->all(),'Name',
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
        else if ($kriteria == 'jenis_identitas')
        {
            $options =  ArrayHelper::map(MasterJenisIdentitas::find()->orderBy('id')->asArray()->all(),'id',
                function($model) {
                    return $model['Nama'];
                });
            array_unshift( $options, yii::t('app',' ---Semua---'));

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'kabupaten')
        {
            $options =  ArrayHelper::map(Kabupaten::find()->orderBy('ID')->asArray()->all(),'NamaKab',
                function($model) {
                    return $model['NamaKab'];
                });
            array_unshift( $options, yii::t('app',' ---Semua---'));

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'kecamatan')
        {
            $sql = "SELECT DISTINCT(members.Kecamatan) AS kec FROM members WHERE members.Kecamatan != ''";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'kec','kec');
            // $options2 = \yii\helpers\ArrayHelper::merge(["0"=>"---Semua---"],$options);
            $options[null] = yii::t('app',' ---Semua---');
            $options[XI] = " Kosong";
            asort($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'kelurahan')
        {
            $sql = "SELECT DISTINCT(members.Kelurahan) AS kel FROM members WHERE members.Kelurahan != ''";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'kel','kel');
            // $options2 = \yii\helpers\ArrayHelper::merge(["0"=>"---Semua---"],$options);
            $options[null] = yii::t('app',' ---Semua---');
            $options[XI] = " Kosong";
            asort($options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'kabupaten2')
        {
            $options =  ArrayHelper::map(Kabupaten::find()->orderBy('ID')->asArray()->all(),'NamaKab',
                function($model) {
                    return $model['NamaKab'];
                });
            array_unshift( $options, yii::t('app',' ---Semua---'));

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'nama_institusi')
        {
            $sql = 'SELECT * FROM members';
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','InstitutionName');
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

    public function actionLoadFilterKriteria($kriteria)
    {
        if ($kriteria !== 'range_umur' && $kriteria !== 'jenis_kelamin' && $kriteria !== 'propinsi' && $kriteria !== ''
            && $kriteria !== 'propinsi2' && $kriteria !== 'lokasi_pinjam' && $kriteria !== 'kategori_koleksi' && $kriteria !== 'jenis_identitas' && $kriteria !=='kabupaten' && $kriteria !=='kabupaten2' && $kriteria !=='data_entry' && $kriteria !== 'lokasi-pinjam' && $kriteria !== 'nama_institusi'
            && $kriteria !== 'lokasi_perpus' && $kriteria !== 'ruang_perpus' && $kriteria !== 'tujuan' && $kriteria !== 'kriteria_pengunjung' && $kriteria !== 'createdate'       
            ) 
        {
            $options = ArrayHelper::map(VLapKriteriaAnggota::find()->where(['kriteria'=>$kriteria_anggota])->all(),'id_dtl_anggota','dtl_anggota');
            $options[0] = yii::t('app',' ---Semua---');
            asort($options);
            $options = array_filter($options);
            $contentOptions = Html::dropDownList( $kriteria_anggota.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
            );
        } 
        else if ($kriteria == 'lokasi_perpus')
        {
            $sql = "SELECT * FROM location_library";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','Name');
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'jenis_kelamin')
        {
            $sql = "SELECT * FROM jenis_kelamin";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','Name');
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
        }
        else if ($kriteria == 'ruang_perpus')
        {
            $sql = "SELECT * FROM locations";
            $data = Yii::$app->db->createCommand($sql)->queryAll(); 
            $options = ArrayHelper::map($data,'ID','Name');
            $options2 = \yii\helpers\ArrayHelper::merge(["0"=>yii::t('app',' ---Semua---')],$options);

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options2, 
                ['class' => 'select2 col-sm-6',]
                );
        }  
        else if ($kriteria == 'tujuan')
        {
            $options =  ArrayHelper::map(TujuanKunjungan::find()->orderBy('ID')->asArray()->all(),'ID',
                function($model) {
                    return $model['Code'];
                });
            array_unshift( $options, yii::t('app',' ---Semua---'));

            $contentOptions = Html::dropDownList( $kriteria.'[]',
                'selected option',  
                $options, 
                ['class' => 'select2 col-sm-6',]
                );
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

   
    //   [actionLoadSelecterKriteria description]
    //   @param  [type] $i [description]
    //   @return [type]    [description]
     
    public function actionLoadSelecterKriteriaLokasi($i)
    {
        return $this->renderPartial('select-kriteria-lokasi',['i'=>$i]);
    }
    public function actionLoadSelecterKriteriaLokasi2($i)
    {
        return $this->renderPartial('select-kriteria-lokasi2',['i'=>$i]);
    }
    public function actionLoadSelecterKriteriaAnggota($i)
    {
        return $this->renderPartial('select-kriteria-anggota',['i'=>$i]);
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
        // var_dump($_POST);
        // echo "</pre>";

        // print_r(count(array_filter($_POST['kriterias'])));
        // print_r(isset($_POST['kota_terbit']));
        // echo 'Okeee'.$_POST['periode'];
        if ($tampilkan == 'kunjungan-periodik-frekuensi')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-kunjungan-periodik-frekuensi').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );
        }
        elseif ($tampilkan == 'kunjungan-periodik-data')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-kunjungan-periodik-data').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );  
        }
        elseif ($tampilkan == 'kunjungan-khusus-anggota-frekuensi')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-khusus-anggota-frekuensi').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );  
        }
        elseif ($tampilkan == 'kunjungan-khusus-anggota-data')
        {
            echo (count(array_filter($_POST['kriterias'])) != 0 ? 
                '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('render-khusus-anggota-data').'">'."<iframe>"
                :"<script>swal('".yii::t('app','Pilih kriteria terlebih dahulu')."');</script>"
            );  
        }

        if ($tampilkan == 'anggota-sering-berkunjung-frekuensi') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('anggota-sering-berkunjung-frekuensi').'">';
            echo "<iframe>";
        }
        if ($tampilkan == 'anggota-sering-berkunjung-data') 
        {
            echo '<script>$("#export").show();</script><iframe class="col-sm-12" style="height: 500px; padding: 0;" src="'.Url::to('anggota-sering-berkunjung-data').'">';
            echo "<iframe>";
        }  
    }

public function actionRenderKunjunganPeriodikData() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $tujuan = '';
        $sqlPeriode = '';

        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode= yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }

        $sql = "SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info,
                (SELECT (COUNT(*) + (SELECT COUNT(*) FROM groupguesses WHERE groupguesses.`CreateDate` ".$sqlPeriode.")) AS red FROM memberguesses
                WHERE memberguesses.`CreateDate` ".$sqlPeriode.") AS count
                FROM(
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoAnggota AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id 
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                ) member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'non anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoPengunjung AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = memberguesses.Profesi_id
                LEFT JOIN master_pendidikan ON master_pendidikan.id = memberguesses.PendidikanTerakhir_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                WHERE memberguesses.NoAnggota IS NULL ".$tujuan.$andGenderValue."
                ) non_member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'rombongan' AS ket,
                DATE_FORMAT(groupguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                groupguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                groupguesses.NoPengunjung AS no_pengunjung,
                groupguesses.NamaKetua AS nama,
                CONCAT(IFNULL(CONCAT(groupguesses.CountLaki, ' Laki-laki'),''),'<br>', IFNULL(CONCAT(groupguesses.CountPerempuan, ' Perempuan'),'')) AS gender,
                CONCAT(IFNULL(CONCAT(groupguesses.CountPNS, ' PNS','<br>'),''), IFNULL(CONCAT(groupguesses.CountPSwasta, ' Pegawai Swasta','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPeneliti, ' Peneliti','<br>'),''), IFNULL(CONCAT(groupguesses.CountGuru, ' Guru','<br>'),''), IFNULL(CONCAT(groupguesses.CountDosen, ' Dosen','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPensiunan, ' Pensiunan','<br>'),''), IFNULL(CONCAT(groupguesses.CountTNI, ' TNI','<br>'),''), IFNULL(CONCAT(groupguesses.CountWiraswasta, ' Wiraswasta','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPelajar, ' Pelajar','<br>'),''), IFNULL(CONCAT(groupguesses.CountMahasiswa, ' Mahasiswa','<br>'),''), IFNULL(CONCAT(groupguesses.CountLainnya, ' Lainnya'),'')) AS pekerjaan,
                CONCAT(IFNULL(CONCAT(groupguesses.CountSD, ' SD','<br>'),''), IFNULL(CONCAT(groupguesses.CountSMP, ' SMP','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountSMA, ' SMA','<br>'),''), IFNULL(CONCAT(groupguesses.CountD1, ' D1','<br>'),''), IFNULL(CONCAT(groupguesses.CountD2, ' D2','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountD3, ' D3','<br>'),''), IFNULL(CONCAT(groupguesses.CountS1, ' S1','<br>'),''), IFNULL(CONCAT(groupguesses.CountS2, ' S2','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountS3, ' S3'),'')) AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                groupguesses.Information AS info
                FROM
                groupguesses
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                ) group_guess ) test
                WHERE tgl_kunjungan ";
        $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY tgl_kunjungan ASC LIMIT 1000';

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 
// print_r($sql);
// die;

        $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
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
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
            $header =  ['<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 233; width: 100%;" >'];
            $set = 60;
        } else {
            $header =  [''];
            $set = 10;
        }

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            //'format' => Pdf::FORMAT_LETTER,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'marginTop' => $set,
            'marginLeft' => 0,
            'marginRight' => 0,
            'content' => $this->renderPartial('pdf-view-kunjungan-periodik-data', $content),
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=>$header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px; ">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }

public function actionExportExcelKunjunganPeriodikData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $tujuan = '';
        $sqlPeriode = '';

        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode= yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }

        $sql = "SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoAnggota AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id 
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                ) member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'non anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoPengunjung AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = memberguesses.Profesi_id
                LEFT JOIN master_pendidikan ON master_pendidikan.id = memberguesses.PendidikanTerakhir_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                WHERE memberguesses.NoAnggota IS NULL ".$tujuan.$andGenderValue."
                ) non_member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'rombongan' AS ket,
                DATE_FORMAT(groupguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                groupguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                groupguesses.NoPengunjung AS no_pengunjung,
                groupguesses.NamaKetua AS nama,
                CONCAT(IFNULL(CONCAT(groupguesses.CountLaki, ' Laki-laki'),''),'<br>', IFNULL(CONCAT(groupguesses.CountPerempuan, ' Perempuan'),'')) AS gender,
                CONCAT(IFNULL(CONCAT(groupguesses.CountPNS, ' PNS','<br>'),''), IFNULL(CONCAT(groupguesses.CountPSwasta, ' Pegawai Swasta','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPeneliti, ' Peneliti','<br>'),''), IFNULL(CONCAT(groupguesses.CountGuru, ' Guru','<br>'),''), IFNULL(CONCAT(groupguesses.CountDosen, ' Dosen','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPensiunan, ' Pensiunan','<br>'),''), IFNULL(CONCAT(groupguesses.CountTNI, ' TNI','<br>'),''), IFNULL(CONCAT(groupguesses.CountWiraswasta, ' Wiraswasta','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPelajar, ' Pelajar','<br>'),''), IFNULL(CONCAT(groupguesses.CountMahasiswa, ' Mahasiswa','<br>'),''), IFNULL(CONCAT(groupguesses.CountLainnya, ' Lainnya'),'')) AS pekerjaan,
                CONCAT(IFNULL(CONCAT(groupguesses.CountSD, ' SD','<br>'),''), IFNULL(CONCAT(groupguesses.CountSMP, ' SMP','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountSMA, ' SMA','<br>'),''), IFNULL(CONCAT(groupguesses.CountD1, ' D1','<br>'),''), IFNULL(CONCAT(groupguesses.CountD2, ' D2','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountD3, ' D3','<br>'),''), IFNULL(CONCAT(groupguesses.CountS1, ' S1','<br>'),''), IFNULL(CONCAT(groupguesses.CountS2, ' S2','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountS3, ' S3'),'')) AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                groupguesses.Information AS info
                FROM
                groupguesses
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                ) group_guess ) test
                WHERE tgl_kunjungan ";
        
        $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY tgl_kunjungan ASC';

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $format_hari;

    $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

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
                <th colspan="11">'.yii::t('app','Kunjungan Periodik').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="11">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Kunjungan').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Nomor Kunjungan').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Jenis Kelamin').'</th>
                <th>'.yii::t('app','Pekerjaan').'</th>
                <th>'.yii::t('app','Pendidikan').'</th>
                <th>'.yii::t('app','Tujuan Kunjungan').'</th>
                <th>'.yii::t('app','Informasi Dicari').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['periode'].'</td>
                    <td>'.$data['lokasi'].'</td>
                    <td>'.$data['lok_ruang'].'</td>
                    <td>'.$data['no_pengunjung'].'</td>
                    <td>'.$data['nama'].'</td>
                    <td>'.$data['gender'].'</td>
                    <td>'.$data['pekerjaan'].'</td>
                    <td>'.$data['pendidikan'].'</td>
                    <td>'.$data['tujuan'].'</td>
                    <td>'.$data['info'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtKunjunganPeriodikData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $tujuan = '';
        $sqlPeriode = '';

        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode= yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }

        $sql = "SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, gender2, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, gender2, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoAnggota AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                '' AS gender2,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id 
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                ) member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, gender2, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'non anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoPengunjung AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                '' AS gender2,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = memberguesses.Profesi_id
                LEFT JOIN master_pendidikan ON master_pendidikan.id = memberguesses.PendidikanTerakhir_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                WHERE memberguesses.NoAnggota IS NULL ".$tujuan.$andGenderValue."
                ) non_member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, gender2, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'rombongan' AS ket,
                DATE_FORMAT(groupguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                groupguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                groupguesses.NoPengunjung AS no_pengunjung,
                groupguesses.NamaKetua AS nama,
                CONCAT(groupguesses.CountLaki, ' Laki-laki') AS gender,
                CONCAT(groupguesses.CountPerempuan, ' Perempuan') AS gender2,
                CONCAT(groupguesses.CountPNS, ' PNS' ,'<br>', groupguesses.CountPSwasta, ' Pegawai Swasta','<br>', 
                       groupguesses.CountPeneliti, ' Peneliti','<br>', groupguesses.CountGuru, ' Guru','<br>', groupguesses.CountDosen, ' Dosen','<br>', 
                       groupguesses.CountPensiunan, ' Pensiunan','<br>', groupguesses.CountTNI, ' TNI','<br>', groupguesses.CountWiraswasta, ' Wiraswasta','<br>', 
                       groupguesses.CountPelajar, ' Pelajar','<br>', groupguesses.CountMahasiswa, ' Mahasiswa','<br>', groupguesses.CountLainnya, ' Lainnya') AS pekerjaan,
                CONCAT(groupguesses.CountSD, ' SD' ,'<br>', groupguesses.CountSMP, ' SMP','<br>', 
                       groupguesses.CountSMA, ' SMA','<br>', groupguesses.CountD1, ' D1','<br>', groupguesses.CountD2, ' D2','<br>', 
                       groupguesses.CountD3, ' D3','<br>', groupguesses.CountS1, ' S1','<br>', groupguesses.CountS2, ' S2','<br>', 
                       groupguesses.CountS3, ' S3') AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                groupguesses.Information AS info
                FROM
                groupguesses
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                ) group_guess ) test
                WHERE tgl_kunjungan ";
        
        $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY tgl_kunjungan ASC';   

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'periode'=> $model['periode'], 'lokasi'=>$model['lokasi'], 'lok_ruang'=>$model['lok_ruang'], 'no_pengunjung'=>$model['no_pengunjung']
                         , 'nama'=>$model['nama'], 'gender'=>$model['gender'], 'gender2'=>$model['gender2'], 'pekerjaan'=>$model['pekerjaan'], 'pendidikan'=>$model['pendidikan'], 'tujuan'=>$model['tujuan'], 'info'=>$model['info'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'kunjungan_periodik'=> yii::t('app','Kunjungan Periodik'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_kunjungan'=> yii::t('app','Tanggal Kunjungan'),
        'lokasi_perpustakaan'=> yii::t('app','Lokasi Perpustakaan'),
        'lokasi_ruang'=> yii::t('app','Lokasi Ruang'),
        'nomor_kunjungan'=> yii::t('app','Nomor Kunjungan'),
        'nama'=> yii::t('app','Nama'),
        'jenis_kelamin'=> yii::t('app','Jenis Kelamin'),
        'pekerjaan'=> yii::t('app','Pekerjaan'),
        'pendidikan'=> yii::t('app','Pendidikan'),
        'tujuan_kunjungan'=> yii::t('app','Tujuan Kunjungan'),
        'informasi_dicari'=> yii::t('app','Informasi Dicari'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/buku_tamu/laporan-buku_tamu-kunjungan-periodik-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Kunjungan-Periodik-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordKunjunganPeriodikData()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $tujuan = '';
        $sqlPeriode = '';

        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode= yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }

        $sql = "SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoAnggota AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id 
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                ) member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'non anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoPengunjung AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = memberguesses.Profesi_id
                LEFT JOIN master_pendidikan ON master_pendidikan.id = memberguesses.PendidikanTerakhir_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                WHERE memberguesses.NoAnggota IS NULL ".$tujuan.$andGenderValue."
                ) non_member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'rombongan' AS ket,
                DATE_FORMAT(groupguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                groupguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                groupguesses.NoPengunjung AS no_pengunjung,
                groupguesses.NamaKetua AS nama,
                CONCAT(IFNULL(CONCAT(groupguesses.CountLaki, ' Laki-laki'),''),'<br>', IFNULL(CONCAT(groupguesses.CountPerempuan, ' Perempuan'),'')) AS gender,
                CONCAT(IFNULL(CONCAT(groupguesses.CountPNS, ' PNS','<br>'),''), IFNULL(CONCAT(groupguesses.CountPSwasta, ' Pegawai Swasta','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPeneliti, ' Peneliti','<br>'),''), IFNULL(CONCAT(groupguesses.CountGuru, ' Guru','<br>'),''), IFNULL(CONCAT(groupguesses.CountDosen, ' Dosen','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPensiunan, ' Pensiunan','<br>'),''), IFNULL(CONCAT(groupguesses.CountTNI, ' TNI','<br>'),''), IFNULL(CONCAT(groupguesses.CountWiraswasta, ' Wiraswasta','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPelajar, ' Pelajar','<br>'),''), IFNULL(CONCAT(groupguesses.CountMahasiswa, ' Mahasiswa','<br>'),''), IFNULL(CONCAT(groupguesses.CountLainnya, ' Lainnya'),'')) AS pekerjaan,
                CONCAT(IFNULL(CONCAT(groupguesses.CountSD, ' SD','<br>'),''), IFNULL(CONCAT(groupguesses.CountSMP, ' SMP','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountSMA, ' SMA','<br>'),''), IFNULL(CONCAT(groupguesses.CountD1, ' D1','<br>'),''), IFNULL(CONCAT(groupguesses.CountD2, ' D2','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountD3, ' D3','<br>'),''), IFNULL(CONCAT(groupguesses.CountS1, ' S1','<br>'),''), IFNULL(CONCAT(groupguesses.CountS2, ' S2','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountS3, ' S3'),'')) AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                groupguesses.Information AS info
                FROM
                groupguesses
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                ) group_guess ) test
                WHERE tgl_kunjungan ";
        
        $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY tgl_kunjungan ASC';

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $format_hari;

    $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
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
                <th colspan="11">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="11">'.yii::t('app','Kunjungan Periodik').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="11">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal Kunjungan').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Nomor Kunjungan').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Jenis Kelamin').'</th>
                <th>'.yii::t('app','Pekerjaan').'</th>
                <th>'.yii::t('app','Pendidikan').'</th>
                <th>'.yii::t('app','Tujuan Kunjungan').'</th>
                <th>'.yii::t('app','Informasi Dicari').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['periode'].'</td>
                    <td>'.$data['lokasi'].'</td>
                    <td>'.$data['lok_ruang'].'</td>
                    <td>'.$data['no_pengunjung'].'</td>
                    <td>'.$data['nama'].'</td>
                    <td>'.$data['gender'].'</td>
                    <td>'.$data['pekerjaan'].'</td>
                    <td>'.$data['pendidikan'].'</td>
                    <td>'.$data['tujuan'].'</td>
                    <td>'.$data['info'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfKunjunganPeriodikData() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $tujuan = '';
        $sqlPeriode = '';

        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = '%d-%M-%Y';
                $periode= yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }
        
        $sql = "SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoAnggota AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id 
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                ) member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'non anggota' AS ket,
                DATE_FORMAT(memberguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                memberguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                memberguesses.NoPengunjung AS no_pengunjung,
                memberguesses.Nama AS nama,
                jenis_kelamin.Name AS gender,
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                memberguesses.Information AS info
                FROM
                memberguesses
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = memberguesses.Profesi_id
                LEFT JOIN master_pendidikan ON master_pendidikan.id = memberguesses.PendidikanTerakhir_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                WHERE memberguesses.NoAnggota IS NULL ".$tujuan.$andGenderValue."
                ) non_member
                UNION ALL
                SELECT ket, tgl_kunjungan, periode, lokasi, lok_ruang, no_pengunjung, nama, gender, pekerjaan, pendidikan, tujuan, info
                FROM(
                SELECT 'rombongan' AS ket,
                DATE_FORMAT(groupguesses.CreateDate, '%Y-%m-%d') AS tgl_kunjungan,
                groupguesses.CreateDate AS periode,
                location_library.Name AS lokasi,
                locations.Name AS lok_ruang,
                groupguesses.NoPengunjung AS no_pengunjung,
                groupguesses.NamaKetua AS nama,
                CONCAT(IFNULL(CONCAT(groupguesses.CountLaki, ' Laki-laki'),''),'<br>', IFNULL(CONCAT(groupguesses.CountPerempuan, ' Perempuan'),'')) AS gender,
                CONCAT(IFNULL(CONCAT(groupguesses.CountPNS, ' PNS','<br>'),''), IFNULL(CONCAT(groupguesses.CountPSwasta, ' Pegawai Swasta','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPeneliti, ' Peneliti','<br>'),''), IFNULL(CONCAT(groupguesses.CountGuru, ' Guru','<br>'),''), IFNULL(CONCAT(groupguesses.CountDosen, ' Dosen','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPensiunan, ' Pensiunan','<br>'),''), IFNULL(CONCAT(groupguesses.CountTNI, ' TNI','<br>'),''), IFNULL(CONCAT(groupguesses.CountWiraswasta, ' Wiraswasta','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountPelajar, ' Pelajar','<br>'),''), IFNULL(CONCAT(groupguesses.CountMahasiswa, ' Mahasiswa','<br>'),''), IFNULL(CONCAT(groupguesses.CountLainnya, ' Lainnya'),'')) AS pekerjaan,
                CONCAT(IFNULL(CONCAT(groupguesses.CountSD, ' SD','<br>'),''), IFNULL(CONCAT(groupguesses.CountSMP, ' SMP','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountSMA, ' SMA','<br>'),''), IFNULL(CONCAT(groupguesses.CountD1, ' D1','<br>'),''), IFNULL(CONCAT(groupguesses.CountD2, ' D2','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountD3, ' D3','<br>'),''), IFNULL(CONCAT(groupguesses.CountS1, ' S1','<br>'),''), IFNULL(CONCAT(groupguesses.CountS2, ' S2','<br>'),''), 
                       IFNULL(CONCAT(groupguesses.CountS3, ' S3'),'')) AS pendidikan,
                tujuan_kunjungan.TujuanKunjungan AS tujuan,
                groupguesses.Information AS info
                FROM
                groupguesses
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                ) group_guess ) test
                WHERE tgl_kunjungan ";
        $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY tgl_kunjungan ASC';

        $data = Yii::$app->db->createCommand($sql)->queryAll(); 
// print_r($sql);
// die;

        $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
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
        $content = $this->renderPartial('pdf-view-kunjungan-periodik-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

    }

public function actionRenderKhususAnggotaData() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
      //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun']; 
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Sex_id  = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['kategori_koleksi'])) {
            foreach ($_POST['kategori_koleksi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collectioncategorys.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Status_Anggota'])) {
            foreach ($_POST['Status_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND status_anggota.Nama = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        }



       if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }  
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        $sql = "SELECT ".$periode_format.",
                memberguesses.NoAnggota AS MemberNo,
                location_library.Name AS lok_perpus,
                locations.Name AS lok_ruang, 
                memberguesses.NoAnggota AS MemberNo,
                memberguesses.Nama AS Anggota,
                jenis_kelamin.Name AS jenis_kelamin,
                CONCAT(members.PlaceOfBirth,', ', DATE_FORMAT(members.DateOfBirth, '%d-%M-%Y')) AS TTL,
                (YEAR(CURDATE())-YEAR(members.DateOfBirth)) AS umur,
                members.Address AS alamat,
                members.City AS kab_kota,
                members.Province AS provinsi,
                members.NoHp AS telepon,
                members.Email AS email,
                jenis_anggota.jenisanggota AS jenis_anggota, 
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan, 
                master_fakultas.Nama AS fakultas,
                master_jurusan.Nama AS jurusan, 
                kelas_siswa.namakelassiswa AS kelas
                FROM memberguesses 
                LEFT JOIN members ON TRIM(TRAILING  '   ' FROM memberguesses.NoAnggota) = members.MemberNo
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN jenis_anggota ON jenis_anggota.id = members.JenisAnggota_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id 
                LEFT JOIN master_fakultas ON master_fakultas.id = members.Fakultas_id 
                LEFT JOIN master_jurusan ON master_jurusan.id = members.Jurusan_id
                LEFT JOIN kelas_siswa ON kelas_siswa.id = members.Kelas_id  
                WHERE DATE(memberguesses.CreateDate) ";      
        $sql .= $sqlPeriode;
        $sql .= ' AND memberguesses.NoAnggota IS NOT NULL';
        $sql .= $andValue;
        $sql .= ' ORDER BY DATE_FORMAT(memberguesses.CreateDate,"%Y-%m-%d") ASC';
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        // array_filter($Berdasarkan);
        $Berdasarkan = array_filter($Berdasarkan);
if (sizeof($Berdasarkan) !=1) {
        $Berdasarkan = implode(yii::t('app',' dan '),$Berdasarkan);
}else{$Berdasarkan = implode($Berdasarkan);}
// die;

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan; 
        $content['kop'] = isset($_POST['kop']);

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
            'marginLeft' =>0,
            'marginRight' => 0,
            'content' => $this->renderPartial('pdf-view-khusus-anggota-data', $content),
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=>$header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }

public function actionExportExcelKunjunganKhususData()
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
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun']; 
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Sex_id  = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['kategori_koleksi'])) {
            foreach ($_POST['kategori_koleksi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collectioncategorys.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Status_Anggota'])) {
            foreach ($_POST['Status_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND status_anggota.Nama = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        }



       if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }  
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        $sql = "SELECT ".$periode_format.",
                memberguesses.NoAnggota AS MemberNo,
                location_library.Name AS lok_perpus,
                locations.Name AS lok_ruang, 
                memberguesses.NoAnggota AS MemberNo,
                memberguesses.Nama AS Anggota,
                jenis_kelamin.Name AS jenis_kelamin,
                CONCAT(members.PlaceOfBirth,', ', DATE_FORMAT(members.DateOfBirth, '%d-%M-%Y')) AS TTL,
                (YEAR(CURDATE())-YEAR(members.DateOfBirth)) AS umur,
                members.Address AS alamat,
                members.City AS kab_kota,
                members.Province AS provinsi,
                members.NoHp AS telepon,
                members.Email AS email,
                jenis_anggota.jenisanggota AS jenis_anggota, 
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan, 
                master_fakultas.Nama AS fakultas,
                master_jurusan.Nama AS jurusan, 
                kelas_siswa.namakelassiswa AS kelas
                FROM memberguesses 
                LEFT JOIN members ON TRIM(TRAILING  '   ' FROM memberguesses.NoAnggota) = members.MemberNo
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN jenis_anggota ON jenis_anggota.id = members.JenisAnggota_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id 
                LEFT JOIN master_fakultas ON master_fakultas.id = members.Fakultas_id 
                LEFT JOIN master_jurusan ON master_jurusan.id = members.Jurusan_id
                LEFT JOIN kelas_siswa ON kelas_siswa.id = members.Kelas_id  
                WHERE DATE(memberguesses.CreateDate) ";      
        $sql .= $sqlPeriode;
        $sql .= ' AND memberguesses.NoAnggota IS NOT NULL';
        $sql .= $andValue;
        $sql .= ' ORDER BY Periode ASC';

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
                <th colspan="20">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="20">'.yii::t('app','Kunjungan Member Periodik').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="20">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Jenis Kelamin').'</th>
                <th>'.yii::t('app','Tempat & Tanggal Lahir').'</th>
                <th>'.yii::t('app','Umur').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Kabupaten / Kota').'</th>
                <th>'.yii::t('app','Propinsi').'</th>
                <th>'.yii::t('app','Telepon').'</th>
                <th>'.yii::t('app','Email').'</th>
                <th>'.yii::t('app','Jenis Anggota').'</th>
                <th>'.yii::t('app','Pekerjaan').'</th>
                <th>'.yii::t('app','Pendidikan').'</th>
                <th>'.yii::t('app','Fakultas').'</th>
                <th>'.yii::t('app','Jurusan').'</th>
                <th>'.yii::t('app','Kelas').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['lokasi_pinjam'].'</td>
                    <td>'.$data['lok_ruang'].'</td>
                    <td>'.$data['MemberNo'].'</td>
                    <td>'.$data['Anggota'].'</td>
                    <td>'.$data['jenis_kelamin'].'</td>
                    <td>'.$data['TTL'].'</td>
                    <td>'.$data['umur'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kab_kota'].'</td>
                    <td>'.$data['provinsi'].'</td>
                    <td>'.$data['telepon'].'</td>
                    <td>'.$data['email'].'</td>
                    <td>'.$data['jenis_anggota'].'</td>
                    <td>'.$data['pekerjaan'].'</td>
                    <td>'.$data['pendidikan'].'</td>
                    <td>'.$data['fakultas'].'</td>
                    <td>'.$data['jurusan'].'</td>
                    <td>'.$data['kelas'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtKhususAnggotaData()
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
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun']; 
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Sex_id  = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['kategori_koleksi'])) {
            foreach ($_POST['kategori_koleksi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collectioncategorys.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Status_Anggota'])) {
            foreach ($_POST['Status_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND status_anggota.Nama = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        }



       if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }  
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        $sql = "SELECT ".$periode_format.",
                location_library.Name AS lok_perpus,
                locations.Name AS lok_ruang, 
                memberguesses.NoAnggota AS MemberNo,
                memberguesses.Nama AS Anggota,
                jenis_kelamin.Name AS jenis_kelamin,
                CONCAT(members.PlaceOfBirth,', ', DATE_FORMAT(members.DateOfBirth, '%d-%M-%Y')) AS TTL,
                (YEAR(CURDATE())-YEAR(members.DateOfBirth)) AS umur,
                members.Address AS alamat,
                members.City AS kab_kota,
                members.Province AS provinsi,
                members.NoHp AS telepon,
                members.Email AS email,
                jenis_anggota.jenisanggota AS jenis_anggota, 
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan, 
                master_fakultas.Nama AS fakultas,
                master_jurusan.Nama AS jurusan, 
                kelas_siswa.namakelassiswa AS kelas
                FROM memberguesses 
                LEFT JOIN members ON TRIM(TRAILING  '   ' FROM memberguesses.NoAnggota) = members.MemberNo
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN jenis_anggota ON jenis_anggota.id = members.JenisAnggota_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id 
                LEFT JOIN master_fakultas ON master_fakultas.id = members.Fakultas_id 
                LEFT JOIN master_jurusan ON master_jurusan.id = members.Jurusan_id
                LEFT JOIN kelas_siswa ON kelas_siswa.id = members.Kelas_id  
                WHERE DATE(memberguesses.CreateDate) ";      
        $sql .= $sqlPeriode;
        $sql .= ' AND memberguesses.NoAnggota IS NOT NULL';
        $sql .= $andValue;
        $sql .= ' ORDER BY Periode ASC';

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
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'lok_perpus'=>$model['lok_perpus'], 'lok_ruang'=>$model['lok_ruang'], 'MemberNo'=>$model['MemberNo'], 'Anggota'=>$model['Anggota']
                        , 'jenis_kelamin'=>$model['jenis_kelamin'], 'TTL'=>$model['TTL'], 'umur'=>$model['umur'], 'alamat'=>$model['alamat'] , 'kab_kota'=>$model['kab_kota'], 'provinsi'=>$model['provinsi']
                        , 'telepon'=>$model['telepon'], 'email'=>$model['email'], 'jenis_anggota'=>$model['jenis_anggota'], 'pekerjaan'=>$model['pekerjaan'], 'pendidikan'=>$model['pendidikan'], 'fakultas'=>$model['fakultas']
                        , 'jurusan'=>$model['jurusan'], 'kelas'=>$model['kelas'] );
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'Berdasarkan'=>$Berdasarkan,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'kunjungan_memberperiodik'=> yii::t('app','Kunjungan Member Periodik'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal'=> yii::t('app','Tanggal'),
        'lokasi_perpustakaan'=> yii::t('app','Lokasi Perpustakaan'),
        'lokasi_ruang'=> yii::t('app','Lokasi Ruang'),
        'nomor_anggota'=> yii::t('app','Nomor Anggota'),
        'nama'=> yii::t('app','Nama'),
        'jenis_kelamin'=> yii::t('app','Jenis Kelamin'),
        'ttl'=> yii::t('app','Tempat & Tanggal Lahir'),
        'umur'=> yii::t('app','Umur'),
        'alamat'=> yii::t('app','Alamat'),
        'kabupaten_kota'=> yii::t('app','Kabupaten / Kota'),
        'propinsi'=> yii::t('app','Propinsi'),
        'telepon'=> yii::t('app','Telepon'),
        'email'=> yii::t('app','Email'),
        'jenis_anggota'=> yii::t('app','Jenis Anggota'),
        'pekerjaan'=> yii::t('app','Pekerjaan'),
        'pendidikan'=> yii::t('app','Pendidikan'),
        'fakultas'=> yii::t('app','Fakultas'),
        'jurusan'=> yii::t('app','Jurusan'),
        'kelas'=> yii::t('app','Kelas'),
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/buku_tamu/laporan-buku_tamu-kunjungan-khusus-anggota-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Baca-ditempat-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordKunjunganKhususData()
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
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun']; 
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Sex_id  = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['kategori_koleksi'])) {
            foreach ($_POST['kategori_koleksi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collectioncategorys.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Status_Anggota'])) {
            foreach ($_POST['Status_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND status_anggota.Nama = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        }



       if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }  
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        $sql = "SELECT ".$periode_format.",
                memberguesses.NoAnggota AS MemberNo,
                location_library.Name AS lok_perpus,
                locations.Name AS lok_ruang, 
                memberguesses.NoAnggota AS MemberNo,
                memberguesses.Nama AS Anggota,
                jenis_kelamin.Name AS jenis_kelamin,
                CONCAT(members.PlaceOfBirth,', ', DATE_FORMAT(members.DateOfBirth, '%d-%M-%Y')) AS TTL,
                (YEAR(CURDATE())-YEAR(members.DateOfBirth)) AS umur,
                members.Address AS alamat,
                members.City AS kab_kota,
                members.Province AS provinsi,
                members.NoHp AS telepon,
                members.Email AS email,
                jenis_anggota.jenisanggota AS jenis_anggota, 
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan, 
                master_fakultas.Nama AS fakultas,
                master_jurusan.Nama AS jurusan, 
                kelas_siswa.namakelassiswa AS kelas
                FROM memberguesses 
                LEFT JOIN members ON TRIM(TRAILING  '   ' FROM memberguesses.NoAnggota) = members.MemberNo
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN jenis_anggota ON jenis_anggota.id = members.JenisAnggota_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id 
                LEFT JOIN master_fakultas ON master_fakultas.id = members.Fakultas_id 
                LEFT JOIN master_jurusan ON master_jurusan.id = members.Jurusan_id
                LEFT JOIN kelas_siswa ON kelas_siswa.id = members.Kelas_id  
                WHERE DATE(memberguesses.CreateDate) ";      
        $sql .= $sqlPeriode;
        $sql .= ' AND memberguesses.NoAnggota IS NOT NULL';
        $sql .= $andValue;
        $sql .= ' ORDER BY Periode ASC';

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
    // header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center" width="200"> 
            <tr>
                <th colspan="20">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="20">'.yii::t('app','Kunjungan Member Periodik').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="20">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Jenis Kelamin').'</th>
                <th>'.yii::t('app','Tempat & Tanggal Lahir').'</th>
                <th>'.yii::t('app','Umur').'</th>
                <th>'.yii::t('app','Alamat').'</th>
                <th>'.yii::t('app','Kabupaten / Kota').'</th>
                <th>'.yii::t('app','Propinsi').'</th>
                <th>'.yii::t('app','Telepon').'</th>
                <th>'.yii::t('app','Email').'</th>
                <th>'.yii::t('app','Jenis Anggota').'</th>
                <th>'.yii::t('app','Pekerjaan').'</th>
                <th>'.yii::t('app','Pendidikan').'</th>
                <th>'.yii::t('app','Fakultas').'</th>
                <th>'.yii::t('app','Jurusan').'</th>
                <th>'.yii::t('app','Kelas').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['lokasi_pinjam'].'</td>
                    <td>'.$data['lok_ruang'].'</td>
                    <td>'.$data['MemberNo'].'</td>
                    <td>'.$data['Anggota'].'</td>
                    <td>'.$data['jenis_kelamin'].'</td>
                    <td>'.$data['TTL'].'</td>
                    <td>'.$data['umur'].'</td>
                    <td>'.$data['alamat'].'</td>
                    <td>'.$data['kab_kota'].'</td>
                    <td>'.$data['provinsi'].'</td>
                    <td>'.$data['telepon'].'</td>
                    <td>'.$data['email'].'</td>
                    <td>'.$data['jenis_anggota'].'</td>
                    <td>'.$data['pekerjaan'].'</td>
                    <td>'.$data['pendidikan'].'</td>
                    <td>'.$data['fakultas'].'</td>
                    <td>'.$data['jurusan'].'</td>
                    <td>'.$data['kelas'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfKunjunganKhususData()
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
      //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun']; 
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y %H:%i:%S") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Sex_id  = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 

        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['kategori_koleksi'])) {
            foreach ($_POST['kategori_koleksi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND collectioncategorys.Name  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND  DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Status_Anggota'])) {
            foreach ($_POST['Status_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND status_anggota.Nama = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        }



       if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }  
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        $sql = "SELECT ".$periode_format.",
                memberguesses.NoAnggota AS MemberNo,
                location_library.Name AS lok_perpus,
                locations.Name AS lok_ruang, 
                memberguesses.NoAnggota AS MemberNo,
                memberguesses.Nama AS Anggota,
                jenis_kelamin.Name AS jenis_kelamin,
                CONCAT(members.PlaceOfBirth,', ', DATE_FORMAT(members.DateOfBirth, '%d-%M-%Y')) AS TTL,
                (YEAR(CURDATE())-YEAR(members.DateOfBirth)) AS umur,
                members.Address AS alamat,
                members.City AS kab_kota,
                members.Province AS provinsi,
                members.NoHp AS telepon,
                members.Email AS email,
                jenis_anggota.jenisanggota AS jenis_anggota, 
                master_pekerjaan.Pekerjaan AS pekerjaan,
                master_pendidikan.Nama AS pendidikan, 
                master_fakultas.Nama AS fakultas,
                master_jurusan.Nama AS jurusan, 
                kelas_siswa.namakelassiswa AS kelas
                FROM memberguesses 
                LEFT JOIN members ON TRIM(TRAILING  '   ' FROM memberguesses.NoAnggota) = members.MemberNo
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN jenis_anggota ON jenis_anggota.id = members.JenisAnggota_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id 
                LEFT JOIN master_fakultas ON master_fakultas.id = members.Fakultas_id 
                LEFT JOIN master_jurusan ON master_jurusan.id = members.Jurusan_id
                LEFT JOIN kelas_siswa ON kelas_siswa.id = members.Kelas_id  
                WHERE DATE(memberguesses.CreateDate) ";      
        $sql .= $sqlPeriode;
        $sql .= ' AND memberguesses.NoAnggota IS NOT NULL';
        $sql .= $andValue;
        $sql .= ' ORDER BY Periode ASC';
        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        $Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        // array_filter($Berdasarkan);
        $Berdasarkan = array_filter($Berdasarkan);
if (sizeof($Berdasarkan) !=1) {
        $Berdasarkan = implode(yii::t('app','dan'),$Berdasarkan);
}else{$Berdasarkan = implode($Berdasarkan);}
// die;

        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['sql'] = $sql; 
        $content['Berdasarkan'] =  $Berdasarkan; 
        $content_kop['kop'] = isset($_POST['kop']);

        if ($content_kop['kop']) {
            /*$header =  ['<img src="<?= Yii::getAlias('@uploaded_files/aplikasi/kop.png');?>" >'];*/
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
        $content = $this->renderPartial('pdf-view-khusus-anggota-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Data.pdf', 'D');

    }


public function actionAnggotaSeringBerkunjungData() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        IF (isset($_POST['periode'])) 
        {
            IF ($_POST['periode'] == "harian") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = "DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'),";
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            ELSEIF ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'MONTH(memberguesses.CreateDate),';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            ELSEIF ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'YEAR(memberguesses.CreateDate),';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            ELSE 
            {
                $periode = NULL;
            }
        }

        // var_dump($_POST['kataloger']);
       

            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2 FROM memberguesses WHERE DATE(memberguesses.CreateDate)
                    BETWEEN '2016-01-01' AND '2016-12-31' AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY test2 DESC) 
                    a ON a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY ".$group_by." memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY ".$group_by." memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
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

        // echo $sql;
        // die;

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
            'content' => $this->renderPartial('pdf-anggota-sering-berkunjung-data', $content),
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

public function actionExportExcelAnggotaSeringBerkunjungData()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            IF ($_POST['periode'] == "harian") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = "DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'),";
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            ELSEIF ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'MONTH(memberguesses.CreateDate),';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            ELSEIF ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'YEAR(memberguesses.CreateDate),';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            ELSE 
            {
                $periode = NULL;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;
   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT memberguesses.CreateDate AS Periode,
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2 FROM memberguesses WHERE DATE(memberguesses.CreateDate)
                    BETWEEN '2016-01-01' AND '2016-12-31' AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY test2 DESC) 
                    a ON a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT memberguesses.CreateDate AS Periode,
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT memberguesses.CreateDate AS Periode,
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY ".$group_by." memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
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
                <th colspan="7">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="7">'.yii::t('app','Anggota Sering Berkunjung').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="7">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Tujuan Kunjungan').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: middle;">'.$no.'</td>
                    <td style="vertical-align: middle;">'.$data['Periode'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_angg'].'</td>
                    <td style="vertical-align: middle;">'.$data['nama'].'</td>
                    <td style="vertical-align: middle;">'.$data['loc_perpus'].'</td>
                    <td style="vertical-align: middle;">'.$data['loc_ruang'].'</td>
                    <td style="vertical-align: middle;">'.$data['tuj_kunj'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportExcelOdtAnggotaSeringBerkunjungData()
{
    $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            IF ($_POST['periode'] == "harian") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = "DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'),";
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            ELSEIF ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'MONTH(memberguesses.CreateDate),';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            ELSEIF ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'YEAR(memberguesses.CreateDate),';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            ELSE 
            {
                $periode = NULL;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;

            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT memberguesses.CreateDate AS Periode,
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2 FROM memberguesses WHERE DATE(memberguesses.CreateDate)
                    BETWEEN '2016-01-01' AND '2016-12-31' AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY test2 DESC) 
                    a ON a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT memberguesses.CreateDate AS Periode,
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT memberguesses.CreateDate AS Periode,
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY ".$group_by." memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];     
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $headers = Yii::getAlias('@webroot','/teeeesst');

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'no_angg'=>$model['no_angg'], 'nama'=>$model['nama']
                        , 'loc_perpus'=>$model['loc_perpus'], 'loc_ruang'=>$model['loc_ruang'], 'tuj_kunj'=>$model['tuj_kunj']);
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'inValue'=>$inValue, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        );
    $detail2[] = array(
        'laporan_data'=> yii::t('app','Laporan Detail Data'),
        'anggota_seringberkunjung'=> yii::t('app','Anggota Sering Berkunjung'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal'=> yii::t('app','Tanggal'),
        'nomor_anggota'=> yii::t('app','Nomor Anggota'),
        'nama'=> yii::t('app','Nama'),
        'lokasi_perpustakaan'=> yii::t('app','Lokasi Perpustakaan'),
        'lokasi_ruang'=> yii::t('app','Lokasi Ruang'),
        'tujuan_kunjungan'=> yii::t('app','Tujuan Kunjungan'),
        );
// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS

    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/buku_tamu/laporan-buku_tamu-anggota-sering-berkunjung-data.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-buku_tamu-anggota-sering-Berkunjung-data.ods');
    // !Open Office Calc Area


}

public function actionExportWordAnggotaSeringBerkunjungData()
{
        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $subjek = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            IF ($_POST['periode'] == "harian") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = "DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'),";
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            ELSEIF ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'MONTH(memberguesses.CreateDate),';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            ELSEIF ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'YEAR(memberguesses.CreateDate),';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            ELSE 
            {
                $periode = NULL;
            }
        }

        // var_dump($_POST['kataloger']);
        // die;
   
            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT memberguesses.CreateDate AS Periode,
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2 FROM memberguesses WHERE DATE(memberguesses.CreateDate)
                    BETWEEN '2016-01-01' AND '2016-12-31' AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY test2 DESC) 
                    a ON a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT memberguesses.CreateDate AS Periode,
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT memberguesses.CreateDate AS Periode,
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY ".$group_by." memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];     
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    $type = $_GET['type'];
    $filename = 'Laporan_Detail_Data.'.$type;
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="7">'.yii::t('app','Laporan Detail Data').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="7">'.yii::t('app','Anggota Sering Berkunjung').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="7">'.yii::t('app','Berdasarkan Ranking').' '.$inValue.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <th>No.</th>
                <th>'.yii::t('app','Tanggal').'</th>
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Tujuan Kunjungan').'</th>
            </tr>
            ';
        $no = 1;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: middle;">'.$no.'</td>
                    <td style="vertical-align: middle;">'.$data['Periode'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_angg'].'</td>
                    <td style="vertical-align: middle;">'.$data['nama'].'</td>
                    <td style="vertical-align: middle;">'.$data['loc_perpus'].'</td>
                    <td style="vertical-align: middle;">'.$data['loc_ruang'].'</td>
                    <td style="vertical-align: middle;">'.$data['tuj_kunj'].'</td>
                </tr>
            ';
        $no++;
        endforeach;
        
    echo '</table>';

}

public function actionExportPdfAnggotaSeringBerkunjungData()
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        IF (isset($_POST['periode'])) 
        {
            IF ($_POST['periode'] == "harian") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = "DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'),";
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            ELSEIF ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'MONTH(memberguesses.CreateDate),';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            ELSEIF ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'memberguesses.CreateDate AS Periode';
                $group_by = 'YEAR(memberguesses.CreateDate),';
                $periode = yii::t('app','Tahunan');
                $periode2 = yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            ELSE 
            {
                $periode = NULL;
            }
        }

        // var_dump($_POST['kataloger']);
       

            $inValue = $_POST['rank'];
            if ($_POST['periode'] == "harian"){
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2 FROM memberguesses WHERE DATE(memberguesses.CreateDate)
                    BETWEEN '2016-01-01' AND '2016-12-31' AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY test2 DESC) 
                    a ON a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%d-%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            elseif ($_POST['periode'] == "bulanan") {
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";
            
            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT DATE_FORMAT(memberguesses.CreateDate,'%M-%Y') AS Periode, memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY ".$group_by." memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota AND a.Periode = DATE_FORMAT(memberguesses.CreateDate,'%M-%Y')
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
             $sql .= $data2['unt_limit'];
                }
            else{
            $sql2 = "SELECT IFNULL(SUM(a.test2),0) AS unt_limit FROM (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota
                    ORDER BY test2 DESC LIMIT ".$inValue.") a";

            $data2 = Yii::$app->db->createCommand($sql2)->queryOne(); 

            $sql = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS nama, 
                    a.test2 as cont_test2,
                    location_library.Name loc_perpus,
                    locations.Name AS loc_ruang,
                    tujuan_kunjungan.TujuanKunjungan AS tuj_kunj
                    FROM memberguesses
                    INNER JOIN (SELECT memberguesses.NoAnggota AS test1, COUNT(memberguesses.NoAnggota) AS test2
                    FROM memberguesses WHERE DATE(memberguesses.CreateDate) ".$sqlPeriode." AND memberguesses.NoAnggota IS NOT NULL GROUP BY ".$group_by." memberguesses.NoAnggota
                    ORDER BY test2 DESC) a  on a.test1 = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    WHERE DATE(memberguesses.CreateDate) ";      
             $sql .= $sqlPeriode;
             $sql .= " AND memberguesses.NoAnggota IS NOT NULL ORDER BY cont_test2 DESC, Nama ASC LIMIT ";
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

        // echo $sql;
        // die;

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
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            ]);

        $pdf = $pdf->api; // fetches mpdf api
        $content = $this->renderPartial('pdf-anggota-sering-berkunjung-data', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Detail_Data.pdf', 'D');

    }

// /////////////////////////////////batas view_data dgn view_vrekuensi//////////////////////////////////// //     
public function actionRenderKunjunganPeriodikFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';
        $tujuan = '';
          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $Group = 'Periode';
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $Group = 'YEAR(Periode), MONTH(Periode)';
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $Group = 'YEAR(Periode)';
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }

        $sql = "SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'non anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang,
                '' AS jum_anggota, 
                CONCAT(COUNT(memberguesses.ID),' (',queri.jum_angg,')') AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                GROUP BY lokasi_ruang, ".$Group."
                ) non_anggota 
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang, 
                CONCAT(COUNT(jenis_kelamin.`ID`),' (',queri.jum_angg,')') AS jum_anggota, 
                '' AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                GROUP BY lokasi_ruang, ".$Group."
                ) anggota 
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM (
                SELECT 'rombongan' AS ket, 
                DATE_FORMAT(groupguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(groupguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus,
                locations.Name AS lokasi_ruang, 
                '' AS jum_anggota, 
                '' AS jum_non_anggota, 
                CASE
                  WHEN ".$genderValue." = 1 THEN groupguesses.CountLaki
                  WHEN ".$genderValue." = 2 THEN groupguesses.CountPerempuan
                ELSE CONCAT(groupguesses.CountPersonel,' (',groupguesses.CountLaki,' Laki-laki',groupguesses.CountPerempuan,', Perempuan',' )') 
                END rombongan
                FROM groupguesses 
                JOIN jenis_kelamin
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                GROUP BY groupguesses.CreateDate
                ) rombongan ) test 
                WHERE Periode ";
        
        $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY Periode ASC';



        $data = Yii::$app->db->createCommand($sql)->queryAll(); 
// echo $sql;
// die;
        $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
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
            'marginLeft' =>0,
            'marginRight' =>0,
            'content' => $this->renderPartial('pdf-view-kunjungan-periodik-frekuensi', $content),
            'options' => [
                'title' => 'Laporan Frekuensi',
                'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [
                'SetHeader'=>$header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
        ]);
        return $pdf->render();
        
    }

public function actionExportExcelKunjunganPeriodikFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $tujuan = '';
        $sqlPeriode = '';
          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $Group = 'Periode';
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $Group = 'YEAR(Periode), MONTH(Periode)';
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $Group = 'YEAR(Periode)';
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }

        $sql = "SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'non anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang,
                '' AS jum_anggota, 
                CONCAT(COUNT(memberguesses.ID),' (',queri.jum_angg,')') AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                GROUP BY lokasi_ruang, ".$Group."
                ) non_anggota 
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang, 
                CONCAT(COUNT(jenis_kelamin.`ID`),' (',queri.jum_angg,')') AS jum_anggota, 
                '' AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                GROUP BY lokasi_ruang, ".$Group."
                ) anggota 
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM (
                SELECT 'rombongan' AS ket, 
                DATE_FORMAT(groupguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(groupguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus,
                locations.Name AS lokasi_ruang, 
                '' AS jum_anggota, 
                '' AS jum_non_anggota, 
                CASE
                  WHEN ".$genderValue." = 1 THEN groupguesses.CountLaki
                  WHEN ".$genderValue." = 2 THEN groupguesses.CountPerempuan
                ELSE CONCAT(groupguesses.CountPersonel,' (',groupguesses.CountLaki,' Laki-laki',groupguesses.CountPerempuan,', Perempuan',' )') 
                END rombongan
                FROM groupguesses 
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                GROUP BY groupguesses.CreateDate
                ) rombongan ) test 
                WHERE Periode ";

    $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY Periode ASC';

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


    $filename = 'Laporan_Periodik_Frekuensi.xls';
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="7">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="7">'.yii::t('app','Kunjungan Periodik').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="7">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Kunjungan').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Jumlah Anggota').'</th>
                <th>'.yii::t('app','Jumlah Non Anggota').'</th>
                <th>'.yii::t('app','Jumlah Rombongan (Personil)').'</th>
            </tr>
            ';
        $no = 1;
        $totalJumlahAnggota = 0;
        $totalJumlahNonAnggota = 0;
        $totalJumlahrombongan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['lokasi_perpus'].'</td>
                    <td>'.$data['lokasi_ruang'].'</td>
                    <td>'.$data['jum_anggota'].'</td>
                    <td>'.$data['jum_non_anggota'].'</td>
                    <td>'.$data['rombongan'].'</td>
                </tr>
            ';
                        $totalJumlahAnggota = $totalJumlahAnggota + $data['jum_anggota'];
                        $totalJumlahNonAnggota = $totalJumlahNonAnggota + $data['jum_non_anggota'];
                        $totalJumlahrombongan = $totalJumlahrombongan + $data['rombongan'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahAnggota.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahNonAnggota.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahrombongan.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtKunjunganPeriodikFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $tujuan = '';
        $sqlPeriode = '';
          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $Group = 'Periode';
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $Group = 'YEAR(Periode), MONTH(Periode)';
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $Group = 'YEAR(Periode)';
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }

        $sql = "SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'non anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang,
                '' AS jum_anggota, 
                CONCAT(COUNT(memberguesses.ID),' (',queri.jum_angg,')') AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                GROUP BY lokasi_ruang, ".$Group."
                ) non_anggota 
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang, 
                CONCAT(COUNT(jenis_kelamin.`ID`),' (',queri.jum_angg,')') AS jum_anggota, 
                '' AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                GROUP BY lokasi_ruang, ".$Group."
                ) anggota
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM (
                SELECT 'rombongan' AS ket, 
                DATE_FORMAT(groupguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(groupguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus,
                locations.Name AS lokasi_ruang, 
                '' AS jum_anggota, 
                '' AS jum_non_anggota, 
                CASE
                  WHEN ".$genderValue." = 1 THEN groupguesses.CountLaki
                  WHEN ".$genderValue." = 2 THEN groupguesses.CountPerempuan
                ELSE CONCAT(groupguesses.CountPersonel,' (',groupguesses.CountLaki,' Laki-laki',groupguesses.CountPerempuan,', Perempuan',' )') 
                END rombongan
                FROM groupguesses 
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                GROUP BY groupguesses.CreateDate
                ) rombongan ) test 
                WHERE Periode ";

        $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY Periode ASC';     

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

    
    $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'lokasi_perpus'=>$model['lokasi_perpus'], 'lokasi_ruang'=>$model['lokasi_ruang'], 'jum_anggota'=>$model['jum_anggota']
                         , 'jum_non_anggota'=>$model['jum_non_anggota'], 'rombongan'=>$model['rombongan'] );
        $Jumlahjum_anggota = $Jumlahjum_anggota + $model['jum_anggota'];
        $Jumlahjum_non_anggota = $Jumlahjum_non_anggota + $model['jum_non_anggota'];
        $Jumlahrombongan = $Jumlahrombongan + $model['rombongan'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'Totaljum_anggota'=>$Jumlahjum_anggota,
        'Totaljum_non_anggota'=>$Jumlahjum_non_anggota,
        'Totalrombongan'=>$Jumlahrombongan,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'kunjungan_periodik'=> yii::t('app','Kunjungan Periodik'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_kunjungan'=> yii::t('app','Tanggal Kunjungan'),
        'lokasi_perpustakaan'=> yii::t('app','Lokasi Perpustakaan'),
        'lokasi_ruang'=> yii::t('app','Lokasi Ruang'),
        'jumlah_anggota'=> yii::t('app','Jumlah Anggota'),
        'jumlah_nonanggota'=> yii::t('app','Jumlah Non Anggota'),
        'Jumlah_rombongan'=> yii::t('app','Jumlah Rombongan (Personil)'),
        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/buku_tamu/laporan-buku_tamu-kunjungan-periodik.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Kunjungan-Periodik-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordKunjunganPeriodikFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $tujuan = '';
        $sqlPeriode = '';
          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $Group = 'Periode';
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $Group = 'YEAR(Periode), MONTH(Periode)';
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $Group = 'YEAR(Periode)';
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }

        $sql = "SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'non anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang,
                '' AS jum_anggota, 
                CONCAT(COUNT(memberguesses.ID),' (',queri.jum_angg,')') AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                GROUP BY lokasi_ruang, ".$Group."
                ) non_anggota 
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang, 
                CONCAT(COUNT(jenis_kelamin.`ID`),' (',queri.jum_angg,')') AS jum_anggota, 
                '' AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                GROUP BY lokasi_ruang, ".$Group."
                ) anggota 
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM (
                SELECT 'rombongan' AS ket, 
                DATE_FORMAT(groupguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(groupguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus,
                locations.Name AS lokasi_ruang, 
                '' AS jum_anggota, 
                '' AS jum_non_anggota, 
                CASE
                  WHEN ".$genderValue." = 1 THEN groupguesses.CountLaki
                  WHEN ".$genderValue." = 2 THEN groupguesses.CountPerempuan
                ELSE CONCAT(groupguesses.CountPersonel,' (',groupguesses.CountLaki,' Laki-laki',groupguesses.CountPerempuan,', Perempuan',' )') 
                END rombongan
                FROM groupguesses 
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                GROUP BY groupguesses.CreateDate
                ) rombongan ) test 
                WHERE Periode ";

    $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY Periode ASC';

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


    $type = $_GET['type'];
    $filename = 'Laporan_Periodik_Frekuensi.'.$type;
    // header("Content-type: application/vnd-ms-word");
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center"> 
            <tr>
                <th colspan="7">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="7">'.yii::t('app','Kunjungan Periodik').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="7">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Kunjungan').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Jumlah Anggota').'</th>
                <th>'.yii::t('app','Jumlah Non Anggota').'</th>
                <th>'.yii::t('app','Jumlah Rombongan (Personil)').'</th>
            </tr>
            ';
        $no = 1;
        $totalJumlahAnggota = 0;
        $totalJumlahNonAnggota = 0;
        $totalJumlahrombongan = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['lokasi_perpus'].'</td>
                    <td>'.$data['lokasi_ruang'].'</td>
                    <td>'.$data['jum_anggota'].'</td>
                    <td>'.$data['jum_non_anggota'].'</td>
                    <td>'.$data['rombongan'].'</td>
                </tr>
            ';
                        $totalJumlahAnggota = $totalJumlahAnggota + $data['jum_anggota'];
                        $totalJumlahNonAnggota = $totalJumlahNonAnggota + $data['jum_non_anggota'];
                        $totalJumlahrombongan = $totalJumlahrombongan + $data['rombongan'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahAnggota.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahNonAnggota.'
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahrombongan.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfKunjunganPeriodikFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $tujuan = '';
        $sqlPeriode = '';
          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $Group = 'Periode';
                $periode_format = '%d-%M-%Y';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $Group = 'YEAR(Periode), MONTH(Periode)';
                $periode_format = '%M-%Y';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $Group = 'YEAR(Periode)';
                $periode_format = '%Y';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        $inValue = array();
        if (isset($_POST['anggota']) == true) {
                
                    $inValue[] =  '"anggota"';
                
            }    
        if (isset($_POST['non_anggota']) == true) {
                
                    $inValue[]=  '"non anggota"';
                
            }        
        if (isset($_POST['rombongan']) == true) {
                
                    $inValue[] =  '"rombongan"';
                
            }
        
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['ruang_perpus'])) {
                    $test = Locations::find()->where(['in', 'ID', $_POST['ruang_perpus']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['ruang_perpus'] = $groupValue;
                }else{$VALUE['ruang_perpus'] = array('Semua');}
            }
                if ($value != "0" ) {
                    $ruang = Locations::findOne(['ID' => $value]);
                    $andValue .= ' AND lokasi_ruang LIKE "'.$ruang->Name.'" ';
                }
        }

        if (isset($_POST['tujuan'])) {
            foreach ($_POST['tujuan'] as $key => $value) {
                if ($value != "0" ) {
                    $tujuan .= ' AND tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                    $tujuan_rombongan .= ' where tujuan_kunjungan.ID = "'.addslashes($value).'" ';
                }
            }
        }

        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                $lokasi = LocationLibrary::findOne(['ID' => $value]);
                $nilaiAndValue[] = " lokasi_perpus LIKE '%".$lokasi->Name."%'";
                $Value2[] .= $lokasi->Name;
                if (!in_array('0',$_POST['lokasi_perpus'])) {
                    $test = LocationLibrary::find()->where(['LIKE', 'Name', $lokasi->Name])->limit(1)->asArray()->All();
                     $groupValue = array();
                     $groupValue[$t] = implode("','",$Value2);
                    $VALUE['lokasi_perpus'] = $groupValue;
                    }else{$VALUE['lokasi_perpus'] = array('Semua');}
                }if ($value != "0" ) {$andValue .= ' AND '.implode('OR ',$nilaiAndValue);}
            }
            

        $genderValue = '0';
        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                $Value[] .= "'".$value."'";
                if (!in_array('0',$_POST['jenis_kelamin'])) {
                    $test = JenisKelamin::find()->where(['in', 'ID', $_POST['jenis_kelamin']])->asArray()->All();
                    $groupValue = array();
                        foreach ($test as $t => $tval) {
                            $groupValue[$t] = $tval['Name'];
                        }
                    $VALUE['jenis_kelamin'] = $groupValue;
                    }else{$VALUE['jenis_kelamin'] = array('Semua');}
            }
                if ($value != null ) {
                    $genderValue = addslashes($value);
                    if ($value != 0) {
                        $andGenderValue .= ' AND memberguesses.JenisKelamin_id = "'.addslashes($value).'" ';
                        $andGenderValue2 .= ' AND members.Sex_id = "'.addslashes($value).'" ';
                    }
                }
        }

        $sql = "SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'non anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang,
                '' AS jum_anggota, 
                CONCAT(COUNT(memberguesses.ID),' (',queri.jum_angg,')') AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = memberguesses.JenisKelamin_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoPengunjung IS NOT NULL ".$tujuan.$andGenderValue."
                GROUP BY lokasi_ruang, ".$Group."
                ) non_anggota 
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM ( 
                SELECT 'anggota' AS ket, 
                DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(memberguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus, 
                locations.Name AS lokasi_ruang, 
                CONCAT(COUNT(jenis_kelamin.`ID`),' (',queri.jum_angg,')') AS jum_anggota, 
                '' AS jum_non_anggota, 
                '' AS rombongan 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                INNER JOIN (
                    SELECT
                    lokasi_ruang,
                    Periode,
                    Periodes,
                    GROUP_CONCAT(DISTINCT temp_table.jum_anggota SEPARATOR ',') AS jum_angg
                    FROM
                    (
                    SELECT 'anggota' AS ket, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                    DATE_FORMAT(memberguesses.CreateDate,'%Y') AS Periodes, 
                    location_library.Name AS lokasi_perpus, 
                    locations.`ID` AS lokasi_ruang, 
                    CONCAT(COUNT(jenis_kelamin.`ID`),' ',jenis_kelamin.`Name`) AS jum_anggota, 
                    '' AS jum_non_anggota, 
                    '' AS rombongan 
                    FROM memberguesses 
                    LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota
                    LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                    LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                    LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = memberguesses.TujuanKunjungan_Id
                    LEFT JOIN jenis_kelamin ON jenis_kelamin.ID = members.Sex_id 
                    LEFT JOIN master_pekerjaan ON master_pekerjaan.id = members.Job_id 
                    LEFT JOIN master_pendidikan ON master_pendidikan.id = members.EducationLevel_id
                    WHERE DATE_FORMAT(memberguesses.CreateDate,'%Y-%m-%d') ". $sqlPeriode ." AND memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                    GROUP BY lokasi_ruang, ".$Group.",jenis_kelamin.`ID`
                    ) temp_table 
                    GROUP BY YEAR(Periode),lokasi_ruang
                ) queri ON queri.Periodes = DATE_FORMAT(memberguesses.CreateDate,'%Y') AND queri.lokasi_ruang = locations.ID
                WHERE memberguesses.NoAnggota IS NOT NULL AND members.MemberNo IS NOT NULL ".$tujuan.$andGenderValue2."
                GROUP BY lokasi_ruang, ".$Group."
                ) anggota 
                UNION ALL 
                SELECT ket, Periode, Periodes, lokasi_perpus, lokasi_ruang, jum_anggota, jum_non_anggota, rombongan 
                FROM (
                SELECT 'rombongan' AS ket, 
                DATE_FORMAT(groupguesses.CreateDate,'%Y-%m-%d') AS Periode, 
                DATE_FORMAT(groupguesses.CreateDate,'".$periode_format."') AS Periodes, 
                location_library.Name AS lokasi_perpus,
                locations.Name AS lokasi_ruang, 
                '' AS jum_anggota, 
                '' AS jum_non_anggota, 
                CASE
                  WHEN ".$genderValue." = 1 THEN groupguesses.CountLaki
                  WHEN ".$genderValue." = 2 THEN groupguesses.CountPerempuan
                ELSE CONCAT(groupguesses.CountPersonel,' (',groupguesses.CountLaki,' Laki-laki',groupguesses.CountPerempuan,', Perempuan',' )') 
                END rombongan
                FROM groupguesses 
                LEFT JOIN locations ON locations.ID = groupguesses.Location_Id
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id
                LEFT JOIN tujuan_kunjungan ON tujuan_kunjungan.ID = groupguesses.TujuanKunjungan_Id
                ".$tujuan_rombongan."
                GROUP BY groupguesses.CreateDate
                ) rombongan ) test 
                WHERE Periode ";
        
        $sql .= $sqlPeriode;
        $inValue = implode(',', $inValue);
        if($inValue != ''){
        $sql .= 'AND ket IN ('.$inValue.')';
        }
        $sql .= $andValue;
        $sql .= ' ORDER BY Periode ASC';



        $data = Yii::$app->db->createCommand($sql)->queryAll(); 
// echo $sql;
// die;
        $Berdasarkan = array();
        foreach ($VALUE as $key => $value) {
            $Berdasarkan[] .= $this->getRealNameKriteria($key).' (\''.implode(yii::t('app',' , '), $value).'\')';
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
        $content = $this->renderPartial('pdf-view-kunjungan-periodik-frekuensi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');
        
}

public function actionRenderKhususAnggotaFrekuensi() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $join = '';
        $andValue = '';
        $sqlPeriode = '';
      //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND memberguesses.JenisKelamin_id  = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_kelamin ON jenis_kelamin.Name = memberguesses.JenisKelamin_id ';
        } 
        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['program_studi'])) {
            foreach ($_POST['program_studi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_program_studi.id = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_program_studi on master_program_studi.id = members.ProgramStudi_id ';
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_anggota on jenis_anggota.id = members.JenisAnggota_id ';
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pekerjaan on master_pekerjaan.id = members.Job_id ';
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pendidikan on master_pendidikan.id = members.EducationLevel_id ';
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN kelas_siswa on kelas_siswa.id = members.Kelas_id ';
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_fakultas on master_fakultas.id = members.Fakultas_id ';
        }
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_jurusan on master_jurusan.id = members.Jurusan_id ';
        }
        $sql = "SELECT ".$periode_format.",
                CASE
                 WHEN memberguesses.NoAnggota IS NOT NULL THEN COUNT(memberguesses.NoAnggota) 
                 ELSE NULL
                END AS Jumlah,
                locations.Name AS Ruang, 
                location_library.Name AS Nama_perpus 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                ".$join." ";      
        $sql .= " WHERE memberguesses.CreateDate ";
        $sql .= $sqlPeriode;
        $sql .= " and memberguesses.NoAnggota IS NOT NULL ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } else {
                    $sql .= " GROUP BY YEAR(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                }


        $data = Yii::$app->db->createCommand($sql)->queryAll(); 
        // $Berdasarkan = array();
        // foreach ($_POST['kriterias'] as $key => $value) {
        //     $Berdasarkan[] .= '' .$this->getRealNameKriteria($value).'';
        // }
        // $Berdasarkan = implode(' dan ', $Berdasarkan);

        // if(sizeof($_POST['lokasi_perpus']) != 0 || sizeof($_POST['ruang_perpus']) != 0) 
        // {$Berdasarkan = array();
        // foreach ($_POST['kriterias'] as $key => $value) {
        //     $Berdasarkan[] .= $this->getRealNameKriteria($value).' ';
        // }
        // $Berdasarkan = implode(' dan ', $Berdasarkan);} 
        // else
        // {$Berdasarkan = array();
        // foreach ($_POST['kriterias'] as $key => $value) {
        //     $Berdasarkan[] .= $this->getRealNameKriteria($value).' ';
        // }
        // $Berdasarkan = implode($Berdasarkan);}

        // print_r(sizeof(array_filter($_POST['kriterias'])));
        if(sizeof(array_filter($_POST['kriterias'])) > 1 ) 
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);} 
        else
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode($Berdasarkan);}
        // print_r($Berdasarkan);
        // die;

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
            'content' => $this->renderPartial('pdf-view-khusus-anggota-frekuensi', $content),
            'options' => [
            'title' => 'Laporan Data',
            'subject' => 'Perpustakaan Nasional Republik Indonesia'],
            'methods' => [ 
                'SetHeader'=>$header,
                'SetFooter'=>['<div class="footer" style="margin-right:60px;">Page {PAGENO}</div>'],
            ],
            ]);
        return $pdf->render();

    }

public function actionExportExcelKhususAnggotaFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $join = '';
        $andValue = '';
        $sqlPeriode = '';
      //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND memberguesses.JenisKelamin_id  = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_kelamin ON jenis_kelamin.Name = memberguesses.JenisKelamin_id ';
        } 
        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['program_studi'])) {
            foreach ($_POST['program_studi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_program_studi.id = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_program_studi on master_program_studi.id = members.ProgramStudi_id ';
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_anggota on jenis_anggota.id = members.JenisAnggota_id ';
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pekerjaan on master_pekerjaan.id = members.Job_id ';
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pendidikan on master_pendidikan.id = members.EducationLevel_id ';
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN kelas_siswa on kelas_siswa.id = members.Kelas_id ';
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_fakultas on master_fakultas.id = members.Fakultas_id ';
        }
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_jurusan on master_jurusan.id = members.Jurusan_id ';
        }
        $sql = "SELECT ".$periode_format.",
                CASE
                 WHEN memberguesses.NoAnggota IS NOT NULL THEN COUNT(memberguesses.NoAnggota) 
                 ELSE NULL
                END AS Jumlah,
                locations.Name AS Ruang, 
                location_library.Name AS Nama_perpus 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                ".$join." ";       
        $sql .= " WHERE memberguesses.CreateDate ";
        $sql .= $sqlPeriode;
        $sql .= " and memberguesses.NoAnggota IS NOT NULL ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } else {
                    $sql .= " GROUP BY YEAR(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $periode2 = $periode2;
    $format_hari = $periode;
    if(sizeof(array_filter($_POST['kriterias'])) > 1 ) 
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);} 
        else
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode($Berdasarkan);}

$headers = Yii::getAlias('@webroot','/teeeesst');


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
                <th colspan="5">'.yii::t('app','Kunjungan Member Periodik').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Kunjungan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Jumlah Anggota').'</th>
            </tr>
            ';
        $no = 1;
        $totalJumlahAnggota = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Ruang'].'</td>
                    <td>'.$data['Nama_perpus'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $totalJumlahAnggota = $totalJumlahAnggota + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahAnggota.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtKhususAnggotaFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $join = '';
        $andValue = '';
        $sqlPeriode = '';
      //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND memberguesses.JenisKelamin_id  = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_kelamin ON jenis_kelamin.Name = memberguesses.JenisKelamin_id ';
        } 
        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['program_studi'])) {
            foreach ($_POST['program_studi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_program_studi.id = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_program_studi on master_program_studi.id = members.ProgramStudi_id ';
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_anggota on jenis_anggota.id = members.JenisAnggota_id ';
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pekerjaan on master_pekerjaan.id = members.Job_id ';
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pendidikan on master_pendidikan.id = members.EducationLevel_id ';
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN kelas_siswa on kelas_siswa.id = members.Kelas_id ';
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_fakultas on master_fakultas.id = members.Fakultas_id ';
        }
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_jurusan on master_jurusan.id = members.Jurusan_id ';
        }
        $sql = "SELECT ".$periode_format.",
                CASE
                 WHEN memberguesses.NoAnggota IS NOT NULL THEN COUNT(memberguesses.NoAnggota) 
                 ELSE NULL
                END AS Jumlah,
                locations.Name AS Ruang, 
                location_library.Name AS Nama_perpus 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                ".$join." ";       
        $sql .= " WHERE memberguesses.CreateDate ";
        $sql .= $sqlPeriode;
        $sql .= " and memberguesses.NoAnggota IS NOT NULL ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } else {
                    $sql .= " GROUP BY YEAR(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = $inValue;
    if(sizeof(array_filter($_POST['kriterias'])) > 1 ) 
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);} 
        else
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode($Berdasarkan);}

    $headers = Yii::getAlias('@webroot','/teeeesst');


    // Open Office Calc Area

    $no = 1;
    $data = array();
    foreach($model as $model):
        $data[] = array('no'=> $no++,'Periode'=> $model['Periode'], 'Ruang'=>$model['Ruang'], 'Nama_perpus'=>$model['Nama_perpus'], 'Jumlah'=>$model['Jumlah'] );
        $Jumlah = $Jumlah + $model['Jumlah'];
    endforeach;

    $detail[] = array(
        'menu'=>$menu, 
        'Berdasarkan'=>$Berdasarkan, 
        'format_hari'=>$format_hari, 
        'periode2'=>$periode2,
        'Berdasarkan'=>$Berdasarkan,
        'TotalJumlah'=>$Jumlah,
        );
    $detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'kunjungan_memberperiodik'=> yii::t('app','Kunjungan Member Periodik'),
        'berdasarkan'=> yii::t('app','Berdasarkan'),
        'tanggal_kunjungan'=> yii::t('app','Tanggal Kunjungan'),
        'lokasi_ruang'=> yii::t('app','Lokasi Ruang'),
        'lokasi_perpustakaan'=> yii::t('app','Lokasi Perpustakaan'),
        'jumlah_anggota'=> yii::t('app','Jumlah Anggota'),
        
        );

// print_r(sizeof($_POST['kriterias']));
// die;

    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
    $template = Yii::getAlias('@uploaded_files').'/templates/laporan/buku_tamu/laporan-buku_tamu-kunjungan-khusus-anggota.ods'; 


    // $OpenTBS->LoadTemplate($template);
    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 

    $OpenTBS->MergeBlock('a,b', $data);
    $OpenTBS->MergeBlock('detail', $detail);
    $OpenTBS->MergeBlock('detail2', $detail2);

    $OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Baca-ditempat-frekuensi.ods');
    // !Open Office Calc Area


}

public function actionExportWordKhususAnggotaFrekuensi()
{
    // $model = Opaclogs::find()->All();

        $_POST =  $_SESSION['Array_POST_Filter'];
        $join = '';
        $andValue = '';
        $sqlPeriode = '';
      //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND memberguesses.JenisKelamin_id  = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_kelamin ON jenis_kelamin.Name = memberguesses.JenisKelamin_id ';
        } 
        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['program_studi'])) {
            foreach ($_POST['program_studi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_program_studi.id = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_program_studi on master_program_studi.id = members.ProgramStudi_id ';
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_anggota on jenis_anggota.id = members.JenisAnggota_id ';
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pekerjaan on master_pekerjaan.id = members.Job_id ';
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pendidikan on master_pendidikan.id = members.EducationLevel_id ';
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN kelas_siswa on kelas_siswa.id = members.Kelas_id ';
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_fakultas on master_fakultas.id = members.Fakultas_id ';
        }
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_jurusan on master_jurusan.id = members.Jurusan_id ';
        }
        $sql = "SELECT ".$periode_format.",
                CASE
                 WHEN memberguesses.NoAnggota IS NOT NULL THEN COUNT(memberguesses.NoAnggota) 
                 ELSE NULL
                END AS Jumlah,
                locations.Name AS Ruang, 
                location_library.Name AS Nama_perpus 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                ".$join." ";       
        $sql .= " WHERE memberguesses.CreateDate ";
        $sql .= $sqlPeriode;
        $sql .= " and memberguesses.NoAnggota IS NOT NULL ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } else {
                    $sql .= " GROUP BY YEAR(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                }

    $model = Yii::$app->db->createCommand($sql)->queryAll(); 

    $periode2 = $periode2;
    $format_hari = $periode;
    if(sizeof(array_filter($_POST['kriterias'])) > 1 ) 
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);} 
        else
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode($Berdasarkan);}

$headers = Yii::getAlias('@webroot','/teeeesst');

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
                <th colspan="5">'.yii::t('app','Kunjungan Member Periodik').' '.$periode2.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Berdasarkan').' '.$Berdasarkan.'</th>
            </tr>
            <tr>
            </tr>
            ';
    echo '<table border="1" align="center">
            <tr>
                <td>No.</td>
                <th>'.yii::t('app','Tanggal Kunjungan').'</th>
                <th>'.yii::t('app','Lokasi Ruang').'</th>
                <th>'.yii::t('app','Lokasi Perpustakaan').'</th>
                <th>'.yii::t('app','Jumlah Anggota').'</th>
            </tr>
            ';
        $no = 1;
        $totalJumlahAnggota = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td>'.$no.'</td>
                    <td>'.$data['Periode'].'</td>
                    <td>'.$data['Ruang'].'</td>
                    <td>'.$data['Nama_perpus'].'</td>
                    <td>'.$data['Jumlah'].'</td>
                </tr>
            ';
                        $totalJumlahAnggota = $totalJumlahAnggota + $data['Jumlah'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahAnggota.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfKhususAnggotaFrekuensi() 
    {
        $_POST =  $_SESSION['Array_POST_Filter'];
        $join = '';
        $andValue = '';
        $sqlPeriode = '';
      //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") Periode';
                $periode = yii::t('app','Harian');
                $periode2= yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") Periode';
                $periode = yii::t('app','Bulanan');
                $periode2= yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") Periode';
                $periode = yii::t('app','Tahunan');
                $periode2= yii::t('app','Periode').' '.$_POST['fromTahunan'].' s/d '.$_POST['toTahunan'];
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,1,1,$_POST['fromTahunan']))."' AND '".date("Y-m-d", mktime(0,0,0,12,31,$_POST['toTahunan']))."' ";
            }
            else 
            {
                $periode = null;
            }
        }

        if (isset($_POST['jenis_kelamin'])) {
            foreach ($_POST['jenis_kelamin'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND memberguesses.JenisKelamin_id  = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_kelamin ON jenis_kelamin.Name = memberguesses.JenisKelamin_id ';
        } 
        if (isset($_POST['propinsi'])) {
            foreach ($_POST['propinsi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.Province  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['propinsi2'])) {
            foreach ($_POST['propinsi2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ProvinceNow  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['lokasi_pinjam'])) {
            foreach ($_POST['lokasi_pinjam'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['jenis_identitas'])) {
            foreach ($_POST['jenis_identitas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jenis_identitas.id  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kecamatan'])) {
            foreach ($_POST['kecamatan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kecamatan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kecamatan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kelurahan'])) {
            foreach ($_POST['kelurahan'] as $key => $value) {
                if ($value == "" ) {
                            $andValue .= '';
                            }
                    else if ($value == "XI") {
                                $andValue .= ' AND members.Kelurahan IS NULL ';
                            }
                    else {
                        $andValue .= " AND members.Kelurahan = '".addslashes($value)."' ";
                    }
            }
        }
        if (isset($_POST['kabupaten'])) {
            foreach ($_POST['kabupaten'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.City  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['kabupaten2'])) {
            foreach ($_POST['kabupaten2'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.CityNow  = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['range_umur'])) {
            foreach ($_POST['range_umur'] as $key => $value) {
                if ($value != "0" ) {
                    $umur = MasterRangeUmur::findOne(['id' => $value]);
                    $andValue .= ' AND (YEAR(CURDATE())-YEAR(members.DateOfBirth))  BETWEEN "'.$umur->umur1.'" AND "'.$umur->umur2.'" ';
                }
            }
        }
        if (isset($_POST['data_entry'])) {
            foreach ($_POST['data_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) >= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        } 
        if (isset($_POST['todata_entry'])) {
            foreach ($_POST['todata_entry'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND DATE(member_perpanjangan.CreateDate) <= "'.date("Y-m-d", strtotime( $value ) ).'" ';
                }
            }
        }
        if (isset($_POST['nama_institusi'])) {
            foreach ($_POST['nama_institusi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.InstitutionName  = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['nomer_anggota'])) {
            foreach ($_POST['nomer_anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND members.ID = "'.addslashes($value).'" ';
                }
            }
        } 
        if (isset($_POST['program_studi'])) {
            foreach ($_POST['program_studi'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_program_studi.id = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_program_studi on master_program_studi.id = members.ProgramStudi_id ';
        } 
        if (isset($_POST['Jenis_Anggota'])) {
            foreach ($_POST['Jenis_Anggota'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND jenis_anggota.jenisanggota = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN jenis_anggota on jenis_anggota.id = members.JenisAnggota_id ';
        } 
        if (isset($_POST['Pekerjaan'])) {
            foreach ($_POST['Pekerjaan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pekerjaan.Pekerjaan = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pekerjaan on master_pekerjaan.id = members.Job_id ';
        }
        if (isset($_POST['Pendidikan'])) {
            foreach ($_POST['Pendidikan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_pendidikan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_pendidikan on master_pendidikan.id = members.EducationLevel_id ';
        }
        if (isset($_POST['Kelas'])) {
            foreach ($_POST['Kelas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND kelas_siswa.namakelassiswa = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN kelas_siswa on kelas_siswa.id = members.Kelas_id ';
        }
        if (isset($_POST['Fakultas'])) {
            foreach ($_POST['Fakultas'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_fakultas.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_fakultas on master_fakultas.id = members.Fakultas_id ';
        }
        if (isset($_POST['ruang_perpus'])) {
            foreach ($_POST['ruang_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND locations.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['lokasi_perpus'])) {
            foreach ($_POST['lokasi_perpus'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND location_library.ID = "'.addslashes($value).'" ';
                }
            }
        }
        if (isset($_POST['Jurusan'])) {
            foreach ($_POST['Jurusan'] as $key => $value) {
                if ($value != "0" ) {
                    $andValue .= ' AND master_jurusan.Nama = "'.addslashes($value).'" ';
                }
            }
        $join .= ' LEFT JOIN master_jurusan on master_jurusan.id = members.Jurusan_id ';
        }
        $sql = "SELECT ".$periode_format.",
                CASE
                 WHEN memberguesses.NoAnggota IS NOT NULL THEN COUNT(memberguesses.NoAnggota) 
                 ELSE NULL
                END AS Jumlah,
                locations.Name AS Ruang, 
                location_library.Name AS Nama_perpus 
                FROM memberguesses 
                LEFT JOIN members ON members.MemberNo = memberguesses.NoAnggota 
                LEFT JOIN locations ON locations.ID = memberguesses.Location_Id 
                LEFT JOIN location_library ON location_library.ID = locations.LocationLibrary_id 
                ".$join." ";       
        $sql .= " WHERE memberguesses.CreateDate ";
        $sql .= $sqlPeriode;
        $sql .= " and memberguesses.NoAnggota IS NOT NULL ";
        $sql .= $andValue;
        if ($_POST['periode'] == "harian"){
                    $sql .= " GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } elseif ($_POST['periode'] == "bulanan") {
                    $sql .= " GROUP BY MONTH(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                } else {
                    $sql .= " GROUP BY YEAR(memberguesses.CreateDate), Nama_perpus, Ruang ORDER BY Jumlah DESC";
                }


        $data = Yii::$app->db->createCommand($sql)->queryAll(); 

        if(sizeof(array_filter($_POST['kriterias'])) > 1 ) 
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode(yii::t('app',' dan '), $Berdasarkan);} 
        else
        {$Berdasarkan = array();
        foreach ($_POST['kriterias'] as $key => $value) {
            $Berdasarkan[] .= yii::t('app',$this->getRealNameKriteria($value)).' ';
        }
        $Berdasarkan = implode($Berdasarkan);}

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
        $content = $this->renderPartial('pdf-view-khusus-anggota-frekuensi', $content);
        if ($content_kop['kop']) {
        $pdf->SetHTMLHeader('<img src="'.Yii::$app->urlManager->createUrl("../uploaded_files/aplikasi/kop.png").'" style="margin-top: -30px; height: 180; width: 100%;" >');
        }else{
        $pdf->SetHTMLHeader();
        }
        $pdf->SetHTMLFooter('<div class="footer" style="position: relative; float: left;">Pages {PAGENO}</div>');
        $pdf->WriteHtml($content);
        echo $pdf->Output('Laporan_Periodik_Frekuensi.pdf', 'D');

}


public function actionAnggotaSeringBerkunjungFrekuensi() 
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        IF (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") AS Periode';
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

            $SQL = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS Nama, 
                    COUNT(memberguesses.NoAnggota) AS jum_kunj
                    FROM memberguesses
                    WHERE DATE(memberguesses.CreateDate) ";      
             $SQL .= $sqlPeriode;
             $SQL .= $andValue;
             IF ($_POST['periode'] == "harian"){
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSEIF ($_POST['periode'] == "bulanan") {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSE {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota  ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                }
             $SQL .= $inValue;        


        $data = Yii::$app->db->createCommand($SQL)->queryAll(); 


        $content['LaporanKriteria'] = ""; 
        $content['LaporanSubJudulKriteriaVal'] = '$kriteriaVal'; 
        $content['TableLaporan'] = $data; 
        $content['LaporanPeriode'] = $periode;
        $content['LaporanPeriode2'] = $periode2;
        $content['SQL'] = $SQL; 
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
            'content' => $this->renderPartial('pdf-anggota-sering-berkunjung-frekuensi', $content),
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

public function actionExportExcelAnggotaSeringBerkunjungFrekuensi()
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
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") AS Periode';
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

            $SQL = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS Nama, 
                    COUNT(memberguesses.NoAnggota) AS jum_kunj
                    FROM memberguesses
                    WHERE DATE(memberguesses.CreateDate) ";      
             $SQL .= $sqlPeriode;
             $SQL .= $andValue;
             IF ($_POST['periode'] == "harian"){
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSEIF ($_POST['periode'] == "bulanan") {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSE {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota  ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                }
             $SQL .= $inValue;        

    $model = Yii::$app->db->createCommand($SQL)->queryAll(); 
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
                <th colspan="5">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Anggota Sering Berkunjung').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Jumlah Kunjungan').'</th>
            </tr>
            ';
        $no = 1;
        $totalJumlahExemplar = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: middle;">'.$no.'</td>
                    <td style="vertical-align: middle;">'.$data['Periode'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_angg'].'</td>
                    <td style="vertical-align: middle;">'.$data['Nama'].'</td>
                    <td style="vertical-align: middle;">'.$data['jum_kunj'].'</td>
                </tr>
            ';
                        $totalJumlahExemplar = $totalJumlahExemplar + $data['jum_kunj'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahExemplar.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportExcelOdtAnggotaSeringBerkunjungFrekuensi()
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
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") AS Periode';
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

            $SQL = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS Nama, 
                    COUNT(memberguesses.NoAnggota) AS jum_kunj
                    FROM memberguesses
                    WHERE DATE(memberguesses.CreateDate) ";      
             $SQL .= $sqlPeriode;
             $SQL .= $andValue;
             IF ($_POST['periode'] == "harian"){
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSEIF ($_POST['periode'] == "bulanan") {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSE {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota  ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                }
             $SQL .= $inValue;        

    $model = Yii::$app->db->createCommand($SQL)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;
    $Berdasarkan = $inValue;

$headers = Yii::getAlias('@webroot','/teeeesst');

$no = 1;
$data = array();
foreach($model as $model):
    $data[] = array('no' => $model['no'], 'Periode' => $model['Periode'], 'no_angg' => $model['no_angg'], 'Nama' => $model['Nama'], 'jum_kunj' => $model['jum_kunj']);
    $jumlah_kunjungan = $jumlah_kunjungan + $model['jum_kunj'];
endforeach;


$detail[] = array(
    'Berdasarkan' => $Berdasarkan,
    'periode2' => $periode2,
    'format_hari' => $format_hari,
    'totalJumlahKunjungan' => $jumlah_kunjungan,
    'sql' => $sql,
    );
$detail2[] = array(
        'laporan_frekuensi'=> yii::t('app','Laporan Frekuensi'),
        'anggota_seringberkunjung'=> yii::t('app','Anggota Sering Berkunjung'),
        'berdasarkan_ranking'=> yii::t('app','Berdasarkan Ranking'),
        'tanggal'=> yii::t('app','Tanggal'),
        'nomor_anggota'=> yii::t('app','Nomor Anggota'),
        'nama'=> yii::t('app','Nama'),
        'jumlah_kunjungan'=> yii::t('app','Jumlah Kunjungan'),
        );

$OpenTBS = new \hscstudio\export\OpenTBS;
$templete = yii::getAlias('@uploaded_files').'/templates/laporan/buku_tamu/laporan-buku_tamu-anggota-sering-berkunjung-frekuensi.ods';

$OpenTBS->LoadTemplate($templete, OPENTBS_ALREADY_UTF8);

$OpenTBS->MergeBlock('a,b', $data);
$OpenTBS->MergeBlock('detail', $detail);
$OpenTBS->MergeBlock('detail2', $detail2);

$OpenTBS->Show(OPENTBS_DOWNLOAD, 'laporan-Baca-Buku-Tamu.ods');

}

public function actionExportWordAnggotaSeringBerkunjungFrekuensi()
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
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") AS Periode';
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

            $SQL = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS Nama, 
                    COUNT(memberguesses.NoAnggota) AS jum_kunj
                    FROM memberguesses
                    WHERE DATE(memberguesses.CreateDate) ";      
             $SQL .= $sqlPeriode;
             $SQL .= $andValue;
             IF ($_POST['periode'] == "harian"){
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSEIF ($_POST['periode'] == "bulanan") {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSE {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota  ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                }
             $SQL .= $inValue;        

    $model = Yii::$app->db->createCommand($SQL)->queryAll(); 
    $periode2 = $periode2;
    $format_hari = $periode;

$headers = Yii::getAlias('@webroot','/teeeesst');
// $headers = Yii::$app->urlManager->createUrl('@app',"../uploaded_files/aplikasi/kop.png");
// print_r($headers);
// die;


    $type = $_GET['type'];
    $filename = 'Laporan Buku Tamu Frekuensi.'.$type;
    header("Content-Disposition: attachment; filename =".$filename."");
    header("Pragma: no-cahce");
    header("Expires: 0");
    echo '<table border="0" align="center" width="700"> 
            <tr>
                <th colspan="5">'.yii::t('app','Laporan Frekuensi').' '.$format_hari.'</th>
            </tr>
            <tr>
                <th colspan="5">'.yii::t('app','Anggota Sering Berkunjung').' '.$periode2.'</th>
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
                <th>'.yii::t('app','Nomor Anggota').'</th>
                <th>'.yii::t('app','Nama').'</th>
                <th>'.yii::t('app','Jumlah Kunjungan').'</th>
            </tr>
            ';
        $no = 1;
        $totalJumlahExemplar = 0;
        foreach($model as $data):
            echo '
                <tr align="center">
                    <td style="vertical-align: middle;">'.$no.'</td>
                    <td style="vertical-align: middle;">'.$data['Periode'].'</td>
                    <td style="vertical-align: middle;">'.$data['no_angg'].'</td>
                    <td style="vertical-align: middle;">'.$data['Nama'].'</td>
                    <td style="vertical-align: middle;">'.$data['jum_kunj'].'</td>
                </tr>
            ';
                        $totalJumlahExemplar = $totalJumlahExemplar + $data['jum_kunj'];
                        $no++;
                    endforeach;
                echo '
                    <tr align="center">
                        <td colspan="4" style="font-weight: bold;">
                            Total
                        </td>
                        <td style="font-weight: bold;">
                            '.$totalJumlahExemplar.'
                        </td>
                    </tr>
                    ';
        
    echo '</table>';

}

public function actionExportPdfAnggotaSeringBerkunjungFrekuensi()
    {

        $_POST =  $_SESSION['Array_POST_Filter'];
        $andValue = '';
        $sqlPeriode = '';

          //Untuk Header Laporan berdasarkan Periode yng dipilih
        if (isset($_POST['periode'])) 
        {
            if ($_POST['periode'] == "harian") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%d-%M-%Y") AS Periode';
                $periode = yii::t('app','Harian');
                $periode2 = yii::t('app','Periode').' '.$_POST['from_date'].' s/d '.$_POST['to_date'];

                $sqlPeriode = "BETWEEN '".date("Y-m-d", strtotime($_POST['from_date']) )."' AND '".date("Y-m-d", strtotime($_POST['to_date']) )."' ";
            } 
            elseif ($_POST['periode'] == "bulanan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%M-%Y") AS Periode';
                $periode = yii::t('app','Bulanan');
                $periode2 = yii::t('app','Periode').' '.date("M", mktime(0, 0, 0, $_POST['fromBulan'], 10)).'-'.$_POST['fromTahun'].' s/d '.date("M", mktime(0, 0, 0, $_POST['toBulan'], 10)).'-'.$_POST['toTahun'];
                
                $sqlPeriode = "BETWEEN '".date("Y-m-d", mktime(0,0,0,$_POST['fromBulan'],1,$_POST['fromTahun']))."' AND '".date("Y-m-t", mktime(0,0,0,$_POST['toBulan'],1,$_POST['toTahun']))."' ";
            } 
            elseif ($_POST['periode'] == "tahunan") 
            {
                $periode_format = 'DATE_FORMAT(memberguesses.CreateDate,"%Y") AS Periode';
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

            $SQL = "SELECT ".$periode_format.",
                    memberguesses.NoAnggota AS no_angg, 
                    memberguesses.Nama AS Nama, 
                    COUNT(memberguesses.NoAnggota) AS jum_kunj
                    FROM memberguesses
                    WHERE DATE(memberguesses.CreateDate) ";      
             $SQL .= $sqlPeriode;
             $SQL .= $andValue;
             IF ($_POST['periode'] == "harian"){
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY DATE_FORMAT(memberguesses.CreateDate,'%d-%m-%Y'), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSEIF ($_POST['periode'] == "bulanan") {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY MONTH(memberguesses.CreateDate), memberguesses.NoAnggota ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                } ELSE {
                    $SQL .= " AND memberguesses.NoAnggota IS NOT NULL GROUP BY YEAR(memberguesses.CreateDate), memberguesses.NoAnggota  ORDER BY jum_kunj DESC, Nama ASC LIMIT ";
                }
             $SQL .= $inValue;        


        $data = Yii::$app->db->createCommand($SQL)->queryAll(); 

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
        $content = $this->renderPartial('pdf-anggota-sering-berkunjung-frekuensi', $content);
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
        elseif ($kriterias == 'Pekerjaan') 
        {
            $name = 'Pekerjaan';
        } 
        elseif ($kriterias == 'kecamatan') 
        {
            $name = 'Kecamatan';
        }
        elseif ($kriterias == 'kelurahan') 
        {
            $name = 'Kelurahan';
        } 
        elseif ($kriterias == 'Pendidikan') 
        {
            $name = 'Pendidikan';
        }
        elseif ($kriterias == 'Status_Anggota') 
        {
            $name = 'Status Anggota';
        }
        elseif ($kriterias == 'Jenis_Anggota') 
        {
            $name = 'Jenis Anggota';
        }
        elseif ($kriterias == 'nomer_anggota') 
        {
            $name = 'Nomer Anggota';
        }
        elseif ($kriterias == 'jenis_kelamin') 
        {
            $name = 'Jenis Kelamin';
        }
        elseif ($kriterias == 'Kelas') 
        {
            $name = 'Kelas';
        }
        elseif ($kriterias == 'Fakultas') 
        {
            $name = 'Fakultas';
        }
        elseif ($kriterias == 'Jurusan') 
        {
            $name = 'Jurusan';
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
