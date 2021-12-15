<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\MasterPekerjaan $model
 */

$this->title = Yii::t('app', 'Update').' '.Yii::t('app','Cetak Bebas Pustaka') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => Url::to(['/setting/member'])];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bebas Pustaka'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="kelas-siswa-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
