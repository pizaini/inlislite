<?php
/**
 * @Create: Henry
 * @Date: 16.06.15
 */

namespace inliscore\adminlte\widgets;

use \yii\web\AssetBundle;

class JCookieAsset  extends AssetBundle{
    public $sourcePath = '@bower/jquery-cookie/src';

    public $js
        = [
            'jquery.cookie.js',
        ];

    public $depends=[
        'yii\web\YiiAsset'
    ];
}

