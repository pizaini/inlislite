<?php $homeUrl=Yii::$app->homeUrl;
$detail_url=Yii::$app->urlManager->createAbsoluteUrl('detail-opac');
$this->title = 'Online Public Access Catalog';
?>

          <!-- Main content -->
          <section class="content">
            <div class="box box-default">
                    <div class="box-body" style="padding:20px 0">
                    <div class="breadcrumb">
                  <ol class="breadcrumb">
                  <li><a href="<?=$homeUrl ?>">Home</a></li>
            <li><a href="<?=$homeUrl."browse" ?>">Browse</a></li>

          </ol>
            </div>
                   
   <div role="main" class="main template-dir-browse template-name-home">
      <div class="container">

<div id="alert">
</div>



<div class="row">
  <div class="browse list-group col-sm-3 hidden-xs" id="list1">
          <a href="<?=$homeUrl."browse?tag=Author" ?>" class="list-group-item <?php if(isset($_GET['tag']) && $_GET['tag']=='Author') echo"active"; ?>">
        <?= yii::t('app','Pengarang')?>        <span class="pull-right"><i class="fa fa-angle-right"></i></span>
      </a><a href="<?=$homeUrl."browse?tag=Subject" ?>" class="list-group-item <?php if(isset($_GET['tag']) && $_GET['tag']=='Subject') echo"active"; ?>">
        <?= yii::t('app','Subyek')?>        <span class="pull-right"><i class="fa fa-angle-right"></i></span>
      </a><a href="<?=$homeUrl."browse?tag=Publisher" ?>" class="list-group-item <?php if(isset($_GET['tag']) && $_GET['tag']=='Publisher') echo"active"; ?>">
        <?= yii::t('app','Penerbit')?>        <span class="pull-right"><i class="fa fa-angle-right"></i></span>
      </a><a href="<?=$homeUrl."browse?tag=PublishLocation" ?>" class="list-group-item <?php if(isset($_GET['tag']) && $_GET['tag']=='PublishLocation') echo"active"; ?>">  
        <?= yii::t('app','Tempat Terbit')?>        <span class="pull-right"><i class="fa fa-angle-right"></i></span>
      </a><a href="<?=$homeUrl."browse?tag=PublishYear" ?>" class="list-group-item <?php if(isset($_GET['tag']) && $_GET['tag']=='PublishYear') echo"active"; ?>">  
        <?= yii::t('app','Tahun Terbit')?>        <span class="pull-right"><i class="fa fa-angle-right"></i></span>
      </a>
      </div>

   <?php if(preg_match('/[^a-zA-Z\d]/',$_GET['tag']) == false){
      if($_GET['tag']=='Author'){
    ?> 

   <div class="browse list-group col-sm-3 hidden-xs" id="list2">
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Alphabetical" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Alphabetical') echo"active"; ?>">
                      Alphabetical            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Subject" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Subject') echo"active"; ?>">
                      <?= yii::t('app','Subyek')?>           <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Publisher" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Publisher') echo"active"; ?>">
                     <?= yii::t('app','Penerbit')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=PublishLocation" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='PublishLocation') echo"active"; ?>">
                      <?= yii::t('app','Tempat Terbit')?>            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=PublishYear" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='PublishYear') echo"active"; ?>">
                       <?= yii::t('app','Tahun Terbit')?>              <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>  
             
          </div>

    <?php }
     else if($_GET['tag']=='Subject'){?>
    <div class="browse list-group col-sm-3 hidden-xs" id="list2">
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Alphabetical" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Alphabetical') echo"active"; ?>">
                      Alphabetical            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>                
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Author" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Author') echo"active"; ?>">
                      <?= yii::t('app','Pengarang')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Publisher" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Publisher') echo"active"; ?>">
                      <?= yii::t('app','Penerbit')?>           <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>  
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=PublishLocation" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='PublishLocation') echo"active"; ?>">
                      <?= yii::t('app','Tempat Terbit')?>              <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=PublishYear" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='PublishYear') echo"active"; ?>">
                     <?= yii::t('app','Tahun Terbit')?>           <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
       </div>
  
    <?php }
     else if($_GET['tag']=='Publisher'){?>
    <div class="browse list-group col-sm-3 hidden-xs" id="list2">
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Alphabetical" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Alphabetical') echo"active"; ?>">
                      Alphabetical            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>                
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Author" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Author') echo"active"; ?>">
                       <?= yii::t('app','Pengarang')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Subject" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Subject') echo"active"; ?>">
                      <?= yii::t('app','Subyek')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>  
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=PublishLocation" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='PublishLocation') echo"active"; ?>">
                     <?= yii::t('app','Tempat Terbit')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=PublishYear" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='PublishYear') echo"active"; ?>">
                      <?= yii::t('app','Tahun Terbit')?>           <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
       </div>

    <?php }
     else if($_GET['tag']=='PublishLocation'){?>
    <div class="browse list-group col-sm-3 hidden-xs" id="list2">
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Alphabetical" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Alphabetical') echo"active"; ?>">
                      Alphabetical            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>                
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Author" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Author') echo"active"; ?>">
                       <?= yii::t('app','Pengarang')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Publisher" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Publisher') echo"active"; ?>">
                      <?= yii::t('app','Penerbit')?>          <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=PublishYear" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='PublishYear') echo"active"; ?>">
                      <?= yii::t('app','Tahun Terbit')?>            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>  
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Subject" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Subject') echo"active"; ?>">
                      <?= yii::t('app','Subyek')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
       </div>
    <?php }
     else if($_GET['tag']=='PublishYear'){?>
    <div class="browse list-group col-sm-3 hidden-xs" id="list2">
              <!-- <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Alphabetical" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Alphabetical') echo"active"; ?>">
                      By Alphabetical            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>  -->              
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Author" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Author') echo"active"; ?>">
                      <?= yii::t('app','Pengarang')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Publisher" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Publisher') echo"active"; ?>">
                       <?= yii::t('app','Penerbit')?>            <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=PublishLocation" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='PublishLocation') echo"active"; ?>">
                      <?= yii::t('app','Tempat Terbit')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>  
              <a href="<?=$homeUrl."browse?tag=".$_GET['tag']."&findBy=Subject" ?>" class="list-group-item clearfix <?php if((isset($_GET['findBy'])) && $_GET['findBy']=='Subject') echo"active"; ?>">
                      <?= yii::t('app','Subyek')?>             <span class="pull-right"><i class="fa fa-angle-right"></i></span>
                  </a>
       </div>
    <?php } ?>
   <?php } else {throw new \yii\web\HttpException(404, 'The requested Item could not be found.');}?>



      <?php if(isset($model)){ ?>
    <div class="browse list-group col-sm-3 hidden-xs" id="list3">
              
            <?php for($i=0;$i<sizeof($model);$i++){
              if($_GET['findBy']=='Alphabetical'){
              if((isset($_GET['query'])) && $_GET['query']==$model[$i]['A']) {$active='active';} else {$active='';};
              echo"<a style=\"padding: 8px 40px 8px 8px;\" href=\"".$homeUrl."browse?tag=".$_GET['tag']."&findBy=".$_GET['findBy']."&query=".$model[$i]['A']."\"";  echo" class=\"list-group-item clearfix ".$active."\">";
              echo    $model[$i]['A'];
              echo" <span class=\"pull-right\"><i class=\"fa fa-angle-right\"></i></span>";
              echo"</a> ";
              } else {
                if((isset($_GET['query'])) && $_GET['query']==$model[$i]['name']) {$active='active';} else {$active='';};
                echo"<a style=\"padding: 8px 40px 8px 8px;\" href=\"".$homeUrl."browse?tag=".$_GET['tag']."&findBy=".$_GET['findBy']."&query=".$model[$i]['name']."\"";  echo" class=\"list-group-item clearfix ".$active."\">";
                if($model[$i]['name']=='')$model[$i]['name']='-';
                echo    $model[$i]['name'];
                echo"<span class=\"badge\">".$model[$i]['jml']."</span>";
                echo"</a> ";
              }
               } ?>                      
              
              
    </div>
    <?php }?>
          <?php if(isset($model2)){ ?>
    <div class="browse list-group col-sm-3 hidden-xs" id="list3">
              
            <?php for($i=0;$i<sizeof($model2);$i++){
             
                
                echo"<a style=\"padding: 8px 40px 8px 8px;\" href=\"".$homeUrl."browse?action=browse&tag=".$_GET['tag']."&findBy=".$_GET['findBy']."&query=".$_GET['query']."&query2=".$model2[$i]['name']."\"";  echo" class=\"list-group-item clearfix \">";
                if($model2[$i]['name']=='')$model2[$i]['name']='-';
                echo    $model2[$i]['name'];
                echo"<span class=\"badge\">".$model2[$i]['jml']."</span>";
                echo"</a> ";
              
               } ?>                      
                       
    </div>
    <?php }?>
 <!--   <div class="browse list-group col-sm-3 hidden-xs" id="list3">
              <a href="/Browse/Subject?findby=era&category=&query=%221952-%22&query_field=era_facet&facet_field=Subject_facet" class="list-group-item clearfix">
          1952-                  
          <span class="badge">5</span>
              </a>
             <a href="/Browse/Subject?findby=era&category=&query=%221952-%22&query_field=era_facet&facet_field=Subject_facet" class="list-group-item clearfix">
          1952-                  
          <span class="badge">5</span>
              </a>
               <a href="/Browse/Subject?findby=era&category=&query=%221952-%22&query_field=era_facet&facet_field=Subject_facet" class="list-group-item clearfix">
          1952-                  
          <span class="badge">5</span>
              </a>
         
      
 </div>
   
    <div class="browse list-group col-sm-3" id="list4">
            <a class="list-group-item clearfix" href="detail_telusur.html">
          Zoology        <span class="badge">2</span>
        </a>
            <a class="list-group-item clearfix" href="">
          Animal pathology        <span class="badge">1</span>
        </a>
            <a class="list-group-item clearfix" href="">
          Wildlife        <span class="badge">1</span>
        </a>
            <a class="list-group-item clearfix" href="">
          Zoology - Animal pathology - Animal diseases        <span class="badge">1</span>
        </a>
            <a class="list-group-item clearfix" href="">
          Zoology - Wildlife - Animal diseases        <span class="badge">1</span>
        </a>
    </div> -->
</div>
</div>
</div>      
                    
                      <div class="row">&nbsp;</div>
                    
          </div>
                  </div>
          </section><!-- /.content -->