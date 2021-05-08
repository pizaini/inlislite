<?php 
use kartik\widgets\ActiveForm;
use common\models\Collectionsources;
use common\models\Collectionmedias;
use common\models\Collectioncategorys;
use common\models\Collectionrules;
use common\models\Partners;
use common\models\Locations;
use common\models\LocationLibrary;
use common\models\Collectionstatus;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
?>

<?php 
if ($processid!='OPAC1'  && $processid !='OPAC0' && $processid != 'KARANTINA' && $processid != '')
{
    echo '<div class="col-md-3">';
}
?>

<?php
switch ($processid) {

    default:
    case 'OPAC1':
    case 'OPAC0':
    case 'KARANTINA':
        # code...
        break;
    case 'MEDIA':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data' => ArrayHelper::map(Collectionmedias::find()->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;
    case 'SUMBER':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data' => ArrayHelper::map(Collectionsources::find()->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;
    case 'KATEGORI':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data'=>ArrayHelper::map(Collectioncategorys::find()->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;
    case 'AKSES':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data'=>ArrayHelper::map(Collectionrules::find()->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;
    case 'STATUS':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data'=>ArrayHelper::map(Collectionstatus::find()->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;
    case 'LOKASILIB':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data'=>ArrayHelper::map(LocationLibrary::find()->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;
    case 'LOKASI':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data'=>ArrayHelper::map(Locations::find()->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;
    
}
?>


<?php 
if ($processid!='OPAC1'  && $processid !='OPAC0' && $processid != 'KARANTINA' && $processid != '')
{
    echo '</div>';
}
?>


