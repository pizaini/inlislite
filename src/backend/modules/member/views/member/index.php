<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

use kartik\grid\GridView;
use kartik\widgets\Select2;
use mdm\admin\components\Helper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MemberSearch $searchModel
 */


$this->title = Yii::t('app', 'Daftar Anggota');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-index">
    <div class="page-header">
<?php url::remember()?>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success'])
        //Html::encode($this->title)  ?>
    </div>
    <?php /*Pjax::begin(['id' => 'search']);*/ ?>
    <?php echo $this->render('_search2', ['model' => $searchModel, 'rules' => $rules]); ?>
    <?php /*Pjax::end();*/ ?>
    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Members',
]), ['create'], ['class' => 'btn btn-success'])*/ ?>
    </p>


    <?php Pjax::begin(['id' => 'myGridview']);
    echo GridView::widget([
        'id' => 'myGrid',
        'dataProvider' => $dataProvider,
        'toolbar' => [
            ['content' =>
                \common\components\PageSize::widget(
                    [
                        'template' => '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label' => yii::t('app','Tampilkan :'),
                        'labelOptions' => [
                            'class' => 'col-sm-4 control-label',
                            'style' => [
                                'width' => '75px',
                                'margin' => '0px',
                                'padding' => '0px',
                            ]

                        ],
                        // gridview dengan if
                        'sizes'=>(Yii::$app->config->get('language') != 'en' ? Yii::$app->params['pageSize'] : Yii::$app->params['pageSize_ing']),
                        'options' => [
                            'id' => 'aa',
                            'class' => 'form-control'
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
                'class' => '\kartik\grid\CheckboxColumn',
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
                'label' => 'Foto',
                'format' => ['image', ['width' => '100', 'height' => '100']],
                'value' => function ($model) {
                    return $model->getImageUrl();
                },
                //'mergeHeader'=>true,
                //'contentOptions' => ['class' => 'come-class']
            ],
            [

                'attribute' => 'MemberNo',
                'visible' => \common\components\MemberHelpers::customMemberForm(1, 3)
            ],
            [
                //'label'=>'Nama',
                'format' => 'raw',
                'attribute' => 'Fullname',
                'value' => function ($data) {
                    $url = Url::to(['update', 'id' => $data->ID]);
                    return Html::a($data->Fullname, $url, ['title' => $data->Fullname, 'Onclick' => 'test()']);
                },
                'visible' => \common\components\MemberHelpers::customMemberForm(2, 3)
            ],
            //'Fullname',

            [

                'attribute' => 'PlaceOfBirth',
                'visible' => \common\components\MemberHelpers::customMemberForm(3, 3)
            ],
            [
                'attribute' => 'DateOfBirth',
                'format' => [
                    'datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'
                ],
                'visible' => \common\components\MemberHelpers::customMemberForm(4, 3)
            ],
            [

                'attribute' => 'Address',
                'visible' => \common\components\MemberHelpers::customMemberForm(5, 3)
            ],
            
            [

                'attribute' => 'Province',
                'visible' => \common\components\MemberHelpers::customMemberForm(7, 3)
            ],
            [

                'attribute' => 'City',
                'visible' => \common\components\MemberHelpers::customMemberForm(6, 3)
            ],
            [

                'attribute' => 'AddressNow',
                'visible' => \common\components\MemberHelpers::customMemberForm(8, 3)
            ],
            [

                'attribute' => 'ProvinceNow',
                'visible' => \common\components\MemberHelpers::customMemberForm(10, 3)
            ],
            [

                'attribute' => 'CityNow',
                'visible' => \common\components\MemberHelpers::customMemberForm(9, 3)
            ],
            [

                'attribute' => 'NoHp',
                'visible' => \common\components\MemberHelpers::customMemberForm(11, 3)
            ],
            [

                'attribute' => 'Phone',
                'visible' => \common\components\MemberHelpers::customMemberForm(12, 3)
            ],
            [
                'attribute' => 'TahunAjaran',
                'label' => yii::t('app','Tahun Ajaran'),
                //'value' => 'identityType.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(34, 3)
            ],
           
            [
                'attribute' => 'Agama_id',
                'value' => 'agama.Name',
                'visible' => \common\components\MemberHelpers::customMemberForm(17, 3)
            ],
            [
                'attribute' => 'Kelas_id',
                'label' => yii::t('app','Kelas'),
                'value' => 'kelas.namakelassiswa',
                'visible' => \common\components\MemberHelpers::customMemberForm(35, 3)
            ],
            [
                'attribute' => 'UnitKerja_id',
                'label' => yii::t('app','Unit Kerja'),
                'value' => 'departments.Name',
                'visible' => \common\components\MemberHelpers::customMemberForm(36, 3)
            ],
            [
                'attribute' => 'Jurusan_id',
                'value' => 'jurusan.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(38, 3)
            ],
             [
                'attribute' => 'ProgramStudi_id',
                'label' => yii::t('app','Program Studi'),
                'value' => 'programStudi.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(48, 3),
            ],
            [
                'attribute' => 'Fakultas_id',
                'value' => 'fakultas.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(37, 3)
            ],
            [

                'attribute' => 'InstitutionName',
                'visible' => \common\components\MemberHelpers::customMemberForm(26, 3)
            ],
            [

                'attribute' => 'InstitutionAddress',
                'visible' => \common\components\MemberHelpers::customMemberForm(27, 3)
            ],
            [

                'attribute' => 'InstitutionPhone',
                'visible' => \common\components\MemberHelpers::customMemberForm(28, 3)
            ],


            [
                'attribute' => 'JenisIdentitas',
                'label' => yii::t('app','Jenis Identitas'),
                'value' => 'identityType.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(13, 3)
            ],
            [
                'attribute' => 'IdentityNo',
                'label' => yii::t('app','Nomor Identitas'),
                'visible' => \common\components\MemberHelpers::customMemberForm(14, 3)
            ],
            [
                'attribute' => 'EducationLevel_id',
                'value'=>'educationLevel.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(19, 3)
            ],
            [
                'attribute' => 'sex',
                'label' => yii::t('app','Jenis Kelamin'),
                'value' => 'sex.Name',
                'visible' => \common\components\MemberHelpers::customMemberForm(15, 3)
            ],
            [
                'attribute' => 'MaritalStatus_id',
                'value'=>'maritalStatus.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(20, 3)
            ],
            [
                'attribute' => 'Job_id',
                'label' => yii::t('app','Pekerjaan'),
                'value'=>'job.Pekerjaan',
                'visible' => \common\components\MemberHelpers::customMemberForm(16, 3)
            ],
            [
                'attribute' => 'RegisterDate',
                'format' => [
                    'datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'
                ],
                'visible' => \common\components\MemberHelpers::customMemberForm(21, 3)
            ],
            [
                'attribute' => 'EndDate',
                'label' => yii::t('app','Masa Berlaku'),
                'format' => [
                    'datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'
                ],
                'visible' => \common\components\MemberHelpers::customMemberForm(22, 3)
            ],
            [
                'attribute' => 'MotherMaidenName',
                'label' => yii::t('app','Nama Ibu'),
                'visible' => \common\components\MemberHelpers::customMemberForm(25, 3)
            ],
            [
                'attribute' => 'Email',
                'visible' => \common\components\MemberHelpers::customMemberForm(29, 3)
            ],
            [
                'attribute' => 'JenisPermohonan_id',
                'label' => yii::t('app','Status Anggota'),
                'value' => 'jenisPermohonan.Name',
                'visible' => \common\components\MemberHelpers::customMemberForm(23, 3)
            ],
            [
                'attribute' => 'JenisAnggota',
                'value' => 'jenisAnggota.jenisanggota',
                'label' => yii::t('app','Jenis Anggota'),
                'visible' => \common\components\MemberHelpers::customMemberForm(18, 3)
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data->StatusAnggota_id == 3) {
                        return '<span class="label label-primary">' . $data->statusAnggota->Nama . '</span>';
                    } else {
                        return '<span class="label label-warning">' . $data->statusAnggota->Nama . '</span>';
                    }


                },
                'visible' => \common\components\MemberHelpers::customMemberForm(24, 3)
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 90px;'],
                'template' => Helper::filterActionColumn('{delete}'),
                //'template' => '{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"> ' . Yii::t('app', 'Edit') . '</span>', Yii::$app->urlManager->createUrl(['member/member/update', 'id' => $model->ID, 'edit' => 't']), [
                            'title' => Yii::t('app', 'Edit'),
                            'data-toggle' => 'tooltip',
                            'class' => 'btn btn-primary btn-sm'
                        ]);
                    },

                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"> ' . Yii::t('app', 'Delete') . '</span>', Yii::$app->urlManager->createUrl(['member/member/delete', 'id' => $model->ID, 'edit' => 't']), [
                            'title' => Yii::t('app', 'Delete'),
                            'data-toggle' => 'tooltip',
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },

                ],
            ],
        ],


        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => false,

        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' => '

