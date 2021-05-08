<?php

use inliscore\adminlte\widgets\InfoBox;
use dosamigos\chartjs\ChartJs;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'INLISlite v3';
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = (Yii::$app->config->get('KodeRegistrasi') == '') ? ['label' => yii::t('app','Penggunaan aplikasi ini belum diregistrasikan. Klik di sini untuk informasi lebih lengkap'), 'url' => Url::to(['setting/umum/registrasi/'])] : ['label' => yii::t('app','Penggunaan aplikasi ini telah teregistrasi untuk ').Yii::$app->config->get('NamaPerpustakaan')] ;


?>
<div class="site-index">
   	<div class="row">
		<div class="col-md-12">
			<h4 class="page-header">
				<?= yii::t('app','Jenis Perpustakaan : ')?> <?php 
				$jenisPerpustakaan = \common\models\JenisPerpustakaan::findOne(Yii::$app->config->get('JenisPerpustakaan'));
				echo $jenisPerpustakaan->Name ?>
			</h4> 

			<?php 
				// if(Yii::$app->config->get('KodeRegistrasi') == ''){
				// 	echo '<div class="col-md-12">
				// 			Penggunaan aplikasi ini belum diregistrasikan. <a href="'.Url::to(['setting/umum/registrasi/']).'">Klik di sini untuk informasi lebih lengkap</a>
				//         </div>';
					
				// }else{
				// 	echo '<div class="col-md-12">
				// 			<i class="icon fa fa-check"></i> Penggunaan aplikasi ini telah teregistrasi untuk '.Yii::$app->config->get('NamaPerpustakaan').'
				// 		</div>';
				// }
			?>
			
			
			<div class="col-md-3 col-sm-6 col-xs-12">
		
				<?php
				$count = (new \yii\db\Query())
					->from('catalogs')
					->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_BROWN,
								  'head'=>number_format($count),
								  'text'=>Yii::t('app','Judul'),
								  'icon'=>'fa fa-book',
								  'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  'footer_link'=>Url::to(['pengkatalogan/katalog'])
							  ]);?>
			</div>

			<div class="col-md-3 col-sm-6 col-xs-12">

				<?php
				$count = (new \yii\db\Query())
					->from('catalogfiles')
					->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_GREEN,
								  'head'=>number_format($count),
								  'text'=>Yii::t('app','Kontent Digital'),
								  'icon'=>'fa fa-book',
								  'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  'footer_link'=>Url::to(['pengkatalogan/katalog-konten-digital'])
							  ]);?>
			</div>
		
			<div class="col-md-3 col-sm-6 col-xs-12">
				<?php
				$count = (new \yii\db\Query())
					->from('collections')
					->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_YEL,
								  'head'=>number_format($count),
								  'text'=>Yii::t('app','Eksemplar'),
								  'icon'=>'fa fa-book',
								  'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  'footer_link'=>Url::to(['akuisisi/koleksi'])
							  ]);?>
			</div>

			<div class="col-md-3 col-sm-6 col-xs-12">
			<!-- ANGGOTA -->
				<?php
				$count = (new \yii\db\Query())
					->from('members')
					->count();
				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_AQUA,
								  'head'=>number_format($count),
								  'text'=>Yii::t('app','Anggota'),
								  'icon'=>'fa fa-users',
								  'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  'footer_link'=>Url::to(['member/member/index'])
							  ]);?>
			</div>
		  
		</div>

		<!-- KUNJUNGAN -->
		<div class="col-md-12">
			<div class="col-md-12">
				<!-- KUNJUNGAN -->
							   <!-- Kunjungan Anggota -->
					<?php /*echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_AQUA,
								  'head'=>\common\models\Memberguesses::find()->count(),
								  'text'=>'Kunjungan Anggota',
								  'icon'=>'fa fa-users',
								  'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  'footer_link'=>'#'
							  ]);*/?>
				<?php

				$anggota = (new \yii\db\Query())
					->from('memberguesses')
					->where(['not', ['NoAnggota' => null]])
					->count();

				$nonAnggota = (new \yii\db\Query())
					->from('memberguesses')
					->where(['NoAnggota' => null])
					->count();


					//echo $anggota;
				?>
					<!-- Kunjungan NonAnggota -->
					
					
					<?php

					$anggota = Yii::$app->db->createCommand("select  count(1) from memberguesses where NoANggota is not null;")->queryScalar();
					$nonanggota = Yii::$app->db->createCommand("select count(1) from memberguesses where NoANggota is null; ")->queryScalar();
					 echo \dosamigos\highcharts\HighCharts::widget([
					'clientOptions' => [
						'chart' => [
								'type' => 'pie',
								'options3d'=>[
									'enabled'=> true,
									'alpha'=> 65,
									'beta'=> 0
								]
						],
						'plotOptions' => [
							'allowPointSelect'=> true,
							'cursor' => 'pointer',
							'depth'=> 35,
							'series'=> [
								'dataLabels'=> [
									'enabled'=> true,
									'format'=> '{point.name}: {point.y} '.yii::t('app','Orang').'' 
								]
							]
						],
						'title' => [
							 'text' => yii::t('app','Data Kunjungan')
							 ],
						'yAxis'=> [
								'title'=> [
									'text'=> 'Title of the y-axis'
								],
								'min'=> 0 // this sets minimum values of y to 0
							],
						'series' => [
							[ // new opening bracket
								'data' => [
									[
									  'name'=> yii::t('app','Anggota'),
									  'y'=> intval($anggota),
									  
									],
									[ 'name'=> yii::t('app','Non Anggota'),
									  'y'=> intval($nonanggota),
									]

								   
								],
							] 
						],
						/*'drilldown'=>[
							'series'=> [[
								'name'=> 'Microsoft Internet Explorer',
								'id'=> 'Microsoft Internet Explorer',
								'data'=> [
									['v11.0', 24.13],
									['v8.0', 17.2],
									['v9.0', 8.11],
									['v10.0', 5.33],
									['v6.0', 1.06],
									['v7.0', 0.5]
								]
							]]
						]*/
						
						]
						]);?>
			</div>
		</div>
	</div>

