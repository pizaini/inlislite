<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\MasterJenisIdentitas $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app','Jenis Identitas');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => Url::to(['/setting/member'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-jenis-identitas-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
