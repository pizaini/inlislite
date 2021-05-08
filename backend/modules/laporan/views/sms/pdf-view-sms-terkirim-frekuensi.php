<?php

// echo $sql;
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?><br> <?= yii::t('app','SMS Terkirim')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= yii::t('app','Nama Anggota')?>
			
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
							<?= yii::t('app','Tanggal Pengiriman')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Pesan Terkirim')?>
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
								<?= $TableLaporan['Periode'] ?>
							</td>
							<td>
								<?= $TableLaporan['Fullname'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_sms'] ?>
							</td>
						</tr>
						<?php $JumlahGagal = $JumlahGagal + $TableLaporan['jum_sms']  ?>
						<?php $i++ ?>
					<?php endforeach ?>
					<tr>
						<td colspan='3' style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $JumlahGagal  ?>
						</td>
					</tr>

				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>