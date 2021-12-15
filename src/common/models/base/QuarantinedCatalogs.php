<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "quarantined_catalogs".
 *
 * @property double $ID
 * @property string $ControlNumber
 * @property string $BIBID
 * @property string $Title
 * @property string $Author
 * @property string $Edition
 * @property string $Publisher
 * @property string $PublishLocation
 * @property string $PublishYear
 * @property string $Subject
 * @property string $PhysicalDescription
 * @property string $ISBN
 * @property string $CallNumber
 * @property string $Note
 * @property string $Languages
 * @property string $DeweyNo
 * @property string $ApproveDateOPAC
 * @property integer $IsOPAC
 * @property boolean $IsBNI
 * @property boolean $IsKIN
 * @property boolean $IsRDA
 * @property string $CoverURL
 * @property integer $Branch_id
 * @property integer $Worksheet_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property string $MARC_LOC
 * @property string $PRESERVASI_ID
 * @property integer $QUARANTINEDBY
 * @property string $QUARANTINEDDATE
 * @property string $QUARANTINEDTERMINAL
 * @property double $Member_id
 * @property string $KIILastUploadDate
 *
 * @property \common\models\QuarantinedCatalogRuas[] $quarantinedCatalogRuas
 * @property \common\models\Users $createBy
 * @property \common\models\Members $member
 * @property \common\models\Users $qUARANTINEDBY
 * @property \common\models\Users $updateBy
 */
class QuarantinedCatalogs extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quarantined_catalogs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'ControlNumber', 'BIBID', 'Title', 'Worksheet_id'], 'required'],
            [['ID', 'Member_id'], 'number'],
            [['Note', 'MARC_LOC'], 'string'],
            [['ApproveDateOPAC', 'CreateDate', 'UpdateDate', 'QUARANTINEDDATE', 'KIILastUploadDate'], 'safe'],
            [['IsOPAC', 'Branch_id', 'Worksheet_id', 'CreateBy', 'UpdateBy', 'QUARANTINEDBY'], 'integer'],
            [['IsBNI', 'IsKIN', 'IsRDA'], 'boolean'],
            [['ControlNumber', 'BIBID'], 'string', 'max' => 50],
            [['Title', 'Author', 'Subject', 'ISBN', 'CallNumber'], 'string', 'max' => 700],
            [['Edition', 'Publisher', 'PublishLocation', 'PublishYear', 'PhysicalDescription', 'Languages', 'DeweyNo', 'CoverURL'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal', 'QUARANTINEDTERMINAL'], 'string', 'max' => 100],
            [['PRESERVASI_ID'], 'string', 'max' => 20],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['Member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['Member_id' => 'ID']],
            [['QUARANTINEDBY'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['QUARANTINEDBY' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'ControlNumber' => Yii::t('app', 'Control Number'),
            'BIBID' => Yii::t('app', 'Bibid'),
            'Title' => Yii::t('app', 'Title'),
            'Author' => Yii::t('app', 'Author'),
            'Edition' => Yii::t('app', 'Edition'),
            'Publisher' => Yii::t('app', 'Publisher'),
            'PublishLocation' => Yii::t('app', 'Publish Location'),
            'PublishYear' => Yii::t('app', 'Publish Year'),
            'Subject' => Yii::t('app', 'Subject'),
            'PhysicalDescription' => Yii::t('app', 'Physical Description'),
            'ISBN' => Yii::t('app', 'Isbn'),
            'CallNumber' => Yii::t('app', 'Call Number'),
            'Note' => Yii::t('app', 'Note'),
            'Languages' => Yii::t('app', 'Languages'),
            'DeweyNo' => Yii::t('app', 'Dewey No'),
            'ApproveDateOPAC' => Yii::t('app', 'Approve Date Opac'),
            'IsOPAC' => Yii::t('app', 'Is Opac'),
            'IsBNI' => Yii::t('app', 'Is Bni'),
            'IsKIN' => Yii::t('app', 'Is Kin'),
            'IsRDA' => Yii::t('app', 'Is Rda'),
            'CoverURL' => Yii::t('app', 'Cover Url'),
            'Branch_id' => Yii::t('app', 'Branch ID'),
            'Worksheet_id' => Yii::t('app', 'Worksheet ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'MARC_LOC' => Yii::t('app', 'Marc  Loc'),
            'PRESERVASI_ID' => Yii::t('app', 'Preservasi  ID'),
            'QUARANTINEDBY' => Yii::t('app', 'Quarantinedby'),
            'QUARANTINEDDATE' => Yii::t('app', 'Quarantineddate'),
            'QUARANTINEDTERMINAL' => Yii::t('app', 'Quarantinedterminal'),
            'Member_id' => Yii::t('app', 'Member ID'),
            'KIILastUploadDate' => Yii::t('app', 'Kiilast Upload Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuarantinedCatalogRuas()
    {
        return $this->hasMany(\common\models\QuarantinedCatalogRuas::className(), ['CatalogId' => 'ID']);
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
    public function getMember()
    {
        return $this->hasOne(\common\models\Members::className(), ['ID' => 'Member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQUARANTINEDBY()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'QUARANTINEDBY']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
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
