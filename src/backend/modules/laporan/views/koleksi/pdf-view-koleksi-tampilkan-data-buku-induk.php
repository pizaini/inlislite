<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin: 40; margin-top: 0px;">

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?> <br /> <?= yii::t('app','Buku Induk Perpustakaan')?> <?= $LaporanPeriode2 ?><br><?= yii::t('app','Berdasarkan')?> <?= yii::t('app',$Berdasarkan) ?><br>
						
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
				<tr class="success" >
					<td style="font-weight: bold;">
						No.
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Tanggal Pengadaan')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Nomor Induk')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Jenis Bahan')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Bentuk Fisik')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Judul')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Pengarang')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Edisi')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Tempat Terbit')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Penerbit')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Tahun Terbit')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Deskripsi Fisik')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Jenis Sumber Perolehan')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Nama Sumber Perolehan')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Kategori')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','ISBN')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','ISSN')?>
					</td>
					<td style="font-weight: bold;">
						<?= yii::t('app','Harga')?>
					</td>
				</tr>


				<?php $i = 1; ?>
				<?php foreach ($TableLaporan as $TableLaporan): ?>
					<tr>
						<td>
							<?= $i ?>
						</td>
						<td>
							<?= $TableLaporan['tanggalpengadaan'] ?>
						</td>
						<td>
							<?= $TableLaporan['no_induk'] ?>
						</td>
						<td>
							<?= $TableLaporan['JenisBahan'] ?>
						</td>
						<td>
							<?= $TableLaporan['BentukFisik'] ?>
						</td>
						<td>
							<?= $TableLaporan['Judul'] ?>
						</td>
						<td>
							<?= $TableLaporan['author'] ?>
						</td>
						<td>
							<?= $TableLaporan['Edisi'] ?>
						</td>
						<td>
							<?= $TableLaporan['TempatTerbit'] ?>
						</td>
						<td>
							<?= $TableLaporan['Penerbit'] ?>
						</td>
						<td>
							<?= $TableLaporan['TahunTerbit'] ?>
						</td>
						<td>
							<?= $TableLaporan['deskripsi'] ?>
						</td>
						<td>
							<?= $TableLaporan['JenisSumber'] ?>
						</td>
						<td>
							<?= $TableLaporan['Partner'] ?>
						</td>
						<td>
							<?= $TableLaporan['Kategori'] ?>
						</td>
						<td>
							<?= $TableLaporan['isbn'] ?>
						</td>
						<td>
							<?= $TableLaporan['issn'] ?>
						</td>
						<td>
							<?= $TableLaporan['Price'] ?>
						</td>
						
					</tr>
				
					<?php $i++ ?>
				<?php endforeach ?>

			</table>
	</center>
</div>