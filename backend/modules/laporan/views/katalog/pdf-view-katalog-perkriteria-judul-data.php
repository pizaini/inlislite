<?php

// echo $sql;
// $kriterias = implode($_POST['kriterias']);
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?>  <br><?= yii::t('app','Katalog Perkriteria')?> <?= $LaporanPeriode2 ?><br /><?= yii::t('app','Berdasarkan')?> <?= yii::t('app',$Berdasarkan) ?>
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
							<tr class="success" >
								<td style="font-weight: bold; width: 50px;">
									No.
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Tanggal')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Control Number')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','BIB-ID')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Judul')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Pengarang')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Penerbit')?>
								</td>
							</tr>

							<?php $i = 1; ?>
							<?php $totalJumlahJudul = 0; ?>
							<?php $totalJumlahExemplar = 0; ?>
							<?php foreach ($TableLaporan as $TableLaporan): ?>
								<tr>
									<td>
										<?= $i ?>
									</td>
									<td>
										<?= $TableLaporan['Periode'] ?>
									</td>
									<td>
										<?= $TableLaporan['NoPanggil'] ?>
									</td>
									<td>
										<?= $TableLaporan['BIBID'] ?>
									</td>
									<td>
										<?= $TableLaporan['Judul'] ?>
									</td>
									<td>
										<?= $TableLaporan['Pengarang'] ?>
									</td>
									<td>
										<?= $TableLaporan['publisher'] ?>
									</td>
								</tr>
								<?php $i++ ?>
							<?php endforeach ?>
				</table>
	<center style="text-align: left; font-weight: bold;">
		<?php if ($TableLaporan['katalog'] < 1000) {
		}else{?>
		<br/>
		<p style="text-align: left; font-size: 24px; font-family: arial">
			<?= yii::t('app','Jumlah data')?> <?= $TableLaporan['katalog'] ?> <?= yii::t('app','Export untuk melihat seluruh data')?>
			
		</p>
		<?php }?>
	</center>
	</center>
</div>
