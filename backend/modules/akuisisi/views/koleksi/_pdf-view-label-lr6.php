<?php 
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>

	<?php foreach ($LabelData as $LabelData): ?>
		<table style="width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<?php ($LabelData['Warna1'] == '') ? $warna='' : $warna=';background-color:'.$LabelData['Warna1']; ?>
				<td style="border:solid 1px #CCC; height:47px; text-align: center; padding: 5px; font-size: 12px <?=$warna?>"><?=$LabelData['NamaPerpustakaan']?></td>
			</tr>
			<tr>
				<td style="height:79px; border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; text-align: center"><?=\common\components\CollectionHelpers::getLabelCallNumber($LabelData['CallNumber'])?></td>
			</tr>
		</table>
	<?php endforeach ?>
					