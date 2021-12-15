<?php

namespace common\models;

use Yii;
use \common\models\base\Pelanggaran as BasePelanggaran;

/**
 * This is the model class for table "pelanggaran".
 */
class Pelanggaran extends BasePelanggaran
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CollectionLoanItem_id', 'JenisDenda_id','JenisPelanggaran_id'], 'required'],
            [['CollectionLoanItem_id', 'JumlahDenda', 'Member_id', 'Collection_id'], 'number'],
            [['JenisPelanggaran_id', 'JenisDenda_id', 'CreateBy', 'UpdateBy', 'JumlahSuspend'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['Paid'], 'boolean'],
            [['CollectionLoan_id'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CollectionLoanItem_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionloanitems::className(), 'targetAttribute' => ['CollectionLoanItem_id' => 'ID']],
            [['Collection_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collections::className(), 'targetAttribute' => ['Collection_id' => 'ID']],
            [['CollectionLoan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionloans::className(), 'targetAttribute' => ['CollectionLoan_id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['JenisDenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisDenda::className(), 'targetAttribute' => ['JenisDenda_id' => 'ID']],
            [['JenisPelanggaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisPelanggaran::className(), 'targetAttribute' => ['JenisPelanggaran_id' => 'ID']],
            [['Member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['Member_id' => 'ID']],
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
            'CollectionLoan_id' => Yii::t('app', 'Collection Loan'),
            'CollectionLoanItem_id' => Yii::t('app', 'Collection Loan Item'),
            'JenisPelanggaran_id' => Yii::t('app', 'Jenis Pelanggaran'),
            'JenisDenda_id' => Yii::t('app', 'Jenis Denda'),
            'JumlahDenda' => Yii::t('app', 'Jumlah Denda'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'JumlahSuspend' => Yii::t('app', 'Jumlah Suspend'),
            'Paid' => Yii::t('app', 'Paid'),
            'Member_id' => Yii::t('app', 'Member ID'),
            'Collection_id' => Yii::t('app', 'Collection'),
        ];
    }

}
