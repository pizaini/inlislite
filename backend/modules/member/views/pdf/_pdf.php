<?php
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
<style>

.container{
    /*padding-bottom: 20px;*/

}

.container-card {
    /*background-image: url("<?=$frontImage?>");*/
    /*position: absolute;*/
    float: left;
    max-width: 316px !important;
    max-height: 216px !important;
    width: 316px;
    height: 216px;
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
    /* color: #000044; */
}
.barcodecell {
    text-align: center;
    vertical-align: middle;
    background-color: white;

}
p {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  height: 200px;
}

</style>

<?php
foreach ($id as $key => $value): ?>
        <?php
		      $model = \common\models\Members::findOne($value);
	        $separator = DIRECTORY_SEPARATOR;
	        $frontImage = Yii::getAlias('@uploaded_files') . "{$separator}settings{$separator}kartu_anggota{$separator}bg_cardmember".Yii::$app->config->get('KartuAnggota').".png";

                $backImage = Yii::getAlias('@uploaded_files') . "{$separator}settings{$separator}kartu_anggota{$separator}bg_cardmemberbelakang.png";

	        $image = Yii::getAlias('@uploaded_files') . "{$separator}foto_anggota{$separator}$model->PhotoUrl";

	        if (!realpath($image))
	        {
	            $image=Yii::getAlias('@uploaded_files') . "{$separator}foto_anggota{$separator}nophoto.jpg";
	        }

          $font_size = 'font-size: 14px;';
          if(strlen($model->Fullname) > 25){
              $font_size = 'font-size: 8px;';
          }
        ?>




<!-- <img src="<?=$frontImage?>" width="316px" height="216px"> -->
<div class="container-card" style="background-image: url(<?=$frontImage?>); background-repeat: no-repeat; background-size: 100% 100%; background-color: black;">
  <div class="main">
      <br/><br/><br/><br/><br/>
      <div style="width: 200px;text-align: center;font-weight: bold;<?=$font_size?>">
           <?php echo $model->Fullname; ?>
           <!-- BARCODE -->

           <div class="barcodecell" style="margin-top: 5px; width: 98%; font-size: 10px">
            <!-- <barcode code="<?= $model->MemberNo; ?>" type="C39" class="barcode" size="0.7"/> -->
             <?php
          echo '<img style="padding-top:5px;width:90%" src="data:image/png;base64,' . base64_encode($generator->getBarcode($model->MemberNo, $generator::TYPE_CODE_39,1)) . '">';
          echo '<br/>';
          echo $model->MemberNo;
            ?>
           </div>
      </div>

      <div class="right">
          <div style="width: 100px;background-color: #000;color: #ffffff;text-align: center;margin-left: 60px;font-weight: bold;">
              <?php echo $model->jenisAnggota->jenisanggota; ?>
          </div>
          <div style="width: 100px;text-align: center;margin-left: 60px;padding-bottom: 5px;">
             <?=$model->MemberNo?>
          </div>
          <div style="width: 100px;text-align: center;margin-left: 60px;padding-bottom: 5px;font-size: 12px">
             Berlaku Hingga
             <?= \common\components\Helpers::DateTimeToViewFormat($model->EndDate)?>
             <img src="<?=$image?>"  style="height: 100px; border: 5px solid white " />
          </div>
      </div>
  </div>
</div>

<div width="316px" height="216px" style="position:fixed; left:316px; overflow: hidden; background-image: url(<?=$backImage?>); background-repeat: no-repeat; background-size: 100% 100%;">

   <div style="font-size: 10px">
                  <?= Yii::$app->config->get('Text_BELAKANG'); ?>
    </div>
</div>
<!-- <img src="<?=$backImage?>" width="316px" height="216px"> -->


<div style="width: 500px; padding-bottom: 10px"></div>


<?php endforeach; ?>
