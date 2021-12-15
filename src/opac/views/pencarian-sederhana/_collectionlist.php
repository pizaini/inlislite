<?php
use common\components\OpacHelpers;

$isbooking = Yii::$app->config->get('IsBookingActivated');
$this->registerJS('
$(document).ready(function() {
    $(\'#collection' . $catID . '\').DataTable(
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

<script type="text/javascript">

    function booking(CiD, id) {
        /*var pos = document.getElementById('cat43').innerHTML;
        alert("#cat"+id);*/
        $.ajax({
            type: "POST",
            cache: false,
            url: "?action=boooking&colID=" + id,
            success: function (response) {
                //$("#bookingShow"+id).html(response);
                $("#alert").html(response);
                collection(CiD);
                search(CiD);
                //$("#collapsecollection"+CiD).collapse('show');
            }
        });


    }

    function cols123(id) {
        //var id = $("#catalogID").val();
        $.ajax({
            type: "POST",
            cache: false,
            url: "?action=showCollection&catID=" + id,
            success: function (response) {
                $("#collectionShow" + id).html(response);
            }
        });
    }
</script>

<div class="table-responsive">
    <table id="collection<?= $catID ?>" class="table table-striped table-bordered" cellspacing="0">
        <thead>
        <tr>
            <th>No. Barcode</th>
            <th>No. Panggil</th>
            <th>Akses</th>
            <th>Lokasi</th>
            <th>Ketersediaan</th>
        </tr>

        </thead>

        <tbody>
        <?php
        for ($i = 1; $i <= $countCollectionList; $i++) {
            $dateNow = new \DateTime("now");
            if ($dataCollectionList[$i]['BookingMemberID'] == $noAnggota && $dataCollectionList[$i]['BookingExpiredDate'] > $dateNow->format("Y-m-d H:i:sO")) {
                $dataCollectionList[$i]['ketersediaan'] = "Sudah Anda pesan";
                //$dataCollectionList[$i]['akses'] = "Di Booking";
            } else
                if ($dataCollectionList[$i]['BookingExpiredDate'] > $dateNow->format("Y-m-d H:i:s")) {
                    $dataCollectionList[$i]['ketersediaan'] = "Sudah Dipesan";
                    //$dataCollectionList[$i]['akses'] = "Di Booking";
                }
            if ($isbooking=='1' && $dataCollectionList[$i]['ketersediaan'] == "Tersedia" && ($dataCollectionList[$i]['akses'] == "Dapat dipinjam" || $dataCollectionList[$i]['akses'] == "Tersedia" )) {
                if (!isset($noAnggota)) {
                    $booking = "
                          <br>
                           <a href=\"javascript:void(0)\" class=\"btn btn-success btn-xs navbar-btn\" onclick='tampilLogin()'>pesan</a>
 

                
                        ";                    
                } else
                    $booking = "
                            <form>
                            <input type=\"button\" onclick=\"booking(" . $dataCollectionList[$i]['Catalog_id'] . "," . $dataCollectionList[$i]['id'] . ")\" class=\"btn btn-success btn-xs navbar-btn\" value=\"pesan\">

                            </form>					
    					";
            } else {
                $booking = "";
            }

            ?>
            <tr>

                <td>
                    <?php echo $dataCollectionList[$i]['NomorBarcode'] ?>
                </td>
                <td>
                    <?php echo $dataCollectionList[$i]['CallNumber'] ?>
                </td>
                <td>
                    <?php echo $dataCollectionList[$i]['akses'] ?>
                </td>
                <td>
                    <?php echo $dataCollectionList[$i]['namaperpus'] . " - " . $dataCollectionList[$i]['lokasi'] ?>
                </td>
                <td>
                    <?php
                    echo OpacHelpers::maskedStatus($dataCollectionList[$i]['ketersediaan']);
                    echo $booking;
                    ?>
                </td>

            </tr>
            <?php
        }
        ?>


        </tbody>
    </table>


</div>


