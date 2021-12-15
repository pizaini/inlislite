<?php

namespace common\models;

use Yii;
use \common\models\base\MasterKelasBesar as BaseMasterKelasBesar;

/**
 * This is the model class for table "master_kelas_besar".
 */
class MasterKelasBesar extends BaseMasterKelasBesar
{
	public $Copies;
/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kdKelas', 'namakelas'], 'required'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['kdKelas'], 'string', 'max' => 3],
            [['namakelas'], 'string', 'max' => 255],
            [['warna'], 'string', 'max' => 50],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']],
[['kdKelas', 'namakelas'],'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'kdKelas'  => Yii::t('app', 'Kode Klass'),
            'namakelas'  => Yii::t('app', 'Nama Klass'),
            'warna'  => Yii::t('app', 'Warna'),
        ];
    }

}
