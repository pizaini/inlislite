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

$this->title = Yii::t('app', 'Kustomisasi Kolom Daftar Anggota');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'),];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Umum'), ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="members-form">
    <div class="page-header">
        <!-- <h3><?= Html::encode($this->title) ?> | <?= Html::encode($jenis_perpus) ?></h3> -->
        <h3> <?= Yii::t('app','Isian yang dimunculkan untuk kolom daftar anggota') ?> | <?= Html::encode($jenis_perpus) ?></h3>
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
// custom gridview value
                'label'=>yii::t('app','Nama'),
                'value' => function ($data) {
                    return yii::t('app',$data->name);
                },
            ],
            [
                //'name' => 'checkbox-column',
                'header' => 'Tampilkan
                        <input type="checkbox" class="select-on-check-all" name="selection_all" value="1">',
                'class'=>'\kartik\grid\CheckboxColumn',
                'rowSelectedClass'=>GridView::TYPE_SUCCESS,
                'checkboxOptions' => function ($searchModel, $key, $index, $column) {
                    // return [
                    //     'checked' => $searchModel->getDaftarAnggota($_GET["id"],$searchModel->id),

                    // ];
                    if($searchModel->mandatory == '1'){
                        return [
                            'checked' => $searchModel->getTrueFalse($_GET["id"],$searchModel->id),
                            'disabled'=>true,
                            'value'=>1,
                        ];
                    }else{
                        // return [
                        //     'checked' => $searchModel->getTrueFalse($_GET["id"],$searchModel->id),

                        // ];
                        return [
                            'checked' => $searchModel->getDaftarAnggota($_GET["id"],$searchModel->id),

                        ];
                    }
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
// echo '<p>';
// echo Html::button(Yii::t('app', 'Create'), ['id'=>'save2', 'class' => 'btn btn-primary',  ]);
// echo  '&nbsp;' . Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) . '</p>';



    $js = "$('#save,#save2').on('click',function() {
            $.post(
                \"save-daftar-anggota\", {
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