<?php

namespace common\models;

use Yii;
use \common\models\base\Locations as BaseLocations;
use yii\web\UploadedFile;

/**
 * This is the model class for table "locations".
 */
class Locations extends BaseLocations
{
	public $Copies;
	public $Logo;


    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [['Code', 'Name'], 'required'],
            [['ISPUSTELING', 'IsPrintBarcode', 'IsGenerateVisitorNumber', 'IsInformationSought', 'IsVisitsDestination'], 'boolean'],
            [['CreateBy', 'UpdateBy','LocationLibrary_id'], 'integer'],
            [['CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['Code'], 'string', 'max' => 10],
            [['Code'], 'unique'],
            [['Logo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['Name', 'Description'], 'string', 'max' => 255],
            [['UrlLogo','CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['LocationLibrary_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationLibrary::className(), 'targetAttribute' => ['LocationLibrary_id' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }

	/**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Code' => Yii::t('app', 'Code'),
            'Name' => Yii::t('app', 'Name'),
            'Description' => Yii::t('app', 'Description'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'ISPUSTELING' => Yii::t('app', 'Ispusteling'),
            'UrlLogo' => Yii::t('app', 'Url Logo'),
            'IsPrintBarcode' => Yii::t('app', 'Aktifkan cetak no. pengunjung'),
            'IsGenerateVisitorNumber' => Yii::t('app', 'Tampilkan nomor pengunjung'),
            'IsInformationSought' => Yii::t('app', 'Tampilkan ruas informasi yang dicari'),
            'IsVisitsDestination' => Yii::t('app', 'Tampilkan ruas maksud kunjungan'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'Copies' => Yii::t('app', 'Copies'),
	    'Logo' => Yii::t('app', 'Logo'),
        ];
    }

}
