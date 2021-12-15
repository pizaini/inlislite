<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "serial_articles_repeatable".
 *
 * @property string $ID
 * @property double $serial_article_ID
 * @property string $article_field
 * @property string $value
 * @property integer $CreateBy
 * @property integer $UpdateBy
 *
 * @property Users $createBy
 * @property SerialArticles $serialArticlce
 * @property Users $updateBy
 */
class SerialArticlesRepeatable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'serial_articles_repeatable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial_article_ID'], 'required'],
            [['serial_article_ID'], 'number'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['article_field'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 111],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['serial_article_ID'], 'exist', 'skipOnError' => true, 'targetClass' => SerialArticles::className(), 'targetAttribute' => ['serial_article_ID' => 'id']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'serial_article_ID' => 'Serial Articlce  ID',
            'article_field' => 'Article Field',
            'value' => 'Value',
            'CreateBy' => 'Create By',
            'UpdateBy' => 'Update By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(Users::className(), ['ID' => 'CreateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSerialArticlce()
    {
        return $this->hasOne(SerialArticles::className(), ['id' => 'serial_article_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(Users::className(), ['ID' => 'UpdateBy']);
    }

    public function behaviors()
    {
        return [
        \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'CreateBy',
                'updatedByAttribute' => 'UpdateBy',
            ],
        ];
    }
}
