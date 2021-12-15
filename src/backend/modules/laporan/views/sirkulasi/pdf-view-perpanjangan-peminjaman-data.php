<?php 

//echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?> <br /> <?= yii::t('app','Sirkulasi Peminjaman')?> <?= $LaporanPeriode2 ?><br /> <?= yii::t('app','Berdasarkan')?>  <?= $Berdasarkan?>
		<!-- <?php print_r($TableLaporan); ?>-->
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
				<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
							<tr class="success" >
								<td style="font-weight: bold; width: 50px;">
									No.
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Tanggal Perpanjangan')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Tanggal Jatuh Tempo')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Tanggal Dikembalikan')?>
								</td>
								<td style="font-weight: bold; width: 100px;">
									<?= yii::t('app','Nomor Induk')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Data Bibliografis')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Nomor Anggota')?>
								</td>
								<td style="font-weight: bold;">
									<?= yii::t('app','Nama Anggota')?>
								</td>
								<td style="font-weight: bold; width: 100px;">
									<?= yii::t('app','Petugas Perpanjangan')?>
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
										<?= $TableLaporan['TanggalPerpanjangan'] ?>
									</td>
									<td>
										<?= $TableLaporan['TanggalJatuhTempo'] ?>
									</td>
									<td>
										<?= $TableLaporan['TanggalDikembalikan'] ?>
									</td>
									<td>
										<?= $TableLaporan['no_induk'] ?>
									</td>
									<td>
										<?= $TableLaporan['DataBib'] ?>
									</td>
									<td>
										<?= $TableLaporan['NoAnggota'] ?>
									</td>
									<td>
										<?= $TableLaporan['NamaAnggota'] ?>
									</td>
									<td>
										<?= $TableLaporan['Petugas'] ?>
									</td>
								</tr>
								<?php $i++ ?>
							<?php endforeach ?>

				</table>
	</center>
</div>