<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "opac_counter".
 *
 * @property integer $hit_id
 * @property string $ip_address
 * @property string $city
 * @property string $region_name
 * @property string $country
 * @property string $lat
 * @property string $long
 * @property string $create_at
 * @property string $update_at
 */
class OpacCounter extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'opac_counter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip_address'], 'required'],
            [['create_at', 'update_at'], 'safe'],
            [['ip_address'], 'string', 'max' => 30],
            [['city', 'region_name', 'country', 'lat', 'long'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hit_id' => Yii::t('app', 'Hit ID'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'city' => Yii::t('app', 'City'),
            'region_name' => Yii::t('app', 'Region Name'),
            'country' => Yii::t('app', 'Country'),
            'lat' => Yii::t('app', 'Lat'),
            'long' => Yii::t('app', 'Long'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
        ];
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
             \nhkey\arh\ActiveRecordHistoryBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',
                'updatedAtAttribute' => 'update_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            // [
            //     'class' => BlameableBehavior::className(),
            //     'createdByAttribute' => 'CreateBy',
            //     'updatedByAttribute' => 'UpdateBy',
            // ],
            // [
            //     // 'class' => TerminalBehavior::className(),
            //     // 'createdTerminalAttribute' => 'CreateTerminal',
            //     // 'updatedTerminalAttribute' => 'UpdateTerminal',
            //     // 'value' => \Yii::$app->request->userIP,
            // ],
        ];
    }


    
}
