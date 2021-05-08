<?php
namespace common\components;


use Yii;
use yii\base\Model;
use common\models\Fields;
use common\models\Catalogs;
use common\models\CatalogRuas;
use common\components\Helpers;

require_once __DIR__.'/Marc/File/MARC.php';
require_once __DIR__.'/Marc/File/MARCXML.php';
/**
 * CollectionmediaSearch represents the model behind the search form about `common\models\Collectionmedias`.
 */
class MarcHelpers
{
    
    public static function CreateModelBibliografis($modelbib,$tagcode,$subruascode,$subruasvalue)
    {
        switch ($tagcode) {
            case '245':
            switch ($subruascode) {
                case 'a':
                $modelbib->Title = trim($subruasvalue);
                break;
                case 'b':
                $modelbib->TitleAdded = trim($subruasvalue);
                break;
                case 'c':
                $modelbib->PenanggungJawab = trim($subruasvalue);
                break;
            }
            break;
            case '100':
            //case '110':
            //case '111':
            switch ($subruascode) {
                case 'a':
                $modelbib->Author=trim($subruasvalue);
                break;
            }
            break;
            case '700':
            case '710':
            case '711':
            switch ($subruascode) {
                case 'a':
                $modelbib->AuthorAdded[]=trim($subruasvalue);
                break;
            }
            break;
            case '260':
            switch ($subruascode) {
                case 'a':
                $modelbib->PublishLocation = trim($subruasvalue);
                break;
                case 'b':
                $modelbib->Publisher = trim($subruasvalue);
                break;
                case 'c':
                $modelbib->PublishYear = trim($subruasvalue);
                break;
            }
            break;
            case '250':
            switch ($subruascode) {
                case 'a':
                $modelbib->Edition = trim($subruasvalue);
                break;
            }
            break; 
            case '082':
            switch ($subruascode) {
                case 'a':
                $modelbib->Class = trim($subruasvalue);
                break;
            }
            break;
            case '300':
            switch ($subruascode) {
                case 'a':
                $modelbib->JumlahHalaman .= trim($subruasvalue);
                break;
                case 'b':
                $modelbib->KeteranganIllustrasi .= trim($subruasvalue);
                break;
                case 'c':
                $modelbib->Dimensi .= trim($subruasvalue);
                break;
            }
            break;
            case '084':
            switch ($subruascode) {
                case 'a':
                $modelbib->CallNumber[] = trim($subruasvalue);
                break;
            }
            break;
            case '650':
            case '600':
            case '651':
            switch ($subruascode) {
                case 'a':
                $modelbib->SubjectTag[] = $tagcode;
                $modelbib->Subject[] = trim($subruasvalue);
                break;
            }
            break;
            case '020':
            switch ($subruascode) {
                case 'a':
                $modelbib->ISBN[] = trim($subruasvalue);
                break;
            }
            break;    
            case '500':
            case '502':
            case '504':
            case '505':
            case '520':
            switch ($subruascode) {
                case 'a':
                $modelbib->NoteTag[] = $tagcode;
                $modelbib->Note[] = trim($subruasvalue);
                break;
            }
            break;   
            /*case '863':
            switch ($subruascode) {
                case 'a':
                $model->EDISISERIAL = trim($subruasvalue);
                break;
            }
            break;*/
            default:
                # code...
            break;
        }
    }

    public static function Export($id,$type)
    {
        $output;
        $model=Catalogs::findOne($id);
        $modelcatruas = CatalogRuas::find()->where(['CatalogId'=>$id])->all();
        
        $record = new File_MARC_Record();
        foreach ($modelcatruas as $ruas) {
            $fieldruas = Fields::getByTag($ruas->Tag);
            if ((bool) !$fieldruas->Fixed) {
                //Data field
                $subFields = array();
                foreach ($ruas->catalogSubruas as $subruas) {
                    $key = str_replace('#', ' ', $subruas->SubRuas);
                    $value = ($subruas->Value == '#') ? '' : $subruas->Value;
                    $subFields[] = new File_MARC_Subfield($key, $value);
                }
                $indikator1 = str_replace('#', ' ', $ruas->Indicator1);
                $indikator2 = str_replace('#', ' ', $ruas->Indicator2);
                $record->appendField(new File_MARC_Data_Field($ruas->Tag, $subFields,
                                $indikator1,
                                $indikator2
                ));
            } else {
                //Control field
                $value = str_replace(array('*', '#'), ' ', $ruas->Value);
                $record->appendField(new File_MARC_Control_Field($ruas->Tag, $value));
            }
        }
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        $type = strtolower($type);
        switch ($type) {
            case 'marc21':
                $output = $record->toRaw();
                $filename = 'Record_'.$model->ID.".mrc";
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Content-Type:text/plain');
                echo $output;
            break;

            case 'marcxml':
                $output = $record->toXML();
                $filename = 'Record_'.$model->ID.".xml";
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header("Content-Type: application/xml; charset=utf-8");
                echo $output;
            break;

            
            case 'mods':
            case 'dc_rdf':
            case 'dc_oai':
            case 'dc_srw':
                $output = $record->toXML();
                $filename = 'Record_'.$model->ID."_".$type.".xml";
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header("Content-Type: application/xml; charset=utf-8");

                //Create temp file
                $temp = \tempnam('/tmp','Record');
                $fp_temp = \fopen($temp, "w");
                \fwrite($fp_temp, $output);
                //Close temp file
                \fclose($fp_temp);


                if($type == 'mods')
                {
                    $xslfile = "MARC21slim2MODS3-5.xsl";
                }
                else if($type == 'dc_rdf')
                {
                    $xslfile = "MARC21slim2RDFDC.xsl";
                }
                else if($type == 'dc_oai')
                {
                    $xslfile = "MARC21slim2OAIDC.xsl";
                }
                else if($type == 'dc_srw')
                {
                    $xslfile = "MARC21slim2SRWDC.xsl";
                }

                $xslDoc = new \DOMDocument();
                $xslDoc->load(\dirname(__FILE__)."/xsl/".$xslfile);

                $xmlDoc = new \DOMDocument();
                $xmlDoc->load($temp);


                $proc = new \XSLTProcessor();
                $proc->importStylesheet($xslDoc);
                echo $proc->transformToXML($xmlDoc);

            break;
        }
        
    }

