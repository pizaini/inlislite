<?php

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?> <br><?= yii::t('app','Kunjungan Periodik')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?> <br>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px;">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success">
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Kunjungan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Lokasi Perpustakaan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Lokasi Ruang')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Non Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Rombongan (Personil)')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<?php $totalJumlahAnggota = 0; ?>
					<?php $totalJumlahNonAnggota = 0; ?>
					<?php $totalJumlahrombongan = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['Periodes'] ?>
							</td>
							<td>
								<?= $TableLaporan['lokasi_perpus'] ?>
							</td>
							<td>
								<?= $TableLaporan['lokasi_ruang'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_anggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_non_anggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['rombongan'] ?>
							</td>
						</tr>
						<?php $totalJumlahAnggota = $totalJumlahAnggota + $TableLaporan['jum_anggota']  ?>
						<?php $totalJumlahNonAnggota = $totalJumlahNonAnggota + $TableLaporan['jum_non_anggota']  ?>
						<?php $totalJumlahrombongan = $totalJumlahrombongan + $TableLaporan['rombongan']  ?>
						<?php $i++ ?>
					<?php endforeach ?>
					<tr>
						<td colspan="4" style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?php echo $totalJumlahAnggota  ?>
						</td>
						<td style="font-weight: bold;">
							<?php echo $totalJumlahNonAnggota  ?>
						</td>
						<td style="font-weight: bold;">
							<?php echo $totalJumlahrombongan  ?>
						</td>
					</tr>

				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>