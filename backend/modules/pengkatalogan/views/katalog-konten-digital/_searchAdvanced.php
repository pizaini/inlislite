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
    'builder' => [
        'id' => 'query-builder',
        'allowGroups' => false,
        'selectPlaceholder'=>yii::t('app','-Pilih Kriteria-'),
        'filters' => [
            ['id' => 'catalogs.Worksheet_id', 
             'label' => yii::t('app','Jenis Bahan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(['0'=>'-Pilih Jenis Bahan-'],ArrayHelper::map(\common\models\Worksheets::find()->addSelect(['ID','(CASE WHEN Keterangan IS NULL THEN Name ELSE CONCAT(Name,\'(\',Keterangan,\')\') END) AS Name'])->orderby('NoUrut ASC')->all(),'ID','Name'))
            ],
            ['id' => 'catalogs.BIBID', 'label' => 'BIBID', 'type' => 'string'],
            ['id' => 'catalogs.Title', 'label' => 'Judul', 'type' => 'string'],
            ['id' => 'catalogs.Author', 'label' => 'Pengarang', 'type' => 'string'],
            ['id' => 'catalogs.PublishLocation', 'label' => 'Tempat Terbit', 'type' => 'string'],
            ['id' => 'catalogs.Publisher', 'label' => 'Penerbit', 'type' => 'string'],
            ['id' => 'catalogs.PublishYear', 'label' => 'Tahun Terbit',  'type' => 'integer', 'input'=>'text'],
            ['id' => 'FileURL', 'label' => 'URL', 'type' => 'string'],
            [
                'id' => 'DATE(catalogfiles.CreateDate)', 'label' => 'Tanggal diunggah', 'type' => 'date',
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
            ['id' => 'usercreateby.username', 'label' => 'Diunggah oleh', 'type' => 'string'],
            
             
        ]
    ]
 ])?>
  <div class="form-group pull-right">
      <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class' => 'btn btn-primary btn-sm']); ?>
      <?php //echo Html::resetButton('Ulangi',['class' => 'btn btn-default']); 
        echo Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Ulangi'), ['index'], ['class' => 'btn btn-info btn-sm']);
      ?>
  </div>
 <?php QueryBuilderForm::end() ?>
 </div>

</div>
