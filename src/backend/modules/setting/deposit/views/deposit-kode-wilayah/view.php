<?php


use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\DepositKodeWilayah $model
 */

$this->title = 'Kode Wilayah SSKCKR - '.$model->nama_wilayah;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengaturan'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SSKCKR'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', ''), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-kode-wilayah-view">
   <?php 
		echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']);
    	echo  '&nbsp;' . Html::a(Yii::t('app', 'Update'), ['update?id='.$model->ID], ['class' => 'btn btn-primary']). '</div>';
	?>
   
	<br>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'nama_wilayah',
            'kode_wilayah',
        ],
       
    ]) ?>

</div>
