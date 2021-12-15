<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "saran".
 *
 * @property integer $ID
 * @property string $NoAnggota
 * @property string $Nama
 * @property string $Phone
 * @property string $Email
 * @property string $Saran
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 */
class Saran extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'saran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Saran'], 'string'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['NoAnggota', 'Email', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 50],
            [['Nama'], 'string', 'max' => 255],
            [['Phone'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NoAnggota' => 'No Anggota',
            'Nama' => 'Nama',
            'Phone' => 'Phone',
            'Email' => 'Email',
            'Saran' => 'Saran',
            'CreateBy' => 'Create By',
            'CreateDate' => 'Create Date',
            'CreateTerminal' => 'Create Terminal',
            'UpdateBy' => 'Update By',
            'UpdateDate' => 'Update Date',
            'UpdateTerminal' => 'Update Terminal',
        ];
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
             \nhkey\arh\ActiveRecordHistoryBehavior::className(),
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
        ];
    }


    
}
