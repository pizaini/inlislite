<?php
namespace guestbook\controllers;

use Yii;

use common\models\LoginForm;
use common\models\Memberguesses;
use common\models\Groupguesses;

use common\models\Locations;
use common\models\LocationLibrary;
use common\models\Userloclibforloan;

use common\models\Users;
use common\models\Settingparameters;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\CookieCollection;
use yii\web\Controller;
use yii\web\Response;
use yii\validators\EmailValidator;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Site controller
 */
class SiteController extends Controller
{

   public $layout = "buku-tamu";
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*'access' => [
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
            ],*/
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

    public function cekLogin()
    {
      $cookies = Yii::$app->request->cookies;
      if ($cookies->getValue('location_Bukutamu_id') !== null) {
          // go to member page
          // $this->redirect(\Yii::$app->urlManager->createUrl("site/pindai-anggota"));
      } else {
          $this->redirect(\Yii::$app->urlManager->createUrl("site/login"));
      }
    }

    /**
     * Buku tamu main Index
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Locations;
        $message = "";

        $cookies = Yii::$app->request->cookies;
        if ($cookies->getValue('location_Bukutamu_id') !== null) {
            // go to member page
            $this->redirect(\Yii::$app->urlManager->createUrl("site/member"));
        } else {
            $this->redirect(\Yii::$app->urlManager->createUrl("site/login"));
        }
    }

    /**
     * Buku tamu login web
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout ="buku-tamu";
        // $this->layout ="login";

        $model = new Users;
        $message = "";

        if ($model->load(Yii::$app->request->post()))
        {
            if (Yii::$app->request->post('Users')['username'] == "" || Yii::$app->request->post('Users')['username'] == "username")
            {
                $message = Yii::t('app', 'Nama Pengguna tidak boleh kosong!');
                //"Nama Pengguna atau Kata Sandi salah!"
            }
            else if (Yii::$app->request->post('Users')['password'] == "")
            {
                $message = Yii::t('app', 'Kata Sandi tidak boleh kosong!');
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
                            'name' => 'usersSetLocationBukutamu',
                            'value' => $dataUsers,
                            ]));

                        $this->redirect(\Yii::$app->urlManager->createUrl("site/setting-locations"));
                    }
                }
                else
                {
                    $message = Yii::t('app', 'Nama Pengguna atau Kata Sandi salah!');
                }
            }
        }

        Yii::$app->getSession()->setFlash('message', $message);
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Buku tamu member website
     * @return mixed
     */
    public function actionMember()
    {
        $this->cekLogin();

        $this->layout ="buku-tamu";
        $model = new Memberguesses;
        $memberID = Yii::$app->request->post('MemberID');
        $PhotoUrl = Yii::$app->request->post('PhotoUrl');
        $model->Location_Id = Yii::$app->request->cookies->getValue('location_Bukutamu_id');


        if ($model->load(Yii::$app->request->post())) {
            // echo'<pre>';print_r(Yii::$app->request->post());die;
            $IsVisitsDestinationVal = Yii::$app->request->post('IsVisitsDestination');
            if ($model->TujuanKunjungan_Id == "" && !empty($IsVisitsDestinationVal)) { // validasi Tujuan Kunjungan
                $message = "Pilih Tujuan Kunjungan Anda Terlebih Dahulu";
            } else {
                if(Yii::$app->config->get('CountingBukuTamu') == '1'){
                    if ($model->save()) {
                        $message = 'Selamat Datang '.Html::encode($model->Nama);
                        // clear
                        $model = new Memberguesses;
                        $memberID = null;
                        $PhotoUrl = null;
                    } else {
                        $messageCount = 0;
                        $message = "Silahkan Lengkapi Isian Data Anda";
                        $atributs = $model->attributeLabels();
                        foreach ($model->errors as $errorKey => $error) {
                            if ($messageCount == 0) {
                                //$message .=" ". $atributs[$errorKey];
                            } else {
                                //$message .=" ,". $atributs[$errorKey];
                            }
                        }
                        //$message .= " dengan benar";
                    }
                }else{
                    $check_bukutamu = Yii::$app->db->createCommand('SELECT NoAnggota FROM memberguesses WHERE DATE(CreateDate) = DATE(NOW()) AND NoAnggota = :memberno');
                    $check_bukutamu->bindValue(':memberno', Yii::$app->request->post('Memberguesses')['NoAnggota']);
                    $check_bukutamu = $check_bukutamu->queryOne();
                    // echo'<pre>';print_r($check_bukutamu);die;
                    if(empty($check_bukutamu)){
                        $memberguess = new \common\models\Memberguesses;
                        $memberguess->NoAnggota = Yii::$app->request->post('Memberguesses')['NoAnggota'];
                        $memberguess->Nama = Yii::$app->request->post('Memberguesses')['Nama'];
                        $memberguess->Alamat = Yii::$app->request->post('Memberguesses')['Alamat'];
                        $memberguess->Location_Id = Yii::$app->request->cookies->getValue('location_Bukutamu_id');
                        $memberguess->TujuanKunjungan_Id = Yii::$app->request->post('Memberguesses')['TujuanKunjungan_Id'];
                        if($memberguess->save()){
                            $message = 'Selamat Datang '.Html::encode($model->Nama);
                            // clear
                            $model = new Memberguesses;
                            $memberID = null;
                            $PhotoUrl = null;
                        } else {
                            $messageCount = 0;
                            $message = "Silahkan Lengkapi Isian Data Anda";
                            $atributs = $model->attributeLabels();
                            foreach ($model->errors as $errorKey => $error) {
                                if ($messageCount == 0) {
                                    //$message .=" ". $atributs[$errorKey];
                                } else {
                                    //$message .=" ,". $atributs[$errorKey];
                                }
                            }
                            //$message .= " dengan benar";
                        }
                    }
                }
                
            }
            Yii::$app->getSession()->setFlash('message', $message);
            return $this->redirect("member");
        }
        return $this->render('member', [
            'model' => $model,
            'MemberID' => $memberID,
            'PhotoUrl' => $PhotoUrl,
            'totalVisitors' => $this->getTotalVisitors(),
            'audio'=> $this->getAudioBukutamu(),
        ]);
    }

