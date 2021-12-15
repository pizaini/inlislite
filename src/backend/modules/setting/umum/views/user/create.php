<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 */

$this->title = Yii::t('app', 'Tambah Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-perpustakaan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'model2' => $model2,
        'model3' => $model3,
    ]) ?>

</div>
