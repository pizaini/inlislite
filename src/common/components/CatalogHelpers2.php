<?php

namespace common\components;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fields;
use common\models\Catalogs;
use common\models\Cardformats;
use common\models\CatalogRuas;
use common\models\CatalogSubruas;
use common\models\CatalogCardTemplate;
use common\models\AuthHeader;

class CatalogHelpers2
{
	public static function createTaglistFromCatalog($CatalogId)
	{
	    //$modelcatruas = CatalogRuas::find()->where(['and','CatalogId = '.$CatalogId,['not in','Tag',array('001','035','990')]])->all();

		//$modelcatruas = CatalogRuas::find()->where(['and','CatalogId = '.$CatalogId,['not in','Tag',array('990')]])->all();

        $taglist=array();
		$modelcatruas = CatalogRuas::find()->where(['CatalogId'=>$CatalogId])->all();
	    $index=0;
	    foreach ($modelcatruas as $data) {
	        $dataField = Fields::getByTag((string)$data->Tag);
	        $taglist[$index]["id"] = $dataField->ID;
			$taglist[$index]["name"] = $dataField->Name;
			$taglist[$index]["tag"] = $data->Tag;
			$taglist[$index]["ind1"] = $data->Indicator1;
			$taglist[$index]["ind2"] = $data->Indicator2;
			$taglist[$index]["value"] = $data->Value;
			$taglist[$index]["mandatory"] = $dataField->Mandatory;
			$taglist[$index]["length"] = $dataField->Length;
			$taglist[$index]["enabled"] = $dataField->Enabled;
			$taglist[$index]["iscustomable"] = $dataField->IsCustomable;
			$taglist[$index]["fixed"] = $dataField->Fixed;
			$taglist[$index]["repeatable"] = $dataField->Repeatable;
	        $index++;
	    }

	    return $taglist;
            
    }

}