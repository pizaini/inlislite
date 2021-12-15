<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Cardformats $model
 */

$this->title = Yii::t('app', 'Create Cardformats');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Katalog'), 'url' => Url::to(['/setting/katalog'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cardformats'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>
<div class="cardformats-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
