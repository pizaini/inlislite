<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "vw_catalog_marc".
 *
 * @property double $CatalogID
 * @property string $ControlNumber
 * @property string $BIBID
 * @property string $Title
 * @property string $Author
 * @property string $Tag
 * @property string $TagName
 * @property string $Indicator1
 * @property string $Indicator2
 * @property string $RuasValue
 * @property string $SubRuas
 * @property string $SubRuasName
 * @property string $SubRuasValue
 */
class VwCatalogMarc extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vw_catalog_marc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CatalogID'], 'number'],
            [['ControlNumber', 'BIBID', 'Title'], 'required'],
            [['Title', 'Author'], 'string'],
            [['ControlNumber', 'BIBID', 'SubRuas', 'SubRuasName'], 'string', 'max' => 255],
            [['Tag'], 'string', 'max' => 3],
            [['TagName'], 'string', 'max' => 100],
            [['Indicator1', 'Indicator2'], 'string', 'max' => 1],
            [['RuasValue', 'SubRuasValue'], 'string', 'max' => 4000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CatalogID' => Yii::t('app', 'Catalog ID'),
            'ControlNumber' => Yii::t('app', 'Control Number'),
            'BIBID' => Yii::t('app', 'Bibid'),
            'Title' => Yii::t('app', 'Title'),
            'Author' => Yii::t('app', 'Author'),
            'Tag' => Yii::t('app', 'Tag'),
            'TagName' => Yii::t('app', 'Tag Name'),
            'Indicator1' => Yii::t('app', 'Indicator1'),
            'Indicator2' => Yii::t('app', 'Indicator2'),
            'RuasValue' => Yii::t('app', 'Ruas Value'),
            'SubRuas' => Yii::t('app', 'Sub Ruas'),
            'SubRuasName' => Yii::t('app', 'Sub Ruas Name'),
            'SubRuasValue' => Yii::t('app', 'Sub Ruas Value'),
        ];
    }


    
}
