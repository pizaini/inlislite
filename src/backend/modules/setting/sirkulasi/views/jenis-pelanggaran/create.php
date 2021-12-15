<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPelanggaran $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Jenis Pelanggaran');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jenis Pelanggarans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-pelanggaran-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
