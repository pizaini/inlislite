<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?> <br><?= yii::t('app','Anggota Sering Baca Ditempat')?> <?= $LaporanPeriode2 ?> <br><?= yii::t('app','Berdasarkan Ranking')?> <?= $inValue ?><br> 
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
							<?= yii::t('app','Nomor Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama Anggota')?> 
						</td>
						<td style="font-weight: bold;" width="150">
							<?= yii::t('app','Jumlah Eksemplar Dibaca')?> 
						</td>
						<td style="font-weight: bold;" width="150">
							<?= yii::t('app','Jumlah Judul Dibaca')?> 
						</td>
					</tr>

					<?php $i = 1; ?>
					<?php $totalEksemplar = 0; ?>
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
								<?= $TableLaporan['no_anggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['Nama'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_eks'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_jud'] ?>
							</td>
						</tr>
						<?php $totalEksemplar = $totalEksemplar + $TableLaporan['jum_eks']  ?>
						<?php $totalJudul = $totalJudul + $TableLaporan['jum_jud']  ?>
						<?php $i++ ?>
					<?php endforeach ?>

					<tr>
						<td colspan="4" style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalEksemplar  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totalJudul  ?>
						</td>
					</tr>

				</table>

<!--<?php print_r($TableLaporan); ?>-->

	</center>
</div>