<?php

namespace backend\modules\pengkatalogan\controllers;

use common\components\OpacHelpers;

use common\models\SerialArticleFilesSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;

//load model
use common\models\MasterEdisiSerial;
use common\models\MasterEdisiSerialSearch;
use common\models\SerialArticles;
use common\models\SerialArticlesRepeatable;
use common\models\SerialArticlesSearch;
use common\models\SerialArticlefiles;
use common\models\Catalogs;
//load component / helper
use common\components\MarcHelpers;
use common\components\CatalogHelpers;
use common\components\CatalogHelpers2;
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
/**
 * KatalogController implements the CRUD actions for Collections model.
 */
class ArtikelController extends Controller
{
    public  $counterLoop = 0;
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

    public function actionIndex()
    {
        $perpage = 20;
        $getPerPage = $_GET['per-page'];
        if(!empty($getPerPage)){
            $perpage = (int)$_GET['per-page'];
        }

        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new SerialArticlesSearch;
        $dataProvider = $searchModel->advancedSearch($rules);
        $dataProvider->pagination->pageSize=$perpage;
        // \Yii::$app->session['SessCatalogTabActive'] = null;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rules'=> $rules,
            ]);
    }

    public function actionBindCatalogsArticle($id,$catalogid,$type)
    {
        $isUserHasAccess = CatalogHelpers::isUserHasAccess(Yii::$app->user->identity->id);
        //validasi jika user tidak mendapat akses untuk entri koleksi
        if($isUserHasAccess == false)
        {
            return "<div class=\"standard-error-summary\" style=\"color:red; text-align:center\">User ". Yii::$app->user->identity->username." tidak mempunyai akses melakukan entri/koreksi koleksi!</div>";
        }else{
            // echo'<pre>';print_r($type);die;
            if($id == 0)
            {
                $header='Tambah Artikel';
                $model = new SerialArticles();
                $modelReaped = new SerialArticlesRepeatable();
                $model->Catalog_id = NULL;
            }else{
                $model = SerialArticles::findOne($id);

                // $modelArticleRepeat = SerialArticlesRepeatable::find(['serial_article_ID'=>$id])->asArray()->All();
                $modelArticleRepeat = SerialArticlesRepeatable::find()->where(['serial_article_ID' => $id])->asArray()->All(); 
                  if (count($modelArticleRepeat) >= 0) {
                    foreach($modelArticleRepeat as $key => $item)
                    {
                       $arr[$item['article_field']][$key] = $item;
                    }
                        if(sizeof($arr['Kreator']) == '0'){$arrKreator[] = '';}else
                        {foreach($arr['Kreator'] as $key => $item)
                            {
                                $arrKreator[] = $item['value'];
                            }
                        }
                        if(sizeof($arr['Kontributor']) == '0'){$arrKontributor[] = '';}else
                        {foreach($arr['Kontributor'] as $key => $item)
                            {
                                $arrKontributor[] = $item['value'];
                            }
                        }
                        if(sizeof($arr['Subjek']) == '0'){$arrSubjek[] = '';}else
                        {foreach($arr['Subjek'] as $key => $item)
                            {
                                $arrSubjek[] = $item['value'];
                            }
                        }
                  }

                if($model->TANGGAL_TERBIT_EDISI_SERIAL)
                {
                    $model->TANGGAL_TERBIT_EDISI_SERIAL = Yii::$app->formatter->asDate($model->TANGGAL_TERBIT_EDISI_SERIAL, 'php:d-m-Y');
                }
                $header='Edit Article - '.$model->Title;
            }

                $articleRepeat['Kreator']=array();
                $articleRepeat['Kontributor']=array();
                $articleRepeat['Subjek']=array();
                $articleRepeat['Kreator']=$arrKreator;
                $articleRepeat['Kontributor']=$arrKontributor;
                $articleRepeat['Subjek']=$arrSubjek;

            return $this->renderAjax('_modalArtikel', [
                'header' =>  $header,
                'model' =>$model,
                'modelReaped' =>$modelReaped,
                'articleRepeat'=>$articleRepeat,
                'modelArticleRepeat' => $modelArticleRepeat,
                'id'=>$id,
                'catalogid'=>$catalogid,
                'type'=>$type,
                'refer'=>$refer
            ]);

        }
    }

    public function actionSaveCatalogsArticle($id,$catalogId)
    {

        if($id == 0)
        {
            $model = new SerialArticles();
            $model->Catalog_id = NULL;
        }else{
            $model = SerialArticles::findOne($id);
            $modelArticleRepeat = SerialArticlesRepeatable::find(['serial_article_ID'=>$id])->asArray()->All();
        }
        
        $articleId = $model->id;
        $post = Yii::$app->request->post();

        $tglEdisiSerial = MasterEdisiSerial::findOne($_POST['EDISISERIAL']);
        // echo'<pre>';print_r($tglEdisiSerial);die;
        if($post['type'] == '1'){
            $model['EDISISERIAL'] = $tglEdisiSerial->no_edisi_serial;
            $model['TANGGAL_TERBIT_EDISI_SERIAL'] = $tglEdisiSerial->tgl_edisi_serial;    
        }
        
        $rowDeletedCategory = \common\models\SerialArticlesRepeatable::deleteAll('serial_article_ID = :articleId', [':articleId' => $model->id]);

        unset($post['SerialArticles']['EDISISERIAL']);
        $model->Catalog_id = ($post['SerialArticles']['Catalog_id'] !== '') ? $post['SerialArticles']['Catalog_id'] : NULL;

            
        if (Yii::$app->request->isAjax)
        {
            //OpacHelpers::print__r($post);
            $model->load($post);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        elseif($model->load($post))
        {

            $trans = Yii::$app->db->beginTransaction();
            try {

                $model->save();
            // echo '<pre>'; print_r($post);die;

            foreach ($_POST['SerialArticlesRepeatable']['value']['Creator'] as $key => $value) {
                $modelCreator = new SerialArticlesRepeatable();
                $modelCreator->serial_article_ID = $model->id;
                $modelCreator->article_field = 'Kreator';
                $modelCreator->value = $value;
                $modelCreator->save();
            }

            foreach ($_POST['SerialArticlesRepeatable']['value']['Contributor'] as $key => $value) {
                $modelContributor = new SerialArticlesRepeatable();
                $modelContributor->serial_article_ID = $model->id;
                $modelContributor->article_field = 'Kontributor';
                $modelContributor->value = $value;
                $modelContributor->save();
            }
            foreach ($_POST['SerialArticlesRepeatable']['value']['Subject'] as $key => $value) {
                $modelContributor = new SerialArticlesRepeatable();
                $modelContributor->serial_article_ID = $model->id;
                $modelContributor->article_field = 'Subjek';
                $modelContributor->value = $value;
                $modelContributor->save();
            }

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
                            $redirectUrl = Yii::$app->request->referrer;
                        }
                        return $this->redirect($redirectUrl);
                    }

                } else {

                    $trans->commit();

                    //Set active tab
                    // \Yii::$app->session['SessCatalogTabActive'] = 'artikel';

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
                            $redirectUrl = Yii::$app->request->referrer;
                        }
                        return $this->redirect($redirectUrl);
                    }
                }

            } catch (Exception $e) {
                $trans->rollback();
            }
        }
    }

    public function actionBindCatalogsDigitalArticle($id,$catalogId)
    {
        $isUserHasAccess = CatalogHelpers::isUserHasAccess(Yii::$app->user->identity->id);
        //validasi jika user tidak mendapat akses untuk entri koleksi
        if($isUserHasAccess == false)
        {
            return "<div class=\"standard-error-summary\" style=\"color:red; text-align:center\">User ". Yii::$app->user->identity->username." tidak mempunyai akses melakukan entri/koreksi koleksi!</div>";
        }else{

            if($id == 0)
            {
                $header='Tambah Konten Digital Article | ';
                $model = new SerialArticlefiles();
                // $model->Catalog_id = $catalogid;
            }else{
                $model = SerialArticlefiles::findOne($id);
                if($model->TANGGAL_TERBIT_EDISI_SERIAL)
                {
                    $model->TANGGAL_TERBIT_EDISI_SERIAL = Yii::$app->formatter->asDate($model->TANGGAL_TERBIT_EDISI_SERIAL, 'php:d-m-Y');
                }
                $header='Edit Article - '.$model->Title;
            }

            return $this->render('_formDigitalArtikel', [
                'header' =>  $header,
                'model' =>$model,
                'id'=>$id,
                'catalogid'=>$catalogid,
                'refer'=>$refer
            ]);

        }
    }

    public function actionUploadKontenDigitalArtikel() {

        $model = new SerialArticlefiles();
        if (Yii::$app->request->isPost) {
            // print_r(Yii::$app->request->post());die;
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $post= Yii::$app->request->post();

            $size = $this->actionGetFileSize($model->file->size);

            
            $fileflash = $post['fileExecutable'];
            $isCompress = $post['isCompress'];
            $artikelID = $post['artikelID'];
            $getCatalogID = SerialArticles::find()->addSelect(['Catalog_id'])->where(['id' => $artikelID])->one();
            $modelcat =  Catalogs::find()->addSelect(['Worksheet_id'])->where(['ID'=>$getCatalogID['Catalog_id']])->one();
            $worksheetDir=DirectoryHelpers::GetDirWorksheet($modelcat->Worksheet_id);
            // echo'<pre>';print_r($worksheetDir) ; die;
            if($worksheetDir){
                $worksheetDir = $worksheetDir;
            }else{
                $worksheetDir = 'Artikel';
            }
            
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
            $model->sizeFile= $size;
            $model->Articles_id= $artikelID;
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
                                    // 'delete_url'=>'delete-konten-digital?id='.$model->getPrimaryKey(),
                                    // 'delete_type'=>'DELETE'
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

    public function actionCatalogList($q = null, $id = null) {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $type = ($_GET['type'] == '0') ? 'AND worksheets.IsBerisiArtikel = "1"' : 'AND worksheets.ISSERIAL = "1"';
            
            $command = Yii::$app->db->createCommand('
                SELECT catalogs.ID as id, catalogs.Title AS `text` FROM catalogs 
                INNER JOIN worksheets ON worksheets.ID = catalogs.Worksheet_id
                WHERE Title LIKE "%'.$q.'%" '.$type.' LIMIT 20');
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Catalogs::find($id)->Title];
        }
        return $out;
    }

    public function actionGetEdisiSerial(){
        $options = ArrayHelper::map(MasterEdisiSerial::find()->addSelect(['id','no_edisi_serial'])->where(['Catalog_id' => $_GET['catID']])->all(),'id','no_edisi_serial');
        asort($options);
        $options = array_filter($options);
        
        $contentOptions = Html::dropDownList( 'EDISISERIAL',
            'selected option',  
            $options, 
            ['class' => 'select2 col-sm-10','id'=>'ediserial', 'onchange' => 'edisiserial(this)']
            );

        return $contentOptions;
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
            die;
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

    public function actionGetFileSize($bytes){
        
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

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
     * Finds the Collections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SerialArticles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
