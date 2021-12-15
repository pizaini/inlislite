<?php 
use common\models\MasterUangJaminan;
use common\models\MasterJenisIdentitas;
/*echo "<pre>";
print_r($anggota);
die;*/

?>




<div class="panel panel-default panel-body" >
	<center style="text-align: center; font-size: 16px">
			TANDA TERIMA PEMESANAN KOLEKSI<br>
			<?= Yii::$app->config->get('NamaPerpustakaan') ?>
	</center>
<hr>
	<table width="100%">
		<tr>
			<td width="150px">
				No. Anggota / No. Pengunjung
			</td>
			<td width="200px">
				: <?= $anggota[0]['MemberNo']; ?>
			</td>
			<td width="150px">
				Tanggal 	
			</td>
			<td width="200px">
				: <?= date("d-m-Y H:i:s"); ?> 
	
			</td>
		</tr>	
		<tr>
			<td width="150px">
				Nama 	
			</td>
			<td width="200px">
				: <?= $anggota[0]['Fullname']; ?>
			</td>
		</tr>		
	</table>

	<br>

	<table style="border: 1px solid black" width="100%">

		<tr>
			<td width="50px" style="border: 1px solid black; font-weight: bold">
				<center>No</center>
			</td>
			<td width="300px" style="border: 1px solid black; font-weight: bold">
				<center>Judul Buku</center>
			</td>
			<td width="300px" style="border: 1px solid black; font-weight: bold">
				<center>Jatuh Tempo</center> 
			</td>
		</tr>
		<?php foreach ($booking as $i => $data):;    ?>	
		<tr>
			<td width="50px" style="border: 1px solid black">
				<center><?= $i+1; ?></center>
			</td>
			<td width="300px" style="border: 1px solid black">
				<center><?= $data['Title']; ?></center>
			</td>
			<td width="300px" style="border: 1px solid black">
				<center><?= $data['BookingExpiredDate']; ?></center>



			</td>
		</tr>
		<?php endforeach; ?>		
	</table>
	<br>

	<table width="100%">
		<tr>
			<td width="300px" style=" font-weight: bold">
				<center>Peminjam</center>
			</td>
			<td width="300px" style=" font-weight: bold">
				<center>Petugas</center> 
			</td>
		</tr>	
		<tr>
			<td width="300px" height="150px" style="">
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<center>(......................................................)</center>
			</td>
			<td width="300px" height="150px" style="">
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<center>(......................................................)</center>
			</td>
		</tr>		
	</table>
	<br>
	<br>




</div>



