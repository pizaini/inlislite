<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\DepositKodeWilayah $model
 */

$this->title = Yii::t('app', 'Update Master Kode Wilayah');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SSKCKR'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Kode Wilayah'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-kode-wilayah-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
