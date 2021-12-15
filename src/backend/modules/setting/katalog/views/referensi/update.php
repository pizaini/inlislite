<?php

use yii\helpers\Html;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 * @var common\models\Refferences $model
 */

$this->title =  Yii::t('app', 'Update').' '.Yii::t('app', 'Refferences') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Katalog'), 'url' => Url::to(['/setting/katalog'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Refferences'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="refferences-update">


    <?= $this->render('_form', [
        'model' => $model,
        'model2' => $model2,
                     
    ]) ?>

</div>
