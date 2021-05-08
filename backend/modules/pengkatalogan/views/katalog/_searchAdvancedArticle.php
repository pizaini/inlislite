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
    //'options' => ['data-pjax' => true ],
    'builder' => [
        'id' => 'query-builder-article',
        'allowGroups' => false,
        'selectPlaceholder'=>yii::t('app','-Pilih Kriteria-'),
        'filters' => [
            //['id' => 'ID', 'label' => 'Id', 'type' => 'integer'],
            /*['id' => 'catalogs.Worksheet_id', 
             'label' => 'Jenis Bahan', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(['0'=>'-Pilih Jenis Bahan-'],ArrayHelper::map(\common\models\Worksheets::find()->addSelect(['ID','(CASE WHEN Keterangan IS NULL THEN Name ELSE CONCAT(Name,\'(\',Keterangan,\')\') END) AS Name'])->orderby('NoUrut ASC')->all(),'ID','Name'))
            ],*/
            ['id' => 'NomorBarcode', 'label' => 'Nomor Barcode', 'type' => 'string'],
            ['id' => 'NoInduk', 'label' => 'No. Induk', 'type' => 'string'],
            ['id' => 'RFID', 'label' => 'RFID', 'type' => 'string'],
            ['id' => 'EDISISERIAL', 'label' => 'Edisi Serial', 'type' => 'string'],
            ['id' => 'catalogs.ISBN', 'label' => 'ISBN / ISSN', 'type' => 'string'],
            ['id' => 'Source_id', 
             'label' => 'Jenis Sumber', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectionsources::find()->all(),'ID','Name'),['0'=>'-Pilih Jenis Sumber-'])
            ],
            ['id' => 'Media_id', 
             'label' => 'Bentuk Fisik', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectionmedias::find()->all(),'ID','Name'),['0'=>'-Pilih Bentuk Fisik-'])
            ],
            ['id' => 'Category_id', 
             'label' => 'Jenis Kategori', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectioncategorys::find()->all(),'ID','Name'),['0'=>'-Pilih Jenis Kategori-'])
            ],
            ['id' => 'Rule_id', 
             'label' => 'Akses', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectionrules::find()->all(),'ID','Name'),['0'=>'-Pilih Akses-'])
            ],
            ['id' => 'Status_id', 
             'label' => 'Ketersediaan', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Collectionstatus::find()->all(),'ID','Name'),['0'=>'-Pilih Ketersediaan-'])
            ],
            ['id' => 'Location_Library_id', 
             'label' => 'Lokasi Perpustakaan', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\LocationLibrary::find()->all(),'ID','Name'),['0'=>'-Pilih Lokasi Perpustakaan-'])
            ],
            ['id' => 'Location_id', 
             'label' => 'Lokasi Ruang', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Locations::find()->all(),'ID','Name'),['0'=>'-Pilih Lokasi Ruang-'])
            ],
            ['id' => 'catalogs.BIBID', 'label' => 'BIBID', 'type' => 'string'],
            ['id' => 'IDJILID', 'label' => 'IDJILID', 'type' => 'string'],
            ['id' => 'NOMORPANGGILJILID', 'label' => 'Nomor Panggil Jilid', 'type' => 'string'],
            ['id' => 'BAHAN_SERTAAN', 'label' => 'Bahan Sertaan (Serial)', 'type' => 'string'],
            ['id' => 'KETERANGAN_LAIN', 'label' => 'Keterangan Lain (Serial)', 'type' => 'string'],
            ['id' => 'collections.ID', 'label' => 'ID Koleksi', 'type' => 'string'],
            ['id' => 'collections.CreateBy', 'label' => 'Operator (Tambah)', 'type' => 'string'],
            ['id' => 'collections.UpdateBy', 'label' => 'Operator (Ubah Terakhir)', 'type' => 'string'],
            [
                'id' => 'DATE(collections.CreateDate)', 'label' => 'Tanggal Entri', 'type' => 'date',
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
                'id' => 'DATE(collections.UpdateDate)', 'label' => 'Tanggal Ubah Terakhir', 'type' => 'date',
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
                'id' => 'TanggalPengadaan', 'label' => 'Tanggal Pengadaan', 'type' => 'date',
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
            ['id' => 'serial_articles.ISOPAC',
             'label' => 'OPAC paijo',
             'type' => 'integer',
             'input'=> 'radio',
             'values'=> ['1'=>'Tampil','0'=>'Tidak Tampil']
            ],
            [
                'id' => 'YEAR(TGLENTRYJILID)', 'label' => 'Tahun Jilid', 'type' => 'date',
                /*'validation'=> [
                      'format'=> 'YYYY'
                ],*/
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
             
        ]
    ]
 ])?>
 <input type="hidden" name="rda" value="<?=$rda?>">
 <input type="hidden" name="for" value="<?=$for?>">
 <input type="hidden" name="id" value="<?=$id?>">
 <input type="hidden" name="edit" value="<?=$edit?>">
 <input type="hidden" name="refer" value="<?=$refer?>">
  <div class="form-group pull-right">
      <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i>'.Yii::t('app',' Cari'), ['class' => 'btn btn-primary btn-sm']); ?>
      <?php //echo Html::resetButton('Ulangi',['class' => 'btn btn-default']); 
        echo Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Ulangi'), ['reset-catalogs-collection?id='.$id.'&rda='.$rda.'&refer='.$refer], ['class' => 'btn btn-info btn-sm']);
      ?>
  </div>
 <?php QueryBuilderForm::end() ?>
 </div>

</div>

