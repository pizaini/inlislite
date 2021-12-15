<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 */

$this->title = Yii::t('app', 'Tambah User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-perpustakaan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
