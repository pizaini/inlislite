<?php
/**
 * Created by PhpStorm.
 * User: mazpaijo
 * Date: 08/01/2018
 * Time: 11.10
 */
namespace common\models\elastic;
Class Book extends \yii\elasticsearch\ActiveRecord
{
    public static function index(){
        return "catalog";
    }

    public static function type(){
        return "Book";
    }

    /**
     * @return array This model's mapping
     */
    public static function mapping()
    {
        return [
            static::type() => [
                'properties' => [
                    'id'             => ['type' => 'long'],
                    'name'           => ['type' => 'text','analyzer' => 'keyword'],
                    'author_name'    => ['type' => 'text','analyzer' => 'keyword'],
                    'publisher_name' => ['type' => 'text','analyzer' => 'keyword'],
                    'created_at'     => ['type' => 'long'],
                    'updated_at'     => ['type' => 'long'],
                    'status'         => ['type' => 'long'],
                    'suppliers'      => [
                        'type'      => 'nested',
                        'properties' => [
                            'id'  => ['type' => 'long'],
                            'name' => ['type' => 'text','analyzer' => 'keyword'],
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * Set (update) mappings for this model
     */
    public static function updateMapping()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->setMapping(static::index(), static::type(), static::mapping());
    }

    /**
     * Create this model's index
     */
    public static function createIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->createIndex(static::index(), [
            'settings' => [ 'index' => ['refresh_interval' => '1s'] ],
            'mappings' => static::mapping(),
            //'warmers' => [ /* ... */ ],
            //'aliases' => [ /* ... */ ],
            //'creation_date' => '...'
        ]);
    }

    /**
     * Delete this model's index
     */
    public static function deleteIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->deleteIndex(static::index(), static::type());
    }

    public static function updateRecord($book_id, $columns){
        try{
            $record = self::get($book_id);
            foreach($columns as $key => $value){
                $record->$key = $value;
            }

            return $record->update();
        }
        catch(\Exception $e){
            //handle error here
            return false;
        }
    }

    public static function deleteRecord($book_id)
    {
        try{
            $record = self::get($book_id);
            $record->delete();
            return 1;
        }
        catch(\Exception $e){
            //handle error here
            return false;
        }
    }

    public static function addRecord(Book $book){
        $isExist = false;

        try{
            $record = self::get($book->id);
            if(!$record){
                $record = new self();
                $record->setPrimaryKey($book->id);
            }
            else{
                $isExist = true;
            }
        }
        catch(\Exception $e){
            $record = new self();
            $record->setPrimaryKey($book->id);
        }

        $suppliers = [
            ['id' => '1', 'name' => 'ABC'],
            ['id' => '2', 'name' => 'XYZ'],
        ];

        $record->id   = $book->id;
        $record->name = $book->name;
        $record->author_name = $book->author_name;
        $record->suppliers = $book->suppliers;

        try{
            if(!$isExist){
                $result = $record->insert();
            }
            else{
                $result = $record->update();
            }
        }
        catch(\Exception $e){
            $result = false;
            //handle error here
        }

        return $result;
    }

    public  function  attributes(){
        return [
            'id',
            'name',
            'author_name',
            'publisher_name',
            'created_at',
            'updated_at',
            'status',
            'suppliers',
        ];
    }
}
