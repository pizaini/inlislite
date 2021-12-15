<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;


// Model
use common\models\Survey;
use common\models\SurveyPertanyaan;
use common\models\SurveySearch;
use common\models\Membersonline;
use common\models\SurveyPilihan;
use common\models\SurveyPilihanSesi;
use common\models\SurveyIsian;



// Component


/**
* SurveyController implements the create actions for Members model.
* @author 
*/

class LokerController extends Controller
{
    public $layout = 'base-layout';

	/**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->post()) {
            Yii::$app->session->setFlash('success', 'Belum');
        } 
        
         // echo "Yohohohoho....";
        return $this->render('index',[
            // 'model' => $model,
        ]);
       
    }


    public function actionPertanyaan($id)
    {
        $model = Survey::find()->where(['ID' => $id])->asArray()->one();
        $question = SurveyPertanyaan::find()->where(['Survey_id' => $id])->orderBy('NoUrut')->asArray()->all();
        $member = new Membersonline;
        

        //Foreach for Content Survey
        $stp = $this->pertanyaanItem($question,$model);

        //print_r($stp);

        return $this->render('content',[
            'model' => $model,
            'stp' => $stp,
            'member' => $member,
        ]);
    }



    public function pertanyaanItem($question,$model) {

        $i= 2;
        $stp= array();
        $stp[1]= ['title'=> $model['NamaSurvey'],
                    'icon'=>'glyphicon glyphicon-transfer',
                    'content'=>"<h5>".$model['RedaksiAwal']."</h5>",
                    ];
        $content= array();


        foreach ($question as $question) 
        {
            if ($question['JenisPertanyaan']== 'Pilihan') 
            {
                $pilihan = SurveyPilihan::findAll(['Survey_Pertanyaan_id' => $question['ID'] ]);
                $items = '';

                $content = $this->renderPartial('pilihan',['pilihan'=>$pilihan,'question'=>$question,'items'=>$items]);

            } else 
            {
                $content= "<h5>".$question['Pertanyaan'].'</h5>'.Html::textarea($question['ID'],'',['class'=>'col-sm-12','id'=>'isian-survey']).'<br><br>';
                # code Isian Bebas...
            }
            
            if ($question['IsMandatory'] == 1) {
                $mandatory = false;
            } else {
                $mandatory = true;
            }

            $stp[$i]=[
                'title'=>$i,
                'icon'=>'glyphicon glyphicon-transfer',
                'skippable'=>$mandatory,
                'content'=>"<div onclick='CheckInput(".$question['ID'].")'>".$content."</div>", 
                'buttons' => [
                    'next' => [
                        'title' => Yii::t('app','Next'), 
                        'options' => [
                            'class' => 'btn btn-default',
                            'onclick' => 'nextSteps()',
                            'id' => 'next_step'.$question['ID'],
                            'disabled' => 'disabled'
                        ],
                    ],
                ],
            ];    
           
            $i++;
        }
        return $stp;
    }



    public function actionEntrySurvey($srvid,$pilihan)
    {
        if (Yii::$app->request->isAjax) 
        {
            $pilihan = explode(",", $pilihan);
            foreach ($pilihan as $pil) {
                $SurveyPilihan = SurveyPilihan::findOne($pil);
                $SurveyPilihan->ChoosenCount = ++$SurveyPilihan->ChoosenCount;
                $SurveyPilihan->save();

                $PilihanSesi = new SurveyPilihanSesi();
                $PilihanSesi->Survey_Pilihan_id = $pil;
                $PilihanSesi->MemberNo = 'Nanti ambil di Sesion Jika user login';
                $PilihanSesi->Sesi = $_COOKIE['PHPSESSID'];
                $PilihanSesi->save();
            }

            return 'Berhasil memasukkan data Survey';
        } else{
            return "Nooooo";
        }

        //echo '<pre>'; print_r($post); echo '</pre>';
        //die;
    }


    public function actionEntryIsian($qstid,$isian)
    {
        if (Yii::$app->request->isAjax) 
        {
            $SurveyIsian = new SurveyIsian();
            $SurveyIsian->Survey_Pertanyaan_id = $qstid;
            $SurveyIsian->Sesi = $_COOKIE['PHPSESSID'];
            $SurveyIsian->MemberNo = "dari Session";
            $SurveyIsian->Isian = $isian;
            $SurveyIsian->save();


            return 'Berhasil memasukkan data Survey';
        } else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        //echo '<pre>'; print_r($post); echo '</pre>';
        //die;
    }



    /**
     * Finds the Survey model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Survey the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Survey::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}
