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
use yii\helpers\Json;

// Model
use common\models\Survey;
use common\models\SurveyPertanyaan;
use common\models\SurveySearch;
use common\models\Membersonline;
use common\models\Members;
use common\models\SurveyPilihan;
use common\models\SurveyPilihanSesi;
use common\models\SurveyIsian;

use yii\helpers\Url;

use dosamigos\highcharts\HighCharts;

////////////////////////
use yii\grid\GridView;
use yii\data\ActiveDataProvider;



// Component

/**
* SurveyController implements the create actions for Members model.
* @author 
*/

class SurveyController extends Controller
{
	public $layout = 'base-layout';
	/**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // echo "Yohohohoho....";
        // $model = Survey::findAll(['IsActive' => 1]);
        // session_start();
        $model = Survey::find()->where(['IsActive' => 1])->orderBy('NomorUrut')->asArray()->all();

        // print_r($model);
        // die;
        return $this->render('index',[
            'model' => $model,
        ]);
    }

    /**
     * [actionPertanyaan description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionPertanyaan($id)
    {
        // Menghapus memberNo dari cookie;
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('NoAnggotaforSurvey');
        unset($cookies['NoAnggotaforSurvey']);

        $model = Survey::find()->where(['ID' => $id])->asArray()->one();
        // $question = SurveyPertanyaan::find()->where(['Survey_id' => $id])->orderBy('NoUrut')->asArray()->all();
        $question = SurveyPertanyaan::find()->where(['Survey_id' => $id])->orderBy('NoUrut')->all();
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


    /**
     * [pertanyaanItem description]
     * @param  [type] $question [description]
     * @param  [type] $model    [description]
     * @return [type]           [description]
     */
    public function pertanyaanItem($question,$model) {

        $i= 2;
        $stp= array();

        // ($model['TargetSurvey'] == 1 ? $disabled =['disabled' => 'disabled'] : $disabled = '');

        if ($model['TargetSurvey'] == 1) {
            $stp[1]= [
                        // 'title'=> $model['NamaSurvey'],
                        'title'=> 1,
                        'icon'=>'glyphicon glyphicon-check',
                        'content'=>"<h4 class='text-center'>".$model['RedaksiAwal']."</h4>
                                <div class='row' style='margin-top: 20px;margin-bottom: 20px'>
                                    <div class=\"col-sm-8 col-sm-offset-2 text-center \">
                                        <label>Nomor Anggota</label>
                                        <input class=\"form-control login-field text-center\" value=\"\" id=\"login-name\" name='login-name' type=\"text\">
                                        <label>Password</label>
                                        <input class=\"form-control login-field text-center\" value=\"\" id=\"login-pass\" name='login-pass' type=\"password\">
                                    </div>
                                </div>
                                <div class='col-sm-12'><button id='stepwizard_step1_next_wahooo' type='button' class='btn btn-lg btn-primary col-sm-4 col-sm-offset-4'>Submit</button></div>
                                ",
                        'buttons' => [
                            'next' => [
                                'title' => Yii::t('app','Next'), 
                                'options' => [
                                    'class' => 'btn btn-lg btn-primary',
                                    // 'onclick' => 'nextSteps()',
                                    // 'id' => 'next_step'.$question['ID'],
                                    'disabled' => 'disabled'
                                ],
                            ],
                            // 'prev' => Html::a('Kembali ke survey', Url::to(['/survey']),['class' => 'btn btn-lg btn-primary pull-right','data-pjax'=>'0','style'=>'margin-bottom: 30px;' ])
                        ],
                    ];
        } else {
            $stp[1]= [
                        // 'title'=> $model['NamaSurvey'],
                        'title'=> 1,
                        'icon'=>'glyphicon glyphicon-check',
                        'content'=>"<h4 style='margin-bottom: 20px;' class='text-center'>".$model['RedaksiAwal']."</h4>",
                        'buttons' => [
                            'next' => [
                                'title' => Yii::t('app','Next'), 
                                'options' => [
                                    'class' => 'btn btn-lg btn-primary',
                                    // 'onclick' => 'nextSteps()',
                                    // 'id' => 'next_step'.$question['ID'],
                                ],
                            ],
                            // 'html' => ['title' => Html::a('Kembali ke survey', Url::to(['/survey']),['class' => 'btn btn-lg btn-primary pull-right','data-pjax'=>'0','style'=>'margin-bottom: 30px;' ])]
                        ],
                    ];
        }
        
        $content= array();
        $jmlPertanyaan = count($question);
        $currentQ = 1;
        foreach ($question as $question) 
        {
            if ($question->JenisPertanyaan == 'Pilihan') 
            {
                $pilihan = SurveyPilihan::findAll(['Survey_Pertanyaan_id' => $question->ID ]);
                $items = '';

                $content = $this->renderPartial('pilihan',['pilihan'=>$pilihan,'question'=>$question,'items'=>$items]);
                $tipe = 0;
            } 
            else 
            {
                # code Isian Bebas...
                $content= "<h4 class='text-center'>".$question->Pertanyaan.'</h4>'.Html::textarea($question->ID,'',['class'=>'col-sm-12','id'=>'isian-survey']).'<br><br>';
                $tipe = 1;
            }
            
            // echo '<h1>'.$currentQ.'<h1>';

            if ($currentQ !== $jmlPertanyaan) 
            {
                if ($question->IsMandatory == 1) 
                {
                    $mandatory = false;
                    $cekinput = "onclick='CheckInput(".$question->ID.",".$tipe.")'";
                } 
                else 
                {
                    $mandatory = true;
                    $cekinput = "onclick='CheckInput(".$question->ID.",".$tipe.")'";
                }
            } 
            else 
            {
                if ($question->IsMandatory == 1) 
                {
                    $mandatory = false;
                    $cekinput = "onclick='CheckInput(".$question->ID.",".$tipe.")'";
                } 
                else 
                {
                    $mandatory = false;
                    $cekinput = "onmouseover='enableSaveButton(".$question->ID.",".$tipe.")'";
                }
            }
            

            $stp[$i]=[
                'title'=>$i,
                'icon'=>'glyphicon glyphicon-check',
                'skippable'=>$mandatory,
                'content'=> "<h4 class='text-center'> Pertanyaan ".($i-1)." dari ".$jmlPertanyaan."</h4>".
                        "<div style='margin-bottom: 20px;' ".$cekinput." >".$content."</div>", 
                'buttons' => [
                    'next' => [
                        'title' => Yii::t('app','Next'), 
                        'options' => [
                            'class' => 'btn btn-lg btn-primary',
                            'onclick' => 'nextSteps('.$question->ID.','.$tipe.')',
                            'id' => 'next_step'.$question->ID,
                            'disabled' => 'disabled'
                        ],
                    ],
                ],
            ];    
           
            $i++;
            $currentQ++;
        }
        return $stp;
    }


