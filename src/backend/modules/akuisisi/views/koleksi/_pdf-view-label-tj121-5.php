<?php 
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
	
	
	<?php 
	// echo '<pre>';print_r($LabelData); die;
	$no=0;
	$item=0;
	$rec=0;
	$jumlahData=count($LabelData);
	// $LabelDatax = '781.629 922 KAC ';
	foreach ($LabelData as $LabelData): 
		if(preg_match_all('/(\b[a-z]{1}\b)+\s/', $LabelData['CallNumber'], $matches, PREG_SET_ORDER) != 0){
    		$fre = end(explode(array_unique(end($matches))[0],$LabelData['CallNumber']));
    		if($fre == ''){
    			$fre = null;
    		}
    	$pop = explode(array_unique(end($matches))[0],$LabelData['CallNumber']);
		array_pop($pop);
		array_push($pop, array_unique(end($matches))[0]);
		$test = preg_split('/(?<=\d)(?=\s[A-Z]|[A-Z])/i', implode(' ',$pop));
		// print_r($test); echo 'as<br>';
		// print_r(reset($test)); echo 'as<br>';
		// print_r(end($test)); echo 'asd<br>';
		// print_r(array_unique(end($matches))[0]); echo '<br>';
		// $ex = explode(' ',rtrim($pop[0],' '));
		// array_push($ex,array_unique(end($matches))[0]);
		$body = $fre .'<br>'.reset($test).str_replace(' ', '<br>', preg_replace("/[[:blank:]]+/"," ",end($test)));
		}else {
			// $fre = null;
			$body = str_replace(' ', '<br>', $LabelData['CallNumber']);
		}
		//echo str_replace(' ', '<br>', ltrim(preg_replace( '/[^a-zA-Z ]/', '',$LabelData['CallNumber']),' '));
		
		// echo implode(' ', $ex);
		// // echo preg_match_all('/(\b[a-z]{1}\b)/', $LabelDatax, $matches, PREG_SET_ORDER);
		// print_r(explode(array_unique(end($matches))[0],$LabelDatax));
		// print_r(array_pop($pop)); echo '<br>';
		// print_r($body);
		// die;
	$rec++;

	if($item == 0){
		echo '<div style="padding-top:17px; padding-bottom:26px; padding-left:0.4px;">';
		echo '<table cellspacing="0" cellpadding="0" border="0" align="center">';
	}

	if($no==0)
	{
		echo '<tr>';
	}

	?>

	<td style="border:none 0.2px blue; height:153px; padding: 4.5px; padding-bottom: -1.4px; width:50%; text-align: left;">
		<table style="border:none; width:277px;" cellpadding="0" cellspacing="0">
			<tr>
				<td style="border:solid 0.2px #ccc; height:139px; width:211.5px; text-align: center; " >
			<span style="font-size: 18px">
				<?php echo $body?>
			</span>
				</td>
			</tr>
		</table>
	</td>

	<?php

	if($no == 1 || $i == ($jumlahData -1))
    {
       if($i == ($jumlahData -1))
       {
            echo '<td style="width:50%; padding-bottom: 10px; padding-right: 10px; text-align: left;">&nbsp;</td>';
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
