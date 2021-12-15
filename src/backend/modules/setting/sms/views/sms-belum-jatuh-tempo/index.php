<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'SMS Belum Jatuh Tempo');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sms'), 'url' => Url::to(['/setting/sms'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settingparameters-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
