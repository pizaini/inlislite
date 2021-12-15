<?php

namespace common\models;

use Yii;
use \common\models\base\Worksheets as BaseWorksheets;

/**
 * This is the model class for table "worksheets".
 */
class Worksheets extends BaseWorksheets
{
	public $TotalCatalogs;

	/**
     * @inheritdoc
     */
        /*
    public function rules()
    {
        return [
            [['Name', 'CODE'], 'required'],
            [['NoUrut'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['ISSERIAL'], 'boolean'],
            [['Name', 'CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['DEPOSITFORMAT_CODE'], 'string', 'max' => 5],
            [['CODE'], 'string', 'max' => 10],
            [['Name'], 'unique'],
            [['Format_id'], 'exist', 'skipOnError' => true, 'targetClass' => Formats::className(), 'targetAttribute' => ['Format_id' => 'ID']]
        ];
    }

	/**
     * @inheritdoc
     */
        /*
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Name' => Yii::t('app', 'Name'),
            'Format_id' => Yii::t('app', 'Format ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'NoUrut' => Yii::t('app', 'No Urut'),
            'DEPOSITFORMAT_CODE' => Yii::t('app', 'Depositformat  Code'),
            'ISSERIAL' => Yii::t('app', 'Isserial'),
            'CODE' => Yii::t('app', 'Code'),
            'TotalCatalogs' => Yii::t('app', 'Total Catalogs'),
        ];
    }

    public function loadJenisBahan() {
        $model= BaseWorksheets::find()
        ->addSelect(['ID','Name'])
        ->where(['Format_id'=>1])
        ->orderBy(['id' => SORT_ASC])
        ->all();
        return $model;
    }
         * /
         */
}
