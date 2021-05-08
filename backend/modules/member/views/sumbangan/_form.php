<?php

use yii\helpers\Html;

use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\jui\AutoComplete;


/**
 * @var yii\web\View $this
 * @var common\models\Sumbangan $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="sumbangan-form">

    <div class="page-header">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        

        echo '&nbsp;' . Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-warning']);
        ?>
        </div>

    <?php
    $member_name = (new \yii\db\Query())
        ->from('members')
        //\common\models\Members::find()
        ->select(['(CONCAT(MemberNo," - ",Fullname)) as label'])
        //->asArray()
        ->all();

    echo $form->field($model, 'MemberNo', [
    ])->widget(AutoComplete::className(), [
        'options' => [
            'class' => 'form-control',
            'placeholder' => 'Masukan No.Anggota',
            'style' => 'width:300px;',
            'maxlength' => 255,
        ],
        'clientOptions' => ['source' => $member_name]
    ])->label(Yii::t('app', 'Anggota'));
    ?>

    <?php  /*echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

//'Member_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Member ID').'...']],

'Jumlah'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Jumlah').'...', 'maxlength'=>10]], 

'Keterangan'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'Keterangan').'...', 'maxlength'=>45]], 

    ]


    ]);*/
    ?>
    <?php

        

            echo $form->field($model, 'Jumlah')->textInput([
                'placeholder' => Yii::t('app', 'Jumlah'),
                'style'=>'width:300px;',
                'maxlength' => 255,
            ]);
        
        ?>
     <?=
        $form->field($model, 'Keterangan', [
                /* 'template' => '<span class="input-group-addon" style="width: 136px">'.Yii::t('app','Address') .' *</span>{input}',
                  'options'  => [
                  'class' => 'input-group form-group'
                  ], */
        ])->textArea([
            'placeholder' => Yii::t('app', 'Keterangan'),
            'style' => 'width:350px;',
            'maxlength' => 255,
        ])
        ?>
 
 

<!-- Tambah Koleksi Sumbangan -->
<div class="collection-detail">
   <div class="col-sm-12" style="padding: 0px; padding-bottom: 20px">
   <div class="box-title" style="background-color: #f4f4f4; padding:8px; font-size: 12px; font-weight: bold; border-top: dotted 1px #CCC;">Daftar Koleksi
       <span class="pull-right">
           <?php echo  '&nbsp;' . Html::a(Yii::t('app', 'Pilih Koleksi'), ['pilih-judul'], ['class' => '','data-toggle'=>"modal",
                                                    'data-target'=>"#pilihsalin-modal",
                                                    'data-title'=>"Pilih Koleksi",]);?>
       </span>
   </div>
        <div class="box box-default">
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <div id="koleksi-item">
                    <!-- load koleksi -->
                    <?php 

                         // mendapatkan data
                        $daftarItem = Yii::$app->sirkulasi->getItemSumbangan();
                         echo \Yii::$app->view->render('_listKoleksi',
                            array(
                                'daftarItem'=>$daftarItem,
                                 'n' => 1,
                            ),true);
                    ?>

                </div>
            </div>
            <!-- /.box-body -->
          </div>
   </div>
   

</div>
<!-- ./Tambah Koleksi Sumbangan -->

</div>


