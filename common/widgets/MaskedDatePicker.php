<?php
/**
 *
 * @copyright Copyright &copy; Henry<alvin_vna@yahoo.com>, 2016
 * 
 */

namespace common\widgets;

use kartik\date\DatePicker;
use yii\widgets\MaskedInputAsset;

/**
 * Implements inputMask jquery plugin in conjunction with DatePicker jquery plugin
 * 
 * All techniques are performed according to the logic of widgetTrait package @see https://github.com/kartik-v/yii2-krajee-base.
 *
 * Class MaskedDatePicker
 * @author Henry <alvin_vna@yahoo.com>
 * @see http://finasmart.com
 * @see https://github.com/kartik-v/yii2-widget-datepicker
 */
class MaskedDatePicker extends DatePicker
{

    /**
     * to include or not inputmask for this field
     * @var bool
     */
    public $enableMaskedInput = false;
    /**
     * Parameters input mask jquery plugin 
     * property transfer in the form of a nested array 'plugin Options'
     * events transmitted in the form of a nested array 'plugin Events'
     * format mask as the value of the array element 'mask'
     * example:
     * $maskedInputOptions = [
     *      'mask' => '99.99.9999',
     *      'pluginOptions' => [
     *      ],
     *      'pluginEvents' => [
     *          'complete' => "function(){console.log('complete');}"
     *      ],
     * ]
     * @see https://github.com/RobinHerbots/jquery.inputmask 
     * Important! Event must be specified without the prefix 'in' (ie 'on Complete' => 'complete')
     * @var array
     */
    public $maskedInputOptions;

    /**
     * Configure the settings widget.
     *
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        /*$this->pluginOptions = isset($this->maskedInputOptions['pluginOptions']) ? $this->maskedInputOptions['pluginOptions'] : [];*/
        $this->pluginEvents = isset($this->maskedInputOptions['pluginEvents']) ? $this->maskedInputOptions['pluginEvents'] : [];

        if(isset($this->maskedInputOptions['mask'])) {
            $this->pluginOptions['mask'] = $this->maskedInputOptions['mask'];
        }

    }

    /**
     * Runs widget.
     *
     * @inheritdoc
     */
    public function run()
    {
        parent::run();
        if($this->enableMaskedInput) {
            $this->registerClientScript();
        }
    }

    /**
     * Registers necessary scripts for masked Input jquery plugin.
     * 
     * If you use range datepicker - mask will be used for the second field.
     * 
     */
    public function registerClientScript()
    {
        $element = "jQuery('#" . $this->options['id'] . "')";
        MaskedInputAsset::register($this->getView());
        $this->registerPlugin('inputmask', $element);
        if(isset($this->options2['id'])){
            $element2 = "jQuery('#" . $this->options2['id'] . "')";
            $this->registerPlugin('inputmask', $element2);
        }
    }
}