<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 */

$this->title = Yii::t('app', 'Update User') . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'ID' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="jenis-perpustakaan-update">

    <!-- <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> Koreksi</h3>
    </div> -->

    <?= $this->render('_form', [
        'model' => $model,
        'model2' => $model2,
        'model3' => $model3,
        'modelUser' => $modelUser,
        'modelAssignment'=>$modelAssignment
    ]) ?>

</div>
