<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package KatalogController.php
 * @version 1.0.0
 * @author Andy Dodot <dodot.kurniawan@gmail.com>
 */

namespace keanggotaan\controllers;

use common\components\OpacHelpers;

use common\models\CatalogfilesOnline;
use common\models\SerialArticleFilesSearch;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

//load model
use common\models\AuthData;
use common\models\Worksheets;
use common\models\Worksheetfields;
use common\models\Worksheetfielditems;
use common\models\WorksheetFieldSearch;
use common\models\Refferenceitems;
use common\models\Fielddatas;
use common\models\FielddataSearch;
use common\models\Fieldindicator1Search;
use common\models\Fieldindicator2Search;
use common\models\FieldSearch;
use common\models\Catalogs;
use common\models\CatalogRuas;
use common\models\CatalogSubruas;
use common\models\Catalogfiles;
use common\models\Collections;
use common\models\Collectionsources;
use common\models\Collectionloanitems;
use common\models\CollectionBiblio;
use common\models\CatalogSearch;
use common\models\LocationLibrary;
use common\models\Colloclib;
use common\models\Partners;
use common\models\Users;
use common\models\KataSandang;
use common\models\Fields;
use common\models\QuarantinedCollections;
use common\models\QuarantinedCatalogs;
use common\models\QuarantinedCatalogSearch;
use common\models\Stockopnamedetail;
use common\models\Tempnoinduk;
use common\models\Library;
use common\models\CollectionSearch;
use common\models\CatalogfileSearch;
use common\models\KeranjangKatalog;
use common\models\Settingparameters;
use common\models\Collectioncategorys;
use common\models\Collectionmedias;
use common\models\SerialArticles;
use common\models\SerialArticlesSearch;
use common\models\SerialArticlefiles;
use common\models\Members;
//load component / helper
use common\components\MarcHelpers;
use common\components\CatalogHelpers;
use common\components\CatalogHelpers2;
use common\components\CollectionHelpers;
use common\components\DirectoryHelpers;
use yii\helpers\ArrayHelper;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\validators\Validator;
use yii\helpers\Json;
use yii\web\Response;
use kartik\widgets\ActiveForm;
Use yii\helpers\FileHelper;
use mdm\admin\components\Helper;
use common\components\ElasticHelper;
use common\models\UserMemberOnlines;
use common\models\CatalogsOnline;
/**
 * KatalogController implements the CRUD actions for Collections model.
 */
class KatalogController extends Controller
{
    public  $counterLoop = 0;
    public  $isFromMember = false;
    public $memberonlineID;
    public $modelMemberOnline;