<br/>
	<!-- SIRKULASI -->
	<div class="row">
		<div class="col-md-6">
			<h4 class="page-header"><?= yii::t('app','Koleksi Sedang dipinjam')?></h4>
			<div class="col-md-4 col-sm-6 col-xs-12">
				
				<!-- SIRKULASI -->
				
				<?php 
				// Peminjaman Judul
				//SELECT COUNT(*) FROM collectionloanitems 
				//INNER JOIN Collections ON 
				//collectionloanitems.Collection_id=collections.id 
				//WHERE collectionloanitems.LoanStatus = 'Loan' 
				//AND collectionloanitems.Member_id IS NOT NULL 
				//GROUP BY Catalog_id

				$query = (new \yii\db\Query())
					->from('collectionloanitems')
					->select('collectionloanitems.ID')
					->where(['collectionloanitems.LoanStatus'=>'Loan',])
					->andWhere(['not', ['collectionloanitems.Member_id' => null]])
					->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
					->groupBy('Catalog_id');



				/*$query = \common\models\Collectionloanitems::find()
						   //->select(['COUNT(*) as jumlah'])
						   ->where(['collectionloanitems.LoanStatus'=>'Loan',])
						   ->andWhere(['not', ['collectionloanitems.Member_id' => null]])
						   ->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
						   ->groupBy('Catalog_id');*/
				$countPeminjamanLoanJudul = $query->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_BROWN,
								  'head'=>number_format($countPeminjamanLoanJudul),
								  'text'=>yii::t('app','Judul'),
								  'icon'=>'fa fa-book',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div>

			<div class="col-md-4 col-sm-6 col-xs-12">
				 <!-- EKSEMPLAR -->
				<?php 

				// Peminjaman Anggota
				//SELECT COUNT(*) FROM collectionloanitems 
				//WHERE collectionloanitems.LoanStatus = 'Loan' 
				//AND collectionloanitems.Member_id IS NOT NULL";

				$query = (new \yii\db\Query())
					->from('collectionloanitems')
					->select('collectionloanitems.ID')
					->where(['collectionloanitems.LoanStatus'=>'Loan',])
					->andWhere(['not', ['collectionloanitems.Member_id' => null]]);
					//->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
					//->groupBy('Catalog_id');

			  /*  $query = \common\models\Collectionloanitems::find()
						   //->select(['COUNT(*) as jumlah'])
						   ->where(['collectionloanitems.LoanStatus'=>'Loan',])
						   ->andWhere(['not', ['collectionloanitems.Member_id' => null]]);
						   //->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
						   //->groupBy('Member_id');*/
				$countPeminjamanLoan = $query->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_YEL,
								  'head'=>number_format($countPeminjamanLoan),
								  'text'=>Yii::t('app','Eksemplar'),
								  'icon'=>'fa fa-book',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div>
			
			<div class="col-md-4 col-sm-6 col-xs-12">
				 <!-- SIRKULASI -->
				<?php 

				// Peminjaman Anggota
				//SELECT COUNT(*) FROM collectionloanitems 
				//WHERE collectionloanitems.LoanStatus = 'Loan' 
				//AND collectionloanitems.Member_id IS NOT NULL 
				//GROUP BY collectionloanitems.Member_id


				$query = (new \yii\db\Query())
					->from('collectionloanitems')
					->select('collectionloanitems.ID')
					->where(['collectionloanitems.LoanStatus'=>'Loan',])
					->andWhere(['not', ['collectionloanitems.Member_id' => null]])
					//->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
					->groupBy('Member_id');

				/*$query = \common\models\Collectionloanitems::find()
						   //->select(['COUNT(*) as jumlah'])
						   ->where(['collectionloanitems.LoanStatus'=>'Loan',])
						   ->andWhere(['not', ['collectionloanitems.Member_id' => null]])
						   //->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
						   ->groupBy('Member_id');*/
				$countPeminjamanAnggotaLoan = $query->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_AQUA,
								  'head'=>number_format($countPeminjamanAnggotaLoan),
								  'text'=>yii::t('app','Anggota'),
								  'icon'=>'fa fa-users',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div>
		</div>
		
		<div class="col-md-6">
			<h4 class="page-header"><?= yii::t('app','Koleksi Telah dikembalikan')?></h4>
			<div class="col-md-4 col-sm-6 col-xs-12">
				
				<!-- SIRKULASI -->
				
				<?php 
				// Peminjaman Judul
				//SELECT COUNT(*) FROM collectionloanitems 
				//INNER JOIN Collections ON collectionloanitems.Collection_id=collections.id 
				//WHERE collectionloanitems.LoanStatus = 'Return' 
				//AND collectionloanitems.Member_id IS NOT NULL 
				//GROUP BY Catalog_id
				
				/*$query = \common\models\Collectionloanitems::find()
						   //->select(['COUNT(*) as jumlah'])
						   ->where(['collectionloanitems.LoanStatus'=>'Return',])
						   ->andWhere(['not', ['collectionloanitems.Member_id' => null]])
						   ->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
						   ->groupBy('Catalog_id');*/

				$query = (new \yii\db\Query())
					->from('collectionloanitems')
					->select('collectionloanitems.ID')
					->where(['collectionloanitems.LoanStatus'=>'Return',])
					->andWhere(['not', ['collectionloanitems.Member_id' => null]])
					->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
					->groupBy('Catalog_id');

				$countPeminjamanReturnJudul = $query->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_BROWN,
								  'head'=>number_format($countPeminjamanReturnJudul),
								  'text'=>yii::t('app','Judul'),
								  'icon'=>'fa fa-book',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				 <!-- EKSEMPLAR -->
				<?php 

				// Peminjaman Anggota
				//SELECT COUNT(*) FROM collectionloanitems 
				//WHERE collectionloanitems.LoanStatus = 'Return' 
				//AND collectionloanitems.Member_id IS NOT NULL";

				
				/*$query = \common\models\Collectionloanitems::find()
						   //->select(['COUNT(*) as jumlah'])
						   ->where(['collectionloanitems.LoanStatus'=>'Return',])
						   ->andWhere(['not', ['collectionloanitems.Member_id' => null]]);
						   //->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
						   //->groupBy('Member_id');*/

				$query = (new \yii\db\Query())
					->from('collectionloanitems')
					->select('collectionloanitems.ID')
					->where(['collectionloanitems.LoanStatus'=>'Return',])
					->andWhere(['not', ['collectionloanitems.Member_id' => null]]);
					//->andWhere(['not', ['collectionloanitems.Member_id' => null]])
					//->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
					//->groupBy('Catalog_id');

				$countPeminjamanReturn = $query->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_YEL,
								  'head'=>number_format($countPeminjamanReturn),
								  'text'=>Yii::t('app','Eksemplar'),
								  'icon'=>'fa fa-book',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				 <!-- SIRKULASI -->
				<?php 

				//Peminjaman Anggota
				//SELECT COUNT(*) FROM collectionloanitems 
				//WHERE collectionloanitems.LoanStatus = 'Return' 
				//AND collectionloanitems.Member_id IS NOT NULL 
				//GROUP BY collectionloanitems.Member_id
				
				/*$query = \common\models\Collectionloanitems::find()
						   //->select(['COUNT(*) as jumlah'])
						   ->where(['collectionloanitems.LoanStatus'=>'Return',])
						   ->andWhere(['not', ['collectionloanitems.Member_id' => null]])
						   //->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
						   ->groupBy('Member_id');*/

				$query = (new \yii\db\Query())
					->from('collectionloanitems')
					->select('collectionloanitems.ID')
					->where(['collectionloanitems.LoanStatus'=>'Return',])
					->andWhere(['not', ['collectionloanitems.Member_id' => null]])
					//->andWhere(['not', ['collectionloanitems.Member_id' => null]])
					//->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
					->groupBy('Member_id');

				$countPeminjamanAnggotaReturn = $query->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_AQUA,
								  'head'=>number_format($countPeminjamanAnggotaReturn),
								  'text'=>yii::t('app','Anggota'),
								  'icon'=>'fa fa-users',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div>
		</div>

		<!-- Koleksi Baca Ditempat -->
		<div class="col-md-12">
			<h4 class="page-header"><?= yii::t('app','Koleksi Dibaca Ditempat')?></h4>
			<!-- <div class="col-md-12 col-sm-6 col-xs-12"> -->
 			<div class="col-md-3 col-sm-6 col-xs-12">
				<?php 
				// $query = (new \yii\db\Query())
				// 	->from('collectionloanitems')
				// 	->select('collectionloanitems.ID')
				// 	->where(['collectionloanitems.LoanStatus'=>'Return',])
				// 	->andWhere(['not', ['collectionloanitems.Member_id' => null]])
				// 	->join('INNER JOIN', \common\models\Collections::tableName(), 'collectionloanitems.Collection_id=collections.ID')
				// 	->groupBy('Catalog_id');

				$query = (new \yii\db\Query())
					->from('bacaditempat')
					->select('bacaditempat.ID')
					->join('INNER JOIN', \common\models\Collections::tableName(), 'bacaditempat.collection_id=collections.ID')
					->groupBy('Catalog_id');

				$countPeminjamanReturnJudul = $query->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_BROWN,
								  'head'=>number_format($countPeminjamanReturnJudul),
								  'text'=>yii::t('app','Judul'),
								  'icon'=>'fa fa-book',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				 <!-- EKSEMPLAR -->
				<?php 
				$query = (new \yii\db\Query())
					->from('bacaditempat')
					->select('bacaditempat.ID');
				$countPeminjamanReturn = $query->count();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_YEL,
								  'head'=>number_format($countPeminjamanReturn),
								  'text'=>yii::t('app','Eksemplar'),
								  'icon'=>'fa fa-book',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div>
				
			<!-- Jumlah Anggota Bacaditempat -->
			<div class="col-md-3 col-sm-6 col-xs-12">
				<?php 


				$query = (new \yii\db\Query())
					->from('bacaditempat')
					->select('bacaditempat.ID')
					->where(['not', ['bacaditempat.Member_id' => null]])
					->groupBy('bacaditempat.Member_id');

				$anggotabacaditempat = $query->count();


				// $anggotabacaditempat = Yii::$app->db->createCommand("select count(1) from bacaditempat where Member_id is not null group by Member_id;")->queryScalar();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_AQUA,
								  'head'=>number_format($anggotabacaditempat),
								  'text'=>Yii::t('app','Anggota'),
								  'icon'=>'fa fa-users',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div><!-- /Jumlah Anggota Bacaditempat -->

			<!-- Jumlah NonAnggota Bacaditempat -->
			<div class="col-md-3 col-sm-6 col-xs-12">
				<?php 

				$query = (new \yii\db\Query())
					->from('bacaditempat')
					->select('bacaditempat.ID')
					->where(['bacaditempat.Member_id' => null])
					->groupBy('bacaditempat.NoPengunjung');

				$nonanggotabacaditempat = $query->count();

				// $nonanggotabacaditempat = Yii::$app->db->createCommand("select count(1) from bacaditempat where Member_id is null group by NoPengunjung;")->queryScalar();

				echo \inliscore\adminlte\widgets\SmallBox::widget([
								  'type'=>\inliscore\adminlte\widgets\SmallBox::TYPE_MAR,
								  'head'=>number_format($nonanggotabacaditempat),
								  'text'=>Yii::t('app','Non Anggota'),
								  'icon'=>'fa fa-users',
								  // 'footer'=>'Detail <i class="fa fa-hand-o-right"></i>',
								  // 'footer_link'=>'#'
							  ]);
				?>
			</div><!-- /Jumlah NonAnggota Bacaditempat -->

					
			
			<!-- </div> -->
		</div><!-- Koleksi baca ditempat statistik -->
	</div>
