<?php 

use common\models\MasterUangJaminan;
use common\models\MasterJenisIdentitas;

 ?>

<div id="section-to-print">
 	<!-- Table Detail Peminjaman -->
    <div class="table-responsive" id="pilihLocker" > <!-- hidden="hidden" -->
        <table class="table table-condensed table-bordered table-striped table-hover request-table" style="table-layout: fixed;">
            <tbody>
            	<tr>
            		<td class="col-sm-4"><?= Yii::t('app','No.Peminjaman')?></td>
            		<td class="col-sm-8" id="noPeminjaman"><?php echo $model->No_pinjaman; ?></td>
            	</tr>
            	<tr>
            		<td><?= Yii::t('app','No.Anggota')?></td>
            		<td><?php echo $model->no_member; ?> </td>
            	</tr>

				<!-- Data Members atau Memberguesses -->
            	<tr>
            		<td><?= Yii::t('app','Nama')?></td>
            		<td><?php echo $data["nama"]; ?> </td>
            	</tr>
<!--             	<tr>
            		<td>Jenis Kelamin</td>
            		<td><?php echo $data["jenisKelamin"]; ?></td>
            	</tr>

 -->

                <?php if ($model->jenis_jaminan): ?>
            	<tr>
            		<td><?= Yii::t('app','Jenis Jaminan')?></td>
            		<td><?php echo $model->jenis_jaminan; ?></td>
            	</tr>
            	<tr>
	            	<?php if ($model->jenis_jaminan == 'Kartu Identitas') {
	            		$jenisIdentitas = MasterJenisIdentitas::findOne($model->id_jamin_idt);
	            		echo "<td>".yii::t('app','Nomor Identitas - ').$jenisIdentitas->Nama."</td>";
	            		echo '<td>'.$model->no_identitas.'</td>';
	            	} else if ($model->jenis_jaminan == 'Uang') {
	            		$uangJaminan = MasterUangJaminan::findOne($model->id_jamin_uang) ;
	            		echo "<td>".yii::t('app','Nominal Uang Jaminan')."</td>";
	            		echo '<td>'.$uangJaminan->No.' ( '.$uangJaminan->Name.' ) </td>';
	            	}
	            	?>
            	</tr>
                <?php endif ?>
            	
                <tr>
            		<td><?= Yii::t('app','Loker')?></td>
            		<td><?php echo $data["lockers"]; ?></td>
            	</tr>
            	<tr>
            		<td><?= Yii::t('app','Tgl.Pinjam')?></td>
            		<td><?php echo $model->tanggal_pinjam; ?></td>
            	</tr>
            </tbody>
        </table>
    </div>



	<?php 
	// DetailView::widget([
 //            'model' => $model,
            
 //        'attributes' => [
 //            'No_pinjaman',
 //            'no_member',
 //            'no_identitas',
 //            'jenis_jaminan',
 //            'id_jamin_idt',
 //            'id_jamin_uang',
 //            'loker_id',
 //            'tanggal_pinjam',
 //            'tanggal_kembali',
 //        ],
 //    ]) 
    ?>
</div>