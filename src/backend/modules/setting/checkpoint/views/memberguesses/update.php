<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Memberguesses $model
 */

$this->title = 'Update Memberguesses' . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Memberguesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="memberguesses-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
