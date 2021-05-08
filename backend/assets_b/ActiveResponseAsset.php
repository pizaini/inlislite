<?php

/**
 * @package   srusakov\yii2-activeresponse
 * @author    Sergey Rusakov <srusakov@gmail.com>
 * @copyright Copyright &copy; Sergey Rusakov, 2015
 * @version   1.0
 */

namespace backend\assets_b;

use yii\web\AssetBundle;


/**
 * Asset bundle for ActiveResponse
 *
 * @see http://github.com/srusakov/yii2-activeresponse
 * @since 1.0
 */
class ActiveResponseAsset extends AssetBundle
{
    public $sourcePath = '@web/assets_b';
    public $basePath = '@webroot';
    public $baseUrl = '@web/assets_b';

    public $js = [
        'js/activeresponse.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
}