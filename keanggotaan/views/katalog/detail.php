<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;
use common\models\Cardformats;
use yii\helpers\ArrayHelper;
?>

<?php

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */

$this->title = 'Detail Katalog - '.$model->BIBID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Detail Katalog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->BIBID;
?>
<div class="catalogs-detail">
     <div class="col-sm-3" style="margin-left:-15px">
        <div class="thumbnail template-thumbnail" style="margin-bottom: 10px">
          <?php
              $urlcover;
              if($model->CoverURL)
              {
                  $urlcover= '../../../uploaded_files/sampul_koleksi/original/Monograf/'.$model->CoverURL.'?rnd='.rand();
                  
              }else{
                  $urlcover= '../../../uploaded_files/sampul_koleksi/nophoto.jpg';
              }

              if(file_exists(Yii::getAlias('@uploaded_files/sampul_koleksi/original/Monograf/'.$model->CoverURL)))
              {
                  echo Html::img($urlcover, [
                      'class' => 'template-thumbnail',
                      'style' =>[
                           'width'=>'200px'
                      ]

                  ]); 
              }else{
                  echo '<span style="color:Red"><center>'.$model->CoverURL.' <br><b>File cover tidak ditemukan</b></center></span>';
              }

          ?>
          </div>
          <div class="thumbnail template-thumbnail" style="padding: 5px; text-align: center;margin-bottom: 10px">
          <b>Jumlah Eksemplar : <span style="font-size: 13px"> <?=$jumlahEksemplar?></span></b>
          </div>
          <div class="thumbnail template-thumbnail" style="padding: 5px;margin-bottom: 10px">
          <center><b>Cetak Bentuk kartu :</b></center>
          <br>
           <?php
            echo Select2::widget([
            'id' => 'cbCardFormat',
            'name' => 'cbCardFormat',
            'data' => ArrayHelper::map(Cardformats::find()->all(),'ID','Name'),
            'size' => 'sm',
            'addon'=> [
                          'prepend' => [
                              'content' => '<i class="glyphicon glyphicon-print"></i>'
                          ],
                          'append' => [
                              'content' => Html::button('<i class="glyphicon glyphicon-save"></i>', [
                                  'id'=>'btnCetakkartu',
                                  'class' => 'btn btn-primary btn-sm', 
                                  'title' => 'Cetak', 
                                  //'data-toggle' => 'tooltip'
                              ]),
                              'asButton' => true
                          ]
                      ]
            ]);
           ?>
          </div>
   </div>
   <div class="col-sm-9" style="padding: 0px">
       <div class="nav-tabs-custom success">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#lengkap" data-toggle="tab"><?= Yii::t('app','Bentuk Lengkap')?></a></li>
                  <li><a href="#marc" data-toggle="tab"><?= Yii::t('app','Bentuk MARC')?></a></li>
                  <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                      Unduh Katalog <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="download?id=<?=Yii::$app->request->get('id')?>&amp;type=MARC21">Format MARC Unicode/UTF-8</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="download?id=<?=Yii::$app->request->get('id')?>&amp;type=MARCXML">Format MARC XML</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="download?id=<?=Yii::$app->request->get('id')?>&amp;type=MODS">Format MODS</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="download?id=<?=Yii::$app->request->get('id')?>&amp;type=DC_RDF">Format Dublin Core (RDF)</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="download?id=<?=Yii::$app->request->get('id')?>&amp;type=DC_OAI">Format Dublin Core (OAI)</a></li>
                      <li role="presentation"><a role="menuitem" tabindex="-1" href="download?id=<?=Yii::$app->request->get('id')?>&amp;type=DC_SRW">Format Dublin Core (SRW)</a></li>
                    </ul>
                  </li>
                </ul>
                <div class="tab-content">
                  
                    
                  <!-- LENGKAP -->
                  <div class="tab-pane fade active in" id="lengkap">
                    <table class="table table-striped">
                        <tbody>
                        <?php 
                        foreach ($data['Detail'] as $key2 => $value) {
                        ?>
                        <tr>
                          <td style="width: 200px"><?=$value['Label']?></td>
                          <td style="width: 10px">:</td>
                          <td><?=$value['Value']?></td>
                        </tr>
                        <?php
                        }
                        ?>

                        
                      </tbody>
                    </table>

                  </div>
                  <!-- MARC  -->
                  <div class="tab-pane fade" id="marc">
                     <table class="table table-striped">
                        <tbody>
                        <tr style="background-color: #eeeeee">
                          <td style="width: 70px">Tag</td>
                          <td style="width: 70px">Indikator 1</td>
                          <td style="width: 70px">Indikator 2</td>
                          <td>Isi</td>
                        </tr>
                        <?php 
                        foreach ($data['Taglist'] as $index => $datatags) {
                        ?>
                          <tr>
                            <td><?=$datatags['tag']?></td>
                            <td><?=$datatags['ind1']?></td>
                            <td><?=$datatags['ind2']?></td>
                            <td><?=$datatags['value']?></td>
                          </tr>
                        <?php
                        }
                        ?>
                        
                      </tbody>
                    </table>

                  </div>

                </div><!-- /.tab-content -->
            </div><!-- /.nav-tabs-custom -->
       
   </div>
  

