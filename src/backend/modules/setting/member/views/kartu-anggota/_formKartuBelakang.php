<?php
/**
 * @file    _formKartuBelakang.php
 * @date    24/8/2015
 * @time    12:25 AM
 * @author  Henry <alvin_vna@yahoo.com>
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license
 */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Locations $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'layout' => 'horizontal'
    ]); ?>


<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label"><?php echo Yii::t('app','Text Bagian Belakang Kartu Anggota');?></label>
  <div class="col-sm-10">
<?php //cho $form->field($model, 'Text_BELAKANG')->textarea(['class'=>'textarea','style' => 'width:700px'])->label(false); ?>
         <textarea name="DynamicModel[Text_BELAKANG]" class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
        <?=$model->Text_BELAKANG ?>
    </textarea>
   </div>
</div>

<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label"><?php echo Yii::t('app','Gambar Latar Belakang Kartu Anggota');?></label>

  <div class="col-xs-6 col-sm-4">
    <div class="thumbnail template-thumbnail">
    <?php // echo Html::img('http://localhost/inlislite3/uploaded_files/settings/kartu_anggota/bg_cardmemberbelakang.png?timestamp='.rand(), [ ?>
    <?= Html::img(Yii::$app->urlManager->createUrl("../uploaded_files/settings/kartu_anggota").'/bg_cardmemberbelakang.png?timestamp='.rand(), [
            'class' => 'border',
            'style' =>[
                 'width'=>'320px',
                 'height'=>'203px'
            ]

        ]); ?>
    <?php echo FileInput::widget([
            'name' => 'kartu_anggota_belakang',
            //'id'=>'kartu_anggota',
            'options'=>[
                'accept' => 'image/*'
            ],
            'pluginOptions' => [

                'showPreview' => false,
                'showCaption' => true,
                'showRemove' => false,
                'showUpload' => true,
                'browseLabel' => '',
                'removeLabel' => Yii::t('app','Remove'),
                'uploadLabel' => Yii::t('app','Upload'),
                'uploadUrl' => Url::to(['/setting/member/kartu-anggota/upload-kartu-belakang']),
                'allowedFileExtensions'=> ["jpg", "png", "gif"],
                'msgInvalidFileExtension'=>Yii::t('app','Invalid extension for file "{name}". Only "{extensions}" files are supported.'),
                'minImageWidth'=> 1004,
                'minImageHeight'=> 638,
            ]
        ]);?>
        </div>
   </div>

</div>
<div class="form-group">
  <label for="inputEmail3" class="col-sm-2 control-label">

  </label>
  <div class="col-sm-10">
     <?= Html::submitButton(Yii::t('app','Create'), ['class' => 'btn btn-success' ]) ?>
   </div>
</div>

<?php ActiveForm::end();?>
