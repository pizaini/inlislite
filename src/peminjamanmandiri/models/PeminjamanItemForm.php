<?php
namespace peminjamanmandiri\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

use common\components\MemberHelpers;
use common\components\Helpers;

class PeminjamanItemForm extends Model
{

	public $nomorBarcode;
	public $tglTransaksi;

 	public function rules()
    {
        return [
            ['nomorBarcode', 'filter', 'filter' => 'trim'],
            ['nomorBarcode', 'required'],
            ['tglTransaksi', 'required'],
            //['noAnggota', 'string', 'min' => 2, 'max' => 255],
        ];
    }
}
