<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Agama $model
 */

$this->title = Yii::t('app', 'Update').' '.Yii::t('app', 'Agama') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agamas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="agama-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
