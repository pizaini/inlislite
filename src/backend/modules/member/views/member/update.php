<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;
use common\widgets\AjaxButton;
/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 */
$this->title = Yii::t('app', 'Koreksi Anggota #') . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$base=Yii::$app->homeUrl;
?>
<?php $form = ActiveForm::begin(
                      [
                          'type'=>ActiveForm::TYPE_HORIZONTAL,
                          'enableClientValidation' => true,
                          'formConfig' => [
                              'labelSpan' => '3',
                              //'deviceSize' => ActiveForm::SIZE_TINY,
                              'showErrors'=>false,
                          ],
                         /*'fieldConfig' => [
                                          'template' => "<div class=\"row\">
                                                          \n<div class=\"col-sm-12\">{label} {input}</div>
                                                          \n
                                                          <div class=\"col-xs-offset-3 col-xs-9\">
                                                          <div style=\"margin-top: 5px;margin-bottom: 10px;\"></div></div>
                                                          </div>",
                                      ],*/
                      ]
                      );

$url2           = Url::to('reset-password');
$ajaxOptions    = [
                    'type' => 'POST',
                    'url'  => $url2,
                    'data' => array(
                            'NoAnggota' => $model->MemberNo,
                            'memberID'  => $model->ID,
                           
                        ),
                  
                   'success'=>new yii\web\JsExpression('function(data){
                      if(data == "1"){
                        alertSwal("'.yii::t('app','BERHASIL : Password Keanggotaan Online sudah direset').'\n'.yii::t('app','Passwordnya adalah member123').'","success","5000");

                      }else{
                        alertSwal("'.yii::t('app','Password Gagal diReset').'","warning","3000");

                      }
                   }'),
                   'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){ 
                           var msg = cleanResponseError(xhr.responseText,"Not Found (#404): ");
                            alertSwal(msg,"info","1700");
                          }'),
                ];



?>
<div class="members-update">

    <div class="page-header">
        <h3>
        &nbsp;
        <!--<span class="glyphicon glyphicon-edit"></span> Koreksi -->

<!-- Button -->

        <div class="pull-left">
          
            <?php
            echo '<p>';
            echo  Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary btn-sm']);
           /* echo  '&nbsp;' . Html::a(Yii::t('app', 'Salin dari Data Kependudukan'), ['detail-kependudukan'], ['class' => 'btn btn-primary btn-sm','data-toggle'=>"modal",
                                                    'data-target'=>"#myModal",
                                                    'data-title'=>"Detail Data",]);*/
            if($modelUser->IsCanResetMemberPassword){
             echo   '&nbsp;' . AjaxButton::widget([
                            'label' => Yii::t('app','Reset Password Keanggotaan Online'),
                            'ajaxOptions' => $ajaxOptions,
                            'htmlOptions' => [
                                'class' => 'btn btn-success btn-sm',
                                'id' => 'cari',
                                'type' => 'submit'
                            ]
                        ]);
           }
            //echo  '&nbsp;' .Html::a(Yii::t('app', 'Atur Foto'), ['crop-profile-image'], ['class' => 'btn btn-info btn-sm','data-toggle' => 'modal','data-target' => '#crop-modal']);
            

            echo  '&nbsp;' .Html::a(Yii::t('app', 'Kartu Anggota'), ['/member/pdf/kartu-anggota-satuan/','tipe'=>'2','id'=>$model->ID], ['class' => 'btn bg-maroon btn-sm','target'=>'_blank']);

            echo  '&nbsp;' .Html::a(Yii::t('app', 'Selesai'), url::previous(), ['class' => 'btn btn-warning btn-sm']);

           ?>
          </div>
           <?php
           echo yii\bootstrap\ButtonDropdown::widget([
              'label' => Yii::t('app','Cetak Bebas Pustaka'),
              'options' => [
                'class'=>'btn bg-purple btn-sm'
              ],
              'dropdown' => [
                  'items' => [
                      [ 
                        'label' => 'Model 1 (A4)', 
                        'url' =>  ['/member/pdf/cetak-bebas-pustaka/',
                          'id'=>$model->ID,'tipe'=>'1'
                        ]
                      ],
                      [
                        'label' => 'Model 2 (8,5" x 5,5")', 
                        'url' =>  ['/member/pdf/cetak-bebas-pustaka/',
                          'id'=>$model->ID,'tipe'=>'2'
                        ]
                      ],

                  ],
              ],
          ]);
            echo '</p>';
            ?>
      </h3>
    </div>
    

    <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#fa-icons" data-toggle="tab"><?= Yii::t('app','Detail Anggota')?></a></li>
                  <li><a href="#foto" data-toggle="tab"><?= Yii::t('app','Foto Anggota')?></a></li>
                  <li><a href="#pelanggaran" data-toggle="tab"><?= Yii::t('app','Data Pelanggaran')?></a></li>
                  <li><a href="<?=$base?>member/member/histori-peminjaman?id=<?=$model->ID?>" ><?= Yii::t('app','Data Peminjaman')?></a></li>
                  <li>
                     <a href="#perpanjangan" data-toggle="tab"><?= Yii::t('app','Data Perpanjangan')?></a>
                  </li>
                  <li>
                     <a href="#sumbangan" data-toggle="tab"><?= Yii::t('app','Data Sumbangan')?></a>
                  </li>
                </ul>
                <div class="tab-content">
                  
                    
                  <!-- Detail Anggota -->
                  <div class="tab-pane active" id="fa-icons">
                     <div class="row">

                      <br/>
                      
                        <?= $this->render('_formEdit', [
                        'model' => $model,
                        'form'=>$form,
                        'membersForm'=>$membersForm,
                        'pendaftaran'=>$pendaftaran
                    ]) ?>
                     </div>

                  </div>
                  <!-- Foto Anggota -->
                  <div class="tab-pane" id="foto">
                    <div class="row">
                     <br/>
                         <?= $this->render('_formFoto', [
                          'model' => $model,
                          'form'=>$form,
                          'membersForm'=>$membersForm,
                          'pendaftaran'=>$pendaftaran
                      ]) ?>

                    </div>
                  </div>

                 
                  <!-- list pelanggaran -->
                  <div class="tab-pane" id="pelanggaran">
                    <div class="row">
                     <br/>
                       <?= $this->render('_listPelanggaran', [
                          'model' => $model,
                          //'form'=>$form,
                          //'membersForm'=>$membersForm,
                          'pendaftaran'=>$pendaftaran,
                          'dataProviderPelanggaran' => $dataProviderPelanggaran,
                          'searchModelPelanggaran' => $searchModelPelanggaran,
                      ]) ?>


                    </div>
                  </div>

                  <!-- list peminjaman -->
                  <div class="tab-pane" id="peminjaman">
                    <div class="row">
                     <br/>
                       <?= $this->render('_listPeminjaman', [
                          'model' => $model,
                          //'form'=>$form,
                          //'membersForm'=>$membersForm,
                          'pendaftaran'=>$pendaftaran,
                          'dataProviderPeminjaman' => $dataProviderPeminjaman,
                          'searchModelPeminjaman' => $searchModelPeminjaman,
                      ]) ?>


                    </div>
                  </div>

                    <!-- list perpanjangan -->
                    <div class="tab-pane" id="perpanjangan">
                        <div class="row">
                            <br/>
                            <?= $this->render('_listPerpanjangan', [
                                'model' => $model,
                                'dataProviderPerpanjangan' => $dataProviderPerpanjangan,
                            ]) ?>


                        </div>
                    </div>
                    
                     <!-- list sumbangan -->
                  <div class="tab-pane" id="sumbangan">
                    <div class="row">
                     <br/>
                    
                      <table class="table table-hover">
                            <tbody><tr>
                              <th>No</th>
                              <th><?= yii::t('app','Jumlah Sumbangan')?></th>
                              <th><?= yii::t('app','Jumlah Koleksi')?></th>
                              <th><?= yii::t('app','Keterangan')?></th>
                            </tr>
                             <?php 
                             //var_dump($dataSumbangan);

                             if(!is_null($dataSumbangan)){
                             $i = 1;
                              foreach($dataSumbangan as $row )
                              {
                                ?>
                                <tr>
                                  <td><?=$i++?></td>
                                  <td><?=$row["Jumlah"]?></td>
                                  <td><?=$row["JumlahKoleksi"]?></td>
                                  <td><?=$row["Keterangan"]?></td>
                                </tr>
                            <?php
                                  
                              }
                            }
                             ?>
                            
                            
                          </tbody>
                  </table>

                    </div>
                  </div>

                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->


