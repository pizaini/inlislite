<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "collectionorderitems".
 *
 * @property integer $id
 * @property string $No_Induk
 * @property string $Title
 * @property string $Author
 * @property string $Publisher
 * @property string $OrderDate
 * @property double $Collection_id
 * @property string $Barcode
 * @property double $Catalog_id
 * @property double $member_id
 * @property double $Branch_id
 * @property double $Location_id
 * @property string $status
 * @property string $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property string $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 */
class Collectionorderitems extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collectionorderitems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OrderDate', 'CreateDate', 'UpdateDate'], 'safe'],
            [['Collection_id'], 'required'],
            [['Collection_id', 'Catalog_id', 'member_id', 'Branch_id', 'Location_id'], 'number'],
            [['No_Induk', 'Barcode', 'status', 'CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Title', 'Author', 'Publisher'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'No_Induk' => Yii::t('app', 'No  Induk'),
            'Title' => Yii::t('app', 'Title'),
            'Author' => Yii::t('app', 'Author'),
            'Publisher' => Yii::t('app', 'Publisher'),
            'OrderDate' => Yii::t('app', 'Order Date'),
            'Collection_id' => Yii::t('app', 'Collection ID'),
            'Barcode' => Yii::t('app', 'Barcode'),
            'Catalog_id' => Yii::t('app', 'Catalog ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'Branch_id' => Yii::t('app', 'Branch ID'),
            'Location_id' => Yii::t('app', 'Location ID'),
            'status' => Yii::t('app', 'Status'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }


/**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
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
