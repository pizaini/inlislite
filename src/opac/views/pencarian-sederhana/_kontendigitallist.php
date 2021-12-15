<?php
use common\components\DirectoryHelpers;

?>


<style type="text/css">
/*.table-striped2>tbody>tr:nth-child(odd)>td, */
.table-striped2>tbody>tr:nth-child(odd)>th {
   background-color: #c5d4ff;
 }    


</style>
<script type="text/javascript">

    function logDownload(id) {
        $.ajax({
            type: "POST",
            cache: false,
            url: "?action=logDownload&ID=" + id,
        });


    }
</script>
<div class="table-responsive" id="kontendigital">
    <p>
    <table class="table table-striped2 table-bordered">


        <tr>
            <th >No</th>
            <th ><?= yii::t('app','Nama File')?></th>
            <th> <?= yii::t('app','Nama File Format Flash')?></th>
            <th ><?= yii::t('app','Format File')?></th>
            <th ><?= yii::t('app','Aksi')?></th>

            <?php
            for ($i = 1; $i <= $countCollectionList; $i++) {    
            ?>
        <tr>
            <td><?= $i ?></td>
            
            <?php if($dataCollectionList[$i]['FileFlash']!='' && $dataCollectionList[$i]['FileFlash'] != NULL)
            {  
                $fakePath = DirectoryHelpers::GetTemporaryFolder($dataCollectionList[$i]['ID'],2);
                if(!isset($noAnggota) && $dataCollectionList[$i]['IsPublish']===2){
                    $kata="<a href=\"javascript:void(0)\" onclick=\"tampilLogin()\" >Baca Online</a>";
                } else {
                $kata="<a href=\"../uploaded_files".$fakePath."\" onclick=\"logDownload(".$dataCollectionList[$i]['ID'].")\" target=\"_blank\" >Baca Online</a>";
                }
            }
            else{
                
                $fakePath = DirectoryHelpers::GetTemporaryFolder($dataCollectionList[$i]['ID'],1);
                if((!isset($noAnggota)) && $dataCollectionList[$i]['IsPublish']==2){
                $kata="<a href=\"javascript:void(0)\" onclick=\"tampilLogin()\" >Download</a>";
                } else{
                $kata="<a  href=\"../uploaded_files".$fakePath."\" onclick=\"logDownload(".$dataCollectionList[$i]['ID'].")\"target=\"_blank\" >Download</a>";           
                }
            }
           
           
            ?>
            <td><?=$dataCollectionList[$i]['FileURL']?></td>           
            <td><?=$dataCollectionList[$i]['FileFlash']?></td>           
            <td><?= $dataCollectionList[$i]['FormatFile']; ?></td>
            <td><?=$kata?></td>
        </tr>
        <?php
        }
        ?>

    </table>
    </p>
<?php
/*echo "<pre>";
print_r($dataCollectionList);*/
?>
</div>
