<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */


use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

//Helpers
use common\components\MemberHelpers;



//Get Foto Anggota.

$image = MemberHelpers::getPathFotoAnggota() . 'nophoto.jpg' . "?timestamp=" . rand();
$imageOriginal = MemberHelpers::getPathFotoAnggotaOriginal() . $model->PhotoUrl."?timestamp=" . rand();
//echo $image;
if ($model->PhotoUrl) {
    $img = Yii::getAlias('@uploaded_files') . '/foto_anggota/' . $model->PhotoUrl;
} else $img = Yii::getAlias('@uploaded_files') . '/foto_anggota/' . $model->ID;


if (!file_exists($img)) {
    $image = MemberHelpers::getPathFotoAnggota() . "nophoto.jpg?timestamp=" . rand();
    $imageOriginal = MemberHelpers::getPathFotoAnggotaOriginal() . "nophoto.jpg?timestamp=" . rand();
} else {
    $image = MemberHelpers::getPathFotoAnggota() . $model->PhotoUrl . "?timestamp=" . rand();
    $imageOriginal = MemberHelpers::getPathFotoAnggotaOriginal() . $model->PhotoUrl . "?timestamp=" . rand();
}


?>
<div class="content">
	<div class="col-xs-3">
		<!-- FOTO ANGGOTA -->

		<?php
		echo Html::img($imageOriginal,['id' => 'fotoanggota','class'=>'img-thumbnail', 'style'=>['width'=>'200px','height'=>'183px'],'alt'=>'Foto Anggota']);
		?>



	</div>
	<div class="col-xs-9">
		<div class="table-responsive">
			<table class="table">
				<tbody>
				<?php
				//var_dump($membersLoanForm);

				$val = \yii\helpers\ArrayHelper::getValue($membersLoanForm, ['0']);
				if($val['Member_Field_id'] == "1"){
					?>
					<tr>
						<th style="width:25%"><?= yii::t('app','No Anggota')?></th>
						<th style="width:5%">:</th>
						<td ><?=$model->MemberNo;?></td>
					</tr>
					<?php
				}
				?>
				<?php
				$val = \yii\helpers\ArrayHelper::getValue($membersLoanForm, ['1']);
				if($val['Member_Field_id'] == "2"){
					?>
					<tr>
						<th style="width:25%"><?= yii::t('app','Nama Anggota')?></th>
						<th style="width:5%">:</th>
						<td ><?=$model->Fullname;?></td>
					</tr>
					<?php
				}
				?>
				<?php
				$val = \yii\helpers\ArrayHelper::getValue($membersLoanForm, ['2']);
				if($val['Member_Field_id'] == "3"){
					?>
					<tr>
						<th style="width:25%"><?= yii::t('app','Tempat Lahir')?></th>
						<th style="width:5%">:</th>
						<td ><?=$model->PlaceOfBirth;?></td>
					</tr>
					<?php
				}
				?>
				<?php
				$val = \yii\helpers\ArrayHelper::getValue($membersLoanForm, ['3']);
				if($val['Member_Field_id'] == "4"){
					?>
					<tr>
						<th style="width:25%"><?= yii::t('app','Tanggal Lahir')?></th>
						<th style="width:5%">:</th>
						<td ><?=\common\components\Helpers::DateTimeToViewFormat($model->DateOfBirth);?></td>
					</tr>
					<?php
				}
				?>
				<?php
				$val = \yii\helpers\ArrayHelper::getValue($membersLoanForm, ['4']);
				if($val['Member_Field_id'] == "5"){
					?>
					<tr>
						<th style="width:25%"><?= yii::t('app','Alamat Sesuai KTP')?></th>
						<th style="width:5%">:</th>
						<td ><?=$model->Address;?></td>
					</tr>
					<?php
				}
				?>
				<?php
				$val = \yii\helpers\ArrayHelper::getValue($membersLoanForm, ['6']);
				if($val['Member_Field_id'] == "7"){
					?>
					<tr>
						<th style="width:25%"><?= yii::t('app','Propinsi')?></th>
						<th style="width:5%">:</th>
						<td ><?=$model->Province;?></td>
					</tr>
					<?php
				}
				?>

				<?php
				$val = \yii\helpers\ArrayHelper::getValue($membersLoanForm, ['5']);
				if($val['Member_Field_id'] == "6"){
					?>
					<tr>
						<th style="width:25%"><?= yii::t('app','Kabupaten / Kota')?></th>
						<th style="width:5%">:</th>
						<td ><?=$model->City;?></td>
					</tr>
					<?php
				}
				?>

				<?php
				$val = \yii\helpers\ArrayHelper::getValue($membersLoanForm, ['18']);
				if(!is_null($val)){
					?>
					<tr>
						<th><?= yii::t('app','Jenis Anggota')?></th>
						<th>:</th>
						<td><?=$model->jenisAnggota->jenisanggota;?></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<th><?= yii::t('app','Masa Berlaku Anggota')?></th>
					<th>:</th>
					<td><?= \common\components\Helpers::DateTimeToViewFormat($model->RegisterDate)?> s/d <?= \common\components\Helpers::DateTimeToViewFormat($model->EndDate)?></td>
				</tr>

				</tbody></table>
		</div>

	</div>
</div>
<br/>