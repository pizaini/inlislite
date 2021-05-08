<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\BacaditempatKembali $model
 */

$this->title = Yii::t('app', 'Create Bacaditempat Kembali');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bacaditempat Kembalis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bacaditempat-kembali-create">
    <div class="page-header">
        <h3><span class="glyphicon glyphicon-plus-sign"></span> Tambah</h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
