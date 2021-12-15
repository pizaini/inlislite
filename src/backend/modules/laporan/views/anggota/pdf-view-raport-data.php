<?php 

// echo $sql;
// $Berdasarkan = '';
// $Berdasarkan .= implode($_POST[implode($_POST['kriterias'])]);
// var_dump($Berdasarkan);
// die;

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?> <br /> <?= yii::t('app','Laporan Siswa')?> <?= $LaporanPeriode2 ?> <br /><?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan?>
		<!-- <?php print_r($TableLaporan); ?>-->
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
							<?= yii::t('app','Nama Kelas')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama Anggota')?>
						</td>
						<td style="font-weight: bold; width: 100px; ">
							<?= yii::t('app','Judul')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Pengarang')?>
						</td>
						<td style="font-weight: bold; width: 200px;">
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
								<?= $TableLaporan['kelas'] ?>
							</td>
							<td>
								<?= $TableLaporan['nomor'] ?>
							</td>
							<td>
								<?= $TableLaporan['nama'] ?>
							</td>
							<td>
								<?= $TableLaporan['judul'] ?>
							</td>
							<td>
								<?= $TableLaporan['pengarang'] ?>
							</td>
							<td>
								<?= $TableLaporan['penerbit'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>

		</table>
	</center>
</div>