<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?> <br /> <?= yii::t('app','Koleksi Sering Dipinjam')?> <?= $LaporanPeriode2 ?> <br><?= yii::t('app','Berdasarkan Ranking')?> <?= $inValue ?> 			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Pinjam')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Data Bibliografis')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Induk')?>
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
							<td style="text-align: left;"> 
								<?= $TableLaporan['DataBib'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_anggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['Nama'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_induk'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>

				</table>

<!--<?php print_r($TableLaporan); ?>-->

	</center>
</div>