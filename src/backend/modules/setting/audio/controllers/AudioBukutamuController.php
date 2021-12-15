<?php

namespace backend\modules\setting\audio\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use yii\filters\VerbFilter;

use common\models\Settingparameters;
// use common\models\LocationSearch;


/**
 * AudioBukutamuController implements the CRUD actions for Settingparameters Audio setting.
 */
class AudioBukutamuController extends Controller
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
     * [actionIndex description]
     * @return [type] [description]
     */
    public function actionIndex()
    {
        $this->redirect(\Yii::$app->urlManager->createUrl("/setting/audio/audio-bukutamu/file-setting"));

    }



    /**
     * Lists all Locations models.
     * @return mixed
     */
    public function actionFileSetting()
    {

        $model = Settingparameters::findOne(['Name'=> 'AudioBukutamu']);
        // $model = $this->findModel('37');


         if (Yii::$app->request->post()) {
            if ($model->file = UploadedFile::getInstance($model,'file')) {
                // $model->Value = 'selamat-datang.'.$model->file->extension;
                // $model->save();
                Yii::$app->config->set('AudioBukutamu', 'selamat-datang.'.$model->file->extension);
                $model->file->saveAs( '../uploaded_files/settings/audio/selamat-datang.'.$model->file->extension );
                //$existFile = true;
                //save the path in DB..
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Audio Berhasil di Upload'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            }


        }

        // echo "asdasd";die;
        $oldFile = '../uploaded_files/settings/audio/'.$this->getAudioBukutamu();
        if (file_exists($oldFile)) {
            $existFile = true;
        } else {
            $existFile = false;
            // echo "The file $filename does not exist";
        }

        // echo "string";die;
        
        return $this->render('index', [
          'model' => $model,
          'existFile' => $existFile,
          'audio'=> $this->getAudioBukutamu(),
        ]);
    }



    public function actionDeleteAudio()
    {
        // $model = $this->findModel('37');
        if (unlink('../uploaded_files/settings/audio/'.Yii::$app->config->get('AudioBukutamu'))) 
        {
            // $model->Value = '';
            // $model->save();
            Yii::$app->config->set('AudioBukutamu', ' ');
            Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Audio Berhasil di Hapus'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
        } 
        else 
        {
            Yii::$app->getSession()->setFlash('danger', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Audio gagal di Hapus'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
        }
        
        // unlink('../uploaded_files/settings/audio/selamat-datang.mp3');
        return $this->redirect(['index']);
    }


    /**
     * Get audio for checkpoint/bukutamu
     * @return total 
     */
    public function getAudioBukutamu()
    {
        // 37 is ID in settingparameters for audio bacaditempat/checkpoint
        // $file = Settingparameters::findOne('37');
        $file = Yii::$app->config->get('AudioBukutamu');
        return $file;
    }

  
    /**
     * Finds the Locations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Locations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settingparameters::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
