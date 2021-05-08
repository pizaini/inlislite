<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "members".
 *
 * @property double $ID
 * @property string $MemberNo
 * @property string $Fullname
 * @property string $PlaceOfBirth
 * @property string $DateOfBirth
 * @property string $Address
 * @property string $AddressNow
 * @property string $Phone
 * @property string $InstitutionName
 * @property string $InstitutionAddress
 * @property string $InstitutionPhone
 * @property integer $IdentityType_id
 * @property string $IdentityNo
 * @property integer $EducationLevel_id
 * @property integer $Sex_id
 * @property integer $MaritalStatus_id
 * @property integer $Job_id
 * @property string $RegisterDate
 * @property string $EndDate
 * @property string $MotherMaidenName
 * @property string $Email
 * @property integer $JenisPermohonan_id
 * @property integer $JenisAnggota_id
 * @property integer $StatusAnggota_id
 * @property integer $LoanReturnLateCount
 * @property integer $Branch_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property string $NoHp
 * @property string $NamaDarurat
 * @property string $TelpDarurat
 * @property string $AlamatDarurat
 * @property string $StatusHubunganDarurat
 * @property string $City
 * @property string $Province
 * @property string $CityNow
 * @property string $ProvinceNow
 * @property string $Kecamatan
 * @property string $Kelurahan
 * @property string $RT
 * @property string $RW
 * @property string $KecamatanNow
 * @property string $KelurahanNow
 * @property string $RTNow
 * @property string $RWNow
 * @property integer $Kelas_id
 * @property string $TahunAjaran
 * @property integer $Agama_id
 * @property integer $Jurusan_id
 * @property integer $Fakultas_id
 * @property integer $UnitKerja_id
 * @property string $KeteranganLain
 * @property boolean $IsLunasBiayaPendaftaran
 * @property string $BiayaPendaftaran
 * @property string $TanggalBebasPustaka
 * @property string $KIILastUploadDate
 *
 * @property \common\models\Bacaditempat[] $bacaditempats
 * @property \common\models\Bookinglogs[] $bookinglogs
 * @property \common\models\Catalogfiles[] $catalogfiles
 * @property \common\models\Catalogs[] $catalogs
 * @property \common\models\Collectionloanextends[] $collectionloanextends
 * @property \common\models\Collectionloanitems[] $collectionloanitems
 * @property \common\models\Collectionloans[] $collectionloans
 * @property \common\models\Favorite[] $favorites
 * @property \common\models\Historydata[] $historydatas
 * @property \common\models\KeranjangAnggota[] $keranjangAnggotas
 * @property \common\models\Lockers[] $lockers
 * @property \common\models\MemberPerpanjangan[] $memberPerpanjangans
 * @property \common\models\Memberloanauthorizecategory[] $memberloanauthorizecategories
 * @property \common\models\Memberloanauthorizelocation[] $memberloanauthorizelocations
 * @property \common\models\Branchs $branch
 * @property \common\models\Agama $agama
 * @property \common\models\MasterPekerjaan $job
 * @property \common\models\JenisKelamin $sex
 * @property \common\models\Departments $unitKerja
 * @property \common\models\Users $createBy
 * @property \common\models\MasterPendidikan $educationLevel
 * @property \common\models\MasterFakultas $fakultas
 * @property \common\models\MasterJenisIdentitas $identityType
 * @property \common\models\JenisAnggota $jenisAnggota
 * @property \common\models\JenisPermohonan $jenisPermohonan
 * @property \common\models\MasterJurusan $jurusan
 * @property \common\models\KelasSiswa $kelas
 * @property \common\models\MasterStatusPerkawinan $maritalStatus
 * @property \common\models\StatusAnggota $statusAnggota
 * @property \common\models\Users $updateBy
 * @property \common\models\Opaclogs[] $opaclogs
 * @property \common\models\Pelanggaran[] $pelanggarans
 * @property \common\models\QuarantinedCatalogs[] $quarantinedCatalogs
 * @property \common\models\Requestcatalog[] $requestcatalogs
 * @property \common\models\Sumbangan[] $sumbangans
 */
