<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterJamBuka $model
 */

$this->title = Yii::t('app', 'Add').' '.Yii::t('app', 'Master Jam Buka');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Master Jam Buka'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-jam-buka-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
