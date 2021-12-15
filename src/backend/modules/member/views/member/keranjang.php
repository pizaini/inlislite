<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

use kartik\grid\GridView;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MemberSearch $searchModel
 */

$this->title = Yii::t('app', 'Daftar Keranjang Anggota');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-index">
    <div class="page-header">
        
    <?= Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success'])
//Html::encode($this->title) ?>
    </div>
    <?php  echo $this->render('_search2', ['model' => $searchModel,'rules' => $rules]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Members',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>


    <?php Pjax::begin(['id' => 'myGridview']); echo GridView::widget([
        'id'=>'myGrid',
        'pjax'=>true,
        'dataProvider' => $dataProvider,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>yii::t('app','Tampilkan :'),
                        'labelOptions'=>[
                            'class'=>'col-sm-4 control-label',
                            'style'=>[
                                'width'=> '75px',
                                'margin'=> '0px',
                                'padding'=> '0px',
                            ]

                        ],
                        // gridview dengan if
                        'sizes'=>(Yii::$app->config->get('language') != 'en' ? Yii::$app->params['pageSize'] : Yii::$app->params['pageSize_ing']),
                        'options'=>[
                            'id'=>'aa',
                            'class'=>'form-control'
                        ]
                    ]
                 )

            ],

            //'{toggleData}',
            //'{export}',
        ],
        'pager' => [
            'firstPageLabel' => Yii::t('app','Awal'),
            'lastPageLabel'  => Yii::t('app','Akhir')
        ],
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
 'columns' => [
            [
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    return [
                        'value' => $searchModel->ID
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [ 
                'label'=>'Foto',
                'format' =>  ['image',['width'=>'100','height'=>'100']],
                'value' => function ($model) {
                    return $model->getImageUrl(); 
                },
                //'mergeHeader'=>true,
                //'contentOptions' => ['class' => 'come-class']
            ],
            
            'MemberNo',
            [
                         //'label'=>'Nama',
                         'format'=>'raw',
                         'attribute'=>'Fullname',
                         'value' => function($data){
                             $url = Url::to(['update','id'=>$data->ID]);
                             return Html::a($data->Fullname, $url, ['title' => $data->Fullname]); 
                         }
            ],
            //'Fullname',
            'PlaceOfBirth',
            ['attribute'=>'DateOfBirth',
                'format'=>[
                        'datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
            'Address',
            'NoHp',
            [
                'attribute'=>'JenisIdentitas',
                'label'=>yii::t('app','Jenis Identitas'),
                'value'=>'identityType.Nama'
            ],
            [
                'attribute'=>'sex',
                'label'=>yii::t('app','Jenis Kelamin'),
                'value'=>'sex.Name'
            ],
           
            [
                'attribute'=>'JenisAnggota',
                'label'=>yii::t('app','Jenis Anggota'),
                'value'=>'jenisAnggota.jenisanggota',
            ],

            [
                'attribute'=>'status',
                'format' => 'raw',
                'value'=>function($data){
                            if($data->StatusAnggota_id == 3){
                                 return '<span class="label label-primary">'.$data->statusAnggota->Nama.'</span>'; 
                            }else{
                                 return '<span class="label label-warning">'.$data->statusAnggota->Nama.'</span>'; 
                            }
                            
                            
                         }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 90px;'],
				
                'template' => '{delete}',
                'buttons' => [
				'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"> '.Yii::t('app', 'Edit').'</span>', Yii::$app->urlManager->createUrl(['member/member/update','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                    'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-primary btn-sm'
                                                  ]);},

                'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash"> '.Yii::t('app', 'Delete').'</span>', Yii::$app->urlManager->createUrl(['member/member/delete','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Delete'),
                                                    'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},

                ],
            ],
        ],
       

        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>'

<div class="form-group" style="padding-bottom:30px">
  <label for="cbAction" class="col-md-1 control-label control-label-sm" style="margin-right: -46px;">'.Yii::t('app','Action').' : </label>
  <div class="col-md-2">' . Select2::widget([
    'id' => 'cbAction',
    'name' => 'cbAction',
    'data' => [
            'aktivasi'=>yii::t('app','Aktivasi'),
            'cetak'=>yii::t('app','Cetak kartu anggota'),
            'cetak-bebas-pustaka'=>yii::t('app','Cetak bebas pustaka'),
            //'keranjang-anggota'=>'Masukan ke keranjang anggota',
            'delete-bulk-keranjang'=>yii::t('app','Hapus dari keranjang anggota'),
            
        ],
    'size'=>'sm',
    'pluginEvents' => [
        "select2:select" => 'function() { 
            var id = $("#cbAction").val();
             if(id == "cetak"){
                $("#actionDropdown").show();
                $("#actionDropdownPustaka").hide();
            }else if(id == "cetak-bebas-pustaka"){
                $("#actionDropdownPustaka").show();
                $("#actionDropdown").hide();
            }else
            {
                 $("#actionDropdown").hide();
                 $("#actionDropdownPustaka").hide();
            }
        }',
    ]
    
]) . '</div>
   <div id="actionDropdown" class="col-md-3" style="display: none; margin-left: -18px;">'. Select2::widget([
    'id' => 'cbActionDetail',
    'name' => 'cbActionDetail',
    'data' => [
            'model1'=>'Cetak kartu anggota terpilih (satuan)',
            //'cetak1'=>'Standar Barcode Kartu Anggota Jateng',
            //'delete-bulk1'=>'Cetak kartu anggota terpilih (lembar A4)',
            'model2'=>'Standar A4 Kartu Anggota',
        ],
    'size'=>'sm',
    'pluginEvents' => [
        "select2:select" => 'function() { 
            var id = $("#cbAction").val();
            if(id == "cetak"){
                $("#actionDropdown").show();
            }else
            {
                 $("#actionDropdown").hide();
            }
        }',
    ]
    
]) .'</div> <div id="actionDropdownPustaka" class="col-md-3" style="display: none; margin-left: -18px;">'. Select2::widget([
    'id' => 'cbActionBebasPustaka',
    'name' => 'cbActionBebasPustaka',
    'data' => [
            'model1'=>'Model 1',
            'model2'=>'Model 2',
        ],
    'size'=>'sm',
    
]) .'</div>
   <div class="col-md-4" style="margin-left: -21px;">'.
     Html::submitButton('<i class="glyphicon glyphicon-check"></i> '.yii::t('app','Proses'), [
                        'id'=>'btnCheckprocess',
                        'class' => 'btn btn-primary btn-sm ', 
                        'title' => 'Proses', 
                        'data-toggle' => 'tooltip'
                    ]).' '.

 Html::submitButton('<i class="glyphicon glyphicon-check"></i> '.yii::t('app','Kosongkan keranjang anggota'), [
                        'id'=>'btnDeleteAllKeranjang',
                        'class' => 'btn btn-danger btn-sm ', 
                        'title' => yii::t('app','Klik untuk kosongkan keranjang anggota'), 
                        'data-toggle' => 'tooltip'
                    ])   
    .'</div>
