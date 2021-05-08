<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\web\Session;
use common\components\DirectoryHelpers;
$session = Yii::$app->session;
$this->title = 'Online Public Access Catalog';
$homeUrl=Yii::$app->homeUrl;
$rootPath=\Yii::$app->basePath;
//echo" isi dari path =".$rootPath;
?>

              <div class="box-body" style="padding:20px 0">
                  <?php
                if($settingUnggul['Show']=='TRUE' && sizeof($modelUnggul) != 0){
                    ?>
               <h4><?= Yii::t('app', 'KOLEKSI UNGGULAN')?>         </h4>

                                    <!-- CLIENT SLIDER STARTS-->
                    <div class="carousel slide clients-carousel" id="clients-slider">
                    <div class="carousel-inner">

            <?php
            $maxUnggul= $settingUnggul['Jumlah'] <= sizeof($modelUnggul)  ? $settingUnggul['Jumlah'] : sizeof($modelUnggul);
            //echo"isi dari maxUnggul =".sizeof($modelUnggul);
            for($i=0;$i<$maxUnggul;$i++){
               $urlcover;
              if($modelUnggul[$i]['coverurl'])
              {
                 
                   if(file_exists(Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($modelUnggul[$i]['Worksheet_id']).'/'.$modelUnggul[$i]['coverurl'])))
                  {
                    $urlcover= '../uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($modelUnggul[$i]['Worksheet_id']).'/'.$modelUnggul[$i]['coverurl'];
                  }else
                  {
                    $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
                  }
              }else{
                  $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
              }



            

              if(strlen($modelUnggul[$i]['title'])>=100){
                $potongkata=substr($modelUnggul[$i]['title'],0,100);
                $modelUnggul[$i]['title']=$potongkata."....";
              }
              if($i==0){
              echo"
              <div class=\"item  active\">
                            <div class=\"row\">
              <div class=\"col-sm-3 col-xs-6\">
                                     <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelUnggul[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\" width:97px; height:144px;\" ><p>  ".$modelUnggul[$i]['title']."</p>
                                    </a>
                                </div>

              ";
              //echo" isinya ".$i."<br>";
              } elseif ($i==sizeof($modelUnggul)-1) {
                if($i%4==0){
                echo"
                <div class=\"item\">
                              <div class=\"row\">

                ";
                }
                echo"
                <div class=\"col-sm-3 col-xs-6\">
                                    <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelUnggul[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\" width:97px; height:144px;\"><p>  ".$modelUnggul[$i]['title']." </p>
                                    </a>
                                </div>
              </div>
                          </div>



                ";

              //echo" isinya ".$i."<br>";
              } else
              if ($i%4==0) {
              echo"
              <div class=\"item\">
                            <div class=\"row\">
              <div class=\"col-sm-3 col-xs-6\">
                                    <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelUnggul[$i]['catalog_id']."\">
                                         <img   src=\"".$urlcover."\" style=\" width:97px; height:144px;\"  ><p>  ".$modelUnggul[$i]['title']." </p>
                                    </a>

                                </div>



              ";
              //echo" isinya ".$i."<br>";
              } elseif($i%4==3){
              echo"

              <div class=\"col-sm-3 col-xs-6\">
                                     <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelUnggul[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\" width:97px; height:144px;\"><p>  ".$modelUnggul[$i]['title']." </p>
                                    </a>
                                </div>
              </div>
                          </div>


              ";

              //echo" isinya ".$i."<br>";
              }  else
              {
              echo"

              <div class=\"col-sm-3 col-xs-6\">
                                     <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelUnggul[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\" width:97px; height:144px;\" ><p>  ".$modelUnggul[$i]['title']."</p>
                                    </a>
                                </div>
              ";

              //echo" isinya ".$i."<br>";
              }



              }


             ?>

             </div>
             <?php
             if(sizeof($modelUnggul)>4){
             echo"
             <a data-slide=\"prev\" href=\"#clients-slider\" class=\"left carousel-control\">‹</a>
                       <a data-slide=\"next\" href=\"#clients-slider\" class=\"right carousel-control\">›</a>
             ";
             }

              ?>

          </div>



         <?php

        }


                if($settingTC['Show']=='TRUE' && sizeof($modelTC) != 0){
          ?>
               <h4><?= Yii::t('app', 'KOLEKSI SERING DI PINJAM')?>         </h4>

                                    <!-- CLIENT SLIDER STARTS-->
                    <div class="carousel slide clients-carousel" id="clients-slider2">
                    <div class="carousel-inner">

            <?php
            $maxTC= $settingTC['Jumlah'] <= sizeof($modelTC)  ? $settingTC['Jumlah'] : sizeof($modelTC);
            //echo"isi dari maxTC =".sizeof($modelTC);
            for($i=0;$i<$maxTC;$i++){
              $urlcover;
              if($modelTC[$i]['coverurl'])
              {
                  
                  if(file_exists(Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($modelTC[$i]['Worksheet_id']).'/'.$modelTC[$i]['coverurl'])))
                  {
                       $urlcover= '../uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($modelTC[$i]['Worksheet_id']).'/'.$modelTC[$i]['coverurl'];
                  }else
                  {
                    $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
                  }
              }else{
                  $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
              }





              if(strlen($modelTC[$i]['title'])>=100){
                $potongkata=substr($modelTC[$i]['title'],0,100);
                $modelTC[$i]['title']=$potongkata."....";
              }
              if($i==0){
              echo"
              <div class=\"item  active\">
                            <div class=\"row\">
              <div class=\"col-sm-3 col-xs-6\">
                                     <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelTC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\" width:97px; height:144px;\"><p>  ".$modelTC[$i]['title']."</p>
                                    </a>
                                </div>

              ";

              //echo" isinya ".$i."<br>";
              } elseif ($i==sizeof($modelTC)-1) {
                if($i%4==0){
                echo"
                <div class=\"item\">
                              <div class=\"row\">

                ";
                }
                echo"
                <div class=\"col-sm-3 col-xs-6\">
                                    <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelTC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\" width:97px; height:144px;\"><p>  ".$modelTC[$i]['title']." </p>
                                    </a>
                                </div>
              </div>
                          </div>



                ";
              //echo" isinya ".$i."<br>";
              } else
              if ($i%4==0) {
              echo"
              <div class=\"item\">
                            <div class=\"row\">
              <div class=\"col-sm-3 col-xs-6\">
                                    <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelTC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\" width:97px; height:144px;\"><p>  ".$modelTC[$i]['title']." </p>
                                    </a>

                                </div>



              ";

              //echo" isinya ".$i."<br>";
              } elseif($i%4==3){
              echo"

              <div class=\"col-sm-3 col-xs-6\">
                                     <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelTC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\" width:97px; height:144px;\"><p>  ".$modelTC[$i]['title']." </p>
                                    </a>
                                </div>
              </div>
                          </div>


              ";
              //echo" isinya ".$i."<br>";
              }  else
              {
              echo"

              <div class=\"col-sm-3 col-xs-6\">
                                     <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelTC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\" width:97px; height:144px;\"><p>  ".$modelTC[$i]['title']."</p>
                                    </a>
                                </div>
              ";
              //echo" isinya ".$i."<br>";
              }



              }


             ?>

             </div>
             <?php
             if(sizeof($modelTC)>4){
             echo"
             <a data-slide=\"prev\" href=\"#clients-slider2\" class=\"left carousel-control\">‹</a>
                       <a data-slide=\"next\" href=\"#clients-slider2\" class=\"right carousel-control\">›</a>
             ";
             }

              ?>

          </div>



         <?php

        }

                if($settingNC['Show']=='TRUE' && sizeof($modelNC) != 0){
          ?>
               <h4><?= Yii::t('app', 'KOLEKSI TERBARU')?>         </h4>

                                    <!-- CLIENT SLIDER STARTS-->
                    <div class="carousel slide clients-carousel" id="clients-slider3">
                    <div class="carousel-inner">

            <?php
            $maxNC= $settingNC['Jumlah'] <= sizeof($modelNC)  ? $settingNC['Jumlah'] : sizeof($modelNC);
            //echo"isi dari maxNC =".sizeof($modelNC);
            for($i=0;$i<$maxNC;$i++){
              $urlcover;
              if($modelNC[$i]['coverurl'])
              {
                  if(file_exists(Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($modelNC[$i]['Worksheet_id']).'/'.$modelNC[$i]['coverurl'])))
                  {
                       $urlcover= '../uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($modelNC[$i]['Worksheet_id']).'/'.$modelNC[$i]['coverurl'];
                  }else
                  {
                    $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
                  }

              }else{
                  $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
              }


              if(strlen($modelNC[$i]['title'])>=100){
                $potongkata=substr($modelNC[$i]['title'],0,100);
                $modelNC[$i]['title']=$potongkata."....";
              }
              if($i==0){
              echo"
              <div class=\"item  active\">
                            <div class=\"row\">
              <div class=\"col-sm-3 col-xs-6\">
                                     <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelNC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\"width:97px; height:144px;\"><p>  ".$modelNC[$i]['title']."</p>
                                    </a>
                                </div>

              ";
              //echo" isinya ".$i."<br>";
              } elseif ($i==sizeof($modelNC)-1) {
                if($i%4==0){
                echo"
                <div class=\"item\">
                              <div class=\"row\">

                ";
                }
                echo"
                <div class=\"col-sm-3 col-xs-6\">
                                    <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelNC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\"width:97px; height:144px;\"><p>  ".$modelNC[$i]['title']." </p>
                                    </a>
                                </div>
              </div>
                          </div>



                ";
              //echo" isinya ".$i."<br>";
              } else
              if ($i%4==0) {
              echo"
              <div class=\"item\">
                            <div class=\"row\">
              <div class=\"col-sm-3 col-xs-6\">
                                    <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelNC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\"width:97px; height:144px;\"><p>  ".$modelNC[$i]['title']." </p>
                                    </a>

                                </div>



              ";
              //echo" isinya ".$i."<br>";
              } elseif($i%4==3){
              echo"

              <div class=\"col-sm-3 col-xs-6\">
                                     <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelNC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\"width:97px; height:144px;\"><p>  ".$modelNC[$i]['title']." </p>
                                    </a>
                                </div>
              </div>
                          </div>


              ";
              //echo" isinya ".$i."<br>";
              }  else
              {
              echo"

              <div class=\"col-sm-3 col-xs-6\">
                                     <a class=\"thumbnail\" href=\"".$homeUrl."detail-opac?id=".$modelNC[$i]['catalog_id']."\">
                                         <img  src=\"".$urlcover."\" style=\"width:97px; height:144px;\"><p>  ".$modelNC[$i]['title']."</p>
                                    </a>
                                </div>
              ";
              //echo" isinya ".$i."<br>";
              }



              }


             ?>

             </div>
             <?php
             if(sizeof($modelNC)>4){
             echo"
             <a data-slide=\"prev\" href=\"#clients-slider3\" class=\"left carousel-control\">‹</a>
                       <a data-slide=\"next\" href=\"#clients-slider3\" class=\"right carousel-control\">›</a>
             ";
             }

              ?>

          </div>



         <?php

        }




              ?>
             </div>
            </div>
                     <script type="text/rocketscript">
        $(document).ready(function () {
            $("#clients-slider").carousel({
                interval: 2000 //TIME IN MILLI SECONDS
            });
        });
    </script>
          </section><!-- /.content -->



