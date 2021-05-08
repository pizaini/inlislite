<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */

/* @var $this yii\web\View */

$this->title = 'Sukses Pendaftaran Anggota';
?>
<div style="clear:both;"></div>

<center>

    <div class="col-md-12">

        <div class="member-form-success" style="font-size: 12px" >
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
            <?php


            $url2           = \yii\helpers\Url::to('cetak');
            $ajaxOptions    = [
                'type' => 'GET',
                'url'  => $url2,
                'data' => array(
                    'NoAnggota' => $model->MemberNo,
                    'memberID'  => $model->ID,
                ),
                'success'=>new yii\web\JsExpression('function(data){
                    try {
			            	var oIframe = document.getElementById(\'Iframe1Slip\');
			                var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
			                if (oDoc.document) oDoc = oDoc.document;
			                oDoc.write(\'<html><head>\');
			                oDoc.write(\'</head><body onload="this.focus(); this.print(true);" style="text-align: left; font-size: 8pt; width: 95%; height:90%">\');
			                oDoc.write(data + \'</body></html>\');
			                oDoc.close();
		                } catch (e) {
			                alert(e.message);
			                self.print();
			            }

                 }'),
                'error' => new yii\web\JsExpression('function(xhr, ajaxOptions, thrownError){
                   					alert(xhr.responseText);

                   				}'),
            ];

            echo \yii\helpers\Html::a(Yii::t('app', 'Selesai'), ['/pendaftaran/'], ['class' => 'btn btn-md btn-success',]);
            echo "&nbsp;";

			echo \common\widgets\AjaxButton::widget([
                                        'label' => '<i class="glyphicon glyphicon-check"></i> ' .Yii::t('app','Cetak'),
                                        'ajaxOptions' => $ajaxOptions,
                                        'htmlOptions' => [
                                            'class' => 'btn btn-warning',
                                            'id' => 'print',
                                            'type' => 'submit'
                                        ]
                                    ]);


            ?>

        </div>
        <div style="padding-bottom: 150px">&nbsp;</div>

</center>

<iframe id='Iframe1Slip' src='#' class='clsifrm' style="width: 0pt; height: 0pt; border: none;" ></iframe>
<div id="divPrint" style="display:inline;"></div>
