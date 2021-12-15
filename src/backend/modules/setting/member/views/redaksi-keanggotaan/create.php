<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Memberrules $model
 */

$this->title = Yii::t('app', 'Create Memberrules');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => Url::to(['/setting/member'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Memberrules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('

$(".textarea").wysihtml5();

');

?>
<div class="memberrules-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
