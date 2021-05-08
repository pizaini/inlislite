<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */
if($for == 'cat')
{
    $pageTitle = Yii::t('app', 'Koreksi Katalog');
    $labelTitle = Yii::t('app', 'Catalogs');
    $alias = $model->BIBID;
}else{
    $pageTitle = Yii::t('app', 'Koreksi Koleksi');
    $labelTitle = Yii::t('app', 'Collections');
    $alias = $model->NomorBarcode;
}
$rdaLabel = '';
if($rda == '1')
{
    $rdaLabel = ' (RDA)';
}
if( \Yii::$app->session['SessCatalogTabActive'] != null)
{
    switch (\Yii::$app->session['SessCatalogTabActive']) {
        case 'katalog':
            $tabStatusKatalog='active in';
            $tabStatusEdisi='';
            $tabStatusKoleksi='';
            $tabStatusCover='';
            $tabStatusKontenDigital='';
            $tabStatusKontenDigitalArtikel='';
            break;
        case 'edisi':
            $tabStatusKatalog='';
            $tabStatusEdisi='active in';
            $tabStatusKoleksi='';
            $tabStatusCover='';
            $tabStatusKontenDigital='';
            $tabStatusKontenDigitalArtikel='';
            break;
        case 'koleksi':
            $tabStatusKatalog='';
            $tabStatusEdisi='';
            $tabStatusKoleksi='active in';
            $tabStatusCover='';
            $tabStatusKontenDigital='';
            $tabStatusKontenDigitalArtikel='';
            break;
        case 'cover':
            $tabStatusKatalog='';
            $tabStatusEdisi='';
            $tabStatusKoleksi='';
            $tabStatusCover='active in';
            $tabStatusKontenDigital='';
            $tabStatusKontenDigitalArtikel='';
            break;
        case 'kontendigital':
            $tabStatusKatalog='';
            $tabStatusEdisi='';
            $tabStatusKoleksi='';
            $tabStatusCover='';
            $tabStatusKontenDigital='active in';
            $tabStatusKontenDigitalArtikel='';
            break;
        case 'artikel':
            $tabStatusKatalog='';
            $tabStatusEdisi='';
            $tabStatusKoleksi='';
            $tabStatusCover='';
            $tabStatusKontenDigital='';
            $tabStatusArtikel='active in';
            $tabStatusKontenDigitalArtikel='';
            break;
        case 'kontendigitalArtikel' :
            $tabStatusKatalog='';
            $tabStatusEdisi='';
            $tabStatusKoleksi='';
            $tabStatusCover='';
            $tabStatusKontenDigital='';
            $tabStatusArtikel='';
            $tabStatusKontenDigitalArtikel = 'active in';
    }
}else{
    $tabStatusKatalog='active in';
    $tabStatusEdisi='';
    $tabStatusKoleksi='';
    $tabStatusCover='';
    $tabStatusKontenDigital='';
    $tabStatusArtikel='';
    $tabStatusKontenDigitalArtikel='';
}

