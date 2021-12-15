<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

use common\models\Locations;

/**
 * @var yii\web\View $this
 * @var common\models\MasterLoker $model
 */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Master Lokers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-loker-view">
   <p> <a class="btn btn-warning" href="locker">Kembali</a>        <a class="btn btn-primary" href="updatelocker?id=<?=$model->ID?>">Koreksi</a>        <a class="btn btn-danger" href="deletelocker?id=<?=$model->ID?>" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



   <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'No',   
        'Name',    
       ['attribute'=>'locations_id','value'=> function($model){
                $lokasi = Locations::findOne(['ID'=>$model->locations_id]);
                return $lokasi['Name'];
            },'label'=>'Lokasi'], 
        'status', 
    ],
]);
?>
   
  

</div>
