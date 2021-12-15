<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Koleksi Sering Dipinjam');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'digitalcollection'), 'url' => Url::to(['/setting/digitalcollection'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settingparameters-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