    public static function MultipleExport($id,$type)
    {

        $mystring = $type;
        $findme   = '&';
        $pos = strpos($mystring, $findme);


        if ($pos === false) {
            $type=$type;
            $filename="Record_";
        } else {
            $type=explode("&", $mystring);
            $type=$type[0];
            $filename="All_Record";
        }

        $outputXml;
        $outputMarc;
        $catID = (is_array($id) ? $id : array($id));
        $type = strtolower($type);
        // $filename="Record_";

        foreach ($catID as $keys => $id) {
            $model=Catalogs::findOne($id);
            $modelcatruas = CatalogRuas::find()->where(['CatalogId'=>$id])->all();
            $record = new File_MARC_Record();
        
            foreach ($modelcatruas as $ruas) {
                $fieldruas = Fields::getByTag($ruas->Tag);
                if ((bool) !$fieldruas->Fixed) {
                    //Data field
                    $subFields = array();
                    foreach ($ruas->catalogSubruas as $subruas) {
                        $key = str_replace('#', ' ', $subruas->SubRuas);
                        $value = ($subruas->Value == '#') ? '' : $subruas->Value;
                        $subFields[] = new File_MARC_Subfield($key, $value);
                    }
                    $indikator1 = str_replace('#', ' ', $ruas->Indicator1);
                    $indikator2 = str_replace('#', ' ', $ruas->Indicator2);
                    $record->appendField(new File_MARC_Data_Field($ruas->Tag, $subFields,
                                    $indikator1,
                                    $indikator2
                    ));
                } else {
                    //Control field
                    $value = str_replace(array('*', '#'), ' ', $ruas->Value);
                    $record->appendField(new File_MARC_Control_Field($ruas->Tag, $value));
                }

            }

            $outputXml .= $record->toXML("UTF-8",true,false);
            $outputMarc .= $record->toRaw();
        }
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        
        switch ($type) {
            case 'marc21':
                $filename .=implode("_",$catID).".mrc";

                //echo $filename;die;
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Content-Type:text/plain');
                
                echo $outputMarc;
               




            break;

            case 'marcxml':
                $output = $record->toXMLMultiple("UTF-8",true,$outputXml);
                if ($pos === false) {
                    $filename .=implode("_",$catID).".xml";
                } else {
                    $filename="All_Record.xml";
                }
                // $filename .=implode("_",$catID).".xml";
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header("Content-Type: application/xml; charset=utf-8");
                echo $output;
            break;

            
            case 'mods':
            case 'dc_rdf':
            case 'dc_oai':
            case 'dc_srw':
                //$output = $record->toXML();
                $output = $record->toXMLMultiple("UTF-8",true,$outputXml);
                //$filename = 'Record_'.$model->ID."_".$type.".xml";
                // $filename .=implode("_",$catID)."_".$type.".xml";
                if ($pos === false) {
                    $filename .=implode("_",$catID)."_".$type.".xml";
                } else {
                    $filename="All_Record.xml";
                }
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header("Content-Type: application/xml; charset=utf-8");

                //Create temp file
                $temp = \tempnam('/tmp','Record');
                $fp_temp = \fopen($temp, "w");
                \fwrite($fp_temp, $output);
                //Close temp file
                \fclose($fp_temp);


                if($type == 'mods')
                {
                    $xslfile = "MARC21slim2MODS3-5.xsl";
                }
                else if($type == 'dc_rdf')
                {
                    $xslfile = "MARC21slim2RDFDC.xsl";
                }
                else if($type == 'dc_oai')
                {
                    $xslfile = "MARC21slim2OAIDC.xsl";
                }
                else if($type == 'dc_srw')
                {
                    $xslfile = "MARC21slim2SRWDC.xsl";
                }

                $xslDoc = new \DOMDocument();
                $xslDoc->load(\dirname(__FILE__)."/xsl/".$xslfile);

                $xmlDoc = new \DOMDocument();
                $xmlDoc->load($temp);


                $proc = new \XSLTProcessor();
                $proc->importStylesheet($xslDoc);
                echo $proc->transformToXML($xmlDoc);

            break;
        }
        
        
    }



