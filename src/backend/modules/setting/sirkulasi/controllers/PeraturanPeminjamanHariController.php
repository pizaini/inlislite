<?php

namespace backend\modules\setting\sirkulasi\controllers;

use Yii;
use common\models\base\PeraturanPeminjamanHari;
use common\models\base\PeraturanPeminjamanHariSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Collectioncategorysloanhari;
/**
 * PeraturanPeminjamanHariController implements the CRUD actions for PeraturanPeminjamanHari model.
 */
class PeraturanPeminjamanHariController extends Controller
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
     * Lists all PeraturanPeminjamanHari models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PeraturanPeminjamanHariSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single PeraturanPeminjamanHari model.
     * @param integer $id
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
     * Creates a new PeraturanPeminjamanHari model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PeraturanPeminjamanHari;
        $status=false;
        $trans = Yii::$app->db->beginTransaction();
        if ($model->load(Yii::$app->request->post())) {
            try {
                if($model->save()){
                    $loanHariID = $model->getPrimaryKey();
                    $loancat = $_POST['PeraturanPeminjamanHari']['collectionCategory'];
                    // echo '<pre>'; print_r($loancat); 
                    // Jika Jenis Koleksi tidak null maka insert ke memberloanauthorizeCategory
                    if ($loancat != "") {
                        foreach ($loancat as $key => $value) {
                            $modelCategorysLoanHari = new Collectioncategorysloanhari();
                            $modelCategorysLoanHari->Category_id = $value;
                            $modelCategorysLoanHari->Peminjaman_hari_id = $loanHariID;
                            if($modelCategorysLoanHari->save())
                            {
                                $status=true;
                            }else{
                                $status=false;
                                $trans->rollback();
                                return;
                            }
                        }
                    }
                    if($status==true)
                    {
                         $trans->commit();
                    }
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
            }catch (CDbException $e) {
                $trans->rollback();
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PeraturanPeminjamanHari model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $loanHariID = $model->ID;
            //delete dlu di memberloanauthorizeLocaitons where NoAnggota
            $rowDeleted = Collectioncategorysloanhari::deleteAll('Peminjaman_hari_id = :Peminjaman_hari_id', [':Peminjaman_hari_id' => $loanHariID]);
            $loancat = $_POST['PeraturanPeminjamanHari']['collectionCategory'];
            // Jika Jenis Koleksi tidak null maka insert ke memberloanauthorizeCategory
            if ($loancat != "") {
                foreach ($loancat as $key => $value) {
                    $modelCategorysLoanHari = new Collectioncategorysloanhari();
                    $modelCategorysLoanHari->Category_id = $value;
                    $modelCategorysLoanHari->Peminjaman_hari_id = $loanHariID;
                    $modelCategorysLoanHari->save();
                }
            }

			 Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Edit'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
       return $this->redirect(['index']);
        } else {

            $model->collectionCategory = $this->getCategoryCollectionDefaultByHariID($model->ID);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * To take initial category collection data.
     * @param  int $loanHariID [id hari]
     * @return Array Categorys
     */
    public function getCategoryCollectionDefaultByHariID($loanHariID) {
        $query = new \yii\db\Query;

        $query->select('Category_id')
            ->from('collectioncategorysloanhari')
            ->where('Peminjaman_hari_id = "' . $loanHariID .'"')
            ->orderBy('Category_id');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = $d['Category_id'];
        }
        return $out;
    }

    /**
     * Deletes an existing PeraturanPeminjamanHari model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
     * Finds the PeraturanPeminjamanHari model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PeraturanPeminjamanHari the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PeraturanPeminjamanHari::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
