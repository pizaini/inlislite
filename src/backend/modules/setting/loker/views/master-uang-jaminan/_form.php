<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\MasterUangJaminan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="master-uang-jaminan-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 

    echo "<div class='page-header'>";
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    echo  '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']);
    echo "</div>";

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        'No'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Nominal').'...', 'maxlength'=>255]], 

        'Name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Terbilang').'...', 'maxlength'=>255]], 

        ]


        ]);
    ActiveForm::end(); ?>

</div>


<script type="text/javascript">
    $('#masteruangjaminan-no').change( function(){
        var bilangan =  $('#masteruangjaminan-no').val();
        terbilang(bilangan);
    });


    function terbilang(bilangan){
        // var bilangan=document.getElementById("nominal").value;
        var kalimat="";
        var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
        var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
        var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');
        var panjang_bilangan = bilangan.length;
        
        /* pengujian panjang bilangan */
        if(panjang_bilangan > 15){
            kalimat = "Diluar Batas";
        }else{
            /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
            for(i = 1; i <= panjang_bilangan; i++) {
                angka[i] = bilangan.substr(-(i),1);
            }
            
            var i = 1;
            var j = 0;
            
            /* mulai proses iterasi terhadap array angka */
            while(i <= panjang_bilangan){
                subkalimat = "";
                kata1 = "";
                kata2 = "";
                kata3 = "";
                
                /* untuk Ratusan */
                if(angka[i+2] != "0"){
                    if(angka[i+2] == "1"){
                        kata1 = "Seratus";
                    }else{
                        kata1 = kata[angka[i+2]] + " Ratus";
                    }
                }
                
                /* untuk Puluhan atau Belasan */
                if(angka[i+1] != "0"){
                    if(angka[i+1] == "1"){
                        if(angka[i] == "0"){
                            kata2 = "Sepuluh";
                        }else if(angka[i] == "1"){
                            kata2 = "Sebelas";
                        }else{
                            kata2 = kata[angka[i]] + " Belas";
                        }
                    }else{
                        kata2 = kata[angka[i+1]] + " Puluh";
                    }
                }
                
                /* untuk Satuan */
                if (angka[i] != "0"){
                    if (angka[i+1] != "1"){
                        kata3 = kata[angka[i]];
                    }
                }
                
                /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
                if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")){
                    subkalimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
                }
                
                /* gabungkan variabe sub kalimat (untuk Satu blok 3 angka) ke variabel kalimat */
                kalimat = subkalimat + kalimat;
                i = i + 3;
                j = j + 1;
            }
            
            /* mengganti Satu Ribu jadi Seribu jika diperlukan */
            if ((angka[5] == "0") && (angka[6] == "0")){
                kalimat = kalimat.replace("Satu Ribu","Seribu");
            }
        }

        var kalimat = kalimat.replace(/ +(?= )/g,'');
        $('#masteruangjaminan-name').val(kalimat + "Rupiah");
        // document.getElementById("terbilang").innerHTML=kalimat;
    }

</script>