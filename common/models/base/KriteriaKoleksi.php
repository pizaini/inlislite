<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "kriteria_koleksi".
 *
 * @property integer $ID
 * @property string $jns_kriteria
 * @property double $catalog_id
 * @property string $title
 * @property string $author
 * @property string $alamat_image
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property string $PublishYear
 * @property integer $Jumlah
 * @property string $worksheet_name
 * @property string $isLKD
 *
 * @property \common\models\Catalogs $catalog
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class KriteriaKoleksi extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kriteria_koleksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jns_kriteria'], 'required'],
            [['catalog_id'], 'number'],
            [['CreateBy', 'UpdateBy', 'Jumlah'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['jns_kriteria', 'title', 'author'], 'string', 'max' => 255],
            [['alamat_image'], 'string', 'max' => 500],
            [['CreateTerminal', 'UpdateTerminal', 'worksheet_name'], 'string', 'max' => 100],
            [['PublishYear'], 'string', 'max' => 25],
            [['isLKD'], 'string', 'max' => 10],
            [['catalog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['catalog_id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
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
            'jns_kriteria' => Yii::t('app', 'Jns Kriteria'),
            'catalog_id' => Yii::t('app', 'Catalog ID'),
            'title' => Yii::t('app', 'Title'),
            'author' => Yii::t('app', 'Author'),
            'alamat_image' => Yii::t('app', 'Alamat Image'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'PublishYear' => Yii::t('app', 'Publish Year'),
            'Jumlah' => Yii::t('app', 'Jumlah'),
            'worksheet_name' => Yii::t('app', 'Worksheet Name'),
            'isLKD' => Yii::t('app', 'Is Lkd'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(\common\models\Catalogs::className(), ['ID' => 'catalog_id']);
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
