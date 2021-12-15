<?php

namespace backend\modules\setting\umum\controllers;

use Yii;
use common\models\JenisPerpustakaan;
use common\models\JenisPerpustakaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


//MODEL

use common\models\MemberFields;
use common\models\MembersOnlineForm;
use common\models\MembersOnlineFormEdit;
use common\models\MembersForm;
use common\models\MembersInfoForm;
use common\models\MembersLoanForm;
use common\models\MembersLoanreturnForm;
use common\models\MembersFormList;
use common\models\MemberFieldSearch;

/**
 * JenisPerpustakaanController implements the CRUD actions for JenisPerpustakaan model.
 */
class JenisPerpustakaanController extends Controller
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
     * Lists all JenisPerpustakaan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JenisPerpustakaanSearch;
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
     * Creates a new JenisPerpustakaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JenisPerpustakaan;

        if ($model->load(Yii::$app->request->post())) {
            $check = Yii::$app->db->createCommand('SELECT ID FROM jenis_perpustakaan WHERE Name = :name')
            ->bindValue(':name', Yii::$app->request->post()['JenisPerpustakaan']['Name'])
            ->queryOne();
            if($check){
                Yii::$app->getSession()->setFlash('danger', [
                    'type' => 'danger',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app', 'Nama Jenis Perpustakaan Sudah Ada'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
                return $this->redirect(['index']);
            }else{
                if($model->save()){
                    $member_field = Yii::$app->db->createCommand('SELECT id FROM member_fields WHERE Mandatory = 1')->queryAll();

                    foreach ($member_field as $key => $value) {
                        // echo'<pre>';print_r($value);die;
                        $membersform = new MembersForm;
                        $membersform->Member_Field_id = $value['id'];
                        $membersform->Jenis_Perpustakaan_id = $model->ID;
                        $membersform->save();
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
                }
                
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing JenisPerpustakaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
                'message' => Yii::t('app', 'Success Edit'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing JenisPerpustakaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($id == Yii::$app->config->get('JenisPerpustakaan')){
            // $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('danger', [
                'type' => 'danger',
                'duration' => 500,
                'icon' => 'fa fa-info-circle',
                'message' => Yii::t('app', 'Failed Delete'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
            return $this->redirect(['index']);
        }else{
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'info',
                'duration' => 500,
                'icon' => 'fa fa-info-circle',
                'message' => Yii::t('app', 'Success Delete'),
                'title' => 'Info',
                'positonY' => Yii::$app->params['flashMessagePositionY'],
                'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
            return $this->redirect(['index']);
        }
        
    }

    /**
     * List For MemberField
     * @param  [type] $id [description]
     * @return [type]     [description]
     */

    public function actionFormdaftaranggota($id)
    {
        $jenis_perpus = JenisPerpustakaan::findOne($id)->Name;
        //Ambil data dari MemberFields.
        $searchModel = new MemberFieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination = false;

        return $this->render('_formDaftarAnggota', [
            'jenis_perpus' => $jenis_perpus,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionFormentrianggotaonline($id)
    {
        $jenis_perpus = JenisPerpustakaan::findOne($id)->Name;
        //Ambil data dari MemberFields.
        $searchModel = new MemberFieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination = false;

        return $this->render('_formEntriAnggotaOnline', [
            'jenis_perpus' => $jenis_perpus,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionFormeditanggotaonline($id)
    {
        $jenis_perpus = JenisPerpustakaan::findOne($id)->Name;
        //Ambil data dari MemberFields.
        $searchModel = new MemberFieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination = false;

        return $this->render('_formEditAnggotaOnline', [
            'jenis_perpus' => $jenis_perpus,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionFormentripeminjaman($id)
    {
        $jenis_perpus = JenisPerpustakaan::findOne($id)->Name;
        //Ambil data dari MemberFields.
        $searchModel = new MemberFieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination = false;

        return $this->render('_formEntriPeminjaman', [
            'jenis_perpus' => $jenis_perpus,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionFormentripengembalian($id)
    {
        $jenis_perpus = JenisPerpustakaan::findOne($id)->Name;
        //Ambil data dari MemberFields.
        $searchModel = new MemberFieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination = false;

        return $this->render('_formEntriPengembalian', [
            'jenis_perpus' => $jenis_perpus,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionForminfoanggota($id)
    {
        $jenis_perpus = JenisPerpustakaan::findOne($id)->Name;
        //Ambil data dari MemberFields.
        $searchModel = new MemberFieldSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->pagination = false;

        return $this->render('_formInfoAnggota', [
            'jenis_perpus' => $jenis_perpus,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionCustom($id)
    {
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

    public function actionSaveCustom()
    {

        if (Yii::$app->request->post('pk')) {
            $selection = (array)Yii::$app->request->post('pk');//typecasting
            $id_perpus = Yii::$app->request->post('id');
            // echo'<pre>';print_r(Yii::$app->request->post());die;
            if (isset($selection)) {
                // Hapus row berdasarkan jenis perpustakaannya.
                $a = MembersForm::deleteAll('Jenis_Perpustakaan_id = :id ', [':id' => $id_perpus]);
                foreach ($selection as $val) {
                    // Insert
                    $model = new MembersForm;
                    $model->Member_Field_id = $val;
                    $model->Jenis_Perpustakaan_id = $id_perpus;
                    $model->save();
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
                $jenis_perpus = JenisPerpustakaan::findOne($id_perpus)->Name;
                return $this->redirect(['custom',
                    'id' => $id_perpus,
                    'jenis_perpus' => $jenis_perpus
                ]);
            }else{
                echo 'a';die;
            }
        }else{
            // echo'<pre>';print_r(Yii::$app->request->post()['id']);die;
            $a = MembersForm::deleteAll('Jenis_Perpustakaan_id = :id ', [':id' => Yii::$app->request->post()['id']]);
            // $jenis_perpus = JenisPerpustakaan::findOne(Yii::$app->request->post()['id'])->Name;
            //     return $this->redirect(['custom',
            //         'id' => $id_perpus,
            //         'jenis_perpus' => $jenis_perpus
            //     ]);
        }
    }


    public function actionSaveDaftarAnggota()
    {

        if (Yii::$app->request->post('pk')) {
            $selection = (array)Yii::$app->request->post('pk');//typecasting
            $id_perpus = Yii::$app->request->post('id');
            if (isset($selection)) {
                // Hapus row berdasarkan jenis perpustakaannya.
                $a = MembersFormList::deleteAll('Jenis_Perpustakaan_id = :id ', [':id' => $id_perpus]);
                foreach ($selection as $val) {
                    // Insert
                    $model = new MembersFormList;
                    $model->Member_Field_id = $val;
                    $model->Jenis_Perpustakaan_id = $id_perpus;
                    $model->save();
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
                $jenis_perpus = JenisPerpustakaan::findOne($id_perpus)->Name;
                return $this->redirect(['formdaftaranggota',
                    'id' => $id_perpus,
                    'jenis_perpus' => $jenis_perpus
                ]);
            }
        }
    }


    public function actionSaveEntriAnggotaOnline()
    {

        if (Yii::$app->request->post('pk')) {
            $selection = (array)Yii::$app->request->post('pk');//typecasting
            $id_perpus = Yii::$app->request->post('id');
            if (isset($selection)) {
                // Hapus row berdasarkan jenis perpustakaannya.use common\models\MembersOnlineForm;
                $a = MembersOnlineForm::deleteAll('Jenis_Perpustakaan_id = :id ', [':id' => $id_perpus]);
                foreach ($selection as $val) {
                    // Insert
                    $model = new MembersOnlineForm;
                    $model->Member_Field_id = $val;
                    $model->Jenis_Perpustakaan_id = $id_perpus;
                    $model->save();
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
                $jenis_perpus = JenisPerpustakaan::findOne($id_perpus)->Name;
                return $this->redirect(['formentrianggotaonline',
                    'id' => $id_perpus,
                    'jenis_perpus' => $jenis_perpus
                ]);
            }
        }
    }

    public function actionSaveEditAnggotaOnline()
    {

        if (Yii::$app->request->post('pk')) {
            $selection = (array)Yii::$app->request->post('pk');//typecasting
            $id_perpus = Yii::$app->request->post('id');
            if (isset($selection)) {
                // Hapus row berdasarkan jenis perpustakaannya.use common\models\MembersOnlineForm;
                $a = MembersOnlineFormEdit::deleteAll('Jenis_Perpustakaan_id = :id ', [':id' => $id_perpus]);
                foreach ($selection as $val) {
                    // Insert
                    $model = new MembersOnlineFormEdit;
                    $model->Member_Field_id = $val;
                    $model->Jenis_Perpustakaan_id = $id_perpus;
                    $model->save();
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
                $jenis_perpus = JenisPerpustakaan::findOne($id_perpus)->Name;
                return $this->redirect(['formeditanggotaonline',
                    'id' => $id_perpus,
                    'jenis_perpus' => $jenis_perpus
                ]);
            }
        }
    }

    public function actionSaveEntriPeminjaman()
    {

        if (Yii::$app->request->post('pk')) {
            $selection = (array)Yii::$app->request->post('pk');//typecasting
            $id_perpus = Yii::$app->request->post('id');
            if (isset($selection)) {
                // Hapus row berdasarkan jenis perpustakaannya.use common\models\MembersOnlineForm;
                $a = MembersLoanForm::deleteAll('Jenis_Perpustakaan_id = :id ', [':id' => $id_perpus]);
                foreach ($selection as $val) {
                    // Insert
                    $model = new MembersLoanForm;
                    $model->Member_Field_id = $val;
                    $model->Jenis_Perpustakaan_id = $id_perpus;
                    $model->save();
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
                $jenis_perpus = JenisPerpustakaan::findOne($id_perpus)->Name;
                return $this->redirect(['formentripeminjaman',
                    'id' => $id_perpus,
                    'jenis_perpus' => $jenis_perpus
                ]);
            }
        }
    }

    public function actionSaveEntriPengembalian()
    {

        if (Yii::$app->request->post('pk')) {
            $selection = (array)Yii::$app->request->post('pk');//typecasting
            $id_perpus = Yii::$app->request->post('id');
            if (isset($selection)) {
                // Hapus row berdasarkan jenis perpustakaannya.use common\models\MembersOnlineForm;
                $a = MembersLoanreturnForm::deleteAll('Jenis_Perpustakaan_id = :id ', [':id' => $id_perpus]);
                foreach ($selection as $val) {
                    // Insert
                    $model = new MembersLoanreturnForm;
                    $model->Member_Field_id = $val;
                    $model->Jenis_Perpustakaan_id = $id_perpus;
                    $model->save();
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
                $jenis_perpus = JenisPerpustakaan::findOne($id_perpus)->Name;
                return $this->redirect(['formentripengembalian',
                    'id' => $id_perpus,
                    'jenis_perpus' => $jenis_perpus
                ]);
            }
        }
    }

    public function actionSaveInfoAnggota()
    {

        if (Yii::$app->request->post('pk')) {
            $selection = (array)Yii::$app->request->post('pk');//typecasting
            $id_perpus = Yii::$app->request->post('id');
            if (isset($selection)) {
                // Hapus row berdasarkan jenis perpustakaannya.use common\models\MembersOnlineForm;
                $a = MembersInfoForm::deleteAll('Jenis_Perpustakaan_id = :id ', [':id' => $id_perpus]);
                foreach ($selection as $val) {
                    // Insert
                    $model = new MembersInfoForm;
                    $model->Member_Field_id = $val;
                    $model->Jenis_Perpustakaan_id = $id_perpus;
                    $model->save();
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
                $jenis_perpus = JenisPerpustakaan::findOne($id_perpus)->Name;
                return $this->redirect(['forminfoanggota',
                    'id' => $id_perpus,
                    'jenis_perpus' => $jenis_perpus
                ]);
            }
        }
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
        if (($model = JenisPerpustakaan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
