<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package KatalogController.php
 * @version 1.0.0
 * @author Rico Ulul Ilmy <rico.ululn@gmail.com>
 */


use yii\helpers\Url; 
use common\components\DirectoryHelpers;
use common\components\OpacHelpers;


function create_metadata($outputObj, $cur_record, $identifier, $setspec, $db) {


	$url=Yii::$app->urlManager->createAbsoluteUrl('');
	$urlDetail=Yii::$app->urlManager->createAbsoluteUrl('detail-opac');

	$metadata_node = $outputObj->create_metadata($cur_record);
    $oai_node = $outputObj->addChild($metadata_node, "record");
	$oai_node->setAttribute("xmlns","http://www.loc.gov/MARC21/slim");
	$oai_node->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
	$oai_node->setAttribute("xsi:schemaLocation", "http://www.loc.gov/MARC21/slim http://www.loc.gov/standards/marcxml/schema/MARC21slim.xsd");

	$record 	= get_record($identifier, $db);
	$cover 		= get_cover($identifier, $db);
	$files 		= get_files($identifier, $db);
	$digitalCol = OpacHelpers::getDigitalCollectionDir($identifier);
	//$data[$value['Tag']] = [];
	foreach ($record as $key => $value) {		
			
			// echo "<pre>";
			// print_r($record);
			// die;
			 	
				/* if(!$data) {
					$data[$value['Tag']]['SubRuasField'][] = $value['SubRuasField'];
					 $data[$value['Tag']]['ruas'][] = $value['ruas'];
					 $data[$value['Tag']]['subruas'][] = $value['subruas'];

				} else
				if($data[$value['Tag']]){
				
					 array_push($data[$value['Tag']]['SubRuasField'],$value['SubRuasField']);
					 array_push($data[$value['Tag']]['ruas'],$value['ruas']);
					 array_push($data[$value['Tag']]['subruas'],$value['subruas']);
				 } 
				 else
				 {
					  $data[$value['Tag']]['SubRuasField'][] = $value['SubRuasField'];
					  $data[$value['Tag']]['ruas'][] = $value['ruas'];
					  $data[$value['Tag']]['subruas'][] = $value['subruas'];

				 } */
			 
			
			
			switch ($value['Tag']) {
			
				case 001:
					
					$fixfield_node = $outputObj->addChild($oai_node,"controlfield",utf8_for_xml(htmlspecialchars($value['ruas'])));
					$fixfield_node->setAttribute("tag","001");
					break;
				
				case 002:
					$fixfield_node = $outputObj->addChild($oai_node,"controlfield",utf8_for_xml(htmlspecialchars($value['ruas'])));
					$fixfield_node->setAttribute("tag","002");
					break;
				case 003:
					$fixfield_node = $outputObj->addChild($oai_node,"controlfield",utf8_for_xml(htmlspecialchars($value['ruas'])));
					$fixfield_node->setAttribute("tag","003");
					break;
				case 004:
					$fixfield_node = $outputObj->addChild($oai_node,"controlfield",utf8_for_xml(htmlspecialchars($value['ruas'])));
					$fixfield_node->setAttribute("tag","004");
					break;
				case 005:
					$fixfield_node = $outputObj->addChild($oai_node,"controlfield",utf8_for_xml(htmlspecialchars($value['ruas'])));
					$fixfield_node->setAttribute("tag","005");
					break;
				case 006:
					$fixfield_node = $outputObj->addChild($oai_node,"controlfield",utf8_for_xml(htmlspecialchars($value['ruas'])));
					$fixfield_node->setAttribute("tag","006");
					break;
				case 007:
					$fixfield_node = $outputObj->addChild($oai_node,"controlfield",utf8_for_xml(htmlspecialchars($value['ruas'])));
					$fixfield_node->setAttribute("tag","007");
					break;
				case 008:
					$fixfield_node = $outputObj->addChild($oai_node,"controlfield",utf8_for_xml(htmlspecialchars($value['ruas'])));
					$fixfield_node->setAttribute("tag","008");
					break;
				default:

				/* $val= isset($value['SubRuasField']) ? $value['subruas'] : $value['ruas'] ;
				$ind1= isset($value['Indicator1']) ? $value['Indicator1'] : "#" ;
				$ind2= isset($value['Indicator2']) ? $value['Indicator2'] : "#" ;
				$subfield= isset($value['SubRuasField']) ? $value['SubRuasField'] : "$" ;
				$datafield = $outputObj->addChild($oai_node,"datafield");			
				$datafield->setAttribute("tag",$value['Tag']);
				$datafield->setAttribute("ind1",$ind1);
				$datafield->setAttribute("ind2",$ind2);
				$subfield_node = $outputObj->addChild($datafield,"subfield",htmlspecialchars($val));
				$subfield_node->setAttribute("code",htmlspecialchars($subfield));
 */
				break;							
			}
			
				$val= isset($value['SubRuasField']) ? $value['subruas'] : $value['ruas'] ;
				$ind1= isset($value['Indicator1']) ? $value['Indicator1'] : "#" ;
				$ind2= isset($value['Indicator2']) ? $value['Indicator2'] : "#" ;
				$subfield= isset($value['SubRuasField']) ? $value['SubRuasField'] : "$" ;
				/* $datafield = $outputObj->addChild($oai_node,"datafield");			
				$datafield->setAttribute("tag",$value['Tag']);
				$datafield->setAttribute("ind1",$ind1);
				$datafield->setAttribute("ind2",$ind2);
				$subfield_node = $outputObj->addChild($datafield,"subfield",htmlspecialchars($val));
				$subfield_node->setAttribute("code",htmlspecialchars($subfield)); */

			
			if(!$data) {
					// $data[$value['Tag']]['size'] = sizeof($data[$value['Tag']]['SubRuasField']);
					$data[$value['Tag']]['CatalogID']= $value['CatalogID'];
					$data[$value['Tag']]['Tag']= $value['Tag'];
					$data[$value['Tag']]['Indicator1']= $value['Indicator1'];
					$data[$value['Tag']]['Indicator2']= $value['Indicator2'];
					$data[$value['Tag']]['SubRuasField'][] = $value['SubRuasField'];
					$data[$value['Tag']]['ruas'][] = $value['ruas'];
					$data[$value['Tag']]['subruas'][] = $value['subruas'];
					$datafield = $outputObj->addChild($oai_node,"datafield");			
					$datafield->setAttribute("tag",$value['Tag']);
					$datafield->setAttribute("ind1",$ind1);
					$datafield->setAttribute("ind2",$ind2);
					$subfield_node = $outputObj->addChild($datafield,"subfield",utf8_for_xml(htmlspecialchars($val)));
					$subfield_node->setAttribute("code",utf8_for_xml(htmlspecialchars($subfield)));
					$data[$value['Tag']]['size'] = sizeof($data[$value['Tag']]['SubRuasField']);
				
				} else
				 //if(array_key_exists('SubRuasField',$data[$value['Tag']])){
				if($data[$value['Tag']]){
				//$data[11]=11;
					// echo"<pre>";
					// print_r($data[$value['Tag']]['SubRuasField']);
					// die;
					if(in_array($value['SubRuasField'],$data[$value['Tag']]['SubRuasField'])){
						$datafield = $outputObj->addChild($oai_node,"datafield");			
						$datafield->setAttribute("tag",$value['Tag']);
						$datafield->setAttribute("ind1",$ind1);
						$datafield->setAttribute("ind2",$ind2);
						$subfield_node = $outputObj->addChild($datafield,"subfield",utf8_for_xml(htmlspecialchars($val)));
						$subfield_node->setAttribute("code",utf8_for_xml(htmlspecialchars($subfield)));
					} else {
						$subfield_node = $outputObj->addChild($datafield,"subfield",utf8_for_xml(htmlspecialchars($val)));
						$subfield_node->setAttribute("code",utf8_for_xml(htmlspecialchars($subfield)));
					}

					array_push($data[$value['Tag']]['SubRuasField'],$value['SubRuasField']);
					 array_push($data[$value['Tag']]['ruas'],$value['ruas']);
					 array_push($data[$value['Tag']]['subruas'],$value['subruas']);
					/* $subfield_node = $outputObj->addChild($datafield,"subfield",htmlspecialchars($val));
					$subfield_node->setAttribute("code",htmlspecialchars($subfield)); */
				
				
					
					//$data[$value['Tag']]['size'] = sizeof($data[$value['Tag']]['SubRuasField']);
					/* if(sizeof($data[$value['Tag']]['SubRuasField']) == 2) {
						$datafield = $outputObj->addChild($oai_node,"datafield");			
						$datafield->setAttribute("tag",$value['Tag']);
						$datafield->setAttribute("ind1",$ind1);
						$datafield->setAttribute("ind2",$ind2);
						$subfield_node = $outputObj->addChild($datafield,"subfield",htmlspecialchars($val));
						$subfield_node->setAttribute("code",htmlspecialchars($subfield));
					} else {
						$subfield_node = $outputObj->addChild($datafield,"subfield",htmlspecialchars($val));
						$subfield_node->setAttribute("code",htmlspecialchars($subfield));
					} */
					
						
					
					
				 } 
				 else
				 {	
					$data[$value['Tag']]['CatalogID']= $value['CatalogID'];
					$data[$value['Tag']]['Tag']= $value['Tag'];
					$data[$value['Tag']]['Indicator1']= $value['Indicator1'];
					$data[$value['Tag']]['Indicator2']= $value['Indicator2'];
					$data[$value['Tag']]['SubRuasField'][] = $value['SubRuasField'];
					$data[$value['Tag']]['ruas'][] = $value['ruas'];
					$data[$value['Tag']]['subruas'][] = $value['subruas'];
					$datafield = $outputObj->addChild($oai_node,"datafield");			
					$datafield->setAttribute("tag",$value['Tag']);
					$datafield->setAttribute("ind1",$ind1);
					$datafield->setAttribute("ind2",$ind2);
					$subfield_node = $outputObj->addChild($datafield,"subfield",utf8_for_xml(htmlspecialchars($val)));
					$subfield_node->setAttribute("code",utf8_for_xml(htmlspecialchars($subfield)));
					$data[$value['Tag']]['size'] = sizeof($data[$value['Tag']]['SubRuasField']);					
				 }			
	}
	/* echo "<pre>";
			print_r($data);
			die;
	 */
	/* foreach ($data as $keyData => $valueData){
	echo "<pre>";
			print_r($data);
			die;
		
	} */
	
	$datafield = $outputObj->addChild($oai_node,"datafield");			
	$datafield->setAttribute("tag","856");
	$datafield->setAttribute("ind1","4");
	$datafield->setAttribute("ind2","0");
	$subfield_node = $outputObj->addChild($datafield,"subfield",$urlDetail."?id=".$identifier);
	$subfield_node->setAttribute("code","u");

	foreach ($cover as $key => $value) {

		$datafield = $outputObj->addChild($oai_node,"datafield");			
		$datafield->setAttribute("tag","856");
		$datafield->setAttribute("ind1","4");
		$datafield->setAttribute("ind2","0");
		$subfield_node = $outputObj->addChild($datafield,"subfield",str_replace("opac","uploaded_files",$url)."sampul_koleksi/original/".DirectoryHelpers::GetDirWorksheet($value['Worksheet_id']).'/'.$value['CoverURL']);
		$subfield_node->setAttribute("code","u");		
	}

	foreach ($files as $key => $value) {

		$datafield = $outputObj->addChild($oai_node,"datafield");			
		$datafield->setAttribute("tag","856");
		$datafield->setAttribute("ind1","4");
		$datafield->setAttribute("ind2","0");
		$subfield_node = $outputObj->addChild($datafield,"subfield",str_replace("opac","uploaded_files",$url).$digitalCol[$key]['path']);
		$subfield_node->setAttribute("code","u");		
	}

	}


