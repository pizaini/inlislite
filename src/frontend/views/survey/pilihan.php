<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;

// $form = ActiveForm::begin();
$items = '';

// $item = array();

// foreach ($pilihan as $pilihan) 
// {
//     if ($question['IsCanMultipleAnswer'] == 1) 
//     {
//         if ($question['Orientation'] == 'Vertikal') 
//         {
//             $items .= "<p><label>".Html::checkbox($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."</label></p>";
//         } 
//         else 
//         {

//             $items .= '<li style="display: inline;">'.Html::checkbox($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."</li>";
//         }
//     } 
//     else 
//     {
//         if ($question['Orientation'] == 'Vertikal') 
//         {
//             $items .= "<p><label>".Html::radio($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."</label></p>";
//         } 
//         else 
//         {
//             $items .= '<li style="display: inline;">'.Html::radio($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."</li>";
//         }
//     }
// }



// if ($question['Orientation'] == 'Vertikal') 
// {
//     if ($question['IsCanMultipleAnswer'] == 1) 
//     {
//         foreach ($pilihan as $pilihan) 
//         {
//             $items .= "<p><label>".Html::checkbox($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."<label><p>";
//         }
//     }
//     else
//     {
//         foreach ($pilihan as $pilihan) 
//         {
//             $items .= "<p><label>".Html::radio($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."<label><p>";
//         }
//     }
// } 
// else 
// {
//     if ($question['IsCanMultipleAnswer'] == 1) 
//     {
//         foreach ($pilihan as $pilihan) 
//         {
//             $items .= "<p><label>".Html::checkbox($question['ID'], $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])."<label><p>";
//         }
//     }
// }




// echo $content = "<h4 class='text-center'>".$question['Pertanyaan'] ."</h4>"."<div style='margin: 30px;'>".$items."</div>";
 ?>

<div class="col-sm-12">
    
    <h4 class='text-center'> <?= $question->Pertanyaan ?></h4>
    <br/>
    <?php if ($question->Orientation == 'Vertikal'): ?>
        <!-- Perulangan untuk pilihan pertanyaan Vertikal -->
        <?php if ($question->IsCanMultipleAnswer == 1): ?>
            <?php foreach ($pilihan as $pilihan): ?>
                <p>
                    <label><?= Html::checkbox($question->ID, $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])  ?><label>
                </p>
            <?php endforeach ?>
        <?php else: ?>
            <?php foreach ($pilihan as $pilihan): ?>
                <p>
                    <label><?= Html::radio($question->ID, $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan']) ?><label>
                </p>
            <?php endforeach ?>
        <?php endif ?>
        <!-- /Perulangan untuk pilihan pertanyaan -->
        
    <?php else: ?>
        <!-- Perulangan untuk pilihan pertanyaan Horizontal -->
        <div class="row">
        <?php if ($question->IsCanMultipleAnswer == 1): ?>
            
            <?php foreach ($pilihan as $pilihan): ?>
                <div class="col-sm-3">
                    <label><?= Html::checkbox($question->ID, $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])  ?><label>
                </div>
            <?php endforeach ?>

        <?php else: ?>
            <?php foreach ($pilihan as $pilihan): ?>
                <div class="col-sm-3">
                    <label><?= Html::radio($question->ID, $checked = false, $options= ['label' => $pilihan['Pilihan'],'value'=>$pilihan['ID'],'id'=>'Pilihan'])  ?><label>
                </div>

            <?php endforeach ?>
        <?php endif ?>
        <!-- /Perulangan untuk pilihan pertanyaan Horizontal -->
        </div>

    <?php endif ?>

</div>


