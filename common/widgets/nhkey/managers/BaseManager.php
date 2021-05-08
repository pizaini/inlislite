<?php
/**
 * @link http://mikhailmikhalev.ru
 * @author Mikhail Mikhalev
 */

namespace common\widgets\nhkey\managers;

use Yii;


abstract class BaseManager implements ActiveRecordHistoryInterface
{

    /**
     * @var array list of updated fields
     */
    public $updatedFields;

    /**
     * @var boolean Flag for save current user_id in history
     */
    public $saveUserId = true;

    /**
     * @inheritdoc
     */
    public function setOptions($options)
    {
        if (is_array($options)) {
            foreach ($options as $optionKey => $optionValue)
                $this->{$optionKey} = $optionValue;
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setUpdatedFields($attributes)
    {
        $this->updatedFields = $attributes;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function run($type, $object)
    {
        $pk = $object->primaryKey();
        $pk = $pk[0];

        $data = [
            'table' => $object->tableName(),
            'field_id' => $object->getPrimaryKey(),
            'type' => $type,
            'date' => date('Y-m-d H:i:s', time()),
        ];

        if ($this->saveUserId)
            $data['user_id'] = isset(Yii::$app->user->id) ?  Yii::$app->user->id : '';

        switch ($type) {
            case self::AR_INSERT:

                foreach ($object as $key => $value) {
                    $data['field_name'] = $key;
                    $data['new_value'] = $value;


                    if ($key !== 'CreateDate' && $key !== 'CreateBy' && $key !== 'CreateTerminal' && $key !== 'UpdateBy' && $key !== 'UpdateDate' && $key !== 'UpdateTerminal' && $value !== null && $value !== "" ) 
                    {
                        $this->saveField($data);
                    } 
                    
                }

                // $data['field_name'] = $pk;
                // echo "<pre>";
                // print_r($object);die;
                // $data['new_value'] = implode(',',$this->$updatedFields);
                // $this->saveField($data);
                break;
            case self::AR_UPDATE:
                foreach ($this->updatedFields as $updatedFieldKey => $updatedFieldValue) {
                    $data['field_name'] = $updatedFieldKey;
                    $data['old_value'] = $updatedFieldValue;
                    $data['new_value'] = $object->$updatedFieldKey;
                    if($data['old_value'] != $data['new_value']){
                        $this->saveField($data);
                    }
                }
                break;
            case self::AR_DELETE:
                $data['field_name'] = $pk;
                $this->saveField($data);
                break;
            case self::AR_UPDATE_PK:
                $data['field_name'] = $pk;
                $data['old_value'] = $object->getOldPrimaryKey();
                $data['new_value'] = $object->{$pk};
                $this->saveField($data);
                break;
        }
    }

}
