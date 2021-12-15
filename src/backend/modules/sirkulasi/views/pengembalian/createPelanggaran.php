<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

use common\widgets\AjaxButton;


use common\models\JenisPelanggaran;
use common\models\JenisDenda;

/**
 * @var yii\web\View $this
 * @var common\models\Collectionloanitems $model
 */
$this->title = Yii::t('app', 'Entri Pengembalian - Pelanggaran');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sirkulasi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php 
$form 			= ActiveForm::begin(
				    [
				    	'action'=> Url::to('simpan-pelanggaran'),
				        'type'=>ActiveForm::TYPE_HORIZONTAL,
				        'enableClientValidation' => true,
				        'formConfig' => [
				            'labelSpan' => '3',
				            //'deviceSize' => ActiveForm::SIZE_TINY,
				        ],
				    ]
			    );

$url2           = Url::to('hapus-item');
?>
<input type="hidden" value="<?= $for ?>" name="for">
<div class="page-header">
	<h3>
		&nbsp;
		<div class="pull-left">
			<?php
			echo '<p>';
			echo Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> '.Yii::t('app', 'Proses'), ['class' => 'btn btn-success','id'=>'btn-simpan']);
			echo '&nbsp;' .Html::a('<span class="glyphicon glyphicon-remove"></span> '. Yii::t('app', 'Batal').' Transaksi', ['index'], ['class' => 'btn btn-danger']);
			echo '</p>';
			?>
		</div>
	</h3>
</div>


<?php 
      if (count($daftarItem) > 0){
      ?>
<!-- ANGGOTA AREA -->
<div class="nav-tabs-custom" id="anggota-area">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#detail-anggota" data-toggle="tab"><?= Yii::t('app','Detail Anggota')?></a></li>
    <li><a href="#loan-locations" data-toggle="tab"><?= Yii::t('app','Lokasi Anggota')?></a></li>
    <li><a href="#loan-category" data-toggle="tab"><?= Yii::t('app','Kategori Koleksi')?></a></li>
    <li><a href="#history-last-loan" data-toggle="tab"><?= Yii::t('app','Histori Peminjaman')?></a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="detail-anggota">
      <div class="row">
        <?=$tab_infoanggota?>
      </div>
    </div>
    <div class="tab-pane" id="loan-locations">
      <?=$tab_loanLocation?>
    </div>
    <div class="tab-pane" id="loan-category">
      <?=$tab_loanCategory?>
    </div>
    <div class="tab-pane" id="history-last-loan">
       <?=$tab_historyLoan?>
    </div>
  </div><!-- /.tab-content-->
</div><!-- /.nav-tabs-custom -->
<!-- /.ANGGOTA AREA -->

<?php
}
?>

<!-- KOLEKSI YANG TERKENA PELANGGARAN -->
<div style="text-align: center; font-size: 14px; background-color: #73b9d7; padding: 10px;
                      color: #fff; text-shadow: 0 1px 2px #222; margin-bottom: 3px; border-radius: 5px;">
                      <b>KOLEKSI YANG TERKENA PELANGGARAN</b></div>
