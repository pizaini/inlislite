<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\Select2;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\AjaxButton;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionloanitems $model
 */
$this->title = Yii::t('app', 'Detail Stock Opname : (' . $modelStockOpname->ProjectName.', Tahun : '. $modelStockOpname->Tahun . ')');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sirkulasi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php 

// $form 			= ActiveForm::begin(
// 				    [
// 				    	//'action'=> Url::to('simpan'),
// 				        'type'=>ActiveForm::TYPE_HORIZONTAL,
// 				        'enableClientValidation' => true,
// 				        'formConfig' => [
// 				            'labelSpan' => '3',
// 				            //'deviceSize' => ActiveForm::SIZE_TINY,
// 				        ],
// 				    ]
// 			    );

$url2           = Url::to('view-koleksi');
$ajaxOptions    = [
                    'type' => 'POST',
                    'url'  => $url2,
                    'data' => array(
                            //'NoAnggota' => $noAnggota,
                            //'memberID'  => $memberID,
                            'NomorBarcode' =>  new yii\web\JsExpression('$("#dynamicmodel-nomorbarcode").val()'),
                            'StockopnameID' => $modelStockOpname->ID,
                            'TglTransaksi' => date('Y-m-d')
                        ),
                    //'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                    //'update' => '#divAnggota',
                    //'success'=>new yii\web\JsExpression('function(data){ $("#divAnggota").html(data);'.$dataSuccess.' }'),
                   'success'=>new yii\web\JsExpression('
                   		function(data){ 
                   			// $.pjax({container: "#stockopnameGrid-pjax"});
                   			// $.pjax({container: "#summarykoleksiGrid-pjax"});
                   			// $.pjax({container: "#myGridListColl-pjax"});
                   			$.pjax.reload({container:"#stockopname-detail"});  //Reload GridView
                   			//alert(data);
                   			//$("#koleksi-item").html(data); 
                   			$("#dynamicmodel-nomorbarcode").val("");
                   			$("#dynamicmodel-nomorbarcode").focus(); 
                   			//$("#btn-simpan").prop("disabled", false);
                   			$("#parent-warning-barcode").hide();
                   		}'),
                   'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
                   					// alert(xhr.responseText); 
									var str = xhr.responseText;
									var message = str.replace("Not Found (#404):","");

                   					$("#parent-warning-barcode").show();
                   					$("#warning-scanbarcode").html(message);
                   					$("#dynamicmodel-nomorbarcode").val("");
                   					$("#dynamicmodel-nomorbarcode").focus();
                   					//$("#btn-simpan").prop("disabled", true);
                   				}'),
                ];

?>

<div class="page-header">
	<h3>
		&nbsp;
		<div class="pull-left">
			<?php
			echo '<p>';
			/*echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.Yii::t('app', 'Create') , ['class' => 'btn btn-success','id'=>'btn-simpan']);*/
			echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-ok"></span> '. Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning']);
			echo '</p>';
			?>
		</div>
	</h3>
</div>


