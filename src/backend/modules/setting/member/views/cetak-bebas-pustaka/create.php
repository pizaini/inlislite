<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\MasterPekerjaan $model
 */


$this->title = Yii::t('app', 'Add').' '.Yii::t('app','Cetak Bebas Pustaka');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => Url::to(['/setting/member'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kelas-siswa-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
