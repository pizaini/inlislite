<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */

$this->title = Yii::t('app', 'View Jilid Collections');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ' Jilid Collections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collections-view">
    <?= $this->render('_formView', [
        'dataProvider' => $dataProvider,
        'model'=>$model
    ]) ?>

</div>
