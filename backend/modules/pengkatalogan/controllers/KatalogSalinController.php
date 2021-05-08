<?php

namespace backend\modules\pengkatalogan\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CatalogHelpers;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\validators\Validator;
use yii\helpers\ArrayHelper;
use common\models\Library;
use common\models\CollectionBiblio;
use common\models\Settingcatalogdetail;
use yii\data\ArrayDataProvider;
use common\models\Catalogs;
use common\models\CatalogRuas;
use common\models\CatalogSubruas;
use common\models\Fields;
use yii\web\Response;

/**
 * KatalogController implements the CRUD actions for Collections model.
 */
class KatalogSalinController extends Controller
{
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
     * Lists all Collections models.
     * @return mixed
     */
    public function actionIndex()
    {
        /*$searchModel = new CatalogSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            ]);*/
        return $this->render('index');
    }

    public function actionSru()
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
            $modelImportMarc->sru($url,$port,$db,$critId,$query,$startRecord,$maxRecord,$protocol,$taglist,'salin');

            //Star parsing
            $arrayData=array();
            CatalogHelpers::getCatalogDataFromTaglist2($taglist,$arrayData);
            
            //print_r($arrayData); die;
            $dataProvider = new ArrayDataProvider([
                //'key'=>'ID',
                'allModels' => $arrayData,
                'sort' => false,
                'pagination' =>false
            ]);

