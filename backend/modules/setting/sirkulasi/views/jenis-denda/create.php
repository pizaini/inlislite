<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\JenisDenda $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Jenis Denda');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jenis Dendas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-denda-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