<div class="form-group" style="padding-bottom:30px">
  <label for="cbAction" class="col-md-1 control-label control-label-sm" style="margin-right: -46px;">' . Yii::t('app', 'Action') . ' : </label>
  <div class="col-md-2">' . Select2::widget([
                    'id' => 'cbAction',
                    'name' => 'cbAction',
                    'data' => [
                        'aktivasi' => yii::t('app','Aktivasi'),
                        'extend_member' => yii::t('app','Perpanjangan Masa Berlaku Anggota'),
                        'cetak' => yii::t('app','Cetak kartu anggota'),
                        'cetak_kartu_blakang' => yii::t('app','Cetak kartu belakang'),
                        //'cetak-bebas-pustaka' => 'Cetak bebas pustaka',
                        'keranjang-anggota' => yii::t('app','Masukan ke keranjang anggota'),
                        'delete-bulk' => yii::t('app','Hapus data'),

                    ],
                    'size' => 'sm',
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
   <div id="actionDropdown" class="col-md-3" style="display: none; margin-left: -18px;">' . Select2::widget([
                    'id' => 'cbActionDetail',
                    'name' => 'cbActionDetail',
                    'data' => [
                        'model1' => 'Cetak kartu anggota terpilih (satuan)',
                        //'cetak1'=>'Standar Barcode Kartu Anggota Jateng',
                        //'delete-bulk1'=>'Cetak kartu anggota terpilih (lembar A4)',
                        'model2' => 'Standar A4 Kartu Anggota',
                    ],
                    'size' => 'sm',
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

                ]) . '</div>
   <div class="col-md-2" style="margin-left: -21px;">' .
                Html::submitButton('<i class="glyphicon glyphicon-check"></i> '.yii::t('app','Proses'), [
                    'id' => 'btnCheckprocess',
                    'class' => 'btn btn-primary btn-sm ',
                    'title' => 'Proses',
                    'data-toggle' => 'tooltip'
                ])

                . '</div>
