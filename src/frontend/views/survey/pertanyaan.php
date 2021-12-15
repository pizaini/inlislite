<?php

// Widgets
use yii\helpers\Html;
use kartik\grid\GridView;
// use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SurveyIsianSearch $searchModel
 */


?>


<?php // Pjax::begin();?>
<div class="">
    <div class="modal-dialog">
        <!-- <div class="modal-content"> -->
        <div class="">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title"><?= $model['NamaSurvey']; ?></h4>
            </div>
            <div class="modal-body">
                <p id="redaksi-awal-t"><?= $model['RedaksiAwal']; ?></p>
            </div>   

            <div class="modal-body">
                <?php if ($model['TargetSurvey'] == 1) { ?>
                <?php $form = ActiveForm::begin(['id' => 'login-form',]); ?>
                <?= $form->field($member, 'NoAnggota') ?>
                <?= $form->field($member, 'Password')->passwordInput() ?>
                <?php ActiveForm::end(); ?>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <?php if ($model['TargetSurvey'] == 1) { ?>
                <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary pull-right']); ?>
                <?php } else{ ?>
                <?= Html::a('Next', ['content'],['class' => 'btn btn-primary']) ?>
                <!-- <button type="button" class="btn btn-primary">Next</button> -->
                <?php } ?>
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php //Pjax::end();?>




