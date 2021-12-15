<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Survey $model
 */

$this->title = Yii::t('app','Update').' '.Yii::t('app','survey'). ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Surveys', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app','Update');
?>
<div class="survey-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
