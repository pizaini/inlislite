<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\grid\GridView;
use yii\widgets\Pjax;


use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use common\widgets\AjaxButton;

?>
<?php 
$url2           = Url::to('hapus-item');
// print_r($daftarItem);die;
?>
<!-- <h4>
      <i class="glyphicon glyphicon-shopping-cart"></i>
      Items in Cart
    </h4> -->
          <?php 
      if (count($daftarItem) > 0){
      ?>
    <div class="table-responsive">  
    <table class="table table-striped table-bordered table-hover table-condensed">
      <thead>
        <tr>
          <th width="40px" style="text-align: left">No</th>
          <th width="100px"><?= yii::t('app','Nomor Barcode')?></th>
          <th><?= yii::t('app','Judul')?></th>
          <th width="100px"><?=yii::t('app','Tahun Terbit')?></th>
          <th width="100px"><?=yii::t('app','Tanggal Kirim')?></th>
          <th width="40px">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        
        <?php foreach ($daftarItem as $item): ?>
          <input type="text" name="PengirimanKoleksi[]" style="display: none">
        <?php
        $totalItem = count($daftarItem);
        ?>
        <tr>
          <td style="text-align: left"><?php echo $n++; ?></td>
          <td>
            <?php echo trim($item['NOBARCODE']); ?>
            <input type="text" name="nobarcode[]" id="nobarcode-<?=$n?>" style="display: none" value="<?php echo trim($item['NOBARCODE']); ?>">
            <input type="text" name="colid[]" id="colid-<?=$n?>" style="display: none" value="<?php echo trim($item['CollectionID']); ?>">
          </td>
          <td>
            <?php echo $item['Title']; ?>
            <input type="text" name="title[]" id="title-<?=$n?>" style="display: none" value="<?php echo trim($item['Title']); ?>">
          </td>
          <td>
            <?php echo $item['TahunTerbit']; ?>
            <input type="text" name="tahunterbit[]" id="tahunterbit-<?=$n?>" style="display: none" value="<?php echo trim($item['TahunTerbit']); ?>">
            <input type="text" name="callnumber[]" id="callnumber-<?=$n?>" style="display: none" value="<?php echo trim($item['CallNumber']); ?>">
            <input type="text" name="noinduk[]" id="noinduk-<?=$n?>" style="display: none" value="<?php echo trim($item['NoInduk']); ?>">
          </td>
          <td>
            <input type="date" name="tanggalkirim[]" id="tanggalkirim-<?=$n?>" class="form-control" value="<?=date('Y-m-d')?>">    
          </td>
          <td style="text-align: center">
            <?php
            echo AjaxButton::widget([
              'label' => '<i class="glyphicon glyphicon-remove"></i> ' .Yii::t('app','Batal'),
              'ajaxOptions' => [
                    'type' => 'POST',
                    'url'  => $url2,
                    'data' => array(
                            'NOBARCODE' => trim($item['NOBARCODE']),
                            'index'=> ($n - 2)
                        ),
                   'success'=>new yii\web\JsExpression('function(data){ 
                      $("#koleksi-item").html(data); 
                    }'),
                   'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
                            alert(xhr.responseText); 
                            $("#peminjamanitemform-nobarcode").val("");
                            $("#peminjamanitemform-nobarcode").focus();
                          }'),
                ],
              'htmlOptions' => [
              'class' => 'btn btn-danger btn-sm',
              'data-toggle'=>"tooltip",
              'title'=>"Batal",
              'id' => 'hapus-'.$n,
              'type' => 'submit'
              ]
              ]);
            ?>   
           
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
   <?php 
      }else{
        $this->registerJs("
  
  $('#btn-simpan').prop('disabled', true);

  
    
  
");
      }
   ?>
