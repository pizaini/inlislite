<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Holidays $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Hari Libur');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Holidays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holidays-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
