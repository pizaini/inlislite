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
    <div class="col-xs-9 col-xs-9 col-xs-offset-3">
<?php 

QueryBuilderForm::begin([
    'rules' => $rules,
    'builder' => [
        'id' => 'query-builder',
        'allowGroups' => false,
        'selectPlaceholder'=>'-Pilih Kriteria-',
        'filters' => [
            //['id' => 'ID', 'label' => 'Id', 'type' => 'integer'],
            ['id' => 'members.MemberNo', 'label' => yii::t('app','No.Anggota'), 'type' => 'string'],
            ['id' => 'members.Fullname', 'label' => yii::t('app','Nama Anggota'), 'type' => 'string'],
            [
                'id' => 'LoanDate', 'label' => yii::t('app','Tanggal Pinjam'), 'type' => 'date',
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
                'id' => 'DueDate', 'label' => yii::t('app','Tanggal Jatuh Tempo'), 'type' => 'date',
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
            ['id' => 'CollectionLoan_id', 'label' => yii::t('app','No.Transaksi'), 'type' => 'string'],
            ['id' => 'collections.NomorBarcode', 'label' => yii::t('app','Nomor Barcode'), 'type' => 'string'],
            ['id' => 'catalogs.Title', 'label' => yii::t('app','Judul'), 'type' => 'string'],
            ['id' => 'users.ID', 
             'label' => yii::t('app','Operator Tambah'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Users::find()->all(),'ID','Fullname'),['0'=>'-Pilih Operator-'])
            ],
            
            ////////////////// Field search Lokasi perpustakaan
            ['id' => 'collectionloans.LocationLibrary_id', 
             'label' => yii::t('app','Lokasi Perpustakaan'), 
             'type' => 'integer',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\LocationLibrary::find()->all(),'ID','Name'),['0'=>'-Pilih Operator-'])
            ],
            //////////////// Field search Lokasi perpustakaan
            ['id' => 'members.KecamatanNow', 
             'label' => yii::t('app','Lokasi Kecamatan Anggota'), 
             'type' => 'string',
             'input'=> 'select',
             'values'=> ArrayHelper::merge(ArrayHelper::map(\common\models\Members::find()->addSelect(['KecamatanNow'])->all(),'KecamatanNow','KecamatanNow'),['0'=>'-Pilih Operator-'])
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