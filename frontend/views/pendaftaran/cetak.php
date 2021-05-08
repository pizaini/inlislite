<?php
/**
 * Created by PhpStorm.
 * User: Henry <alvin_vna@yahoo.com>
 * Date: 2/23/16
 * Time: 1:48 PM
 */
?>
<center>

    <div class="col-md-12">

        <div class="member-form-success" style="font-size: 12px">
            <h2><?= $model->Fullname ?></h2>
            <p>
                Anda telah terdaftar sebagai anggota <?= Yii::$app->config->get('NamaPerpustakaan'); ?>
            </p>

            <?php
            $tipeNomorAnggota = Yii::$app->config->get('TipeNomorAnggota');

            if (strtolower(trim($tipeNomorAnggota)) == "otomatis") {
                echo "<p>dengan nomor anggota</p>";
            } else {
                echo "<p>dengan nomor anggota sementara</p>";
            }
            ?>
            <h3><?= $model->MemberNo ?></h3>
            <p>
                Silahkan menghubungi petugas layanan keanggotaan untuk mendapatkan kartu anggota anda.
            </p>
            <p>
                Terima Kasih.
            </p>


        </div>


</center>