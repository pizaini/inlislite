<?php
namespace peminjamanmandiri\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

use common\components\MemberHelpers;
use common\components\Helpers;

class PeminjamanForm extends Model
{

	public $noAnggota;


 	public function rules()
    {
        return [
            ['noAnggota', 'filter', 'filter' => 'trim'],
            ['noAnggota', 'required'],
            ['noAnggota', 'string', 'min' => 2, 'max' => 255],
        ];
    }
}
