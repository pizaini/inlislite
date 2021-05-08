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
    $oai_node = $outputObj->addChild($metadata_node, "oai_marc");
    $oai_node->setAttribute("status","n");
    $oai_node->setAttribute("type","a");
    $oai_node->setAttribute("level","m");
    $oai_node->setAttribute("encLvl","7");
    $oai_node->setAttribute("catForm","a");

	$oai_node->setAttribute("xmlns","http://www.openarchives.org/OAI/1.1/oai_marc");
	$oai_node->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
	$oai_node->setAttribute("xsi:schemaLocation", "http://www.openarchives.org/OAI/1.1/oai_marc http://www.openarchives.org/OAI/1.1/oai_marc.xsd");

	$record 	= get_record($identifier, $db);
	$cover 		= get_cover($identifier, $db);
	$files 		= get_files($identifier, $db);
	$digitalCol = OpacHelpers::getDigitalCollectionDir($identifier);

	

	foreach ($record as $key => $value) {

		switch ($value['Tag']) {
			case 001:
				
				$fixfield_node = $outputObj->addChild($oai_node,"fixfield",htmlspecialchars($value['ruas']));
				$fixfield_node->setAttribute("id","001");
				break;
			
			case 002:
				$fixfield_node = $outputObj->addChild($oai_node,"fixfield",htmlspecialchars($value['ruas']));
				$fixfield_node->setAttribute("id","002");
				break;
			case 003:
				$fixfield_node = $outputObj->addChild($oai_node,"fixfield",htmlspecialchars($value['ruas']));
				$fixfield_node->setAttribute("id","003");
				break;
			case 004:
				$fixfield_node = $outputObj->addChild($oai_node,"fixfield",htmlspecialchars($value['ruas']));
				$fixfield_node->setAttribute("id","004");
				break;
			case 005:
				$fixfield_node = $outputObj->addChild($oai_node,"fixfield",htmlspecialchars($value['ruas']));
				$fixfield_node->setAttribute("id","005");
				break;
			case 006:
				$fixfield_node = $outputObj->addChild($oai_node,"fixfield",htmlspecialchars($value['ruas']));
				$fixfield_node->setAttribute("id","006");
				break;
			case 007:
				$fixfield_node = $outputObj->addChild($oai_node,"fixfield",htmlspecialchars($value['ruas']));
				$fixfield_node->setAttribute("id","007");
				break;
			case 008:
				$fixfield_node = $outputObj->addChild($oai_node,"fixfield",htmlspecialchars($value['ruas']));
				$fixfield_node->setAttribute("id","008");
				break;
			default:

			$val= isset($value['SubRuasField']) ? $value['subruas'] : $value['ruas'] ;
			$ind1= isset($value['Indicator1']) ? $value['Indicator1'] : "#" ;
			$ind2= isset($value['Indicator2']) ? $value['Indicator2'] : "#" ;
			$subfield= isset($value['SubRuasField']) ? $value['SubRuasField'] : "#" ;
			$varfield_node = $outputObj->addChild($oai_node,"varfield");			
			$varfield_node->setAttribute("id",$value['Tag']);
			$varfield_node->setAttribute("i1",$ind1);
			$varfield_node->setAttribute("i2",$ind2);
			$subfield_node = $outputObj->addChild($varfield_node,"subfield",htmlspecialchars($val));
			$subfield_node->setAttribute("label",htmlspecialchars($subfield));

			break;							
		}
	}
	$varfield_node = $outputObj->addChild($oai_node,"varfield");			
	$varfield_node->setAttribute("id","856");
	$varfield_node->setAttribute("i1","4");
	$varfield_node->setAttribute("i2","0");
	$subfield_node = $outputObj->addChild($varfield_node,"subfield",$urlDetail."?id=".$identifier);
	$subfield_node->setAttribute("code","u");

	foreach ($cover as $key => $value) {

	$varfield_node = $outputObj->addChild($oai_node,"varfield");			
	$varfield_node->setAttribute("id","856");
	$varfield_node->setAttribute("i1","4");
	$varfield_node->setAttribute("i2","0");
		$subfield_node = $outputObj->addChild($varfield_node,"subfield",str_replace("opac","uploaded_files",$url)."sampul_koleksi/original/".DirectoryHelpers::GetDirWorksheet($value['Worksheet_id']).'/'.$value['CoverURL']);
		$subfield_node->setAttribute("code","u");		
	}

	foreach ($files as $key => $value) {

	$varfield_node = $outputObj->addChild($oai_node,"varfield");			
	$varfield_node->setAttribute("id","856");
	$varfield_node->setAttribute("i1","4");
	$varfield_node->setAttribute("i2","0");
		$subfield_node = $outputObj->addChild($varfield_node,"subfield",str_replace("opac","uploaded_files",$url).$digitalCol[$key]['path']);
		$subfield_node->setAttribute("code","u");		
	}

	}



function get_record ($identifier, $db){
	
	$query = 'select cr.CatalogID,cr.Tag,cr.Indicator1,cr.Indicator2,csr.SubRuas SubRuasField,cr.Value ruas,csr.Value subruas 
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

	