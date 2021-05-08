	<?php
	if(isset($booking)){
	?> 
		<table class="table table-bordered">
		<tr>
			<td>
				no
			</td>
			<td>
				Judul Buku
			</td>
			<td>
				Expired
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
			</tr> 
			";
		$i++;
		}
		//echo"catalog IDNYA ".$booking->Title;
		?>

		</table>

		<?php 
	}
		?>