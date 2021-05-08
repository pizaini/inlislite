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
            ['id' => 'Title', 'label' => 'Judul', 'type' => 'string'],
            ['id' => 'EDISISERIAL', 'label' => 'Edisi Serial', 'type' => 'string'],
            ['id' => 'serial_articles.ISOPAC',
             'label' => 'Tampil',
             'type' => 'integer',
             'input'=> 'radio',
             'values'=> ['1'=>'Tampil','0'=>'Tidak Tampil']
            ],
            [
                'id' => 'DATE(CreateDate)', 'label' => 'Tanggal Entri', 'type' => 'date',
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

