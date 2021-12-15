<?php 
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;

Modal::begin([
  'id' => 'crop-modal',
  'options' => [
        'max-height' => '500px',
        'max-width' => '650px',
        //'style'=>['width'=>'550px']
  ],
  'header' => '<h4 class="modal-title" id="myModalLabel">Sesuaikan Ukuran Foto</h4>',
    
  
]);


echo \newerton\jcrop\jCrop::widget([
    // Image URL
    'url' => $imageOriginal,
    // options for the IMG element
    'imageOptions' => [
        'id' => 'imageId',
        //'width' => '100%',
        'alt' => 'Crop this image',
        'style' => 'max-width:1000px'
    ],
    
    // Jcrop options (see Jcrop documentation [http://deepliquid.com/content/Jcrop_Manual.html])
    'jsOptions' => array(
        'minSize' => [150, 150],
        'aspectRatio' => 131 / 144,
        'setSelect' => [0, 0, 150, 150],
        'onRelease' => new yii\web\JsExpression("function() {ejcrop_cancelCrop(this);}"),
        //customization
        //'bgColor' => '#FF0000',
        
        'bgColor' => 'white',
        'bgOpacity' => '0.5',
        'boxWidth' => '440',
        'selection' => true,
        'theme' => 'light',

                        
    ),
    // if this array is empty, buttons will not be added
    'buttons' => array(
        'start' => array(
                'label' => Yii::t('app', 'Sesuaikan ukuran foto'),
                'htmlOptions' => array(
                    'class' => 'btn btn-primary',
                //'style' => 'color:red;' // make sure style ends with « ; »
                )
         ),
        'crop' => array(
                'label' => Yii::t('app', 'Potong'),
                'htmlOptions' => array(
                    'class' => 'btn btn-success',
                //'style' => 'color:red;' // make sure style ends with « ; »
                )
            ),
            'cancel' => array(
                'label' => Yii::t('app', 'Batal'),
                'htmlOptions' => array(
                    'class' => 'btn btn-danger',
                //'style' => 'color:red;' // make sure style ends with « ; »
                )
            )
    ),
    // URL to send request to (unused if no buttons)
    'ajaxUrl' => 'crop',
    // Additional parameters to send to the AJAX call (unused if no buttons)
   'ajaxParams' => array('NoAnggota' => $model->ID),
]);

?>
                <style>
                /* Dirty Workaround against bootstrap and jcrop */
                img {
                    max-width: none;
                }

                .jcrop-keymgr {
                    display: none !important;
                }

            </style>

<?php
Modal::end();
?>

