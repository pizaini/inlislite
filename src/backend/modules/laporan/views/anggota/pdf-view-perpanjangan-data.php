<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Detail Data')?> <?= $LaporanPeriode ?> <br /> <?= yii::t('app','Anggota Per Perpanjangan')?> <?= $LaporanPeriode2 ?> <br> <?= yii::t('app','Berdasarkan')?> <?= $Berdasarkan ?><br>
			<?php //print_r($TableLaporan) ?>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Perpanjangan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tanggal Akhir Berlaku')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jenis Kelamin')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Tempat & Tanggal Lahir')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Umur')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jenis Identitas')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nomor Identitas')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Alamat')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kabupaten / Kota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Provinsi')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Telepon')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Email')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jenis Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Pekerjaan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Pendidikan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Fakultas')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Jurusan')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kelas')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Lokasi Pinjam')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Status Anggota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Biaya Perpanjangan')?>
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
								<?= $TableLaporan['akhir_berlaku'] ?>
							</td>
							<td>
								<?= $TableLaporan['MemberNo'] ?>
							</td>
							<td>
								<?= $TableLaporan['Anggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['jenis_kelamin'] ?>
							</td>
							<td>
								<?= $TableLaporan['TTL'] ?>
							</td>
							<td>
								<?= $TableLaporan['umur'] ?>
							</td>
							<td>
								<?= $TableLaporan['jenis_identitas'] ?>
							</td>
							<td>
								<?= $TableLaporan['no_identitas'] ?>
							</td>
							<td>
								<?= $TableLaporan['alamat'] ?>
							</td>
							<td>
								<?= $TableLaporan['kab_kota'] ?>
							</td>
							<td>
								<?= $TableLaporan['provinsi'] ?>
							</td>
							<td>
								<?= $TableLaporan['telepon'] ?>
							</td>
							<td>
								<?= $TableLaporan['email'] ?>
							</td>
							<td>
								<?= $TableLaporan['jenis_anggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['pekerjaan'] ?>
							</td>
							<td>
								<?= $TableLaporan['pendidikan'] ?>
							</td>
							<td>
								<?= $TableLaporan['fakultas'] ?>
							</td>
							<td>
								<?= $TableLaporan['jurusan'] ?>
							</td>
							<td>
								<?= $TableLaporan['kelas'] ?>
							</td>
							<td>
								<?= $TableLaporan['lokasi_pinjam'] ?>
							</td>
							<td>
								<?= $TableLaporan['status_anggota'] ?>
							</td>
							<td>
								<?= $TableLaporan['Biaya'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>
		</table>

	<center style="text-align: left; font-weight: bold;">
		<?php if ($TableLaporan['member'] < 1000) {
		}else{?>
		<br/>
		<p style="text-align: left; font-size: 24px; font-family: arial">
			Jumlah data <?= $TableLaporan['member'] ?> Export untuk melihat seluruh data
			
		</p>
		<?php }?>
	</center>

	</center>
</div>