<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2018 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */


use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;
use common\widgets\AjaxButton;

/**
 * @var yii\web\View $this
 * @var common\models\PengirimanKoleksi $model
 */

$this->title = Yii::t('app', 'Create Pengiriman Koleksi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengiriman Koleksi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$url_cetak           = Url::to('pengiriman-koleksi-cetak');
?>
<div class="pengiriman-koleksi-create">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#fa-icons" data-toggle="tab"><?= Yii::t('app','Input Data Pengiriman')?></a></li>
          <li><a href="<?=$url_cetak?>"><?= Yii::t('app','Cetak Data Pengiriman')?></a></li>
        </ul>
        <div class="tab-content">
          
            
          <div class="tab-pane active" id="fa-icons">
             <div class="row">

              <br/>
              
                <?= $this->render('_form', [
                  'model' => $model
                  ]) 
                ?>
             </div>

          </div>
          
          
        </div><!-- /.tab-content -->
    </div><!-- /.nav-tabs-custom -->
</div>
