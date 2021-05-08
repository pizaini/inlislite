<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;
use common\widgets\AjaxButton;
/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 */
$this->title = Yii::t('app', 'Cetak Pengiriman Koleksi') ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengiriman Koleksi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Cetak Pengiriman Koleksi');
$url_cetak           = Url::to('create');
?>



<div class="members-update">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li><a href="<?=$url_cetak ?>"><?= Yii::t('app','Input Data Pengiriman')?></a></li>
          <li class="active"><a href="#cetak" data-toggle="tab"><?= Yii::t('app','Cetak Data Pengiriman')?></a></li>
        </ul>
        <div class="tab-content">
          
          <div class="tab-pane active" id="cetak">
             <div class="row">

              <br/>
              
                <?= $this->render('_formCetak', [
                  'model' => $model
                  ]) 
                ?>
             </div>

          </div>
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div>