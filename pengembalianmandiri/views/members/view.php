<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model guestbook\models\Members */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-view">

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
            'MemberNo',
            'Fullname',
            'PlaceOfBirth',
            'DateOfBirth',
            'Address',
            'AddressNow',
            'Phone',
            'InstitutionName',
            'InstitutionAddress',
            'InstitutionPhone',
            'IdentityType',
            'IdentityNo',
            'EducationLevel',
            'Religion',
            'Sex',
            'MaritalStatus',
            'JobName',
            'RegisterDate',
            'EndDate',
            'BarCode',
            'PicPath',
            'MotherMaidenName',
            'Email:email',
            'JenisPermohonan',
            'JenisPermohonanName',
            'JenisAnggota',
            'JenisAnggotaName',
            'StatusAnggota',
            'StatusAnggotaName',
            'Handphone',
            'ParentName',
            'ParentAddress',
            'ParentPhone',
            'ParentHandphone',
            'Nationality',
            'LoanReturnLateCount',
            'Branch_id',
            'User_id',
            'CreateBy',
            'CreateDate',
            'CreateTerminal',
            'UpdateBy',
            'UpdateDate',
            'UpdateTerminal',
            'AlamatDomisili',
            'RT',
            'RW',
            'Kelurahan',
            'Kecamatan',
            'Kota',
            'KodePos',
            'NoHp',
            'NamaDarurat',
            'TelpDarurat',
            'AlamatDarurat',
            'StatusHubunganDarurat',
            'City',
            'Province',
            'CityNow',
            'ProvinceNow',
            'JobNameDetail',
            'namakelassiswa',
            'tahunAjaran',
        ],
    ]) ?>

</div>
