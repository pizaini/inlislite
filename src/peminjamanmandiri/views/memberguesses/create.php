<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model guestbook\models\Memberguesses */

$this->title = Yii::t('app', 'Create Memberguesses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Memberguesses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="memberguesses-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
