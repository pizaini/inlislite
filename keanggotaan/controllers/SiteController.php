<?php
namespace keanggotaan\controllers;

use common\components\Helpers;
use common\models\Membersonline;
use common\models\Members;
use Yii;
use common\models\LoginKeanggotaanForm;
use keanggotaan\models\PasswordResetRequestForm;
use keanggotaan\models\ResetPasswordForm;
use keanggotaan\models\SignupForm;
use keanggotaan\models\ContactForm;

use common\models\MemberPerpanjangan;
use common\models\MemberPerpanjanganSearch;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
/**
 * Site controller
 */
class SiteController extends Controller
{
    //public $layout = 'main';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','daftar-peminjaman','data-loker','daftar-pelanggaran','baca-ditempat'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','daftar-peminjaman','data-loker','daftar-pelanggaran','baca-ditempat'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'page' => [
                'class' => 'yii\web\ViewAction',
            ],
        ];
    }

    

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {


        if (!\Yii::$app->user->isGuest) {
            return $this->render('index');
        }else{
            return $this->redirect(['site/login']);
        }
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
		$this->layout = 'login';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginKeanggotaanForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //echo $model->login();
            //return $this->goBack();
             return $this->redirect(['site/index']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    

   

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'login';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Menampilkan daftar peminjaman
     *
     * @return mixed
     */
    public function actionDaftarPeminjaman()
    {   
        $searchModel = new \common\models\CollectionloanitemSearch;
        $dataProvider = $searchModel->advancedSearchMember('Loan',false,Yii::$app->user->identity->NoAnggota);

        return $this->render('peminjaman', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }
    
     /**
     * Menampilkan daftar pengembalian
     *
     * @return mixed
     */
    public function actionDaftarPengembalian()
    {
       
        $searchModel = new \common\models\CollectionloanitemSearch;
        $dataProvider = $searchModel->advancedSearchMember('Return',false,Yii::$app->user->identity->NoAnggota);

        return $this->render('pengembalian', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }

    public function actionDaftarPerpanjanganSirkulasi()
    {
        
        $searchModel = new \common\models\CollectionloanextendsSearch;
        $user = Members::find()->where(['MemberNo' => Yii::$app->user->identity->NoAnggota])->one();

        $params=Yii::$app->request->getQueryParams();
        $params['Member_id'] = $user['ID'] ;
        $dataProvider = $searchModel->advancedSearchByMember($params);

        return $this->render('perpanjangan-sirkulasi', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }

    /**
     * Menampilkan daftar pelanggaran
     *
     * @return mixed
     */
    public function actionDaftarPelanggaran()
    {
       
        $searchModel = new \common\models\PelanggaranSearch;
        $user = Members::find()->where(['MemberNo' => Yii::$app->user->identity->NoAnggota])->one();

        $params=Yii::$app->request->getQueryParams();
        $params['Member_id'] = $user['ID'] ;
        $dataProvider = $searchModel->search($params);
 
        
        return $this->render('pelanggaran', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }

    /**
     * Menampilkan daftar Baca ditempat
     *
     * @return mixed
     */
    public function actionBacaDitempat()
    {
       
        $searchModel = new \common\models\BacaditempatSearch;
        $searchModel->MemberNo  = Yii::$app->user->identity->NoAnggota;
        $dataProvider = $searchModel->search2(Yii::$app->request->getQueryParams());

        return $this->render('bacaditempat', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }

    /**
     * Menampilkan daftar loker yang pernah digunakan.
     *
     * @return mixed
     */
    public function actionDataLoker()
    {
       
        $searchModel = new \common\models\LockersSearch;

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('loker', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }


    public function actionKirimPassword(){
        $noAnggota = trim(Yii::$app->request->post('NoAnggota'));
        $email = trim(Yii::$app->request->post('Email'));
        $newPassword = $this->generateRandomString(6);
        $generatePass = sha1($newPassword);
        $memberOnline = Membersonline::find()->where(['NoAnggota'=>$noAnggota])->andWhere(['Email'=>$email])->one();
        if ($memberOnline !== null) {
            $memberOnline->Password = $generatePass;
            if($memberOnline->save()){
                Yii::$app->mailer->compose()
                    ->setFrom('inlis.pnri@gmail.com')
                    ->setTo($email)
                    ->setSubject('Password Anda')
                    ->setHtmlBody('Password anda adalah :<b>'.$newPassword.'</b><br/>Silahkan login menggunakan password tersebut, dan mengganti dengan password pilihan anda pada sistem.Terimakasih.')
                    ->send();

                return "Password baru anda telah dikirimkan ke E-mail anda.";
            }

        }else{
            throw new \yii\web\HttpException(404, 'Maaf, Nomor Anggota / Alamat Email anda belum terdaftar di database kami. Silahkan hubungi bagian layanan ' . Yii::$app->config->get('NamaPerpustakaan') );
        }
    }

    public function actionKirimNomor(){
        $nama = trim(Yii::$app->request->post('Nama'));
        $tgl = trim(Yii::$app->request->post('Tgl'));
        $email = trim(Yii::$app->request->post('Email'));

        $sql = "SELECT NoAnggota FROM membersonline WHERE (select lower(FULLNAME) from members where members.MemberNo = membersonline.NoAnggota )=:nama AND lower(Email)=:email AND (select DateOfBirth from members where members.MemberNo = membersonline.NoAnggota ) =:tgl ";
        $result = Yii::$app->db->createCommand($sql)
                ->bindValues([':nama' => strtolower($nama),':email'=>strtolower($email),':tgl'=>Helpers::DateToMysqlFormat('-',$tgl)])
                ->queryScalar();


        if ($result) {
                Yii::$app->mailer->compose()
                    ->setFrom('inlis.pnri@gmail.com')
                    ->setTo($email)
                    ->setSubject('Nomor Anggota Anda')
                    ->setHtmlBody('Nomor Anggota anda adalah :<b>'.$result.'</b><br/>Silahkan login menggunakan nomor anggota tersebut.Terimakasih.')
                    ->send();
            return "Nomor Anggota telah dikirimkan ke E-mail anda.";

        }else{
            throw new \yii\web\HttpException(404, 'Maaf, anda belum terdaftar. Silahkan hubungi bagian layanan keanggotaan ' . Yii::$app->config->get('NamaPerpustakaan') );
        }
    }


    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function actionDaftarPerpanjangan(){
        $searchModel = new MemberPerpanjanganSearch;
        
        $memberID =  $searchModel->getMemberID(Yii::$app->user->identity->NoAnggota);
        $params = Yii::$app->request->getQueryParams();
        $params['Member_Id'] = $memberID;
        $dataProvider = $searchModel->search($params);
        
        return $this->render('indexPerpanjangan', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKoleksiSedangDipesan(){

        $searchModel = new \common\models\Bookinglogs;
        $dataProvider = $searchModel->searchBooking(Yii::$app->user->identity->NoAnggota,false);

        return $this->render('indexKoleksiDipesan', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'title'        => 'Koleksi Sedang Dipesan'
        ]);
    }

    public function actionKoleksiPernahDipesan(){
        $searchModel = new \common\models\Bookinglogs;
        $dataProvider = $searchModel->searchBooking(Yii::$app->user->identity->NoAnggota,true);

        return $this->render('indexKoleksiDipesan', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title'        => 'Koleksi Pernah Dipesan'
        ]);
    }

    public function actionKunjungan(){
        $searchModel = new \common\models\Memberguesses;
        $searchModel->NoAnggota  = Yii::$app->user->identity->NoAnggota;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('indexKunjungan', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title'        => 'Histori Kunjungan'
            
        ]);
    }


    /**
     * [actionLokerDipinjam description]
     * @return [type] [description]
     */
    public function actionLokerPinjam()
    {
        /*$searchModel = new \common\models\LockersSearch;
        $searchModel->tanggal_kembali = null;
        $searchModel->no_member  = Yii::$app->user->identity->NoAnggota;
        $dataProvider = $searchModel->getLoker(Yii::$app->request->getQueryParams());

        return $this->render('indexLoker', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => 'Daftar Loker Sedang Dipinjam',
            ]);*/



         $searchModel = new \common\models\LockersSearch;
        $dataProvider = new ActiveDataProvider([
            'query' => \common\models\Lockers::find()
            ->leftJoin('master_loker', 'master_loker.ID = lockers.loker_id')
            ->leftJoin('locations', 'locations.ID = master_loker.locations_id')
            ->where('lockers.tanggal_kembali is NULL and locations.LocationLibrary_id = "'.$_SESSION['location'].'" ORDER BY lockers.tanggal_pinjam DESC'),
            // 'query' => Lockers::find()->where(['tanggal_kembali' => null])->orderBy(['No_pinjaman'=>SORT_DESC,]),
            'pagination' => [
                'pageSize' => 20,
                ],
            ]);

        return $this->render('indexLoker', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => 'Daftar Loker Sedang Dipinjam',
            ]);
    }

    /**
     * [actionLokerKembali description]
     * @return [type] [description]
     */
    public function actionLokerKembali()
    {
        $searchModel = new \common\models\LockersSearch;
        //$searchModel->tanggal_kembali  = null;
        $searchModel->no_member  = Yii::$app->user->identity->NoAnggota;
        $dataProvider = $searchModel->getLoker(Yii::$app->request->getQueryParams());

        return $this->render('indexLoker', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => 'Daftar Pernah Dipinjam',
            ]);
    }


    /**
     * [actionLokerPelanggaran description]
     * @return [type] [description]
     */
    public function actionLokerPelanggaran()
    {
        $searchModel = new \common\models\LockersSearch;
        //$searchModel->tanggal_kembali  = null;
        $searchModel->no_member  = Yii::$app->user->identity->NoAnggota;
        $dataProvider = $searchModel->getLokerPelanggaran(Yii::$app->request->getQueryParams());

        return $this->render('indexLokerPelanggaran', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => 'Histori Pelanggaran',
            ]);
    }


    public function actionSumbanganAnggota()
    {
        $searchModel = new \common\models\SumbanganSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('indexSumbangan', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionKoleksiFavorit()
    {
        $searchModel = new \common\models\Favorite;
        $searchModel->Member_Id  = Yii::$app->user->identity->NoAnggota;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        
        return $this->render('indexFavorit', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }



    public function actionGetChartData()
    {
        $sql = "SELECT internum,CountBacaDitempat,CountPelanggaran,CountPeminjaman FROM
(SELECT
DATE_FORMAT(m1, '%b %Y') AS YINTERVAL, DATE_FORMAT(m1, '%Y-%m') AS internum
FROM(SELECT
(CONCAT(YEAR(CURDATE()),'-01-01')) +INTERVAL m MONTH AS m1
FROM (
SELECT @rownum:=@rownum+1 AS m FROM
(SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) t1,
(SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) t2,
(SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) t3,
(SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) t4,
(SELECT @rownum:=-1) t0
) d1 ) d2 WHERE m1<=CONCAT(YEAR(CURDATE()),'-12-12')
ORDER BY m1 ) I

LEFT JOIN
(SELECT DATE_FORMAT(bacaditempat.CreateDate,'%b %Y')AS DATE, COUNT(1) AS CountBacaDitempat  FROM bacaditempat INNER JOIN members ON members.ID = bacaditempat.member_id
WHERE members.MemberNo = '".Yii::$app->user->identity->NoAnggota."'
GROUP BY DATE_FORMAT(bacaditempat.CreateDate,'%b %Y')
ORDER BY bacaditempat.CreateDate ASC) A
ON I.YINTERVAL = A.DATE

LEFT JOIN
(SELECT DATE_FORMAT(pelanggaran.CreateDate,'%b %Y')AS DATE, COUNT(1) AS CountPelanggaran FROM pelanggaran INNER JOIN members ON members.ID = pelanggaran.member_id
WHERE members.MemberNo = '".Yii::$app->user->identity->NoAnggota."'
GROUP BY DATE_FORMAT(pelanggaran.CreateDate,'%b %Y')
ORDER BY pelanggaran.CreateDate ASC) B
ON I.YINTERVAL = B.DATE

LEFT JOIN
(SELECT DATE_FORMAT(collectionloanitems.CreateDate,'%b %Y')AS DATE, COUNT(1) AS CountPeminjaman FROM collectionloanitems INNER JOIN members ON members.ID = collectionloanitems.member_id
WHERE members.MemberNo = '".Yii::$app->user->identity->NoAnggota."'
GROUP BY DATE_FORMAT(collectionloanitems.CreateDate,'%b %Y')
ORDER BY collectionloanitems.CreateDate ASC) C
ON I.YINTERVAL = C.DATE

ORDER BY I.internum ASC";
        $result = Yii::$app->db->createCommand($sql)->queryAll();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //var_dump($result);
        return $result;
    }
}
