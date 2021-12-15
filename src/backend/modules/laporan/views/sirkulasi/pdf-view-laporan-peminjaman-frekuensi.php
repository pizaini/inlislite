<?php

// die;
// echo "<pre>";
// echo $sql;
// var_dump($TableLaporan);
// echo "</pre>";
$kriterias = implode($_POST['kriterias']);
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?><br> <?= yii::t('app','Sirkulasi Peminjaman')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?>
			
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
							<?= yii::t('app','Kriteria')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Peminjaman')?>
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
					<?php $JumlahEksemplar = 0; ?>
					<?php $JumlahPeminjam = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['Periode'] ?>
							</td>
							<td>
								<?= $TableLaporan['Kriteria'] ?>
							</td>
							<td>
								<?= $TableLaporan['JumlahPeminjam'] ?>
							</td>
							<td>
								<?= $TableLaporan['JumlahJudul'] ?>
							</td>
							<td>
								<?= $TableLaporan['JumlahEksemplar'] ?>
							</td>
							
						</tr>
						<?php $totalJumlahJudul = $totalJumlahJudul + $TableLaporan['JumlahJudul']  ?>
						<?php $JumlahEksemplar = $JumlahEksemplar + $TableLaporan['JumlahEksemplar']  ?>
						<?php $JumlahPeminjam = $JumlahPeminjam + $TableLaporan['JumlahPeminjam']  ?>
						<?php $i++ ?>
					<?php endforeach ?>
					<tr>
						<td colspan='3' style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $JumlahPeminjam  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahJudul  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $JumlahEksemplar  ?>
						</td>
					</tr>

				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>