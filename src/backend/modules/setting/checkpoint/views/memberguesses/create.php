<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Memberguesses $model
 */

$this->title = Yii::t('app','Add').' '.Yii::t('app','Memberguesses');
$this->params['breadcrumbs'][] = ['label' => 'Memberguesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="memberguesses-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
