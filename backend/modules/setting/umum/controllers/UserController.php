<?php

namespace backend\modules\setting\umum\controllers;

// use common\models\Historydata;
// use common\models\HistorydataSearch;
use common\models\ModelHistory;
use common\models\ModelHistorySearch;
use common\models\Userloclibforcol;
use common\models\Userloclibforloan;
use common\models\UserSetting;
use common\models\UsersSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


use mdm\admin\models\Assignment;

/**
 * JenisPerpustakaanController implements the CRUD actions for JenisPerpustakaan model.
 */
class UserController extends Controller {

   


    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'assign' => ['post'],
                    'assign' => ['post'],
                    'revoke' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all JenisPerpustakaan models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new UsersSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single JenisPerpustakaan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Lists all Historydata models.
     * @return mixed
     */
    public function actionHistory($id)
    {
        $searchModel = new ModelHistorySearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['ModelHistorySearch']['user_id'] = $id;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('model-history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // public function actionMyHistory()
    // {
    //     $searchModel = new HistorydataSearch;

    //     $queryParams= Yii::$app->request->getQueryParams();
    //     $queryParams['HistorydataSearch']['CreateBy'] = Yii::$app->user->identity->getId();
    //     $dataProvider = $searchModel->search($queryParams);

    //     return $this->render('history', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }
    
    /**
     * [actionMyHistory description]
     * @return [type] [description]
     */
    public function actionMyHistory()
    {
        $searchModel = new ModelHistorySearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['ModelHistorySearch']['user_id'] = Yii::$app->user->identity->getId();
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('model-history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * [actionPencarian description]
     * @return [type] [description]
     */
    public function actionPencarian()
    {
        $request    = Yii::$app->request;
        $Keyword    = urldecode($_GET['katakunci']);
        $ruas       = $_GET['ruas'];

        $searchModel = new CatalogsMasterSearch(['IsOPAC' => 1]);
        //$searchModel = new CatalogsSearch();
        $queryParams = Yii::$app->request->getQueryParams();
        $queryParams['CatalogsMasterSearch'][$_GET['ruas']] = $_GET['katakunci'];
        $queryParams['CatalogsMasterSearch']['Member_id'] = $_GET['Member_id'];
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('pencarian', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }



    /**
     * Creates a new JenisPerpustakaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new UserSetting;
        $model2 = new Userloclibforcol;
        $model3 = new Userloclibforloan;


        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post()) && $model3->load(Yii::$app->request->post())) {
            $model->password = sha1($model->username);
            $model->Role_id = 1;
            if ($model->save()) {

                $model2->User_id = $model->ID;
                $arr = $model2->LocLib_id;

                if ($arr !== "") {
                    foreach ($arr as $loc => $value) {

                        //$model2->LocLib_id=$value;
                        $sql = "INSERT INTO `userloclibforcol` (`User_id`, `LocLib_id`) VALUES (" . $model2->User_id . ", " . $value . ");";
                        Yii::$app->db->createCommand($sql)->query();
                    }
                }

                $model3->User_id = $model->ID;
                $array = $model3->LocLib_id;

                if ($array !== "") {
                    foreach ($array as $sir => $sirkulasi) {

                        //$model3->LocLib_id=$sirkulasi;
                        $sql = "INSERT INTO `userloclibforloan` (`User_id`, `LocLib_id`) VALUES (" . $model3->User_id . ", " . $sirkulasi . ");";
                        Yii::$app->db->createCommand($sql)->query();
                    }
                }


                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
                return $this->redirect(['user/update','id' => $model->ID,'edit'=>'t']);
            } else {
                var_dump($model->getErrors());
                return $this->render('create', [
                    'model' => $model,
                    'model2' => $model2,
                    'model3' => $model3,
                    ]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'model2' => $model2,
                        'model3' => $model3,
            ]);
        }
    }

    /**
     * Updates an existing JenisPerpustakaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $username = Yii::$app->user->identity->username;
        $modelUser = UserSetting::findByUsername($username);

        
        if($id == '46' || $id == '43' || $id == '33'|| $id == '1'){
            // Periksa apakah yg update adalah user tersebut.
            if(!(Yii::$app->user->identity->getId() == '46' || Yii::$app->user->identity->getId() == '43')){

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Anda tidak berhak mengkoreksi data User System.'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
                return $this->redirect(['index']);
            }
        }

        $model = $this->findModel($id);

        // print_r($model);die;

        $model2 = new Userloclibforcol;
        $model3 = new Userloclibforloan;
        $modelAssignment = $this->findModelAssignment($id);
        // print_r($modelAssignment);die;

        $iddatabase = $model->ID;
        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post()) && $model3->load(Yii::$app->request->post())) {
            if ($model->save()) {

                $model2->User_id = $model->ID;
                $arr = $model2->LocLib_id;
                    $sqldelcol = "DELETE FROM `userloclibforcol` WHERE `User_id`=" . $iddatabase . ";";
                    Yii::$app->db->createCommand($sqldelcol)->query();

                if ($arr !== "") {
                  foreach ($arr as $loc => $value) {

                      //$model2->LocLib_id=$value;

                      $sql = "INSERT INTO `userloclibforcol` (`User_id`, `LocLib_id`) VALUES (" . $model2->User_id . ", " . $value . ");";
                      Yii::$app->db->createCommand($sql)->query();
                  }
                }
                    $sqldelloan = "DELETE FROM `userloclibforloan` WHERE `User_id`=" . $iddatabase . ";";
                    Yii::$app->db->createCommand($sqldelloan)->query();
                $model3->User_id = $model->ID;
                $array = $model3->LocLib_id;

                if ($array !== "") {
                  foreach ($array as $sir => $sirkulasi) {

                      //$model3->LocLib_id=$sirkulasi;
                      $sql = "INSERT INTO `userloclibforloan` (`User_id`, `LocLib_id`) VALUES (" . $model3->User_id . ", " . $sirkulasi . ");";
                      Yii::$app->db->createCommand($sql)->query();
                  }
                }

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
                return $this->redirect(['index']);
            } else {
               return $this->render('create', [
                  'model' => $model,
                  'model2' => $model2,
                  'model3' => $model3,
                  ]);
            }
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'model2' => $model2,
                        'model3' => $model3,
                        'modelUser'=>$modelUser,
                        'modelAssignment' => $modelAssignment,
                        
            ]);
        }
    }

    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return Assignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAssignment($id)
    {
        $class ='common\models\User';
        if (($user = $class::findOne($id)) !== null) {
            return new Assignment($id, $user);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Deletes an existing JenisPerpustakaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {

     if($id == '46' || $id == '43' || $id == '33'|| $id == '1'){
         Yii::$app->getSession()->setFlash('error', [
             'type' => 'error',
             'duration' => 500,
             'icon' => 'fa fa-info-circle',
             'message' => Yii::t('app', 'User System tidak dapat dihapus.'),
             'title' => 'Info',
             'positonY' => Yii::$app->params['flashMessagePositionY'],
             'positonX' => Yii::$app->params['flashMessagePositionX']
         ]);
     }else{
         //               $sql="INSERT INTO `userloclibforcol` (`User_id`, `LocLib_id`) VALUES (".$model2->User_id.", ".$value.");";
         $this->findModel($id)->delete();
         $col = "DELETE FROM `userloclibforcol` WHERE `User_id` = " . $id . "; ";
         Yii::$app->db->createCommand($col)->query();
         $loan = "DELETE FROM `userloclibforloan` WHERE `User_id` = " . $id . "; ";
         Yii::$app->db->createCommand($loan)->query();
         //$this->findModelcol($id)->delete();
         //      $this->findModelloan($id)->delete();
         Yii::$app->getSession()->setFlash('success', [
             'type' => 'info',
             'duration' => 500,
             'icon' => 'fa fa-info-circle',
             'message' => Yii::t('app', 'Success Delete'),
             'title' => 'Info',
             'positonY' => Yii::$app->params['flashMessagePositionY'],
             'positonX' => Yii::$app->params['flashMessagePositionX']
         ]);
     }

        return $this->redirect(['index']);
    }

    public function actionDeleteHistory($id) {

        $data = Historydata::findOne($id);
        $data->delete();

        Yii::$app->getSession()->setFlash('success', [
            'type' => 'info',
            'duration' => 500,
            'icon' => 'fa fa-info-circle',
            'message' => Yii::t('app', 'Success Delete'),
            'title' => 'Info',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
        ]);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * List For MemberField
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionCustom($id) {
        $jenis_perpus = JenisPerpustakaan::findOne($id)->Name;
        //Ambil data dari MemberFields.
        $searchModel = new MemberFieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination = false;

        return $this->render('_listMemberFields', [
                    'jenis_perpus' => $jenis_perpus,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }


    public function actionChangePassword()
    {
        $id = $_SESSION['__id'];

        $user = Yii::$app->user->identity;
        $loadedPost = $user->load(Yii::$app->request->post());

        if ($loadedPost && $user->validate()) {
            $user->password = sha1($user->newPassword);
            $user->password_hash = Yii::$app->security->generatePasswordHash($user->newPassword);
            $user->save(false);

            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 500,
                'icon' => 'fa fa-info-circle',
                'message' => Yii::t('app', 'Success Change Password'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
            return $this->refresh();
        }

        return $this->render('change-password', [
            'user' => $user,
            ]);

    }


    /**
     * Finds the JenisPerpustakaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JenisPerpustakaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserSetting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * [findModel3 description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    protected function findModel3($id) {
        if (($model = Userloclibforcol::findOne($id)) !== null)
        {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionResetPassword() {
        $id = Yii::$app->request->post('ID');
        $model = $this->findModel($id);
        $username = trim($model->username);
        if(Yii::$app->request->post()){
            if ($model !== null) {
                $model->Password = $model->username;
                if($model->save(false)){
                    return 1;
                }else{
                    return '0';
                }
            } else {
                return '0';
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
    }

    /**
     * Assign items
     * @param string $id
     * @return array
     */
    public function actionAssign($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = new Assignment($id);
        $success = $model->assign($items);
        Yii::$app->getResponse()->format = 'json';
        return array_merge($model->getItems(), ['success' => $success]);
    }

    /**
     * Assign items
     * @param string $id
     * @return array
     */
    public function actionRevoke($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = new Assignment($id);
        $success = $model->revoke($items);
        Yii::$app->getResponse()->format = 'json';
        return array_merge($model->getItems(), ['success' => $success]);
    }

}
