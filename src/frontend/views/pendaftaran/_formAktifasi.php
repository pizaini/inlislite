<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Aktivasi Keanggotaan Online');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$url2           = Url::to('anggota-aktif');
$ajaxOptions    = [
	'type' => 'POST',
	'url'  => $url2,
	'data' => array(
		'NoAnggota' => new yii\web\JsExpression('function(){ return $("#dynamicmodel-memberno").val(); }'),
		'Password' => new yii\web\JsExpression('function(){ return $("#dynamicmodel-password").val(); }'),
	),

	'success'=>new yii\web\JsExpression('function(data){
				if(data == "sukses"){
                       swal({
                            title:" ",
                            text: "'. Yii::t('app', "Anggota berhasil terdaftar.") .'",
                            type: "success",
                             timer: 3000,
                            cancelButtonText: "Tutup",
                            closeOnConfirm: true,
                          });
                          $("#dynamicmodel-memberno").val("");
                          $("#dynamicmodel-password").val("");

                }else if(data == "already"){
					 swal({
                            title:" ",
                            text: "'. Yii::t('app', "Maaf No.Anggota ") .'" + $("#dynamicmodel-memberno").val() + "'. Yii::t('app', " sudah pernah terdaftar disistem kami") .'",
                            type: "warning",
                             timer: 4000,
                            cancelButtonText: "Tutup",
                            closeOnConfirm: true,
                          });
                }else{
					 swal({
                            title:" ",

							text: "'. Yii::t('app', "Maaf No.Anggota ") .'" + $("#dynamicmodel-memberno").val() + "'. Yii::t('app', " tidak terdaftar disistem kami,") .'" + "\n" + "'. Yii::t('app', " Silahkan hubungi bagian layanan keanggotaan untuk bantuan pengaktifan.") .'",
                            type: "warning",
                             timer: 4000,
                            cancelButtonText: "Tutup",
                            closeOnConfirm: true,
                          });
                 }
                   }'),

];
?>
<center>
<div class="site-reset-password">

	<div class="row">
		<div class="col-md-12">
			<?php $form = ActiveForm::begin(['id' => 'reset-password-form','layout' => 'horizontal','enableAjaxValidation' => true,]); ?>

			<?= $form->field($model, 'memberNo')->label(Yii::t('app', 'No.Anggota *')) ?>

			<?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Password *').'<p><span class="label label-info">('.Yii::t("app", "minimal 6 karakter").')</span></p>') ?>

			<div class="form-group">
				<?php
				echo \common\widgets\AjaxButton::widget([
						'label' => Yii::t('app','Aktifkan'),
						'ajaxOptions' => $ajaxOptions,
						'htmlOptions' => [
							'class' => 'btn btn-success btn-md',
							'id' => 'cari',
							'type' => 'submit'
						]
					]);
				?>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
</center>