            return $this->renderAjax('records', [
            'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * Creates a new Collections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpload($type="MARC21MRC")
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
                $modelImportMarc->ekstrak2($taglist,$modelbib,$type);
                $arrayData=array();
                CatalogHelpers::getCatalogDataFromTaglist2($taglist,$arrayData);
                
                //print_r($arrayData); die;
                $dataProvider = new ArrayDataProvider([
                    //'key'=>'ID',
                    'allModels' => $arrayData,
                    'sort' => false,
                    'pagination' =>false
                ]);

                return $this->renderAjax('records', [
                'dataProvider' => $dataProvider
                ]);
            }
        }
    }

    public function actionSaveRecords($wksid)
    {
        if (Yii::$app->request->isAjax) 
        {
            $post = Yii::$app->request->post();
            $countsuccess=0;
            $msg='<center><a href=\'../../pengkatalogan/katalog/index\' style=\'color:black;font-weight:bold; text-decoration:blink;\'>[ Lihat Daftar Katalog ]</a></center><br><ul>';
            $trans = Yii::$app->db->beginTransaction();
            try {
                $counter=0;
                foreach ($post['ck'] as $key => $taglist) {
                        $counter++;
                        $taglistdata = array();
                        $taglistdata= json_decode($taglist,true);
                        $catalogFieldValues = array();
                        $catalogFieldValues = CatalogHelpers::convertToCatalogFields2($taglistdata);
                        $BIBID = CatalogHelpers::getBibId(1); 
                        $modelcatalogs = new Catalogs;
                        $modelcatalogs->Worksheet_id=$wksid;
                        $modelcatalogs->BIBID = $BIBID;

                        if(array_key_exists('IsRDA', $catalogFieldValues))
                            $modelcatalogs->IsRDA = trim($catalogFieldValues['IsRDA']);

                        if(array_key_exists('ControlNumber', $catalogFieldValues))
                            $modelcatalogs->ControlNumber = trim($catalogFieldValues['ControlNumber']);

                        if(array_key_exists('Title', $catalogFieldValues))
                            $modelcatalogs->Title = trim($catalogFieldValues['Title']);

                        if(array_key_exists('Author', $catalogFieldValues))
                            $modelcatalogs->Author=trim($catalogFieldValues['Author']);

                        if(array_key_exists('Publisher', $catalogFieldValues))
                            $modelcatalogs->Publisher=trim($catalogFieldValues['Publisher']);

                        if(array_key_exists('PublishLocation', $catalogFieldValues))
                            $modelcatalogs->PublishLocation=trim($catalogFieldValues['PublishLocation']);

                        if(array_key_exists('PublishYear', $catalogFieldValues))
                            $modelcatalogs->PublishYear=trim($catalogFieldValues['PublishYear']);

                        if(array_key_exists('Publikasi', $catalogFieldValues))
                            $modelcatalogs->Publikasi=trim($catalogFieldValues['Publikasi']);

                        if(array_key_exists('Subject', $catalogFieldValues))
                            $modelcatalogs->Subject=trim($catalogFieldValues['Subject']);

                        if(array_key_exists('Edition', $catalogFieldValues))
                            $modelcatalogs->Edition=trim($catalogFieldValues['Edition']);

                        if(array_key_exists('PhysicalDescription', $catalogFieldValues))
                            $modelcatalogs->PhysicalDescription=trim($catalogFieldValues['PhysicalDescription']);

                        if(array_key_exists('ISBN', $catalogFieldValues))
                            $modelcatalogs->ISBN=trim($catalogFieldValues['ISBN']);

                        if(array_key_exists('CallNumber', $catalogFieldValues))
                            $modelcatalogs->CallNumber=trim($catalogFieldValues['CallNumber']);

                        if(array_key_exists('Note', $catalogFieldValues))
                            $modelcatalogs->Note=trim($catalogFieldValues['Note']);

                        if(array_key_exists('Languages', $catalogFieldValues))
                            $modelcatalogs->Languages=trim($catalogFieldValues['Languages']);

                        if(array_key_exists('DeweyNo', $catalogFieldValues))
                            $modelcatalogs->DeweyNo=trim($catalogFieldValues['DeweyNo']);
                        //save catalog
                        
                        if($modelcatalogs->save())
                        {
                            $seq=1;
                            $lastCatalogId=$modelcatalogs->getPrimaryKey();

                            //BIBID
                            $modelcatalogruas = new CatalogRuas;
                            $modelcatalogruas->CatalogId=$lastCatalogId;
                            $modelcatalogruas->Indicator1='#';
                            $modelcatalogruas->Indicator2='#';
                            $modelcatalogruas->Tag='035';
                            $modelcatalogruas->Value='$a '.$BIBID;
                            $modelcatalogruas->Sequence=$seq++;
                            if($modelcatalogruas->save())
                            {
                                $lastRuasId=$modelcatalogruas->getPrimaryKey();
                                $modelcatalogsubruas = new CatalogSubruas;
                                $modelcatalogsubruas->RuasID=$lastRuasId;
                                $modelcatalogsubruas->SubRuas='a';
                                $modelcatalogsubruas->Value=$BIBID;
                                $modelcatalogsubruas->Sequence=1;
                                //save catalog subruas BIBID
                                $modelcatalogsubruas->save();
                            }

                            //print_r($taglistdata); die;
                            foreach ($taglistdata as $key => $dataruas) {
                                if((string)$dataruas['tag'] != '035' && strtolower((string)$dataruas['tag']) != 'leader')
                                {
                                    $modelcatalogruas = new CatalogRuas;
                                    $modelcatalogruas->CatalogId=$lastCatalogId;
                                    if($dataruas['ind1'] != NULL)
                                        $modelcatalogruas->Indicator1=(string)$dataruas['ind1'];
                                    if($dataruas['ind2'] != NULL)
                                        $modelcatalogruas->Indicator2=(string)$dataruas['ind2'];
                                    $modelcatalogruas->Tag=(string)$dataruas['tag'];
                                    $modelcatalogruas->Value=$dataruas['value'];
                                    $modelcatalogruas->Sequence=$seq++;
                                    //save catalog ruas
                                    if($modelcatalogruas->save())
                                    {
                                        $lastRuasId=$modelcatalogruas->getPrimaryKey();
                                        $seqruas=1;
                                        $valuesub= explode("$",substr($dataruas['value'],1,strlen($dataruas['value'])));
                                        for ($i=0; $i < count($valuesub) ; $i++) { 

                                            $koderuas=substr($valuesub[$i],0,1);
                                            $isiruas=substr($valuesub[$i],1,strlen($valuesub[$i]));
                                            $modelcatalogsubruas = new CatalogSubruas;
                                            $modelcatalogsubruas->RuasID=$lastRuasId;
                                            $modelcatalogsubruas->SubRuas=$koderuas;
                                            $modelcatalogsubruas->Value=$isiruas;
                                            $modelcatalogsubruas->Sequence=$seqruas++;
                                            //save catalog subruas
                                            $modelcatalogsubruas->save();
                                        }
                                        
                                    }else{
                                        var_dump($modelcatalogruas->getErrors());
                                        print_r( $modelcatalogruas);
                                    }
                                }
                                
                            }
                            $countsuccess++;
                            $msg .= '<li><span style=\'color:blue\'>Berhasil submit data pada judul <b>: '. $modelcatalogs->Title .'</b></span></li>';
                        }else{
                            $msg .= '<li><span style=\'color:red\'>- Gagal submit data pada judul <b>: '. $modelcatalogs->Title . var_dump($modelcatalogs->getErrors()).'</b></span></li>';
                        }
                    
                }

                $trans->commit();
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $msg.'</ul>';
                
            } catch (Exception $e) {
                $trans->rollback();
            }
            //return $this->redirect(['/pengkatalogan/katalog/index']);
        }
    }

    /**
     * get list of dropdown by fast action.
     * @return mixed
     */
    public function actionGetDropdownSalinkatalog($id)
    {
        $model = new \backend\models\ImportMarcForm();
        return $this->renderAjax('_subDropdownSalinkatalog', [
            'processid' => $id,
            'model' => $model,
            ]);
    }


    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionCreate()
    {
        /*$searchModel = new CatalogSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            ]);*/
        $librarydata= ArrayHelper::map(Library::loadSumber(),'ID','NAME');
        $library=array();
        $library[0]='File Record';
        foreach ($librarydata as $key => $value) {
            $library[$key]=$value;
        }
        return $this->render('create',['library'=>$library]);
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionRecords()
    {
        return $this->render('records', [
                'dataProvider' => $dataProvider
                ]);
    }
}

?>