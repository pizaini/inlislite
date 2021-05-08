<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "cardex".
 *
 * @property integer $ID
 * @property integer $MASTER_ID
 * @property string $JUDUL
 * @property string $FREKUENSI
 * @property string $PENERBIT
 * @property string $TEMPAT_TERBIT
 * @property string $ISSN
 * @property string $MATA_UANG
 * @property double $HARGA
 * @property double $EKSEMPLAR
 * @property string $NOMOR
 * @property string $TANGGAL
 * @property string $LAST_MODIFIED_BY
 * @property string $LAST_MODIFIED_DATE
 * @property string $ALAMAT_PENERBIT
 *
 * @property \common\models\Masterserial $mASTER
 */
class Cardex extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cardex';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MASTER_ID'], 'integer'],
            [['HARGA', 'EKSEMPLAR'], 'number'],
            [['TANGGAL', 'LAST_MODIFIED_DATE'], 'safe'],
            [['JUDUL', 'PENERBIT'], 'string', 'max' => 500],
            [['FREKUENSI', 'TEMPAT_TERBIT', 'NOMOR'], 'string', 'max' => 50],
            [['ISSN', 'MATA_UANG'], 'string', 'max' => 20],
            [['LAST_MODIFIED_BY'], 'string', 'max' => 45],
            [['ALAMAT_PENERBIT'], 'string', 'max' => 1000],
            [['MASTER_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Masterserial::className(), 'targetAttribute' => ['MASTER_ID' => 'ID']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'MASTER_ID' => Yii::t('app', 'Master  ID'),
            'JUDUL' => Yii::t('app', 'Judul'),
            'FREKUENSI' => Yii::t('app', 'Frekuensi'),
            'PENERBIT' => Yii::t('app', 'Penerbit'),
            'TEMPAT_TERBIT' => Yii::t('app', 'Tempat  Terbit'),
            'ISSN' => Yii::t('app', 'Issn'),
            'MATA_UANG' => Yii::t('app', 'Mata  Uang'),
            'HARGA' => Yii::t('app', 'Harga'),
            'EKSEMPLAR' => Yii::t('app', 'Eksemplar'),
            'NOMOR' => Yii::t('app', 'Nomor'),
            'TANGGAL' => Yii::t('app', 'Tanggal'),
            'LAST_MODIFIED_BY' => Yii::t('app', 'Last  Modified  By'),
            'LAST_MODIFIED_DATE' => Yii::t('app', 'Last  Modified  Date'),
            'ALAMAT_PENERBIT' => Yii::t('app', 'Alamat  Penerbit'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMASTER()
    {
        return $this->hasOne(\common\models\Masterserial::className(), ['ID' => 'MASTER_ID']);
    }


    
}
