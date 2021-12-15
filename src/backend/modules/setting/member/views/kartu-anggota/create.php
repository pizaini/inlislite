<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Settingparameters $model
 */

$this->title = 'Create Settingparameters';
$this->params['breadcrumbs'][] = ['label' => 'Settingparameters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settingparameters-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
