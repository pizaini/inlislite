<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Survey $model
 */

$this->title = Yii::t('app','Add').' '.Yii::t('app','survey');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Survey'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
