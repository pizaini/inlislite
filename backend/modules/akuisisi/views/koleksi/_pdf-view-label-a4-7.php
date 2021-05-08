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

	?>

	<td style="width:50%;padding-bottom: 25px; padding-right: 55px; text-align: left;">
		<table style="width:283px; " cellpadding="0" cellspacing="0">
			<tr>
			<?php ($LabelData['Warna1'] == '') ? $warna='' : $warna=';background-color:'.$LabelData['Warna1']; ?>
				<td style="border:solid 1px #CCC; height:53px; text-align: center; width:283px; padding: 5px; font-size: 12px <?=$warna?>"><?=$LabelData['NamaPerpustakaan']?></td>
			</tr>
			<tr>
				<td style="height:90px; border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; text-align: center"><?=\common\components\CollectionHelpers::getLabelCallNumber($LabelData['CallNumber'])?></td>
			</tr>
			<tr>
				<td style="text-align: center;">
					<?php 
					echo '<img style="padding-top:5px;width:260px; height:39px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($LabelData['Barcode'], $generator::TYPE_CODE_39,1)) . '">';
					?>
					<br>
					*<?=$LabelData['Barcode']?>*
				</td>
			</tr>
		</table>
	</td>

	<?php

	if($no == 1 || $i == ($jumlahData -1))
    {
       if($i == ($jumlahData -1))
       {
            echo '<td style="width:50%;padding-bottom: 25px; padding-right: 55px; text-align: left;">&nbsp;</td>';
       }
       echo '</tr>';
       $no=0;
    }else{
       $no++;
    }

	if($item == 7 || $rec == $jumlahData)
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
					