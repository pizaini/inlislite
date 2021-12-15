<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Stockopname $model
 */

$this->title = Yii::t('app', 'Create Stockopname');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Stockopnames'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stockopname-create">
   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