<div>

      <?php 
      if (count($daftarItem) > 0){
      ?>
    <div class="table-responsive">  
    <table class="table table-striped table-bordered table-hover table-condensed">
      <thead>
        <tr>
          <th width="40px" style="text-align: right">No</th>
          <th width="100px"><?= yii::t('app','No.Peminjaman')?></th>
          <th width="100px"><?= yii::t('app','No.Barcode')?></th>
          <th width="300px"><?= yii::t('app','Judul')?>/th>
          <th width="100px"><?= yii::t('app','Jatuh Tempo')?></th>
          <th width="100px"><?= yii::t('app','Terlambat')?></th>
          <th width="141px"><?= yii::t('app','Pelanggaran')?></th>
          <th width="141px"><?= yii::t('app','Jenis Denda')?></th>
          <th width="141px"><?= yii::t('app','Jumlah Denda')?></th>
          <th width="141px"><?= yii::t('app','Jumlah Skorsing')?></th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($daftarItem as $item): ?>
        <?php
          $totalItem = count($daftarItem);
           

          


          if($item['DueDate'] < $item['TglKembali'])
            {
               $class = "class='danger'";
               
            }else{
              $class = "";
             
            } 
        ?>
        <tr <?=$class?> >
          <td style="text-align: right"><?php echo $n; ?></td>
          <td><?php echo trim($item['NomorPinjam']); ?></td>
          <td><?php echo trim($item['NomorBarcode']); ?></td>
          <td><?php echo $item['Title']; ?></td>
          <td style="text-align: right">
           <?php  
              echo \common\components\Helpers::DateTimeToViewFormat($item['TglKembali']);
             ?>
          </td>
          <td style="text-align: right">
            <?php 
                // $late = \common\components\SirkulasiHelpers::lateDays(date('Y-m-d') ,date("Y-m-d", strtotime($item['DueDate'])));
            	$late = \common\components\SirkulasiHelpers::lateDays($item['TglKembali'] ,date("Y-m-d", strtotime($item['DueDate'])));
            	if($late > 0){
            		$html = $late.' Hari';


                //Peraturan Peminjaman (Tanggal)
                if ($daySuspend == 0 &&  $dendaPerTenor == 0)
                {
                    $sql = "SELECT DendaType,DendaTenorJumlah,DendaTenorSatuan,DendaPerTenor,DendaTenorMultiply,DaySuspend,SuspendType,SuspendTenorJumlah,SuspendTenorSatuan,SuspendTenorMultiply FROM peraturan_peminjaman_tanggal" .
                        " WHERE DATE(SYSDATE()) BETWEEN TanggalAwal AND TanggalAkhir";
                     $result = Yii::$app->db->createCommand($sql)->queryAll();

                    if (!is_null($result))
                    {
                        $dendaType = $result[0]["DendaType"];
                        $daySuspend = $result[0]["DaySuspend"];
                        $dendaPerTenor = $result[0]["DendaPerTenor"];
                        $dendaTenorJumlah = $result[0]["DendaTenorJumlah"];

                        $suspendType = $result[0]["SuspendType"];
                        $suspendTenorJumlah = $result[0]["SuspendTenorJumlah"];

                       

                        if ($dendaPerTenor > 0)
                        {
                            if ($dendaType == "Konstan")
                            {
                                $penaltyAmount = $dendaPerTenor;
                            }
                            else
                            {
                                $dendaTenorSatuan = strtolower($result[0]["DendaTenorSatuan"]);
                                $dendaTenorMultiply = $result[0]["DendaTenorMultiply"];
                                $Divider = 1;
                                if ($dendaTenorSatuan == "hari")
                                {
                                    $Divider = 1 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "minggu")
                                {
                                    $Divider = 7 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "bulan")
                                {
                                    $Divider = 30 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "tahun")
                                {
                                    $Divider = 365 * $dendaTenorJumlah;
                                }
                                $multiplierBase = (int)floor((double)$late / $Divider);
                                if ($multiplierBase == 0) { $multiplierBase = 1; };
                                $penaltyAmount = $dendaPerTenor * $multiplierBase * $dendaTenorMultiply;
                            }
                        }


                        if($daySuspend>0){
                           if ($suspendType == "Konstan")
                            {
                                $penaltySuspend = $daySuspend;
                            }
                            else
                            {
                                $suspendTenorSatuan = strtolower($result[0]["SuspendTenorSatuan"]);
                                $suspendTenorMultiply = $result[0]["SuspendTenorMultiply"];
                                $Divider = 1;
                                if ($suspendTenorSatuan == "hari")
                                {
                                    $Divider = 1 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "minggu")
                                {
                                    $Divider = 7 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "bulan")
                                {
                                    $Divider = 30 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "tahun")
                                {
                                    $Divider = 365 * $suspendTenorJumlah;
                                }
                                $multiplierBase = (int)floor((double)$late / $Divider);
                                if ($multiplierBase == 0) { $multiplierBase = 1; };
                                $penaltySuspend = $daySuspend * $multiplierBase * $suspendTenorMultiply;
                            }
                        }

                    }
                }
                //End Peraturan Peminjaman (Tanggal)
                //
                //Peraturan Peminjaman (Hari)
                if ($daySuspend == 0 &&  $dendaPerTenor == 0)
                {
                    $sql = "SELECT DendaType,DendaTenorJumlah,DendaTenorSatuan,DendaPerTenor,DendaTenorMultiply,DaySuspend,SuspendType,SuspendTenorJumlah,SuspendTenorSatuan,SuspendTenorMultiply FROM peraturan_peminjaman_hari" .
                        " WHERE DayIndex = IF(DAYOFWEEK(SYSDATE()) = 1, 7, DAYOFWEEK(SYSDATE()) - 1)";
                     $result = Yii::$app->db->createCommand($sql)->queryAll();

                    if (!is_null($result))
                    {
                        $dendaType = $result[0]["DendaType"];
                        $daySuspend = $result[0]["DaySuspend"];
                        $dendaPerTenor = $result[0]["DendaPerTenor"];
                        $dendaTenorJumlah = $result[0]["DendaTenorJumlah"];

                         $suspendType = $result[0]["SuspendType"];
                        $suspendTenorJumlah = $result[0]["SuspendTenorJumlah"];

                        if ($dendaPerTenor > 0)
                        {
                            if ($dendaType == "Konstan")
                            {
                                $penaltyAmount = $dendaPerTenor;
                            }
                            else
                            {
                                $dendaTenorSatuan = strtolower($result[0]["DendaTenorSatuan"]);
                                $dendaTenorMultiply = $result[0]["DendaTenorMultiply"];
                                $Divider = 1;
                                if ($dendaTenorSatuan == "hari")
                                {
                                    $Divider = 1 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "minggu")
                                {
                                    $Divider = 7 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "bulan")
                                {
                                    $Divider = 30 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "tahun")
                                {
                                    $Divider = 365 * $dendaTenorJumlah;
                                }
                                $multiplierBase = (int)floor((double)$late / $Divider);
                                if ($multiplierBase == 0) { $multiplierBase = 1; };
                                $penaltyAmount = $dendaPerTenor * $multiplierBase * $dendaTenorMultiply;
                            }
                        }

                        if($daySuspend>0){
                           if ($suspendType == "Konstan")
                            {
                                $penaltySuspend = $daySuspend;
                            }
                            else
                            {
                                $suspendTenorSatuan = strtolower($result[0]["SuspendTenorSatuan"]);
                                $suspendTenorMultiply = $result[0]["SuspendTenorMultiply"];
                                $Divider = 1;
                                if ($suspendTenorSatuan == "hari")
                                {
                                    $Divider = 1 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "minggu")
                                {
                                    $Divider = 7 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "bulan")
                                {
                                    $Divider = 30 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "tahun")
                                {
                                    $Divider = 365 * $suspendTenorJumlah;
                                }
                                $multiplierBase = (int)floor((double)$late / $Divider);
                                if ($multiplierBase == 0) { $multiplierBase = 1; };
                                $penaltySuspend = $daySuspend * $multiplierBase * $suspendTenorMultiply;
                            }
                        }

                    }
                }
                //End Peraturan Peminjaman (Hari)
                //
                //Jenis Anggota
                if ($daySuspend == 0 &&  $dendaPerTenor == 0)
                {
                    $sql = "SELECT DendaType,DendaTenorJumlah,DendaTenorSatuan,DendaPerTenor,DendaTenorMultiply,DaySuspend,SuspendType,SuspendTenorJumlah,SuspendTenorSatuan,SuspendTenorMultiply FROM members" .
                        " INNER JOIN jenis_anggota ON members.JenisAnggota_id = jenis_anggota.ID" .
                        " WHERE members.ID = " . $memberID;
                     $result = Yii::$app->db->createCommand($sql)->queryAll();

                    if (!is_null($result))
                    {
                        $dendaType = $result[0]["DendaType"];
                        $daySuspend = $result[0]["DaySuspend"];
                        $dendaPerTenor = $result[0]["DendaPerTenor"];
                        $dendaTenorJumlah = $result[0]["DendaTenorJumlah"];

                         $suspendType = $result[0]["SuspendType"];
                        $suspendTenorJumlah = $result[0]["SuspendTenorJumlah"];

                        if ($dendaPerTenor > 0)
                        {
                            if ($dendaType == "Konstan")
                            {
                                $penaltyAmount = $dendaPerTenor;
                            }
                            else
                            {
                                $dendaTenorSatuan = strtolower($result[0]["DendaTenorSatuan"]);
                                $dendaTenorMultiply = $result[0]["DendaTenorMultiply"];
                                $Divider = 1;
                                if ($dendaTenorSatuan == "hari")
                                {
                                    $Divider = 1 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "minggu")
                                {
                                    $Divider = 7 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "bulan")
                                {
                                    $Divider = 30 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "tahun")
                                {
                                    $Divider = 365 * $dendaTenorJumlah;
                                }
                                $multiplierBase = (int)floor((double)$late / $Divider);
                                if ($multiplierBase == 0) { $multiplierBase = 1; };
                                $penaltyAmount = $dendaPerTenor * $multiplierBase * $dendaTenorMultiply;
                            }
                        }

                        if($daySuspend>0){
                           if ($suspendType == "Konstan")
                            {
                                $penaltySuspend = $daySuspend;
                            }
                            else
                            {
                                $suspendTenorSatuan = strtolower($result[0]["SuspendTenorSatuan"]);
                                $suspendTenorMultiply = $result[0]["SuspendTenorMultiply"];
                                $Divider = 1;
                                if ($suspendTenorSatuan == "hari")
                                {
                                    $Divider = 1 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "minggu")
                                {
                                    $Divider = 7 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "bulan")
                                {
                                    $Divider = 30 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "tahun")
                                {
                                    $Divider = 365 * $suspendTenorJumlah;
                                }
                                $multiplierBase = (int)floor((double)$late / $Divider);
                                if ($multiplierBase == 0) { $multiplierBase = 1; };
                                $penaltySuspend = $daySuspend * $multiplierBase * $suspendTenorMultiply;
                            }
                        }
                    }
                }
                //End Jenis Anggota
                //
                //Jenis Bahan
                if ($daySuspend == 0 &&  $dendaPerTenor == 0)
                {
                    $sql = "SELECT DendaType,DendaTenorJumlah,DendaTenorSatuan,DendaPerTenor,DendaTenorMultiply,DaySuspend,SuspendType,SuspendTenorJumlah,SuspendTenorSatuan,SuspendTenorMultiply FROM collections" .
                        " INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID" .
                        " INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID" .
                        " WHERE collections.ID = " . $item['Collection_id'];
                     $result = Yii::$app->db->createCommand($sql)->queryAll();

                    if (!is_null($result))
                    {
                        $dendaType = $result[0]["DendaType"];
                        $daySuspend = $result[0]["DaySuspend"];
                        $dendaPerTenor = $result[0]["DendaPerTenor"];
                        $dendaTenorJumlah = $result[0]["DendaTenorJumlah"];

                         $suspendType = $result[0]["SuspendType"];
                        $suspendTenorJumlah = $result[0]["SuspendTenorJumlah"];
                        
                        if ($dendaPerTenor > 0)
                        {
                            if ($dendaType == "Konstan")
                            {
                                $penaltyAmount = $dendaPerTenor;
                            }
                            else
                            {
                                $dendaTenorSatuan = strtolower($result[0]["DendaTenorSatuan"]);
                                $dendaTenorMultiply = $result[0]["DendaTenorMultiply"];
                                $Divider = 1;
                                if ($dendaTenorSatuan == "hari")
                                {
                                    $Divider = 1 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "minggu")
                                {
                                    $Divider = 7 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "bulan")
                                {
                                    $Divider = 30 * $dendaTenorJumlah;
                                }
                                else if ($dendaTenorSatuan == "tahun")
                                {
                                    $Divider = 365 * $dendaTenorJumlah;
                                }
                                $multiplierBase = (int)floor((double)$late / $Divider);
                                if ($multiplierBase == 0) { $multiplierBase = 1; };
                                $penaltyAmount = $dendaPerTenor * $multiplierBase * $dendaTenorMultiply;
                            }
                        }

                        if($daySuspend>0){
                           if ($suspendType == "Konstan")
                            {
                                $penaltySuspend = $daySuspend;
                            }
                            else
                            {
                                $suspendTenorSatuan = strtolower($result[0]["SuspendTenorSatuan"]);
                                $suspendTenorMultiply = $result[0]["SuspendTenorMultiply"];
                                $Divider = 1;
                                if ($suspendTenorSatuan == "hari")
                                {
                                    $Divider = 1 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "minggu")
                                {
                                    $Divider = 7 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "bulan")
                                {
                                    $Divider = 30 * $suspendTenorJumlah;
                                }
                                else if ($suspendTenorSatuan == "tahun")
                                {
                                    $Divider = 365 * $suspendTenorJumlah;
                                }
                                $multiplierBase = (int)floor((double)$late / $Divider);
                                if ($multiplierBase == 0) { $multiplierBase = 1; };
                                $penaltySuspend = $daySuspend * $multiplierBase * $suspendTenorMultiply;
                            }
                        }
                    }
                }
                //End Jenis Bahan



           // 

            	}else{
            		$html = '0 Hari';
            	}
            	echo $html;
              
            ?>
          </td>
          <td>
             <?php 
             	echo Select2::widget([
			    'name' => 'ddlPelanggaran_'.$n,
			    //'value' => '',
			    'size'=>'sm',
		    	'data' => ArrayHelper::map(JenisPelanggaran::find()->all(),'ID','JenisPelanggaran'),
			    'options' => [
			    	'multiple' => false,]
				]);
             ?>
          </td>
          <td >
             <?php 
             	echo Select2::widget([
			    'name' => 'ddlDenda_'.$n,
			    //'value' => '',
			    'size'=>'sm',
		    	'data' => ArrayHelper::map(JenisDenda::find()->all(),'ID','Name'),
			    'options' => [
			    	'multiple' => false, ]
				]);
             ?>
          </td>
          <td style="text-align: right">
            <?php 

             	echo Html::textInput('jmlDenda_'.$n,$penaltyAmount,['type'=>'number','class'=>'form-control input-sm','style'=>['text-align'=> 'right']]);
            ?>
          </td>
          <td style="text-align: right">
            <?php 
             	echo Html::textInput('jmlSuspend_'.$n,$penaltySuspend,['type'=>'number','class'=>'form-control input-sm','style'=>['text-align'=> 'right']]);
            ?>
           
          </td>
        </tr>
        <?php $n = $n+1; ?>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8"><strong>Total Koleksi Yang Terkena Pelanggaran : </strong></td>
          <td style="text-align: right">&nbsp;</td>
          <td style="text-align: right">
            <strong><?=$totalItem?></strong>
          </td>
        </tr>
      </tfoot>
    </table>
    </div>
   <?php 
      }else{
        $this->registerJs("
  
  $('#btn-simpan').prop('disabled', true);

  
  
  
");
      }
   ?>

<table style="width: 30%; margin-top: 20px;">
    <tbody><tr>
      <td class="style4" colspan="3">
        &nbsp;<?= yii::t('app','Keterangan : ')?>
      </td>
    </tr>
    <tr>
      <td class="style4">
        &nbsp;
      </td>
      <td style="background-color: #ebcccc">
        &nbsp;
      </td>
      <td>
        &nbsp;<?= yii::t('app','Sudah jatuh tempo.')?>
      </td>
    </tr>
</tbody>
</table>
<!-- ./KOLEKSI YANG TERKENA PELANGGARAN -->


<?