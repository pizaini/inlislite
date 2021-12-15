<?php

namespace article\controllers;
use yii;
use yii\web\Request;
use common\models\Collections;
use common\models\Members;
class BookingController extends \yii\web\Controller {

    public function actionIndex() {
        $request = \Yii::$app->request;
        if ($request->isAjax && $_GET['action'] === "showBookingDetail") {

            if (\Yii::$app->user->isGuest) {
                $noAnggota = null;
                return $this->renderAjax('_bookingList', ['noAnggota' => $noAnggota, 'jmlbooking' => 0,]);
            } else {
                $dateNow = new \DateTime("now");
                $noAnggota = \Yii::$app->user->identity->NoAnggota;
                $booking = Collections::find()
                        ->select([
                            'collections.BookingExpiredDate',
                            'catalogs.Title',
                            'collections.ID',
                        ])
                        ->leftJoin('catalogs', '`catalogs`.`ID` = `collections`.`Catalog_id`')
                       ->andWhere('BookingMemberID ="' . $noAnggota.'"')
                        ->andWhere('BookingExpiredDate >  "' . $dateNow->format("Y-m-d H:i:s") . '"')
                        ->all();
                 $alert = false;
                return $this->renderAjax('_bookingList', [
                            'jmlbooking' => sizeof($booking),
                            'booking' => $booking,
                            'noAnggota' => $noAnggota,
                            'alert' => $alert,
                ]);
            }
        }
        if ($request->isAjax && $_GET['action'] === "cancelBooking") {

            if (\Yii::$app->user->isGuest) {
                $noAnggota = null;
                return $this->renderAjax('_bookingList', ['noAnggota' => $noAnggota,]);
            } else {
                $dateNow = new \DateTime("now");
                $noAnggota = \Yii::$app->user->identity->NoAnggota;


                $params2 = [':ID' => addslashes($_GET['colID'])];
                $command = Yii::$app->db->createCommand("UPDATE collections SET BookingMemberID='', BookingExpiredDate='0000-00-00 00:00:00s' WHERE ID=:ID;");
                $command->bindValues($params2);
                $command->execute();



                \Yii::$app->getSession()->setFlash('success', [
                    'type' => 'danger',
                    'duration' => 2500,
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'message' => \Yii::t('app', ' item telah di hapus dari keranjang pesan'),
                    'title' => 'success',
                    'positonY' => \Yii::$app->params['flashMessagePositionY'],
                    'positonX' => \Yii::$app->params['flashMessagePositionX']
                ]);

                $alert = TRUE;

                $noAnggota = \Yii::$app->user->identity->NoAnggota;
                $booking = Collections::find()
                        ->select([
                            'collections.BookingExpiredDate',
                            'catalogs.Title',
                            'collections.ID',
                        ])
                        ->leftJoin('catalogs', '`catalogs`.`ID` = `collections`.`Catalog_id`')
                        ->andWhere('BookingMemberID ="' . $noAnggota.'"')
                        ->andWhere('BookingExpiredDate >  "' . $dateNow->format("Y-m-d H:i:s") . '"')
                        ->all();
                return $this->redirect(Yii::$app->request->referrer);
                // return $this->renderAjax('_bookingList', [
                //             'jmlbooking' => sizeof($booking),
                //             'noAnggota' => $noAnggota,
                //             'alert' => $alert,
                // ]);


            }
        }
        return $this->render('index');
    }
    public function actionCetak($id){

        $dateNow = new \DateTime("now");
        $noAnggota = \Yii::$app->user->identity->NoAnggota;
        $anggota = Members::find()->where(['MemberNo' => $noAnggota])->all();

        $booking = Collections::find()
                        ->select([
                            'collections.BookingExpiredDate',
                            'catalogs.Title',
                            'collections.ID',
                        ])
                        ->leftJoin('catalogs', '`catalogs`.`ID` = `collections`.`Catalog_id`')
                       ->andWhere('BookingMemberID ="' . $noAnggota.'"')
                        ->andWhere('BookingExpiredDate >  "' . $dateNow->format("Y-m-d H:i:s") . '"')
                        ->all();



        return $this->renderPartial('cetak-booking',[
                    'booking' => $booking,
                    'anggota' => $anggota,
                    ]);

    }
}