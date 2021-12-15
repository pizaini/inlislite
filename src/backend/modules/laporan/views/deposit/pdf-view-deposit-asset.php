<?php
// echo '<pre>';
// echo $sql;
// echo '</pre>';die;
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Aset ')?><br/><?= $LaporanPeriode2 ?>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px;">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success">
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Judul')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Penerbit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Alamat')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Penerimaan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Eksemplar')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Mata Uang')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Harga')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kulit Muka Buku (cover)')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Finishing Kulit Muka Buku')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Bentuk Finishing Hard Cover')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Punggung Buku Penjilidan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Halaman')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jenis Kertas Buku')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Ukuran Buku')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kondisi Buku')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kondisi Usang')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Full Color')?>
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
								<?= $TableLaporan['tanggal'] ?>
							</td>
							<td>
								<?= $TableLaporan['judul'] ?>
							</td>
							<td>
								<?= $TableLaporan['penerbit'] ?>
							</td>
							<td>
								<?= $TableLaporan['alamat'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_penerimaan'] ?>
							</td>
							<td>
								<?= $TableLaporan['jumlah_eks'] ?>
							</td>
							<td>
								<?= $TableLaporan['mata_uang'] ?>
							</td>
							<td>
								<?= $TableLaporan['harga'] ?>
							</td>
							<td>
								<?= $TableLaporan['kulit_muka_buku'] ?>
							</td>
							<td>
								<?= $TableLaporan['finishing_kulit_muka_bku'] ?>
							</td>
							<td>
								<?= $TableLaporan['bentuk_finishing_hard_cover'] ?>
							</td>
							<td>
								<?= $TableLaporan['punggung_buku'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_halaman'] ?>
							</td>
							<td>
								<?= $TableLaporan['jenis_kerts_buku'] ?>
							</td>
							<td>
								<?= $TableLaporan['ukuran_bku'] ?>
							</td>
							<td>
								<?= $TableLaporan['kondsi_bku'] ?>
							</td>
							<td>
								<?= $TableLaporan['kondsi_usang'] ?>
							</td>
							<td>
								<?= $TableLaporan['fullclor'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>
				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>