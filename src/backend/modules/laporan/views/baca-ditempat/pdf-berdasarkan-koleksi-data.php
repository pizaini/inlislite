<?php 

// echo $sql;
// die;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?> <br /> <?= yii::t('app','Koleksi Baca di Tempat')?> <?= $LaporanPeriode2 ?><br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?>
			
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px;">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success">
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Baca')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Lokasi Perpustakaan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Lokasi Ruang')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Induk')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Data Bibliografis')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Anggota / Kunjungan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama')?>
						</td>
					</tr>

					<?php $i = 1; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_baca'] ?>
							</td>
							<td>
								<?= $TableLaporan['LokasiPerpustakaan'] ?>
							</td>
							<td>
								<?= $TableLaporan['LokasiRuang'] ?>
							</td>
							<td>
								<?= $TableLaporan['NoInduk'] ?>
							</td>
							<td>
								<?= $TableLaporan['DataBib'] ?>
							</td>
							<td>
								<?= $TableLaporan['NoAnggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['Nama'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>

				</table>

<!--<?php print_r($TableLaporan); ?>-->

	</center>
</div>