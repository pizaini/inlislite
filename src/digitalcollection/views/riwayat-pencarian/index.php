<?php 
use yii\widgets\ListView;
use nirvana\infinitescroll\InfiniteScrollPager;
use kop\y2sp\ScrollPager;
use yii\web\Session;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\HtmlPurifier;
$session = Yii::$app->session;
$session->open();


$this->registerJS('
$(document).ready(function() {
    $(\'#riwayat\').DataTable(
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



if(isset($_SESSION['RiwayatPencarian'])){
$RiwayatPencarian=$_SESSION['RiwayatPencarian'];
$riwayat=$RiwayatPencarian;





?>
<script type="text/javascript">
    
function deleteSession() {       
        //var id = $("#catalogID").val();
        $.ajax({
            type     :"POST",
            cache    : false,
            url  : "?action=deleteSession",
          
        });


    }

</script>

<div class="row"> 
<!-- <div class="col-md-10"> -->
<h2> RIWAYAT PENCARIAN </h2>
<!-- <a href="#" id="aKill" onclick="deleteSession()" > Kill Session</a> -->
<br><br>
<div class="table-responsive">
<table  id="riwayat" class="table table-striped table-bordered" cellspacing="0">
<thead>
    <tr>
        <th >No</th>
        <th >Ip</th>
        <th >Action</th>
        <th >Keyword</th>
        <th >Bahan</th>
        <th >Waktu</th>
  </tr>
    </tr>
</thead>

<tbody>
    <?php 
    $length=sizeof($riwayat);
   
//    echo "isi".$i;die;*/
    for($i=$length-1, $j = 1; $i >= 0, $j <=sizeof($riwayat); $i--,$j++){
    //for($i=0;$i<sizeof($riwayat);$i++){
    
    

    echo"    <tr>
        <td>
        ".$j."    
        </td>
        <td>"
            .($riwayat[$i]['ip']).
        "</td>
        <td>"
            .$riwayat[$i]['action'].
        "</td>
        <td>
        <a href='".$riwayat[$i]['url']."' >".Html::encode($riwayat[$i]['keyword'])." </a>
            
        </td>
        <td>"
            .$riwayat[$i]['bahan'].
        "</td>
        <td>"
            .$riwayat[$i]['time'].
        "</td>
    </tr>";




    
    }


    ?> 



</tbody>
</table>


</div>

</div>

<?php }?>

