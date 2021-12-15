<?php

namespace common\models;

use Yii;
use \common\models\base\Sumbangan as BaseSumbangan;

/**
 * This is the model class for table "sumbangan".
 */
class Sumbangan extends BaseSumbangan
{
    public $MemberNo;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Member_id', 'Jumlah'], 'number'],
            [['MemberNo'],'required'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate','MemberNo'], 'safe'],
            [['Keterangan'], 'string', 'max' => 45],
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
            'Member_id' => Yii::t('app', 'Member ID'),
            'MemberNo' => Yii::t('app', 'NoAnggota'),
            'Jumlah' => Yii::t('app', 'Jumlah'),
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
