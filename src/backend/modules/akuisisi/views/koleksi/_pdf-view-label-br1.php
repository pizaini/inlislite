<?php 
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>

	<?php foreach ($LabelData as $LabelData): ?>
		<table style="width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td style="height: 71px;min-width:100%; text-align: center; border:solid 1px #CCC;">
					<span style="font-size: 12px">
					<?php 
					echo '<img style="padding-top:5px;width:80%; height:39px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($LabelData['Barcode'], $generator::TYPE_CODE_39,1)) . '">';
					?>
					<br>
					*<?=$LabelData['Barcode']?>*
					</span>
				</td>
			</tr>
		</table>
	<?php endforeach ?>
					