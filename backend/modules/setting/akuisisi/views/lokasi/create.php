<?php

use yii\helpers\Html;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 * @var common\models\Locations $model
 */

$this->title = Yii::t('app', 'Create Locations') . ' Ruang';;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Akuisisi'), 'url' => Url::to(['/setting/akuisisi'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="locations-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
