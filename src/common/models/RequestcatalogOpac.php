<?php

namespace common\models;

use Yii;
use \common\models\base\Requestcatalog as BaseRequestcatalog;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;
/**
 * This is the model class for table "requestcatalog".
 */
class RequestcatalogOpac extends BaseRequestcatalog
{
	public $noAnggota;
	public $Publishment;

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            /*'ID' => Yii::t('app', 'ID'),
            'Type' => Yii::t('app', 'Type'),*/
            'Title' => Yii::t('app', 'Judul'),
            /*'Subject' => Yii::t('app', 'Subject'),*/
            'Author' => Yii::t('app', 'Pengarang'),
            'PublishLocation' => Yii::t('app', 'Kota Terbit'),
            'PublishYear' => Yii::t('app', 'Tahun Terbit'),
            'Publisher' => Yii::t('app', 'Penerbit'),
            'Comments' => Yii::t('app', 'Keterangan'),
            'MemberID' => Yii::t('app', 'usulancoll_Member ID'),
            /*'CallNumber' => Yii::t('app', 'Call Number'),
            'ControlNumber' => Yii::t('app', 'Control Number'),*/
            'DateRequest' => Yii::t('app', 'usulancoll_Date Request'),
            'Status' => Yii::t('app', 'usulancoll_Status'),
            'noAnggota' => Yii::t('app', 'Nomor Anggota'),
            /*'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),*/
            'WorksheetID' => Yii::t('app', 'Jenis Bahan'),
            'Publishment' => Yii::t('app', 'usulancoll_Publishment')
        ];
    }
    public function rules()
    {
        return [
            // username and password are both required
            [['Title','noAnggota'], 'required'],
            [['Comments'], 'string'],
            [['MemberID'], 'number'],
            [['DateRequest', 'CreateDate', 'UpdateDate'], 'safe'],
            [['CreateBy', 'UpdateBy', 'WorksheetID'], 'integer'],
            [['Type', 'PublishYear', 'Publisher', 'CallNumber', 'ControlNumber'], 'string', 'max' => 50],
            [['Title', 'Subject', 'Author', 'PublishLocation'], 'string', 'max' => 255],
            [['Status'], 'string', 'max' => 20],
            // password is validated by validatePassword()
            ['noAnggota', 'validateMemberID'],
        ];
    }
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
                'class' => TerminalBehavior::className(),
                'createdTerminalAttribute' => 'CreateTerminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                'value' => \Yii::$app->request->userIP,
            ],
        ];
    }
    protected function getUser()
    {

        $this->noAnggota = Members::findOne(['MemberNo' => $this->noAnggota]);
        
        return $this->noAnggota;
    }
    public function validateMemberID($attribute, $params)
    {
        $user = $this->getUser();

        if (!$this->noAnggota) {
            $this->addError($attribute, Yii::t('app','Incorrect No.Anggota.'));
        } 
    }
}
