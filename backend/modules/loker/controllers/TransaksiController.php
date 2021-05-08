<?php
namespace backend\modules\loker\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Session;
use yii\helpers\Json;
use yii\db\Query;
use kartik\mpdf\Pdf;

use yii\data\ActiveDataProvider;

use PHPPdf\Core\FacadeBuilder;
use PHPPdf\DataSource\DataSource;

use common\models\Members;
use common\models\MemberSearch;
use common\models\Lockers;
use common\models\LockersSearch;
use common\models\Memberguesses;
use common\models\MasterLoker;
use common\models\JenisKelamin;


use common\models\MasterPelanggaranLocker;



/**
* TransaksiController implements the CRUD actions for Locker Transaction model.
*/
class TransaksiController extends Controller
{
    public $styleSheets;
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LockersSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);


    }

    /**
     * [actionDaftarPeminjaman description]
     * @return [type] [description]
     */
    public function actionDaftarPeminjaman()
    {
        $status = "peminjaman";

        $searchModel = new LockersSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['LockersSearch']['kembali'] = "NULL";
        $dataProvider = $searchModel->search($queryParams);
        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        

        // $query = Lockers::findAll();
        // $dataProvider = new ActiveDataProvider([
        //     'query' => Lockers::find()
        //     ->leftJoin('master_loker', 'master_loker.ID = lockers.loker_id')
        //     ->leftJoin('locations', 'locations.ID = master_loker.locations_id')
        //     ->where('lockers.tanggal_kembali is NULL and locations.LocationLibrary_id = "'.$_SESSION['location'].'" ORDER BY lockers.tanggal_pinjam DESC'),
        //     // 'query' => Lockers::find()->where(['tanggal_kembali' => null])->orderBy(['No_pinjaman'=>SORT_DESC,]),
        //     'pagination' => [
        //         'pageSize' => 20,
        //         ],
        //     ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => yii::t('app','Daftar Transaksi Peminjaman Locker'),
            'status' => $status,
            ]);
    }

    /**
     * [actionDaftarPengembalian description]
     * @return [type] [description]
     */
    public function actionDaftarPengembalian()
    {
        $status = "pengembalian";
        
        $searchModel = new LockersSearch;

        $queryParams= Yii::$app->request->getQueryParams();
        $queryParams['LockersSearch']['kembali'] = "NOT NULL";
        $dataProvider = $searchModel->search($queryParams);
        // $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        // $dataProvider = new ActiveDataProvider([
        //     'query' =>  Lockers::find()
        //     ->leftJoin('master_loker', 'master_loker.ID = lockers.loker_id')
        //     ->leftJoin('locations', 'locations.ID = master_loker.locations_id')
        //     ->where('lockers.tanggal_kembali IS NOT NULL and locations.LocationLibrary_id = "'.$_SESSION['location'].'" ORDER BY lockers.tanggal_kembali DESC'),
        //     // 'query' => Lockers::find()->where(['not', ['tanggal_kembali' => null]])->orderBy(['No_pinjaman'=>SORT_DESC,]),
        //     'pagination' => [
        //         'pageSize' => 20,
        //         ],
        //     ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => yii::t('app','Daftar Transaksi Pengembalian Locker'),
            'status' => $status,
            ]);
    }


    /**
     * [actionPeminjaman description]
     * @return [type] [description]
     */
    public function actionPeminjaman()
    {
        $model = new Lockers;

        $sql = 'select m.* from  master_loker m left join locations lo on m.locations_id = lo.ID where m.status = "ready" and lo.LocationLibrary_id = "'.$_SESSION['location'].'"';
        $lockerReady = Yii::$app->db->createCommand($sql)->queryAll();
        // $lockerReady = MasterLoker::find()->where(['status' => 'ready'])->all() ;

        if ($model->load(Yii::$app->request->post())) {
            $model->tanggal_pinjam = date("Y-m-d H:i:s");
            // $model->No_pinjaman =  date_timestamp_get($model->tanggal_pinjam);   // Input tanggal pinjam sesuai waktu saat data dimasukkan
            if ($model->save()) {
                $this->actionLockerUsedById($model->loker_id);                                   // Update Status Locker yg terpinjam menjadi 'used'
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);


                if (Yii::$app->config->get('CetakBuktiTransaksi') == 1)
                {
                    return $this->redirect(['viewpeminjaman', 'id' => $model->ID]);
                }
                else
                {
                    return $this->redirect(['peminjaman']);
                }
            } else {
                Yii::$app->getSession()->setFlash('failed', [
                    'type' => 'warning',
                    'duration' => 5000,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Failed Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            return $this->redirect(['peminjaman']);
            }

        } else {
            return $this->render('peminjaman',
                [
                'model' => $model,
                'lockerReady' => $lockerReady,
                ]
            );
        }

    }

    /**
     * [actionLockerUsedById MengUpdate / merubah status locker di table MasterLoker menjadi 'used' berdasarkan id]
     * @return [type] [description]
     */
    public function actionLockerUsedById($id)
    {
        $masterlocker = MasterLoker::findOne($id);
        $masterlocker->status = 'used';
        $masterlocker->save();
    }
    /**
     * [actionLockerReadyById MengUpdate / merubah status locker di table MasterLoker menjadi 'ready' berdasarkan id]
     * @return [type] [description]
     */
    public function actionLockerReadyById($id)
    {
        $masterlocker = MasterLoker::findOne($id);
        $masterlocker->status = 'ready';
        $masterlocker->save();
    }
    /**
     * [actionLockerOutoforderById MengUpdate / merubah status locker di table MasterLoker menjadi 'Out of Order' berdasarkan id]
     * @return [type] [description]
     */
    public function actionLockerOutoforderById($id,$idLocker)
    {
        // Set Jumlah pelanggaran disimpan di transaksi lockers
        $lockers = Lockers::findOne($idLocker);
        
        $jumlahDenda = MasterPelanggaranLocker::findOne($lockers->id_pelanggaran_locker);
        $lockers->denda = $jumlahDenda['denda'];
        $lockers->save();

        // Set Master Locker status menjadi Out of Order
        $masterlocker = MasterLoker::findOne($id);
        $masterlocker->status = 'Out of Order';

        $masterlocker->save();
    }

    /**
     * Finds the Members model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Members the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPinjamanbynomember($noMember)
    {
        // if (($model = Lockers::findOne(['no_member'=> $noMember, 'tanggal_kembali' => null])) == null) {
        // } else {
        //     echo "Member telah meminjam Locker, Harap kembalikan kunci locker terlebih dahulu sebelum meminjam kembali";
        // }

        // Revisi
        if (($model = Lockers::findAll(['no_member'=> $noMember, 'tanggal_kembali' => null])) == null) {
        } else {

            echo "Member telah meminjam ";
            foreach ($model as $model) {
                $a = MasterLoker::findOne(['ID'=> $model->loker_id]);
                echo $a->Name.", ";
            }
        }
    }

    /**
     * [actionViewpemesanan Melihat detail hasil jika pemesanan berhasil, Untuk selanjutnya di cetak/print ]
     * @param  [integer] $id [id dari pemesanan table Lockers]
     * @return [type]     [render views view.php]
     */
    public function actionViewpeminjaman($id){

        $model = Lockers::findOne($id);

        $dataMember = Members::findOne(['MemberNo'=>$model->no_member]);
        $dataMemberguesses = Memberguesses::findOne(['NoPengunjung'=>$model->no_member]);
        $lockers = MasterLoker::findOne($model->loker_id);
        if ($dataMember != null) {
            $namaMember = $dataMember->Fullname;
            $jenisKelamin = JenisKelamin::findOne($dataMember->Sex_id);
        } else {
            $namaMember = $dataMemberguesses->Nama ;
            $jenisKelamin = JenisKelamin::findOne($dataMemberguesses->JenisKelamin_id);
        }

        $data = array(
            'nama' => $namaMember,
            'jenisKelamin' => $jenisKelamin->Name,
            'lockers' => $lockers->Name,
        );
        return $this->render('view',
            [
                'model' => $model, 'data' => $data,
            ]
        );
    }




    /**
     * [actionPengembalian Proses Pengembalian Kunci locker]
     * @return [type] [description]
     */
    public function actionPengembalian()
    {
        $model = new Lockers;
        if ($model->load(Yii::$app->request->post()))
        {
            $idPelang = $model->id_pelanggaran_locker;
            $model = Lockers::findOne(['No_pinjaman'=>$model->No_pinjaman]);
            // echo "<pre>";
            // print_r($model);die;
            // $model = array(
            //     'tanggal_kembali' => date("Y-m-d H:i:s"),
            //     'id_pelanggaran_locker' => $idPelang,
            //     );
            $model->tanggal_kembali = date("Y-m-d H:i:s");
            $model->id_pelanggaran_locker = $idPelang;
            if ($model->save()) {
                if ($model->id_pelanggaran_locker == null) {
                    $this->actionLockerReadyById($model->loker_id);                                   // Update Status Locker yg dikembalikan menjadi 'ready'
                } else {
                    $this->actionLockerOutoforderById($model->loker_id,$model->ID);                                   // Update Status Locker yg Rusak/hilang menjadi 'out Of Order'
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
                // if ($model->id_pelanggaran_locker != null && Yii::$app->config->get('CetakBuktiPelanggaran') == 1) {
                if (Yii::$app->config->get('CetakBuktiPelanggaran') == 1) {
                    // return  "<script type=\"text/javascript\">
                    //         window.open('cetak-bukti-pelanggaran?id=".$model->ID."', '_blank');
                    //         window.location = window.location.href;
                    //     </script>";

                    // Simpan MemberID di Session
                        $sessionCetak = new Session();
                        $sessionCetak->open();
                        $sessionCetak->set('printPelanggaranLoker', ['id'=>'1','idPelanggaran'=>$model->ID]);
                    // Simpan MemberID di Session

                    return $this->redirect(['pengembalian']);

                    //return $this->redirect(['pengembalian']);   // Belum bisa buka new tab jika cetak
                } else {
                    return $this->redirect(['pengembalian']);
                }

            } else {
                Yii::$app->getSession()->setFlash('failed', [
                    'type' => 'warning',
                    'duration' => 5000,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Failed Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
                //print_r($model->save());
                return $this->redirect(['pengembalian']);
            }
        }
        else
        {
            return $this->render('pengembalian',
                [
                 'model' => $model,
                ]
            );

        }

    }

    /**
     * [actionCeknomorpengembalian Proses Cek pengembalian by Nomor Barcode kunci Locker atau Nomor Peminjaman]
     * @return [type] [description]
     */
    public function actionCeknomorpengembalian($id)
    {
        $noLocker = MasterLoker::findOne(['No' => $id]);
        $noPeminjaman = Lockers::findOne(['No_pinjaman' => $id,'tanggal_kembali' => null]);

        //Jika Nomor Locker tidak Kosong (atau nomor yang dimasukkan adalah nomor Barcode Locker)
        //Data yg tersedia MasterLoker ID,Nomor,Namaloker,Tempat
        if ($noLocker != null) {
            $idLocker = $noLocker->ID;
            // Mencari data dari table Lockers (loker_id) berdasarkan (ID) MasterLoker |
            // Jika data pencarian tidak kosong
            // data yang tersedia di $dataPengembalian adalah data Lockers
            if (($dataPengembalian = Lockers::findOne(['loker_id' => $idLocker, 'tanggal_kembali' => null])) != Null) {
                // print_r($dataPengembalian);
                // echo Json::encode($DataMember);
                $dataMember = Members::findOne(['MemberNo'=>$dataPengembalian->no_member]);
                $dataMemberguesses = Memberguesses::findOne(['NoPengunjung'=>$dataPengembalian->no_member]);
                // $lockers = MasterLoker::findOne($model->loker_id);
                if ($dataMember != null) {
                    $namaMember = $dataMember->Fullname;
                    $jenisKelamin = JenisKelamin::findOne($dataMember->Sex_id);
                } else {
                    $namaMember = $dataMemberguesses->Nama ;
                    $jenisKelamin = JenisKelamin::findOne($dataMemberguesses->JenisKelamin_id);
                }

                $data = array(
                    'nama' => $namaMember,
                    'jenisKelamin' => $jenisKelamin->Name,
                    'lockers' => $noLocker->Name,
                    );

                return $this->renderPartial('_detailPeminjaman',
                    [
                    'model' => $dataPengembalian, 'data' => $data,
                    ]
                    );

            } else {
                //echo "Data Peminjaman Tidak ditemukan, / Kunci Sudah dikembaliakan";
            }
        // Jika nomor peminjaman tidak kosong (atau nomor yang dimasukkan adalah nomor Peminjaman)
        // Data yang tersedia data Loker berdasarkan nomorpeminjaman
        } elseif ($noPeminjaman != Null) {
            $dataPengembalian = $noPeminjaman;
            //print_r($dataPengembalian);
            $dataMember = Members::findOne(['MemberNo'=>$dataPengembalian->no_member]);
            $dataMemberguesses = Memberguesses::findOne(['NoPengunjung'=>$dataPengembalian->no_member]);
            $lockers = MasterLoker::findOne($dataPengembalian->loker_id);
            if ($dataMember != null) {
                $namaMember = $dataMember->Fullname;
                $jenisKelamin = JenisKelamin::findOne($dataMember->Sex_id);
            } else {
                $namaMember = $dataMemberguesses->Nama ;
                $jenisKelamin = JenisKelamin::findOne($dataMemberguesses->JenisKelamin_id);
            }

            $data = array(
                'nama' => $namaMember,
                'jenisKelamin' => $jenisKelamin->Name,
                'lockers' => $lockers->Name,
                );

            return $this->renderPartial('_detailPeminjaman',
                [
                'model' => $dataPengembalian, 'data' => $data,
                ]
                );
        } else {
            //echo "Data Peminjaman Tidak ditemukan";
        }
    }

    /**
     * [actionCekpelanggaran description]
     * @param  [type] $idPel [description]
     * @return [type]        [description]
     */
    public function actionCekpelanggaran($idPel){
        $pelanggaran = MasterPelanggaranLocker::findOne($idPel);
        echo Json::encode($pelanggaran);
    }






    /**
     * [actionCheckid Check membership apakah nomor member terdaftar di table members atau memberguess]
     * @param  [integer] $noMember [member or memberguess number]
     * @return [Json Encode]     [sending result with Json encode format for ajax]
     */
    public function actionCheckmembership($noMember)
    {
       // $DataMember = Members::find()->where(['MemberNo'=>$noMember])->one();

        $DataMember = (new Query())
        ->from('members')
        ->where(['MemberNo' => $noMember])
        ->all();

        if ($DataMember == NULL)
        {
            $DataMember = (new Query())
            ->from('memberguesses')
            ->where(['NoPengunjung' => $noMember])
            ->all();

            if ($DataMember != NULL)
            {
                echo Json::encode($DataMember);
            }
        }
        else
        {
            echo Json::encode($DataMember);
        }

    }

    /**
     * [actionCheckBarcodeLoker description]
     * @param  [type] $noBarcode [description]
     * @return [type]            [description]
     */
    public function actionCheckBarcodeLoker($noBarcode)
    {

        $DataLoker = MasterLoker::find()
            ->leftJoin('locations', 'locations.ID = master_loker.locations_id')
            ->where('master_loker.status = "ready" and locations.LocationLibrary_id = "'.$_SESSION['location'].'" and master_loker.No = "'.addslashes($noBarcode).'"')
            ->one();

        echo Json::encode($DataLoker);

    }


// ////////////////////////////////Bawaan GII Generator

    /**
     * Displays a single Lockers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

        return $this->redirect(['view', 'id' => $model->ID]);
        } else {
        return $this->render('view-lockers', ['model' => $model]);
        }
    }

    /**
     * Creates a new Lockers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lockers;

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
            // return $this->redirect(['view', 'id' => $model->ID]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lockers model.
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
                    'message' => Yii::t('app','Success Edit'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            // return $this->redirect(['view', 'id' => $model->ID]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lockers model.
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



    // Cetak Controller

//     /**
//      * [renderPdf description]
//      * @param  [type]  $view   [description]
//      * @return [type]          [description]
//      */
//     public function renderPdf($view, $data = null, $return = false)
//     {
//         $facade = FacadeBuilder::create()
//             ->setEngineType('pdf')
//             ->setEngineOptions(array(
//                 'format' => 'jpg',
//                 'quality' => 120,
//                 'engine' => 'gd',
//             ))
//             ->build();
//         $viewPath = $this->viewPath;
//         try {
//             $content = $facade->render($this->renderFile("{$viewPath}/$view.xml", $data, true), $this->styleSheets ? DataSource::fromString($this->renderFile("$viewPath/{$this->styleSheets}.xml", $data, true)) : null);
//         } catch (Exception $e) {
//             throw $e;
//         }
//         if (!$return) {
//             header('Content-Type: application/pdf');
// //            header('Content-Disposition: attachment; filename="' . $this->action->id . '?' . Yii::app()->request->queryString . '"');
//             echo $content;
//             return;
//         }
//         return $content;
//     }




    /**
     * Cetak transaksi Locker
     * @param int $id
     */
    public function actionCetak($id)
    {
        $model = $this->loadModel($id);
        $dataMember = Members::findOne(['MemberNo'=>$model->no_member]);
        $dataMemberguesses = Memberguesses::findOne(['NoPengunjung'=>$model->no_member]);
        $lockers = MasterLoker::findOne($model->loker_id);

        if ($dataMember != null) {
            $namaMember = $dataMember->Fullname;
            // $jenisKelamin = JenisKelamin::findOne($dataMember->Sex_id);
        } else {
            $namaMember = $dataMemberguesses->Nama ;
            // $jenisKelamin = JenisKelamin::findOne($dataMemberguesses->JenisKelamin_id);
        }

        $data = array(
            'nama' => $namaMember,
            // 'jenisKelamin' => $jenisKelamin->Name,
            'lockers' => $lockers->Name,
        );

        // $pdf = new Pdf([
        //     'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
        //     'content' => $this->renderPartial('cetak-peminjaman',['model'=> $model,'data'=>$data]),
        //     'options' => [
        //         'title' => 'Bukti Pelanggaran Kunci Loker',
        //         'subject' => 'Perpustakaan Nasional Republik Indonesia'
        //     ],
        //     // 'methods' => [
        //     //     'SetHeader' => ['Generated By: Krajee Pdf Component||Printed On: ' . date("r")],
        //     //     'SetFooter' => ['|Page {PAGENO}|'],
        //     // ]
        // ]);
        // return $pdf->render();
        return $this->renderPartial('cetak-peminjaman',['model'=> $model,'data'=>$data]);

    }








    // /**
    //  * [actionCetakPelanggaran description]
    //  * @param  [type] $id [description]
    //  * @return [type]     [description]
    //  */
    // public function actionCetakPelanggaran($id)
    // {
    //     $model = $this->loadModel($id);
    //     $dataMember = Members::findOne(['MemberNo'=>$model->no_member]);
    //     $dataMemberguesses = Memberguesses::findOne(['NoPengunjung'=>$model->no_member]);
    //     $lockers = MasterLoker::findOne($model->loker_id);

    //     $pelanggaran = MasterPelanggaranLocker::findOne($model->id_pelanggaran_locker);

    //     if ($dataMember != null) {
    //         $namaMember = $dataMember->Fullname;
    //         $jenisKelamin = JenisKelamin::findOne($dataMember->Sex_id);
    //     } else {
    //         $namaMember = $dataMemberguesses->Nama ;
    //         $jenisKelamin = JenisKelamin::findOne($dataMemberguesses->JenisKelamin_id);
    //     }

    //     $data = array(
    //         'nama' => $namaMember,
    //         'jenisKelamin' => $jenisKelamin->Name,
    //         'lockers' => $lockers->Name,
    //         'pelanggaran' => $pelanggaran->jenis_pelanggaran,
    //         'denda' => $pelanggaran->denda,
    //     );
    //     $this->styleSheets = '';
    //     $this->renderPdf('detailPelanggaran', compact('model', 'data'));
    // }






    /**
     * [actionMpdfDemo1 generate data to pdf with MPDF Controller]
     * @return [pdf] [pdf to show on page]
     */
    public function actionCetakBuktiPelanggaran($id)
    {

        $model = $this->loadModel($id);
        $dataMember = Members::findOne(['MemberNo'=>$model->no_member]);
        $dataMemberguesses = Memberguesses::findOne(['NoPengunjung'=>$model->no_member]);
        $lockers = MasterLoker::findOne($model->loker_id);

        $pelanggaran = MasterPelanggaranLocker::findOne($model->id_pelanggaran_locker);

        if ($dataMember != null) {
            $namaMember = $dataMember->Fullname;
            // $jenisKelamin = JenisKelamin::findOne($dataMember->Sex_id);
        } else {
            $namaMember = $dataMemberguesses->Nama ;
            // $jenisKelamin = JenisKelamin::findOne($dataMemberguesses->JenisKelamin_id);
        }
        $data = array(
            'nama' => $namaMember,
            'lockers' => $lockers->Name,
            'pelanggaran' => (isset($pelanggaran->jenis_pelanggaran) ? $pelanggaran->jenis_pelanggaran : null ),
            'denda' => (isset($pelanggaran->denda) ? $pelanggaran->denda : null ),
        );

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'content' => $this->renderPartial('cetak-pelanggaran',['model'=> $model,'data'=>$data]),
            'options' => [
                'title' => 'Bukti Pelanggaran Kunci Loker',
                'subject' => 'Perpustakaan Nasional Republik Indonesia'
            ],
            // 'methods' => [
            //     'SetHeader' => ['Generated By: Krajee Pdf Component||Printed On: ' . date("r")],
            //     'SetFooter' => ['|Page {PAGENO}|'],
            // ]
        ]);
        return $this->renderPartial('cetak-pelanggaran',['model'=> $model,'data'=>$data]);
        // return $pdf->render();

    }










    /**
     * [loadModel description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function loadModel($id)
    {
        $model = Lockers::findOne($id);
        if ($model === null)
            // throw new CHttpException(404, 'The requested page does not exist.');
            throw new NotFoundHttpException('The requested page does not exist.');
        return $model;
    }


    /**
     * Finds the Lockers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lockers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lockers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }




}
