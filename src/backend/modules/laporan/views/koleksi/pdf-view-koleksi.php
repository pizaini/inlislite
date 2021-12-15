<?php 

// die;
// echo "<pre>";
// echo $sql;
// var_dump($TableLaporan);
// echo "</pre>";
$kriterias = implode($_POST['kriterias']);
$test = ($this->context->getRealNameKriteria($kriterias));
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >
	
	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">		
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?> <br> <?= yii::t('app','Pengadaan Koleksi')?> <?= $LaporanPeriode2 ?> <br/><?= yii::t('app','Berdasarkan')?> <?= yii::t('app',$Berdasarkan) ?>
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kategori')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jumlah Judul')?>
						</td>
						<td style="font-weight: bold; width: 90px; ">
							<?= yii::t('app','Jumlah Eksemplar')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<?php $totalJumlahEksemplar = 0; ?>
					<?php $totalJumlahJudul = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['Periode'] ?>
							</td>
							<td>
								<?= $TableLaporan['Subjek'] ?>
							</td>
							<td>
								<?= $TableLaporan['JumlahJudul'] ?>
							</td>
							<td>
								<?= $TableLaporan['CountEksemplar'] ?>
							</td>
						</tr>
						<?php $totalJumlahEksemplar = $totalJumlahEksemplar + $TableLaporan['CountEksemplar']  ?>
						<?php $totalJumlahJudul = $totalJumlahJudul + $TableLaporan['JumlahJudul']  ?>
						<?php $i++ ?>
					<?php endforeach ?>
					<tr>
						<td colspan="3" style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahJudul  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahEksemplar  ?>
						</td>
					</tr>

				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>