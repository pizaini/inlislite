<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MemberPerpanjangan $model
 */

$this->title = Yii::t('app', 'Tambah Perpanjangan Anggota');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Perpanjangan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-perpanjangan-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelMember' => $modelMember,
    ]) ?>

</div>
