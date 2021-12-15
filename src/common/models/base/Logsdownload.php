<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "logsdownload".
 *
 * @property integer $id
 * @property string $User_id
 * @property string $ip
 * @property integer $catalogfilesID
 * @property integer $isLKD
 * @property string $waktu
 *
 * @property \common\models\Catalogfiles $catalogfiles
 * @property \common\models\Members $user
 */
class Logsdownload extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logsdownload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catalogfilesID', 'isLKD'], 'integer'],
            [['waktu'], 'safe'],
            [['User_id'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 15],
            [['catalogfilesID'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogfiles::className(), 'targetAttribute' => ['catalogfilesID' => 'ID']],
            [['User_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['User_id' => 'MemberNo']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'User_id' => Yii::t('app', 'User ID'),
            'ip' => Yii::t('app', 'Ip'),
            'catalogfilesID' => Yii::t('app', 'Catalogfiles ID'),
            'isLKD' => Yii::t('app', 'Is Lkd'),
            'waktu' => Yii::t('app', 'Waktu'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogfiles()
    {
        return $this->hasOne(\common\models\Catalogfiles::className(), ['ID' => 'catalogfilesID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\Members::className(), ['MemberNo' => 'User_id']);
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
