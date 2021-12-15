<?php
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
<style>

.container{
    /*padding-bottom: 20px;*/
    font-family: Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif;

}

.container-card {
    /*background-image: url("<?=$frontImage?>");*/
    /*position: absolute;*/
    float: left;
    max-width: 216px !important;
    max-height: 316px !important;
    width: 216px;
    height: 316px;
    font-family: Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif;
}

.main {
    position: relative;
    top: 8px;
    left: 16px;
    font-size: 18px;
    padding-left: 10px;
    /*float: left;
     width: 158px;
    background-color: #000080;
    */



}

.right {
    position: relative;
    margin-top: -180px;
    margin-left: 140px;
    /*float: right;
        background-color: #000044;
    opacity: 0.10;*/
    width: 158px;
    /*height: 190px;*/
    font-size: 15px;

}

.barcode {
    padding: 1.5mm;
    margin: 0;
    vertical-align: top;
    color: #000044;
}
.barcodecell {
    text-align: center;
    vertical-align: middle;
    /*background-color: white;*/
}
p {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  height: 200px;
}

table {
    border-collapse: collapse;
}

td {
    padding-top: 0em;
    padding-bottom: 0em;
    font-size: 12px;
}
</style>
<?php

foreach ($id as $key => $value): ?>
        <?php
            $model = \common\models\Members::findOne($value);
            $separator = DIRECTORY_SEPARATOR;

            // $frontImage = Yii::getAlias('@uploaded_files') . "{$separator}settings{$separator}kartu_anggota{$separator}template_membership_card_".Yii::$app->config->get('KartuAnggota').".png";
            $frontImage = Yii::getAlias('@uploaded_files') . "{$separator}settings{$separator}kartu_anggota{$separator}bg_cardmember".Yii::$app->config->get('KartuAnggota').".png";

            $backImage = Yii::getAlias('@uploaded_files') . "{$separator}settings{$separator}kartu_anggota{$separator}bg_cardmemberbelakang.png";

            $image = Yii::getAlias('@uploaded_files') . "{$separator}foto_anggota{$separator}$model->PhotoUrl";

            if (!realpath($image))
            {
                $image=Yii::getAlias('@uploaded_files') . "{$separator}foto_anggota{$separator}nophoto.jpg";
            }

            $font_size = 'font-size: 12px;';
            $margin_barcode = 'margin-top: 5px;';
            if(strlen($model->Fullname) > 25){
                $font_size = 'font-size: 8px;';
                $margin_barcode = 'margin-top: 1px;';
            }
        ?>




<!-- <img src="<?=$frontImage?>" width="316px" height="216px"> -->
<div class="container-card" style="background-image: url(<?=$frontImage?>); background-repeat: no-repeat; background-size: 100% 100%; ">
    <div class="main">

        <div class="" style="padding-top: 96px; margin-left: 59px; float: left; width:120px; ">
            <img src="<?=$image?>"  style="height: 77px;width: 77px; border: 2px solid white " />
        </div>

        <div class="" style="padding-top: 5px; float: left;font-weight: bold ; <?=$font_size?> margin-right: 10px; text-align: center;">
            <?php echo $model->Fullname; ?><br>
            <span style="font-size: 11px; margin-top: -10; font-weight: normal;"><?=$model->MemberNo?></span><br/>
            <?php echo strtoupper($model->jenisAnggota->jenisanggota); ?>
        </div>
        <div class="" style="padding-top: 30px; float: left; font-weight: bold ;font-size: 16px;margin-right: 10px;">
            <div class="barcodecell" >
                <div class="barcodecell" style="<?=$margin_barcode?> width: 100%; font-size: 10px">
                            <?php
echo '<img style="padding-top:5px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($model->MemberNo, $generator::TYPE_CODE_93,1,30)) . '">';
echo '<br/>';
echo $model->MemberNo;
            ?>
               <!--  <barcode style="width: 100%;" code="3303040103057003" type="C39" size="0.7"
               class="barcode"/>
               3303040103057003 -->
            </div>
            </div>
        </div>

<!--

        <div class="" style="padding-top: 100px; margin-left: 0px; float: left; width:84px; font-weight: bold ;font-size: 11px; text-align: center; ">
            Kartu Anggota Umum
        </div>


        <div class="" style="padding-top: 33px; margin-left: 0px; float: left; width:84px; font-size: 10px; text-align: center; ">
            Berlaku Hingga <?= \common\components\Helpers::DateTimeToViewFormat($model->EndDate)?>
        </div>
 -->



    </div>
</div>

<div width="216px" height="316px" style="position:fixed; left:216px; overflow: hidden; background-image: url(<?=$backImage?>); background-repeat: no-repeat; background-size: 100% 100%;">
    <div style="font-size: 10px">
                  <?= Yii::$app->config->get('Text_BELAKANG'); ?>
    </div>

</div>
<!-- <img src="<?=$backImage?>" width="316px" height="216px"> -->


<div style="width: 500px; padding-bottom: 10px"></div>


<?php endforeach; ?>
