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
// print_r($LocID);die;
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

            if (strlen($model->Fullname) > 25) 
            {
                $td = 'style="font-size: 10px"';
                $barcodecell = 'style="margin-top: 5px; width: 100%; font-size: 10px"';
            }else{
                $barcodecell = 'style="margin-top: 15px; width: 100%; font-size: 10px"';
            }
        ?>




<!-- <img src="<?=$frontImage?>" width="316px" height="216px"> -->
<div class="container-card" style="background-image: url(<?=$frontImage?>); background-repeat: no-repeat; background-size: 100% 100%; ">
    <div class="main">

        <div class="" style="padding-top: 67px; margin-left: 3px; float: left; width:66px; ">
            <img src="<?=$image?>"  style="height: 75px;width: 62px; border: 2px solid white " />
        </div>

        <div style="padding-top: 66px; padding-left: 10px; float: left;">
            <table cellspacing="0px" border="0">
                <tr>
                    <td>
                        Nomor
                    </td>
                    <td>
                        : <?=$model->MemberNo?>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">
                        Nama
                    </td>
                    <td>
                        : <span <?php echo $td; ?> ><?php echo $model->Fullname; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Jenis
                    </td>
                    <td>
                        : <?php echo $model->jenisAnggota->jenisanggota; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Lokasi Perpustakaan
                    </td>
                    <td>
                        : <?php echo $LocID; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" height="22px"; style="vertical-align: bottom;">
                        Berlaku Hingga <?= \common\components\Helpers::DateTimeToViewFormat($model->EndDate)?>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-right: 10px;">

            <div class="barcodecell" <?php echo $barcodecell ?> >
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
</div>

<div width="316px" height="216px" style="position:fixed; left:316px; overflow: hidden; background-image: url(<?=$backImage?>); background-repeat: no-repeat; background-size: 100% 100%;">
    <div style="font-size: 10px">
                  <?= Yii::$app->config->get('Text_BELAKANG'); ?>
    </div>

</div>
<!-- <img src="<?=$backImage?>" width="316px" height="216px"> -->


<div style="width: 500px; padding-bottom: 10px"></div>

<?php endforeach; ?>
