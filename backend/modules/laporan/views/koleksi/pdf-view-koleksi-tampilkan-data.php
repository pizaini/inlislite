<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?><br /> <?= yii::t('app','Pengadaan Koleksi')?> <?= $LaporanPeriode2 ?> <br><?= yii::t('app','Berdasarkan')?> <?= yii::t('app',$Berdasarkan) ?>
			<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
				<tr class="success" >
					<td style="font-weight: bold;">
						No.
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Nomor Induk')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Data Bibliografis')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Nomor Panggil')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Tanggal Pengadaan')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Sumber Perolehan')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Jenis Bahan')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Bentuk Fisik')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Kategori')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Jenis Akses')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Harga')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Nomor Barcode')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Nomor RFID')?>
					</td>
				</tr>

				<?php $i = 1; ?>
				<?php foreach ($TableLaporan as $TableLaporan): ?>
					<tr>
						<td>
							<?= $i ?>
						</td>
						<td>
							<?= $TableLaporan['NoInduk'] ?>
						</td>
						<td>
							<?= $TableLaporan['data'], $TableLaporan['data2'], $TableLaporan['data3'], $TableLaporan['data4'], $TableLaporan['data5'], $TableLaporan['data6'], $TableLaporan['data7'] ?>
						</td>
						<td>
							<?= $TableLaporan['NomorPanggil'] ?>
						</td>
						<td>
							<?= $TableLaporan['TanggalPengadaan'] ?>
						</td>
						<td>
							<?= $TableLaporan['SumberPerolehan'] ?>
						</td>
						<td>
							<?= $TableLaporan['JenisBahan'] ?>
						</td>
						<td>
							<?= $TableLaporan['JenisMedia'] ?>
						</td>
						<td>
							<?= $TableLaporan['Kategori'] ?>
						</td>
						<td>
							<?= $TableLaporan['JenisAkses'] ?>
						</td>
						<td>
							<?= $TableLaporan['Harga'] ?>
						</td>
						<td>
							<?= $TableLaporan['NomorBarcode'] ?>
						</td>
						<td>
							<?= $TableLaporan['RFID'] ?>
						</td>
					</tr>
				
					<?php $i++ ?>
				<?php endforeach ?>

				

			</table>



			<?php //print_r($TableLaporan) ?>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	







	</center>
</div>