</div>
 <div class="row" >
        <div class="col-sm-12" >
            <div class="box-title" style="background-color: #f4f4f4; padding:8px; font-size: 12px; font-weight: bold; border-top: dotted 1px #CCC;">Daftar Konten Digital</div>
            <div class="box  box-default">
                <div class="box-body no-padding">
                  <table class="table table-condensed">
                    <tbody>
                    <tr style="font-weight: bold; font-size: 12px">
                      <td style="width: 2.99%;">#</td>
                      <td style="width: 50%">File</td>
                      <td>File flash (swf)</td>
                    </tr>
                    <?php 
                    $no=0;
                    foreach ($modelfiles as $data) {
                    $no++;
                    ?>
                        <tr>
                          <td><?=$no?></td>
                          <td><a target='_blank' href='../../../uploaded_files/dokumen_isi/Monograf/<?=$data->FileURL?>' ><?=$data->FileURL?></a></td>
                          <td>
                          <?php 
                          if(!($data->FileFlash)) { echo "-"; } else {
                          ?>
                          <a target='_blank' href='../../../uploaded_files/dokumen_isi/Monograf/<?=str_replace(".rar","",str_replace(".zip","",$data->FileURL)).'/'.$data->FileFlash?>' ><?=$data->FileFlash?></a>
                          <?php
                          }
                          ?>
                          </td>
                        </tr>
                    <?php
                    }
                    ?>
                    
                  </tbody></table>
                </div>
                <!-- /.box-body -->
              </div>
        </div>

</div>

<div class="collection-detail">
   <div class="col-sm-12" style="padding: 0px; padding-bottom: 20px">
   <div class="box-title" style="background-color: #f4f4f4; padding:8px; font-size: 12px; font-weight: bold; border-top: dotted 1px #CCC;">Daftar Koleksi</div>
        <div class="box box-default">
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <?php Pjax::begin(['id' => 'myGridviewCollDetail']); echo GridView::widget([
                'id' => 'GridviewColl',
                'dataProvider' => $dataProviderColl,
                'toolbar'=> [
                    ['content'=>
                         \common\components\PageSize::widget(
                            [
                                'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                                'label'=>Yii::t('app', 'Showing :'),
                                'labelOptions'=>[
                                    'class'=>'col-sm-4 control-label',
                                    'style'=>[
                                        'width'=> '75px',
                                        'margin'=> '0px',
                                        'padding'=> '0px',
                                    ]

                                ],
                                'sizes'=>Yii::$app->params['pageSize'],
                                'options'=>[
                                    'id'=>'aa',
                                    'class'=>'form-control'
                                ]
                            ]
                         )

                    ],

                    //'{toggleData}',
                    '{export}',
                ],
                'filterSelector' => 'select[name="per-page"]',
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                                 //'label'=>'Nama',
                                 'format'=>'raw',
                                 'attribute'=>'NomorBarcode',
                                 'value' => function($data) use ($for){
                                     if($for=='karantina')
                                     {
                                        $url = Url::to(['viewkarantina','id'=>$data->ID,'edit'=>'t']);
                                     }else{
                                        $url = Url::to(['..\..\katalog\update','for' => 'coll','id'=>$data->ID,'edit'=>'t']);
                                     }
                                     
                                     return Html::a($data->NomorBarcode, 'javascript:void(0)', ['class'=>'editCollection','onclick'=>'js:DetailCollection('.$data->ID.')','title' => $data->NomorBarcode]); 
                                 }
                    ],
                    'NoInduk',
                    
                    [
                        'attribute'=>'Rule_id',
                        'value'=>'rule.Name',
                    ],
                    [
                        'attribute'=>'Location_id',
                        'value'=>'location.Name',
                    ],
                    [
                        'attribute'=>'Status_id',
                        'value'=>'status.Name',
                    ],
                ],
                'summary'=>'',
                'responsive'=>true,
                'containerOptions'=>['style'=>'font-size:12px'],
                'hover'=>true,
                'condensed'=>true,
            ]); Pjax::end(); ?>
            </div>
            <!-- /.box-body -->
          </div>
   </div>
   

</div>
<?php
Modal::begin(['id' => 'detail-collection-modal']);
echo "<div id='detailModalCollection'></div>";
Modal::end();
?>

<?php 

    $this->registerJs(' 

    $(document).ready(function(){
    $(\'#btnCetakkartu\').click(function(){
        var CekActionDetail = $(\'#cbCardFormat\').val();
        var ids = ['.Yii::$app->request->get('id').'];
        var arrayId = {ids} 
        var ids = jQuery.param(arrayId);
        var url =  \''.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/cetak-kartu-proses"]).'\' ;
        window.location.href = url+\'?idcardformat=\'+CekActionDetail+\'&\'+ids;

    });
    });
    function DetailCollection(id) {
         isLoading = false;
         if($.ajax({
            type     :"POST",
            cache    : false,
            url  : "'.Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/detail-collection"]).'?id="+id,
            beforeSend : function(){
              $("#detailModalCollection").html("<center>Loading form...</center>");
            },
            success  : function(response) {
                $("#detailModalCollection").html(response);
            }
        }))
        {
          $("#detail-collection-modal").modal("show");
          $("#detail-collection-modal").removeAttr("tabindex");
        }
    }
    ', \yii\web\View::POS_HEAD);

?>
