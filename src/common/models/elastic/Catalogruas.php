<?php
/**
 * Created by PhpStorm.
 * User: mazpaijo
 * Date: 03/01/2018
 * Time: 11.39
 */
namespace common\models\elastic;
class Catalogruas extends \yii\elasticsearch\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */

    public static function index(){
        return "elstic_ruas";
    }

    public static function type(){
        return "catalogruas";
    }

    public static  function mapping(){

        return [
            static::type() =>[
                'properties' => [
                    'ID' => ['type' => 'long'],
                    'ControlNumber'  => ['type' => 'text','analyzer' => 'keyword'],
                    'BIBID'  => ['type' => 'text','analyzer' => 'keyword'],
                    'Title' => ['type' => 'text','analyzer' => 'keyword'],
                    'Author'  => ['type' => 'text','fielddata' => true,'analyzer' => 'keyword'],
                    'Edition'  => ['type' => 'text','analyzer' => 'keyword'],
                    'Publisher'  => ['type' => 'text','fielddata' => true,'analyzer' => 'keyword'],
                    'PublishLocation'  => ['type' => 'text','fielddata' => true,'analyzer' => 'keyword'],
                    'PublishYear'  => ['type' => 'text','fielddata' => true,'analyzer' => 'keyword'],
                    'Publikasi'  => ['type' => 'text','fielddata' => true,'analyzer' => 'keyword'],
                    'Subject'  => ['type' => 'text','fielddata' => true,'analyzer' => 'keyword'],
                    'PhysicalDescription'  => ['type' => 'text','analyzer' => 'keyword'],
                    'ISBN'  => ['type' => 'text','analyzer' => 'keyword'],
                    'CallNumber'  => ['type' => 'text','analyzer' => 'keyword'],
                    'Note'  => ['type' => 'text','analyzer' => 'keyword'],
                    'Languages'  => ['type' => 'text','fielddata' => true,'analyzer' => 'keyword'],
                    'DeweyNo'  => ['type' => 'text','analyzer' => 'keyword'],
                    'ApproveDateOPAC'  => ['type' => 'text','analyzer' => 'keyword'],
                    'IsOPAC'  => ['type' => 'long'],
                    'IsBNI'  => ['type' => 'long'],
                    'IsKIN' => ['type' => 'long'],
                    'IsRDA'  => ['type' => 'long'],
                    'CoverURL' => ['type' => 'text','analyzer' => 'keyword'],
                    'Branch_id'  => ['type' => 'long'],
                    'Worksheet_id'  => ['type' => 'long'],
                    'CreateBy'  => ['type' => 'long'],
                    'CreateDate' => ['type' => 'text','analyzer' => 'keyword'],
                    'CreateTerminal' => ['type' => 'text','analyzer' => 'keyword'],
                    'UpdateBy'  => ['type' => 'long'],
                    'UpdateDate'=> ['type' => 'text','analyzer' => 'keyword'],
                    'UpdateTerminal'=> ['type' => 'text','analyzer' => 'keyword'],
                    'MARC_LOC'=> ['type' => 'text','analyzer' => 'keyword'],
                    'PRESERVASI_ID' => ['type' => 'long'],
                    'QUARANTINEDBY' => ['type' => 'long'],
                    'QUARANTINEDDATE' => ['type' => 'text','analyzer' => 'keyword'],
                    'QUARANTINEDTERMINAL' => ['type' => 'text','analyzer' => 'keyword'],
                    'Member_id' => ['type' => 'long'],
                    'KIILastUploadDate'  => ['type' => 'text','analyzer' => 'keyword'],
                    'worksheet_name'  => ['type' => 'text','analyzer' => 'keyword'],
                    'ISSERIAL'  => ['type' => 'long'],
                    'subruas'      => [
                        'type'      => 'nested',
                        'properties' => [
                            'subID'  => ['type' => 'long'],
                            'CatalogId' => ['type' => 'long'],
                            'Tag' => ['type' => 'long'],
                            'ind1' => ['type' => 'text','analyzer' => 'keyword'],
                            'ind2' => ['type' => 'text','analyzer' => 'keyword'],
                            'RuasID' => ['type' => 'long'],
                            'SubRuas' => ['type' => 'text','analyzer' => 'keyword'],
                            'Value' => ['type' => 'text','analyzer' => 'keyword'],
                            'Sequence' => ['type' => 'long'],
                        ]
                    ]
                ]
            ]
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
            'settings' => [ 'index' => ['refresh_interval' => '1s','blocks.read_only' => false ] ],
            'mappings' => static::mapping(),
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


    public static function addRecord(Catalogruas $catRuas){
        $isExist = false;

        try{
            $record = self::get($catRuas->ID);
            if(!$record){
                $record = new self();
                $record->setPrimaryKey($catRuas->ID);
            }
            else{
                $isExist = true;
            }
        }
        catch(\Exception $e){
            $record = new self();
            $record->setPrimaryKey($catRuas->ID);
            $msg = array('Status' => 'Error', "Data" => $e);
            print_r($msg);
            die;
        }

        $record->ID   = $catRuas->ID;
        $record->ControlNumber = $catRuas->ControlNumber;
        $record->BIBID = $catRuas->BIBID;
        $record->Title = $catRuas->Title;
        $record->Author = $catRuas->Author;
        $record->Edition = $catRuas->Edition;
        $record->Publisher = $catRuas->Publisher;
        $record->PublishLocation = $catRuas->PublishLocation;
        $record->PublishYear = $catRuas->PublishYear;
        $record->Publikasi = $catRuas->Publikasi;
        $record->Subject = $catRuas->Subject;
        $record->PhysicalDescription = $catRuas->PhysicalDescription;
        $record->ISBN = $catRuas->ISBN;
        $record->CallNumber = $catRuas->CallNumber;
        // $record->Note = $catRuas->Note;
        $record->Languages = $catRuas->Languages;
        $record->DeweyNo = $catRuas->DeweyNo;
        $record->ApproveDateOPAC = $catRuas->ApproveDateOPAC;
        $record->IsOPAC = $catRuas->IsOPAC;
        /*$record->IsBNI = $catRuas->IsBNI;
        $record->IsKIN = $catRuas->IsKIN;
        $record->IsRDA = $catRuas->IsRDA;*/
        $record->CoverURL = $catRuas->CoverURL;
        $record->Branch_id = $catRuas->Branch_id;
        $record->Worksheet_id = $catRuas->Worksheet_id;
        $record->CreateBy = $catRuas->CreateBy;
        $record->CreateDate = $catRuas->CreateDate;
        $record->CreateTerminal = $catRuas->CreateTerminal;
        $record->UpdateBy = $catRuas->UpdateBy;
        $record->UpdateDate = $catRuas->UpdateDate;
        $record->UpdateTerminal = $catRuas->UpdateTerminal;
        //$record->MARC_LOC = $catRuas->MARC_LOC;
        $record->PRESERVASI_ID = $catRuas->PRESERVASI_ID;
        $record->QUARANTINEDBY = $catRuas->QUARANTINEDBY;
        $record->QUARANTINEDDATE = $catRuas->QUARANTINEDDATE;
        $record->QUARANTINEDTERMINAL = $catRuas->QUARANTINEDTERMINAL;
        $record->Member_id = $catRuas->Member_id;
        $record->KIILastUploadDate = $catRuas->KIILastUploadDate;
        $record->worksheet_name = $catRuas->worksheet_name;
        $record->ISSERIAL = $catRuas->ISSERIAL;
        $record->subruas = $catRuas->subruas;

        try{
            if(!$isExist){
                $result = $record->insert();
                $msg = 'insert';
                unset($catRuas);
                unset($record);
            }
            else{
                $result = $record->update();
                $msg = 'update';
                unset($record);
                unset($catRuas);
            }
        }
        catch(\Exception $e){
            $result = false;
            $msg = array('Status' => 'Error', "Data" => $e);
            //handle error here
            $msg = array('Status' => 'Error', "Data" => $e);
            print_r($msg);
            die;


        }
        return $msg;
    }

         public function attributes()
         {
             return [
                 'ID',
                 'ControlNumber',
                 'BIBID',
                 'Title',
                 'Author',
                 'Edition',
                 'Publisher',
                 'PublishLocation',
                 'PublishYear',
                 'Publikasi',
                 'Subject',
                 'PhysicalDescription',
                 'ISBN',
                 'CallNumber',
                 'Note',
                 'Languages',
                 'DeweyNo',
                 'ApproveDateOPAC',
                 'IsOPAC',
                 'IsBNI',
                 'IsKIN',
                 'IsRDA',
                 'CoverURL',
                 'Branch_id',
                 'Worksheet_id',
                 'CreateBy',
                 'CreateDate',
                 'CreateTerminal',
                 'UpdateBy',
                 'UpdateDate',
                 'UpdateTerminal',
                 'MARC_LOC',
                 'PRESERVASI_ID',
                 'QUARANTINEDBY',
                 'QUARANTINEDDATE',
                 'QUARANTINEDTERMINAL',
                 'Member_id',
                 'KIILastUploadDate',
                 'worksheet_name',
                 'ISSERIAL',
                 'subruas',
                 ];
         }
}

?>