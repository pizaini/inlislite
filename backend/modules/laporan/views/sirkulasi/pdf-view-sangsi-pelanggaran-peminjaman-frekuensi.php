<?php

// echo $sql;
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?> <br /> <?= yii::t('app','Sangsi Pelanggaran Peminjaman')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?> <br>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Peminjaman')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Judul')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Eksemplar')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Total Uang')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Total Skorsing')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<?php $totalJumlahJudul = 0; ?>
					<?php $totalJumlahEksemplar = 0; ?>
					<?php $totaltotal_uang = 0; ?>
					<?php $totaltotal_skorsing = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['Periode'] ?>
							</td>
							<td>
								<?= $TableLaporan['JumlahJudul'] ?>
							</td>
							<td>
								<?= $TableLaporan['JumlahEksemplar'] ?>
							</td>
							<td>
								<?= $TableLaporan['total_uang'] ?>
							</td>
							<td>
								<?= $TableLaporan['total_skorsing'] ?>
							</td>
						</tr>
						<?php $totalJumlahJudul = $totalJumlahJudul + $TableLaporan['JumlahJudul']  ?>
						<?php $totalJumlahEksemplar = $totalJumlahEksemplar + $TableLaporan['JumlahEksemplar']  ?>
						<?php $totaltotal_uang = $totaltotal_uang + $TableLaporan['total_uang']  ?>
						<?php $totaltotal_skorsing = $totaltotal_skorsing + $TableLaporan['total_skorsing']  ?>
						<?php $i++ ?>
					<?php endforeach ?>
					<tr>
						<td colspan='2' style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahJudul  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahEksemplar  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totaltotal_uang  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totaltotal_skorsing  ?>
						</td>
					</tr>

				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>