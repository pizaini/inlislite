<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('checkpoint', dirname(dirname(__DIR__)) . '/checkpoint');
Yii::setAlias('guestbook', dirname(dirname(__DIR__)) . '/guestbook');
Yii::setAlias('pengembalianmandiri', dirname(dirname(__DIR__)) . '/pengembalianmandiri');
Yii::setAlias('peminjamanmandiri', dirname(dirname(__DIR__)) . '/peminjamanmandiri');
Yii::setAlias('opac', dirname(dirname(__DIR__)) . '/opac');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('uploaded_files', dirname(dirname(__DIR__)) . '/uploaded_files');
Yii::setAlias('bacaditempat', dirname(dirname(__DIR__)) . '/bacaditempat');
Yii::setAlias('keanggotaan', dirname(dirname(__DIR__)) . '/keanggotaan');
Yii::setAlias('digitalcollection', dirname(dirname(__DIR__)) . '/digitalcollection');
Yii::setAlias('article', dirname(dirname(__DIR__)) . '/article');
Yii::setAlias('upload',dirname('.') . '/uploaded_files');
include dirname(dirname(__DIR__)) . '/inliscore/extensions.php';
Yii::setAlias('api', dirname(dirname(__DIR__)) . '/api');

use common\components\OpacHelpers;
//OpacHelpers::clearTemp();