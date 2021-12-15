<?php 
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>



<?php 
echo '<br/><br/><br/><br/><br/><br/><br/>';
echo '<img style="width:200px" src="data:image/png;base64,' . base64_encode($generator->getBarcode('16030400002', $generator::TYPE_CODE_39,1)) . '">';
echo '<br/>';
echo 'code 39 (1)(200px): 16030400002 ';
            ?>
<?php 
echo '<br/><br/><br/><br/><br/><br/><br/>';
echo '<img style="padding-top:5px;width:25%" src="data:image/png;base64,' . base64_encode($generator->getBarcode('16030400002', $generator::TYPE_CODE_39,1)) . '">';
echo '<br/>';
echo 'code 39 (1)(25%): 16030400002 ';
            ?>