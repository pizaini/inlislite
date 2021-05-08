<?php 

// echo "<pre>";
// echo print_r($TableLaporan);
// echo "</pre>";

// var_dump($TableLaporan);
// $kriterias = implode($_POST['nama_siswa']);
// $name = ($this->context->getRealNameKriteria($kriterias));
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Frekuensi')?> <?= $LaporanPeriode ?> <br> <?= yii::t('app','Laporan Siswa')?> <?= $LaporanPeriode2 ?>  <br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?>
			
		</p>
	</center>
<table width="40%" border="0" style="border-collapse: collapse; font-size: 13px; font-family: times new roman;">
<tr>
<td width="25%" style="font-weight: bold;">
	<?= yii::t('app','Nama')?>
</td>
<td style="font-weight: bold;">
	:
</td>
<td width="70%" style="font-weight: bold;">
	<?= (sizeof($Nama) > 0 ? $Nama : ' - ' ); ?>
</td>
</tr>
<tr>
<td width="25%" style="font-weight: bold;">
	<?= yii::t('app','NISM')?>
</td>
<td style="font-weight: bold;">
	:
</td>
<td width="70%" style="font-weight: bold;">
	<?= (sizeof($No_angg) > 0 ? $No_angg : ' - ' ); ?>
</td>
</tr>
<tr>
<td width="25%" style="font-weight: bold;">
	<?= yii::t('app','Kelas')?>
</td>
<td style="font-weight: bold;">
	:
</td>
<td width="70%" style="font-weight: bold;">
	<?= (sizeof($Kelas) > 0 ? $Kelas : ' - ' ); ?>
</td>
</tr>

<tr>
<td colspan="4" width="25%" style="font-weight: bold;">
	<?= yii::t('app','Semester')?>
</td>
<td style="font-weight: bold;">
	:
</td>
<td width="70%" style="font-weight: bold;">
	 <!-- <?= $kunjCount; ?> -->
</td>
</tr>

<tr>
<td colspan="4" width="25%" style="font-weight: bold;">
	<?= yii::t('app','Jumlah Kunjungan')?>
</td>
<td style="font-weight: bold;">
	:
</td>
<td width="70%" style="font-weight: bold;">
	<?= $sirkCount; ?> <!-- <?= $kunjCount; ?> -->
</td>
</tr>
<!--<tr> 
<td colspan="4" width="25%" style="font-weight: bold;">
	<?//= yii::t('app','Sirkulasi Baca di Tempat dan Peminjaman')?>
</td>
<td style="font-weight: bold;">
	:
</td>
<td width="70%" style="font-weight: bold;">
	<?= $sirkCount; ?>
</td>
</tr> -->
</table>
<br />
	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold; text-align: center;">
							No.
						</td>
						<!-- <td style="font-weight: bold; text-align: center;">
							<?//= yii::t('app','Tanggal')?>
						</td> -->
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Klasifikasi Buku')?>
						</td>
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Baca Ditempat')?>
						</td>
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Jumlah Dipinjam')?>
						</td>
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Jumlah')?>
						</td>
					</tr>

					<?php $i = 1; ?>
					<?php $totalJumlahExemplar = 0; ?>
					<?php $totalJumlahExemplarJud = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<!-- <td>
								<?//= $TableLaporan['Periode'] ?>
							</td> -->
							<td>
								<?= $TableLaporan['subjek'] ?>
							</td>
							<td>
								<?= $TableLaporan['Jumlah_bca_dtmpat'] ?>
							</td>
							<td>
								<?= $TableLaporan['Jumlah_pinj'] ?>
							</td>
							<td>
								<?= $TableLaporan['Jumlah_bca_dtmpat'] + $TableLaporan['Jumlah_pinj'] ?>
							</td>
						</tr>
						<?php $totalJumlahBcaDtmpat = $totalJumlahBcaDtmpat + $TableLaporan['Jumlah_bca_dtmpat']  ?>
						<?php $totalJumlahPnjm = $totalJumlahPnjm + $TableLaporan['Jumlah_pinj']  ?>
						<?php $i++ ?>
					<?php endforeach ?>

					<tr>
						<td colspan="2" style="font-weight: bold;">
							Total
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahBcaDtmpat  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahPnjm  ?>
						</td>
						<td style="font-weight: bold;">
							<?= $totalJumlahBcaDtmpat + $totalJumlahPnjm  ?>
						</td>
					</tr>

				</table>
<!--<?php print_r($TableLaporan); ?>-->

	</center>
</div>