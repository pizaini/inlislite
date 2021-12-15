<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterDjkn $model
 */

$this->title = Yii::t('app', 'Update').' '.Yii::t('app', 'Master Djkn') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Djkns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="master-djkn-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
