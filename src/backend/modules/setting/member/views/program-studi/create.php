<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterProgramStudi $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Program Studi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Program Studis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-program-studi-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
