<?php
namespace console\controllers;

use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\FileHelper;
use Yii;


/*
 * Clean assets
 *
 * php yii clean-assets
 */
class CleanAssetsController extends Controller{

	public $dry_run = false;
    public $verbose = false;
    public $silent = false;
    /*
     * nbr asset dirs to keep; might be one or more for app, one for toolbar, etc
     */
    public $keep = 0;
    public $asset_dir = 'assets';
    public $dirAssets = [];

	public function actionIndex()
    {
    	$this->dirAssets = $this->getDirAssets();
    	$arrlength = count($this->dirAssets);
    	

        foreach (Yii::$app->requestedParams as $param) {
            if (stripos($param, 'dry-run') !== false || stripos($param, 'dryrun') !== false) {
                $this->dry_run = true;
            } else if (stripos($param, 'verbose') !== false) {
                $this->verbose = true;
            } else if (stripos($param, 'silent') !== false) {
                $this->silent = true;
            } else if (stripos($param, 'keep=') !== false) {
                $keep = substr($param, strpos($param, '=') + 1);
                $this->keep = (int)$keep;
            }
        }
        
        for($x = 0; $x < $arrlength; $x++) {
		    $this->echo_msg( $this->dirAssets[$x]);
		    $this->echo_msg('Checking '.$this->dirAssets[$x].'/ to remove old caches .. ');
	        $nbr_cleaned = self::cleanAssetDir($this->dirAssets[$x]);
	        $this->echo_msg('Done. Removed '.$nbr_cleaned.' '.$this->dirAssets[$x].'/ cache'.($nbr_cleaned == 1 ? '' : 's'));
		}

       
        return 0;
    }

    private function echo_msg($msg, $show=true)
    {
        if (!$this->silent && ($this->dry_run || $this->verbose || $show)) {
            echo $msg."\n";
        }
    }
    
    /*
     * remove prior asset dirs
     *
     * @return void
     */
    public function cleanAssetDir($dirAsset)
    {
        $now = time();
        $asset_temp_dirs = glob($dirAsset . '/*' , GLOB_ONLYDIR);
        // check if less than want to keep
        if (count($asset_temp_dirs) <= $this->keep) {
            return 0;
        }
        // get all dirs and sort by modified
        $modified = [];
        foreach ($asset_temp_dirs as $asset_temp_dir) {
            $modified[$asset_temp_dir] = filemtime($asset_temp_dir);
        }
        asort($modified);
        $nbr_dirs = count($modified);
        // keep last dirs
        for ($i = min($nbr_dirs, $this->keep); $i > 0; $i--) {
            array_pop($modified);
        }
        if ($this->dry_run) {
            $msg_try = 'would have ';
        } else {
            $msg_try = '';
        }

        // remove dirs
        foreach ($modified as $dir => $mod) {
            $this->echo_msg($msg_try.'removed '.$dir.', last modified '.Yii::$app->formatter->asDatetime($mod));
            if (!$this->dry_run) {
                FileHelper::removeDirectory($dir);
            }
        }
        return $this->dry_run ? 0 : $nbr_dirs;
    }

   public static function getDirAssets()
   {
   		return [
   	// 		 'assets',
			 // 'backend/assets',
   	// 		 'article/assets',
   	// 		 'bacaditempat/assets',
   	// 		 'digitalcollection/assets',
   	// 		 'guestbook/assets',
   	// 		 'keanggotaan/assets',
   	// 		 'opac/assets',
			 // 'peminjamanmandiri/assets',
			 // 'pengembalianmandiri/assets',

        dirname(Yii::getAlias('@backend')).'/assets',
        Yii::getAlias('@backend').'/assets',
        Yii::getAlias('@article').'/assets',
        Yii::getAlias('@bacaditempat').'/assets',
        Yii::getAlias('@digitalcollection').'/assets',
        Yii::getAlias('@guestbook').'/assets',
        Yii::getAlias('@keanggotaan').'/assets',
        Yii::getAlias('@opac').'/assets',
        Yii::getAlias('@peminjamanmandiri').'/assets',
        Yii::getAlias('@pengembalianmandiri').'/assets',
   			 // RUNTIME 
			 // 'backend/runtime',
			 // 'article/runtime',
   	// 		 'bacaditempat/runtime',   			 
   	// 		 'digitalcollection/runtime',
   	// 		 'frontend/runtime',
   	// 		 'guestbook/runtime',
   	// 		 'keanggotaan/runtime',
   	// 		 'opac/runtime',
   	// 		 'console/runtime',
			 // 'peminjamanmandiri/runtime', 
			 // 'pengembalianmandiri/runtime',

        Yii::getAlias('@backend').'/runtime',
        Yii::getAlias('@article').'/runtime',
        Yii::getAlias('@bacaditempat').'/runtime',
        Yii::getAlias('@digitalcollection').'/runtime',
        Yii::getAlias('@guestbook').'/runtime',
        Yii::getAlias('@keanggotaan').'/runtime',
        Yii::getAlias('@opac').'/runtime',
        Yii::getAlias('@peminjamanmandiri').'/runtime',
        Yii::getAlias('@pengembalianmandiri').'/runtime', 			 
   		];
   }
}