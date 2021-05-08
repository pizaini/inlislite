<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use common\models\Collectionmedias;

?>
<?php $form = ActiveForm::begin([ 'enableClientValidation' => true,
                'options'                => [
                    'id'      => 'dynamic-form'
                 ]]);
                ?>

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Detail Ucapan Terima Kasih</h4>
      </div>

      <div class="modal-body">
        <div class="row">

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Judul</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'TITLE')->textArea(['inline'=>true])->label(false) ?>
          </div>

        </div>

        </div>
       
      </div>

        
        


      <div class="modal-footer">
       <div class="form-group">
        <div class="body-group kv-fieldset-inline">
          <div class="col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
          </div>
        </div>
       </div>
      </div>
<?php 

ActiveForm::end(); 

?>

