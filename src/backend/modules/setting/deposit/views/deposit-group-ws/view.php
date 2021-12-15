<?php


use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\DepositGroupWs $model
 */

$this->title = 'Group Wajib Serah - '.$model->group_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengaturan')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SSKCKR Group Ws'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-group-ws-view">
	<?php 
		echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']);
    	echo  '&nbsp;' . Html::a(Yii::t('app', 'Update'), ['update?id='.$model->id_group], ['class' => 'btn btn-primary']). '</div>';
	?>
   
	<br>


    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'id_group',
            'group_name',
        ],
       
    ]) ?>

</div>
