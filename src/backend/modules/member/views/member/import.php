<?php
use yii\helpers\Url;
use kartik\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;

$this->title = Yii::t('app', 'Import data anggota');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php 
$url = Yii::$app->urlManager->createUrl(['../uploaded_files/templates/datasheet/anggota/001_FormatAnggota.xlsx']);
?>
    Template : <?= Html::a(yii::t('app','Unduh Template'), $url, ['class'=>'btn btn-primary btn-xs btn-flat']) ?>
    <br>
    <br>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <!-- $form->field($model, 'file')->fileInput() -->
    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
        'options'=>['accept'=>'.xls, .xlsx, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'],
        'pluginOptions'=>[
            'allowedFileExtensions'=>['xls','xlsx'],
            'showPreview' => false,
            'autoReplace' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => true,
            'uploadLabel' => Yii::t('app','Proses'),
            'uploadUrl' => Url::to(['/member/member/import-anggota']),
        ]
    ]);?>

<?php ActiveForm::end() ?>
</div>
</div>
<div clas='error'>
    

</div>

