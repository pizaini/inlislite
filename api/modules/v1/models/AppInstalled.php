<?php

namespace api\modules\v1\models;

use Yii;


/**
 * This is the base-model class for table "app_installed".
 *
 * @property integer $ID
 * @property string $ActivationCode
 * @property string $PerpustakaanName
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property string $Type
 */
class AppInstalled extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_installed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ActivationCode', 'CreateDate','Type'], 'required'],
            [['PerpustakaanName'], 'string'],
            [['CreateDate'], 'safe'],
            [['ActivationCode'], 'string', 'max' => 255],
            [['CreateTerminal','Type'], 'string', 'max' => 100],
            [['ActivationCode'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'ActivationCode' => Yii::t('app', 'Activation Code'),
            'PerpustakaanName' => Yii::t('app', 'Perpustakaan Name'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'Type' => Yii::t('app', 'Type'),
        ];
    }

    

   
}
