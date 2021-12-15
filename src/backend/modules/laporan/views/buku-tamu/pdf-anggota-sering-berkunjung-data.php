<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?> <br><?= yii::t('app','Anggota Sering Berkunjung')?> <?= $LaporanPeriode2 ?> <br><?= yii::t('app','Berdasarkan Ranking')?> <?= $inValue ?><br> 
			
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;" width="80">
							<?= yii::t('app','Tanggal')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Lokasi Perpustakaan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Lokasi Ruang')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tujuan Kunjungan')?>
						</td>
					</tr>

					<?php $i = 1; ?>
					<?php $totalJudul = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['Periode'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_angg'] ?>
							</td>
							<td>
								<?= $TableLaporan['nama'] ?>
							</td>
							<td>
								<?= $TableLaporan['loc_perpus'] ?>
							</td>
							<td>
								<?= $TableLaporan['loc_ruang'] ?>
							</td>
							<td>
								<?= $TableLaporan['tuj_kunj'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>

				</table>

<!--<?php print_r($TableLaporan); ?>-->

	</center>
</div>