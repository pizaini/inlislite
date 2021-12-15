<?php

namespace inliscore\adminlte;

/**
 * Description of AdminlteTheme
 *
 * @author Henry (dewa) <alvin_vna@yahoo.com>
 */
class Theme extends \yii\base\Theme
{
    public $pathMap = [
        '@backend/views' => ['@inliscore/adminlte/views'],
        '@keanggotaan/views' => ['@inliscore/adminlte/views'],
    ];

}