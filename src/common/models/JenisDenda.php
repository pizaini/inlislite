<?php

namespace common\models;

use Yii;
use \common\models\base\JenisDenda as BaseJenisDenda;

/**
 * This is the model class for table "jenis_denda".
 */
class JenisDenda extends BaseJenisDenda
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'Name'], 'required'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Code', 'Name'], 'string', 'max' => 50],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']],
            [['Code', 'Name'], 'unique'],
        ];
    }
}
