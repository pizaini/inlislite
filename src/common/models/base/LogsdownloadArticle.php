<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "logsdownload_article".
 *
 * @property string $id
 * @property string $User_id
 * @property string $ip
 * @property integer $articlefilesID
 * @property string $waktu
 *
 * @property \common\models\SerialArticlefiles $articlefiles
 * @property \common\models\Members $user
 */
class LogsdownloadArticle extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logsdownload_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['articlefilesID'], 'integer'],
            [['waktu'], 'safe'],
            [['User_id'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 15],
            [['articlefilesID'], 'exist', 'skipOnError' => true, 'targetClass' => SerialArticlefiles::className(), 'targetAttribute' => ['articlefilesID' => 'ID']],
            [['User_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['User_id' => 'MemberNo']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'User_id' => 'User ID',
            'ip' => 'Ip',
            'articlefilesID' => 'Articlefiles ID',
            'waktu' => 'Waktu',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticlefiles()
    {
        return $this->hasOne(\common\models\SerialArticlefiles::className(), ['ID' => 'articlefilesID']);
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
