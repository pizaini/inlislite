<?php 

 // echo $sql;
// echo "<pre>";
// print_r($loclibrary->Name);
// echo "</pre>";die;

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<table width="100%" border="0" style=" font-size: 12px;font-family: times new roman;">
			<tr>
				<td colspan="2" style="text-align: right;"><?= date("d F Y") ?></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td width="100px"><?= yii::t('app','Nomor')?> </td>
				<td>: <?= $letter->LETTER_NUMBER_UT ?></td>
			</tr>
			<tr>
				<td><?= yii::t('app','Perihal')?> </td>
				<td>: <?= yii::t('app','Ucapan Terimakasih Pengiriman KCKR')?> </td>
			</tr>
		</table>
	</center>
	<p style="font-size: 12px; margin-top: 20px">
		<?= yii::t('app','Kepada Yth.')?> <br>

		<?= yii::t('app','Pimpinan Penerbit ').$deposit_ws->nama_penerbit; ?> <br>

		<?= $deposit_ws->kabupaten; ?><br><br><br>
	</p>

	<p style="font-size: 12px; margin-top: 20px">
		<?= yii::t('app','Dengan ini diinformasikan bahwa kami telah menerima Karya Cetak / Karya Rekam dari ')?> <br>

		<?= yii::t('app','Penerbit / Instansi  Saudara yang telah dikirim melalui Pos dengan rincian sebagai berikut')?> :
	</p>

		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
				<tr class="success">
					<td style="font-weight: bold; text-align: center">
						No.
					</td>
					<td style="font-weight: bold; text-align: center">
						<?= yii::t('app','Tanggal Terima')?>
					</td>
					<td style="font-weight: bold; text-align: center">
						<?= yii::t('app','Jenis Koleksi')?>
					</td>
					<td style="font-weight: bold; text-align: center">
						<?= yii::t('app','Jumlah Copy')?>
					</td>
					
				</tr>


				<?php $i = 1; ?>
				<?php foreach ($TableLaporan as $TableLaporan): ?>
					<tr>
						<td style="text-align: center">
							<?= $i ?>
						</td>
						<td>
							<?= $TableLaporan['tgl_terima'] ?>
						</td>
						<td>
							<?= $TableLaporan['nama'] ?>
						</td>
						<td>
							<?= $TableLaporan['jumlah'] ?>
						</td>
					</tr>
				
					<?php $i++ ?>
				<?php endforeach ?>

			</table>

	<p style="font-size: 12px; margin-top: 20px">
		<br/>
		<?= yii::t('app','Atas kerjasama dan kepatuhan Saudara dalam melaksanakan Undang-Undang No.4 Tahun 1990')?><br>

		<?= yii::t('app','tentang Serah Simpan Karya Cetak dan Karya Rekam, Kami ucapkan terimakasih.')?>
		<br/>
		<br/>
		<br/>
	</p>
	<center style="text-align: center; font-weight: bold;">
		<table width="100%" border="0" style=" font-size: 13px;font-family: times new roman;">
			<tr>
				<td colspan="2" style="text-align: right;"><?= $loclibrary->Name ?></td>
			</tr>
		</table>
	</center>
</div>
