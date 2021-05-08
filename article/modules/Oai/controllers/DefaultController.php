<?php

namespace article\modules\Oai\controllers;

use Yii;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\web\Request;

use \PDO;

class DefaultController extends Controller
{
    public function actionIndex()
    {
		/**
		 * An array for collecting erros which can be reported later. It will be checked before a new action is taken.
		 */
		$errors = array();


		/**
		 * Supported attributes associate to verbs.
		 */
		$attribs = array ('from', 'identifier', 'metadataPrefix', 'set', 'resumptionToken', 'until');

		if (in_array($_SERVER['REQUEST_METHOD'],array('GET','POST'))) {
				$args = $_REQUEST;
		} else {
			$errors[] = oai_error('badRequestMethod', $_SERVER['REQUEST_METHOD']);
			$GLOBALS['errors'] = $errors;
		}

		$basePathOai =  \Yii::$app->basePath .'/modules/Oai/';

		require_once($basePathOai . 'oaidp-config.php');

		$GLOBALS['METADATAFORMATS'] = $METADATAFORMATS;
		require_once($basePathOai . 'oaidp-util.php');



		// Always using htmlentities() function to encodes the HTML entities submitted by others.
		// No one can be trusted.
		foreach ($args as $key => $val) {
			$checking = htmlspecialchars(stripslashes($val));
			if (!is_valid_attrb($checking)) {
				$errors[] = oai_error('badArgument', $checking);
				$GLOBALS['errors'] = $errors;
			} else {$args[$key] = $checking; }
		}
		if (!empty($errors)) {	
			$GLOBALS['errors'] = $errors;
			$GLOBALS['args'] = $args;
			$GLOBALS['compress'] = $compress;
			oai_exit(); 
		}

		foreach($attribs as $val) {
			unset($$val);
		}



		// Create a PDO object
		try {

				$db = new PDO($DSN, $DB_USER, $DB_PASSWD, $DB_OPTION);
		} catch (PDOException $e) {
		    exit('Connection failed: ' . $e->getMessage());
		}

		// For generic usage or just trying:
		// require_once('xml_creater.php');
		// In common cases, you have to implement your own code to act fully and correctly.
		require_once($basePathOai . 'ands_tpa.php');

		// Default, there is no compression supported
		$compress = FALSE;
		if (isset($compression) && is_array($compression)) {
			if (in_array('gzip', $compression) && ini_get('output_buffering')) {
				$compress = TRUE;
			}
		}

		if (SHOW_QUERY_ERROR) {
			echo "Args:\n"; print_r($args);
		}


		if (isset($args['verb'])) {
			switch ($args['verb']) {

				case 'Identify':
					// we never use compression in Identify
					$compress = FALSE;
					if(count($args)>1) {
						foreach($args as $key => $val) {
							if(strcmp($key,"verb")!=0) {
								$errors[] = oai_error('badArgument', $key, $val);
								$GLOBALS['errors'] = $errors;
							}	
						}
					}
					$GLOBALS['args'] = $args;
					if (empty($errors)) include $basePathOai.'identify.php';

					break;

				case 'ListMetadataFormats':
					$checkList = array("ops"=>array("identifier"));
					$GLOBALS['args'] = $args;
					checkArgs($args, $checkList);
					if (empty($errors)) include $basePathOai.'listmetadataformats.php';
					break;

				case 'ListSets':
					if(isset($args['resumptionToken']) && count($args) > 2) {
							$errors[] = oai_error('exclusiveArgument');
							$GLOBALS['errors'] = $errors;
					}
					$checkList = array("ops"=>array("resumptionToken"));
					$GLOBALS['args'] = $args;
					checkArgs($args, $checkList);
					if (empty($errors)) include $basePathOai.'listsets.php';
					break;

				case 'GetRecord':
					$checkList = array("required"=>array("metadataPrefix","identifier"));
					$GLOBALS['args'] = $args;
					checkArgs($args, $checkList);
					if (empty($errors)) include $basePathOai.'getrecord.php';
					break;

				case 'ListIdentifiers':
				case 'ListRecords':
					if(isset($args['resumptionToken'])) {
						if (count($args) > 2) {
							$errors[] = oai_error('exclusiveArgument');
							$GLOBALS['errors'] = $errors;
						}
						$checkList = array("ops"=>array("resumptionToken"));
					} else {
						$checkList = array("required"=>array("metadataPrefix"),"ops"=>array("from","until","set"));
					}
					$GLOBALS['args'] = $args;
					checkArgs($args, $checkList);
					if (empty($errors)) include $basePathOai.'listrecords.php';
					

					break;
				default:
					// we never use compression with errors
					$compress = FALSE;
					$errors[] = oai_error('badVerb', $args['verb']);
					$GLOBALS['errors'] = $errors;
			} /*switch */
		} else {
			$errors[] = oai_error('noVerb');
			;
			$GLOBALS['errors'] = $errors;
		}


		if (!empty($errors))
		{	
			$GLOBALS['errors'] = $errors;
			$GLOBALS['args'] = $args;
			$GLOBALS['compress'] = $compress;
			oai_exit(); 
		}

		if ($compress) {
			ob_start('ob_gzhandler');
		}


			/**
			 * change for Yii
			 */
			
			Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
		    $headers = Yii::$app->response->headers;
		    $headers->add('Content-Type', 'text/xml');

		    /**
			 * end change for Yii
			 */

			header(CONTENT_TYPE);

			if(isset($outputObj)) {
				$outputObj->display();
			} else {
				exit("There is a bug in codes");
			}

			if ($compress) {
				ob_end_flush();
			}
	}
}

?>