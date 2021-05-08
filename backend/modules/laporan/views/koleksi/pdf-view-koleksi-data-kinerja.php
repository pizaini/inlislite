<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?><br /> <?= yii::t('app','Kinerja User')?> <?= $LaporanPeriode2 ?><br>
			
			
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
						<td style="font-weight: bold; width: 80px; ">
							Username / 
							<br /> Full Name
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jenis Aktifitas')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','ID Data')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Deskripsi')?>
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
								<?= $TableLaporan['Kataloger'] ?>
							</td>
							<td>
								<?= $TableLaporan['nama_kriteria'] ?>
							</td>
							<td>
								<?= $TableLaporan['id_record'] ?>
							</td>
							<td align="left">
								<?= $TableLaporan['actions'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>

				</table>
	</center>
</div>