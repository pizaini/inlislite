<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "notif_sms_gateway".
 *
 * @property integer $ID
 * @property integer $member_id
 * @property integer $number_HP
 * @property integer $collectionloanitem_id
 * @property string $send_message_text
 * @property string $criteria_message
 * @property string $send_date
 * @property string $send_status
 */
class NotifSmsGateway extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notif_sms_gateway';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'number_HP'], 'required'],
            [['member_id', 'number_HP', 'collectionloanitem_id'], 'integer'],
            [['send_message_text'], 'string'],
            [['send_date'], 'safe'],
            [['criteria_message'], 'string', 'max' => 20],
            [['send_status'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'number_HP' => Yii::t('app', 'Number  Hp'),
            'collectionloanitem_id' => Yii::t('app', 'Collectionloanitem ID'),
            'send_message_text' => Yii::t('app', 'Send Message Text'),
            'criteria_message' => Yii::t('app', 'Criteria Message'),
            'send_date' => Yii::t('app', 'Send Date'),
            'send_status' => Yii::t('app', 'Send Status'),
        ];
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
