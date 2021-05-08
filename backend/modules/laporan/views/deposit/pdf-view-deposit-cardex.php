<?php 

// echo "<pre>";
// echo print_r($sql);
// echo "</pre>";die;

// var_dump($TableLaporan);
// $kriterias = implode($_POST['nama_siswa']);
// $name = ($this->context->getRealNameKriteria($kriterias));
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >
	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Laporan Cardex ')?><br/><?= $LaporanPeriode2 ?>
			
		</p>
	</center>
	<center style="text-align: center; font-size: 11px">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success" >
						<th style="font-weight: bold; text-align: center;">
							No.
						</th>
						<!-- <td style="font-weight: bold; text-align: center;">
							<?//= yii::t('app','Tanggal')?>
						</td> -->
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Tanggal')?>
						</th>
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Nomor Edisi Serial')?>
						</th>
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Judul')?>
						</th>
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Penerbit')?>
						</th>
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Alamat')?>
						</th>
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Tanggal Terima')?>
						</th>
						<th style="font-weight: bold; text-align: center;">
							<?= yii::t('app','Jumlah Eksemplar')?>
						</th>
					</tr>
					<?php $i = 1; ?>
					<?php $JumlahGagal = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['tanggal'] ?>
							</td>
							<td>
								<?= $TableLaporan['eds_serial'] ?>
							</td>
							<td style="text-align: left;">
								<?= $TableLaporan['judul'] ?>
							</td>
							<td>
								<?= $TableLaporan['penerbit'] ?>
							</td>
							<td>
								<?= $TableLaporan['alamat'] ?>
							</td>
							<td>
								<?= $TableLaporan['tgl_penerimaan'] ?>
							</td>
							<td>
								<?= $TableLaporan['jum_eks'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>
				</table>
<!--<?php print_r($TableLaporan); ?>-->

	</center>
</div>