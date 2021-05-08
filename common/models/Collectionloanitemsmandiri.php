<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
//use \common\models\base\Collectionloanitems as BaseCollectionloanitems;

/**
 * This is the model class for table "collectionloanitems".
 */
class Collectionloanitemsmandiri extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collectionloanitems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CollectionLoan_id', 'Collection_id', 'member_id'], 'required'],
            [['LoanDate', 'DueDate', 'ActualReturn', 'CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['LateDays', 'CreateBy', 'UpdateBy'], 'integer'],
            [['Collection_id', 'member_id'], 'number'],
            [['CollectionLoan_id'], 'string', 'max' => 255],
            [['LoanStatus'], 'string', 'max' => 50],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['Collection_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collections::className(), 'targetAttribute' => ['Collection_id' => 'ID']],
            [['CollectionLoan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collectionloans::className(), 'targetAttribute' => ['CollectionLoan_id' => 'ID']],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['member_id' => 'ID']],
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
            'CollectionLoan_id' => Yii::t('app', 'Collection Loan ID'),
            'LoanDate' => Yii::t('app', 'Loan Date'),
            'DueDate' => Yii::t('app', 'Due Date'),
            'ActualReturn' => Yii::t('app', 'Actual Return'),
            'LateDays' => Yii::t('app', 'Late Days'),
            'LoanStatus' => Yii::t('app', 'Loan Status'),
            'Collection_id' => Yii::t('app', 'Collection ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'KIILastUploadDate' => Yii::t('app', 'Kiilast Upload Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionloanextends()
    {
        return $this->hasMany(\common\models\Collectionloanextends::className(), ['CollectionLoanItem_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(\common\models\Collections::className(), ['ID' => 'Collection_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionLoan()
    {
        return $this->hasOne(\common\models\Collectionloans::className(), ['ID' => 'CollectionLoan_id']);
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
    public function getMember()
    {
        return $this->hasOne(\common\models\Members::className(), ['ID' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateBy()
    {
        return $this->hasOne(\common\models\Users::className(), ['ID' => 'UpdateBy']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPelanggarans()
    {
        return $this->hasMany(\common\models\Pelanggaran::className(), ['CollectionLoanItem_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSentitems()
    {
        return $this->hasMany(\common\models\Sentitems::className(), ['CollectionLoanItem_id' => 'ID']);
    }
}
