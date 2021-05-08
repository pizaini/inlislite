<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyIsian $model
 */

$this->title = 'Update Survey Isian' . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Survey Isians', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="survey-isian-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