    /**
     * [actionCheckMembership description]
     * @return [type] [description]
     */
    public function actionCheckMembership()
    {
        $nomember = $_POST['nomember'];
        $passmember = $_POST['passmember'];
        $data = Membersonline::findOne(['NoAnggota' => $nomember,'Password' => SHA1($passmember)]);
        echo Json::encode($data);
        // echo Json::encode($_POST);
        $cookies = Yii::$app->response->cookies;
        if ($data !== null) 
        {
            $cookies->add(new \yii\web\Cookie([
                'name' => 'NoAnggotaforSurvey',
                'value' => $nomember,
            ]));
            
        } 
        else 
        {
            $cookies->add(new \yii\web\Cookie([
                'name' => 'NoAnggotaforSurvey',
                'value' => null,
            ]));
        }
    }

    /**
     * [actionEntrySurvey description]
     * @param  [type] $srvid   [description]
     * @param  [type] $pilihan [description]
     * @return [type]          [description]
     */
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
                $PilihanSesi->MemberNo = Yii::$app->request->cookies->getValue('NoAnggotaforSurvey');
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

    /**
     * [actionEntryIsian description]
     * @param  [type] $qstid [description]
     * @param  [type] $isian [description]
     * @return [type]        [description]
     */

    public function actionEntryIsian()
    {
        // echo'<pre>';print_r($_POST);echo'</pre>';die;
        if (Yii::$app->request->isAjax) 
        {
            foreach ($_POST['jawaban'] as $key => $value) {
                
                $SurveyIsian = new SurveyIsian();
                $SurveyIsian->Survey_Pertanyaan_id = $value['idqst'];
                $SurveyIsian->Sesi = $_COOKIE['PHPSESSID'];
                $SurveyIsian->MemberNo = Yii::$app->request->cookies->getValue('NoAnggotaforSurvey');
                $SurveyIsian->Isian = $value['jwb'];
                $SurveyIsian->save();
            }


            return 'Berhasil memasukkan data Survey';
            // $SurveyIsian = new SurveyIsian();
            // $SurveyIsian->Survey_Pertanyaan_id = $qstid;
            // $SurveyIsian->Sesi = $_COOKIE['PHPSESSID'];
            // $SurveyIsian->MemberNo = Yii::$app->request->cookies->getValue('NoAnggotaforSurvey');
            // $SurveyIsian->Isian = $isian;
            // $SurveyIsian->save();


            // return 'Berhasil memasukkan data Survey';
        } else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        //echo '<pre>'; print_r($post); echo '</pre>';
        //die;
    }

