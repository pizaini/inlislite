<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "auth_usulan".
 *
 * @property integer $ID
 * @property string $ISI
 * @property string $TAG
 * @property string $BIBID
 * @property double $CATALOG_ID
 * @property string $PROMOTEBY
 * @property string $PROMOTEDATE
 * @property string $PROMOTETERMINAL
 * @property string $ISICLEAN
 * @property string $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property string $UpdateBy
 * @property string $UpdateTerminal
 * @property string $UpdateDate
 *
 * @property \common\models\Catalogs $cATALOG
 */
class AuthUsulan extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_usulan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ISI', 'ISICLEAN'], 'string'],
            [['CATALOG_ID'], 'required'],
            [['CATALOG_ID'], 'number'],
            [['PROMOTEDATE', 'CreateDate', 'UpdateDate'], 'safe'],
            [['TAG'], 'string', 'max' => 3],
            [['BIBID'], 'string', 'max' => 255],
            [['PROMOTEBY', 'PROMOTETERMINAL', 'CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CATALOG_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Catalogs::className(), 'targetAttribute' => ['CATALOG_ID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'ISI' => Yii::t('app', 'Isi'),
            'TAG' => Yii::t('app', 'Tag'),
            'BIBID' => Yii::t('app', 'Bibid'),
            'CATALOG_ID' => Yii::t('app', 'Catalog  ID'),
            'PROMOTEBY' => Yii::t('app', 'Promoteby'),
            'PROMOTEDATE' => Yii::t('app', 'Promotedate'),
            'PROMOTETERMINAL' => Yii::t('app', 'Promoteterminal'),
            'ISICLEAN' => Yii::t('app', 'Isiclean'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCATALOG()
    {
        return $this->hasOne(\common\models\Catalogs::className(), ['ID' => 'CATALOG_ID']);
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
