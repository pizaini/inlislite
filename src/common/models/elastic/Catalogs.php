<?php
/**
 * Created by PhpStorm.
 * User: mazpaijo
 * Date: 03/01/2018
 * Time: 11.39
 */
namespace common\models\elastic;
use \common\models\base\CatalogRuas;
class Catalogs extends \yii\elasticsearch\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */

    public static function index(){
        return "catalog";
    }

    public static function type(){
        return "catalogruas";
    }

    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
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
            ];
    }

}

?>