    /**
     * Buku tamu pilih lokasi
     * @return mixed
     */
    public function actionSettingLocations()
    {
        $this->layout ="buku-tamu";

        $model = new Locations;
        $message = "";

        if ($users = Yii::$app->request->cookies->getValue('usersSetLocationBukutamu')) 
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
                        'name' => 'location_Bukutamu_id',
                        'value' => Yii::$app->request->post('Locations')['ID'],
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'location_detail',
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

    /**
     * [actionLoadSelecterLocations description]
     * @param  [type] $idLoc [description]
     * @return [type]        [description]
     */
    public function actionLoadSelecterLocations($idLoc)
    {
        // print_r(Locations::find(['LocationLibrary_id'=>$idLoc])->orderBy('ID')->asArray()->all()) ;
        $model = new Locations;
        echo Html::activeDropDownList($model, 'ID',
            // ArrayHelper::map(Locations::find(['LocationLibrary_id'=> $idLoc ])->select(['Name', 'ID'])->orderBy('ID')->all(), 'ID', 'Name'),
            ArrayHelper::map(Locations::find()->where('LocationLibrary_id = '.$idLoc)->select(['Name', 'ID'])->orderBy('ID')->all(), 'ID', 'Name'),
            ['prompt' => Yii::t('app', '-- Silahkan pilih lokasi --'), 'class'=>'form-control']) ;
    }


    /**
     * Buku tamu nonmember website index
     * @return mixed
     */
    public function actionNonmember()
    {
        $this->cekLogin();

        $this->layout ="buku-tamu";

        $model = new Memberguesses;
        $model->NoAnggota = null;
        $model->Location_Id = Yii::$app->request->cookies->getValue('location_Bukutamu_id');

        if ($model->load(Yii::$app->request->post())) {

            $IsVisitsDestinationVal = Yii::$app->request->post('IsVisitsDestination');
           
            $tjID = Yii::$app->request->post()['Memberguesses']['TujuanKunjungan_Id'];
            $ifID = Yii::$app->request->post()['Memberguesses']['Information'];
            
            if ((isset($tjID) && empty($tjID) )|| (isset($ifID) && empty($ifID))) {
               $message = Yii::t('app', 'Harap Pilih Tujuan Kunjungan');       
            } elseif ($model->Nama == "") { // validasi Nama 
                $message = Yii::t('app', 'Mohon lengkapi data anda');
            } elseif ($model->JenisKelamin_id == "") { // validasi JenKel
                $message = Yii::t('app', 'Mohon lengkapi data anda');
            } else if ($model->Alamat == "") { // validasi Alamat 
                $message = Yii::t('app', 'Mohon lengkapi data anda');
            } else if ($model->TujuanKunjungan_Id == "" && !empty($IsVisitsDestinationVal)) { // validasi Tujuan Kunjungan
                $message = Yii::t('app', 'Harap Pilih Tujuan Kunjungan');
            } else {


           /* elseif ($model->Nama == "") { // validasi Nama 
                $message = "Mohon lengkapi data Anda";
            } elseif ($model->JenisKelamin_id == "") { // validasi JenKel
                $message = "Mohon lengkapi data Anda";
            } else if ($model->Alamat == "") { // validasi Alamat 
                $message = "Mohon lengkapi data Anda";
            } else if ($model->TujuanKunjungan_Id == "" && !empty($IsVisitsDestinationVal)) { // validasi Tujuan Kunjungan
                $message = "Pilih Tujuan Kunjungan Anda Terlebih Dahulu";
            } else {*/
                // generate guest number if IsGenerateVisitorNumber
                $location = Locations::find()
                    ->where(['ID' => Yii::$app->request->cookies->getValue('location_Bukutamu_id')])
                    ->one();
                if (!empty($_COOKIE["location_Bukutamu_id"])) {
                    if ($location->IsGenerateVisitorNumber == 1) {
                        $number = Yii::$app->db->createCommand("SELECT hasil35dec('GST') FROM DUAL")
                            ->queryScalar();
                        // echo'<pre>';print_r($model->Location_Id.$number);die;
                        $model->NoPengunjung = $number.$model->Location_Id;
                    }
                    else
                    {
                        $number = Yii::$app->db->createCommand("SELECT hasil35dec('GST') FROM DUAL")
                            ->queryScalar();
                        $model->NoPengunjung = $number.$model->Location_Id;
                    }
                }

                if ($model->save())
                {
                    $message = 'Selamat Datang '.$model->Nama;

                    // Play audio selamat datang
                    Yii::$app->getSession()->setFlash('audio', "<script>welcomeAudio.play();</script>");

                    // set barcode number
                    if ($location->IsGenerateVisitorNumber == 1)
                    {
                        Yii::$app->getSession()->setFlash('barcodeNumber', $model->NoPengunjung);
                    }

                    // clear
                    $model = new Memberguesses;
                }
                else
                {
                    $messageCount = 0;
                    $message = Yii::t('app', 'Mohon lengkapi data anda');
                    $atributs = $model->attributeLabels();
                    foreach ($model->errors as $errorKey => $error) {
                        if ($messageCount == 0) {
                            //$message .=" ". $atributs[$errorKey];
                        } else {
                            //$message .=" ,". $atributs[$errorKey];
                        }
                    }
                    //$message .= " dengan benar";
                }
            }
            Yii::$app->getSession()->setFlash('message', Html::encode($message));
            // return $this->redirect("nonmember");
        }

        return $this->render('nonmember', [
            'model' => $model,
            'totalVisitors' => $this->getTotalVisitors(),
            'audio'=> $this->getAudioBukutamu(),
        ]);
    }

    /**
     * Buku tamu grup website
     * @return mixed
     */
    public function actionGroup()
    {
        $this->cekLogin();

        $this->layout ="buku-tamu";

        $model = new Groupguesses;
        $model->Location_ID = Yii::$app->request->cookies->getValue('location_Bukutamu_id');
        $validator = new EmailValidator();
        if ($model->load(Yii::$app->request->post())) {
            // validasi else
            // jumlah-jumlah
            $jmlKelamin = $model->CountLaki + $model->CountPerempuan;
            $jmlPendidikan = $model->CountSD
                + $model->CountSMP
                + $model->CountSMA
                + $model->CountD1
                + $model->CountD2
                + $model->CountD3
                + $model->CountS1
                + $model->CountS2
                + $model->CountS3;
            $jmlPekerjaan = $model->CountPNS
                + $model->CountPSwasta
                + $model->CountPeneliti
                + $model->CountGuru
                + $model->CountDosen
                + $model->CountPensiunan
                + $model->CountTNI
                + $model->CountWiraswasta
                + $model->CountPelajar
                + $model->CountMahasiswa
                + $model->CountLainnya;

            $IsVisitsDestinationVal = Yii::$app->request->post('IsVisitsDestination');

            if ($model->NamaKetua == "") { // validasi Nama Ketua Rombongan
                $message = "Silahkan Isi Nama Ketua Rombongan";
            } else if ($model->NomerTelponKetua == "") { // validasi Nomor Telepon Ketua Rombongan
                $message = "Silahkan Isi Nomor Telepon Ketua Rombongan";
            } else if ($model->AsalInstansi == "") { // validasi Nama Instansi
                $message = "Silahkan Isi Nama Instansi";
            } else if ($model->AlamatInstansi == "") { // validasi Alamat Instansi
                $message = "Silahkan Isi Alamat Instansi";
            } else if ($model->TeleponInstansi == "") { // validasi Nomor Telepon Instansi
                $message = Yii::t('app', 'Silahkan Isi Nomor Telepon Instansi');
            } else if ($model->EmailInstansi == "") { // validasi Alamat Email Instansi
                $message = Yii::t('app', 'Silahkan Isi Alamat Email Instansi');
            } else if (!$validator->validate($model->EmailInstansi)) { // validasi Email
                $message = Yii::t('app', 'Alamat email tidak valid!');
            } else if ($jmlPekerjaan == 0) { // validasi Data Pekerjaan
                $message = Yii::t('app', 'Silahkan Isi Data Pekerjaan');
            } else if ($model->CountPersonel > $jmlPekerjaan) { // validasi Data Pekerjaan
                $perspekmin = $model->CountPersonel - $jmlPekerjaan;
                $message = Yii::t('app', 'Jumlah Data Pekerjaan dengan Jumlah Personel Tidak Sesuai').'<br>'.Yii::t('app', 'Kurang '). $perspekmin .Yii::t('app', ' Orang');
            } else if ($model->CountPersonel < $jmlPekerjaan) { // validasi Data Pekerjaan
                $perspekplus = $jmlPekerjaan - $model->CountPersonel;
                $message = "Jumlah Data Pekerjaan dengan Jumlah Personel Tidak Sesuai." . "\n" . "Lebih " . $perspekplus . " Orang";
            } else if ($jmlPendidikan == 0) { // validasi Data Pendidikan
                $message = Yii::t('app', 'Silahkan Isi Data Pendidikan');
            }else if ($model->CountPersonel > $jmlPendidikan ) { // validasi Data Pendidikan
                $perpenmin = $model->CountPersonel - $jmlPendidikan;
                $message = "Jumlah Data Pendidikan dengan Jumlah Personel Tidak Sesuai." . "\n". "Minus " . $perpenmin . " Orang";
            } else if ($model->CountPersonel < $jmlPendidikan) { // validasi Data Pendidikan
                $perpenplus = $jmlPendidikan - $model->CountPersonel;
                $message = "Jumlah Data Pendidikan dengan Jumlah Personel Tidak Sesuai." . "\n" . "Lebih " . $perpenplus . " orang";
            } else if ($jmlKelamin == 0 ) { // validasi Data Jenis Kelamin
                $message = "Silahkan Isi Data Jenis Kelamin";
            } else if ($model->CountPersonel > $jmlKelamin) { // validasi Data Jenis Kelamin
                $perkelmin = $model->CountPersonel - $jmlKelamin;
                $message = "Jumlah Data Jenis Kelamin dengan Jumlah Personel Tidak Sesuai." . "\n" . "Minus " . $perkelmin . " Orang";
            } else if ($model->CountPersonel < $jmlKelamin ) { // validasi Data Jenis Kelamin
                $perkelplus = $jmlKelamin - $model->CountPersonel;
                $message = "Jumlah Data Jenis Kelamin dengan Jumlah Personel Tidak Sesuai." . "\n" . "Lebih " . $perkelplus . " Orang";
            } else if ($model->TujuanKunjungan_ID == "" && !empty($IsVisitsDestinationVal)) { // validasi Tujuan Kunjungan
                $message = "Pilih Tujuan Kunjungan Anda Terlebih Dahulu";
            } else {
                // generate group number if IsGenerateVisitorNumber
                $location = Locations::find()
                    ->where(['ID' => Yii::$app->request->cookies->getValue('location_Bukutamu_id')])
                    ->one();
                if (!empty($_COOKIE["location_Bukutamu_id"])) {
                    if ($location->IsGenerateVisitorNumber == 1) {
                        $number = Yii::$app->db->createCommand("SELECT hasil35dec('GRP') FROM DUAL")
                            ->queryScalar();
                        $model->NoPengunjung = $number;
                    }
                }
                if ($model->save()) {
                    $message = 'Selamat Datang '.$model->AsalInstansi;

                           // Play audio selamat datang
                    Yii::$app->getSession()->setFlash('audio', "<script>welcomeAudio.play();</script>");

                    // set barcode number
                    if ($location->IsGenerateVisitorNumber == 1) {
                        Yii::$app->getSession()->setFlash('barcodeNumber', $model->NoPengunjung);
                    }

                    // clear
                    $model = new Groupguesses;
                    return $this->redirect("group");
                } else {
                    $messageCount = 0;
                    $message = "Tolong masukkan data ";
                    $atributs = $model->attributeLabels();
                    foreach ($model->errors as $errorKey => $error) {
                        if ($messageCount == 0) {
                            $message .=" ". $atributs[$errorKey];
                        } else {
                            $message .=" ,". $atributs[$errorKey];
                        }
                    }
                    $message .= " dengan benar";
                }
            }
            Yii::$app->getSession()->setFlash('message', Html::encode($message));
            // return $this->redirect("group");
        }

        return $this->render('group', [
            'model' => $model,
            'totalVisitors' => $this->getTotalVisitors(),
            'totalGroup' => $this->getTotalGroup(),
            'audio'=> $this->getAudioBukutamu(),
        ]);
    }

    /**
     * Get Total Visitors
     * @return total
     */
    public function getTotalVisitors()
    {
        $IDLOcation = Yii::$app->request->cookies->getValue('location_Bukutamu_id');
        $today     = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
        $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
        $totalSingle = Memberguesses::findBySql("SELECT * FROM memberguesses where CreateDate  BETWEEN '$today' AND '$tomorrow' AND Location_Id = '$IDLOcation'")->count();

        $totalGroups = Yii::$app->db->createCommand("SELECT sum(CountPersonel) FROM groupguesses where CreateDate  BETWEEN  '$today' AND '$tomorrow' AND Location_ID = '$IDLOcation'")
           ->queryScalar();

        $total = $totalGroups + $totalSingle;

        return $total;
    }

    /**
     * Get Total Visitors
     * @return total
     */
    public function getTotalGroup()
    {
        $today     = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
        $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));

