<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Stockopname $model
 */

$this->title = Yii::t('app', 'Update Stockopname') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Stockopnames'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="stockopname-update">

   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
