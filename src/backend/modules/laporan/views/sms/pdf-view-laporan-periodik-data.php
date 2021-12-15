<?php

// echo $sql;
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?><br> <?= yii::t('app','Pengiriman SMS Otomatis')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold; width: 100px;">
							<?= yii::t('app','Tanggal Pengiriman')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Handphone')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Status Pengiririman')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Anggota')?>
						</td>
						<td style="font-weight: bold; ">
							<?= yii::t('app','Nama')?>
						</td>
						<td style="font-weight: bold; ">
							<?= yii::t('app','Nomor Induk')?>
						</td>
						<td style="font-weight: bold; width: 130px;">
							<?= yii::t('app','Tanggal Peminjaman')?>
						</td>
						<td style="font-weight: bold; width: 130px;">
							<?= yii::t('app','Tanggal Jatuh Tempo')?>
						</td>
						<td style="font-weight: bold; width: 130px;">
							<?= yii::t('app','Tanggal Dikembalikan')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_kirim'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_hp'] ?>
							</td>
							<td>
								<?= $TableLaporan['status_kirim'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_anggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['nama'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_induk'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_pinjam'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_jatuh_tempo'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_kembali'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>
				</table>
	</center>
</div>