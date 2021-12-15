<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
?>
<div class="col-md-4">
    <div id="frameFoto" class="img-frame">

        <video id="inlis_camera" width="320" height="240" autoplay></video>
        <canvas id="snapshot" width="320" height="240" style="display: none"></canvas>

        <form>
            <div id="pre_take_buttons" style="padding-top: 10px">
                <button class="btn btn-primary" type="button" onClick="preview_snapshot()"><span class="glyphicon glyphicon-camera"></span> <?= Yii::t('app','Ambil Foto') ?> </button>
            </div>
            <div id="post_take_buttons" style="display:none;padding-top: 10px">

                <button class="btn btn-primary" type="button" onClick="cancel_preview()"><span class="glyphicon glyphicon-camera"></span> <?= Yii::t('app','Ulangi Foto') ?></button>
                <button class="btn btn-warning" type="button" onClick="save_photo()"><span class="glyphicon glyphicon-save"></span> <?= Yii::t('app','Simpan Foto') ?></button>

            </div>
        </form>
        <?php
            $this->registerJs("
            
                var videoTracks;
                var player = document.getElementById('inlis_camera'); 
                var snapshotCanvas = document.getElementById('snapshot');
                  

                var handleSuccess = function(stream) {
                    // Attach the video stream to the video element and autoplay.
                    player.srcObject = stream;
                    videoTracks = stream.getVideoTracks();

                };

                  

                navigator.mediaDevices.getUserMedia({video: true}).then(handleSuccess);

                function preview_snapshot() {
                    var context = snapshot.getContext('2d');
                    context.drawImage(player, 0, 0, snapshotCanvas.width, 
                        snapshotCanvas.height);
                    $('#snapshot').show();
                    $('#inlis_camera').hide();

                    document.getElementById('pre_take_buttons').style.display = 'none';
                    document.getElementById('post_take_buttons').style.display = '';
                    videoTracks.forEach(function(track) {track.stop()});

                }

                function cancel_preview() {
                    // cancel preview freeze and return to live camera feed
                    $('#inlis_camera').show();
                    $('#snapshot').hide();
                    
                    // swap buttons back
                    document.getElementById('pre_take_buttons').style.display = '';
                    document.getElementById('post_take_buttons').style.display = 'none';

                    navigator.mediaDevices.getUserMedia({video: true})
                      .then(handleSuccess);
                }

                function save_photo() {
                    var dataUrl = snapshotCanvas.toDataURL('image/jpeg');
                    $.ajax({
                    type: \"POST\",
                    url: \"save-foto?id=$model->ID\",
                    data: { 
                    imgBase64: dataUrl
                    }
                    }).done(function(msg) {
                        location.reload();
                    // console.log('saved');
                    // Do Any thing you want
                    });
                }

                "
                ,yii\web\View::POS_END);
        ?>
    </div>
</div>
<div class="col-md-8">
    <?= yii::t('app','Unggah Foto Anggota')?>
    <?php echo FileInput::widget([
        'name' => 'image',
        'options'=>[
            'accept' => 'image/*'
        ],
        'pluginOptions' => [

            'showPreview' => false,
            'showCaption' => true,
            'showRemove' => false,
            'showUpload' => true,
            'browseLabel' => '',
            'removeLabel' => Yii::t('app','Remove'),
            'uploadLabel' => Yii::t('app','Upload'),
            'uploadUrl' => Url::to(['/member/member/upload-foto-anggota?id='.$model->ID]),
            'allowedFileExtensions'=> ["jpg", "jpeg"],
            'msgInvalidFileExtension'=>Yii::t('app','Invalid extension for file "{name}". Only "{extensions}" files are supported.'),
            'minImageWidth'=> 1004,
            'minImageHeight'=> 638,
        ]
    ]);?>
</div>