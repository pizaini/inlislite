<?php 
use yii\helpers\Url;

?>
<div class="settingKatalog-default-index">
    <h1>Master Katalog</h1>
     <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Application buttons -->
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Daftar Master</h3>
                  </div>
                  <div class="box-body">
                    <a class="btn btn-app" href="<?=Url::to('katalog/tag')?>">
                      <i class="fa fa-list-alt"></i> Tag
                    </a>
                    
                    <a class="btn btn-app" href="<?=Url::to('katalog/referensi')?>">
                      <i class="fa fa-list-alt"></i> Referensi
                    </a>

                    <a class="btn btn-app" href="<?=Url::to('katalog/lembar-kerja')?>">
                      <i class="fa fa-list-alt"></i> Lembar Kerja 
                    </a>

                    <a class="btn btn-app" href="<?=Url::to('katalog/format-kartu')?>">
                      <i class="fa fa-list-alt"></i> Format Kartu
                    </a>

                    <a class="btn btn-app" href="<?=Url::to('katalog/warna-ddc')?>">
                      <i class="fa fa-list-alt"></i> Warna DDC
                    </a>

                    <a class="btn btn-app" href="<?=Url::to('katalog/parameter-katalog-detail')?>">
                      <i class="fa fa-list-alt"></i> Pengaturan Detail Katalog
                    </a>

                    <a class="btn btn-app" href="<?=Url::to('katalog/penyedia-katalog')?>">
                      <i class="fa fa-list-alt"></i> Penyedia Katalog
                    </a>

                    <a class="btn btn-app" href="<?=Url::to('katalog/parameter-katalog')?>">
                      <i class="fa fa-list-alt"></i> Pengaturan Lainnya
                    </a>

                  </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div>
    </section>
</div>
