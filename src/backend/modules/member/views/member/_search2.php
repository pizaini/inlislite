<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package _form.php
 * @version 1.0.0
 * @author Henry <alvin_vna@yahoo.com>
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

use kartik\widgets\Select2;
use leandrogehlen\querybuilder\QueryBuilderForm;

/**
 * @var yii\web\View $this
 * @var common\models\MemberSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="row">
    <div class="col-xs-9 col-xs-9 col-xs-offset-3">
<?php 


QueryBuilderForm::begin([
    'rules' => $rules,
    'options' => ['data-pjax' => true ],
    'builder' => [
        'id' => 'query-builder',
        'allowGroups' => false,
        'selectPlaceholder'=>yii::t('app','-Pilih Kriteria-'),
        'filters' => [
            //['id' => 'ID', 'label' => 'Id', 'type' => 'integer'],
            ['id' => 'Fullname', 'label' => yii::t('app','Nama'), 'type' => 'string'],
            ['id' => 'MemberNo', 'label' => yii::t('app','No.Anggota'), 'type' => 'string'],
            ['id' => 'PlaceOfBirth', 'label' => yii::t('app','Tempat Lahir'), 'type' => 'string'],
            [
                'id' => 'DateOfBirth', 'label' => yii::t('app','Tanggal Lahir'), 'type' => 'date',
                'validation'=> [
                      'format'=> 'YYYY-MM-DD'
                ],
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy-mm-dd',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
            ['id' => 'Address', 'label' => yii::t('app','Alamat'), 'type' => 'string'],
            ['id' => 'AddressNow', 'label' => yii::t('app','Alamat Tempat Tinggal Sekarang'), 'type' => 'string'],
            ['id' => 'Province', 'label' => yii::t('app','Propinsi'), 'type' => 'string'],
            ['id' => 'City', 'label' => yii::t('app','Kabupaten / Kota'), 'type' => 'string'],
            ['id' => 'ProvinceNow', 'label' => yii::t('app','Propinsi Sekarang'), 'type' => 'string'],
            ['id' => 'CityNow', 'label' => yii::t('app','Kabupaten/kota Sekarang'), 'type' => 'string'],
            ['id' => 'NoHP', 'label' => yii::t('app','No. HP'), 'type' => 'string'],
            ['id' => 'Phone', 'label' => yii::t('app','No. Telepon Rumah'), 'type' => 'string'],
            ['id' => 'IdentityType_id', 
             'label' => yii::t('app','Jenis Identitas'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterJenisIdentitas::find()->all(),'id','Nama')
            ],
            ['id' => 'IdentityNo', 'label' => yii::t('app','No Identitas'), 'type' => 'string'],
            ['id' => 'jenis_kelamin.Name', 
             'label' => yii::t('app','Jenis Kelamin'), 
             'type' => 'boolean',
             'input'=> 'radio',
             'values'=> [
                  'Pria'=> yii::t('app','Pria'),
                  'Wanita'=> yii::t('app','Wanita')
                ],
            ],
            ['id' => 'Job_id', 
             'label' => yii::t('app','Pekerjaan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterPekerjaan::find()->all(),'id','Pekerjaan')
            ],
            ['id' => 'Agama_id', 
             'label' => yii::t('app','Agama'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\Agama::find()->all(),'ID','Name')
            ],
            ['id' => 'JenisAnggota_id', 
             'label' => yii::t('app','Jenis Anggota'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\JenisAnggota::find()->all(),'id','jenisanggota')
            ],
            ['id' => 'EducationLevel_id', 
             'label' => yii::t('app','Pendidikan Terakhir'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterPendidikan::find()->all(),'id','Nama')
            ],
            ['id' => 'MaritalStatus_id', 
             'label' => yii::t('app','Status Perkawinan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterStatusPerkawinan::find()->all(),'id','Nama')
            ],
            [
                'id' => 'RegisterDate', 'label' => yii::t('app','Tanggal Pendaftaran'), 'type' => 'date',
                'validation'=> [
                      'format'=> 'YYYY-MM-DD'
                ],
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy-mm-dd',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
            [
                'id' => 'EndDate', 'label' => yii::t('app','Tanggal Berlaku Akhir'), 'type' => 'date',
                'validation'=> [
                      'format'=> 'YYYY-MM-DD'
                ],
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy-mm-dd',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
            ['id' => 'JenisPermohonan_id', 
             'label' => yii::t('app','Jenis Permohonan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\JenisPermohonan::find()->all(),'ID','Name')
            ],
            ['id' => 'StatusAnggota_id', 
             'label' => yii::t('app','Status Anggota'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\StatusAnggota::find()->all(),'id','Nama')
            ],
            ['id' => 'MotherMaidenName', 'label' => yii::t('app','Ibu Kandung'), 'type' => 'string'],
            ['id' => 'InstitutionName', 'label' => yii::t('app','Nama Institusi'), 'type' => 'string'],
            ['id' => 'InstitutionAddress', 'label' => yii::t('app','Alamat Institusi'), 'type' => 'string'],
            ['id' => 'InstitutionPhone', 'label' => yii::t('app','No. Telepon Institusi'), 'type' => 'string'],
            ['id' => 'Email', 'label' => yii::t('app','Email'), 'type' => 'string'],
            ['id' => 'NamaDarurat', 'label' => yii::t('app','Nama Darurat'), 'type' => 'string'],
            ['id' => 'AlamatDarurat', 'label' => yii::t('app','Alamat Darurat'), 'type' => 'string'],
            ['id' => 'TelpDarurat', 'label' => yii::t('app','Telp Darurat'), 'type' => 'string'],
            ['id' => 'StatusHubunganDarurat', 'label' => yii::t('app','Status Hub.Darurat'), 'type' => 'string'],
            ['id' => 'UnitKerja_id', 
             'label' => yii::t('app','Unit Kerja'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\Departments::find()->all(),'ID','Name')
            ],
            ['id' => 'Kelas_id', 
             'label' => yii::t('app','Kelas'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\KelasSiswa::find()->all(),'id','namakelassiswa')
            ],
            ['id' => 'Fakultas_id', 
             'label' => yii::t('app','Fakultas'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterFakultas::find()->all(),'id','Nama')
            ],
            ['id' => 'Jurusan_id', 
             'label' => yii::t('app','Jurusan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterJurusan::find()->all(),'id','Nama')
            ],
             ['id' => 'ProgramStudi_id', 
             'label' => yii::t('app','Program Studi'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterProgramStudi::find()->all(),'id','Nama')
            ],
            ['id' => 'KeteranganLain', 'label' => yii::t('app','Keterangan Lain'), 'type' => 'string'],
            ['id' => 'IsLunasBiayaPendaftaran', 
             'label' => yii::t('app','Lunas Biaya Pendaftaran'), 
             'type' => 'boolean',
             'input'=> 'radio',
             'values'=> [
                  '1'=> 'Lunas',
                  '0'=> 'Belum Lunas'
                ],
            ],
            // ['id' => 'members.CreateBy', 
            //  'label' => yii::t('app','Operator Tambah'), 
            //  'type' => 'integer',
            //  'input'=> 'select',
            //  'values'=> ArrayHelper::map(\common\models\Users::find()->all(),'ID','Fullname')
            // ],
            [
                'id' => 'members.CreateDate', 'label' => yii::t('app','Tanggal Entri'), 'type' => 'date',
                'validation'=> [
                      'format'=> 'YYYY-MM-DD'
                ],
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy-mm-dd',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
            // ['id' => 'members.UpdateBy', 
            //  'label' => yii::t('app','Operator Koreksi Terakhir'), 
            //  'type' => 'integer',
            //  'input'=> 'select',
            //  'values'=> ArrayHelper::map(\common\models\Users::find()->all(),'ID','Fullname')
            // ],
            [
                'id' => 'members.UpdateDate', 'label' => yii::t('app','Tgl.Koreksi Terakhir'), 'type' => 'date',
                'validation'=> [
                      'format'=> 'YYYY-MM-DD'
                ],
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy-mm-dd',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
             
        ]
    ]
 ])?>
 
  <div class="form-group pull-right">
      <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> '.Yii::t('app',' Cari'), ['class' => 'btn btn-primary']); ?>
      <?php //echo Html::resetButton('Ulangi',['class' => 'btn btn-default']); 
        echo Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Ulangi'), ['index'], ['class' => 'btn btn-info']);
      ?>
  </div>
 <?php QueryBuilderForm::end() ?>
 </div>

</div>