<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterProgramStudi $model
 */

$this->title = Yii::t('app', 'Update').' '.Yii::t('app', 'Master Program Studi') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Program Studis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="master-program-studi-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
