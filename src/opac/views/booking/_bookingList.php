	<?php
	use common\widgets\Alert;
	use yii\helpers\Html;
	use yii\helpers\Url;
	//use yii\widgets\DetailView;

	use common\components\Helpers;
	if(isset($alert) && $alert==TRUE){
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
}



	if(isset($booking)){
	?> 
		<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
			<td>
				No
			</td>
			<td>
				Judul Buku
			</td>
			<td>
				Expired
			</td>
			<td>
				Action
			</td>
		</tr>  
		<?php
		$i=1;
		foreach($booking as $data){
			echo"
			<tr>
				<td>
					".$i."
				</td>
				<td>
					".$data->Title."
				</td>
				<td>
					".$data->BookingExpiredDate."
				</td>
				<td>
				<a class=\"btn btn-danger\" onclick='cancelBooking(".$data->ID.")' href=\"javascript:void(0)\" role=\"button\">Cancel</a>
				</td>
				
			</tr> 
			";
		$i++;
		}
		//echo"catalog IDNYA ".$booking->Title;
		?>

		</table>

		<?php

	}
	if($jmlbooking==0){
    $this->registerJS('
    $(document).ready(
        function() {
            $(\'a.bookmarkShow\').hide();
        }
    );
    ');



    }else{
        $this->registerJS('

        $(document).ready(
            function() {
                $(\'a.bookmarkShow\').text(\'Keranjang('.$jmlbooking.')\');
            }
        );
        ');

    }



		?>

<iframe id='Iframe1Slip' src='#' class='clsifrm' style="width: 0pt; height: 0pt; border: none;" ></iframe>
<div id="divPrint" style="display:inline;"></div>

<?php
$url = Url::to(['cetak']);
$this->registerJs("
	$('#PrintButton').click(function(){
		$.get('". $url ."', {id: 1},function(data, status){
			if(status == 'success'){
				try {
					var oIframe = document.getElementById('Iframe1Slip');
					var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
					if (oDoc.document) oDoc = oDoc.document;
					oDoc.write('<html><head>');
					oDoc.write('</head><body onload=\"this.focus(); this.print(true);\" style=\"text-align: left; font-size: 8pt; width: 95%; height:90%\">');
					oDoc.write(data + '</body></html>');
					oDoc.close();
				} catch (e) {
					alert(e.message);
					self.print();
				}
			}
		});
	});
");
 ?>
