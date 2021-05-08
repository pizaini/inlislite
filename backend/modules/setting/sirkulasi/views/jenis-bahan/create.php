<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LocationLibrary $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Location Library');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Location Libraries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-library-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
