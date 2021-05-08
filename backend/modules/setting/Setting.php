<?php

namespace backend\modules\setting;

class Setting extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\setting\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->modules = [
            'akuisisi' => [
                // you should consider using a shorter namespace here!
                'class' => 'backend\modules\setting\akuisisi\Akuisisi',
            ],
            'article' => [
                'class' => 'backend\modules\setting\article\article',
            ],
            'katalog' => [
                // you should consider using a shorter namespace here!
                'class' => 'backend\modules\setting\katalog\Katalog',
            ],
            'member' => [
                'class' => 'backend\modules\setting\member\Member',
            ],
            'umum' => [
                'class' => 'backend\modules\setting\umum\Umum',
            ],
            'checkpoint' => [
                'class' => 'backend\modules\setting\checkpoint\Checkpoint',
            ],
            'opac' => [

                'class' => 'backend\modules\setting\opac\opac',
            ],
            'digitalcollection' => [

                'class' => 'backend\modules\setting\digitalcollection\digitalcollection',
            ],
            'audio' => [

                'class' => 'backend\modules\setting\audio\Audio',
            ],
            'sirkulasi' => [
                'class' => 'backend\modules\setting\sirkulasi\sirkulasi',
            ],
            'loker' => [
                'class' => 'backend\modules\setting\loker\Loker',
            ],
            'sms' => [

                'class' => 'backend\modules\setting\sms\sms',
            ],
            'updater' => [

                'class' => 'backend\modules\setting\updater\updater',
            ], 
			'deposit' => [

                'class' => 'backend\modules\setting\deposit\Deposit',
            ], 

        ];
    }
}
