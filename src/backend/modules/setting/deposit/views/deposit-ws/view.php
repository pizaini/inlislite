<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DepositWs */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deposit Ws'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-ws-view">

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
            'jenis_penerbit',
            'id_deposit_group_penerbit_ws',
            'id_deposit_kelompok_penerbit_ws',
            'nama_penerbit',
            'alamat1',
            'alamat2',
            'alamat3',
            'kabupaten',
            'id_wilayah_ws',
            'kode_pos',
            'no_telp1',
            'no_telp2',
            'no_telp3',
            'no_fax',
            'email:email',
            'contact_person',
            'no_contact',
            'koleksi_per_tahun',
            'keterangan',
            'status',
        ],
    ]) ?>

</div>
