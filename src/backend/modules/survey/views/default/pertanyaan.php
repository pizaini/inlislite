<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;



$form = ActiveForm::begin();


$item = array();

foreach ($pilihan as $pilihan) 
{
    if ($question['IsCanMultipleAnswer'] == 1) 
    {
        if ($question['Orientation'] == 'Vertikal') 
        {
            //$items .= $form->field($pilihan, 'Survey_Pertanyaan_id')->checkbox(array('label'=>$pilihan['Pilihan']));

            $item[$pilihan['ID']] = $pilihan['Pilihan'];
                //$form->field($model, 'name[]')->checkboxList(['a' => 'Item A', 'b' => 'Item B', 'c' => 'Item C']).
                //"<input type='checkbox' name='check-pilihan-".$pilihan['Survey_Pertanyaan_id']."' value='".$pilihan['ID']."'>".$pilihan['Pilihan']." <br>";
        } else 
        {
            $item[$pilihan['ID']] = $pilihan['Pilihan'];
            // $items .= 
            //     //$form->field($model, 'name[]')->checkboxList(['a' => 'Item A', 'b' => 'Item B', 'c' => 'Item C']).
            //     "<td><input type='checkbox' name='check-pilihan-".$pilihan['Survey_Pertanyaan_id']."' value='".$pilihan['ID']."'>".$pilihan['Pilihan']." </td>";
        }
        $pitem = $form->field($pilihan, 'Survey_Pertanyaan_id[]')->checkboxList($item)->label(false);
    } else 
    {
        if ($question['Orientation'] == 'Vertikal') 
        {
            $item[$pilihan['ID']] = $pilihan['Pilihan'];
            // $items .= 
            //     //$form->field($model, 'name[]')->checkboxList(['a' => 'Item A', 'b' => 'Item B', 'c' => 'Item C']).
            //     "<input type='radio' name='check-pilihan-".$pilihan['Survey_Pertanyaan_id']."' value='".$pilihan['ID']."'>".$pilihan['Pilihan']."<br>";
        } else 
        {
            $item[$pilihan['ID']] = $pilihan['Pilihan'];

            // $items .= 
            //     //$form->field($model, 'name[]')->checkboxList(['a' => 'Item A', 'b' => 'Item B', 'c' => 'Item C']).
            //     "<td><input type='radio' name='check-pilihan-".$pilihan['Survey_Pertanyaan_id']."' value='".$pilihan['ID']."'>".$pilihan['Pilihan']." </td>";
        }
        $pitem = $form->field($pilihan, 'Survey_Pertanyaan_id[]')->radioList($item)->label(false);
    }
}


//$pitem = $form->field($pilihan, 'Survey_Pertanyaan_id')->checkboxList($item)->label(false);


echo $content = "<h4>".$question['Pertanyaan'] ."</h4>".$pitem;



$form = ActiveForm::begin();
 ?>