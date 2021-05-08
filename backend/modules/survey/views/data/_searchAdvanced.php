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
            ['id' => 'NamaSurvey', 'label' => 'Nama Survey', 'type' => 'string'],
            [
                'id' => 'TanggalMulai', 'label' => 'Tanggal Mulai', 'type' => 'date',
                'validation'=> [
                      'format'=> 'yyyy-mm-dd'
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
                'id' => 'TanggalSelesai', 'label' => 'Tanggal Selesai', 'type' => 'date',
                'validation'=> [
                      'format'=> 'yyyy-mm-dd'
                ],
                'plugin'=> 'datepicker',
                'pluginConfig'=> [
                  'format'=> 'yyyy-mm-dd',
                  'todayBtn'=> 'linked',
                  'todayHighlight'=> true,
                  'autoclose'=> true
                ]

            ],
            ['id' => 'IsActive', 
             'label' => 'Status', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ['1'=>'Aktif','0'=>'Tidak Aktif']
             // 'values'=> ['0'=>'-Status Survey-',1 => 'Aktif' , 0 => 'Tidak Aktif']
            ],
            ['id' => 'NomorUrut', 'label' => 'No. Urut', 'type' => 'integer'],
            ['id' => 'TargetSurvey', 
             'label' => 'Target Survey', 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ['0'=>'-Target Survey-','1'=>'Anggota','0'=>'Semua']
            ],
          
            // ['id' => 'Source_id', 
            //  'label' => 'Jenis Sumber', 
            //  'type' => 'integer',
            //  'input'=> 'select',
            //  'values'=> ArrayHelper::merge(['0'=>'-Pilih Jenis Sumber-'],ArrayHelper::map(\common\models\Collectionsources::find()->all(),'ID','Name'))
            // ],
             
        ]
    ]
 ])?>
  <div class="form-group pull-right">
      <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> '.Yii::t('app',' Cari'), ['class' => 'btn btn-primary btn-sm']); ?>
      <?php //echo Html::resetButton('Ulangi',['class' => 'btn btn-default']); 
        echo Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Ulangi'), ['index'], ['class' => 'btn btn-info btn-sm']);
      ?>
  </div>
 <?php QueryBuilderForm::end() ?>
 </div>

</div>
