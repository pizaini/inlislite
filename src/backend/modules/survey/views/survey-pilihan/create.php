<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyPilihan $model
 */

$this->title = Yii::t('app','Tambah').Yii::t('app',' Survey Pilihan');
$this->params['breadcrumbs'][] = ['label' => 'Survey Pilihans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-pilihan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
