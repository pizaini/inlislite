<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\DepositGroupWs $model
 */

$this->title = Yii::t('app', 'Update Master Group Ws') . ' - ' . $model->group_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SSKCKR'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Group Ws'), 'url' => ['index']];

?>
<div class="deposit-group-ws-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
