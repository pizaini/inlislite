<?php 
use yii\helpers\Url;

?>
<div class="settingOpac-default-index">
    <h1>Master Setting Digital Collection</h1>
     <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Application buttons -->
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Daftar Master</h3>
                  </div>
                  <div class="box-body">
                   <a class="btn btn-app" href="<?=Url::to('koleksi-unggulan')?>">
                      <i class="fa fa-list-alt"></i> Koleksi Unggulan
                    </a>
                     <a class="btn btn-app" href="<?=Url::to('koleksi-terbaru')?>">
                      <i class="fa fa-list-alt"></i> Koleksi Terbaru
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('koleksi-sering-dipinjam')?>">
                      <i class="fa fa-list-alt"></i> Koleksi Sering Dipinjam
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('faced-setting')?>">
                      <i class="fa fa-list-alt"></i> Setting Faset
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('booking-setting')?>">
                      <i class="fa fa-list-alt"></i> Pengusulan Koleksi
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('history-digital-collection')?>">
                      <i class="fa fa-list-alt"></i> History LKD
                    </a>
                   
                   
                    
                    
                  
                  </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div>
    </section>
</div>
