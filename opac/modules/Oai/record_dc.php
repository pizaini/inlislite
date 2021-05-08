<?php
/** \file
 * \brief Definition of Dublin Core handler.
 *
 * It is not working as it does not provide any content to the metadata node. It only included
 * to demonstrate how a new metadata can be supported. For a working
 * example, please see record_rif.php.
 *
 * @author: Ismail Fahmi, ismail.fahmi@gmail.com
 *
 * \sa oaidp-config.php 
	*/

use yii\helpers\Url; 
use common\components\DirectoryHelpers;
use common\components\OpacHelpers;	

function create_metadata($outputObj, $cur_record, $identifier, $setspec, $db) {
		$metadata_node = $outputObj->create_metadata($cur_record);
		
	$url=Yii::$app->urlManager->createAbsoluteUrl('');
	$urlDetail=Yii::$app->urlManager->createAbsoluteUrl('detail-opac');

    $oai_node = $outputObj->addChild($metadata_node, "oai_dc:dc");
	$oai_node->setAttribute("xmlns:oai_dc","http://www.openarchives.org/OAI/2.0/oai_dc/");
	$oai_node->setAttribute("xmlns:dc","http://purl.org/dc/elements/1.1/");
	$oai_node->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
	$oai_node->setAttribute("xsi:schemaLocation", "http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd");

	$record 	= get_record($identifier, $db);
	$tag_detail = get_tag_detail($identifier, $db);
	$tag_detail_auth = get_tag_detail_author($identifier, $db);
	$tag_detail_subject = get_tag_detail_subject($identifier, $db);
	$tag_detail_contributor = get_tag_detail_contributor($identifier, $db);
	$cover 		= get_cover($identifier, $db);
	$files 		= get_files($identifier, $db);
	$digitalCol = OpacHelpers::getDigitalCollectionDir($identifier);
	
	
	if (!empty($record['Title'])) 				$outputObj->addChild($oai_node,'dc:title', htmlspecialchars($record['Title']));
	
	foreach ($tag_detail_auth as $tag_detail_auth){
		$data_100 = '';
		switch($tag_detail_auth['Tag']){
			case '100' :
			case '110' :
			case '111' :
				$data_100 = $tag_detail_auth['Val'];
				break;
		}
		if (!empty($data_100)) 			$outputObj->addChild($oai_node,'dc:creator', htmlspecialchars($data_100));
	}
	
	foreach ($tag_detail_subject as $tag_detail_subject){
		if (!empty($tag_detail_subject['Val'])) 			$outputObj->addChild($oai_node,'dc:subject', htmlspecialchars($tag_detail_subject['Val']));
	}
	
	if (!empty($record['Publisher'])) 			$outputObj->addChild($oai_node,'dc:publisher', htmlspecialchars($record['Publisher']));
	if (!empty($record['PublishYear'])) 			$outputObj->addChild($oai_node,'dc:publishYear', htmlspecialchars($record['PublishYear']));
	if (!empty($record['PublishLocation'])) 			$outputObj->addChild($oai_node,'dc:coverage', htmlspecialchars($record['PublishLocation']));
	if (!empty($record['PhysicalDescription'])) 			$outputObj->addChild($oai_node,'dc:relation', htmlspecialchars($record['PhysicalDescription']));
	
	foreach ($tag_detail as $tag_detail){
		if (!empty($tag_detail['Val'])) 			$outputObj->addChild($oai_node,'dc:description', htmlspecialchars($tag_detail['Val']));
	}
	
	foreach ($tag_detail_contributor as $tag_detail_contributor){
		if (!empty($tag_detail_contributor['Val'])) 			$outputObj->addChild($oai_node,'dc:contributor', htmlspecialchars($tag_detail_contributor['Val']));
	}
	
	if (!empty($record['Languages'])) 			$outputObj->addChild($oai_node,'dc:language', htmlspecialchars($record['Languages']));
	if (!empty($record['Name'])) 		$outputObj->addChild($oai_node,'dc:type', htmlspecialchars($record['Name']));
	if (!empty($record['ID'])) 		$outputObj->addChild($oai_node,'dc:source', htmlspecialchars($urlDetail."?id=".$record['ID']));
	
	foreach ($cover as $cover){
		if (!empty($cover['CoverURL'])) 			$outputObj->addChild($oai_node,'dc:identifier', htmlspecialchars(str_replace("opac","uploaded_files",$url)."sampul_koleksi/original/".DirectoryHelpers::GetDirWorksheet($cover['Worksheet_id']).'/'.$cover['CoverURL']));
	}
	
	foreach ($files as $files){
		if (!empty($files['FileURL'])) 					$outputObj->addChild($oai_node,'dc:identifier', str_replace("article","uploaded_files",$url).$digitalCol[$key]['path'].htmlspecialchars($files['FileURL']));
	}	
}

