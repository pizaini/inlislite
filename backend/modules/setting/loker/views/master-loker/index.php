<?php

use yii\helpers\Html;
use yii\widget\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Locker'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="loker-default-index">
    <h3>Setting Locker</h3>
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        Ini Halaman : <b style="color: red; display:inline; font-size: 20px;"> </b><br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
