<?php
namespace bacaditempat\controllers;

use Yii;
use common\models\LoginForm;
use common\models\Memberguesses;
use common\models\Members;
use common\models\Groupguesses;

use common\models\Locations;
use common\models\LocationLibrary;
use common\models\Userloclibforloan;

use common\models\Settingparameters;
use common\models\Users;
use common\models\Bacaditempat;

use common\models\Collections;
use common\models\Worksheets;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\CookieCollection;
use yii\web\Controller;
use yii\web\Response;
use yii\validators\EmailValidator;

use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use common\components\DirectoryHelpers;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout ="baca-ditempat";
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
      if ($cookies->getValue('location_id') !== null) {
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
        if ($cookies->getValue('location_id') !== null) {
            // go to member page
            $this->redirect(\Yii::$app->urlManager->createUrl("site/pindai-anggota"));
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
        $this->layout ="baca-ditempat";

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
                            'name' => 'usersSetLocation',
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
     * Buku tamu pilih lokasi
     * @return mixed
     */
    public function actionSettingLocations()
    {
        $this->layout ="baca-ditempat";

        $model = new Locations;
        $message = "";

        if ($users = Yii::$app->request->cookies->getValue('usersSetLocation')) {
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
                // print_r(LocationLibrary::find()->select(['Name', 'ID','Code','Address'])->where(['ID'=> Yii::$app->request->post('LocationLibrary')])->asArray()->one());die;
                if (Yii::$app->request->post('Locations')['ID'] == "") {
                    $message = "Pilih Lokasi Terlebih Dahulu";
                } else {
                    // set location_id cookies
                    $cookies = Yii::$app->response->cookies;
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'location_id',
                        'value' => Yii::$app->request->post('Locations')['ID'],
                    ]));
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'location_detail',
                        'value' => LocationLibrary::find()->select(['Name', 'ID','Code','Address'])->where(['ID'=> Yii::$app->request->post('LocationLibrary')])->asArray()->one(),
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
        } else {
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


    // /**
    //  * Baca di Tempat Pindai No
    //  * @return mixed
    //  */
    // public function actionPindaiNo()
    // {
    //     $this->layout ="baca-ditempat";

    //     $model = new Bacaditempat;

    //     $message = "";
    //     if (Yii::$app->request->post('no') == "" && !empty(Yii::$app->request->post('no'))) {
    //         $message = "Pindai No Anggota/Pengunjung Anda Terlebih Dahulu";
    //     } else if ($this->getVerifiedInfo(Yii::$app->request->post('no'))['verified']) {
    //         $no =  Yii::$app->request->post('no');
    //         Yii::$app->getSession()->setFlash('verifiedNo', $no);
    //         Yii::$app->getSession()->setFlash('verifiedName', $this->getVerifiedInfo($no)['name']);
    //         Yii::$app->getSession()->setFlash('verifiedType', $this->getVerifiedInfo($no)['type']);
    //         Yii::$app->getSession()->setFlash('verifiedId', $this->getVerifiedInfo($no)['id']);
    //         $this->redirect(\Yii::$app->urlManager->createUrl("site/pindai-koleksi"));
    //     }

    //     Yii::$app->getSession()->setFlash('message', $message);

    //     $no =  Yii::$app->request->post('no');
    //     return $this->render('pindaino', [
    //         'locationName' =>  $this->getLocationInfo()['name'],
    //         'libraryName' => $this->getLibraryName(),
    //         'verifiedNo' => (!is_null($no))?$this->getVerifiedInfo($no)['verified']:true,
    //         'readedCollection' => $this->getReadedCollection(),
    //         'no' =>  $no,
    //         'model' =>  $model,
    //         'urlLogo' => $img = $this->getLocationInfo()['urlLogo'],
    //     ]);
    // }



    /**
     * Baca di Tempat Scan User
     * @return mixed
     */
    public function actionPindaiAnggota()
    {
        $this->cekLogin();
        $today     = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
        $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));

        $this->layout ="baca-ditempat";

        // Menghapus data buku dari cookie;
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('bookHistory');
        unset($cookies['bookHistory']);

        $model = new Bacaditempat;
        $message = "";

        Yii::$app->getSession()->setFlash('message', $message);

        $NoAnggota =  Yii::$app->request->post('NoAnggota');
        return $this->render('pindaino', [
            'locationName' =>  $this->getLocationInfo()['name'],
            'libraryName' => $this->getLibraryName(),
            'verifiedNo' => (!is_null($NoAnggota))?$this->getVerifiedInfo($NoAnggota)['verified']:true,
            'readedCollection' => $this->getReadedCollection(),
            'NoAnggota' =>  $NoAnggota,
            'model' =>  $model,
            'urlLogo' => $img = $this->getLocationInfo()['urlLogo'],
        ]);
    }

    /**
     * Baca di Tempat Pindai Koleksi
     * @return mixed
     */
    public function actionPindaiKoleksi()
    {
        $message = "";
        // anggota, guest or groups
		$verifiedNoVal = Yii::$app->session->getFlash('verifiedNo');
        if (!empty($verifiedNoVal)) {
            // check if kolektion is scanned
			$postNo = Yii::$app->request->post('no');
            if ($postNo != "" && !empty($postNo)) {

            }
        } else {
            $this->redirect(\Yii::$app->urlManager->createUrl("site/pindai-no"));
        }

        Yii::$app->getSession()->setFlash('message', $message);

        $no =  Yii::$app->request->post('no');
        return $this->render('pindaikoleksi', [
            'locationName' =>  $this->getLocationInfo()['name'],
            'libraryName' => $this->getLibraryName(),
            'verifiedNo' => (!is_null($no))?$this->getVerifiedInfo($no)['verified']:true,
            'readedCollection' => $this->getReadedCollection(),
            'no' =>  $no,
            'urlLogo' => $img = '../../uploaded_files/foto_anggota/' . Yii::$app->session->getFlash('verifiedId') . '.jpg',
            'verifiedName' => Yii::$app->session->getFlash('verifiedName'),
            'verifiedNo' => Yii::$app->session->getFlash('verifiedNo'),
        ]);
    }

    /**
     * alvin
     * Baca di Tempat Pindai Koleksi
     * @return mixed
     */
    public function actionPindaiBuku($noBuku,$noAnggota)
    {    //Yii::$app->request->cookies

        $model = new Bacaditempat;

        // $cookies = Yii::$app->response->cookies;
        // $cookies->remove('bookHistory');
        // unset($cookies['bookHistory']);
        // die;


        // Cek nomor barcode buku di cookie
        $book = Yii::$app->request->cookies->getValue('bookHistory');
        // Jika data cookie kosong maka set $book array kosong
        if ($book == null)
        {
            $book = array();
        }

        // Cek isi $book cookie nomor barcode
        // JIka barcode exist atau sudah ada
        if (array_key_exists($noBuku,$book))
        {
            $data['exist'] = true;
            //echo "Key exists!";
        }

        // Jika data barcode baru / not exist
        else
        {
            //echo "Key does not exist!";
            // Ambil data member / memInfo berdasarkan barcode member
            $memInfo = Yii::$app->request->cookies->getValue('memInfo');
            // print_r($memInfo);die;
            // AMbil data buku berdasarkan barcode yg di scan dari database
            $data = Yii::$app->db->createCommand("select cl.*, cs.Title, cs.Author, cs.Edition, cs.Publisher, cs.PublishYear, cs.PublishLocation, cs.CoverURL, cs.Worksheet_id from collections cl inner join catalogs cs on cl.Catalog_id = cs.ID where cl.NomorBarcode = :noBuku ")
            ->bindValue(':noBuku', $noBuku)
            ->queryOne();

            if (empty($data)) {
                $data = Yii::$app->db->createCommand("select cl.*, cs.Title, cs.Author, cs.Edition, cs.Publisher, cs.PublishYear, cs.PublishLocation, cs.CoverURL, cs.Worksheet_id from collections cl inner join catalogs cs on cl.Catalog_id = cs.ID where cl.RFID = :noBuku ")
                ->bindValue(':noBuku', $noBuku)
                ->queryOne();
            }

            // Jika data buku ditemukan di database
            if ($data)
            {
                // Mengubah status buku di table collection menjadi dibaca
                $model2 = Collections::findOne($data['ID']);
                $model2->Status_id = 11;    // Status menajadi (ID => 11) Dibaca;
                $model2->save(false);



                // Set data.exist = false
                $data['exist'] = false;
                // Jika type anggota 'member'
                if ($memInfo['type'] == 'member')
                {
                    $model->Member_id = $memInfo['id'];
                    $model->collection_id = $data['ID'];
                    $model->Location_Id = Yii::$app->request->cookies->getValue('location_id');
                }
                // Jika type anggota 'groupguess' / 'guestmembers'
                else if($memInfo['type'] == 'BUKAN_ANGGOTA'){
                    $model->NoPengunjung = 'BUKAN_ANGGOTA';
                    $model->collection_id = $data['ID'];
                    $model->Location_Id = Yii::$app->request->cookies->getValue('location_id');
                }
                else
                {
                    $model->NoPengunjung = $noAnggota;
                    $model->collection_id = $data['ID'];
                    $model->Location_Id = Yii::$app->request->cookies->getValue('location_id');
                }

                // Set/simpan data buku dan member ke table bacaditemppat
                $model->save();
                // Set/simpan data buku yang telah di scan dan valid ke cookie
                $book[$data['NomorBarcode']] = '';
                $book[$data['RFID']] = '';

                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'bookHistory',
                    'value' => $book,
                    ]));

                // $wsName = Worksheets::find($data['Worksheet_id'])->select('Name')->one();
                $data['imgBookUrl'] = $this->getUrlPhotoBukuBacaditempat($CoverURL = $data['CoverURL'],$Worksheet_id = $data['Worksheet_id']);
                // echo $data['imgBookUrl'];die;
            }
            // Jika data buku tidak ditemukan di database
            else
            {
                $data = '';
            }
        }
        // print_r($data['worksheets']);
        // var_dump($data);die;
        return Json::encode($data);
    }


    /**
     * [getUrlPhotoBuku description]
     * @param  [type] $CoverURL     [description]
     * @param  [type] $Worksheet_id [description]
     * @return [type]               [description]
     */
    public function getUrlPhotoBukuBacaditempat($CoverURL,$Worksheet_id)
    {
        if ($CoverURL) {
            // $wsName = Worksheets::find()->where(['ID' => $Worksheet_id])->select('Name')->one();
            $wsName = DirectoryHelpers::GetDirWorksheet($Worksheet_id);
            // $data['worksheets'] = $wsName['Name'];
            $url = '../../uploaded_files/sampul_koleksi/original/'.$wsName.'/'.$CoverURL;
            $url2 = Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.$wsName.'/'.$CoverURL);
            $url = (file_exists($url2)) ? $url : '../../uploaded_files/sampul_koleksi/original/nophoto.jpg' ;
        } else {
            $url = '../../uploaded_files/sampul_koleksi/original/nophoto.jpg';

        }

        return $url;

    }




    /**
     * Get Location Info
     * @return total
     */
    public function getLocationInfo()
    {
        // get cookies
        $cookies = Yii::$app->request->cookies;
        $location_id = Yii::$app->request->cookies->getValue('location_id');

        // get location model
        $location = Locations::find()
            ->where(['ID' => $location_id])
            ->one();

        if ($location) {
            return [
                "name" => $location->Name,
                "urlLogo" => '../../uploaded_files/logo_ruangan/' . $location->UrlLogo,
            ];
        } else {
            return [
                "name" => "",
                "urlLogo" => "",
            ];
        }
    }

    /**
     * Get Library Name
     * @return total
     */
    public function getLibraryName()
    {
        // get settingparameters model
        $settingparameters = Settingparameters::find()
            ->where(['Name' => "NamaPerpustakaan"])
            ->one();

        return ($settingparameters->Value)?$settingparameters->Value:"Perpustakaan Nasional RI";
    }

    /**
     * Get Readed Collection
     * @return total
     */
    public function getReadedCollection()
    {
        $IDLOcation = Yii::$app->request->cookies->getValue('location_id');
        $today     = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
        $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));

        $data = Yii::$app->db->createCommand("SELECT count(*) collections_num, count(distinct c.ID) catalogs_num
            FROM bacaditempat a, collections b, catalogs c
            WHERE a.collection_id = b.ID
            AND b.Catalog_id = c.ID
            AND a.Location_Id = '$IDLOcation'
            AND a.CreateDate  BETWEEN  '".$today."' AND '".$tomorrow."'

            ")
           ->queryOne();

        return [
            "eksemplar" => $data['collections_num'],
            "judul" => $data['catalogs_num'],
        ];
    }

    /**
     * Get Readed Collection
     * @return total
     */
    public function actionReadedCollectionFromMember($noAnggota,$type,$id)
    {
        $IDLOcation = Yii::$app->request->cookies->getValue('location_id');
        $today     = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
        $tomorrow  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));

        // cl.*, c.Title, c.Author, c.Edition, c.Publisher, c.PublishYear, c.PublishLocation from collections cl inner join catalogs cs on cl.Catalog_id = cs.ID

        if ($type == 'member')
        {
            $data = Yii::$app->db->createCommand("SELECT b.*, c.Title, c.Author, c.Edition, c.Publisher, c.PublishYear, c.PublishLocation, b.NomorBarcode, b.RFID, c.CoverURL, c.Worksheet_id
                FROM bacaditempat a, collections b, catalogs c
                WHERE a.collection_id = b.ID
                AND b.Catalog_id = c.ID
                AND a.Member_id = '$id'
                AND a.Location_Id = '$IDLOcation'
                AND a.CreateDate  BETWEEN  '".$today."' AND '".$tomorrow."'")
               ->queryAll();
        }
        else
        {
            $data = Yii::$app->db->createCommand("SELECT b.*, c.Title, c.Author, c.Edition, c.Publisher, c.PublishYear, c.PublishLocation, b.NomorBarcode, b.RFID, c.CoverURL, c.Worksheet_id
                FROM bacaditempat a, collections b, catalogs c
                WHERE a.collection_id = b.ID
                AND b.Catalog_id = c.ID
                AND a.NoPengunjung = '$noAnggota'
                AND a.Location_Id = '$IDLOcation'
                AND a.CreateDate  BETWEEN  '".$today."' AND '".$tomorrow."'")
            ->queryAll();
        }

        //Set Cookie untuk barcode yg sudah di scan oleh member
        //$belum;
        $barcode = ArrayHelper::map($data, 'NomorBarcode', 'Title');
        // array_push($bookCookie, ArrayHelper::map($data, 'RFID', 'Title'));
        $rfid = ArrayHelper::map($data, 'RFID', 'Title');
        $bookCookie = ArrayHelper::merge($barcode,$rfid);
        // var_dump($bookCookie);die;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'bookHistory',
            'value' => $bookCookie,
        ]));

        //Set view atau data list untuk ditampilkan di halaman pindai buku
        foreach ($data as $data)
        {

            // var_dump($data);
            $imgBookUrl = $this->getUrlPhotoBukuBacaditempat($CoverURL = $data['CoverURL'],$Worksheet_id = $data['Worksheet_id']);
            // echo $imgBookUrl;
            echo "<div class='col-sm-12 book-item' style='padding: 10px 0;'><div class='col-sm-2'><img src='".$imgBookUrl."' style='width: 54px; height:84px;'></div><div class='col-sm-10'><h5><b>".$data['Title']."/</b> ".$data['Author']." </h5><p>".$data['PublishLocation']." ".$data['Publisher']." ".$data['PublishYear']."</p></div></div>";
        }

    }

    /**
     * Get Collection Info
     * @return total
     */
    public function getCollectionInfo($no)
    {
        $data = Collections::findOne(['NomorBarcode'=>$no]);

        if ($data) {
            return $data;
        } else {
            return null;
        }

    }


    /**
     * Alvin
     * Get Validasi Anggota
     * @return mixed
     */
    public function actionValidasiAnggota($noAnggota)
    {
        if($noAnggota == ''){
            // echo 'masuk sini';
            $datas = [
                "type" => 'BUKAN_ANGGOTA',
                "name" => 'Bukan Anggota',
                "verified" => true,
                "id" => 0
            ];
        }else{
            // check 11 character and first word contain GRP and GST
            if ((strlen($noAnggota) <= 11) && (substr($noAnggota,0,3) == "GST" || substr($noAnggota,0,3) == "GRP"))
            {
                // potong tiga huruf awal apakah mengandung GST atau GRP
                if (substr($noAnggota,0,3) == "GST")
                {
                    // check in memberguesses
                    // $data = Memberguesses::findBySql("SELECT * FROM memberguesses where NoPengunjung = '$noAnggota'")->one();
                    $data = Memberguesses::findBySql("SELECT * FROM memberguesses where NoPengunjung = :noAnggota",[':noAnggota' => $noAnggota])->one();

                    $type = "guest";

                    if ($data)
                    {
                        $datas = [
                            "type" => $type,
                            "name" => $data->Nama,
                            "verified" => true,
                            "id" => $data->ID
                        ];
                    }
                    else
                    {
                        $datas = null;
                    }
                }
                else if (substr($noAnggota,0,3) == "GRP")
                {
                    // check in groupguessess
                    $data = Groupguesses::findBySql("SELECT * FROM groupguesses where NoPengunjung = :noAnggota",[':noAnggota' => $noAnggota])->one();

                    $type = "group";
                    if ($data)
                    {
                        $datas = [
                            "type" => $type,
                            "name" => $data->NamaKetua,
                            "verified" => true,
                            "id" => $data->ID
                        ];
                    }
                    else
                    {
                        $datas = null;
                    }
                }
            }
            else
            {
                // check in members
                $data = Members::findBySql("SELECT * FROM members where MemberNo = :noAnggota",[':noAnggota' => $noAnggota])->one();

                $type = "member";
                if ($data)
                {
                    $datas = [
                        "type" => $type,
                        "name" => $data->Fullname,
                        "verified" => true,
                        "id" => $data->ID,
                        "PhotoUrl" => $data->PhotoUrl
                    ];
                }
                else
                {
                    $datas = null;
                }
            }
        }
        
        
       // Yii::$app->params['pathFotoAnggota'] = $id;
        // Yii::$app->getSession()->setFlash('memInfo', $datas);
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'memInfo',
            'value' => $datas,
        ]));
        return Json::encode($datas);
    }

    /**
     * Get Verified Info
     * @return mixed
     */
    public function getVerifiedInfo($no)
    {
        // check 11 character and first word contain GRP and GST
        if ((strlen($no) <= 11) && (substr($no,0,3) == "GST" || substr($no,0,3) == "GRP")) {
            // potong tiga huruf awal apakah mengandung GST atau GRP
            if (substr($no,0,3) == "GST") {
                // check in memberguesses
                $data = Memberguesses::findBySql("SELECT * FROM memberguesses where NoPengunjung = '$no'")->one();

                $type = "guest";

                if ($data) {
                    $name = $data->Nama;
                    $id = $data->ID;
                } else {
                    return [
                        "verified" => false,
                    ];
                }
            } else if (substr($no,0,3) == "GRP") {
                // check in groupguessess
                $data = Groupguesses::findBySql("SELECT * FROM groupguesses where NoPengunjung = '$no'")->one();

                $type = "group";
                if ($data) {
                    $name = $data->NamaKetua;
                    $id = $data->ID;
                } else {
                    return [
                        "verified" => false,
                    ];
                }
            }
        } else {
            // check in members
            $data = Members::findBySql("SELECT * FROM members where MemberNo = '$no'")->one();

            $type = "member";
            if ($data) {
                $name = $data->Fullname;
                $id = $data->ID;
            } else {
                return [
                    "verified" => false,
                ];
            }
        }

        return [
            "type" => $type,
            "name" => $name,
            "verified" => true,
            "id" => $id,
        ];
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
}
