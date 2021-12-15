<?php

namespace backend\modules\pengkatalogan\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CatalogHelpers;
use common\models\CatalogSearch;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\validators\Validator;
use yii\helpers\Json;

/**
 * KatalogController implements the CRUD actions for Collections model.
 */
class KatalogExportDataTagController extends Controller
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
        $perpage = 20;
        $getPerPage = $_GET['per-page'];
        if(!empty($getPerPage)){
            $perpage = (int)$_GET['per-page'];
        }

        $rules = Json::decode(Yii::$app->request->get('rules'));
        $searchModel = new CatalogSearch;
        // $dataProvider = $searchModel->searchKatalogDataTag(Yii::$app->request->getQueryParams());
        $dataProvider = $searchModel->advancedSearch(0,$rules);
        $dataProvider->pagination->pageSize=$perpage;
        \Yii::$app->session['SessCatalogTabActive'] = null;
        // echo'<pre>';print_r($dataProvider);die;
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
        return $this->render('create');
    }

    public function actionDownloadExport(){
        $catID = implode(",", $_GET['ids']);

        $sql = "SELECT ID, Title FROM catalogs WHERE ID IN (".$catID.")";
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $filename = 'SemuaDataKatalog.xls';
        $this->actionTemplateExportExcel($data, $filename);
    }

    public function actionDownloadExportAll(){
        $sql = "SELECT ID, Title FROM catalogs";
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $filename = 'SemuaDataKatalog.xls';
        $this->actionTemplateExportExcel($data, $filename);
    }

    public function actionTemplateExportExcel($data, $filename){
        $colTag = array();
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename =".$filename."");
        header("Pragma: no-cahce");
        header("Expires: 0");

        echo '<style> 
                .num {
                  mso-number-format:General;
                }
                .text{
                  mso-number-format:"\@";/*force text*/
                }
                </style>';
        
        echo '<table border="1" align="center">
                <tr>';
            // for ($i=1; $i < 1000 ; $i++) { 
            //     $tag =  str_pad($i, 3, '0', STR_PAD_LEFT);
            //     echo '<th>'."t".$tag.'</th>';
            //     $colTag[] = $tag;
            // }
                echo '<th>No</th>';    
                echo '<th>Judul</th>';    
                echo '<th>Katalog ID</th>';    
                echo '<th>Tag</th>';    
                echo '<th>Indicator 1</th>';    
                echo '<th>Indicator 2</th>';    
                echo '<th>Value</th>';    
            echo'</tr>';
            
            
            $no = 1;
            foreach ($data as $keyData => $valueData) {
                
                $getDetail = Yii::$app->db->createCommand('SELECT * FROM catalog_ruas WHERE CatalogId = "'.$valueData['ID'].'"')->queryAll();

                
                    
                    foreach ($getDetail as $key => $value) {
                        echo '<tr>';
                            if($value['Tag'] == '001'){
                                echo'<td class="text">'.$no++.'</td>';                   
                                echo'<td class="text">'.$valueData['Title'].'</td>';
                            }else{
                                echo'<td class="text"></td>';                   
                                echo'<td class="text"></td>';
                            } 
                        
                            
                            echo'<td class="text">'.$value['CatalogId'].'</td>';                   
                            echo'<td class="text">'.$value['Tag'].'</td>';                   
                            echo'<td class="num">'.$value['Indicator1'].'</td>';                   
                            echo'<td class="num">'.$value['Indicator2'].'</td>';                   
                            echo'<td class="text">'.$value['Value'].'</td>';                       
                        echo '</tr>';
                    }
                // echo '</tr>';
                // foreach ($getDetail as $key => $value) {
                //     echo '<tr>'; 
                //         echo'<td class="text">'.$value['CatalogId'].'</td>';                   
                //         echo'<td class="text">'.$value['Tag'].'</td>';                   
                //         echo'<td class="num">'.$value['Indicator1'].'</td>';                   
                //         echo'<td class="num">'.$value['Indicator2'].'</td>';                   
                //         echo'<td class="text">'.$value['Value'].'</td>';                   
                //     echo '</tr>';
                // }
                echo'<tr></tr>';
                    // foreach ($colTag as $key => $value) {
                    //     // $isi = '<td></td>';
                        
                    //     foreach ($getDetail as $k => $val) 
                    //     {
                            
                    //         if($value == $val['Tag']){
                    //             $isi = '<td>'.$val['Value'].'</td>';
                    //             // echo'<pre>';print_r($val['Value']);
                    //         }
                    //         // else{
                    //         //     $isi = '<td></td>';
                    //         // }

                    //         // echo'<pre>';print_r($value);
                    //     }

                    //     echo $isi;
                    // }
                    
                
                
                
            }
        echo '</table>';
    }
}

?>