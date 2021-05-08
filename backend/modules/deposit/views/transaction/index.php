<?php 
use yii\helpers\Html;


$this->title = 'SSKCKR';

?>

<div class="collections-create">
    <div class="page-header">
        <h3>
            &nbsp;
            <!-- <span class="glyphicon glyphicon-plus-sign"></span> Tambah -->

            <div class="pull-left">
            <?php
                echo '<p>';
                echo  Html::button($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['id'=>'btnSave','onclick'=>'js:ValidationBibliografis();','class' => 'btn btn-success btn-sm']);
                echo  '&nbsp;' . Html::a(Yii::t('app', 'Pilih Judul'), ['pilih-judul'], ['class' => 'btn btn-primary  btn-sm','data-toggle'=>"modal",
                                                    'data-target'=>"#pilihsalin-modal"]);
                //$referUrl = ($for=='cat') ? 'index' : '/akuisisi/koleksi/index';
                echo  '&nbsp;' . Html::a(Yii::t('app', 'Kembali'), Yii::$app->urlManager->createUrl(["/deposit/transaction/index"])/*$referUrl*/, ['class' => 'btn btn-warning btn-sm']);
                
                //echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
                echo '</p>';
            ?>
            </div>
        </h3>
    </div>
    <!-- <?//= $this->render('_form', [
        //'modelcat' => $modelcat,
        //'model' => $model
    //]) ?>   -->

    <?= $this->render('_form', [
        'model' => $model,
        'modelcat' => $modelcat,
        'modelbib' => $modelbib,
        'modeltaksiran' => $modeltaksiran,
        'taglist'=>$taglist,
        'listvar'=>$listvar,
        'mode'=>$mode,
        'for'=>$for,
        'rda'=>$rda,
        'dep'=>$dep,
        'rulesform'=>$rulesform,
        'isAdvanceEntry'=> $isAdvanceEntry,
        'referrerUrl'=>$referUrl
    ]) ?>            
     
</div>