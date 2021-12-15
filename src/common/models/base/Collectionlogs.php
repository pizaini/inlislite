<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "collectionlogs".
 *
 * @property double $Collection_id
 * @property string $Dates
 * @property string $Names
 * @property string $Description
 * @property string $ModifyBy
 * @property string $Terminal
 *
 * @property \common\models\Collections $collection
 */
class Collectionlogs extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collectionlogs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Collection_id', 'Dates'], 'required'],
            [['Collection_id'], 'number'],
            [['Dates'], 'safe'],
            [['Names', 'Description', 'ModifyBy', 'Terminal'], 'string', 'max' => 255],
            [['Collection_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collections::className(), 'targetAttribute' => ['Collection_id' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Collection_id' => Yii::t('app', 'Collection ID'),
            'Dates' => Yii::t('app', 'Dates'),
            'Names' => Yii::t('app', 'Names'),
            'Description' => Yii::t('app', 'Description'),
            'ModifyBy' => Yii::t('app', 'Modify By'),
            'Terminal' => Yii::t('app', 'Terminal'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(\common\models\Collections::className(), ['ID' => 'Collection_id']);
    }


    
}