<!-- <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; z-index: 1041;"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"><div class="loader"><div class="sk-spinner sk-spinner-three-bounce"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div></div></div></div></div></div>
 -->
    </div>


</div>
 <?php ActiveForm::end();?>
<?php 

Modal::begin([
    'id' => 'myModal',
    'size'=>'modal-lg',
    'header' => '<h4 class="modal-title">...</h4>',
]);
 
echo '...';
 
Modal::end();

// loadMasaBerlaku
$masaBerlaku = \common\components\MemberHelpers::loadMasaBerlaku();
$registerDate = date("d-m-Y");
$endDate = date("d-m-Y");

if (strtolower($masaBerlaku->satuan) == "hari")
{
    $endDate = \common\components\Helpers::addDayswithdate(date("Y-m-d"),$masaBerlaku->jumlah); //RegisterDate.AddDays(Jumlah);
}
else if (strtolower($masaBerlaku->satuan) == "minggu")
{
    $endDate = \common\components\Helpers::addDayswithdate(date("Y-m-d"),7 * $masaBerlaku->jumlah); //RegisterDate.AddDays(7 * Jumlah);
}
else if (strtolower($masaBerlaku->satuan) == "bulan")
{
    $endDate = \common\components\Helpers::addMonthWithDate(date("Y-m-d"),$masaBerlaku->jumlah);
}
else if (strtolower($masaBerlaku->satuan) == "tahun")
{
    $endDate = \common\components\Helpers::addYearWithDate(date("Y-m-d"),$masaBerlaku->jumlah);
}
$endDate = \common\components\Helpers::DateTimeToViewFormat($endDate);
//- loadMasaBerlaku

$this->registerJs("

    //$('#members-tglregisterdate').val('".$registerDate."');
    //$('#members-tglenddate').val('".$endDate."');
    isLoading = false;
    $('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)
        var title = button.data('title') 
        var href = button.attr('href') 
        modal.find('.modal-title').html(title)
        modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
        $.post(href)
            .done(function( data ) {
                modal.find('.modal-body').html(data)
            });
        })
  
    $('#crop-modal').on('show.bs.modal', function () {
       $(this).find('.modal-dialog ').css({
              width:'450px',
              height:'auto',
              'max-height':'100%'
       });
});
  
    $('#endField').hide();
    $('#masa-berlaku').text($('#members-tglregisterdate').val() +' s.d ' + $('#members-tglenddate').val());

    $('#members-tglregisterdate').change(function () {
       $.get('masa-berlaku?jenis='+$('#members-jenisanggota_id').val()+'&registerDate='+$('#members-tglregisterdate').val(), function( data ) {
                                            console.log(data);
                                            $('#masa-berlaku').text($('#members-tglregisterdate').val() +' s.d ' + data);
                                             $('#members-tglenddate').val(data);

                                        });
    });

");

?>