    public static function FileToRecord($filePath,&$taglist,&$modelbib,$type='MARC21MRC')
    {
        // Read MARC records from a stream (a file, in this case)
        switch ($type) {
            
            default:
            case 'MARC21MRC':
                $marc_source = new File_MARC($filePath);
                break;

            case 'MARC21XML':
                $marc_source = new File_MARCXML($filePath);
                break;
            case 'DUBLINXML':
            case 'MODSXML':
                self::ForceToXML21($filePath,$type);
                $marc_source = new File_MARCXML($filePath);
                break;;

        }
        
        $counter=0;
        // Go through each record
        while ($record = $marc_source->next()) {
            // Iterate through the fields
            foreach ($record->getFields() as $tag => $subfields) {
                    //echo $tag."<br>";
                    //echo "<b>$subfields</b>";
                    $data =  \common\models\Fields::getByTag($tag);
                        if((int)$tag > 9)
                        {

                            $ind1=$subfields->getIndicator(1);
                            $ind2=$subfields->getIndicator(2);
                            $tagsvalue="";
                            foreach ($subfields->getSubfields() as $code => $value) {
                                $subruascode=substr($value,1,1);
                                $subruasvalue=trim(substr($value,4));
                                $tagsvalue .= "$".$subruascode." ".$subruasvalue." ";
                                //self::CreateModelBibliografis($modelbib,$tag,$subruascode,$subruasvalue);
                            }

                        }else{
                            //Control Field
                            $tagsvalue=substr($subfields,3);
                        }

                        if ($data != null)
                        {
                            $taglist[$counter]["tagname"][$tag]  = $data->Name;
                            $taglist[$counter]["tagid"][$tag] = $data->ID;
                            $taglist[$counter]["tagmandatory"][$tag] = ($data->Mandatory == true) ? 1: 0;
                            $taglist[$counter]["taglength"][$tag] = $data->Length;
                            $taglist[$counter]["tagenabled"][$tag] = ($data->Enabled == true) ? 1: 0;
                            $taglist[$counter]["tagiscustomable"][$tag] = ($data->IsCustomable == true) ? 1: 0;
                            $taglist[$counter]["tagfixed"][$tag] = ($data->Fixed == true) ? 1: 0;
                            $taglist[$counter]["tagrepeatable"][$tag] = ($data->Repeatable == true) ? 1: 0;
                            if($data->Repeatable!=1)
                            {
                                $taglist[$counter]["inputvalue"][$tag]  = $tagsvalue;
                                if((int)$tag > 9)
                                {
                                    $taglist[$counter]["indicator"][$tag]["ind1"]  = (trim($ind1) == '') ? "#" : trim($ind1);
                                    $taglist[$counter]["indicator"][$tag]["ind2"]  = (trim($ind2) == '') ? "#" : trim($ind2);
                                }else{
                                    $taglist[$counter]["indicator"][$tag]["ind1"]  = NULL;
                                    $taglist[$counter]["indicator"][$tag]["ind2"]  = NULL;
                                }
                            }else{
                                $taglist[$counter]["inputvalue"][$tag][]  = $tagsvalue;
                                if((int)$tag > 9)
                                {
                                    $taglist[$counter]["indicator"][$tag][]  = array("ind1"=>(trim($ind1) == "") ? "#" : trim($ind1),"ind2"=> (trim($ind2) == "") ? "#" : trim($ind2));
                                }else{
                                    $taglist[$counter]["indicator"][$tag][]  = array("ind1"=>NULL,"ind2"=> NULL);
                                }
                            }
                        }
                    
                    
                    //echo "<br>";
                //}
            }
        $counter++;
        }

    }

    public static  function ForceToXML21($filepath,$type)
    {
        $xml = simplexml_load_file($filepath);

        if($type == 'DUBLINXML')
        {
            $xslfile = "DC2MARC21slim.xsl";
        }
        else if($type == 'MODSXML')
        {
            $xslfile = "MODS2MARC21slim.xsl";
        }

        $xslDoc = new \DOMDocument();
        $xslDoc->load(\dirname(__FILE__)."/xsl/".$xslfile);
        
        $proc = new \XSLTProcessor();
        $proc->importStylesheet($xslDoc);
        
        $newXml = $proc->transformToXML($xml);
        file_put_contents($filepath, str_replace("marc:", "", $newXml));
    }

