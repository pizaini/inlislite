

<?php 
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
.modal-open {

  overflow-y: auto;

}
</style>


<?php 

$form = ActiveForm::begin([
'id'=>"form-groupws-modal",
'enableAjaxValidation' => false,
'enableClientValidation' => true,
'type'=>ActiveForm::TYPE_HORIZONTAL,
'formConfig' => ['labelSpan'=>3,'deviceSize' => ActiveForm::SIZE_SMALL]
]); ?>

<div class="modal-body" >


        <?= $form->field($model, 'cover')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'muka_buku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'hard_cover')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'penjilidan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'jumlah_halaman')->textInput() ?>

        <?= $form->field($model, 'jenis_kertas_buku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'ukuran_buku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'kondisi_buku')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'kondisi_usang')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'full_color')->textInput(['maxlength' => true]) ?>

</div>

