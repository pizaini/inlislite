<?php

namespace common\models;

use Yii;
use \common\models\base\Settingparameters as BaseSettingparameters;

/**
 * This is the model class for table "settingparameters".
 */
class Settingparameters extends BaseSettingparameters
{
	public $NomorInduk;
    public $NomorIndukTengah;
public $file;
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'NomorInduk' => Yii::t('app', 'Nomor Induk'),
            'NomorIndukTengah' => Yii::t('app', 'Nomor Induk Tengah'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'file' , 'skipOnEmpty' => false,  'maxSize' => 3000000, 'tooBig' => 'File terlalu besar, maksimal 3MB', 'extensions' => 'mp3, ogg'],
            [['Name'], 'required'],
            [['Value'], 'string'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Name'], 'string', 'max' => 50],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }

}
