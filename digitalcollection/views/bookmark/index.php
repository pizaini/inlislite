<?php
use yii\widgets\ListView;
use nirvana\infinitescroll\InfiniteScrollPager;
use kop\y2sp\ScrollPager;
use yii\web\Session;
use yii\helpers\Html;
use common\widgets\Alert;
use kartik\popover\PopoverX;
use yii\helpers\Url;
use common\components\DirectoryHelpers;
if($alert==TRUE){
  foreach (Yii::$app->session->getAllFlashes() as $message):; 

    echo \kartik\widgets\Growl::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
        'showSeparator' => true,
        'delay' => 1, //This delay is how long before the message shows
        'pluginOptions' => [
            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
            'placement' => [
                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
            ]
        ]
    ]);
                  
  endforeach; 
}
###untuk paging#####
###setting page,limit dan offset####
$page= ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
$limit= ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
$offset = ceil($page / $limit); 
$base=Yii::$app->homeUrl;
$this->registerJS("
$(document).ready(function(){
    $('[data-toggle=\"popover\"]').popover(); 
});


  ");
$this->registerJS("
$('#popover').popover({ 
                      html : true,
                      title: function() {
                        return $(\"#popover-head\").html();
                      },
                      content: function() {
                        return $(\"#popover-content\").html();
                      },
                      footer: function() {
                        return $(\"#popover-head\").html();
                      }
                  });

  "); 
$url = Url::to(['Bookmark/cetak']);
/*$this->registerJs("
  $('#PrintButton').click(function(){
    $.get('". $url ."', {id: ".$model->ID."},function(data, status){
      if(status == 'success'){
        try {
          var oIframe = document.getElementById('Iframe1Slip');
          var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
          if (oDoc.document) oDoc = oDoc.document;
          oDoc.write('<html><head>');
          oDoc.write('</head><body onload=\"this.focus(); this.print(true);\" style=\"text-align: left; font-size: 8pt; width: 95%; height:90%\">');
          oDoc.write(data + '</body></html>');
          oDoc.close();
        } catch (e) {
          alert(e.message);
          self.print();
        }
      }
    });
  });
");*/
 ?>    

<script type="text/javascript">
function favorite(id) {        

  $.ajax({
    type     :"POST",
    cache    : false,
    url  : "?action=favourite&catID="+id,
    success  : function(response) {
          $("#favourite"+id).html(response);
      }
  });


}

function Download(id) {        

  $.ajax({
    type     :"POST",
    cache    : false,
    url  : "?action=Download&catID="+id,
    success  : function(response) {
          $("#favourite"+id).html(response);
      }
  });


}


function KirimEmail() {       
  var id = $("#catalogID").val();
  //var email = document.getElementById('emailnya');
  //var email = $("#emailnya").val();
  var email = $("#emailnya").attr('value')
     $.ajax({
        type     :"POST",
        cache    : false,
        url  : "?action=email&emailID="+email,
        success  : function(response) {
            $("#collectionShow"+id).html(response);
        }
    });
}
function collection(id) {       
    //var id = $("#catalogID").val();
     $.ajax({
        type     :"POST",
        cache    : false,
        url  : "?action=showCollection&catID="+id,
        success  : function(response) {
            $("#collectionShow"+id).html(response);
        }
    });
}
function search(id) {   
    //var id = $("#catalogID").val();
    $.ajax({
      type     :"POST",
      cache    : false,
      url  : "?action=search&catID="+id,
      success  : function(response) {
        $("#search"+id).html(response);
      }
    });


  }

function kontenDigital(id) {        
     $.ajax({
        type     :"POST",
        cache    : false,
        url  : "?action=showKontenDigital&catID="+id,
        success  : function(response) {
            $("#kontenDigitalShow"+id).html(response);
        }
    });
}
function hapusBookmark(id) {        
     $.ajax({
        type     :"POST",
        cache    : false,
        url  : "?action=showKontenDigital&catID="+id,
        success  : function(response) {
            $("#kontenDigitalShow"+id).html(response);
        }
    });
}

</script>
        <script language="JavaScript">
        function toggle(source) {
        checkboxes = document.getElementsByName('catID[]');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
        }   
</script>
  <section class="content">
            <div class="box box-default">
              <div class="box-body" style="padding:20px 0">
                  <div class="breadcrumb">
                  <ol class="breadcrumb">
                    <li><a href="<?=$base ?>">Home</a></li>                   
                    <li class="active">Bookmark</li>                    
                  </ol>
            </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="row">
                        <iframe id='Iframe1Slip' src='#' class='clsifrm' style="width: 0pt; height: 0pt; border: none;" ></iframe>
                        <div id="divPrint" style="display:inline;"></div>
                        <form method="POST" action="<?php Yii::$app->homeUrl?>" class="form-inline"> 
                        <!--popover -->
                        <div id="popover-head" class="hide"> <i class="glyphicon glyphicon-lock"></i>  Enter Email adresses</div>
                        <div id="popover-content" class="hide">

                        <div class="form-group">
                          <input type="email" class="form-control"  name="email"  >
                          <button type="submit" class="btn btn-default  " onClick="KirimEmail()">Send</button>
                          <input type="hidden" name="action" value="email">
                          <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                        </div>

                        </div> <!-- akhir popover-->
                          <?php
                         
                            $awal=($page==1) ? $page : (($page-1)*$limit)+1;
                            $akhir=$page*$limit;
                            if($akhir>$countBookmark){$akhir=$countBookmark;}
                            if($countBookmark!=0) echo"Menampilkan <b>".$awal." - ".$akhir."</b> dari <b>".$countBookmark."</b> hasil <br> <br>";
                          
                            if(isset($_SESSION['catIDmerge'])){
                          ?>
                          <div class="col-sm-12">
                                 
                                <input type="checkbox" onClick="toggle(this)"> Pilih semua &nbsp; &nbsp; &nbsp; 
                                <input type="button" id="popover" name="action" class="btn btn-default btn-xs name="" vbar-btn" value="Email">
                               
                                  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Unduh Katalog <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                      <li role="presentation"><a role="menuitem" tabindex="-1" > <input type="submit" name="Download" class="btn btn-link" value="Format MARC Unicode/UTF-8"> </a></li>
                                      <li role="presentation"><a role="menuitem" tabindex="-1" > <input type="submit" name="Download" class="btn btn-link" value="Format MARC XML"> </a></li>
                                      <li role="presentation"><a role="menuitem" tabindex="-1" > <input type="submit" name="Download" class="btn btn-link" value="Format MODS"> </a></li>
                                      <li role="presentation"><a role="menuitem" tabindex="-1" > <input type="submit" name="Download" class="btn btn-link" value="Format Dublin Core (RDF)"> </a></li>
                                      <li role="presentation"><a role="menuitem" tabindex="-1" > <input type="submit" name="Download" class="btn btn-link" value="Format Dublin Core (OAI)"> </a></li>
                                      <li role="presentation"><a role="menuitem" tabindex="-1" > <input type="submit" name="Download" class="btn btn-link" value="Format Dublin Core (SRW)"> </a></li>
                                  </ul>
                                
                               <!-- <input type="button" name="action" class="btn btn-default btn-xs navbar-btn" id="PrintButton" value="PDF"> 
                                <input type="submit" name="action" class="btn btn-default btn-xs navbar-btn" value="Unduh Katalog"> -->
                                <input type="submit" name="action" class="btn btn-default btn-xs navbar-btn" value="Hapus"> 
                                <input type="submit" name="action"class="btn btn-default btn-xs navbar-btn" value="Kosongkan Tampung">
                                <?= Html::csrfMetaTags() ?>
                                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                            </div>
                        </div>
                        </br>

                        <?php   
                                ####untuk menghitung jumlah tmpilan di Bookmark####
                                ####default 10 item perpage##################
                                $count=($countBookmark<$offset*10) ? $countBookmark : $offset*10;
                                $start=$count-9;
                                $start=($start<=0) ? 1 : $start=$count-9;
                                
                            
                            ?>
                        
                          <table class="table2 table-striped" width="100%">
                          <?php
                          for($start;$start<=$count;$start++){

                            if($dataBookmark[$start]['CoverURL'])
                            {

                                 if(file_exists(Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($dataBookmark[$start]['worksheet_id']).'/'.$dataBookmark[$start]['CoverURL'])))
                                {
                                     $urlcover= '../uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($dataBookmark[$start]['worksheet_id']).'/'.$dataBookmark[$start]['CoverURL'];
                                }
                                else {
                                   $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
                                    }
                                }else{
                                    $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
                                }
                                $pengarang=explode(";", $dataBookmark[$start]['author']);
                          ?>
                          <tr><td>
                            <div id="search<?= $dataBookmark[$start]['id'];?>">
                            <div class="row">
                            <div class="col-sm-1"><?= $count*($page-1)+$start;?> &nbsp; <input type="checkbox" name="catID[]" value="<?php echo $dataBookmark[$start]['id'];?>"> &nbsp;
                              <div id="favourite<?=$dataBookmark[$start]['id']?>">
                               <?php 

                              if (!isset($noAnggota)) {
                                 echo "<a href=\"javascript:void(0)\" onclick=\"tampilLogin()\"title=\"Tambah ke Favorite\"><span class=\"glyphicon glyphicon-star\"></span></a>";
                              }
                               else echo"<a onclick=\"favorite(".$dataBookmark[$start]['id'].")\" href=\"javascript:void(0)\"  title=\"Tambah ke Favorite\"><span class=\"glyphicon glyphicon-star\"></span></a>";  
                               ?>
                              </div>
                            </div>
                            <div class="col-sm-2"><img src="<?= $urlcover ?>"style="width:97px ; height:144px" ></div>    
                          <div class="col-sm-9">
                                 <table class="table2" style="background:transparent" width="100%">
                                  <tr>
                                    <th colspan="2"><a  href=<?=$homeUrl."detail-opac?id=".$dataBookmark[$start]['id']?> class="topnav-content"><?= $dataBookmark[$start]['kalimat2'];?></a></th>
                                   </tr>
                                  <tr>
                                    <td width="22%">Jenis Bahan</td>
                                    <td width="78%"><?= $dataBookmark[$start]['worksheet'];?></td>
                                  </tr>
                                  <tr>
                                   <?php
                                      for($x=0;$x<sizeof($pengarang);$x++){
                                        if($x==0){
                                          echo"
                                          <tr>
                                          <td>Pengarang</td>
                                          <td><a href=\"?action=pencarianSederhana&ruas=Pengarang&bahan=".$dataBookmark[$start]['Worksheet_id']."&katakunci=".$dataBookmark[$start]['author']."\"> ".$pengarang[$x]." </a></td>
                                          </tr>
                                          ";
                                        } 
                                        else
                                        {

                                         echo"
                                          <tr>
                                          <td></td>
                                          <td><a href=\"?action=pencarianSederhana&ruas=Pengarang&bahan=".$dataBookmark[$start]['Worksheet_id']."&katakunci=".$dataBookmark[$start]['author']."\"> ".$pengarang[$x]." </a></td>
                                          </tr>
                                          ";
                                        }

                                      }
                                      ?>
                                  </tr>
                                  <tr>
                                    <td>Penerbitan</td>
                                    <td><?= $dataBookmark[$start]['PublishLocation']." : ".   $dataBookmark[$start]['publisher']." ".$dataBookmark[$start]['PublishYear'];?></td></td>
                                  </tr>
                                  <tr>
                                    <td>Konten Digital</td>
                                    <td>: <?php  if($dataBookmark[$start]['KONTEN_DIGITAL']==NULL){$dataBookmark[$start]['KONTEN_DIGITAL']="Tidak Ada Data";} else {echo"<a data-toggle='collapse' data-target='#collapseKontenDigital".$dataBookmark[$start]['id']."'  class='show_hide' id='showmenu".$dataBookmark[$start]['id']."' onclick='kontenDigital(".$dataBookmark[$start]['id'].")' href='javascript:void(0)' >"; } echo $dataBookmark[$start]['KONTEN_DIGITAL'];?> </td>
                                  </tr>

                                  </table>
                          </div>  </div>
                          
                            <div class="collapse" id="collapseKontenDigital<?= $dataBookmark[$start]['id'];?>">
                                <div id="kontenDigitalShow<?= $dataBookmark[$start]['id'];?>">
                                </div>
                            </div>
                            <br>
                            <div class="collapse" id="collapsecollection<?= $dataBookmark[$start]['id'];?>">
                                <div id="collectionShow<?= $dataBookmark[$start]['id'];?>">
                                </div>
                            </div>
                            </div>
                            </td></tr>
                            <?php
                                }
                                }

                            ?>
                            </form>
                            </table>
                            <center>
                            <nav>
                            <?php         

                              $total_records=$countBookmark;
                              $total_pages=ceil($total_records / $limit); 


                              $perpage=10*$offset;
                              $perpage=($perpage>$total_pages) ?  $total_pages : 10*$offset ;
                              $startpage=$perpage-9;
                              $startpage=($startpage<=0) ? 1 : $startpage=$perpage-9;

                              echo"<ul class='pagination pagination-lg' >";
                                
                                if($startpage<=10) {
                                  echo"<li class=\"disable\"> </li>";
                                } else 
                                {
                                   echo"<li> <a href='?page=".($perpage-10)."&limit=".$limit."    '> &laquo;</a></li>" ;           
                                }

                              for ($startpage; $startpage<=$perpage; $startpage++) {                   
                                          echo"<li";
                                      if ($page==$startpage){                    
                                          echo" class='active'";                    
                                      }
                                      echo"> <a href='?page=".$startpage."&limit=".$limit."    '>".$startpage."</a></li>"; 
                                  } 
                                  if($perpage>= $total_pages){
                                      echo"<li class=\"disable\"> </li>";            } 
                                  else {            
                                      echo"<li> <a href='?page=".($perpage+1)."&limit=".$limit."  '> &raquo;</a></li>" ;          
                                       }


                              ?>
                              </ul>
                             </nav>
                            </center>
                        </div>
                                              
                       </div>
                            
                     </div>         
                </div>
              </div>
                    <div class="row">&nbsp;</div>                    
          </section><!-- /.content -->





