<?php
use yii\helpers\Url;
use kartik\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\QuarantinedCollectionSearch $searchModel
 */

$this->title = Yii::t('app', 'Import Data dari Excel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/akuisisi'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quarantined-collections-index">
<?php 
$url = Yii::$app->urlManager->createUrl(['../uploaded_files/templates/datasheet/koleksi/sample_data_koleksi_AACR.xlsx']);
?>
    Template : <?= Html::a('Unduh Template', $url, ['class'=>'btn btn-primary btn-xs btn-flat']) ?>
    <br>
    <br>
    <?php if (isset(\Yii::$app->session['SessErrorImportCollections'])): ?>
      <div class="alert alert-danger alert-dismissable">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
      <h4><i class="icon fa fa-times"></i><?= yii::t('app','Gagal!')?></h4>
      <?php 
      $message= \Yii::$app->session['SessErrorImportCollections'];
      $message= explode("|",$message);
      echo"<ul>";
      foreach ($message as $key => $value) {
         echo"<li>".$value."</li>";
      }
      echo"</ul>";
       ?>
      </div>
    <?php endif;  ?>

    <?php if (isset(\Yii::$app->session['SessSuccessImportCollections'])): ?>
      <div class="alert alert-success alert-dismissable">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
      <h4><i class="icon fa fa-check"></i><?= yii::t('app','Berhasil!')?></h4>
      <?php 
      echo \Yii::$app->session['SessSuccessImportCollections'];
       ?>
      </div>
    <?php endif;  ?>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','target'=>"hidden_iframe"]]) ?>

    <!-- $form->field($model, 'file')->fileInput() -->
    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
        'showMessage'=>true,
        'options'=>['accept'=>'.xls, .xlsx, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'],
        'pluginOptions'=>[
            'allowedFileExtensions'=>['xls','xlsx'],
            'showPreview' => false,
            'autoReplace' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => true,
            'uploadLabel' => Yii::t('app','Proses'),
            'uploadUrl' => Url::to(['proses']),
        ],
        /*'pluginEvents' => [
            'filebatchuploadcomplete' => "function(event) {
            console.log(event);
            }", 
        ]*/
    ]);?>
   <?php 
   
   ?>

<?php ActiveForm::end() ?>
<h5>Informasi penggunaan import excel :</h5>
<ul>
  <li>Pastikan format Tanggal Pengadaan sesuai dengan Template Excel yang disediakan</li>
  <li>Jumlah data pada excel maksimal 200 data, jika melebihi batas yang ditentukan akan ditolak</li>
  <li>Jika jumlah data yang akan diimport melebihi 200 data, sebaiknya menggunakan aplikasi pendukung</li>
</ul>
</div>


