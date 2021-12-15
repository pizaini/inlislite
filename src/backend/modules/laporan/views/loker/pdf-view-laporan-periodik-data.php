<?php

// echo $sql;
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?>  <?= $LaporanPeriode ?><br> <?= yii::t('app','Peminjaman Loker')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px;">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success">
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?php //if ($kriterias == 'data_entry'){ echo "Periode"; }
							//else{ echo "Periode Pendaftaran";} ?>
							<?= yii::t('app','Tanggal Pinjam')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Dikembalikan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Lokasi Perpustakaan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Loker')?>
						</td>
						<td style="font-weight: bold; width: 100px;">
							<?= yii::t('app','Nomor Anggota / Kunjungan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jaminan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Petugas Peminjaman')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Petugas Pengembalian')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<?php $JumlahLoker = 0; ?>
					<?php $JumlahPeminjam = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['TglPinjam'] ?>
							</td>
							<td>
								<?= $TableLaporan['TglDikembalikan'] ?>
							</td>
							<td>
								<?= $TableLaporan['LokasiPerpustakaan'] ?>
							</td>
							<td>
								<?= $TableLaporan['NoLoker'] ?>
							</td>

							<td>
								<?= $TableLaporan['NoAnggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['NamaAnggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['Jaminan'] ?>
							</td>
							<td>
								<?= $TableLaporan['PetugasPeminjaman'] ?>
							</td>
							<td>
								<?= $TableLaporan['PetugasPengembalian'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>
				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>