function get_record ($identifier, $db){
	
	$query = 'select cr.ID as ruasID, cr.CatalogID,cr.Tag,cr.Indicator1,cr.Indicator2,csr.SubRuas SubRuasField,cr.Value ruas,csr.Value subruas 
				from catalog_ruas cr 
				left join catalog_subruas csr on  cr.id = csr.RuasID where cr.CatalogID ='.$identifier;
	$res = $db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$r = $res->execute();
 	if ($r===false) {
		if (SHOW_QUERY_ERROR) {
			echo __FILE__.','.__LINE__."<br />";
			echo "Query: $query<br />\n";
			print_r($db->errorInfo());
			exit();
		} else {
			return array();
		}		
	} else {
		$record = $res->fetchALL(PDO::FETCH_ASSOC);
		return $record;
	}

}


function get_files($identifier, $db) {
	
	$query = 'select * from catalogfiles where IsPublish <> 2 And Catalog_id ='.$identifier;
	$res = $db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$r = $res->execute();
 	if ($r===false) {
		if (SHOW_QUERY_ERROR) {
			echo __FILE__.','.__LINE__."<br />";
			echo "Query: $query<br />\n";
			print_r($db->errorInfo());
			exit();
		} else {
			return array();
		}		
	} else {
		$record = $res->fetchALL(PDO::FETCH_ASSOC);
		return $record;
	}
}
function get_cover($identifier, $db) {
	
	$query = 'SELECT CoverURL,Worksheet_id FROM catalogs where ID ='.$identifier.' and CoverURL is not null and CoverURL <> \'\''; 
	$res = $db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$r = $res->execute();
 	if ($r===false) {
		if (SHOW_QUERY_ERROR) {
			echo __FILE__.','.__LINE__."<br />";
			echo "Query: $query<br />\n";
			print_r($db->errorInfo());
			exit();
		} else {
			return array();
		}		
	} else {
		$record = $res->fetchALL(PDO::FETCH_ASSOC);
		return $record;
	}
}

function utf8_for_xml($string)
{
    return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
}
