<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\KelompokPelanggaran $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Kelompok Pelanggaran');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kelompok Pelanggarans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kelompok-pelanggaran-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
