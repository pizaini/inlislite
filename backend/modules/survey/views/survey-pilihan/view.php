<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\SurveyPilihan $model
 */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Survey Pilihan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-pilihan-view">
 	<p> 
    	<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
   		<!-- <a class="btn btn-primary" href="update?id=<?php $model->ID ?>">Simpan</a>         -->
   		<a class="btn btn-danger" href="delete?id=<?= $model->ID ?>" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    
   		<!-- <a class="btn btn-warning" href="index">Kembali</a>         -->
    	<?= Html::a('Kembali', Yii::$app->request->referrer,['class' => 'btn btn-warning' ]); ?>
   	</p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            'Survey_Pertanyaan_id',
            'Pilihan:ntext',
            'ChoosenCount',
        ],
       
    ]) ?>

</div>
