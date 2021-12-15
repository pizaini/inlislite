<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */

if($for == 'cat')
{
	$pageTitle = Yii::t('app', 'Create Catalogs');
	$labelTitle = Yii::t('app', 'Catalogs');
}else{
	$pageTitle = Yii::t('app', 'Create Collections');
	$labelTitle = Yii::t('app', 'Collections');
}

$rdaLabel = '';
if($rda == '1')
{
    $rdaLabel = ' (RDA)';
}
$this->title = $pageTitle.$rdaLabel;
$this->params['breadcrumbs'][] = ['label' => $labelTitle, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if ($for=='cat') {

    $referUrl = Yii::$app->urlManager->createUrl(["/pengkatalogan/katalog/index"]);
}else{
    $referUrl = Yii::$app->urlManager->createUrl(["/akuisisi/koleksi/index"]);
}
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
                /*echo  '&nbsp;' . Html::a(Yii::t('app', ($for=='cat') ? Yii::t('app', 'Salin Judul') : Yii::t('app', 'Pilih Judul')), ['pilih-judul'], ['class' => 'btn btn-primary  btn-sm','data-toggle'=>"modal",
                                                    'data-target'=>"#pilihsalin-modal",
                                                    'data-title'=>"Pilih Judul",]);
                echo  '&nbsp;' . Html::a(Yii::t('app', 'Salin Katalog dari'), ['salin-katalog?for='.$for], ['class' => 'btn btn-primary btn-sm','data-toggle'=>"modal",
                                                    'data-target'=>"#pilihsalin-modal",
                                                    'data-title'=>"Salin Katalog",]);*/
                //$referUrl = ($for=='cat') ? 'index' : '/akuisisi/koleksi/index';
                echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), $referUrl, ['class' => 'btn btn-warning btn-sm']);
                
                //echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
                echo '</p>';
            ?>
            </div>
        </h3>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'modelcat' => $modelcat,
        'modelbib' => $modelbib,
        'taglist'=>$taglist,
        'listvar'=>$listvar,
        'mode'=>$mode,
        'for'=>$for,
        'rda'=>$rda,
        'rulesform'=>$rulesform,
        'isAdvanceEntry'=> $isAdvanceEntry,
        'referrerUrl'=>$referUrl
    ]) ?>
    <br>
    <br>
    <?php
        echo '<p>';
        echo  Html::button($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['id'=>'btnSave2','onclick'=>'js:ValidationBibliografis();','class' =>'btn btn-success btn-sm']);
       /* echo  '&nbsp;' . Html::a(Yii::t('app', ($for=='cat') ? 'Salin Judul' : 'Pilih Judul'), ['pilih-judul?for='.$for], ['class' => 'btn btn-primary btn-sm','data-toggle'=>"modal",
                                        'data-target'=>"#pilihsalin-modal",
                                        'data-title'=>"Pilih Judul",]);
        echo  '&nbsp;' . Html::a(Yii::t('app', 'Salin Katalog dari'), ['salin-katalog?for='.$for], ['class' => 'btn btn-primary btn-sm','data-toggle'=>"modal",
                                        'data-target'=>"#pilihsalin-modal",
                                        'data-title'=>"Salin Katalog",]);*/
        //$referUrl = ($for=='cat') ? 'index' : '/akuisisi/koleksi/index';
        echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), $referUrl, ['class' => 'btn btn-warning btn-sm']);
        
        //echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
        echo '</p>';
    ?>
            
     
</div>
