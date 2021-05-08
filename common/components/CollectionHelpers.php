<?php

namespace common\components;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collections;
use common\models\Tempnoinduk;
use common\models\MasterKelasBesar;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class CollectionHelpers extends Collections
{
    

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public static function getLatestNomorInduk($format)
    {
        $result;
        $sqlNoInduk;
        $sqlNoIndukExtra = 'MAX(CONVERT(SUBSTRING(NoInduk,1,IF(INSTR(NoInduk, "/") <> 0, INSTR(NoInduk, "/"), LENGTH(NoInduk)) - 1), UNSIGNED INTEGER))';
        $yearnow = Date('Y');
        if(strtolower($format)=='manual')
        {
            $sqlNoInduk= 'SELECT '.$sqlNoIndukExtra.' AS NoInduk FROM tempnoinduk WHERE NoInduk LIKE "%/'.$yearnow.'"';
            $result = Tempnoinduk::findBySql($sqlNoInduk)->one()->NoInduk;
            if(!$result)
            {
                $sqlNoInduk= 'SELECT '.$sqlNoIndukExtra.' AS NoInduk FROM collections WHERE NoInduk LIKE "%/'.$yearnow.'"';
                $result = Collections::findBySql($sqlNoInduk)->one()->NoInduk;
            }
        }else{
            $sqlNoInduk= 'SELECT '.$sqlNoIndukExtra.' AS NoInduk FROM collections WHERE NoInduk LIKE "%/'.$yearnow.'"';
            $result = Collections::findBySql($sqlNoInduk)->one()->NoInduk;
        }

        return $result;
        
    }

    public static function getLabelCallNumber($string)
    {
        $t =  explode(" ",$string);
        $val = "";
        $status="";
        foreach ($t as $key => $value)
        {
            if(is_numeric($value))
            {
                if($key==0)
                {
                    $add = '';
                }else{
                    $add = ' ';
                }
                
                if($status == 'non numeric')
                {
                    $add .= '<br />';
                }
                $val .= $add.$value;
                $status = 'numeric';
            }else{
                if($key==0)
                {
                    $add = '';
                }else{
                    $add = '<br />';
                }
                $val .= $add.$value;
                $status = 'non numeric';
            }
        }
        return $val;
    }
    

    public static function cetakLabel($format,$column,$barcodeSource,$callnumberSource,$ids) 
    {
        $jumlahData =  count($ids);
        $result='';
        $pageSize="size:21cm 29.7cm; margin:0.50cm 0.50cm 0.50cm 0.50cm;";
        $namaPerpustakaan = Yii::$app->config->get('NamaPerpustakaan');
        if($column == 2 || $column == 3)
        {
            $dataColor = MasterKelasBesar::find()
            ->select(['ID','kdKelas','namakelas'])
            ->all();
        }
        //echo $format.'-'.$column.'-'.$barcodeSource.'-'.$callnumberSource.'-'; print_r($ids);
        //die;

        $result .="<table style='width: 100%'>\n";
        $result .="<tr>\n";
        $result .="<td style='vertical-align: top'>\n";
        $fontBarcode =  (Yii::$app->config->get('FontBarcode') == null) ? 'IDAHC39M Code 39 Barcode' : Yii::$app->config->get('FontBarcode');
        $fontSize = 12;
        if ($fontBarcode == "IDAutomationHC39M" || $fontBarcode == "IDAHC39M Code 39 Barcode" )
        {
            $fontSize = 10;
        }
        $countperPage=0;
        for ($i=0; $i < $jumlahData; $i++) { 
            $data = Collections::find()
            ->select(['ID','Catalog_id','NomorBarcode','NoInduk','CallNumber'])
            ->where(['ID'=>$ids[$i]])
            ->orderBy('ID,Catalog_id ASC')
            ->one();


            if(strtolower($barcodeSource)=='nomorbarcode')
            {
                //No Barcode
                $barcode = $data->NomorBarcode;
            }else{
                //Item ID
                $barcode = $data->ID;
            }

            $barcode = '*'.$barcode.'*';

            if(strtolower($callnumberSource)=='catalogs')
            {
                //From Catalogs
                $callnumber = $data->catalog->CallNumber;
            }else{
                //Collections
                $callnumber = $data->CallNumber;
            }


            $singleLabel='';
            switch (strtolower($format)) {
                case 'nopanggilbarcode':

                    $padd='padding-right:10px';
                    switch ($column) {
                        case 1:
                            $pageSize="size:6.5cm 3.4cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                        break;

                        case 2:
                            $pageSize="size:13.31cm 3.4cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                        break;

                        case 3;
                            $pageSize="size:21cm 28.7cm; margin:0.50cm 0.50cm 0.50cm 0.50cm;";
                        break;     
                    }
                    $singleLabel .="<table cellpadding='0px' cellspacing='0px' style='mso-cellspacing: 0px; padding: 0px; margin: 0px; text-align: center;'>\n";
                    $singleLabel .="<tr>\n";
                    $singleLabel .="<td colspan='2' style='height: 40px; font-family: Arial; font-size: 12px; padding:4px 4px 4px 4px; border-style: solid; border-width: 1px;'>\n";
                    $singleLabel .=$namaPerpustakaan;
                    $singleLabel .="</td>\n";
                    $singleLabel .="</tr>\n";
                    $singleLabel .="<tr>\n";
                    $singleLabel .="<td style='width:75%;font-family:\"".$fontBarcode."\"; font-size: ".$fontSize."pt; padding:2px 2px 10px 2px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px;'>\n";
                    $singleLabel .=str_replace("\r\n", "<br />", $barcode);
                    $singleLabel .="</td>\n";
                    $singleLabel .="<td style='width:25%;font-family: Arial; font-size: 14px; padding:2px 2px 10px 2px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px; border-right-style: solid; border-right-width: 1px;'>\n";
                    $singleLabel .= str_replace("\r\n", "<br />", $callnumber);
                    $singleLabel .="</td>\n";
                    $singleLabel .="</tr>\n";
                    $singleLabel .="</table>\n";
                break;

                case 'nopanggil':

                    $padd='';
                    switch ($column) {
                        case 1:
                            $pageSize="size:3.15cm 2.69cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                            $heightLabel='25px';
                        break;

                        case 2:
                            $pageSize="size:6.35cm 2.69cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                            $heightLabel='25px';
                        break;

                        case 3;
                            $pageSize="size:21cm 28.7cm; margin:0.50cm 0.50cm 0.50cm 0.50cm;";
                            $heightLabel='120px';
                        break;     
                    }
                    $singleLabel .="<table cellpadding='0px' cellspacing='0px' style='mso-cellspacing: 0px; padding: 0px; margin: 0px; text-align: center;'>\n";
                    $singleLabel .="<tr>\n";
                    $singleLabel .="<td style='height: 40px; font-family: Arial; font-weight:bold; font-size: 10px; padding:4px 4px 4px 4px; border-style: solid; border-width: 1px;'>\n";
                    $singleLabel .=$namaPerpustakaan;
                    $singleLabel .="</td>\n";
                    $singleLabel .="</tr>\n";
                    $singleLabel .="<tr>\n";
                    $singleLabel .="<td style='height: ".$heightLabel.";font-family: Arial; font-size: 10px; font-weight:bold; padding:2px 2px 10px 2px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px; border-right-style: solid; border-right-width: 1px;'>\n";
                    $singleLabel .= str_replace("\r\n", "<br />", $callnumber);
                    $singleLabel .="</td>\n";
                    $singleLabel .="</tr>\n";
                    $singleLabel .="</table>\n";
                break;

                case 'barcode':
                    $padd='';
                    switch ($column) {
                        case 1:
                            $pageSize="size:4.22cm 1.9cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                        break;

                        case 2:
                            $pageSize="size:8.61cm 1.9cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                        break;

                        case 3;
                            $pageSize="size:21cm 28.7cm; margin:0.50cm 0.50cm 0.50cm 0.50cm;";
                        break;     
                    }
                    $singleLabel .="<table cellpadding='0px' cellspacing='0px' style='mso-cellspacing: 0px; padding: 0px; margin: 0px; text-align: center;'>\n";
                    $singleLabel .="<tr>\n";
                    $singleLabel .="<td style='font-family:\"".$fontBarcode."\"; font-size: 9pt; padding:2px 2px 2px 2px;'>\n";
                    $singleLabel .=str_replace("\r\n", "<br />", $barcode);
                    $singleLabel .="</td>\n";
                    $singleLabel .="</tr>\n";
                    $singleLabel .="</table>\n";
                break;

                case 'warna':

                break;
            }
            
            
            switch ($column) {
                case 1:
                    $result .="<div class=WordSection1>";
                    $result .=$singleLabel;
                    $result .="</div>";
                break;

                case 2:
                    $paddRight='';  
                    if($countperPage == 0)
                    {
                       $result .="<div class=WordSection1>\n"; 
                       $result .="<table width='100%' style='padding:0px'>\n"; 
                       $result .="<tr>\n";
                       $paddRight = ($padd == '') ? '' : $padd;
                    }
                    
                    $result .="<td style='width:50%;".$paddRight."'>\n";
                    $result .=$singleLabel;
                    $result .="</td>\n";

                    if($countperPage == 1 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .="<td style='width:50%;'>\n";
                            $result .="&nbsp;";
                            $result .="</td>\n";
                       }
                       $result .="</tr>\n";
                       $result .="</table>\n"; 
                       $result .="</div>\n"; 
                       $countperPage=0;
                    }else{
                       $countperPage++;
                    }
                break;

                case 3;
                    if($countperPage == 0)
                    {
                       $result .="<div class=WordSection1>\n"; 
                       $result .="<table width='100%' style='padding:0px'>\n"; 
                       $result .="<tr>\n";
                    }
                    
                    $result .="<td style='width:33%; padding-bottom:10px; padding-left:10px'>\n";
                    $result .=$singleLabel;
                    $result .="</td>\n";

                    if($countperPage == 2 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            if($countperPage == 0)
                            {
                                $result .="<td style='width:66%'>\n";
                            }elseif($countperPage == 1)
                            {
                                $result .="<td style='width:33%'>\n";
                            }
                            $result .="&nbsp;";
                            $result .="</td>\n";
                       }
                       $result .="</tr>\n";
                       $result .="</table>\n"; 
                       $result .="</div>\n"; 
                       $countperPage=0;
                    }else{
                       $countperPage++;
                    }
                break;     
            }
        }
        $result .="</td>\n";
        $result .="</tr>\n";
        $result .="</table>";

        //echo $result; die;
        header("Content-type: application/vnd.ms-word;charset=utf-8");
        header("Content-Disposition: attachment;Filename=Label.doc");

        $exportResult='';

        $exportResult .= "<html " .
            "xmlns:o='urn:schemas-microsoft-com:office:office' " .
            "xmlns:w='urn:schemas-microsoft-com:office:word'" .
            "xmlns='http://www.w3.org/TR/REC-html40'>" .
            "<head><title>X</title>";
        $exportResult .= "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UTF-8\">";
        $exportResult .= "<meta name=ProgId content=Word.Document>";
        $exportResult .= "<meta name=Generator content=\"Microsoft Word 9\">";
        $exportResult .= "<meta name=Originator content=\"Microsoft Word 9\">";
        $exportResult .= "<!--[if gte mso 9]> <xml> <w:WordDocument> <w:View>Print</w:View> <w:Zoom>100</w:Zoom> <w:DoNotOptimizeForBrowser/> </w:WordDocument> </xml> <![endif]-->";
        $exportResult .= "<style>";
        $exportResult .= "@page WordSection1 {".$pageSize." mso-header-margin:0.0in; mso-footer-margin: 0.0in; mso-paper-source:0;}";
        $exportResult .= "div.WordSection1 {page:WordSection1;}";
        $exportResult .= "@page WordSection2 {size:841.7pt 595.45pt;mso-page-orientation:landscape;margin:1.25in 1.0in 1.25in 1.0in;mso-header-margin:.5in;mso-footer-margin:.5in;mso-paper-source:0;}";
        $exportResult .= "div.WordSection2 {page:WordSection2;}";
        $exportResult .= "</style>";
        $exportResult .= "</head>";
        $exportResult .= "<body>";
        $exportResult .= $result;
        $exportResult .= "</body>";
        $exportResult .= "</html>";

        echo $exportResult;

        
       
        //die;
        //$today=date("Ymdhis");
        // Saving the document as OOXML file...
        //$phpWord->save('KartuKatalog'.$today.'.docx', 'Word2007',true);
        //$phpWord->save('KartuKatalog'.$today.'.odt', 'ODText',true);

    }

    
}
