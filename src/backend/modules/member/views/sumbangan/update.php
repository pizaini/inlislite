<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var common\models\Sumbangan $model
 */

$this->title = Yii::t('app', 'Koreksi Sumbangan') . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sumbangan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Koreksi');
?>
<style type="text/css">
.modal .modal-dialog { width: 95%; }
.modal .modal-body {
    max-height: 550px;
    overflow-y: auto;
}
</style>
<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'enableClientValidation' => true,]);?>
<div class="sumbangan-update">


    <?= $this->render('_form', [
        'model' => $model,
        'form'=>$form
    ]) ?>

</div>
<?php  ActiveForm::end();?>

<?php
Modal::begin([
    'id' => 'pilihsalin-modal',
    'size'=>'modal-lg',
    'header' => '<h4 class="modal-title">...</h4>',
    'options' => [
      'width' => '900px',
  ],
]);
 
echo '...';
 
Modal::end();
?>   
<?php 
$this->registerJS("
    $('#pilihsalin-modal').on('show.bs.modal', function (event) {
        isLoading = false;
        var button = $(event.relatedTarget)
        var modal = $(this)
        var title = button.data('title') 
        var href = button.attr('href') 
        var params = {};
        modal.find('.modal-title').html(title)
        modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
        $.post(href,params)
            .done(function( data ) {
                modal.removeAttr('tabindex');
                modal.find('.modal-body').html(data)
            });
});");

?>

<script type="text/javascript">
sendCatalog = function(id) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : "<?=Url::to(["pilih-judul-proses"])?>",
      data: {id:id},
      success  : function(response) {
          $("#koleksi-item").html(response);
      },
      error : function(xhr, ajaxOptions, thrownError){ 
                           var msg = cleanResponseError(xhr.responseText,"Not Found (#404): ");
                            alertSwal(msg,"info","2000");
                            
                          }

  });
}
</script>