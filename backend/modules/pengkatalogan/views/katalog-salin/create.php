<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Collections $model
 */

$this->title = Yii::t('app', 'Salin Katalog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ' Daftar Salin Katalog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="collections-create">
    <div class="page-body">
            <?= $this->render('_form',['library'=>$library])?>
    </div>
</div>
