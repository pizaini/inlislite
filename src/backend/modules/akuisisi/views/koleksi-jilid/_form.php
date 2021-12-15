

<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use common\models\Locations;



/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<style type="text/css">
.modal .modal-dialog { width: 95%; }
.modal .modal-body {
    max-height: 550px;
    overflow-y: auto;
}
</style>
<div class="page-header">
    <h3>
        <?php
            echo '<p>';
            echo  Html::button(Yii::t('app', 'Save'), [
                        'id'=>'btnSave',
                        'class' => 'btn btn-success btn-sm', 
                        'title' => Yii::t('app', 'Save'), 
                        //'data-toggle' => 'tooltip'
                    ]);
            echo  '&nbsp;' .Html::a(Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning btn-sm']);
            //echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</p>';
            echo '</p>';
        ?>
    </h3>
</div>
<div id="jilid-collections-create" class="jilid-collections-create">

<div id="infoMessage"></div>
  <!-- form start -->
  <form role="form">
    <div class="box-body">

      <div class="form-group">
        <div class="row">
          <label class="control-label col-sm-2" for="email"><?php echo Html::label(yii::t('app','Tahun Jilid')); ?></label>
          <div class="col-sm-4">
            <?php echo Html::textInput('txtTahunJilid',date( 'Y'),['readonly'=>'true','class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Tahun Jilid').'...']); ?>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="row">
          <label class="control-label col-sm-2" for="email"><?php echo Html::label(yii::t('app','Nomor Panggil Jilid')); ?></label>
          <div class="col-sm-4">
            <?php echo Html::textInput('txtNoPanggilJilid',NULL,['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Nomor Panggil Jilid').'...']); ?>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="row">
          <label class="control-label col-sm-2" for="email"><?php echo Html::label(yii::t('app','Lokasi')); ?></label>
          <div class="col-sm-4">
            <?php 
                  echo Select2::widget([
                    'id' => 'cbLokasi',
                    'name' => 'cbLokasi',
                    'data'=>ArrayHelper::map(Locations::find()->all(),'ID','Name'),
                                      'pluginOptions' => [
                                          'allowClear' => true,
                                      ],
                    'options'=> ['placeholder'=>Yii::t('app', 'Choose').' '.Yii::t('app', 'coll_Location ID')]
                    ]);?>
          </div>
        </div>
      </div>
    </div>
  </form>
<div class="row">
<div class="col-sm-12">
<?php
echo Html::a(Yii::t('app', 'Tambah Koleksi'), 'javascript:void(0)', ['id'=>'btnAddKoleksi','class' => 'btn btn-primary btn-sm pull-left','data-toggle' => 'tooltip']);
?>
</div>
</div>
    <?php Pjax::begin(['id' => 'myGridview']); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'ID',
            [
                'attribute'=>'NomorBarcode',
                'label'=> yii::t('app','Nomor Barcode'),
            ],
            [
                'attribute'=>'NoInduk',
                'label'=> yii::t('app','Nomor Induk'),
            ],
            [
                'attribute'=>'DataBib',
                //'value'=>'source.Name',
                'format' => 'raw',
                'width' => '40%'
            ],
            [
                'attribute'=>'EDISISERIAL',
                'label'=> yii::t('app','Edisi Serial'),
            ],
            [
                'attribute'=>'TANGGAL_TERBIT_EDISI_SERIAL',
                'label'=> yii::t('app','Tanggal Terbit Edisi Serial'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 50px;'],
                'template' => '<div class="btn-group-vertical">{delete}</div>',
                'buttons' => [
                  'delete' => function ($url, $model) {
                                      return Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), false, [
                                                      'class'=>'ajaxDelete btn btn-danger btn-sm',
                                                      'delete-url'=>Yii::$app->urlManager->createUrl(['akuisisi/koleksi-jilid/delete-serial-collection','id' => $model['ID']]), 
                                                      'pjax-container'=>'myGridview',
                                                      'title' => Yii::t('app', 'Delete'),
                                                      //'data-toggle' => 'tooltip',
                                                      /*'data' => [
                                                          'confirm' => Yii::t('yii','Are you sure you want to delete this item?')
                                                      ],*/
                                                    ]);},

                ],
            ],
        ],
        'summary'=>'',
        'containerOptions'=>['style'=>'font-size:12px'],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        /*'panel' => [
            //'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> Daftar koleksi yang dijilid</h3>',
            //'type'=>'info',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Tambah Koleksi'), 'javascript:void(0)', ['id'=>'btnAddKoleksi','class' => 'btn btn-success','data-toggle' => 'tooltip']),
            'heading'=>false,
            'footer'=>false
        ],*/
]); Pjax::end(); ?>

</div>



<?php
Modal::begin(['id' => 'add-koleksi-modal']);

echo "<div id='modalKoleksi'></div>";

Modal::end();
?>



</div>


<?php 

$this->registerJs("

    $('#btnAddKoleksi').click(function(e) {
        if($.ajax({
            type     :'POST',
            cache    : false,
            url  : '".Yii::$app->urlManager->createUrl(["akuisisi/koleksi-jilid/show-serial-collection"])."',
            success  : function(response) {
                $('#modalKoleksi').html(response);
            }
        }))
        {
          $('#add-koleksi-modal').modal('show');
        }
    });

    $('#btnSave').click(function(){
        $.ajax({
            type: 'POST',
            url : '".Yii::$app->urlManager->createUrl(["akuisisi/koleksi-jilid/save"])."',
            data: $('#jilid-collections-create :input').serialize(),
            success : function(response) {
               $('#infoMessage').html(response);
            }
        });

    });

    $(document).on('ready pjax:success', function () {
      $('.ajaxDelete').on('click', function (e) {
        e.preventDefault();
        var deleteUrl     = $(this).attr('delete-url');
        var pjaxContainer = $(this).attr('pjax-container');
        $.ajax({
          url:   deleteUrl,
          type:  'post',
          error: function (xhr, status, error) {
            alert('There was an error with your request.' 
                  + xhr.responseText);
          }
        }).done(function (data) {
          $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
        });
      });
    });

    ");
?>
