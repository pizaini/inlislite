	<?php
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
<!-- 			<td>
	Expired
</td> -->
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