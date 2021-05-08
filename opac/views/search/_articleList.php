<?php
use common\components\OpacHelpers;

$this->registerJS('
$(document).ready(function() {
    $(\'#article' . $catID . '\').DataTable(
        {
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true
        });
} );


    ');
?>



<div class="table-responsive">
    <table id="article<?= $catID ?>" class="table table-striped table-bordered" cellspacing="0">
        <thead>
        <tr>
            <th><?= yii::t('app','Judul')?></th>
            <th><?= yii::t('app','Jenis Artikel')?></th>
            <th><?= yii::t('app','Pengarang')?></th>
            <th><?= yii::t('app','EDISISERIAL')?></th>
        </tr>

        </thead>

        <tbody>
        <?php
        //OpacHelpers::print__r($hasilSearch);
        for ($i = 0; $i < sizeof($hasilSearch); $i++) {
            ?>
            <tr>

                <td>
                    <?php echo $hasilSearch[$i]['Title'] ?>
                </td>
                <td>
                    <?php echo $hasilSearch[$i]['Article_type'] ?>
                </td>
                <td>
                    <?php echo $hasilSearch[$i]['Creator'] ?>
                </td>
                <td>
                    <?php echo $hasilSearch[$i]['EDISISERIAL'] ?>
                </td>




            </tr>
            <?php
        }
        ?>


        </tbody>
    </table>


</div>


