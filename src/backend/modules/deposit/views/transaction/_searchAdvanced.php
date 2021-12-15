<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package _form.php
 * @version 1.0.0
 * @author Henry <alvin_vna@yahoo.com>
 */

use yii\helpers\Html;
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
    'options' => ['data-pjax' => true ],
    'builder' => [
        'id' => 'query-builder',
        'allowGroups' => false,
        'selectPlaceholder'=>yii::t('app','-Pilih Kriteria-'),
        'filters' => [
            //['id' => 'ID', 'label' => 'Id', 'type' => 'integer'],
            ['id' => 'catalogs.Worksheet_id', 
             'label' => yii::t('app','Jenis Bahan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Worksheets::find()->all(),'ID','Name'),['0'=>'-Pilih Jenis Bahan-'])
            ],
            ['id' => 'NomorBarcode', 'label' => yii::t('app','Nomor Barcode'), 'type' => 'string'],
            ['id' => 'NoInduk', 'label' => yii::t('app','No. Induk'), 'type' => 'string'],
            ['id' => 'RFID', 'label' => 'RFID', 'type' => 'string'],
            ['id' => 'collections.CallNumber', 'label' => yii::t('app','Nomor Panggil'), 'type' => 'string'],
            ['id' => 'catalogs.Title', 'label' => yii::t('app','Judul'), 'type' => 'string'],
            ['id' => 'catalogs.Author', 'label' => yii::t('app','Pengarang'), 'type' => 'string'],
            ['id' => 'catalogs.PublishLocation', 'label' => yii::t('app','Tempat Terbit'), 'type' => 'string'],
            ['id' => 'catalogs.Publisher', 'label' => yii::t('app','Penerbit'), 'type' => 'string'],
            ['id' => 'catalogs.PublishYear', 'label' => yii::t('app','Tahun Terbit'),  'type' => 'integer', 'input'=>'text'],
            ['id' => 'catalogs.PhysicalDescription', 'label' => yii::t('app','Deskripsi Fisik'), 'type' => 'string'],
            ['id' => 'catalogs.Edition', 'label' => yii::t('app','Edisi'), 'type' => 'string'],
            ['id' => 'EDISISERIAL', 'label' => yii::t('app','Edisi Serial'), 'type' => 'string'],
            ['id' => 'catalogs.ISBN', 'label' => 'ISBN / ISSN', 'type' => 'string'],
            ['id' => 'Source_id', 
             'label' => yii::t('app','Jenis Sumber'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectionsources::find()->all(),'ID','Name'),['0'=>'-Pilih Jenis Sumber-'])
            ],
            ['id' => 'Media_id', 
             'label' => yii::t('app','Bentuk Fisik'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectionmedias::find()->all(),'ID','Name'),['0'=>'-Pilih Bentuk Fisik-'])
            ],
            ['id' => 'Category_id', 
             'label' => yii::t('app','Jenis Kategori'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectioncategorys::find()->all(),'ID','Name'),['0'=>'-Pilih Jenis Kategori-'])
            ],
            ['id' => 'Rule_id', 
             'label' => yii::t('app','Akses'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectionrules::find()->all(),'ID','Name'),['0'=>'-Pilih Akses-'])
            ],
            ['id' => 'Status_id', 
             'label' => yii::t('app','Ketersediaan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectionstatus::find()->all(),'ID','Name'),['0'=>'-Pilih Ketersediaan-'])
            ],
            ['id' => 'Location_Library_id', 
             'label' => yii::t('app','Lokasi Perpustakaan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\LocationLibrary::find()->all(),'ID','Name'),['0'=>'-Pilih Lokasi Perpustakaan-'])
            ],
            ['id' => 'Location_id', 
             'label' => yii::t('app','Lokasi Ruang'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Locations::find()->all(),'ID','Name'),['0'=>'-Pilih Lokasi Ruang-'])
            ],
            ['id' => 'catalogs.BIBID', 'label' => 'BIBID', 'type' => 'string'],
            ['id' => 'IDJILID', 'label' => 'IDJILID', 'type' => 'string'],
            ['id' => 'NOMORPANGGILJILID', 'label' => yii::t('app','Nomor Panggil Jilid'), 'type' => 'string'],
            ['id' => 'BAHAN_SERTAAN', 'label' => yii::t('app','Bahan Sertaan (Serial)'), 'type' => 'string'],
            ['id' => 'KETERANGAN_LAIN', 'label' => yii::t('app','Keterangan Lain (Serial)'), 'type' => 'string'],
            ['id' => 'collections.ID', 'label' => yii::t('app','ID Koleksi'), 'type' => 'string'],
            ['id' => 'usr_create.username', 'label' => yii::t('app','Operator (Tambah)'), 'type' => 'string'],
            // ['id' => 'collections.UpdateBy', 'label' => 'Operator (Ubah Terakhir)', 'type' => 'string'],
            ['id' => 'usr_update.username', 'label' => yii::t('app','Operator (Ubah Terakhir)'), 'type' => 'string'],
            [
                'id' => 'DATE(collections.CreateDate)', 'label' => yii::t('app','Tanggal Entri'), 'type' => 'date',
                /*'validation'=> [
                      'format'=> 'YYYY-MM-DD'
                ],*/
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy-mm-dd',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
            [
                'id' => 'DATE(collections.UpdateDate)', 'label' => yii::t('app','Tanggal Ubah Terakhir'), 'type' => 'date',
                /*'validation'=> [
                      'format'=> 'YYYY-MM-DD'
                ],*/
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy-mm-dd',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
            [
                'id' => 'TanggalPengadaan', 'label' => yii::t('app','Tanggal Pengadaan'), 'type' => 'date',
                /*'validation'=> [
                      'format'=> 'YYYY-MM-DD'
                ],*/
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy-mm-dd',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
            ['id' => 'collections.ISOPAC', 
             'label' => 'OPAC', 
             'type' => 'integer',
             'input'=> 'radio',
             'values'=> ['1'=>'Tampil','0'=>'Tidak Tampil']
            ],
            [
                'id' => 'SUBSTR(IDJILID,6,4)', 
                'label' => yii::t('app','Tahun Jilid'), 
                'type' => 'integer',
                'input'=> 'text'

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
