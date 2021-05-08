<?php

namespace common\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

use \common\models\base\Fielddatas as BaseFielddatas;

/**
 * This is the model class for table "fielddatas".
 */
class Fielddatas extends BaseFielddatas
{

	public $Tag;
    public $Isi;
    public $ruas;

    /**
     * @inheritdoc
     */
    /*
    public function rules()
    {
        return [
            [['Field_id', 'Code', 'Name'], 'required'],
            [['Field_id', 'SortNo', 'Repeatable', 'IsShow'], 'integer'],
            [['CreateDate', 'UpdateDate', 'ruas'], 'safe'],
            [['Code', 'Name'], 'string', 'max' => 255],
            [['Delimiter'], 'string', 'max' => 5],
            [['CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Field_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fields::className(), 'targetAttribute' => ['Field_id' => 'ID']],
            [['Tag'], 'string', 'max' => 3],
        ];
    }
*/
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Field_id' => Yii::t('app', 'Field ID'),
            'Code' => Yii::t('app', 'Code'),
            'Name' => Yii::t('app', 'Name'),
            'Delimiter' => Yii::t('app', 'Delimiter'),
            'SortNo' => Yii::t('app', 'Sort No'),
            'Repeatable' => Yii::t('app', 'Repeatable'),
            'IsShow' => Yii::t('app', 'Is Show'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'Isi' => Yii::t('app', 'Isi'),
        ];
    }


/**
     * @inheritdoc
     * @return type array
     */
    public function behaviors()
    {
        return [
       // \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
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
