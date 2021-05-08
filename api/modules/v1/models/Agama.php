<?php
namespace api\modules\v1\models;
use \yii\db\ActiveRecord;
/**
 * Agama Model
 *
 * @author Henry <alvin_vna@yahoo.com>
 */
class Agama extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'agama';
	}

    /**
     * @inheritdoc
     */
    /*public static function primaryKey()
    {
        return ['code'];
    }*/

    /**
     * Define rules for validation
     */
     public function rules()
    {
        return [
            [['Name'], 'required'],
            [['CreateBy', 'UpdateBy'], 'integer'],
            [['CreateDate', 'UpdateDate', 'KIILastUploadDate'], 'safe'],
            [['Name', 'CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
            [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
            [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
        ];
    }
}
