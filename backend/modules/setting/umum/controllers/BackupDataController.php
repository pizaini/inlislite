<?php

namespace backend\modules\setting\umum\controllers;

use Yii;
use yii\web\Controller;
use oe\modules\backuprestore\models\UploadForm;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\base\ErrorException;

class BackupDataController extends \yii\web\Controller
{
    public $menu = [];
    public $tables = [];
    public $fp;
    public $file_name;
    public $_path = null;
    public $back_temp_file = 'db_inlislitev3_backup_';

	public function actionIndex(){
		return $this->render('index');
	}

	public function actionZip()
    {
        foreach (glob("*.zip") as $filename) {
            // echo "$filename size " . filesize($filename) . "\n";
            unlink("$filename");
        }

        function getDirectorySize($path) 
        { 
        $totalsize = 0; 
        $totalcount = 0; 
        $dircount = 0; 
        if ($handle = opendir ($path)) 
        { 
          while (false !== ($file = readdir($handle))) 
          { 
            $nextpath = $path . '/' . $file; 
            if ($file != '.' && $file != '..' && !is_link ($nextpath)) 
            { 
              if (is_dir ($nextpath)) 
              { 
                $dircount++; 
                $result = getDirectorySize($nextpath); 
                $totalsize += $result['size']; 
                $totalcount += $result['count']; 
                $dircount += $result['dircount']; 
              } 
              elseif (is_file ($nextpath)) 
              { 
                $totalsize += filesize ($nextpath); 
                $totalcount++; 
              } 
            } 
          } 
        } 
        closedir ($handle); 
        $total['size'] = $totalsize; 
        $total['count'] = $totalcount; 
        $total['dircount'] = $dircount; 
        return $total; 
        } 

        function sizeFormat($size) 
        { 
          if($size<1024) 
          { 
              return $size." bytes"; 
          } 
          else 
          { 
              $size=round($size/1024,1); 
              return $size." KB"; 
          }
          // else if($size<(1024*1024*1024)) 
          // { 
          //     $size=round($size/(1024*1024),1); 
          //     return $size." MB"; 
          // } 
          // else 
          // { 
          //     $size=round($size/(1024*1024*1024),1); 
          //     return $size." GB"; 
          // } 

        } 

        $path = dirname(Yii::getAlias('@backend')).'/uploaded_files';
        $ar=getDirectorySize($path); 

        
        // echo "Total size : ".sizeFormat($ar['size'])."<br>"; 
        // echo "No. of files : ".$ar['count']."<br>"; 
        // echo "No. of directories : ".$ar['dircount']."<br>";
        
        // die;
        $cek = "999000 KB";
        if(str_replace(" KB", "", $cek) > str_replace(" KB", "", sizeFormat($ar['size']))){
            $archive_file_name = 'backup_uploaded_files_'.date('Ymd').'.zip';
            // Initialize archive object
            $zip = new \ZipArchive();

            $zip->open($archive_file_name, \ZipArchive::CREATE);

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file)
            {
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($path) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
                else
                {
                    //create directory with empty listing
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($path) + 1);
                    if ($relativePath) {
                        $zip->addEmptyDir($relativePath);
                        /*echo($file);
                        echo "\n";
                        echo "relative = ".$relativePath;
                        echo "\n";*/
                    }
                }
            }