class Members extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'members';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MemberNo', 'Fullname'], 'required'],
            [['DateOfBirth', 'RegisterDate', 'EndDate', 'CreateDate', 'UpdateDate', 'TanggalBebasPustaka', 'KIILastUploadDate','PhotoUrl'], 'safe'],
            [['IdentityType_id', 'EducationLevel_id', 'Sex_id', 'MaritalStatus_id', 'Job_id', 'JenisPermohonan_id', 'JenisAnggota_id', 'StatusAnggota_id', 'LoanReturnLateCount', 'Branch_id', 'CreateBy', 'UpdateBy', 'Kelas_id', 'Agama_id', 'Jurusan_id','ProgramStudi_id', 'Fakultas_id', 'UnitKerja_id','JenjangPendidikan_id'], 'integer'],
            [['IsLunasBiayaPendaftaran'], 'boolean'],
            [['BiayaPendaftaran'], 'number'],
            [['MemberNo', 'RT', 'RW', 'RTNow', 'RWNow', 'TahunAjaran'], 'string', 'max' => 50],
            [['Fullname', 'PlaceOfBirth', 'Address', 'AddressNow', 'InstitutionName', 'InstitutionAddress', 'InstitutionPhone', 'AlamatDarurat', 'City', 'Province', 'CityNow', 'ProvinceNow', 'KeteranganLain'], 'string', 'max' => 255],
            [['Phone', 'IdentityNo', 'MotherMaidenName', 'Email', 'CreateTerminal', 'UpdateTerminal', 'NoHp', 'NamaDarurat', 'TelpDarurat', 'StatusHubunganDarurat', 'Kecamatan', 'Kelurahan', 'KecamatanNow', 'KelurahanNow'], 'string', 'max' => 100],
            [['Branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branchs::className(), 'targetAttribute' => ['Branch_id' => 'ID']],
            [['Agama_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agama::className(), 'targetAttribute' => ['Agama_id' => 'ID']],
            [['Job_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPekerjaan::className(), 'targetAttribute' => ['Job_id' => 'id']],
            [['Sex_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisKelamin::className(), 'targetAttribute' => ['Sex_id' => 'ID']],
            [['UnitKerja_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::className(), 'targetAttribute' => ['UnitKerja_id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['EducationLevel_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPendidikan::className(), 'targetAttribute' => ['EducationLevel_id' => 'id']],
            [['Fakultas_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterFakultas::className(), 'targetAttribute' => ['Fakultas_id' => 'id']],
            [['IdentityType_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJenisIdentitas::className(), 'targetAttribute' => ['IdentityType_id' => 'id']],
            [['JenisAnggota_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisAnggota::className(), 'targetAttribute' => ['JenisAnggota_id' => 'id']],
            [['JenisPermohonan_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisPermohonan::className(), 'targetAttribute' => ['JenisPermohonan_id' => 'ID']],
            [['Jurusan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJurusan::className(), 'targetAttribute' => ['Jurusan_id' => 'id']],
            [['Kelas_id'], 'exist', 'skipOnError' => true, 'targetClass' => KelasSiswa::className(), 'targetAttribute' => ['Kelas_id' => 'id']],
            [['MaritalStatus_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterStatusPerkawinan::className(), 'targetAttribute' => ['MaritalStatus_id' => 'id']],
            [['StatusAnggota_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusAnggota::className(), 'targetAttribute' => ['StatusAnggota_id' => 'id']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']],
            [['JenjangPendidikan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJenjangPendidikan::className(), 'targetAttribute' => ['JenjangPendidikan_id' => 'ID']],
      
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'MemberNo' => Yii::t('app', 'Member No'),
            'Fullname' => Yii::t('app', 'Fullname'),
            'PlaceOfBirth' => Yii::t('app', 'Place Of Birth'),
            'DateOfBirth' => Yii::t('app', 'Date Of Birth'),
            'Address' => Yii::t('app', 'Address'),
            'AddressNow' => Yii::t('app', 'Address Now'),
            'Phone' => Yii::t('app', 'Phone'),
            'InstitutionName' => Yii::t('app', 'Institution Name'),
            'InstitutionAddress' => Yii::t('app', 'Institution Address'),
            'InstitutionPhone' => Yii::t('app', 'Institution Phone'),
            'IdentityType_id' => Yii::t('app', 'Identity Type ID'),
            'IdentityNo' => Yii::t('app', 'Identity No'),
            'EducationLevel_id' => Yii::t('app', 'Education Level ID'),
            'Sex_id' => Yii::t('app', 'Sex ID'),
            'MaritalStatus_id' => Yii::t('app', 'Marital Status ID'),
            'Job_id' => Yii::t('app', 'Job ID'),
            'RegisterDate' => Yii::t('app', 'Register Date'),
            'EndDate' => Yii::t('app', 'End Date'),
            'MotherMaidenName' => Yii::t('app', 'Mother Maiden Name'),
            'Email' => Yii::t('app', 'Email'),
            'JenisPermohonan_id' => Yii::t('app', 'Jenis Permohonan ID'),
            'JenisAnggota_id' => Yii::t('app', 'Jenis Anggota ID'),
            'StatusAnggota_id' => Yii::t('app', 'Status Anggota ID'),
            'LoanReturnLateCount' => Yii::t('app', 'Loan Return Late Count'),
            'Branch_id' => Yii::t('app', 'Branch ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'NoHp' => Yii::t('app', 'No Hp'),
            'NamaDarurat' => Yii::t('app', 'Nama Darurat'),
            'TelpDarurat' => Yii::t('app', 'Telp Darurat'),
            'AlamatDarurat' => Yii::t('app', 'Alamat Darurat'),
            'StatusHubunganDarurat' => Yii::t('app', 'Status Hubungan Darurat'),
            'City' => Yii::t('app', 'City'),
            'Province' => Yii::t('app', 'Province'),
            'CityNow' => Yii::t('app', 'City Now'),
            'ProvinceNow' => Yii::t('app', 'Province Now'),
            'Kecamatan' => Yii::t('app', 'Kecamatan'),
            'Kelurahan' => Yii::t('app', 'Kelurahan'),
            'RT' => Yii::t('app', 'Rt'),
            'RW' => Yii::t('app', 'Rw'),
            'KecamatanNow' => Yii::t('app', 'Kecamatan Now'),
            'KelurahanNow' => Yii::t('app', 'Kelurahan Now'),
            'RTNow' => Yii::t('app', 'Rtnow'),
            'RWNow' => Yii::t('app', 'Rwnow'),
            'Kelas_id' => Yii::t('app', 'Kelas ID'),
            'TahunAjaran' => Yii::t('app', 'Tahun Ajaran'),
            'Agama_id' => Yii::t('app', 'Agama ID'),
            'Jurusan_id' => Yii::t('app', 'Jurusan ID'),
            'Fakultas_id' => Yii::t('app', 'Fakultas ID'),
            'UnitKerja_id' => Yii::t('app', 'Unit Kerja ID'),
            'KeteranganLain' => Yii::t('app', 'Keterangan Lain'),
            'IsLunasBiayaPendaftaran' => Yii::t('app', 'Is Lunas Biaya Pendaftaran'),
            'BiayaPendaftaran' => Yii::t('app', 'Biaya Pendaftaran'),
            'TanggalBebasPustaka' => Yii::t('app', 'Tanggal Bebas Pustaka'),
            'KIILastUploadDate' => Yii::t('app', 'Kiilast Upload Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBacaditempats()
    {
        return $this->hasMany(\common\models\Bacaditempat::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookinglogs()
    {
        return $this->hasMany(\common\models\Bookinglogs::className(), ['memberId' => 'MemberNo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogfiles()
    {
        return $this->hasMany(\common\models\Catalogfiles::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogs()
    {
        return $this->hasMany(\common\models\Catalogs::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloanextends()
    {
        return $this->hasMany(\common\models\Collectionloanextends::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloanitems()
    {
        return $this->hasMany(\common\models\Collectionloanitems::className(), ['member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloans()
    {
        return $this->hasMany(\common\models\Collectionloans::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(\common\models\Favorite::className(), ['Member_Id' => 'MemberNo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistorydatas()
    {
        return $this->hasMany(\common\models\Historydata::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeranjangAnggotas()
    {
        return $this->hasMany(\common\models\KeranjangAnggota::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLockers()
    {
        return $this->hasMany(\common\models\Lockers::className(), ['no_member' => 'MemberNo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberPerpanjangans()
    {
        return $this->hasMany(\common\models\MemberPerpanjangan::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberloanauthorizecategories()
    {
        return $this->hasMany(\common\models\Memberloanauthorizecategory::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberloanauthorizelocations()
    {
        return $this->hasMany(\common\models\Memberloanauthorizelocation::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(\common\models\Branchs::className(), ['ID' => 'Branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgama()
    {
        return $this->hasOne(\common\models\Agama::className(), ['ID' => 'Agama_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(\common\models\MasterPekerjaan::className(), ['id' => 'Job_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSex()
    {
        return $this->hasOne(\common\models\JenisKelamin::className(), ['ID' => 'Sex_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitKerja()
    {
        return $this->hasOne(\common\models\Departments::className(), ['ID' => 'UnitKerja_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'CreateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEducationLevel()
    {
        return $this->hasOne(\common\models\MasterPendidikan::className(), ['id' => 'EducationLevel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFakultas()
    {
        return $this->hasOne(\common\models\MasterFakultas::className(), ['id' => 'Fakultas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdentityType()
    {
        return $this->hasOne(\common\models\MasterJenisIdentitas::className(), ['id' => 'IdentityType_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisAnggota()
    {
        return $this->hasOne(\common\models\JenisAnggota::className(), ['id' => 'JenisAnggota_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPermohonan()
    {
        return $this->hasOne(\common\models\JenisPermohonan::className(), ['ID' => 'JenisPermohonan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJurusan()
    {
        return $this->hasOne(\common\models\MasterJurusan::className(), ['id' => 'Jurusan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelas()
    {
        return $this->hasOne(\common\models\KelasSiswa::className(), ['id' => 'Kelas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasOne(\common\models\Departments::className(), ['ID' => 'UnitKerja_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaritalStatus()
    {
        return $this->hasOne(\common\models\MasterStatusPerkawinan::className(), ['id' => 'MaritalStatus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusAnggota()
    {
        return $this->hasOne(\common\models\StatusAnggota::className(), ['id' => 'StatusAnggota_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpaclogs()
    {
        return $this->hasMany(\common\models\Opaclogs::className(), ['User_id' => 'MemberNo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPelanggarans()
    {
        return $this->hasMany(\common\models\Pelanggaran::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCatalogs()
    {
        return $this->hasMany(\common\models\QuarantinedCatalogs::className(), ['Member_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestcatalogs()
    {
        return $this->hasMany(\common\models\Requestcatalog::className(), ['MemberID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumbangans()
    {
        return $this->hasMany(\common\models\Sumbangan::className(), ['Member_id' => 'ID']);
    }


/**
     * @inheritdoc
     * @return type array
     */ 
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
        ];
    }


    
}
