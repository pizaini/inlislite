<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace article\assets_b;

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
       
        
        
        'css/_all-skins.css',
        'css/ionicons.min.css',
        'css/bootstrap.css',
        'css/font-awesome.min.css',
       
        'css/style-slider.css',
        'css/datatables.css',
        'css/AdminLTE.css',
        'css/site.css',
        'css/styles.css',
        
        
    ];
     public $js = [
        'js/app.js',
        'js/bootstrap.min.js',
        'js/datatables.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
