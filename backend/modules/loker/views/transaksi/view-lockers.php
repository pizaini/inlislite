<?php

use yii\bootstrap\Button;
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Lockers $model
 */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Lockers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lockers-view">
    <p> 
        <?= Html::a(yii::t('app','Kembali'), Yii::$app->request->referrer,['class' => 'btn btn-warning' ]); ?>
        <?php echo Button::widget([
                // 'label' => 'Action',
            'encodeLabel' => false,
            'label' => '<span class="glyphicon glyphicon-print"></span>'. yii::t('app',' Cetak ulang struk peminjaman'),
            'options' => ['class' => 'btn btn-primary','id' => 'printPeminjaman'],
            ]); ?>

        <?php if (isset( $model->tanggal_kembali )): ?> 
            <?php echo Button::widget([
                // 'label' => 'Action',
                'encodeLabel' => false,
                'label' => '<span class="glyphicon glyphicon-print"></span> Cetak ulang struk pengembalian',
                'options' => ['class' => 'btn btn-danger','id' => 'printPelanggaran'],
                ]); ?>
        <?php endif ?>
      <!--   <a class="btn btn-primary"  href="update?id=<?= $model->ID ?>">Koreksi</a>         -->
       <!--  <a class="btn btn-danger" href="delete?id=<?= $model->ID ?>" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>     -->
    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            [
            'attribute'=>'No_pinjaman',
            'label'=>yii::t('app','Nomor Pinjamansss'),
            ],
            [
            'attribute'=>'no_member',
            'label'=>yii::t('app','Nomor Anggota'),
            ],
            // 'no_identitas',
            // 'jenis_jaminan',

            [
                'attribute'=>'jenis_jaminan',
                'label'=>yii::t('app','Jenis Jaminan'),
                'value'=> $model->jenis_jaminan.' - '.$model->uangJaminan['Name'].$model->jenisIdentitas['Nama'].' '.$model->no_identitas,
            ],  

            // 'id_jamin_idt',
            // 'id_jamin_uang',
            // 'loker.Name',
            [
                'attribute'=>'loker_id',
                'value'=> $model->loker['Name'],
            ],
            
            [
            'attribute'=>'tanggal_pinjam',
            'label'=>yii::t('app','Tgl.Pinjam'),
            ],
            [
            'attribute'=>'tanggal_kembali',
            'label'=>yii::t('app','Tgl.Kembali'),
            ],
            // [
            //     'attribute'=>'loker_id',
            //     'value'=> ( isset( $model->tanggal_kembali ) ) ? $model->tanggal_kembali : "Belum Dikembalikan",
            // ],
            [
                'attribute'=>'id_pelanggaran_locker',
                'value'=> ( isset( $model->pelanggaran['deskripsi'] ) ) ? $model->pelanggaran['deskripsi'].' - Denda ( '.$model->denda.') ' : "Tidak ada pelanggaran",
            ],
        ],
       
    ]) ?>

</div>

<iframe id='Iframe1Slip' src='#' class='clsifrm' style="width: 0pt; height: 0pt; border: none;" ></iframe>
<div id="divPrint" style="display:inline;"></div>


<?php
$urlPinjam = Url::to(['transaksi/cetak']);
$this->registerJs("
    $('#printPeminjaman').click(function(){
        $.get('". $urlPinjam ."', {id: ".$model->ID."},function(data, status){
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
");
 ?>

<?php
$urlPelanggaran = Url::to(['transaksi/cetak-bukti-pelanggaran']);
$this->registerJs("
    $('#printPelanggaran').click(function(){
        $.get('". $urlPelanggaran ."', {id: ".$model->ID."},function(data, status){
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
");
 ?>