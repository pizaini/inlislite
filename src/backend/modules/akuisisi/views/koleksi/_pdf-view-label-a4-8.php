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
					
		<table style="width:212px; " cellpadding="0" cellspacing="0">
			<tr>
				<td style="text-align: center; width:60px; height: 212px" rowspan="2">
						<?php 
						echo '<img rotate="-90" style="height:190px; ; width:39px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($LabelData['Barcode'], $generator::TYPE_CODE_39,1)) . '">';
						?>
						<svg width='10' height='100'><text x="-100" y="10" transform='rotate(-90)'>*<?=$LabelData['Barcode']?>*</text></svg>
				</td>
				<?php ($LabelData['Warna1'] == '') ? $warna='' : $warna=';background-color:'.$LabelData['Warna1']; ?>
				<td style="border:solid 1px #CCC; height:62px; text-align: center; width:212px; padding: 5px; font-size: 12px <?=$warna?>"><?=$LabelData['NamaPerpustakaan']?></td>
			</tr>
			<tr>
				<td style="height:150px; border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; text-align: center"><?=\common\components\CollectionHelpers::getLabelCallNumber($LabelData['CallNumber'])?></td>
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
					