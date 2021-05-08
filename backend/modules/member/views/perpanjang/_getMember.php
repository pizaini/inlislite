<?php
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Members;

?>

<div class="form-group field-" id="member" >
    
    <?php
        $path = Yii::$app->homeUrl;
        $path = str_replace("backend/", "", $path);
        // print_r($path);
        foreach ($member as $member) { 
    ?>
        <div class="form-group">
            <label class="control-label col-md-4" for="jenis-anggota">Nama</label>
            <div class="col-md-8">
                <label><?= $member->Fullname ?></label>
                <?php 
                if ($member->PhotoUrl) {
                    $img = Yii::getAlias('@uploaded_files') . '/foto_anggota/' . $member->PhotoUrl;
                } else

                $img = Yii::getAlias('@uploaded_files') . '/foto_anggota/' . $member->ID;

                if (!file_exists($img)) {
                    $image = '../../../uploaded_files/' .Yii::$app->params['pathFotoAnggota'] . '/'."nophoto.jpg?timestamp=" . rand();
                } else {
                   $image = '../../../uploaded_files/' .Yii::$app->params['pathFotoAnggota'] . '/'.$member->PhotoUrl."?timestamp=" . rand();
                   
                }

                //if($member->PhotoUrl) {
                    // $a = $path.'uploaded_files/foto_anggota/'.$member->PhotoUrl;
                    // $a = getcwd().'/../..'.$a;
                    // // echo $a;
                    // if(file_exists($a)){
                    //     $image = $path.'uploaded_files/foto_anggota/'.$member->PhotoUrl;
                    // }else{
                    //     $image = $path.'uploaded_files/foto_anggota/nophoto.jpg';
                    // }

                    // $image = file_exists($path.'uploaded_files/foto_anggota/'.$member->PhotoUrl)? $a : $path.'uploaded_files/foto_anggota/nophoto.jpg';
                    
                //  }else{ 
                //     $image = $path.'uploaded_files/foto_anggota/nophoto.jpg';
                // } 
                ?>
                
            </div>    
        </div>
        
        <div class="form-group">
            <label class="control-label col-md-4">Tempat Lahir</label>
            <div class="col-md-8"><?= $member->PlaceOfBirth ?></div>     
        </div>

        <div class="form-group">
            <label class="control-label col-md-4">Tanggal Lahir</label>
            <div class="col-md-8"><?= $member->DateOfBirth ?></div>     
        </div>
        
    <?php } ?>
</div>
<script>
    $('#gmb_anggota').html('<img src="<?= $image ?>" width="40%">');
</script>

