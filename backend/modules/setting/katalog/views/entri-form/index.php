<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;



/**
 * @var yii\web\View $this
 * @var common\models\Collectioncategorys $model
 */

$this->title = Yii::t('app', 'Pengaturan Form Entri Katalog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'katalog'), 'url' => Url::to(['/setting/katalog'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<style type="text/css">
	.col-sm-4 label{
		font-weight: normal;
	}

	.table{
		margin-bottom: 0px;
	}
</style>


<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
<div class="settingparameters-create">
    <div class="page-header">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>        
    </div>

    <div class="settingparameters-form">
        <div class="form-group">

            <?= $form->field($model,'FormEntriKatalog')->radioList(['Simple'=>Yii::t('app', 'Simple'),'Advance'=>Yii::t('app', 'Advance')], ['inline'=>true])->label(Yii::t('app', 'Form Entri Katalog'))?>

            <?php //echo $form->field($model,'Value4')->radioList(['Simple'=>Yii::t('app', 'Simple'),'Advance'=>Yii::t('app', 'Advance')], ['inline'=>true])->label(Yii::t('app', 'Entry Form Collection'))?>

        </div>
    </div>
</div>




<!-- Modal -->
<div id="SettingJaminanUang" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app','Setting').' '.Yii::t('app','Uang Jaminan') ?></h4>
            </div>
            <div class="modal-body row">
                
                


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>




    
<div class="alert alert-info alert-dismissable" style="margin-top: 20px">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> <?= Yii::t('app','Catatan')  ?></h4>
    <?= Yii::t('app','Setiap user dapat mengubah ke modul Sederhana ataupun MARC, dengan cara klik tombol Tampilkan Sederhana / MARC di form entri katalog/koleksi ')  ?>
</div>
