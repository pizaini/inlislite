<?php

namespace digitalcollection\controllers;

class RiwayatPencarianController extends \yii\web\Controller
{
    public $layout = 'main-sederhana';
    public function actionIndex()
    {
        return $this->render('index');
    }

}
