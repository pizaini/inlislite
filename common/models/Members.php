<?php

namespace common\models;

use Yii;
use \common\models\base\Members as BaseMembers;

use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\helpers\Json;
use Imagine\Image\Box;
use Imagine\Image\Point;
use common\components\MemberHelpers;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

//use \common\models\MemberFields;
/**
 * This is the model class for table "members".
 */
class Members extends BaseMembers
{

    public $locationCategory;
    public $collectionCategory;
    public $dateRange;

    public $image;
    public $crop_info;

    public $password;
    //public $TglLahir;
    //public $TglRegisterDate;
    //public $TglEndDate;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'image',
                'file',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
            ],

            [['Fullname','IdentityNo','Sex_id','IdentityType_id','StatusAnggota_id', 'password','Email','AddressNow','Address'], 'required', 'on' => 'register'],

            [['password'], 'string', 'min' => 6],
            ['Email', 'email'],
            [['MemberNo', 'RT', 'RW', 'RTNow', 'RWNow', 'TahunAjaran'], 'string', 'max' => 50],
            ['crop_info', 'safe'],
            [['TglLahir','MemberNo', 'Fullname','IdentityNo','Sex_id','IdentityType_id','StatusAnggota_id','TglRegisterDate', 'TglEndDate', 'JenisAnggota_id'], 'required'],
            [['MemberNo', 'IdentityNo'],'unique'],
            [['DateOfBirth', 'RegisterDate', 'EndDate', 'CreateDate', 'UpdateDate','PhotoUrl'], 'safe'],
            [['IdentityType_id', 'EducationLevel_id', 'MaritalStatus_id', 'Job_id', 'JenisPermohonan_id', 'JenisAnggota_id', 'StatusAnggota_id', 'LoanReturnLateCount', 'Branch_id', 'Kelas_id', 'Agama_id', 'Jurusan_id', 'Fakultas_id','ProgramStudi_id', 'UnitKerja_id','JenjangPendidikan_id'], 'integer'],
            [['IsLunasBiayaPendaftaran'], 'boolean'],
            [['BiayaPendaftaran'], 'number'],
            [['MemberNo', 'Fullname', 'PlaceOfBirth', 'Address', 'AddressNow', 'Phone', 'InstitutionName', 'InstitutionAddress', 'InstitutionPhone', 'IdentityNo', 'MotherMaidenName', 'Email', 'NoHp', 'NamaDarurat', 'TelpDarurat', 'AlamatDarurat', 'StatusHubunganDarurat', 'KeteranganLain','PhotoUrl'], 'string', 'max' => 255],
            [['Fullname','DateOfBirth','PlaceOfBirth'], 'validateNameTTL'],

            //[['CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['City', 'Province', 'CityNow', 'ProvinceNow'], 'string', 'max' => 45],
           [['Phone', 'IdentityNo', 'MotherMaidenName', 'Email', 'CreateTerminal', 'UpdateTerminal', 'NoHp', 'NamaDarurat', 'TelpDarurat', 'StatusHubunganDarurat', 'Kecamatan', 'Kelurahan', 'KecamatanNow', 'KelurahanNow'], 'string', 'max' => 100],
            [['Branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branchs::className(), 'targetAttribute' => ['Branch_id' => 'ID']],
            [['JenisPermohonan_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisPermohonan::className(), 'targetAttribute' => ['JenisPermohonan_id' => 'ID']],
            [['StatusAnggota_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusAnggota::className(), 'targetAttribute' => ['StatusAnggota_id' => 'id']],
            [['MaritalStatus_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterStatusPerkawinan::className(), 'targetAttribute' => ['MaritalStatus_id' => 'id']],
            [['Agama_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agama::className(), 'targetAttribute' => ['Agama_id' => 'ID']],
            [['EducationLevel_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPendidikan::className(), 'targetAttribute' => ['EducationLevel_id' => 'id']],
            [['Fakultas_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterFakultas::className(), 'targetAttribute' => ['Fakultas_id' => 'id']],
            [['IdentityType_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJenisIdentitas::className(), 'targetAttribute' => ['IdentityType_id' => 'id']],
            [['JenisAnggota_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisAnggota::className(), 'targetAttribute' => ['JenisAnggota_id' => 'id']],
            [['Job_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPekerjaan::className(), 'targetAttribute' => ['Job_id' => 'id']],
            [['Jurusan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJurusan::className(), 'targetAttribute' => ['Jurusan_id' => 'id']],
            [['Kelas_id'], 'exist', 'skipOnError' => true, 'targetClass' => KelasSiswa::className(), 'targetAttribute' => ['Kelas_id' => 'id']],
            //[['MasaBerlaku_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasaBerlakuAnggota::className(), 'targetAttribute' => ['MasaBerlaku_id' => 'id']],
            [['Sex_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisKelamin::className(), 'targetAttribute' => ['Sex_id' => 'ID']],
            [['UnitKerja_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::className(), 'targetAttribute' => ['UnitKerja_id' => 'ID']],
            [['JenjangPendidikan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJenjangPendidikan::className(), 'targetAttribute' => ['JenjangPendidikan_id' => 'ID']],

        ];
    }

    /* public function addressAlreadyInUse($address,$list_id,$owner_id)
     {
         if (Elist::model()->active()->countByAttributes(array('address'=>$address,'owner_id'=>$owner_id),'id<>'.$list_id)>0) {
         return true;
        } else
        return false;
    }*/
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'MemberNo' => Yii::t('app', 'Member Number'),
            'Fullname' => Yii::t('app', 'Nama Lengkap'),
            'PlaceOfBirth' => Yii::t('app', 'Place Of Birth'),
            'DateOfBirth' => Yii::t('app', 'Date Of Birth'),
            'Address' => Yii::t('app', 'Address'),
            'AddressNow' => Yii::t('app', 'Address Now'),
            'Phone' => Yii::t('app', 'Phone'),
            'InstitutionName' => Yii::t('app', 'Institution Name'),
            'InstitutionAddress' => Yii::t('app', 'Institution Address'),
            'InstitutionPhone' => Yii::t('app', 'Institution Phone'),
            'IdentityType_id' => Yii::t('app', 'Jenis Identitas'),
            'IdentityNo' => Yii::t('app', 'Nomor Identitas'),
            'EducationLevel_id' => Yii::t('app', 'EducationLevel_id'),
            'Sex_id' => Yii::t('app', 'Jenis Kelamin'),
            'MaritalStatus_id' => Yii::t('app', 'MaritalStatus_id'),
            'Job_id' => Yii::t('app', 'Pekerjaan'),
            'TglRegisterDate' => Yii::t('app', 'TglRegisterDate'),
            'TglLahir' => Yii::t('app', 'Tanggal Lahir'),
            'TglEndDate' => Yii::t('app', 'Masa Berlaku'),
            'MotherMaidenName' => Yii::t('app', 'Nama Ibu Kandung'),
            'Email' => Yii::t('app', 'Email'),
            'JenisPermohonan_id' => Yii::t('app', 'JenisPermohonan_id'),
            'JenisAnggota_id' => Yii::t('app', 'Jenis Anggota'),
            'StatusAnggota_id' => Yii::t('app', 'Status Anggota'),
            'Handphone' => Yii::t('app', 'Handphone'),
            'ParentName' => Yii::t('app', 'Parent Name'),
            'ParentAddress' => Yii::t('app', 'Parent Address'),
            'ParentPhone' => Yii::t('app', 'Parent Phone'),
            'ParentHandphone' => Yii::t('app', 'Parent Handphone'),
            'Nationality' => Yii::t('app', 'Nationality'),
            'LoanReturnLateCount' => Yii::t('app', 'Loan Return Late Count'),
            'Branch_id' => Yii::t('app', 'Branch ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'AlamatDomisili' => Yii::t('app', 'Alamat Domisili'),
            'RT' => Yii::t('app', 'Rt'),
            'RW' => Yii::t('app', 'Rw'),
            'Kelurahan' => Yii::t('app', 'Kelurahan'),
            'Kecamatan' => Yii::t('app', 'Kecamatan'),
            'Kota' => Yii::t('app', 'Kota'),
            'KodePos' => Yii::t('app', 'Kode Pos'),
            'NoHp' => Yii::t('app', 'Nomor Handphone'),
            'NamaDarurat' => Yii::t('app', 'Nama Darurat'),
            'TelpDarurat' => Yii::t('app', 'Telp Darurat'),
            'AlamatDarurat' => Yii::t('app', 'Alamat Darurat'),
            'StatusHubunganDarurat' => Yii::t('app', 'Status Hubungan Darurat'),
            'City' => Yii::t('app', 'City'),
            'Province' => Yii::t('app', 'Province'),
            'CityNow' => Yii::t('app', 'City Now'),
            'ProvinceNow' => Yii::t('app', 'Province Now'),
            'JobNameDetail' => Yii::t('app', 'Job Name Detail'),
            'Kelas_id' => Yii::t('app', 'Kelas_id'),
            'tahunAjaran' => Yii::t('app', 'Tahun Ajaran'),
            'Agama_id' => Yii::t('app', 'Agama'),
            //'MasaBerlaku_id' => Yii::t('app', 'MasaBerlaku_id'),
            'Jurusan_id' => Yii::t('app', 'Jurusan_id'),
            'Fakultas_id' => Yii::t('app', 'Fakultas_id'),
            'ProgramStudi_id' => Yii::t('app', 'ProgramStudi_id'),
            'UnitKerja_id' => Yii::t('app', 'UnitKerja_id'),
            'IsLunasBiayaPendaftaran' => Yii::t('app', 'IsLunasBiayaPendaftaran'),
            'BiayaPendaftaran' => Yii::t('app', 'Biaya Pendaftaran'),
            'Kecamatan' => Yii::t('app', 'Kecamatan'),
            'Kelurahan' => Yii::t('app', 'Kelurahan'),
            'RT' => Yii::t('app', 'Rt'),
            'RW' => Yii::t('app', 'Rw'),
            'KecamatanNow' => Yii::t('app', 'Kecamatan saat ini'),
            'KelurahanNow' => Yii::t('app', 'Kelurahan saat ini'),
            'RTNow' => Yii::t('app', 'RT saat ini'),
            'RWNow' => Yii::t('app', 'RW saat ini'),
            'PhotoUrl' => Yii::t('app', 'URL Foto'),
            'JenjangPendidikan_id' => Yii::t('app', 'JenjangPendidikan_id'),

        ];
    }

    public function getimageurl() {
        // return your image url here
        //return \Yii::$app->request->BaseUrl.'/uploads/'.$this->ID.'.jpg';

        /*$imgTemp = Yii::getAlias('@uploaded_files') . '/foto_anggota/temp/' . $this->ID . '.jpg';

        if (!file_exists($imgTemp)) {
            $image = '../../../uploaded_files/' .Yii::$app->params['pathFotoAnggota'] . '/temp/'."nophoto.jpg?timestamp=" . rand();
        } else {
           $image = '../../../uploaded_files/' .Yii::$app->params['pathFotoAnggota'] . '/temp/'.$this->ID.".jpg?timestamp=" . rand();
           
        }*/
        if ($this->PhotoUrl) {
            $img = Yii::getAlias('@uploaded_files') . '/foto_anggota/' . $this->PhotoUrl;
        } else

        $img = Yii::getAlias('@uploaded_files') . '/foto_anggota/' . $this->ID;

        if (!file_exists($img)) {
            $image = '../../../uploaded_files/' .Yii::$app->params['pathFotoAnggota'] . '/'."nophoto.jpg?timestamp=" . rand();
        } else {
           $image = '../../../uploaded_files/' .Yii::$app->params['pathFotoAnggota'] . '/'.$this->PhotoUrl."?timestamp=" . rand();
           
        }

        return $image;

    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramStudi()
    {
        return $this->hasOne(\common\models\MasterProgramStudi::className(), ['id' => 'ProgramStudi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberOnline()
    {
        return $this->hasOne(\common\models\Membersonline::className(), ['NoAnggota' => 'MemberNo']);
    }

    public function validateNameTTL()
    {
        if ($this->ID) {
           $cekDuplicateNamaTTL=\common\models\Members::find()->andWhere('ID != :ID',[':ID' => $this->ID])->andWhere('Fullname = :Fullname',[':Fullname' => $this->Fullname])->andWhere('DateOfBirth = :DateOfBirth',[':DateOfBirth' => $this->DateOfBirth])->andWhere('PlaceOfBirth = :PlaceOfBirth',[':PlaceOfBirth' => $this->PlaceOfBirth])->count();

        } else {
           $cekDuplicateNamaTTL=\common\models\Members::find()->andWhere('MemberNo != :MemberNo',[':MemberNo' => $this->MemberNo])->andWhere('Fullname = :Fullname',[':Fullname' => $this->Fullname])->andWhere('DateOfBirth = :DateOfBirth',[':DateOfBirth' => $this->DateOfBirth])->andWhere('PlaceOfBirth = :PlaceOfBirth',[':PlaceOfBirth' => $this->PlaceOfBirth])->count();
        }

            if ($cekDuplicateNamaTTL != 0 ) {
                $this->addError('Error Saving', 'Nama Lengkap '.$this->Fullname.' dan Tanggal Lahir '.$this->DateOfBirth.' dan Tempat Lahir '.$this->PlaceOfBirth.' sudah di gunakan');
            }

    }

    public function behaviors()
    {
        return [
            \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'CreateDate',
                'updatedAtAttribute' => 'UpdateDate',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'CreateBy',
                'updatedByAttribute' => 'UpdateBy',
            ],
            [
                'class' => TerminalBehavior::className(),
                'createdTerminalAttribute' => 'CreateTerminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                'value' => \Yii::$app->request->userIP,
            ],
            [
                'class'=>'common\components\behaviors\DateConverter',
                'physicalFormat'=>'Y-m-d',
                'attributes'=>[
                    'TglLahir' => 'DateOfBirth',
                    'TglRegisterDate' => 'RegisterDate',
                    'TglEndDate' => 'EndDate'
                ]
            ],
        ];
    }
}
