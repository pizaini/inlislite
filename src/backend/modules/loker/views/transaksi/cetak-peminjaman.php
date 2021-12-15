<?php 
use common\models\MasterUangJaminan;
use common\models\MasterJenisIdentitas;


?>




<div class="panel panel-default panel-body" >
	<center style="text-align: center; font-size: 16px">
			TANDA TERIMA PEMINJAMAN LOKER<br>
			<?= Yii::$app->config->get('NamaPerpustakaan') ?>
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
				<!-- : <?= date("d-m-Y H:i:s"); ?> -->
		 		: <?= $model->tanggal_pinjam;?> 
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
				<center>Jaminan</center> 
			</td>
		</tr>	
		<tr>
			<td width="300px" style="border: 1px solid black">
				<center><?= $data['lockers']?></center>
			</td>
			<td width="300px" style="border: 1px solid black">
				<?php if ($model->jenis_jaminan == 'Kartu Identitas') {
            		$jenisIdentitas = MasterJenisIdentitas::findOne($model->id_jamin_idt);
            		echo "<center>" .$jenisIdentitas->Nama."</center>";
            		echo "<center>".$model->no_identitas.'</center>';
            	} elseif ($model->jenis_jaminan == 'Uang') {
            		$uangJaminan = MasterUangJaminan::findOne($model->id_jamin_uang) ;
            		echo "<center>Nominal Uang Jaminan</center>";
            		echo "<center>Rp." .strrev(implode('.',str_split(strrev(strval($uangJaminan->No)),3)))." ( ".$uangJaminan->Name." ) </center>";
            	} else {
            		echo "<center> Tanpa Jaminan </center>";
            	}
            	?>
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