$this->title = $pageTitle .$rdaLabel. ' - ' . $alias;
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
                    if($for=='coll')
                    {
                        $url=Yii::$app->urlManager->createUrl(["akuisisi/koleksi/karantina-proses"]).'?id='.$model->ID;
                    }
                    else if ($for=='cat')
                    {
                        $url=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/karantina-proses"]).'?id='.$model->ID;
                    }
                    
                    echo  '&nbsp;' . Html::a(Yii::t('app', 'Ganti Judul'), ['pilih-judul?for='.$for], ['class' => 'btn btn-primary btn-sm','data-toggle'=>"modal",
                                                        'data-target'=>"#pilihsalin-modal",
                                                        'data-title'=>"Ganti Judul",]);
                    echo  '&nbsp;' . Html::a(Yii::t('app', 'Pindahkan ke Karantina'), '#', ['class' => 'btn btn-danger btn-sm', 
                        'onclick'=>'
                            swal(
                            {   
                              title: "Apakah anda yakin?",   
                              text: "akan memindahkan data ini ke karantina",   
                              showCancelButton: true,   
                              closeOnConfirm: false,   
                              showLoaderOnConfirm: true,
                              confirmButtonColor: "#DD6B55",   
                              confirmButtonText: "OK, Karantinakan!", 
                            }, 
                            function(){   
                              window.location.href = "'.$url.'";
                            });
                        ']);
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
                    'taglist'=>$taglist,
                    'listvar'=>$listvar,
                    'mode'=>$mode,
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

    <?php
    
}else{
?>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="<?=$tabStatusKatalog?>"><a href="#katalog" data-toggle="tab"><?= Yii::t('app','Katalog')?></a></li>
          <?php
            if ($isSerial) {
            echo "<li class=".$tabStatusEdisi."><a href='#edisi' data-toggle='tab'>".Yii::t('app','Edisi Serial')."</a></li> ";
          } ?>
          <li class="<?=$tabStatusKoleksi?>"><a href="#koleksi" data-toggle="tab"><?= Yii::t('app','Koleksi')?></a></li>
          <li class="<?=$tabStatusCover?>"><a href="#cover" data-toggle="tab"><?= Yii::t('app','Cover')?></a></li>
          <li class="<?=$tabStatusKontenDigital?>"><a href="#kontendigital" data-toggle="tab"><?= Yii::t('app','Konten Digital')?></a></li>
            <?php
            if ($isSerial) {
            echo "<li class=".$tabStatusArtikel."><a href='#artikel' data-toggle='tab'>".Yii::t('app','Artikel')."</a></li> ";
            echo "<li class=".$tabStatusKontenDigitalArtikel."><a href='#kontenDigitalArtikel' data-toggle='tab'>".Yii::t('app','Konten Digital Artikel')."</a></li> ";
            } ?>

        </ul>
        <div class="tab-content">
          
            
          <!-- Detail Katalog -->
          <div class="tab-pane fade <?=$tabStatusKatalog?>" id="katalog">
             <div class="row" style="padding:10px">

              <br/>
                <div class="col-sm-12">
                <?php
                    echo '<p>';
                    echo  Html::button($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Membuat'), ['id'=>'btnSave','onclick'=>'js:ValidationBibliografis();','class' =>'btn btn-success btn-sm']);
                    if($for=='coll')
                    {
                        $url=Yii::$app->urlManager->createUrl(["akuisisi/koleksi/karantina-proses"]).'?id='.$model->ID;
                    }
                    else if ($for=='cat')
                    {
                        $url=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/karantina-proses"]).'?id='.$model->ID;
                    }
                    echo  '&nbsp;' . Html::a(Yii::t('app', 'Salin Sebagai Katalog Baru'), ['create?for='.$for.'&rda='.$rda.'&dc='.$model->ID], ['class' => 'btn btn-primary btn-sm']);

                    echo  '&nbsp;' . Html::a(Yii::t('app', 'Pindahkan ke Karantina'), '#', ['class' => 'btn btn-danger btn-sm', 
                        'onclick'=>'
                            swal(
                            {   
                              title: "Apakah anda yakin?",   
                              text: "akan memindahkan data ini ke karantina",   
                              showCancelButton: true,   
                              closeOnConfirm: false,   
                              showLoaderOnConfirm: true,
                              confirmButtonColor: "#DD6B55",   
                              confirmButtonText: "OK, Karantinakan!", 
                            }, 
                            function(){   
                              window.location.href = "'.$url.'";
                            });
                        ']);

                    echo  '&nbsp;' . Html::a(Yii::t('app', 'Detail'), ['detail?id='.$model->ID], ['class' => 'btn btn-primary btn-sm']);
                    
                    echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), $referUrl, ['class' => 'btn btn-warning btn-sm']);
                    
                    //echo  '&nbsp;' . Html::a(Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
                    echo '</p>';
                ?>
                </div>
                <br>
                <br>
                <br>
                <br>
                <?= $this->render('_form', [
                    'worksheetid'=>$worksheetid,
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
                            'id'=>'catalog',
                            'urlHistori'=>'detail-histori?id='.$model->ID.'&for='.$for
                        
                    ]);
                ?>
             </div>
             
              
          </div>
          <!-- Cover -->
          <div class="tab-pane fade <?=$tabStatusCover?>" id="cover">
            <div class="row">
             <br/>
                 <?= $this->render('_formCover', [
                  'model' => $model,
                  'referrerUrl'=>$referrerUrl
              ]) ?>

            </div>
          </div>

         
          <!-- list koleksi -->
          <div class="tab-pane fade <?=$tabStatusKoleksi?>" id="koleksi">
            <div class="row">
             <br/>
               <?= $this->render('_formCollections', [
                    'dataProviderColl' => $dataProviderColl,
                    'searchModelColl' => $searchModelColl,
                    'rulesColl'=>$rulesColl,
                    'rda'=>$rda,
                    'for'=>$for,
                    'id'=>Yii::$app->getRequest()->getQueryParam('id'),
                    'edit'=>Yii::$app->getRequest()->getQueryParam('edit'),
                    'referrerUrl'=>$referrerUrl
              ]) ?>


            </div>
          </div>

          <!-- konten digital -->
          <div class="tab-pane fade <?=$tabStatusKontenDigital?>" id="kontendigital">
            <div class="row">
             <br/>
               <?= $this->render('_formDigitalContent', [
                    'model' => $modelkontendigital,
                    'dataProviderKontenDigital' => $dataProviderKontenDigital,
                    'searchModelKontenDigital' => $searchModelKontenDigital,
                    'id'=>Yii::$app->getRequest()->getQueryParam('id'),
              ]) ?>


            </div>
          </div>


            <!-- Artikel -->
            <div class="tab-pane fade <?=$tabStatusArtikel?>" id="artikel">
                <div class="row">
                    <br/>
                    <?= $this->render('_formArticles', [
                        'dataProviderArticles' => $dataProviderArticles,
                        'searchModelArticles' => $searchModelArticles,
                        'rulesColl'=>$rulesColl,
                        'rda'=>$rda,
                        'for'=>$for,
                        'id'=>Yii::$app->getRequest()->getQueryParam('id'),
                        'edit'=>Yii::$app->getRequest()->getQueryParam('edit'),
                        'referrerUrl'=>$referrerUrl
                    ]) ?>


                </div>
            </div>

            <!-- edisi serial -->
            <div class="tab-pane fade <?=$tabStatusEdisi?>" id="edisi">
                <div class="row">
                    <br/>
                    <?= $this->render('_formEdisiSerial', [
                        'dataProviderEdisiSerial' => $dataProviderEdisiSerial,
                        'searchModelEdisiSerial' => $searchModelEdisiSerial,
                        'rulesColl'=>$rulesColl,
                        'rda'=>$rda,
                        'for'=>$for,
                        'id'=>Yii::$app->getRequest()->getQueryParam('id'),
                        'edit'=>Yii::$app->getRequest()->getQueryParam('edit'),
                        'referrerUrl'=>$referrerUrl
                    ]) ?>


                </div>
            </div>

            <!-- Koleksi Digital Artikel -->
            <div class="tab-pane fade <?=$tabStatusKontenDigitalArtikel?>" id="kontenDigitalArtikel">
                <div class="row">
                    <br/>
                    <?= $this->render('_formDigitalContentArticle', [
                        'model' => $modelkontendigitalArtikel,
                        'dataProviderKontenDigitalArticles' => $dataProviderKontenDigitalArticles,
                        'searchModelKontenDigitalArticles' => $searchModelKontenDigitalArticles,
                        'dataProviderArticles' => $dataProviderArticles,
                        'dataProviderArticlesWithKontenDigital' => $dataProviderArticlesWithKontenDigital,
                        'searchModelArticle' => $searchModelArticles,
                        'rulesColl'=>$rulesColl,
                        'rda'=>$rda,
                        'for'=>$for,
                        'id'=>Yii::$app->getRequest()->getQueryParam('id'),
                        'edit'=>Yii::$app->getRequest()->getQueryParam('edit'),
                        'referrerUrl'=>$referrerUrl
                    ]) ?>


                </div>
            </div>

        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->

<?php 
}
?>   
            

    
