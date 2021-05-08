<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use mdm\admin\AnimateAsset;
use yii\web\YiiAsset;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use common\models\Roles;
use common\models\LocationLibrary;


use common\models\Userloclibforcol;
use common\models\Userloclibforloan;

/**
 * @var yii\web\View $this
 * @var common\models\JenisPerpustakaan $model
 * @var yii\widgets\ActiveForm $form
 */




/*$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
    $userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->title = Yii::t('rbac-admin', 'Assignment') . ' : ' . $userName;

$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;*/

AnimateAsset::register($this);
YiiAsset::register($this);


if (!$model->isNewRecord) {
    $opts = Json::htmlEncode([
            'items' => $modelAssignment->getItems()
        ]);
    $this->registerJs("var _opts = {$opts};");
}




$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';




// echo $model->id;die;

if ($model->id !== null)
{

    $userlibforCol = Userloclibforcol::find()->where('User_id = '.$model->id)->select('LocLib_id')->asArray()->all();
    $userlibforLoan = Userloclibforloan::find()->where('User_id = '.$model->id)->select('LocLib_id')->asArray()->all();

}
else
{

    $userlibforCol = array();
    $userlibforLoan =  array();

}



?>

<style type="text/css">
    .padding0{
        padding: 0;
    }
    .kecilin > .col-sm-8{
        width: 100%;
    }
</style>

