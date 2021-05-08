<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Departments $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app','Unit Kerja') ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Departments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="departments-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
