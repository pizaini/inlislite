<?php

namespace common\models;

use Yii;
use \common\models\base\MasterLoker as BaseMasterLoker;

/**
 * This is the model class for table "master_loker".
 */
class MasterLoker extends BaseMasterLoker
{

  /**
   * @inheritdoc
   */
  public function rules()
  {
      return [
          [['No', 'Name','locations_id'], 'required'],
          [['No'], 'unique'],
          [['locations_id', 'CreateBy', 'UpdateBy'], 'integer'],
          [['CreateDate', 'UpdateDate'], 'safe'],
          [['No', 'Name'], 'string', 'max' => 255],
          [['status'], 'string', 'max' => 20],
          [['CreateTerminal', 'UpdateTerminal'], 'string', 'max' => 100],
          [['CreateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['CreateBy' => 'ID']],
          [['locations_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['locations_id' => 'ID']],
          [['UpdateBy'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['UpdateBy' => 'ID']]
      ];
  }


  /**
   * @return \yii\db\ActiveQuery
   */
  public function getLocations()
  {
      return $this->hasOne(\common\models\Locations::className(), ['ID' => 'locations_id']);
  }

}
