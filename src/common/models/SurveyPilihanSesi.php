<?php

namespace common\models;

use Yii;
use \common\models\base\SurveyPilihanSesi as BaseSurveyPilihanSesi;

/**
 * This is the model class for table "SurveyPilihanSesi".
 */
class SurveyPilihanSesi extends BaseSurveyPilihanSesi
{


  /**
       * @inheritdoc
       * @return type array
       */
      public function behaviors()
      {
          return [
          // \common\widgets\nhkey\ActiveRecordHistoryBehavior::className(),
          ];
      }

}
