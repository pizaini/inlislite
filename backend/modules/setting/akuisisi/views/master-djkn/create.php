<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterDjkn $model
 */

$this->title =  Yii::t('app', 'Add').' '.Yii::t('app', 'Master Djkn');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Djkns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-djkn-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
