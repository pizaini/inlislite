<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 */

$this->title = Yii::t('app', 'Update Fields') . ' ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Katalog'), 'url' => Url::to(['/setting/katalog'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="fields-update">

<!--     <div class="page-header">
        <h3><span class="glyphicon glyphicon-edit"></span> Koreksi</h3>
    </div> -->

    <?= $this->render('_form', [
        'model' => $model,
        'newIndikator1' => $newIndikator1,
        'newIndikator2' => $newIndikator2,
        'newSubruas' => $newSubruas,
//        'indikator1' => $indikator1,
  //      'indikator2' => $indikator2,
    //    'subruas' => $subruas,
]) ?>

</div>
