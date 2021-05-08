<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace inliscore\adminlte\widgets;
use Yii;
use yii\helpers\Url;
use yii\bootstrap\Widget;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/**
 * NavBar renders a navbar HTML component.
 *
 * Any content enclosed between the [[begin()]] and [[end()]] calls of NavBar
 * is treated as the content of the navbar. You may use widgets such as [[Nav]]
 * or [[\yii\widgets\Menu]] to build up such content. For example,
 *
 * ```php
 * use yii\bootstrap\NavBar;
 * use yii\widgets\Menu;
 *
 * NavBar::begin(['brandLabel' => 'NavBar Test']);
 * echo Nav::widget([
 *     'items' => [
 *         ['label' => 'Home', 'url' => ['/site/index']],
 *         ['label' => 'About', 'url' => ['/site/about']],
 *     ],
 * ]);
 * NavBar::end();
 * ```
 *
 * @see http://getbootstrap.com/components/#navbar
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class NavBar extends Widget
{
    /**
     * @var array the HTML attributes for the widget container tag. The following special options are recognized:
     *
     * - tag: string, defaults to "nav", the name of the container tag.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var array the HTML attributes for the container tag. The following special options are recognized:
     *
     * - tag: string, defaults to "div", the name of the container tag.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $containerOptions = [];
    /**
     * @var string|boolean the text of the brand of false if it's not used. Note that this is not HTML-encoded.
     * @see http://getbootstrap.com/components/#navbar
     */
    public $brandLabel = "App Name";
    public $brandShortLabel = "<i class='fa fa-th'></i>";
    /**
     * @param array|string|boolean $url the URL for the brand's hyperlink tag. This parameter will be processed by [[Url::to()]]
     * and will be used for the "href" attribute of the brand link. Default value is false that means
     * [[\yii\web\Application::homeUrl]] will be used.
     */
    public $brandUrl = false;
    /**
     * @var array the HTML attributes of the brand link.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $brandOptions = ['style'=>'padding-top:15px;font-size:12px;'];
    /**
     * @var string text to show for screen readers for the button to toggle the navbar.
     */
    public $screenReaderToggleText = 'Toggle navigation';
    /**
     * @var boolean whether the navbar content should be included in an inner div container which by default
     * adds left and right padding. Set this to false for a 100% width navbar.
     */
    public $renderInnerContainer = false;
    /**
     * @var array the HTML attributes of the inner container.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $innerContainerOptions = [];
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->clientOptions = false;
        echo Html::beginTag('header', ['class'=>'main-header']);
        
        ?>
        <?php
        Html::addCssClass($this->options, 'navbar');
        if ($this->options['class'] === 'navbar') {
            Html::addCssClass($this->options, 'navbar-default');
        }
        
        if (empty($this->options['role'])) {
            $this->options['role'] = 'navigation';
        }
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'nav');
        echo Html::beginTag($tag, $options);


        echo $this->renderToggleButton();


        // Logo Perpus
        echo Html::img(Yii::$app->urlManager->createUrl('../uploaded_files/aplikasi/logo_perpusnas_2015.png'), ['alt'=>'logo perpustakaan inlislite', 'class'=>'logo-perpus','height'=>'70px']);

        // Nama Perpus dan Alamat
        Html::addCssClass($this->brandOptions, 'logo hidden-xs');
        echo Html::a(
            Html::tag('span',$this->brandShortLabel,['class'=>'',]).
            Html::tag('span',$this->brandLabel,['class'=>'logo-lg'])
            , $this->brandUrl === false ? Yii::$app->homeUrl : $this->brandUrl , $this->brandOptions);

        ?>
        <div class="collapse navbar-collapse pull-right clockZ" id="navbar-collapse">
            <span class="pull-right" style="color:#fff; margin-right:5px; margin-top:5px" id="clocktime" ></span>
        </div>
        

        
        <?php
        
        if ($this->renderInnerContainer) {
            if (!isset($this->innerContainerOptions['class'])) {
                Html::addCssClass($this->innerContainerOptions, 'container');
            }
            echo Html::beginTag('div', $this->innerContainerOptions);
        }
        Html::addCssClass($this->containerOptions, 'navbar-custom-menu');
        $options = $this->containerOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::beginTag($tag, $options);
    }
    /**
     * Renders the widget.
     */
    public function run()
    {
        $tag = ArrayHelper::remove($this->containerOptions, 'tag', 'div');
        echo Html::endTag($tag);
        if ($this->renderInnerContainer) {
            echo Html::endTag('div');
        }
        $tag = ArrayHelper::remove($this->options, 'tag', 'nav');
        echo Html::endTag($tag, $this->options);
        echo Html::endTag('header');
        ?>
        <?php
        BootstrapPluginAsset::register($this->getView());
    }
    /**
     * Renders collapsible toggle button.
     * @return string the rendering toggle button.
     */
    protected function renderToggleButton()
    {
        $bar = Html::tag('span', '', ['class' => 'icon-bar']);
        $screenReader = "<span class=\"sr-only\">{$this->screenReaderToggleText}</span>";
        return Html::a("{$screenReader}\n{$bar}\n{$bar}\n{$bar}", '#', [
            'class' => 'sidebar-toggle ass',
            // add new
            'style' => ['padding-top'=>'59px'],
            // add new
            'data-toggle' => 'offcanvas',
            'role'=>"button",
        ]);
    }
}
$lang = Yii::$app->config->get('language');
?>
<script>
    $(function(){
        $(document).on('click','.language',function(){
            var lang = $(this).attr('id');
            // var demo = "<?= Yii::$app->urlManager->createUrl('site/language') ?>";

            $.post("<?= Yii::$app->urlManager->createUrl('site/language') ?>",{'lang':lang},function(data){
            // $.post(demo,{'lang':lang},function(data){
                console.log(data);
                location.reload();
            });
        });
    });
    //alert(Date());
    function startTime()
    {   var today=new Date();
        var weekday=new Array(7);
        var weekday=["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"];
        var weekday_en=new Array(7);
        var weekday_en=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        var monthname=new Array(12);
        var monthname=["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        var monthname_en=new Array(12);
        var monthname_en=["January","February","March","April","May","June","July","August","September","October","November","December"];
        var dayname=weekday[today.getDay()];
        var day=today.getDate();
        var month=monthname[today.getMonth()];
        var year=today.getFullYear();
        var h=today.getHours();
        var m=today.getMinutes();
        var s=today.getSeconds();
        h=checkTime(h);
        m=checkTime(m);
        s=checkTime(s);
        if ('<?= $lang ?>'=='en') {
            var dayname=weekday_en[today.getDay()];
            var month=monthname_en[today.getMonth()];
        }else{
            var dayname=weekday[today.getDay()];
            var month=monthname[today.getMonth()];
        }
        document.getElementById('clocktime').innerHTML=dayname+", "+day+" "+month+" "+year+", "+h+":"+m+":"+s;
        t=setTimeout(function(){startTime()},500);
    }
    // function checkTime to add a zero in front of numbers < 10
    function checkTime(i)
    {   if(i<10){i="0"+i;}
        return i;
    }

    setInterval(startTime, 500);
</script>

