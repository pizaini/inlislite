<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel guestbook\models\MemberguessesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Memberguesses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="memberguesses-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Memberguesses'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'NoAnggota',
            'Nama',
            'Status',
            'MasaBerlaku',
            // 'Profesi',
            // 'PendidikanTerakhir',
            // 'JenisKelamin',
            // 'Alamat',
            // 'CreateBy',
            // 'CreateDate',
            // 'CreateTerminal',
            // 'UpdateBy',
            // 'UpdateDate',
            // 'UpdateTerminal',
            // 'Deskripsi',
            // 'LOCATIONLOANS_ID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