<div class="jenis-perpustakaan-form">
    <div class="col-sm-8">
        <?php
        $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]]);
        echo '<div class="page-header">'.
            Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Simpan'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']). ' ';
        if(!$model->isNewRecord){
            //
           
            

            if($modelUser->IsCanResetUserPassword){
                $url2           = Url::to('reset-password');
            $ajaxOptions    = [
                'type' => 'POST',
                'url'  => $url2,
                'data' => array(
                    'ID'  => $model->ID,

                ),
                'success'=>new yii\web\JsExpression('function(data){
                     
                      if(data == "1"){
                       swal({
                            title:" ",
                            text: "BERHASIL : Password user sudah direset \n Passwordnya sama dengan nama user anda",
                            type: "success",
                             timer: 1700,
                            cancelButtonText: "Tutup",
                            closeOnConfirm: true,
                          });
                      }else{
                        swal({
                            title:" ",
                            text: "Password Gagal di Reset",
                            type: "warning",
                            timer: 1700,
                            cancelButtonText: "Tutup",
                            closeOnConfirm: true,
                          });
                      }
                   }'),
                'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){
                            alert(xhr.responseText);

                          }'),
            ];
                echo   '&nbsp;' . \common\widgets\AjaxButton::widget([
                        'label' => Yii::t('app','Reset Password User'),
                        'ajaxOptions' => $ajaxOptions,
                        'htmlOptions' => [
                            'class' => 'btn btn-success',
                            'id' => 'cari',
                            'type' => 'submit'
                        ]
                    ]) . '&nbsp;';
            }


        }
        echo    Html::a(Yii::t('app', 'Back') , '../user',['class' =>  'btn btn-warning' ]).
        '</div>';
        ?>

        <div class="row" >
            <?= $form->field($model, 'username',[ 'options' => ['class'=>'col-sm-8']])->textInput(['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Username')])->label(Yii::t('app', 'Nama User*') ) ?>

            <?= $form->field($model, 'Fullname', [ 'options' => ['class'=>'col-sm-8']])->textInput(['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Fulllname')])->label(Yii::t('app', 'Nama Lengkap*') ) ?>

            <?= $form->field($model, 'EmailAddress', [ 'options' => ['class'=>'col-sm-8']])->textInput(['placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Email')])->label(Yii::t('app', 'Email') ) ?>

            <?php
            // echo Form::widget([

            //     'model' => $model,
            //     'form' => $form,
            //     'columns' => 1,
            //     'attributes' => [
            //         'username' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Username') . '...', 'maxlength' => 50]],
            //         'Fullname' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Full Name') . '...', 'maxlength' => 255]],
            //         'EmailAddress' => ['type' => Form::INPUT_TEXT, 'options' => ['style' => 'width:300px', 'placeholder' => Yii::t('app', 'Enter') . ' ' . Yii::t('app', 'Email Address') . '...', 'maxlength' => 255]],
            //     ]
            // ]);

           /* echo $form->field($model, 'Role_id',[ 'options' => ['class'=>'col-sm-8']])->widget('\kartik\widgets\Select2', [
                'data' => ArrayHelper::map(Roles::find()->all(), 'ID', 'Name'),
                'pluginOptions' => [
                    // 'allowClear' => true,
                ],
                'options' => ['placeholder' => Yii::t('app', 'Choose') . ' ' . Yii::t('app', 'Role')]
            ])->label("Hak Akses");*/



            ?>
        </div>

        <?php
        echo $form->field($model, 'IsActive',[ 'options' => ['class'=>'col-sm-8']])->checkbox(['label' => yii::t('app','Aktif')]);

        echo $form->field($model, 'IsCanResetUserPassword',[ 'options' => ['class'=>'col-sm-8']])->checkbox(array('label' => Yii::t('app', 'Dapat Reset Password User')));

        echo $form->field($model, 'IsCanResetMemberPassword',[ 'options' => ['class'=>'col-sm-8']])->checkbox(array('label' => Yii::t('app', 'Dapat Reset Password Anggota')));

        ?>
    </div>


    <?php if ($model->isNewRecord): ?>
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-12">
                    <hr/>
                    <h4 class="text-danger"><?= yii::t('app','Hak Akses User dapat ditambahkan setelah data user tersimpan')?></h4>
                    <hr/>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="col-sm-8">
            <div class="row">
                 <div class="col-sm-12">
                 <hr/>
                    <h4><?= yii::t('app','Hak Akses User')?></h4>
                    <hr/>
                </div>
                <div class="col-sm-5">
                    <input class="form-control search" data-target="avaliable"
                           placeholder="<?= Yii::t('rbac-admin', 'Search for avaliable') ?>">
                    <select multiple size="10" class="form-control list" data-target="avaliable">
                    </select>
                </div>
                <div class="col-sm-1">
                    <br><br>
                    <?= Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => (string)$model->id], [
                        'class' => 'btn btn-success btn-assign',
                        'data-target' => 'avaliable',
                        'title' => Yii::t('rbac-admin', 'Assign')
                    ]) ?><br><br>
                    <?= Html::a('&lt;&lt;' . $animateIcon, ['revoke', 'id' => (string)$model->id], [
                        'class' => 'btn btn-danger btn-assign',
                        'data-target' => 'assigned',
                        'title' => Yii::t('rbac-admin', 'Remove')
                    ]) ?>
                </div>
                <div class="col-sm-5">
                    <input class="form-control search" data-target="assigned"
                           placeholder="<?= Yii::t('rbac-admin', 'Search for assigned') ?>">
                    <select multiple size="10" class="form-control list" data-target="assigned">
                    </select>
                </div>
            </div>
        </div>
    <?php endif ?>


    <div class="col-sm-12">
        <div class="row" >

            <div class="col-sm-2" style="padding-top: 15px; text-align: right;">
                <label class="control-label "><?= yii::t('app','Hak Lokasi Perpustakaan')?></label>
            </div>

            <div class="col-sm-10 " style="padding:0; padding-top: 15px">
                <div class="col-sm-3 padding0">
                    <?php echo $form->field($model2, 'LocLib_id',[ 'options' => ['class'=>'col-sm-12 padding0 kecilin']])->checkboxList(ArrayHelper::map(LocationLibrary::find()->all(), 'ID', 'Name'))->label(yii::t('app','Koleksi'),['class'=>'col-sm-12']);?>
                </div>
                <div class="col-sm-3 padding0">
                    <?php echo $form->field($model3, 'LocLib_id',[ 'options' => ['class'=>'col-sm-12 padding0 kecilin']])->checkboxList(ArrayHelper::map(LocationLibrary::find()->all(), 'ID', 'Name'))->label(yii::t('app','Sirkulasi'),['class'=>'col-sm-12']);?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end();
        ?>
    </div>
</div>

<script type="text/javascript">

    // $("#checkbox").attr("checked", true);
    // $('#test').prop('checked', true);
</script>



<?php

$script = <<< JS

JS;

foreach ($userlibforCol as $forCol) {
$loclibid =  $forCol['LocLib_id'];

$script .= <<< JS
    $("input[value='$loclibid'][name='Userloclibforcol\\[LocLib_id\\]\\[\\]']").attr("checked",true);
JS;
}

foreach ($userlibforLoan as $forLoan) {
$loclibid =  $forLoan['LocLib_id'];

$script .= <<< JS
    $("input[value='$loclibid'][name='Userloclibforloan\\[LocLib_id\\]\\[\\]']").attr("checked",true);
JS;
}


$this->registerJs($script);
?>
