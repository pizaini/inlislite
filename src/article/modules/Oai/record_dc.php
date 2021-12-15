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
use common\components\OpacHelpers;

function create_metadata($outputObj, $cur_record, $identifier, $setspec, $db) {
		$metadata_node = $outputObj->create_metadata($cur_record);
	$url=Yii::$app->urlManager->createAbsoluteUrl('');

    $oai_node = $outputObj->addChild($metadata_node, "oai_dc:dc");
	$oai_node->setAttribute("xmlns:oai_dc","http://www.openarchives.org/OAI/2.0/oai_dc/");
	$oai_node->setAttribute("xmlns:dc","http://purl.org/dc/elements/1.1/");
	$oai_node->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
	$oai_node->setAttribute("xsi:schemaLocation", "http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd");

	$record 	= get_record($identifier, $db);
	$tag_detail = get_tag_detail($identifier, $db);
	$files 		= get_files($identifier, $db);
	$digitalCol = OpacHelpers::getDigitalCollectionArticleDir($identifier);
	// echo'<pre>';print_r($digitalCol);die;
	
	if (!empty($record['Title'])) 				$outputObj->addChild($oai_node,'dc:title', htmlspecialchars($record['Title']));
	if (!empty($record['dc_creator'])) 				$outputObj->addChild($oai_node,'dc:creator', htmlspecialchars($record['dc_creator']));
	if (!empty($record['dc_subject'])) 				$outputObj->addChild($oai_node,'dc:subject', htmlspecialchars($record['dc_subject']));
	if (!empty($record['dc_publisher'])) 			$outputObj->addChild($oai_node,'dc:publisher', htmlspecialchars($record['dc_publisher']));
	if (!empty($record['dc_publishYear'])) 			$outputObj->addChild($oai_node,'dc:publishYear', htmlspecialchars($record['dc_publishYear']));
	if (!empty($record['dc_description_1'])) 		$outputObj->addChild($oai_node,'dc:description', htmlspecialchars(strip_tags(html_entity_decode($record['dc_description_1']))));
	
	foreach ($tag_detail as $tag_detail){
		switch ($tag_detail['article_field']) {
			case 'Kreator':
				if (!empty($tag_detail['value'])) 			$outputObj->addChild($oai_node,'dc:creator', htmlspecialchars($tag_detail['value']));
			break;
			case 'Subjek':
				if (!empty($tag_detail['value'])) 			$outputObj->addChild($oai_node,'dc:subject', htmlspecialchars($tag_detail['value']));
			break;
			case 'Kontributor':
				if (!empty($tag_detail['value'])) 			$outputObj->addChild($oai_node,'dc:contributor', htmlspecialchars($tag_detail['value']));
			break;
		}
	}
	
	if (!empty($record['Abstract'])) 		$outputObj->addChild($oai_node,'dc:description', htmlspecialchars($record['Abstract']));
	if (!empty($record['dc_description_3'])) 		$outputObj->addChild($oai_node,'dc:description', htmlspecialchars($record['dc_description_3']));
	if (!empty($record['dc_description_4'])) 		$outputObj->addChild($oai_node,'dc:description', htmlspecialchars($record['dc_description_4']));
	if (!empty($record['dc_date'])) 				$outputObj->addChild($oai_node,'dc:date', htmlspecialchars($record['dc_date']));
	if (!empty($record['dc_format'])) 				$outputObj->addChild($oai_node,'dc:format', htmlspecialchars($record['dc_format']));
	if (!empty($record['dc_language'])) 			$outputObj->addChild($oai_node,'dc:language', htmlspecialchars($record['dc_language']));
	if (!empty($record['dc_identifier_1'])) 		$outputObj->addChild($oai_node,'dc:identifier', htmlspecialchars($record['dc_identifier_1']));
	if (!empty($record['dc_identifier_2'])) 		$outputObj->addChild($oai_node,'dc:identifier', htmlspecialchars($record['dc_identifier_2']));
	if (!empty($record['dc_identifier_3'])) 		$outputObj->addChild($oai_node,'dc:identifier', htmlspecialchars($record['dc_identifier_3']));
	if (!empty($record['dc_identifier_4'])) 		$outputObj->addChild($oai_node,'dc:identifier', htmlspecialchars($record['dc_identifier_4']));
	
	foreach ($files as $key => $files){
		// echo'<pre>';print_r($files);die;
		if (!empty($files['FileURL'])) 					$outputObj->addChild($oai_node,'dc:identifier', str_replace("article","uploaded_files",$url).$digitalCol[$key]['path'].htmlspecialchars($files['FileURL']));
		
	}
		
}

function get_record ($identifier, $db){
	
	$query = 'SELECT * FROM serial_articles WHERE id=' .$identifier;

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

function get_tag_detail($identifier, $db) {
	
	$query = 'SELECT 
				 B.* FROM serial_articles A
				LEFT JOIN serial_articles_repeatable B ON A.`id` = B.`serial_article_ID` 
				where A.id='.$identifier;
				
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

function get_files($identifier, $db) {
	
	$query = 'SELECT 
				B.*
				FROM
				serial_articles A
				Left Join serial_articlefiles B ON A.id = B.Articles_id
				WHERE
				A.ID='.$identifier;
				
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
