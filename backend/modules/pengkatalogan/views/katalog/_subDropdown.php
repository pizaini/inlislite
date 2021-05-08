<?php 
use kartik\widgets\ActiveForm;
use common\models\Cardformats;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
?>

<?php 
if ($processid!='OPAC1'  && $processid !='OPAC0' && $processid != 'KARANTINA' && $processid != 'KERANJANG0' && $processid != 'KERANJANG1' && $processid != '')
{
    echo '<div class="col-md-3">';
}
?>

<?php
$data = [
    "xml" => "Format MARC XML",
    "mrc" => "Format MARC Unicode/UTF-8"
];
switch ($processid) {

    default:
    case 'OPAC1':
    case 'OPAC0':
    case 'KARANTINA':
    case 'KERANJANG0':
    case 'KERANJANG1':
        # code...
        break;
    case 'KARTU':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data' => ArrayHelper::map(Cardformats::find()->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;
    case 'EXPORT':
    case 'EXPORTALL':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data' => $data,
            'size' => 'sm',
            ]);
        break;
    
}
?>


<?php 
if ($processid!='OPAC1'  && $processid !='OPAC0' && $processid != 'KARANTINA' && $processid != 'KERANJANG0' && $processid != 'KERANJANG1' && $processid != '')
{
    echo '</div>';
}
?>


