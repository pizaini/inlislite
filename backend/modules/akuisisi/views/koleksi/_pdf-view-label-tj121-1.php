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
		echo '<div style="padding-top:18.5px; padding-left:29px; padding-bottom:5.6px;padding-right:19px; ">';

		echo '<table cellspacing="0" cellpadding="0">';
	}

	if($no==0)
	{
		echo '<tr>';
	}

	?>

	<td style="width:75%;padding-bottom: 19px; padding-top: 7.8px; padding-right: 15px; padding-left: 7px;  margin-right: 25px; margin-bottom: 50px; margin-top: 50px; text-align: left; ">
		<table style="width:283px; " cellpadding="0" cellspacing="0">
			<tr>
				<td style="border:solid 1px #CCC; height:53px; width:283px; text-align: center; font-size: 16px" colspan="2"><?=$LabelData['NamaPerpustakaan']?></td>
			</tr>
			<tr>
				<td style="height:95px; width:75%; text-align: center;padding-left: 3px; padding-right: 3px;border-left:solid 1px #CCC; border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;">
					<span style="font-size: 12px"><?=$LabelData['Title']?>
					<br>
					<?php 
					echo '<img style="padding-top:5px;width:180px;height:39px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($LabelData['Barcode'], $generator::TYPE_CODE_39,1)) . '">';
					?>
					<br>
					*<?=$LabelData['Barcode']?>*
					</span>
				</td>
				<td style="height:95px; width:90px;border-bottom:solid 1px #CCC;padding-left: 3px; padding-right: 3px; border-right:solid 1px #CCC; text-align: center "><?=$LabelData['CallNumber']?></td>
			</tr>
		</table>
	</td>

	<?php

	if($no == 1 || $i == ($jumlahData -1))
    {
       if($i == ($jumlahData -1))
       {
            echo '<td style="width:50%;padding-bottom: 10px; padding-right: 10px; text-align: left;">&nbsp;</td>';
       }
       echo '</tr>';
       $no=0;
    }else{
       $no++;
    }

	if($item == 9 || $rec == $jumlahData)
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
					