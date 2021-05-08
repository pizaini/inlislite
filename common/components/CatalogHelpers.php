<?php

namespace common\components;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fields;
use common\models\Collections;
use common\models\Catalogs;
use common\models\Catalogfiles;
use common\models\Cardformats;
use common\models\CatalogRuas;
use common\models\CatalogSubruas;
use common\models\CatalogCardTemplate;
use common\models\AuthHeader;
use common\models\CatalogRuasOnline;
use common\models\CatalogSubruasOnline;
use common\models\SerialArticles;
use common\models\SerialArticlesRepeatable;
use common\models\SerialArticlesSearch;
use common\models\SerialArticlefiles;

/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class CatalogHelpers extends Catalogs
{
    
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public static function isUserHasAccess($userID)
    {
        $sql = "SELECT users.ID FROM users INNER JOIN userloclibforcol ON userloclibforcol.User_id=users.ID WHERE ID = " .$userID.  " and IsActive = 1";
        $result = Yii::$app->db->createCommand($sql)->queryScalar();
        return $result;
    }

    public static function getControlNumber($formatid)
    {
        //$initValue = $BranchCode.;
        $controlNumber; $newControlNumber; $branchCode_Controlnum; $digitFormat_Controlnum;

        $branchCode_Controlnum = 'INLIS';
        $digitFormat_Controlnum = 15;

        if ($formatid == 2)
        {
            $branchCode_Controlnum = "AUTH";
            $controlNumber = AuthHeader::find()->select('MAX(ID) AS ID')->one()->ID;
        }
        else
        {
            $sql='SELECT MAX(ControlNumber) AS MaxControlNumber FROM catalogs WHERE ControlNumber LIKE "'.$branchCode_Controlnum.'0%"';
            $controlNumber = Catalogs::findBySql($sql)->one()->MaxControlNumber;
        }
        (int)$ControlNumberIndex = 0;
        if($controlNumber)
        {
            $controlNumber = (int)preg_replace( '/[^0-9]/', '', $controlNumber);
        }
        $newControlNumber =  $branchCode_Controlnum.str_pad((int)$controlNumber+1, $digitFormat_Controlnum, '0', STR_PAD_LEFT);
        
        return $newControlNumber ;
        
    }

    public static function getBibId($formatid)
    {
        $bibCode; $digitFormat; $newId; $maxId; $bibCode_Auth; $digitFormat_Auth; $bibCode_Biblio; $digitFormat_Biblio; 

        $bibCode_Auth = 'AUTH-';
        $digitFormat_Auth = 11;
        $bibCode_Biblio = '0010-';
        $digitFormat_Biblio = 6;

        $yearMonth =  date('my');
        if($formatid == 2)
        {
            $bibCode = $bibCode_Auth;
            $digitFormat = $digitFormat_Auth;
            $maxId = AuthHeader::find()->select('MAX(ID) AS ID')->one()->ID;
        }else{
            $bibCode = $bibCode_Biblio;
            $digitFormat = $digitFormat_Biblio;
            $CodeLen = strlen($bibCode.(string)$yearMonth);
            $BibIdLen = $CodeLen + $digitFormat;
            $sql = 'SELECT SUBSTR(MAX(BIBID),"'.$bibCode.(string)$yearMonth.'") AS MaxBibId FROM catalogs WHERE BIBID LIKE "'.$bibCode.(string)$yearMonth.'%" AND LENGTH(BIBID)='.(string)$BibIdLen;
            $maxId = Catalogs::findBySql($sql)->one()->MaxBibId;
        }
        $maxId =  (int)$maxId + 1;
        $newId =  $bibCode.$yearMonth.str_pad($maxId, $digitFormat, '0', STR_PAD_LEFT);

        return $newId ;
    }

    public static function getCallNumber($id,$refer)
    {
        $resfinal=[];
        if($refer=='catalog')
        {
            $catalogid=$id;
        }else{
            //when give id coll
            $catalogid =  Collections::findOne($id)->Catalog_id; 
        }
        $res = CatalogRuas::find()->addSelect(['Value'])->where(['CatalogId'=>$catalogid,'Tag'=>['084','090']])->asArray()->all();
        $res = \yii\helpers\ArrayHelper::map($res,'Value','Value');
        foreach ($res as $key => $value) {
           $resfinal[] = trim(preg_replace('/(\$\w)(.*?)(\$?)/', '', $value));
        }
        return $resfinal;
    }

    public static function deleteOldSubRuas($catid,$tag)
    {
        \Yii::$app
        ->db
        ->createCommand()
        ->delete('catalog_ruas', [ 'and',['CatalogId' => $catid], ['Tag' => $tag]])
        ->execute();
        // echo 'bisa nihh';die;
    }

    /**
     * Save catalog ruas .
     * @return mixed
     */
    public static function saveCatalogRuas($catid,$ind1,$ind2,$tag,$value,$seq)
    {
        $modelcatruas = new CatalogRuas;
        $modelcatruas->CatalogId=$catid;
        if($ind1 != NULL){
            $modelcatruas->Indicator1=(string)$ind1;
        }
        if($ind2 != NULL){
            $modelcatruas->Indicator2=(string)$ind2;
        }
        $modelcatruas->Tag=(string)$tag;
        $modelcatruas->Value=$value;
        $modelcatruas->Sequence=$seq;
        if($modelcatruas->save())
        {
            //echo $catid.'|'.$ind1.'|'.$ind2.'|'.$tag.'|'.trim($value).'|'.$seq.'<br>';
            return true;
        }else{
            //echo '<b>'.$catid.'|'.$ind1.'|'.$ind2.'|'.$tag.'|'.trim($value).'|'.$seq.'</b><br>';
            //echo var_dump($modelcatruas->getErrors()); 
            return false;
        }
    }

    public static function saveCatalogRuasOnline($catid,$ind1,$ind2,$tag,$value,$seq)
    {
        $modelcatruas = new CatalogRuasOnline;
        $modelcatruas->CatalogId=$catid;
        $modelcatruas->CreateBy=1;
        if($ind1 != NULL){
            $modelcatruas->Indicator1=(string)$ind1;
        }
        if($ind2 != NULL){
            $modelcatruas->Indicator2=(string)$ind2;
        }
        $modelcatruas->Tag=(string)$tag;
        $modelcatruas->Value=$value;
        $modelcatruas->Sequence=$seq;
        if($modelcatruas->save())
        {
            //echo $catid.'|'.$ind1.'|'.$ind2.'|'.$tag.'|'.trim($value).'|'.$seq.'<br>';
            return true;
        }else{
            //echo '<b>'.$catid.'|'.$ind1.'|'.$ind2.'|'.$tag.'|'.trim($value).'|'.$seq.'</b><br>';
            //echo var_dump($modelcatruas->getErrors());
            return false;
        }
    }

    /**
     * Save catalog sub ruas .
     * @return mixed
     */
    public static function saveCatalogSubruas($subruas,$value,$seq)
    {
        $lastRuasId = CatalogRuas::find()->select('MAX(ID) AS ID')->one()->ID;
        $modelcatsubruas = new CatalogSubruas;
        $modelcatsubruas->RuasID=$lastRuasId;
        $modelcatsubruas->SubRuas=$subruas;
        $modelcatsubruas->Value=$value;
        $modelcatsubruas->Sequence=$seq;
        if($modelcatsubruas->save())
        {
            return true;
        }else{

            return false;
        }
    }

    public static function saveCatalogSubruasOnline($subruas,$value,$seq)
    {
        $lastRuasId = CatalogRuas::find()->select('MAX(ID) AS ID')->one()->ID;
        $modelcatsubruas = new CatalogSubruasOnline;
        $modelcatsubruas->RuasID=$lastRuasId;
        $modelcatsubruas->SubRuas=$subruas;
        $modelcatsubruas->Value=$value;
        $modelcatsubruas->Sequence=$seq;
        $modelcatsubruas->CreateBy = 1;
        if($modelcatsubruas->save())
        {
            return true;
        }else{

            return false;
        }
    }

    public static function getTandaBaca($tag,$code) {
        $output = \common\models\Fields::find()
        ->addSelect(['fielddatas.Delimiter AS TandaBaca'])
        ->innerJoin('fielddatas',' fielddatas.Field_id = fields.ID')
        ->where([
            'fields.Tag'=>$tag,
            'fielddatas.Code'=>$code
            ])
        ->one();
        return trim((string)$output->TandaBaca);
    }

    public static function cleanDollarRuas($value)
    {
        return trim(preg_replace('/(\$\w)(.*?)(\$?)/', '', $value));
    }

    public static function cleanLastChar($char,$value)
    {
        if($char=='/')
        {
            $output=trim(preg_replace('/(\/\z)(.*?)(\$?)/', '', $value));
        }else{
             $output=trim(preg_replace('/('.preg_quote($char).'\z)(.*?)(\$?)/', '', $value));
        }
        return $output;
    }

    public static function cleanFirstChar($char,$value)
    {
        if($char=='/')
        {
            $output=trim(preg_replace('/(\A\/)(.*?)(\$?)/', '', $value));
        }else{
            $output=trim(preg_replace('/(\A'.preg_quote($char).')(.*?)(\$?)/', '', $value));
        }
        return $output;
    }

    /**
     * Save catalog ruas .
     * @return mixed
     */
    public static function hasTag($id,$tag)
    {
        (int)$status = CatalogRuas::findBySql("SELECT * FROM catalog_ruas WHERE CatalogId=".$id." AND Tag LIKE '".$tag."'")->count();
        if($status > 1)
            $status=1;
        return (bool)$status;

    }

    public static function cetakKartu($idcardformat,$ids) 
    {

        $jumlahData =  count($ids);
        $modelcard =  Cardformats::findOne($idcardformat);
        $result='';
        if($modelcard != NULL)
        {
            $fontName = $modelcard->FontName;
            $fontSize = $modelcard->FontSize;
            $width = $modelcard->Width;
            $height = $modelcard->Height;
            

            for ($i=0; $i < $jumlahData ; $i++) { 
                $modelcat = Catalogs::findOne($ids[$i]);

                //echo $modelcat->ID.'<br>';
                $hastag1xx = self::hasTag($modelcat->ID,'1%');
                $template = $modelcard->FormatTeksNoAuthor;
                if ($hastag1xx==true)
                {
                    $template = $modelcard->FormatTeks;
                }
                $card = self::parseString($modelcat->ID,$template);
                // echo '<pre>'; print_r($card['CardList']); echo '</pre>';
                // $IsiTeksList =  $card['CardList'];
                // $HangingRowOn = 0;
                // $HangingRowOnStart = false;
                // $HangingStart = 0;
                // $HangingCount = 0;
                // $IsiTeksList = str_replace("--","&#8209;&#8209;",$IsiTeksList[0]);
                // $sItem = explode("\r\n",$IsiTeksList); 
                // echo '<pre>'; print_r($sItem); echo '</pre>'; die;

                $IsiTeksList =  $card['CardList'];
                $HangingRowOn = 0;
                $HangingRowOnStart = false;
                $HangingStart = 0;
                $HangingCount = 0;
                $IsiTeksList = str_replace("--","&#8209;&#8209;",$IsiTeksList[0]);
                $sItem = explode("\r\n",$IsiTeksList); 
                // $sItem5 = explode('|',$IsiTeksList); 
                // $sItem512 = trim($sItem5[4]); 
                // $sItem51 = substr($sItem512,0,1); 
                // switch (substr($sItem512,0,1)) {
                //     case 1:
                //         echo "s";
                //         break;
                //     case 2:
                //         echo "d";
                //         break;
                //     case 3:
                //         echo "t";
                //         break;
                //     case 4:
                //         echo "e";
                //         break;
                //     case 5:
                //         echo "l";
                //         break;
                //     case 6:
                //         echo "e";
                //         break;
                //     case 7:
                //         echo "t";
                //         break;
                //     case 8:
                //         echo "d";
                //         break;
                //     case 9:
                //         echo "s";
                //         break;
                //     default:
                //         print_r(substr($sItem512,0,1));
                // }
                // echo '<pre>'; print_r($card['CardList']); echo '</pre>'; die;
                // echo '<pre>'; print_r($sItem); echo '</pre>'; die;
                // echo '<pre>'; print_r(str_replace("b", "13",$sItem5[15])); echo '</pre>'; die;

                $result .="<div style='white-space: pre; vertical-align:top; font-family: ".$fontName ."; font-size: ".$fontSize."pt'>";
                for ($k = 0; $k < count($sItem); $k++)
                {
                    $sRow = str_replace(" "," ",$sItem[$k]);
                    // echo $sRow.'<br>';die;
                    if (strpos(trim($sRow), "|Alignment:right|") !== FALSE)
                    {
                        // echo'msuk Alignment right'.$sRow;die;
                        $sRow = str_replace("|Alignment:right|","",$sRow);
                        $sRow = "<div style='text-align: right'>".$sRow."</div>"; 
                    }

                    if (strpos(trim($sRow), "|HangingIndent:") !== FALSE)
                    {
                        // echo'msuk hanging indent'.$sRow;
                        $HangingRowOnStart = true;
                        $FirtMarked = strpos($sRow,"|HangingIndent:");
                        $LastMarked = strpos($sRow,"|", (int)$FirtMarked + 1);
                        $Option = substr($sRow,(int)$FirtMarked + 1,(int)$LastMarked - (int)$FirtMarked - 1);
                        $HangingCount = (int)explode(":",$Option)[1];
                        $HangingStart = $i;
                        $sRow = str_replace("|HangingIndent:".$HangingCount."|","",$sRow);
                        // echo $sRow;die; 
                        $a = str_replace(" ", "", $sRow);
                        $b = substr($a, 0, 1);

                       switch ($b) {
                           case '1':
                               $j245 = '  s';
                           break;
                           case '2':
                               $j245 = '  d';
                           break;
                           case '3':
                               $j245 = '  t';
                           break;
                           case '4':
                               $j245 = '  e';
                           break;
                           case '5':
                               $j245 = '  l';
                           break;
                           case '6':
                               $j245 = '  e';
                           break;
                           case '7':
                               $j245 = '  t';
                           break;
                           case '8':
                               $j245 = '  d';
                           break;
                           case '9':
                               $j245 = '  s';
                           break;
                           default:
                               $j245 = $b;
                       }

                       $c = substr(trim($sRow),1);
                       // echo $c; die;
                       // echo $j245.$c; die;
                        $sRow = $j245.$c;
                       // echo $sRow; die;
                       // if($b == 0){
                           
                       //     $sRow = $b. ' '.$c;   
                       // }else{
                       //     $sRow = $j245. ' '.$c;
                       // }


                    }

                    if ($HangingRowOnStart)
                    {
                        $HangingRowOn++;
                    }
                    if ($HangingCount > 0)
                    {
                        if (!empty($sRow))
                        {
                            
                            (float)$indentation = 0.75 * 72;
                            if ($HangingRowOn <= 1)
                            {
                                $sRow = "<div style='text-indent: -".$indentation."pt; margin: 0 0 0 ".$indentation."pt;'>".$sRow."</div>";
                            }
                            else
                            {
                                $sRow = "<div style='margin: 0 0 0 ".$indentation."pt;'>".$sRow."</div>";
                            }

                           // echo'msuk hanging count'.$sRow;
                        }
                    }
                    if (!Helpers::endsWith($sRow,"</div>"))
                    {
                        $result .= $sRow."\r\n";
                    }
                    else
                    {
                        $sRow =trim($sRow); 
                        if (!empty($sRow))
                        {
                            $result .= $sRow;
                        }
                        else
                        {
                            $result .= "\r\n\r\n";
                        }
                    }

                        
                }
                $result .="</div>";


                if ($i < $jumlahData - 1 || $k < count($IsiTeksList) - 1)
                {
                    $result .="<br style='page-break-before: always;' />";
                }
            }
           
            //die;


            header("Content-type: application/vnd.ms-word;charset=utf-8");
            header("Content-Disposition: attachment;Filename=KartuKatalog_".date("Ymdhis").".doc");

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
            $exportResult .= "@page WordSection1 {size:13.97cm 7.62cm; margin:0.50cm 1.00cm 1.20cm 1.00cm; mso-header-margin:0.0in; mso-footer-margin: 0.0in; mso-paper-source:0;}";
            $exportResult .= "div.WordSection1 {page:WordSection1;}";
            $exportResult .= "@page WordSection2 {size:841.7pt 595.45pt;mso-page-orientation:landscape;margin:1.25in 1.0in 1.25in 1.0in;mso-header-margin:.5in;mso-footer-margin:.5in;mso-paper-source:0;}";
            $exportResult .= "div.WordSection2 {page:WordSection2;}";
            $exportResult .= "</style>";
            $exportResult .= "</head>";
            $exportResult .= "<body>";
            $exportResult .= "<div class=WordSection1>";
            $exportResult .= $result;
            $exportResult .= "</div>";
            $exportResult .= "</body>";
            $exportResult .= "</html>";

            echo $exportResult;

        }
       
        //die;
        //$today=date("Ymdhis");
        // Saving the document as OOXML file...
        //$phpWord->save('KartuKatalog'.$today.'.docx', 'Word2007',true);
        //$phpWord->save('KartuKatalog'.$today.'.odt', 'ODText',true);

    }

    public static function parseString($catalogId,$template)
    {
        $oTemplate = self::parseCatalogTemplate($template);
        //echo '<pre>'; print_r($oTemplate); echo '</pre>';die;
        //echo '<br>';
        $cardList=[];
        $tagPlaceIndex=[];
        $isNoCard = false;
        $cc=0;
        foreach ($oTemplate as $key => $value) {

            $result='';
            $cc++;

            if($value['Alignment'] == 'right')
            {
                $result .= '|Alignment:right|';
            }
            if ($value['HangingIndent'] >= 0)
            {
                $result .= '|HangingIndent:'.$value['HangingIndent'].'|';
            }
            if(!empty($value['NumberingFirstValue']))
            {
                $numberFirstValue =  $value['NumberingFirstValue'].$value['RuasRepeatableDelimiter'];
                $result .= $numberFirstValue;
            }

            $counterTag=0;
            $tagCount=0;
            $tagList = explode('|',$value['Tag']);
            $tagCount = count($tagList);
            $counterNumbering=0;
            $prevTagCodeStart='';

            //echo $tagCount.'<br>';
            //echo $value['Tag'].'<br>';
            foreach ($tagList as $key => $tagCode) {
                $counterTag++;
                $tagcode = trim($tagCode);
                if(empty($tagcode))
                {
                    continue;
                }
                if($prevTagCodeStart != substr($tagCode,0,1))
                {
                    $prevTagCodeStart=substr($tagCode,0,1);
                    $counterNumbering=0;
                }
                $countersubfield=0;
                $variableCount=0;
                $prevTag='';
                //$value['Tag'] = $tagCode;
                $variableCount =  CatalogRuas::find()->where(['Tag'=>$tagCode,'CatalogId'=> $catalogId])->count();
                if($variableCount == 0)
                {
                    if($value['IsDontShowCardIfTagNotExist'] > 0)
                    {
                        $isNoCard=true;
                        break;
                    }
                }

                $modelruas =  CatalogRuas::find()->where(['Tag'=>trim($tagCode),'CatalogId'=> $catalogId])->all();
                foreach ($modelruas as $valueruas) {

                    $countersubfield++;
                    $counterNumbering++;

                    
                    

                    if(!empty($value['NumberingFirstValue']))
                    {
                        //echo 'NumberingFirstValue';
                        if($counterNumbering == 1)
                        {
                            $counterNumbering++;
                        }
                    }

                    if ($countersubfield == 1 && $counterNumbering > 1)
                    {
                        //echo 'RuasRepeatableDelimiter';
                        $result .= $value['RuasRepeatableDelimiter'];
                    }

                    if ($countersubfield > $value['RepeatableMaxCount']) {
                        // echo 'countersubfield='.$countersubfield.' > RepeatableMaxCount('.$valueruas->Tag.')='. $value['RepeatableMaxCount'].'<br>';  
                        continue;  
                    }
                    if($valueruas)
                    {
                        
                        $sVal = '';
                        $test='';
                        $icountersubsubfield=0;
                        $modelsubruas =  CatalogSubruas::find()->where(['RuasID'=>$valueruas->ID])->all();
                        $modelsubruasCount =  CatalogSubruas::find()->where(['RuasID'=>$valueruas->ID])->count();


                        foreach ($modelsubruas as $valuesubruas) {
                            if($valueruas->Tag == '260')
                            {
                                if($valuesubruas->SubRuas == 'a')
                                {
                                    $valsubruas = Helpers::collapseSpaces($valuesubruas->Value);
                                    if(!empty($valsubruas))
                                    {
                                        $delimiter='';
                                        if(!Helpers::endsWith(Helpers::collapseSpaces($valuesubruas->Value),':'))
                                        {
                                            $delimiter=' : ';
                                        }
                                        $valuesubruas->Value=Helpers::collapseSpaces($valuesubruas->Value).$delimiter;
                                    }
                                }
                                if($valuesubruas->SubRuas == 'b')
                                {
                                    $valsubruas = Helpers::collapseSpaces($valuesubruas->Value);
                                    if(!empty($valsubruas))
                                    {
                                        $delimiter='';
                                        if(!Helpers::endsWith(Helpers::collapseSpaces($valuesubruas->Value),','))
                                        {
                                            $delimiter=' , ';
                                        }
                                        $valuesubruas->Value=Helpers::collapseSpaces($valuesubruas->Value).$delimiter;
                                    }
                                }
                                if($valuesubruas->SubRuas == 'c')
                                {
                                    $valsubruas = Helpers::collapseSpaces($valuesubruas->Value);
                                    if(!empty($valsubruas))
                                    {
                                        $valuesubruas->Value=Helpers::collapseSpaces($valuesubruas->Value);
                                    }
                                }
                            }
                            $icountersubsubfield++;
                            if(!empty($value['SubRuas']))
                            {

                                if(strpos(trim($value['SubRuas']),$valuesubruas->SubRuas) !== FALSE)
                                {
                                    $sVal .= str_replace('#',' ',Helpers::collapseSpaces($valuesubruas->Value));
                                }

                                if($value['WordTruncateCount'] != 0)
                                {
                                    
                                    $s = explode(' ',$sVal);
                                    if(count($s) > (int)$value['WordTruncateCount'])
                                    {
                                        $s1='';
                                        for ($i=0; $i < (int)$value['WordTruncateCount']; $i++) { 
                                            $s1 .= $s[$i].' ';
                                        }
                                        $sVal = Helpers::collapseSpaces($s1);
                                    }
                                }

                                $PrevTag = $value['Tag'];
                            }else{
                                $sVal .= str_replace('#',' ',Helpers::collapseSpaces($valuesubruas->Value));
                                $sVal = Helpers::collapseSpaces($sVal);

                                if($value['WordTruncateCount'] != 0)
                                {
                                    $s = explode(' ',$sVal);
                                    if(count($s) > (int)$value['WordTruncateCount'])
                                    {
                                        $s1='';
                                        for ($i=0; $i < (int)$value['WordTruncateCount']; $i++) { 
                                            $s1 .= $s[$i].' ';
                                        }
                                        $sVal = Helpers::collapseSpaces($s1);
                                    }
                                }
                            }

                            $subruasdelim = Helpers::collapseSpaces($value['SubRuasDelimiter']);
                            if(!empty($subruasdelim))
                            {
                                if($icountersubsubfield < $modelsubruasCount)
                                {
                                    if(!strpos($sVal,$value['SubRuasDelimiter']) > 0)
                                    {
                                        $sVal .= $value['SubRuasDelimiter'];
                                    }
                                }
                            }
                        }
                        
                        if(strlen($sVal) > (int)$value['Length'])
                        {
                            if ((int)$value['StartPosition'] >= 0)
                            {
                                if ((int)$value['Length'] > 0)
                                {

                                    $pos = -1;
                                    if(preg_match("/[a-zA-Z0-9]/", $str, $matches, PREG_OFFSET_CAPTURE)) {
                                        $pos = $matches[0][1];
                                    }
                                    
                                    if ($pos >= 0)
                                    {
                                        $sVal = substr($sVal,$pos,(int)$value['Length']);
                                    }
                                    else
                                    {
                                        $sVal = substr($sVal,(int)$value['StartPosition'],(int)$value['Length']);
                                    }
                                }
                            }
                        }


                        if (!empty($value['FontMode']))
                        {

                            if (strtolower(Helpers::collapseSpaces($value['FontMode'])) == "uppercase")
                            {

                                $sVal = strtoupper($sVal);
                            }
                            else if (strtolower(Helpers::collapseSpaces($value['FontMode'])) == "lowercase")
                            {
                                $sVal = strtolower($sVal);
                            }
                        }
                        if ((int)$value['SpaceReplacer'] > 0)
                        {
                            $sVal = str_replace(' ','#',$sVal);
                            if ((int)$value['SpaceReplacer'] == 13)
                            {
                                $sVal = str_replace('#','\r\n',$sVal);
                            }
                            else
                            {
                                $sVal = str_replace('#',$value['SpaceReplacer'],$sVal);
                            }
                        }
                        if ((int)$value['IsSplitCard'] == 0)
                        {
                            if (!empty($value['NumberingMode']))
                            {
                                if (Helpers::collapseSpaces($value['NumberingMode']) == "ordernumeric")
                                {
                                    $sVal = $counterNumbering.'. '.$sVal;
                                }
                                else if (Helpers::collapseSpaces($value['NumberingMode']) == "orderromawi")
                                {
                                    $sVal = Helpers::romanNumerals($counterNumbering).'. '.$sVal;
                                }
                            }
                            $result .= $sVal;
                        }

                        if ((int)$value['IsSplitCard'] == 1)
                        {
                            $cardList[] = $sVal;
                            $tagPlaceIndex[] = strpos($template,$value['OriginalString']);
                        }

                        if (!empty($value['RuasRepeatableDelimiter']))
                        {
                            if ($countersubfield < $variableCount)
                            {
                                $result .= $value['RuasRepeatableDelimiter'];
                            }
                        }
                    }
                }

                if ($counterTag < $tagCount)
                {
                    $result .= $value['MultiTagDelimiter'];
                }
            }
            $sAll = $result;
            //echo  $sAll.'<br>';
            
            /*if($cc == 20)
            {
                echo  $result; die;
            }*/
            if(!empty($sAll))
            {
                $sAll = $value['RuasDelimiter'].$sAll;
                if (!empty($value['EndChar']))
                {
                    if (!Helpers::endsWith($sAll,$value['EndChar']))
                    {
                        $sAll = $sAll.$value['EndChar'];
                    }
                }
            }
            $template = str_replace($value['OriginalString'],$sAll,$template);
        }
        $template = str_replace("--  --","--",$template);
        $template = str_replace(",  ,",",",$template);

        $start=0;
        while(($pos = strpos($template,"\r\n  \r\n",$start)) !== FALSE){
            $template =  str_replace("\r\n  \r\n","\r\n",$template);
            $start = $pos +1;
        }

        $start=0;
        while(($pos = strpos($template,"\r\n  \r\n",$start)) !== FALSE){
            $template =  str_replace("\r\n  \r\n","\r\n",$template); 
            $start = $pos +1;
        }

        $start=0;
        while(($pos = strpos($template,"\r\n\r\n\r\n",$start)) !== FALSE){
            $template =  str_replace("\r\n\r\n\r\n","\r\n\r\n",$template); 
            $start = $pos +1;
        }

        $start=0;
        while(($pos = strpos($template,"\r\n",$start)) !== FALSE){
            $template =  str_replace("\r\n\r\n\r\n","\r\n\r\n",$template); 
            $start = $pos +1;
        }

        $start=0;
        while(($pos = strpos($template,"\r\n\r\n\r\n",$start)) !== FALSE){
            $template = str_replace("\r\n\r\n\r\n", "\r\n\r\n",$template);
            $start = $pos +1;
        }

        $start=0;
        while(($pos = Helpers::endsWith($template,"\r\n")) !== FALSE){
            $template = substr($template,0,strlen($template) - 2);
            $start = $pos +1;
        }
        
        if (count($cardList) == 0)
        {
            if (!$isNoCard)
            {
                $cardList[] = $template;
            }
        }
        else
        {
            $result='';
            for ($n = 0; $n < count($cardList); $n++)
            {
                $Newtemplate = $template;
                $Newtemplate = substr_replace($Newtemplate,(string)$cardList[$n], (int)$tagPlaceIndex[$n], 0);
                $cardList[$n] = $Newtemplate;
                $result .= $cardList[$n];
            }
            $template = $result;
        }

        $cardResult;
        $cardResult['Text'] = $template;
        $cardResult['CardList'] = $cardList;

        //echo '<pre>'; print_r($cardList); echo '</pre>';die;
        //die;
        return $cardResult;;
    }

    public static function parseCatalogTemplate($template)
    {
        
        $oTemplate = [];
        $starTag='<'; $endTag='>';
        (bool)$findStartTag =  false;
        (bool)$findEndTag =  false;
        for ($i=0; $i < strlen($template); $i++) { 
            if($findEndTag == false)
            {
                if($template[$i] == $starTag)
                {
                    $findStartTag = true;
                    $processingTag = '';
                }

                if($findStartTag == true)
                {
                    $processingTag .= $template[$i];
                    if($template[$i] == $endTag)
                    {
                        $findEndTag = true;
                    }
                }
            }
            if($findEndTag == true)
            {

                $dataTemplate = self::getCatalogCardTag($processingTag);
                $oTemplate[] =  [
                'OriginalString'=>$dataTemplate['OriginalString'],
                'Tag'=>$dataTemplate['Tag'],
                'SubRuas'=>$dataTemplate['SubRuas'],
                'RepeatableMaxCount'=>$dataTemplate['RepeatableMaxCount'],
                'MultiTagDelimiter'=>$dataTemplate['MultiTagDelimiter'],
                'StartPosition'=>$dataTemplate['StartPosition'],
                'Length'=>$dataTemplate['Length'],
                'FontMode'=>$dataTemplate['FontMode'],
                'SpaceReplacer'=>$dataTemplate['SpaceReplacer'],
                'NumberingMode'=>$dataTemplate['NumberingMode'],
                'NumberingFirstValue'=>$dataTemplate['NumberingFirstValue'],
                'RuasDelimiter'=>$dataTemplate['RuasDelimiter'],
                'RuasRepeatableDelimiter'=>$dataTemplate['RuasRepeatableDelimiter'],
                'IsSplitCard'=>$dataTemplate['IsSplitCard'],
                'WordTruncateCount'=>$dataTemplate['WordTruncateCount'],
                'Alignment'=>$dataTemplate['Alignment'],
                'HangingIndent'=>$dataTemplate['HangingIndent'],
                'SubRuasDelimiter'=>$dataTemplate['SubRuasDelimiter'],
                'EndChar'=>$dataTemplate['EndChar'],
                'IsDontShowCardIfTagNotExist'=>$dataTemplate['IsDontShowCardIfTagNotExist']
                ];
                  

                
                $processingTag='';
                $findStartTag =  false;
                $findEndTag =  false;
            }
        }
        return $oTemplate;

        //echo '<pre>'; print_r($oTemplate); echo '</pre>';die;
    }

    public static function getCatalogCardTag($processingTag)
    {
        try {
            $result_template = [];
            $result_template['OriginalString'] = $processingTag; //Teks Format
            $result_template['Tag'] = ""; //Tag/Ruas Code
            $result_template['SubRuas'] =""; //Field (- : semua isi sub ruas)
            $result_template['RepeatableMaxCount'] = 1; //Max Banyak data tag repeatable yang dikeluarkan (<1 : semuanya)
            $result_template['MultiTagDelimiter'] = "";
            $result_template['StartPosition'] = -1; //Format Kiri : Karakter dimulai dari
            $result_template['Length'] = -1; //Format Kiri : Karakter sampai dengan
            $result_template['FontMode'] = ""; //Uppercase/Lowecase
            $result_template['SpaceReplacer'] = -1; //Replace space. Misalkan Tag 008 : 20130507#  a3 dad ## ==> jadi 20130507###a3#dad###
            $result_template['NumberingMode'] = ""; //Order Numeric/Romawi
            $result_template['NumberingFirstValue'] = ""; //Nilai Pertama
            $result_template['RuasDelimiter'] = ""; //Karakter Pemisah antara Ruas
            $result_template['RuasRepeatableDelimiter'] = ""; //Karakter Pemisah antara Isi Ruas Repeatable
            $result_template['IsSplitCard'] = 0; //Apakah Kartunya dipisah untuk isi Ruas Repeatable
            $result_template['WordTruncateCount'] = 0; //Jumlah Pemotongan kata
            $result_template['Alignment'] = "left"; //Alignment
            $result_template['HangingIndent'] = -1; //HangingIndent
            $result_template['SubRuasDelimiter'] = ""; //Karakter Pemisah antara sub ruas
            $result_template['IsDontShowCardIfTagNotExist'] = 0; //Jika data isi tag tidak ada, maka kartu tidak ditampilkan
            $result_template['EndChar'] = ""; //Jika data isi tag tidak ada, maka kartu tidak ditampilkan

            $processingTag =  str_replace('<', '',str_replace('>','', $processingTag));
            $counter=0;
            $results = explode(',',$processingTag);
            foreach($results as $result) {    
                $counter++;
                $cleanResult =  Helpers::collapseSpaces($result);
                if($counter == 1)
                {
                    $result_template['Tag'] = $cleanResult;
                }else if($counter == 2) {
                    $result_template['SubRuas'] = $cleanResult;
                    if($cleanResult == '-')
                    {
                        $result_template['SubRuas']="";
                    }
                }else if($counter == 3) {
                    if($cleanResult != '')
                    {
                        $result_template['RepeatableMaxCount']=(int)$cleanResult;
                    }

                    if ((int)$result_template['RepeatableMaxCount'] < 1)
                    {
                        $result_template['RepeatableMaxCount']=PHP_INT_MAX;
                    }
                }else{
                    if($cleanResult != '' && strpos($cleanResult,':') !== FALSE)
                    {
                        $optionValue = explode(':',$cleanResult);
                        $option =  trim(Helpers::collapseSpaces($optionValue[0]));
                        $value =  Helpers::collapseSpaces($optionValue[1]);
                        if($option == "MultiTagDelimiter"){
                            $result_template['MultiTagDelimiter'] = str_replace("br", PHP_EOL,$value);
                        }
                        else if($option == "StartPosition"){
                            $result_template['StartPosition'] = (int)$value;
                        }
                        else if($option == "Length"){
                            $result_template['Length'] = (int)$value;
                        }
                        else if($option == "FontMode"){
                            $result_template['FontMode'] = $value;
                        }
                        else if($option == "SpaceReplacer"){
                            $result_template['SpaceReplacer'] = (int)$value;
                        }
                        else if($option == "RuasDelimiter"){
                            $result_template['RuasDelimiter'] = str_replace("br", PHP_EOL,$value);
                        }
                        else if($option == "NumberingMode"){
                            $result_template['NumberingMode'] = $value;
                        }
                        else if($option == "NumberingFirstValue"){
                            $result_template['NumberingFirstValue'] = $value;
                        }
                        else if($option == "RuasRepeatableDelimiter"){
                            $result_template['RuasRepeatableDelimiter'] = str_replace("br", PHP_EOL,$value);
                        }
                        else if($option == "IsSplitCard"){
                            $result_template['IsSplitCard'] = (int)$value;
                        }
                        else if($option == "WordTruncateCount"){
                            $result_template['WordTruncateCount'] = (int)$value;
                        }
                        else if($option == "Alignment"){
                            $result_template['Alignment'] = $value;
                        }
                        else if($option == "HangingIndent"){
                            $result_template['HangingIndent'] = (int)$value;
                        }
                        else if($option == "SubRuasDelimiter"){
                            $result_template['SubRuasDelimiter'] = $value;
                        }
                        else if($option == "EndChar"){
                            $result_template['EndChar'] = $value;
                        }
                        else if($option == "IsDontShowCardIfTagNotExist"){
                            $result_template['IsDontShowCardIfTagNotExist'] = (int)$value;
                        }
                    }
                }
            }
            return $result_template;


        }catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }   

    public static function isTagExist($val,$array) {
        foreach ($array as $item)
            if ($item['tag'] == $val)
                return true;
        return false;
    }
    
    public static function convertToCatalogDetails($taglist)
    {
        $modeldetilkatalog = \common\models\Settingcatalogdetail::find()->all();
        $result = array();
        $delimiter = '<br/>';
        $regexReplaceDollar = '/(\$\w)(.*?)(\$?)/';
        $counterAuthor=0;
        $counterNote=0;
        // echo '<pre>'; print_r($taglist); echo '</pre>';die;
        foreach ($modeldetilkatalog as $key => $datadetail) {
             if(self::isTagExist($datadetail->field->Tag, $taglist))
             {
                $result[$key]['Label'] =   $datadetail->field->Name;

                foreach ($taglist as $index => $tags) {

                    if($tags['tag'] == $datadetail->field->Tag)
                    {
                        //Khusus untuk tag 100,7XX handle $e
                        if($tags['tag'] == '100' || $tags['tag'] == '700' || $tags['tag'] == '710' || $tags['tag'] == '711')
                        {
                            $tval =  explode("$", $tags['value']);
                            $valueE='';
                            foreach ($tval as $tkey => $tvalue) {
                                $tvalue = trim($tvalue);
                                if(substr($tvalue,0,1) == "a")
                                {
                                    $valueA = trim(substr($tvalue,1));
                                }
                                if(substr($tvalue,0,1) == "e")
                                {
                                    $valueE = trim(substr($tvalue,1));
                                }
                            }
                            if($valueE != '')
                            {
                                $valueE = " (".$valueE.")";
                            } 

                            if($result[$key]['Value'] == NULL)
                            {
                                $result[$key]['Value'] =   $valueA.$valueE;
                            }else{
                                $result[$key]['Value'] .=   $delimiter.$valueA.$valueE;
                            }

                        }else{
                            if($result[$key]['Value'] == NULL)
                            {
                                $result[$key]['Value'] =   trim(preg_replace($regexReplaceDollar, '', $tags['value']));
                            }else{
                                $result[$key]['Value'] .=   $delimiter. trim(preg_replace($regexReplaceDollar, '', $tags['value']));
                            }
                        }
                            

                        
                    }
                }
             }
        }

        return $result;
    }

    public static function convertToCatalogFields($taglist)
    {
        $result = array();
        $tagvalues =  $taglist['inputvalue'];
        $delimiter = ' ; ';
        $regexReplaceDollar = '/(\$\w)(.*?)(\$?)/';
        $counterAuthor=0;
        $counterNote=0;
        foreach ($tagvalues as $tagcode => $tagvalue) {
            switch ($tagcode) {
                case '245':
                    $titlemix =  explode("$",(is_array($tagvalue)) ? $tagvalue[0] : $tagvalue);
                    $titlefinal = '';
                    for ($i=0; $i < count($titlemix) ; $i++) { 
                        $subruascode=substr($titlemix[$i],0,1);
                        $subruasvalue=substr($titlemix[$i],1,strlen($titlemix[$i]));
                        if(trim($subruasvalue) != '')
                        {
                            /*switch ($subruascode) {
                                 case 'a':
                                    $titlefinal .= $subruasvalue;
                                    break;
                                 case 'b':
                                    $titlefinal .= ' : '.$subruasvalue;
                                    break;
                                 case 'c':
                                    $titlefinal .= ' / '.$subruasvalue;
                                    break;
                            }*/

                            $titlefinal .= $subruasvalue;
                        }
                    }
                    $result['Title']=$titlefinal;
                    break;
                case '100':
                case '700':
                case '710':
                case '711':
                    $counterAuthor++;
                    if(is_array($tagvalue))
                    {
                        foreach ($tagvalue as $key => $value) {
                            $tval =  explode("$", $value);
                            $valueE='';
                            foreach ($tval as $tkey => $tvalue) {
                                $tvalue = trim($tvalue);
                                if(substr($tvalue,0,1) == "a")
                                {
                                    $valueA = trim(substr($tvalue,1));
                                }
                                if(substr($tvalue,0,1) == "e")
                                {
                                    $valueE = trim(substr($tvalue,1));
                                }
                            }
                            if($valueE != '')
                            {
                                $valueE = " (".$valueE.")";
                            }

                            if($valueA != '')
                            {
                                if($key==0)
                                {
                                    if($counterAuthor==1)
                                    {
                                        $result['Author'] = $valueA.$valueE;
                                    }else{
                                        $result['Author'] .= $delimiter.$valueA.$valueE;
                                    }
                                }else{
                                    $result['Author'] .= $delimiter.$valueA.$valueE;
                                }
                            }
                        }
                    }else{
                        $tval =  explode("$", $tagvalue);
                        $valueE='';
                        foreach ($tval as $tkey => $tvalue) {
                            $tvalue = trim($tvalue);
                            if(substr($tvalue,0,1) == "a")
                            {
                                $valueA = trim(substr($tvalue,1));
                            }
                            if(substr($tvalue,0,1) == "e")
                            {
                                $valueE = trim(substr($tvalue,1));
                            }
                        }
                        if($valueE != '')
                        {
                            $valueE = " (".$valueE.")";
                        }

                        if($valueA != '')
                        {
                            if($counterAuthor==1)
                            {
                                $result['Author'] =  $valueA.$valueE;
                            }else{
                                $result['Author'] .= $delimiter.$valueA.$valueE;
                            }
                        }
                    }
                    break;
                case '250':
                    if(is_array($tagvalue))
                    {
                        foreach ($tagvalue as $key => $value) {
                            if(trim(preg_replace($regexReplaceDollar, '', $value)) != '')
                            {
                                if($key==0)
                                {
                                    $result['Edition'] = trim(preg_replace($regexReplaceDollar, '', $value));
                                }else{
                                    $result['Edition'] .= $delimiter.trim(preg_replace($regexReplaceDollar, '', $value));
                                }
                            }
                        }
                    }else{
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            $result['Edition'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                        }
                    }
                    break;
                case '260':
                case '264':
                    if($tagcode=='264')
                    {
                        $result['IsRDA'] = 1;
                    }else{
                        $result['IsRDA'] = 0;
                    }

                    if(is_array($tagvalue))
                    {
                        $publication=[];
                        $publishlocation=[];
                        $publisher=[];
                        $publishyear=[];
                        foreach ($tagvalue as $key => $value) {
                            $publishmix =  explode("$",$value);
                            $cleanPublication='';
                            for ($i=0; $i < count($publishmix) ; $i++) { 
                                $subruascode=substr($publishmix[$i],0,1);
                                $subruasvalue=substr($publishmix[$i],1,strlen($publishmix[$i]));
                                if(trim($subruasvalue) != '')
                                {
                                    $cleanPublication .= $subruasvalue;
                                    switch ($subruascode) {
                                         case 'a':
                                            $publishlocation[] = $subruasvalue;
                                            break;
                                         case 'b':
                                            $publisher[] = $subruasvalue;
                                            break;
                                         case 'c':
                                            $publishyear[] = $subruasvalue;
                                            break;
                                    }
                                }
                            }
                            if($cleanPublication!='')
                            {
                                $publication[] = $cleanPublication;
                            }
                        }
                        $result['Publikasi']=implode(";",$publication);
                        $result['PublishLocation']=implode(";",$publishlocation);
                        $result['Publisher']=implode(";",$publisher);
                        $result['PublishYear']=implode(";",$publishyear);
                    }else{
                        $publishmix =  explode("$",$tagvalue);
                        $cleanPublication='';
                        for ($i=0; $i < count($publishmix) ; $i++) { 
                            $subruascode=substr($publishmix[$i],0,1);
                            $subruasvalue=substr($publishmix[$i],1,strlen($publishmix[$i]));
                            if(trim($subruasvalue) != '')
                            {
                                $cleanPublication .= $subruasvalue;
                                switch ($subruascode) {
                                     case 'a':
                                        $result['PublishLocation'] = $subruasvalue;
                                        break;
                                     case 'b':
                                        $result['Publisher'] = $subruasvalue;
                                        break;
                                     case 'c':
                                        $result['PublishYear'] = $subruasvalue;
                                        break;
                                }
                            }
                        }
                        if($cleanPublication!='')
                        {
                            $result['Publikasi'] = $cleanPublication;
                        }
                    }
                    
                    break;
                case '650':
                case '600':
                case '651':
                    if(is_array($tagvalue))
                    {
                        foreach ($tagvalue as $key => $value) {
                            if(trim(preg_replace($regexReplaceDollar, '', $value)) != '')
                            {
                                if($key==0)
                                {
                                    $result['Subject'] = trim(preg_replace($regexReplaceDollar, '', $value));
                                }else{
                                    $result['Subject'] .= ' -- '.trim(preg_replace($regexReplaceDollar, '', $value));
                                }
                            }
                        }
                    }else{
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            $result['Subject'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                        }
                    }
                    
                    break;
                case '300':
                $tagvalueID = [$tagvalue];
                if (count($tagvalueID, COUNT_RECURSIVE) == 1) {
                    $tagvalue = [$tagvalue];
                }
                foreach ($tagvalue as $tagvalue) {
                    $tagvalue300 .= $tagvalue;
                }
                // print_r(count($tagvalueID, COUNT_RECURSIVE));echo '<br/>';
                // print_r($tagvalue);die;
                    if(trim(preg_replace($regexReplaceDollar, '', $tagvalue300)) != '')
                    {
                        $physicaldescmix =  explode('$',$tagvalue300);
                        $physicaldescmixfinal = '';
                        for ($i=0; $i < count($physicaldescmix) ; $i++) { 
                            $subruascode=substr($physicaldescmix[$i],0,1);
                            $subruasvalue=substr($physicaldescmix[$i],1,strlen($physicaldescmix[$i]));
                            if(trim($subruasvalue) != '')
                            {
                                /*switch ($subruascode) {
                                     case 'a':
                                        $physicaldescmixfinal .= $subruasvalue;
                                        break;
                                     case 'b':
                                        $physicaldescmixfinal .= ' : '.$subruasvalue;
                                        break;
                                     case 'c':
                                        $physicaldescmixfinal .= ' ; '.$subruasvalue;
                                        break;
                                }*/

                                $physicaldescmixfinal .= $subruasvalue;
                            }
                        }
                        // echo'<pre>';print_r($tagvalue);die;
                        $result['PhysicalDescription']=$physicaldescmixfinal;
                    }
                    break;
                case '020':
                case '022':
                case '024':
                    if(is_array($tagvalue))
                    {
                        foreach ($tagvalue as $key => $value) {
                            if(trim(preg_replace($regexReplaceDollar, '', $value)) != '')
                            {
                                if($key==0)
                                {
                                    $result['ISBN'] = trim(preg_replace($regexReplaceDollar, '', $value));
                                }else{
                                    $result['ISBN'] .= $delimiter.trim(preg_replace($regexReplaceDollar, '', $value));
                                }
                            }
                        }
                    }else{
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            $result['ISBN'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                        }
                    }
                    break;
                case '084':
                    if(is_array($tagvalue))
                    {
                        foreach ($tagvalue as $key => $value) {
                            if(trim(preg_replace($regexReplaceDollar, '', $value)) != '')
                            {
                                if($key==0)
                                {
                                    $result['CallNumber'] = trim(preg_replace($regexReplaceDollar, '', $value));
                                }else{
                                    $result['CallNumber'] .= $delimiter.trim(preg_replace($regexReplaceDollar, '', $value));
                                }
                            }
                        }
                    }else{
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            $result['CallNumber'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                        }
                    }
                    break;
                case '500':
                case '502':
                case '504':
                case '505':
                case '520':
                case '542':
                    $counterNote++;
                    if(is_array($tagvalue))
                    {
                        foreach ($tagvalue as $key => $value) {
                            if($key==0)
                            {
                                if(trim(preg_replace($regexReplaceDollar, '', $value)) != '')
                                {
                                    if($counterNote==1)
                                    {
                                        $result['Note'] = trim(preg_replace($regexReplaceDollar, '', $value));
                                    }else{
                                        $result['Note'] .= $delimiter.trim(preg_replace($regexReplaceDollar, '', $value));
                                    }
                                }
                            }else{
                                $result['Note'] .= $delimiter.trim(preg_replace($regexReplaceDollar, '', $value));
                            }
                        }
                    }else{
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            if($counterNote==1)
                            {
                                $result['Note'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }else{
                                $result['Note'] .= $delimiter. trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }
                        }
                    }
                    break;
                case '008':
                    if(strlen($tagvalue) >= 40)
                    {
                        $result['Languages']=substr($tagvalue,35,3);
                    }
                    break;
                case '082':
                    if(is_array($tagvalue))
                    {
                        foreach ($tagvalue as $key => $value) {
                            if(trim(preg_replace($regexReplaceDollar, '', $value)) != '')
                            {
                                if($key==0)
                                {
                                    $result['DeweyNo'] = trim(preg_replace($regexReplaceDollar, '', $value));
                                }else{
                                    $result['DeweyNo'] .= $delimiter.trim(preg_replace($regexReplaceDollar, '', $value));
                                }
                            }
                        }
                    }else{
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            $result['DeweyNo'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                        }
                    }
                    break;

            }
        }

        return $result;
    }

    public static function convertToCatalogFields2($taglist)
    {
        $result = array();
        $tagvalues =  $taglist;
        $delimiter = ' ; ';
        $regexReplaceDollar = '/(\$\w)(.*?)(\$?)/';
        $counterAuthor=0;
        $counterNote=0;
        //echo '<pre>'; print_r($taglist); echo '</pre>';die;
        if (is_array($taglist))
        {
            foreach ($taglist as $index => $tags) {
                $tagcode=$tags['tag'];
                $tagvalue=$tags['value'];
                switch ($tagcode) 
                {
                    case '001':

                        if($result['ControlNumber']==NULL)
                        {
                            $result['ControlNumber'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                        }else{
                            $result['ControlNumber'] .= $delimiter. trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                        }
                        break;
                    case '245':

                        if($result['Title']==NULL)
                        {
                            $result['Title'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                        }else{
                            $result['Title'] .= $delimiter. trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                        }
                        break;
                    case '100':
                    case '700':
                    case '710':
                    case '711':
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            if($result['Author']==NULL)
                            {
                                $result['Author'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }else{
                                $result['Author'] .= $delimiter. trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }
                        }
                        break;
                    case '250':
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            if($result['Edition']==NULL)
                            {
                                $result['Edition'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }else{
                                $result['Edition'] =  $delimiter.trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }
                        }
                        break;
                    case '260':
                    case '264':
                        if($tagcode=='264')
                        {
                            $result['IsRDA'] = 1;
                        }else{
                            $result['IsRDA'] = 0;
                        }

                        if(is_array($tagvalue))
                        {
                            $publication=[];
                            $publishlocation=[];
                            $publisher=[];
                            $publishyear=[];
                            foreach ($tagvalue as $key => $value) {
                                $publishmix =  explode("$",$value);
                                $cleanPublication='';
                                for ($i=0; $i < count($publishmix) ; $i++) { 
                                    $subruascode=substr($publishmix[$i],0,1);
                                    $subruasvalue=substr($publishmix[$i],1,strlen($publishmix[$i]));
                                    if(trim($subruasvalue) != '')
                                    {
                                        $tandaBaca = '';
                                        $tb = (string)Fields::getTandaBaca($tagcode,$subruascode);
                                        if(!empty($tb))
                                        {
                                            if(strpos($subruasvalue,(string)Fields::getTandaBaca($tagcode,$subruascode)) === FALSE)
                                            {
                                                $tandaBaca = (string)Fields::getTandaBaca($tagcode,$subruascode);
                                            }
                                        }
                                        $cleanPublication .= $subruasvalue.$tandaBaca;
                                        switch ($subruascode) {
                                             case 'a':
                                                $publishlocation[] = $subruasvalue.$tandaBaca;
                                                break;
                                             case 'b':
                                                $publisher[] = $subruasvalue.$tandaBaca;
                                                break;
                                             case 'c':
                                                $publishyear[] = $subruasvalue.$tandaBaca;
                                                break;
                                        }
                                    }
                                }
                                if($cleanPublication!='')
                                {
                                    $publication[] = $cleanPublication;
                                }
                            }
                            $result['Publikasi']=implode(";",$publication);
                            $result['PublishLocation']=implode(";",$publishlocation);
                            $result['Publisher']=implode(";",$publisher);
                            $result['PublishYear']=implode(";",$publishyear);
                        }else{
                            $publishmix =  explode("$",$tagvalue);
                            $cleanPublication='';
                            for ($i=0; $i < count($publishmix) ; $i++) { 
                                $subruascode=substr($publishmix[$i],0,1);
                                $subruasvalue=substr($publishmix[$i],1,strlen($publishmix[$i]));
                                if(trim($subruasvalue) != '')
                                {
                                    $tandaBaca = '';
                                    $tb = (string)Fields::getTandaBaca($tagcode,$subruascode);
                                    if(!empty($tb))
                                    {
                                        if(strpos($subruasvalue,(string)Fields::getTandaBaca($tagcode,$subruascode)) === FALSE)
                                        {
                                            $tandaBaca = (string)Fields::getTandaBaca($tagcode,$subruascode);
                                        }
                                    }

                                    $cleanPublication .= $subruasvalue.$tandaBaca;
                                    switch ($subruascode) {
                                         case 'a':
                                            $result['PublishLocation'] = $subruasvalue.$tandaBaca;
                                            break;
                                         case 'b':
                                            $result['Publisher'] = $subruasvalue.$tandaBaca;
                                            break;
                                         case 'c':
                                            $result['PublishYear'] = $subruasvalue.$tandaBaca;
                                            break;
                                    }
                                }
                            }
                            if($cleanPublication!='')
                            {
                                $result['Publikasi'] = $cleanPublication;
                            }
                        }
                        break;
                    case '650':
                    case '600':
                    case '651':
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            if($result['Subject']==NULL)
                            {
                                $result['Subject'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }else{
                                $result['Subject'] .=  ' -- '. trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }
                        }
                        break;
                    case '300':
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            $physicaldescmix =  explode('$',$tagvalue);
                            $physicaldescmixfinal = '';
                            for ($i=0; $i < count($physicaldescmix) ; $i++) { 
                                $subruascode=substr($physicaldescmix[$i],0,1);
                                $subruasvalue=substr($physicaldescmix[$i],1,strlen($physicaldescmix[$i]));
                                if(trim($subruasvalue) != '')
                                {
                                    /*switch ($subruascode) {
                                         case 'a':
                                            $physicaldescmixfinal .= $subruasvalue;
                                            break;
                                         case 'b':
                                            $physicaldescmixfinal .= ' : '.$subruasvalue;
                                            break;
                                         case 'c':
                                            $physicaldescmixfinal .= ' ; '.$subruasvalue;
                                            break;
                                    }*/

                                    $physicaldescmixfinal .= $subruasvalue;
                                }
                            }
                            $result['PhysicalDescription']=$physicaldescmixfinal;
                        }
                        break;
                    case '020':
                    case '022':
                    case '024':
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            if($result['ISBN']==NULL)
                            {
                                $result['ISBN'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }else{
                                $result['ISBN'] .=  $delimiter. trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }
                            
                        }
                        break;
                    case '084':
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            if($result['CallNumber']==NULL)
                            {
                                $result['CallNumber'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }else{
                                $result['CallNumber'] .=  $delimiter. trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }
                            
                        }
                        break;
                    case '500':
                    case '502':
                    case '504':
                    case '505':
                    case '520':
                    case '542':
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            if($result['Note']==NULL)
                            {
                                $result['Note'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }else{
                                $result['Note'] .= $delimiter. trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }
                        }
                        break;
                    case '008':
                        if(strlen($tagvalue) >= 40)
                        {
                            if($result['Languages']==NULL)
                            {
                                $result['Languages'] =  substr($tagvalue,35,3);
                            }else{
                                $result['Languages'] .= $delimiter.substr($tagvalue,35,3);
                            }
                            
                        }
                        break;
                    case '082':
                        if(trim(preg_replace($regexReplaceDollar, '', $tagvalue)) != '')
                        {
                            if($result['DeweyNo']==NULL)
                            {
                                $result['DeweyNo'] =  trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }else{
                                $result['DeweyNo'] .=  $delimiter.trim(preg_replace($regexReplaceDollar, '', $tagvalue));
                            }
                            
                        }
                        break;

                }
            }
        }else{

            echo 'not array'; die;
        }

        return $result;
    }

    public static function getCatalogDataFromTaglist($taglist,&$arrayData){
        $catalogFieldValues = array();
        $catalogDetailValues = array();
        foreach ($taglist as $key => $taglistdata) {
            $catalogFieldValues = self::convertToCatalogFields($taglistdata);
            $catalogDetailValues = self::convertToCatalogDetails($taglistdata);
            
            $arrayData[$key]['Detail']=$catalogDetailValues;
            $arrayData[$key]['Taglist']=$taglistdata;
            if(array_key_exists('Title', $catalogFieldValues))
                $arrayData[$key]['Title']=trim($catalogFieldValues['Title']);
            else
                $arrayData[$key]['Title']='';

            if(array_key_exists('Author', $catalogFieldValues))
                $arrayData[$key]['Author']=trim($catalogFieldValues['Author']);
            else
                $arrayData[$key]['Author']='';

            if(array_key_exists('Publisher', $catalogFieldValues))
                $arrayData[$key]['Publisher']=trim($catalogFieldValues['Publisher']);
            else
                $arrayData[$key]['Publisher']='';

            if(array_key_exists('PublishLocation', $catalogFieldValues))
                $arrayData[$key]['PublishLocation']=trim($catalogFieldValues['PublishLocation']);
            else
                $arrayData[$key]['PublishLocation']='';

            if(array_key_exists('PublishYear', $catalogFieldValues))
                $arrayData[$key]['PublishYear']=trim($catalogFieldValues['PublishYear']);
            else
                $arrayData[$key]['PublishYear']='';

            if(array_key_exists('Subject', $catalogFieldValues))
                $arrayData[$key]['Subject']=trim($catalogFieldValues['Subject']);
            else
                $arrayData[$key]['Subject']='';

            if(array_key_exists('IsRDA', $catalogFieldValues))
                $arrayData[$key]['Mode']=trim($catalogFieldValues['IsRDA']);
            else
                $arrayData[$key]['Mode']='';
        }
    }

    public static function getCatalogDataFromTaglist2($taglist,&$arrayData){
        $catalogFieldValues = array();
        $catalogDetailValues = array();
        foreach ($taglist as $key => $taglistdata) {
            $catalogFieldValues = self::convertToCatalogFields2($taglistdata);
            $catalogDetailValues = self::convertToCatalogDetails($taglistdata);
            
            $arrayData[$key]['Detail']=$catalogDetailValues;
            $arrayData[$key]['Taglist']=$taglistdata;
            if(array_key_exists('Title', $catalogFieldValues))
                $arrayData[$key]['Title']=trim($catalogFieldValues['Title']);
            else
                $arrayData[$key]['Title']='';

            if(array_key_exists('Author', $catalogFieldValues))
                $arrayData[$key]['Author']=trim($catalogFieldValues['Author']);
            else
                $arrayData[$key]['Author']='';

            if(array_key_exists('Publisher', $catalogFieldValues))
                $arrayData[$key]['Publisher']=trim($catalogFieldValues['Publisher']);
            else
                $arrayData[$key]['Publisher']='';

            if(array_key_exists('PublishLocation', $catalogFieldValues))
                $arrayData[$key]['PublishLocation']=trim($catalogFieldValues['PublishLocation']);
            else
                $arrayData[$key]['PublishLocation']='';

            if(array_key_exists('PublishYear', $catalogFieldValues))
                $arrayData[$key]['PublishYear']=trim($catalogFieldValues['PublishYear']);
            else
                $arrayData[$key]['PublishYear']='';

            if(array_key_exists('Subject', $catalogFieldValues))
                $arrayData[$key]['Subject']=trim($catalogFieldValues['Subject']);
            else
                $arrayData[$key]['Subject']='';

            if(array_key_exists('IsRDA', $catalogFieldValues))
                $arrayData[$key]['Mode']=trim($catalogFieldValues['IsRDA']);
            else
                $arrayData[$key]['Mode']='';
        }
    }

    public static function getRegexPatternCleanTandaBaca($tag)
    {
        $patterns = array();
        $tandaBacaAll = Fields::getTandaBacaByTag($tag);
        foreach ($tandaBacaAll as $data) {
            $tb = trim($data->TandaBaca);
            if($tb != '')
            {
                $patterns[] = '/(\\'.trim($data->TandaBaca).'\z)/';
                //$patterns[] = '/(\A\\'.trim($data->TandaBaca).')/';
            }
        }

        return $patterns;
    }

    public static function deleteKontenDigital($id)
    {
        $trans = Yii::$app->db->beginTransaction();     
        try {

            $model = Catalogfiles::findOne($id);
            $worksheetDir=\common\components\DirectoryHelpers::GetDirWorksheet($model->catalog->Worksheet_id);

            $path =  Yii::getAlias('@uploaded_files').
            DIRECTORY_SEPARATOR.
            'dokumen_isi'.
            DIRECTORY_SEPARATOR.
            $worksheetDir.
            DIRECTORY_SEPARATOR.
            $model->FileURL;

            if($model->FileFlash)
            {
                $pathFolder = Yii::getAlias('@uploaded_files').
                DIRECTORY_SEPARATOR.
                'dokumen_isi'.
                DIRECTORY_SEPARATOR.
                $worksheetDir.
                DIRECTORY_SEPARATOR.
                str_replace(".rar","",str_replace(".zip","",$model->FileURL));
            }
            
            // $command = Yii::$app->db->createCommand('DELETE FROM logsdownload WHERE logsdownload.catalogfilesID ='.$id.'; ');
            $command2 = Yii::$app->db->createCommand(' DELETE FROM catalogfiles WHERE ID='.$id);
            $countsuccess=0;
            // if($command->execute() && $command2->execute())
            if($command2->execute())
            // if($model->delete())
            {
                $countsuccess++;
                if(file_exists($path))
                {
                    if(unlink($path))
                    {
                        $countsuccess++;
                    }
                }

                if($pathFolder)
                {
                    if(\common\components\DirectoryHelpers::RemoveDirRecursive($pathFolder))
                    {
                        $countsuccess++;
                    }
                }
            }

            if($countsuccess > 0)
            {
                $trans->commit();
                return true;
            }
        } catch (Exception $e) {
            $trans->rollback();
        }
    }

    public static function deleteKontenDigitalArticle($id)
    {
        $trans = Yii::$app->db->beginTransaction();     
        try {

            $modelserfile = SerialArticlefiles::findOne($id);
            $modelarticle = SerialArticles::findOne($modelserfile->Articles_id);
            $model = Catalogs::findOne($modelarticle->Catalog_id);
            // echo'<pre>';print_r($model->Worksheet_id);die;
            $worksheetDir=\common\components\DirectoryHelpers::GetDirWorksheet($model->Worksheet_id);

            $path =  Yii::getAlias('@uploaded_files').
            DIRECTORY_SEPARATOR.
            'dokumen_isi'.
            DIRECTORY_SEPARATOR.
            $worksheetDir.
            DIRECTORY_SEPARATOR.
            $modelserfile->FileURL;

            if($modelserfile->FileFlash)
            {
                $pathFolder = Yii::getAlias('@uploaded_files').
                DIRECTORY_SEPARATOR.
                'dokumen_isi'.
                DIRECTORY_SEPARATOR.
                $worksheetDir.
                DIRECTORY_SEPARATOR.
                str_replace(".rar","",str_replace(".zip","",$modelserfile->FileURL));
            }
            
            // $command = Yii::$app->db->createCommand('DELETE FROM logsdownload WHERE logsdownload.catalogfilesID ='.$id.'; ');
            $command2 = Yii::$app->db->createCommand(' DELETE FROM serial_articlefiles WHERE ID='.$id);
            $countsuccess=0;
            // if($command->execute() && $command2->execute())
            if($command2->execute())
            // if($model->delete())
            {
                $countsuccess++;
                if(file_exists($path))
                {
                    if(unlink($path))
                    {
                        $countsuccess++;
                    }
                }

                if($pathFolder)
                {
                    if(\common\components\DirectoryHelpers::RemoveDirRecursive($pathFolder))
                    {
                        $countsuccess++;
                    }
                }
            }

            if($countsuccess > 0)
            {
                $trans->commit();
                return true;
            }
        } catch (Exception $e) {
            $trans->rollback();
        }
    }

    /**
     * simple method to encrypt or decrypt a plain text string
     * initialization vector(IV) has to be the same when encrypting and decrypting
     * PHP 5.4.9 ( check your PHP version for function definition changes )
     *
     * this is a beginners template for simple encryption decryption
     * before using this in production environments, please read about encryption
     * use at your own risk
     *
     * @param string $action: can be 'encrypt' or 'decrypt'
     * @param string $string: string to encrypt or decrypt
     *
     * @return string
     */
    public static function encrypt_decrypt($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'INLISV32016';
        $secret_iv = 'INLISV32016';

        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    
}
