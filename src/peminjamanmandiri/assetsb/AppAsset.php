<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace peminjamanmandiri\assetsb;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       // 'css/site.css',
       'web/css/bootstrap.css',
       'web/css/font-awesome.min.css',
       //'web/css/ionicons.min.css',
       'web/css/AdminLTE.css',
       'web/css/styles.css',
       'web/css/skins/_all-skins.css',
       'web/css/sweetalert.css',
       'web/css/sweetalertoverride.css',
       ];
    public $js = [
        'web/js/popup-print-element.js',
        //'web/js/jQuery-2.1.4.min.js',
        'web/js/bootstrap.min.js',
        'web/js/plugins/jquery.slimscroll.min.js',
        'web/js/plugins/sweetalert.min.js',
        //'web/js/plugins/jQuery.print.js',
        //'web/js/app.min.js',
        //'web/js/demo.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
