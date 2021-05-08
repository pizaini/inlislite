<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\DepositGroupWs;
use common\models\DepositKodeWilayah;
use common\models\DepositKelompokPenerbit;
use yii\widgets\Pjax;

?>
<?php $form = ActiveForm::begin([ 'enableClientValidation' => true,
                'options'                => [
                    'id'      => 'dynamic-form'
                 ]]);
                ?>

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">SSKCKR</h4>
      </div>

      <div class="modal-body">
        <div class="row">
            <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Jenis Penerbit</b>
            </div>
            <div class="col-sm-9">
                <div class="col-sm-9" style="width:55%; margin-left: -15px;">
                <?= $form->field($model, 'jenis_penerbit')->widget('\kartik\widgets\select2',['data'=>array('swasta'=>'Swasta','pemerintah'=>'Pemerintah'),'pluginOptions'=>['allowClear'=>true,],])->label(false) ?> 
                </div>
            </div>
        </div>


        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Group Penerbit</b>
            </div>
          <div class="col-sm-7">
            <?php Pjax::begin(['id' => 'pjax-collection-partners','timeout' => false ]); ?>
                       <?= $form->field($model,'id_group_deposit_group_ws')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(DepositGroupWs::find()->all(),'id_group',function($model) {return $model['group_name'];
                }),
                      'pluginOptions' => [
                          'allowClear' => true,
                      ],
                      'options'=> ['placeholder'=>Yii::t('app', 'Choose')]
                  ])->label(false)?>
                  <?php Pjax::end(); ?>


            

          </div>
          
        </div>
            <div class="col-sm-2">
                <?php 
                      echo '<p>'. Html::a(Yii::t('app', 'Tambah'), 'javascript:void(0)', ['id'=>'btnAddPartners','onclick'=>'js:AddPartners();','class' => 'btn bg-maroon btn-sm']);
                ?>
            </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Kelompok Penerbit</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model,'id_deposit_kelompok_penerbit_ws')->widget('\kartik\widgets\Select2',[
                      'data'=>ArrayHelper::map(DepositKelompokPenerbit::find()->all(),'ID',function($model) {return $model['Name'];
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
                <b>Nama Penerbit</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'nama_penerbit')->textInput(['maxlength' => true],['inline'=>true])->label(false) ?>
            <!-- <?= $form->field($model, 'nama_penerbit')->textInput(['maxlength' => true]) ?> -->
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Alamat1</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'alamat1')->textArea(['maxlength' => true],['inline'=>true])->label(false) ?>
            <!-- <?= $form->field($model, 'nama_penerbit')->textInput(['maxlength' => true]) ?> -->
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Alamat2</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'alamat2')->textArea(['maxlength' => true],['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Alamat3</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'alamat3')->textArea(['maxlength' => true],['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Kota/Kabupaten</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'kabupaten')->textInput(['maxlength' => true],['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Wilayah</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'ID_deposit_kode_wilayah')->widget('\kartik\widgets\select2',['data'=>ArrayHelper::map(DepositKodeWilayah::find()->all(),'ID',function($model) {
                    return $model['kode_wilayah'].' - '.$model['nama_wilayah'];
                }),'pluginOptions'=>['allowClear'=>true,],'options'=>['placeholder'=>yii::t('app','Wilayah')]])->label(false)?>

          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Kode Pos</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'kode_pos')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Nomor Telp1</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'no_telp1')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Nomor Telp2</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'no_telp2')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Nomor Telp3</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'no_telp3')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>


        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Nomor Fax</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'no_fax')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Email</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'email')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Kontak Person</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'contact_person')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Nomor Contact</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'no_contact')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Koleksi Per Tahun</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'koleksi_per_tahun')->textInput(['inline'=>true])->label(false) ?>
          </div>

        </div>

        
        <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                <b>Keterangan</b>
            </div>
          <div class="col-sm-8">
            <?= $form->field($model, 'keterangan')->textArea(['inline'=>true])->label(false) ?>
          </div>

        </div>

        <!-- <div class="body-group kv-fieldset-inline">
            <div class="col-sm-3">
                &nbsp;
            </div>
          <div class="col-sm-8">
            <//?php echo Html::activeCheckbox($model,'status',['label'=> yii::t('app','Status')]); ?>
          </div>

        </div>
        </div> -->
       
      </div>

        
        


      <div class="modal-footer">
       <div class="form-group">
        <div class="body-group kv-fieldset-inline">
          <div class="col-sm-10">
		  <?php if($dep == '1') { ?>
			<button type="button" class="btn btn-md btn-success" onclick="btnSave()">
				<?= $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'); ?>
			</button>
		  <?php } else { ?>
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		  <?php } ?>
            
          </div>
        </div>
       </div>
      </div>

<input type="hidden" id="hdnAjaxUrlPartner" value="<?=Yii::$app->urlManager->createUrl(["setting/deposit/deposit-group-ws/bind-ws"])?>">
<input type="hidden" id="hdnAjaxUrlWs" value="<?=Yii::$app->urlManager->createUrl(["setting/deposit/deposit-ws/create"])?>">
<input type="hidden" id="dep" name="dep" value="<?=$dep?>">
<?php 

$this->registerJs('

function AddPartners(){
    if($.ajax({
          type     :"POST",
          cache    : false,
          url  : $("#hdnAjaxUrlPartner").val(),
          success  : function(response) {
              $("#modalPartners").html(response);
          }
      }))
    {
    $("#rekanan-modal").modal("show");
    // alert("asdasdas");
    }
}

	function btnSave(){
		$.ajax({
			type : "POST",
			data : $("#dynamic-form").serialize(),
			url : $("#hdnAjaxUrlWs").val(),
			dataType : "JSON",
			success : function(data){
				if(data == true){
					alert("ok")
					$.pjax.reload({container:"#pjax-collection-partners"});
					$("#deposit-form").modal("hide");
				}else{
					
				}
			}
		});
	}
');

ActiveForm::end(); 

	

?>