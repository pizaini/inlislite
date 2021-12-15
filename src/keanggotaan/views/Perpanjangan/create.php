<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MemberPerpanjangan $model
 */

$this->title = Yii::t('app', 'Tambah Perpanjangan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Perpanjangan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="member-perpanjangan-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelMember' => $modelMember,
    ]) ?>

</div>
