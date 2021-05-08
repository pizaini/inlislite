<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;
use \common\models\base\CatalogSubruas as BaseCatalogSubruas;

/**
 * This is the model class for table "catalog_subruas".
 */
class CatalogSubruasOnline extends BaseCatalogSubruas
{
	/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
        //\nhkey\arh\ActiveRecordHistoryBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'CreateDate',
                'updatedAtAttribute' => 'UpdateDate',
                'value' => new \yii\db\Expression('NOW()'),
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
