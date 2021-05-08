<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the base-model class for table "readinlocation".
 *
 * @property double $ID
 * @property string $MemberNo
 * @property string $Nama
 * @property string $Status
 * @property string $Profesi
 * @property string $Pendidikan
 * @property string $JenisKelamin
 * @property string $Alamat
 * @property string $TypeIdCard
 * @property string $NoIdCard
 * @property string $NoBarcode
 * @property string $Title
 * @property string $Author
 * @property string $Publisher
 * @property string $ControlNumber
 * @property integer $LocationId
 * @property integer $CreateBy
 * @property string $CreateDate
 * @property string $CreateTerminal
 * @property integer $UpdateBy
 * @property string $UpdateDate
 * @property string $UpdateTerminal
 *
 * @property \common\models\Users $createBy
 * @property \common\models\Locations $location
 * @property \common\models\Users $updateBy
 */
class Readinlocation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'readinlocation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Nama', 'Title', 'LocationId'], 'required'],
            [['Title', 'Author'], 'string'],
            [['LocationId', 'CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['MemberNo', 'Status', 'Profesi', 'Pendidikan', 'JenisKelamin', 'TypeIdCard', 'NoIdCard', 'NoBarcode', 'ControlNumber'], 'string', 'max' => 50],
            [['Nama', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Alamat', 'Publisher'], 'string', 'max' => 255],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['LocationId'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['LocationId' => 'ID']],
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
            'MemberNo' => Yii::t('app', 'Member No'),
            'Nama' => Yii::t('app', 'Nama'),
            'Status' => Yii::t('app', 'Status'),
            'Profesi' => Yii::t('app', 'Profesi'),
            'Pendidikan' => Yii::t('app', 'Pendidikan'),
            'JenisKelamin' => Yii::t('app', 'Jenis Kelamin'),
            'Alamat' => Yii::t('app', 'Alamat'),
            'TypeIdCard' => Yii::t('app', 'Type Id Card'),
            'NoIdCard' => Yii::t('app', 'No Id Card'),
            'NoBarcode' => Yii::t('app', 'No Barcode'),
            'Title' => Yii::t('app', 'Title'),
            'Author' => Yii::t('app', 'Author'),
            'Publisher' => Yii::t('app', 'Publisher'),
            'ControlNumber' => Yii::t('app', 'Control Number'),
            'LocationId' => Yii::t('app', 'Location ID'),
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
    public function getLocation()
    {
        return $this->hasOne(\common\models\Locations::className(), ['ID' => 'LocationId']);
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
