<?php

use yii\helpers\Html;
use kartik\grid\GridView;
// use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\widgets\Pjax;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SurveyIsianSearch $searchModel
 */


$this->title = Yii::t('app', 'Survei Pemustaka');
Yii::$app->view->params['subTitle'] = '<h3 style="padding-top: 15px;">'.Yii::t('app', 'Selamat Datang').' <br> '.Yii::t('app', 'Survey Terhadap Pemustaka').'<h3>';


$now = date("d-m-Y");
?>

<?php // Pjax::begin();?>
<div class="">
    <div class="modal-dialog">
        <div class="">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title text-center"><?= Yii::t('app', 'Silahkan memilih Survei') ?></h4>
            </div>
            <div class="modal-body">
                <?php foreach ($model as $row) { ?>
                    <?php if (strtotime($row['TanggalMulai']) <= strtotime($now) && strtotime($now) < strtotime($row['TanggalSelesai']) ) { ?>
                    <div id="content-survey" style="margin-bottom: 10px" >
                        <?= Html::a('<h4>'.$row['NamaSurvey'].'</h4>', ['pertanyaan', 'id' => $row['ID']],['id'=>'pertanyaan-'.$row['ID'],'class'=>'btn btn-primary btn-md btn-block','style'=>'border-radius:50px']) ?>
                    </div>
                    <?php } ?>
                <?php } ?>
                <p hidden="hidden" id="redaksi-awal-t"></p>
            </div>
            <!-- <div class="modal-footer"> -->
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                <?php // echo Html::a('Kembali', Yii::$app->request->referrer,['class' => 'btn btn-warning' ]); ?>
                <!-- <button type="button" class="btn btn-warning">Kembali</button> -->
            <!-- </div> -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php // Pjax::end();?>