    public static function FileToRecord2($filePath,&$taglist,&$modelbib,$type='MARC21MRC')
    {
        // Read MARC records from a stream (a file, in this case)
        switch ($type) {
            
            default:
            case 'MARC21MRC':
                $marc_source = new File_MARC($filePath);
                break;

            case 'MARC21XML':
                $marc_source = new File_MARCXML($filePath);
                break;
            case 'DUBLINXML':
            case 'MODSXML':
                self::ForceToXML21($filePath,$type);
                $marc_source = new File_MARCXML($filePath);
                break;

        }
        $counter=0;
        // Go through each record
        while ($record = $marc_source->next()) {
            // Iterate through the fields
            $countertag=0;
            $taglist[$counter][0]['tag'] = 'Leader';
            $taglist[$counter][0]['value'] = $record->getLeader();
            //print_r($record->getFields());
            foreach ($record->getFields() as $tag => $subfields) {
                   // echo $tag;
                   // echo "<b>$subfields</b>"."<br>";
                    
                        
                            //echo $tag.'<br>';
                            $data =  \common\models\Fields::getByTag($tag);
                            $countertag++;
                            if((int)$tag > 9)
                            {
                                //Data Field
                                $tagsvalue="";
                                foreach ($subfields->getSubfields() as $code => $value) {
                                    $subruascode=substr($value,1,1);
                                    $subruasvalue=trim(substr($value,4));
                                    $tagsvalue .= "$".$subruascode." ".$subruasvalue." ";
                                    //self::CreateModelBibliografis($modelbib,$tag,$subruascode,$subruasvalue);
                                }
                                $ind1=$subfields->getIndicator(1);
                                $ind2=$subfields->getIndicator(2);
                            }else{
                                //Control Field
                                $tagsvalue=substr($subfields,3);
                            }
                            if ($data != null)
                            {
                                $taglist[$counter][$countertag]['id'] = $data->ID;
                                $taglist[$counter][$countertag]['name'] = $data->Name;
                                $taglist[$counter][$countertag]['tag'] = $data->Tag;
                                if((int)$tag > 9)
                                {
                                    $taglist[$counter][$countertag]['ind1'] = (trim($ind1)=='') ? "#" : trim($ind1);
                                    $taglist[$counter][$countertag]['ind2'] = (trim($ind2)=='') ? "#" : trim($ind2);
                                }else{
                                    $taglist[$counter][$countertag]['ind1'] = NULL;
                                    $taglist[$counter][$countertag]['ind2'] = NULL;
                                }
                                $taglist[$counter][$countertag]['value'] = $tagsvalue;
                                $taglist[$counter][$countertag]['mandatory'] = ($data->Mandatory == true) ? 1: 0;
                                $taglist[$counter][$countertag]['length'] = $data->Length;
                                $taglist[$counter][$countertag]['enabled'] = ($data->Enabled == true) ? 1: 0;
                                $taglist[$counter][$countertag]['iscustomable'] = ($data->IsCustomable == true) ? 1: 0;
                                $taglist[$counter][$countertag]['fixed'] = ($data->Fixed == true) ? 1: 0;
                                $taglist[$counter][$countertag]['repeatable'] = ($data->Repeatable == true) ? 1: 0;
                            }
                        
                        
                   /* else{
                        //echo "<b>".substr($subfields,4)."</b><br>";
                    }*/
                    
                    //echo "<br>";
                //}
                
            }
            /*print_r($taglist);
             die;*/
            //echo'---';
        $counter++;
        /*if($counter==2)
        {
            die;
        }*/
        }

    }



    public static function SruToRecord($url,$port,$db,$criteria,$query,$startRecord,$maxRecord,$protocol,&$taglist,$mode)
    {
        try{
            if($port != '-')
            {
                $port = ':'.$port;
            }else{
                $port = '';
            }

            if($db != '-')
            {
                $db = '/'.$db;
            }else{
                $db = '';
            }
            if($protocol == 'z3950')
            {
                //for z3950 (LOC)
                $version = 'version=1.1&';
                $criteria = 'query';
                $query = '%22'.$query.'%22';
            }else{
                //for protocol sru
                $version = '';
                $query = rawurlencode($query);
            }
            $request='http://'.str_replace('http://','',$url).$port.$db.'?'.$version.'operation=searchRetrieve&'.$criteria.'='.$query.'&startRecord='.$startRecord.'&maxitem='.$maxRecord;
            //echo '<a href='.$request.'>'.$request.'</a>'; 
            if($mode == 'entri')
            {
                //mapping taglist for mode entri
                if($protocol == 'z3950')
                {
                    $taglist = self::Z3950CatalogMapping($request);
                }else{
                    $taglist = self::SRUCatalogMapping($request);
                }
            }else if($mode == 'salin'){

                //mapping taglist for mode salin
                if($protocol == 'z3950')
                {
                    $taglist = self::Z3950CatalogMapping2($request);
                }else{
                    $taglist = self::SRUCatalogMapping2($request);
                } 
            }
            
            //echo '<pre>'; print_r($taglist); echo '</pre>'; die;

        }//end of try

        catch(Exception $e){echo $e->getMessage();exit();}
    }

