<?php 
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
	
	
	<?php 
	$no=0;
	$item=0;
	$rec=0;
	$jumlahData=count($LabelData);
	foreach ($LabelData as $LabelData): 
	$rec++; 

	if($item == 0){
		echo '<div style="padding:58px;">';
		echo '<table cellspacing="0" cellpadding="0">';
	}

	if($no==0)
	{
		echo '<tr>';
	}


	$location = Yii::$app->location->get();
	$id = \common\models\LocationLibrary::findOne($location);
	$loclib= $id->Name;
	?>

	<td style="width:50%;padding-bottom: 15px; padding-right: 55px; text-align: left;">
		<table style="width:283px; " cellpadding="0" cellspacing="0">
			<tr>
				<td style="border:solid 1px #CCC; height:53px; text-align: center; width:283px; padding: 5px; font-size: 12px; background-color:#000; color:#FFF; "><?=$loclib?><br><?=$LabelData['NamaPerpustakaan']?> 
					
				</td>
			</tr>
			<tr>
				<td style="height:90px; border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; text-align: left; padding-left: 60px;padding-right: 60px"><?=\common\components\CollectionHelpers::getLabelCallNumber($LabelData['CallNumber'])?></td>
			</tr>
			<tr>
				<td style="text-align: center;border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; ">
					<span style="font-size:11px; font-weight: bold">
						<?=$loclib?>
					</span><br>
					<?php 
					echo '<img style="padding-top:5px;width:260px; height:39px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($LabelData['Barcode'], $generator::TYPE_CODE_39,1)) . '">';
					?>
					<br>
					<span style="font-size:12px; ">*<?=$LabelData['Barcode']?>*</span><br>
					<span style="font-size:11px; font-weight: bold"><?=$LabelData['NamaPerpustakaan']?> </span>
				</td>
			</tr>
			<tr>
				<td style="text-align: center;border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; ">
					<span style="font-size:11px; font-weight: bold">
						<?=$loclib?>
					</span><br>
					<?php 
					echo '<img style="padding-top:5px;width:260px; height:39px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($LabelData['Barcode'], $generator::TYPE_CODE_39,1)) . '">';
					?>
					<br>
					<span style="font-size:12px; ">*<?=$LabelData['Barcode']?>*</span><br>
					<span style="font-size:11px; font-weight: bold"><?=$LabelData['NamaPerpustakaan']?> </span>
				</td>
			</tr>
		</table>
	</td>

	<?php

	if($no == 1 || $i == ($jumlahData -1))
    {
       if($i == ($jumlahData -1))
       {
            echo '<td style="width:50%; padding-bottom: 15px;padding-right: 55px; text-align: left;">&nbsp;</td>';
       }
       echo '</tr>';
       $no=0;
    }else{
       $no++;
    }

	if($item == 5 || $rec == $jumlahData)
    {
       echo '</table>';
       echo '</div>';
       $item=0;
    }else{
       $item++;
    }

	?>
	

	<?php
	endforeach 
	?>
					