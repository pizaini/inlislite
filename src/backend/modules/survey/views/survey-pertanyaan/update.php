<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyPertanyaan $model
 */

$this->title = Yii::t('app','Update').' '.Yii::t('app','Survey Pertanyaan'). ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Survey Pertanyaan'];
$this->params['breadcrumbs'][] = ['label' => $model->ID];
$this->params['breadcrumbs'][] = Yii::t('app','Update');
?>
<div class="survey-pertanyaan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
