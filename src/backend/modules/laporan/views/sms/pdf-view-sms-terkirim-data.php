<?php

// echo $sql;
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?><br> <?= yii::t('app','SMS Terkirim')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= yii::t('app','Nama Anggota')?>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px;">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success">
						<td style="font-weight: bold; width: 40px;">
							No.
						</td>
						<td style="font-weight: bold; width: 140px; ">
							<?php //if ($kriterias == 'data_entry'){ echo "Periode"; }
							//else{ echo "Periode Pendaftaran";} ?>
							<?= yii::t('app','Tanggal Pengiriman')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Handphone')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Isi Pesan')?>
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
								<?= $TableLaporan['Fullname'] ?>
							</td>
							<td>
								<?= $TableLaporan['NoHp'] ?>
							</td>
							<td>
								<?= $TableLaporan['teks'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>
				</table>
	</center>
</div>