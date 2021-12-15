<?php

namespace opac\controllers;

use common\components\OpacHelpers;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\CatalogsSearch;
use common\models\Settingparameters;
use common\models\Locations;
use common\models\LocationLibrary;
use common\models\Users;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\ElasticHelper;
use yii\helpers\ArrayHelper;
use common\models\LoginKeanggotaanForm;
use common\models\OpacCounter;
use yii\httpclient\Client;

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
        //redirect to elastic search if enabled
        if (Yii::$app->config->get('OpacIndexer')==1){
            return $this->redirect(array('/search'));
        }

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
function is_connected()
{
    $connected = @fsockopen("ip-api.com", 80); 
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;

}

if(is_connected())
{	
          $ip = \common\components\OpacHelpers::getip();		
        $client = new Client(['baseUrl' => 'http://ip-api.com/json/']);
		  $response = $client->get($ip)->send();
		  
		  if ($response->isOk) {
              $data = json_decode($response->content, true);
              if(Yii::$app->config->get('IsHitCounterOpac') == '0'){
                  $check = OpacCounter::find()->where(['ip_address' => $data['query'], 'DATE(create_at)' => date('Y-m-d')])->count();
				  if(empty($check)){
                      $model = new OpacCounter();
                      $model->ip_address = $data['query'];
                      $model->city = $data['city'];
                      $model->region_name = $data['regionName'];
                      $model->country = $data['country'];
                      $model->lat = $data['lat'];
                      $model->long = $data['lon'];
                      $model->save(false);
                  }
                  return $this->render('index', [
                              'modelTC' => $modelTC,
                              'modelNC' => $modelNC,
                              'modelUnggul' => $modelUnggul,
                              'settingNC' => $settingNC,
                              'settingTC' => $settingTC,
                              'settingUnggul' => $settingUnggul,
                  ]);
              }
			  
              $checkOpacCounter = \common\components\OpacHelpers::tableExist('opac_counter');
			  if($checkOpacCounter !== 0){
                  $model = new OpacCounter();
                  $model->ip_address = $data['query'];
                  $model->city = $data['city'];
                  $model->region_name = $data['regionName'];
                  $model->country = $data['country'];
                  $model->lat = $data['lat'];
                  $model->long = $data['lon'];
                  $model->save(false);
              
			  }
              
              // $model->create_at = $data['lon'];
          }
}		
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

    public function actionLoginPetugas() {
        $this->layout ="main";

        // $this->layout ="login";

        $model = new Users;
        $message = "";

        if ($model->load(Yii::$app->request->post()))
        {
            if (Yii::$app->request->post('Users')['username'] == "" || Yii::$app->request->post('Users')['username'] == "username")
            {
                $message = "Nama Pengguna tidak boleh kosong!";
                //"Nama Pengguna atau Kata Sandi salah!"
            }
            else if (Yii::$app->request->post('Users')['password'] == "")
            {
                $message = "Kata Sandi tidak boleh kosong!";
            }
            else
            {
                $dataUsers = $this->isMatch(Yii::$app->request->post('Users')['username'], Yii::$app->request->post('Users')['password']);
                // $statusUser = Userloclibforloan::findAll(['User_id' => $dataUsers['ID']]);
                $sqltatus = "SELECT ID,Name FROM location_library WHERE ID IN (
                    SELECT LocLib_id FROM userloclibforcol WHERE User_id = ".$dataUsers['ID']."
                    UNION SELECT LocLib_id FROM userloclibforloan WHERE User_id = ".$dataUsers['ID'].")";
                // matching with user database
                if ($dataUsers)
                {
                    $statusUser = Yii::$app->db->createCommand($sqltatus)->queryAll();
                    if ($statusUser == null)
                    {
                        $message = "Tidak ada hak akses!";
                    }
                    else
                    {
                        $cookies = Yii::$app->response->cookies;
                        $cookies->add(new \yii\web\Cookie([
                            'name' => 'usersSetLocationOpac',
                            'value' => $dataUsers,
                        ]));

                        $this->redirect(\Yii::$app->urlManager->createUrl("site/setting-locations"));
                    }
                }
                else
                {
                    $message = "Nama Pengguna atau Kata Sandi salah!";
                }
            }
        }

        Yii::$app->getSession()->setFlash('message', $message);
        return $this->render('loginpetugas', [
            'model' => $model,
        ]);
    }

    public function actionSettingLocations()
    {
        $this->layout ="main";

        $model = new Locations;
        $message = "";

        if ($users = Yii::$app->request->cookies->getValue('usersSetLocationOpac'))
        {

            $loclib = Yii::$app->db->createCommand("SELECT * FROM
                      (SELECT User_id, LocLib_id FROM userloclibforloan
                      WHERE User_id = ".$users['ID']."
                      UNION ALL
                      SELECT User_id, LocLib_id FROM userloclibforcol
                      WHERE User_id = ".$users['ID']."
                      ) a
                    GROUP BY a.LocLib_id ")
                ->queryAll();

            foreach ($loclib as $loclib) {
                $loclibs[$loclib['LocLib_id']] = Yii::$app->db->createCommand("select ID, Name from location_library where ID = '".$loclib['LocLib_id']."'")
                    ->queryOne();

            }

            // print_r($loclibs);die;

            if ($model->load(Yii::$app->request->post())) {

                if (Yii::$app->request->post('Locations')['ID'] == "") {
                    $message = "Pilih Lokasi Terlebih Dahulu";
                } else {
                    // set location_Bukutamu_id cookies
                    $cookies = Yii::$app->response->cookies;
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'location_opac_id',
                        'value' => Yii::$app->request->post('Locations')['ID'],
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'location_opac_name',
                        'value' => Locations::find()->select(['Name', 'ID'])->where(['ID'=>Yii::$app->request->post('Locations')['ID']])->asArray()->one(),
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'location_detail_opac',
                        'value' => LocationLibrary::find()->select(['Name', 'ID','Code','Address'])->where(['ID'=>Yii::$app->request->post('LocationLibrary')])->asArray()->one(),
                        // Yii::$app->request->post('LocationLibrary'),
                    ]));

                    // go to index
                    return $this->goHome();
                }
            }

            Yii::$app->getSession()->setFlash('message', $message);
            return $this->render('settinglocations', [
                'model' => $model,
                'loclibs' => $loclibs,
            ]);
        }
        else
        {
            $this->redirect('index');
        }

        // $loclib = Yii::$app->db->createCommand("select LocLib_id from userloclibforloan where User_id = '".$users['ID']."'")
        //         ->queryAll();
    }

    public function isMatch($username, $password)
    {
        $passwordHash = strtoupper(sha1($password));
        // $model = Users::findAll(['username' => $username, 'password' => $passwordHash]);
        $model = Users::find()->where("username = :username AND password = :passwordHash",[':passwordHash' => $passwordHash,':username' => $username ])->select(['username', 'ID'])->one();
        // $total = Users::findBySql("SELECT * FROM users where username = '$username' AND password = '$passwordHash'")->count();

        if ($model !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public function actionLoadSelecterLocations($idLoc)
    {
        // print_r(Locations::find(['LocationLibrary_id'=>$idLoc])->orderBy('ID')->asArray()->all()) ;
        $model = new Locations;
        echo Html::activeDropDownList($model, 'ID',
            // ArrayHelper::map(Locations::find(['LocationLibrary_id'=> $idLoc ])->select(['Name', 'ID'])->orderBy('ID')->all(), 'ID', 'Name'),
            ArrayHelper::map(Locations::find()->where('LocationLibrary_id = '.$idLoc)->select(['Name', 'ID'])->orderBy('ID')->all(), 'ID', 'Name'),
            ['prompt' => "-- Silahkan pilih lokasi --", 'class'=>'form-control']) ;
    }

    public function actionIndexing(){

        //ElasticHelper::CreateAllIndex(100000);
        //return 'berhasil';
        Yii::$app->getSession()->setFlash('success', [
            'type' => 'info',
            'duration' => 2500,
            'icon' => 'glyphicon glyphicon-ok-sign',
            'message' => Yii::t('app', ' Katalog berhasil disimpan di dalam keranjang'),
            'title' => 'success',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
        ]);
        return $this->goHome();
    }

}