</div>
<div class="modal fade" id="modal-registrasi">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?= yii::t('app','Form Registrasi Aplikasi')?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="formRegis">
          <div class="box-body">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label"><?= yii::t('app','Nama Perpustakaan')?></label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="namaPerpus" name="namaPerpus" placeholder="Nama Perpustakaan" readonly="readonly">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label"><?= yii::t('app','Kode Registrasi')?></label>

              <div class="col-sm-10">
                <input type="text" class="form-control" id="kodeRegis" name="kodeRegis" placeholder="Kode Registrasi" readonly="readonly">
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">><?= yii::t('app','Batal')?></button>
            <button type="button" class="btn btn-info pull-right" onclick="proses();">><?= yii::t('app','Proses')?></button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script type="text/javascript">
	function registrasi(){
		var d = new Date();
		var n = d.getFullYear();
		var m = d.getMonth() + 1;
		var date = d.getDate();
		var time = d.getHours()+d.getMinutes()+d.getMilliseconds();
		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		for (var i = 0; i < 15; i++)
		   text += possible.charAt(Math.floor(Math.random() * possible.length));

		var nmPerpus = '<?php echo Yii::$app->config->get('NamaPerpustakaan'); ?>';
		var kodeRegis = text+'_'+n+m+date+time;
		$('#namaPerpus').val(nmPerpus);
		$('#kodeRegis').val(kodeRegis);
		$('#modal-registrasi').modal('show');
	}

	function proses(){
		$.ajax({
			url : '<?= Yii::$app->urlManager->createUrl(["/setting/umum/registrasi/proses"]) ?>',
			data : $('#formRegis').serialize(),
			type : 'POST',
			dataType : 'JSON',
			success : function(data){
				// console.log(data.status)
				if(data.status == 'TRUE'){
					swal(
					  'Berhasil!',
					  'Aplikasi Inlislite telah teregistrasi di Perpustakaan Nasional RI',
					  'success'
					)
					$('#modal-registrasi').modal('hide');
				}else{
					swal(
					  'Oops...',
					  'Gagal!',
					  'error'
					)
				}
				
			}
		});
	}
</script>