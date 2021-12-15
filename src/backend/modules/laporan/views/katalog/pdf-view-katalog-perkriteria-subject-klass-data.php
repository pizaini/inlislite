<?php

// echo $sql;
// $kriterias = implode($_POST['kriterias']);
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			Laporan Detail Data <?= $LaporanPeriode ?>  <br>Katalog Perkriteria <?= $LaporanPeriode2 ?><br /><?= yii::t('app','Berdasarkan')?> <?= yii::t('app',$Berdasarkan) ?>
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
							<tr class="success" >
								<td style="font-weight: bold; width: 50px;">
									No.
								</td>
								<td style="font-weight: bold;">
									Tanggal
								</td>
								<?php if($kriterias != 'subjek'):?>
								<td style="font-weight: bold;">
									<!-- ?php if ($kriterias != 'no_klas'){echo "Subjek";}else{echo "Kelas Besar";} ?-->
									Klas DCC
								</td>
								<?php endif ?>
								<td style="font-weight: bold;">
									Subjek
								</td>
								<td style="font-weight: bold;">
									BIB-ID
								</td>
								<td style="font-weight: bold;">
									Judul
								</td>
								<td style="font-weight: bold;">
									Penerbit 
								</td>
								<td style="font-weight: bold;">
									Deskripsi Fisik
								</td>
								<td style="font-weight: bold;">
									Jumlah Eksemplar
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
									<?php if($kriterias != 'subjek'):?>
									<td>
										<?= $TableLaporan['klas'] ?>
									</td>
									<?php endif?>
									<td>
										<?= $TableLaporan['subj'] ?>
									</td>
									<td>
										<?= $TableLaporan['BIBID'] ?>
									</td>
									<td>
										<?= $TableLaporan['Judul'] ?>
									</td>
									<td>
										<?= $TableLaporan['publisher'] ?>
									</td>
									<td>
										<?= $TableLaporan['deskripsi_fisik'] ?>
									</td>
									<td>
										<?= $TableLaporan['jml_eks'] ?>
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
			Jumlah data <?= $TableLaporan['katalog'] ?> Export untuk melihat seluruh data
			
		</p>
		<?php }?>
	</center>
	</center>
</div>
