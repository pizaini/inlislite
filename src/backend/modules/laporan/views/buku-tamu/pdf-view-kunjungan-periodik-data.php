<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?><br><?= yii::t('app','Kunjungan Periodik')?> <?= $LaporanPeriode2 ?><br /> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?><br>			
		<!-- <?php print_r($TableLaporan); ?>-->

			
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
							<?= yii::t('app','Nomor Kunjungan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jenis Kelamin')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Pekerjaan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Pendidikan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tujuan Kunjungan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Informasi Dicari')?>
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
								<?= $TableLaporan['periode'] ?>
							</td>
							<td>
								<?= $TableLaporan['lokasi'] ?>
							</td>
							<td>
								<?= $TableLaporan['lok_ruang'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_pengunjung'] ?>
							</td>
							<td>
								<?= $TableLaporan['nama'] ?>
							</td>
							<td>
								<?= $TableLaporan['gender'] ?>
							</td>
							<td>
								<?= $TableLaporan['pekerjaan'] ?>
							</td>
							<td>
								<?= $TableLaporan['pendidikan'] ?>
							</td>
							<td>
								<?= $TableLaporan['tujuan'] ?>
							</td>
							<td>
								<?= $TableLaporan['info'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>

		</table>
		<center style="text-align: left; font-weight: bold;">
		<?php if ($TableLaporan['count'] < 1000) {
		}else{?>
		<br/>
		<p style="text-align: left; font-size: 24px; font-family: arial">
			Jumlah data <?= $TableLaporan['count'] ?> Export untuk melihat seluruh data
			
		</p>
		<?php }?>
	</center>
	</center>
</div>