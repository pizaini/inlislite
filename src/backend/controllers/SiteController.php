<?php
namespace backend\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\httpclient\Client;

/**
 * Site controller
 */
class SiteController extends Controller
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        /**
         *  Sending Client
         */
        if($this->check_internet_connection()){
            $urlActivation = Yii::$app->params['urlActivation'];
            $activationCode = Yii::$app->config->get('ActivationCode');
            if($activationCode == ""){
                $client = new Client(['baseUrl' => $urlActivation]);
                $response = $client->get('activation')->send();
                if ($response->isOk) {
                    $ActivationCode = $response->data['ActivationCode'];
                    Yii::$app->config->set('ActivationCode',$ActivationCode);
                    $newClient = $client->post('application', 
                        [
                                    'ActivationCode' => $ActivationCode, 
                                    'PerpustakaanName' => Yii::$app->config->get('NamaPerpustakaan'),
                                    'CreateDate' => date('Y-m-d H:m:s'),
                                    'CreateTerminal' => $this->getPublicIP(),
                                    'Type' => 'PHP'

                        ]
                    )->send();
                }
            }

        }

        $modelLocation = new DynamicModel(['location']);
        $location = Yii::$app->location->get();
        $message = "";
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            
            // echo'<pre>';print_r($cek);die;
            //die;
            if (trim(Yii::$app->request->post('LoginForm')['username']) == "" ) {
                $message = yii::t('app','Nama Pengguna tidak boleh kosong!');
                //"Nama Pengguna atau Kata Sandi salah!"
            } else if (trim(Yii::$app->request->post('LoginForm')['password']) == "") {
                $message = yii::t('app','Kata Sandi tidak boleh kosong!');
            } else {
                 // $cek = \common\models\Users::find()->where('username = "'.$model['username'].'"')->one();
                 $cek = \common\models\Users::find('username=:username', array(':username'=>$model['username']))->one();
                 if($cek['IsActive'] == 1 && $cek['username'] == TRUE){
                    if($model->login()){
                         // jika blm ada location
                         
                         if(empty($location)){
                            // tentukan location
                             $sql = "SELECT ID,Name FROM location_library WHERE ID IN (".
                                 "SELECT LocLib_id FROM userloclibforcol WHERE User_id = ". Yii::$app->user->identity->getId() .
                                 " UNION SELECT LocLib_id FROM userloclibforloan WHERE User_id = " . Yii::$app->user->identity->getId() .
                                 ")";
                             $result = Yii::$app->db->createCommand($sql)->queryAll();

                             if(count($result) == 1){
                                 Yii::$app->location->set($result[0]['ID']);
                                 return $this->goHome();
                             }elseif(count($result) > 1){
                                 //Pilih Lokasinya.
                                 return $this->render('location', [
                                     'model' => $modelLocation,
                                     'modelLocation' => $result,
                                 ]);
                             }else{
                                 // gak ada lokasi yang diset.
                                 $message = yii::t("app","Anda tidak memiliki hak akses dilokasi perpustakaan!");
                             }
                         }else{
                             return $this->goBack();
                         }

                    }else{
                        $message = yii::t('app','Nama Pengguna atau Kata Sandi salah!');
                        
                    }
                 }else if($cek['IsActive'] !== 1 && $cek['username'] == TRUE){
                    $message = yii::t('app','User tidak aktif');
                 }else if($cek['IsActive'] !== 1 && $cek['username'] == FALSE){
                    $message = yii::t('app','Username atau Password salah!');
                 }
                 // matching with user database
                //  if($model->login()){
                //      // jika blm ada location
                     
                //      if(empty($location)){
                //         // tentukan location
                //          $sql = "SELECT ID,Name FROM location_library WHERE ID IN (".
                //              "SELECT LocLib_id FROM userloclibforcol WHERE User_id = ". Yii::$app->user->identity->getId() .
                //              " UNION SELECT LocLib_id FROM userloclibforloan WHERE User_id = " . Yii::$app->user->identity->getId() .
                //              ")";
                //          $result = Yii::$app->db->createCommand($sql)->queryAll();

                //          if(count($result) == 1){
                //              Yii::$app->location->set($result[0]['ID']);
                //              return $this->goHome();
                //          }elseif(count($result) > 1){
                //              //Pilih Lokasinya.
                //              return $this->render('location', [
                //                  'model' => $modelLocation,
                //                  'modelLocation' => $result,
                //              ]);
                //          }else{
                //              // gak ada lokasi yang diset.
                //              $message = "Anda tidak memiliki hak akses dilokasi perpustakaan!";
                //          }
                //      }else{
                //          return $this->goBack();
                //      }

                // }else{
                //     $message = "Username atau Password salah!";
                    
                // }
            }  
        }elseif($modelLocation->load(Yii::$app->request->post())){

            if (trim(Yii::$app->request->post('DynamicModel')['location']) != "" ) {
                Yii::$app->location->set(Yii::$app->request->post('DynamicModel')['location']);
                return $this->goHome();
            }

        }

        if (!\Yii::$app->user->isGuest) {
            if(!empty($location)){
                return $this->goHome();
            }else{
                $sql = "SELECT ID,Name FROM location_library WHERE ID IN (".
                    "SELECT LocLib_id FROM userloclibforcol WHERE User_id = ". Yii::$app->user->identity->getId() .
                    " UNION SELECT LocLib_id FROM userloclibforloan WHERE User_id = " . Yii::$app->user->identity->getId() .
                    ")";

                $result = Yii::$app->db->createCommand($sql)->queryAll();

                if(count($result) == 1){
                    Yii::$app->location->set($result[0]['ID']);
                    return $this->goHome();
                }elseif(count($result) > 1){
                    //Pilih Lokasinya.
                    return $this->render('location', [
                        'model' => $modelLocation,
                        'modelLocation' => $result,
                    ]);
                }else{
                    // gak ada lokasi yang diset.
                }

            }
        }



            Yii::$app->getSession()->setFlash('error', [
                                'type' => \common\components\Alert::TYPE_SUCCESS,
                                'message' => $message]
                            );
            
            return $this->render('login', [
                'model' => $model,
            ]);
        
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->location->remove();
        return $this->goHome();
    }

    /**
     * [check_internet_connection description]
     * @param  string $sCheckHost [description]
     * @return [type]             [description]
     */
    public function check_internet_connection($sCheckHost = 'www.google.com') 
    {
        return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 5);
    }

    public function getPublicIP(){
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('get')
            ->setUrl('http://ipv4bot.whatismyipaddress.com')
            ->send();
        if ($response->isOk) {
            return $response->content;
        }else{
             return "dewa";
        }

    }
}
