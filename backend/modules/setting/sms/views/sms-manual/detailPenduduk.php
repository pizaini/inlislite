<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use mdm\admin\components\Helper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MasterKependudukanSearch $searchModel
 */

?>

<?php  //echo $this->render('_searchPenduduk', ['model' => $searchModel,'rules' => $rules]); ?>


<div class="table-responsive">

<?php echo $this->render('_searchModalAdvanced', ['model' => $searchModel,'rules' => $rules]); ?>
    <!-- <div class="col-md-1"> -->
        
        <?php 
            echo Html::button('<i class="glyphicon glyphicon-check"></i> '.yii::t('app','Proses Data Terpilih'), [
                'id'=>'btnCheckprocess',
                'class' => 'btn btn-primary btn-sm', 
                'title' => yii::t('app','Proses Data Terpilih'), 
                //'data-toggle' => 'tooltip'

            ]);

            // echo Html::button('<i class="glyphicon glyphicon-check"></i> Pilih Semua Data', [
            //     'type'=>'submit',
            //     'id'=>'btnDownloadall',
            //     'class' => 'btn btn-success btn-sm', 
            //     'title' => 'Pilih Semua Data', 
            //     // 'style' => 'display : none'
            // ]);
        ?>
        <!-- <br> -->
    
        <?php Pjax::begin(['id' => 'myGridview']);
    echo GridView::widget([
        'id'=>'myGrid',
        'pjax'=>true,
        'pjaxSettings' => [
            'options' => [
                'enablePushState' => false,
            ],
        ],
        'dataProvider' => $dataProvider,
        'toolbar' => [
            ['content' =>
                \common\components\PageSize::widget(
                    [
                        'template' => '{label} <div class="col-sm-8" style="width:175px">{list}</div>',
                        'label'=>Yii::t('app', 'Tampilkan :'),
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
        'filterSelector' => 'select[name="per-page"]',
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class'       => '\kartik\grid\CheckboxColumn',
                'pageSummary' => true,
                'rowSelectedClass' => GridView::TYPE_INFO,
                'name' => 'cek',
                'checkboxOptions' => function ($url, $model)  {
                    return [
                        'value' => $model->ID
                    ];
                },
                'vAlign' => GridView::ALIGN_TOP,
                // 'hidden'=> ($for=='karantina') ? true : false
                'hidden'=> false
            ],
            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'contentOptions'=>['style'=>'width: 50px;'],
            //     'template' => '<span style="display:inline">{choose}</span>',
            //     'buttons' => [
            //         'choose' => function ($url, $model)  {
            //         $id= $model->ID;

            //         return Html::a('<span class="glyphicon glyphicon-check"></span> '.Yii::t('app','Choose'), '#', [
            //                       'title' => Yii::t('app', 'Choose'), 
            //                       //'data-toggle' => 'tooltip',
            //                       'data-dismiss'=>'modal',
            //                       'class' => 'btn btn-primary btn-sm',
            //                       'onclick'=>"
            //                         $.ajax({
            //                             type     :'POST',
            //                             cache    : false,
            //                             url  : 'bind-penduduk?id=".$id."',
            //                             success  : function(response) {
            //                                 var data =  $.parseJSON(response);
                                            
            //                                 $('#members-identityno').val(data.nik);
            //                                 $('#dynamicmodel-fullname').val(data.Fullname);                                  
            //                                 $('#outbox-destinationnumber').val(data.NoHp);
            //                             }
            //                         });return false;",
            //                     ]);},

            //     ],
            // ],
            ['class' => 'yii\grid\SerialColumn'],
            [

                'attribute' => 'MemberNo',
                'visible' => \common\components\MemberHelpers::customMemberForm(1, 3)
            ],
            [
                //'label'=>'Nama',
                'format' => 'raw',
                'attribute' => 'Fullname',
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
                'value' => 'kelas.namakelassiswa',
                'visible' => \common\components\MemberHelpers::customMemberForm(35, 3)
            ],
            [
                'attribute' => 'Jurusan_id',
                'value' => 'jurusan.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(38, 3)
            ],
             [
                'attribute' => 'ProgramStudi_id',
                'value' => 'programStudi.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(48, 3),
                'label'=>'Program Studi'
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
                'value' => 'identityType.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(13, 3)
            ],
            [
                'attribute' => 'IdentityNo',
                'visible' => \common\components\MemberHelpers::customMemberForm(14, 3)
            ],
            [
                'attribute' => 'EducationLevel_id',
                'value'=>'educationLevel.Nama',
                'visible' => \common\components\MemberHelpers::customMemberForm(19, 3)
            ],
            [
                'attribute' => 'sex',
                'label' => 'P/W',
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
                'format' => [
                    'datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y'
                ],
                'visible' => \common\components\MemberHelpers::customMemberForm(22, 3)
            ],
            [
                'attribute' => 'Email',
                'visible' => \common\components\MemberHelpers::customMemberForm(29, 3)
            ],
            [
                'attribute' => 'JenisPermohonan_id',
                'value' => 'jenisPermohonan.Name',
                'visible' => \common\components\MemberHelpers::customMemberForm(23, 3)
            ],
            [
                'attribute' => 'JenisAnggota',
                'value' => 'jenisAnggota.jenisanggota',
                'label' => 'Jenis Anggota',
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
        ],


        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ', ['create'], ['class' => 'btn btn-success','title' => Yii::t('app','Add'),'data-toggle' => 'tooltip',]),
            /*'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info']),*/
            'showFooter'=>false
        ],
    ]);  Pjax::end(); ?>

</div>

<?php 

    $this->registerJs(' 
    $(document).ready(function(){
        $(\'#btnCheckprocess\').click(function(){
            var ids = $(\'#myGrid\').yiiGridView(\'getSelectedRows\');
            var arrayId = {ids} 
            var ids = jQuery.param(arrayId);
            
            // alert(arrayId)
            $.ajax({
                type     :\'POST\',
                cache    : false,
                url  : \'pilih\',
                data : {id: ids},
                success  : function(response) {
                    var data =  $.parseJSON(response);
                    // console.log(data)
                    // $(\'#members-identityno\').val(data.nik);
                    $(\'#dynamicmodel-fullname\').val(data.nama);                                  
                    $(\'#outbox-destinationnumber\').val(data.hp);
                    $(\'#pilihsalin-modal\').modal(\'hide\');
                }
            });
        });
    });
    
    ', \yii\web\View::POS_READY);
?>

