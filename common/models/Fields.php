<?php

namespace common\models;

use Yii;
use \common\models\base\Fields as BaseFields;

/**
 * This is the model class for table "fields".
 */
class Fields extends BaseFields
{
    public $TandaBaca;

	 /**
     * @inheritdoc
     */
    /*
    public function rules()
    {
        return [
            [['Tag', 'Name', 'Format_id'], 'required'],
            [['Fixed', 'Enabled', 'Length', 'Repeatable', 'Mandatory', 'IsCustomable', 'Format_id', 'Group_id'], 'integer'],
            [['CreateDate', 'UpdateDate'], 'safe'],
            [['ISSUBSERIAL'], 'boolean'],
            [['Tag'], 'string', 'max' => 3],
            [['Name', 'CreateBy', 'CreateTerminal', 'UpdateBy', 'UpdateTerminal'], 'string', 'max' => 100],
            [['DEFAULTSUBTAG'], 'string', 'max' => 12],
            [['Tag', 'Format_id'], 'unique', 'targetAttribute' => ['Tag', 'Format_id'], 'message' => 'The combination of Tag and Format ID has already been taken.'],
            [['Group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fieldgroups::className(), 'targetAttribute' => ['Group_id' => 'ID']],
            [['Format_id'], 'exist', 'skipOnError' => true, 'targetClass' => Formats::className(), 'targetAttribute' => ['Format_id' => 'ID']]
        ];
    }
*/
    public static function getTandaBaca($tag,$code) {
        $output = Fields::find()
        ->addSelect(['fielddatas.Delimiter AS TandaBaca'])
        ->innerJoin('fielddatas',' fielddatas.Field_id = fields.ID')
        ->where([
            'fields.Tag'=>$tag,
            'fielddatas.Code'=>$code
            ])
        ->one();
        return (string)$output->TandaBaca;
    }

    public static function getTandaBacaByTag($tag) {
        $output = Fields::find()
        ->addSelect(['fielddatas.Delimiter AS TandaBaca'])
        ->innerJoin('fielddatas',' fielddatas.Field_id = fields.ID')
        ->where([
            'fields.Tag'=>$tag,
            ])
        ->all();
        return $output;
    }

    public static function getByTag($tag) {
        $output = Fields::find()
        ->addSelect(['ID','Tag','Name','Mandatory','Length','Enabled','IsCustomable','Fixed','Repeatable'])
        ->where([
            'Tag'=>$tag
            ])
        ->one();
        return $output;
    }
}
