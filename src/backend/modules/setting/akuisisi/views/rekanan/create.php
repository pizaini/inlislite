<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Partners $model
 */

$this->title = Yii::t('app', 'Tambah Nama Sumber Perolehan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/setting/akuisisi'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
