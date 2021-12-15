<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-view">
   <p> <a class="btn btn-warning" href="/inlislite3/backend/gii">Kembali</a>        <a class="btn btn-primary" href="/inlislite3/backend/gii/default/update?id=%24model-%3Eid">Koreksi</a>        <a class="btn btn-danger" href="/inlislite3/backend/gii/default/delete?id=%24model-%3Eid" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        ],
        ['attributes' => [
            'MemberNo',
            'Fullname',
            'PlaceOfBirth',
            [
                        'attribute'=>'DateOfBirth',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],
            'Address',
            'AddressNow',
            'Phone',
            'InstitutionName',
            'InstitutionAddress',
            'InstitutionPhone',
            'IdentityType_id',
            'IdentityNo',
            'EducationLevel_id',
            'Religion',
            'Sex_id',
            'MaritalStatus',
            'Job_id',
            [
                        'attribute'=>'RegisterDate',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],
            [
                        'attribute'=>'EndDate',
                        'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                        'type'=>DetailView::INPUT_WIDGET,
                        'widgetOptions'=> [
                            'class'=>DateControl::classname(),
                            'type'=>DateControl::FORMAT_DATETIME
                        ]
                    ],
            'BarCode',
            'PicPath',
            'MotherMaidenName',
            'Email:email',
            'JenisPermohonan',
            'JenisPermohonanName',
            'JenisAnggota_id',
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
            'Kelas_id',
            'tahunAjaran',
            'Agama_id',
            'MasaBerlaku_id',
            'Jurusan_id',
            'Fakultas_id',
            'UnitKerja_id',
        ],
       
    ]) ?>

</div>
