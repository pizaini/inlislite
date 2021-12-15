<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\TujuanKunjungan $model
 */

$this->title = Yii::t('app', 'Add') . ' ' .Yii::t('app', 'Tujuan Kunjungan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tujuan Kunjungans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tujuan-kunjungan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
