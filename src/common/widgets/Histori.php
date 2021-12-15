<?php
/**
 *  Histori List
 *
 *  Date    : 2016-03-02
 *  Author  : Henry <alvin_vna@yahoo.com>
 *
 * @author Henry <alvin_vna@yahoo.com>
 */
namespace common\widgets;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

use common\models\Users;
use inliscore\adminlte\widgets\Box;

class Histori extends \yii\base\Widget {

    public $model;
    public $id;
    public $labelLink = "History Update";
    public $urlHistori = "#";
    

    public function init() {
        parent::init();
    }

    public function run() {

        $modelUserCreate = Users::findOne($this->model->CreateBy);
        $modelUserUpdate = Users::findOne($this->model->UpdateBy);

        $createDate = \common\components\Helpers::DateTimeIndonesiaFormat($this->model->CreateDate);
        $createTerminal = $this->model->CreateTerminal;
        $updateDate = \common\components\Helpers::DateTimeIndonesiaFormat($this->model->UpdateDate);
        $updateTerminal = $this->model->UpdateTerminal;

        echo '<div class="col-sm-12" style="padding-top: 10px">';
        Box::begin([
            'type'=>Box::TYPE_INFO,
            'solid'=>false,
            //'title'=>'Header',
            'withBorder'=>true,
            'collapse_remember'=>'false',
            'collapse'=>false
        ]);
        echo "<table class=\"tablea\">";
        echo "<tbody>";

        echo "<tr>
                  <th style=\"width: 150px\">Create By</th>
                  <th style=\"width: 10px\">:</th>
                  <th>$modelUserCreate->username ($modelUserCreate->Fullname)</th>
                </tr>";
        echo "<tr>
                  <th style=\"width: 150px\">Create Date</th>
                  <th style=\"width: 10px\">:</th>
                  <th>$createDate</th>
                </tr>";
        echo "<tr>
                  <th style=\"width: 150px\">Create Terminal</th>
                  <th style=\"width: 10px\">:</th>
                  <th>$createTerminal</th>
                </tr>";

        echo "<tr>
                  <th style=\"width: 150px\">Last Update By</th>
                  <th style=\"width: 10px\">:</th>
                  <th>$modelUserUpdate->username ($modelUserUpdate->Fullname)</th>
                </tr>";
        echo "<tr>
                  <th style=\"width: 150px\">Last Update Date</th>
                  <th style=\"width: 10px\">:</th>
                  <th>$updateDate</th>
                </tr>";
        echo "<tr>
                  <th style=\"width: 150px\">Last Update Terminal</th>
                  <th style=\"width: 10px\">:</th>
                  <th>$updateTerminal</th>
                </tr>";

        echo "<tr>
                  <th colspan='3' style=\"width: 150px\"><br/>".
                Html::a(Yii::t('app', $this->labelLink), [$this->urlHistori], ['class' => '','data-toggle'=>"modal",
                'data-target'=>"#myModal-".$this->id,
                'data-title'=>"Detail Data History",])
            ."</th>

                </tr>";


        echo "</tbody>";
        //echo $this->model->CreateDate;
        echo "</table>";
        Box::end();
        echo '</div>';
        
        \yii\bootstrap\Modal::begin([
            'id' => 'myModal-'.$this->id,
            'size'=>'modal-lg',
            'header' => '<h4 class="modal-title">...</h4>',
        ]);

        echo '...';

        \yii\bootstrap\Modal::end();

        
        $this->registerJS();
    }
    
    public function registerJS() {
        $view = $this->getView();
        $js = "
        isLoading = false;
        $('#myModal-$this->id').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var modal = $(this)
        var title = button.data('title') 
        var href = button.attr('href') 
        modal.find('.modal-title').html(title)
        modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
        $.post(href)
            .done(function( data ) {
                modal.find('.modal-body').html(data)
            });
        })";
                
        
        $view->registerJs($js);
    }

}