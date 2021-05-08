<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "serial_articlefiles".
 *
 * @property integer $ID
 * @property double $Articles_id
 * @property string $FileURL
 * @property string $FileFlash
 * @property integer $IsPublish
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property boolean $IsFromMember
 * @property double $Member_id
 *
 * @property \common\models\SerialArticles $articles
 */
class SerialArticlefiles extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'serial_articlefiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Articles_id'], 'required'],
            [['Articles_id', 'Member_id'], 'number'],
            [['IsPublish', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['IsFromMember'], 'boolean'],
            [['FileURL', 'FileFlash', 'sizeFile'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Articles_id'], 'exist', 'skipOnError' => true, 'targetClass' => SerialArticles::className(), 'targetAttribute' => ['Articles_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Articles_id' => Yii::t('app', 'Articles ID'),
            'FileURL' => Yii::t('app', 'File Url'),
            'FileFlash' => Yii::t('app', 'File Flash'),
            'IsPublish' => Yii::t('app', 'Is Publish'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'IsFromMember' => Yii::t('app', 'Is From Member'),
            'Member_id' => Yii::t('app', 'Member ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasOne(\common\models\SerialArticles::className(), ['id' => 'Articles_id']);
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
