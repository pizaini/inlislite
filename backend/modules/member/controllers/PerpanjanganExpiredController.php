<?php

namespace backend\modules\member\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\ErrorException;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;



//MODEL
use common\models\MemberPerpanjanganSearch;
use common\models\Members;
use common\models\Membersonline;
use common\models\MemberSearch;
use common\models\PelanggaranSearch;
use common\models\MembersForm;
use common\models\JenisAnggota;

// Component
use common\components\Helpers;
use common\components\MemberHelpers;
use leandrogehlen\querybuilder\Translator;




/**
 * MemberController implements the CRUD actions for Members model.
 * @author Henry <alvin_vna@yahoo.com>
 */
class PerpanjanganExpiredController extends Controller
{
	public function actionExtend()
    {
    	$post = Yii::$app->request->post();
    	// print_r($post);die;	
    	if(isset($post['action']) && isset($post['row_id'])){
	    	$rowid = $post['row_id'];
		    $member_id = implode(',', $rowid);
		    // echo '<pre>';print_r($member_id);
	           $sql = 'UPDATE members
						LEFT JOIN jenis_anggota ON
						    members.JenisAnggota_id = jenis_anggota.id
						    SET members.EndDate = ADDDATE(members.EndDate , jenis_anggota.MasaBerlakuAnggota) WHERE members.ID IN ('. $member_id .')';
	            
	            $command = Yii::$app->db->createCommand($sql);
	            $command->execute();

	            if($command){
	            	$msg =  yii::t('app','Perpanjangan Berhasil.');
	            }else{
	            	throw new \yii\web\HttpException(404, yii::t('app','Harap pilih anggota.'));
	            }
        }else{
            throw new \yii\web\HttpException(404, yii::t('app','Harap pilih anggota.'));
        }
        return $msg;
    }
}