<?php

namespace backend\modules\survey\controllers;

use Yii;
use yii\web\Controller;
use common\models\Survey;
use common\models\SurveyPertanyaan;
use common\models\SurveySearch;
use common\models\Membersonline;
use common\models\SurveyPilihan;
use common\models\SurveyIsian;

class DefaultController extends Controller
{
    public function actionIndex()
    {
    	// echo "Yohohohoho....";
    	// $model = Survey::findAll(['IsActive' => 1]);
    	$model = Survey::find()->where(['IsActive' => 1])->asArray()->all();

    	// print_r($model);
    	// die;
        return $this->render('index',[
        	'model' => $model,
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

    	//echo "Yohohohoho....";
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
///////////////////////////////////////////////
		        $content = $this->renderPartial('pertanyaan',['pilihan'=>$pilihan,'question'=>$question,'items'=>$items]);

		       // $content = "<h4>".$question['Pertanyaan'] ."</h4>".$items;
		       
		    } else 
		    {
		        $content= "<h4>".$question['Pertanyaan']."</h4> <textarea class='col-sm-10' name=''></textarea>";
		        # code Isian Bebas...
		    }
		    
		    if ($question['IsMandatory'] == 1) {
		        $mandatory = false;
		    } else {
		        $mandatory = true;
		    }

		    $stp[$i]=['title'=>$i,
		            'icon'=>'glyphicon glyphicon-transfer','skippable'=>$mandatory,
		            'content'=>$content, 
		            ];    
		   
		    $i++;
		}

		return $stp;

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
