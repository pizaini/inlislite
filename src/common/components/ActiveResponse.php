<?php
/**
 * @package   srusakov\yii2-activeresponse
 * @author    Sergey Rusakov <srusakov@gmail.com>
 * @copyright Copyright &copy; Sergey Rusakov, 2015
 * @version   1.0
 */

namespace common\components;
use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\View;
/**
 * Yii2 ajax module with active control from server side
 * @see http://github.com/srusakov/yii2-activeresponse
 * @since 1.0
 */
class ActiveResponse extends Component {
    /**
     * @var array Массив действий для исполнения на стороне клиента
     */
    public $result = [
        'actions' => array(), // массив действий, которые надо будет выполнить на стороне клиента
        'error' => null, // сообщение об ошибке, если будет
        'return2callback' => null, // значение, которое можно возвратить в callback функцию, указанную при $.callPHP
    ];
    /**
     * Registers the needed assets
     * Use it in layout file as:
     *   ActiveResponse::registerAssets($this)
     * @param View $view Current layout
     */
    public static function registerAssets($view)
    {
        ActiveResponseAsset::register($view);
        $js = "window.actireresponse_root_url='" . Url::to(['/']) . "';";
        $view->registerJs($js);
    }
    public function __toString() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return \yii\helpers\Json::encode($this->result);
    }
    /**
     * Alert a message with window.alert(msg)
     * @param string $msg
     */
    function alert($msg)
    {
        $this->result['actions'][] = array('act' => 'alert', 'msg' => $msg);
        return $this;
    }
    /**
     * jQuery(#item).val(val)
     */
    function val($item, $val)
    {
        $this->result['actions'][] = array('act' => 'val', 'item' => $item, 'val' => $val);
        return $this;
    }
    /**
     * jQuery(#item).html(val)
     */
    function html($item, $val)
    {
        $this->result['actions'][] = array('act' => 'html', 'item' => $item, 'val' => $val);
        return $this;
    }
    /**
     * jQuery(#item).attr(attr,val)
     */
    function attr($item, $attr, $val)
    {
        $this->result['actions'][] = array('act' => 'attr', 'item' => $item, 'attr' => $attr, 'val' => $val);
        return $this;
    }
    /**
     * jQuery(#item).css(attr,val)
     */
    function css($item, $attr, $val)
    {
        $this->result['actions'][] = array('act' => 'css', 'item' => $item, 'attr' => $cssattr, 'val' => $val);
        return $this;
    }
    /**
     * location.href = href
     */
    function redirect($href)
    {
        $this->result['actions'][] = array('act' => 'redirect', 'href' => $href);
        return $this;
    }
    /**
     * execute javascript with eval()
     */
    function script($script)
    {
        $this->result['actions'][] = array('act' => 'script', 'script' => $script);
        return $this;
    }
    /**
     * jQuery(#item).focus()
     */
    function focus($fieldname)
    {
        $this->result['actions'][] = array('act' => 'script', 'script' => "\$('#{$fieldname}').focus();");
        return $this;
    }
    /**
     * jQuery(#item).addClass(class)
     */
    function addClass($fieldname, $class)
    {
        $this->result['actions'][] = array('act' => 'script', 'script' => "\$('#{$fieldname}').addClass('{$class}');");
        return $this;
    }
    /**
     * jQuery(#item).removeClass(class)
     */
    function removeClass($fieldname, $class)
    {
        $this->result['actions'][] = array('act' => 'script', 'script' => "\$('#{$fieldname}').removeClass('{$class}');");
        return $this;
    }
    /**
     * jQuery(#item).show(item)
     */
    function show($fieldname)
    {
        $this->script("\$('#{$fieldname}').show();");
        return $this;
    }
    /**
     * jQuery(#item).hide(item)
     */
    function hide($fieldname)
    {
        $this->script("\$('#{$fieldname}').hide();");
        return $this;
    }
    /**
     * jQuery(#item).fadeIn(item)
     */
    function fadeIn($fieldname)
    {
        $this->script("\$('#{$fieldname}').fadeIn();");
        return $this;
    }
    /**
     * jQuery(#item).fadeOut(item)
     */
    function fadeOut($fieldname)
    {
        $this->script("\$('#{$fieldname}').fadeOut();");
        return $this;
    }
}