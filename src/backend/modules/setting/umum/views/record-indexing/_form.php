<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use kartik\widgets\ActiveForm;

    /**
     * @var yii\web\View $this
     * @var common\models\Collectioncategorys $model
     */

    $this->title = Yii::t('app', 'Pengaturan Nama Perpustakaan');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), ];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Umum'), ];
    $this->params['breadcrumbs'][] = $this->title;

    ?>

    <style type="text/css">
        .col-sm-4 label {
            font-weight: normal;
        }

        .table {
            margin-bottom: 0px;
        }
    </style>


    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="settingparameters-create">
        <div class="page-header">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => ' btn btn-primary']) ?>
        </div>

        <div class="settingparameters-form">
            <div class="col-sm-9">



                <div class="form-horizontal"> <!-- hidden="hidden" -->
                    <table class="table" style="table-layout: fixed;">
                        <thead>
                        <tr>
                            <td class="col-sm-3"><label
                                        class="control-label"><?= Yii::t('app', 'Aktifkan Indexer') ?></label></td>
                            <td>
                                <div class="col-sm-4" style="padding: 0;margin-left: 13px;">
                                    <?= $form->field($model, 'Value1')->checkbox(['label'=>'Ya'])->label(false); ?>

                                </div>
                                <div class="padding0 col-sm-9"><b class="hint-uang"></b></div>
                            </td>
                        </tr>
                        </thead>
                    </table>
                </div>


                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>




