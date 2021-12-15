<?php
use yii\helpers\Url;
use common\components\OpacHelpers;
use common\components\DirectoryHelpers;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use common\models\CollectionSearchKardeks;
use yii\helpers\HTML;
$ids=$catalogID;
$pencarian_url=Yii::$app->urlManager->createAbsoluteUrl('pencarian-sederhana');
$base=Yii::$app->homeUrl;


$isBooking= Yii::$app->config->get('IsBookingActivated');
$this->registerJS('
$(document).ready(function() {
    $(\'#detail\').DataTable(
        {
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true
        });
} );


    ');
?>

<script type="text/javascript">

  function keranjang(id) {
        //var id = $("#catalogID").val();
        $.ajax({
          type     :"POST",
          cache    : false,
          url  : "?action=keranjang&catID="+id,
          success  : function(response) {
            $("#keranjang").html(response);
      }
        });


      }
  function logDownload(id) {
    $.ajax({
        type: "POST",
        cache: false,
        url: "?action=logDownload&ID=" + id,
    });


  }

    </script>
    <div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="gridSystemModalLabel">Cite This</h4>
        </div>
        <div class="modal-body">
         <b> APA Citation </b>  <br>
         <?= $cite['APA']?> <br>
       <!--  <b> Chicago Style Citation </b>  <br>
         <?/*= $cite['APA']*/?> <br>
         <b> MLA Citation </b>  <br>
         <?/*= $cite['APA']*/?> <br> -->
      <div id="keranjang">

      </div>

       </div>
       <div class="modal-footer">
        <p align="center" style="color:grey">Peringatan: citasi ini tidak selalu 100% akurat!</p>

      </div>
    </div>
  </div>
</div>
<section class="content">
  <div class="box box-default">
    <div class="box-body" style="padding:20px 0">
      <div class="breadcrumb">
        <ol class="breadcrumb">
          <li><a href="<?=$base; ?>">Home</a></li>
          <li><a >Detail Result</a></li>



        </ol>
      </div>

      <div class="row">
        <div class="col-md-9">
          <div class="row">
            <div class="col-md-2"><br><img src="<?= $urlcover ?>" style="width:97px ; height:144px"></div>
            <div class="col-md-10"><center>
              <a href="" data-toggle="modal" data-target=".bs-example-modal-md"  ><i class="glyphicon glyphicon-cog"></i> Cite This</a>&nbsp; &nbsp; &nbsp; &nbsp;
              <a href="javascript:void(0)" onclick="keranjang(<?= $modelCatalog->ID; ?>)" ><i class="glyphicon glyphicon-shopping-cart"></i> Tampung</a>&nbsp; &nbsp; &nbsp; &nbsp;
              <!--<a href="" ><i class="glyphicon glyphicon-new-window"></i> Export Record</a>-->
            </center>
            <!-- <?//php echo '<pre>';print_r((empty($modelArticleRepeatable) ? null : implode(', ',$modelArticleRepeatable['Kreator']))); echo '</pre>'; ?> -->
            <!-- <?//php echo '<pre>';print_r(implode(', ',$modelArticle['Kreator'])); echo '</pre>'; ?> -->
            <table class="table table-striped">
                <tr>
                    <div class="row">
                        <div class="col-sm-4"> <td width="40%"><?= yii::t('app','Article Title')?></td> </div>
                        <div class="col-sm-8"> <td><?=$modelArticle->Title ?></td> </div>

                    </div>
                </tr>
                <tr>
                    <div class="row">
                        <div class="col-sm-4"> <td width="40%"><?= yii::t('app','Catalogs Title')?></td> </div>
                        <div class="col-sm-8"> <td><?=$modelCatalog->Title ?></td> </div>

                    </div>
                </tr>
                <!--<tr>
                    <td><?/*= yii::t('app','Article Type')*/?></td>
                    <td><?/*=$modelArticle->Article_type*/?></td>
                </tr>-->
                <tr>
                    <td><?= yii::t('app','Content')?></td>
                    <td><?=$modelArticle->Content?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','Creator')?></td>
                    <td><?=(empty($modelArticleRepeatable) ? null : implode(', ',$modelArticleRepeatable['Kreator']))?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','Contributor')?></td>
                    <td><?=(empty($modelArticleRepeatable) ? null : implode(', ',$modelArticleRepeatable['Kontributor']))?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','Start Page')?></td>
                    <td><?=$modelArticle->StartPage?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','Pages')?></td>
                    <td><?=$modelArticle->Pages?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','Subject')?></td>
                    <td><?=(empty($modelArticleRepeatable) ? null : implode(', ',$modelArticleRepeatable['Subjek']))?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','DDC')?></td>
                    <td><?=$modelArticle->DDC?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','Call_Number')?></td>
                    <td><?=$modelArticle->Call_Number?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','Edisi Serial')?></td>
                    <td><?=$modelArticle->EDISISERIAL?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','Tanggal Terbit Edisi Serial')?></td>
                    <td><?=$modelArticle->TANGGAL_TERBIT_EDISI_SERIAL?></td>
                </tr>
                <tr>
                    <td><?= yii::t('app','Abstract')?></td>
                    <td><?=$modelArticle->Abstract?></td>
                </tr>
            </table>

            <br>
          </div>
        </div>


            <div class="row">
                <div class="col-md-12">
              <span class="nav-tabs-content">
            <ul class="nav nav-tabs">
              <!--<li class="active"><a href="#tab_11" data-toggle="tab"><?/*= yii::t('app','Eksemplar')*/?></a></li>-->
               <li><a href="#tab_11 " data-toggle="tab" title="Tampilkan Konten Digital"><?= yii::t('app','Konten Digital')?></a></li>
              <li><a href="#tab_22" data-toggle="tab" title="Tampilkan Metadata MARC"><?= yii::t('app','MARC')?></a></li>
              <!--<li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  <?/*= yii::t('app','Unduh Katalog')*/?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?/*=Yii::$app->request->get('id')*/?>&amp;type=MARC21">Format MARC Unicode/UTF-8</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?/*=Yii::$app->request->get('id')*/?>&amp;type=MARCXML">Format MARC XML</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?/*=Yii::$app->request->get('id')*/?>&amp;type=MODS">Format MODS</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?/*=Yii::$app->request->get('id')*/?>&amp;type=DC_RDF">Format Dublin Core (RDF)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?/*=Yii::$app->request->get('id')*/?>&amp;type=DC_OAI">Format Dublin Core (OAI)</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="detail-opac/download?id=<?/*=Yii::$app->request->get('id')*/?>&amp;type=DC_SRW">Format Dublin Core (SRW)</a></li>
                </ul>
              </li>-->



            </ul>
          </span>
                    <div class="tab-content">
                        <div class="tab-pane-content active" id="tab_11">
                            <div class="table-responsive" id="kontendigital">
                                <p>
                                <table class="table table-striped2 table-bordered">


                                    <tr>
                                        <th >No</th>
                                        <th ><?= yii::t('app','Nama File')?></th>
                                        <th> <?= yii::t('app','Nama File Format Flash')?></th>
                                        <th ><?= yii::t('app','Format File')?></th>
                                        <th ><?= yii::t('app','Aksi')?></th>

                                        <?php
                                        for ($i = 0; $i < $countDataKontenDigitalArticle; $i++) {
                                        ?>
                                    <tr>
                                        <td><?= $i+1 ?></td>

                                        <?php if($dataKontenDigitalArticle[$i]['FileFlash']!='' && $dataKontenDigitalArticle[$i]['FileFlash'] != NULL)
                                        {
                                            $fakePath = DirectoryHelpers::GetTemporaryFolderArticle($dataKontenDigitalArticle[$i]['ID'],2);
                                            if(!isset($noAnggota) && $dataKontenDigitalArticle[$i]['IsPublish']===2){
                                                $kata="<a href=\"javascript:void(0)\" onclick=\"tampilLogin()\" >Baca Online</a>";
                                            } else {
                                                $kata="<a href=\"../uploaded_files".$fakePath."\" onclick=\"logDownload(".$dataKontenDigitalArticle[$i]['ID'].")\" target=\"_blank\" >Baca Online</a>";
                                            }
                                        }
                                        else{
                                            $fakePath = DirectoryHelpers::GetTemporaryFolderArticle($dataKontenDigitalArticle[$i]['ID'],1);
                                            if((!isset($noAnggota)) && $dataKontenDigitalArticle[$i]['IsPublish']==2){
                                                $kata="<a href=\"javascript:void(0)\" onclick=\"tampilLogin()\" >Download</a>";
                                            } else{
                                                $kata="<a  href=\"../uploaded_files".$fakePath."\" onclick=\"logDownload(".$dataKontenDigitalArticle[$i]['ID'].")\"target=\"_blank\" >Download</a>";
                                            }
                                        }


                                        ?>
                                        <td><?=$dataKontenDigitalArticle[$i]['FileURL']?></td>
                                        <td><?=$dataKontenDigitalArticle[$i]['FileFlash']?></td>
                                        <td><?= $dataKontenDigitalArticle[$i]['FormatFile']; ?></td>
                                        <td><?=$kata?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>

                                </table>
                                </p>
                            </div>
                        </div>
                        <div class="tab-pane-content" id="tab_22">
                            <?php
                            if($marcOpac!=""){
                                echo"<table class=\"table table-bordered\">
                                <tr>
                                    <td>Tag</td>
                                    <td> Ind1 </td>
                                    <td>Ind2</td>
                                    <td>Isi</td>
                                </tr>
                                ";
                                for($i=0; $i < sizeof($marcOpac); $i++){
                                    echo"<tr>
                                <td>
                                ".$marcOpac[$i]['Tag']."
                                </td>
                                
                                 <td>
                                ".$marcOpac[$i]['Indicator1']."
                                </td>
                            
                                 <td>
                                ".$marcOpac[$i]['Indicator2']."
                                </td>
                            
                                 <td>
                                ".$marcOpac[$i]['Value']."
                                </td>
                            
                            
                                </tr>
                                ";
                                }
                            }
                            echo"</table>";
                            ?>

                        </div>
                        <div class="tab-pane-content " id="tab_12">


                        </div>
                        <div class="tab-pane-content" id="tab_33">Content Unduh katalog</div>
                    </div>
                </div>
            </div>
            <div class="row">&nbsp;</div>
        </div>