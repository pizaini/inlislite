<?php 

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 */

	$this->title = Yii::t('app', 'Ganti Password') ;
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['index']];
	// $this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['view', 'ID' => $user->ID]];
	$this->params['breadcrumbs'][] = Yii::t('app', 'Password');

 ?>

 <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]]);  ?>

 <div class="page-header col-sm-12">
 	<div class="">
 		<?= Html::submitButton(Yii::t('app', 'Save'),['class'=>'btn btn-primary'])  ?>
 	</div>
 </div>


<div class="col-md-6">
	
 <?= $form->field($user, 'currentPassword')->label(Yii::t('app', 'Password Lama 
 <span class="require">*</span>'))  ?>
 <?= $form->field($user, 'newPassword')->passwordInput()->label(Yii::t('app', 'Password Baru <span class="require">*</span> <p><span class="label label-info">(minimal 6 karakter)</span></p>'))  ?>
 <?= $form->field($user, 'confirmNewPassword')->passwordInput()->label(Yii::t('app', 'Ulangi Password Baru <span class="require">*</span> <p><span class="label label-info">(minimal 6 karakter)</span></p>'))  ?>

</div>

<?php ActiveForm::end() ?>