function get_record ($identifier, $db){
	
	// $query = 'SELECT * FROM t_oai_dc WHERE identifier=' .$identifier;
	$query = 'SELECT catalogs.*, worksheets.Name FROM catalogs INNER JOIN worksheets ON catalogs.Worksheet_id = worksheets.ID WHERE catalogs.id=' .$identifier;

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
		$record = $res->fetch(PDO::FETCH_ASSOC);
		return $record;
	}
}

function get_tag_detail_author($identifier, $db) {
	
	$query = 'SELECT 
				B.Tag AS \'Tag\',
				B.Indicator1 AS \'Ind1\',
				B.Indicator2 AS \'Ind2\',
				B.Value AS \'Value\',
				C.SubRuas AS \'SubRuas\',
				C.Value AS \'Val\'
				FROM
				catalogs A
				Left Join catalog_ruas B ON A.id = B.CatalogId
				Left Join catalog_subruas C ON B.ID = C.RuasID
				where B.Tag IN (100,110,111)
				AND A.id='.$identifier;
	
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
		$records = array();
		$hasNext = 1;
		while($hasNext){
			$record = $res->fetch(PDO::FETCH_ASSOC);
			if ($record){
				array_push($records, $record);
			} else {
				$hasNext = 0;
			}
		}

		return $records;
	}
}

function get_tag_detail_subject($identifier, $db) {
	
	$query = 'SELECT 
				B.Tag AS \'Tag\',
				B.Indicator1 AS \'Ind1\',
				B.Indicator2 AS \'Ind2\',
				B.Value AS \'Value\',
				C.SubRuas AS \'SubRuas\',
				C.Value AS \'Val\'
				FROM
				catalogs A
				Left Join catalog_ruas B ON A.id = B.CatalogId
				Left Join catalog_subruas C ON B.ID = C.RuasID
				where B.Tag LIKE "6%"
				AND A.id='.$identifier;
	
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
		$records = array();
		$hasNext = 1;
		while($hasNext){
			$record = $res->fetch(PDO::FETCH_ASSOC);
			if ($record){
				array_push($records, $record);
			} else {
				$hasNext = 0;
			}
		}

		return $records;
	}
}

function get_tag_detail_contributor($identifier, $db) {
	
	$query = 'SELECT 
				B.Tag AS \'Tag\',
				B.Indicator1 AS \'Ind1\',
				B.Indicator2 AS \'Ind2\',
				B.Value AS \'Value\',
				C.SubRuas AS \'SubRuas\',
				C.Value AS \'Val\'
				FROM
				catalogs A
				Left Join catalog_ruas B ON A.id = B.CatalogId
				Left Join catalog_subruas C ON B.ID = C.RuasID
				where B.Tag LIKE "7%"
				AND A.id='.$identifier;
	
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
		$records = array();
		$hasNext = 1;
		while($hasNext){
			$record = $res->fetch(PDO::FETCH_ASSOC);
			if ($record){
				array_push($records, $record);
			} else {
				$hasNext = 0;
			}
		}

		return $records;
	}
}

function get_tag_detail($identifier, $db) {
	
	$query = 'SELECT 
				B.Tag AS \'Tag\',
				B.Indicator1 AS \'Ind1\',
				B.Indicator2 AS \'Ind2\',
				B.Value AS \'Value\',
				C.SubRuas AS \'SubRuas\',
				C.Value AS \'Val\'
				FROM
				catalogs A
				Left Join catalog_ruas B ON A.id = B.CatalogId
				Left Join catalog_subruas C ON B.ID = C.RuasID
				where B.Tag IN (500)
				AND A.id='.$identifier;
	
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
		$records = array();
		$hasNext = 1;
		while($hasNext){
			$record = $res->fetch(PDO::FETCH_ASSOC);
			if ($record){
				array_push($records, $record);
			} else {
				$hasNext = 0;
			}
		}

		return $records;
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
