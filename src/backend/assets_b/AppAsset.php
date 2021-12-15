<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets_b;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/assets_b';
    public $css = [
        'css/site.css',
	'css/sweetalertoverride.css',
    ];
    public $js = [
        'js/app.js',
        'js/catalogs.js',
        'js/swal_override.js',
        'js/simpleUpload.min.js',
	'plugins/webcam/webcam.js'

    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
