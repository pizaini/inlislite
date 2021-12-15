<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\AjaxButton;

?>


<?php 
$token          = Yii::$app->request->csrfParam . "=" . Yii::$app->request->csrfToken;
$url            = Url::to('view-anggota');
$url2           = Url::to('peminjaman/viewkoleksi');
$dataAjax       = '"NoAnggota="+$("#peminjamanform-noanggota").val()+"&'.$token.'"';
$dataSuccess    = '$("#TxtNoItem,#BtnCariItem").removeAttr("disabled");$("#TxtNoItem").focus()';
$errorAdd       = '$("#TxtNoItem,#BtnCariItem").attr("disabled", "disabled");$("#peminjamanform-noanggota").focus()';


$ajaxOptions    = [
                    'type' => 'POST',
                    'url'  => $url,
                    //'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                    'update' => '#divAnggota',
                    'success'=>new yii\web\JsExpression('function(data){ $("#divAnggota").html(data);'.$dataSuccess.' }'),
                    'error' => new yii\web\JsExpression(\common\components\HtmlString::AjaxErrorResponse('divAnggota',$errorAdd)),
                ];

$htmlButton     = array('id' => 'BtnCari', 'class' => 'btn btn-warning','style'=>'padding: 6px 12px !important;');

?>
<?php $form = ActiveForm::begin(['id' => 'form-peminjaman']); ?>
<div class="content_edit" id="post_edit_1">

<table border="0">
    <tr>
        <td valign="top" class='icon-users'>&nbsp;&nbsp;
         <?= $form->field($model, 'noAnggota')->label(false)->textInput([
                    'placeholder' => Yii::t('app', 'Masukkan No.Angota'),
                 ]) ?>
          <?php //Html::activeTextInput($model,'noAnggota',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').'  No.'.Yii::t('app', 'Angota').'']); ?>
        </td>
        <td valign="top" style="padding-top: 18px; padding-left: 10px">
            <?php
            /*echo AjaxButton::widget([
                'label' => 'Cari Anggota',
                'ajaxOptions' => $ajaxOptions,
                'htmlOptions' => [
                    'class' => 'btn btn-warning',
                    'id' => 'cari',
                    'type' => 'submit'
                ]
            ]);*/
            ?>
        
            <?php 

            echo Html::submitButton(Yii::t('app', 'Cari Anggota') , ['class' => 'btn btn-warning']);
            //AjaxButton::widget("Cari Anggota", $url, v, $htmlButton); ?>
        </td>
        <td width="5%">&nbsp;</td>
        <td valign="top" class='icon-book'>&nbsp;&nbsp;<?php //CHtml::textField("TxtNoItem","",array('id'=>'TxtNoItem','placeholder'=>'Barcode Koleksi')); ?></td>
        <td valign="top"></td>
        <td style="display: none;">&nbsp;&nbsp;&nbsp;<a href="#" id="ShowPopUp">List Koleksi Yang Dipinjam</a></td>
    </tr>
</table>
</div>
 <?php $form = ActiveForm::end(); ?>
<script type="text/javascript">

$('#peminjamanform-noanggota').focus();
    // show loader during ajax call
    function showLoader(post_id) {
        $('#post_edit_1').html('<div class="loader" style="padding: 15px 0;"><div class="sk-spinner sk-spinner-three-bounce" style="margin:0;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div>');
    }


</script>