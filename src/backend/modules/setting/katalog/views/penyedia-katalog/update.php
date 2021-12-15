<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Library $model
 */

$this->title = Yii::t('app', 'Update') . ' ' .Yii::t('app', 'Library') . ' ' . $model->NAME;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Katalog'), 'url' => Url::to(['/setting/katalog'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Library'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="library-update">

    <?= $this->render('_form', [
        'model' => $model,
        'model2' => $model2,
                //'model3' => $model3,
    ]) ?>

</div>
