

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
        <?php
            echo '<p>';
            echo Html::button(Yii::t('app', 'Save'), [
                        'id'=>'btnUpdate',
                        'class' => 'btn btn-success btn-sm', 
                        'title' => Yii::t('app', 'Save'), 
                        //'data-toggle' => 'tooltip'
                    ]);
            echo  '&nbsp;' .Html::button(Yii::t('app', 'Delete Jilid'), [
                        'id'=>'btnDelete',
                        'class' => 'btn btn-danger btn-sm', 
                        'title' => Yii::t('app', 'Delete'), 
                    ]);
            echo  '&nbsp;' .Html::a(Yii::t('app', 'Selesai'), ['index'], ['class' => 'btn btn-warning btn-sm']);
            echo '</p>';
        ?>
</div>
<div id="jilid-collections-create" class="jilid-collections-create">

<div id="infoMessage"></div>
  <!-- form start -->
  <form role="form">
    <div class="box-body">
      
      <div class="form-group">
        <div class="row">
          <label class="control-label col-sm-2" for="email"><?php echo Html::label('ID Jilid'); ?></label>
          <div class="col-sm-4">
            <b><?=$model->IDJILID?></b>
          </div>
        </div>
      </div>


      <div class="form-group">
        <div class="row">
          <label class="control-label col-sm-2" for="email"><?php echo Html::label('No. Panggil Jilid'); ?></label>
          <div class="col-sm-4">
            <?php echo Html::textInput('txtNoPanggilJilid',$model->NOMORPANGGILJILID,['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'No. Panggil Jilid').'...']); ?>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="row">
          <label class="control-label col-sm-2" for="email"><?php echo Html::label('Lokasi'); ?></label>
          <div class="col-sm-4">
            <?php 
                  echo Select2::widget([
                    'model' => $model,
                    'attribute' => 'Location_id',
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
            'NomorBarcode', 
            'NoInduk', 
            [
                'attribute'=>'DataBib',
                //'value'=>'source.Name',
                'format' => 'raw',
                'width' => '40%'
            ],
            'EDISISERIAL', 
            'TANGGAL_TERBIT_EDISI_SERIAL',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['style'=>'width: 50px;'],
                'template' => '<div class="btn-group-vertical">{delete}</div>',
                'buttons' => [
                  'delete' => function ($url, $model) {
                                      return Html::a('<span class="glyphicon glyphicon-scissors"></span> '.Yii::t('app', 'Remove Jilid'), Yii::$app->urlManager->createUrl(['akuisisi/koleksi-jilid/remove-jilid','id' => $model->ID]), [
                                                      'class'=>'btn btn-danger btn-sm',
                                                      'title' => Yii::t('app', 'Remove Jilid'),
                                                      //'data-toggle' => 'tooltip',
                                                      'data' => [
                                                          'confirm' => Yii::t('yii','Are you sure you want to delete this item?')
                                                      ],
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
            url  : '".Yii::$app->urlManager->createUrl(["akuisisi/koleksi-jilid/show-serial-collection-view","idjilid" => $model->IDJILID,"idcat" => $model->Catalog_id])."',
            success  : function(response) {
                $('#modalKoleksi').html(response);
            }
        }))
        {
          $('#add-koleksi-modal').modal('show');
        }
    });

    $('#btnUpdate').click(function(){
        $.ajax({
            type: 'POST',
            url : '".Yii::$app->urlManager->createUrl(["akuisisi/koleksi-jilid/update","idjilid" => $model->IDJILID,"idcat" => $model->Catalog_id])."',
            data: $('#jilid-collections-create :input').serialize(),
            success : function(response) {
               alertSwal('Data berhasil disimpan','success','2000');
            }
        });

    });
    
    $('#btnDelete').click(function(){
        swal(
        {   
          title: 'Apakah anda yakin?',   
          text: 'akan menghapus jilid ini',   
          showCancelButton: true,   
          closeOnConfirm: false,   
          confirmButtonColor: '#DD6B55',   
          confirmButtonText: 'OK, Hapus!',
          cancelButtonText: 'Tidak',  
        }, 
        function(){  
          $.ajax({
              type: 'POST',
              url : '".Yii::$app->urlManager->createUrl(["akuisisi/koleksi-jilid/remove-jilid-all","idjilid" => $model->IDJILID,"idcat" => $model->Catalog_id])."',
              success : function(response) {
                 $('#infoMessage').html(response);
              }
          });
        });

    });


    ");
?>
