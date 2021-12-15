<?php

namespace common\models;

use Yii;
use \common\models\base\Collectioncategorys as BaseCollectioncategorys;
use \common\models\Collectioncategorysdefault;

/**
 * This is the model class for table "collectioncategorys".
 */
class Collectioncategorys extends BaseCollectioncategorys
{
	public $Copies;

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Code' => Yii::t('app', 'Code'),
            'Name' => Yii::t('app', 'Name'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'Copies' => Yii::t('app','Copies'),
        ];
    }

     public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
      //  return Model::scenarios();
    return BaseCollectioncategorys::scenarios();
         
    }


    /**
     * Function untuk mencari CollectionCategory_id berdasarkan jenis anggota
     * dan id member field.
     *
     * @param  [int] $jenis [id jenis anggota]
     * @param  [int] $id           [id member field]
     * @return [string]            [Member_Field_id]
     */
    public function getTrueFalse($jenis,$id)
    {
        $sql = Collectioncategorysdefault::find()->select('CollectionCategory_id')->where(['CollectionCategory_id' => $id,'JenisAnggota_id' => $jenis])->one();
        //$sql = MembersForm::findBySql("SELECT Member_Field_id FROM members_form WHERE Member_Field_id = $id AND Jenis_Perpustakaan_id = $jenis_perpus")->all();
        /*foreach($sql as $a)
            return $a->Member_Field_id;*/

       return $sql;

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'Name'], 'required'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['Code'], 'string', 'max' => 50],
            [['Name'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']],
            [['Code', 'Name'],'unique'] //adding
        ];
    }
}