    /**
     * [actionHasilSurvey description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionHasilSurvey($id)
    {
        // echo "Hasil Survey";
        $model = Survey::find()->where(['ID' => $id])->asArray()->one();
       
        $pertanyaan = SurveyPertanyaan::find()->where(['Survey_id' => $id])->orderBy('NoUrut')->asArray()->all();
        $contentIsian = array();

        foreach ($pertanyaan as $pertanyaan) 
        {
            $forChart = SurveyPilihan::find()->where(['Survey_Pertanyaan_id' => $pertanyaan['ID']])->asArray()->all();

            // Variable label untuk Diagram PIE
            $labelChart = array();

            // Variable untuk diagram batang
            $valueChart = array();
            $categoriesChart = array();

            foreach ($forChart as $forChart) 
            {

                array_push($labelChart, ['y' => intval($forChart['ChoosenCount']),
                    'name' => $forChart['Pilihan']]);

                array_push($valueChart,intval($forChart['ChoosenCount']));
                array_push($categoriesChart,$forChart['Pilihan']);
            }

            if ($pertanyaan['JenisPertanyaan'] == 'Pilihan') 
            {
                $chartContent[] = HighCharts::widget([
                    'clientOptions' => [
                        'chart' => [
                                'type' => 'pie'
                        ],
                        'title' => [
                             'text' => $pertanyaan['Pertanyaan']
                             ],

                        'tooltip' => [
                             'pointFormat' => 'Jumlah Pemilih: <b>{point.y}</b>'
                             ],

                        'plotOptions' => [
                             'pie' => [
                                 'allowPointSelect' => true,
                                 'cursor' => 'pointer',
                                 'dataLabels' => [
                                    'enabled' => true,
                                    'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    'style' => ['color'=> ('Highcharts.theme && Highcharts.theme.contrastTextColor') || 'black'],
                                  ],
                                 'showInLegend' => true,
                                 ],
                             ],
                        'series' => [
                            [
                                'name' => 'Pilihan',
                                'colorByPoint' => 'true',
                                'data' => 
                                    $labelChart
                                ,
                            ]
                        ],
                    ]
                ]);
            }
            else
            {
                // $forTable = SurveyIsian::find()->where(['Survey_Pertanyaan_id' => $pertanyaan['ID']])->asArray()->all();

                $dataProviderIsian = new ActiveDataProvider([
                    'query' => SurveyIsian::find()->addselect(['Isian', 'COUNT(Isian) AS Sesi'])->where(['Survey_Pertanyaan_id'=> $pertanyaan['ID']])->groupBy('Isian'),
                    'pagination' => [
                        'pagesize' => 5,
                    ],
                ]);
                // echo'<pre>';print_r($dataProviderIsian);echo'</pre>';

                $contentIsian[] = '<center><h4> Isian : '.$pertanyaan['Pertanyaan'].'</h4></center>'.GridView::widget([
                    'dataProvider' => $dataProviderIsian,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        // 'ID',
                        'Isian',
                        [
                            'attribute' => 'Sesi',
                            'label' => yii::t('app','Jumlah'),
                        ],
                    ],
                ]);
                
            }




        }

        return $this->render('hasil-survey',[
            'model' => $model,
            'chartContent' => $chartContent,
            'contentIsian' => $contentIsian,
            ]);
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
