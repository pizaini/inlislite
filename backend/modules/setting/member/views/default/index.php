<?php
use yii\helpers\Url;

?>
<div class="settingMember-default-index">
    <h1><?=Yii::t('app','Master Members')?></h1>
     <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Application buttons -->
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Daftar Master</h3>
                  </div>
                  <div class="box-body">
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/kartu-anggota'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Members Card')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/redaksi-keanggotaan'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Editors Rule Membership')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/jenis-anggota'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Member Type')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/master-jenis-identitas'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Type Identity')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/master-pekerjaan'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Pekerjaan')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/pendidikan'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Pendidikan')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/kelompok-umur'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Kelompok Umur')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/kelas'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Kelas')?>
                    </a>
                     <a class="btn btn-app" href="<?=Url::to(['/setting/member/fakultas'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Fakultas')?>
                    </a>
                     <a class="btn btn-app" href="<?=Url::to(['/setting/member/jurusan'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Jurusan')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/biaya-pendaftaran'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Biaya Pendaftaran')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/biaya-perpanjangan'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Biaya Perpanjangan')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/masa-berlaku-anggota'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Masa Berlaku Anggota')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/jenis-kelamin'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Jenis Kelamin')?>
                    </a>
                    <a class="btn btn-app" href="<?=Url::to(['/setting/member/agama'])?>">
                      <i class="fa fa-list-alt"></i> <?=Yii::t('app','Agama')?>
                    </a>
                  </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div>
    </section>
</div>
