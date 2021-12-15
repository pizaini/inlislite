<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Agama $model
 */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agamas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agama-view">
   <p> <?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Remove'), ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
            'model' => $model,


        'attributes' => [
            'Name',
        ],

    ]) ?>

</div>
