<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MemberPerpanjangan $model
 */

$this->title = Yii::t('app', 'Update Member Perpanjangan') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member Perpanjangans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="member-perpanjangan-update">

    <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> Koreksi</h3>
    </div>

    <?= $this->render('_formUpdate', [
        'model' => $model,
        'modelMember' => $modelMember,
    ]) ?>

</div>