    public static function Z3950CatalogMapping($request)
    {
        $xml = \simplexml_load_file($request);
        $counterRecord=0;
        $records_vars = $xml->children('zs', true)->records;
        //echo count($records_vars);
        //var_dump($records_vars); die;
        
        foreach ($records_vars as $key => $records_var) {
            foreach ($records_var->record as $key2 => $recordData_vars) {
                foreach ($recordData_vars->recordData as $key3 => $recordsx) {   
                    $countercontrolfield=0;
                    $counterdatafield=0;
                    $countertag=0;
                    foreach ($recordsx->children()->record as $key4 => $recordx) {
                        /*$taglist[$counterRecord][$countertag]['tag']='leader';
                        $taglist[$counterRecord][$countertag]['value']=(string)$recordx->leader;*/
                        $countertag++;

                        foreach($recordx->controlfield as $a => $b) {
                            foreach ($recordx->controlfield[$countercontrolfield]->attributes() as $c => $d) {
                                $data =  \common\models\Fields::getByTag($d);
                                $tagcode= (string)$d;
                                $taglist[$counterRecord]['tagid'][$tagcode] = $data->ID;
                                $taglist[$counterRecord]['tagname'][$tagcode] = $data->Name;
                                $taglist[$counterRecord]['tagmandatory'][$tagcode] = ($data->Mandatory == true) ? 1: 0;
                                $taglist[$counterRecord]['taglength'][$tagcode] = $data->Length;
                                $taglist[$counterRecord]['tagenabled'][$tagcode] = ($data->Enabled == true) ? 1: 0;
                                $taglist[$counterRecord]['tagiscustomable'][$tagcode] = ($data->IsCustomable == true) ? 1: 0;
                                $taglist[$counterRecord]['tagfixed'][$tagcode] = ($data->Fixed == true) ? 1: 0;
                                $taglist[$counterRecord]['tagrepeatable'][$tagcode] = ($data->Repeatable == true) ? 1: 0;
                            }
                            $taglist[$counterRecord]['inputvalue'][$tagcode]=(string)$b;
                            $countercontrolfield++;
                            $countertag++;
                        }

                        foreach($recordx->datafield as $a => $b) {
                            $counterIndicator=0;
                            $tagcode=  (string)$b->attributes()->tag; 
                            $data =  \common\models\Fields::getByTag($tagcode);
                            
                            foreach ($recordx->datafield[$counterdatafield]->attributes() as $c => $d) {

                                switch ($c) {
                                    case 'tag':
                                        $taglist[$counterRecord]['tagid'][$tagcode] = $data->ID;
                                        $taglist[$counterRecord]['tagname'][$tagcode] = $data->Name;
                                        $taglist[$counterRecord]['tagmandatory'][$tagcode] = ($data->Mandatory == true) ? 1: 0;
                                        $taglist[$counterRecord]['taglength'][$tagcode] = $data->Length;
                                        $taglist[$counterRecord]['tagenabled'][$tagcode] = ($data->Enabled == true) ? 1: 0;
                                        $taglist[$counterRecord]['tagiscustomable'][$tagcode] = ($data->IsCustomable == true) ? 1: 0;
                                        $taglist[$counterRecord]['tagfixed'][$tagcode] = ($data->Fixed == true) ? 1: 0;
                                        $taglist[$counterRecord]['tagrepeatable'][$tagcode] = ($data->Repeatable == true) ? 1: 0;
                                        break;

                                    case 'ind1':
                                        $dataIndicator1=array();
                                        if($data->Repeatable!=true)
                                        {
                                            $taglist[$counterRecord]["indicator"][$tagcode]["ind1"]  = (trim((string)$d) == '') ? "#" : trim((string)$d);
                                        }else{
                                            $dataIndicator1  = (trim((string)$d) == "") ? "#" : trim((string)$d);
                                        }
                                        break;

                                    case 'ind2':
                                        if($data->Repeatable!=true)
                                        {
                                            $taglist[$counterRecord]["indicator"][$tagcode]["ind2"]  = (trim((string)$d) == '') ? "#" : trim((string)$d);
                                        }else{
                                            $dataIndicator = array();
                                            $dataIndicator2  = (trim((string)$d) == "") ? "#" : trim((string)$d);
                                            $dataIndicator = ['ind1'=>$dataIndicator1,'ind2'=>$dataIndicator2];
                                            $taglist[$counterRecord]["indicator"][$tagcode][]  = $dataIndicator;
                                        }
                                        break;
                                }
                            }
                            $countersubfield=0;
                            $tagvalue='';
                            foreach ($b->subfield as $e => $f) {
                                foreach ($b->subfield[$countersubfield]->attributes() as $g => $h) {
                                    if($tagvalue=='')
                                    {
                                        $tagvalue .= '$'.$h;
                                    }else{
                                        $tagvalue .= ' $'.$h;
                                    }
                                            
                                }
                                $tagvalue .= ' '.trim($f);
                                $countersubfield++;
                            }
                            if($data->Repeatable!=true)
                            {
                                $taglist[$counterRecord]['inputvalue'][$tagcode]=$tagvalue;
                            }else{
                                $taglist[$counterRecord]["inputvalue"][$tagcode][]  = $tagvalue;
                            }
                            
                            $counterdatafield++;
                            $countertag++;
                        }

                        $counterRecord++;
                        //echo '<pre>'; print_r($recordx); echo '</pre>';
                    }
                }
            }
        }

        return $taglist;
    }

