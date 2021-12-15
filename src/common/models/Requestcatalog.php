<?php

namespace common\models;

use Yii;
use \common\models\base\Requestcatalog as BaseRequestcatalog;

/**
 * This is the model class for table "requestcatalog".
 */
class Requestcatalog extends BaseRequestcatalog
{
	public $noAnggota;
	public $Publishment;

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            /*'ID' => Yii::t('app', 'ID'),
            'Type' => Yii::t('app', 'Type'),*/
            'Title' => Yii::t('app', 'Judul'),
            /*'Subject' => Yii::t('app', 'Subject'),*/
            'Author' => Yii::t('app', 'Pengarang'),
            'PublishLocation' => Yii::t('app', 'Kota Terbit'),
            'PublishYear' => Yii::t('app', 'Tahun Terbit'),
            'Publisher' => Yii::t('app', 'Penerbit'),
            'Comments' => Yii::t('app', 'Keterangan'),
            'MemberID' => Yii::t('app', 'usulancoll_Member ID'),
            /*'CallNumber' => Yii::t('app', 'Call Number'),
            'ControlNumber' => Yii::t('app', 'Control Number'),*/
            'DateRequest' => Yii::t('app', 'usulancoll_Date Request'),
            'Status' => Yii::t('app', 'usulancoll_Status'),
            /*'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),*/
            'WorksheetID' => Yii::t('app', 'Jenis Bahan'),
            'Publishment' => Yii::t('app', 'usulancoll_Publishment')
        ];
    }
}
