<?php 
use common\models\MasterUangJaminan;
use common\models\MasterJenisIdentitas;


?>




<div class="panel panel-default panel-body" >
	<center style="text-align: center; font-size: 16px">
			BUKTI PENGEMBALIAN LOKER<br>
			<?= Yii::$app->config->get('NamaPerpustakaan')  ?>
	</center>
<hr>
	<table width="100%">
		<tr>
			<td width="150px">
				No. Transaksi
			</td>
			<td width="200px">
				: <?= $model->No_pinjaman; ?>
			</td>
			<td width="150px">
				Tanggal 	
			</td>
			<td width="200px">
				: <?= date("d-m-Y H:i:s"); ?>
		<!-- 		// : <?= $model->tanggal_pinjam;?> -->
			</td>
		</tr>	
		<tr>
			<td width="150px">
				No. Anggota / No. Pengunjung
			</td>
			<td width="200px">
				: <?= $model->no_member; ?>
			</td>
			<td width="150px">
				Nama 	
			</td>
			<td width="200px">
				: <?= $data["nama"]; ?>
			</td>
		</tr>		
	</table>

	<br>

	<table style="border: 1px solid black" width="100%">
		<tr>
			<td width="300px" style="border: 1px solid black; font-weight: bold">
				<center>Nomor Loker</center>
			</td>
			<td width="300px" style="border: 1px solid black; font-weight: bold">
				<center>Jumlah Denda</center> 
			</td>
		</tr>	
		<tr>
			<td width="300px" style="border: 1px solid black">
				<center><?= $data['lockers']?></center>
			</td>
			<td width="300px" style="border: 1px solid black">
				<?php if (isset($data["pelanggaran"])): ?>
					<center><?= $data["pelanggaran"];?></center>
					<center style="font-size: 14px; font-weight: bold;">Rp. <?= strrev(implode('.',str_split(strrev(strval($data["denda"])),3)));?></center>
				<?php else: ?>
					<center>Tidak Ada Pelanggaran</center>
				<?php endif ?>
			</td>
		</tr>		
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



