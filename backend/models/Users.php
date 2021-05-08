<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "users".
 *
 * @property integer $ID
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property string $Fullname
 * @property string $EmailAddress
 * @property boolean $IsActive
 * @property string $SesID
 * @property string $MaxDateSesID
 * @property string $ActivationCode
 * @property integer $LoginAttemp
 * @property string $LastSubmtLogin
 * @property string $LastSuccess
 * @property integer $Department_id
 * @property integer $Branch_id
 * @property integer $Role_id
 * @property boolean $IsCanResetUserPassword
 * @property boolean $IsCanResetMemberPassword
 * @property boolean $IsAdvanceEntryCatalog
 * @property boolean $IsAdvanceEntryCollection
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $KIILastUploadDate
 *
 * @property \common\models\Agama[] $agamas
 * @property \common\models\Agama[] $agamas0
 * @property \common\models\Applications[] $applications
 * @property \common\models\Applications[] $applications0
 * @property \common\models\AuthAssignment[] $authAssignments
 * @property \common\models\AuthAssignment[] $authAssignments0
 * @property \common\models\AuthData[] $authDatas
 * @property \common\models\AuthData[] $authDatas0
 * @property \common\models\AuthHeader[] $authHeaders
 * @property \common\models\AuthHeader[] $authHeaders0
 * @property \common\models\AuthItem[] $authItems
 * @property \common\models\AuthItem[] $authItems0
 * @property \common\models\AuthItemChild[] $authItemChildren
 * @property \common\models\AuthItemChild[] $authItemChildren0
 * @property \common\models\AuthRule[] $authRules
 * @property \common\models\AuthRule[] $authRules0
 * @property \common\models\Bacaditempat[] $bacaditempats
 * @property \common\models\Bibidavailable[] $bibidavailables
 * @property \common\models\Bibidavailable[] $bibidavailables0
 * @property \common\models\Bookmark[] $bookmarks
 * @property \common\models\Bookmark[] $bookmarks0
 * @property \common\models\Branchs[] $branchs
 * @property \common\models\Branchs[] $branchs0
 * @property \common\models\Cardformats[] $cardformats
 * @property \common\models\Cardformats[] $cardformats0
 * @property \common\models\CatalogRuas[] $catalogRuas
 * @property \common\models\CatalogRuas[] $catalogRuas0
 * @property \common\models\CatalogSubruas[] $catalogSubruas
 * @property \common\models\CatalogSubruas[] $catalogSubruas0
 * @property \common\models\Catalogfiles[] $catalogfiles
 * @property \common\models\Catalogfiles[] $catalogfiles0
 * @property \common\models\Catalogs[] $catalogs
 * @property \common\models\Catalogs[] $catalogs0
 * @property \common\models\Catalogs[] $catalogs1
 * @property \common\models\Catalogstaging[] $catalogstagings
 * @property \common\models\Catalogstaging[] $catalogstagings0
 * @property \common\models\CheckpointLocations[] $checkpointLocations
 * @property \common\models\CheckpointLocations[] $checkpointLocations0
 * @property \common\models\Collectioncategorys[] $collectioncategorys
 * @property \common\models\Collectioncategorys[] $collectioncategorys0
 * @property \common\models\Collectioncategorysdefault[] $collectioncategorysdefaults
 * @property \common\models\Collectioncategorysdefault[] $collectioncategorysdefaults0
 * @property \common\models\Collectionloanitems[] $collectionloanitems
 * @property \common\models\Collectionloanitems[] $collectionloanitems0
 * @property \common\models\Collectionloans[] $collectionloans
 * @property \common\models\Collectionloans[] $collectionloans0
 * @property \common\models\Collectionlocations[] $collectionlocations
 * @property \common\models\Collectionlocations[] $collectionlocations0
 * @property \common\models\Collectionmedias[] $collectionmedias
 * @property \common\models\Collectionmedias[] $collectionmedias0
 * @property \common\models\Collectionrules[] $collectionrules
 * @property \common\models\Collectionrules[] $collectionrules0
 * @property \common\models\Collectionrulesitems[] $collectionrulesitems
 * @property \common\models\Collectionrulesitems[] $collectionrulesitems0
 * @property \common\models\Collections[] $collections
 * @property \common\models\Collections[] $collections0
 * @property \common\models\Collections[] $collections1
 * @property \common\models\Collections[] $collections2
 * @property \common\models\Collectionsources[] $collectionsources
 * @property \common\models\Collectionsources[] $collectionsources0
 * @property \common\models\Collectionstatus[] $collectionstatuses
 * @property \common\models\Collectionstatus[] $collectionstatuses0
 * @property \common\models\Colloclib[] $colloclibs
 * @property \common\models\Colloclib[] $colloclibs0
 * @property \common\models\Currency[] $currencies
 * @property \common\models\Currency[] $currencies0
 * @property \common\models\Departments[] $departments
 * @property \common\models\Departments[] $departments0
 * @property \common\models\Fielddatas[] $fielddatas
 * @property \common\models\Fielddatas[] $fielddatas0
 * @property \common\models\Fieldgroups[] $fieldgroups
 * @property \common\models\Fieldgroups[] $fieldgroups0
 * @property \common\models\Fieldindicator1s[] $fieldindicator1s
 * @property \common\models\Fieldindicator1s[] $fieldindicator1s0
 * @property \common\models\Fieldindicator2s[] $fieldindicator2s
 * @property \common\models\Fieldindicator2s[] $fieldindicator2s0
 * @property \common\models\Fields[] $fields
 * @property \common\models\Fields[] $fields0
 * @property \common\models\Formats[] $formats
 * @property \common\models\Formats[] $formats0
 * @property \common\models\Groupguesses[] $groupguesses
 * @property \common\models\Groupguesses[] $groupguesses0
 * @property \common\models\Historydata[] $historydatas
 * @property \common\models\Historydata[] $historydatas0
 * @property \common\models\Holidays[] $holidays
 * @property \common\models\Holidays[] $holidays0
 * @property \common\models\JenisAnggota[] $jenisAnggotas
 * @property \common\models\JenisAnggota[] $jenisAnggotas0
 * @property \common\models\JenisDenda[] $jenisDendas
 * @property \common\models\JenisDenda[] $jenisDendas0
 * @property \common\models\JenisKelamin[] $jenisKelamins
 * @property \common\models\JenisKelamin[] $jenisKelamins0
 * @property \common\models\JenisPelanggaran[] $jenisPelanggarans
 * @property \common\models\JenisPelanggaran[] $jenisPelanggarans0
 * @property \common\models\JenisPermohonan[] $jenisPermohonans
 * @property \common\models\JenisPermohonan[] $jenisPermohonans0
 * @property \common\models\JenisPerpustakaan[] $jenisPerpustakaans
 * @property \common\models\JenisPerpustakaan[] $jenisPerpustakaans0
 * @property \common\models\JudulKoleksi[] $judulKoleksis
 * @property \common\models\JudulKoleksi[] $judulKoleksis0
 * @property \common\models\Kabupaten[] $kabupatens
 * @property \common\models\Kabupaten[] $kabupatens0
 * @property \common\models\KataSandang[] $kataSandangs
 * @property \common\models\KataSandang[] $kataSandangs0
 * @property \common\models\KelasSiswa[] $kelasSiswas
 * @property \common\models\KelasSiswa[] $kelasSiswas0
 * @property \common\models\KelompokPelanggaran[] $kelompokPelanggarans
 * @property \common\models\KelompokPelanggaran[] $kelompokPelanggarans0
 * @property \common\models\KriteriaKoleksi[] $kriteriaKoleksis
 * @property \common\models\KriteriaKoleksi[] $kriteriaKoleksis0
 * @property \common\models\Library[] $libraries
 * @property \common\models\Library[] $libraries0
 * @property \common\models\Librarysearchcriteria[] $librarysearchcriterias
 * @property \common\models\Librarysearchcriteria[] $librarysearchcriterias0
 * @property \common\models\LocationLibrary[] $locationLibraries
 * @property \common\models\LocationLibrary[] $locationLibraries0
 * @property \common\models\LocationLibraryDefault[] $locationLibraryDefaults
 * @property \common\models\LocationLibraryDefault[] $locationLibraryDefaults0
 * @property \common\models\Locations[] $locations
 * @property \common\models\Locations[] $locations0
 * @property \common\models\Mailserver[] $mailservers
 * @property \common\models\Mailserver[] $mailservers0
 * @property \common\models\MasaBerlakuAnggota[] $masaBerlakuAnggotas
 * @property \common\models\MasaBerlakuAnggota[] $masaBerlakuAnggotas0
 * @property \common\models\MasterFakultas[] $masterFakultas
 * @property \common\models\MasterFakultas[] $masterFakultas0
 * @property \common\models\MasterJenisIdentitas[] $masterJenisIdentitas
 * @property \common\models\MasterJenisIdentitas[] $masterJenisIdentitas0
 * @property \common\models\MasterJurusan[] $masterJurusans
 * @property \common\models\MasterJurusan[] $masterJurusans0
 * @property \common\models\MasterKelasBesar[] $masterKelasBesars
 * @property \common\models\MasterKelasBesar[] $masterKelasBesars0
 * @property \common\models\MasterKependudukan[] $masterKependudukans
 * @property \common\models\MasterKependudukan[] $masterKependudukans0
 * @property \common\models\MasterPekerjaan[] $masterPekerjaans
 * @property \common\models\MasterPekerjaan[] $masterPekerjaans0
 * @property \common\models\MasterPendidikan[] $masterPendidikans
 * @property \common\models\MasterPendidikan[] $masterPendidikans0
 * @property \common\models\MasterRangeUmur[] $masterRangeUmurs
 * @property \common\models\MasterRangeUmur[] $masterRangeUmurs0
 * @property \common\models\MasterStatusPerkawinan[] $masterStatusPerkawinans
 * @property \common\models\MasterStatusPerkawinan[] $masterStatusPerkawinans0
 * @property \common\models\MemberFields[] $memberFields
 * @property \common\models\MemberFields[] $memberFields0
 * @property \common\models\MemberPerpanjangan[] $memberPerpanjangans
 * @property \common\models\MemberPerpanjangan[] $memberPerpanjangans0
 * @property \common\models\Memberguesses[] $memberguesses
 * @property \common\models\Memberguesses[] $memberguesses0
 * @property \common\models\Memberloanauthorizecategory[] $memberloanauthorizecategories
 * @property \common\models\Memberloanauthorizecategory[] $memberloanauthorizecategories0
 * @property \common\models\Memberloanauthorizelocation[] $memberloanauthorizelocations
 * @property \common\models\Memberloanauthorizelocation[] $memberloanauthorizelocations0
 * @property \common\models\Memberrules[] $memberrules
 * @property \common\models\Memberrules[] $memberrules0
 * @property \common\models\Members[] $members
 * @property \common\models\Members[] $members0
 * @property \common\models\MembersForm[] $membersForms
 * @property \common\models\MembersForm[] $membersForms0
 * @property \common\models\MembersFormList[] $membersFormLists
 * @property \common\models\MembersFormList[] $membersFormLists0
 * @property \common\models\MembersInfoForm[] $membersInfoForms
 * @property \common\models\MembersInfoForm[] $membersInfoForms0
 * @property \common\models\MembersLoanForm[] $membersLoanForms
 * @property \common\models\MembersLoanForm[] $membersLoanForms0
 * @property \common\models\MembersLoanreturnForm[] $membersLoanreturnForms
 * @property \common\models\MembersLoanreturnForm[] $membersLoanreturnForms0
 * @property \common\models\MembersOnlineForm[] $membersOnlineForms
 * @property \common\models\MembersOnlineForm[] $membersOnlineForms0
 * @property \common\models\MembersOnlineFormEdit[] $membersOnlineFormEdits
 * @property \common\models\MembersOnlineFormEdit[] $membersOnlineFormEdits0
 * @property \common\models\Membersonline[] $membersonlines
 * @property \common\models\Membersonline[] $membersonlines0
 * @property \common\models\Menu[] $menus
 * @property \common\models\Menu[] $menus0
 * @property \common\models\Migration[] $migrations
 * @property \common\models\Migration[] $migrations0
 * @property \common\models\Modules[] $modules
 * @property \common\models\Modules[] $modules0
 * @property \common\models\Opacfields[] $opacfields
 * @property \common\models\Opacfields[] $opacfields0
 * @property \common\models\Partners[] $partners
 * @property \common\models\Partners[] $partners0
 * @property \common\models\Pelanggaran[] $pelanggarans
 * @property \common\models\Pelanggaran[] $pelanggarans0
 * @property \common\models\Pengiriman[] $pengirimen
 * @property \common\models\Pengiriman[] $pengirimen0
 * @property \common\models\Propinsi[] $propinsis
 * @property \common\models\Propinsi[] $propinsis0
 * @property \common\models\Publishers[] $publishers
 * @property \common\models\Publishers[] $publishers0
 * @property \common\models\QuarantinedAuthData[] $quarantinedAuthDatas
 * @property \common\models\QuarantinedAuthData[] $quarantinedAuthDatas0
 * @property \common\models\QuarantinedAuthHeader[] $quarantinedAuthHeaders
 * @property \common\models\QuarantinedAuthHeader[] $quarantinedAuthHeaders0
 * @property \common\models\QuarantinedCatalogRuas[] $quarantinedCatalogRuas
 * @property \common\models\QuarantinedCatalogRuas[] $quarantinedCatalogRuas0
 * @property \common\models\QuarantinedCatalogSubruas[] $quarantinedCatalogSubruas
 * @property \common\models\QuarantinedCatalogSubruas[] $quarantinedCatalogSubruas0
 * @property \common\models\QuarantinedCatalogs[] $quarantinedCatalogs
 * @property \common\models\QuarantinedCatalogs[] $quarantinedCatalogs0
 * @property \common\models\QuarantinedCollections[] $quarantinedCollections
 * @property \common\models\QuarantinedCollections[] $quarantinedCollections0
 * @property \common\models\QuarantinedPengiriman[] $quarantinedPengirimen
 * @property \common\models\QuarantinedPengiriman[] $quarantinedPengirimen0
 * @property \common\models\Readinlocation[] $readinlocations
 * @property \common\models\Readinlocation[] $readinlocations0
 * @property \common\models\Refferenceitems[] $refferenceitems
 * @property \common\models\Refferenceitems[] $refferenceitems0
 * @property \common\models\Refferences[] $refferences
 * @property \common\models\Refferences[] $refferences0
 * @property \common\models\Requestcatalog[] $requestcatalogs
 * @property \common\models\Requestcatalog[] $requestcatalogs0
 * @property \common\models\RfidTemp[] $rfidTemps
 * @property \common\models\RfidTemp[] $rfidTemps0
 * @property \common\models\Rolemodule[] $rolemodules
 * @property \common\models\Rolemodule[] $rolemodules0
 * @property \common\models\Roles[] $roles
 * @property \common\models\Roles[] $roles0
 * @property \common\models\Settingcatalogdetail[] $settingcatalogdetails
 * @property \common\models\Settingcatalogdetail[] $settingcatalogdetails0
 * @property \common\models\Settingparameters[] $settingparameters
 * @property \common\models\Settingparameters[] $settingparameters0
 * @property \common\models\StatusAnggota[] $statusAnggotas
 * @property \common\models\StatusAnggota[] $statusAnggotas0
 * @property \common\models\Stockopname[] $stockopnames
 * @property \common\models\Stockopname[] $stockopnames0
 * @property \common\models\Stockopnamedetail[] $stockopnamedetails
 * @property \common\models\Stockopnamedetail[] $stockopnamedetails0
 * @property \common\models\Sumbangan[] $sumbangans
 * @property \common\models\Sumbangan[] $sumbangans0
 * @property \common\models\SumbanganKoleksi[] $sumbanganKoleksis
 * @property \common\models\SumbanganKoleksi[] $sumbanganKoleksis0
 * @property \common\models\Survey[] $surveys
 * @property \common\models\Survey[] $surveys0
 * @property \common\models\SurveyPertanyaan[] $surveyPertanyaans
 * @property \common\models\SurveyPertanyaan[] $surveyPertanyaans0
 * @property \common\models\SurveyPilihan[] $surveyPilihans
 * @property \common\models\SurveyPilihan[] $surveyPilihans0
 * @property \common\models\Tempnoinduk[] $tempnoinduks
 * @property \common\models\Tempnoinduk[] $tempnoinduks0
 * @property \common\models\TujuanKunjungan[] $tujuanKunjungans
 * @property \common\models\TujuanKunjungan[] $tujuanKunjungans0
 * @property \common\models\User[] $users
 * @property \common\models\User[] $users0
 * @property \common\models\Userloclibforcol[] $userloclibforcols
 * @property \common\models\Userloclibforcol[] $userloclibforcols0
 * @property \common\models\Userloclibforcol[] $userloclibforcols1
 * @property \common\models\Userloclibforloan[] $userloclibforloans
 * @property \common\models\Userloclibforloan[] $userloclibforloans0
 * @property \common\models\Userloclibforloan[] $userloclibforloans1
 * @property \common\models\Userlogs[] $userlogs
 * @property \common\models\Userlogs[] $userlogs0
 * @property \common\models\Userlogs[] $userlogs1
 * @property \common\models\Branchs $branch
 * @property \common\models\Departments $department
 * @property \common\models\Roles $role
 * @property \common\models\Users $updateBy
 * @property \common\models\Users[] $users1
 * @property \common\models\Users $createBy
 * @property \common\models\Users[] $users2
 * @property \common\models\Warnaddc[] $warnaddcs
 * @property \common\models\Warnaddc[] $warnaddcs0
 * @property \common\models\Worksheetfielditems[] $worksheetfielditems
 * @property \common\models\Worksheetfielditems[] $worksheetfielditems0
 * @property \common\models\Worksheetfields[] $worksheetfields
 * @property \common\models\Worksheetfields[] $worksheetfields0
 * @property \common\models\Worksheets[] $worksheets
 * @property \common\models\Worksheets[] $worksheets0
 */
