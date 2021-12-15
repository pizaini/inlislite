<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "collectionsourcesub".
 *
 * @property integer $ID
 * @property string $Name
 * @property integer $Sort_ID
 * @property integer $CollectionSource_ID
 * @property string $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property string $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Collections[] $collections
 * @property \common\models\Collectionsources $collectionSource
 */
class Collectionsourcesub extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collectionsourcesub';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'CollectionSource_ID'], 'required'],
            [['Sort_ID', 'CollectionSource_ID'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Name'], 'string', 'max' => 255],
            [['CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CollectionSource_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionsources::className(), 'targetAttribute' => ['CollectionSource_ID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Name' => Yii::t('app', 'Name'),
            'Sort_ID' => Yii::t('app', 'Sort  ID'),
            'CollectionSource_ID' => Yii::t('app', 'Collection Source  ID'),
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
    public function getCollections()
    {
        return $this->hasMany(\common\models\Collections::className(), ['CollectionSourceSub_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionSource()
    {
        return $this->hasOne(\common\models\Collectionsources::className(), ['ID' => 'CollectionSource_ID']);
    }


    
}
