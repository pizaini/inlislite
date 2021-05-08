<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "opaclogs_keyword".
 *
 * @property integer $Id
 * @property integer $OpaclogsId
 * @property string $Field
 * @property string $Keyword
 *
 * @property \common\models\Opaclogs $opaclogs
 */
class OpaclogsKeyword extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'opaclogs_keyword';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OpaclogsId'], 'required'],
            [['OpaclogsId'], 'integer'],
            [['Keyword'], 'string'],
            [['Field'], 'string', 'max' => 20],
            [['OpaclogsId'], 'exist', 'skipOnError' => true, 'targetClass' => Opaclogs::className(), 'targetAttribute' => ['OpaclogsId' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'OpaclogsId' => 'Opaclogs ID',
            'Field' => 'Field',
            'Keyword' => 'Keyword',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpaclogs()
    {
        return $this->hasOne(\common\models\Opaclogs::className(), ['ID' => 'OpaclogsId']);
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
