<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model guestbook\models\Memberguesses */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Memberguesses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="memberguesses-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'NoAnggota',
            'Nama',
            'Status',
            'MasaBerlaku',
            'Profesi',
            'PendidikanTerakhir',
            'JenisKelamin',
            'Alamat',
            'CreateBy',
            'CreateDate',
            'CreateTerminal',
            'UpdateBy',
            'UpdateDate',
            'UpdateTerminal',
            'Deskripsi',
            'LOCATIONLOANS_ID',
        ],
    ]) ?>

</div>
