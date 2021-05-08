<?php 
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>

	<?php 
	$no=0;
	$item=0;
	$rec=0;
	$jumlahData=count($LabelData);
	// echo '<pre>'; print_r($LabelData); die;
	foreach ($LabelData as $LabelData): 
	$rec++; 

	if($item == 0){
		echo '<div style="padding:10px -40px;">';
		echo '<table cellspacing="0" cellpadding="0">';
	}

	if($no==0)
	{
		echo '<tr>';
	}
	?>
	
	<td style="width:90%;padding-bottom: 10px; padding-right: -55px; text-align: left;">
		
		<table style="width:300px; height: 40px;" cellpadding="0" cellspacing="0">
			<tr>
				<td style="text-align: center; width:60px; height: 212px" rowspan="7">
						<?php 
						// echo '<img rotate="-90" style="height:190px; ; width:39px" src="data:image/png;base64,' . base64_encode($generator->getBarcode($LabelData['Barcode'], $generator::TYPE_CODE_39,1)) . '">';
						?>
						<!-- <svg width='10' height='100'><text x="-100" y="10" transform='rotate(-90)'>*<?//=$LabelData['Barcode']?>*</text></svg> -->
				</td>
				
				<td style="border:solid 1px #CCC; height:62px; text-align: center; width:212px; padding: 0px; font-size: 12px "><?=$LabelData['NamaPerpustakaan']?></td>
			</tr>
			<?php ($LabelData['Warna1'] == '') ? $warna1='' : $warna1=';background-color:'.$LabelData['Warna1']; ?>
			<?php ($LabelData['Warna2'] == '') ? $warna2='' : $warna2=';background-color:'.$LabelData['Warna2']; ?>
			<?php ($LabelData['Warna3'] == '') ? $warna3='' : $warna3=';background-color:'.$LabelData['Warna3']; ?>
			<?php ($LabelData['Warna4'] == '') ? $warna4='' : $warna4=';background-color:'.$LabelData['Warna4']; ?>
			<?php ($LabelData['Warna5'] == '') ? $warna5='' : $warna5=';background-color:'.$LabelData['Warna5']; ?>

			<tr>
				<td style="border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; text-align: center">

				<table width="100%" height="600px" cellspacing="1" cellpadding="3" bgcolor="#FFF" style="margin: 0px">
					<tr>
						<td style="height: 30px; text-align: center <?=$warna1?>"><?=$LabelData['KodeWarna1']?></td>
					</tr>
					<tr>
						<td style="height: 30px; text-align: center <?=$warna2?>"><?=$LabelData['KodeWarna2']?></td>
					</tr>
					<tr>
						<td style="height: 30px; text-align: center <?=$warna3?>"><?=$LabelData['KodeWarna3']?></td>
					</tr>
					<tr>
						<td style="height: 30px; text-align: center <?=$warna4?>"><?=$LabelData['KodeWarna4']?></td>
					</tr>
					<tr>
						<td style="height: 30px; text-align: center <?=$warna5?>"><?=$LabelData['KodeWarna5']?></td>
					</tr>
				</table>

				</td>
			</tr>
			
			<tr>
				<td style="height: 40px; font-size: 14px;border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; text-align: center"><?=$LabelData['CallNumber']?></td>
			</tr>
		</table>
	</td>

	<?php

	if($no == 2 || $i == ($jumlahData -1))
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

	if($item == 11 || $rec == $jumlahData)
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
					