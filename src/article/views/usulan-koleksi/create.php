<?php

use yii\helpers\Html;


$this->title = Yii::t('app', 'Create Requestcatalog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requestcatalogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$base=Yii::$app->homeUrl;
?>
<br>
<div class="breadcrumb">
	<ol class="breadcrumb">
		<li><a href="<?=$base; ?>">Home</a></li>
		<li>Usulan Koleksi</li>
	</ol>
</div>

<div class="requestcatalog-create">
    <div class="page-header">
       <center> <h3>Usulan Koleksi</h3> </center>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