    public static function Z3950CatalogMapping2($request)
    {
        $xml = \simplexml_load_file($request);
        $counterRecord=0;
        $records_vars = $xml->children('zs', true)->records;
        //echo count($records_vars);
        //var_dump($records_vars);
        foreach ($records_vars as $key => $records_var) {
            foreach ($records_var->record as $key2 => $recordData_vars) {
                foreach ($recordData_vars->recordData as $key3 => $recordsx) {   
                    $countercontrolfield=0;
                    $counterdatafield=0;
                    $countertag=0;
                    foreach ($recordsx->children()->record as $key4 => $recordx) {
                        $taglist[$counterRecord][$countertag]['tag']='leader';
                        $taglist[$counterRecord][$countertag]['value']=(string)$recordx->leader;
                        $countertag++;

                        foreach($recordx->controlfield as $a => $b) {
                            foreach ($recordx->controlfield[$countercontrolfield]->attributes() as $c => $d) {
                                $data =  \common\models\Fields::getByTag($d);
                                $taglist[$counterRecord][$countertag]['id'] = $data->ID;
                                $taglist[$counterRecord][$countertag]['name'] = $data->Name;
                                $taglist[$counterRecord][$countertag]['tag']=(string)$d;
                                $taglist[$counterRecord][$countertag]['mandatory'] = ($data->Mandatory == true) ? 1: 0;
                                $taglist[$counterRecord][$countertag]['length'] = $data->Length;
                                $taglist[$counterRecord][$countertag]['enabled'] = ($data->Enabled == true) ? 1: 0;
                                $taglist[$counterRecord][$countertag]['iscustomable'] = ($data->IsCustomable == true) ? 1: 0;
                                $taglist[$counterRecord][$countertag]['fixed'] = ($data->Fixed == true) ? 1: 0;
                                $taglist[$counterRecord][$countertag]['repeatable'] = ($data->Repeatable == true) ? 1: 0;
                            }
                            $taglist[$counterRecord][$countertag]['value']=(string)$b;
                            $countercontrolfield++;
                            $countertag++;
                        }

                        foreach($recordx->datafield as $a => $b) {
                            foreach ($recordx->datafield[$counterdatafield]->attributes() as $c => $d) {
                                switch ($c) {
                                    case 'tag':
                                        $data =  \common\models\Fields::getByTag($d);
                                        $taglist[$counterRecord][$countertag]['id'] = $data->ID;
                                        $taglist[$counterRecord][$countertag]['name'] = $data->Name;
                                        $taglist[$counterRecord][$countertag]['tag']=(string)$d;
                                        $taglist[$counterRecord][$countertag]['mandatory'] = ($data->Mandatory == true) ? 1: 0;
                                        $taglist[$counterRecord][$countertag]['length'] = $data->Length;
                                        $taglist[$counterRecord][$countertag]['enabled'] = ($data->Enabled == true) ? 1: 0;
                                        $taglist[$counterRecord][$countertag]['iscustomable'] = ($data->IsCustomable == true) ? 1: 0;
                                        $taglist[$counterRecord][$countertag]['fixed'] = ($data->Fixed == true) ? 1: 0;
                                        $taglist[$counterRecord][$countertag]['repeatable'] = ($data->Repeatable == true) ? 1: 0;
                                        break;

                                    case 'ind1':
                                        $taglist[$counterRecord][$countertag]['ind1']=(trim((string)$d) == "") ? "#" :  (string)$d;
                                        break;

                                    case 'ind2':
                                        $taglist[$counterRecord][$countertag]['ind2']=(trim((string)$d) == "") ? "#" :  (string)$d;
                                        break;
                                }
                            }
                            $countersubfield=0;
                            $tagvalue='';
                            foreach ($b->subfield as $e => $f) {
                                foreach ($b->subfield[$countersubfield]->attributes() as $g => $h) {
                                    if($tagvalue=='')
                                    {
                                        $tagvalue .= '$'.$h;
                                    }else{
                                        $tagvalue .= ' $'.$h;
                                    }
                                            
                                }
                                $tagvalue .= ' '.trim($f);
                                $countersubfield++;
                            }
                            $taglist[$counterRecord][$countertag]['value']=$tagvalue;
                            $counterdatafield++;
                            $countertag++;
                        }

                        $counterRecord++;
                        //echo '<pre>'; print_r($recordx); echo '</pre>';
                    }
                }
            }
        }

        return $taglist;
    }

