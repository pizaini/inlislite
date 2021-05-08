<?php

namespace common\models;

use Yii;
use \common\models\base\LocationLibrary as BaseLocationLibrary;

use \common\models\LocationLibraryDefault;
/**
 * This is the model class for table "location_library".
 */
class LocationLibrary extends BaseLocationLibrary
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code', 'Name','Address'], 'required'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['Code'], 'string', 'max' => 50],
            [['Code'], 'unique'],
            [['Name','Address'], 'string', 'max' => 255],
            [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
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
            'Code' => Yii::t('app', 'Code'),
            'Name' => Yii::t('app', 'Name'),
            'Address' => Yii::t('app','Address'),
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
     * Function untuk mencari Location_Library_id berdasarkan jenis anggota
     * dan id member field.
     *
     * @param  [int] $jenis [id jenis anggota]
     * @param  [int] $id           [id member field]
     * @return [string]            [Member_Field_id]
     */
    public function getTrueFalse($jenis,$id)
    {
        $sql = LocationLibraryDefault::find()->select('Location_Library_id')->where(['Location_Library_id' => $id,'JenisAnggota_id' => $jenis])->one();
        //$sql = MembersForm::findBySql("SELECT Member_Field_id FROM members_form WHERE Member_Field_id = $id AND Jenis_Perpustakaan_id = $jenis_perpus")->all();
        /*foreach($sql as $a)
            return $a->Member_Field_id;*/

       return $sql;

    }



}
