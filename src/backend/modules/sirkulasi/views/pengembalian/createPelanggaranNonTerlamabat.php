<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Pelanggaran $model
 */

$this->title = Yii::t('app', 'Create').' '.Yii::t('app', 'Pelanggaran');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pelanggarans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pelanggaran-create">

    <?= $this->render('_formPelanggaran', [
        'model' => $model,
        'modelItem' => $modelItem,
        'for'=>$for
    ]) ?>

</div>
