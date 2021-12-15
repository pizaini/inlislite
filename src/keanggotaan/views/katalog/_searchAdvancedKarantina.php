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
            ['id' => 'Worksheet_id', 
             'label' => yii::t('app','Jenis Bahan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(['0'=>'-Pilih Jenis Bahan-'],ArrayHelper::map(\common\models\Worksheets::find()->addSelect(['ID','(CASE WHEN Keterangan IS NULL THEN Name ELSE CONCAT(Name,\'(\',Keterangan,\')\') END) AS Name'])->orderby('NoUrut ASC')->all(),'ID','Name'))
            ],
            ['id' => 'catalogfilesCount.PunyaKontenDigital', 
             'label' => 'Konten Digital', 
             'type' => 'integer',
             'input'=> 'radio',
             'values'=> ['1'=>'Ada','0'=>'Tidak Ada']
            ],
            ['id' => 'Title', 'label' => 'Judul', 'type' => 'string'],
            ['id' => 'Author', 'label' => 'Pengarang', 'type' => 'string'],
            ['id' => 'PublishLocation', 'label' => 'Tempat Terbit', 'type' => 'string'],
            ['id' => 'Publisher', 'label' => 'Penerbit', 'type' => 'string'],
            ['id' => 'PublishYear', 'label' => 'Tahun Terbit',  'type' => 'integer', 'input'=>'text'],
            ['id' => 'Subject', 'label' => 'Subjek', 'type' => 'string'],
            ['id' => 'quarantined_catalogs.CallNumber', 'label' => 'Nomor Panggil', 'type' => 'string'],
            ['id' => 'ControlNumber', 'label' => 'Control Number', 'type' => 'string'],
            ['id' => 'BIBID', 'label' => 'BIBID', 'type' => 'string'],
            ['id' => 'ISBN', 'label' => 'ISBN / ISSN', 'type' => 'string'],
            ['id' => 'Edition', 'label' => 'Edisi', 'type' => 'string'],
            ['id' => 'PhysicalDescription', 'label' => 'Deskripsi Fisik', 'type' => 'string'],
            [
                'id' => 'YEAR(catalogfiles.CreateDate)', 'label' => 'Tahun Upload', 'type' => 'date',
                'validation'=> [
                      'format'=> 'YYYY'
                ],
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
            ['id' => 'quarantined_catalogs.ID', 'label' => 'Catalog ID', 'type' => 'string'],
            /*['id' => 'collections.NoInduk', 'label' => 'No. Induk', 'type' => 'string'],
            ['id' => 'collections.NomorBarcode', 'label' => 'Nomor Barcode', 'type' => 'string'],
            ['id' => 'collections.RFID', 'label' => 'RFID', 'type' => 'string'],*/
            ['id' => 'usercreateby.username', 'label' => 'Operator (Tambah)', 'type' => 'string'],
            ['id' => 'userupdateby.username', 'label' => 'Operator (Ubah Terakhir)', 'type' => 'string'],
            [
                'id' => 'DATE(quarantined_catalogs.CreateDate)', 'label' => 'Tanggal Entri', 'type' => 'date',
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
                'id' => 'DATE(quarantined_catalogs.UpdateDate)', 'label' => 'Tanggal Ubah Terakhir', 'type' => 'date',
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
 <input type="hidden" name="for" value="<?=$for?>">
  <div class="form-group pull-right">
      <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i>'.Yii::t('app',' Cari'), ['class' => 'btn btn-primary btn-sm']); ?>
      <?php //echo Html::resetButton('Ulangi',['class' => 'btn btn-default']); 
        echo Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Ulangi'), ['index'], ['class' => 'btn btn-info btn-sm']);
      ?>
  </div>
 <?php QueryBuilderForm::end() ?>
 </div>

</div>
