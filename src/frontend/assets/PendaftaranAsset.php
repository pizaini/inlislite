<?php
/**
 * @link http://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 20015 INLISlite v3.0
 * @license http://www.inlislite.perpusnas.go.id/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Henryc <alvin_vna@yahoo.com>
 * @since 1.0
 */
class PendaftaranAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/frontend/assets';
    public $css = [
        'css/login.css',
        'css/site.css',
        'css/styleslog.css',
        //'css/tab.css',
    ];
    public $js = [
        'js/app.js',
		'js/bootstrap.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}
