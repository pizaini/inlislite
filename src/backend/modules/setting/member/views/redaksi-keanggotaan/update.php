<?php

use yii\helpers\Html;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var common\models\Memberrules $model
 */

$this->title = Yii::t('app', 'Update Memberrules') ;//. ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => Url::to(['/setting/member'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Memberrules'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php

$this->registerJs('

$(".textarea").wysihtml5();

');

?>
<div class="memberrules-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