            // Zip archive will be created only after closing object
            $zip->close();
            // return $this->redirect('download-zip');
        }else{
            throw new ForbiddenHttpException;
        }
        // die;

        
        
        // $rootPath = dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/uploaded_files_1';
        
        

        
    }

    public function actionDownloadZip(){

        foreach (glob("*.zip") as $filename) {
            // echo "$filename size " . filesize($filename) . "\n";
            if($filename){
                header("Content-type: application/zip"); 
                header("Content-Disposition: attachment; filename=$filename"); 
                header("Pragma: no-cache"); 
                header("Expires: 0"); 
                readfile("$filename");
                unlink("$filename");
                exit;

                return $this->redirect('index');
            }else{
                return $this->redirect('index');
            }

        }
        return $this->redirect('index');
        

        
    }

    public function actionSwal (){

      
    }  


    public function actionCek(){
        // print_r($this->_path = Yii::$app->basePath . '/_backup/');die;
    	$cek_file = $this->_path = Yii::$app->basePath . '/_backup/';
		// $files1 = scandir($file_path);
        $scanned_directory = scandir($cek_file,1);

        
		// $scanned_directory = array_diff(scandir($cek_file), array('..', '.'));
		/*print_r($scanned_directory[0]);die;
        
		$file = $scanned_directory[0];*/

		$file_path = $this->_path = Yii::$app->basePath . '/_backup/'.$scanned_directory[0];
		
		if (file_exists($file_path)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file_path));
		    readfile($file_path);
		    unlink($file_path);
		    exit;
		}

		return $this->redirect('index');
		// if (file_exists($file_path)) {
		//     echo "The file $file exists";
		// } else {
		//     echo "The file $file does not exist";
		// }
		// print_r($files1[2]);die;
    }

    protected function getPath() {
        if (isset($this->module->path))
            $this->_path = $this->module->path;
        else
            $this->_path = Yii::$app->basePath . '/_backup/';

        if (!file_exists($this->_path)) {
            mkdir($this->_path);
            chmod($this->_path, '777');
        }
        return $this->_path;
    }

    public function execSqlFile($sqlFile) {
        $message = "ok";

        if (file_exists($sqlFile)) {
            $sqlArray = file_get_contents($sqlFile);

            $cmd = Yii::$app->db->createCommand($sqlArray);
            try {
                $cmd->execute();
            } catch (CDbException $e) {
                $message = $e->getMessage();
            }
        }
        return $message;
    }

    public function getColumns($tableName) {
        $sql = 'SHOW CREATE TABLE ' . $tableName;
        $cmd = Yii::$app->db->createCommand($sql);
        $table = $cmd->queryOne();

        $create_query = $table['Create Table'] . ';';

        $create_query = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_query);
        $create_query = preg_replace('/AUTO_INCREMENT\s*=\s*([0-9])+/', '', $create_query);
        if ($this->fp) {
            $this->writeComment('TABLE `' . addslashes($tableName) . '`');
            $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`;' . PHP_EOL . $create_query . PHP_EOL . PHP_EOL;
            fwrite($this->fp, $final);
        } else {
            $this->tables[$tableName]['create'] = $create_query;
            return $create_query;
        }
    }

    public function getData($tableName) {
        $sql = 'SELECT * FROM ' . $tableName;
        $cmd = Yii::$app->db->createCommand($sql);
        $dataReader = $cmd->query();

        $data_string = '';

        foreach ($dataReader as $data) {
            $itemNames = array_keys($data);
            $itemNames = array_map("addslashes", $itemNames);
            $items = join('`,`', $itemNames);

            $array = array_values($data);
            $array2 = array_map(function($value) {
               return $value === null ? 'NULL' : $value;
            }, $array);
            $itemValues = array_map("addslashes", $array2);
            $valueString = join("','", $itemValues);
            $valueString = "('" . $valueString . "'),";
            $valueString = preg_replace("['NULL']","NULL", $valueString);
            $values = "\n" . $valueString;
            if ($values != "") {
                $data_string .= "INSERT INTO `$tableName` (`$items`) VALUES" . rtrim($values, ",") . ";" . PHP_EOL;
            }
        }

        if ($data_string == '')
            return null;

        if ($this->fp) {
            $this->writeComment('TABLE DATA ' . $tableName);
            $final = $data_string . PHP_EOL . PHP_EOL . PHP_EOL;
            fwrite($this->fp, $final);
        } else {
            $this->tables[$tableName]['data'] = $data_string;
            return $data_string;
        }
    }

    public function getTables($dbName = null) {
        $sql = 'SHOW FULL TABLES WHERE Table_Type = "BASE TABLE"';
        $cmd = Yii::$app->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function StartBackup($addcheck = true) {
        $this->file_name = $this->path . $this->back_temp_file . date('Y_m_d_His') . '.sql';

        $this->fp = fopen($this->file_name, 'w+');

        if ($this->fp == null)
            return false;
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        if ($addcheck) {
            fwrite($this->fp, 'SET AUTOCOMMIT=0;' . PHP_EOL);
            fwrite($this->fp, 'START TRANSACTION;' . PHP_EOL);
            fwrite($this->fp, 'SET SQL_QUOTE_SHOW_CREATE = 1;' . PHP_EOL);
        }
        fwrite($this->fp, 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;' . PHP_EOL);
        fwrite($this->fp, 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;' . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        $this->writeComment('START BACKUP');
        return true;
    }

    public function EndBackup($addcheck = true) {
        $this->writeTriggers();
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        fwrite($this->fp, 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;' . PHP_EOL);
        fwrite($this->fp, 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;' . PHP_EOL);

        if ($addcheck) {
            fwrite($this->fp, 'COMMIT;' . PHP_EOL);
        }
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        $this->writeComment('END BACKUP');
        fclose($this->fp);
        $this->fp = null;
    }

    public function writeComment($string) {
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        fwrite($this->fp, '-- ' . $string . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
    }

    public function writeTriggers() {
        fwrite($this->fp, \common\components\BackupHelpers::backupHelp() . PHP_EOL);
    }

    public function actionCreate() {
        $start_time = ini_set('max_execution_time', 9000);

        $flashError = '';
        $flashMsg = '';
        
        if($start_time > 9000){
            $flashError = 'success';
            $flashMsg = 'The file was created !!!';

            \Yii::$app->getSession()->setFlash($flashError, $flashMsg);
            $this->redirect(array('index'));
        }else{
            $tables = $this->getTables();

            if (!$this->StartBackup()) {
                //render error
                $flashError = 'error';
                $flashMsg = 'Some errors creating the file';
                return $this->render('index');
            }

            foreach ($tables as $tableName) {
                $this->getColumns($tableName);
            }
            foreach ($tables as $tableName) {
                $this->getData($tableName);
            }
            $this->EndBackup();

            $flashError = 'success';
            $flashMsg = 'The file was created !!!';

            \Yii::$app->getSession()->setFlash($flashError, $flashMsg);
            
            // $this->redirect(array('index'));
        }
        
    }

    public function actionClean($redirect = true) {
        $ignore = array('tbl_user', 'tbl_user_role', 'tbl_event');
        $tables = $this->getTables();

        if (!$this->StartBackup()) {
            //render error
            Yii::$app->user->setFlash('success', "Error");
            return $this->render('index');
        }

        $message = '';

        foreach ($tables as $tableName) {
            if (in_array($tableName, $ignore))
                continue;
            fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
            fwrite($this->fp, 'DROP TABLE IF EXISTS ' . addslashes($tableName) . ';' . PHP_EOL);
            fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);

            $message .= $tableName . ',';
        }
        $this->EndBackup();

        // logout so there is no problme later .
        Yii::$app->user->logout();

        $this->execSqlFile($this->file_name);
        unlink($this->file_name);
        $message .= ' are deleted.';
        Yii::$app->session->setFlash('success', $message);
        return $this->redirect(array('index'));
    }

    public function actionDelete($file = null) {
        $flashError = '';
        $flashMsg = '';

        $file = $_GET[0]['filename'];

        $this->updateMenuItems();
        if (isset($file)) {
            $sqlFile = $this->path . basename($file);
            if (file_exists($sqlFile)) {
                unlink($sqlFile);
                $flashError = 'success';
                $flashMsg = 'The file ' . $sqlFile . ' was successfully deleted.';
            } else {
                $flashError = 'error';
                $flashMsg = 'The file ' . $sqlFile . ' was not found.';
            }
        } else {
            $flashError = 'error';
            $flashMsg = 'The file ' . $sqlFile . ' was not found.';
        }
        \Yii::$app->getSession()->setFlash($flashError, $flashMsg);
        $this->redirect(array('index'));
    }

    public function actionDownload($file = null) {
        $this->updateMenuItems();
        if (isset($file)) {
            $sqlFile = $this->path . basename($file);
            if (file_exists($sqlFile)) {
                $request = Yii::$app->getRequest();
                $request->sendFile(basename($sqlFile), file_get_contents($sqlFile));
            }
        }
        throw new CHttpException(404, Yii::t('app', 'File not found'));
    }

    // public function actionIndex() {
    //     //$this->layout = 'column1';
    //     $this->updateMenuItems();
    //     $path = $this->path;
    //     $dataArray = array();

    //     $list_files = glob($path . '*.sql');
    //     if ($list_files) {
    //         $list = array_map('basename', $list_files);
    //         sort($list);
    //         foreach ($list as $id => $filename) {
    //             $columns = array();
    //             $columns['id'] = $id;
    //             $columns['name'] = basename($filename);
    //             $columns['size'] = filesize($path . $filename);

    //             $columns['create_time'] = date('Y-m-d H:i:s', filectime($path . $filename));
    //             $columns['modified_time'] = date('Y-m-d H:i:s', filemtime($path . $filename));

    //             $dataArray[] = $columns;
    //         }
    //     }
    //     $dataProvider = new ArrayDataProvider(['allModels' => $dataArray]);
    //     return $this->render('index', array(
    //                 'dataProvider' => $dataProvider,
    //     ));
    // }

    public function actionSyncdown() {
        $tables = $this->getTables();

        if (!$this->StartBackup()) {
            //render error
            return $this->render('index');
        }

        foreach ($tables as $tableName) {
            $this->getColumns($tableName);
        }
        foreach ($tables as $tableName) {
            $this->getData($tableName);
        }
        $this->EndBackup();
        return $this->actionDownload(basename($this->file_name));
    }

    public function actionRestore($file = null) {
        $flashError = '';
        $flashMsg = '';

        $file = $_GET[0]['filename'];

        $this->updateMenuItems();
        $sqlFile = $this->path . basename($file);
        if (isset($file)) {
            $sqlFile = $this->path . basename($file);
            $flashError = 'success';
            $flashMsg = 'Looks like working :)';
        } else {
            $flashError = 'error';
            $flashMsg = 'Problems with the file name';
        }
        $this->execSqlFile($sqlFile);

        \Yii::$app->getSession()->setFlash($flashError, $flashMsg);
        $this->redirect(array('index'));
    }

    public function actionUpload() {
        $model = new UploadForm();
        if (isset($_POST['UploadForm'])) {
            $model->attributes = $_POST['UploadForm'];
            //oe change cUploaded for this
            $model->upload_file = UploadedFile::getInstance($model, 'upload_file');
            if ($model->upload_file->saveAs($this->path . $model->upload_file)) {
                // redirect to success page
                return $this->redirect(array('index'));
            }
        }

        return $this->render('upload', array('model' => $model));
    }

    protected function updateMenuItems($model = null) {
        // create static model if model is null
        if ($model == null)
            $model = new UploadForm();

        switch ($this->action->id) {
            case 'restore': {
                    $this->menu[] = array('label' => Yii::t('app', 'View Site'), 'url' => Yii::$app->HomeUrl);
                }
            case 'create': {
                    $this->menu[] = array('label' => Yii::t('app', 'List Backup'), 'url' => array('index'));
                }
                break;
            case 'upload': {
                    $this->menu[] = array('label' => Yii::t('app', 'Create Backup'), 'url' => array('create'));
                }
                break;
            default: {
                    $this->menu[] = array('label' => Yii::t('app', 'List Backup'), 'url' => array('index'));
                    $this->menu[] = array('label' => Yii::t('app', 'Create Backup'), 'url' => array('create'));
                    $this->menu[] = array('label' => Yii::t('app', 'Upload Backup'), 'url' => array('upload'));
                    $this->menu[] = array('label' => Yii::t('app', 'Restore Backup'), 'url' => array('restore'));
                    $this->menu[] = array('label' => Yii::t('app', 'Clean Database'), 'url' => array('clean'));
                    $this->menu[] = array('label' => Yii::t('app', 'View Site'), 'url' => Yii::$app->HomeUrl);
                }
                break;
        }
    }
}