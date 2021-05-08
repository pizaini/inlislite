<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyPertanyaan $model
 */

$this->title = Yii::t('app','Add').' '.Yii::t('app','Survey Pertanyaan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Survey Pertanyaan')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-pertanyaan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
