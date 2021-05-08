<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\DepositGroupWs $model
 */

$this->title = Yii::t('app', 'Create Master Group Ws');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SSKCKR'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Group Ws'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-group-ws-create">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
