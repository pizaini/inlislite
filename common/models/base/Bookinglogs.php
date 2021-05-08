<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "bookinglogs".
 *
 * @property string $memberId
 * @property double $collectionId
 * @property string $bookingDate
 * @property string $bookingExpired
 *
 * @property \common\models\Members $member
 */
class Bookinglogs extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bookinglogs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['collectionId'], 'number'],
            [['bookingDate', 'bookingExpired'], 'safe'],
            [['memberId'], 'string', 'max' => 50],
            [['memberId'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['memberId' => 'MemberNo']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'memberId' => Yii::t('app', 'Member ID'),
            'collectionId' => Yii::t('app', 'Collection ID'),
            'bookingDate' => Yii::t('app', 'Booking Date'),
            'bookingExpired' => Yii::t('app', 'Booking Expired'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(\common\models\Members::className(), ['MemberNo' => 'memberId']);
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
