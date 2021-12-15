<?php

namespace common\models;

use Yii;
use \common\models\base\Catalogfiles as BaseCatalogfiles;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;

/**
 * This is the model class for table "catalogfiles".
 */
class CatalogfilesOnline extends BaseCatalogfiles
{
    public $BIBID;
    public $FileURLComplete;
    public $FileType;
    public $FileSize;
    public $DataBib;
    public $file;
    public $isCompress;
    public $fileExecutable;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'file'],
            [['isCompress'], 'integer'],
            [['fileExecutable'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'FileURL' => Yii::t('app', 'File'),
            'FileFlash' => Yii::t('app', 'Nama File Format Flash'),
            'FileType' => Yii::t('app', 'Tipe File'),
            'FileSize' => Yii::t('app', 'Ukuran File'),
            'BIBID' => Yii::t('app', 'BIB ID'),
            'DataBib' => Yii::t('app', 'Data Bibliografis'),
            'CreateDate' => Yii::t('app', 'Tanggal Unggah'),
            'createBy.Fullname' => Yii::t('app', 'Diunggah oleh'),
            'isCompress' => Yii::t('app', 'Bentuk Flipbook (zip/rar)'),
            'fileExecutable' => Yii::t('app', 'Nama File Format Flash'),
            'IsPublish' => Yii::t('app', 'Tampil di OPAC'),
        ];
    }

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
                'class' => TerminalBehavior::className(),
                'createdTerminalAttribute' => 'CreateTerminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                'value' => \Yii::$app->request->userIP,
            ],
        ];
    }
}
