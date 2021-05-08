<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterLoker $model
 */

$this->title = Yii::t('app','Add').' '.Yii::t('app','Master Loker');
$this->params['breadcrumbs'][] = ['label' => 'Master Lokers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-loker-create">

    <?= $this->render('_form-locker', [
        'model' => $model,
    ]) ?>

</div>
