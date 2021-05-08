<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "mailserver".
 *
 * @property integer $ID
 * @property string $Modul
 * @property string $Host
 * @property integer $Port
 * @property string $CredentialMail
 * @property string $CredentialPassword
 * @property boolean $EnableSsl
 * @property string $MailFrom
 * @property string $MailDisplayName
 * @property boolean $IsActive
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class Mailserver extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mailserver';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Modul', 'Host', 'Port', 'CredentialMail', 'CredentialPassword', 'MailFrom', 'MailDisplayName'], 'required'],
            [['Port', 'CreateBy', 'UpdateBy'], 'integer'],
            [['EnableSsl', 'IsActive'], 'boolean'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Modul', 'Host', 'CredentialMail', 'CredentialPassword', 'MailFrom', 'MailDisplayName'], 'string', 'max' => 50],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
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
            'Modul' => Yii::t('app', 'Modul'),
            'Host' => Yii::t('app', 'Host'),
            'Port' => Yii::t('app', 'Port'),
            'CredentialMail' => Yii::t('app', 'Credential Mail'),
            'CredentialPassword' => Yii::t('app', 'Credential Password'),
            'EnableSsl' => Yii::t('app', 'Enable Ssl'),
            'MailFrom' => Yii::t('app', 'Mail From'),
            'MailDisplayName' => Yii::t('app', 'Mail Display Name'),
            'IsActive' => Yii::t('app', 'Is Active'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
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
