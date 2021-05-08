<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */

$this->title = Yii::t('app', 'Create Jilid Collections');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ' Jilid Collections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collections-create">
    <?= $this->render('_form', [
        'dataProvider' => $dataProvider
    ]) ?>

</div>
