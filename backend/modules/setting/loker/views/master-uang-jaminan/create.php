<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterUangJaminan $model
 */

$this->title = Yii::t('app','Add').' Master Uang Jaminan';
$this->params['breadcrumbs'][] = ['label' => 'Master Uang Jaminans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-uang-jaminan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
