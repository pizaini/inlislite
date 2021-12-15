<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";
$kriterias = implode($_POST['kriterias']);
$test = ($this->context->getRealNameKriteria($kriterias));
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin: 40; margin-top: 0px;">


	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?> <br> <?= yii::t('app','Usulan Koleksi')?> <?= $LaporanPeriode2 ?><br> <?= yii::t('app','Berdasarkan')?> <?= yii::t('app',$Berdasarkan) ?>
			
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
				<?php if (sizeof($_POST['kriterias']) !=1) {
						}else
						{ echo '<td style="font-weight: bold; width: 120px;">'.yii::t('app',$test).' </td>'; }?>
				<td style="font-weight: bold;">
					<?= yii::t('app','Jumlah Judul Diusulkan')?>
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
					<?php if (sizeof($_POST['kriterias']) !=1) {
							}else
							{ echo '<td style="font-weight: bold; width: 120px;">'.$TableLaporan['Subjek'].' </td>'; }?>
					<td>
						<?= $TableLaporan['CountJudul'] ?>
					</td>
				</tr>
				<?php $totalJumlahJudul = $totalJumlahJudul + $TableLaporan['CountJudul']  ?>
				<?php $i++ ?>
			<?php endforeach ?>

			<tr>
				<td <?php if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}?> style="font-weight: bold;">
					Total
				</td>
				<td style="font-weight: bold;">
					<?= $totalJumlahJudul  ?>
				</td>
			</tr>

		</table>

	</center>
</div>