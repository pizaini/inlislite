<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use common\models\CollectionBiblio;
use common\components\CatalogHelpers;
use common\components\Helpers;
use common\components\MarcHelpers;

class ImportMarcForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

   /* public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx','checkExtensionByMimeType' => false],
        ];
    }*/
    
    public function upload()
    {
        $path = Yii::getAlias('@uploaded_files') . '/temporary/marc/imported/';
        if ($this->validate()) {
            $this->file->saveAs($path.Yii::$app->user->identity->ID.'_' . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }

    }

    public function sru($url,$port,$db,$criteria,$query,$startRecord,$maxRecord,$protocol,&$taglist,$mode)
    {
        try {
            MarcHelpers::SruToRecord($url,$port,$db,$criteria,$query,$startRecord,$maxRecord,$protocol,$taglist,$mode);
        } catch(ErrorException $e){
                Yii::warning($e);
                echo $e;
                return false;
        }
    }

    //Old mapping taglist
    public function ekstrak(&$taglist,&$modelbib,$type='MARC21MRC'){
        try{
            
                $path = Yii::getAlias('@uploaded_files') . '/temporary/marc/imported/'.Yii::$app->user->identity->ID.'_'.$this->file->baseName . '.' . $this->file->extension;
                MarcHelpers::FileToRecord($path,$taglist,$modelbib,$type);
                    
            }catch(ErrorException $e){
                Yii::warning($e);
                echo $e;
                return false;
            }
    } 

    //New mapping taglist
    public function ekstrak2(&$taglist,&$modelbib,$type='MARC21MRC'){
        try{
            
                $path = Yii::getAlias('@uploaded_files') . '/temporary/marc/imported/'.Yii::$app->user->identity->ID.'_'.$this->file->baseName . '.' . $this->file->extension;
                MarcHelpers::FileToRecord2($path,$taglist,$modelbib,$type);
                    
            }catch(ErrorException $e){
                Yii::warning($e);
                echo $e;
                return false;
            }
    } 

   
   
    /**
    * Process deletion of file imported
    *
    * @return boolean the status of deletion
    */
    public function deleteFile() {
        $path = Yii::getAlias('@uploaded_files') . '/temporary/imported_data_sheet/imported/';
        $file = $path. $this->file->baseName . '.' . $this->file->extension;
        chmod($file, 0666);
        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }
 
        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }
 
        return true;
    }
}