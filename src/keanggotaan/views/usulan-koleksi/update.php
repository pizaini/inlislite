<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Requestcatalog $model
 */

$this->title = Yii::t('app', 'Update Requestcatalog') . ' ' . $model->Title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requestcatalogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Title, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="requestcatalog-update">

    <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> Koreksi</h3>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
