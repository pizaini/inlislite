<?php 

//echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?><br /> <?= yii::t('app','Pengembalian Terlambat')?> <?= $LaporanPeriode2 ?> <br /> <?= yii::t('app','Berdasarkan')?> <?= yii::t('app','Anggota')?> <?= $test?>
			
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
							<?= yii::t('app','Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Barcode')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Pinjam')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Jatuh Tempo')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tgl.Pengembalian')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Hari Terlambat')?>
						</td>
					</tr>

					<?php $i = 1; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['Periode'] ?>
							</td>
							<td>
								<?= $TableLaporan['Anggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_barcode'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_pinjam'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_tempo'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_pengembalian'] ?>
							</td>
							<td>
								<?= $TableLaporan['terlambat'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>

				</table>
	<center style="text-align: left; font-weight: bold;">
		<?php if ($TableLaporan['k_user'] < 1000) {
		}else{?>
		<br/>
		<p style="text-align: left; font-size: 24px; font-family: arial">
			Jumlah data <?= $TableLaporan['k_user'] ?> Export untuk melihat seluruh data
			
		</p>
		<?php }?>
	</center>
	</center>
</div>