    public static function SRUCatalogMapping($request)
    {
        $xml = \simplexml_load_file($request);
        $counterRecord=0;
        $recordsx = $xml->record;
        //echo count($records_vars);
        //var_dump($xml); die;
        foreach ($recordsx as $key4 => $recordx) {
            $countercontrolfield=0;
            $counterdatafield=0;
            $countertag=0;
            /*$taglist[$counterRecord][$countertag]['tag']='leader';
            $taglist[$counterRecord][$countertag]['value']=(string)$recordx->leader;*/
            //$countertag++;

            foreach($recordx->controlfield as $a => $b) {
                if(isset($recordx->controlfield[$countercontrolfield]))
                {
                    foreach ($recordx->controlfield[$countercontrolfield]->attributes() as $c => $d) {
                        $data =  \common\models\Fields::getByTag($d);
                        $tagcode= (string)$d;
                        $taglist[$counterRecord]['tagid'][$tagcode] = $data->ID;
                        $taglist[$counterRecord]['tagname'][$tagcode] = $data->Name;
                        $taglist[$counterRecord]['tagmandatory'][$tagcode] = ($data->Mandatory == true) ? 1: 0;
                        $taglist[$counterRecord]['taglength'][$tagcode] = $data->Length;
                        $taglist[$counterRecord]['tagenabled'][$tagcode] = ($data->Enabled == true) ? 1: 0;
                        $taglist[$counterRecord]['tagiscustomable'][$tagcode] = ($data->IsCustomable == true) ? 1: 0;
                        $taglist[$counterRecord]['tagfixed'][$tagcode] = ($data->Fixed == true) ? 1: 0;
                        $taglist[$counterRecord]['tagrepeatable'][$tagcode] = ($data->Repeatable == true) ? 1: 0;
                    }
                    $taglist[$counterRecord]['inputvalue'][$tagcode]=(string)$b;
                    $countercontrolfield++;
                    $countertag++;
                }
            }

            foreach($recordx->datafield as $a => $b) {
                $counterIndicator=0;
                $tagcode=  (string)$b->attributes()->tag; 
                $data =  \common\models\Fields::getByTag($tagcode);
                
                if(isset($recordx->datafield[$counterdatafield]))
                {
                    foreach ($recordx->datafield[$counterdatafield]->attributes() as $c => $d) {

                        switch ($c) {
                            case 'tag':
                                $taglist[$counterRecord]['tagid'][$tagcode] = $data->ID;
                                $taglist[$counterRecord]['tagname'][$tagcode] = $data->Name;
                                $taglist[$counterRecord]['tagmandatory'][$tagcode] = ($data->Mandatory == true) ? 1: 0;
                                $taglist[$counterRecord]['taglength'][$tagcode] = $data->Length;
                                $taglist[$counterRecord]['tagenabled'][$tagcode] = ($data->Enabled == true) ? 1: 0;
                                $taglist[$counterRecord]['tagiscustomable'][$tagcode] = ($data->IsCustomable == true) ? 1: 0;
                                $taglist[$counterRecord]['tagfixed'][$tagcode] = ($data->Fixed == true) ? 1: 0;
                                $taglist[$counterRecord]['tagrepeatable'][$tagcode] = ($data->Repeatable == true) ? 1: 0;
                                break;

                            case 'ind1':
                                $dataIndicator1=array();
                                if($data->Repeatable!=true)
                                {
                                    $taglist[$counterRecord]["indicator"][$tagcode]["ind1"]  = (trim((string)$d) == '') ? "#" : trim((string)$d);
                                }else{
                                    $dataIndicator1  = (trim((string)$d) == "") ? "#" : trim((string)$d);
                                }
                                break;

                            case 'ind2':
                                if($data->Repeatable!=true)
                                {
                                    $taglist[$counterRecord]["indicator"][$tagcode]["ind2"]  = (trim((string)$d) == '') ? "#" : trim((string)$d);
                                }else{
                                    $dataIndicator = array();
                                    $dataIndicator2  = (trim((string)$d) == "") ? "#" : trim((string)$d);
                                    $dataIndicator = ['ind1'=>$dataIndicator1,'ind2'=>$dataIndicator2];
                                    $taglist[$counterRecord]["indicator"][$tagcode][]  = $dataIndicator;
                                }
                                break;
                        }
                    }
                    $countersubfield=0;
                    $tagvalue='';
                    foreach ($b->subfield as $e => $f) {
                        foreach ($b->subfield[$countersubfield]->attributes() as $g => $h) {
                            if($tagvalue=='')
                            {
                                $tagvalue .= '$'.$h;
                            }else{
                                $tagvalue .= ' $'.$h;
                            }
                                    
                        }
                        $tagvalue .= ' '.trim($f);
                        $countersubfield++;
                    }
                    if($data->Repeatable!=true)
                    {
                        $taglist[$counterRecord]['inputvalue'][$tagcode]=$tagvalue;
                    }else{
                        $taglist[$counterRecord]["inputvalue"][$tagcode][]  = $tagvalue;
                    }
                    
                    $counterdatafield++;
                    $countertag++;
                }
            }

            $counterRecord++;
            //echo '<pre>'; print_r($recordx); echo '</pre>';
                    
        }
        return $taglist;
    }

