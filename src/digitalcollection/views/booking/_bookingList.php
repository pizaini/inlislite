	<?php
	use common\widgets\Alert;
	use yii\helpers\Html;
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