</div>'
            ,
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]); ?>

</div>

<?php

$this->registerJs('

    $(document).ready(function(){
    $(\'#btnCheckprocess\').click(function(){
        var CekAction = $(\'#cbAction\').val();
        var CekActionDetail = $(\'#cbActionDetail\').val();
        var CekActionDetail2 = $(\'#cbActionBebasPustaka\').val();
        var CekId = $(\'#myGrid\').yiiGridView(\'getSelectedRows\');
        var status = true;
        if (CekAction === \'delete-bulk\')
        {
            status = false;
            swal({   
                title:" ",   
                text: "'.yii::t('app','Apakah anda yakin akan menghapus data yang dipilih?').'",   
                type: "warning",                
                showCancelButton: true,
                confirmButtonText: "'.yii::t('app','Ya').'",
                cancelButtonText: "'.yii::t('app','Batal').'",
                confirmButtonColor: "#DD6B55",
                  
                closeOnCancel: true,
                showLoaderOnConfirm: true, 
            }, 
            function(isConfirm){      
               if (isConfirm) {
                    status = true;
                    $.ajax({
                        type: \'POST\',
                        url : "' . Yii::$app->urlManager->createUrl(["member/member/checkbox-process"]) . '",
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

        if (CekAction === \'extend_member\')
        {
            $.ajax({
                type: \'POST\',
                url : "' . Yii::$app->urlManager->createUrl(["member/perpanjangan-expired/extend"]) . '",
                data : {row_id: CekId, action: CekAction, actionid : CekActionDetail},
                success : function(response) {
                    // console.log(response)
                    swal({   
                        title: "",  
                        type: "success", 
                        text: response,  
                     });  
                    $(\'#checkError\').html(response);
                    $.pjax.reload({container:"#myGridview"}); 
                }
                ,
                error:function(xhr, ajaxOptions, thrownError){ 
                    var str = xhr.responseText;
                    swal({   
                        title: "",  
                        type: "error", 
                        text: str.replace("Not Found (#404): ",""),  
                     });  
                   
                }
            });
            
            return true;
        }

        if (CekAction === \'cetak_kartu_blakang\')
        {
            $.ajax({
                type: \'POST\',
                url : "' . Yii::$app->urlManager->createUrl(["member/member/checkbox-process"]) . '",
                data : {action: CekAction},
                
            });
            // alertSwal("Berhasil Cetak Kartu");

            return true;
        }


        if (CekAction === \'cetak-bebas-pustaka\')
        {
            if(CekId.length >= 1){
                $.ajax({
                    type: \'POST\',
                    url : "' . Yii::$app->urlManager->createUrl(["member/member/checkbox-process"]) . '",
                    data : {row_id: CekId, action: CekAction, actionid : CekActionDetail,actionid2 : CekActionDetail2},

                });

            }else{
                 alertSwal("'. yii::t('app','Harap pilih anggota.').'","error","2000");
            }

                return true;
        }

        if(status){
            $.ajax({
                type: \'POST\',
                url : "' . Yii::$app->urlManager->createUrl(["member/member/checkbox-process"]) . '",
                data : {row_id: CekId, action: CekAction, actionid : CekActionDetail,actionid2 : CekActionDetail2},
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

$this->registerJs(
    '$("document").ready(function(){
        $("#search").on("pjax:end", function() {
            $.pjax.reload({container:"#myGridview"});  //Reload GridView
        });
    });'
);






?>
 