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
          <th width="40px" style="text-align: right">No</th>
          <th width="100px">No.Barcode</th>
          <th>Judul</th>
          <th width="100px">No.Induk</th>
          <th width="100px">No.Panggil</th>
          <th width="40px">&nbsp;</th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($daftarItem as $item): ?>
        <?php
        $totalItem = count($daftarItem);
        //$sumQty += $c['qty'];
        //$sumPrice += ($c['qty'] * $c['price']);
        ?>
        <tr>
          <td style="text-align: right"><?php echo $n++; ?></td>
          <td><?php echo trim($item['NomorBarcode']); ?></td>
          <td><?php echo $item['DataBib']; ?></td>
          <td style="text-align: right">
           <?php echo $item['NomorInduk']; ?>
          </td>
          <td style="text-align: right">
           <?php echo $item['NomorPanggil']; ?>
          </td>
          <td style="text-align: center">
            <?php
            /*$product_id = null;

            if (!empty($product)) {
              $product_id = $product->id;
            }*/
            ?>
            <?php
            echo AjaxButton::widget([
              'label' => '<i class="glyphicon glyphicon-remove"></i> ' .Yii::t('app','Batal'),
              'ajaxOptions' => [
                    'type' => 'POST',
                    'url'  => $url2,
                    'data' => array(
                            'ID' => trim($item['ID']),
                            'index'=> ($n - 2)
                        ),
                   'success'=>new yii\web\JsExpression('function(data){ 
                      $("#koleksi-item").html(data); 
                    }'),
                   'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
                            alert(xhr.responseText); 

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
      <tfoot>
        <tr>
          <td colspan="4"><strong>Total Koleksi : </strong></td>
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
  
 // $('#btn-simpan').prop('disabled', true);

  
  
  
");
      }
   ?>
<?php




?>