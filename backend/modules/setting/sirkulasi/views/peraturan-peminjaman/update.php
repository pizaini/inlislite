<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionrules $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Entry Data Jenis Akses') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collectionrules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="collectionrules-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
