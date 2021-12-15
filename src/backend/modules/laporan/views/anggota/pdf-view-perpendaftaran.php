<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";
$kriterias = implode($_POST['kriterias']);
$name = ($this->context->getRealNameKriteria($kriterias));
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?> <br><?= yii::t('app','Anggota Per Pendaftaraan')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?> <br>
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold; text-align: center;">
							No.
						</td>
						<td style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Tanggal')?> 
						</td>
						<?php if (sizeof($_POST["kriterias"]) !=1) {
					    }else if 
					    ($kriterias == $kriterias){ 
					    echo '<td style="font-weight: bold; text-align: center;">'.yii::t('app',$name).'</td>'; } 
					    ?>
						<th style="font-weight: bold; text-align: center;">
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
							<?php if (sizeof($_POST["kriterias"]) !=1) {
						    }else if 
						    ($kriterias == $kriterias){ 
						    echo '<td>'.$TableLaporan['subjek'].'</td>'; } 
						    ?>
							<td>
								<?= $TableLaporan['Jumlah'] ?>
							</td>
						</tr>
						<?php $totalJumlahExemplar = $totalJumlahExemplar + $TableLaporan['Jumlah']  ?>
						<?php $i++ ?>
					<?php endforeach ?>

					<tr>
						<td <?php if (sizeof($_POST['kriterias']) !=1){echo 'colspan="2"';} else {echo 'colspan="3"';}?> style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahExemplar  ?>
						</td>
					</tr>

				</table>
<!--<?php print_r($TableLaporan); ?>-->

	</center>
</div>