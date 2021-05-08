<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Agama $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Agama');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agamas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agama-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
