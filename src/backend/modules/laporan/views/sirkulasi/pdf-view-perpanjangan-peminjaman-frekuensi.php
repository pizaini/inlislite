<?php

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?> <br /> <?= yii::t('app','Transaksi Peminjaman')?> <?= $LaporanPeriode2 ?> <br /> <?= yii::t('app','Berdasarkan')?>  <?= $Berdasarkan?>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Judul')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Eksemplar')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<?php $totalJumlahJudul = 0; ?>
					<?php $totalJumlahExemplar = 0; ?>
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
						</tr>
						<?php $totalJumlahJudul = $totalJumlahExemplar + $TableLaporan['JumlahJudul']  ?>
						<?php $totalJumlahExemplar = $totalJumlahExemplar + $TableLaporan['JumlahEksemplar']  ?>
						<?php $i++ ?>
					<?php endforeach ?>
					<tr>
						<td colspan="2" style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahJudul  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahExemplar  ?>
						</td>
					</tr>
		</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>