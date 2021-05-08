<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package _form.php
 * @version 1.0.0
 * @author Henry <alvin_vna@yahoo.com>
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

// Kartik Widgets
//use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;
use kartik\widgets\Typeahead;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\daterange\DateRangePicker;



/**
 * @var yii\web\View $this
 * @var common\models\Members $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="members-form">

<?= $form->errorSummary($model); ?>
    <div class="col-sm-6">



        <?=$form->field($modelDynamic, 'Fullname', [

                ])->textInput([
                    'placeholder' => $model->getAttributeLabel('Fullname'),
                    'style'=>'width:354px;text-transform: uppercase',
                    'maxlength'=>255,
                   'readonly'=> true
                 ])?>

    <?=$form->field($model2, 'DestinationNumber', [

                ])->textInput([
                    'placeholder' => $model2->getAttributeLabel('DestinationNumber'),
                    'style'=>'width:350px;',
                    'maxlength'=>255,
                 ])?>

    <?=$form->field($model2, 'TextDecoded', [

                ])->textArea([
                'rows' => '6',
                'maxlength' => 1000
                ])->label(yii::t('app','Pesan')); ?>


           


