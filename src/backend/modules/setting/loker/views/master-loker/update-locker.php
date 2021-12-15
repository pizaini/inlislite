<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterLoker $model
 */

$this->title = Yii::t('app','Update').' ' . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Master Lokers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-loker-update">


    <?= $this->render('_form-locker', [
        'model' => $model,
    ]) ?>

</div>
