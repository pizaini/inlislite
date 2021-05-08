<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Settingparameters $model
 */

$this->title = 'Update Settingparameters' . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Settingparameters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="settingparameters-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
