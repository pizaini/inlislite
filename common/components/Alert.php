<?php
/**
 * @file    Alert.php
 * @date    19/11/2015
 * @author  Henry <alvin_vna@yahoo.com>
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license
 */


namespace common\components;
use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Html;



/*
* Usage
*
* Once the extension is installed, simply add widget to your page as follows:
*
* 1) Default usage, render all flash messages stored in session flash via Yii::$app->session->setFlash().
*
* echo Alert::widget(); 
* 2) Custom usage example:
*
* echo \common\components\Alert::widget([
*        'type' => \common\components\Alert::TYPE_WARNING,
*        'options' => [
*            'title' => 'Success message',
*            'text' => "You will not be able to recover this imaginary file!",
*            'confirmButtonText'  => "Yes, delete it!",
*            'cancelButtonText' =>  "No, cancel plx!"
*        ]
* ]);
*
* Alert Options
* You can find them on the options page http://t4t5.github.io/sweetalert/ 
*
*/

/**
 * 
 * Alert widget renders a message from session flash or custom messages.
 * @package common\components\alert
 * @author Henry <alvin_vna@yahoo.com>
 */
class Alert extends Widget
{
    /**
     * Info type of the alert
     */
    const TYPE_INFO = 'info';
    /**
     * Error type of the alert
     */
    const TYPE_ERROR = 'error';
    /**
     * Success type of the alert
     */
    const TYPE_SUCCESS = 'success';
    /**
     * Warning type of the alert
     */
    const TYPE_WARNING = 'warning';
    /**
     * @var string the type of the alert to be displayed. One of the `TYPE_` constants.
     * Defaults to `TYPE_SUCCESS`
     */
    public $type = self::TYPE_SUCCESS;
    /**
     * All the flash messages stored for the session are displayed and removed from the session
     * Default true.
     * @var bool
     */
    public $useSessionFlash = true;
    /**
     * @var bool If set to true, the user can dismiss the modal by clicking outside it.
     */
    public $allowOutsideClick = true;
    /**
     * @var int Auto close timer of the modal. Set in ms (milliseconds). default - 1,5 second
     */
    public $timer = 1500;
    /**
     * Plugin options
     * @var array
     */
    public $options = [];
    /**
     * Initializes the widget
     */
    public function init()
    {
        parent::init();
        if ($this->useSessionFlash) {
            $session = \Yii::$app->getSession();
            $flashes = $session->getAllFlashes();
            foreach (Yii::$app->session->getAllFlashes() as $message){
                    if(!empty($message['type'])){
                        $this->options['type'] = (!empty($message['type'])) ? $message['type'] : $this->type;
                    } 
                    
                    
                    $this->options['title'] = ' ';
                    $this->options['text'] = (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!';
                    $session->removeFlash((!empty($message['type'])) ? $message['type'] : $this->type);
            }
            /*foreach ($flashes as $type => $data) {
                $data = (array)$data;
                foreach ($data as $message) {
                    $this->options['type'] = $type;
                    $this->options['title'] = $message;
                }
                $session->removeFlash($type);
            }*/
        }
    }
    /**
     * Render alert
     * @return string|void
     */
    public function run()
    {
        $this->registerAssets();
    }
    /**
     * Register client assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        \backend\assets_b\AlertAsset::register($view);
        if ($this->useSessionFlash) {
            $js = 'swal(' . $this->getOptions() . ');';
            $view->registerJs($js, $view::POS_END);
        }
    }
    /**
     * Get plugin options
     * @return string
     */
    public function getOptions()
    {
        $this->options['allowOutsideClick'] = $this->allowOutsideClick;
        //$this->options['timer'] = $this->timer;
        $this->options['type'] = $this->type;
        return Json::encode($this->options);
    }
}