        $totalGroups = Groupguesses::findBySql("SELECT * FROM groupguesses where CreateDate  BETWEEN '$today' AND '$tomorrow'")->count();

        return $totalGroups;
    }

    /**
     * Get audio for guestbook/bukutamu
     * @return total
     */
    public function getAudioBukutamu()
    {
        // 37 is ID in settingparameters for audio bacaditempat/guestbook
        $file = Settingparameters::findOne(['Name'=> 'AudioBukutamu']);
        return $file;
    }


    /**
     * is username and password match with database
     * @return total
     */
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

    /**
     * Get barcode image
     *
     * @return mixed
     */
    function actionImageBarcode($fontText) {
        $width = 330;
        $height = 80;
        $font     = "IDAutomationHC39M";
        $fontSize = 18;

        header("Content-Type: image/png");
        $response = Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'image/png');
        $response->format = Response::FORMAT_RAW;

        // create image with dimension
        $im = @imagecreatetruecolor($width, $height)
            or die("Cannot Initialize new GD image stream");
        $colorWhite = imagecolorallocate($im, 255, 255, 255);
        $colorBlack = imagecolorallocate($im, 0, 0, 0);

        // draw image from text
        imagefilledrectangle($im, 0, 0, $width, $height, $colorWhite);
        putenv('GDFONTPATH=' . realpath('./web/css/fonts'));
        imagettftext($im, $fontSize, 0, 10, $height - 20, $colorBlack, './web/css/fonts/'.$font.".ttf", "*".$fontText."*");
        imagepng($im);
        imagedestroy($im);

        return $response->send();
    }
}
