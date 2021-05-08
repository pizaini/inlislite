<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Letter $model
 */

?>
<div class="letter-update">

    <div class="page-header">
        <h3>Koreksi Ucapan Terima Kasih</h3>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'letter_id' => Yii::$app->getRequest()->getQueryParam('id'),
    ]) ?>

</div>

<div class="letter-update">

    <div class="page-header">
        <h5> &nbsp;</h5>
    </div>

    <?= $this->render('letter-detail-list', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>