    public function behaviors()
    {
        return [
        'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
        'delete' => ['post'],
        ],
        ],
        ];
    }

    /**
     * [fungsi untuk redirect ke halaman terakhir d akses]
     * @return mixed
     */
    public function goBackUrl()
    {
        if(Yii::$app->request->referrer){
            return $this->redirect(Yii::$app->request->referrer);
        }else{
            return $this->goHome();
        }
    }

    /**
     * [fungsi untuk memunculkan flash message]
     * @param  string $type    [info,warning,danger,success]
     * @param  string $Message [isi dari pesan yang akan tampil]
     * @return mixed
     */
    public function actionFlashMessage($type,$Message)
    {
        Yii::$app->getSession()->setFlash('success', [
                        'type' => $type,
                        'duration' => 2000,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app',$Message),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
    }

    /**
     * [fungsi untuk menampilkan detil koleksi]
     * @param  integer $id [ID koleksi]
     * @return mixed
     */
    public function actionDetailCollection($id)
    {
        $model = Collections::findOne($id);

        return $this->renderAjax('detailCollections', [
            'model' =>$model
            ]);
    }

    /**
     * [fungsi untuk download file marc]
     * @param  integer $id   [Katalog ID]
     * @param  string $type [marc21,marcxml,mods,dc_rdf,dc_srw,dc_oai]
     * @return file
     */
    public function actionDownload($id,$type)
    {
        MarcHelpers::Export($id,$type);
    }

    public function actionDownloadKatalogAll($actionid){
        // print_r($actionid);die;
        $cekID = Yii::$app->db->createCommand('SELECT ID FROM catalogs')->queryAll();
        $ids = array_column($cekID, 'ID');
        // echo'<pre>';print_r($first_names);
        switch ($actionid) {

            case 'mrc':
                $type="MARC21";
                break;
            case 'xml':
                $type="MARCXML";
                break;
            case 'Format MODS':
                $type="MODS";
                break;
            case 'Format Dublin Core (RDF)':
                $type="DC_RDF";
                break;
            case 'Format Dublin Core (OAI)':
                $type="DC_OAI";
                break;
            case 'Format Dublin Core (SRW)':
                $type="DC_SRW";
                break;                
        }
        $id=$ids;
        if (sizeof($id)==1) {
            MarcHelpers::Export($id,$type);
        } else {
            MarcHelpers::MultipleExport($id,$type);
        }
    }

    public function actionDownloadKatalog($actionid,array $ids)
    {   
        // print_r($ids);die;
        switch ($actionid) {

            case 'mrc':
                $type="MARC21";
                break;
            case 'xml':
                $type="MARCXML";
                break;
            case 'Format MODS':
                $type="MODS";
                break;
            case 'Format Dublin Core (RDF)':
                $type="DC_RDF";
                break;
            case 'Format Dublin Core (OAI)':
                $type="DC_OAI";
                break;
            case 'Format Dublin Core (SRW)':
                $type="DC_SRW";
                break;                
        }
        $id=$ids;
        if (sizeof($id)==1) {
            MarcHelpers::Export($id,$type);
        } else {
            MarcHelpers::MultipleExport($id,$type);
        }
    }

    /**
     * [actionCetakKartuProses download kartu katalog]
     * @param  integer $idcardformat [utama,judulsubyek,pengarang tambahan,seri]
     * @param  array  $ids          [record yang di centang]
     * @return file
     */
    public function actionCetakKartuProses($idcardformat,array $ids)
    {
        if(count($ids) > 0)
        {
            CatalogHelpers::cetakKartu($idcardformat,$ids);
        }
        
    }

    /**
     * [fungsi untuk karantina data katalog]
     * @param  integer $id [id katalog]
     * @return mixed
     */
    public function actionKarantinaProses($id)
    {
        (int)$countColl = Collections::find()
         ->where(['Catalog_id'=>$id])
         ->count();

         (int)$countFileCover = Catalogs::find()
         ->where(['ID'=>$id])
         ->andWhere(['not', ['CoverURL' => null]])
         ->count();

         (int)$countFiles = Catalogfiles::find()
         ->where(['Catalog_id'=>$id])
         ->count();

         $model = Catalogs::findOne($id);
         if($countColl > 0)
         {
            $this->actionFlashMessage('danger',yii::t('app','Koleksi dengan BIBID=').$model->BIBID.yii::t('app',' tersambung dengan data koleksi, harap pindahkan dulu data koleksi'));
         }
         else if($countFileCover > 0)
         {
            $this->actionFlashMessage('danger',yii::t('app','Koleksi dengan BIBID=').$model->BIBID.yii::t('app',' memiliki cover, harap pindahkan dulu cover'));
         }
         else if($countFiles > 0)
         {
            $this->actionFlashMessage('danger',yii::t('app','Koleksi dengan BIBID=').$model->BIBID.yii::t('app',' tersambung dengan data konten digital, harap pindahkan dulu data konten digital'));
         }else{
            $trans = Yii::$app->db->beginTransaction();
            try {
                $modelq1 = QuarantinedCatalogs::findOne($id);
                if($modelq1 != null)
                {
                    //Jika sudah ada id katalog di tabel karantina maka d delete dahulu
                    $modelq1->delete();
                }
                
                    //Insert ke table karantina dari table katalog
                    $command = Yii::$app->db->createCommand('INSERT INTO quarantined_catalogs SELECT * FROM catalogs WHERE ID=:ID');
                    $command->bindParam(':ID', $id);
                    if($command->execute())
                    {
                        //Update data timespan karantina
                        $modelq2 = QuarantinedCatalogs::findOne($id);
                        $modelq2->QUARANTINEDBY = (int)Yii::$app->user->identity->ID;
                        $modelq2->QUARANTINEDDATE = new \yii\db\Expression('NOW()');
                        $modelq2->QUARANTINEDTERMINAL = \Yii::$app->request->userIP;
                        if($modelq2->save())
                        {
                            //Insert ke table karantina ruas dari table katalog ruas
                            $command = Yii::$app->db->createCommand('INSERT INTO quarantined_catalog_ruas SELECT * FROM catalog_ruas WHERE CatalogId=:ID');
                            $command->bindParam(':ID', $id);
                            if($command->execute())
                            {
                                //Insert ke table karantina subruas dari table katalog subruas
                                $command = Yii::$app->db->createCommand('INSERT INTO quarantined_catalog_subruas SELECT * FROM catalog_subruas WHERE RuasID IN (SELECT ID FROM catalog_ruas WHERE CatalogId=:ID)');
                                $command->bindParam(':ID', $id);
                                if($command->execute())
                                {
                                    //[warning] history save nya belum
                                    //
                                    //
                               
                                    if($model->delete())
                                    {
                                        $trans->commit(); 
                                        $this->actionFlashMessage('info',yii::t('app','Koleksi dengan BIBID=').$model->BIBID.yii::t('app',' berhasil dikarantina.'));
                                        return $this->redirect(['index']);
                                    }else{
                                        $this->actionFlashMessage('danger',yii::t('app','Koleksi dengan BIBID=').$model->BIBID.yii::t('app',' gagal dihapus.'));
                                    }
                                }
                            }
                            
                        }else{
                            $this->actionFlashMessage('danger',yii::t('app','Koleksi dengan BIBID=').$model->BIBID.yii::t('app',' gagal mengubah timestamp di koleksi'));
                        }
                    }else{
                        $this->actionFlashMessage('danger',yii::t('app','Koleksi dengan BIBID=').$model->BIBID.yii::t('app',' Gagal disimpan di Karantina koleksi').var_dump($modelq->getErrors()));
                    }
                
            } catch (Exception $e) {
                $trans->rollback();
            }
         }
         return $this->goBackUrl();
    }

    /**
     * [fungsi untuk hapus cover katalog]
     * @param  integer $id [ID katalog]
     * @return mixed
     */
    public function actionDeleteCover($id,$refer)
    {
        $model  = Catalogs::findOne($id);
        $worksheetDir=DirectoryHelpers::GetDirWorksheet($model->Worksheet_id);
        $path =  Yii::getAlias('@uploaded_files').
        DIRECTORY_SEPARATOR.
        'sampul_koleksi'.
        DIRECTORY_SEPARATOR.
        'original'.
        DIRECTORY_SEPARATOR.
        $worksheetDir.
        DIRECTORY_SEPARATOR.
        $model->CoverURL;

        $model->CoverURL= NULL;
        if($model->save(false))
        { 
            if(file_exists($path))
            {
                unlink($path);
            }
                      
            //Set active tab
            \Yii::$app->session['SessCatalogTabActive'] = 'cover';
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 500,
                'icon' => 'fa fa-info-circle',
                'message' => Yii::t('app','Success Delete'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            if(Yii::$app->request->referrer){
                if(strpos(Yii::$app->request->referrer,'&refer') > -1)
                {
                    $redirectUrl = Yii::$app->request->referrer;
                }else{
                    $redirectUrl = Yii::$app->request->referrer.'&refer='.$refer;
                }
                return $this->redirect($redirectUrl);
            }
            
        }
    }

    /**
     * [fungsi untuk upload cover]
     * @param  integer $id [id katalog]
     * @return mixed
     */
    public function actionUploadCover($id,$refer) {
        if (isset($_FILES['catalogcover' . $id])) {
            $file = \yii\web\UploadedFile::getInstanceByName('catalogcover' . $id);
            $model= CatalogsOnline::findOne($id);
            $worksheetDir=DirectoryHelpers::GetDirWorksheet($model->Worksheet_id);
          //save original
            if ($file->saveAs(Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.$worksheetDir.'/' . $id . '.' . $file->extension))) {
                 //save original
                //if ($file->saveAs(Yii::getAlias('@uploaded_files/sampul_koleksi/thumbnail/'.$worksheetDir.'/' .$id . '.' . $file->extension))) {
                  
                    $model->CoverURL = $id . '.' . $file->extension;
                    if($model->save())
                    {
                        //Set active tab
                        \Yii::$app->session['SessCatalogTabActive'] = 'cover';
                        //Now save file data to database
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'info',
                            'duration' => 500,
                            'icon' => 'fa fa-info-circle',
                            'message' => Yii::t('app', 'Success Upload'),
                            'title' => 'Info',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);

                        if(Yii::$app->request->referrer){
                            if(strpos(Yii::$app->request->referrer,'&refer') > -1)
                            {
                                $redirectUrl = Yii::$app->request->referrer;
                            }else{
                                $redirectUrl = Yii::$app->request->referrer.'&refer='.$refer;
                            }
                            return $this->redirect($redirectUrl);
                        }
                    }
                //}
            } 
            
            
        }
    }

    /**
     * [fungsi untuk hapus konten digital per ID catalogfiles]
     * @param  integer $id [id konten digital]
     * @return mixed
     */
    public function actionDeleteKontenDigital($id)
    {
        if(CatalogHelpers::deleteKontenDigital($id))
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * [fungsi reset halaman konten digital]
     * @param  integer  $id      [id katalog]
     * @param  integer $isreset [description]
     * @return mixed
     */
    public function actionResetKontenDigital($id,$isreset=0)
    {
        //Set active tab
        \Yii::$app->session['SessCatalogTabActive'] = 'kontendigital';
        if($isreset==0)
        {
            Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
        }
        return $this->redirect(['update?for=cat&id='.$id.'&edit=t']);
    }

    /**
     * [fungsi untuk mereset/menghapus file-file temporary]
     * @return mixed
     */
    public function resetTempDirKontenDigital()
    {
        $mainPath = 'uploaded_files/temporary/konten_digital/';
        $userID = (string)Yii::$app->user->identity->ID;
        if($userID!=null)
        {
            $dirUserID =  Yii::getAlias('@'.$mainPath).$userID;
            \common\components\DirectoryHelpers::RemoveDirRecursive($dirUserID);
        }
    }

    /**
     * [fungsi untuk mengekstrak rar]
     * @param  string $file [path file]
     * @return mixed
     */
    public function unrarKontenDigital($file){  
        $rar_file = \rar_open($file) or die("Can't open Rar archive");
        $destination = pathinfo(realpath($file), PATHINFO_DIRNAME); 
        $filefolder =  pathinfo(realpath($file), PATHINFO_FILENAME); 
        $entries = \rar_list($rar_file);

        foreach ($entries as $entry) {
            $entry->extract($destination. DIRECTORY_SEPARATOR .$filefolder);
        }

        \rar_close($rar_file); 
        
    }
      

    /**
     * [fungsi untuk mengekstrak zip]
     * @param  string $file [path file]
     * @return mixed
     */
    public function unzipKontenDigital($file){  
                // create object  
        $zip = new \ZipArchive() ; 
        $destination = pathinfo(realpath($file), PATHINFO_DIRNAME); 
        $filefolder =  pathinfo(realpath($file), PATHINFO_FILENAME); 
        // open archive  
        if ($zip->open($file)) 
        {  
            // extract contents to destination directory  
            $zip->extractTo($destination. DIRECTORY_SEPARATOR .$filefolder);  
        }  
        else
        {
            die ('Could not open archive:' .$file);  
        }
        
        // close archive  
        $zip->close();    
    }

    /**
     * [fungsi untuk cek setiap file, dan mengenerate nama file baru agar tidak konflik]
     * @param  string $name     [file name]
     * @param  string $ext      [untuk menambahkan extension pada generate file name]
     * @param  string $pathdir  [file path untuk membuat path baru dengan generate file name]
     * @param  string $newpath  [file path untuk pengecekan file fisik]
     * @param  integer $counter  [penanda angka yang akan di tambah jika ada file yang sama]
     * @param  string &$newname [variable output]
     * @return string $newname
     */
    public function checkFile($name,$ext,$pathdir,$newpath,$counter,&$newname)
    {
        while (file_exists($newpath)) {
               $newname = $name .'_'.str_pad($counter , 3, '0', STR_PAD_LEFT);
               $newpath = $pathdir.DIRECTORY_SEPARATOR.$newname.'.'.$ext;
                //echo $newpath.'<br>';
               $counter++;
               $this->checkFile($name,$ext,$pathdir,$newpath,$counter,$newname);
        }
    }

    /**
     * [fungsi untuk mendapatkan nama file baru]
     * @param  string $pathdir  [file path untuk membuat path baru dengan generate file name]
     * @param  string $path     [file path untuk mendapatkan properties file name]
     * @param  string $filename [file name]
     * @return string 
     */
    public function getNewFileNameKontenDigital($pathdir,$path, $filename){
        $name = pathinfo(realpath($path), PATHINFO_FILENAME);
        $ext = pathinfo(realpath($path), PATHINFO_EXTENSION);
        $newname='';
        $counter = 1;
        $newpath = $path;
        $this->checkFile($name,$ext,$pathdir,$newpath,$counter,$newname);
        if(empty($newname))
        {
            $newname=$filename;
        }
        return $newname.'.'.$ext;
        die;
    }

     /**
     * [fungsi untuk upload konten digital katalog]
     * @param  [int] $id [id katalog]
     * @return mixed
     */
    public function actionUploadKontenDigital($id) {
        $model = new CatalogfilesOnline();
        if (Yii::$app->request->isPost) {
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $post= Yii::$app->request->post();

            $fileflash = $post['fileExecutable'];
            $isCompress = $post['isCompress'];
            $modelcat =  Catalogs::find()->addSelect(['Worksheet_id'])->where(['ID'=>$id])->one();
            $worksheetDir=DirectoryHelpers::GetDirWorksheet($modelcat->Worksheet_id);
            //echo $worksheetDir; die;
            $ext = $model->file->getExtension();

            $filepath = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDir.DIRECTORY_SEPARATOR.$model->file->name);
            $dirpath = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDir);

            if (file_exists($filepath)) {
                $newFileName = $this->getNewFileNameKontenDigital($dirpath ,$filepath,$model->file->name);
            }else{
                $newFileName = $model->file->name;
            }
            $filepath = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDir.'/'.$newFileName);

            //echo $filepath;
            $model->CreateBy =1;
            $model->FileURL = $newFileName;
            $model->FileFlash =$fileflash;
            $model->Catalog_id= $id;
            $trans = Yii::$app->db->beginTransaction();
            //$bibid= Catalogs::findOne($id)->BIBID;      
            try {

                //FileHelper::createDirectory(Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDir.'/' .$bibid),777);
                if($model->validate())
                {
                    if ($model->file->saveAs($filepath)) {
                        if($isCompress==1)
                        {
                            if($model->file->getExtension() == 'rar')
                            {
                                $this->unrarKontenDigital($filepath);
                            }
                            else if ($model->file->getExtension() == 'zip')
                            {
                                $this->unzipKontenDigital($filepath);
                            }
                            
                        }
                        if($model->save(false))
                        {
                            $trans->commit();
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return ['files'=>[
                                [
                                    'url'=>$filepath,
                                    'thumbnail_url'=>$filepath,
                                    'name'=>$model->file->name,
                                    'type'=>$model->file->type,
                                    'size'=>$model->file->size,
                                    'delete_url'=>'delete-konten-digital?id='.$model->getPrimaryKey(),
                                    'delete_type'=>'DELETE'
                                ]
                            ]];
                        }
                        /*else{
                            var_dump($model->getErrors());
                        }*/
                    }else{
                        echo yii::t('app','Gagal mengunggah berkas ke server');
                    }
                }else{
                        $error='';
                        if($model->getErrors()){
                            foreach ($model->getErrors() as $key => $value) {
                                foreach ($value as $key2 => $value2) {
                                    $error .=$value2.'<br>';
                                }
                            }
                        }
                        echo $error;
                }
            } catch (Exception $e) {
                $trans->rollback();
            }
        }
    }

    /**
     * [fungsi untuk upload konten digital katalog]
     * @param  [int] $id [id katalog]
     * @return mixed
     */
    public function actionUploadKontenDigitalArtikel($id) {
        $model = new SerialArticlefiles();
        if (Yii::$app->request->isPost) {
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $post= Yii::$app->request->post();

            $fileflash = $post['fileExecutable'];
            $isCompress = $post['isCompress'];
            $serialID = $post['serialID'];
            $modelcat =  Catalogs::find()->addSelect(['Worksheet_id'])->where(['ID'=>$id])->one();
            $worksheetDir=DirectoryHelpers::GetDirWorksheet($modelcat->Worksheet_id);
            //echo $worksheetDir; die;
            $ext = $model->file->getExtension();

            $filepath = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDir.DIRECTORY_SEPARATOR.$model->file->name);
            $dirpath = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDir);

            if (file_exists($filepath)) {
                $newFileName = $this->getNewFileNameKontenDigital($dirpath ,$filepath,$model->file->name);
            }else{
                $newFileName = $model->file->name;
            }
            $filepath = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDir.'/'.$newFileName);

            //echo $filepath;
            $model->FileURL = $newFileName;
            $model->FileFlash =$fileflash;
            $model->Articles_id= $serialID;
            $trans = Yii::$app->db->beginTransaction();
            //$bibid= Catalogs::findOne($id)->BIBID;
            try {

                //FileHelper::createDirectory(Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDir.'/' .$bibid),777);
                if($model->validate())
                {
                    if ($model->file->saveAs($filepath)) {
                        if($isCompress==1)
                        {
                            if($model->file->getExtension() == 'rar')
                            {
                                $this->unrarKontenDigital($filepath);
                            }
                            else if ($model->file->getExtension() == 'zip')
                            {
                                $this->unzipKontenDigital($filepath);
                            }

                        }
                        if($model->save(false))
                        {
                            $trans->commit();
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return ['files'=>[
                                [
                                    'url'=>$filepath,
                                    'thumbnail_url'=>$filepath,
                                    'name'=>$model->file->name,
                                    'type'=>$model->file->type,
                                    'size'=>$model->file->size,
                                    'delete_url'=>'delete-konten-digital?id='.$model->getPrimaryKey(),
                                    'delete_type'=>'DELETE'
                                ]
                            ]];
                        }
                        /*else{
                            var_dump($model->getErrors());
                        }*/
                    }else{
                        echo yii::t('app','Gagal mengunggah berkas ke server');
                    }
                }else{
                    $error='';
                    if($model->getErrors()){
                        foreach ($model->getErrors() as $key => $value) {
                            foreach ($value as $key2 => $value2) {
                                $error .=$value2.'<br>';
                            }
                        }
                    }
                    echo $error;
                }
            } catch (Exception $e) {
                $trans->rollback();
            }
        }
    }

    /**
     * [fungsi untuk autosuggest pada entri no panggil]
     * @param  integer $id    [id katalog / id koleksi]
     * @param  string  $refer [cat,coll]
     * @return string json
     */
    public function actionAutoSuggestCallNumber($id,$refer)
    {
        $result = CatalogHelpers::getCallNumber($id,$refer);
        return json_encode($result);
    }

    /**
     * [fungsi autosuggest pada entri tajuk pengarang]
     * @return string json
     */
    public function actionTajukPengarang()
    {
        $result = AuthData::getAuthDataPengarang(true);
        return json_encode($result);
    }

    /**
     * [fungsi autosuggest pada entri tajuk subyek]
     * @return string json
     */
    public function actionTajukSubyek()
    {
        $result = AuthData::getAuthDataSubyek(true);
        return json_encode($result);
    }

    /**
     * [fungsi autosuggest pada entri tajuk ddc]
     * @return string json
     */
    public function actionTajukDdc($subject)
    {
        $result = AuthData::getAuthDataDDC(true,$subject);
        return json_encode($result);
    }

    /**
     * [fungsi autosuggest pada entri tajuk pengarang dengan $]
     * @return string json
     */
    public function actionTajukPengarangDollar()
    {
        $result = AuthData::getAuthDataPengarang(false);
        return json_encode($result);

    }

    /**
     * [fungsi autosuggest pada entri tajuk subyek dengan $]
     * @return string json
     */
    public function actionTajukSubyekDollar()
    {
        $result = AuthData::getAuthDataSubyek(false);
        return json_encode($result);
    }
    
    /**
     * [fungsi untuk mendapatkan tanggal hari ini dalam format Y-m-d H:i:s]
     * @return string
     */
    public function actionGetDatetimeNowStr()
    {
        $time = new \DateTime('now', new \DateTimeZone('UTC'));
        $timestr = $time->format('Y-m-d H:i:s');
        return $timestr;
    }

    /**
     * [fungsi untuk pesan multiple checkbox dalam history log]
     * @param  bool $success      [status pesan]
     * @param  string $value        [isi pesan]
     * @param  string $karantinaMsg [karantina pesan]
     * @return string
     */
    public function actionGetMessageCheckboxProcess($success,$value,$karantinaMsg='')
    {
        if($success == true)
            if($karantinaMsg != '')
                return '<span style="color:green">'.$this->actionGetDatetimeNowStr().' - '.$karantinaMsg.' '.yii::t('app','pada').' BIBID = '.$value.'</span><br>';
            else
                return '<span style="color:green">'.$this->actionGetDatetimeNowStr().' - '.yii::t('app','Berhasil diubah').' BIBID = '.$value.'</span><br>';
        else
            if($karantinaMsg != '')
                return '<span style="color:red">'.$this->actionGetDatetimeNowStr().' - '.$karantinaMsg.' BIBID = '.$value.'</span><br>'; 
            else
                return '<span style="color:red">'.$this->actionGetDatetimeNowStr().' - '.yii::t('app','Gagal diubah pada').' BIBID = '.$value.'</span><br>'; 
    }

    /**
     * [fungsi untuk memproses data katalog multiple checkbox]
     * @return mixed
     */
    public function actionCheckboxProcess()
    {
        $post = Yii::$app->request->post(); $msg='';
        //echo '<pre>'; print_r($post); echo '</pre>';die;
        if(isset($post['action']) && isset($post['row_id']))
        {
            $actid;
            $rowid = $post['row_id'];
            if(isset($post['actionid']))
                $actid = $post['actionid'];

            switch ($post['action']) {
                case 'OPAC1':
                    foreach ($rowid as $key => $value) {
                        $model = Catalogs::findOne($value);
                        $model->IsOPAC =  1;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->BIBID);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->BIBID);
                        }
                    }
                    break;

                case 'OPAC0':
                    foreach ($rowid as $key => $value) {
                        $model = Catalogs::findOne($value);
                        $model->IsOPAC =  0;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->BIBID);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->BIBID);
                        }
                    }
                    break;
                 case 'KERANJANG0':
                    KeranjangKatalog::deleteAll(['in','Catalog_id',$rowid]);
                    //return $this->redirect(['keranjang']);
                    break;
                case 'DELETE_PERMANENT':
                    foreach ($rowid as $key => $id) {
                         (int)$countColl = QuarantinedCollections::find()
                         ->where(['Catalog_id'=>$id])
                         ->count();

                         if($countColl > 0)
                         {
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->BIBID,yii::t('app','Tidak dapat menghapus'));
                         }
                            QuarantinedCatalogs::deleteAll(['in','ID',$rowid]);

                    }
                    break;
                 case 'KERANJANG1':
                    foreach ($rowid as $key => $value) {
                        $model = Catalogs::findOne($value);
                        $modelkeranjang1 = KeranjangKatalog::findOne($value);
                        if($modelkeranjang1 != null)
                        {
                            //Jika sudah ada id koleksi di tabel keranjang koleksi maka d delete dahulu
                            $modelkeranjang1->delete();
                        }

                        $modelkeranjang2 = new KeranjangKatalog();
                        $modelkeranjang2->Catalog_id = $value;

                        if($modelkeranjang2->save())
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->BIBID,yii::t('app','Katalog berhasil dimasukan ke keranjang'));
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->BIBID,yii::t('app','Katalog gagal dimasukan ke keranjang'));
                        }
                    }
                    break;

                case 'KARTU':
                    CatalogHelpers::cetakKartu($actid,$rowid);
                    break;

                case 'KARANTINA':
                    foreach ($rowid as $key => $id) {
                         (int)$countColl = Collections::find()
                         ->where(['Catalog_id'=>$id])
                         ->count();

                         (int)$countFileCover = Catalogs::find()
                         ->where(['ID'=>$id])
                         ->andWhere(['not', ['CoverURL' => null]])
                         ->count();

                         (int)$countFiles = Catalogfiles::find()
                         ->where(['Catalog_id'=>$id])
                         ->count();

                         $model = Catalogs::findOne($id);
                         if($countColl > 0)
                         {
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->BIBID,yii::t('app',' tersambung dengan data koleksi, harap pindahkan dulu data koleksi'));
                         }
                         else if($countFileCover > 0)
                         {
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->BIBID,yii::t('app',' memiliki cover, harap pindahkan dulu cover'));
                         }
                         else if($countFiles > 0)
                         {
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->BIBID,yii::t('app',' tersambung dengan data konten digital, harap pindahkan dulu data konten digital'));
                         }else{
                            $trans = Yii::$app->db->beginTransaction();
                            try {
                                $modelq1 = QuarantinedCatalogs::findOne($id);
                                if($modelq1 != null)
                                {
                                    //Jika sudah ada id katalog di tabel karantina maka d delete dahulu
                                    $modelq1->delete();
                                }
                                
                                    //Insert ke table karantina dari table katalog
                                    $command = Yii::$app->db->createCommand('INSERT INTO quarantined_catalogs SELECT * FROM catalogs WHERE ID=:ID');
                                    $command->bindParam(':ID', $id);
                                    if($command->execute())
                                    {
                                        //Update data timespan karantina
                                        $modelq2 = QuarantinedCatalogs::findOne($id);
                                        $modelq2->QUARANTINEDBY = (int)Yii::$app->user->identity->ID;
                                        $modelq2->QUARANTINEDDATE = new \yii\db\Expression('NOW()');
                                        $modelq2->QUARANTINEDTERMINAL = \Yii::$app->request->userIP;
                                        if($modelq2->save())
                                        {
                                            //Insert ke table karantina ruas dari table katalog ruas
                                            $command = Yii::$app->db->createCommand('INSERT INTO quarantined_catalog_ruas SELECT * FROM catalog_ruas WHERE CatalogId=:ID ON DUPLICATE KEY UPDATE quarantined_catalog_ruas.ID = quarantined_catalog_ruas.ID;');
                                            $command->bindParam(':ID', $id);
                                            if($command->execute())
                                            {
                                                //Insert ke table karantina subruas dari table katalog subruas
                                                $command = Yii::$app->db->createCommand('INSERT INTO quarantined_catalog_subruas SELECT * FROM catalog_subruas WHERE RuasID IN (SELECT ID FROM catalog_ruas WHERE CatalogId=:ID) ON DUPLICATE KEY UPDATE quarantined_catalog_subruas.ID = quarantined_catalog_subruas.ID;');
                                                $command->bindParam(':ID', $id);
                                                if($command->execute())
                                                {
                                                    //[warning] history save nya belum
                                                    //
                                                    //
                                               
                                                    if($model->delete())
                                                    {
                                                        $trans->commit(); 
                                                        $msg .= $this->actionGetMessageCheckboxProcess(true,$model->BIBID,yii::t('app','Katalog berhasil dikarantina.'));
                                                    }else{
                                                        $msg .= $this->actionGetMessageCheckboxProcess(false,$model->BIBID,yii::t('app','Katalog gagal dihapus.'));
                                                    }
                                                }
                                            }
                                            
                                        }else{
                                            $msg .= $this->actionGetMessageCheckboxProcess(false,yii::t('app','Katalog gagal mengubah timestamp di koleksi'));
                                        }
                                    }else{
                                        $msg .= $this->actionGetMessageCheckboxProcess(false,yii::t('app','Katalog gagal disimpan di Karantina koleksi').var_dump($modelq->getErrors()));
                                    }
                                
                                } catch (Exception $e) {
                                        $trans->rollback();
                                    }
                         }
                    }
                    break;
                
                default:
                    # code...
                    break;
         }   
        }

        if($msg != '')
            $msg = '
                <div class="box-group" id="accordion">
                    <div class="panel panel-success">
                      <div class="box-header">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseMsg">
                            Riwayat proses aksi
                          </a>
                      </div>
                      <div id="collapseMsg" class="panel-collapse collapse in">
                        <div class="standard-error-summary">
                         '.$msg.'    
                        </div>
                      </div>
                    </div>
                </div>
            ';
        return $msg;
    }

    /**
     * [fungsi untuk memproses data konten digital multiple checkbox]
     * @return mixed
     */
    public function actionCheckboxProcessKontenDigital()
    {
        $post = Yii::$app->request->post(); $msg='';
        //echo '<pre>'; print_r($post); echo '</pre>';die;
        if(isset($post['action']) && isset($post['row_id']))
        {
            $actid;
            $rowid = $post['row_id'];
            if(isset($post['actionid']))
                $actid = $post['actionid'];

            switch ($post['action']) {
                case 'OPAC':
                    foreach ($rowid as $key => $value) {
                        $model = Catalogfiles::findOne($value);
                        $model->IsPublish =  $actid;
                        $model->save(false);
                    }
                    /*\Yii::$app->session['SessCatalogTabActive'] = 'kontendigital';
                    $this->actionFlashMessage('info','Data konten digital berhasil diubah');
                    return $this->goBackUrl();*/
                    return true;
                    break;

                case 'DOWNLOAD':
                    $files=array();
                    foreach ($rowid as $key => $value) {
                        $model = Catalogfiles::findOne($value);
                        $modelcat = \common\models\Catalogs::findOne($model->Catalog_id);
                        $worksheetDir=DirectoryHelpers::GetDirWorksheet($modelcat->Worksheet_id);
                        $path = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDir.'/').$model->FileURL;
                        $files[$key]= $path;
                    }
                    $prefixFileDownload='DownloadedFiles_';
                    $dirLevel ='../../';
                    $mainPath = 'uploaded_files/temporary/konten_digital/';
                    $pathZip;
                    $pathReadyDownload;
                    $this->resetTempDirKontenDigital();
                    if(\common\components\DirectoryHelpers::CreateZip($dirLevel,$mainPath,$files,$prefixFileDownload,$pathZip,$pathReadyDownload))
                    {
                        //$this->redirect([$pathReadyDownload]);
                        return $pathReadyDownload;
                    }else{
                        return 'gagal membuat zip';
                    }

                    break;
                case 'REMOVE':
                    foreach ($rowid as $key => $value) {
                        CatalogHelpers::deleteKontenDigital($value);
                    }
                    /*\Yii::$app->session['SessCatalogTabActive'] = 'kontendigital';
                    $this->actionFlashMessage('info','Data konten digital berhasil diubah');
                    return $this->goBackUrl();*/
                    return true;
                    break;
            }
        }

    }

    /**
     * [fungsi untuk menampilkan data katalog]
     * @return mixed
     */
    public function actionIndex()
    {
        $perpage = 20;
        $getPerPage = $_GET['per-page'];
        if(!empty($getPerPage)){
            $perpage = (int)$_GET['per-page'];
        }

        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CatalogSearch;
        $jumlahJudul;$jumlahEksemplar;
        $memberID = Members::find()->where(['MemberNo'=>Yii::$app->user->identity->NoAnggota])->one();
        $dataProvider = $searchModel->advancedSearchByMemberID(0,$rules,$jumlahJudul,$jumlahEksemplar,$memberID->ID);
        $dataProvider->pagination->pageSize=$perpage;
        \Yii::$app->session['SessCatalogTabActive'] = null;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>'katalog',
            'rules'=> $rules,
            'jumlahJudul'=>$jumlahJudul,
            'jumlahEksemplar'=>$jumlahEksemplar
            ]);
    }

    /**
     * [fungsi untuk menampilkan detail view  dari karantina katalog]
     * @param  integer $id [id katalog karantina]
     * @return mixed
     */
    public function actionViewkarantina($id)
    {
        $model = $this->findModelKarantina($id);

        return $this->render('viewkarantina', ['model' => $model]);
        
    }   

    /**
     * [fungsi untuk menampilkan data karantina katalog]
     * @return mixed
     */
    public function actionKarantina()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new QuarantinedCatalogSearch;
        $dataProvider = $searchModel->advancedSearch($rules);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>'karantina',
            'rules'=> $rules
        ]);

        
    }

    /**
     * [fungsi untuk me-restore/memulihkan katalog yang sudah karantina]
     * @param  integer $id [id katalog karantina]
     * @return mixed
     */
    public function actionRestore($id)
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModelKarantina($id);
            //Insert ke table katalog dari table katalog
            $command = Yii::$app->db->createCommand('INSERT INTO catalogs SELECT * FROM quarantined_catalogs WHERE ID=:ID');
            $command->bindParam(':ID', $id);
            if($command->execute())
            {
                //Update data timespan katalog
                $modelq2 = Catalogs::findOne($id);
                $modelq2->QUARANTINEDBY = (int)Yii::$app->user->identity->ID;
                $modelq2->QUARANTINEDDATE = new \yii\db\Expression('NOW()');
                $modelq2->QUARANTINEDTERMINAL = \Yii::$app->request->userIP;
                if($modelq2->save())
                {
                    //Insert ke table katalog ruas dari table katalog ruas
                    $command = Yii::$app->db->createCommand('INSERT INTO catalog_ruas SELECT * FROM quarantined_catalog_ruas WHERE CatalogId=:ID');
                    $command->bindParam(':ID', $id);
                    if($command->execute())
                    {
                        //Insert ke table katalog subruas dari table katalog subruas
                        $command = Yii::$app->db->createCommand('INSERT INTO catalog_subruas SELECT * FROM quarantined_catalog_subruas WHERE RuasID IN (SELECT ID FROM quarantined_catalog_ruas WHERE CatalogId=:ID)');
                        $command->bindParam(':ID', $id);
                        if($command->execute())
                        {
                            //[warning] history save nya belum
                            //
                            //
                       
                            if($model->delete())
                            {
                                $trans->commit(); 
                                $this->actionFlashMessage('info',yii::t('app','Data berhasil direstore'));
                            }else{
                                $this->actionFlashMessage('danger',yii::t('app','Data gagal direstore'));
                            }
                        }
                    }
                    
                }else{
                    $this->actionFlashMessage('danger',yii::t('app','Gagal mengubah timestamp karantina di koleksi'));
                }
            }else{
                $this->actionFlashMessage('danger',yii::t('app','Gagal menambah data di katalog'));
            }
            
        } catch (Exception $e) {
            $trans->rollback();
        }
        return $this->redirect(['karantina']);

        
    }


    /**
     * [fungsi untuk menampilkan data keranjang katalog]
     * @return mixed
     */
    public function actionKeranjang()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CatalogSearch;
        $jumlahJudul;$jumlahEksemplar;
        $dataProvider = $searchModel->advancedSearch(1,$rules,$jumlahJudul,$jumlahEksemplar);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>'keranjang',
            'rules'=>$rules,
            'jumlahJudul'=>$jumlahJudul,
            'jumlahEksemplar'=>$jumlahEksemplar
            ]);
    }

    /**
     * [fungsi untuk mengosongkan keranjang katalog]
     * @return [type] [description]
     */
    public function actionKeranjangReset()
    {
        if(Yii::$app->user->identity->ID)
        {
            KeranjangKatalog::deleteAll('CreateBy = '.(string)Yii::$app->user->identity->ID);
        }
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CatalogSearch;
        $dataProvider = $searchModel->advancedSearch(1,$rules);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>'keranjang',
            'rules'=>$rules
            ]);
    }

    /**
     * [fungsi untuk menampilkan dropdown helper saat aksi cepat]
     * @param  string $id [OPAC1,OPAC0,KARTU,KERANJANG1,KARANTINA]
     * @return mixed
     */
    public function actionGetDropdown($id)
    {
        return $this->renderAjax('_subDropdown', [
            'processid' => $id,
            ]);
    }

    /**
     * [fungsi untuk menampilkan dropdwon helper saat aksi cepat salin katalog]
     * @param  string $id [0,1,4,5]
     * @return mixed
     */
    public function actionGetDropdownSalinkatalog($id)
    {
        $model = new \backend\models\ImportMarcForm();
        return $this->renderAjax('_subDropdownSalinkatalog', [
            'processid' => $id,
            'model'=>$model
            ]);
    }

    /**
     * [fungsi untuk menampilkan dropdwon helper saat aksi cepat konten digital]
     * @param  string $id [DOWNLOAD,OPAC,REMOVE]
     * @return mixed
     */
    public function actionGetDropdownKontenDigital($id)
    {
        return $this->renderAjax('_subDropdownKontenDigital', [
            'processid' => $id,
            ]);
    }

    /**
     * [fungsi untuk menampilkan detil katalog]
     * @param  string $id [id katalog]
     * @return mixed
     */
    public function actionDetail($id)
    {
        $model = Catalogs::findOne($id);
        $taglist = CatalogHelpers2::createTaglistFromCatalog($id);
        $bentukLengkap = CatalogHelpers::convertToCatalogDetails($taglist);
        $data['Detail'] = $bentukLengkap;
        $data['Taglist'] = $taglist;
        $modelfiles =  Catalogfiles::find()->where(['Catalog_id'=>$id])->all();
        //for tab collections
        $rulesColl = Json::decode(Yii::$app->request->get('rules'));
        $searchModelColl = new CollectionSearch;
        $dataProviderColl = $searchModelColl->advancedSearchByCatalogId($id,$rulesColl);
        $dataProviderColl->pagination = false;
        $dataProviderColl->sort = false;

        $jumlahEksemplar = Collections::find()->where(['Catalog_id'=>$id])->count();
        return $this->render('detail', [
            'data' => $data,
            'model' => $model,
            'modelfiles' => $modelfiles,
            'jumlahEksemplar'=>$jumlahEksemplar,
            'rulesColl' => $rulesColl,
            'searchModelColl' => $searchModelColl,
            'dataProviderColl' => $dataProviderColl
            ]);
    }     

    /**
     * [fungsi untuk proses simpan ke catalog ruas]
     * @param  model $model                [model collections]
     * @param  model $modelcat             [model catalog]
     * @param  array $taglist              [array taglist]
     * @param  string $Input_ControlNumber [isi control number]
     * @param  string $Input_BIBID         [isi bib id]
     * @param  string $for                 [cat,coll]
     * @param  integer &$lastCatalogId     [output katalog id terakhir]
     * @param  integer &$seq               [output sequence]
     * @param  string $t005                [isi dari tag 005 =Ymdhis]
     * @param  string $inputmode           [1=advance,0=simple]
     * @return mixed
     */
    public function actionSaveRuas($model,$modelcat,$taglist,$Input_ControlNumber,$Input_BIBID,$for,&$lastCatalogId,&$seq,$inputmode,$updatedatetime)
    {

        $dataOld = [];
        $dataNew = [];
        $indexNew = 0;

        //Jika mode insert, maka LastCatalogId diambil dari primary ID di model catalog
        if($model->isNewRecord)
        {
            $lastCatalogId = $modelcat->getPrimaryKey();
        }
        //Jika mode update, maka LastCatalogId diambil dari ID yang sedang diedit
        else
        {
            $lastCatalogId = $modelcat->ID;
            //ambil data catalog ruas sebelum di delete-insert
            $modelruas = CatalogRuas::find()->where(['CatalogId'=>$lastCatalogId])->orderby('Sequence ASC')->asArray()->all();
            $ruasid = array();
            foreach ($modelruas as $ruas) {
                $ruasid[] = $ruas['ID'];
                $dataOld[] = [
                    'Ruasid'=>$ruas['ID'],
                    'Tag'=>$ruas['Tag'],
                    'Indicator1'=>$ruas['Indicator1'],
                    'Indicator2'=>$ruas['Indicator2'],
                    'Value'=>$ruas['Value']];
            }

            //reset table catalog ruas dan catalog subruas berdasarkan katalog id
            CatalogRuas::deleteAll('CatalogId = '.$lastCatalogId);
            CatalogSubruas::deleteAll(['in','RuasID',$ruasid]);
        }


        //Saving input ruas & sub ruas
        //Compare to session to get tag advance
        $sessionTaglist = \Yii::$app->session['taglist'];

        if(count($sessionTaglist) > 0)
        {

            $taglist1 = $taglist;
            if($inputmode==1)
            {
                //Compare session in advance
                $taglist2 = array_intersect($sessionTaglist,$taglist1);
                foreach( $taglist1['inputvalue'] as $key => $value ){
                    //jika ketemu tag 001,005 atau 035, maka di keluarkan dari array taglist karena tag 001,005 dan 035 sudah di proses di atas.
                    if($key == '001' || $key == '005' || $key == '035')
                    {
                        unset($taglist2['inputvalue'][$key]);
                    }
                    else{
                        //saat mode advance maka seluruh tag input dan tag session dicocokan setiap isinya
                        //jika berbeda maka, value tag session akan diisi oleh value tag input
                        if($taglist2['inputvalue'][$key] != $value)
                        {
                            $taglist2['inputvalue'][$key] = $value;
                        }
                    }
                  
                }
                foreach( $taglist1['indicator'] as $key => $value ){
                    //jika ketemu tag 001,005 atau 035, maka di keluarkan dari array taglist karena tag 001,005 dan 035 sudah di proses di atas.
                    if($key == '001' || $key == '005' || $key == '035')
                    {
                        unset($taglist2['indicator'][$key]);
                    }
                    else{
                        //saat mode advance maka seluruh tag input dan tag session dicocokan setiap isinya
                        //jika berbeda maka, value tag session akan diisi oleh value tag input
                        if($taglist2['indicator'][$key] != $value)
                        {
                            $taglist2['indicator'][$key] = $value;
                        }
                    }
                }

                //variable taglist diisi dengan variable hasil komparasi
                $taglist = $taglist2;
            }else{
                //Compare session in simple
                $taglist2 = array_intersect($sessionTaglist,$taglist1);
                foreach( $taglist1['inputvalue'] as $key => $value ){
                    //jika ketemu tag 001,005 atau 035, maka di keluarkan dari array taglist karena tag 001,005 dan 035 sudah di proses di atas.
                    if($key == '001' || $key == '005' || $key == '035')
                    {
                        unset($taglist2['inputvalue'][$key]);
                    }else{
                       //saat mode simple maka hanya beberapa tag (sesuai dengan desain mapping form simple catalog) input dan tag session dicocokan setiap isinya
                       //jika berbeda maka, value tag session akan diisi oleh value tag input
                       if(
                        $key == '245' ||
                        $key == '246' ||
                        $key == '240' ||
                        $key == '247' ||
                        $key == '255' ||
                        $key == '538' ||
                        $key == '856' ||
                        $key == '740' ||
                        $key == '100' ||
                        $key == '700' ||
                        $key == '710' ||
                        $key == '711' ||
                        $key == '260' ||
                        $key == '264' ||
                        $key == '300' ||
                        $key == '310' ||
                        $key == '321' ||
                        $key == '336' ||
                        $key == '337' ||
                        $key == '338' ||
                        $key == '250' ||
                        $key == '082' ||
                        $key == '084' ||
                        $key == '020' ||
                        $key == '022' ||
                        $key == '650' || 
                        $key == '651' || 
                        $key == '600' || 
                        $key == '500' ||
                        $key == '502' ||
                        $key == '504' ||
                        $key == '505' ||
                        $key == '520' ||
                        $key == '542' ||
                        $key == '008'
                        )
                        {
                            if($taglist2['inputvalue'][$key] != $value)
                            {
                                $taglist2['inputvalue'][$key] = $value;
                            }
                        } 
                    }
                  
                }

                foreach( $taglist1['indicator'] as $key => $value ){
                    //jika ketemu tag 001,005 atau 035, maka di keluarkan dari array taglist karena tag 001,005 dan 035 sudah di proses di atas.
                    if($key == '001' || $key == '005' || $key == '035')
                    {
                        unset($taglist2['indicator'][$key]);
                    }else{
                       //saat mode simple maka hanya beberapa tag (sesuai dengan desain mapping form simple catalog) input dan tag session dicocokan setiap isinya
                       //jika berbeda maka, value tag session akan diisi oleh value tag input
                       if(
                        $key == '245' ||
                        $key == '246' ||
                        $key == '240' ||
                        $key == '247' ||
                        $key == '255' ||
                        $key == '538' ||
                        $key == '856' ||
                        $key == '740' ||
                        $key == '100' ||
                        $key == '700' ||
                        $key == '710' ||
                        $key == '711' ||
                        $key == '260' ||
                        $key == '264' ||
                        $key == '300' ||
                        $key == '310' ||
                        $key == '321' ||
                        $key == '336' ||
                        $key == '337' ||
                        $key == '338' ||
                        $key == '250' ||
                        $key == '082' ||
                        $key == '084' ||
                        $key == '020' ||
                        $key == '022' ||
                        $key == '650' || 
                        $key == '651' || 
                        $key == '600' || 
                        $key == '500' ||
                        $key == '502' ||
                        $key == '504' ||
                        $key == '505' ||
                        $key == '520' ||
                        $key == '542' ||
                        $key == '008'
                        )
                        {
                            if($taglist2['indicator'][$key] != $value)
                            {
                                $taglist2['indicator'][$key] = $value;
                            }
                        } 
                    }
                  
                }
                //variable taglist diisi dengan variable hasil komparasi
                $taglist = $taglist2;
            }
            
        }

        
        //echo '<pre>'; print_r($taglist); die;
        $tagruasid =  $taglist['ruasid'];
        $tagvalues =  $taglist['inputvalue'];
        $tagindicators =  $taglist['indicator'];
        $regexReplaceDollar = '/(\$\w)(.*?)(\$?)/';
        


        //Saving controlnumber
        CatalogHelpers::saveCatalogRuasOnline($lastCatalogId,NULL,NULL,'001',$Input_ControlNumber,$seq++);
        $dataNew[$indexNew++] = [
            'Ruasid'=>(isset($tagruasid['001']))? $tagruasid['001'] : '',
            'Tag'=>'001',
            'Indicator1'=>NULL,
            'Indicator2'=>NULL,
            'Value'=>$Input_ControlNumber];

        //Saving tag 005 (Tanggal Dan Jam Pemakaian Terakhir)
        $t005 = date('Ymdhis');
        CatalogHelpers::saveCatalogRuasOnline($lastCatalogId,NULL,NULL,'005',$t005,$seq++);
        $dataNew[$indexNew++] = [
            'Ruasid'=>(isset($tagruasid['005']))? $tagruasid['005'] : '',
            'Tag'=>'005',
            'Indicator1'=>NULL,
            'Indicator2'=>NULL,
            'Value'=>$t005];

        //Saving bibid
        if(CatalogHelpers::saveCatalogRuasOnline($lastCatalogId,'#','#','035','$a '.$Input_BIBID,$seq++))
        {
            $dataNew[$indexNew++] = [
                'Ruasid'=>(isset($tagruasid['035'][0]))? $tagruasid['035'][0] : '',
                'Tag'=>'035',
                'Indicator1'=>'#',
                'Indicator2'=>'#',
                'Value'=>'$a '.$Input_BIBID];
            //save catalog sub ruas records
            CatalogHelpers::saveCatalogSubruasOnline('a',trim($Input_BIBID),1);
        }

        /*echo '<pre>'; print_r($taglist); echo '</pre>';
        die;*/
        foreach ($tagvalues as $tagcode => $tagvalue) {
            //handle for repeatable tags
            if(is_array($tagvalue))
            {
                foreach ($tagvalue as $key => $value) {
                    $ind1=$tagindicators[$tagcode][$key]['ind1'];
                    $ind2=$tagindicators[$tagcode][$key]['ind2'];
                    if(trim(preg_replace($regexReplaceDollar, '', $value)) != '')
                    { 
                        //save catalog ruas
                        if(CatalogHelpers::saveCatalogRuasOnline($lastCatalogId,$ind1,$ind2,$tagcode,trim($value),$seq++))
                        {

                            $dataNew[$indexNew++] = [
                                'Ruasid'=>(isset($tagruasid[$tagcode][$key]))? $tagruasid[$tagcode][$key] : '',
                                'Tag'=>$tagcode,
                                'Indicator1'=>$ind1,
                                'Indicator2'=>$ind2,
                                'Value'=>trim($value)];
                            $seqruas=1;
                            $valuesub= explode("$",substr($value,1,strlen($value)));
                            for ($i=0; $i < count($valuesub) ; $i++) { 

                                $koderuas=substr($valuesub[$i],0,1);
                                $isiruas=substr($valuesub[$i],1,strlen($valuesub[$i]));
                                //save catalog subruas
                                CatalogHelpers::saveCatalogSubruasOnline($koderuas,trim($isiruas),$seqruas++);
                            }
                        }
                    }
                }

            }else{
                if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                {
                    //save catalog ruas
                    if((int)$tagcode < 10)
                    {
                        $ind1=NULL;
                        $ind2=NULL;
                    }else{
                        $ind1=$tagindicators[$tagcode]['ind1'];
                        $ind2=$tagindicators[$tagcode]['ind2'];
                    } 
                    if(CatalogHelpers::saveCatalogRuasOnline($lastCatalogId,$ind1,$ind2,$tagcode,trim($tagvalue),$seq++))
                    {
                        $dataNew[$indexNew++] = [
                            'Ruasid'=>(isset($tagruasid[$tagcode]))? $tagruasid[$tagcode] : '',
                            'Tag'=>$tagcode,
                            'Indicator1'=>$ind1,
                            'Indicator2'=>$ind2,
                            'Value'=>trim($tagvalue)];
                        $seqruas=1;
                        $valuesub= explode("$",substr($tagvalue,1,strlen($tagvalue)));
                        for ($i=0; $i < count($valuesub) ; $i++) { 

                            $koderuas=substr($valuesub[$i],0,1);
                            $isiruas=substr($valuesub[$i],1,strlen($valuesub[$i]));
                            //save catalog subruas
                            CatalogHelpers::saveCatalogSubruasOnline($koderuas,trim($isiruas),$seqruas++);
                        }
                    }
                }
            }
        }
        if($for == 'cat')
        {
            //jika saat di katalog, maka ketika catalog ruas dan subruas di reset
            //ambil array data no induk koleksi sesuai dengan id katalognya
            $modelcollnoinduk = Collections::find()->where(['Catalog_id'=>$lastCatalogId])->all();
            foreach ($modelcollnoinduk as $data) {
                if($data->NoInduk && CatalogHelpers::saveCatalogRuasOnline($lastCatalogId,'#','#','990','$a '.$data->NoInduk,$seq++))
                {
                    $dataNew[$indexNew++] = [
                        'Tag'=>'990',
                        'Indicator1'=>'#',
                        'Indicator2'=>'#',
                        'Value'=>'$a '.$data->NoInduk];
                    //save catalog subruas
                    CatalogHelpers::saveCatalogSubruasOnline('a',trim($data->NoInduk),1);
                }
            }
        }

        //START HISTORY UPDATE 
        $ruasUpdate=[];
        $ruasAdd=[];
        $ruasDelete=[];
        $indexUpdate=0;
        $indexAdd=0;
        $indexDel=0;
        foreach ($dataNew as $index => $data) {
            if($data['Ruasid']!=NULL)
            {
                //checking data in old ruas
                foreach ($dataOld as $index2 => $data2) {
                    if($data2['Ruasid'] == $data['Ruasid'])
                    {
                        $indicator1Old = $data2['Indicator1'];
                        $indicator2Old = $data2['Indicator2'];
                        $valueOld = $data2['Value'];
                        break;
                    }
                }
                //ketika ruas ada yg diupdate
                if($data['Indicator1'] != $indicator1Old || $data['Indicator2'] != $indicator2Old || $data['Value'] != $valueOld)
                {
                    //if($data['Indicator1'] != $indicator1Old)
                    //{
                        $ruasUpdate[$indexUpdate]['Indicator1'] = ['tag'=>$data['Tag'],'old_value'=>$indicator1Old,'new_value'=>$data['Indicator1']];
                    //}

                    //if($data['Indicator2'] != $indicator2Old)
                    //{
                        $ruasUpdate[$indexUpdate]['Indicator2'] = ['tag'=>$data['Tag'],'old_value'=>$indicator1Old,'new_value'=>$data['Indicator2']];
                    //}

                    //if($data['Value'] != $valueOld)
                    //{
                        $ruasUpdate[$indexUpdate]['Value'] = ['tag'=>$data['Tag'],'old_value'=>$valueOld,'new_value'=>$data['Value']];
                    //}
                    $indexUpdate++;
                }
                
            }else{
                //ketika ruas ada yg ditambahkan
                $ruasAdd[$indexAdd]['Indicator1'] = ['tag'=>$data['Tag'],'old_value'=>'','new_value'=>$data['Indicator1']];
                $ruasAdd[$indexAdd]['Indicator2'] = ['tag'=>$data['Tag'],'old_value'=>'','new_value'=>$data['Indicator2']];
                $ruasAdd[$indexAdd]['Value'] = ['tag'=>$data['Tag'],'old_value'=>'','new_value'=>$data['Value']];
                $indexAdd++;
            }
        }
        //checking ruas yhang di delete
        foreach ($dataOld as $index => $data) {
            //checking data in new ruas
            $isExist=false;
            foreach ($dataNew as $index2 => $data2) {
                if($data2['Ruasid'] == $data['Ruasid'])
                {
                    $isExist=true;
                    break;
                }
            }
            if($isExist==false)
            {
                $ruasDelete[$indexDel]['Indicator1'] = ['tag'=>$data['Tag'],'old_value'=>'','new_value'=>$data['Indicator1']];
                $ruasDelete[$indexDel]['Indicator2'] = ['tag'=>$data['Tag'],'old_value'=>'','new_value'=>$data['Indicator2']];
                $ruasDelete[$indexDel]['Value'] = ['tag'=>$data['Tag'],'old_value'=>'','new_value'=>$data['Value']];
                $indexDel++;
            }
        }
        //echo '<pre>'; print_r($dataNew); echo '</pre>';
        //echo '<pre>'; print_r($dataOld); echo '</pre>';
        //echo '<pre>'; print_r($ruasDelete); echo '</pre>';
        //die;
        $date = $updatedatetime;
        $table='catalogs';
        $userid= (int)Yii::$app->user->identity->ID;
        if($model->isNewRecord)
        {
            $type=0;
        }else{
            $type=1;
        }
        foreach ($ruasAdd as $index => $data) {
            $newIndicator1= $data['Indicator1']['new_value'];
            $newIndicator2= $data['Indicator2']['new_value'];
            $newValue= $data['Value']['new_value'];

            if(isset($newIndicator1))
            {
                $tag = $data['Indicator1']['tag'];
                $descNewInd1 = '  '.$newIndicator1;
            }else{
                $descNewInd1='';
            }

            if(isset($newIndicator2))
            {
                $tag = $data['Indicator2']['tag'];
                $descNewInd2 = '  '.$newIndicator2;
            }else{
                $descNewInd2='';
            }

            if(isset($newValue))
            {
                $tag = $data['Value']['tag'];
                $descNewValue = '  '.$newValue;
            }else{
                $descNewValue='';
            }

            $modelhistory = new \common\models\ModelhistoryCatalogs;
            $modelhistory->date = $date;
            $modelhistory->table = $table;
            $modelhistory->field_name = (string)$tag;
            $modelhistory->field_id = (string)$lastCatalogId;
            $modelhistory->old_value = 'Tambah Tag';
            $modelhistory->new_value = $descNewInd1.$descNewInd2.$descNewValue;
            $modelhistory->type = $type;
            $modelhistory->user_id =(string)$userid;
            if($modelhistory->save())
            {
                //skipp
            }else{
                /*echo 'error on inserting history for add'; 
                echo '<pre>'; print_r($data);
                echo '<pre>'; print_r($modelhistory->getErrors());
                die;*/
            }
        }

        foreach ($ruasUpdate as $index => $data) {
            $oldIndicator1= $data['Indicator1']['old_value'];
            $newIndicator1= $data['Indicator1']['new_value'];
            $oldIndicator2= $data['Indicator2']['old_value'];
            $newIndicator2= $data['Indicator2']['new_value'];
            $oldValue= $data['Value']['old_value'];
            $newValue= $data['Value']['new_value'];

            if(isset($oldIndicator1))
            {
                $tag = $data['Indicator1']['tag'];
                $descOldInd1 = '  '.$oldIndicator1;
            }else{
                $descOldInd1='';
            }

            if(isset($oldIndicator2))
            {
                $tag = $data['Indicator2']['tag'];
                $descOldInd2 = '  '.$oldIndicator2;
            }else{
                $descOldInd2='';
            }

            if(isset($newIndicator1))
            {
                $tag = $data['Indicator1']['tag'];
                $descNewInd1 = '  '.$newIndicator1;
            }else{
                $descNewInd1='';
            }

            if(isset($newIndicator2))
            {
                $tag = $data['Indicator2']['tag'];
                $descNewInd2 = '  '.$newIndicator2;
            }else{
                $descNewInd2='';
            }

            if(isset($oldValue))
            {
                $tag = $data['Value']['tag'];
                $descOldValue = '  '.$oldValue;
            }else{
                $descOldValue='';
            }

            if(isset($newValue))
            {
                $tag = $data['Value']['tag'];
                $descNewValue = '  '.$newValue;
            }else{
                $descNewValue='';
            }
            $modelhistory = new \common\models\ModelhistoryCatalogs;
            $modelhistory->date = $date;
            $modelhistory->table = $table;
            $modelhistory->field_name = (string)$tag;
            $modelhistory->field_id = (string)$lastCatalogId;
            $modelhistory->old_value = 'Edit Tag | '.$descOldInd1.$descOldInd2.$descOldValue;
            $modelhistory->new_value = $descNewInd1.$descNewInd2.$descNewValue;
            $modelhistory->type = $type;
            $modelhistory->user_id = (string)$userid;
            if($modelhistory->save())
            {
                //skipp
            }else{
                /*echo 'error on inserting history for update'; 
                echo '<pre>'; print_r($data);
                echo '<pre>'; print_r($modelhistory->getErrors());
                die;*/
            }
        }

        foreach ($ruasDelete as $index => $data) {
            $tag = $data['Value']['tag'];
            $newIndicator1= $data['Indicator1']['new_value'];
            $newIndicator2= $data['Indicator2']['new_value'];
            $newValue= $data['Value']['new_value'];
            $modelhistory = new \common\models\ModelhistoryCatalogs;
            $modelhistory->date = $date;
            $modelhistory->table = $table;
            $modelhistory->field_name = (string)$tag;
            $modelhistory->field_id = (string)$lastCatalogId;
            $modelhistory->old_value = 'Hapus Tag ';
            $modelhistory->new_value = '  '.$newIndicator1.'  '.$newIndicator2.'  '.$newValue;
            $modelhistory->type = $type;
            $modelhistory->user_id = (string)$userid;
            if($modelhistory->save())
            {
                //skipp
            }else{
                /*echo 'error on inserting history for delete';
                echo '<pre>'; print_r($data);
                echo '<pre>'; print_r($modelhistory->getErrors());
                die;*/
            }
        }

        //END HISTORY UPDATE 



    }

    /**
     * [fungsi untuk menghapus index data session taglist agar saat di intersect tidak terbawa kembali]
     * @return mixed
     */
    public function actionRemoveTaglist()
    {
        if(Yii::$app->request->post())
        {
            
            $post = Yii::$app->request->post();
            $tag = $post['tag'];
            $index = $post['index'];
            $sessionTaglist = \Yii::$app->session['taglist'];
            if(count($sessionTaglist) > 0)
            {
                $taglist = $sessionTaglist;
                if($index!='')
                {
                    if(array_key_exists($tag,$taglist['inputvalue']))
                    {
                        if(array_key_exists($index,$taglist['inputvalue'][$tag]))
                        {
                            unset($taglist['ruasid'][$tag][$index]);
                            unset($taglist['inputvalue'][$tag][$index]);
                            unset($taglist['indicator'][$tag][$index]);

                            $taglist['ruasid'][$tag] = array_values($taglist['ruasid'][$tag]);
                            $taglist['inputvalue'][$tag] = array_values($taglist['inputvalue'][$tag]);
                            $taglist['indicator'][$tag] = array_values($taglist['indicator'][$tag]);
                        }
                    }
                }else{
                    unset($taglist['ruasid'][$tag]);
                    unset($taglist['inputvalue'][$tag]);
                    unset($taglist['indicator'][$tag]);
                }

            }

            Yii::$app->session['taglist'] = $taglist;
        }
    }

    /**
     * [fungsi untuk proses simpan data koleksi]
     * @param  model $model                  [model collections]
     * @param  model $modelcat               [model catalogs]
     * @param  string $lastCatalogId         [id katalog terakhir]
     * @param  string $Input_JumlahEksemplar [jumlah eksemplar]
     * @param  transaction $trans            [data transactions]
     * @param  string $for                   [cat,coll]
     * @param  integer $seq                  [sequence]
     * @param  integer $rda                  [status rda]
     * @return mixed 
     */
    public function actionSaveCollection($model,$modelcat,$lastCatalogId,$Input_JumlahEksemplar,$trans,$for,$seq,$rda,$referUrl,$worksheetOld,$worksheetNew)
    {
        if($for == 'coll')
        {
            //[ONLY FOR AKUISISI COLLECTION]
            //Saving no induk
            if($model->isNewRecord)
            {
                for($i=0; $i < (int)$Input_JumlahEksemplar; $i++)
                {
                    $noindukforruas =$model->NoInduk[$i];
                    if($noindukforruas && CatalogHelpers::saveCatalogRuasOnline($lastCatalogId,'#','#','990','$a '.$noindukforruas,$seq++))
                    {
                            //save catalog sub ruas records
                        CatalogHelpers::saveCatalogSubruasOnline('a',trim($noindukforruas),1);
                    }
                }

            }else{

                    //Insert no induk dari form
                if($model->NoInduk && CatalogHelpers::saveCatalogRuasOnline($lastCatalogId,'#','#','990','$a '.$model->NoInduk,$seq++))
                {
                        //save catalog sub ruas records
                    CatalogHelpers::saveCatalogSubruasOnline('a',trim($model->NoInduk),1);
                }

                    //ketika reset catalog ruas, tag 990 no induk yg selain di edit, msih bisa terbawa dan disave di catalog ruas n subruas
                $modelcollother = Collections::find()->where(['and','Catalog_id = '.$lastCatalogId,['not in','ID',$model->ID]])->all();
                foreach ($modelcollother as $data) {
                    if($data->NoInduk && CatalogHelpers::saveCatalogRuasOnline($lastCatalogId,'#','#','990','$a '.$data->NoInduk,$seq++))
                    {
                            //save catalog sub ruas records
                        CatalogHelpers::saveCatalogSubruasOnline('a',trim($data->NoInduk),1);
                    }
                }
            }

            $successSave = 0;
            for($i=0; $i < (int)$Input_JumlahEksemplar; $i++)
            {
                //Set model collection new dari model input
                if($model->isNewRecord)
                {
                    $modelcoll = new Collections;
                    $modelcoll->ID=NULL;
                    $modelcoll->Branch_id=37; 
                    $modelcoll->IsVerified=0;
                    $formatNomorInduk = Yii::$app->config->get('NomorInduk');
                    $nomorIndukTengah = Yii::$app->config->get('NomorIndukTengah');
                    $templateNomorInduk = Yii::$app->config->get('FormatNomorInduk');
                    $newItemID = str_pad((int)Collections::find()->select('MAX(ID) AS ID')->one()->ID +1 , 11, '0', STR_PAD_LEFT);
                    $latestNomorIndukDigit;
                    if(trim(strtolower($formatNomorInduk))=='manual')
                    {
                        if($model->NoInduk[$i] != '')
                        {
                            $noinduk= $model->NoInduk[$i];
                        }else{
                            $noinduk= $newItemID;
                        }
                    }else{
                        //$latestNomorInduk=CollectionHelpers::getLatestNomorInduk($formatNomorInduk);
                        
                        $latestNomorInduk=(int)Settingparameters::find()->where(['Name'=>'NomorIndukCounter'])->one()->Value;
                        $tahunPengadaan = Yii::$app->formatter->asDate($model->TanggalPengadaan, 'php:Y');


                        $pilihjudul = Yii::$app->request->post('pilihjudul');
                        if($pilihjudul)
                        {
                            $modelcatforworksheet = Catalogs::findOne($pilihjudul);
                        }else{
                            $modelcatforworksheet = $modelcat;
                        }
                        $kodeJenisBahan =  Worksheets::findOne($modelcatforworksheet->Worksheet_id)->CODE;
                        $kodeKategoriKoleksi =  Collectioncategorys::findOne($model->Category_id)->Code;
                        $kodeBentukFisik =  Collectionmedias::findOne($model->Media_id)->Code;
                        $BentukSources =  Collectionsources::findOne($model->Source_id)->Code;
                        $tahunIni = $tahunPengadaan;

                        $sql = "SELECT DATE_FORMAT(collections.TanggalPengadaan, '%Y') AS test, COUNT(collections.TanggalPengadaan)+1 AS test2 FROM collections WHERE DATE_FORMAT(collections.TanggalPengadaan, '%Y') = '".$tahunPengadaan."'";
                        $data = Yii::$app->db->createCommand($sql)->queryOne(); 
                        $latestNomorIndukDigit = str_pad($data['test2'],5,"0", STR_PAD_LEFT);
                        //Jika isi dari nomor induk tengah mengandung saparator slash /
                        $templateNomorInduks = explode('|',$templateNomorInduk);
                        $noinduk = '';
                        foreach ($templateNomorInduks as $key => $templateData) {
                                if($key == 0 || $key == 4 || $key == 8)
                                {

                                    if($templateData != '0')
                                    {
                                        if(strpos($templateData,'{') !== FALSE)
                                        {
                                            $noinduk .= str_replace('}','', str_replace('{','', $templateData));
                                        }else if(strpos($templateData,'^') !== FALSE)
                                        {
                                            $noinduk .= $BentukSources;
                                        }else{
                                            switch ($templateData) {
                                                //Jenis Bahan
                                                case '2':
                                                    $noinduk .= $kodeJenisBahan;
                                                    break;
                                                //Kategori Koleksi
                                                case '3':
                                                    $noinduk .= $kodeKategoriKoleksi;
                                                    break;
                                                //Bentuk Fisik
                                                case '4':
                                                    $noinduk .= $kodeBentukFisik;
                                                    break;
                                            }
                                        }
                                    }
                                }else{
                                    switch ($templateData) {
                                        //Number pad left
                                        case '0':
                                            $noinduk .=$latestNomorIndukDigit;
                                            break;
                                        //year
                                        case '1':
                                            $noinduk .=$tahunIni;
                                            break;
                                        case '2':
                                            $noinduk .='';
                                            break;
                                        case '3':
                                            $noinduk .='/';
                                            break;
                                        case '4':
                                            $noinduk .='-';
                                            break;
                                        case '5':
                                            $noinduk .='.';
                                            break;
                                    }
                                }
                        }
                        /*if(strstr($nomorIndukTengah, '/') != '')
                        {
                            $noinduk = $latestNomorIndukDigit4.$nomorIndukTengah.$tahunIni;
                        }else{
                            $noinduk = $nomorIndukTengah.'-'.$latestNomorInduk+$i.'-'.$tahunPengadaan;
                        }*/
                    }
                    //echo $noinduk; die;
                    
                    //Check di setting parameter untuk format no barcode
                    if (trim(str_replace(' ','',strtolower(Yii::$app->config->get('FormatNomorBarcode'))))=='no.induk')
                    {
                        $NomorID = $noinduk;
                    }else{
                        $NomorID = $newItemID;
                    }

                    //Check di setting parameter untuk format no rfid
                    if (trim(str_replace(' ','',strtolower(Yii::$app->config->get('FormatNomorRFID'))))=='no.induk')
                    {
                        $NomorID2 = $noinduk;
                    }else{
                        $NomorID2 = $newItemID;
                    }

                    if(trim(strtolower($formatNomorInduk))=='manual')
                    {
                        if($model->NomorBarcode[$i] != '')
                        {
                            $nobarcode= $model->NomorBarcode[$i];
                        }else{
                            $nobarcode= $NomorID;
                        }

                        if($model->RFID[$i] != '')
                        {
                            $norfid= $model->RFID[$i];
                        }else{
                            $norfid= $NomorID2;
                        }
                    }else{
                        $nobarcode= $NomorID;
                        $norfid= $NomorID2;
                    }
                    
                    $modelcoll->NomorBarcode= $nobarcode;
                    $modelcoll->RFID= $norfid;
                    $modelcoll->NoInduk= $noinduk;
                    $modelcoll->JumlahEksemplar = $model->JumlahEksemplar;
                }else{
                    $modelcoll = Collections::findOne($model->ID);
                    $modelcoll->NoInduk= $model->NoInduk;
                    $modelcoll->NomorBarcode= $model->NomorBarcode;
                    $modelcoll->RFID= $model->RFID;
                    $modelcoll->Branch_id=$model->Branch_id; 
                    $modelcoll->IsVerified=(int)$model->IsVerified;
                    $modelcoll->JumlahEksemplar = 1;
                }
                $modelcoll->Catalog_id=$lastCatalogId;
                $modelcoll->EDISISERIAL = $model->EDISISERIAL;
                if($model->TANGGAL_TERBIT_EDISI_SERIAL)
                {
                    $modelcoll->TANGGAL_TERBIT_EDISI_SERIAL = Yii::$app->formatter->asDate($model->TANGGAL_TERBIT_EDISI_SERIAL, 'php:Y-m-d');
                }
                $modelcoll->BAHAN_SERTAAN = $model->BAHAN_SERTAAN;
                $modelcoll->KETERANGAN_LAIN = $model->KETERANGAN_LAIN;
                $modelcoll->TanggalPengadaan = Yii::$app->formatter->asDate($model->TanggalPengadaan, 'php:Y-m-d');
                $modelcoll->Source_id = $model->Source_id;
                //$modelcoll->Keterangan_Sumber = $model->Keterangan_Sumber;
                $modelcoll->Media_id = $model->Media_id;
                $modelcoll->Category_id = $model->Category_id;
                $modelcoll->Rule_id = $model->Rule_id;
                $modelcoll->Location_Library_id = $model->Location_Library_id;
                $modelcoll->Location_id = $model->Location_id;
                $modelcoll->Partner_id = $model->Partner_id;
                $modelcoll->ISREFERENSI = $model->ISREFERENSI;
                $modelcoll->Status_id = $model->Status_id;
                $modelcoll->Currency = $model->Currency;
                $modelcoll->Price = $model->Price;
                $modelcoll->PriceType = $model->PriceType;
                $modelcoll->CallNumber = $model->CallNumber;
                $modelcoll->ISOPAC = $modelcat->IsOPAC;

                //save collection records
                if($modelcoll->save())
                {
                    //save counter in settingparam
                    
                    if($latestNomorIndukDigit!=NULL)
                    {
                        $modelsettingparam = Settingparameters::find()->where(['Name'=>'NomorIndukCounter'])->one();
                        $modelsettingparam->Value=$latestNomorIndukDigit;
                        if($modelsettingparam->save(false))
                        {
                            $successSave++;
                        }
                    }else{
                         $successSave++;
                    }
                }
                /*else{
                    echo var_dump($modelcoll->getErrors()); die;
                }*/

            }
            //echo $successSave; die;
            //jika jumlah Katalog tersimpan sama dengan isian jumlah eksemplar, maka sukses
            if($successSave == (int)$Input_JumlahEksemplar)
            {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                $trans->commit();
                if($model->isNewRecord)
                {
                    return $this->redirect(['/akuisisi/koleksi/index']);
                }else{
                    if($referUrl){
                        return $this->redirect(CatalogHelpers::encrypt_decrypt('decrypt',$referUrl));
                    }else{
                        return $this->redirect(['/akuisisi/koleksi/index']);
                    }
                }
            }
        }elseif($for=='cat'){
            //Set active tab
            \Yii::$app->session['SessCatalogTabActive'] = 'katalog';

            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 500,
                'icon' => 'fa fa-info-circle',
                'message' => Yii::t('app','Success Save'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            $trans->commit();

            //START -- Pindahkan data2 konten digital
            if($worksheetOld != $worksheetNew)
            {
                $dataKontenDigital = Catalogfiles::find()->where(['Catalog_id'=>$lastCatalogId]);
                if($dataKontenDigital->count() > 0)
                {
                   foreach ($dataKontenDigital->all() as $item) {
                        $worksheetDirOld=DirectoryHelpers::GetDirWorksheet($worksheetOld);
                        $worksheetDirNew=DirectoryHelpers::GetDirWorksheet($worksheetNew);
                        $fileUrl = $item->FileURL;
                        $filepathOld = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDirOld.DIRECTORY_SEPARATOR.$fileUrl);
                        $dirpath= Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDirNew);
                        $filepathNew = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDirNew.DIRECTORY_SEPARATOR.$fileUrl);

                        //if file exist in new location then rename it
                        $newFileName = $fileUrl;
                        $transcatalogfiles = Yii::$app->db->beginTransaction();
                        try {
                            if (file_exists($filepathNew)) {
                                $newFileName = $this->getNewFileNameKontenDigital($dirpath ,$filepathNew,$fileUrl);
                                $modelcatalogfiles = Catalogfiles::findOne($item->ID);
                                $modelcatalogfiles->FileURL = $newFileName;
                                $modelcatalogfiles->save();
                            }
                            $filepathNew = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDirNew.DIRECTORY_SEPARATOR.$newFileName);

                            $nameFileFolderOld='';
                            if($item->FileFlash != ''){
                                $nameFileFolderOld= str_replace(".rar","",str_replace(".zip","",$fileUrl));
                                $nameFileFolderNew= str_replace(".rar","",str_replace(".zip","",$newFileName));
                                $fileFolderOld = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDirOld.DIRECTORY_SEPARATOR.$nameFileFolderOld);
                                $fileFolderNew = Yii::getAlias('@uploaded_files/dokumen_isi/'.$worksheetDirNew.DIRECTORY_SEPARATOR.$nameFileFolderNew);
                            }

                            //moving file
                            if(file_exists($filepathOld))
                            {
                                rename($filepathOld,$filepathNew);
                            }
                            
                            //moving folder
                            if($nameFileFolderOld!='' && is_dir($fileFolderOld))
                            {
                                rename($fileFolderOld,$fileFolderNew);
                            }

                            $transcatalogfiles->commit();

                        } catch (Exception $e) {
                            $transcatalogfiles->rollback();
                        }
                    } 
                }
            }
            //END -- Pindahkan data2 konten digital
            
            return $this->redirect(['update?for=cat&rda='.$rda.'&id='.$lastCatalogId.'&edit=t&refer='.$referUrl]);
            
            
        }
    }
    public function wait(){
        
        $command = Yii::$app->db->createCommand("SELECT Value from settingparameters WHERE name ='IsEntryCatalog';");
        $isEntry = $command->queryOne();
        //check apakah catalog sedang di edit
        //jika sedang di edit maka kita tunggu dlu
        //jika tidak di edit kita kirim value ke setting parameter biar user lain menunggu

        $this->counterLoop++;

        if ($isEntry) {
            if ($isEntry['Value']==='1') {

                //check jika sudah 5 kali looping maka lock di lepas (mencegah ada error infinite loop)
                if ($this->counterLoop==5) {
                    $command = Yii::$app->db->createCommand("UPDATE settingparameters SET Value=0 WHERE Name ='IsEntryCatalog';");
                    $command->execute();
                } else {
                    //sleep 1 - 3 sec
                    usleep(rand(1000000,3000000));
                    $this->wait();
                }
            } else
            if ($isEntry['Value']==='0') {          
                //proses lock
                //biar tidak bentrok dengan proses lain
                $command = Yii::$app->db->createCommand("UPDATE settingparameters SET Value=1 WHERE Name ='IsEntryCatalog';");
                $command->execute();
            }
            else {
                //setting parameter isinya kosong kita insert dulu
                $command = Yii::$app->db->createCommand("INSERT INTO settingparameters(Name,Value) VALUES ('IsEntryCatalog',0);");
                $command->execute();

            }
        } else {
                //setting parameter belum ada value kita inser dulu
                $command = Yii::$app->db->createCommand("INSERT INTO settingparameters(Name,Value) VALUES ('IsEntryCatalog',0);");
                $command->execute();

            }
        }

        

    /**
     * [fungsi simpan data]
     * @param  array $post     [post request]
     * @param  model $modelcat [model catalogs]
     * @param  model $model    [model collections]
     * @param  model $modelbib [model collectionbiblio]
     * @param  string $for     [cat,coll]
     * @return mixed
     */
    public function actionSave($post,$modelcat,$model,$modelbib,$for)
    {
        $this->wait();
        // echo '<pre>'; print_r($post); echo '</pre>';die;
        //Load model dari var post
        
        /*$taglistnya = \Yii::$app->session['taglist'];
        echo '<pre>'; print_r($taglistnya['inputvalue']); echo '</pre>';
        die;*/

        $model->load($post);
        $modelbib->load($post);
        $modelcat->load($post);
        $catalogid=Yii::$app->request->post('catalogid');
        $inputmode = Yii::$app->request->post('modeform');
        $pilihjudul = Yii::$app->request->post('pilihjudul');
        $rda = Yii::$app->request->post('rdastatus');
        $duplicateCatalog = Yii::$app->request->get('dc');
        $referUrl = Yii::$app->request->post('referUrl');

        $Input_Worksheet_id = $modelcat->Worksheet_id;

        //Jika proses duplikasi katalog, maka model di reset untuk proses create baru
        if($duplicateCatalog)
        {
            $model = new Collections;
            $modelcat = new Catalogs;
            $modelcat->Worksheet_id = $Input_Worksheet_id;
        }

        if($pilihjudul && $for == 'coll')
        {
            $lastCatalogId=$pilihjudul;
            if($model->isNewRecord)
            {
                $Input_JumlahEksemplar = $model->JumlahEksemplar;
            }else{
                $Input_JumlahEksemplar =1;
            }
        }else{

            

            //Set input value untuk catalog records
            $taglist = array();
            if($inputmode == "1")
            {
                //Advance input for catalogs
                $taglist = $this->actionCreateTaglistAdvance($model,$modelbib,$post,false);
            }else{
                //Simple input for catalogs
                $taglist = $this->actionCreateTaglistSimple($model,$modelbib,$post,$for,$Input_Worksheet_id,$rda);
            }

            // echo '<pre>'; print_r($taglist); echo '</pre>';die;
            
            $catalogFieldValues = array();
            $catalogFieldValues = CatalogHelpers::convertToCatalogFields($taglist);
            //Set value for insert catalogs
            if($for=='cat')
            {
                $Input_JumlahEksemplar=0;
                //Set var Jumlah Eksemplar, jika sedang mode insert maka isinya dari apa yang diinput di form
                
                if($modelcat->isNewRecord)
                {
                    //$Input_JumlahEksemplar = $model->JumlahEksemplar;
                    //Reset variable, dan set controlnumber,bibid,worksheet_id
                    //echo 'create'; die;
                    $modelcat->ControlNumber=CatalogHelpers::getControlNumber(1); 
                    $modelcat->BIBID=CatalogHelpers::getBibId(1);
                }else{
                    $data =  Catalogs::findOne($catalogid);
                    $dataOld = ['Worksheet_id'=>$data->Worksheet_id];
                } 
            }else{
                //Set var Jumlah Eksemplar, jika sedang mode insert maka isinya dari apa yang diinput di form
                if($model->isNewRecord)
                {
                    $Input_JumlahEksemplar = $model->JumlahEksemplar;
                    //Reset variable, dan set controlnumber,bibid,worksheet_id
                    $modelcat->ControlNumber=CatalogHelpers::getControlNumber(1); 
                    $modelcat->BIBID=CatalogHelpers::getBibId(1);
                }else{
                    $Input_JumlahEksemplar =1;
                } 
            }
            $modelcat->IsRDA=$rda; 
            
            if(array_key_exists('Title', $catalogFieldValues))
                $modelcat->Title=trim($catalogFieldValues['Title']);
            if(array_key_exists('Author', $catalogFieldValues))
                $modelcat->Author=trim($catalogFieldValues['Author']);
            if(array_key_exists('Edition', $catalogFieldValues))
                $modelcat->Edition=trim($catalogFieldValues['Edition']);
            if(array_key_exists('Publisher', $catalogFieldValues))
                $modelcat->Publisher=trim($catalogFieldValues['Publisher']);
            if(array_key_exists('PublishLocation', $catalogFieldValues))
                $modelcat->PublishLocation=trim($catalogFieldValues['PublishLocation']);
            if(array_key_exists('PublishYear', $catalogFieldValues))
                $modelcat->PublishYear=trim($catalogFieldValues['PublishYear']);
            if(array_key_exists('Publikasi', $catalogFieldValues))
                $modelcat->Publikasi=trim($catalogFieldValues['Publikasi']);
            if(array_key_exists('Subject', $catalogFieldValues))
                $modelcat->Subject=trim($catalogFieldValues['Subject']);
            if(array_key_exists('PhysicalDescription', $catalogFieldValues))
                $modelcat->PhysicalDescription=trim($catalogFieldValues['PhysicalDescription']);
            if(array_key_exists('ISBN', $catalogFieldValues))
                $modelcat->ISBN=trim($catalogFieldValues['ISBN']);
            if(array_key_exists('CallNumber', $catalogFieldValues))
                $modelcat->CallNumber=trim($catalogFieldValues['CallNumber']);
            if(array_key_exists('Note', $catalogFieldValues))
                $modelcat->Note=trim($catalogFieldValues['Note']);
            if(array_key_exists('DeweyNo', $catalogFieldValues))
                $modelcat->DeweyNo=trim($catalogFieldValues['DeweyNo']);
            if(array_key_exists('Languages', $catalogFieldValues))
                $modelcat->Languages=trim($catalogFieldValues['Languages']); 
        }

        $trans = Yii::$app->db->beginTransaction();
        try {
            //save catalogs records
            //echo '<pre>'; print_r($modelcat); echo '</pre>';die;
            //Special treatment for edisiserial
            $edisiserialpost= $post['TagsValue']['863']['0'];
            if(!empty($edisiserialpost) && $for=='coll')
            {
                $t863=$post['TagsValue']['863']['0'];
                $t863mix =  explode("$",$t863);
                for ($i=0; $i < count($t863mix) ; $i++) { 
                    $subruascode=substr($t863mix[$i],0,1);
                    $subruasvalue=substr($t863mix[$i],1,strlen($t863mix[$i]));
                    if(trim($subruasvalue) != '')
                    {
                        switch ($subruascode) {
                             case 'a':
                                $model->EDISISERIAL = $subruasvalue;
                                break;
                        }
                    }
                }
                
            }
            if($pilihjudul && $for=='coll')
            {
                
                $worksheetOld = $modelcat->Worksheet_id;
                $worksheetNew = $modelcat->Worksheet_id;
                $catruas = CatalogRuas::find()
                                ->addSelect(['MAX(Sequence) + 1 AS Sequence'])
                                ->where(['CatalogId'=>$lastCatalogId])
                                ->one();
                $seq= (int)$catruas->Sequence;
                $this->actionSaveCollection($model,$modelcat,$lastCatalogId,$Input_JumlahEksemplar,$trans,$for,$seq,$rda,$referUrl,$worksheetOld,$worksheetNew);
            }else{
                /*\common\components\OpacHelpers::print__r(Yii::$app->user->identity->NoAnggota);*/
                $membrs = Members::find()->where(['MemberNo' => Yii::$app->user->identity->NoAnggota])->one();
               // \common\components\OpacHelpers::print__r($membrs);
                $modelcat->CreateBy = 1;
                $modelcat->Member_id = $membrs->ID;
                if($modelcat->save())
                {
                    //check isEntry
                    /*$command = Yii::$app->db->createCommand("SELECT Value from settingparameters WHERE name ='IsEntryCatalog'");
                    $isEntry = $command->queryAll();*/

                    //update isEntry biar bisa di gunakan setelah berhasil save
                    $command = Yii::$app->db->createCommand("UPDATE settingparameters SET Value=0 WHERE Name ='IsEntryCatalog'");
                    $isEntry = $command->execute();

                    
                    $lastCatalogId=$modelcat->getPrimaryKey();
                    $Input_ControlNumber=$modelcat->ControlNumber;
                    $Input_BIBID=$modelcat->BIBID;
                    $seq = 1;
                    $worksheetOld = $modelcat->Worksheet_id;
                    $worksheetNew = $modelcat->Worksheet_id;
                    if($for == 'cat' || ($for== 'coll' && $model->isNewRecord))
                    {
                        //JIKA SAAT INSERT BARU
                        $updatedatetime = date('Y-m-d H:i:s', time());
                        if($catalogid==NULL)
                        {
                            $modelhistory = new \common\models\ModelhistoryCatalogs;
                            $modelhistory->date = $updatedatetime;
                            $modelhistory->table = 'catalogs';
                            $modelhistory->field_name = 'Worksheet_id';
                            $modelhistory->field_id = (string)$lastCatalogId;
                            $modelhistory->old_value = NULL;
                            $modelhistory->new_value = $modelcat->Worksheet_id;
                            $modelhistory->type = 0;
                            $modelhistory->user_id =(string)Yii::$app->user->identity->ID;;
                            if($modelhistory->save())
                            {
                                //skipp
                            }else{
                                /*echo 'error on inserting history for add catalogs'; 
                                echo '<pre>'; print_r($modelhistory->getErrors());
                                die;*/
                            }
                        //JIKA SAAT EDIT
                        }else{
                            //START HISTORY UPDATE FOR CATALOGS
                            foreach ($dataOld as $key => $value) {
                                switch ($key) {
                                    case 'Worksheet_id':
                                        if($value != $modelcat->Worksheet_id)
                                        {
                                            //Set variabel worksheet old for konten digital
                                            $worksheetOld = $value;
                                            //Saving history
                                            $modelhistory = new \common\models\ModelhistoryCatalogs;
                                            $modelhistory->date = $updatedatetime;
                                            $modelhistory->table = 'catalogs';
                                            $modelhistory->field_name = (string)$key;
                                            $modelhistory->field_id = (string)$lastCatalogId;
                                            $modelhistory->old_value = $value;
                                            $modelhistory->new_value = $modelcat->Worksheet_id;
                                            $modelhistory->type = 1;
                                            $modelhistory->user_id =(string)Yii::$app->user->identity->ID;;
                                            if($modelhistory->save())
                                            {
                                                //skipp
                                            }else{
                                                /*echo 'error on inserting history for update catalogs'; 
                                                echo '<pre>'; print_r($modelhistory->getErrors());
                                                die;*/
                                            }
                                        }
                                        break;
                                }
                            }
                        }
                        $this->actionSaveRuas($model,$modelcat,$taglist,$Input_ControlNumber,$Input_BIBID,$for,$lastCatalogId,$seq,$inputmode,$updatedatetime);
                    }
                    $this->actionSaveCollection($model,$modelcat,$lastCatalogId,$Input_JumlahEksemplar,$trans,$for,$seq,$rda,$referUrl,$worksheetOld,$worksheetNew);
                    if (Yii::$app->config->get('OpacIndexer')==1){
                        ElasticHelper::CreateIndexByID($modelcat->ID);
                    }
                    //
                    //Yii::$app->user->login($modelMemberOnline);
                }
                /*else{
                    \common\components\OpacHelpers::print__r($modelcat);
                    var_dump($modelcat->getErrors()); die;
                }*/
            }
        } catch (Exception $e) {
            $trans->rollback();
            return $this->render('create', [
                'model' => $model,
                'modelcat' => $modelcat,
                'modelbib' => $modelbib
                ]);
        }
        

    }

    /**
     * [fungsi generate rule validasi form entri sederhana]
     * @param  integer $rda [status rda]
     * @return mixed
     */
    public function actionValidateRequiredSimpleForm($rda) {

        $CatalogInputTags=array();
        $required=array();
            $CatalogInputTags['245'] = "Judul";
            $CatalogInputTags['246'] = "Variasi Bentuk Judul";
            $CatalogInputTags['740'] = "Judul Asli";
            $CatalogInputTags['100'] = "Tajuk Pengarang Utama";
            $CatalogInputTags['700'] = "Tajuk Pengarang Tambahan";
            $CatalogInputTags['260'] = "Publikasi";
            $CatalogInputTags['336'] = "Jenis Isi";
            $CatalogInputTags['337'] = "Jenis Media";
            $CatalogInputTags['338'] = "Jenis Carrier";
            $CatalogInputTags['250'] = "Edisi";
            $CatalogInputTags['082'] = "No. Klas DDC";
            $CatalogInputTags['084'] = "No. Panggil";
            $CatalogInputTags['300'] = "Deskripsi Fisik";
            $CatalogInputTags['020'] = "ISBN";
            $CatalogInputTags['022'] = "ISSN";
            $CatalogInputTags['650'] = "Subjek";
            $CatalogInputTags['520'] = "Catatan";
        
        foreach ($CatalogInputTags as $key => $value) {
            //Jika form bukan rda, maka tag 336,337 dan 338 d skip 
            if($rda== '0' && ($key=='336' ||  $key=='337' || $key=='338'))
            {
                continue;
            }

            //Jika form rda, maka data field yang dicari adalah tag 264
            if($rda == '1' && $key=='260')
            {
                $fields =  Fields::find()->where(['Tag'=>'264'])->one();
            }else{
                $fields =  Fields::find()->where(['Tag'=>$key])->one();
            }
            

            $hasDelimiterError = false;
       
            //jika field mandatory
            if($fields)
            {

                if ($fields->Mandatory) {
                    if($key=='260' || $key =='300')
                    {
                        $required[$key]=array('status'=>'required','message'=>$value.yii::t('app',' tidak boleh kosong, silahkan isi salah satu.'));
                    }else{
                        $required[$key]=array('status'=>'required','message'=>$value.yii::t('app',' tidak boleh kosong.'));
                    }
                }else{
                    $required[$key]=array('status'=>'','message'=>'');
                } 
                
            }
        }

        return $required;
    }

    /**
     * [fungsi create catalogs]
     * @param  string $for [cat,coll]
     * @param  integer $rda [status rda]
     * @return mixed
     */
    public function actionCreate($for,$rda)
    {
        /*$memberonlineID=Yii::$app->user->identity->ID;
        $modelMemberOnline = UserMemberOnlines::findOne($memberonlineID);
        //bypass user
        $user = User::findOne(1);
        Yii::$app->user->setIdentity($user);*/

        $modelcat = new CatalogsOnline;
        $model = new Collections;
        $modelbib = new CollectionBiblio;

        if (Yii::$app->request->post()) {
            $this->actionSave(Yii::$app->request->post(),$modelcat,$model,$modelbib,$for);
            return $this->redirect('index');
        }else{
            //reset session taglist at begining
            \Yii::$app->session['taglist']=[];
            //prevent inject url param 
            $for_req = Yii::$app->request->get('for');
            $rda_req = Yii::$app->request->get('rda');
            //jika request url param 'for' bukan cat/coll maka redirect ke index
            if($for_req!='cat' && $for_req!='coll')
            {
                return $this->goHome();
            }
            //jika request url param 'rda' bukan 1/0 maka redirect ke index
            else if($rda_req!='1' && $rda_req!='0')
            {
                return $this->goHome();
            }else{
                //$isUserHasAccess = CatalogHelpers::isUserHasAccess(Yii::$app->user->identity->id);
                //bypass user access
                $isUserHasAccess = 1;
                //\common\components\OpacHelpers::print__r($isUserHasAccess);
                //jika user tidak diberikan akses untuk entri koleksi
                if($for_req=='coll' && $isUserHasAccess == false)
                {
                    $this->getView()->registerJs('
                        swal({
                            title: " ",
                            text: "User '. Yii::$app->user->identity->username.' '.yii::t('app','tidak mempunyai akses melakukan entri koleksi!').'",
                            type: "warning",
                            timer: 5000,
                            cancelButtonText: "'.yii::t('app','Tutup').'",
                            closeOnConfirm: true,
                        },
                        function(){
                            window.location.href = "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi"]).'";
                        });
                    ');
                }
            }

            $taglist = array();
            $taglistDb = array();
            
            //ambil status bentuk form entri dari tiap user
            $modeluser = Users::findOne((int)Yii::$app->user->identity->ID);
            $isAdvanceEntryCollection = (int)$modeluser->IsAdvanceEntryCollection;
            $isAdvanceEntryCatalog = (int)$modeluser->IsAdvanceEntryCatalog;

            //mapping default array
            $rulesform['008_Bahasa'] = (int)Worksheetfields::getStatusTag008(5,1,29);
            $rulesform['008_KaryaTulis'] = (int)Worksheetfields::getStatusTag008(17,1,29);
            $rulesform['008_KelompokSasaran'] = (int)Worksheetfields::getStatusTag008(2,1,29);
            $listvar['publication']=array();
            $listvar['lokasidaring']=array();
            $listvar['lokasidaringtype']=array();
            $listvar['judulsebelum']=array();
            $listvar['judulsebelumtype']=array();
            $listvar['frekuensisebelum']=array();
            $listvar['frekuensisebelumtype']=array();
            $listvar['author']=array();
            $listvar['authortype']= array();
            $listvar['isbn']= array();
            $listvar['subject']= array();
            $listvar['subjecttag']=array();
            $listvar['subjectind']= array();
            $listvar['callnumber']= array();
            $listvar['note']= array();
            $listvar['notetag']= array();
            $listvar['titlevarian']= array();
            $listvar['titleoriginal']= array();
            $listvar['input_required']=array();
            $listvar['input_required'] = $this->actionValidateRequiredSimpleForm($rda);

            if($for == 'cat')
            {
                 $a=1;
                 if ( 1==1) {
                    $isAdvanceEntry=$isAdvanceEntryCatalog;

                    //Ducplicating catalog 
                    $dc = Yii::$app->request->get('dc');
                    if($dc)
                    {
                        $model=Catalogs::findOne($dc);
                        if($model != NULL)
                        {
                            $modelcat=$model;
                            
                            //Create modolbib for prepare simple entry
                            $this->actionCreateModelBibFromCatalog($dc,$modelbib);
                            if(!$modelbib->AuthorType)
                                $modelbib->AuthorType=0;
                            if(!$modelbib->AuthorAddedType)
                                $modelbib->AuthorAddedType[0]=0;
                            if(!$modelbib->LokasiDaringType)
                                $modelbib->LokasiDaringType=0;
                            if(!$modelbib->LokasiDaringAddedType)
                                $modelbib->LokasiDaringAddedType[0]=0;
                            if(!$modelbib->JudulSebelumType)
                                $modelbib->JudulSebelumType=0;
                            if(!$modelbib->JudulSebelumAddedType)
                                $modelbib->JudulSebelumAddedType[0]=0;
                            if(!$modelbib->FrekuensiSebelumType)
                                $modelbib->FrekuensiSebelumType=0;
                            if(!$modelbib->FrekuensiSebelumAddedType)
                                $modelbib->FrekuensiSebelumAddedType[0]=0;
                            if(!$modelbib->SubjectTag)
                                $modelbib->SubjectTag[0]='650';
                            if(!$modelbib->SubjectInd)
                                $modelbib->SubjectInd[0]='#';
                            if(!$modelbib->NoteTag)
                                $modelbib->NoteTag[0]='520';

                            $dataPublication = [];
                            foreach ($modelbib->PublishLocation as $key => $value) {
                               $dataPublication[$key] = ['publishlocation'=>$value] + ['publisher'=>$modelbib->Publisher[$key]] + ['publishyear'=>$modelbib->PublishYear[$key]];
                            }
                            $listvar['publication']=$dataPublication;
                            $listvar['author']=$modelbib->AuthorAdded;
                            $listvar['authortype']= $modelbib->AuthorAddedType;
                            $listvar['lokasidaring']=$modelbib->LokasiDaringAdded;
                            $listvar['lokasidaringtype']= $modelbib->LokasiDaringAddedType;
                            $listvar['judulsebelum']=$modelbib->JudulSebelumAdded;
                            $listvar['judulsebelumtype']= $modelbib->JudulSebelumAddedType;
                            $listvar['frekuensisebelum']=$modelbib->FrekuensiSebelumAdded;
                            $listvar['frekuensisebelumtype']= $modelbib->FrekuensiSebelumAddedType;
                            $listvar['isbn']= $modelbib->ISBN;
                            $listvar['issn']= $modelbib->ISSN;
                            $listvar['subject']= $modelbib->Subject;
                            $listvar['subjecttag']=$modelbib->SubjectTag;
                            $listvar['subjectind']= $modelbib->SubjectInd;
                            $listvar['callnumber']= $modelbib->CallNumber;
                            $listvar['note']= $modelbib->Note;
                            $listvar['notetag']= $modelbib->NoteTag;
                            $listvar['titlevarian']= $modelbib->TitleVarian;
                            $listvar['titleoriginal']= $modelbib->TitleOriginal;


                            $worksheetid = (int)$model->Worksheet_id;

                            $rulesform['008_Bahasa'] = (int)Worksheetfields::getStatusTag008(5,$worksheetid,29);
                            $rulesform['008_KaryaTulis'] = (int)Worksheetfields::getStatusTag008(17,$worksheetid,29);
                            $rulesform['008_KelompokSasaran'] = (int)Worksheetfields::getStatusTag008(2,$worksheetid,29);

                            //Create taglist for prepare advance entry
                            $this->actionCreateTaglistFromCatalog($dc,$worksheetid,$for,$taglist,$rda);
                            \Yii::$app->session['taglist'] = $taglist;
                        }
                    }else{
                        //set default om entry first
                        $modelbib->AuthorType=0;
                        $modelbib->LokasiDaringType=0;
                        $modelbib->JudulSebelumType=0;
                        $modelbib->FrekuensiSebelumType=0;
                        $modelbib->NoteTag[0]='520';
                        $modelbib->SubjectInd='#';
                        $modelbib->AuthorAddedType[0]=0;
                        $modelbib->LokasiDaringAddedType[0]=0;
                        $modelbib->JudulSebelumAddedType[0]=0;
                        $modelbib->FrekuensiSebelumAddedType[0]=0;

                        //if create clean for prepare advance entry
                        $taglist = $this->actionCreateTaglistClean($taglist,1,$for,$rda);
                        \Yii::$app->session['taglist'] = $taglist;
                    }
                }else{
                 throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }

            }
            else
            {
                 if (\Yii::$app->user->can('akuisisi') || \Yii::$app->user->can('superadmin')) {
                     //on entri coll
                    $isAdvanceEntry=$isAdvanceEntryCollection;
                    $model->Price=0;
                    $model->Currency='IDR';
                    $model->TanggalPengadaan=date('d-m-Y');
                 }else{
                     throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                 }
               
            }
            //when no postback given
            return $this->render('create', [
                'model' => $model,
                'modelcat' => $modelcat,
                'modelbib' => $modelbib,
                'taglist'=>$taglist,
                'listvar'=>$listvar,
                'mode'=>'create',
                'for'=>$for,
                'rda'=>$rda,
                'rulesform'=>$rulesform,
                'isAdvanceEntry'=> $isAdvanceEntry
                ]);
        } 
    }

    /**
     * [fungsi update data]
     * @param  string $for [cat,coll]
     * @param  integer $id  [id katalog]
     * @param  integer $rda [status rda]
     * @return mixed
     */
    public function actionUpdate($for,$id,$rda,$refer=NULL)
    {
        if (Yii::$app->user->isGuest) {
            $isFromMember = false;
        } else {
            $isFromMember = true;
            $member = Members::find()->where(['MemberNo'=>Yii::$app->user->identity->NoAnggota])->one();
            $countCatMember = Catalogs::find()->where(['id' => $id,'Member_id' => $member->ID])->count();
        }

        //check if we make request from keanggotaan online
        //and edit its owns catalogs
        if ($isFromMember==false || $countCatMember ==0){
            throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        if($for=='cat')
        {
            $member = Members::find()->where(['MemberNo'=>Yii::$app->user->identity->NoAnggota])->one();
            $count = Catalogs::find()->where(['id' => $id,'Member_id' => $member->ID])->count();

            if (\Yii::$app->user->can('katalog') || \Yii::$app->user->can('superadmin') || $count !=0) {
                $model = Catalogs::findOne($id);
                $modelcat = $model;
            }else{
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }else{
            if (\Yii::$app->user->can('akuisisi') || \Yii::$app->user->can('superadmin')) {
                $model = Collections::findOne($id);
                $modelcat = Catalogs::findOne($model->Catalog_id);
            }else{
                throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        }
        $modelbib = new CollectionBiblio;

        if (Yii::$app->request->post()) {
            $this->actionSave(Yii::$app->request->post(),$modelcat,$model,$modelbib,$for);
            
        }else{
            //reset session taglist at begining
            \Yii::$app->session['taglist']=[];
            //prevent inject url param
            $for_req = Yii::$app->request->get('for');
            $rda_req = Yii::$app->request->get('rda');
            //jika request url param 'for' bukan cat/coll maka redirect ke index
            if($for_req!='cat' && $for_req!='coll')
            {
                return $this->goHome();
            }
            //jika request url param 'rda' bukan 1/0 maka redirect ke index
            else if($rda_req!='1' && $rda_req!='0')
            {
                return $this->goHome();
            }else{
                $isUserHasAccess = CatalogHelpers::isUserHasAccess(Yii::$app->user->identity->id);
                //jika user tidak diberikan akses untuk edit koleksi
                if($for_req=='coll' && $isUserHasAccess == false)
                {
                    $this->getView()->registerJs('
                        swal({
                            title: " ",
                            text: "User '. Yii::$app->user->identity->username.' '.yii::t('app','tidak mempunyai akses melakukan entri koleksi!').'",
                            type: "warning",
                            timer: 5000,
                            cancelButtonText: "'.yii::t('app','Tutup').'",
                            closeOnConfirm: true,
                        },
                        function(){
                            window.location.href = "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi"]).'";
                        });
                    ');
                }
            }

            //mapping default array
            $rulesform['008_Bahasa'] = 0;
            $rulesform['008_KaryaTulis'] = 0;
            $rulesform['008_KelompokSasaran'] = 0;
            $taglistDb = array();
            $listvar['publication']=array();
            $listvar['lokasidaring']=array();
            $listvar['lokasidaringtype']=array();
            $listvar['judulsebelum']=array();
            $listvar['judulsebelumtype']=array();
            $listvar['frekuensisebelum']=array();
            $listvar['frekuensisebelumtype']=array();
            $listvar['author']=array();
            $listvar['authortype']= array();
            $listvar['isbn']= array();
            $listvar['subject']= array();
            $listvar['subjecttag']=array();
            $listvar['subjectind']= array();
            $listvar['callnumber']= array();
            $listvar['note']= array();
            $listvar['notetag']= array();
            $listvar['titlevarian']= array();
            $listvar['titleoriginal']= array();
            $listvar['frekuensisebelum']=array();
            $listvar['frekuensisebelumtype']=array();
            $listvar['input_required']=array();
            $listvar['input_required'] = $this->actionValidateRequiredSimpleForm($rda);
            
            //Parsing dari katalog ruas n subruas
            if($model !== null){
                if($for=='cat')
                {
                    $CatalogId=$id;
                    //load tag 008 simple
                    $rulesform['008_Bahasa'] = (int)Worksheetfields::getStatusTag008(5,$model->Worksheet_id,29);
                    $rulesform['008_KaryaTulis'] = (int)Worksheetfields::getStatusTag008(17,$model->Worksheet_id,29);
                    $rulesform['008_KelompokSasaran'] = (int)Worksheetfields::getStatusTag008(2,$model->Worksheet_id,29);

                    //for tab collections
                    $rulesColl = Json::decode(Yii::$app->request->get('rules'));
                    if ($rulesColl) 
                    {
                        \Yii::$app->session['SessCatalogTabActive'] = 'koleksi';
                    }
                    $searchModelColl = new CollectionSearch;
                    $dataProviderColl = $searchModelColl->advancedSearchByCatalogId($id,$rulesColl);

                    //for tab articles
                    $searchModelArticles = new SerialArticlesSearch;
                    $dataProviderArticles = $searchModelArticles->advancedSearchByCatalogId($id,$rulesColl);
                    $dataProviderArticlesWithKontenDigital = $searchModelArticles->advancedSearchWithKontenDigitalByCatalogId($id,$rulesColl);

                    /*$searchModel = new SerialArticlesSearch;
                    $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());*/

                    //for tab konten digital artikel
                    $modelkontendigitalArtikel =  new SerialArticlefiles();
                    $modelkontendigitalArtikel->isCompress = 1;
                    $par='';
                    $searchModelKontenDigitalArticles = new SerialArticleFilesSearch();
                    $dataProviderKontenDigitalArticles = $searchModelKontenDigitalArticles->search($par);

                    //for tab konten digital
                    $modelkontendigital =  new Catalogfiles;
                    $modelkontendigital->isCompress = 1;
                    $rulesKontenDigital = Json::decode(Yii::$app->request->get('ruleskontendigital'));

                    if ($rulesKontenDigital)
                    {
                        \Yii::$app->session['SessCatalogTabActive'] = 'kontendigital';
                    }
                    $searchModelKontenDigital = new CatalogfileSearch;
                    $dataProviderKontenDigital = $searchModelKontenDigital->advancedSearchByCatalogId($id,$rulesKontenDigital);
                }else{

                    //saat edit koleksi
                    $model->TanggalPengadaan = Yii::$app->formatter->asDate($model->TanggalPengadaan, 'php:d-m-Y');
                    if($model->TANGGAL_TERBIT_EDISI_SERIAL)
                    {
                        $model->TANGGAL_TERBIT_EDISI_SERIAL = Yii::$app->formatter->asDate($model->TANGGAL_TERBIT_EDISI_SERIAL, 'php:d-m-Y');
                    }
                    $CatalogId=$model->Catalog_id;
                }
                
                //Create model bib
                $this->actionCreateModelBibFromCatalog($CatalogId,$modelbib);
                if(!$modelbib->AuthorType)
                    $modelbib->AuthorType=0;
                if(!$modelbib->AuthorAddedType)
                    $modelbib->AuthorAddedType[0]=0;
                if(!$modelbib->SubjectTag)
                    $modelbib->SubjectTag[0]='650';
                if(!$modelbib->SubjectInd)
                    $modelbib->SubjectInd[0]='#';
                if(!$modelbib->NoteTag)
                    $modelbib->NoteTag[0]='520';
                if(!$modelbib->LokasiDaringType)
                    $modelbib->LokasiDaringType=0;
                if(!$modelbib->LokasiDaringAddedType)
                    $modelbib->LokasiDaringAddedType[0]=0;
                if(!$modelbib->JudulSebelumType)
                    $modelbib->JudulSebelumType=0;
                if(!$modelbib->JudulSebelumAddedType)
                    $modelbib->JudulSebelumAddedType[0]=0;
                if(!$modelbib->FrekuensiSebelumType)
                    $modelbib->FrekuensiSebelumType=0;
                if(!$modelbib->FrekuensiSebelumAddedType)
                    $modelbib->FrekuensiSebelumAddedType[0]=0;
                $dataPublication = [];
                if(isset($modelbib->PublishLocation)) {
                    
                    foreach ($modelbib->PublishLocation as $key => $value) {
                        $dataPublication[$key] = ['publishlocation'=>$value] + ['publisher'=>$modelbib->Publisher[$key]] + ['publishyear'=>$modelbib->PublishYear[$key]];
                    }
                } else
                if(isset($modelbib->Publisher)) {
                    foreach ($modelbib->Publisher as $key => $value) {
                        $dataPublication[$key] = ['publishlocation'=>$value] + ['publisher'=>$modelbib->Publisher[$key]] + ['publishyear'=>$modelbib->PublishYear[$key]];
                    }
                } else
                if(isset($modelbib->PublishYear)) {
                    foreach ($modelbib->PublishYear as $key => $value) {
                        $dataPublication[$key] = ['publishlocation'=>$value] + ['publisher'=>$modelbib->Publisher[$key]] + ['publishyear'=>$modelbib->PublishYear[$key]];
                    }
                } 
                
                
                $listvar['publication']=$dataPublication;
                $listvar['author']=$modelbib->AuthorAdded;
                $listvar['authortype']= $modelbib->AuthorAddedType;
                $listvar['lokasidaring']=$modelbib->LokasiDaringAdded;
                $listvar['lokasidaringtype']=$modelbib->LokasiDaringAddedType;
                $listvar['judulsebelum']=$modelbib->JudulSebelumAdded;
                $listvar['judulsebelumtype']=$modelbib->JudulSebelumAddedType;
                $listvar['frekuensisebelum']=$modelbib->FrekuensiSebelumAdded;
                $listvar['frekuensisebelumtype']=$modelbib->FrekuensiSebelumAddedType;
                $listvar['datamatematis']= $modelbib->DataMatematis;
                $listvar['catatanrinciansistem']= $modelbib->CatatanRincianSistem;
                $listvar['isbn']= $modelbib->ISBN;
                $listvar['issn']= $modelbib->ISSN;
                $listvar['subject']= $modelbib->Subject;
                $listvar['subjecttag']=$modelbib->SubjectTag;
                $listvar['subjectind']= $modelbib->SubjectInd;
                $listvar['callnumber']= $modelbib->CallNumber;
                $listvar['note']= $modelbib->Note;
                $listvar['notetag']= $modelbib->NoteTag;
                $listvar['titlevarian']= $modelbib->TitleVarian;
                $listvar['titleoriginal']= $modelbib->TitleOriginal;

                // echo '<pre>'; print_r($modelbib); die;
                //Get user setting 
                $modelusrs = Users::findOne((int)Yii::$app->user->identity->ID);
                $isAdvanceEntryCollection = (int)$modelusrs->IsAdvanceEntryCollection;
                $isAdvanceEntryCatalog = (int)$modelusrs->IsAdvanceEntryCatalog;
                if($for == 'cat')
                {
                    $isAdvanceEntry=$isAdvanceEntryCatalog;
                }
                else
                {
                    $isAdvanceEntry=$isAdvanceEntryCollection;
                }

                //Create taglist
                $worksheetid = (int)$modelcat->Worksheet_id;
                $taglist=array();
                $this->actionCreateTaglistFromCatalog($CatalogId,$worksheetid,$for,$taglist,$rda);
                \Yii::$app->session['taglist'] = $taglist;
            }

                //check if is serial
                $isSerial = Worksheets::findOne($worksheetid)->ISSERIAL;
            //when no postback given
            return $this->render('update', [
                'searchModelArticles' => $searchModelArticles,
                'dataProviderArticlesWithKontenDigital' => $dataProviderArticlesWithKontenDigital,
                'dataProviderArticles' => $dataProviderArticles,
                'modelkontendigitalArtikel' => $modelkontendigitalArtikel,
                'searchModelKontenDigitalArticles' => $searchModelKontenDigitalArticles,
                'dataProviderKontenDigitalArticles' => $dataProviderKontenDigitalArticles,
                'isSerial' => $isSerial,
                'for'=>$for,
                'rda'=>$rda,
                'worksheetid'=>$worksheetid,
                'model' => $model,
                'modelcat' => $modelcat,
                'modelbib' => $modelbib,
                'taglist'=>$taglist,
                'listvar'=>$listvar, 
                'rulesform'=>$rulesform,
                'isAdvanceEntry' => 0,
                'mode'=>'update',
                'dataProviderColl' => $dataProviderColl,
                'searchModelColl' => $searchModelColl,
                'rulesColl'=>$rulesColl,
                'modelkontendigital'=>$modelkontendigital,
                'dataProviderKontenDigital' => $dataProviderKontenDigital,
                'searchModelKontenDigital' => $searchModelKontenDigital,
                'referrerUrl'=>($refer != NULL) ? CatalogHelpers::encrypt_decrypt('decrypt',$refer) : Yii::$app->request->referrer
                ]);
        } 
    }

    /**
     * [fungsi Bikin array taglist yang clean + mix kalo ada data tag nya]
     * @param  array $taglist     [array taglist]
     * @param  integer $worksheetId [id jenis bahan]
     * @param  string $for         [cat,coll]
     * @param  integer $rda         [status rda]
     * @param  boolean $salincatalog         [status salincatalog]
     * @return mixed
     */
    public function actionCreateTaglistClean($taglist,$worksheetId,$for,$rda,$salincatalog=false)
    {
        //load data dari worksheetfield
        if($for=='cat')
        {
            $modelwf = Worksheetfields::find()
            ->joinWith(['field'],' fields.ID = worksheetfields.Field_id')
            ->where(
                [
                'Worksheet_id'=>(int)$worksheetId
                ])->all();
        }else{

            $modelwf = Worksheetfields::find()
            ->joinWith(['field'],' fields.ID = worksheetfields.Field_id')
            ->where(
                [
                'Worksheet_id'=>(int)$worksheetId,
                'IsAkuisisi'=>1
                ])->all();
        }

        foreach ($modelwf as $data) {
            //jika rda dan tagnya 260, maka di skio
            if($rda == '1' && $data->field->Tag == '260')
            {
                continue;
            }
            //jika form non rda tapi tag 264,336,337,338,246,740,542 ada maka di skip
            if($rda == '0' && (
                $data->field->Tag == '264' || 
                $data->field->Tag == '336' || 
                $data->field->Tag == '337' || 
                $data->field->Tag == '338' || 
                $data->field->Tag == '246' || 
                $data->field->Tag == '740' || 
                $data->field->Tag == '542'
                ))
            {
                continue;
            }

            //jika didalam variable taglist ada key 'inputvalue'
            if (array_key_exists('inputvalue', $taglist)) 
            {
                //jika ketika mode edit input varible dari database belum ada di array taglist maka ditambahkan defualt $a
                if (!array_key_exists($data->field->Tag, $taglist['inputvalue'])) {
                    if($data->field->Fixed)
                    {
                        if($taglist['inputvalue'][$data->field->Tag] == NULL)
                        {
                            if($worksheetId==1 && $data->field->Tag == '007')
                            {
                                $taglist['inputvalue'][$data->field->Tag] = 'ta';
                            }else{
                                $taglist['inputvalue'][$data->field->Tag] = '';
                            }  
                        }else{
                            //jika isian tag fixed ada dan sedang proses salin katalog
                            if($salincatalog == true && ($data->field->Tag == '001' || $data->field->Tag == '005' || $data->field->Tag == '035'))
                            {
                                $taglist['inputvalue'][$data->field->Tag] = ''; 
                            }
                        }
                    }else{
                        if($data->field->Tag == '336')
                        {
                            $extraValueRDA = '$2 rdacontent';
                        }
                        else if($data->field->Tag == '337')
                        {
                            $extraValueRDA = '$2 rdamedia';
                        }
                        elseif($data->field->Tag == '338')
                        {
                            $extraValueRDA = '$2 rdacarrier';
                        }else{
                            $extraValueRDA ='';
                        }

                        if($data->field->Repeatable)
                        {
                            $taglist['inputvalue'][$data->field->Tag][0] = '$a '.$extraValueRDA;
                        }else{
                            $taglist['inputvalue'][$data->field->Tag] = '$a '.$extraValueRDA;  
                        }
                    }
                    $taglist['tagid'][$data->field->Tag] = $data->field->ID;
                    $taglist['tagname'][$data->field->Tag] = $data->field->Name;
                    $taglist['tagmandatory'][$data->field->Tag] = $data->field->Mandatory;
                    $taglist['taglength'][$data->field->Tag] = $data->field->Length;
                    $taglist['tagenabled'][$data->field->Tag] = $data->field->Enabled;
                    $taglist['tagiscustomable'][$data->field->Tag] = $data->field->IsCustomable;
                    $taglist['tagfixed'][$data->field->Tag] = $data->field->Fixed;
                    $taglist['tagrepeatable'][$data->field->Tag] = $data->field->Repeatable;
                    if($data->field->Repeatable)
                    {
                        $taglist['indicator'][$data->field->Tag][0] = array('ind1'=>'#','ind2'=>'#');
                    }else{
                        $taglist['indicator'][$data->field->Tag] = array('ind1'=>'#','ind2'=>'#');  
                    }
                    
                }else{
                    if($data->field->Fixed)
                    {
                        if($taglist['inputvalue'][$data->field->Tag] == NULL)
                        {
                            if($worksheetId==1 && $data->field->Tag == '007')
                            {
                                $taglist['inputvalue'][$data->field->Tag] = 'ta';
                            }else{
                                $taglist['inputvalue'][$data->field->Tag] = '';
                            }
                        }else{
                            //jika isian tag fixed ada dan sedang proses salin katalog
                            if($salincatalog == true && ($data->field->Tag == '001' || $data->field->Tag == '005' || $data->field->Tag == '035'))
                            {
                                $taglist['inputvalue'][$data->field->Tag] = ''; 
                            }
                        }
                    }
                }
            }else{
                if($data->field->Fixed)
                {
                    if($taglist['inputvalue'][$data->field->Tag] == NULL)
                    {
                        if($worksheetId==1 && $data->field->Tag == '007')
                        {
                            $taglist['inputvalue'][$data->field->Tag] = 'ta';
                        }else{
                            $taglist['inputvalue'][$data->field->Tag] = '';
                        }
                    }else{
                        //jika isian tag fixed ada dan sedang proses salin katalog
                        if($salincatalog == true && ($data->field->Tag == '001' || $data->field->Tag == '005' || $data->field->Tag == '035'))
                        {
                            $taglist['inputvalue'][$data->field->Tag] = ''; 
                        }
                    }
                }else{
                    if($data->field->Tag == '336')
                    {
                        $extraValueRDA = '$2 rdacontent';
                    }
                    else if($data->field->Tag == '337')
                    {
                        $extraValueRDA = '$2 rdamedia';
                    }
                    elseif($data->field->Tag == '338')
                    {
                        $extraValueRDA = '$2 rdacarrier';
                    }else{
                        $extraValueRDA ='';
                    }

                    if($data->field->Repeatable)
                    {
                        $taglist['inputvalue'][$data->field->Tag][0] = '$a '.$extraValueRDA;
                    }else{
                        $taglist['inputvalue'][$data->field->Tag] = '$a '.$extraValueRDA;  
                    }
                    
                }
                
                $taglist['tagid'][$data->field->Tag] = $data->field->ID;
                $taglist['tagname'][$data->field->Tag] = $data->field->Name;
                $taglist['tagmandatory'][$data->field->Tag] = $data->field->Mandatory;
                $taglist['taglength'][$data->field->Tag] = $data->field->Length;
                $taglist['tagenabled'][$data->field->Tag] = $data->field->Enabled;
                $taglist['tagiscustomable'][$data->field->Tag] = $data->field->IsCustomable;
                $taglist['tagfixed'][$data->field->Tag] = $data->field->Fixed;
                $taglist['tagrepeatable'][$data->field->Tag] = $data->field->Repeatable;
                if($data->field->Repeatable)
                {
                    $taglist['indicator'][$data->field->Tag][0] = array('ind1'=>'#','ind2'=>'#');
                }else{
                    $taglist['indicator'][$data->field->Tag] = array('ind1'=>'#','ind2'=>'#');  
                }
            }
            //simpan varible tag dari database
            if($data->field->Tag == '336')
            {
                $extraValueRDA = '$2 rdacontent';
            }
            else if($data->field->Tag == '337')
            {
                $extraValueRDA = '$2 rdamedia';
            }
            elseif($data->field->Tag == '338')
            {
                $extraValueRDA = '$2 rdacarrier';
            }else{
                $extraValueRDA ='';
            }

            if($data->field->Repeatable)
            {
                $taglistDb['inputvalue'][$data->field->Tag][0] = '$a '.$extraValueRDA;
            }else{
                $taglistDb['inputvalue'][$data->field->Tag] = '$a '.$extraValueRDA;
            }
            
            
            
        }

        if (array_key_exists('inputvalue', $taglist)) 
        {
            //jika ada varible yg diinput, tapi bukan tag dari list db maka akan dibuang
            foreach ($taglist['inputvalue'] as $key => $value) {
                //jika didalam variable taglistdb tidak ada key maka di unset
                if (!array_key_exists($key, $taglistDb['inputvalue'])) {
                    unset($taglist['inputvalue'][$key]);
                    unset($taglist['tagid'][$key]);
                    unset($taglist['tagname'][$key]);
                    unset($taglist['tagmandatory'][$key]);
                    unset($taglist['taglength'][$key]);
                    unset($taglist['tagenabled'][$key]);
                    unset($taglist['tagiscustomable'][$key]);
                    unset($taglist['tagfixed'][$key]);
                    unset($taglist['tagrepeatable'][$key]);
                    unset($taglist['indicator'][$key]);
                }
            }
        }
        return $taglist;
    }
    
    /**
     * [fungsi untuk membuat taglist dari advance form]
     * @param  model  $model         [model collection]
     * @param  model  $modelbib      [model collectionbiblio]
     * @param  array  $post          [array input]
     * @param  boolean $forsimpleform [apakah untuk k simple form?]
     * @return mixed
     */
    public function actionCreateTaglistAdvance($model,$modelbib,$post,$forsimpleform=false)
    {
        //dapatkan post data dari advance input
            $taglist = array();
            $tagsSingle = array();
            $tagsRepeatable = array();
            $ruasid = array();

            $model->load($post);
            /*echo '<pre>'; print_r($post); echo '</pre>';
            die;*/
            foreach ($post as $key => $value) {
                switch ($key) {

                    //set ruas id value 
                    case 'Ruasid':
                        $ruasid=$value;
                        break;

                    //set ruas value 
                    case 'TagsValue':
                        foreach ($value as $tagcode => $tagvalue) {
                            
                            //tag repeatable
                            if(is_array($tagvalue))
                            {
                                foreach ($tagvalue as $indextag => $tagvaluerepeatable) {
                                    /*echo $tagcode.' '.$tagvaluerepeatable.'<br>';*/
                                    $tagvalue=trim($tagvaluerepeatable);
                                    $tagvaluesub = explode("$",substr($tagvalue,1,strlen($tagvalue)));
                                    for ($i=0; $i < count($tagvaluesub) ; $i++) { 

                                        $subruascode=substr($tagvaluesub[$i],0,1);
                                        $subruasvalue=substr($tagvaluesub[$i],1,strlen($tagvaluesub[$i]));
                                        if(trim($subruasvalue) != '')
                                        {
                                            $tagsRepeatable[$tagcode][$indextag][$subruascode] = trim($subruasvalue);
                                            if($forsimpleform==true)
                                            {
                                                //membuat variable $modelbib terisi untuk keperluan simple form
                                                $this->actionCreateTagSimple($model,$modelbib,$tagcode,$subruascode,$subruasvalue);
                                            }
                                        }
                                    }
                                     
                                }
                            //tag single
                            }else{
                                /*echo $tagcode.' '.$tagvalue.'<br>';*/
                                $tagvalue=trim($tagvalue);
                                if((int)$tagcode < 10)
                                {
                                    $tagsSingle[$tagcode]['fixed'] = $tagvalue;
                                    if($forsimpleform==true)
                                    {
                                        switch ($tagcode) {
                                            case '008':
                                            if(strlen($tagvalue) >= 40 )
                                            {
                                                $language = substr($tagvalue,35,3);
                                                $bentukkaryatulis = substr($tagvalue,33,1);
                                                $kelompoksasaran = substr($tagvalue,22,1);

                                                $modelbib->Bahasa = $language;
                                                $modelbib->BentukKaryaTulis = $bentukkaryatulis;
                                                $modelbib->KelompokSasaran = $kelompoksasaran;
                                            }
                                            break;
                                        }
                                    }
                                }else{
                                    $tagvaluesub = explode("$",substr($tagvalue,1,strlen($tagvalue)));
                                    for ($i=0; $i < count($tagvaluesub) ; $i++) { 

                                        $subruascode=substr($tagvaluesub[$i],0,1);
                                        $subruasvalue=substr($tagvaluesub[$i],1,strlen($tagvaluesub[$i]));
                                        if(trim($subruasvalue) != '')
                                        {
                                            $tagsSingle[$tagcode][$subruascode] = trim($subruasvalue);
                                            if($forsimpleform==true)
                                            {
                                                //membuat variable $modelbib terisi untuk keperluan simple form
                                                $this->actionCreateTagSimple($model,$modelbib,$tagcode,$subruascode,$subruasvalue);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        break;

                    //set indicator1 value 
                    case 'Indicator1':
                        foreach ($value as $tagcode => $ind1value) {
                            
                            //tag repeatable
                            if(is_array($ind1value))
                            {
                                foreach ($ind1value as $indextag => $ind1valuerepeatable) {
                                    /*echo $tagcode.' '.$ind1valuerepeatable.'<br>';*/
                                    $ind1value=trim($ind1valuerepeatable);
                                    if($ind1value != '')
                                    {
                                        $tagsRepeatableInd[$tagcode][$indextag]['ind1'] = $ind1value;
                                        if($forsimpleform==true)
                                        {
                                            switch ($tagcode) {
                                                case '100':
                                                /*case '110':
                                                case '111':*/
                                                    $modelbib->AuthorType=$ind1value;
                                                break;
                                                case '700':
                                                case '710':
                                                case '711':
                                                    if($tagcode == '711')
                                                        $modelbib->AuthorAddedType[]='##';
                                                    else
                                                        $modelbib->AuthorAddedType[]=$ind1value;
                                                break;
                                                case '650':
                                                case '600':
                                                case '651':
                                                        $modelbib->SubjectInd[]=$ind1value;
                                                break;

                                            }
                                        }
                                    } 
                                }
                            //tag single
                            }else{
                                /*echo $tagcode.' '.$ind1value.'<br>';*/
                                $ind1value=trim($ind1value);
                                if($ind1value != '')
                                {
                                    $tagsSingleInd[$tagcode]['ind1'] = $ind1value;
                                    if($forsimpleform==true)
                                    {
                                        switch ($tagcode) {
                                            case '100':
                                            /*case '110':
                                            case '111':*/
                                                $modelbib->AuthorType=$ind1value;
                                            break;
                                            case '700':
                                            case '710':
                                            case '711':
                                                if($tagcode == '711')
                                                    $modelbib->AuthorAddedType[]='##';
                                                else
                                                    $modelbib->AuthorAddedType[]=$ind1value;
                                            break;
                                            case '650':
                                            case '600':
                                            case '651':
                                                    $modelbib->SubjectInd[]=$ind1value;
                                            break;

                                        }
                                    }
                                }
                            }
                        }
                        break;

                    //set indicator2 value 
                    case 'Indicator2':
                        foreach ($value as $tagcode => $ind2value) {
                            
                            //tag repeatable
                            if(is_array($ind2value))
                            {
                                foreach ($ind2value as $indextag => $ind2valuerepeatable) {
                                    /*echo $tagcode.' '.$ind2valuerepeatable.'<br>';*/
                                    $ind2value=trim($ind2valuerepeatable);
                                    if($ind2value != '')
                                    {
                                        $tagsRepeatableInd[$tagcode][$indextag]['ind2'] = $ind2value;
                                    } 
                                }
                            //tag single
                            }else{
                                /*echo $tagcode.' '.$ind2value.'<br>';*/
                                $ind2value=trim($ind2value);
                                if($ind2value != '')
                                {
                                    $tagsSingleInd[$tagcode]['ind2'] = $ind2value;
                                    if($forsimpleform==true)
                                    {
                                        if($tagcode == 245 && $ind2value != '#')
                                        {
                                            $modelbib->KataSandang = (int)$ind2value-1;
                                        }
                                    }
                                }
                            }
                        }
                        break;
                    
                }
            }

            //echo '<pre>'; print_r($modelbib); echo '</pre>';
            //die;
            
            //mapping tag ke bentuk catalog ruas dengan penambahan $
            foreach ($tagsSingle as $key => $value) {
                $outputTags = '';
                if((int)$key < 10)
                {
                    $outputTags = $value['fixed'];
                }else{
                    foreach ($value as $keyruas => $valueruas) 
                    {
                        $isTandaBaca = -1;
                    

                        //START CLEAN TANDA BACA
                        $patterns = CatalogHelpers::getRegexPatternCleanTandaBaca($key);
                        if(count($patterns) > 0)
                        {
                            //regex clean tandabaca di awal dan akhir
                            $cleanValue = preg_replace($patterns, "", $valueruas);
                        }else{
                            $cleanValue = $valueruas;
                        }
                        $cleanValue=trim($cleanValue);
                        //END CLEAN TANDA BACA 

                        //SET TANDA BACA UNTUK DEFAULT LOOP
                        $tandaBaca = (string)Fields::getTandaBaca($key,$keyruas);
                        if(!empty($tandaBaca))
                        {
                            $isTandaBaca = strpos($outputTags,$tandaBaca);
                        }

                        if($isTandaBaca == -1)
                        {
                            //$outputTags .= '$'.$keyruas.' '.$cleanValue.' ';
                            $outputTags .= '$'.$keyruas.' '.$cleanValue;
                        }else{
                            //$outputTags .= $tandaBaca.'$'.$keyruas.' '.$cleanValue.' ';
                            $outputTags .= $tandaBaca.'$'.$keyruas.' '.$cleanValue;
                        }
                    }
                }
                $taglist['ruasid'][$key] = (isset($ruasid[$key])) ? $ruasid[$key] : '';
                $taglist['inputvalue'][$key]  = $outputTags;
                $taglist['tagname'][$key]  = 'Unknown';
            }

             //Single value for indicator
            foreach ($tagsSingleInd as $key => $value) {  
                foreach ($value as $keyruas => $valueruas) 
                {
                    $taglist['indicator'][$key][$keyruas]  = $valueruas;
                }
            } 

            foreach ($tagsRepeatable as $key => $value) {
                
                foreach ($value as $keyruas => $valueruas)
                {
                    $outputTags = '';
                    foreach ($valueruas as $keyruas2 => $valueruas2)
                    {
                         $isTandaBaca = -1;
                    

                        //START CLEAN TANDA BACA
                        $patterns = CatalogHelpers::getRegexPatternCleanTandaBaca($key);
                        if(count($patterns) > 0)
                        {
                            //regex clean tandabaca di awal dan akhir
                            $cleanValue = preg_replace($patterns, "", $valueruas2);
                        }else{
                            $cleanValue = $valueruas2;
                        }
                        $cleanValue=trim($cleanValue);
                        //END CLEAN TANDA BACA 

                        //SET TANDA BACA UNTUK DEFAULT LOOP
                        $tandaBaca = (string)Fields::getTandaBaca($key,$keyruas2);
                        if(!empty($tandaBaca))
                        {
                            $isTandaBaca = strpos($outputTags,$tandaBaca);
                        }

                        if($isTandaBaca == -1)
                        {
                            //$outputTags .= '$'.$keyruas.' '.$cleanValue.' ';
                            $outputTags .= '$'.$keyruas2.' '.$cleanValue;
                        }else{
                            //$outputTags .= $tandaBaca.'$'.$keyruas.' '.$cleanValue.' ';
                            $outputTags .= $tandaBaca.'$'.$keyruas2.' '.$cleanValue;
                        }
                    }
                    $taglist['ruasid'][$key][$keyruas] = (isset($ruasid[$key][$keyruas])) ? $ruasid[$key][$keyruas] : '';
                    $taglist['inputvalue'][$key][$keyruas] = $outputTags;
                    $taglist['tagname'][$key][$keyruas] = 'Unknown';
                }
            }

            //Repeatable value for indicator
            foreach ($tagsRepeatableInd as $key => $value) {  
                foreach ($value as $keyruas => $valueruas) 
                {
                    $taglist['indicator'][$key][$keyruas]  = $valueruas;
                }
            }

            if (array_key_exists('tagname', $taglist)) 
            {
                foreach ($taglist['tagname'] as $key => $value) {
                    $datatag = Fields::find()
                    ->where(['Tag'=>$key])
                    ->one();
                    unset($taglist['tagname'][$key]);
                    $taglist['tagname'][$key] = $datatag->Name;
                    $taglist['tagid'][$key] = $datatag->ID;
                    $taglist['tagmandatory'][$key] = $datatag->Mandatory;
                    $taglist['taglength'][$key] = $datatag->Length;
                    $taglist['tagenabled'][$key] = $datatag->Enabled;
                    $taglist['tagiscustomable'][$key] = $datatag->IsCustomable;
                    $taglist['tagfixed'][$key] = $datatag->Fixed;
                    $taglist['tagrepeatable'][$key] = $datatag->Repeatable;
                }
            }

            //$session->open();
            //$session['taglist'] = $taglist;
            return $taglist;

    }

    /**
     * [fungsi untuk membuat taglist dari simple form]
     * @param  model $model       [model collection]
     * @param  model $modelbib    [model collectionbiblio]
     * @param  array $post        [array input]
     * @param  string $for         [cat,coll]
     * @param  integer $worksheetid [id jenis bahan]
     * @param  integer $rda         [status rda]
     * @return mixed
     */
    public function actionCreateTaglistSimple($model,$modelbib,$post,$for,$worksheetid,$rda)
    {
        //dapatkan post data dari advance input
        $tagsSingle = array();
        $tagsSingleInd = array();
        $tagsRepeatable = array();
        $tagsRepeatableInd = array();
        $taglist = array();
        $ruasid = array();

        $modelbib->load($post);
        $model->load($post);
        $ruasid=$post['Ruasid'];

        // echo '<pre>'; print_r($modelbib); die;

        $mainAuthors='';
        if($modelbib->Author){
            $authors= explode($modelbib->Author,';');
            if(count($authors) > 0)
            {
                $mainAuthors=$authors[0];
            }else{
                $mainAuthors=$modelbib->Author;
            }
        }

        if($modelbib->Title){
            if($mainAuthors!='')
            {
                $tagsSingleInd[245]['ind1'] = '1';
            }else{
                $tagsSingleInd[245]['ind1'] = '#';
            }
            if($modelbib->KataSandang)
            {
                $titlemix = trim($modelbib->Title);
                $tagsSingleInd[245]['ind2'] = (int)$modelbib->KataSandang + 1;
            }else{
                $titlemix = trim($modelbib->Title);
                $tagsSingleInd[245]['ind2'] = '#';
            }
            $tagsSingle[245]['a'] = $titlemix;
            
        }

        if($modelbib->TitleAdded){
            $tagsSingle[245]['b'] = trim($modelbib->TitleAdded);
        }

        if($modelbib->PenanggungJawab)
        {
            $tagsSingle[245]['c'] = trim($modelbib->PenanggungJawab);
        }

        if($modelbib->TitleVarian[0])
        {
            foreach ($modelbib->TitleVarian as $key => $value) {
                $tagsRepeatable[246][$key]['a'] = trim($value);
                $tagsRepeatableInd[246][$key] = array('ind1'=>'#','ind2'=>'#');
            }
        }

        if($modelbib->JudulSeragam)
        {
            $tagsSingle[240]['a'] = trim($modelbib->JudulSeragam);
        }

        if($modelbib->DataMatematis)
        {
            $tagsSingle[255]['a'] = trim($modelbib->DataMatematis);
        }

        if($modelbib->CatatanRincianSistem)
        {
            $tagsSingle[538]['a'] = trim($modelbib->CatatanRincianSistem);
        }

        if($modelbib->TitleOriginal[0])
        {
            foreach ($modelbib->TitleOriginal as $key => $value) {
                $tagsRepeatable[740][$key]['a'] = trim($value);
                $tagsRepeatableInd[740][$key] = array('ind1'=>'#','ind2'=>'#');
            }
        }

        if($modelbib->Author){
            $tagsSingle[100]['a'] = trim($modelbib->Author);
            if($modelbib->AuthorType != '')
            {
                $tagsSingleInd[100]['ind1'] = trim($modelbib->AuthorType);
            }
            else
            {
                $tagsSingleInd[100]['ind1'] = '#';
            }
            $tagsSingleInd[100]['ind2'] = '#';

            if($modelbib->AuthorRelatorTerm){
                $tagsSingle[100]['e'] = trim($modelbib->AuthorRelatorTerm);
            }

        }

        if($modelbib->LokasiDaringAdded[0])
        {
            foreach ($modelbib->LokasiDaringAdded as $key => $value) {
                $tagsRepeatable[856][$key]['a'] = trim($value);
                $tagsRepeatableInd[856][$key] = array('ind1'=>'#','ind2'=>'#');
            }
        }

        if($modelbib->JudulSebelumAdded[0])
        {
            foreach ($modelbib->JudulSebelumAdded as $key => $value) {
                $tagsRepeatable[247][$key]['a'] = trim($value);
                $tagsRepeatableInd[247][$key] = array('ind1'=>'#','ind2'=>'#');
            }
        }

        if($modelbib->FrekuensiSebelumAdded[0])
        {
            foreach ($modelbib->FrekuensiSebelumAdded as $key => $value) {
                $tagsRepeatable[321][$key]['a'] = trim($value);
                $tagsRepeatableInd[321][$key] = array('ind1'=>'#','ind2'=>'#');
            }
        }

        if($modelbib->AuthorAdded[0])
        {
            $count700=0;
            $count710=0;
            $count711=0;
            foreach ($modelbib->AuthorAdded as $key => $value) {
                $tagAuthorAdded;
                $ind1AuthorAdded;
                switch ($modelbib->AuthorAddedType[$key]) {
                    case '0':
                    case '1':
                    case '3':
                    $tagAuthorAdded = '700';
                    $ind1AuthorAdded = $modelbib->AuthorAddedType[$key];
                    $count700++;
                    break;
                    case '#':
                    $tagAuthorAdded = '710';
                    $ind1AuthorAdded = '#';
                    $count710++;
                    break;
                    case '##':
                    $tagAuthorAdded = '711';
                    $ind1AuthorAdded = '#';
                    $count711++;
                    break;

                    default:
                            # code...
                    break;
                }

                if($tagAuthorAdded == '700'){
                    $indexruas=$count700-1;
                }else if($tagAuthorAdded == '710'){
                    $indexruas=$count710-1;
                }else if($tagAuthorAdded == '711'){
                    $indexruas=$count711-1;
                }
                $tagsRepeatable[$tagAuthorAdded][$indexruas]['a'] = trim($value);
                $tagsRepeatableInd[$tagAuthorAdded][$indexruas] = array('ind1'=>$ind1AuthorAdded,'ind2'=>'#');

                if($modelbib->AuthorAddedRelatorTerm[$key])
                {
                    $tagsRepeatable[$tagAuthorAdded][$indexruas]['e'] = trim($modelbib->AuthorAddedRelatorTerm[$key]);
                }
            }
        }

        if($modelbib->PublishLocation || $modelbib->Publisher || $modelbib->PublishYear){
            if($rda == '1')
            {
                $tagdestination = '264';
            }else{
                $tagdestination = '260';
            }
            $modelfieldPublication = Fields::find()->where(['Tag'=>$tagdestination])->one();
            if($modelfieldPublication->Repeatable)
            {
                
                //reset index of array
                $modelbib->PublishLocation = array_values($modelbib->PublishLocation);
                $modelbib->Publisher = array_values($modelbib->Publisher);
                $modelbib->PublishYear = array_values($modelbib->PublishYear);

                foreach ($modelbib->PublishLocation as $indexrepeat => $valuerepeat) {
                    $publishAll = array();

                    if(!empty($valuerepeat))
                    {
                        $publishAll['a'] = $valuerepeat;
                    }
                    if(!empty($modelbib->Publisher[$indexrepeat]))
                    {
                        $publishAll['b'] = $modelbib->Publisher[$indexrepeat];
                    }
                    if(!empty($modelbib->PublishYear[$indexrepeat]))
                    {
                        $publishAll['c'] = $modelbib->PublishYear[$indexrepeat];
                    }

                    if(count($publishAll) > 0)
                    {
                        $tagsRepeatable[$tagdestination][$indexrepeat] = $publishAll;
                        $tagsRepeatableInd[$tagdestination][$indexrepeat] = array('ind1'=>'#','ind2'=>'#');
                    }
                }

                if(is_array($tagsRepeatable[$tagdestination]))
                {
                    $tagsRepeatable[$tagdestination] = array_values($tagsRepeatable[$tagdestination]);
                    $tagsRepeatableInd[$tagdestination] = array_values($tagsRepeatableInd[$tagdestination]);
                }
                
            }else{
                if($modelbib->PublishLocation)
                    $tagsSingle[$tagdestination]['a'] = trim($modelbib->PublishLocation);
                if($modelbib->Publisher)
                    $tagsSingle[$tagdestination]['b'] = trim($modelbib->Publisher);
                if($modelbib->PublishYear)
                    $tagsSingle[$tagdestination]['c'] = trim($modelbib->PublishYear);

                $tagsSingleInd[$tagdestination]['ind1'] = '#';
                $tagsSingleInd[$tagdestination]['ind2'] = '#';
            }
        }

        if($modelbib->JenisIsi)
        {
            $modelfield336 = Fields::find()->where(['Tag'=>'336'])->one();
            if($modelfield336->Repeatable)
            {
                $tagsRepeatable[336][] = array('a'=>trim($modelbib->JenisIsi),'2'=>'rdacontent');
                $tagsRepeatableInd[336][] = array('ind1'=>'#','ind2'=>'#');
            }else{
                $tagsSingle[336]['a'] = trim($modelbib->JenisIsi);
                $tagsSingle[336]['2'] = 'rdacontent';
                $tagsSingleInd[336]['ind1'] = '#';
                $tagsSingleInd[336]['ind2'] = '#';
            }
        }

        if($modelbib->JenisMedia)
        {
            $modelfield337 = Fields::find()->where(['Tag'=>'337'])->one();
            if($modelfield337->Repeatable)
            {
                $tagsRepeatable[337][] = array('a'=>trim($modelbib->JenisMedia),'2'=>'rdamedia');
                $tagsRepeatableInd[337][] = array('ind1'=>'#','ind2'=>'#');
            }else{
                $tagsSingle[337]['a'] = trim($modelbib->JenisMedia);
                $tagsSingle[337]['2'] = 'rdamedia';
                $tagsSingleInd[337]['ind1'] = '#';
                $tagsSingleInd[337]['ind2'] = '#';
            }
        }

        if($modelbib->JenisCarrier)
        {
            $modelfield338 = Fields::find()->where(['Tag'=>'338'])->one();
            if($modelfield338->Repeatable)
            {
                $tagsRepeatable[338][] = array('a'=>trim($modelbib->JenisCarrier),'2'=>'rdacarrier');
                $tagsRepeatableInd[338][] = array('ind1'=>'#','ind2'=>'#');
            }else{
                $tagsSingle[338]['a'] = trim($modelbib->JenisCarrier);
                $tagsSingle[338]['2'] = 'rdacarrier';
                $tagsSingleInd[338]['ind1'] = '#';
                $tagsSingleInd[338]['ind2'] = '#';
            }
        }

        if($modelbib->Edition)
        {
            $tagsSingle[250]['a'] = trim($modelbib->Edition);
            $tagsSingleInd[250]['ind1'] = '#';
            $tagsSingleInd[250]['ind2'] = '#';
        }

        if($modelbib->Class)
        {
            $modelfield082 = Fields::find()->where(['Tag'=>'082'])->one();
            if($modelfield082->Repeatable)
            {
                $tagsRepeatable['082'][] = array('a'=>trim($modelbib->Class));
                $tagsRepeatableInd['082'][] = array('ind1'=>'#','ind2'=>'#');
            }else{
                $tagsSingle['082']['a'] = trim($modelbib->Class);
                $tagsSingleInd['082']['ind1'] = '#';
                $tagsSingleInd['082']['ind2'] = '#'; 
            }
        }

        if($modelbib->CallNumber[0])
        {
            foreach ($modelbib->CallNumber as $key => $value) {
                $tagsRepeatable['084'][$key]['a'] = trim($value);
                $tagsRepeatableInd['084'][$key] = array('ind1'=>'#','ind2'=>'#');
            }
        }

        
        if($modelbib->JumlahHalaman)
        {
            $tagsSingle[300]['a'] = trim($modelbib->JumlahHalaman);
        }

        if($modelbib->KeteranganIllustrasi)
        {
            $tagsSingle[300]['b'] = trim($modelbib->KeteranganIllustrasi);
        }

        if($modelbib->Dimensi)
        {
            $tagsSingle[300]['c'] = trim($modelbib->Dimensi);
        }

        if($modelbib->JumlahHalaman || $modelbib->KeteranganIllustrasi || $modelbib->Dimensi) {
            $tagsSingleInd[300]['ind1'] = '#';
            $tagsSingleInd[300]['ind2'] = '#';
        }

        if($modelbib->FrekuensiSaatIni)
        {
            $tagsSingle[310]['a'] = trim($modelbib->FrekuensiSaatIni);
        }

        
        /*if($modelbib->PhisycalDescription) {
            try {
                $part1 = explode(':',$modelbib->PhisycalDescription);
                if($part1[1])
                    $part2 = explode(';',$part1[1]);
                if($part1)
                    $Paging = $part1[0];
                if($part2)
                    $Ill = $part2[0];
                if($part2)
                   $Size = $part2[1];
                if($Paging)
                    $tagsSingle[300]['a'] = trim($Paging);
                if($Ill)
                    $tagsSingle[300]['b'] = trim($Ill);
                if($Size)
                    $tagsSingle[300]['c'] = trim($Size);
                $tagsSingleInd[300]['ind1'] = '#';
                $tagsSingleInd[300]['ind2'] = '#';
            } catch (Exception $e) {
                       //Kalo form penulisan ga bisa di explode 
            }
        }*/
        if($modelbib->ISBN[0])
        {
            foreach ($modelbib->ISBN as $key => $value) {
                $tagsRepeatable['020'][$key]['a'] = trim($value);
                $tagsRepeatableInd['020'][$key] = array('ind1'=>'#','ind2'=>'#');
            }
        }

        if($modelbib->ISSN[0])
        {
            foreach ($modelbib->ISSN as $key => $value) {
                $tagsRepeatable['022'][$key]['a'] = trim($value);
                $tagsRepeatableInd['022'][$key] = array('ind1'=>'#','ind2'=>'#');
            }
        }

        if($modelbib->Subject[0])
        {
            $count600=0;
            $count650=0;
            $count651=0;
            foreach ($modelbib->Subject as $key => $value) {
                $tagSubject = $modelbib->SubjectTag[$key];
                $ind1Subject = $modelbib->SubjectInd[$key];
                if($tagSubject == '600'){
                    $count600++;
                    $indexruas=$count600-1;
                }else if($tagSubject == '650'){
                    $count650++;
                    $indexruas=$count650-1;
                }else if($tagSubject == '651'){
                    $count651++;
                    $indexruas=$count651-1;
                }
                $tagsRepeatable[$tagSubject][$indexruas]['a'] = trim($value);
                $tagsRepeatableInd[$tagSubject][$indexruas] = array('ind1'=>$ind1Subject,'ind2'=>'#');
            }
        }

        if($modelbib->Note[0])
        {    
            $count520=0;
            $count502=0;
            $count504=0;
            $count505=0;
            $count500=0;
            $count542=0;
            foreach ($modelbib->Note as $key => $value) {
                $tagNote = $modelbib->NoteTag[$key];
                if($tagNote == '520'){
                    $count520++;
                    $indexruas=$count520-1;
                }else if($tagNote == '502'){
                    $count502++;
                    $indexruas=$count502-1;
                }else if($tagNote == '504'){
                    $count504++;
                    $indexruas=$count504-1;
                }else if($tagNote == '505'){
                    $count505++;
                    $indexruas=$count505-1;
                }else if($tagNote == '500'){
                    $count500++;
                    $indexruas=$count500-1;
                }else if($tagNote == '542'){
                    $count542++;
                    $indexruas=$count542-1;
                }
                $tagsRepeatable[$tagNote][$indexruas]['a'] = trim($value);
                $tagsRepeatableInd[$tagNote][$indexruas] = array('ind1'=>'#','ind2'=>'#');
            }
        }

        //jika entri coll maka edisiserialnya d cek, untuk di mapping
        if($for == 'coll')
        {
            if($model->EDISISERIAL){
                $modelfield863 = Fields::find()->where(['Tag'=>'863'])->one();
                if($modelfield863->Repeatable)
                {
                    $tagsRepeatable['863'][] = array('a'=>trim($model->EDISISERIAL));
                    $tagsRepeatableInd['863'][] = array('ind1'=>'#','ind2'=>'#');
                }else{
                    $tagsSingle['863']['a'] = trim($model->EDISISERIAL);
                    $tagsSingleInd['863']['ind1'] = '#';
                    $tagsSingleInd['863']['ind2'] = '#';
                }
            }
        }


        $datenow = (string)date("ymd");

        $kelompoksasaran='|';
        $bentukkaryatulis='|';
        $bahasa='|';
        if($modelbib->KelompokSasaran  != '')
        {
            $kelompoksasaran=$modelbib->KelompokSasaran;
        }
        if($modelbib->BentukKaryaTulis  != '')
        {
            $bentukkaryatulis=$modelbib->BentukKaryaTulis;
        }
        if($modelbib->Bahasa  != '')
        {
            $bahasa=$modelbib->Bahasa;
        }

        //default value 
        $tagsSingle['006']['fixed'] =  'a################';
        $tagsSingle['007']['fixed'] =  ($worksheetid == 1) ? 'ta' : '';
        $tagsSingle['008']['fixed'] =  $datenow.'################'.$kelompoksasaran.'##########'.$bentukkaryatulis.'#'.$bahasa.'##';

        /*echo '<pre>'; print_r($modelbib); echo '<pre/><br>';
        die;*/


        //Single value
        foreach ($tagsSingle as $key => $value) {
            $outputTags = '';
            if($value)
                ksort($value);   
            if((int)$key < 10)
            {
                $outputTags = $value['fixed'];
            }else{
                foreach ($value as $keyruas => $valueruas) 
                {
                    $isTandaBaca = -1;
                    

                    //START CLEAN TANDA BACA
                    $patterns = CatalogHelpers::getRegexPatternCleanTandaBaca($key);
                    if(count($patterns) > 0)
                    {
                        //regex clean tandabaca di awal dan akhir
                        $cleanValue = preg_replace($patterns, "", $valueruas);
                    }else{
                        $cleanValue = $valueruas;
                    }
                    $cleanValue=trim($cleanValue);
                    //END CLEAN TANDA BACA 

                    //SET TANDA BACA UNTUK DEFAULT LOOP
                    $tandaBaca = (string)Fields::getTandaBaca($key,$keyruas);
                    if(!empty($tandaBaca))
                    {
                        $isTandaBaca = strpos($outputTags,$tandaBaca);
                    }

                    if($isTandaBaca == -1)
                    {
                        //$outputTags .= '$'.$keyruas.' '.$cleanValue.' ';
                        $outputTags .= '$'.$keyruas.' '.$cleanValue;
                    }else{
                        //$outputTags .= $tandaBaca.'$'.$keyruas.' '.$cleanValue.' ';
                        $outputTags .= $tandaBaca.'$'.$keyruas.' '.$cleanValue;
                    }

                }
            }
            $taglist['ruasid'][$key] = (isset($ruasid[$key])) ? $ruasid[$key] : '';
            $taglist['inputvalue'][$key]  = $outputTags;
            $taglist['tagname'][$key]  = 'Unknown';

        }

        //Single value for indicator
        foreach ($tagsSingleInd as $key => $value) {  
            foreach ($value as $keyruas => $valueruas) 
             $taglist['indicator'][$key][$keyruas]  = $valueruas;
        } 

         //Repeatable value
        foreach ($tagsRepeatable as $key => $value) {
            foreach ($value as $keyruas => $valueruas)
            {
                //trap manual untuk publishment karena mix input pada setiap sub ruas
                if((int)$key==264 || (int)$key==260 || $key=='082')
                {
                    $valr='';
                    foreach ($valueruas as $keyruas2 => $valueruas2){
                        $isTandaBaca = -1;

                        //START CLEAN TANDA BACA
                        $patterns = CatalogHelpers::getRegexPatternCleanTandaBaca($key);
                        if(count($patterns) > 0)
                        {
                            //regex clean tandabaca di awal dan akhir
                            $cleanValue = preg_replace($patterns, "", $valueruas2);
                        }else{
                            $cleanValue = $valueruas2;
                        }
                        $cleanValue=trim($cleanValue);
                        //END CLEAN TANDA BACA

                        //SET TANDA BACA UNTUK DEFAULT LOOP
                        $tandaBaca = (string)Fields::getTandaBaca($key,$keyruas2);
                        if(!empty($tandaBaca))
                        {
                            $isTandaBaca = strpos($valr,$tandaBaca);
                        }

                        if($isTandaBaca == -1)
                        {
                            //$valr .= '$'.$keyruas2.' '.trim($cleanValue).' ';
                            $valr .= '$'.$keyruas2.' '.trim($cleanValue);
                        }else{
                            //$valr .= $tandaBaca.'$'.$keyruas2.' '.trim($cleanValue) .' ';
                            $valr .= $tandaBaca.'$'.$keyruas2.' '.trim($cleanValue);
                        }

                    }
                    $taglist['ruasid'][$key][$keyruas] = (isset($ruasid[$key][$keyruas])) ? $ruasid[$key][$keyruas] : '';
                    $taglist['inputvalue'][$key][$keyruas] = $valr;   
                }else{
                    foreach ($valueruas as $keyruas2 => $valueruas2)
                    {
                        //$taglist['inputvalue'][$key][] = '$'.$keyruas2.' '.$valueruas2.' ';
                        $taglist['ruasid'][$key][$keyruas] = (isset($ruasid[$key][$keyruas])) ? $ruasid[$key][$keyruas] : '';
                       /* if($key == '336' || $key == '337' || $key == '338')
                        {*/
                            $taglist['inputvalue'][$key][$keyruas] .= '$'.$keyruas2.' '.$valueruas2;
                        /*}else{
                            $taglist['inputvalue'][$key][$keyruas] = '$'.$keyruas2.' '.$valueruas2;
                        }*/
                        
                    }
                }
                $taglist['tagname'][$key][$keyruas] = 'Unknown';
            }
        }

        //Repeatable value for indicator
        foreach ($tagsRepeatableInd as $key => $value) {  
            foreach ($value as $keyruas => $valueruas) 
                $taglist['indicator'][$key][$keyruas]  = $valueruas;
        }



       /* echo '<pre>'; print_r($tagsSingleInd); echo '<pre/>';
        die;*/

        


        if($for=='cat')
        {
            $modelwf = Worksheetfields::find()
            ->joinWith(['field'],' fields.ID = worksheetfields.Field_id')
            ->where(
                [
                'Worksheet_id'=>(int)$worksheetid
                ])->all();
        }else{
            

            $modelwf = Worksheetfields::find()
            ->joinWith(['field'],' fields.ID = worksheetfields.Field_id')
            ->where(
                [
                'Worksheet_id'=>(int)$worksheetid, 
                'IsAkuisisi'=>1
                ])->all();
            unset($taglist['inputvalue']['006']);
            unset($taglist['inputvalue']['007']);
            unset($taglist['inputvalue']['008']);

        }

        if (array_key_exists('tagname', $taglist)) 
        {
            foreach ($taglist['tagname'] as $key => $value) {
                $tagdesc = Fields::find()
                ->where(['Tag'=>$key])
                ->one();
                unset($taglist['tagname'][$key]);
                $taglist['tagname'][$key] = $tagdesc->Name;
            }
        }

        foreach ($modelwf as $data) {
            //jika form rda tapi tag 260 maka di skip
            if($rda == '1' && $data->field->Tag == '260')
            {
                continue;
            }
            //jika form non rda tapi tag 264,336,337,338,246,740,542 ada maka di skip
            if($rda == '0' && (
                $data->field->Tag == '264' || 
                $data->field->Tag == '336' || 
                $data->field->Tag == '337' || 
                $data->field->Tag == '338' || 
                $data->field->Tag == '246' || 
                $data->field->Tag == '740' || 
                $data->field->Tag == '542'
                ))
            {
                continue;
            }

            if (array_key_exists('inputvalue', $taglist)) 
            {
                //jika ketika mode edit input varible dari database belum ada di array taglist maka ditambahkan defualt $a
                if (!array_key_exists($data->field->Tag, $taglist['inputvalue'])) {
                    if($data->field->Fixed)
                    {
                        $taglist['inputvalue'][$data->field->Tag] = '';
                    }else{
                        if($data->field->Tag == '336')
                        {
                            $extraValueRDA = '$2 rdacontent';
                        }
                        else if($data->field->Tag == '337')
                        {
                            $extraValueRDA = '$2 rdamedia';
                        }
                        elseif($data->field->Tag == '338')
                        {
                            $extraValueRDA = '$2 rdacarrier';
                        }else{
                            $extraValueRDA ='';
                        }

                        $taglist['inputvalue'][$data->field->Tag] = '$a '.$extraValueRDA;
                    }
                    $taglist['tagid'][$data->field->Tag] = $data->field->ID;
                    $taglist['tagname'][$data->field->Tag] = $data->field->Name;
                    $taglist['tagmandatory'][$data->field->Tag] = $data->field->Mandatory;
                    $taglist['taglength'][$data->field->Tag] = $data->field->Length;
                    $taglist['tagenabled'][$data->field->Tag] = $data->field->Enabled;
                    $taglist['tagiscustomable'][$data->field->Tag] = $data->field->IsCustomable;
                    $taglist['tagfixed'][$data->field->Tag] = $data->field->Fixed;
                    $taglist['tagrepeatable'][$data->field->Tag] = $data->field->Repeatable;
                    if($data->field->Repeatable)
                    {
                        $taglist['indicator'][$data->field->Tag][0] = array('ind1'=>'#','ind2'=>'#');
                    }else{
                        $taglist['indicator'][$data->field->Tag] = array('ind1'=>'#','ind2'=>'#');  
                    }
                    
                }else{
                    $taglist['tagid'][$data->field->Tag] = $data->field->ID;
                    $taglist['tagmandatory'][$data->field->Tag] = $data->field->Mandatory;
                    $taglist['taglength'][$data->field->Tag] = $data->field->Length;
                    $taglist['tagenabled'][$data->field->Tag] = $data->field->Enabled;
                    $taglist['tagiscustomable'][$data->field->Tag] = $data->field->IsCustomable;
                    $taglist['tagfixed'][$data->field->Tag] = $data->field->Fixed;
                    $taglist['tagrepeatable'][$data->field->Tag] = $data->field->Repeatable;
                }
            }else{
                if($data->field->Fixed)
                {
                    $taglist['inputvalue'][$data->field->Tag] = '';
                }else{
                    if($data->field->Tag == '336')
                    {
                        $extraValueRDA = '$2 rdacontent';
                    }
                    else if($data->field->Tag == '337')
                    {
                        $extraValueRDA = '$2 rdamedia';
                    }
                    elseif($data->field->Tag == '338')
                    {
                        $extraValueRDA = '$2 rdacarrier';
                    }else{
                        $extraValueRDA ='';
                    }
                    
                    $taglist['inputvalue'][$data->field->Tag] = '$a '.$extraValueRDA;
                }
                
                $taglist['tagid'][$data->field->Tag] = $data->field->ID;
                $taglist['tagname'][$data->field->Tag] = $data->field->Name;
                $taglist['tagmandatory'][$data->field->Tag] = $data->field->Mandatory;
                $taglist['taglength'][$data->field->Tag] = $data->field->Length;
                $taglist['tagenabled'][$data->field->Tag] = $data->field->Enabled;
                $taglist['tagiscustomable'][$data->field->Tag] = $data->field->IsCustomable;
                $taglist['tagfixed'][$data->field->Tag] = $data->field->Fixed;
                $taglist['tagrepeatable'][$data->field->Tag] = $data->field->Repeatable;
                if($data->field->Repeatable)
                {
                    $taglist['indicator'][$data->field->Tag][0] = array('ind1'=>'#','ind2'=>'#');
                }else{
                    $taglist['indicator'][$data->field->Tag] = array('ind1'=>'#','ind2'=>'#');  
                }
            }
            //simpan varible tag dari database
            //$taglistDb['inputvalue'][$data->field->Tag] = '$a ';
            
            
        }

        //handle when entry collection, when worksheetfields TAG is less than taglist(form entry simple)
        foreach ($taglist['inputvalue'] as $key => $value) {
           if(count($taglist['tagid']) > 0)
            {
               if (!array_key_exists($key, $taglist['tagid'])) {
                    $data =  Fields::getByTag($key);
                    $taglist['tagid'][$key] = $data->ID;
                    $taglist['tagmandatory'][$key] = $data->Mandatory;
                    $taglist['taglength'][$key] = $data->Length;
                    $taglist['tagenabled'][$key] = $data->Enabled;
                    $taglist['tagiscustomable'][$key] = $data->IsCustomable;
                    $taglist['tagfixed'][$key] = $data->Fixed;
                    $taglist['tagrepeatable'][$key] = $data->Repeatable;
               } 
            }else{
                $data =  Fields::getByTag($key);
                $taglist['tagid'][$key] = $data->ID;
                $taglist['tagmandatory'][$key] = $data->Mandatory;
                $taglist['taglength'][$key] = $data->Length;
                $taglist['tagenabled'][$key] = $data->Enabled;
                $taglist['tagiscustomable'][$key] = $data->IsCustomable;
                $taglist['tagfixed'][$key] = $data->Fixed;
                $taglist['tagrepeatable'][$key] = $data->Repeatable;
            }
        }


        return $taglist;

    }


    /**
     * [fungsi untuk merender form entri berdasarkan jenis bahan]
     * @param  intiger $id  [id jenis bahan]
     * @param  string $for [cat,coll]
     * @param  integer $rda [status rda]
     * @return mixed
     */
    public function actionEntryBibByWorksheet($id,$for,$rda)
    {
        //load setting form entri
        $modeluser = Users::findOne((int)Yii::$app->user->identity->ID);
        $isAdvanceEntryCollection = (int)$modeluser->IsAdvanceEntryCollection;
        $isAdvanceEntryCatalog = (int)$modeluser->IsAdvanceEntryCatalog;

        //ambil status serial/bukan
        $isSerial = (int)Worksheets::findOne($id)->ISSERIAL;
        if($for == 'cat')
        {
            $isAdvanceEntry=$isAdvanceEntryCatalog;
        }
        else
        {
            $isAdvanceEntry=$isAdvanceEntryCollection;
        }

        //jika form advance
        if((int)$isAdvanceEntry == 1)
        {
            $taglist = array();
            $taglistDb = array();
            $model = new Collections;
            $modelbib = new CollectionBiblio;
            if (Yii::$app->request->isAjax) 
            {
                $post = Yii::$app->request->post();
                if(!empty($post))
                {
                    $taglist = $this->actionCreateTaglistAdvance($model,$modelbib,$post,false);
                    \Yii::$app->session['taglist'] = $taglist;
                }
            }
            $taglist = $this->actionCreateTaglistClean($taglist,$id,$for,$rda);
            /*echo '<pre>'; print_r($taglist); echo '</pre>'; die;*/
            return $this->renderAjax('_entryBibliografiAdvance', [
                'for'=> $for,
                'rda'=> $rda,
                'worksheetid' => (int)$id,
                'isSerial'=> $isSerial,
                'taglist' => $taglist, 
                'model' => $model, 
                'isAdvanceEntry' => (int)$isAdvanceEntry
                ]);
        }else{
            //foreach taglist untuk mendapatkan value current input
            $modelbib = new CollectionBiblio;
            $model = new Collections;
            $rulesform['008_Bahasa'] = (int)Worksheetfields::getStatusTag008(5,(int)$id,29);
            $rulesform['008_KaryaTulis'] = (int)Worksheetfields::getStatusTag008(17,(int)$id,29);
            $rulesform['008_KelompokSasaran'] = (int)Worksheetfields::getStatusTag008(2,(int)$id,29);
            $listvar['publication']=array();
            $listvar['author']=array();
            $listvar['authortype']= array();
            $listvar['isbn']= array();
            $listvar['issn']= array();
            $listvar['subject']= array();
            $listvar['subjecttag']=array();
            $listvar['subjectind']= array();
            $listvar['callnumber']= array();
            $listvar['note']= array();
            $listvar['notetag']= array();
            $listvar['titlevarian']= array();
            $listvar['titleoriginal']= array();
            $listvar['input_required']=array();
            $listvar['input_required'] = $this->actionValidateRequiredSimpleForm($rda);


            $modelbib->AuthorType=0;
            $modelbib->AuthorAddedType[0]=0;
            $modelbib->NoteTag[0]='520';
            $modelbib->SubjectInd='#';

            if (Yii::$app->request->isAjax) 
            {
                $post = Yii::$app->request->post();
                if(!empty($post))
                {
                    $modelbib->load($post);
                    $model->load($post);
                    $dataPublication = [];
                    foreach ($modelbib->PublishLocation as $key => $value) {
                       $dataPublication[$key] = ['publishlocation'=>$value] + ['publisher'=>$modelbib->Publisher[$key]] + ['publishyear'=>$modelbib->PublishYear[$key]];
                    }
                    $listvar['publication']=$dataPublication;
                    $listvar['author'] = $modelbib->AuthorAdded;
                    $listvar['authortype'] = $modelbib->AuthorAddedType;
                    $listvar['isbn'] = $modelbib->ISBN;
                    $listvar['issn'] = $modelbib->ISSN;
                    $listvar['note'] = $modelbib->Note;
                    $listvar['notetag'] = $modelbib->NoteTag;
                    $listvar['titlevarian']= $modelbib->TitleVarian;
                    $listvar['titleoriginal']= $modelbib->TitleOriginal;
                    $listvar['subject'] = $modelbib->Subject;
                    $listvar['subjecttag'] = $modelbib->SubjectTag;
                    $listvar['subjectind'] = $modelbib->SubjectInd;
                    $listvar['callnumber'] = $modelbib->CallNumber;
                    $taglist = array();
                    $taglist = $this->actionCreateTaglistClean($taglist,1,$for,$rda);
                    $taglist['ruasid']=$post['Ruasid'];
                }
            }
            return $this->renderAjax('_entryBibliografiSimple', [
                'for'=> $for,
                'rda'=> $rda,
                'worksheetid' => (int)$id,
                'isSerial'=> $isSerial,
                'modelbib' => $modelbib, 
                'model' => $model, 
                'listvar'=>$listvar,
                'isAdvanceEntry' => (int)$isAdvanceEntry,
                'rulesform'=>$rulesform,
                'taglist'=>$taglist
                ]);
        }
    }

    /**
     * [fungsi membuat array taglist dari catalog id]
     * @param  string $CatalogId    [id katalog]
     * @param  integer $Worksheet_id [jenis bahan]
     * @param  string $for          [cat,coll]
     * @param  array &$taglist     [output taglist]
     * @param  integer $rda          [status rda]
     * @return mixed
     */
    public function actionCreateTaglistFromCatalog($CatalogId,$Worksheet_id,$for,&$taglist,$rda)
    {
        //$modelcatruas = CatalogRuas::find()->where(['and','CatalogId = '.$CatalogId,['not in','Tag',array('001','035','990')]])->all();
        if($for=='cat')
        {
            $tagException = ['990'];
        }else{
            //jika form koleksi, maka tag dibawah ini tidak tampil
            $tagException = ['001','005','006','007','008','990'];
        }
        $modelcat = Catalogs::findOne($CatalogId);
        $rda=$modelcat->IsRDA;
        $modelcatruas = CatalogRuas::find()->where(['and','CatalogId = '.$CatalogId,['not in','Tag',$tagException]])->orderby('Sequence ASC')->all();
        //echo '<pre>'; print_r($modelcatruasdatas->models); echo '</pre>';die;
        foreach ($modelcatruas as $data) {
            $RuasID = $data->ID;
            $Tag = $data->Tag;
            $Indicator1 = $data->Indicator1;
            $Indicator2 = $data->Indicator2;
            $Value = $data->Value;
            if($for=='coll' && $Tag=='863')
            {
                $Value='$a ';
            }
            $modelcatsubruas = CatalogSubruas::find()->where(['RuasID'=>$RuasID])->all();

            $modelfields = Fields::find()->where(['Tag'=>$Tag])->one();

            if($modelfields->Repeatable)
            {
                $taglist['ruasid'][$Tag][] = $RuasID;
                $taglist['inputvalue'][$Tag][] = $Value;
                $taglist['tagname'][$Tag] = $modelfields->Name;
                $taglist['indicator'][$Tag][] = array('ind1'=>$Indicator1,'ind2'=>$Indicator2);
            }
            else
            {
                $taglist['ruasid'][$Tag] = $RuasID;
                $taglist['inputvalue'][$Tag] = $Value;
                $taglist['tagname'][$Tag] = $modelfields->Name;
                $taglist['indicator'][$Tag] = array('ind1'=>$Indicator1,'ind2'=>$Indicator2);
            }

            
        }

        $worksheetid = (int)$Worksheet_id;
        if($for=='cat')
        {
            $modelwf = Worksheetfields::find()
            ->joinWith(['field'],' fields.ID = worksheetfields.Field_id')
            ->where(
                [
                'Worksheet_id'=>(int)$worksheetid
                ])->all();
        }else{
            

            $modelwf = Worksheetfields::find()
            ->joinWith(['field'],' fields.ID = worksheetfields.Field_id')
            ->where(
                [
                'Worksheet_id'=>(int)$worksheetid, 
                'IsAkuisisi'=>1
                ])->all();
            /*unset($taglist['inputvalue']['006']);
            unset($taglist['inputvalue']['007']);
            unset($taglist['inputvalue']['008']);*/

        }

        if (array_key_exists('tagname', $taglist)) 
        {
            foreach ($taglist['tagname'] as $key => $value) {
                $tagdesc = Fields::find()
                ->where(['Tag'=>$key])
                ->one();
                unset($taglist['tagname'][$key]);
                $taglist['tagname'][$key] = $tagdesc->Name;
            }
        }

        foreach ($modelwf as $data) {
            //jika form rda tapi tag 260 maka di skip
            if($rda == '1' && $data->field->Tag == '260')
            {
                continue;
            }
            //jika form non rda tapi tag 264,336,337,338,246,740,542 ada maka di skip
            if($rda == '0' && (
                $data->field->Tag == '264' || 
                $data->field->Tag == '336' || 
                $data->field->Tag == '337' || 
                $data->field->Tag == '338' || 
                $data->field->Tag == '246' || 
                $data->field->Tag == '740' || 
                $data->field->Tag == '542'
                ))
            {
                continue;
            }

            if (array_key_exists('inputvalue', $taglist)) 
            {
                //jika ketika mode edit input varible dari database belum ada di array taglist maka ditambahkan defualt $a
                if (!array_key_exists($data->field->Tag, $taglist['inputvalue'])) {
                    if($data->field->Fixed)
                    {
                        $taglist['inputvalue'][$data->field->Tag] = '';
                    }else{
                        if($data->field->Tag == '336')
                        {
                            $extraValueRDA = '$2 rdacontent';
                        }
                        else if($data->field->Tag == '337')
                        {
                            $extraValueRDA = '$2 rdamedia';
                        }
                        elseif($data->field->Tag == '338')
                        {
                            $extraValueRDA = '$2 rdacarrier';
                        }else{
                            $extraValueRDA ='';
                        }
                        $taglist['inputvalue'][$data->field->Tag] = '$a '.$extraValueRDA;
                    }
                    $taglist['tagid'][$data->field->Tag] = $data->field->ID;
                    $taglist['tagname'][$data->field->Tag] = $data->field->Name;
                    $taglist['tagmandatory'][$data->field->Tag] = $data->field->Mandatory;
                    $taglist['taglength'][$data->field->Tag] = $data->field->Length;
                    $taglist['tagenabled'][$data->field->Tag] = $data->field->Enabled;
                    $taglist['tagiscustomable'][$data->field->Tag] = $data->field->IsCustomable;
                    $taglist['tagfixed'][$data->field->Tag] = $data->field->Fixed;
                    $taglist['tagrepeatable'][$data->field->Tag] = $data->field->Repeatable;
                    if($data->field->Repeatable)
                    {
                        $taglist['indicator'][$data->field->Tag][0] = array('ind1'=>'#','ind2'=>'#');
                    }else{
                        $taglist['indicator'][$data->field->Tag] = array('ind1'=>'#','ind2'=>'#');  
                    }
                    
                }else{
                    $taglist['tagid'][$data->field->Tag] = $data->field->ID;
                    $taglist['tagmandatory'][$data->field->Tag] = $data->field->Mandatory;
                    $taglist['taglength'][$data->field->Tag] = $data->field->Length;
                    $taglist['tagenabled'][$data->field->Tag] = $data->field->Enabled;
                    $taglist['tagiscustomable'][$data->field->Tag] = $data->field->IsCustomable;
                    $taglist['tagfixed'][$data->field->Tag] = $data->field->Fixed;
                    $taglist['tagrepeatable'][$data->field->Tag] = $data->field->Repeatable;
                }
            }else{
                if($data->field->Fixed)
                {
                    $taglist['inputvalue'][$data->field->Tag] = '';
                }else{
                    if($data->field->Tag == '336')
                    {
                        $extraValueRDA = '$2 rdacontent';
                    }
                    else if($data->field->Tag == '337')
                    {
                        $extraValueRDA = '$2 rdamedia';
                    }
                    elseif($data->field->Tag == '338')
                    {
                        $extraValueRDA = '$2 rdacarrier';
                    }else{
                        $extraValueRDA ='';
                    }

                    $taglist['inputvalue'][$data->field->Tag] = '$a '.$extraValueRDA;
                }
                
                $taglist['tagid'][$data->field->Tag] = $data->field->ID;
                $taglist['tagname'][$data->field->Tag] = $data->field->Name;
                $taglist['tagmandatory'][$data->field->Tag] = $data->field->Mandatory;
                $taglist['taglength'][$data->field->Tag] = $data->field->Length;
                $taglist['tagenabled'][$data->field->Tag] = $data->field->Enabled;
                $taglist['tagiscustomable'][$data->field->Tag] = $data->field->IsCustomable;
                $taglist['tagfixed'][$data->field->Tag] = $data->field->Fixed;
                $taglist['tagrepeatable'][$data->field->Tag] = $data->field->Repeatable;
                if($data->field->Repeatable)
                {
                    $taglist['indicator'][$data->field->Tag][0] = array('ind1'=>'#','ind2'=>'#');
                }else{
                    $taglist['indicator'][$data->field->Tag] = array('ind1'=>'#','ind2'=>'#');  
                }
            }


           /* if($rda == '0' && $data->field->Tag == '260' && $modelcat->IsRDA == 1)
            {
                unset($taglist['inputvalue'][$data->field->Tag]);
                unset($taglist['tagid'][$data->field->Tag]);
                unset($taglist['tagname'][$data->field->Tag]);
                unset($taglist['tagmandatory'][$data->field->Tag]);
                unset($taglist['taglength'][$data->field->Tag]);
                unset($taglist['tagenabled'][$data->field->Tag]);
                unset($taglist['tagiscustomable'][$data->field->Tag]);
                unset($taglist['tagfixed'][$data->field->Tag]);
                unset($taglist['tagrepeatable'][$data->field->Tag]);
                unset($taglist['indicator'][$data->field->Tag][]0);

            }

            if($rda == '1' && ($data->field->Tag == '264' || $data->field->Tag == '336' || $data->field->Tag == '337' || $data->field->Tag == '338') && $modelcat->IsRDA == 0)
            {
                unset($taglist['inputvalue'][$data->field->Tag]);
                unset($taglist['tagid'][$data->field->Tag]);
                unset($taglist['tagname'][$data->field->Tag]);
                unset($taglist['tagmandatory'][$data->field->Tag]);
                unset($taglist['taglength'][$data->field->Tag]);
                unset($taglist['tagenabled'][$data->field->Tag]);
                unset($taglist['tagiscustomable'][$data->field->Tag]);
                unset($taglist['tagfixed'][$data->field->Tag]);
                unset($taglist['tagrepeatable'][$data->field->Tag]);
                unset($taglist['indicator'][$data->field->Tag][]0);

            }*/
            //simpan varible tag dari database
            //$taglistDb['inputvalue'][$data->field->Tag] = '$a ';
            
            
        }
        //handle when entry collection, when worksheetfields TAG is less than taglist(form entry simple)
        foreach ($taglist['inputvalue'] as $key => $value) {
            if(count($taglist['tagid']) > 0)
            {
               if (!array_key_exists($key, $taglist['tagid'])) {
                    $data =  Fields::getByTag($key);
                    $taglist['tagid'][$key] = $data->ID;
                    $taglist['tagmandatory'][$key] = $data->Mandatory;
                    $taglist['taglength'][$key] = $data->Length;
                    $taglist['tagenabled'][$key] = $data->Enabled;
                    $taglist['tagiscustomable'][$key] = $data->IsCustomable;
                    $taglist['tagfixed'][$key] = $data->Fixed;
                    $taglist['tagrepeatable'][$key] = $data->Repeatable;
               } 
            }else{
                $data =  Fields::getByTag($key);
                $taglist['tagid'][$key] = $data->ID;
                $taglist['tagmandatory'][$key] = $data->Mandatory;
                $taglist['taglength'][$key] = $data->Length;
                $taglist['tagenabled'][$key] = $data->Enabled;
                $taglist['tagiscustomable'][$key] = $data->IsCustomable;
                $taglist['tagfixed'][$key] = $data->Fixed;
                $taglist['tagrepeatable'][$key] = $data->Repeatable;
            }
           
        }
    }

    /**
     * [fungsi untuk membuat array modelbib untuk form simple dari catalog id]
     * @param  integer $CatalogId [id katalog]
     * @param  model &$modelbib [output model collectionbiblio]
     * @return array $modelbib
     */
    public function actionCreateModelBibFromCatalog($CatalogId,&$modelbib)
    {
        $modelcatruas = CatalogRuas::find()->where(['and','CatalogId = '.$CatalogId,['not in','Tag',array('001','035','990')]])->orderby('Sequence ASC')->all();
            // echo '<pre>'; print_r($modelcatruas); echo '</pre>';die;
            foreach ($modelcatruas as $data) {
                $RuasID = $data->ID;
                $Tag = $data->Tag;
                $Indicator1 = $data->Indicator1;
                $Indicator2 = $data->Indicator2;
                $Value = $data->Value;
                if($Tag == '008')
                {
                    //20160229################d#########d#eng##
                    //160616################d##########d#ind##
                    //160610###########################|#ara##
                    //echo $Value; die;
                    if(strlen($Value) >= 40)
                    {
                        $modelbib->KelompokSasaran=substr($Value,22,1);
                        $modelbib->BentukKaryaTulis=substr($Value,33,1);
                        $modelbib->Bahasa=substr($Value,35,3); 
                    }
                }
                $modelcatsubruas = CatalogSubruas::find()->where(['RuasID'=>$RuasID])->all();

                $modelfields = Fields::find()->where(['Tag'=>$Tag])->one();

                if($modelfields->Repeatable)
                {
                    if($Tag=='650' || $Tag=='600' || $Tag=='651')
                    {
                        $modelbib->SubjectInd[] = $Indicator1;
                    }
                }

                if($Tag=='245')
                {
                    if(is_int((int)$Indicator2))
                    {
                        $modelbib->KataSandang = (int)$Indicator2 - 1;
                    }
                }

                foreach ($modelcatsubruas as $datasub) {
                    switch ($Tag) {
                        case '245':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->Title = trim($datasub->Value);
                            break;
                            case 'b':
                            $modelbib->TitleAdded = trim($datasub->Value);
                            break;
                            case 'c':
                            $modelbib->PenanggungJawab = trim($datasub->Value);
                            break;
                        }
                        break;
                        case '246':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->TitleVarian[] = trim($datasub->Value);
                            break;
                        }
                        break;
                        
                        case '255':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->DataMatematis = trim($datasub->Value);
                            break;
                        }
                        break;
                        case '538':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->CatatanRincianSistem = trim($datasub->Value);
                            break;
                        }
                        break;  
                        case '740':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->TitleOriginal[] = trim($datasub->Value);
                            break;
                        }
                        break;
                        case '100':
                        case '110':
                        case '111':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->Author=trim($datasub->Value);
                            $modelbib->AuthorType=$Indicator1;
                            break;
                            case 'e':
                            $modelbib->AuthorRelatorTerm=trim($datasub->Value);
                            break;
                        }
                        break;
                        case '700':
                        case '710':
                        case '711':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->AuthorAdded[]=trim($datasub->Value);
                            if($Tag == '711')
                                $modelbib->AuthorAddedType[]='##';
                            else
                                $modelbib->AuthorAddedType[]=$Indicator1;

                            break;
                            case 'e':
                            $modelbib->AuthorAddedRelatorTerm[]=trim($datasub->Value);
                            break;
                        }
                        break;
                        case '856':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->LokasiDaringAdded[]=trim($datasub->Value);
                            $modelbib->LokasiDaringAddedType[]=$Indicator1;
                            break;
                        }
                        break;
                        case '247':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->JudulSebelumAdded[]=trim($datasub->Value);
                            $modelbib->JudulSebelumAddedType[]=$Indicator1;
                            break;
                        }
                        break;
                        case '260':
                        case '264':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->PublishLocation[] = trim($datasub->Value);
                            break;
                            case 'b':
                            $modelbib->Publisher[] = trim($datasub->Value);
                            break;
                            case 'c':
                            $modelbib->PublishYear[] = trim($datasub->Value);
                            break;
                        }
                        break;
                        case '336':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->JenisIsi = trim($datasub->Value);
                            break;
                        }
                        break; 
                        case '337':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->JenisMedia = trim($datasub->Value);
                            break;
                        }
                        break; 
                        case '338':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->JenisCarrier = trim($datasub->Value);
                            break;
                        }
                        break; 
                        case '250':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->Edition = trim($datasub->Value);
                            break;
                        }
                        break; 
                        case '082':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->Class = trim($datasub->Value);
                            break;
                        }
                        break;
                        case '300':
                        switch ($datasub->SubRuas) {
                            case 'a':
                                $modelbib->JumlahHalaman = trim($datasub->Value);
                            break;
                            case 'b':
                                $modelbib->KeteranganIllustrasi = trim($datasub->Value);
                            break;
                            case 'c':
                                $modelbib->Dimensi = trim($datasub->Value);
                            break;
                        }
                        break;
                        case '310':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->FrekuensiSaatIni = trim($datasub->Value);
                            break;
                        }
                        break;
                        case '321':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->FrekuensiSebelumAdded[]=trim($datasub->Value);
                            $modelbib->FrekuensiSebelumAddedType[]=$Indicator1;
                            break;
                        }
                        break; 
                        case '084':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->CallNumber[] = trim($datasub->Value);
                            break;
                        }
                        break;
                        case '020':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->ISBN[] = trim($datasub->Value);
                            break;
                        }
                        break;
                        case '022':
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->ISSN[] = trim($datasub->Value);
                            break;
                        }
                        break; 
                        case '650':
                        case '600':
                        case '651':
                        $modelbib->SubjectTag[] = $Tag;
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->Subject[] = trim($datasub->Value);
                            break;
                        }
                        break;   
                        case '500':
                        case '502':
                        case '504':
                        case '505':
                        case '520':
                        case '542':
                        $modelbib->NoteTag[] = $Tag;
                        switch ($datasub->SubRuas) {
                            case 'a':
                            $modelbib->Note[] = trim($datasub->Value);
                            break;
                        }
                        break;    
                        
                        default:
                            # code...
                        break;
                    }
                }
            }
            if(!$modelbib->AuthorType)
                $modelbib->AuthorType=0;
            if(!$modelbib->AuthorAddedType)
                $modelbib->AuthorAddedType[0]=0;
            if(!$modelbib->SubjectTag)
                $modelbib->SubjectTag[0]='650';
            if(!$modelbib->SubjectInd)
                $modelbib->SubjectInd[0]='#';
            if(!$modelbib->NoteTag)
                $modelbib->NoteTag[0]='520';
    }
    
    /**
     * [fungsi untuk mapping model collection dan collectionbiblio dari array taglist untuk keperluan salin katalog k form simple]
     * @param  model $model    [model collection]
     * @param  model $modelbib [model collection biblio]
     * @param  array $taglist  [array taglist]
     * @return mixed
     */
    public function actionCreateTaglistToBiblio($model,$modelbib,$taglist)
    {
        foreach ($taglist as $key => $value) {
            switch ($key) {

                //set ruas value 
                case 'inputvalue':
                    foreach ($value as $tagcode => $tagvalue) {
                        
                        //tag repeatable
                        if(is_array($tagvalue))
                        {
                            foreach ($tagvalue as $indextag => $tagvaluerepeatable) {
                                /*echo $tagcode.' '.$tagvaluerepeatable.'<br>';*/
                                $tagvalue=trim($tagvaluerepeatable);
                                $tagvaluesub = explode("$",substr($tagvalue,1,strlen($tagvalue)));
                                for ($i=0; $i < count($tagvaluesub) ; $i++) { 

                                    $subruascode=substr($tagvaluesub[$i],0,1);
                                    $subruasvalue=substr($tagvaluesub[$i],1,strlen($tagvaluesub[$i]));
                                    if(trim($subruasvalue) != '')
                                    {
                                        $this->actionCreateTagSimple($model,$modelbib,$tagcode,$subruascode,$subruasvalue);
                                    }
                                }
                                 
                            }
                        //tag single
                        }else{
                            /*echo $tagcode.' '.$tagvalue.'<br>';*/
                            if((int)$tagcode < 10)
                            {
                                $tagvalue= ltrim($tagvalue);
                                    switch ($tagcode) {
                                        case '008':
                                        if(strlen($tagvalue) >= 40 )
                                        {
                                            //910711s1990    io a     b   f000 0 ind 
                                            //160530c20012019ijib###bcb###a111#ebchiod                     
                                            $language = substr($tagvalue,35,3);

                                            //di LOC
                                            $bentukkaryatulis = substr($tagvalue,34,1);
                                            $kelompoksasaran = substr($tagvalue,24,1);


                                            //yg bner
                                            /*$bentukkaryatulis = substr($tagvalue,33,1);
                                            $kelompoksasaran = substr($tagvalue,22,1);*/

                                            $modelbib->Bahasa = $language;
                                            $modelbib->BentukKaryaTulis = $bentukkaryatulis;
                                            $modelbib->KelompokSasaran = $kelompoksasaran;
                                        }
                                        break;
                                    }
                            }else{
                                $tagvalue=trim($tagvalue);
                                $tagvaluesub = explode("$",substr($tagvalue,1,strlen($tagvalue)));
                                for ($i=0; $i < count($tagvaluesub) ; $i++) { 

                                    $subruascode=substr($tagvaluesub[$i],0,1);
                                    $subruasvalue=substr($tagvaluesub[$i],1,strlen($tagvaluesub[$i]));
                                    if(trim($subruasvalue) != '')
                                    {
                                       $this->actionCreateTagSimple($model,$modelbib,$tagcode,$subruascode,$subruasvalue);
                                    }
                                }
                            }
                        }
                    }
                    break;

                //set indicator1 value 
                case 'indicator':
                    foreach ($value as $tagcode => $indvalue) {
                        
                        //tag repeatable
                        if(!array_key_exists('ind1',$indvalue))
                        {
                            foreach ($indvalue as $indextag => $ind1valuerepeatable) {

                                foreach ($ind1valuerepeatable as $indname => $indvaluefinal) {
                                    if($indvaluefinal!='')
                                    {
                                        if($indname=='ind1')
                                        {

                                           switch ($tagcode) {
                                                case '100':
                                                /*case '110':
                                                case '111':*/
                                                    $modelbib->AuthorType=$indvaluefinal;
                                                break;
                                                case '700':
                                                case '710':
                                                case '711':
                                                    if($tagcode == '711')
                                                        $modelbib->AuthorAddedType[]='##';
                                                    else
                                                        $modelbib->AuthorAddedType[]=$indvaluefinal;
                                                break;
                                                case '650':
                                                case '600':
                                                case '651':
                                                        $modelbib->SubjectInd[]=$indvaluefinal;
                                                break;

                                            } 
                                        }
                                        else if($indname =='ind2')
                                        {
                                            if($tagcode == 245 && $indvaluefinal != '#')
                                            {
                                                $modelbib->KataSandang = (int)$indvaluefinal-1;
                                            }
                                        }
                                    }
                                    
                                }
                            }
                        //tag single
                        }else{
                            /*echo $tagcode.' '.$ind1value.'<br>';*/
                            foreach ($indvalue as $indname => $indvaluefinal) {
                                if($indvaluefinal!='')
                                {
                                    if($indname=='ind1')
                                    {

                                       switch ($tagcode) {
                                            case '100':
                                            /*case '110':
                                            case '111':*/
                                                $modelbib->AuthorType=$indvaluefinal;
                                            break;
                                            case '700':
                                            case '710':
                                            case '711':
                                                if($tagcode == '711')
                                                    $modelbib->AuthorAddedType[]='##';
                                                else
                                                    $modelbib->AuthorAddedType[]=$indvaluefinal;
                                            break;
                                            case '650':
                                            case '600':
                                            case '651':
                                                    $modelbib->SubjectInd[]=$indvaluefinal;
                                            break;

                                        } 
                                    }
                                    else if($indname =='ind2')
                                    {
                                        if($tagcode == 245 && $indvaluefinal != '#')
                                        {
                                            $modelbib->KataSandang = (int)$indvaluefinal-1;
                                        }
                                    }
                                }
                                
                            }
                        }
                            
                    }
                    break;
                
            }
        }
    }

    /**
     * [fungsi untuk membuat model collection & collection biblio dari tag,subruas code dan subruas value]
     * @param  model $model         [model collection]
     * @param  model $modelbib      [model collection biblio]
     * @param  string $tagcode      [tag]
     * @param  string $subruascode  [subruas code]
     * @param  string $subruasvalue [subruas value]
     * @return mixed
     */
    public function actionCreateTagSimple($model,$modelbib,$tagcode,$subruascode,$subruasvalue)
    {
        // echo'<pre>';print_r($subruasvalue);die;
        switch ($tagcode) {
            case '245':
            switch ($subruascode) {
                case 'a':
                $modelbib->Title = trim($subruasvalue);
                break;
                case 'b':
                $modelbib->TitleAdded = trim($subruasvalue);
                break;
                case 'c':
                $modelbib->PenanggungJawab = trim($subruasvalue);
                break;
            }
            break;
            case '246':
            switch ($subruascode) {
                case 'a':
                $modelbib->TitleVarian[] = trim($subruasvalue);
                break;
            }
            break;
            case '240':
            switch ($subruascode) {
                case 'a':
                $modelbib->JudulSeragam = trim($subruasvalue);
                break;
            }
            break;
            case '255':
            switch ($subruascode) {
                case 'a':
                $modelbib->DataMatematis = trim($subruasvalue);
                break;
            }
            break;
            case '538':
            switch ($subruascode) {
                case 'a':
                $modelbib->CatatanRincianSistem = trim($subruasvalue);
                break;
            }
            break;
            case '247':
            switch ($subruascode) {
                case 'a':
                $modelbib->JudulSebelumAdded[] = trim($subruasvalue);
                break;
            }
            break;
            case '740':
            switch ($subruascode) {
                case 'a':
                $modelbib->TitleOriginal[] = trim($subruasvalue);
                break;
            }
            break;
            case '856':
            switch ($subruascode) {
                case 'a':
                $modelbib->LokasiDaringAdded[] = trim($subruasvalue);
                break;
            }
            break;
            case '247':
            switch ($subruascode) {
                case 'a':
                $modelbib->JudulSebelumAdded[] = trim($subruasvalue);
                break;
            }
            break;
            case '100':
            //case '110':
            //case '111':
            switch ($subruascode) {
                case 'a':
                $modelbib->Author=trim($subruasvalue);
                break;
                case 'a':
                $modelbib->AuthorRelatorTerm=trim($subruasvalue);
                break;
            }
            break;
            case '700':
            case '710':
            case '711':
            switch ($subruascode) {
                case 'a':
                $modelbib->AuthorAdded[]=trim($subruasvalue);
                break;
                case 'e':
                $modelbib->AuthorAddedRelatorTerm[]=trim($subruasvalue);
                break;
            }
            break;
            case '260':
            case '264':
            switch ($subruascode) {
                case 'a':
                $modelbib->PublishLocation[] = $subruasvalue;
                break;
                case 'b':
                $modelbib->Publisher[] = $subruasvalue;
                break;
                case 'c':
                $modelbib->PublishYear[] = $subruasvalue;
                break;
            }
            break;
            case '336':
            switch ($subruascode) {
                case 'a':
                $modelbib->JenisIsi = trim($subruasvalue);
                break;
            }
            break;
            case '337':
            switch ($subruascode) {
                case 'a':
                $modelbib->JenisMedia = trim($subruasvalue);
                break;
            }
            break;
            case '338':
            switch ($subruascode) {
                case 'a':
                $modelbib->JenisCarrier = trim($subruasvalue);
                break;
            }
            break; 
            case '250':
            switch ($subruascode) {
                case 'a':
                $modelbib->Edition = trim($subruasvalue);
                break;
            }
            break; 
            case '082':
            switch ($subruascode) {
                case 'a':
                $modelbib->Class = trim($subruasvalue);
                break;
            }
            break;
            case '300':
            switch ($subruascode) {
                case 'a':
                $modelbib->JumlahHalaman .= trim($subruasvalue);
                break;
                case 'b':
                $modelbib->KeteranganIllustrasi .= trim($subruasvalue);
                break;
                case 'c':
                $modelbib->Dimensi .= trim($subruasvalue);
                break;
            }
            break;
            case '310':
            switch ($subruascode) {
                case 'a':
                $modelbib->FrekuensiSaatIni = trim($subruasvalue);
                break;
            }
            break;
            case '321':
            switch ($subruascode) {
                case 'a':
                $modelbib->FrekuensiSebelumAdded[] = trim($subruasvalue);
                break;
            }
            break;
            case '084':
            switch ($subruascode) {
                case 'a':
                $modelbib->CallNumber[] = trim($subruasvalue);
                break;
            }
            break;
            case '650':
            case '600':
            case '651':
            switch ($subruascode) {
                case 'a':
                $modelbib->SubjectTag[] = $tagcode;
                $modelbib->Subject[] = trim($subruasvalue);
                break;
            }
            break;
            case '020':
            switch ($subruascode) {
                case 'a':
                $modelbib->ISBN[] = trim($subruasvalue);
                break;
            }
            break; 
            case '022':
            switch ($subruascode) {
                case 'a':
                $modelbib->ISSN[] = trim($subruasvalue);
                break;
            }
            break;    
            case '500':
            case '502':
            case '504':
            case '505':
            case '520':
            case '542':
            switch ($subruascode) {
                case 'a':
                $modelbib->NoteTag[] = $tagcode;
                $modelbib->Note[] = trim($subruasvalue);
                break;
            }
            break;   
            case '863':
            switch ($subruascode) {
                case 'a':
                $model->EDISISERIAL = trim($subruasvalue);
                break;
            }
            break;
            default:
                # code...
            break;
        }
    }

    /**
     * [fungsi untuk klik tombol form simple]
     * @param  integer $worksheetid [id jenis bahan]
     * @param  string $for         [cat,coll]
     * @param  integer $rda         [status rda]
     * @return mixed
     */
    public function actionEntrySimple($worksheetid,$for,$rda)
    {
        $modelbib = new CollectionBiblio;
        $model = new Collections;
        $taglist = array();
        $rulesform['008_Bahasa'] = (int)Worksheetfields::getStatusTag008(5,(int)$worksheetid,29);
        $rulesform['008_KaryaTulis'] = (int)Worksheetfields::getStatusTag008(17,(int)$worksheetid,29);
        $rulesform['008_KelompokSasaran'] = (int)Worksheetfields::getStatusTag008(2,(int)$worksheetid,29);
        $isSerial = (int)Worksheets::findOne($worksheetid)->ISSERIAL;

        if (Yii::$app->request->isAjax) 
        {
            $post = Yii::$app->request->post();
            $taglist1 = $this->actionCreateTaglistAdvance($model,$modelbib,$post,true);
            //echo '<pre>'; print_r($modelbib); die;
            $sessionTaglist = \Yii::$app->session['taglist'];
            //compare session taglist dengan taglist input
            if(count($sessionTaglist) > 0)
            {
                $taglist2 = array_intersect($sessionTaglist,$taglist1);
                foreach( $taglist1['inputvalue'] as $key => $value ){
                    //jika ketemu tag 001,005 atau 035, maka di skip
                    if($key == '001' || $key == '005' || $key == '035')
                    {
                        continue;
                    }
                    //seluruh tag input dan tag session dicocokan setiap isinya
                    //jika berbeda maka, value tag session akan diisi oleh value tag input
                    if($taglist2['inputvalue'][$key] != $value)
                    {
                        $taglist2['inputvalue'][$key] = $value;
                    }
                  
                }
                foreach( $taglist1['indicator'] as $key => $value ){
                    //jika ketemu tag 001,005 atau 035, maka di skip
                    if($key == '001' || $key == '005' || $key == '035')
                    {
                        continue;
                    }
                    //seluruh tag input dan tag session dicocokan setiap isinya
                    //jika berbeda maka, value tag session akan diisi oleh value tag input
                    if($taglist2['indicator'][$key] != $value)
                    {
                        $taglist2['indicator'][$key] = $value;
                    }
                  
                }
                $taglist = $taglist2;
            }else{
                $taglist = $taglist1;
            }
            \Yii::$app->session['taglist']= $taglist;

        }

        $this->actionSaveEntryMode(0,$for);
        $listvar['publication']=array();
        $listvar['author']=array();
        $listvar['authortype']= array();
        $listvar['lokasidaring']=array();
        $listvar['lokasidaringtype']= array();
        $listvar['judulsebelum']=array();
        $listvar['judulsebelumtype']= array();
        $listvar['frekuensisebelum']=array();
        $listvar['frekuensisebelumtype']= array();
        $listvar['isbn']= array();
        $listvar['subject']= array();
        $listvar['subjecttag']=array();
        $listvar['subjectind']= array();
        $listvar['callnumber']= array();
        $listvar['note']= array();
        $listvar['notetag']= array();
        $listvar['titlevarian']= array();
        $listvar['titleoriginal']= array();
        $dataPublication = [];
        if($modelbib->PublishLocation)
        {
            foreach ($modelbib->PublishLocation as $key => $value) {
               $dataPublication[$key] = ['publishlocation'=>$value] + ['publisher'=>$modelbib->Publisher[$key]] + ['publishyear'=>$modelbib->PublishYear[$key]];
            }
        }
        $listvar['publication']=$dataPublication;
        $listvar['author'] = $modelbib->AuthorAdded;
        $listvar['authortype'] = $modelbib->AuthorAddedType;
        $listvar['lokasidaring'] = $modelbib->LokasiDaringAdded;
        $listvar['lokasidaringtype'] = $modelbib->LokasiDaringAddedType;
        $listvar['judulsebelum'] = $modelbib->JudulSebelumAdded;
        $listvar['judulsebelumtype'] = $modelbib->JudulSebelumAddedType;
        $listvar['frekuensisebelum'] = $modelbib->FrekuensiSebelumAdded;
        $listvar['frekuensisebelumtype'] = $modelbib->FrekuensiSebelumAddedType;
        $listvar['isbn'] = $modelbib->ISBN;
        $listvar['issn'] = $modelbib->ISSN;
        $listvar['note'] = $modelbib->Note;
        $listvar['notetag'] = $modelbib->NoteTag;
        $listvar['titlevarian']= $modelbib->TitleVarian;
        $listvar['titleoriginal']= $modelbib->TitleOriginal;
        $listvar['subject'] = $modelbib->Subject;
        $listvar['subjecttag'] = $modelbib->SubjectTag;
        $listvar['subjectind'] = $modelbib->SubjectInd;
        $listvar['callnumber'] = $modelbib->CallNumber;
        $listvar['input_required']=array();
        $listvar['input_required'] = $this->actionValidateRequiredSimpleForm($rda);
        return $this->renderAjax('_entryBibliografiSimple', [
            'for'=>$for,
            'rda'=>$rda,
            'worksheetid' => $worksheetid,
            'isSerial'=> $isSerial,
            'modelbib' => $modelbib,
            'model' => $model,
            'taglist' => $taglist,
            'isAdvanceEntry' => 0,
            'listvar'=> $listvar,
            'rulesform'=>$rulesform
            ]);
    }
    
    /**
     * [fungsi untuk klik tombol form advance]
     * @param  integer $worksheetid [id jenis bahan]
     * @param  string $for         [cat,coll]
     * @param  integer $rda         [status rda]
     * @return mixed
     */
    public function actionEntryAdvance($worksheetid,$for,$rda)
    {
        $model = new Collections;
        $modelbib = new CollectionBiblio;
        $taglist = array();
        $isSerial = (int)Worksheets::findOne($worksheetid)->ISSERIAL;
        if (Yii::$app->request->isAjax) 
        {
            $post=Yii::$app->request->post();
            $taglist1 = $this->actionCreateTaglistSimple($model,$modelbib,$post,$for,$worksheetid,$rda);
            //echo '<pre>'; print_r($taglist1); die;
            $sessionTaglist = \Yii::$app->session['taglist'];
            if(count($sessionTaglist) > 0)
            {
                $taglist2 = array_intersect($sessionTaglist,$taglist1);
                foreach( $taglist1['inputvalue'] as $key => $value ){
                    //jika ketemu tag 001,005 atau 035, maka di skip
                    if($key == '001' || $key == '005' || $key == '035')
                    {
                        continue;
                    }
                    //hanya beberapa tag (sesuai dengan desain mapping form simple catalog) input dan tag session dicocokan setiap isinya
                    //jika berbeda maka, value tag session akan diisi oleh value tag input
                    if($key == '245' ||
                        $key == '246' ||
                        $key == '740' ||
                        $key == '100' ||
                        $key == '700' ||
                        $key == '710' ||
                        $key == '711' ||
                        $key == '260' ||
                        $key == '264' ||
                        $key == '300' ||
                        $key == '336' ||
                        $key == '337' ||
                        $key == '338' ||
                        $key == '250' ||
                        $key == '082' ||
                        $key == '084' ||
                        $key == '020' ||
                        $key == '022' ||
                        $key == '650' || 
                        $key == '651' || 
                        $key == '600' || 
                        $key == '500' ||
                        $key == '502' ||
                        $key == '504' ||
                        $key == '505' ||
                        $key == '520' ||
                        $key == '542' ||
                        $key == '008'
                        )
                    {
                        if($taglist2['inputvalue'][$key] != $value)
                        {
                            $taglist2['inputvalue'][$key] = $value;
                        }
                    }
                }
                foreach( $taglist1['indicator'] as $key => $value ){
                    //jika ketemu tag 001,005 atau 035, maka di skip
                    if($key == '001' || $key == '005' || $key == '035')
                    {
                        continue;
                    }
                    //hanya beberapa tag (sesuai dengan desain mapping form simple catalog) input dan tag session dicocokan setiap isinya
                    //jika berbeda maka, value tag session akan diisi oleh value tag input
                    if($key == '245' ||
                        $key == '246' ||
                        $key == '740' ||
                        $key == '100' ||
                        $key == '700' ||
                        $key == '710' ||
                        $key == '711' ||
                        $key == '260' ||
                        $key == '264' ||
                        $key == '300' ||
                        $key == '336' ||
                        $key == '337' ||
                        $key == '338' ||
                        $key == '250' ||
                        $key == '082' ||
                        $key == '084' ||
                        $key == '020' ||
                        $key == '022' ||
                        $key == '650' || 
                        $key == '651' || 
                        $key == '600' || 
                        $key == '500' ||
                        $key == '502' ||
                        $key == '504' ||
                        $key == '505' ||
                        $key == '520' ||
                        $key == '542' ||
                        $key == '008'
                        )
                    {
                        if($taglist2['indicator'][$key] != $value)
                        {
                            $taglist2['indicator'][$key] = $value;
                        }
                    }
                }
                $taglist = $taglist2;
            }else{
                $taglist = $taglist1;
            }

            \Yii::$app->session['taglist'] = $taglist;
            $this->actionSaveEntryMode(1,$for);
            return $this->renderAjax('_entryBibliografiAdvance', [
                'for'=> $for,
                'worksheetid' => $worksheetid,
                'isSerial'=> $isSerial,
                'model' => $model,
                'taglist' => $taglist,
                'isAdvanceEntry' => 1
                ]);

        }
    }

    /**
     * [fungsi untuk menyimpan histori mode entri terkahir yang digunakan user]
     * @param  integer $mode [advance=1,simple=0]
     * @param  string $for  [cat,coll]
     * @return mixed
     */
    public function actionSaveEntryMode($mode,$for)
    {
        $userId =  (int)Yii::$app->user->identity->ID;
        $model = Users::findOne($userId);
        if($for == 'cat')
        {
            $model->IsAdvanceEntryCatalog = (bool)$mode;
        }else{
            $model->IsAdvanceEntryCollection = (bool)$mode;
        }
        $model->save(false);

    }

    /**
     * [fungsi untuk check duplikasi judul dan penerbitan]
     * @return mixed
     */
    public function actionCheckDuplicate()
    {
        if(Yii::$app->request->post())
        {
            
            $out="success";
            $post = Yii::$app->request->post();
            $tcatalogid = $post['catalogid'];
            $t245a = $post['t245a'];
            $t260a = $post['t260a'];
            $t260b = $post['t260b'];
            $t260c = $post['t260c'];

            $patterns245 = CatalogHelpers::getRegexPatternCleanTandaBaca('245');
            $patterns260 = CatalogHelpers::getRegexPatternCleanTandaBaca('260');

            //START CLEAN TANDA BACA
            $t245a=trim($t245a);
            if(count($patterns245) > 0)
            {
                //regex clean tandabaca di awal dan akhir
                $cleanValue = preg_replace($patterns245, "", $t245a);
            }else{
                $cleanValue = $t245a;
            }
            $t245a=trim($cleanValue);


            $t260a = trim($t260a);
            $t260b = trim($t260b);
            $t260c = trim($t260c);
            if(count($patterns260) > 0)
            {
                //regex clean tandabaca di awal dan akhir
                $cleanValue1 = preg_replace($patterns260, "", $t260a);
                $cleanValue2 = preg_replace($patterns260, "", $t260b);
                $cleanValue3 = preg_replace($patterns260, "", $t260c);
            }else{
                $cleanValue1 = $t260a;
                $cleanValue2 = $t260b;
                $cleanValue3 = $t260c;
            }
            $t260a = trim($cleanValue1);
            $t260b = trim($cleanValue2);
            $t260c = trim($cleanValue3);

            //END CLEAN TANDA BACA 

            if($tcatalogid != '')
            {
                $extraNotID = ' WHERE ID !='.$tcatalogid;
            }else{
                $extraNotID ='';
            }
            $command = Yii::$app->db->createCommand("SELECT Title,PublishLocation,Publisher,PublishYear FROM catalogs".$extraNotID." ORDER BY ID DESC");
            /*echo "SELECT ID FROM catalogs".$extraNotID." ORDER BY ID DESC"; */
            $dataCatalogs = $command->queryAll();

            $tandaBaca245 = Fields::getTandaBacaByTag('245');

            foreach ($dataCatalogs as $row) {
                $Title = $row['Title'];

                foreach ($tandaBaca245 as $data) {
                    $tb = trim($data->TandaBaca);
                    if($tb != '')
                    {
                        if (strpos($Title, $tb) !== false) {
                            $Title = strstr($Title, $tb, true);
                        }
                    }
                }
                $Title = trim($Title);


                $PublishLocation = $row['PublishLocation'];
                $Publisher = $row['Publisher'];
                $PublishYear = $row['PublishYear'];

                if(count($patterns260) > 0)
                {
                    //regex clean tandabaca di awal dan akhir
                    $cleanValue1 = preg_replace($patterns260, "", $PublishLocation);
                    $cleanValue2 = preg_replace($patterns260, "", $Publisher);
                    $cleanValue3 = preg_replace($patterns260, "", $PublishYear);
                }else{
                    $cleanValue1 = $PublishLocation;
                    $cleanValue2 = $Publisher;
                    $cleanValue3 = $PublishYear;
                }
                $PublishLocation = trim($cleanValue1);
                $Publisher = trim($cleanValue2);
                $PublishYear = trim($cleanValue3);

                //echo 'Input : 245a='.$t245a.' | 260a='.$t260a.' | 260b='.$t260b.' | 260c='.$t260c;
                //echo 'Input : 245a='.$Title.' | 260a='.$PublishLocation.' | 260b='.$Publisher.' | 260c='.$PublishYear;
                //die;
                if($t245a == $Title && $t260a == $PublishLocation && $t260b == $Publisher && $t260c == $PublishYear)
                {
                    $out="failed";
                    break;
                }
                
            }
            echo $out;
        }
    }

    /**
     * [fungsi untuk merender entri no induk berdasarkan jumlah eksmplar]
     * @param  string $tglpengadaan [tanggal pengadaan koleksi]
     * @param  string $eks          [jumlah eksemplar]
     * @return mixed
     */
    public function actionBindNoInduk($tglpengadaan,$eks)
    {
        $formatNomorInduk = Yii::$app->config->get('NomorInduk');
        
        return $this->renderPartial('_noInduk', [
            'jumlahEksemplar' => (int)$eks,
            'formatnoinduk'=> $formatNomorInduk,
            ]);
    }

    /**
     * [fungsi untuk modal popup sumber koleksi (partners)]
     * @param  integer $id      [id partners]
     * @param  integer $edit    [status edit]
     * @param  integer $catcoll [status form ini apakah ada di form entri koleksi (0) / di form catalog edit -> entri eksemplar (1)]
     * @param  integer $catid   [catalog id / jika sedang di form catalog edit -> tambah eksemplar]
     * @param  integer $collid  [collection id / jika sedang di form catalog edit -> edit eksemplar]
     * @return mixed
     */
    public function actionBindPartners($id,$edit,$catcoll,$catid=NULL,$collid=NULL)
    {

        if($edit == '0')
        {
            $header='Tambah Rekanan';
            $model = new Partners;
        }else{
            $header='Edit Rekanan';
            $model = Partners::findOne($id);
        }

        return $this->renderAjax('_rekanan', [
            'edit' =>  $edit,
            'catcoll' =>  $catcoll,
            'catid' =>  $catid,
            'collid' =>  $collid,
            'header' =>  $header,
            'model' =>$model
            ]);


    }


    /**
     * [fungsi proses simpan data sumber koleksi (partners)]
     * @return mixed
     */
    public function actionSavePartners()
    {
        if (Yii::$app->request->isAjax) 
        {
            $post = Yii::$app->request->post();
            $edit= $post['edit'];
            $ID= $post['ID'];
            $Name= $post['Name'];
            $Address= $post['Address'];
            $Phone= $post['Phone'];
            $Fax= $post['Fax'];
            if($edit == '0')
            {
                $model = new Partners;
            }else{
                $model = Partners::findOne($ID);
            }

            $model->Name = $Name;
            $model->Address = $Address;
            $model->Phone = $Phone;
            $model->Fax = $Fax;
            //$model->Partnership = $Partnership;
            if($model->save())
            {
                return $model->getPrimaryKey();
            }else{
                return var_dump($model->getErrors());
            }
        }

    }

    /**
     * [fungsi untuk modal popup tambah/edit eksemplar]
     * @param  integer $id        [id collections]
     * @param  integer $catalogid [id catalogs]
     * @return mixed
     */
    public function actionBindCatalogsCollection($id,$catalogid,$refer)
    {
        $isUserHasAccess = CatalogHelpers::isUserHasAccess(Yii::$app->user->identity->id);
        //validasi jika user tidak mendapat akses untuk entri koleksi
        if($isUserHasAccess == false)
        {
            return "<div class=\"standard-error-summary\" style=\"color:red; text-align:center\">User ". Yii::$app->user->identity->username." tidak mempunyai akses melakukan entri/koreksi koleksi!</div>";
        }else{

            if($id == 0)
            {
                $header='Tambah Eksemplar | '.Catalogs::findOne($catalogid)->Title;
                $model = new Collections;
                $model->ISOPAC=1;
                $model->Currency='IDR';
                $model->Price=0;
                $model->JumlahEksemplar=1;
            }else{
                $model = Collections::findOne($id);
                $model->TanggalPengadaan = Yii::$app->formatter->asDate($model->TanggalPengadaan, 'php:d-m-Y');
                if($model->TANGGAL_TERBIT_EDISI_SERIAL)
                {
                    $model->TANGGAL_TERBIT_EDISI_SERIAL = Yii::$app->formatter->asDate($model->TANGGAL_TERBIT_EDISI_SERIAL, 'php:d-m-Y');
                }
                $header='Edit Eksemplar - '.$model->NomorBarcode.' | '. $model->catalog->Title;
            }

            $modelcat= Catalogs::findOne($catalogid);
            $isSerial = (int)Worksheets::findOne($modelcat->Worksheet_id)->ISSERIAL;
            return $this->renderAjax('_collection', [
                'header' =>  $header,
                'model' =>$model,
                'id'=>$id,
                'catalogid'=>$catalogid,
                'isSerial'=>$isSerial,
                'refer'=>$refer
                ]);

        }
    }

    /**
     * [fungsi untuk modal popup tambah/edit Article]
     * @param  integer $id        [id collections]
     * @param  integer $catalogid [id catalogs]
     * @return mixed
     */
    public function actionBindCatalogsArticle($id,$catalogid,$refer)
    {
        $isUserHasAccess = CatalogHelpers::isUserHasAccess(Yii::$app->user->identity->id);
        //validasi jika user tidak mendapat akses untuk entri koleksi
        if($isUserHasAccess == false)
        {
            return "<div class=\"standard-error-summary\" style=\"color:red; text-align:center\">User ". Yii::$app->user->identity->username." tidak mempunyai akses melakukan entri/koreksi koleksi!</div>";
        }else{

            if($id == 0)
            {
                $header='Tambah Article | '.Catalogs::findOne($catalogid)->Title;
                $model = new SerialArticles();
                $model->Catalog_id = $catalogid;
            }else{
                $model = SerialArticles::findOne($id);
                if($model->TANGGAL_TERBIT_EDISI_SERIAL)
                {
                    $model->TANGGAL_TERBIT_EDISI_SERIAL = Yii::$app->formatter->asDate($model->TANGGAL_TERBIT_EDISI_SERIAL, 'php:d-m-Y');
                }
                $header='Edit Article - '.$model->Title;
            }

            return $this->renderAjax('_article', [
                'header' =>  $header,
                'model' =>$model,
                'id'=>$id,
                'catalogid'=>$catalogid,
                'refer'=>$refer
            ]);

        }
    }

    public function actionBindCatalogsDigitalArticle($id,$catalogid,$refer)
    {
        $isUserHasAccess = CatalogHelpers::isUserHasAccess(Yii::$app->user->identity->id);
        //validasi jika user tidak mendapat akses untuk entri koleksi
        if($isUserHasAccess == false)
        {
            return "<div class=\"standard-error-summary\" style=\"color:red; text-align:center\">User ". Yii::$app->user->identity->username." tidak mempunyai akses melakukan entri/koreksi koleksi!</div>";
        }else{

            if($id == 0)
            {
                $header='Tambah Konten DIgital Article | '.Catalogs::findOne($catalogid)->Title;
                $model = new SerialArticlefiles();
                $model->Catalog_id = $catalogid;
            }else{
                $model = SerialArticlefiles::findOne($id);
                if($model->TANGGAL_TERBIT_EDISI_SERIAL)
                {
                    $model->TANGGAL_TERBIT_EDISI_SERIAL = Yii::$app->formatter->asDate($model->TANGGAL_TERBIT_EDISI_SERIAL, 'php:d-m-Y');
                }
                $header='Edit Article - '.$model->Title;
            }

            return $this->renderAjax('_article', [
                'header' =>  $header,
                'model' =>$model,
                'id'=>$id,
                'catalogid'=>$catalogid,
                'refer'=>$refer
            ]);

        }
    }

    /**
     * [fungsi untuk mereset data koleksi saat edit katalog]
     * @param  integer $id [id katalog]
     * @return mixed
     */
    public function actionResetCatalogsCollection($id,$rda,$refer)
    {
        //Set active tab
        \Yii::$app->session['SessCatalogTabActive'] = 'koleksi';
        
        return $this->redirect(['update?for=cat&rda='.$rda.'&id='.$id.'&edit=t&refer='.$refer]);
    }

    /**
     * [fungsi proses simpan data koleksi di form edit katalog]
     * @param  integer $id [id collections]
     * @return mixed
     */
    public function actionSaveCatalogsCollection($id,$refer)
    {
        if($id == 0)
        {
            $model = new Collections; 
        }else{
            $model = Collections::findOne($id); 
        }  
        $post = Yii::$app->request->post();

        if (Yii::$app->request->isAjax) 
        {
            $model->load($post);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        elseif($model->load($post))
        {

            $trans = Yii::$app->db->beginTransaction();
            $formatNomorInduk = Yii::$app->config->get('NomorInduk');
            $nomorIndukTengah = Yii::$app->config->get('NomorIndukTengah');
            $templateNomorInduk = Yii::$app->config->get('FormatNomorInduk');
            try {
                
            
                $catruas = CatalogRuas::find()
                                    ->addSelect(['MAX(Sequence) + 1 AS Sequence'])
                                    ->where(['CatalogId'=>$model->Catalog_id])
                                    ->one();
                $seq= (int)$catruas->Sequence;

                if($model->isNewRecord)
                {
                    for($i=0; $i < (int)$model->JumlahEksemplar; $i++)
                    {
                        //$noindukforruas =$model->NoInduk[$i];
                        if(trim(strtolower($formatNomorInduk))=='manual')
                        {
                            if($model->NoInduk[$i] != '')
                            {
                                $noindukforruas= $model->NoInduk[$i];
                            }else{
                                $noindukforruas= $newItemID;
                            }
                        }else{
                            //$latestNomorInduk=CollectionHelpers::getLatestNomorInduk($formatNomorInduk);
                            $latestNomorInduk=(int)Settingparameters::find()->where(['Name'=>'NomorIndukCounter'])->one()->Value;
                            $latestNomorIndukDigit = str_pad(($latestNomorInduk+1), 4, '0', STR_PAD_LEFT);
                            $tahunIni = $tahunPengadaan;
                            $modelcatforworksheet = Catalogs::findOne($id);

                            $kodeJenisBahan =  Worksheets::findOne($modelcatforworksheet->Worksheet_id)->CODE;
                            $kodeKategoriKoleksi =  Collectioncategorys::findOne($model->Category_id)->Code;
                            $kodeBentukFisik =  Collectionmedias::findOne($model->Media_id)->Code;
                            $tahunPengadaan = Yii::$app->formatter->asDate($model->TanggalPengadaan, 'php:Y');
                            //Jika isi dari nomor induk tengah mengandung saparator slash /
                            $templateNomorInduks = explode('|',$templateNomorInduk);
                            $noindukforruas = '';
                            foreach ($templateNomorInduks as $key => $templateData) {
                                if($key == 0 || $key == 2 || $key == 4)
                                {

                                    if($templateData != '0')
                                    {
                                        if(strpos($templateData,'{') !== FALSE)
                                        {
                                            $noindukforruas .= str_replace('}','', str_replace('{','', $templateData));
                                        }else{
                                            switch ($templateData) {
                                                //Jenis Bahan
                                                case '2':
                                                    $noindukforruas .= $kodeJenisBahan;
                                                    break;
                                                //Kategori Koleksi
                                                case '3':
                                                    $noindukforruas .= $kodeKategoriKoleksi;
                                                    break;
                                                //Bentuk Fisik
                                                case '4':
                                                    $noindukforruas .= $kodeBentukFisik;
                                                    break;
                                            }
                                        }
                                    }
                                }else{
                                    switch ($templateData) {
                                        //Number pad left
                                        case '0':
                                            $noindukforruas .=$latestNomorIndukDigit;
                                            break;
                                        //year
                                        case '1':
                                            $noindukforruas .=$tahunIni;
                                            break;
                                    }
                                }
                            }
                        }
                        if($noindukforruas && CatalogHelpers::saveCatalogRuasOnline($model->Catalog_id,'#','#','990','$a '.$noindukforruas,$seq++))
                        {
                                //save catalog sub ruas records
                            CatalogHelpers::saveCatalogSubruasOnline('a',trim($noindukforruas),1);
                        }
                    }

                }else{

                        //Insert no induk dari form
                    if($model->NoInduk && CatalogHelpers::saveCatalogRuasOnline($model->Catalog_id,'#','#','990','$a '.$model->NoInduk,$seq++))
                    {
                            //save catalog sub ruas records
                        CatalogHelpers::saveCatalogSubruasOnline('a',trim($model->NoInduk),1);
                    }

                        //ketika reset catalog ruas, tag 990 no induk yg selain di edit, msih bisa terbawa dan disave di catalog ruas n subruas
                    $modelcollother = Collections::find()->where(['and','Catalog_id = '.$model->Catalog_id,['not in','ID',$model->ID]])->all();
                    foreach ($modelcollother as $data) {
                        if($data->NoInduk && CatalogHelpers::saveCatalogRuasOnline($model->Catalog_id,'#','#','990','$a '.$data->NoInduk,$seq++))
                        {
                                //save catalog sub ruas records
                            CatalogHelpers::saveCatalogSubruasOnline('a',trim($data->NoInduk),1);
                        }
                    }

                    (int)$model->JumlahEksemplar = 1;
                }

                $successSave = 0;
                $msgerror=[];
                for($i=0; $i < (int)$model->JumlahEksemplar; $i++)
                {
                    //Set model collection new dari model input
                    if($model->isNewRecord)
                    {
                        $modelcoll = new Collections;
                        $modelcoll->ID=NULL;
                        $modelcoll->Branch_id=37; 
                        $modelcoll->IsVerified=0;
                        $newItemID = str_pad((int)Collections::find()->select('MAX(ID) AS ID')->one()->ID +1 , 11, '0', STR_PAD_LEFT);
                        $latestNomorIndukDigit;
                        if(trim(strtolower($formatNomorInduk))=='manual')
                        {
                            if($model->NoInduk[$i] != '')
                            {
                                $noinduk= $model->NoInduk[$i];
                            }else{
                                $noinduk= $newItemID;
                            }
                        }else{
                            //$latestNomorInduk=CollectionHelpers::getLatestNomorInduk($formatNomorInduk);
                            $latestNomorInduk=(int)Settingparameters::find()->where(['Name'=>'NomorIndukCounter'])->one()->Value;
                            $tahunIni = $tahunPengadaan;


                            $modelcatforworksheet = Catalogs::findOne($model->Catalog_id);
                            $kodeJenisBahan =  Worksheets::findOne($modelcatforworksheet->Worksheet_id)->CODE;
                            $kodeKategoriKoleksi =  Collectioncategorys::findOne($model->Category_id)->Code;
                            $kodeBentukFisik =  Collectionmedias::findOne($model->Media_id)->Code;
                            $BentukSources =  Collectionsources::findOne($model->Source_id)->Code;
                            $tahunPengadaan = Yii::$app->formatter->asDate($model->TanggalPengadaan, 'php:Y');
                            $sql = "SELECT DATE_FORMAT(collections.TanggalPengadaan, '%Y') AS test, COUNT(collections.TanggalPengadaan)+1 AS test2 FROM collections WHERE DATE_FORMAT(collections.TanggalPengadaan, '%Y') = '".$tahunPengadaan."'";
                            $data = Yii::$app->db->createCommand($sql)->queryOne(); 
                            $latestNomorIndukDigit = str_pad($data['test2'],5,"0", STR_PAD_LEFT);
                            // print_r($dataxxxx['test2']);die;
                            //Jika isi dari nomor induk tengah mengandung saparator slash /
                            $templateNomorInduks = explode('|',$templateNomorInduk);
                            $noinduk = '';
                            foreach ($templateNomorInduks as $key => $templateData) {
                                if($key == 0 || $key == 4 || $key == 8)
                                {

                                    if($templateData != '0')
                                    {
                                        if(strpos($templateData,'{') !== FALSE)
                                        {
                                            $noinduk .= str_replace('}','', str_replace('{','', $templateData));
                                        }else if(strpos($templateData,'^') !== FALSE)
                                        {
                                            $noinduk .= $BentukSources;
                                        }else{
                                            switch ($templateData) {
                                                //Jenis Bahan
                                                case '2':
                                                    $noinduk .= $kodeJenisBahan;
                                                    break;
                                                //Kategori Koleksi
                                                case '3':
                                                    $noinduk .= $kodeKategoriKoleksi;
                                                    break;
                                                //Bentuk Fisik
                                                case '4':
                                                    $noinduk .= $kodeBentukFisik;
                                                    break;
                                            }
                                        }
                                    }
                                }else{
                                    switch ($templateData) {
                                        //Number pad left
                                        case '0':
                                            $noinduk .=$latestNomorIndukDigit;
                                            break;
                                        //year
                                        case '1':
                                            $noinduk .=$tahunIni;
                                            break;
                                        case '2':
                                            $noinduk .='';
                                            break;
                                        case '3':
                                            $noinduk .='/';
                                            break;
                                        case '4':
                                            $noinduk .='-';
                                            break;
                                        case '5':
                                            $noinduk .='.';
                                            break;
                                    }
                                }
                            }
                            /*if(strstr($nomorIndukTengah, '/') != '')
                            {
                                $noinduk = $latestNomorIndukDigit4.$nomorIndukTengah.$tahunIni;
                            }else{
                                $noinduk = $nomorIndukTengah.'-'.$latestNomorInduk+$i.'-'.$tahunPengadaan;
                            }*/
                        }
                        //Check di setting parameter untuk format no barcode
                        if (trim(str_replace(' ','',strtolower(Yii::$app->config->get('FormatNomorBarcode'))))=='no.induk')
                        {
                            $NomorID = $noinduk;
                        }else{
                            $NomorID = $newItemID;
                        }

                        //Check di setting parameter untuk format no rfid
                        if (trim(str_replace(' ','',strtolower(Yii::$app->config->get('FormatNomorRFID'))))=='no.induk')
                        {
                            $NomorID2 = $noinduk;
                        }else{
                            $NomorID2 = $newItemID;
                        }

                        if(trim(strtolower($formatNomorInduk))=='manual')
                        {
                            if($model->NomorBarcode[$i] != '')
                            {
                                $nobarcode= $model->NomorBarcode[$i];
                            }else{
                                $nobarcode= $NomorID;
                            }

                            if($model->RFID[$i] != '')
                            {
                                $norfid= $model->RFID[$i];
                            }else{
                                $norfid= $NomorID2;
                            }
                        }else{
                            $nobarcode= $NomorID;
                            $norfid= $NomorID2;
                        }
                        
                        $modelcoll->NomorBarcode= $nobarcode;
                        $modelcoll->RFID= $norfid;
                        $modelcoll->NoInduk= $noinduk;
                        $modelcoll->JumlahEksemplar = $model->JumlahEksemplar;
                    }else{
                        $modelcoll = Collections::findOne($model->ID);
                        $modelcoll->NoInduk= $model->NoInduk;
                        $modelcoll->NomorBarcode= $model->NomorBarcode;
                        $modelcoll->RFID= $model->RFID;
                        $modelcoll->Branch_id=$model->Branch_id; 
                        $modelcoll->IsVerified=(int)$model->IsVerified;
                        $modelcoll->JumlahEksemplar = 1;
                    }
                    $modelcoll->Catalog_id=$model->Catalog_id;
                    $modelcoll->EDISISERIAL = $model->EDISISERIAL;
                    if($model->TANGGAL_TERBIT_EDISI_SERIAL)
                    {
                        $modelcoll->TANGGAL_TERBIT_EDISI_SERIAL = Yii::$app->formatter->asDate($model->TANGGAL_TERBIT_EDISI_SERIAL, 'php:Y-m-d');
                    }
                    $modelcoll->BAHAN_SERTAAN = $model->BAHAN_SERTAAN;
                    $modelcoll->KETERANGAN_LAIN = $model->KETERANGAN_LAIN;
                    $modelcoll->TanggalPengadaan = Yii::$app->formatter->asDate($model->TanggalPengadaan, 'php:Y-m-d');
                    $modelcoll->Source_id = $model->Source_id;
                    //$modelcoll->Keterangan_Sumber = $model->Keterangan_Sumber;
                    $modelcoll->Media_id = $model->Media_id;
                    $modelcoll->Category_id = $model->Category_id;
                    $modelcoll->Rule_id = $model->Rule_id;
                    $modelcoll->Location_Library_id = $model->Location_Library_id;
                    $modelcoll->Location_id = $model->Location_id;
                    $modelcoll->Partner_id = $model->Partner_id;
                    $modelcoll->ISREFERENSI = $model->ISREFERENSI;
                    $modelcoll->Status_id = $model->Status_id;
                    $modelcoll->Currency = $model->Currency;
                    $modelcoll->Price = $model->Price;
                    $modelcoll->PriceType = $model->PriceType;
                    $modelcoll->CallNumber = $model->CallNumber;
                    $modelcoll->ISOPAC = $model->ISOPAC;
                    //save collection records
                    if($modelcoll->save())
                    {
                        if($latestNomorIndukDigit!=NULL)
                        {
                            $modelsettingparam = Settingparameters::find()->where(['Name'=>'NomorIndukCounter'])->one();
                            $modelsettingparam->Value=$latestNomorIndukDigit;
                            if($modelsettingparam->save(false))
                            {
                                $successSave++;
                            }
                        }else{
                             $successSave++;
                        }
                    }else{
                        if($modelcoll->getErrors())
                        {
                            $trans->rollback();
                            foreach ($modelcoll->getErrors() as $key => $value) {
                               foreach ($value as $key2 => $value2) {
                                    $msgerror[] = $value2;
                                }
                            }
                        }

                    }

                }
                //jika jumlah Katalog tersimpan sama dengan isian jumlah eksemplar, maka sukses
                //echo $successSave; die;
                if($successSave == (int)$model->JumlahEksemplar)
                {

                    $trans->commit();
                    //Set active tab
                    \Yii::$app->session['SessCatalogTabActive'] = 'koleksi';

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                    
                    if(Yii::$app->request->referrer){
                        if(strpos(Yii::$app->request->referrer,'&refer') > -1)
                        {
                            $redirectUrl = Yii::$app->request->referrer;
                        }else{
                            $redirectUrl = Yii::$app->request->referrer.'&refer='.$refer;
                        }
                        return $this->redirect($redirectUrl);
                    }
                }else{
                    \Yii::$app->session['SessCatalogTabActive'] = 'koleksi';
                    foreach ($msgerror as $key => $value) {
                        Yii::$app->getSession()->setFlash('success'.$key , [
                                                'type' => 'danger',
                                                'duration' => 3000,
                                                'icon' => 'fa fa-info-circle',
                                                'message' => $value,
                                                'title' => 'Warning',
                                                'positonY' => Yii::$app->params['flashMessagePositionY'],
                                                'positonX' => Yii::$app->params['flashMessagePositionX']
                                              
                        ]);
                    }
                    //seharusnya tidak begini, ini dikarenakan belum ketemu show flash di modal
                    if(Yii::$app->request->referrer){
                        if(strpos(Yii::$app->request->referrer,'&refer') > -1)
                        {
                            $redirectUrl = Yii::$app->request->referrer;
                        }else{
                            $redirectUrl = Yii::$app->request->referrer.'&refer='.$refer;
                        }
                        return $this->redirect($redirectUrl);
                    }
                }

            } catch (Exception $e) {
                $trans->rollback();
            }
        }

    }


    public function actionSaveCatalogsArticle($id,$refer,$catalogId)
    {

        if($id == 0)
        {
            $model = new SerialArticles();
            $model->Catalog_id = $catalogId;
        }else{
            $model = SerialArticles::findOne($id);
        }
        $post = Yii::$app->request->post();


        if (Yii::$app->request->isAjax)
        {
            //OpacHelpers::print__r($post);
            $model->load($post);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        elseif($model->load($post))
        {
            //OpacHelpers::print__r($post);
            $trans = Yii::$app->db->beginTransaction();
            try {
                $model->save();
                if($model->getErrors())
                {
                    $trans->rollback();
                    foreach ($model->getErrors() as $key => $value) {
                        foreach ($value as $key2 => $value2) {
                            $msgerror[] = $value2;
                        }
                    }
                    \Yii::$app->session['SessCatalogTabActive'] = 'koleksi';
                    foreach ($msgerror as $key => $value) {
                        Yii::$app->getSession()->setFlash('success'.$key , [
                            'type' => 'danger',
                            'duration' => 3000,
                            'icon' => 'fa fa-info-circle',
                            'message' => $value,
                            'title' => 'Warning',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']

                        ]);
                    }
                    //seharusnya tidak begini, ini dikarenakan belum ketemu show flash di modal
                    if(Yii::$app->request->referrer){
                        if(strpos(Yii::$app->request->referrer,'&refer') > -1)
                        {
                            $redirectUrl = Yii::$app->request->referrer;
                        }else{
                            $redirectUrl = Yii::$app->request->referrer.'&refer='.$refer;
                        }
                        return $this->redirect($redirectUrl);
                    }

                } else {

                    $trans->commit();

                    //Set active tab
                    \Yii::$app->session['SessCatalogTabActive'] = 'artikel';

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);

                    if(Yii::$app->request->referrer){
                        if(strpos(Yii::$app->request->referrer,'&refer') > -1)
                        {
                            $redirectUrl = Yii::$app->request->referrer;
                        }else{
                            $redirectUrl = Yii::$app->request->referrer.'&refer='.$refer;
                        }
                        return $this->redirect($redirectUrl);
                    }
                }

            } catch (Exception $e) {
                $trans->rollback();
            }
        }
    }

    /**
     * [fungsi saat pilih/ubah judul]
     * @return mixed
     */
    public function actionPilihJudul()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CatalogSearch;
        $dataProvider = $searchModel->advancedSearch(0,$rules);
        return $this->renderAjax('_pilihJudul', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>Yii::$app->request->get('for'),
            'rules'=>$rules
            ]);
    }

    /**
     * [fungsi ketika klik tombol pilih di halaman modal pilih judul]
     * @param  string $for [cat,coll]
     * @param  integer $rda [status rda]
     * @return mixed
     */
    public function actionPilihJudulProses($for,$rda)
    {
        if (Yii::$app->request->isAjax) 
        {
            \Yii::$app->session['taglist']=[];
            $post = Yii::$app->request->post();
            $modeluser = Users::findOne((int)Yii::$app->user->identity->ID);
            $isAdvanceEntryCollection = (int)$modeluser->IsAdvanceEntryCollection;
            $isAdvanceEntryCatalog = (int)$modeluser->IsAdvanceEntryCatalog;
            $CatalogId=$post['id'];
            $Worksheet_id=$post['workshetid'];
            $isSerial = (int)Worksheets::findOne($Worksheet_id)->ISSERIAL;
            if($for == 'cat')
            {
                $isAdvanceEntry=$isAdvanceEntryCatalog;
            }
            else
            {
                $isAdvanceEntry=$isAdvanceEntryCollection;
            }
            if((int)$isAdvanceEntry == 1)
            {

                $model = new Collections;
                $taglist=array();
                $this->actionCreateTaglistFromCatalog($CatalogId,$Worksheet_id,$for,$taglist,$rda);
                \Yii::$app->session['taglist']= $taglist;
                $this->actionSaveEntryMode(1,$for);
                return $this->renderAjax('_entryBibliografiAdvance', [
                    'for'=> $for,
                    'worksheetid' => $Worksheet_id,
                    'isSerial'=> $isSerial,
                    'model' => $model,
                    'taglist' => $taglist,
                    'isAdvanceEntry' => 1
                    ]);
            }else{
                //foreach taglist untuk mendapatkan value current input
                $modelbib = new CollectionBiblio;
                $model = new Collections;
                $taglist=array();
                $this->actionCreateModelBibFromCatalog($CatalogId,$modelbib);
                $this->actionSaveEntryMode(0,$for);
                $listvar['publication']=array();
                $listvar['author']=array();
                $listvar['authortype']= array();
                $listvar['isbn']= array();
                $listvar['subject']= array();
                $listvar['subjecttag']=array();
                $listvar['subjectind']= array();
                $listvar['callnumber']= array();
                $listvar['note']= array();
                $listvar['notetag']= array();
                $listvar['titlevarian']= array();
                $listvar['titleoriginal']= array();
                $rulesform['008_Bahasa'] = (int)Worksheetfields::getStatusTag008(5,1,29);
                $rulesform['008_KaryaTulis'] = (int)Worksheetfields::getStatusTag008(17,1,29);
                $rulesform['008_KelompokSasaran'] = (int)Worksheetfields::getStatusTag008(2,1,29);
                if($modelbib->AuthorAddedType==NULL)
                {
                     $modelbib->AuthorAddedType[0]=0;
                }
                $dataPublication = [];
                foreach ($modelbib->PublishLocation as $key => $value) {
                   $dataPublication[$key] = ['publishlocation'=>$value] + ['publisher'=>$modelbib->Publisher[$key]] + ['publishyear'=>$modelbib->PublishYear[$key]];
                }
                $listvar['publication']=$dataPublication;
                $listvar['author'] = $modelbib->AuthorAdded;
                $listvar['authortype'] = $modelbib->AuthorAddedType;
                $listvar['isbn'] = $modelbib->ISBN;
                $listvar['issn'] = $modelbib->ISSN;
                $listvar['note'] = $modelbib->Note;
                $listvar['notetag'] = $modelbib->NoteTag;
                $listvar['titlevarian']= $modelbib->TitleVarian;
                $listvar['titleoriginal']= $modelbib->TitleOriginal;
                $listvar['subject'] = $modelbib->Subject;
                $listvar['subjecttag'] = $modelbib->SubjectTag;
                $listvar['subjectind'] = $modelbib->SubjectInd;
                $listvar['callnumber'] = $modelbib->CallNumber;
                $listvar['input_required']=array();
                $listvar['input_required'] = $this->actionValidateRequiredSimpleForm($rda);

                //Create taglist for prepare advance entry
                $this->actionCreateTaglistFromCatalog($CatalogId,$Worksheet_id,$for,$taglist,$rda);
                \Yii::$app->session['taglist'] = $taglist;



                return $this->renderAjax('_entryBibliografiSimple', [
                    'for'=>$for,
                    'worksheetid' => $Worksheet_id,
                    'isSerial'=> $isSerial,
                    'modelbib' => $modelbib,
                    'taglist' => $taglist,
                    'model' => $model,
                    'isAdvanceEntry' => 0,
                    'listvar'=> $listvar,
                    'rulesform'=>$rulesform
                    ]);
            }
        }
    }

    /**
     * [fungsi salin katalog via sru]
     * @return mixed
     */
    public function actionSalinKatalogSru()
    {
        
        $modelImportMarc = new \backend\models\ImportMarcForm();
        $post = Yii::$app->request->post();
        if($post)
        {
            $libId = $post['libId'];
            $critId = strtolower($post['critId']);
            $modellib = Library::findOne($libId);
            $url= $modellib->URL;
            $port=$modellib->PORT;
            $db=$modellib->DATABASENAME;
            $query = rawurlencode($post['query']);
            $startRecord = 1;
            $maxRecord = $post['maxRecord'];
            $protocol = strtolower($modellib->PROTOCOL);
            $taglist=array();
            //Get taglist from sru
            $modelImportMarc->sru($url,$port,$db,$critId,$query,$startRecord,$maxRecord,$protocol,$taglist,'entri');

            //Star parsing
            $arrayData=array();
            CatalogHelpers::getCatalogDataFromTaglist($taglist,$arrayData);
            
            $dataProvider = new ArrayDataProvider([
                //'key'=>'ID',
                'allModels' => $arrayData,
                'pagination' => false
            ]);

            return $this->renderAjax('_salinKatalogGrid', [
            'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * [fungsi untuk menampilkan modal salin katalog]
     * @param  string $for [cat,coll]
     * @return mixed
     */
    public function actionSalinKatalog($for)
    {
        $model = new FieldSearch;
        $dataProvider = $model->search(Yii::$app->request->getQueryParams());

        $librarydata= ArrayHelper::map(Library::loadSumber(),'ID','NAME');
        $library=array();
        $library[0]='File Record';
        foreach ($librarydata as $key => $value) {
            $library[$key]=$value;
        }
        return $this->renderAjax('_salinKatalog', [
            'dataProvider' => $dataProvider,
            'searchModel' => $model,
            'library'=> $library,
            'for'=>$for
            ]);
    }

    /**
     * [fungsi untuk mengupload data file mrc/xml untuk salin katalog]
     * @param  string $type [type file]
     * @return mixed
     */
    public function actionSalinKatalogUpload($type="MARC21MRC")
    {
        //var_dump(Yii::$app->request->post()); die;
        $modelImportMarc = new \backend\models\ImportMarcForm();
        $taglist=array();
        $modelbib= new CollectionBiblio;
        if (Yii::$app->request->isAjax) {
            $modelImportMarc->file = \yii\web\UploadedFile::getInstance($modelImportMarc, 'file');
            if(($modelImportMarc->file->getExtension()=='mrc' && $type != 'MARC21MRC') || ($modelImportMarc->file->getExtension()=='xml' && $type == 'MARC21MRC'))
            {
                echo "<div class=\"callout callout-danger\">Tidak dapat mengurai berkas dikarenakan tipe file record tidak sesuai</div>";
                die;
            }
            if ($modelImportMarc->upload()) {
                $modelImportMarc->ekstrak($taglist,$modelbib,$type);
                $arrayData=array();
                CatalogHelpers::getCatalogDataFromTaglist($taglist,$arrayData);
                
                //print_r($arrayData); die;
                $dataProvider = new ArrayDataProvider([
                    //'key'=>'ID',
                    'allModels' => $arrayData,
                ]);

                return $this->renderAjax('_salinKatalogGrid', [
                'dataProvider' => $dataProvider
                ]);
            }
        }
    }

    /**
     * [fungsi proses salin katalog saat klik tombol pilih di grid]
     * @param  string $for [cat,coll]
     * @param  integer $rda [status rda]
     * @return mixed
     */
    public function actionSalinKatalogProses($for,$rda)
    {
        if (Yii::$app->request->isAjax) 
        {
            $post = Yii::$app->request->post();
            $modeluser = Users::findOne((int)Yii::$app->user->identity->ID);
            $isAdvanceEntryCollection = (int)$modeluser->IsAdvanceEntryCollection;
            $isAdvanceEntryCatalog = (int)$modeluser->IsAdvanceEntryCatalog;
            $isSerial = (int)Worksheets::findOne(1)->ISSERIAL;
            if($for == 'cat')
            {
                $isAdvanceEntry=$isAdvanceEntryCatalog;
            }
            else
            {
                $isAdvanceEntry=$isAdvanceEntryCollection;
            }


            if($post['inputvalue']['264']){
                $rda=1;
            }else{
                $rda=0;
            }

            \Yii::$app->session['taglist']=[];
            //mix taglist post dan taglist sesuai dgn worksheetfields
            $taglist = $this->actionCreateTaglistClean($post,1,$for,$rda,true);
            \Yii::$app->session['taglist']= $taglist;

            if((int)$isAdvanceEntry == 1)
            {

                $model = new Collections;
                $this->actionSaveEntryMode(1,$for);
                return $this->renderAjax('_entryBibliografiAdvance', [
                    'for'=> $for,
                    'rda'=>$rda,
                    'worksheetid' => 1,
                    'isSerial'=>$isSerial,
                    'model' => $model,
                    'taglist' => $taglist,
                    'isAdvanceEntry' => 1
                    ]);
            }else{
                //foreach taglist untuk mendapatkan value current input
                $modelbib = new CollectionBiblio;
                $model = new Collections;
                //isi modelbib dan model dari taglist($taglist)
                $this->actionCreateTaglistToBiblio($model,$modelbib,$taglist);
                $this->actionSaveEntryMode(0,$for);
                $listvar['publication']=array();
                $listvar['author']=array();
                $listvar['authortype']= array();
                $listvar['isbn']= array();
                $listvar['subject']= array();
                $listvar['subjecttag']=array();
                $listvar['subjectind']= array();
                $listvar['callnumber']= array();
                $listvar['note']= array();
                $listvar['notetag']= array();
                $listvar['titlevarian']= array();
                $listvar['titleoriginal']= array();
                $rulesform['008_Bahasa'] = (int)Worksheetfields::getStatusTag008(5,1,29);
                $rulesform['008_KaryaTulis'] = (int)Worksheetfields::getStatusTag008(17,1,29);
                $rulesform['008_KelompokSasaran'] = (int)Worksheetfields::getStatusTag008(2,1,29);
                if($modelbib->AuthorAddedType==NULL)
                {
                     $modelbib->AuthorAddedType[0]=0;
                }
                $dataPublication = [];
                foreach ($modelbib->PublishLocation as $key => $value) {
                   $dataPublication[$key] = ['publishlocation'=>$value] + ['publisher'=>$modelbib->Publisher[$key]] + ['publishyear'=>$modelbib->PublishYear[$key]];
                }
                $listvar['publication']=$dataPublication;
                $listvar['author'] = $modelbib->AuthorAdded;
                $listvar['authortype'] = $modelbib->AuthorAddedType;
                $listvar['isbn'] = $modelbib->ISBN;
                $listvar['issn'] = $modelbib->ISSN;
                $listvar['note'] = $modelbib->Note;
                $listvar['notetag'] = $modelbib->NoteTag;
                $listvar['titlevarian']= $modelbib->TitleVarian;
                $listvar['titleoriginal']= $modelbib->TitleOriginal;
                $listvar['subject'] = $modelbib->Subject;
                $listvar['subjecttag'] = $modelbib->SubjectTag;
                $listvar['subjectind'] = $modelbib->SubjectInd;
                $listvar['callnumber'] = $modelbib->CallNumber;
                $listvar['input_required']=array();
                $listvar['input_required'] = $this->actionValidateRequiredSimpleForm($rda);
                return $this->renderAjax('_entryBibliografiSimple', [
                    'for'=>$for,
                    'rda'=>$rda,
                    'worksheetid' => 1,
                    'isSerial'=>$isSerial,
                    'modelbib' => $modelbib,
                    'model' => $model,
                    'isAdvanceEntry' => 0,
                    'listvar'=> $listvar,
                    'rulesform'=>$rulesform
                    ]);
            }
        }
    }

    /**
     * [fungsi add tag modal popup]
     * @return mixed
     */
    public function actionAddTag()
    {
        $model = new FieldSearch;
        $dataProvider = $model->search(Yii::$app->request->getQueryParams());
        return $this->renderAjax('_addTag', [
            'dataProvider' => $dataProvider,
            'searchModel' => $model,
            ]);
    }

    /**
     * [fungsi untuk set value setiap ruas modal popup]
     * @return mixed
     */
    public function actionSetRuas()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id=$data['id'];
            $tag=$data['tag'];
            $sort=$data['sort'];
            $model = new FielddataSearch;
            $tagdesc = Fields::findOne($id)->Name;
            $dataProvider = $model->loadSubTagEntry((string)$tag,1);
            $dataProvider->pagination=false;
            $dataProvider->sort=false;
            return $this->renderPartial('_setRuas', [
                'tag' => (string)$tag,
                'tagdesc' => $tagdesc,
                'sort' => (string)$sort,
                'dataProvider' => $dataProvider,
                ]);
        }
    }


     /**
     * [fungsi untuk set value setiap ruas fixed modal popup]
     * @return mixed
     */
    public function actionSetRuasFixed()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $id =  $data['id'];
            $tag =  $data['tag'];
            $v =  $data['v'];
            $sort =  $data['sort'];
            $worksheetid =  $data['worksheetid'];
            $catalogid =  $data['catalogid'];
            $modelworksheetfields = new Worksheetfields;
            $modelworksheetfielditems = new WorksheetFieldItems;
            $modelreferenceitems =  new Refferenceitems;
            $tagdesc = Fields::findOne($id)->Name;
            
            $WorksheetFieldID = $modelworksheetfields
                                ->find()
                                ->select('ID')
                                ->where(['Worksheet_id'=>$worksheetid,'Field_id'=>$id])
                                ->scalar();

            $model= $modelworksheetfielditems
                    ->find()
                    ->where(['Worksheetfield_id'=>$WorksheetFieldID])
                    ->orderBy('StartPosition')
                    ->all();

            $lengthValues=  strlen($v);
            $command = Yii::$app->db->createCommand("SELECT sum(Length) FROM worksheetfielditems WHERE Worksheetfield_id=:Worksheetfield_id");
            $command->bindParam(':Worksheetfield_id', $WorksheetFieldID);
            $lengthField = $command->queryScalar();

            $resultValues = array();

            foreach ($model as $key => $value) {
                try {

                    $resultValues[$key] = substr($v,(int)$value['StartPosition'],(int)$value['Length']);
                    
                } catch (Exception $e) {
                    $resultValues[$key] = '';
                }
            }

            $refValues = $modelreferenceitems->find()->addSelect(['Refference_id','Code','Name'])->asArray()->all();

            if($catalogid != '')
            {
                $modelcatalogs = Catalogs::findOne($catalogid);
                $Createdate =  $modelcatalogs->CreateDate;
                $Publishyear = $modelcatalogs->PublishYear;
            }else{
                $Createdate = null;
                $Publishyear = null;
            }


            return $this->renderPartial('_setRuasFixed', [
                'tag' => (string)$tag,
                'tagdesc' => $tagdesc,
                'sort' => (string)$sort,
                'catalogid' => $catalogid,
                'model' => $model,
                'value' => $v,
                'values' => $resultValues,
                'refValues' => $refValues,
                'createdate'=> $Createdate,
                'publishyear'=> $Publishyear,
                ]); 
        }
    }

    /**
     * [fungsi untuk set indicator 1 setiap ruas modal popup]
     * @return mixed
     */
    public function actionSetIndicator1()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id =  $data['id'];
            $tag =  $data['tag'];
            $sort =  $data['sort'];
            $model = new Fieldindicator1Search;
            $dataProvider = $model->loadIndicatorEntry($id);
            $dataProvider->pagination=false;
            $dataProvider->sort=false;
            return $this->renderPartial('_setIndicator1', [
                'tag' => (string)$tag,
                'sort' => (string)$sort,
                'dataProvider' => $dataProvider,
                ]);
        }
    }

    /**
     * [fungsi untuk set indicator 2 setiap ruas modal popup]
     * @return mixed
     */
    public function actionSetIndicator2()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id =  $data['id'];
            $tag =  $data['tag'];
            $sort =  $data['sort'];
            $model = new Fieldindicator2Search;
            $dataProvider = $model->loadIndicatorEntry($id);
            $dataProvider->pagination=false;
            $dataProvider->sort=false;
            return $this->renderPartial('_setIndicator2', [
                'tag' => (string)$tag,
                'sort' => (string)$sort,
                'dataProvider' => $dataProvider,
                ]);
        }
    }

    

    /**
     * Deletes an existing Collections model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param double $id
     * @return mixed
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', [
            'type' => 'info',
            'duration' => 500,
            'icon' => 'fa fa-info-circle',
            'message' => Yii::t('app','Success Delete'),
            'title' => 'Info',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
        return $this->redirect(['index']);
    }*/

    /**
     * [fungsi untuk mendapatkan status form entri tiap user]
     * @param  string $for [cat,coll]
     * @return mixed
     */
    protected function getIsAdvanceEntry($for)
    {
        $modeluser = Users::findOne((int)Yii::$app->user->identity->ID);
        $isAdvanceEntry;
        if($for == 'cat')
        {
            $isAdvanceEntry = (int)$modeluser->IsAdvanceEntryCatalog;
        }else{
            $isAdvanceEntry = (int)$modeluser->IsAdvanceEntryCollection;
        }

        return $isAdvanceEntry;
    }

    /**
     * [fungsi untuk menampilkan histori simpan /edit]
     * @return mixed
     */
    public function actionDetailHistori()
    {
       $id = Yii::$app->request->get('id'); 
       $for = Yii::$app->request->get('for'); 
       $modelHistori = \common\models\ModelhistoryCatalogs::find();
       $modelHistori->andFilterWhere(['field_id'=>$id]);
       $modelHistori->andFilterWhere(['table'=>($for == 'cat')? 'catalogs' : 'collections']);
       $modelHistori->orderby('date DESC');
       
       $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $modelHistori,
        ]);
       
       
        return $this->renderAjax('detailHistori', [  
            'dataProvider' => $dataProvider,
        ]);
        
    }

    /**
     * [fungsi untuk menampilkan histori simpan /edit article]
     * @return mixed
     */
    public function actionDetailHistoriArticle()
    {
        $id = Yii::$app->request->get('id');
        $for = Yii::$app->request->get('for');
        $modelHistori = \common\models\ModelhistoryCatalogs::find();
        $modelHistori->andFilterWhere(['field_id'=>$id]);
        $modelHistori->andFilterWhere(['table'=> 'serial_articles']);
        $modelHistori->orderby('date DESC');

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $modelHistori,
        ]);


        return $this->renderAjax('detailHistori', [
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Finds the Collections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Collections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelKarantina($id)
    {
        if (($model = QuarantinedCatalogs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