    public static function SRUCatalogMapping2($request)
    {
        $xml = \simplexml_load_file($request);
        $counterRecord=0;
        $recordsx = $xml->record;
        //echo count($records_vars);
        //var_dump($xml); die;
        foreach ($recordsx as $key4 => $recordx) {

            $countertag=0;
            $countercontrolfield=0;
            $counterdatafield=0;
            $taglist[$counterRecord][$countertag]['tag']='leader';
            $taglist[$counterRecord][$countertag]['value']=(string)$recordx->leader;
            $countertag++;

            foreach($recordx->controlfield as $a => $b) {
                if(isset($recordx->controlfield[$countercontrolfield]))
                {
                    foreach ($recordx->controlfield[$countercontrolfield]->attributes() as $c => $d) {
                        $data =  \common\models\Fields::getByTag($d);
                        $taglist[$counterRecord][$countertag]['id'] = $data->ID;
                        $taglist[$counterRecord][$countertag]['name'] = $data->Name;
                        $taglist[$counterRecord][$countertag]['tag']=(string)$d;
                        $taglist[$counterRecord][$countertag]['mandatory'] = ($data->Mandatory == true) ? 1: 0;
                        $taglist[$counterRecord][$countertag]['length'] = $data->Length;
                        $taglist[$counterRecord][$countertag]['enabled'] = ($data->Enabled == true) ? 1: 0;
                        $taglist[$counterRecord][$countertag]['iscustomable'] = ($data->IsCustomable == true) ? 1: 0;
                        $taglist[$counterRecord][$countertag]['fixed'] = ($data->Fixed == true) ? 1: 0;
                        $taglist[$counterRecord][$countertag]['repeatable'] = ($data->Repeatable == true) ? 1: 0;
                    }

                    $taglist[$counterRecord][$countertag]['value']=(string)$b;
                    $countercontrolfield++;
                    $countertag++;
                }
               
            }

            foreach($recordx->datafield as $a => $b) {
                if(isset($recordx->datafield[$counterdatafield]))
                {
                    foreach ($recordx->datafield[$counterdatafield]->attributes() as $c => $d) {
                        switch ($c) {
                            case 'tag':
                                $data =  \common\models\Fields::getByTag($d);
                                $taglist[$counterRecord][$countertag]['id'] = $data->ID;
                                $taglist[$counterRecord][$countertag]['name'] = $data->Name;
                                $taglist[$counterRecord][$countertag]['tag']=(string)$d;
                                $taglist[$counterRecord][$countertag]['mandatory'] = ($data->Mandatory == true) ? 1: 0;
                                $taglist[$counterRecord][$countertag]['length'] = $data->Length;
                                $taglist[$counterRecord][$countertag]['enabled'] = ($data->Enabled == true) ? 1: 0;
                                $taglist[$counterRecord][$countertag]['iscustomable'] = ($data->IsCustomable == true) ? 1: 0;
                                $taglist[$counterRecord][$countertag]['fixed'] = ($data->Fixed == true) ? 1: 0;
                                $taglist[$counterRecord][$countertag]['repeatable'] = ($data->Repeatable == true) ? 1: 0;
                                break;

                            case 'ind1':
                                $taglist[$counterRecord][$countertag]['ind1']=(trim((string)$d) == "") ? "#" :  (string)$d;
                                break;

                            case 'ind2':
                                $taglist[$counterRecord][$countertag]['ind2']=(trim((string)$d) == "") ? "#" :  (string)$d;
                                break;
                        }
                    }

                    
                    $countersubfield=0;
                    $tagvalue='';
                    foreach ($b->subfield as $e => $f) {
                        foreach ($b->subfield[$countersubfield]->attributes() as $g => $h) {
                            if($tagvalue=='')
                            {
                                $tagvalue .= '$'.$h;
                            }else{
                                $tagvalue .= ' $'.$h;
                            }
                                    
                        }
                        $tagvalue .= ' '.trim($f);
                        $countersubfield++;
                    }
                    $taglist[$counterRecord][$countertag]['value']=$tagvalue;
                    $counterdatafield++;
                    $countertag++;
                    
                }


            }

            $counterRecord++;
            //echo '<pre>'; print_r($recordx); echo '</pre>';
        }

        return $taglist;
    }

    
}
