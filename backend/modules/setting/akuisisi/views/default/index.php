<?php 
use yii\helpers\Url;

?>
<div class="settingAkuisisi-default-index">
    <h1>Master Akuisisi</h1>
     <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Application buttons -->
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Daftar Master</h3>
                  </div>
                  <div class="box-body">
                    <a class="btn btn-app" href="<?=Url::to('akuisisi/rekanan')?>">
                      <i class="fa fa-list-alt"></i> Rekanan
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('akuisisi/kategori-koleksi')?>">
                      <i class="fa fa-list-alt"></i> Kategori Koleksi
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('akuisisi/sumber-koleksi')?>">
                      <i class="fa fa-list-alt"></i> Sumber Koleksi
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('akuisisi/media-koleksi')?>">
                      <i class="fa fa-list-alt"></i> Media Koleksi
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('akuisisi/lokasi')?>">
                      <i class="fa fa-list-alt"></i> Lokasi
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('akuisisi/mata-uang')?>">
                      <i class="fa fa-list-alt"></i> Mata Uang
                    </a>
                    <a class="btn btn-app" href="<?=Url::to('akuisisi/lembar-kerja-akuisisi')?>">
                      <i class="fa fa-list-alt"></i> Lembar Kerja Akuisisi
                    </a>
                      <a class="btn btn-app" href="<?=Url::to('akuisisi/nomor-induk')?>">
                      <i class="fa fa-list-alt"></i> Nomor Induk
                    </a>
                  
                  </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div>
    </section>
</div>
