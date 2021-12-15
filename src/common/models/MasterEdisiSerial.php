<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%master_edisi_serial}}".
 *
 * @property double $id
 * @property string $tgl_edisi_serial
 * @property integer $no_edisi_serial
 * @property integer $CreateBy
 * @property integer $UpdateBy
 */
class MasterEdisiSerial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%master_edisi_serial}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tgl_edisi_serial'], 'safe'],
            [['Catalog_id'], 'number'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['no_edisi_serial'], 'string', 'max' => 111],
            [['no_edisi_serial'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tgl_edisi_serial' => yii::t('app','Tanggal Terbit Edisi Serial'),
            'no_edisi_serial' => yii::t('app','Nomor Edisi Serial'),
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
