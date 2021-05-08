<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "collectioncategorysloanhari".
 *
 * @property integer $DataID
 * @property integer $Category_id
 * @property integer $Peminjaman_hari_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Collectioncategorys $collectionCategory
 * @property \common\models\PeraturanPeminjamanHari $peraturanPeminjamanHari
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class Collectioncategorysloanhari extends \yii\db\ActiveRecord
{
    public $collectionCategory;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collectioncategorysloanhari';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Category_id', 'Peminjaman_hari_id', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Category_id','Peminjaman_hari_id'], 'required'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectioncategorys::className(), 'targetAttribute' => ['Category_id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['Peminjaman_hari_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeraturanPeminjamanHari::className(), 'targetAttribute' => ['Peminjaman_hari_id' => 'id']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DataID' => Yii::t('app', 'Data ID'),
            'Category_id' => Yii::t('app', 'Category ID'),
            'Peminjaman_hari_id' => Yii::t('app', 'Peminjaman Hari ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionCategory()
    {
        return $this->hasOne(\common\models\Collectioncategorys::className(), ['ID' => 'Category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'CreateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeraturanPeminjamanHari()
    {
        return $this->hasOne(\common\models\PeraturanPeminjamanHari::className(), ['ID' => 'Peminjaman_hari_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
    }


/**
     * @inheritdoc
     * @return type array
     */ 
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
        ];
    }


    
}
