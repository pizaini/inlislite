<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */
if($for == 'coll')
{
    $pageTitle = Yii::t('app', 'Koreksi Koleksi SSKCKR');
    $labelTitle = Yii::t('app', 'Koleksi SSKCKR');
    $alias = $model->NomorBarcode;
    $tabStatusKoleksi='active in';
}


$this->title = $pageTitle. ' - ' . $alias;
$this->params['breadcrumbs'][] = ['label' => $labelTitle, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $alias, 'url' => ['detail', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

if($referrerUrl == NULL) {
    $referUrl = ($for=='cat') ? 'index' : '/akuisisi/koleksi/index';
}else{
    $referUrl = $referrerUrl;
}


?>
<style type="text/css">
    .nav-tabs-custom > .nav-tabs > li.active
    {
    border-top-color: green;
    }

     .nav-tabs
    {
            font-family: 'Source Sans Pro', sans-serif;
            font-weight: bold;
            font-size: 12px;
            
    }
    .InfoTable {
        padding: 20px;
        border: thin solid #C0C0C0;
        background-color: #E2E9F2;
    }
</style>
<input type="hidden" id="hdnCatalogId" value="<?=Yii::$app->getRequest()->getQueryParam('id')?>">
<?php 
if($for=='coll')
{
    ?>
                <div class="collections-update">
                <div class="col-sm-12">
                <?php
                    echo '<p>';
                    echo  Html::button($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['id'=>'btnSave','onclick'=>'js:ValidationBibliografis();','class' =>'btn btn-success btn-sm']);
                    
                    echo  '&nbsp;' . Html::a(Yii::t('app', 'Ganti Judul'), ['pilih-judul?for='.$for], ['class' => 'btn btn-primary btn-sm','data-toggle'=>"modal",
                                                        'data-target'=>"#pilihsalin-modal",
                                                        'data-title'=>"Ganti Judul",]);
                    echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), $referUrl, ['class' => 'btn btn-warning btn-sm']);
                    
                    //echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
                    echo '</p>';
                ?>
                </div>
                <br>
                <br>
                <?= $this->render('_form', [
                    'worksheetid'=>$worksheetid,
                    'model' => $model,
                    'modelcat' => $modelcat,
                    'modelbib' => $modelbib,
                    'modeltaksiran' => $modeltaksiran,
                    'taglist'=>$taglist,
                    'listvar'=>$listvar,
                    'mode'=>$mode,
                    'edit'=>$edit,
                    'for'=>$for,
                    'rda'=>$rda,
                    'rulesform'=>$rulesform,
                    'isAdvanceEntry'=> $isAdvanceEntry,
                    'referrerUrl'=>$referrerUrl
                ]) ?>
        
                <br>
                <br>
                <div class="col-sm-12">
                <?php
                    echo '<p>';
                    echo  Html::button($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Create'), ['id'=>'btnSave2','onclick'=>'js:ValidationBibliografis();','class' =>'btn btn-success btn-sm']);

                    
                    echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), $referUrl, ['class' => 'btn btn-warning btn-sm']);
                    
                    //echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
                    echo '</p>';
                ?>
                </div>
                <!-- HISTORY -->
                <?php
                    echo \common\widgets\Histori::widget([
                            'model'=>$model,
                            'id'=>'collection',
                            'urlHistori'=>'detail-histori?id='.$model->ID.'&for='.$for
                        
                    ]);
                ?>
                </div>

    <?php } ?>
      
            

    
