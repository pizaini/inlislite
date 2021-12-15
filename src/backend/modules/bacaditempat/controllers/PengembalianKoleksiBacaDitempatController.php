<?php

namespace backend\modules\bacaditempat\controllers;

use Yii;
// use common\models\BacaditempatKembali;
use common\models\Bacaditempat;
// use common\models\BacaditempatKembaliSearch;
use common\models\BacaditempatSearch;
use common\models\Collections;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\base\DynamicModel;

/**
 * PengembalianKoleksiBacaDitempatController implements the CRUD actions for BacaditempatKembali model.
 */
class PengembalianKoleksiBacaDitempatController extends Controller
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
     * Lists all BacaditempatKembali models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BacaditempatSearch;
        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['BacaditempatSearch']['IsReturn'] = '1';
        $dataProvider = $searchModel->search($queryParams);
        $dataProvider->sort = [

        'defaultOrder' => ['UpdateDate' => SORT_DESC],

        'attributes' => [
                  'NoPengunjung', 'collection_id', 'CreateDate', 'CreateTerminal', 'UpdateDate', 'UpdateTerminal', 'Member_id','statusAnggota','MemberNo',
                  'MemberFullname' => [
                      'asc' => ['members.Fullname' => SORT_ASC],
                      'desc' => ['members.Fullname' => SORT_DESC],
                      'label' => Yii::t('app', 'Nama'),
                      // 'default' => SORT_ASC
                  ],
                  'MemberPekerjaan' => [
                      'asc' => ['master_pekerjaan.Pekerjaan' => SORT_ASC],
                      'desc' => ['master_pekerjaan.Pekerjaan' => SORT_DESC],
                      'label' => Yii::t('app', 'Pekerjaan'),
                      // 'default' => SORT_ASC
                  ],
                  'MemberPendidikan' => [
                      'asc' => ['master_pendidikan.Nama' => SORT_ASC],
                      'desc' => ['master_pendidikan.Nama' => SORT_DESC],
                      'label' => Yii::t('app', 'Pendidikan Terakhir'),
                      // 'default' => SORT_ASC
                  ],
                  'MemberJenisKelamin' => [
                      'asc' => ['jenis_kelamin.Name' => SORT_ASC],
                      'desc' => ['jenis_kelamin.Name' => SORT_DESC],
                      'label' => Yii::t('app', 'Jenis Kelamin'),
                      // 'default' => SORT_ASC
                  ],
                  'LocationName' => [
                      'asc' => ['locations.Name' => SORT_ASC],
                      'desc' => ['locations.Name' => SORT_DESC],
                      'label' => Yii::t('app', 'Lokasi Ruangan'),
                      // 'default' => SORT_ASC
                  ],
                  'ColBarcode' => [
                      'asc' => ['collections.NomorBarcode' => SORT_ASC],
                      'desc' => ['collections.NomorBarcode' => SORT_DESC],
                      'label' => Yii::t('app', 'Nomor Barcode'),
                      // 'default' => SORT_ASC
                  ],
                  'CatJudul' => [
                      'asc' => ['catalogs.Judul' => SORT_ASC],
                      'desc' => ['catalogs.Judul' => SORT_DESC],
                      'label' => Yii::t('app', 'Judul'),
                      // 'default' => SORT_ASC
                  ],
                  'CatEdition' => [
                      'asc' => ['catalogs.Edition' => SORT_ASC],
                      'desc' => ['catalogs.Edition' => SORT_DESC],
                      'label' => Yii::t('app', 'Edisi'),
                      // 'default' => SORT_ASC
                  ],
                  'CatPublisher' => [
                      'asc' => ['catalogs.Publisher' => SORT_ASC],
                      'desc' => ['catalogs.Publisher' => SORT_DESC],
                      'label' => Yii::t('app', 'Publisher'),
                      // 'default' => SORT_ASC
                  ],
                  'GuestNama' => [
                      'asc' => ['memberguesses.Nama' => SORT_ASC],
                      'desc' => ['memberguesses.Nama' => SORT_DESC],
                      'label' => Yii::t('app', 'Nama'),
                      // 'default' => SORT_ASC
                  ],
                  'collectionmediaName' => [
                      'asc' => ['collectionmedias.Name' => SORT_ASC],
                      'desc' => ['collectionmedias.Name' => SORT_DESC],
                      'label' => Yii::t('app', 'Bnetuk Fisik'),
                      // 'default' => SORT_ASC
                  ],
                    'locationlocationLibraryName' => [
                         'asc' => ['location_library.Name' => SORT_ASC],
                         'desc' => ['location_library.Name' => SORT_DESC],
                         'label' => Yii::t('app', 'Lokasi Perpustakaan'),
                         'default' => SORT_ASC
                  ],
               ]

        ];


        $model = new DynamicModel(['NomorBarcode',]);
        $model->addRule(['NomorBarcode'], 'string');

        $message = '';

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        if ($model->load(Yii::$app->request->post())) {
            // echo($model->NomorBarcode);die;
            // $model4 = new Bacaditempat;

            if ($model3 = Collections::findOne(['NomorBarcode'=>$model->NomorBarcode])) 
            {
                $model2 = Bacaditempat::find()->where(['collection_id' => $model3->ID, 'Is_return' => '0'])->orderBy(['ID'=>SORT_DESC,])->all();
                // if (($model2 = $this->findBacaditempat(['collection_id'=>$model3->ID])) !== null) 
                // print_r($model2);die;
                if (!empty($model2)) 
                {
                    // print_r($model2);
                    // echo "true";die;
                    $sql = 'UPDATE bacaditempat SET Is_return = "1" , UpdateDate = NOW() WHERE collection_id="'.$model3->ID.'" AND Is_return = "0" ';
                    $command = Yii::$app->db->createCommand($sql);
                    $command->execute();
                    //echo ($sql);die;

                    // $model4->NoPengunjung = $model2->NoPengunjung; 
                    // $model4->collection_id = $model2->collection_id; 
                    // $model4->Member_id = $model2->Member_id; 
                    // $model4->Location_Id = $model2->Location_Id; 

                    // Set collections buku menjadi tersedia (ID = 1)
                    $model3->Status_id = 1;
                    $model3->save(false);

                    // Simpan  bacaditempat_kembali
                    // $model4->save(false);
                    
                    Yii::$app->getSession()->setFlash('success', [
                            'type' => 'info',
                            'duration' => 500,
                            'icon' => 'fa fa-info-circle',
                            'message' => Yii::t('app','Success Save'),
                            'title' => 'Info',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                    return $this->redirect(['index']);
                } 
                else 
                {
                    // Yii::$app->getSession()->setFlash('message', $message);
                    Yii::$app->getSession()->setFlash('warning', [
                        'type' => 'warning',
                        'duration' => 1500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Data buku tidak ada di daftar baca ditempat atau sudah tercatat di pengembalian koleksi Bacaditempat'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                    // $message = "Data buku tidak ditemukan";
                }
                
            } 
            else 
            {
                $message = Yii::t('app','Data buku tidak ditemukan');
            }
            
            
        } 
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model,
            'message' => $message,
        ]);
    }

    /**
     * Displays a single BacaditempatKembali model.
     * @param double $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			 
        return $this->redirect(['view', 'id' => $model->ID]);
        } else {
        return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new BacaditempatKembali model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bacaditempat;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BacaditempatKembali model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param double $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			 Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Edit'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BacaditempatKembali model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param double $id
     * @return mixed
     */
    public function actionDelete($id)
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
    }

    /**
     * Finds the BacaditempatKembali model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return BacaditempatKembali the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bacaditempat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    // protected function findModel($id)
    // {
    //     if (($model = BacaditempatKembali::findOne($id)) !== null) {
    //         return $model;
    //     } else {
    //         throw new NotFoundHttpException('The requested page does not exist.');
    //     }
    // }

}
