<?php

namespace article\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Collections;
use common\models\CollectionmediaSearch;
use common\models\CollectionSearch;
use common\models\CatalogsSearch;
use common\models\Settingparameters;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\web\Request;
use common\models\LoginKeanggotaanForm;
$session = Yii::$app->session;
$session->open();

/**
 * Site controller
 */
class SiteController extends Controller {
    public $layout = 'main-sederhana';
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                /*  [
                  'actions' => ['index'],
                  'allow' => true,
                  'roles' => ['*'],
                  ], */
                ],
            ],
                /* 'verbs' => [
                  'class' => VerbFilter::className(),
                  'actions' => [
                  'logout' => ['post'],
                  ],
                  ], */
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     * 
     */

    public function actionIndex() {

        $modelSetting = new Settingparameters;

        $settingNC['Show'] = $UsulanKoleksi = Yii::$app->config->get('ShowKoleksiTerbaru');
        $settingTC['Show'] = $UsulanKoleksi = Yii::$app->config->get('ShowKoleksiSeringDipinjam');
        $settingUnggul['Show'] = $UsulanKoleksi = Yii::$app->config->get('ShowKoleksiUnggulan');
        $settingNC['Jumlah'] = $UsulanKoleksi = Yii::$app->config->get('KoleksiTerbaruShow');
        $settingTC['Jumlah'] = $UsulanKoleksi = Yii::$app->config->get('KoleksiSeringDipinjamShow');
        $settingUnggul['Jumlah'] = $UsulanKoleksi = Yii::$app->config->get('KoleksiUnggulanShow');


        $sqlNC = "
          SELECT   c.title AS title, c.id AS catalog_id, c.author AS author,
           c.publishyear AS YEAR, 
          TRIM(c.coverurl) AS coverurl, w.Name worksheet_name, w.ID Worksheet_id  
        
           FROM collections s 
          INNER JOIN catalogs c ON c.id = s.catalog_id 
          LEFT JOIN worksheets w ON w.ID = c.Worksheet_id          
           
          WHERE c.ISOPAC =1 and s.ISOPAC =1
          GROUP BY c.id 
          Order by  s.TanggalPengadaan desc,c.ID desc
          LIMIT 0,".$settingNC['Jumlah'].";
        ";
        $sqlTC = " 
          SELECT  COUNT(collection_id) jumlah, c.title AS title, c.id AS catalog_id, c.author AS author,
           c.publishyear AS YEAR, 
          TRIM(c.coverurl) AS coverurl, w.Name worksheet_name, w.ID Worksheet_id
          
           FROM collectionloanitems h 
          INNER JOIN collections s ON s.id = h.collection_id 
          INNER JOIN catalogs c ON c.id = s.catalog_id
          LEFT JOIN worksheets w ON w.ID = c.Worksheet_id 
          
          WHERE c.ISOPAC =1 and s.ISOPAC =1
          GROUP BY c.id 
          ORDER BY jumlah DESC
          LIMIT 0,".$settingTC['Jumlah'].";
        ";
        $sqlUnggul = " 
          SELECT c.title AS title, c.id AS catalog_id, c.author AS author,
           c.publishyear AS YEAR, 
          TRIM(c.coverurl) AS coverurl,
          w.Name worksheet_name, w.ID Worksheet_id 
          FROM kriteria_koleksi k 
          left join catalogs c on k.catalog_id = c.ID  
          LEFT JOIN worksheets w ON w.ID = c.Worksheet_id 
          
          where k.jns_kriteria='koleksi_unggul'  
          and c.ISOPAC =1 
          GROUP BY k.ID 
          ORDER BY k.ID DESC 
        ";

        if($settingNC['Show']=='TRUE')
        $modelNC = Yii::$app->db->createCommand($sqlNC)->queryAll();
        if($settingTC['Show']=='TRUE')
        $modelTC = Yii::$app->db->createCommand($sqlTC)->queryAll();
        if($settingUnggul['Show']=='TRUE')
        $modelUnggul = Yii::$app->db->createCommand($sqlUnggul)->queryAll();


        return $this->render('index', [
                    'modelTC' => $modelTC,
                    'modelNC' => $modelNC,
                    'modelUnggul' => $modelUnggul,
                    'settingNC' => $settingNC,
                    'settingTC' => $settingTC,
                    'settingUnggul' => $settingUnggul,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
      $model = new LoginKeanggotaanForm();

      return $this->renderAjax('login', ['model' => $model,]);
    }


    public function actionLoginanggota()
    {

      $data['_OpacInlislite']=$_POST['_OpacInlislite'];
      $data['LoginKeanggotaanForm']['noanggota']=$_POST['noanggota'];
      $data['LoginKeanggotaanForm']['password']=$_POST['password'];
      $data['login-button']='';
      $model = new LoginKeanggotaanForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(url::previous());
        } else return 'No Anggota / Password Salah'; 
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
