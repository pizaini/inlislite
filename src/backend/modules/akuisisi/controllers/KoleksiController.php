<?php

namespace backend\modules\akuisisi\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Worksheets;
use common\models\Worksheetfields;
use common\models\WorksheetFieldSearch;
use common\models\Fielddatas;
use common\models\FielddataSearch;
use common\models\Catalogs;
use common\models\CatalogRuas;
use common\models\CatalogSubruas;
use common\models\Collections;
use common\models\Collectionloanitems;
use common\models\CollectionBiblio;
use common\models\CollectionSearch;
use common\models\LocationLibrary;
use common\models\Colloclib;
use common\models\Partners;
use common\models\Users;
use common\models\KataSandang;
use common\models\Fields;
use common\models\QuarantinedCollections;
use common\models\QuarantinedCollectionSearch;
use common\models\Stockopnamedetail;
use common\models\KeranjangKoleksi;
use common\models\Locations;
use common\components\CatalogHelpers;
use common\components\CollectionHelpers;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\validators\Validator;
use yii\helpers\Json;
use kartik\mpdf\Pdf;

/**
 * KoleksiController implements the CRUD actions for Collections model.
 */
class KoleksiController extends Controller
{
    public function behaviors()
    {
        return [
        'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
        'delete' => ['post'],
        ],
        ],
        ];
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionBibliografisInput($mode,$worksheetid)
    {
        $model = new Collections;

        $viewform = $mode =='advance' ? '_advance' : '_simple';

        return $this->render($viewform, [
            'model' => $model,
            'worksheetid' => $worksheetid,
            ]);
    }
    
    /**
     * get datetime now.
     * @return string
     */
    public function actionGetDatetimeNowStr()
    {
        $time = new \DateTime('now', new \DateTimeZone('UTC'));
        $timestr = $time->format('Y-m-d H:i:s');

        return $timestr;
    }


    /**
     * execute pdf render
     * @return pdf
     */
    public function exportPdfLabelRoll($View,$Content,$Title,$Subject,$FileName)
    {
        $pdf = new Pdf([
                        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                        'format'=> [65,34],
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'marginTop' => 0.3,
                        'marginLeft' => 0.3,
                        'marginRight' => 0.3,
                        'marginBottom' => 0.3,
                        'content' => $this->renderPartial($View, $Content),
                        'options' => [
                            'title' => $Title,
                            'subject' => $Subject
                        ],
                        'filename' => $FileName,
                        'destination'=> Pdf::DEST_DOWNLOAD
                    ]);
        return $pdf->render();
    }

    /**
     * execute pdf render
     * @return pdf
     */
    public function exportPdfBarcodeRoll($View,$Content,$Title,$Subject,$FileName)
    {
        $pdf = new Pdf([
                        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                        'format'=> [46,19],
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'marginTop' => 0.3,
                        'marginLeft' => 0.3,
                        'marginRight' => 0.3,
                        'marginBottom' => 0.3,
                        'content' => $this->renderPartial($View, $Content),
                        'options' => [
                            'title' => $Title,
                            'subject' => $Subject
                        ],
                        'filename' => $FileName,
                        'destination'=> Pdf::DEST_DOWNLOAD
                    ]);
        return $pdf->render();
    }

    /**
     * execute pdf render
     * @return pdf
     */
    public function exportPdfTJ107($View,$Content,$Title,$Subject,$FileName)
    {
        $pdf = new Pdf([
                        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                        'format'=> [156,194],
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'marginTop' => 0,
                        'marginLeft' => 0,
                        'marginRight' => 3,
                        'marginBottom' => 0,
                        'content' => $this->renderPartial($View, $Content),
                        'options' => [
                            'title' => $Title,
                            'subject' => $Subject
                        ],
                        'filename' => $FileName,
                        'destination'=> Pdf::DEST_DOWNLOAD
                    ]);
        return $pdf->render();
    }

    /**
     * execute pdf render
     * @return pdf
     */
    public function exportPdfTJ121($View,$Content,$Title,$Subject,$FileName)
    {
        $pdf = new Pdf([
                        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                        'format'=> [175,222],
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'marginTop' => 0.7,
                        'marginLeft' => 0.77,
                        'marginRight' => 1.27,
                        'marginBottom' => 0.51,
                        'content' => $this->renderPartial($View, $Content),
                        'options' => [
                            'title' => $Title,
                            'subject' => $Subject
                        ],
                        'filename' => $FileName,
                        'destination'=> Pdf::DEST_DOWNLOAD
                    ]);
        return $pdf->render();
    }

    /**
     * execute pdf render
     * @return pdf
     */
    public function exportPdfTJ121_5($View,$Content,$Title,$Subject,$FileName)
    {
        $pdf = new Pdf([
                        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                        'format'=> [175,219],
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'marginTop' => 0.7,
                        'marginLeft' => 0.77,
                        'marginRight' => 1.27,
                        'marginBottom' => 0.51,
                        'content' => $this->renderPartial($View, $Content),
                        'options' => [
                            'title' => $Title,
                            'subject' => $Subject
                        ],
                        'filename' => $FileName,
                        'destination'=> Pdf::DEST_DOWNLOAD
                    ]);
        return $pdf->render();
    }

    /**
     * execute pdf render
     * @return pdf
     */
    public function exportPdfGC121($View,$Content,$Title,$Subject,$FileName)
    {
        $pdf = new Pdf([
                        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                        'format'=> [160,200],
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'marginTop' => 0.15,
                        'marginLeft' => 0.05,
                        'marginRight' => 0.05,
                        'marginBottom' => 0,
                        'content' => $this->renderPartial($View, $Content),
                        'options' => [
                            'title' => $Title,
                            'subject' => $Subject
                        ],
                        'filename' => $FileName,
                        'destination'=> Pdf::DEST_DOWNLOAD
                    ]);
        return $pdf->render();
    }

    
    /**
     * execute pdf render
     * @return pdf
     */
    public function exportPdfA4($View,$Content,$Title,$Subject,$FileName)
    {
        $pdf = new Pdf([
                        'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                        'format'=> Pdf::FORMAT_A4,
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'marginTop' => 1.54,
                        'marginLeft' => 1.54,
                        'marginRight' => 1.54,
                        'marginBottom' => 1.54,
                        'content' => $this->renderPartial($View, $Content),
                        'options' => [
                            'title' => $Title,
                            'subject' => $Subject
                        ],
                        'filename' => $FileName,
                        'destination'=> Pdf::DEST_DOWNLOAD
                    ]);
        return $pdf->render();
    }

    /**
     * execute word render
     * @return word
     */
    public function exportWord11($LabelData,$Model){
        $fontBarcode =  (Yii::$app->config->get('FontBarcode') == null) ? 'IDAHC39M Code 39 Barcode' : Yii::$app->config->get('FontBarcode');
        $fontSize = 10;
        /*if ($fontBarcode == "IDAutomationHC39M" || $fontBarcode == "IDAHC39M Code 39 Barcode" )
        {
            $fontSize = 10;
        }*/
        $result='';
        $result .="<table style='width: 100%;'>\n";
        $result .="<tr>\n";
        $result .="<td style='vertical-align: top'>\n";

        switch ($Model) {
            case 'a4-11':
                // $pageSize="size:8.27in 11.69in; margin:300.0pt 300.0pt 300.0pt 300.0pt";
                $labelWidth="250px";
                $maxLabelPerPage=6;
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div>";
                        $result .= "<table  cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='5px';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        $paddingright = 'padding-right: 0px;';
                    }


                    $padding='1';
                    $fontsizeperpustakaan='font-size:12px;';

                    $callnumberColumn='';
                    $callnumberColumn .="<td style='font-family: Arial; font-size: 14px; border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC '>";
                    $callnumberColumn .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $callnumberColumn .="</td>";

                    $result .= "<td style='width:50%; ".$paddingright." padding-bottom: 15px; margin: -50px 0.1px 0.1px 0.1px; text-align: left;'>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:".$labelWidth.";padding:".$padding."px;mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    $result .="<tr>";
                    $result .="<td style='text-align: center; width:1px; height: 212px;writing-mode: tb-rl;mso-rotate:90;' rowspan='3'>";
                    $result .="&nbsp;";
                    // //$result .="<div style='-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);-o-transform: rotate(-90deg);-ms-transform: rotate(-90deg);transform: rotate(-90deg);'><span style='display:block;font-family:\"".$LabelData['Font']."\"; font-size: 11pt; '>".str_replace("\r\n", "<br />",'*'.$LabelData['Barcode'].'*')."</span></div>";
                    $result .="</td>";
                    $result .="<td style='height: 80px; width:212px;font-family: Arial; ".$fontsizeperpustakaan." border: solid 1px #CCC ".$warna_perpustakaan."'>";
                    $result .=$LabelData['NamaPerpustakaan'];
                    $result .="</td>";
                    $result .="</tr>";
                    ($LabelData['Warna1'] == '') ? $warna1='' : $warna1=';background-color:'.$LabelData['Warna1'];
                    ($LabelData['Warna2'] == '') ? $warna2='' : $warna2=';background-color:'.$LabelData['Warna2'];
                    ($LabelData['Warna3'] == '') ? $warna3='' : $warna3=';background-color:'.$LabelData['Warna3'];
                    ($LabelData['Warna4'] == '') ? $warna4='' : $warna4=';background-color:'.$LabelData['Warna4'];
                    ($LabelData['Warna5'] == '') ? $warna5='' : $warna5=';background-color:'.$LabelData['Warna5'];
                    $result .="<tr>";
                    $result .="<td style='border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; text-align: center'>";

                    $result .="<table width='100%' cellspacing='1' cellpadding='3' bgcolor='#FFF' style='margin: 0px'>";
                    $result .="<tr><td style='font-family: Arial; text-align: center ".$warna1."'>".$LabelData['KodeWarna1']."</td></tr>";
                    $result .="<tr><td style='font-family: Arial; text-align: center ".$warna2."'>".$LabelData['KodeWarna2']."</td></tr>";
                    $result .="<tr><td style='font-family: Arial; text-align: center ".$warna3."'>".$LabelData['KodeWarna3']."</td></tr>";
                    $result .="<tr><td style='font-family: Arial; text-align: center ".$warna4."'>".$LabelData['KodeWarna4']."</td></tr>";
                    $result .="<tr><td style='font-family: Arial; text-align: center ".$warna5."'>".$LabelData['KodeWarna5']."</td></tr>";
                    $result .="</table>";

                    $result .="</td>";
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='font-family: Arial; height: 30px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .=$LabelData['CallNumber'];
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 2 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .= "<td style='width:30%;".$paddingright." text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;

            
            
        }
        
        $result .="</td>\n";
        $result .="</tr>\n";
        $result .="</table>";

        
        //echo $result; die;
        header("Content-type: application/vnd.ms-word;charset=utf-8");
        header("Content-Disposition: attachment;Filename=Label-".strtoupper($Model)."-".date("Ymd").".doc");

        $exportResult='';

        // $exportResult .= "<html xmlns:v=urn:schemas-microsoft-com:vml " .
        //     "xmlns:o='urn:schemas-microsoft-com:office:office' " .
        //     "xmlns:w='urn:schemas-microsoft-com:office:word'" .
        //     "xmlns:m='http://schemas.microsoft.com/office/2004/12/omml'" .
        //     "xmlns='http://www.w3.org/TR/REC-html40'>" .
        //     "<head>";
        // $exportResult .= "<meta http-equiv=Content-Type content=\"text/html; charset=unicode\">";
        // $exportResult .= "<meta name=ProgId content=Word.Document>";
        // $exportResult .= "<meta name=Generator content=\"Microsoft Word 15\">";
        // $exportResult .= "<meta name=Originator content=\"Microsoft Word 15\">";
        // $exportResult .= "<link rel=File-List href=\"test_files/filelist.xml\">";
        // $exportResult .= "<title>Label</title>";
        // $exportResult .= "<link rel=themeData href=\"test_files/themedata.thmx\">";
        // $exportResult .= "<link rel=colorSchemeMapping href=\"test_files/colorschememapping.xml\">";
        // $exportResult .= "</head>";
        $exportResult .= "<style>@page WordSection1
                            {size:21cm 29.7cm;
                            margin:0.2in 0.1in 0.2in 0.1in;}
                            div.WordSection1
                            {page:WordSection1;}</style>";
        
        // $exportResult .= "<body lang=EN-US style='tab-interval:.5in; margin: -100px -70px 0 -70px; max-width: 500px;'>";
        $exportResult .= "<body lang=EN-US style='tab-interval:.5in;'>";
        $exportResult .= "<div class=WordSection1>";
        $exportResult .= $result;
        $exportResult .= "</div>";
        $exportResult .= "</body>";
        
        $exportResult .= "</html>";

        echo $exportResult;
    }

    public function exportWordTj107($LabelData,$Model){
        $fontBarcode =  (Yii::$app->config->get('FontBarcode') == null) ? 'IDAHC39M Code 39 Barcode' : Yii::$app->config->get('FontBarcode');
        $fontSize = 10;
        /*if ($fontBarcode == "IDAutomationHC39M" || $fontBarcode == "IDAHC39M Code 39 Barcode" )
        {
            $fontSize = 10;
        }*/
        $result='';
        $result .="<table style='width: 100%;'>\n";
        $result .="<tr>\n";
        $result .="<td style='vertical-align: top'>\n";

        switch ($Model) {
            case 'tj107-1':
                
                $pageSize="size:21cm 29.7cm; margin:1.54cm 1.54cm 1.54cm 1.54cm;";
                $labelWidth="203px";
                $maxLabelPerPage=24;
                $additionalRowHeight="";
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div class='WordSection1' style='padding-top:15px; padding-left:0px; '>";
                        $result .= "<table style='width:100%' cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        // $paddingright = 'padding-right: 5px;';
                    }

                    $colspan='';

                    $padding='6';
                    $rowspan='';
                    $bordertopcallnumber='';
                    $fontsizeperpustakaan='font-size:12px;';
                    
                    $result .= "<td style='width:197px; padding-bottom: 2.4px; padding-top: 0px; text-align: left; '>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:99%; mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    
                    $result .="<tr>";
                    $result .="<td style='height:66px; width:100%; border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-top: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .= "<span style='font-family: Arial;font-size: 12px '>".str_replace("\r\n", "<br />", $LabelData['Title'])."</span><br>";
                    $result .="<span style='width:412px;font-family:\"".$LabelData['Font']."\"; font-size: 10pt; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $result .="</td>";
                    //jika tj121-1 / tj121-3
                    if($colspan!='')
                    {
                        $result .=$callnumberColumn;
                    }
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 2 || $i == ($jumlahData - 0))
                    {
                       if($i == ($jumlahData - 0))
                       {
                            $result .= "<td style='width:50%; padding-bottom: 10px; padding-right: 0px; text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;

            
            
        }
        
        $result .="</td>\n";
        $result .="</tr>\n";
        $result .="</table>";

        
        //echo $result; die;
        header("Content-type: application/vnd.ms-word;charset=utf-8");
        header("Content-Disposition: attachment;Filename=Label-".strtoupper($Model)."-".date("Ymd").".doc");

        $exportResult='';

        // $exportResult .= "<html xmlns:v=urn:schemas-microsoft-com:vml " .
        //     "xmlns:o='urn:schemas-microsoft-com:office:office' " .
        //     "xmlns:w='urn:schemas-microsoft-com:office:word'" .
        //     "xmlns:m='http://schemas.microsoft.com/office/2004/12/omml'" .
        //     "xmlns='http://www.w3.org/TR/REC-html40'>" .
        //     "<head>";
        // $exportResult .= "<meta http-equiv=Content-Type content=\"text/html; charset=unicode\">";
        // $exportResult .= "<meta name=ProgId content=Word.Document>";
        // $exportResult .= "<meta name=Generator content=\"Microsoft Word 15\">";
        // $exportResult .= "<meta name=Originator content=\"Microsoft Word 15\">";
        // $exportResult .= "<link rel=File-List href=\"test_files/filelist.xml\">";
        // $exportResult .= "<title>Label</title>";
        // $exportResult .= "<link rel=themeData href=\"test_files/themedata.thmx\">";
        // $exportResult .= "<link rel=colorSchemeMapping href=\"test_files/colorschememapping.xml\">";
        // $exportResult .= "</head>";
        $exportResult .= "<style>@page WordSection1
                            {size:15.5cm 19.4cm;
                            margin:0.2in 0.1in 0.2in 0.1in;}
                            div.WordSection1
                            {page:WordSection1;}</style>";
        
        // $exportResult .= "<body lang=EN-US style='tab-interval:.5in; margin: -100px -70px 0 -70px; max-width: 500px;'>";
        $exportResult .= "<body lang=EN-US style='tab-interval:.5in;'>";
        $exportResult .= "<div class=WordSection1>";
        $exportResult .= $result;
        $exportResult .= "</div>";
        $exportResult .= "</body>";
        
        $exportResult .= "</html>";

        echo $exportResult;
    }

    public function exportWordTj121($LabelData,$Model){
        $fontBarcode =  (Yii::$app->config->get('FontBarcode') == null) ? 'IDAHC39M Code 39 Barcode' : Yii::$app->config->get('FontBarcode');
        $fontSize = 10;
        /*if ($fontBarcode == "IDAutomationHC39M" || $fontBarcode == "IDAHC39M Code 39 Barcode" )
        {
            $fontSize = 10;
        }*/
        $result='';
        $result .="<table style='width: 100%;'>\n";
        $result .="<tr>\n";
        $result .="<td style='vertical-align: top'>\n";

        switch ($Model) {
            case 'tj121-5':
                
                // $pageSize="size:21cm 12cm; margin:1.54cm 1.54cm 1.54cm 1.54cm;";
                $labelWidth="203px";
                $maxLabelPerPage=24;
                $additionalRowHeight="";
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
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
                        $result .= "<div class=WordSection1>";
                        $result .= "<table style='border: none; width:100%' cellspacing='0' cellpadding='0'>";
                    }


                    $result .= "<td style='border: none; width:50%; text-align: center; padding: 4px;'>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='border: none; width:284px; mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    $result .="<tr>";
                    $result .="<td style='height:135px;border-left: solid 1px #CCC; border-top: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .= "<span style='font-family: Arial;font-size: 18px '>".str_replace("\r\n", "<br />", $body)."</span><br>";
                    $result .="</td>";

                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 1 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .= "<td style='width:50%; padding-right: 10px; text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;
     
        }
        
        
        //echo $result; die;
        header("Content-type: application/vnd.ms-word;charset=utf-8");
        header("Content-Disposition: attachment;Filename=Label-".strtoupper($Model)."-".date("Ymd").".doc");

        $exportResult='';

        // $exportResult .= "<html xmlns:v=urn:schemas-microsoft-com:vml " .
        //     "xmlns:o='urn:schemas-microsoft-com:office:office' " .
        //     "xmlns:w='urn:schemas-microsoft-com:office:word'" .
        //     "xmlns:m='http://schemas.microsoft.com/office/2004/12/omml'" .
        //     "xmlns='http://www.w3.org/TR/REC-html40'>" .
        //     "<head>";
        // $exportResult .= "<meta http-equiv=Content-Type content=\"text/html; charset=unicode\">";
        // $exportResult .= "<meta name=ProgId content=Word.Document>";
        // $exportResult .= "<meta name=Generator content=\"Microsoft Word 15\">";
        // $exportResult .= "<meta name=Originator content=\"Microsoft Word 15\">";
        // $exportResult .= "<link rel=File-List href=\"test_files/filelist.xml\">";
        // $exportResult .= "<title>Label</title>";
        // $exportResult .= "<link rel=themeData href=\"test_files/themedata.thmx\">";
        // $exportResult .= "<link rel=colorSchemeMapping href=\"test_files/colorschememapping.xml\">";
        // $exportResult .= "</head>";  
        $exportResult .= "<style>@page WordSection1
                            {size:17.5cm 21.4cm;
                            margin:0.70cm 0.77cm 0cm 0.70cm;}
                            div.WordSection1
                            {page:WordSection1;}</style>";
        
        // $exportResult .= "<body lang=EN-US style='tab-interval:.5in; margin: -100px -70px 0 -70px; max-width: 500px;'>";
        $exportResult .= "<body lang=EN-US style='tab-interval:.5in;'>";
        $exportResult .= "<div class=WordSection1>";
        $exportResult .= $result;
        $exportResult .= "</div>";
        $exportResult .= "</body>";
        
        $exportResult .= "</html>";

        echo $exportResult;
    }

    public function exportWord($LabelData,$Model)
    {
        $fontBarcode =  (Yii::$app->config->get('FontBarcode') == null) ? 'IDAHC39M Code 39 Barcode' : Yii::$app->config->get('FontBarcode');
        $fontSize = 10;
        /*if ($fontBarcode == "IDAutomationHC39M" || $fontBarcode == "IDAHC39M Code 39 Barcode" )
        {
            $fontSize = 10;
        }*/
        $result='';
        $result .="<table style='width: 100%'>\n";
        $result .="<tr>\n";
        $result .="<td style='vertical-align: top'>\n";

        switch ($Model) {
            case 'lr1':
            case 'lr3':

                $pageSize="size:6.5cm 3.4cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                foreach ($LabelData as $LabelData) { 
                    ($LabelData['Warna1'] != '' && $Model == 'lr3') ? $warna=';background-color:'.$LabelData['Warna1'] : $warna='';
                    $singleLabel='';
                    $singleLabel .="<table cellpadding='0px' cellspacing='0px' style='mso-cellspacing: 0px; padding: 6px; margin: 0px; text-align: center;'>";
                    $singleLabel .="<tr>";
                    $singleLabel .="<td colspan='2' style='height: 26px; font-family: Arial; font-size: 12px; border: solid 1px #CCC ".$warna."'>";
                    $singleLabel .=$LabelData['NamaPerpustakaan'];
                    $singleLabel .="</td>";
                    $singleLabel .="</tr>";
                    $singleLabel .="<tr>";
                    $singleLabel .="<td style='border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $singleLabel .= "<span style='width:75%;font-family: Arial;font-size: 10px; '>".str_replace("\r\n", "<br />", $LabelData['Title'])."</span><br>";
                    $singleLabel .="<span style='font-family:\"".$LabelData['Font']."\"; font-size: ".$fontSize."pt; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $singleLabel .="</td>";
                    $singleLabel .="<td style='width:25%;font-family: Arial; font-size: 14px; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $singleLabel .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $singleLabel .="</td>";
                    $singleLabel .="</tr>";
                    $singleLabel .="</table>";

                    $result .="<div class=WordSection1>";
                    $result .=$singleLabel;
                    $result .="</div>";
                }
            break;

            case 'lr2':
            case 'lr4':
                $pageSize="size:6.5cm 3.4cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                foreach ($LabelData as $LabelData) { 
                    ($LabelData['Warna1'] != '' && $Model == 'lr4') ? $warna=';background-color:'.$LabelData['Warna1'] : $warna='';
                    $singleLabel='';
                    $singleLabel .="<table cellpadding='0px' cellspacing='0px' style='mso-cellspacing: 0px; padding: 6px; margin: 0px; text-align: center;'>";
                    $singleLabel .="<tr>";
                    $singleLabel .="<td style='width:75%;height: 30px; font-family: Arial; font-size: 12px; border: solid 1px #CCC;'>";
                    $singleLabel .=$LabelData['NamaPerpustakaan'];
                    $singleLabel .="</td>";
                    $singleLabel .="<td style='font-family: Arial; font-size: 14px; border-top: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC ".$warna."' rowspan='2'>";
                    $singleLabel .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $singleLabel .="</td>";
                    $singleLabel .="</tr>";
                    $singleLabel .="<tr>";
                    $singleLabel .="<td style='border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $singleLabel .= "<span style='font-family: Arial;font-size: 10px; '>".str_replace("\r\n", "<br />", $LabelData['Title'])."</span><br>";
                    $singleLabel .="<span style='font-family:\"".$LabelData['Font']."\"; font-size: ".$fontSize."pt; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $singleLabel .="</td>";
                    $singleLabel .="</tr>";
                    $singleLabel .="</table>";

                    $result .="<div class=WordSection1>";
                    $result .=$singleLabel;
                    $result .="</div>";
                }
            break;

            case 'lr5':
            case 'lr6':
                $pageSize="size:6.5cm 3.4cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                foreach ($LabelData as $LabelData) { 
                    ($LabelData['Warna1'] != '' && $Model == 'lr6') ? $warna=';background-color:'.$LabelData['Warna1'] : $warna='';
                    $singleLabel='';
                    $singleLabel .="<table cellpadding='0px' cellspacing='0px' style='mso-cellspacing: 0px; padding: 6px; margin: 0px; text-align: center;'>";
                    $singleLabel .="<tr>";
                    $singleLabel .="<td style='height: 30px; font-family: Arial; font-size: 12px; border: solid 1px #CCC ".$warna."'>";
                    $singleLabel .=$LabelData['NamaPerpustakaan'];
                    $singleLabel .="</td>";
                    $singleLabel .="</tr>";
                    $singleLabel .="<tr>";
                    $singleLabel .="<td style='height:65px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $singleLabel .= CollectionHelpers::getLabelCallNumber($LabelData['CallNumber']);
                    $singleLabel .="</td>";
                    $singleLabel .="</tr>";
                    $singleLabel .="</table>";

                    $result .="<div class=WordSection1>";
                    $result .=$singleLabel;
                    $result .="</div>";
                }
            break;

            case 'br1':
            case 'br2':
                $pageSize="size:4.6cm 1.9cm; margin:0.00cm 0.00cm 0.00cm 0.00cm;";
                foreach ($LabelData as $LabelData) { 
                    ($LabelData['Warna1'] != '' && $Model == 'lr3') ? $warna=';background-color:'.$LabelData['Warna1'] : $warna='';
                    $singleLabel='';
                    $singleLabel .="<table cellpadding='0px' cellspacing='0px' style='mso-cellspacing: 0px; padding: 5px; margin: 0px; text-align: center;'>";
                    $singleLabel .="<tr>";
                    $singleLabel .="<td style='width:173px;height:52px;border: solid 1px #CCC;'>";
                    if($Model=='br2')
                    {
                        $singleLabel .= "<span style='width:173px;font-family: Arial;font-size: 12px; '>".str_replace("\r\n", "<br />", $LabelData['Title'])."</span><br>";
                    }
                    $singleLabel .="<span style='font-family:\"".$LabelData['Font']."\"; font-size: ".$fontSize."px; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $singleLabel .="</tr>";
                    $singleLabel .="</table>";

                    $result .="<div class=WordSection1>";
                    $result .=$singleLabel;
                    $result .="</div>";
                }
            break;

            case 'tj121-1':
            case 'tj121-2':
            case 'tj121-3':
            case 'tj121-4':

                $pageSize="size:17.5cm 22.2cm; margin:0.70cm 0.77cm 0.51cm 0.77cm;";
                $labelWidth="283px";
                $maxLabelPerPage=10;
                $additionalRowHeight="";
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div class=WordSection1>";
                        $result .= "<table style='width:100%' cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        $paddingright = 'padding-right: 10px;';
                    }

                    $colspan='';
                    if($Model=='tj121-1' || $Model=='tj121-3')
                    {
                        //pengaturan untuk nama perpustakaan wrapping colspan 2
                        $colspan="colspan='2'";
                    }

                    $padding='6';
                    $rowspan='';
                    $bordertopcallnumber='';
                    $fontsizeperpustakaan='font-size:12px;';
                    if($Model=='tj121-2' || $Model=='tj121-4')
                    {
                        //pengaturan untuk callnumber wrapping rowspan 2
                        $padding='5';
                        $rowspan="rowspan='2'";
                        $bordertopcallnumber="border-top: solid 1px #CCC;";
                    }
                    ($LabelData['Warna1'] != '' && $Model == 'tj121-3') ? $warna_perpustakaan=';background-color:'.$LabelData['Warna1'] : $warna_perpustakaan='';
                    ($LabelData['Warna1'] != '' && $Model == 'tj121-4') ? $warna_callnumber=';background-color:'.$LabelData['Warna1'] : $warna_callnumber='';

                    $callnumberColumn='';
                    $callnumberColumn .="<td ".$rowspan." style='width:25%;font-family: Arial; font-size: 14px; ".$bordertopcallnumber." border-right: solid 1px #CCC; border-bottom: solid 1px #CCC ".$warna_callnumber."'>";
                    $callnumberColumn .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $callnumberColumn .="</td>";

                    $result .= "<td style='width:50%; ".$paddingright." padding-bottom: 10px;text-align: left;'>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:".$labelWidth.";padding:".$padding."px;mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    $result .="<tr>";
                    $result .="<td ".$colspan." style='height: 40px; font-family: Arial; ".$fontsizeperpustakaan." border: solid 1px #CCC ".$warna_perpustakaan."'>";
                    $result .=$LabelData['NamaPerpustakaan'];
                    $result .="</td>";
                    //jika tj121-2 / tj121-4
                    if($rowspan!=''){
                        $result .=$callnumberColumn;
                    }
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='height:80px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .= "<span style='font-family: Arial;font-size: 14px '>".str_replace("\r\n", "<br />", $LabelData['Title'])."</span><br>";
                    $result .="<span style='width:212px;font-family:\"".$LabelData['Font']."\"; font-size: ".$fontSize."pt; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $result .="</td>";
                    //jika tj121-1 / tj121-3
                    if($colspan!='')
                    {
                        $result .=$callnumberColumn;
                    }
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 1 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .= "<td style='width:50%;padding-right: 10px; text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;


            case 'gc121-1':
            case 'gc121-2':
            case 'gc121-3':
            case 'gc121-4':
                
                $pageSize="size:16cm 20cm; margin:0.15cm 0.05cm 0.00cm 0.05cm;";
                $labelWidth="287px";
                $maxLabelPerPage=8;
                $additionalRowHeight="<tr><td style='height:120px' colspan='2'>&nbsp;</td></tr>";
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div class=WordSection1>";
                        $result .= "<table style='width:100%' cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        $paddingright = 'padding-right: 10px;';
                    }

                    $colspan='';
                    if($Model=='gc121-1' || $Model=='gc121-3')
                    {
                        //pengaturan untuk nama perpustakaan wrapping colspan 2
                        $colspan="colspan='2'";
                    }

                    $padding='6';
                    $rowspan='';
                    $bordertopcallnumber='';
                    $fontsizeperpustakaan='font-size:12px;';
                    if($Model=='gc121-2' || $Model=='gc121-4')
                    {
                        //pengaturan untuk callnumber wrapping rowspan 2
                        $padding='5';
                        $rowspan="rowspan='2'";
                        $bordertopcallnumber="border-top: solid 1px #CCC;";
                    }
                    ($LabelData['Warna1'] != '' && $Model == 'gc121-3') ? $warna_perpustakaan=';background-color:'.$LabelData['Warna1'] : $warna_perpustakaan='';
                    ($LabelData['Warna1'] != '' && $Model == 'gc121-4') ? $warna_callnumber=';background-color:'.$LabelData['Warna1'] : $warna_callnumber='';

                    $callnumberColumn='';
                    $callnumberColumn .="<td ".$rowspan." style='width:25%;font-family: Arial; font-size: 14px; ".$bordertopcallnumber." border-right: solid 1px #CCC; border-bottom: solid 1px #CCC ".$warna_callnumber."'>";
                    $callnumberColumn .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $callnumberColumn .="</td>";

                    $result .= "<td style='width:50%; ".$paddingright." padding-bottom: 10px;text-align: left;'>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:".$labelWidth.";padding:".$padding."px;mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    $result .="<tr>";
                    $result .="<td ".$colspan." style='height: 30px; font-family: Arial; ".$fontsizeperpustakaan." border: solid 1px #CCC ".$warna_perpustakaan."'>";
                    $result .=$LabelData['NamaPerpustakaan'];
                    $result .="</td>";
                    //jika tj121-2 / tj121-4
                    if($rowspan!=''){
                        $result .=$callnumberColumn;
                    }
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='height:80px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .= "<span style='font-family: Arial;font-size: 14px '>".str_replace("\r\n", "<br />", $LabelData['Title'])."</span><br>";
                    $result .="<span style='width:212px;font-family:\"".$LabelData['Font']."\"; font-size: ".$fontSize."pt; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $result .="</td>";
                    //jika tj121-1 / tj121-3
                    if($colspan!='')
                    {
                        $result .=$callnumberColumn;
                    }
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 1 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .= "<td style='width:50%; ".$paddingright." text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;

            case 'a4-1':
            case 'a4-2':
            case 'a4-3':
            case 'a4-4':
                
                $pageSize="size:21cm 29.7cm; margin:1.54cm 1.54cm 1.54cm 1.54cm;";
                $labelWidth="283px";
                $maxLabelPerPage=12;
                $additionalRowHeight="";
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div class=WordSection1>";
                        $result .= "<table style='width:100%' cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        $paddingright = 'padding-right: 55px;';
                    }

                    $colspan='';
                    if($Model=='a4-1' || $Model=='a4-3')
                    {
                        //pengaturan untuk nama perpustakaan wrapping colspan 2
                        $colspan="colspan='2'";
                    }

                    $padding='6';
                    $rowspan='';
                    $bordertopcallnumber='';
                    $fontsizeperpustakaan='font-size:12px;';
                    if($Model=='a4-2' || $Model=='a4-4')
                    {
                        //pengaturan untuk callnumber wrapping rowspan 2
                        $padding='5';
                        $rowspan="rowspan='2'";
                        $bordertopcallnumber="border-top: solid 1px #CCC;";
                    }
                    ($LabelData['Warna1'] != '' && $Model == 'a4-3') ? $warna_perpustakaan=';background-color:'.$LabelData['Warna1'] : $warna_perpustakaan='';
                    ($LabelData['Warna1'] != '' && $Model == 'a4-4') ? $warna_callnumber=';background-color:'.$LabelData['Warna1'] : $warna_callnumber='';

                    $callnumberColumn='';
                    $callnumberColumn .="<td ".$rowspan." style='width:25%;font-family: Arial; font-size: 14px; ".$bordertopcallnumber." border-right: solid 1px #CCC; border-bottom: solid 1px #CCC ".$warna_callnumber."'>";
                    $callnumberColumn .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $callnumberColumn .="</td>";

                    $result .= "<td style='width:50%; ".$paddingright." padding-bottom: 25px;text-align: left;'>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:".$labelWidth.";padding:".$padding."px;mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    $result .="<tr>";
                    $result .="<td ".$colspan." style='height: 30px; font-family: Arial; ".$fontsizeperpustakaan." border: solid 1px #CCC ".$warna_perpustakaan."'>";
                    $result .=$LabelData['NamaPerpustakaan'];
                    $result .="</td>";
                    //jika tj121-2 / tj121-4
                    if($rowspan!=''){
                        $result .=$callnumberColumn;
                    }
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='height:80px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .= "<span style='font-family: Arial;font-size: 14px '>".str_replace("\r\n", "<br />", $LabelData['Title'])."</span><br>";
                    $result .="<span style='width:212px;font-family:\"".$LabelData['Font']."\"; font-size: 12pt; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $result .="</td>";
                    //jika tj121-1 / tj121-3
                    if($colspan!='')
                    {
                        $result .=$callnumberColumn;
                    }
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 1 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .= "<td style='width:50%;".$paddingright." text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;

            case 'a4-5':
            case 'a4-7':
                
                $pageSize="size:21cm 29.7cm; margin:1.54cm 1.54cm 1.54cm 1.54cm;";
                $labelWidth="212px";
                $maxLabelPerPage=8;
                $additionalRowHeight="";
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div class=WordSection1>";
                        $result .= "<table style='width:100%' cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        $paddingright = 'padding-right: 55px;';
                    }


                    $padding='6';
                    $fontsizeperpustakaan='font-size:12px;';

                    ($LabelData['Warna1'] != '' && $Model == 'a4-7') ? $warna_perpustakaan=';background-color:'.$LabelData['Warna1'] : $warna_perpustakaan='';

                    $callnumberColumn='';
                    $callnumberColumn .="<td style='font-family: Arial; font-size: 14px; border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC '>";
                    $callnumberColumn .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $callnumberColumn .="</td>";

                    $result .= "<td style='width:50%; ".$paddingright." padding-bottom: 25px;text-align: left;'>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:".$labelWidth.";padding:".$padding."px;mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    $result .="<tr>";
                    $result .="<td style='height: 30px; font-family: Arial; ".$fontsizeperpustakaan." border: solid 1px #CCC ".$warna_perpustakaan."'>";
                    $result .=$LabelData['NamaPerpustakaan'];
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='height:60px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .=CollectionHelpers::getLabelCallNumber($LabelData['CallNumber']);
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td>";
                    $result .="<span style='font-family:\"".$LabelData['Font']."\"; font-size: 11pt; font-size: 4vmin; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 1 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .= "<td style='width:50%;".$paddingright." text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;

            case 'a4-6':
            case 'a4-8':
                
                $pageSize="size:21cm 29.7cm; margin:1.54cm 1.54cm 1.54cm 1.54cm;";
                $labelWidth="283px";
                $maxLabelPerPage=10;
                $additionalRowHeight="";
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div class=WordSection1>";
                        $result .= "<table style='width:100%' cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        $paddingright = 'padding-right: 55px;';
                    }


                    $padding='6';
                    $fontsizeperpustakaan='font-size:12px;';
                    $fontsizebarcode = (strlen($LabelData['Barcode']) > 13) ? '8.5pt' : '10pt';

                    ($LabelData['Warna1'] != '' && $Model == 'a4-8') ? $warna_perpustakaan=';background-color:'.$LabelData['Warna1'] : $warna_perpustakaan='';

                    $callnumberColumn='';
                    $callnumberColumn .="<td style='font-family: Arial; font-size: 14px; border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC '>";
                    $callnumberColumn .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $callnumberColumn .="</td>";

                    $result .= "<td style='width:50%; ".$paddingright." padding-bottom: 25px;text-align: left;'>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:".$labelWidth.";padding:".$padding."px;mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    $result .="<tr>";
                    $result .="<td style='text-align: center; width:60px; height: 212px;writing-mode: tb-rl;mso-rotate:90;' rowspan='2'>";
                    //$result .="&nbsp;";
                    $result .="<div style='-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);-o-transform: rotate(-90deg);-ms-transform: rotate(-90deg);transform: rotate(-90deg);'><span style='display:block;font-family:\"".$LabelData['Font']."\"; font-size: ".$fontsizebarcode."; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span></div>";
                    $result .="</td>";
                    $result .="<td style='height: 50px; width:212px;font-family: Arial; ".$fontsizeperpustakaan." border: solid 1px #CCC ".$warna_perpustakaan."'>";
                    $result .=$LabelData['NamaPerpustakaan'];
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='height:110px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .=CollectionHelpers::getLabelCallNumber($LabelData['CallNumber']);
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 1 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .= "<td style='width:50%;".$paddingright." text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;

            case 'a4-9':
                
                $pageSize="size:21cm 29.7cm; margin:1.54cm 1.54cm 1.54cm 1.54cm;";
                $labelWidth="283px";
                $maxLabelPerPage=6;
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div class=WordSection1>";
                        $result .= "<table style='width:100%' cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        $paddingright = 'padding-right: 55px;';
                    }


                    $padding='6';
                    $fontsizeperpustakaan='font-size:12px;';

                    $callnumberColumn='';
                    $callnumberColumn .="<td style='font-family: Arial; font-size: 14px; border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC '>";
                    $callnumberColumn .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $callnumberColumn .="</td>";

                    $result .= "<td style='width:50%; ".$paddingright." padding-bottom: 25px;text-align: left;'>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:".$labelWidth.";padding:".$padding."px;mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    $result .="<tr>";
                    $result .="<td style='text-align: center; width:60px; height: 212px;writing-mode: tb-rl;mso-rotate:90;' rowspan='3'>";
                    //$result .="&nbsp;";
                    $result .="<div style='-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);-o-transform: rotate(-90deg);-ms-transform: rotate(-90deg);transform: rotate(-90deg);'><span style='display:block;font-family:\"".$LabelData['Font']."\"; font-size: 11pt; '>".str_replace("\r\n", "<br />",'*'.$LabelData['Barcode'].'*')."</span></div>";
                    $result .="</td>";
                    $result .="<td style='height: 40px; width:212px;font-family: Arial; ".$fontsizeperpustakaan." border: solid 1px #CCC ".$warna_perpustakaan."'>";
                    $result .=$LabelData['NamaPerpustakaan'];
                    $result .="</td>";
                    $result .="</tr>";
                    ($LabelData['Warna1'] == '') ? $warna1='' : $warna1=';background-color:'.$LabelData['Warna1'];
                    ($LabelData['Warna2'] == '') ? $warna2='' : $warna2=';background-color:'.$LabelData['Warna2'];
                    ($LabelData['Warna3'] == '') ? $warna3='' : $warna3=';background-color:'.$LabelData['Warna3'];
                    ($LabelData['Warna4'] == '') ? $warna4='' : $warna4=';background-color:'.$LabelData['Warna4'];
                    ($LabelData['Warna5'] == '') ? $warna5='' : $warna5=';background-color:'.$LabelData['Warna5'];
                    $result .="<tr>";
                    $result .="<td style='border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC; text-align: center'>";

                    $result .="<table width='100%' cellspacing='1' cellpadding='3' bgcolor='#FFF' style='margin: 0px'>";
                    $result .="<tr><td style='text-align: center ".$warna1."'>".$LabelData['KodeWarna1']."</td></tr>";
                    $result .="<tr><td style='text-align: center ".$warna2."'>".$LabelData['KodeWarna2']."</td></tr>";
                    $result .="<tr><td style='text-align: center ".$warna3."'>".$LabelData['KodeWarna3']."</td></tr>";
                    $result .="<tr><td style='text-align: center ".$warna4."'>".$LabelData['KodeWarna4']."</td></tr>";
                    $result .="<tr><td style='text-align: center ".$warna5."'>".$LabelData['KodeWarna5']."</td></tr>";
                    $result .="</table>";

                    $result .="</td>";
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='height: 30px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .=$LabelData['CallNumber'];
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 1 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .= "<td style='width:50%;".$paddingright." text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;

            case 'a4-10':
                
                $pageSize="size:21cm 29.7cm; margin:1.54cm 1.54cm 1.54cm 1.54cm;";
                $labelWidth="212px";
                $maxLabelPerPage=6;
                $additionalRowHeight="";
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);

                $location = Yii::$app->location->get();
                $id = \common\models\LocationLibrary::findOne($location);
                $loclib= $id->Name;

                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div class=WordSection1>";
                        $result .= "<table style='width:100%' cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        $paddingright = 'padding-right: 55px;';
                    }


                    $padding='6';
                    $fontsizeperpustakaan='font-size:12px;';

                    $warna_perpustakaan=';background-color:#000;color:#FFF';

                    $callnumberColumn='';
                    $callnumberColumn .="<td style='font-family: Arial; font-size: 14px; border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC; '>";
                    $callnumberColumn .= str_replace("\r\n", "<br />", str_replace(" ", "<br />",$LabelData['CallNumber']));
                    $callnumberColumn .="</td>";

                    $result .= "<td style='width:50%; ".$paddingright." padding-bottom: 15px;text-align: left;'>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:".$labelWidth.";padding:".$padding."px;mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    $result .="<tr>";
                    $result .="<td style='height: 30px; font-family: Arial; ".$fontsizeperpustakaan." border: solid 1px #CCC ".$warna_perpustakaan."'>";
                    $result .=$loclib."<br>";
                    $result .=$LabelData['NamaPerpustakaan'];
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='height:60px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-bottom: solid 1px #CCC; text-align: left; padding-left: 60px;padding-right: 60px'>";
                    $result .=CollectionHelpers::getLabelCallNumber($LabelData['CallNumber']);
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC;''>";
                    $result .="<span style='font-size:11px; font-weight: bold'>".$loclib."</span><br>";
                    $result .="<span style='font-family:\"".$LabelData['Font']."\"; font-size: 11pt; font-size: 4vmin; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $result .="<br><span style='font-size:11px; font-weight: bold'>".$LabelData['NamaPerpustakaan']."</span>";
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="<tr>";
                    $result .="<td style='border-bottom:solid 1px #CCC; border-right:solid 1px #CCC;border-left:solid 1px #CCC;''>";
                    $result .="<span style='font-size:11px; font-weight: bold'>".$loclib."</span><br>";
                    $result .="<span style='font-family:\"".$LabelData['Font']."\"; font-size: 11pt; font-size: 4vmin; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $result .="<br><span style='font-size:11px; font-weight: bold'>".$LabelData['NamaPerpustakaan']."</span>";
                    $result .="</td>";
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 1 || $i == ($jumlahData -1))
                    {
                       if($i == ($jumlahData -1))
                       {
                            $result .= "<td style='width:50%;".$paddingright." text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;

            case 'a4-12':
                
                $pageSize="size:21cm 29.7cm; margin:1.54cm 1.54cm 1.54cm 1.54cm;";
                $labelWidth="203px";
                $maxLabelPerPage=24;
                $additionalRowHeight="";
                
                $no=0;
                $item=0;
                $rec=0;
                $jumlahData=count($LabelData);
                foreach ($LabelData as $LabelData) { 
                    $rec++;

                    if($item == 0){
                        $result .= "<div class='WordSection1' style='margin-bottom:50px;'>";
                        $result .= "<table style='width:100%' cellspacing='0' cellpadding='0'>";
                    }
                    $paddingright='';
                    if($no==0)
                    {
                        $result .= '<tr>';
                        $paddingright = 'padding-right: 5px;';
                    }

                    $colspan='';

                    $padding='6';
                    $rowspan='';
                    $bordertopcallnumber='';
                    $fontsizeperpustakaan='font-size:12px;';
                    if($Model=='a4-2' || $Model=='a4-4')
                    {
                        //pengaturan untuk callnumber wrapping rowspan 2
                        $padding='55';
                        $rowspan="rowspan='2'";
                        $bordertopcallnumber="border-top: solid 1px #CCC;";
                    }
                    ($LabelData['Warna1'] != '' && $Model == 'a4-3') ? $warna_perpustakaan=';background-color:'.$LabelData['Warna1'] : $warna_perpustakaan='';
                    ($LabelData['Warna1'] != '' && $Model == 'a4-4') ? $warna_callnumber=';background-color:'.$LabelData['Warna1'] : $warna_callnumber='';

                    $result .= "<td style='width:50%; ".$paddingright." padding-bottom:25px;text-align: left; '>";
                    $result .="<table cellpadding='0px' cellspacing='0px' style='width:200px;mso-cellspacing: 0px;  margin: 0px; text-align: center;'>";
                    
                    $result .="<tr>";
                    $result .="<td style='height:10px;border-left: solid 1px #CCC; border-right: solid 1px #CCC; border-top: solid 1px #CCC; border-bottom: solid 1px #CCC'>";
                    $result .= "<span style='font-family: Arial;font-size: 13px '>".str_replace("\r\n", "<br />", $LabelData['Title'])."</span><br>";
                    $result .="<span style='width:412px;font-family:\"".$LabelData['Font']."\"; font-size: 11pt; '>".str_replace("\r\n", "<br />", '*'.$LabelData['Barcode'].'*')."</span>";
                    $result .="</td>";
                    //jika tj121-1 / tj121-3
                    if($colspan!='')
                    {
                        $result .=$callnumberColumn;
                    }
                    $result .="</tr>";
                    $result .="</table>";
                    $result .="</td>";
                    

                    if($no == 2 || $i == ($jumlahData - 0))
                    {
                       if($i == ($jumlahData - 0))
                       {
                            $result .= "<td style='width:50%;".$paddingright." text-align: left;'>&nbsp;</td>";
                       }
                       $result .= "</tr>";
                       $no=0;
                    }else{
                       $no++;
                    }

                    if($item == ($maxLabelPerPage - 1) || $rec == $jumlahData)
                    {
                       $result .= $additionalRowHeight;
                       $result .= "</table>";
                       $result .= "</div>";
                       $item=0;
                    }else{
                       $item++;
                    }
                }
            break;

            
            
        }
        
        $result .="</td>\n";
        $result .="</tr>\n";
        $result .="</table>";

        
        //echo $result; die;
        header("Content-type: application/vnd.ms-word;charset=utf-8");
        header("Content-Disposition: attachment;Filename=Label-".strtoupper($Model)."-".date("Ymd").".doc");

        $exportResult='';

        $exportResult .= "<html xmlns:v=urn:schemas-microsoft-com:vml " .
            "xmlns:o='urn:schemas-microsoft-com:office:office' " .
            "xmlns:w='urn:schemas-microsoft-com:office:word'" .
            "xmlns:m='http://schemas.microsoft.com/office/2004/12/omml'" .
            "xmlns='http://www.w3.org/TR/REC-html40'>" .
            "<head>";
        $exportResult .= "<meta http-equiv=Content-Type content=\"text/html; charset=unicode\">";
        $exportResult .= "<meta name=ProgId content=Word.Document>";
        $exportResult .= "<meta name=Generator content=\"Microsoft Word 15\">";
        $exportResult .= "<meta name=Originator content=\"Microsoft Word 15\">";
        $exportResult .= "<link rel=File-List href=\"test_files/filelist.xml\">";
        $exportResult .= "<title>Label</title>";
        $exportResult .= "<link rel=themeData href=\"test_files/themedata.thmx\">";
        $exportResult .= "<link rel=colorSchemeMapping href=\"test_files/colorschememapping.xml\">";
        $exportResult .= "</head>";
        $exportResult .= "<div class=WordSection1>";
        $exportResult .= "<body lang=EN-US style='tab-interval:.5in'>";
        $exportResult .= $result;
        $exportResult .= "</body>";
        $exportResult .= "</div>";
        $exportResult .= "</html>";

        echo $exportResult;
    }


    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionCetakLabelProses($actids,array $CekId)
    {
        if(count($CekId) > 0)
        {
            $actids=explode("|", $actids);
            $sumber=$actids[0];
            $model=$actids[1];
            $format=$actids[2];
            $ids=$CekId;
            $jumlahData =  count($ids);
            $result='';
            $namaPerpustakaan = Yii::$app->config->get('NamaPerpustakaan');
            $fontBarcode =  (Yii::$app->config->get('FontBarcode') == null) ? 'IDAHC39M Code 39 Barcode' : Yii::$app->config->get('FontBarcode');
            $datalabel= array();

            for ($i=0; $i < $jumlahData; $i++) { 
                $data = Collections::find()
                ->select(['ID','Catalog_id','NomorBarcode','NoInduk','CallNumber'])
                ->where(['ID'=>$ids[$i]])
                ->orderBy('ID,Catalog_id ASC')
                ->one();

                $barcode = $data->NomorBarcode;

                if(strtolower($sumber)=='catalogs')
                {
                    //From Catalogs
                    $callnumber = $data->catalog->CallNumber;
                }else{
                    //Collections
                    $callnumber = $data->CallNumber;
                }

                
                if(strlen($data->catalog->Title) > 20)
                {
                    $title=substr($data->catalog->Title,0,20).'...';
                }else{
                    $title=$data->catalog->Title;
                }

                $deweyno = $data->catalog->DeweyNo;

                $kodewarna1='&nbsp;';
                $warna1='';
                $kodewarna2='&nbsp;';
                $warna2='';
                $kodewarna3='&nbsp;';
                $warna3='';
                $kodewarna4='&nbsp;';
                $warna4='';
                $kodewarna5='&nbsp;';
                $warna5='';
                if(!empty($deweyno) && strlen($deweyno) > 0)
                {
                    $deweyno = filter_var($deweyno, FILTER_SANITIZE_NUMBER_INT);

                    
                    if(strlen($deweyno) > 0)
                    {
                        $kodewarna1=substr($deweyno,0,1);
                        $warna1 = \common\models\MasterKelasBesar::find()->where(['LIKE', 'kdKelas', $kodewarna1])->one()->warna;
                    }

                    if(strlen($deweyno) > 1)
                    {
                        $kodewarna2=substr($deweyno,1,1);
                        $warna2 = \common\models\MasterKelasBesar::find()->where(['LIKE', 'kdKelas', $kodewarna2])->one()->warna;
                    }
                    if(strlen($deweyno) > 2)
                    {
                        $kodewarna3=substr($deweyno,2,1);
                        $warna3 = \common\models\MasterKelasBesar::find()->where(['LIKE', 'kdKelas', $kodewarna3])->one()->warna;
                    }

                    if(strlen($deweyno) > 3)
                    {
                        $kodewarna4=substr($deweyno,3,1);
                        $warna4 = \common\models\MasterKelasBesar::find()->where(['LIKE', 'kdKelas', $kodewarna4])->one()->warna;
                    }

                    if(strlen($deweyno) > 4)
                    {
                        $kodewarna5=substr($deweyno,4,1);
                        $warna5 = \common\models\MasterKelasBesar::find()->where(['LIKE', 'kdKelas', $kodewarna5])->one()->warna;
                    }  
                }

                

                if(empty($namaPerpustakaan)) { $namaPerpustakaan='&nbsp;'; }
                if(empty($title)) { $title='&nbsp;'; }
                if(empty($barcode)) { $barcode='&nbsp;'; }
                if(empty($callnumber)) { $callnumber='&nbsp;'; }

                $datalabel[] =  [
                    'NamaPerpustakaan'=>$namaPerpustakaan,
                    'Font'=>$fontBarcode,
                    'Barcode'=>$barcode,
                    'CallNumber'=>$callnumber,
                    'DeweyNo'=>$deweyno,
                    'Title'=>$title,
                    'Warna1'=>$warna1,
                    'Warna2'=>$warna2,
                    'Warna3'=>$warna3,
                    'Warna4'=>$warna4,
                    'Warna5'=>$warna5,
                    'KodeWarna1'=>$kodewarna1,
                    'KodeWarna2'=>$kodewarna2,
                    'KodeWarna3'=>$kodewarna3,
                    'KodeWarna4'=>$kodewarna4,
                    'KodeWarna5'=>$kodewarna5
                ];
            }
            
            $content['LabelData'] = $datalabel;

            switch ($format) {
                case 'pdf':

                    //Remder label model for PDF
                    
                    switch ($model) {

                        //Label Roll
                        case 'lr1':
                            $this->exportPdfLabelRoll('_pdf-view-label-lr1.php',$content,'Label LR1',$namaPerpustakaan,'Label-LR1-'.date("Ymd").'.pdf');
                        break;

                        case 'lr2':
                            $this->exportPdfLabelRoll('_pdf-view-label-lr2.php',$content,'Label LR2',$namaPerpustakaan,'Label-LR2-'.date("Ymd").'.pdf');
                        break;

                        case 'lr3':
                            $this->exportPdfLabelRoll('_pdf-view-label-lr3.php',$content,'Label LR3',$namaPerpustakaan,'Label-LR3-'.date("Ymd").'.pdf');
                        break;

                        case 'lr4':
                            $this->exportPdfLabelRoll('_pdf-view-label-lr4.php',$content,'Label LR4',$namaPerpustakaan,'Label-LR4-'.date("Ymd").'.pdf');
                        break;

                        case 'lr5':
                            $this->exportPdfLabelRoll('_pdf-view-label-lr5.php',$content,'Label LR5',$namaPerpustakaan,'Label-LR5-'.date("Ymd").'.pdf');
                        break;

                        case 'lr6':
                            $this->exportPdfLabelRoll('_pdf-view-label-lr6.php',$content,'Label LR6',$namaPerpustakaan,'Label-LR6-'.date("Ymd").'.pdf');
                        break;

                        //Barcode Roll
                        case 'br1':
                            $this->exportPdfBarcodeRoll('_pdf-view-label-br1.php',$content,'Label BR1',$namaPerpustakaan,'Label-BR1-'.date("Ymd").'.pdf');
                        break;

                        case 'br2':
                            $this->exportPdfBarcodeRoll('_pdf-view-label-br2.php',$content,'Label BR2',$namaPerpustakaan,'Label-BR2-'.date("Ymd").'.pdf');
                        break;

                        //TJ 107
                        case 'tj107-1':
                            $this->exportPdfTJ107('_pdf-view-label-tj107-1.php',$content,'Label TJ107-1',$namaPerpustakaan,'Label-TJ107-1-'.date("Ymd").'.pdf');
                        break;

                        //TJ 121
                        case 'tj121-1':
                            $this->exportPdfTJ121('_pdf-view-label-tj121-1.php',$content,'Label TJ121-1',$namaPerpustakaan,'Label-TJ121-1-'.date("Ymd").'.pdf');
                        break;

                        case 'tj121-2':
                            $this->exportPdfTJ121('_pdf-view-label-tj121-2.php',$content,'Label TJ121-2',$namaPerpustakaan,'Label-TJ121-2-'.date("Ymd").'.pdf');
                        break;

                        case 'tj121-3':
                            $this->exportPdfTJ121('_pdf-view-label-tj121-3.php',$content,'Label TJ121-3',$namaPerpustakaan,'Label-TJ121-3-'.date("Ymd").'.pdf');
                        break;

                        case 'tj121-4':
                            $this->exportPdfTJ121('_pdf-view-label-tj121-4.php',$content,'Label TJ121-4',$namaPerpustakaan,'Label-TJ121-4-'.date("Ymd").'.pdf');
                        break;
                        
                        case 'tj121-5':
                            $this->exportPdfTJ121_5('_pdf-view-label-tj121-5.php',$content,'Label TJ121-5',$namaPerpustakaan,'Label-TJ121-5-'.date("Ymd").'.pdf');
                        break;

                        //GC 121
                        case 'gc121-1':
                            $this->exportPdfgc121('_pdf-view-label-gc121-1.php',$content,'Label GC121-1',$namaPerpustakaan,'Label-GC121-1-'.date("Ymd").'.pdf');
                        break;

                        case 'gc121-2':
                            $this->exportPdfgc121('_pdf-view-label-gc121-2.php',$content,'Label GC121-2',$namaPerpustakaan,'Label-GC121-2-'.date("Ymd").'.pdf');
                        break;

                        case 'gc121-3':
                            $this->exportPdfgc121('_pdf-view-label-gc121-3.php',$content,'Label GC121-3',$namaPerpustakaan,'Label-GC121-3-'.date("Ymd").'.pdf');
                        break;

                        case 'gc121-4':
                            $this->exportPdfgc121('_pdf-view-label-gc121-4.php',$content,'Label GC121-4',$namaPerpustakaan,'Label-GC121-4-'.date("Ymd").'.pdf');
                        break;

                        //A4
                        case 'a4-1':
                            $this->exportPdfA4('_pdf-view-label-a4-1.php',$content,'Label A4-1',$namaPerpustakaan,'Label-A4-1-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-2':
                            $this->exportPdfA4('_pdf-view-label-a4-2.php',$content,'Label A4-2',$namaPerpustakaan,'Label-A4-2-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-3':
                            $this->exportPdfA4('_pdf-view-label-a4-3.php',$content,'Label A4-3',$namaPerpustakaan,'Label-A4-3-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-4':
                            $this->exportPdfA4('_pdf-view-label-a4-4.php',$content,'Label A4-4',$namaPerpustakaan,'Label-A4-4-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-5':
                            $this->exportPdfA4('_pdf-view-label-a4-5.php',$content,'Label A4-5',$namaPerpustakaan,'Label-A4-5-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-6':
                            $this->exportPdfA4('_pdf-view-label-a4-6.php',$content,'Label A4-6',$namaPerpustakaan,'Label-A4-6-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-7':
                            $this->exportPdfA4('_pdf-view-label-a4-7.php',$content,'Label A4-7',$namaPerpustakaan,'Label-A4-7-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-8':
                            $this->exportPdfA4('_pdf-view-label-a4-8.php',$content,'Label A4-8',$namaPerpustakaan,'Label-A4-8-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-9':
                            $this->exportPdfA4('_pdf-view-label-a4-9.php',$content,'Label A4-9',$namaPerpustakaan,'Label-A4-9-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-10':
                            $this->exportPdfA4('_pdf-view-label-a4-10.php',$content,'Label A4-10',$namaPerpustakaan,'Label-A4-10-'.date("Ymd").'.pdf');
                        break;

                        case 'a4-11':
                            $this->exportPdfA4('_pdf-view-label-a4-11.php',$content,'Label A4-11',$namaPerpustakaan,'Label-A4-11-'.date("Ymd").'.pdf');
                        break;
                        case 'a4-12':
                            $this->exportPdfA4('_pdf-view-label-a4-12.php',$content,'Label A4-12',$namaPerpustakaan,'Label-A4-12-'.date("Ymd").'.pdf');
                        break;
                    }
                break;

                case 'doc':
                    if($model == 'a4-11'){
                        $this->exportWord11($content['LabelData'],$model);
                    }elseif($model == 'tj107-1'){
                        $this->exportWordTj107($content['LabelData'],$model);   
                    }elseif($model == 'tj121-5'){
                        $this->exportWordTj121($content['LabelData'],$model);   
                    }else{
                        $this->exportWord($content['LabelData'],$model);   
                    }
                    // $this->exportWord($content['LabelData'],$model);
                    break;
                    // $this->exportWord($content['LabelData'],$model);
                    // break;

                case 'odt':
                    echo "<center><h2>Coming soon!</h2>-Under Development-</center>";
                    break;
            }
            
        }
        
    }

    /**
     * get checkbox process message .
     * @return string
     */
    public function actionGetMessageCheckboxProcess($success,$value,$karantinaMsg='')
    {
        if($success == true)
            if($karantinaMsg != '')
                return '<span style="color:green">'.$this->actionGetDatetimeNowStr().' - '.$karantinaMsg.' pada Nomor Barcode = '.$value.'</span><br>';
            else
                return '<span style="color:green">'.$this->actionGetDatetimeNowStr().' - Berhasil diubah  pada Nomor Barcode = '.$value.'</span><br>';
        else
            if($karantinaMsg != '')
                return '<span style="color:red">'.$this->actionGetDatetimeNowStr().' - '.$karantinaMsg.' pada Nomor Barcode = '.$value.'</span><br>'; 
            else
                return '<span style="color:red">'.$this->actionGetDatetimeNowStr().' - Gagal diubah pada Nomor Barcode = '.$value.'</span><br>'; 
    }

    /**
     * Process records which is checked
     * @return mixed
     */
    public function actionCheckboxProcess()
    {
        $isUserHasAccess = CatalogHelpers::isUserHasAccess(Yii::$app->user->identity->id);
        if($isUserHasAccess == false)
        {
            $this->getView()->registerJs('
                alertSwal(,"warning","5000","'.Yii::$app->urlManager->createUrl(["akuisisi/koleksi"]).'");
            ');
            $msg = '
                <div class="box-group" id="accordion">
                    <div class="panel panel-danger">
                      <div class="box-header">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseMsg" style="color:maroon">
                            Proses aksi gagal.
                          </a>
                      </div>
                      <div id="collapseMsg" class="panel-collapse in">
                        <div class="standard-error-summary" style="color:red">
                         User '. Yii::$app->user->identity->username.' tidak mempunyai akses melakukan perubahan data koleksi!
                        </div>
                      </div>
                    </div>
                </div>
            ';
            return $msg;
        }else{
        $post = Yii::$app->request->post(); $msg='';
        //echo '<pre>'; print_r($post); echo '</pre>';die;
        if(isset($post['action']) && isset($post['row_id']))
        {
            $actid;
            $rowid = $post['row_id'];
            if(isset($post['actionid']))
                $actid = $post['actionid'];
            if(isset($post['actionid2']))
                $actid2 = $post['actionid2'];
            
            switch ($post['action']) {
                case 'OPAC1':
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $model->ISOPAC =  1;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode);
                        }
                    }
                    break;

                case 'OPAC0':
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $model->ISOPAC =  0;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode);
                        }
                    }
                    break;

                case 'MEDIA':
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $model->Media_id =  $actid;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode);
                        }
                    }
                    break;

                case 'SUMBER':
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $model->Source_id =  $actid;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode);
                        }
                    }
                    break;

                case 'KATEGORI':
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $model->Category_id =  $actid;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode);
                        }
                    }
                    break;

                case 'AKSES':
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $model->Rule_id =  $actid;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode);
                        }
                    }
                    break;

                case 'STATUS':
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $model->Status_id =  $actid;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode);
                        }
                    }
                    break;

                /*case 'LOKASILIB':
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $model->Location_Library_id =  $actid;
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode);
                        }
                    }
                    break;*/
                case 'DELETE_PERMANENT':
                    QuarantinedCollections::deleteAll(['in','ID',$rowid]);
                    break;

                case 'DEPOSIT_DELETE_PERMANENT':
                    Collections::deleteAll(['in','ID',$rowid]);
                    break;

                case 'LOKASI':
                    
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $model->Location_Library_id = $actid;
                        $model->Location_id         = $actid2;
                        
                        if($model->save(false))
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode);
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode);
                        }
                    }

                    break;
                case 'KERANJANG0':
                    KeranjangKoleksi::deleteAll(['in','Collection_id',$rowid]);
                    //return $this->redirect(['keranjang']);
                    break;

                case 'KERANJANG1':
                    foreach ($rowid as $key => $value) {
                        $model = Collections::findOne($value);
                        $modelkeranjang1 = KeranjangKoleksi::findOne($value);
                        if($modelkeranjang1 != null)
                        {
                            //Jika sudah ada id koleksi di tabel keranjang koleksi maka d delete dahulu
                            $modelkeranjang1->delete();
                        }

                        $modelkeranjang2 = new KeranjangKoleksi();
                        $modelkeranjang2->Collection_id = $value;

                        if($modelkeranjang2->save())
                        {
                            $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode,'Koleksi berhasil dimasukan ke keranjang');
                        }else{
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode,'Koleksi gagal dimasukan ke keranjang');
                        }
                    }
                    break;
                case 'CETAKLABEL':
                    $actids=explode("-", $actid);
                    CollectionHelpers::cetakLabel($actids[0],$actids[1],$actids[2],$actids[3],$rowid);
                    break;
                case 'KARANTINA':
                    foreach ($rowid as $key => $value) {
                         (int)$count = Collectionloanitems::find()
                         ->where(['Collection_id'=>$value])
                         ->count();

                         $model = Collections::findOne($value);
                         if($count > 0)
                         {
                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode,'Koleksi tersambung dengan data peminjaman, sehingga tidak dapat dikarantina.');
                            continue;
                         }else{
                            $trans = Yii::$app->db->beginTransaction();
                            try {
                                $modelq1 = QuarantinedCollections::findOne($value);
                                if($modelq1 != null)
                                {
                                    //Jika sudah ada id koleksi di tabel karantina maka d delete dahulu
                                    $modelq1->delete();
                                }

                                
                                    //Insert ke table karantina dari table koleksi
                                    $command = Yii::$app->db->createCommand('INSERT INTO quarantined_collections SELECT * FROM collections WHERE ID=:ID');
                                    $command->bindParam(':ID', $value);
                                    if($command->execute())
                                    {
                                // echo '<pre>exec';die;
                                        //Update data timespan karantina
                                        $modelq2 = QuarantinedCollections::findOne($value);
                                        $modelq2->QUARANTINEDBY = (int)Yii::$app->user->identity->ID;
                                        $modelq2->QUARANTINEDDATE = new \yii\db\Expression('NOW()');
                                        $modelq2->QUARANTINEDTERMINAL = \Yii::$app->request->userIP;
                                        if($modelq2->save())
                                        {
                                            //[warning] history save nya belum
                                            //
                                            //
                                            //Delete semua data di stockopnamedetail yang berhubungan dengan ID koleksi yang di karantina
                                            $command2 = Yii::$app->db->createCommand('DELETE FROM stockopnamedetail WHERE CollectionID=:ID');
                                            $command2->bindParam(':ID', $value);
                                            $command2->execute();

                                            if($model->delete())
                                            {
                                                $trans->commit(); 
                                                $msg .= $this->actionGetMessageCheckboxProcess(true,$model->NomorBarcode,'Koleksi berhasil dikarantina');
                                            }else{
                                                $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode,'Gagal menghapus di koleksi');
                                                continue;
                                            }
                                            
                                        }else{
                                            $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode,'Gagal mengubah timestamp di karantina Koleksi');
                                            continue;
                                        }
                                    }else{
                                        $msg .= $this->actionGetMessageCheckboxProcess(false,$model->NomorBarcode,'Gagal menyimpan di Karantina koleksi'.var_dump($modelq->getErrors()));
                                        continue;
                                    }
                                
                            } catch (Exception $e) {
                                $trans->rollback();
                            }
                         }
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        if($msg != '')
            $msg = '
                <div class="box-group" id="accordion">
                    <div class="panel panel-success">
                      <div class="box-header">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseMsg">
                            Riwayat proses aksi
                          </a>
                      </div>
                      <div id="collapseMsg" class="panel-collapse in">
                        <div class="standard-error-summary">
                         '.$msg.'    
                        </div>
                      </div>
                    </div>
                </div>
            ';
            return $msg;
        /*else
            Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app','Success Save'),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
            return $this->redirect(['index']);*/
        //return $this->redirect(['index']);
        }
    }


    /**
     * Restore QuarantinedCollections model.
     * @param double $id
     * @return mixed
     */
    public function actionFlashMessage($type,$Message)
    {
        Yii::$app->getSession()->setFlash('success', [
                        'type' => $type,
                        'duration' => 500,
                        'icon' => 'fa fa-info-circle',
                        'message' => Yii::t('app',$Message),
                        'title' => 'Info',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
    }

    public function actionKarantinaProses($id)
    {
        (int)$count = Collectionloanitems::find()
         ->where(['Collection_id'=>$id])
         ->count();

         $model = Collections::findOne($id);
         if($count > 0)
         {
            $this->actionFlashMessage('danger','Koleksi dengan nomor barcode='.$model->NomorBarcode.' tersambung dengan data peminjaman, sehingga tidak dapat dikarantina.');
         }else{
            $trans = Yii::$app->db->beginTransaction();
            try {
                $modelq1 = QuarantinedCollections::findOne($id);
                if($modelq1 != null)
                {
                    //Jika sudah ada id koleksi di tabel karantina maka d delete dahulu
                    $modelq1->delete();
                }
                
                    //Insert ke table karantina dari table koleksi
                    $command = Yii::$app->db->createCommand('INSERT INTO quarantined_collections SELECT * FROM collections WHERE ID=:ID');
                    $command->bindParam(':ID', $id);
                    if($command->execute())
                    {
                        //Update data timespan karantina
                        $modelq2 = QuarantinedCollections::findOne($id);
                        $modelq2->QUARANTINEDBY = (int)Yii::$app->user->identity->ID;
                        $modelq2->QUARANTINEDDATE = new \yii\db\Expression('NOW()');
                        $modelq2->QUARANTINEDTERMINAL = \Yii::$app->request->userIP;
                        if($modelq2->save())
                        {
                            //[warning] history save nya belum
                            //
                            //
                            //Delete semua data di stockopnamedetail yang berhubungan dengan ID koleksi yang di karantina
                            $command2 = Yii::$app->db->createCommand('DELETE FROM stockopnamedetail WHERE CollectionID=:ID');
                            $command2->bindParam(':ID', $id);
                            $command2->execute();

                            if($model->delete())
                            {
                                $trans->commit(); 
                                $this->actionFlashMessage('info','Koleksi dengan nomor barcode='.$model->NomorBarcode.' berhasil dikarantina.');
                            }else{
                                $this->actionFlashMessage('danger','Koleksi dengan nomor barcode='.$model->NomorBarcode.' gagal dihapus.');
                            }
                            
                        }else{
                            $this->actionFlashMessage('danger','Koleksi dengan nomor barcode='.$model->NomorBarcode.' gagal mengubah timestamp di koleksi');
                        }
                    }else{
                        $this->actionFlashMessage('danger','Koleksi dengan nomor barcode='.$model->NomorBarcode.' Gagal disimpan di Karantina koleksi'.var_dump($modelq->getErrors()));
                    }
                
            } catch (Exception $e) {
                $trans->rollback();
            }
         }
         return $this->redirect(['index']);
    }


    /**
     * Restore QuarantinedCollections model.
     * @param double $id
     * @return mixed
     */
    public function actionRestore($id)
    {
        $model = $this->findModelKarantina($id);

        $QuarantinedCatalogId = $model->Catalog_id;

        if(!empty($QuarantinedCatalogId))
        {
            $CatalogId = Catalogs::findOne($QuarantinedCatalogId)->ID;
            if(empty($CatalogId))
            {
                $this->actionFlashMessage('danger','Maaf, koleksi tidak dapat di-restore karena cantuman katalognya telah dikarantinakan');
                return $this->redirect(['karantina']);

            }else{
                $trans = Yii::$app->db->beginTransaction();
                try {

                    //Insert ke table koleksi dari table karantina
                    $command = Yii::$app->db->createCommand('INSERT INTO collections SELECT * FROM quarantined_collections WHERE ID=:ID');
                    $command->bindParam(':ID', $id);
                    if($command->execute())
                    {
                        // $command = Yii::$app->db->createCommand('INSERT INTO pengiriman SELECT * FROM quarantined_pengiriman WHERE ID=:ID');
                        // $command->bindParam(':ID', $id);
                        // $command->execute();

                        //Update data timespan karantina
                        $modelc = Collections::findOne($id);
                        $modelc->QUARANTINEDBY = (int)Yii::$app->user->identity->ID;
                        $modelc->QUARANTINEDDATE = new \yii\db\Expression('NOW()');
                        $modelc->QUARANTINEDTERMINAL = \Yii::$app->request->userIP;
                        if($modelc->save(false))
                        {
                            //hapus data karantina koleksi
                            if($model->delete())
                            {
                                $trans->commit();
                                $this->actionFlashMessage('info','Data berhasil direstore');
                                return $this->redirect(['karantina']);
                            }
                        }else{
                            $trans->rollback();
                            $this->actionFlashMessage('danger','Gagal mengubah timespan karantina di koleksi');
                            return $this->redirect(['karantina']);
                        }
                    }else{
                        $trans->rollback();
                        $this->actionFlashMessage('danger','Gagal menambah data di koleksi');
                        return $this->redirect(['karantina']);
                    }
                } catch (Exception $e) {
                    $trans->rollback();
                }
            }
        }
    }

    /**
     * Truncate
     * @return mixed
     */
    public function actionKeranjangReset()
    {
        KeranjangKoleksi::deleteAll('CreateBy = '.(string)Yii::$app->user->identity->ID);
        //KeranjangKoleksi::deleteAll();
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CollectionSearch;
        $dataProvider = $searchModel->advancedSearch(1,$rules);
       /* $searchModel = new CollectionSearch;
        $dataProvider = $searchModel->search(1,Yii::$app->request->getQueryParams());*/

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>'keranjang',
            'rules'=>$rules
            ]);
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionKarantina()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new QuarantinedCollectionSearch;
        $dataProvider = $searchModel->advancedSearch($rules);

        /*$searchModel = new QuarantinedCollectionSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());*/

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>'karantina',
            'rules'=> $rules
        ]);
    }


    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionKeranjang()
    {
        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CollectionSearch;
        $dataProvider = $searchModel->advancedSearch(1,$rules);

        /*$searchModel = new CollectionSearch;
        $dataProvider = $searchModel->search(1,Yii::$app->request->getQueryParams());*/

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>'keranjang',
            'rules'=>$rules
            ]);
    }

    /**
     * Lists all Collections models.
     * @return mixed
     */
    public function actionIndex()
    {
        $perpage = 20;
        $getPerPage = $_GET['per-page'];
        if(!empty($getPerPage)){
            $perpage = (int)$_GET['per-page'];
        }

        $rules = Json::decode(Yii::$app->request->get('rules'));
        
        $searchModel = new CollectionSearch;
        $dataProvider = $searchModel->advancedSearch(0,$rules);
        $dataProvider->pagination->pageSize=$perpage;
        /*$searchModel = new CollectionSearch;
        $dataProvider = $searchModel->search(0,Yii::$app->request->getQueryParams());*/

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'for'=>'koleksi',
            'rules'=>$rules
            ]);
    }

    /**
     * get list of dropdown by fast action.
     * @return mixed
     */
    public function actionGetDropdown($id)
    {
        return $this->renderAjax('_subDropdown', [
            'processid' => $id,
            ]);
    }

    /**
     * get list of dropdown by fast action.
     * @return mixed
     */
    public function actionGetDropdownRuang($id)
    {
        $model = Locations::find()->where(['LocationLibrary_id'=>$id])->all();
        return $this->renderAjax('_subDropdownRuang', [
            'model' => $model]);
    }

    /**
     * get list of dropdown by fast action.
     * @return mixed
     */
    public function actionGetDropdownLabelmodel($id)
    {
        $model = [];
        switch ($id) {
            default:
            case 'label-roll':
                $model = [
                        'lr1'=>'Model LR1 (No. Panggil + Barcode)',
                        'lr2'=>'Model LR2 (No. Panggil + Barcode)',
                        'lr3'=>'Model LR3 (No. Panggil + Barcode + 1 Warna)',
                        'lr4'=>'Model LR4 (No. Panggil + Barcode + 1 Warna)',
                        'lr5'=>'Model LR5 (No. Panggil Tanpa Barcode)',
                        'lr6'=>'Model LR6 (No. Panggil Tanpa Barcode + 1 Warna)'
                        ];
                break;

            case 'barcode-roll':
                $model = [
                        'br1'=>'Model BR1 (Barcode)',
                        'br2'=>'Model BR2 (Barcode + Judul)'
                        ];
                break;

            case 'label-tj107':
                $model = [
                        'tj107-1'=>'Model TJ107-1 (Hanya Barcode)'
                        ];
                break;

            case 'label-tj121':
                $model = [
                        'tj121-1'=>'Model TJ121-1 (No. Panggil + Barcode)',
                        'tj121-2'=>'Model TJ121-2 (No. Panggil + Barcode)',
                        'tj121-3'=>'Model TJ121-3 (No. Panggil + Barcode + 1 Warna)',
                        'tj121-4'=>'Model TJ121-4 (No. Panggil + Barcode + 1 Warna)',
                        'tj121-5'=>'Model TJ121-5 (No. Panggil Tanpa Barcode)'
                        ];
                break;

            case 'label-gc121':
                $model = [
                        'gc121-1'=>'Model GC121-1 (No. Panggil + Barcode)',
                        'gc121-2'=>'Model GC121-2 (No. Panggil + Barcode)',
                        'gc121-3'=>'Model GC121-3 (No. Panggil + Barcode + 1 Warna)',
                        'gc121-4'=>'Model GC121-4 (No. Panggil + Barcode + 1 Warna)'
                        ];
                break;

            case 'a4':
                $model = [
                        'a4-1'=>'Model A4-1 (No. Panggil + Barcode)',
                        'a4-2'=>'Model A4-2 (No. Panggil + Barcode)',
                        'a4-3'=>'Model A4-3 (No. Panggil + Barcode + 1 Warna)',
                        'a4-4'=>'Model A4-4 (No. Panggil + Barcode + 1 Warna)',
                        'a4-5'=>'Model A4-5 (No. Panggil + Barcode)',
                        'a4-6'=>'Model A4-6 (No. Panggil + Barcode)',
                        'a4-7'=>'Model A4-7 (No. Panggil + Barcode + 1 Warna)',
                        'a4-8'=>'Model A4-8 (No. Panggil + Barcode + 1 Warna)',
                        'a4-9'=>'Model A4-9 (No. Panggil + Barcode + 5 Warna)',
                        'a4-10'=>'Model A4-10 (No. Panggil Rata Kiri + Barcode)',
                        'a4-11'=>'Model A4-11 (No. Panggil + 5 Warna)',
                        'a4-12'=>'Model A4-12 (Barcode + Judul)'
                        ];
                break;
        }
        return $this->renderAjax('_subDropdownLabelmodel', [
            'model' => $model]);
    }

    /**
     * get list of dropdown by fast action.
     * @return mixed
     */
    public function actionGetRuang($id)
    {
        $model = Locations::find()->where(['LocationLibrary_id'=>$id])->all();
        return $this->renderAjax('_ruang', [
            'model' => $model]);
    }

    
    /**
     * Displays a single Collections model.
     * @param double $id
     * @return mixed
     */
    public function actionViewkarantina($id)
    {
        $model = $this->findModelKarantina($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['viewkarantina', 'id' => $model->ID]);
        } else {
            return $this->render('viewkarantina', ['model' => $model]);
        }
    }     

    

    /**
     * Deletes an existing Collections model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param double $id
     * @return mixed
     */
    //ga boleh hapus permanen, wajib di karantina
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', [
            'type' => 'info',
            'duration' => 500,
            'icon' => 'fa fa-info-circle',
            'message' => Yii::t('app','Success Delete'),
            'title' => 'Info',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
            ]);
        return $this->redirect(['index']);
    }*/

    /**
     * Finds the Collections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Collections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $id
     * @return Collections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelKarantina($id)
    {
        if (($model = QuarantinedCollections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
