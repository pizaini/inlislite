<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyIsian $model
 */

$this->title = 'Create Survey Isian';
$this->params['breadcrumbs'][] = ['label' => 'Survey Isians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-isian-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
