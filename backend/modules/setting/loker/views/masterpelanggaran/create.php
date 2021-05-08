<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\MasterPelanggaranLocker $model
 */

$this->title = 'Tambah Master Pelanggaran Locker';
// $this->params['breadcrumbs'][] = ['label' => 'Master Pelanggaran Lockers', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
// 
$this->params['breadcrumbs'][] = Yii::t('app', 'Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loker'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' =>  $this->title];

?>
<div class="master-pelanggaran-locker-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
