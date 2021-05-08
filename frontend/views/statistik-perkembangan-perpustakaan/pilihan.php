<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

// $form = ActiveForm::begin();
$items = '';

// $item = array();

foreach ($pilihan as $pilihan) 
{
    if ($question['IsCanMultipleAnswer'] == 1) 
    {
        if ($question['Orientation'] == 'Vertikal') 
        {
            $items .= Html::checkbox($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."<br>";
        } else 
        {

            $items .= Html::checkbox($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."&nbsp;";
        }
    } else 
    {
        if ($question['Orientation'] == 'Vertikal') 
        {
            $items .= Html::radio($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."<br>";
        } else 
        {
            $items .= Html::radio($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."&nbsp;";
        }
    }
}

echo $content = "<h5>".$question['Pertanyaan'] ."</h5>".$items;
 ?>