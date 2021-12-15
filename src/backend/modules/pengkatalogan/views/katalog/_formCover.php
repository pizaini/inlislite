<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use yii\widgets\ActiveForm;
use common\components\DirectoryHelpers;
/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="row" style="padding: 10px">

<div class="col-sm-3">
    <div class="thumbnail template-thumbnail">
        
        <?php 
        $urlcover;
        $modelcat = \common\models\Catalogs::findOne($model->ID);
        $worksheetDir=DirectoryHelpers::GetDirWorksheet($modelcat->Worksheet_id);
        $url = Yii::$app->urlManager->createUrl("../uploaded_files/sampul_koleksi/");
        //$url = Yii::getAlias('@http_uploaded_files/sampul_koleksi/original/');
        if($model->CoverURL)
        {
            $urlcover= $url.'/original/'.$worksheetDir.'/'.$model->CoverURL.'?rnd='.rand();
            echo Html::a(Yii::t('app', 'Hapus Cover'), ['delete-cover?id='.$model->ID.'&refer='.\common\components\CatalogHelpers::encrypt_decrypt('encrypt',$referrerUrl)], ['class' => 'btn btn-danger btn-sm', 'onclick'=>'return confirm(\'Apakah Anda yakin akan menghapus cover ini?\')']);

        }else{
            $urlcover= $url.'/nophoto.jpg';
        }
        echo Html::img($urlcover, [
            'class' => 'template-thumbnail',
            'style' =>[
                 'width'=>'200px'
            ]

        ]); ?>
    </div>
</div>
<div class="col-sm-5">
<div class="form-group">
    <label for="email">Unggah Cover:</label>
   <?php echo FileInput::widget([
            'name' => 'catalogcover'.$model->ID,
            //'id'=>'kartu_anggota',
            'options'=>[
                'accept' => ['image/*']
            ],
            'pluginOptions' => [
        //        'initialPreview'=> [
        //            '<img src="../../../../uploaded_files/settings/kartu_anggota/bg_cardmember.png" height="190" class="border"/>',
        //        ],
                'showPreview' => false,
                'showCaption' => true,
                'showRemove' => false,
                'showUpload' => true,
                'browseLabel' => '',
                'removeLabel' => Yii::t('app','Remove'),
                'uploadLabel' => Yii::t('app','Upload'),
                'uploadUrl' => Url::to(['/pengkatalogan/katalog/upload-cover?id='.$model->ID.'&refer='.\common\components\CatalogHelpers::encrypt_decrypt('encrypt',$referrerUrl)]),
                'allowedFileExtensions'=> ["jpg", "png", "gif", "jpeg"],
                'msgInvalidFileExtension'=>Yii::t('app','Invalid extension for file "{name}". Only "{extensions}" files are supported.'),
                /*'minImageWidth'=> 1004,
                'minImageHeight'=> 638,*/
            ]
        ]);?>
</div>
<div>
 <table class="InfoTable" cellpadding="0" cellspacing="0" border="0" style="background-color: #FFFFCC; width: 100%;">
                            <tbody style="font-size: 10px"><tr>
                                <td colspan="3"  style="padding: 5px">
                                    <b>Petunjuk</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100px;padding: 5px; padding-bottom: 0px"  >
                                    Jenis File
                                </td>
                                <td style="width: 3px;padding: 5px; padding-bottom: 0px" >&nbsp;:&nbsp;</td>
                                <td  style="padding: 5px; padding-bottom: 0px">
                                    jpg, png, jpeg
                                </td>
                            </tr>
                            <tr>
                                <td  style="padding: 5px; padding-bottom: 0px">
                                    Maks. Ukuran File
                                </td  style="padding: 5px; padding-bottom: 0px">
                                <td style="width: 3px;padding: 5px; padding-bottom: 0px">&nbsp;:&nbsp;</td>
                                <td  style="padding: 5px; padding-bottom: 0px">
                                    <span id="ContentPlaceHolder1_lbMaksFileSize">1 MB</span>
                                </td>
                            </tr>
                            
                        </tbody></table>
</div>  
</div>

</div>


