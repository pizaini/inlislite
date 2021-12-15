<?php

use common\widgets\Alert;
use yii\helpers\Html;

foreach (Yii::$app->session->getAllFlashes() as $message):;

    echo \kartik\widgets\Growl::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
        'showSeparator' => true,
        'delay' => 1, //This delay is how long before the message shows
        'pluginOptions' => [
            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
            'placement' => [
                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
            ]
        ]
    ]);


endforeach;


if ($booking == 0) {
    $this->registerJS('
        $(document).ready(
            function() {
                $(\'a.bookmarkShow\').hide();
            }
        );
        ');


} else {
    $this->registerJS('

        $(document).ready(
            function() {
                $(\'a.bookmarkShow\').show();
                $(\'a.bookmarkShow\').text(\'Keranjang(' . sizeof($booking) . ')\');
            }
        );
        ');

}
?>

              <div class="table-responsive">
              <table  id="detail" class="table table-striped table-bordered" cellspacing="0">
              <thead>
                  <tr>                        
                        <th >No Barcode</th>
                        <th >No. Panggil</th>
                        <th >Akses</th>
                        <th >Lokasi</th>
                        <th >Ketersediaan</th>
                  
                  </tr>
                  </tr>
              </thead>
             
              <tbody>
                  <?php 
                  for($i=1;$i<=$countCollectionDetail;$i++){
                  $dateNow = new \DateTime("now");
                  if($dataCollectionDetail[$i]['BookingMemberID'] == $noAnggota && $dataCollectionDetail[$i]['BookingExpiredDate'] > $dateNow->format("Y-m-d H:i:sO") ){
                    $dataCollectionDetail[$i]['ketersediaan'] = "Sudah Anda Booking";
                    $dataCollectionDetail[$i]['akses'] = "Di Booking";
                    } else
                    if($dataCollectionDetail[$i]['BookingExpiredDate'] > $dateNow->format("Y-m-d H:i:s") ){
                    $dataCollectionDetail[$i]['ketersediaan'] = "Sudah Di Booking  Sampai ".$dataCollectionDetail[$i]['BookingExpiredDate'];
                    $dataCollectionDetail[$i]['akses'] = "Di Booking";
                    }
                  

                  echo"    <tr>
                      <td>"
                          .$dataCollectionDetail[$i]['NomorBarcode'].
                      "</td>
                      <td>"
                          .$dataCollectionDetail[$i]['CallNumber'].
                      "</td>
                      <td> "
                          .$dataCollectionDetail[$i]['akses']."
                          
                      </td>
                      <td>"
                          .$dataCollectionDetail[$i]['namaperpus']." - ".$dataCollectionDetail[$i]['lokasi'].
                      "</td>
                      <td>"
                          .$dataCollectionDetail[$i]['ketersediaan'];
                     
                        if($dataCollectionDetail[$i]['ketersediaan'] == "Tersedia" && ($dataCollectionDetail[$i]['ketersediaan'] == "Dapat dipinjam" || $dataCollectionDetail[$i]['ketersediaan'] == "Tersedia" ))
                      echo"
                        <form>
                        <input type=\"button\" onclick=\"booking(".$dataCollectionDetail[$i]['id'].")\" class=\"btn btn-success btn-xs navbar-btn\" value=\"pesan\">
                        
                        
                        </form>         
                       </td>
                        ";
                  echo"
                      
                  </tr>";

                  }

                  ?> 



              </tbody>
              </table>


              </div>

          