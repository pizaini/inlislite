<?php

namespace common\models;

use Yii;
use \common\models\base\Catalogfiles as BaseCatalogfiles;

/**
 * This is the model class for table "catalogfiles".
 */
class Catalogfiles extends BaseCatalogfiles
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
        [['FileFlash'], 'required'],
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
            'fileExecutable' => Yii::t('app', 'Nama File'),
            'IsPublish' => Yii::t('app', 'Tampil di OPAC'),
        ];
    }
}
