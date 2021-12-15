<?php 
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
?>

<?php 
if ($processid !='DOWNLOAD' && $processid != 'REMOVE' && $processid != '')
{
    echo '<div class="col-md-3">';
}
?>

<?php
switch ($processid) {

    default:
    case 'DOWNLOAD':
    case 'REMOVE':
        # code...
        break;
    case 'OPAC':
        echo Select2::widget([
            'id' => 'cbActionDetailKontenDigital',
            'name' => 'cbActionDetailKontenDigital',
            'data' => ['0'=>'Tidak dipublikasikan','1'=>'Publik','2'=>'Hanya untuk anggota'],
            'size' => 'sm',
            ]);
        break;
    
}
?>


<?php 
if ($processid != 'DOWNLOAD' && $processid != 'REMOVE' && $processid != '')
{
    echo '</div>';
}
?>


