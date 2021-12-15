<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Letter $model
 */

$this->title = 'Ucapan Terima Kasih';
$this->params['breadcrumbs'][] = ['label' => 'Deposit', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Terima Kasih';
?>
<div class="letter-create">
    <div class="page-header">
        <h3><span class="glyphicon glyphicon-plus-sign"></span> Tambah Ucapan Terima Kasih</h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
