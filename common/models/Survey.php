<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

use \common\models\base\Survey as BaseSurvey;

/**
 * This is the model class for table "Survey".
 */
class Survey extends BaseSurvey
{
	
	public function rules()
    {
        return [
            [['NamaSurvey', 'TanggalMulai', 'TanggalSelesai'], 'required'],
            [['TanggalMulai', 'TanggalSelesai', 'CreateDate', 'UpdateDate'], 'safe'],
			[['TanggalMulai'], 'validateTanggalAwal'],
            [['TanggalSelesai'], 'validateTanggalAkhir'],
            [['IsActive'], 'boolean'],
            [['NomorUrut', 'TargetSurvey', 'HasilSurveyShow', 'CreateBy', 'UpdateBy'], 'integer'],
            [['RedaksiAwal', 'RedaksiAkhir'], 'string'],
            [['NamaSurvey'], 'string', 'max' => 200],
            [['Keterangan'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }

	
	public function attributeLabels()
    {
        return [
		    'ID' => 'ID',
            'NamaSurvey' => Yii::t('app', 'Nama Survey'),
            //'TanggalMulai' => 'Tanggal Mulai',
            //'TanggalSelesai' => 'Tanggal Selesai',
            'IsActive' => Yii::t('app', 'Is Active'),
            'NomorUrut' => Yii::t('app', 'Nomor Urut'),
            'TargetSurvey' => Yii::t('app', 'Target Survey'),
            'HasilSurveyShow' => Yii::t('app', 'Hasil Survey Show'),
            'RedaksiAwal' => Yii::t('app', 'Redaksi Awal'),
            'RedaksiAkhir' => Yii::t('app', 'Redaksi Akhir'),
            'Keterangan' => Yii::t('app', 'Keterangan'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
			'TglMulai' => Yii::t('app', 'Tgl.Mulai'),
			'TglSelesai' => Yii::t('app', 'Tanggal Selesai'),
		
		];
	}
	
	
	   /**
     * [validateTanggalAwal description]
     * @return [type] [description]
     */
    public function validateTanggalAwal()
    {

        if ($this->TanggalMulai >= $this->TanggalSelesai ) {
            $this->addError("TanggalMulai",Yii::t('app','Tgl Mulai harus lebih kecil dari Tgl Selesai'));
        }

    }

    /**
     * [validateTanggalAkhir description]
     * @return [type] [description]
     */
    public function validateTanggalAkhir()
    {
        if ($this->TanggalMulai >= $this->TanggalSelesai) {
            $this->addError("TanggalSelesai",Yii::t('app','Tgl Selesai harus lebih besar dari Tgl Mulai'));
        }

    }
	
    public function behaviors()
    {



        return [
            \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'CreateDate',
                'updatedAtAttribute' => 'UpdateDate',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'CreateBy',
                'updatedByAttribute' => 'UpdateBy',
            ],
            [
                'class' => TerminalBehavior::className(),
                'createdTerminalAttribute' => 'CreateTerminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                'value' => \Yii::$app->request->userIP,
            ],
            [
                'class'=>'common\components\behaviors\DateConverter',
                'physicalFormat'=>'Y-m-d',
                'attributes'=>[
                    'TglMulai' => 'TanggalMulai',
                    'TglSelesai' => 'TanggalSelesai',
                ]
            ],
        ];

    }
}

