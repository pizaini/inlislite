<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel guestbook\models\MembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Members');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Members'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'MemberNo',
            'Fullname',
            'PlaceOfBirth',
            'DateOfBirth',
            // 'Address',
            // 'AddressNow',
            // 'Phone',
            // 'InstitutionName',
            // 'InstitutionAddress',
            // 'InstitutionPhone',
            // 'IdentityType',
            // 'IdentityNo',
            // 'EducationLevel',
            // 'Religion',
            // 'Sex',
            // 'MaritalStatus',
            // 'JobName',
            // 'RegisterDate',
            // 'EndDate',
            // 'BarCode',
            // 'PicPath',
            // 'MotherMaidenName',
            // 'Email:email',
            // 'JenisPermohonan',
            // 'JenisPermohonanName',
            // 'JenisAnggota',
            // 'JenisAnggotaName',
            // 'StatusAnggota',
            // 'StatusAnggotaName',
            // 'Handphone',
            // 'ParentName',
            // 'ParentAddress',
            // 'ParentPhone',
            // 'ParentHandphone',
            // 'Nationality',
            // 'LoanReturnLateCount',
            // 'Branch_id',
            // 'User_id',
            // 'CreateBy',
            // 'CreateDate',
            // 'CreateTerminal',
            // 'UpdateBy',
            // 'UpdateDate',
            // 'UpdateTerminal',
            // 'AlamatDomisili',
            // 'RT',
            // 'RW',
            // 'Kelurahan',
            // 'Kecamatan',
            // 'Kota',
            // 'KodePos',
            // 'NoHp',
            // 'NamaDarurat',
            // 'TelpDarurat',
            // 'AlamatDarurat',
            // 'StatusHubunganDarurat',
            // 'City',
            // 'Province',
            // 'CityNow',
            // 'ProvinceNow',
            // 'JobNameDetail',
            // 'namakelassiswa',
            // 'tahunAjaran',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
