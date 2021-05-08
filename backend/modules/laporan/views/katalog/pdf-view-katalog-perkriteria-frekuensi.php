<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";
$kriterias = implode($_POST['kriterias']);
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?><br> <?= yii::t('app','Katalog Perkriteria')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= yii::t('app',$Berdasarkan) ?> <br>
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
	<?php if ($kriterias == 'bahan_pustaka'): ?>
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Bahan Pustaka')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah')?>
						</td>
					</tr>

					<?php $i = 1; ?>
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
								<?= $TableLaporan['BahanPustaka'] ?>
							</td>
							<td>
								<?= $TableLaporan['Jumlah'] ?>
							</td>
						</tr>
						<?php $totalJumlahExemplar = $totalJumlahExemplar + $TableLaporan['Jumlah']  ?>
						<?php $i++ ?>
					<?php endforeach ?>

					<tr>
						<td colspan="3" style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahExemplar  ?>
						</td>
					</tr>

				</table>
	
	<?php elseif ($kriterias == 'kataloger'): ?>
				<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Periode')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kataloger')?> 
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah')?>
						</td>
					</tr>

					<?php $i = 1; ?>
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
								<?= $TableLaporan['Kataloger'] ?>
							</td>
							<td>
								<?= $TableLaporan['Jumlah'] ?>
							</td>
						</tr>
						<?php $totalJumlahExemplar = $totalJumlahExemplar + $TableLaporan['Jumlah']  ?>
						<?php $i++ ?>
					<?php endforeach ?>

					<tr>
						<td colspan="3" style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahExemplar  ?>
						</td>
					</tr>
				</table>

	<?php elseif ($kriterias == 'subjek' || $kriterias == 'no_klas'): ?>
				<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal')?>
						</td>
						<?php if($kriterias == 'subjek'):?>
								<td style="font-weight: bold;">
									<?= yii::t('app','Subjek')?>
								</td>
						<?php endif ?>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kelas Besar')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Judul')?> 
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Eksemplar')?>
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
							<?php if($kriterias == 'subjek'):?>
									<td>
										<?= $TableLaporan['sub'] ?>
									</td>
							<?php endif?>
							<td>
								<?= $TableLaporan['kelas'] ?>
							</td>
							<td>
								<?= $TableLaporan['CountJudul'] ?>
							</td>
							<td>
								<?= $TableLaporan['Jumlah'] ?>
							</td>
						</tr>
						<?php $totalJumlahJudul = $totalJumlahJudul + $TableLaporan['CountJudul']  ?>
						<?php $totalJumlahExemplar = $totalJumlahExemplar + $TableLaporan['Jumlah']  ?>
						<?php $i++ ?>
					<?php endforeach ?>

					<tr>
						<td <?php if($kriterias == 'subjek'){echo 'colspan="4"';}else{echo 'colspan="3"';}?> style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahJudul  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahExemplar  ?>
						</td>
					</tr>
				</table>

	<?php elseif (($kriterias == 'judul') || ($kriterias == 'location')): ?>
				<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app',$this->context->getRealNameKriteria($kriterias)); ?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah ')?>
						</td>
					</tr>

					<?php $i = 1; ?>
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
								<?= $TableLaporan['Kataloger'] ?>
							</td>
							<td>
								<?= $TableLaporan['Jumlah'] ?>
							</td>
						</tr>
						<?php $totalJumlahExemplar = $totalJumlahExemplar + $TableLaporan['Jumlah']  ?>
						<?php $i++ ?>
					<?php endforeach ?>

					<tr>
						<td colspan="3" style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahExemplar  ?>
						</td>
					</tr>

				</table>
			<?php endif ?>		
<!--<?php print_r($TableLaporan); ?>-->

	</center>
</div>