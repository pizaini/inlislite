<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\FileInput;

$this->title = Yii::t('app', 'Pengaturan Audio Buku Tamu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Audio'), 'url' => Url::to(['/setting/audio'])];
$this->params['breadcrumbs'][] = $this->title;

?>



<?php if ($existFile == true): ?>
	<div class="col-sm-12">
		<!-- <h4>Audio saat ini</h4> -->
		<h4 class="text-danger"><?= yii::t('app','Audio Buku tamu sudah di set, Klik button ubah untuk menampilkan form merubah audio')?></h4>
		<audio id="welcomeAudio" controls>
			<source src="<?= Yii::$app->urlManager->createUrl("../uploaded_files/settings/audio").'/'.$audio ?>" type="audio/mpeg">
			<!-- <source src="/inlislite3/uploaded_files/settings/audio/<?= $audio ?>" type="audio/mpeg"> -->
				<?= yii::t('app','Browser anda tidak support HTML5 audio.')?>
		</audio >
	</div>
	<div class="col-sm-12" style="padding-top: 10px;">
		<a href="<?= Yii::$app->urlManager->createUrl("setting/audio/audio-bukutamu/delete-audio") ?>" class="btn btn-danger"><?= Yii::t('app','Delete');  ?></a>
		<button type="button" class="btn btn-warning" id="btnChangeAudio"><?= yii::t('app','Ubah')?></button>
		<!-- <h4 class="text-danger">Audio Buku tamu sudah di set, Klik button ubah untuk menampilkan form merubah audio</h4> -->
		<hr>
	</div>
<?php else: ?>
	<div class="col-sm-12">
		<h4 class="text-success"><?= yii::t('app','Audio Buku tamu belum di set, Silahkan upload file terlebih dahulu untuk mengaktifkan')?></h4>
	</div>
<?php endif ?>


<div class="col-sm-12" id="changeAudio" hidden="hidden">
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

	    <?php //echo $form->field($model, 'file')->fileInput()->label(Yii::t('app','Pilih Audio File')); ?>
	    <div class="col-sm-4" style="padding: 0;">
	    	<?= $form->field($model, 'file')->widget(FileInput::classname(), ['options' => ['accept' => 'audio/*'],])->label(Yii::t('app','Pilih Audio File')); ?>
	    </div>
	    
	    <!-- <input type="hidden" name="<?//= Yii::$app->request->csrfParam; ?>" value="<?//= Yii::$app->request->csrfToken; ?>" />
	 -->
	 <br/>
	 <div class="col-sm-12" style="padding: 0;">
	 	<button type="submit" class="btn btn-primary"><?= Yii::t('app','Save');  ?></button>	
	 </div>
</div>


<?php ActiveForm::end(); ?>


<?php 
$this->registerJs(" 


	if ('".$existFile."' == '') {
		$('#changeAudio').show();
	} 

    $('#btnChangeAudio').click(function()
    {
        $('#changeAudio').slideToggle();
    }
    );

");

?>