<!-- KOLEKSI-AREA -->
<div style="background-image: linear-gradient(to bottom, #59bedc, #0978c5);
     color:#fff;padding:0px 0px 10px 20px;border-radius:3px;">
	<div class="content_edit" id="koleksi-area">
		<table border="0">
		    <tr>
		        <td valign="top" class='icon-users'>&nbsp;&nbsp;
		        	<div class="input-group">
		        		<?= Html::activeTextInput($model,'nomorBarcode',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').'  No.'.Yii::t('app', 'Barcode').'']); ?>
		        		<div class="input-group-btn" >
		        			<?php
						            echo AjaxButton::widget([
						                'label' => '<i class="glyphicon glyphicon-check"></i> ' .Yii::t('app','Ok'),
						                'ajaxOptions' => $ajaxOptions,
						                'htmlOptions' => [
						                    'class' => 'btn btn-warning',
						                    'id' => 'cari',
						                    'type' => 'submit'
						                ]
						            ]);
				            ?>    
		        			
		        		</div><!-- /btn-group -->
		        	</div>
		        	<div class="hint-block col-sm-9"></div>
		        </td>
		        <td valign="top" style="padding-top: 18px; padding-left: 10px">
		           
		        
		        <?php 
		            //echo Html::submitButton(Yii::t('app', 'Cari Item') , ['class' => 'btn btn-warning']);?>
		        </td>
		        <td width="5%">&nbsp;</td>
		        <td valign="top" class='icon-book'>&nbsp;&nbsp;<?php //CHtml::textField("TxtNoItem","",array('id'=>'TxtNoItem','placeholder'=>'Barcode Koleksi')); ?></td>
		        <td valign="top"><?//CHtml::ajaxButton("Cari Koleksi", $url2, $ajaxOptions2, $htmlButton2); ?></td>
		        <td style="display: none;">&nbsp;&nbsp;&nbsp;<a href="#" id="ShowPopUp">List Koleksi Yang Dipinjam</a></td>
		    </tr>
		</table>
	</div>
</div>
<!-- /.KOLEKSI-AREA -->


<!-- HINT - ALERT -->
<div id="parent-warning-barcode" class="callout callout-warning callout-dismissible" hidden="hidden" style="margin-bottom: 0!important;">
	<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
	<!-- <h4><i class="fa fa-info"></i> Note:</h4> -->
	<span id="warning-scanbarcode">Warning nanti disini</span>
</div>
<!-- /.HINT - ALERT -->



<!-- Daftar Koleksi Hasil Stock Opname -->
</br>
<div id="koleksi-item">
	
<?php 
	// $locations = ArrayHelper::map(\common\models\Locations::find()->all(),'ID','Name');
	// $a = [473=>'Usulan',482=>'Diterima'];
	// echo "<pre>";
	// print_r($location);
    // print_r($CountHasilStockOpname);
    Pjax::begin(['id' => 'stockopname-detail']); 
	echo GridView::widget([
        'dataProvider' => $dataProvider,
        'id'=>'stockopnameGrid',
        'pjax'=>true,
		'toolbar'=> [
            ['content'=>
                 \common\components\PageSize::widget(
                    [
                        'template'=> '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>'Tampilkan :',
                        'labelOptions'=>[
                            'class'=>'col-sm-4 control-label',
                            'style'=>[
                                'width'=> '75px',
                                'margin'=> '0px',
                                'padding'=> '0px',
                            ]

                        ],
                        'sizes'=>(Yii::$app->params['pageSize'] + array($CountHasilStockOpname => "Semua")),
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
                'attribute'=>'CreateDate',
                'header'=>'Tgl Cek',
                'format'=>[
                    'datetime',
                    (isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'
                    ]
            ],
            [
            
                'attribute'=>'collection.NomorBarcode',
                'header'=>yii::t('app','Nomor Barcode'),
            ],
            [
            	'attribute'=>'collection.NoInduk',
            	'header'=>'NoInduk'
            ],
            [
            	'attribute'=>'collection.catalog.Title',
            	'header'=>'Judul'
            ],
            [
            	'attribute'=>'collection.catalog.Author',
            	'header'=>'Pengarang'
            ],
            [
            	'attribute'=>'collection.catalog.Publisher',
            	'header'=>'Penerbit'
            ],
            [
            	'attribute'=>'PrevLocationID',
                'value'=>'prevLocation.Name',
                'label'=>Yii::t('app','Lokasi Sebelumnya')
            ],
            [
                'label'=>Yii::t('app','Lokasi Sekarang'),
                'width'=>'50%',
                'format'=>'raw',
                'value'=>function ($model, $key, $index, $widget) { 
                    $status = ArrayHelper::map(\common\models\Locations::find()->all(),'ID','Name');

                    return  
                    Select2::widget([
                        'name' => 'location_id',
                        'data'=> $status,
                        'value'=>$model->CurrentLocationID,
                        'options' => [
                        'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Kriteria'),
                            'class' => 'select',
                            'detID' => $model->ID
                        ],
                    ]);
                },
               
            ],
            
            [
                'attribute'=>'PrevStatusID',
                'value'=>'prevStatus.Name',
                // 'width'=>'90px',
                'label'=>Yii::t('app','Ketersediaan Sebelumnya')
            ],
            [
                'label'=>Yii::t('app','Ketersediaan Sekarang'),
                'width'=>'50%',
                'format'=>'raw',
                'value'=>function ($model, $key, $index, $widget) { 
                    $status = ArrayHelper::map(\common\models\Collectionstatus::find()->all(),'ID','Name');

                    return
                    Select2::widget([
                        'name' => 'status_id',
                        'data'=> $status,
                        'value'=>$model->CurrentStatusID,
                        'options' => [
                        'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Kriteria'),
                            'class' => 'select',
                            'detID' => $model->ID
                        ],
                    ]);
                },
               
            ],
            [
                'attribute'=>'PrevCollectionRuleID',
                'value'=>'prevCollectionRule.Name',
                'label'=>Yii::t('app','Akses Sblmnya')
            ],
            [
                'label'=>Yii::t('app','Akses Sekarang'),
                'width'=>'50%',
                'format'=>'raw',
                'value'=>function ($model, $key, $index, $widget) { 
                    $status = ArrayHelper::map(\common\models\collectionrules::find()->all(),'ID','Name');

                    return  
                    Select2::widget([
                        'name' => 'rule_id',
                        'data'=> $status,
                        'value'=>$model->CurrentCollectionRuleID,
                        'options' => [
                        'placeholder' => Yii::t('app','Choose').' '.Yii::t('app','Kriteria'),
                            'class' => 'select',
                            'detID' => $model->ID
                        ],
                    ]);
                },
               
            ],
            
            [
                'attribute'=>'CreateBy',
                'value'=>'createBy.username',
                'label'=>Yii::t('app','User')
            ],
           


           	[
	            'class' => 'yii\grid\ActionColumn',
				//'contentOptions'=>['style'=>'max-width: 20px;'],
	            'template' => '{delete}',
	            'buttons' => [
				'update' => function ($url, $model) {
	                                return Html::a('<span class="glyphicon glyphicon-pencil"> '.Yii::t('app', 'Edit').'</span>', Yii::$app->urlManager->createUrl(['sirkulasi/stockopname/update','id' => $model->ID,'edit'=>'t']), [
	                                                'title' => Yii::t('app', 'Edit'),
	                                                'class' => 'btn btn-primary btn-sm'
	                                              ]);},
												  
	            'delete' => function ($url, $model) {
	                                return Html::a('<span class="glyphicon glyphicon-trash">'.Yii::t('app', 'Delete').'</span>', Yii::$app->urlManager->createUrl(['sirkulasi/stockopname/delete-detail','id' => $model->ID,'edit'=>'t']), [
	                                                'title' => Yii::t('app', 'Delete'),
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
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Daftar Koleksi Hasil Stock Opname </h3>',
            'type'=>'info',
        
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), Yii::$app->request->url , ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); 
    // Pjax::end(); ?>
</div>
<!-- ,/Daftar Koleksi Hasil Stock Opname -->


<!-- summary koleksi -->
<?php echo GridView::widget([
	'dataProvider' => $dataProdiverHasilStockOpname,
 	'id'=>'summarykoleksiGrid',
    'pjax'=>true,
	'columns' => [
		/*[
			'class' => 'yii\grid\SerialColumn',
			'contentOptions'=>['class'=>'kartik-sheet-style'],
			'width'=>'36px',
			'header'=>'',
			'headerOptions'=>['class'=>'kartik-sheet-style']
		],*/
		[
			'attribute'=>'LocationName',
			'header'=>'Lokasi'
		],
		[
			'attribute'=>'JumlahStockOpname','header'=>'Jumlah Stock Opname','hAlign' => GridView::ALIGN_CENTER,
			'pageSummary'=>true,
			'footer'=>true
		],
		[
			'attribute'=>'JumlahStatusTersedia',
			'header'=>'Tercatat Tersedia',
			'pageSummary'=>true,
			'footer'=>true
		],
		[
			'attribute'=>'JumlahStatusTidakdiketahui',
			'header'=>'Tercatat Tidak diketahui',
			'pageSummary'=>true,
			'footer'=>true
		],
		[
			'attribute'=>'JumlahStatusRusak',
			'header'=>'Tercatat Rusak',
			'pageSummary'=>true,
			'footer'=>true
		],
		[
			'attribute'=>'JumlahStatusDalamPerbaikan',
			'header'=>'Tercatat Dalam Perbaikan',
			'pageSummary'=>true,
			'footer'=>true
		],
		[
			'attribute'=>'JumlahStatusDipinjam',
			'header'=>'Tercatat Dipinjam',
			'pageSummary'=>true,
			'footer'=>true
		],
		[
            'attribute'=>'JumlahStatusDalamPenggandaan',
            'header'=>'Tercatat Dalam Penggandaan',
            'pageSummary'=>true,
            'footer'=>true
        ],
        [
			'attribute'=>'JumlahStatusDihibahkan',
			'header'=>'Tercatat Dihibahkan',
			'pageSummary'=>true,
			'footer'=>true
		],
		[
			'attribute'=>'JumlahStatusDiolah',
			'header'=>'Tercatat Diolah',
			'pageSummary'=>true,
			'footer'=>true
		],
		[
			'attribute'=>'JumlahStatusHilang',
			'header'=>'Tercatat Hilang',
			'pageSummary'=>true,
			'footer'=>true
		],
		[
			'attribute'=>'JumlahStatusTandon',
			'header'=>'Tercatat Tandon',
			'pageSummary'=>true,
			'footer'=>true
		],
		[
			'attribute'=>'JumlahKoleksi',

			'pageSummary'=>true,
			'footer'=>true
		],


	],
	'responsive'=>true,
	'hover'=>true,
	'condensed'=>true,
	'showPageSummary' => true,
	//'floatHeader'=>false,
	 'panel' => [
	'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Rekap Jumlah Koleksi Hasil Stock Opname </h3>',
	'type'=>'info',

	'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), Yii::$app->request->url, ['class' => 'btn btn-info']),
	'showFooter'=>false
],
]); ?>




<div>
	<?php
     // Pjax::begin(['id' => 'myGridviewListColl']); 
     echo GridView::widget([
        'id'=>'myGridListColl',
        'pjax'=>true,
        'dataProvider' => $dataProviderUnscannedCollections,
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
                        'sizes'=>(Yii::$app->params['pageSize'] + array($dataProviderUnscannedCollections->totalCount => "Semua")),
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
                 // 'format'=>'raw',
                 'attribute'=>'NomorBarcode',
                 'label'=>yii::t('app','Nomor Barcode'),
            ],
            //'RFID',
            [
                'attribute'=>'TanggalPengadaan',
                'label'=>yii::t('app','Tanggal Pengadaan'),
                'format' => 'date',
            ],
            [
                'attribute'=>'NoInduk',
                'label'=>yii::t('app','Nomor Induk'),
            ],
            [
                'attribute'=>'DataBib',
                //'value'=>'source.Name',
                'label'=>yii::t('app','Data Bibliografis'),
                'format' => 'raw',
            ],
            [
                'attribute'=>'Media_id',
                'label'=>yii::t('app','Jenis Media'),
                'value'=>'media.Name',
            ],
            [
                'attribute'=>'Source_id',
                'label'=>yii::t('app','Jenis Sumber'),
                'value'=>'source.Name',
            ],
            [
                'attribute'=>'Category_id',
                'label'=>yii::t('app','Jenis Kategori'),
                'value'=>'category.Name',
            ],
            [
                'attribute'=>'Rule_id',
                'label'=>yii::t('app','Akses'),
                'value'=>'rule.Name',
            ],
            [
                'attribute'=>'Status_id',
                'label'=>yii::t('app','Status'),
                'value'=>'status.Name',
            ],
            [
                'attribute'=>'Location_Library_id',
                'label'=>yii::t('app','Lokasi Perpustakaan'),
                'value'=>'locationLibrary.Name',
            ],
            [
                'attribute'=>'Location_id',
                'label'=>yii::t('app','Lokasi'),
                'value'=>'location.Name',
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'ISOPAC', 
                'vAlign'=>'top',
                'label'=>yii::t('app','IsOPAC'),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                /*'contentOptions'=>['style'=>'width: 250px;'],*/
                'template' => $template,
                'buttons' => [                       
                'restore' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-retweet"></span> '.Yii::t('app', 'Restore'), Yii::$app->urlManager->createUrl(['akuisisi/koleksi/restore','id' => $model->ID,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Restore'),
                                                    //'data-toggle' => 'tooltip',
                                                    'class' => 'btn btn-success btn-sm',
                                                    'data' => [
                                                        'confirm' => Yii::t('yii','Are you sure you want to restore this item?'),
                                                        'method' => 'post',
                                                    ],
                                                  ]);},

                ],
            ],
        ],
        //'summary'=>'',
        'responsive'=>true,
        'containerOptions'=>['style'=>'font-size:12px'],
        'hover'=>true,
        'condensed'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app','Daftar Koleksi Belum Diperiksa').' </h3>',
            'type'=>'info',
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), Yii::$app->request->url, ['class' => 'btn btn-info']),

            'showFooter'=>false
        ],
    ]); 
    Pjax::end(); ?>
