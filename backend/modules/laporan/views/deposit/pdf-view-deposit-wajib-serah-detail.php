<?php
// echo '<pre>';
// echo $sql;
// echo '</pre>';
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Daftar Jumlah koleksi per Wajib Serah Detail')?> <br>
			<?= $LaporanPeriode2 ?>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px;">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success">
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama Penerbit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Alamat')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kota Terbit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Email')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jenis Koleksi')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kode Jenis')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Judul')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Pengarang')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','ISBN')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Deposit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Edisi')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Edisi Serial')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Edisi')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tahun Terbit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Terima')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Buat')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['penertbit'] ?>
							</td>
							<td>
								<?= $TableLaporan['alamat'] ?>
							</td>
							<td>
								<?= $TableLaporan['kota'] ?>
							</td>
							<td>
								<?= $TableLaporan['email'] ?>
							</td>
							<td>
								<?= $TableLaporan['jenis_koleksi'] ?>
							</td>
							<td>
								<?= $TableLaporan['kode'] ?>
							</td>
							<td>
								<?= $TableLaporan['judul'] ?>
							</td>
							<td>
								<?= $TableLaporan['pengarang'] ?>
							</td>
							<td>
								<?= $TableLaporan['isbn'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_deposit'] ?>
							</td>
							<td>
								<?= $TableLaporan['edisi'] ?>
							</td>
							<td>
								<?= $TableLaporan['eds_serial'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_eds_serial'] ?>
							</td>
							<td>
								<?= $TableLaporan['thn_terbit'] ?>
							</td>
							<td>
								<?= $TableLaporan['thn_penerimaan'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_buat'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>
				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>