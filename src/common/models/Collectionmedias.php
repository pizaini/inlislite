<?php

namespace common\models;

use Yii;
use \common\models\base\Collectionmedias as BaseCollectionmedias;

/**
 * This is the model class for table "collectionmedias".
 */
class Collectionmedias extends BaseCollectionmedias
{
	public $Copies;

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Code' => Yii::t('app', 'Code'),
            'Name' => Yii::t('app', 'Name'),
            'Worksheet_id' => Yii::t('app', 'Worksheet ID'),
            'KodeBahanPustaka' => Yii::t('app', 'Kode Jenis Deposit'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'Copies' => Yii::t('app','Copies'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'Name'], 'required'],
            [['Worksheet_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['Code'], 'string', 'max' => 50],
            [['Name'], 'string', 'max' => 255],
            [['KodeBahanPustaka', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']],
            [['Worksheet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Worksheets::className(), 'targetAttribute' => ['Worksheet_id' => 'ID']],
            [['Code', 'Name'],'unique'] //adding
        ];
    }
}
