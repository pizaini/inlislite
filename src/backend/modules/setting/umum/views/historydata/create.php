<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Historydata */

$this->title = 'Create Historydata';
$this->params['breadcrumbs'][] = ['label' => 'Historydatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="historydata-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
