<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "masterserial".
 *
 * @property integer $ID
 * @property string $JUDUL
 * @property string $FREKUENSI
 * @property string $PENERBIT
 * @property string $TEMPAT_TERBIT
 * @property string $ISSN
 * @property string $MATA_UANG
 * @property double $HARGA
 * @property string $LAST_MODIFIED_BY
 * @property string $LAST_MODIFIED_DATE
 * @property string $ALAMAT_PENERBIT
 * @property string $JENIS_SERIAL
 *
 * @property \common\models\Cardex[] $cardexes
 */
class Masterserial extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'masterserial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['JUDUL'], 'required'],
            [['HARGA'], 'number'],
            [['LAST_MODIFIED_DATE'], 'safe'],
            [['JUDUL', 'PENERBIT'], 'string', 'max' => 500],
            [['FREKUENSI', 'TEMPAT_TERBIT', 'LAST_MODIFIED_BY', 'JENIS_SERIAL'], 'string', 'max' => 50],
            [['ISSN', 'MATA_UANG'], 'string', 'max' => 20],
            [['ALAMAT_PENERBIT'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'JUDUL' => Yii::t('app', 'Judul'),
            'FREKUENSI' => Yii::t('app', 'Frekuensi'),
            'PENERBIT' => Yii::t('app', 'Penerbit'),
            'TEMPAT_TERBIT' => Yii::t('app', 'Tempat  Terbit'),
            'ISSN' => Yii::t('app', 'Issn'),
            'MATA_UANG' => Yii::t('app', 'Mata  Uang'),
            'HARGA' => Yii::t('app', 'Harga'),
            'LAST_MODIFIED_BY' => Yii::t('app', 'Last  Modified  By'),
            'LAST_MODIFIED_DATE' => Yii::t('app', 'Last  Modified  Date'),
            'ALAMAT_PENERBIT' => Yii::t('app', 'Alamat  Penerbit'),
            'JENIS_SERIAL' => Yii::t('app', 'Jenis  Serial'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCardexes()
    {
        return $this->hasMany(\common\models\Cardex::className(), ['MASTER_ID' => 'ID']);
    }


    
}
