<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MasterKependudukanSearch $searchModel
 */

?>

<?php  //echo $this->render('_searchPenduduk', ['model' => $searchModel,'rules' => $rules]); ?>


<div class="table-responsive">
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'responsive'=>true,
        //'hover'=>true,
        
        'filterSelector' => 'select[name="per-page"]',
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nomorkk',
            'nik',
            [
                         //'label'=>'Nama',
                         'format'=>'raw',
                         'attribute'=>'namalengkap',
                         'value' => function($data){
                             $url = Url::to(['update','id'=>$data->id]);
                             return Html::a($data->namalengkap, '#',
                                [
                                'title' => $data->namalengkap,
                                'onclick'=>"
                                    $.ajax({
                                        type     :'POST',
                                        cache    : false,
                                        url  : 'bind-penduduk?id=".$data->id."',
                                        success  : function(response) {
                                            var data =  $.parseJSON(response);
                                            //alert(data.id);
                                            $('#members-identitytype_id').val(data.identityNik).trigger('change');
                                            $('#members-identityno').val(data.nik);
                                            $('#members-fullname').val(data.nama);
                                            $('#members-placeofbirth').val(data.tempatLahir);
                                            $('#members-tgllahir').val(data.tglLahir);
                                            $('#members-address').text(data.alamat);
                                            $('#members-addressnow').text(data.alamat);
                                            $('#members-sex_id').val(data.jenisKelamin).trigger('change');
                                            $('#members-educationlevel_id').val(data.pendidikan).trigger('change');
                                            $('#members-job_id').val(data.pekerjaan).trigger('change');
                                            $('#members-maritalstatus_id').val(data.statusKawin).trigger('change');
                                            $('#members-agama_id').val(data.agama).trigger('change');
                                            $('#members-statusanggota_id').val(1).trigger('change'); //Otomatis Set Status Jadi Baru
                                            $('#myModal').modal('hide');
                                        }
                                    });return false;",
                                ]
                                ); 
                         }
            ],
            //'namalengkap',
            'al1',
             'alamat', 
             'lhrtempat', 
             'lhrtanggal', 
             'ttl', 
             'umur', 
             'jenis', 
             'sts', 
             'agm', 
             'pendidikan', 
             'pekerjaan', 
        ],
        
    ]);  ?>

</div>