</div>'
            ,
    'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]);  ?>

</div>

<?php 

    $this->registerJs(' 

    $(document).ready(function(){
    $(\'#btnDeleteAllKeranjang\').click(function(){
        /*var CekId = $(\'#myGrid\').yiiGridView(\'getSelectedRows\');
        if(CekId == 0){
             swal({   
                    title: "",  
                    type: "error", 
                    text: "Harap pilih anggota.",  
                });  
            return false;
        }*/
        status = false;
            swal({   
                title:" ",   
                text: "Apakah anda yakin data akan dihapus semua?",   
                type: "warning",                
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Batal",
                confirmButtonColor: "#DD6B55",
                  
                closeOnCancel: true,
                showLoaderOnConfirm: true, 
            }, 
            function(isConfirm){      
               if (isConfirm) {
                    status = true;
                    $.ajax({
                        type: \'POST\',
                        url : "'.Yii::$app->urlManager->createUrl(["member/member/checkbox-process"]).'",
                        data : {row_id: 0,action: "delete-all-keranjang"},
                        success : function(response) {
                          swal({   
                                title: "",  
                                type: "success", 
                                text: response,  
                                //imageUrl: "images/thumbs-up.jpg" 
                             });  
                          $(\'#checkError\').html(response);
                          $.pjax.reload({container:"#myGridview"});  //Reload GridView
                        },
                        error:function(xhr, ajaxOptions, thrownError){ 
                            var str = xhr.responseText;
                                                //alert(xhr.responseText); 
                                                swal({   
                                                    title: "",  
                                                    type: "error", 
                                                    text: str.replace("Not Found (#404): ",""),  
                                                    //imageUrl: "images/thumbs-up.jpg" 
                                                 });  
                                               
                                            }
                    });
                    return true;  
                } else {     
                     status = false;
                    return false;  
                }  
            });
    });


    $(\'#btnCheckprocess\').click(function(){
        var CekAction = $(\'#cbAction\').val();
        var CekActionDetail = $(\'#cbActionDetail\').val();
        var CekId = $(\'#myGrid\').yiiGridView(\'getSelectedRows\');
        var status = true;
        if (CekAction === \'delete-bulk\')
        {
            status = false;
            swal({   
                title:" ",   
                text: "Apakah anda yakin akan menghapus data yang dipilih?",   
                type: "warning",                
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Batal",
                confirmButtonColor: "#DD6B55",
                  
                closeOnCancel: true,
                showLoaderOnConfirm: true, 
            }, 
            function(isConfirm){      
               if (isConfirm) {
                    status = true;
                    $.ajax({
                        type: \'POST\',
                        url : "'.Yii::$app->urlManager->createUrl(["member/member/checkbox-process"]).'",
                        data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                        success : function(response) {
                          swal({   
                                title: "",  
                                type: "success", 
                                text: response,  
                                //imageUrl: "images/thumbs-up.jpg" 
                             });  
                          $(\'#checkError\').html(response);
                          $.pjax.reload({container:"#myGridview"});  //Reload GridView
                        },
                        error:function(xhr, ajaxOptions, thrownError){ 
                            var str = xhr.responseText;
                                                //alert(xhr.responseText); 
                                                swal({   
                                                    title: "",  
                                                    type: "error", 
                                                    text: str.replace("Not Found (#404): ",""),  
                                                    //imageUrl: "images/thumbs-up.jpg" 
                                                 });  
                                               
                                            }
                    });
                    return true;  
                } else {     
                     status = false;
                    return false;  
                }  
            });
          
            
        }
        if (CekAction === \'cetak\')
        {
            $.ajax({
                type: \'POST\',
                url : "' . Yii::$app->urlManager->createUrl(["member/member/checkbox-process"]) . '",
                data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                
            });
            
            return true;
        }

        if(status){
            $.ajax({
                type: \'POST\',
                url : "'.Yii::$app->urlManager->createUrl(["member/member/checkbox-process"]).'",
                data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                success : function(response) {
                  swal({   
                        title: "",  
                        type: "success", 
                        text: response,  
                        //imageUrl: "images/thumbs-up.jpg" 
                     });  
                  $(\'#checkError\').html(response);
                  $.pjax.reload({container:"#myGridview"});  //Reload GridView
                },
                error:function(xhr, ajaxOptions, thrownError){ 
                    var str = xhr.responseText;
                                        //alert(xhr.responseText); 
                                        swal({   
                                            title: "",  
                                            type: "error", 
                                            text: str.replace("Not Found (#404): ",""),  
                                            //imageUrl: "images/thumbs-up.jpg" 
                                         });  
                                       
                                    }
            });
        }

    });
    });', \yii\web\View::POS_READY);

Pjax::end();
?>
 