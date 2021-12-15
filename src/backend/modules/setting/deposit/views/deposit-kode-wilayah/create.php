<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\DepositKodeWilayah $model
 */

$this->title = Yii::t('app', 'Create Master Kode Wilayah');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SSKCKR'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-kode-wilayah-create">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
