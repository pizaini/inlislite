<?php

namespace common\models;

use Yii;
use \common\models\base\MemberPerpanjangan as BaseMemberPerpanjanganMandiri;

/**
 * This is the model class for table "member_perpanjangan".
 */
class MemberPerpanjanganMandiri extends BaseMemberPerpanjanganMandiri
{
    
    /**
     * @inheritdoc
     */

   public function behaviors()
    {
        return [
        
        ];
    }
    
    public function rules()
    {
        return [
            [['Biaya'], 'number'],
            [['Tanggal'], 'required'],
            [['Tanggal', 'CreateDate', 'UpdateDate'], 'safe'],
            [['IsLunas'], 'boolean'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['Keterangan'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['Member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['Member_id' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Member_id' => Yii::t('app', 'No.Anggota'),
            'Tanggal' => Yii::t('app', 'Tanggal Berakhir'),
            'Biaya' => Yii::t('app', 'Biaya'),
            'IsLunas' => Yii::t('app', 'Lunas'),
            'Keterangan' => Yii::t('app', 'Keterangan'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }
}
