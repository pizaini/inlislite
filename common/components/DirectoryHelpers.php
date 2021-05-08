<?php

namespace common\components;

use Yii;
use yii\base\Model;
use common\models\Worksheets;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class DirectoryHelpers
{
   
    public static function GetBytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }
    
    public static function GetDirWorksheet($worksheetid) {
        
        //jika tidak mempunyai gambar maka akan di set sampul secara default.
        $model = Worksheets::findOne($worksheetid);
           
        return $model->Name;
    }

    public function mimeType($file)
    {
        // echo $file;die;
        $mimetype = mime_content_type($file);
        if(in_array($mimetype, array('image/jpg','image/jpeg', 'image/gif', 'image/png'))) {
           // echo 'OK';
           return true;
        } else {
            // echo 'Upload a real image, jerk!';
            unlink($file);
            return false;
        }
    }


    public static function CreateZip($dirLevel,$mainPath,$files,$prefixFileDownload,&$pathZip,&$pathReadyDownload)
    {
        $zip = new \ZipArchive();
        $now = date("Ymdhis");
        $userID = (string)Yii::$app->user->identity->ID;
        $dirUserID =  Yii::getAlias('@'.$mainPath).$userID;
        if(!is_dir($dirUserID))
        {
            mkdir($dirUserID , 0777);
        }
        
        $fileZip=$prefixFileDownload.$now.".zip";
        $pathZip= $dirUserID.DIRECTORY_SEPARATOR.$fileZip;
        $pathReadyDownload = $dirLevel.$mainPath.$userID.DIRECTORY_SEPARATOR.$fileZip;
        

        if(file_exists($pathZip)) {
            unlink ($pathZip); 
        }

        if ($zip->open($pathZip,  \ZipArchive::CREATE)) {
           foreach ($files as $file) {
                $filename =  pathinfo(realpath($file), PATHINFO_BASENAME); 
                $zip->addFile($file,$filename);
            }
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
    /**
     * [fungsi untuk menghapus file]
     * @param  string $file [path file]
     * @param  array $ext [allowed ext]
     * @param  array $mime [allowed mime]
     * @return mixed
     */
    public function deleteFile($file,$ext,$mime){  
        $rar_file = \rar_open($file) or die("Can't open Rar archive");
        $destination = pathinfo(realpath($file), PATHINFO_DIRNAME); 
        $filefolder =  pathinfo(realpath($file), PATHINFO_FILENAME); 
        $entries = \rar_list($rar_file);

        foreach ($entries as $entry) {
            $entry->extract($destination. DIRECTORY_SEPARATOR .$filefolder);
        }

        \rar_close($rar_file); 
        
    } 
    public static function RemoveDirRecursive($dir)
    {
        if (is_dir($dir)) { 
         $objects = scandir($dir); 
         foreach ($objects as $object) { 
           if ($object != "." && $object != "..") { 
             if (is_dir($dir."/".$object))
               self::RemoveDirRecursive($dir."/".$object);
             else
               unlink($dir."/".$object); 
           } 
         }
         rmdir($dir); 
       } 
    }

    public static function CheckDir($dir,$ext=null,$mime=null)
    {
        $dir_iterator = new \RecursiveDirectoryIterator($dir);
        $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);
        $ext=\Yii::$app->params['allowedExt'];
        $mime=\Yii::$app->params['allowedMime'];
        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                
                //check file by extension
                if (!in_array($file->getExtension(),$ext)) {
                    return false;         
                } 
                //check file by mime
                if (!in_array(\mime_content_type($file->getPathname()),$mime)) {
                    return false;
                }
            }    
        }
        return true;
    }

    public static function AddFile($realpath,$file)
    {
        $open = fopen($realpath.'/'.preg_replace("/(.*?)[.](.*)/", "$1", $file).'.html5','w');
        fwrite($open,"<html>\n");
        fwrite($open,"<body>\n");
        fwrite($open,"<embed src=\"".$file."\" style=\"width:100%; height:100%;\">\n");
        fwrite($open,"</body>\n");
        fwrite($open,"</html>");
        fclose($open);
    }

    public static function GetTemporaryFolder($catalogfilesID,$filetype){
    $datas = Yii::$app->db->createCommand("SELECT `catalogfiles`.`id`, `catalogfiles`.`FileURL`, `catalogfiles`.`FileFlash`, `catalogfiles`.`isPublish`, `worksheets`.`id`, `worksheets`.`name`,(SELECT  SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2))  as FormatFile,		(SELECT  SUBSTRING(FileFlash,(LENGTH(FileFlash)-LOCATE('.',REVERSE(FileURL)))+2))  as FormatFileFlash FROM `catalogfiles` LEFT JOIN `catalogs` ON `catalogs`.`ID` = `catalogfiles`.`Catalog_id` LEFT JOIN `worksheets` ON `worksheets`.`ID` = `catalogs`.`Worksheet_id` WHERE `catalogfiles`.`id`=".$catalogfilesID.";")->queryAll();
     switch ($filetype) {
        	case 1:
                    $wName=$datas[0]['name'];
                    $file=$datas[0]['FileURL'];
                    $format=$datas[0]['FormatFile'];
                    $addPath=$wName.'/'.$datas[0]['FileURL'];
                    $realpath = Yii::getAlias('@uploaded_files') . '/dokumen_isi/'.$addPath;


     			    $aliasPath=Yii::getAlias('@uploaded_files');
    		        //di sha1 biar kalo di base64decode ga keliatan namafile dan folderworksheetnya.
    		        //kalo ada yg nyari di hashdatabase keknya juga kemungkinan bisa ditembus kecil
                    $tempPath='/temporary/DigitalCollection/'.base64_encode(urlencode(sha1($addPath))).".".$format;
                    $temp=$aliasPath.$tempPath;

                    if (file_exists($realpath)) {
                        copy($realpath, $temp);
                        //chown($temp, 'www-data');
                        //chmod($temp, 0777);

                    }
                return $tempPath;
                break;
            case 2:
                
            // echo '<pre>';print_r($datas);die;
                $wName=$datas[0]['name'];
                $file=$datas[0]['FileFlash'];
                $format=$datas[0]['FormatFileFlash'];
                $format_file=$datas[0]['FormatFile'];
                $addPath=$wName.'/'.str_replace(".rar","",str_replace(".zip","",$datas[0]['FileURL']));
                $realpath = Yii::getAlias('@uploaded_files') . '/dokumen_isi/'.$addPath;

                $aliasPath=Yii::getAlias('@uploaded_files');
                //di sha1 biar kalo di base64decode ga keliatan namafile dan folderworksheetnya.
                //kalo ada yg nyari di hashdatabase keknya juga kemungkinan bisa ditembus kecil
                $tempPath_file='/temporary/DigitalCollection/'.base64_encode(urlencode(sha1($addPath))).".".$format_file;
                $tempPath='/temporary/DigitalCollection/'.base64_encode(urlencode(sha1($addPath)));
                $formats=preg_replace("/(.*?)[.](.*)/", "$2", $file);
                $returnPath=$tempPath.'/'.$file;
                $temp=$aliasPath.$tempPath;
                $temp_file=$aliasPath.$tempPath_file;

                if($format_file != 'rar' && $format_file != 'zip'){
                    // echo 'bukan rar';die;
                    if (file_exists($realpath)) {
                        copy($realpath, $temp_file);
                        //chown($temp, 'www-data');
                        //chmod($temp, 0777);

                    }
                    return $tempPath_file;
                } else {
                    // echo '<pre>';print_r(preg_replace("/(.*?)[.](.*)/", "$2", $file));
                    // // echo '<pre>';print_r($realpath.'/'.preg_replace("/(.*?)[.](.*)/", "$1", $file));
                    // // echo 'asdasd';
                    // // echo 'rar';
                    // die;
                    if(preg_match('/\bswf\b/i',$formats)){
                        DirectoryHelpers::AddFile($realpath,$file);
                        $returnPath=$tempPath.'/'.preg_replace("/(.*?)[.](.*)/", "$1", $file).'.html5';
                    }

                    if (file_exists($realpath) && !file_exists($temp)) {
                    $source = $realpath;
                    $dest= $temp;
                    mkdir($dest, 0755);
    				foreach (
    				 $iterator = new \RecursiveIteratorIterator(
    				  new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
    				  \RecursiveIteratorIterator::SELF_FIRST) as $item
    				) {
    				  if ($item->isDir()) {
    				        mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
    				    } else {
                            copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
    				    // copy($realpath, $temp);
    				  }
        				}
        				//copy($realpath, $temp);
        				//chown($temp, 'www-data');
        				//chmod($temp, 0777);
        				return $returnPath;
        			}else{
    				    return $returnPath;
    				}
                }
 			    break;       	
 			return false;	
        } 

    }

    public static function GetTemporaryFolderArticle($catalogfilesID,$filetype){
        $datas = Yii::$app->db->createCommand("SELECT `sa`.`id`, `sa`.`FileURL`, `sa`.`FileFlash`, `sa`.`isPublish`, `w`.`id`, `w`.`name`,(SELECT  SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2))  AS FormatFile,		(SELECT  SUBSTRING(FileFlash,(LENGTH(FileFlash)-LOCATE('.',REVERSE(FileURL)))+2))  AS FormatFileFlash 
                    FROM `serial_articlefiles`  sa
                    LEFT JOIN serial_articles s ON s.id = sa.`Articles_id`
                    LEFT JOIN `catalogs` cat ON `cat`.`ID` = s.`Catalog_id`
                    LEFT JOIN `worksheets` w ON `w`.`ID` = cat.`Worksheet_id`
                    WHERE `sa`.`id`=".$catalogfilesID.";")->queryAll();
        switch ($filetype) {
            case 1:
                $wName=$datas[0]['name'];
                $file=$datas[0]['FileURL'];
                $format=$datas[0]['FormatFile'];
                $addPath=$wName.'/'.$datas[0]['FileURL'];
                $realpath = Yii::getAlias('@uploaded_files') . '/dokumen_isi/'.$addPath;


                $aliasPath=Yii::getAlias('@uploaded_files');
                //di sha1 biar kalo di base64decode ga keliatan namafile dan folderworksheetnya.
                //kalo ada yg nyari di hashdatabase keknya juga kemungkinan bisa ditembus kecil
                $tempPath='/temporary/DigitalCollection/'.base64_encode(urlencode(sha1($addPath))).".".$format;
                $temp=$aliasPath.$tempPath;

                if (file_exists($realpath)) {
                    copy($realpath, $temp);
                    //chown($temp, 'www-data');
                    //chmod($temp, 0777);

                }
                return $tempPath;
                break;
            case 2:

                // echo '<pre>';print_r($datas);die;
                $wName=$datas[0]['name'];
                $file=$datas[0]['FileFlash'];
                $format=$datas[0]['FormatFileFlash'];
                $format_file=$datas[0]['FormatFile'];
                $addPath=$wName.'/'.str_replace(".rar","",str_replace(".zip","",$datas[0]['FileURL']));
                $realpath = Yii::getAlias('@uploaded_files') . '/dokumen_isi/'.$addPath;

                $aliasPath=Yii::getAlias('@uploaded_files');
                //di sha1 biar kalo di base64decode ga keliatan namafile dan folderworksheetnya.
                //kalo ada yg nyari di hashdatabase keknya juga kemungkinan bisa ditembus kecil
                $tempPath_file='/temporary/DigitalCollection/'.base64_encode(urlencode(sha1($addPath))).".".$format_file;
                $tempPath='/temporary/DigitalCollection/'.base64_encode(urlencode(sha1($addPath)));
                $formats=preg_replace("/(.*?)[.](.*)/", "$2", $file);
                $returnPath=$tempPath.'/'.$file;
                $temp=$aliasPath.$tempPath;
                $temp_file=$aliasPath.$tempPath_file;

                if($format_file != 'rar' && $format_file != 'zip'){
                    // echo 'bukan rar';die;
                    if (file_exists($realpath)) {
                        copy($realpath, $temp_file);
                        //chown($temp, 'www-data');
                        //chmod($temp, 0777);

                    }
                    return $tempPath_file;
                } else {
                    // echo '<pre>';print_r(preg_replace("/(.*?)[.](.*)/", "$2", $file));
                    // // echo '<pre>';print_r($realpath.'/'.preg_replace("/(.*?)[.](.*)/", "$1", $file));
                    // // echo 'asdasd';
                    // // echo 'rar';
                    // die;
                    if(preg_match('/\bswf\b/i',$formats)){
                        DirectoryHelpers::AddFile($realpath,$file);
                        $returnPath=$tempPath.'/'.preg_replace("/(.*?)[.](.*)/", "$1", $file).'.html5';
                    }

                    if (file_exists($realpath) && !file_exists($temp)) {
                    $source = $realpath;
                    $dest= $temp;
                    mkdir($dest, 0755);
                    foreach (
                     $iterator = new \RecursiveIteratorIterator(
                      new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                      \RecursiveIteratorIterator::SELF_FIRST) as $item
                    ) {
                      if ($item->isDir()) {
                            mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                        } else {
                            copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                        // copy($realpath, $temp);
                      }
                        }
                        //copy($realpath, $temp);
                        //chown($temp, 'www-data');
                        //chmod($temp, 0777);
                        return $returnPath;
                    }else{
                        return $returnPath;
                    }
                }
                break;          
            return false;   
        } 

    }

    public static function getNewFileName($pathdir,$path, $filename){
        $name = pathinfo(realpath($path), PATHINFO_FILENAME);
        $ext = pathinfo(realpath($path), PATHINFO_EXTENSION);
        $newname='';
        $counter = 1;
        $newpath = $path;
        self::checkFile($name,$ext,$pathdir,$newpath,$counter,$newname);
        if(empty($newname))
        {
            $newname=$filename;
        }
        return $newname.'.'.$ext;


    }
    public static function checkFile($name,$ext,$pathdir,$newpath,$counter,&$newname)
    {
        while (file_exists($newpath)) {
               $newname = $name .'_'.str_pad($counter , 3, '0', STR_PAD_LEFT);
               $newpath = $pathdir.DIRECTORY_SEPARATOR.$newname.'.'.$ext;
                //echo $newpath.'<br>';
               $counter++;
               self::checkFile($name,$ext,$pathdir,$newpath,$counter,$newname);
        }
    }
    

    
}
