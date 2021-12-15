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
    <div class="col-xs-12 col-xs-12">
<?php 

QueryBuilderForm::begin([
    'rules' => $rules,
    'options' => ['id'=>'modalsearchadvance'],
    'builder' => [
        'id' => 'query-builder',
        'allowGroups' => false,
        'selectPlaceholder'=>yii::t('app','-Pilih Kriteria-'),
        'filters' => [
            //['id' => 'ID', 'label' => 'Id', 'type' => 'integer'],
            ['id' => 'Fullname', 'label' => 'Nama', 'type' => 'string'],
            ['id' => 'MemberNo', 'label' => 'No.Anggota', 'type' => 'string'],
            ['id' => 'PlaceOfBirth', 'label' => 'Tempat Lahir', 'type' => 'string'],
            [
                'id' => 'DateOfBirth', 'label' => 'Tgl.Lahir', 'type' => 'date',
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
            ['id' => 'Address', 'label' => 'Alamat', 'type' => 'string'],
            ['id' => 'AddressNow', 'label' => 'Alamat Tempat Tinggal Sekarang', 'type' => 'string'],
            ['id' => 'Province', 'label' => 'Propinsi', 'type' => 'string'],
            ['id' => 'City', 'label' => 'Kabupaten/kota', 'type' => 'string'],
            ['id' => 'ProvinceNow', 'label' => 'Propinsi Sekarang', 'type' => 'string'],
            ['id' => 'CityNow', 'label' => 'Kabupaten/kota Sekarang', 'type' => 'string'],
            ['id' => 'NoHP', 'label' => 'No.HP', 'type' => 'string'],
            ['id' => 'Phone', 'label' => 'Telp. Rumah', 'type' => 'string'],
            ['id' => 'IdentityType_id', 
             'label' => 'Jenis Identitas', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterJenisIdentitas::find()->all(),'id','Nama')
            ],
            ['id' => 'IdentityNo', 'label' => 'No.Identitas', 'type' => 'string'],
            ['id' => 'jenis_kelamin.Name', 
             'label' => 'Jenis Kelamin', 
             'type' => 'boolean',
             'input'=> 'radio',
             'values'=> [
                  'Pria'=> 'Pria',
                  'Wanita'=> 'Wanita'
                ],
            ],
            ['id' => 'Job_id', 
             'label' => 'Pekerjaan', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterPekerjaan::find()->all(),'id','Pekerjaan')
            ],
            ['id' => 'Agama_id', 
             'label' => 'Agama', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\Agama::find()->all(),'ID','Name')
            ],
            ['id' => 'JenisAnggota_id', 
             'label' => 'Jenis Anggota', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\JenisAnggota::find()->all(),'id','jenisanggota')
            ],
            ['id' => 'EducationLevel_id', 
             'label' => 'Pendidikan Terakhir', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterPendidikan::find()->all(),'id','Nama')
            ],
            ['id' => 'MaritalStatus_id', 
             'label' => 'Status Perkawinan', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterStatusPerkawinan::find()->all(),'id','Nama')
            ],
            [
                'id' => 'RegisterDate', 'label' => 'Tgl.Pendaftaran', 'type' => 'date',
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
                'id' => 'EndDate', 'label' => 'Tgl.Berlaku Akhir', 'type' => 'date',
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
             'label' => 'Jenis Permohonan', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\JenisPermohonan::find()->all(),'ID','Name')
            ],
            ['id' => 'StatusAnggota_id', 
             'label' => 'Status Anggota', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\StatusAnggota::find()->all(),'id','Nama')
            ],
            ['id' => 'InstitutionName', 'label' => 'Nama Institusi', 'type' => 'string'],
            ['id' => 'InstitutionAddress', 'label' => 'Alamat Institusi', 'type' => 'string'],
            ['id' => 'InstitutionPhone', 'label' => 'Telp.Institusi', 'type' => 'string'],
            ['id' => 'Email', 'label' => 'Alamat Email', 'type' => 'string'],
            ['id' => 'NamaDarurat', 'label' => 'Nama Darurat', 'type' => 'string'],
            ['id' => 'AlamatDarurat', 'label' => 'Alamat Darurat', 'type' => 'string'],
            ['id' => 'TelpDarurat', 'label' => 'Telp.Darurat', 'type' => 'string'],
            ['id' => 'StatusHubunganDarurat', 'label' => 'Status Hub.Darurat', 'type' => 'string'],
            ['id' => 'UnitKerja_id', 
             'label' => 'Unit Kerja', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\Departments::find()->all(),'ID','Name')
            ],
            ['id' => 'Kelas_id', 
             'label' => 'Kelas', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\KelasSiswa::find()->all(),'id','namakelassiswa')
            ],
            ['id' => 'Fakultas_id', 
             'label' => 'Fakultas', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterFakultas::find()->all(),'id','Nama')
            ],
            ['id' => 'Jurusan_id', 
             'label' => 'Jurusan', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterJurusan::find()->all(),'id','Nama')
            ],
             ['id' => 'ProgramStudi_id', 
             'label' => 'Program Studi', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\MasterProgramStudi::find()->all(),'id','Nama')
            ],
            ['id' => 'KeteranganLain', 'label' => 'Keterangan Lain', 'type' => 'string'],
            ['id' => 'IsLunasBiayaPendaftaran', 
             'label' => 'Lunas Biaya Pendaftaran', 
             'type' => 'boolean',
             'input'=> 'radio',
             'values'=> [
                  '1'=> 'Lunas',
                  '0'=> 'Belum Lunas'
                ],
            ],
            ['id' => 'members.CreateBy', 
             'label' => 'Operator Tambah', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\Users::find()->all(),'ID','Fullname')
            ],
            [
                'id' => 'members.CreateDate', 'label' => 'Tgl.Entri', 'type' => 'date',
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
            ['id' => 'members.UpdateBy', 
             'label' => 'Operator Koreksi Terakhir', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::map(\common\models\Users::find()->all(),'ID','Fullname')
            ],
            [
                'id' => 'members.UpdateDate', 'label' => 'Tgl.Koreksi Terakhir', 'type' => 'date',
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
      <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i>'.Yii::t('app',' Cari'), ['class' => 'btn btn-primary btn-sm']); ?>
      <?php //echo Html::resetButton('Ulangi',['class' => 'btn btn-default']); 
        echo Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Ulangi'), ['index'], ['class' => 'btn btn-info btn-sm']);
      ?>
  </div>
 <?php QueryBuilderForm::end() ?>
 </div>

</div>

<script type="text/javascript">
  var frm = $('#modalsearchadvance');
  var trg = $('#pilihsalin-modal .modal-dialog .modal-content .modal-body');
    frm.submit(function (ev) {
        var rules = $('#query-builder').queryBuilder('getRules');

        if ($.isEmptyObject(rules)) {
          return false;
        } else {
            var input = $(this).find("input[name='rules']:first");
            input.val(JSON.stringify(rules));
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (data) {
                    trg.html(data);
                },
                beforeSend : function(){
                  trg.html("<center><h4>Loading data...</h4></center>");
                },
                error : function(){
                  trg.html("<center<h4>Error</h4></center>");
                },
            });
        }

        ev.preventDefault();
    });
</script>