class Users extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'Fullname', 'Role_id'], 'required'],
            [['status', 'LoginAttemp', 'Department_id', 'Branch_id', 'Role_id', 'CreateBy', 'UpdateBy', 'created_at', 'updated_at'], 'integer'],
            [['IsActive', 'IsCanResetUserPassword', 'IsCanResetMemberPassword', 'IsAdvanceEntryCatalog', 'IsAdvanceEntryCollection'], 'boolean'],
            [['MaxDateSesID', 'LastSubmtLogin', 'LastSuccess', 'CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['password', 'password_hash', 'password_reset_token', 'Fullname', 'EmailAddress', 'SesID', 'ActivationCode'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branchs::className(), 'targetAttribute' => ['Branch_id' => 'ID']],
            [['Department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::className(), 'targetAttribute' => ['Department_id' => 'ID']],
            [['Role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['Role_id' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'status' => Yii::t('app', 'Status'),
            'Fullname' => Yii::t('app', 'Fullname'),
            'EmailAddress' => Yii::t('app', 'Email Address'),
            'IsActive' => Yii::t('app', 'Is Active'),
            'SesID' => Yii::t('app', 'Ses ID'),
            'MaxDateSesID' => Yii::t('app', 'Max Date Ses ID'),
            'ActivationCode' => Yii::t('app', 'Activation Code'),
            'LoginAttemp' => Yii::t('app', 'Login Attemp'),
            'LastSubmtLogin' => Yii::t('app', 'Last Submt Login'),
            'LastSuccess' => Yii::t('app', 'Last Success'),
            'Department_id' => Yii::t('app', 'Department ID'),
            'Branch_id' => Yii::t('app', 'Branch ID'),
            'Role_id' => Yii::t('app', 'Role ID'),
            'IsCanResetUserPassword' => Yii::t('app', 'Is Can Reset User Password'),
            'IsCanResetMemberPassword' => Yii::t('app', 'Is Can Reset Member Password'),
            'IsAdvanceEntryCatalog' => Yii::t('app', 'Is Advance Entry Catalog'),
            'IsAdvanceEntryCollection' => Yii::t('app', 'Is Advance Entry Collection'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'KIILastUploadDate' => Yii::t('app', 'Kiilast Upload Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgamas()
    {
        return $this->hasMany(\common\models\Agama::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgamas0()
    {
        return $this->hasMany(\common\models\Agama::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(\common\models\Applications::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications0()
    {
        return $this->hasMany(\common\models\Applications::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(\common\models\AuthAssignment::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments0()
    {
        return $this->hasMany(\common\models\AuthAssignment::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthDatas()
    {
        return $this->hasMany(\common\models\AuthData::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthDatas0()
    {
        return $this->hasMany(\common\models\AuthData::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthHeaders()
    {
        return $this->hasMany(\common\models\AuthHeader::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthHeaders0()
    {
        return $this->hasMany(\common\models\AuthHeader::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(\common\models\AuthItem::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems0()
    {
        return $this->hasMany(\common\models\AuthItem::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(\common\models\AuthItemChild::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(\common\models\AuthItemChild::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthRules()
    {
        return $this->hasMany(\common\models\AuthRule::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthRules0()
    {
        return $this->hasMany(\common\models\AuthRule::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBacaditempats()
    {
        return $this->hasMany(\common\models\Bacaditempat::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBibidavailables()
    {
        return $this->hasMany(\common\models\Bibidavailable::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBibidavailables0()
    {
        return $this->hasMany(\common\models\Bibidavailable::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarks()
    {
        return $this->hasMany(\common\models\Bookmark::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookmarks0()
    {
        return $this->hasMany(\common\models\Bookmark::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchs()
    {
        return $this->hasMany(\common\models\Branchs::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchs0()
    {
        return $this->hasMany(\common\models\Branchs::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCardformats()
    {
        return $this->hasMany(\common\models\Cardformats::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCardformats0()
    {
        return $this->hasMany(\common\models\Cardformats::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogRuas()
    {
        return $this->hasMany(\common\models\CatalogRuas::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogRuas0()
    {
        return $this->hasMany(\common\models\CatalogRuas::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogSubruas()
    {
        return $this->hasMany(\common\models\CatalogSubruas::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogSubruas0()
    {
        return $this->hasMany(\common\models\CatalogSubruas::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogfiles()
    {
        return $this->hasMany(\common\models\Catalogfiles::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogfiles0()
    {
        return $this->hasMany(\common\models\Catalogfiles::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogs()
    {
        return $this->hasMany(\common\models\Catalogs::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogs0()
    {
        return $this->hasMany(\common\models\Catalogs::className(), ['QUARANTINEDBY' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogs1()
    {
        return $this->hasMany(\common\models\Catalogs::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogstagings()
    {
        return $this->hasMany(\common\models\Catalogstaging::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogstagings0()
    {
        return $this->hasMany(\common\models\Catalogstaging::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckpointLocations()
    {
        return $this->hasMany(\common\models\CheckpointLocations::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckpointLocations0()
    {
        return $this->hasMany(\common\models\CheckpointLocations::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectioncategorys()
    {
        return $this->hasMany(\common\models\Collectioncategorys::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectioncategorys0()
    {
        return $this->hasMany(\common\models\Collectioncategorys::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectioncategorysdefaults()
    {
        return $this->hasMany(\common\models\Collectioncategorysdefault::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectioncategorysdefaults0()
    {
        return $this->hasMany(\common\models\Collectioncategorysdefault::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloanitems()
    {
        return $this->hasMany(\common\models\Collectionloanitems::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloanitems0()
    {
        return $this->hasMany(\common\models\Collectionloanitems::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloans()
    {
        return $this->hasMany(\common\models\Collectionloans::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloans0()
    {
        return $this->hasMany(\common\models\Collectionloans::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionlocations()
    {
        return $this->hasMany(\common\models\Collectionlocations::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionlocations0()
    {
        return $this->hasMany(\common\models\Collectionlocations::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionmedias()
    {
        return $this->hasMany(\common\models\Collectionmedias::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionmedias0()
    {
        return $this->hasMany(\common\models\Collectionmedias::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionrules()
    {
        return $this->hasMany(\common\models\Collectionrules::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionrules0()
    {
        return $this->hasMany(\common\models\Collectionrules::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionrulesitems()
    {
        return $this->hasMany(\common\models\Collectionrulesitems::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionrulesitems0()
    {
        return $this->hasMany(\common\models\Collectionrulesitems::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return $this->hasMany(\common\models\Collections::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections0()
    {
        return $this->hasMany(\common\models\Collections::className(), ['JILIDCREATEBY' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections1()
    {
        return $this->hasMany(\common\models\Collections::className(), ['QUARANTINEDBY' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections2()
    {
        return $this->hasMany(\common\models\Collections::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionsources()
    {
        return $this->hasMany(\common\models\Collectionsources::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionsources0()
    {
        return $this->hasMany(\common\models\Collectionsources::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionstatuses()
    {
        return $this->hasMany(\common\models\Collectionstatus::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionstatuses0()
    {
        return $this->hasMany(\common\models\Collectionstatus::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColloclibs()
    {
        return $this->hasMany(\common\models\Colloclib::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColloclibs0()
    {
        return $this->hasMany(\common\models\Colloclib::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencies()
    {
        return $this->hasMany(\common\models\Currency::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencies0()
    {
        return $this->hasMany(\common\models\Currency::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(\common\models\Departments::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments0()
    {
        return $this->hasMany(\common\models\Departments::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFielddatas()
    {
        return $this->hasMany(\common\models\Fielddatas::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFielddatas0()
    {
        return $this->hasMany(\common\models\Fielddatas::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldgroups()
    {
        return $this->hasMany(\common\models\Fieldgroups::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldgroups0()
    {
        return $this->hasMany(\common\models\Fieldgroups::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldindicator1s()
    {
        return $this->hasMany(\common\models\Fieldindicator1s::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldindicator1s0()
    {
        return $this->hasMany(\common\models\Fieldindicator1s::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldindicator2s()
    {
        return $this->hasMany(\common\models\Fieldindicator2s::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldindicator2s0()
    {
        return $this->hasMany(\common\models\Fieldindicator2s::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(\common\models\Fields::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFields0()
    {
        return $this->hasMany(\common\models\Fields::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormats()
    {
        return $this->hasMany(\common\models\Formats::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormats0()
    {
        return $this->hasMany(\common\models\Formats::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupguesses()
    {
        return $this->hasMany(\common\models\Groupguesses::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupguesses0()
    {
        return $this->hasMany(\common\models\Groupguesses::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistorydatas()
    {
        return $this->hasMany(\common\models\Historydata::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistorydatas0()
    {
        return $this->hasMany(\common\models\Historydata::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHolidays()
    {
        return $this->hasMany(\common\models\Holidays::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHolidays0()
    {
        return $this->hasMany(\common\models\Holidays::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisAnggotas()
    {
        return $this->hasMany(\common\models\JenisAnggota::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisAnggotas0()
    {
        return $this->hasMany(\common\models\JenisAnggota::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisDendas()
    {
        return $this->hasMany(\common\models\JenisDenda::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisDendas0()
    {
        return $this->hasMany(\common\models\JenisDenda::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisKelamins()
    {
        return $this->hasMany(\common\models\JenisKelamin::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisKelamins0()
    {
        return $this->hasMany(\common\models\JenisKelamin::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPelanggarans()
    {
        return $this->hasMany(\common\models\JenisPelanggaran::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPelanggarans0()
    {
        return $this->hasMany(\common\models\JenisPelanggaran::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPermohonans()
    {
        return $this->hasMany(\common\models\JenisPermohonan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPermohonans0()
    {
        return $this->hasMany(\common\models\JenisPermohonan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPerpustakaans()
    {
        return $this->hasMany(\common\models\JenisPerpustakaan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPerpustakaans0()
    {
        return $this->hasMany(\common\models\JenisPerpustakaan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJudulKoleksis()
    {
        return $this->hasMany(\common\models\JudulKoleksi::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJudulKoleksis0()
    {
        return $this->hasMany(\common\models\JudulKoleksi::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKabupatens()
    {
        return $this->hasMany(\common\models\Kabupaten::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKabupatens0()
    {
        return $this->hasMany(\common\models\Kabupaten::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKataSandangs()
    {
        return $this->hasMany(\common\models\KataSandang::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKataSandangs0()
    {
        return $this->hasMany(\common\models\KataSandang::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelasSiswas()
    {
        return $this->hasMany(\common\models\KelasSiswa::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelasSiswas0()
    {
        return $this->hasMany(\common\models\KelasSiswa::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelompokPelanggarans()
    {
        return $this->hasMany(\common\models\KelompokPelanggaran::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelompokPelanggarans0()
    {
        return $this->hasMany(\common\models\KelompokPelanggaran::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKriteriaKoleksis()
    {
        return $this->hasMany(\common\models\KriteriaKoleksi::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKriteriaKoleksis0()
    {
        return $this->hasMany(\common\models\KriteriaKoleksi::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibraries()
    {
        return $this->hasMany(\common\models\Library::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibraries0()
    {
        return $this->hasMany(\common\models\Library::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibrarysearchcriterias()
    {
        return $this->hasMany(\common\models\Librarysearchcriteria::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibrarysearchcriterias0()
    {
        return $this->hasMany(\common\models\Librarysearchcriteria::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLibraries()
    {
        return $this->hasMany(\common\models\LocationLibrary::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLibraries0()
    {
        return $this->hasMany(\common\models\LocationLibrary::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLibraryDefaults()
    {
        return $this->hasMany(\common\models\LocationLibraryDefault::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationLibraryDefaults0()
    {
        return $this->hasMany(\common\models\LocationLibraryDefault::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(\common\models\Locations::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations0()
    {
        return $this->hasMany(\common\models\Locations::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailservers()
    {
        return $this->hasMany(\common\models\Mailserver::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailservers0()
    {
        return $this->hasMany(\common\models\Mailserver::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasaBerlakuAnggotas()
    {
        return $this->hasMany(\common\models\MasaBerlakuAnggota::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasaBerlakuAnggotas0()
    {
        return $this->hasMany(\common\models\MasaBerlakuAnggota::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterFakultas()
    {
        return $this->hasMany(\common\models\MasterFakultas::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterFakultas0()
    {
        return $this->hasMany(\common\models\MasterFakultas::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterJenisIdentitas()
    {
        return $this->hasMany(\common\models\MasterJenisIdentitas::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterJenisIdentitas0()
    {
        return $this->hasMany(\common\models\MasterJenisIdentitas::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterJurusans()
    {
        return $this->hasMany(\common\models\MasterJurusan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterJurusans0()
    {
        return $this->hasMany(\common\models\MasterJurusan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKelasBesars()
    {
        return $this->hasMany(\common\models\MasterKelasBesar::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKelasBesars0()
    {
        return $this->hasMany(\common\models\MasterKelasBesar::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKependudukans()
    {
        return $this->hasMany(\common\models\MasterKependudukan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKependudukans0()
    {
        return $this->hasMany(\common\models\MasterKependudukan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterPekerjaans()
    {
        return $this->hasMany(\common\models\MasterPekerjaan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterPekerjaans0()
    {
        return $this->hasMany(\common\models\MasterPekerjaan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterPendidikans()
    {
        return $this->hasMany(\common\models\MasterPendidikan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterPendidikans0()
    {
        return $this->hasMany(\common\models\MasterPendidikan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterRangeUmurs()
    {
        return $this->hasMany(\common\models\MasterRangeUmur::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterRangeUmurs0()
    {
        return $this->hasMany(\common\models\MasterRangeUmur::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterStatusPerkawinans()
    {
        return $this->hasMany(\common\models\MasterStatusPerkawinan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterStatusPerkawinans0()
    {
        return $this->hasMany(\common\models\MasterStatusPerkawinan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberFields()
    {
        return $this->hasMany(\common\models\MemberFields::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberFields0()
    {
        return $this->hasMany(\common\models\MemberFields::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberPerpanjangans()
    {
        return $this->hasMany(\common\models\MemberPerpanjangan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberPerpanjangans0()
    {
        return $this->hasMany(\common\models\MemberPerpanjangan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberguesses()
    {
        return $this->hasMany(\common\models\Memberguesses::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberguesses0()
    {
        return $this->hasMany(\common\models\Memberguesses::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberloanauthorizecategories()
    {
        return $this->hasMany(\common\models\Memberloanauthorizecategory::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberloanauthorizecategories0()
    {
        return $this->hasMany(\common\models\Memberloanauthorizecategory::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberloanauthorizelocations()
    {
        return $this->hasMany(\common\models\Memberloanauthorizelocation::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberloanauthorizelocations0()
    {
        return $this->hasMany(\common\models\Memberloanauthorizelocation::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberrules()
    {
        return $this->hasMany(\common\models\Memberrules::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberrules0()
    {
        return $this->hasMany(\common\models\Memberrules::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(\common\models\Members::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers0()
    {
        return $this->hasMany(\common\models\Members::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersForms()
    {
        return $this->hasMany(\common\models\MembersForm::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersForms0()
    {
        return $this->hasMany(\common\models\MembersForm::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersFormLists()
    {
        return $this->hasMany(\common\models\MembersFormList::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersFormLists0()
    {
        return $this->hasMany(\common\models\MembersFormList::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersInfoForms()
    {
        return $this->hasMany(\common\models\MembersInfoForm::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersInfoForms0()
    {
        return $this->hasMany(\common\models\MembersInfoForm::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersLoanForms()
    {
        return $this->hasMany(\common\models\MembersLoanForm::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersLoanForms0()
    {
        return $this->hasMany(\common\models\MembersLoanForm::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersLoanreturnForms()
    {
        return $this->hasMany(\common\models\MembersLoanreturnForm::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersLoanreturnForms0()
    {
        return $this->hasMany(\common\models\MembersLoanreturnForm::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersOnlineForms()
    {
        return $this->hasMany(\common\models\MembersOnlineForm::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersOnlineForms0()
    {
        return $this->hasMany(\common\models\MembersOnlineForm::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersOnlineFormEdits()
    {
        return $this->hasMany(\common\models\MembersOnlineFormEdit::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersOnlineFormEdits0()
    {
        return $this->hasMany(\common\models\MembersOnlineFormEdit::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersonlines()
    {
        return $this->hasMany(\common\models\Membersonline::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembersonlines0()
    {
        return $this->hasMany(\common\models\Membersonline::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(\common\models\Menu::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus0()
    {
        return $this->hasMany(\common\models\Menu::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMigrations()
    {
        return $this->hasMany(\common\models\Migration::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMigrations0()
    {
        return $this->hasMany(\common\models\Migration::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(\common\models\Modules::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModules0()
    {
        return $this->hasMany(\common\models\Modules::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpacfields()
    {
        return $this->hasMany(\common\models\Opacfields::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpacfields0()
    {
        return $this->hasMany(\common\models\Opacfields::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartners()
    {
        return $this->hasMany(\common\models\Partners::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartners0()
    {
        return $this->hasMany(\common\models\Partners::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPelanggarans()
    {
        return $this->hasMany(\common\models\Pelanggaran::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPelanggarans0()
    {
        return $this->hasMany(\common\models\Pelanggaran::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengirimen()
    {
        return $this->hasMany(\common\models\Pengiriman::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengirimen0()
    {
        return $this->hasMany(\common\models\Pengiriman::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropinsis()
    {
        return $this->hasMany(\common\models\Propinsi::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropinsis0()
    {
        return $this->hasMany(\common\models\Propinsi::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPublishers()
    {
        return $this->hasMany(\common\models\Publishers::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPublishers0()
    {
        return $this->hasMany(\common\models\Publishers::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedAuthDatas()
    {
        return $this->hasMany(\common\models\QuarantinedAuthData::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedAuthDatas0()
    {
        return $this->hasMany(\common\models\QuarantinedAuthData::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedAuthHeaders()
    {
        return $this->hasMany(\common\models\QuarantinedAuthHeader::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedAuthHeaders0()
    {
        return $this->hasMany(\common\models\QuarantinedAuthHeader::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCatalogRuas()
    {
        return $this->hasMany(\common\models\QuarantinedCatalogRuas::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCatalogRuas0()
    {
        return $this->hasMany(\common\models\QuarantinedCatalogRuas::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCatalogSubruas()
    {
        return $this->hasMany(\common\models\QuarantinedCatalogSubruas::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCatalogSubruas0()
    {
        return $this->hasMany(\common\models\QuarantinedCatalogSubruas::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCatalogs()
    {
        return $this->hasMany(\common\models\QuarantinedCatalogs::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCatalogs0()
    {
        return $this->hasMany(\common\models\QuarantinedCatalogs::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCollections()
    {
        return $this->hasMany(\common\models\QuarantinedCollections::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCollections0()
    {
        return $this->hasMany(\common\models\QuarantinedCollections::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedPengirimen()
    {
        return $this->hasMany(\common\models\QuarantinedPengiriman::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedPengirimen0()
    {
        return $this->hasMany(\common\models\QuarantinedPengiriman::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReadinlocations()
    {
        return $this->hasMany(\common\models\Readinlocation::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReadinlocations0()
    {
        return $this->hasMany(\common\models\Readinlocation::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefferenceitems()
    {
        return $this->hasMany(\common\models\Refferenceitems::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefferenceitems0()
    {
        return $this->hasMany(\common\models\Refferenceitems::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefferences()
    {
        return $this->hasMany(\common\models\Refferences::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefferences0()
    {
        return $this->hasMany(\common\models\Refferences::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestcatalogs()
    {
        return $this->hasMany(\common\models\Requestcatalog::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestcatalogs0()
    {
        return $this->hasMany(\common\models\Requestcatalog::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRfidTemps()
    {
        return $this->hasMany(\common\models\RfidTemp::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRfidTemps0()
    {
        return $this->hasMany(\common\models\RfidTemp::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolemodules()
    {
        return $this->hasMany(\common\models\Rolemodule::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolemodules0()
    {
        return $this->hasMany(\common\models\Rolemodule::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(\common\models\Roles::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles0()
    {
        return $this->hasMany(\common\models\Roles::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingcatalogdetails()
    {
        return $this->hasMany(\common\models\Settingcatalogdetail::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingcatalogdetails0()
    {
        return $this->hasMany(\common\models\Settingcatalogdetail::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingparameters()
    {
        return $this->hasMany(\common\models\Settingparameters::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingparameters0()
    {
        return $this->hasMany(\common\models\Settingparameters::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusAnggotas()
    {
        return $this->hasMany(\common\models\StatusAnggota::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusAnggotas0()
    {
        return $this->hasMany(\common\models\StatusAnggota::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnames()
    {
        return $this->hasMany(\common\models\Stockopname::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnames0()
    {
        return $this->hasMany(\common\models\Stockopname::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnamedetails()
    {
        return $this->hasMany(\common\models\Stockopnamedetail::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockopnamedetails0()
    {
        return $this->hasMany(\common\models\Stockopnamedetail::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumbangans()
    {
        return $this->hasMany(\common\models\Sumbangan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumbangans0()
    {
        return $this->hasMany(\common\models\Sumbangan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumbanganKoleksis()
    {
        return $this->hasMany(\common\models\SumbanganKoleksi::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumbanganKoleksis0()
    {
        return $this->hasMany(\common\models\SumbanganKoleksi::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveys()
    {
        return $this->hasMany(\common\models\Survey::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveys0()
    {
        return $this->hasMany(\common\models\Survey::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyPertanyaans()
    {
        return $this->hasMany(\common\models\SurveyPertanyaan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyPertanyaans0()
    {
        return $this->hasMany(\common\models\SurveyPertanyaan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyPilihans()
    {
        return $this->hasMany(\common\models\SurveyPilihan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyPilihans0()
    {
        return $this->hasMany(\common\models\SurveyPilihan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTempnoinduks()
    {
        return $this->hasMany(\common\models\Tempnoinduk::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTempnoinduks0()
    {
        return $this->hasMany(\common\models\Tempnoinduk::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTujuanKunjungans()
    {
        return $this->hasMany(\common\models\TujuanKunjungan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTujuanKunjungans0()
    {
        return $this->hasMany(\common\models\TujuanKunjungan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(\common\models\User::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers0()
    {
        return $this->hasMany(\common\models\User::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserloclibforcols()
    {
        return $this->hasMany(\common\models\Userloclibforcol::className(), ['User_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserloclibforcols0()
    {
        return $this->hasMany(\common\models\Userloclibforcol::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserloclibforcols1()
    {
        return $this->hasMany(\common\models\Userloclibforcol::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserloclibforloans()
    {
        return $this->hasMany(\common\models\Userloclibforloan::className(), ['User_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserloclibforloans0()
    {
        return $this->hasMany(\common\models\Userloclibforloan::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserloclibforloans1()
    {
        return $this->hasMany(\common\models\Userloclibforloan::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserlogs()
    {
        return $this->hasMany(\common\models\Userlogs::className(), ['User_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserlogs0()
    {
        return $this->hasMany(\common\models\Userlogs::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserlogs1()
    {
        return $this->hasMany(\common\models\Userlogs::className(), ['CreateBy' => 'ID']);
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
    public function getDepartment()
    {
        return $this->hasOne(\common\models\Departments::className(), ['ID' => 'Department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(\common\models\Roles::className(), ['ID' => 'Role_id']);
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
    public function getUsers1()
    {
        return $this->hasMany(\common\models\Users::className(), ['UpdateBy' => 'ID']);
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
    public function getUsers2()
    {
        return $this->hasMany(\common\models\Users::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarnaddcs()
    {
        return $this->hasMany(\common\models\Warnaddc::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarnaddcs0()
    {
        return $this->hasMany(\common\models\Warnaddc::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheetfielditems()
    {
        return $this->hasMany(\common\models\Worksheetfielditems::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheetfielditems0()
    {
        return $this->hasMany(\common\models\Worksheetfielditems::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheetfields()
    {
        return $this->hasMany(\common\models\Worksheetfields::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheetfields0()
    {
        return $this->hasMany(\common\models\Worksheetfields::className(), ['UpdateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheets()
    {
        return $this->hasMany(\common\models\Worksheets::className(), ['CreateBy' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorksheets0()
    {
        return $this->hasMany(\common\models\Worksheets::className(), ['UpdateBy' => 'ID']);
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
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
