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
use common\components\SirkulasiHelpers;
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
						<th style="width:25%">No Anggota</th>
						<th style="width:5%">:</th>
						<td ><?=$model->MemberNo;?></td>
					</tr>
					<?php
				}
				?>



				<!-- ////////////////////////////////////////////////////////////////////// -->

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '13');
				if(!empty($val)){
					?>
					<tr>
						<th>Jenis Identitas</th>
						<th>:</th>
						<td><?=$model->identityType->Nama;?></td>
					</tr>
					<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '14');
				if(!empty($val)){
					?>
					<tr>
						<th>No. Identitas</th>
						<th>:</th>
						<td><?=$model->IdentityNo;?></td>
					</tr>
					<?php
				}
				?>
				<!-- ////////////////////////////////////////////////////////////////////// -->


				<?php
				$val = \yii\helpers\ArrayHelper::getValue($membersLoanForm, ['1']);
				if($val['Member_Field_id'] == "2"){
					?>
					<tr>
						<th style="width:25%">Nama Anggota</th>
						<th style="width:5%">:</th>
						<td ><?=$model->Fullname;?></td>
					</tr>
					<?php
				}
				?>

				<!-- ################################################### -->
				
				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '3');
				if(!empty($val)){
					?>
					<tr>
						<th style="width:25%">Tempat Lahir</th>
						<th style="width:5%">:</th>
						<td ><?=$model->PlaceOfBirth;?></td>
					</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '4');
				if(!empty($val)){
					?>
					<tr>
						<th style="width:25%">Tgl Lahir</th>
						<th style="width:5%">:</th>
						<td ><?=\common\components\Helpers::DateTimeToViewFormat($model->DateOfBirth);?></td>
					</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '5');
				if(!empty($val)){
					?>
					<tr>
						<th style="width:25%">Alamat Sesuai KTP</th>
						<th style="width:5%">:</th>
						<td ><?=$model->Address;?></td>
					</tr>
					<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '7');
				if(!empty($val)){
					?>
					<tr>
						<th style="width:25%">Propinsi</th>
						<th style="width:5%">:</th>
						<td ><?=$model->Province;?></td>
					</tr>
					<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '6');
				if(!empty($val)){
					?>
					<tr>
						<th style="width:25%">Kabupaten / Kota</th>
						<th style="width:5%">:</th>
						<td ><?=$model->City;?></td>
					</tr>
					<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '39');
				if(!empty($val)){
				?>
				<tr>
					<th>Kecamatan</th>
					<th>:</th>
					<td><?=$model->Kecamatan;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '40');
				if(!empty($val)){
				?>
				<tr>
					<th>Kelurahan</th>
					<th>:</th>
					<td><?=$model->Kelurahan;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '41');
				if(!empty($val)){
				?>
				<tr>
					<th>RT</th>
					<th>:</th>
					<td><?=$model->RT;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '42');
				if(!empty($val)){
				?>
				<tr>
					<th>RW</th>
					<th>:</th>
					<td><?=$model->RW;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '8');
				if(!empty($val)){
				?>
				<tr>
					<th>Alamat Tempat Tinggal Sekarang</th>
					<th>:</th>
					<td><?=$model->AddressNow;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '10');
				if(!empty($val)){
				?>
				<tr>
					<th>Propinsi Sekarang</th>
					<th>:</th>
					<td><?=$model->ProvinceNow;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '9');
				if(!empty($val)){
				?>
				<tr>
					<th>Kabupaten/Kota Sekarang</th>
					<th>:</th>
					<td><?=$model->CityNow;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '43');
				if(!empty($val)){
				?>
				<tr>
					<th>Kecamatan Now</th>
					<th>:</th>
					<td><?=$model->KecamatanNow;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '44');
				if(!empty($val)){
				?>
				<tr>
					<th>Kelurahan Now</th>
					<th>:</th>
					<td><?=$model->KelurahanNow;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '45');
				if(!empty($val)){
				?>
				<tr>
					<th>RT Now</th>
					<th>:</th>
					<td><?=$model->RTNow;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '46');
				if(!empty($val)){
				?>
				<tr>
					<th>RW Now</th>
					<th>:</th>
					<td><?=$model->RWNow;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '11');
				if(!empty($val)){
				?>
				<tr>
					<th>No. HP</th>
					<th>:</th>
					<td><?=$model->NoHp;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '12');
				if(!empty($val)){
				?>
				<tr>
					<th>No. Telepon Rumah</th>
					<th>:</th>
					<td><?=$model->Phone;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '15');
				if(!empty($val)){
				?>
				<tr>
					<th>Jenis Kelamin</th>
					<th>:</th>
					<td><?=$model->sex->Name;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '19');
				if(!empty($val)){
				?>
				<tr>
					<th>Pendidikan Terakhir</th>
					<th>:</th>
					<td><?=$model->educationLevel->Nama;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '16');
				if(!empty($val)){
				?>
				<tr>
					<th>Pekerjaan</th>
					<th>:</th>
					<td><?=$model->job->Pekerjaan;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '29');
				if(!empty($val)){
				?>
				<tr>
					<th>Alamat Email</th>
					<th>:</th>
					<td><?=$model->Email;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '25');
				if(!empty($val)){
				?>
				<tr>
					<th>Ibu Kandung</th>
					<th>:</th>
					<td><?=$model->MotherMaidenName;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '26');
				if(!empty($val)){
				?>
				<tr>
					<th>Nama Institusi</th>
					<th>:</th>
					<td><?=$model->InstitutionName;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '27');
				if(!empty($val)){
				?>
				<tr>
					<th>Alamat Institusi</th>
					<th>:</th>
					<td><?=$model->InstitutionAddress;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '28');
				if(!empty($val)){
				?>
				<tr>
					<th>No. Telepon Institusi</th>
					<th>:</th>
					<td><?=$model->InstitutionPhone;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '18');
				if(!empty($val)){
				?>
				<tr>
					<th>Jenis Anggota</th>
					<th>:</th>
					<td><?=$model->jenisAnggota->jenisanggota;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '17');
				if(!empty($val)){
				?>
				<tr>
					<th>Agama</th>
					<th>:</th>
					<td><?=$model->agama->Name;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '36');
				if(!empty($val)){
				?>
				<tr>
					<th>Unit Kerja</th>
					<th>:</th>
					<td><?=$model->unitKerja->Name;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '37');
				if(!empty($val)){
				?>
				<tr>
					<th>Fakultas</th>
					<th>:</th>
					<td><?=$model->fakultas->Nama;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '38');
				if(!empty($val)){
				?>
				<tr>
					<th>Jurusan</th>
					<th>:</th>
					<td><?=$model->jurusan->Nama;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '48');
				if(!empty($val)){
				?>
				<tr>
					<th>Program Studi</th>
					<th>:</th>
					<td><?=$model->programStudi->Nama;?></td>
				</tr>
				<?php
				} 
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '21');
				if(!empty($val)){
				?>
				<tr>
					<th>Tanggal Pendaftaran</th>
					<th>:</th>
					<td><?=\common\components\Helpers::DateTimeToViewFormat($model->TglRegisterDate);?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '22');
				if(!empty($val)){
				?>
				<tr>
					<th>Tanggal Berlaku Akhir</th>
					<th>:</th>
					<td><?=\common\components\Helpers::DateTimeToViewFormat($model->EndDate);?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '24');
				if(!empty($val)){
				?>
				<tr>
					<th>Status Anggota</th>
					<th>:</th>
					<td><?=$model->statusAnggota->Nama;?></td>
				</tr>
				<?php
				}
				?>


				<!-- ############################################################ -->

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '20');
				if(!empty($val)){
				?>
				<tr>
					<th>Status Perkawinan</th>
					<th>:</th>
					<td><?=$model->maritalStatus->Nama;?></td>
				</tr>
				<?php
				}
				?>


				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '23');
				if(!empty($val)){
				?>
				<tr>
					<th>Jenis Permohonan</th>
					<th>:</th>
					<td><?=$model->jenisPermohonan->Name;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '30');
				if(!empty($val)){
				?>
				<tr>
					<th>Nama Darurat</th>
					<th>:</th>
					<td><?=$model->NamaDarurat;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '31');
				if(!empty($val)){
				?>
				<tr>
					<th>Alamat Darurat</th>
					<th>:</th>
					<td><?=$model->AlamatDarurat;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '32');
				if(!empty($val)){
				?>
				<tr>
					<th>No. Telepon Darurat</th>
					<th>:</th>
					<td><?=$model->TelpDarurat;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '33');
				if(!empty($val)){
				?>
				<tr>
					<th>Status Hubungan Darurat</th>
					<th>:</th>
					<td><?=$model->StatusHubunganDarurat;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '34');
				if(!empty($val)){
				?>
				<tr>
					<th>Tahun Ajaran</th>
					<th>:</th>
					<td><?=$model->TahunAjaran;?></td>
				</tr>
				<?php
				}
				?>

				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '35');
				if(!empty($val)){
				?>
				<tr>
					<th>Kelas</th>
					<th>:</th>
					<td><?=$model->kelas->namakelassiswa;?></td>
				</tr>
				<?php
				}
				?>


				<?php
				$val = SirkulasiHelpers::searchArrayByKeyAndValue($membersLoanForm, 'Member_Field_id', '47');
				if(!empty($val)){
					$countItem = count(Yii::$app->sirkulasi->getItem());
	                $maksLoan = \common\components\SirkulasiHelpers::getMaksJumlahPeminjaman($model->ID);
	                $sisaKuota = ($maksLoan - $countItem);
				?>
				<tr>
					<th>Sisa Kuota</th>
					<th>:</th>
					<td><?=$sisaKuota;?></td>
				</tr>
				<?php
				}
				?> 

				<!-- ################################################### -->






				<tr>
					<th>Masa Berlaku Anggota</th>
					<th>:</th>
					<td><?= \common\components\Helpers::DateTimeToViewFormat($model->RegisterDate)?> s/d <?= \common\components\Helpers::DateTimeToViewFormat($model->EndDate)?></td>
				</tr>

				</tbody></table>
		</div>

	</div>
</div>
<br/>