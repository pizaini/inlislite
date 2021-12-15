<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\MemberSearch $searchModel
 */

$this->title = Yii::t('app', 'Profil Anggota');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-2">
<?php

//$memberID = $model->ID;
$memberID = ($model->PhotoUrl) ? $model->PhotoUrl : $model->ID;


$imgCheck = Yii::getAlias('@uploaded_files') . '/'.Yii::$app->params['pathFotoAnggota'].'/' . $memberID;

if (!file_exists($imgCheck)) {
    // No.Photo
    $image = '../../uploaded_files/'.Yii::$app->params['pathFotoAnggota'].'/nophoto.jpg?timestamp=' . rand();
}else{
    $image = '../../uploaded_files/'.Yii::$app->params['pathFotoAnggota'].'/'. $memberID .'?timestamp=' . rand();
}
$data = array('MemberNo', 'Fullname', 'PlaceOfBirth', 'DateOfBirth', 'Address', 'City', 'Province', 'AddressNow', 'CityNow', 'ProvinceNow', 'NoHp', 'Phone', 'IdentityType_id', 'IdentityNo', 'Sex_id', 'Job_id', 'Agama_id', 'JenisAnggota_id', 'EducationLevel_id', 'MaritalStatus_id', 'RegisterDate','EndDate','JenisPermohonan_id','StatusAnggota_id','MotherMaidenName','InstitutionName','InstitutionAddress','InstitutionPhone','Email','NamaDarurat','AlamatDarurat','TelpDarurat', 'StatusHubunganDarurat','TahunAjaran','Kelas_id','UnitKerja_id','Fakultas_id','Jurusan_id','Kecamatan','Kelurahan','RT','RW','KecamatanNow','KelurahanNow','RTNow','RWNow','ProgramStudi_id','JenjangPendidikan_id' );

/*echo "<pre>";
print_r($membersInfoForm);
die;*/
?>
  <img class="img-thumbnail" src="<?=$image?>" alt="User Profile Photo">

</div>
<div class="col-md-10">
    <table class="table table-striped">
        <tbody>

            <?php
            for ($i=0,$x=1; $i <49 ; $i++,$x++) { 
            
                if ($membersInfoForm[$i]['Member_Field_id'] == ($x)) {
                echo "<tr>";
                // echo'<pre>';print_r($membersInfoForm[$i]['Member_Field_id']);
                echo " <td class='col-md-2' style='font-weight: bold'>".Yii::t('app',$data[$i])."</td>";
                echo "<td class='col-md-1'>:</td>";

                if($membersInfoForm[$i]['Member_Field_id'] == 13){
                    if($model->$data[$i]){
                        $identitas = Yii::$app->db->createCommand("SELECT Nama FROM master_jenis_identitas WHERE id = ".$model->$data[$i]."")->queryOne();
                        echo "<td>".$identitas['Nama']."</td>"; 
                    }else{
                        echo "<td>-</td>";
                    }

                }else if($membersInfoForm[$i]['Member_Field_id'] == 15){
                    if($model->$data[$i]){
                        $jenis_kelamin = Yii::$app->db->createCommand("SELECT Name FROM jenis_kelamin WHERE ID = ".$model->$data[$i]."")->queryOne();
                        echo "<td>".$jenis_kelamin['Name']."</td>"; 
                    }else{
                        echo "<td>-</td>";
                    }

                }else if($membersInfoForm[$i]['Member_Field_id'] == 16){
                    if($model->$data[$i]){
                        $pekerjaan = Yii::$app->db->createCommand("SELECT Pekerjaan FROM master_pekerjaan WHERE id = ".$model->$data[$i]."")->queryOne();
                        echo "<td>".$pekerjaan['Pekerjaan']."</td>"; 
                    }else{
                        echo "<td>-</td>";
                    }

                }else if($membersInfoForm[$i]['Member_Field_id'] == 17){
                    if($model->$data[$i]){
                        $agama = Yii::$app->db->createCommand("SELECT Name FROM agama WHERE ID = ".$model->$data[$i]."")->queryOne();
                        echo "<td>".$agama['Name']."</td>"; 
                    }else{
                        echo "<td>-</td>";
                    }

                }else if($membersInfoForm[$i]['Member_Field_id'] == 18){
                    if($model->$data[$i]){
                        $jenisanggota = Yii::$app->db->createCommand("SELECT jenisanggota FROM jenis_anggota WHERE id = ".$model->$data[$i]."")->queryOne();
                        echo "<td>".$jenisanggota['jenisanggota']."</td>"; 
                    }else{
                        echo "<td>-</td>";
                    }
                    
                }else if($membersInfoForm[$i]['Member_Field_id'] == 19){
                    if($model->$data[$i]){
                        $pendidikan = Yii::$app->db->createCommand("SELECT Nama FROM master_pendidikan WHERE id = ".$model->$data[$i]."")->queryOne();
                        echo "<td>".$pendidikan['Nama']."</td>";
                    }else{
                        echo "<td>-</td>";
                    }
                    
                }else{
                    if($model->$data[$i]){
                        echo "<td>".$model->$data[$i]."</td>";
                    }else{
                        echo "<td>-</td>";
                    }
                    
                }
                // echo "<td>".$model->$data[$i]."</td>";
                echo "</tr>";
                }

            }

            ?>
        </tbody>
    </table>
</div>
