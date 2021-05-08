<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model guestbook\models\Memberguesses */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Memberguesses',
]) . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Memberguesses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="memberguesses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
