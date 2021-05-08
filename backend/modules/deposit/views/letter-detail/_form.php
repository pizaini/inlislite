<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use common\models\Collectionmedias;

/**
 * @var yii\web\View $this
 * @var common\models\LetterDetail $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="letter-detail-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Detail Ucapan Terima Kasih</h4>
      </div>

    <div class="modal-body">
      <div class="row">

      <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Nama Bahan Perpustakaan</b>
            </div>
          <div class="col-sm-7">
            <?= $form->field($model,'SUB_TYPE_COLLECTION')->widget('\kartik\widgets\Select2',[
                'data'=>ArrayHelper::map(Collectionmedias::find()->where(['not', ['KodeBahanPustaka' => null]])->all(),'ID',function($model) {
                    return $model['KodeBahanPustaka'].' - '.$model['Name'];
                }),
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'options'=> ['placeholder'=>Yii::t('app', 'Choose')]
            ])->label(false)?>

          </div>
          
        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Status ISBN</b>
            </div>
            <div class="col-sm-9">
                <div class="col-sm-9" style="width:55%; margin-left: -15px;">
                <?= $form->field($model, 'ISBN_STATUS')->widget('\kartik\widgets\select2',[
                  'data'=>array('0'=>'Belum Ber ISBN','1'=>'Nomor Tidak Sesuai'),
                    'pluginOptions'=>[
                      'allowClear'=>true,
                    ],
                    'options'=> ['placeholder'=>Yii::t('app', 'Choose')]
                  ])->label(false) ?> 
                </div>
            </div>
        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Judul</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'TITLE')->textArea(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Pengarang</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'AUTHOR')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        
        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Penerbit</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'PUBLISHER')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

		
		
          <div>
            <?= $form->field($model, 'LETTER_ID')->hiddenInput(['value'=> $letter_id])->label(false) ?>
          </div>

          <!-- <div>
            <?//php $form->field($model, 'LETTER_ID')->hiddenInput(['value'=> '2'])->label(false); ?>
          </div> -->


        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Alamat Penerbit</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'PUBLISHER_ADDRESS')->textArea(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Kota Penerbit</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'PUBLISHER_CITY')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Tahun Terbit</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'PUBLISH_YEAR')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>ISSN / ISBN</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'ISBN')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Jumlah Judul</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'QUANTITY')->textInput(['type'=>'number'])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Jumlah Copy</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'COPY')->textInput(['type'=>'number'])->label(false) ?>
          </div>

        </div>

        <!-- <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                &nbsp;
            </div>
          <div class="col-sm-8">
            <?//php echo Html::activeCheckbox($model,'ISBN_STATUS',['label'=> yii::t('app','Status')]); ?>
          </div>

        </div> -->
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
