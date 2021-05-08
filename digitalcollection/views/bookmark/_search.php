<?php
use common\widgets\Alert;
use yii\helpers\Html;
use common\components\DirectoryHelpers;
$homeUrl=Yii::$app->homeUrl;
$detail_url=Yii::$app->urlManager->createAbsoluteUrl('detail-opac');
$pengarang_url=Yii::$app->urlManager->createAbsoluteUrl('pencarianSederhana');
$pengarang=explode(";", $dataResult[1]['author']);



if($dataResult[1]['CoverURL'])
{

     if(file_exists(Yii::getAlias('@uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($dataResult[1]['worksheet_id']).'/'.$dataResult[1]['CoverURL'])))
    {
         $urlcover= '../uploaded_files/sampul_koleksi/original/'.DirectoryHelpers::GetDirWorksheet($dataResult[1]['worksheet_id']).'/'.$dataResult[1]['CoverURL'];
    }
    else {
       $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
        }
    }else{
        $urlcover= '../uploaded_files/sampul_koleksi/original/Monograf/tdkada.gif';
    }


$page= ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
$limit= ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
foreach (Yii::$app->session->getAllFlashes() as $message):; 
    echo \kartik\widgets\Growl::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
        'showSeparator' => true,
        'delay' => 1, //This delay is how long before the message shows
        'pluginOptions' => [
            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
            'placement' => [
                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
            ]
        ]
    ]);
     endforeach; ?>
									<tr><td>
								<div id="search<?= $dataResult[1]['CatalogId'];?>">
								<div class="row">
									<div class="col-sm-1"><?php if($page==1){echo 1;} else { echo ($page-1)*$limit+1;}?> &nbsp; <input type="checkbox" name="catID[]" value="<?= $dataResult[1]['CatalogId'];?>"  > <input type="hidden"  id='catalogID<?= $dataResult[1]['CatalogId'];?>'  name="catalogID" value="<?= $dataResult[1]['CatalogId'];?>"> &nbsp;<?php echo"<a onclick=\"favorite(".$dataResult[1]['CatalogId'].")\" href=\"javascript:void(0)\"  title=\"Tambah ke Favorite\"><span class=\"glyphicon glyphicon-star\"></span></a>";  ?>
									</div>
									<div class="col-md-2"><img src="<?= $urlcover ?>"style="width:97px ; height:144px" ></div> 
									<div class="col-md-9">
										<table class="table" style="background:transparent">
											<tr>
												<th colspan="2"> <a href="<?= $detail_url; ?>?id=<?= $dataResult[1]['CatalogId']; ?>" class="topnav-content"> <?= $dataResult[1]['kalimat2']?> </a></th>
												</tr>
											<tr>
												<td width="22%">Jenis Bahan</td>
												<td width="78%"><?= $dataResult[1]['worksheet'];?></td>
											</tr>
											<?php
											for($x=0;$x<sizeof($pengarang);$x++){
											if($x==0){
												echo"
												<tr>
												<td>Pengarang</td>
												<td><a href=\"?action=pencarianSederhana&ruas=Pengarang&bahan=".$dataResult[1]['worksheet_id']."&katakunci=".$dataResult[1]['author']."\"> ".$pengarang[$x]." </a></td>
												</tr>
												";
											} else
											{

											 echo"
												<tr>
												<td></td>
												<td><a href=\"?action=pencarianSederhana&ruas=Pengarang&bahan=".$dataResult[1]['worksheet_id']."&katakunci=".$dataResult[1]['author']."\"> ".$pengarang[$x]." </a></td>
												</tr>
												";
											}

											}
											?>

											<tr>
												<td>Penerbitan</td>
												<td><?= $dataResult[1]['PublishLocation']." ".   $dataResult[1]['publisher']; echo $dataResult[1]['PublishYear'];?></td>
											</tr>
											<tr>
												<td>Konten Digital</td>
												<td> <?php  if($dataResult[1]['KONTEN_DIGITAL']==NULL){$dataResult[1]['KONTEN_DIGITAL']="Tidak Ada Data";} else {echo"<a data-toggle='collapse' data-target='#collapseKontenDigital".$dataResult[1]['CatalogId']."'  class='show_hide' id='showmenu".$dataResult[1]['CatalogId']."' onclick='kontenDigital(".$dataResult[1]['CatalogId'].")' href='javascript:void(0)' >"; } echo $dataResult[1]['KONTEN_DIGITAL'];?> </td>
											</tr>
											<tr>
												<td>Ketersediaan</td>
												<td> <?php if($dataResult[1]['ALL_BUKU']!=0){echo"<a data-toggle='collapse' data-target='#collapsecollection".$dataResult[1]['CatalogId']."'  id='showmenu".$dataResult[1]['CatalogId']."' onclick='collection(".$dataResult[1]['CatalogId'].")' href='javascript:void(0)' >"; } echo $dataResult[1]['JML_BUKU']." dari ".$dataResult[1]['ALL_BUKU']." ekslempar"; ?> </a></td>
											</tr>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-1">&nbsp;</div>
									<div class="col-sm-11">
										<div class="collapse" id="collapseKontenDigital<?= $dataResult[1]['CatalogId'];?>">
											<div id="kontenDigitalShow<?= $dataResult[1]['CatalogId'];?>">

											</div>
										</div>
										<br>
										<div class="collapse" id="collapsecollection<?= $dataResult[1]['CatalogId'];?>">
											<div id="collectionShow<?= $dataResult[1]['CatalogId'];?>">

											</div>
										</div>
									</div>
								</div>
								</div>
										</td></tr>