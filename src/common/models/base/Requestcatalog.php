<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "requestcatalog".
 *
 * @property integer $ID
 * @property string $Type
 * @property string $Title
 * @property string $Subject
 * @property string $Author
 * @property string $PublishLocation
 * @property string $PublishYear
 * @property string $Publisher
 * @property string $Comments
 * @property double $MemberID
 * @property string $CallNumber
 * @property string $ControlNumber
 * @property string $DateRequest
 * @property string $Status
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property integer $WorksheetID
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Members $member
 * @property \common\models\Users $updateBy
 */
class Requestcatalog extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requestcatalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Title'], 'required'],
            [['Comments'], 'string'],
            [['MemberID'], 'number'],
            [['DateRequest', 'CreateDate', 'UpdateDate'], 'safe'],
            [['CreateBy', 'UpdateBy', 'WorksheetID'], 'integer'],
            [['Type', 'PublishYear', 'Publisher', 'CallNumber', 'ControlNumber'], 'string', 'max' => 50],
            [['Title', 'Subject', 'Author', 'PublishLocation'], 'string', 'max' => 255],
            [['Status'], 'string', 'max' => 20],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['MemberID'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['MemberID' => 'ID']],
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
            'Type' => Yii::t('app', 'Type'),
            'Title' => Yii::t('app', 'Title'),
            'Subject' => Yii::t('app', 'Subject'),
            'Author' => Yii::t('app', 'Author'),
            'PublishLocation' => Yii::t('app', 'Publish Location'),
            'PublishYear' => Yii::t('app', 'Publish Year'),
            'Publisher' => Yii::t('app', 'Publisher'),
            'Comments' => Yii::t('app', 'Comments'),
            'MemberID' => Yii::t('app', 'Member ID'),
            'CallNumber' => Yii::t('app', 'Call Number'),
            'ControlNumber' => Yii::t('app', 'Control Number'),
            'DateRequest' => Yii::t('app', 'Date Request'),
            'Status' => Yii::t('app', 'Status'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'WorksheetID' => Yii::t('app', 'Worksheet ID'),
        ];
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
        return $this->hasOne(\common\models\Members::className(), ['ID' => 'MemberID']);
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
