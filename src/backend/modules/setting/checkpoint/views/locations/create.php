<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Locations $model
 */

$this->title = Yii::t('app', 'Create Locations');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="locations-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
