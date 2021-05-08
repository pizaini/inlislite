<style type="text/css">
/*.table-striped2>tbody>tr:nth-child(odd)>td, */
.table-striped2>tbody>tr:nth-child(odd)>th {
   background-color: #c5d4ff;
 }    


</style>

<div class="table-responsive" id="kontendigital">
    <p>
    <table class="table table-striped2 table-bordered">


        <tr>
            <th >No</th>
            <th >Nama File</th>
            <th >Format File</th>
            <th >Action</th>

            <?php
            for ($i = 1;
            $i <= $countCollectionList;
            $i++) {
            ?>
        <tr>
            <td><?= $i ?></td>
            <td><?= $dataCollectionList[$i]['FileURL']; ?></td>
            <td><?= $dataCollectionList[$i]['FormatFile']; ?></td>
            <td>Download</td>
        </tr>
        <?php
        }
        ?>

    </table>
    </p>
</div>
