<?php

namespace common\widgets;

use yii\web\AssetBundle;

class BarcodeGeneratorAssets extends AssetBundle {

    /**
     * @inherit doc
     */
    public function init() {
        $this->sourcePath = __DIR__ . '/assets';
        $this->js = ['jquery-barcode.min.js'];
        $this->depends = ['yii\web\YiiAsset' ];
        parent::init();
    }

}