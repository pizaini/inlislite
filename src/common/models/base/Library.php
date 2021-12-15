<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "library".
 *
 * @property integer $ID
 * @property string $NAME
 * @property string $URL
 * @property string $PORT
 * @property string $DATABASENAME
 * @property string $RECORDSYNTAX
 * @property string $FULLNAME
 * @property string $PROTOCOL
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Users $updateBy
 * @property \common\models\Librarysearchcriteria[] $librarysearchcriterias
 */
class Library extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'library';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NAME'], 'required'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['NAME', 'DATABASENAME'], 'string', 'max' => 50],
            [['URL', 'FULLNAME'], 'string', 'max' => 255],
            [['PORT'], 'string', 'max' => 10],
            [['RECORDSYNTAX'], 'string', 'max' => 20],
            [['PROTOCOL'], 'string', 'max' => 45],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
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
            'NAME' => Yii::t('app', 'Name'),
            'URL' => Yii::t('app', 'Url'),
            'PORT' => Yii::t('app', 'Port'),
            'DATABASENAME' => Yii::t('app', 'Databasename'),
            'RECORDSYNTAX' => Yii::t('app', 'Recordsyntax'),
            'FULLNAME' => Yii::t('app', 'Fullname'),
            'PROTOCOL' => Yii::t('app', 'Protocol'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getLibrarysearchcriterias()
    {
        return $this->hasMany(\common\models\Librarysearchcriteria::className(), ['LIBRARYID' => 'ID']);
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
