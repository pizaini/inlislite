<?php 

// echo $sql;
// echo "<pre>";
// var_dump($TableLaporan);
// echo "</pre>";

?>

<div class="panel panel-default panel-body" style="font-family: times new roman; border: none; margin: 40; margin-top: 0px;">

	<center style="text-align: center; font-weight: bold;">
		<p style="text-align: center; font-size: 14px">
			Accession List (<?= yii::t('app','Daftar Koleksi Tambahan')?>) <?= $LaporanPeriode ?> <br/><?= yii::t('app','Berdasarkan')?> <?= yii::t('app',$Berdasarkan) ?><br>
						
		</p>
	</center>

	<center style="text-align: left; font-size: 11px">
		<ol>
		<?php foreach ($TableLaporan as $key => $value): ?>
			<li style="margin-bottom: 5px">
				<?= $value['AccessionList'] ?>.  

			</li>	
		<?php endforeach ?>	

		<?php // print_r($TableLaporan) ?>
		</ol>
	</center>
</div>