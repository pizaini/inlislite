<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 */

$this->title = Yii::t('app', 'Default cek Lokasi Perpustakaan pada form entri anggota');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['#']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => Url::to(['/setting/member'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-form">
    <div class="page-header">
        <h3><?= Yii::t('app','Untuk Jenis Anggota') ?> <?= Html::encode($jenis) ?></h3>
    </div>
<?php
echo '<p>';
echo Html::button(Yii::t('app', 'Create'), ['id'=>'save', 'class' => 'btn btn-primary',  ]);
echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</p>';

?>
<?php echo GridView::widget([
        'id'=>'members-field',
        'dataProvider' => $dataProvider,

        'pjax' => true, // pjax is set to always true for this demo
        'pjaxSettings' =>[
            'neverTimeout'=>true,
            'options'=>[
                    'id'=>'kv-unique-id-1',
                ]
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'Name',
            ],
            [
                //'name' => 'checkbox-column',
                'header' => 'Tampilkan
                        <input type="checkbox" class="select-on-check-all" name="selection_all" value="1">',
                'class'=>'\kartik\grid\CheckboxColumn',
                'rowSelectedClass'=>GridView::TYPE_SUCCESS,
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    return [
                        'checked' => $searchModel->getTrueFalse($_GET["id"],$searchModel->ID),

                    ];
                }
            ],

        ],

        'responsive'=>true,
        'hover'=>true,
        'condensed'=>false,
        'summary'=>'',
        'options' => ['style' => 'width: 550px;'],
    ]); ?>

<?php
//echo '<p>';
//echo Html::button(Yii::t('app', 'Create'), ['id'=>'save2', 'class' => 'btn btn-primary',  ]);
//echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</p>';



    $js = "$('#save').on('click',function() {
            $.post(
                \"save-lokasi\", {
                    id : $.QueryString[\"id\"],
                    pk : $('#members-field').yiiGridView('getSelectedRows')
                },
                function () {
                    $.pjax.reload({container:'#kv-unique-id-1'});

                }
            );
        });
";


    $this->registerJs($js, $this::POS_READY);

?>
</div>