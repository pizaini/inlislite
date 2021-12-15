<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterPelanggaranLocker $model
 */

$this->title = 'Koreksi Master Pelanggaran Locker' . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Master Pelanggaran Lockers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-pelanggaran-locker-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
