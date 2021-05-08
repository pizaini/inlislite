<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace inliscore\adminlte\widgets;

use \yii\bootstrap\Alert as BootstrapAlert;
use \yii\bootstrap\Widget;

/**
 * Alert widget renders a message from session flash for AdminLTE alerts. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', '<b>Alert!</b> Danger alert preview. This alert is dismissable.');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Henry <alvin_vna@yahoo.com>
 */
class AlertKartik extends Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the array:
     *       - class of alert type (i.e. danger, success, info, warning)
     *       - icon for alert AdminLTE
     */
    public $alertTypes = [
        'error' => [
            'class' => 'alert-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
        'danger' => [
            'class' => 'alert-danger',
            'icon' => '<i class="icon fa fa-ban"></i>',
        ],
        'success' => [
            'class' => 'alert-success',
            'icon' => '<i class="icon fa fa-check"></i>',
        ],
        'info' => [
            'class' => 'alert-info',
            'icon' => '<i class="icon fa fa-info"></i>',
        ],
        'warning' => [
            'class' => 'alert-warning',
            'icon' => '<i class="icon fa fa-warning"></i>',
        ],
    ];

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    /**
     * Initializes the widget.
     * This method will register the bootstrap asset bundle. If you override this method,
     * make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $message) {

                    \kartik\widgets\Growl::widget([
                        'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
                        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
                        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
                        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
                        'showSeparator' => true,
                        'delay' => 1, //This delay is how long before the message shows
                        'pluginOptions' => [
                            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
                            'placement' => [
                                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                            ]
                        ]
                    ]);
                }

                $session->removeFlash($type);
            }
        }
    }
}
