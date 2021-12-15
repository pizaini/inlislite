<?php
/**
 * @file    index.php
 * @date    21/8/2015
 * @time    12:25 AM
 * @author  Henry <alvin_vna@yahoo.com>
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license
 */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SettingparameterSearch $searchModel
 */

$this->title = Yii::t('app','Setting').' '.Yii::t('app','Membership Card');
$this->params['breadcrumbs'][] = Yii::t('app',$this->title);
?>
<?php

Modal::begin([
    'header' => Yii::t('app', 'Detail Template Membership Card'),
    'id'     => 'modal-for-theme-detail',
    'size'   => Modal::SIZE_LARGE
]);

Modal::end();

$this->registerJs('
$(".btn-detail").on("click", function(e){
    e.preventDefault();
    $.ajax({
        url: $(this).data("ajax-detail"),
        data: {"id": $(this).data("theme")},
        success: function(response){
            $("#modal-for-theme-detail").find(".modal-body").html(response);
        }
    });
    $("#modal-for-theme-detail").modal("show");
});

$(".textarea").wysihtml5();

');

?>
<div class="settingparameters-index">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#layourCard" data-toggle="tab"><?= Yii::t('app','Layout Member Card')?></a></li>
            <li><a href="#fa-icons" data-toggle="tab"><?= Yii::t('app','Member Card Front')?></a></li>
            <li><a href="#glyphicons" data-toggle="tab"><?= Yii::t('app','Member Card Back')?></a></li>
        </ul>
        <div class="tab-content">
            <!-- Layout Kartu Anggota -->
            <div class="tab-pane active" id="layourCard">
                <div class="row">
                    <br/>
                    <?php
                    for ($i = 1; $i <= 4; $i++) {

                        echo $this->render('_formLayoutKartu', ['i' => $i,
                            'model2' => $model2,
                            ]);
                        } ?>
                </div>
            </div>

        
            <!-- Bagian Depan Kartu Anggota -->
            <div class="tab-pane" id="fa-icons">
                <div class="row">
                    <br/>
                    <?php
                    for ($i = 1; $i <= 4; $i++) {

                        echo $this->render('_formKartuAnggota', ['i' => $i,
                            'model2' => $model2,
                            ]);
                        } ?>
                </div>
            </div>
            <!-- Bagian Belakang Kartu Anggota -->
            <div class="tab-pane" id="glyphicons">
                <div class="row">
                    <br/>
                    <?php
                    echo $this->render('_formKartuBelakang',[
                        'model' => $model,
            //  'modelImagesBelakang' => $modelImagesBelakang,
                        ]);
                    ?>
                </div>
            </div>
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div>
