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
    <div class="thumbnail template-thumbnail" style="padding-bottom: 40px;">

        <?= Html::img(Yii::$app->urlManager->createUrl('../uploaded_files/settings/kartu_anggota/template_membership_card_'.$i.'.png?timestamp='.rand()), [
            'class' => 'template-thumbnail',
            'style' =>[
                 'width'=>'320px',
                 'max-height'=>'320px'
            ]
        ]); ?>
        <h3 class="template-name">Template Membership Card <?= $i; ?></h3>
        <div class="template-thumbnail">
            <?php if ($i == $model2->KartuAnggota): ?>
                <div style="width: 50%; float: left;">
                    <?php // Html::tag('span', Yii::t('app', 'Download'), ['class' => 'half-width btn-block btn bg-maroon btn-flat']); ?>
                    <?= Html::a(Yii::t('app', 'Download'), Yii::$app->urlManager->createUrl('../uploaded_files/settings/kartu_anggota/blanko_cardmember_layout_'.$i.'.png'), [
                        'class' => ' btn-block btn bg-maroon btn-flat',
                        'download' => 'blanko_cardmember_layout_1'.$i.'.png',
                    ]); ?>
                </div>
                <div style="width: 50%; float: left;">
                    <?=  Html::tag('span', Yii::t('app', 'Template Aktif'), ['class' => ' btn-block btn btn-flat btn-success disabled']); ?>
                </div>
            <?php else: ?>
                <div style="width: 50%; float: left;">
                    <?php // Html::tag('span', Yii::t('app', 'Download'), ['class' => 'half-width btn-block btn bg-maroon btn-flat']); ?>
                    <?= Html::a(Yii::t('app', 'Download'), Yii::$app->urlManager->createUrl('../uploaded_files/settings/kartu_anggota/blanko_cardmember_layout_'.$i.'.png'), [
                        'class' => ' btn-block btn bg-maroon btn-flat',
                        'download' => 'blanko_cardmember_layout_1'.$i.'.png',
                    ]); ?>
                </div>
                <div style="width: 50%; float: left;">
                    <?= Html::a(Yii::t('app', 'Aktifkan'), ['aktifkan', 'id' => $i], [
                        'class' => ' btn-block btn bg-olive btn-flat',
                    ]); ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
