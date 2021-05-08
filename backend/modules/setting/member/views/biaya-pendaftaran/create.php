<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\JenisAnggota $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app','Biaya Pendaftaran') ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => Url::to(['/setting/member'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="biaya-pendaftaran-create">
    <div class="page-header">
        <h3><span class="glyphicon glyphicon-plus-sign"></span> <?=Yii::t('app','Add')?></h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
