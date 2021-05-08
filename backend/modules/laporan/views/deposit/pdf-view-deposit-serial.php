<?php
// echo '<pre>';
// echo $sql;
// echo '</pre>';die;
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Penerbit Surat Kabar Anggota dan Non Anggota SPS Seluruh Indonesia yang melaksanakan UU. No. 4 Th. 1990 pada tahun ')?><?= $LaporanPeriode2 ?>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px;">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success">
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Periode')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jenis Penerbit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama Penerbit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Penerbit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Judul')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Eksemplar')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Perbulan')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<?php $JumlahGagal = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['bulan'] ?>
							</td>
							<td>
								<?= $TableLaporan['jenis_penerbit'] ?>
							</td>
							<td>
								<?= $TableLaporan['nama'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_penerbit'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_judul'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_eks'] ?>
							</td>
							<td>
								<?= $TableLaporan['tambah'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>
				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>