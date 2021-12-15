<?php



use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\LetterDetail $model
 */

$this->title = $model->TITLE;
$this->params['breadcrumbs'][] = ['label' => 'Letter Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letter-detail-view">
   <p> <a class="btn btn-warning" href="/inlis32/backend/gii">Kembali</a>        <a class="btn btn-primary" href="/inlis32/backend/gii/default/update?id=%24model-%3Eid">Koreksi</a>        <a class="btn btn-danger" href="/inlis32/backend/gii/default/delete?id=%24model-%3Eid" data-confirm="Apakah Anda yakin ingin menghapus item ini?" data-method="post">Hapus</a>    </p>



    <?= DetailView::widget([
            'model' => $model,
            
        'attributes' => [
            // 'LETTER_DETAIL_ID',
            'SUB_TYPE_COLLECTION',
            'TITLE',
            'QUANTITY',
            'COPY',
            'PRICE',
            'LETTER_ID',
            'COLLECTION_TYPE_ID',
            'REMARK',
            'AUTHOR',
            'PUBLISHER',
            'PUBLISHER_ADDRESS',
            'ISBN',
            'PUBLISH_YEAR',
            'PUBLISHER_CITY',
            'ISBN_STATUS',
            'KD_PENERBIT_DTL',
        ],
       
    ]) ?>

</div>
