<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Holidays $model
 */

$this->title = Yii::t('app', 'Update') . ' ' . Yii::t('app', 'Hari Libur') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Holidays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="holidays-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
