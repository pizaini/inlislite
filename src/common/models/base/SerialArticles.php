<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "serial_articles".
 *
 * @property double $id
 * @property string $Article_type
 * @property string $Title
 * @property string $Content
 * @property string $Creator
 * @property string $Contributor
 * @property integer $StartPage
 * @property integer $Pages
 * @property string $Subject
 * @property string $DDC
 * @property string $Call_Number
 * @property string $EDISISERIAL
 * @property string $TANGGAL_TERBIT_EDISI_SERIAL
 * @property double $Catalog_id
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 * @property boolean $ISOPAC
 *
 *
 * @property \common\models\SerialArticlefiles[] $serialArticlefiles
 * @property \common\models\Catalogs $catalog
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 */
class SerialArticles extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'serial_articles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Title'], 'required'],
            [['Content','Abstract'], 'string'],
            [['StartPage', 'Pages', 'CreateBy', 'UpdateBy'], 'integer'],
            [[ 'CreateDate', 'UpdateDate'], 'safe'],
            [['Catalog_id'], 'number'],
            [['ISOPAC'], 'boolean'],
            [['Article_type', 'DDC', 'Call_Number','TANGGAL_TERBIT_EDISI_SERIAL', 'EDISISERIAL'], 'string', 'max' => 255],
            [['Title', 'Creator', 'Contributor', 'Subject'], 'string', 'max' => 700],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Catalog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['Catalog_id' => 'ID']],
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
            'id' => Yii::t('app', 'ID'),
            'Article_type' => Yii::t('app', 'Tipe Artikel'),
            'Title' => Yii::t('app', 'Judul'),
            'Content' => Yii::t('app', 'Konten'),
            'Creator' => Yii::t('app', 'Kreator'),
            'Contributor' => Yii::t('app', 'Kontributor'),
            'StartPage' => Yii::t('app', 'Halaman Awal'),
            'Pages' => Yii::t('app', 'Halaman'),
            'Subject' => Yii::t('app', 'Subjek'),
            'DDC' => Yii::t('app', 'DDC'),
            'Call_Number' => Yii::t('app', 'Nomor Panggil'),
            'EDISISERIAL' => Yii::t('app', 'Edisiserial'),
            'TANGGAL_TERBIT_EDISI_SERIAL' => Yii::t('app', 'Tanggal Terbit Edisi Serial'),
            'Catalog_id' => Yii::t('app', 'Catalog ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'ISOPAC' => Yii::t('app', 'Isopac'),
            'Abstract' => Yii::t('app', 'Abstract'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSerialArticlefiles()
    {
        return $this->hasMany(\common\models\SerialArticlefiles::className(), ['Articles_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(\common\models\Catalogs::className(), ['ID' => 'Catalog_id']);
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
