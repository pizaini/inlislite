<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\LocationLibrarySearch $searchModel
 */


?>
<div class="location-library-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $model,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
            'categoryLoan.Code',
            'categoryLoan.Name',
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

    ]); Pjax::end(); ?>

</div>
