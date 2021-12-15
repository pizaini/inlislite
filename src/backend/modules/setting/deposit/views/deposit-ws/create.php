<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DepositWs */

$this->title = Yii::t('app', 'Create Deposit Ws');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deposit Ws'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-ws-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
