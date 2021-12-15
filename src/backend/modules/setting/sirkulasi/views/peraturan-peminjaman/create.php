<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionrules $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Entry Data Jenis Akses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collectionrules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collectionrules-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
