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
use yii\helpers\Html;
use kartik\widgets\Select2;
?>

<?php 
if ($processid!='OPAC1'  && $processid !='OPAC0' && $processid != 'KERANJANG0' && $processid != 'KERANJANG1' && $processid != 'KARANTINA' && $processid != '')
{
    if($processid=='CETAKLABEL')
    {
        $col='5';
    }else{
        $col='3';
    }
    echo '<div class="col-md-'.$col.'">';
}
?>

<?php
switch ($processid) {

    default:
    case 'OPAC1':
    case 'OPAC0':
    case 'KERANJANG0':
    case 'KERANJANG1':
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
    case 'LOKASI':
        $modelloclib = LocationLibrary::find()->all();
        $loclib_id='';
        foreach ($modelloclib as $key => $value) {
            if($key==0)
            {
                $loclib_id = $value->ID;
                break;
            }
        }
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data'=>ArrayHelper::map($modelloclib,'ID','Name'),
            'size' => 'sm',
            'pluginEvents' => [
                    "select2:select" => 'function() { 
                        var id = $("#cbActionDetail").val();
                         isLoading=true;
                         $.ajax({
                            type     :"POST",
                            cache    : false,
                            url  : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi/get-dropdown-ruang"]).'?id="+id,
                            success  : function(response) {
                                $("#actionDropdownDetail").html(response);
                            }
                        });
                    }',
                ]
            ]);
        echo '</div><div class="col-md-2" id="actionDropdownDetail" >';
        echo Select2::widget([
            'id' => 'cbActionDetail2',
            'name' => 'cbActionDetail2',
            'data'=>ArrayHelper::map(Locations::find()->where(['LocationLibrary_id'=>$loclib_id])->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;
    /*case 'LOKASI':
        echo Select2::widget([
            'id' => 'cbActionDetail',
            'name' => 'cbActionDetail',
            'data'=>ArrayHelper::map(Locations::find()->all(),'ID','Name'),
            'size' => 'sm',
            ]);
        break;*/
    case 'CETAKLABEL':
        echo '<div class="row form-group">';
            echo '<div class="col-md-4">';
                echo '<label for="inputType" class="control-label control-label-sm">'.yii::t('app','Piih sumber No. Panggil').'</label>';
            echo '</div>';
            echo '<div class="col-md-8">';
            echo Html::radioList('cbActionLabel1', ['catalogs'], ['catalogs'=>yii::t('app','Katalog'),'collections'=>yii::t('app','Koleksi')]);
            echo '</div>';
        echo '</div>';
        echo '<div class="row form-group">';
            echo '<div class="col-md-4">';
                echo '<label for="inputType" class="control-label control-label-sm">'.yii::t('app','Pilih ukuran kertas').'</label>';
            echo '</div>';
            echo '<div class="col-md-8">';
                echo Select2::widget([
                    'id' => 'cbActionLabel2',
                    'name' => 'cbActionLabel2',
                    'data'=>[
                        'label-roll'=>yii::t('app','Kertas Label Roll'),
                        'barcode-roll'=>yii::t('app','Kertas Barcode Roll'),
                        'label-tj107'=>yii::t('app','Kertas Label Tom & Jerry 107'),
                        'label-tj121'=>yii::t('app','Kertas Label Tom & Jerry 121'),
                        'label-gc121'=>yii::t('app','Kertas Label Golden Cock 121'),
                        'a4'=>yii::t('app','Kertas A4'),
                    ],
                    'size' => 'sm',
                    'pluginEvents' => [
                        "select2:select" => 'function() { 
                            var id = $("#cbActionLabel2").val();
                             isLoading=true;
                             $.ajax({
                                type     :"POST",
                                cache    : false,
                                url  : "'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi/get-dropdown-labelmodel"]).'?id="+id,
                                success  : function(response) {
                                    $("#label-model").html(response);
                                }
                            });
                        }',
                    ]
                    ]);
            echo '</div>';
        echo '</div>';
        echo '<div class="row form-group">';
            echo '<div class="col-md-4">';
                echo '<label for="inputType" class="control-label control-label-sm">'.yii::t('app','Piih model').' </label>';
            echo '</div>';
            echo '<div id="label-model" class="col-md-8">';
                echo Select2::widget([
                    'id' => 'cbActionLabel3',
                    'name' => 'cbActionLabel3',
                    'data'=>[
                        'lr1'=>'Model LR1 ('.yii::t('app','No. Panggil + Barcode').')',
                        'lr2'=>'Model LR2 ('.yii::t('app','No. Panggil + Barcode').')',
                        'lr3'=>'Model LR3 ('.yii::t('app','No. Panggil + Barcode + 1 Warna').')',
                        'lr4'=>'Model LR4 ('.yii::t('app','No. Panggil + Barcode + 1 Warna').')',
                        'lr5'=>'Model LR5 ('.yii::t('app','No. Panggil Tanpa Barcode').')',
                        'lr6'=>'Model LR6 ('.yii::t('app','No. Panggil Tanpa Barcode + 1 Warna').')',
                    ],
                    'size' => 'sm',
                    ]);
            echo '</div>';
        echo '</div>';
        echo '<div class="row form-group">';
            echo '<div class="col-md-4">';
                echo '<label for="inputType" class="control-label control-label-sm">'.yii::t('app','Piih format dokumen').'</label>';
            echo '</div>';
            echo '<div id="label-model" class="col-md-8">';
                echo Select2::widget([
                    'id' => 'cbActionLabel4',
                    'name' => 'cbActionLabel4',
                    'data'=>[
                        'pdf'=>'Portable Document Format (Pdf)',
                        'doc'=>'Word Document (Doc)',
                        //'odt'=>'Open Office (odt)',
                    ],
                    'size' => 'sm',
                    ]);
            echo '</div>';
        echo '</div>';
        break;
    
}
?>


<?php 
if ($processid!='OPAC1'  && $processid !='OPAC0' && $processid != 'KERANJANG0' && $processid != 'KERANJANG1' && $processid != 'KARANTINA' && $processid != '')
{
    echo '</div>';
}
?>


