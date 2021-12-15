<?php
// echo '<pre>';
// echo $sql;
// echo '</pre>';
?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border:0px; margin:40px; margin-top:0px;" >

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			<?= yii::t('app','Daftar Penerbit Wilayah ')?><?= $Berdasarkan ?><br>
			
		</p>
	</center>

	<center style="text-align: center; font-size: 11px;">	
		<table width="100%" border="1" class="table table-bordered" style="text-align: center; border-collapse: collapse; border: 1px solid black; font-size: 13px; font-family: times new roman;">
					<tr class="success">
						<td style="font-weight: bold;">
							No.
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Nama Penerbit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Alamat Penerbit')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Kota')?>
						</td>
						<td style="font-weight: bold;">
							<?= yii::t('app','Telpon')?>
						</td>
					</tr>
					<?php $i = 1; ?>
					<?php $JumlahGagal = 0; ?>
					<?php foreach ($TableLaporan as $TableLaporan): ?>
						<tr>
							<td>
								<?= $i ?>
							</td>
							<td>
								<?= $TableLaporan['nama_pen'] ?>
							</td>
							<td>
								<?= $TableLaporan['alamat'] ?>
							</td>
							<td>
								<?= $TableLaporan['kota'] ?>
							</td>
							<td>
								<?= $TableLaporan['tlp'] ?>
							</td>
						</tr>
						<?php $i++ ?>
					<?php endforeach ?>
				</table>

<!-- <?php print_r($TableLaporan); ?> -->


	</center>
</div>