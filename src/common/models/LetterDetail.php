<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "letter_detail".
 *
 * @property integer $LETTER_DETAIL_ID
 * @property string $SUB_TYPE_COLLECTION
 * @property string $TITLE
 * @property integer $QUANTITY
 * @property integer $COPY
 * @property string $PRICE
 * @property integer $LETTER_ID
 * @property integer $COLLECTION_TYPE_ID
 * @property string $REMARK
 * @property string $AUTHOR
 * @property string $PUBLISHER
 * @property string $PUBLISHER_ADDRESS
 * @property string $ISBN
 * @property string $PUBLISH_YEAR
 * @property string $PUBLISHER_CITY
 * @property string $ISBN_STATUS
 * @property string $KD_PENERBIT_DTL
 *
 * @property Letter $lETTER
 */
class LetterDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'letter_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SUB_TYPE_COLLECTION', 'QUANTITY', 'COPY', 'LETTER_ID', 'COLLECTION_TYPE_ID'], 'integer'],
            [['TITLE', 'REMARK', 'PUBLISHER_ADDRESS'], 'string', 'max' => 255],
            [['PRICE'], 'string', 'max' => 100],
            [['AUTHOR'], 'string', 'max' => 155],
            [['PUBLISHER'], 'string', 'max' => 50],
            [['ISBN', 'PUBLISHER_CITY', 'KD_PENERBIT_DTL'], 'string', 'max' => 25],
            [['PUBLISH_YEAR'], 'string', 'max' => 15],
            [['ISBN_STATUS'], 'string', 'max' => 55],
            [['SUB_TYPE_COLLECTION'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionmedias::className(), 'targetAttribute' => ['SUB_TYPE_COLLECTION' => 'ID']],
            [['LETTER_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Letter::className(), 'targetAttribute' => ['LETTER_ID' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'SUB_TYPE_COLLECTION' => 'Sub  Type  Collection',
            'TITLE' => 'Title',
            'QUANTITY' => 'Quantity',
            'COPY' => 'Copy',
            'PRICE' => 'Price',
            'LETTER_ID' => 'Letter  ID',
            'COLLECTION_TYPE_ID' => 'Collection  Type  ID',
            'REMARK' => 'Remark',
            'AUTHOR' => 'Author',
            'PUBLISHER' => 'Publisher',
            'PUBLISHER_ADDRESS' => 'Publisher  Address',
            'ISBN' => 'Isbn',
            'PUBLISH_YEAR' => 'Publish  Year',
            'PUBLISHER_CITY' => 'Publisher  City',
            'ISBN_STATUS' => 'Isbn  Status',
            'KD_PENERBIT_DTL' => 'Kd  Penerbit  Dtl',
        ];
    }

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getCollectionmedias() 
    { 
        return $this->hasOne(Collectionmedias::className(), ['ID' => 'SUB_TYPE_COLLECTION']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetter()
    {
        return $this->hasOne(Letter::className(), ['ID' => 'LETTER_ID']);
    }
}