</div>







<?php
$HapusOk        = '$("#ErrorMsg").hide();$("#DataList").html("");$("#DataList").html(data);$("#DivKoleksi").html("");$("#TxtNoItem2").focus();$("#TxtNoItem2").val("");'; 
$HapusItemUrl      = Url::to('hapus-item');
$token          = Yii::$app->request->csrfToken;
$this->registerJs("
	
	//$('#btn-simpan').prop('disabled', true);

	$('#dynamicmodel-nomorbarcode').focus();
	
	$('#dynamicmodel-nomorbarcode').keydown(function(event){
        if(event.keyCode == 13) {
			if($('#dynamicmodel-nomorbarcode').val() == ''){
				// alert('Nomor Barcode tidak boleh kosong.');
				$('#parent-warning-barcode').show();
				$('#warning-scanbarcode').html('Nomor Barcode tidak boleh kosong.');
				$('#dynamicmodel-nomorbarcode').focus();
			}else{
				$('#cari').click();

			}
		}
	});


	// Disable Enter Submit Form
	$(document).on('keyup keypress', 'form input[type=\"text\"]', function(e) {
	  if(e.which == 13) {
	    e.preventDefault();
	    return false;
	  }
	});

    // $('.select').change(function(){
    //     console.log('data');die;
    // });


    $('.select').change(function(){
        $.ajax({
            type: 'POST',
            url : '".Yii::$app->urlManager->createUrl(["sirkulasi/stockopname/detail","id" => Yii::$app->getRequest()->getQueryParam('id')])."',
            data: {
                 value: $(this).val(), 
                 detID: $(this).attr('detID'),
                 name: $(this).attr('name')
             },
            success: function (data) {
                console.log(data);
            },
            });
            
    });
	
	
");

?>