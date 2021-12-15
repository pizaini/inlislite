<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyPilihan $model
 */

$this->title = Yii::t('app','Update').Yii::t('app','Survey Pilihan') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Survey Pilihan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="survey-pilihan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
