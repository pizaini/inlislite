<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use yii\widgets\ActiveForm;
/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="col-xs-6 col-sm-4">
    <div class="thumbnail template-thumbnail">

        <?= Html::img(Yii::$app->urlManager->createUrl('../uploaded_files/settings/kartu_anggota/bg_cardmember'.$i.'.png?timestamp='.rand()), [
            'class' => 'template-thumbnail',
            'style' =>[
                 'width'=>'320px',
                 'height'=>'203px'
            ]

        ]); ?>
        <h3 class="template-name">Layout Kartu Anggota <?= $i; ?></h3>
        <?php echo FileInput::widget([
            'name' => 'kartu_anggota'.$i,
            //'id'=>'kartu_anggota',
            'options'=>[
                'accept' => 'image/*'
            ],
            'pluginOptions' => [
        //        'initialPreview'=> [
        //            '<img src="../../../../uploaded_files/settings/kartu_anggota/bg_cardmember.png" height="190" class="border"/>',
        //        ],
                'showPreview' => false,
                'showCaption' => true,
                'showRemove' => false,
                'showUpload' => true,
                'browseLabel' => '',
                'removeLabel' => Yii::t('app','Remove'),
                'uploadLabel' => Yii::t('app','Upload'),
                'uploadUrl' => Url::to(['/setting/member/kartu-anggota/upload?id='.$i]),
                'allowedFileExtensions'=> ["jpg", "png", "gif"],
                'msgInvalidFileExtension'=>Yii::t('app','Invalid extension for file "{name}". Only "{extensions}" files are supported.'),
                'minImageWidth'=> 1004,
                'minImageHeight'=> 638,
            ]
        ]);?>
        <?php

        // if ($i == $model2->KartuAnggota)
        // {
        //     echo Html::tag('span', Yii::t('app', 'Template Aktif'), ['class' => 'full-width btn-block btn btn-flat btn-info']);
        //     echo Html::tag('span', Yii::t('app', 'Active Template'), ['class' => 'full-width btn-block btn btn-flat btn-info']);
        // }
        // else
        // {
        //     echo Html::tag('span', Yii::t('app', 'Template Tidak Aktif'), ['class' => 'full-width btn-block btn bg-maroon btn-flat']);
        //     echo Html::a(Yii::t('app', 'Aktifkan'), ['aktifkan', 'id' => $i], [
        //         'class' => 'full-width btn-block btn bg-olive btn-flat',

        //     ]);

        // }
        ?>
    </div>
</div>
