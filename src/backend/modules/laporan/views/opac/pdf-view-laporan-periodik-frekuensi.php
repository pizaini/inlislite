<?php

// echo $sql;
$kriterias = implode($_POST['kriterias']);
$test = ($this->context->getRealNameKriteria($kriterias));

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px;">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?><br> <?= yii::t('app','Pemanfaatan OPAC')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?>
			
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
						<?php if (sizeof($_POST['kriterias']) !=1) {
						}else
						{ echo '<td style="font-weight: bold; width: 120px;">'.yii::t('app',$test).' </td>'; }?>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Terminal Komputer')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Pencarian')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<!--<?php $JumlahTerminalKomputer = 0; ?>-->
					<!--<?php $JumlahPencarian = 0; ?>-->
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['Periode'] ?>
							</td>
							<?php if (sizeof($_POST['kriterias']) !=1) {
							}else
							{ echo '<td style="font-weight: bold; width: 120px;">'.$TableLaporan['Subjek'].' </td>'; }?>
							<td>
								<?= $TableLaporan['JumlahTerminalKomputer'] ?>
							</td>
							<td>
								<?= $TableLaporan['JumlahPencarian'] ?>
							</td>
						</tr>
						<?php $JumlahTerminalKomputer = $TableLaporan['opac2']  ?>
						<?php $JumlahPencarian = $TableLaporan['opac']  ?>
						<?php $i++ ?>
					<?php endforeach ?>
					<tr>
						<td <?php if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}?> style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<!--<?= $TableLaporan['opac2'] ?> -->
							<?= $JumlahTerminalKomputer  ?>
						</td>
						<td style="font-weight: bold;">
							<!--<?= $TableLaporan['opac'] ?> -->
							 <?= $JumlahPencarian  ?>
						</td>
					</tr>

				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>

