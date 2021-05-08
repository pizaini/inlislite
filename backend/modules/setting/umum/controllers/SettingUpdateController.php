<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2018
 * @version 1.0.0
 * @author Rico <rico.ulul@gmail.com>
 */
namespace backend\modules\setting\umum\controllers;

use Yii;
use yii\base\DynamicModel;
use common\components\OpacHelpers;
use yii\helpers\FileHelper;

class SettingUpdateController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $model = new DynamicModel([
            'IsActivatingImportingAuthorityData',
            'AuthorityDataLastDate',
            'IsActivatingKII',
            'KIICode',
            'KIILastUploadDate',            

        ]);
        $model->addRule([
            'IsActivatingImportingAuthorityData',
            'IsActivatingKII',], 'required');

        $model->IsActivatingImportingAuthorityData = Yii::$app->config->get('IsActivatingImportingAuthorityData');
        $model->AuthorityDataLastDate = Yii::$app->config->get('AuthorityDataLastDate');

        $model->IsActivatingKII = Yii::$app->config->get('IsActivatingKII');
        $model->KIICode = Yii::$app->config->get('KIICode');
        $model->KIILastUploadDate = Yii::$app->config->get('KIILastUploadDate');

        

        if ($model->load(Yii::$app->request->post())) 
        {
            if ($model->validate()) 
            {
                $date=date('Y-m-d H:i:s');
                Yii::$app->config->set('IsActivatingImportingAuthorityData', Yii::$app->request->post('DynamicModel')['IsActivatingImportingAuthorityData']);
                Yii::$app->config->set('IsActivatingKII', Yii::$app->request->post('DynamicModel')['IsActivatingKII']);
                
                Yii::$app->config->set('AuthorityDataLastDate', $date);
                Yii::$app->config->set('KIILastUploadDate', $date); 
                
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
            }
            else
            {

                Yii::$app->getSession()->setFlash('failed', [
                    'type' => 'error',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Failed Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
            }
            return $this->redirect(['index']);
        }
        else
        {
        return $this->render('index',[
          'model' => $model,]);
        }
    }

    public function RmdirRuntime()
    {
        $dir = Yii::getAlias('@app/runtime');
        FileHelper::removeDirectory($dir);
    }

    public function actionSetImportAuthority()
    {
        $data = Yii::$app->request->post();
        $param = $data['value'];
        Yii::$app->config->set($param, $data[$param]);
        
        Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','Success Save'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
        return print_r($data);
        // return $this->redirect('index');
    }

    public function actionSinkronisasiData(){
        if($_POST['tipe'] == 'up'){
            // update last sinkronisasi server
            $upd_sync_local = Yii::$app->db->createCommand()->update("settingparameters", ["Value" => date('Y-m-d H:i:s')], 'Name = "SinkronisasiLocaltoServer"')->execute();
            $upd_sync_server = Yii::$app->db2->createCommand()->update("settingparameters", ["Value" => date('Y-m-d H:i:s')], 'Name = "SinkronisasiLocaltoServer"')->execute();

            $path = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
            // print_r($path);die;
            $start_php = dirname(dirname($_SERVER['MIBDIRS'])).'/php.exe';
            $exec = exec($start_php.' '.$path. '/yii synchronize/up-server');
        }else{
            // update last sinkronisasi server
            $upd_sync = Yii::$app->db2->createCommand()->update("settingparameters", ["Value" => date('Y-m-d H:i:s')], 'Name = "SinkronisasiServertoLocal"')->execute();

            $path = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
            // print_r($path);die;
            $start_php = dirname(dirname($_SERVER['MIBDIRS'])).'/php.exe';
            $exec = exec($start_php.' '.$path. '/yii synchronize/down-local');
            // print_r($exec);die;
        }
    }

    public function actionCleanAssets(){
        if($_POST){
            $path = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
        
            $start_php = dirname(dirname($_SERVER['MIBDIRS'])).'/php.exe';
            $exec = exec($path. '/yii clean-assets');
        }else{
            return $this->render('_cleanAssets');
        }
        
    }

    public function actionUpdate(){

        $err=[];
        $err2=[];
        //publikasi katalog
        try {
            $cekPublikasicatalog = OpacHelpers::columnExist('catalogs','Publikasi');
            if($cekPublikasicatalog == 0){

            $command = Yii::$app->db->createCommand("
                ALTER TABLE `catalogs` 
                ADD COLUMN `Publikasi` VARCHAR(700) NULL AFTER `PublishYear`;
    
                ALTER TABLE `catalogs` 
                ADD INDEX `catalogs_publikasi` (`Publikasi` ASC);
    
                UPDATE catalogs SET Publikasi = TRIM(CONCAT(PublishLocation, ' ', Publisher, ' ', PublishYear)) WHERE ID > 0;
    
            ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
        //publikasi quarantined
        try {
            $cekPublikasiQcatalog = OpacHelpers::columnExist('quarantined_catalogs','Publikasi');
            if($cekPublikasiQcatalog == 0){
            $command = Yii::$app->db->createCommand("
                ALTER TABLE `quarantined_catalogs` 
                ADD COLUMN `Publikasi` VARCHAR(700) NULL AFTER `PublishYear`;
    
                UPDATE quarantined_catalogs SET Publikasi = TRIM(CONCAT(PublishLocation, ' ', Publisher, ' ', PublishYear)) WHERE ID > 0;
    
    
            ")->execute();
            }

        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
        //logsdownload
        try {
            $command = Yii::$app->db->createCommand("
    
                ALTER TABLE logsdownload
                DROP FOREIGN KEY fk_logsDownload_catalogfiles;
    
                ALTER TABLE logsdownload ADD CONSTRAINT fk_logsDownload_catalogfiles FOREIGN KEY fk_logsDownload_catalogfiles (catalogfilesID)
                REFERENCES catalogfiles(ID)
                ON DELETE SET NULL
                ON UPDATE NO ACTION;
    
            ")->execute();

        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
            $err2=$e;
        }
        //issue #762 Collections & userloclibforcol
        try {

            $command = Yii::$app->db->createCommand("
    
                SET FOREIGN_KEY_CHECKS = 0;
                ALTER TABLE `collections` DROP FOREIGN KEY `collections_location`;
                ALTER TABLE `collections` DROP FOREIGN KEY `collections_location_library`;
                ALTER TABLE `collections` ADD CONSTRAINT `collections_location` FOREIGN KEY (`Location_id`) REFERENCES `locations` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
                ALTER TABLE `collections` ADD CONSTRAINT `collections_location_library` FOREIGN KEY (`Location_Library_id`) REFERENCES `location_library` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `userloclibforcol` DROP FOREIGN KEY `FK_userloclibforcol_loclib`;
                ALTER TABLE `userloclibforcol` ADD CONSTRAINT `FK_userloclibforcol_loclib` FOREIGN KEY (`LocLib_id`) REFERENCES `location_library` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
            ")->execute();
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }

        //issue #810 members cascade
        try {

            $command = Yii::$app->db->createCommand("
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_jenis_anggota`;
                ALTER TABLE `members` ADD CONSTRAINT `members_jenis_anggota` FOREIGN KEY (`JenisAnggota_id`) REFERENCES `jenis_anggota` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
                
                ALTER TABLE `members` DROP FOREIGN KEY `member_agama`;
                ALTER TABLE `members` ADD CONSTRAINT `member_agama` FOREIGN KEY (`Agama_id`) REFERENCES `agama` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `member_job`;
                ALTER TABLE `members` ADD CONSTRAINT `member_job` FOREIGN KEY (`Job_id`) REFERENCES `master_pekerjaan` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `member_sex`;
                ALTER TABLE `members` ADD CONSTRAINT `member_sex` FOREIGN KEY (`Sex_id`) REFERENCES `jenis_kelamin` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `member_unit_kerja`;
                ALTER TABLE `members` ADD CONSTRAINT `member_unit_kerja` FOREIGN KEY (`UnitKerja_id`) REFERENCES `departments` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_education`;
                ALTER TABLE `members` ADD CONSTRAINT `members_education` FOREIGN KEY (`EducationLevel_id`) REFERENCES `master_pendidikan` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_fakultas`;
                ALTER TABLE `members` ADD CONSTRAINT `members_fakultas` FOREIGN KEY (`Fakultas_id`) REFERENCES `master_fakultas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_identitytype`;
                ALTER TABLE `members` ADD CONSTRAINT `members_identitytype` FOREIGN KEY (`IdentityType_id`) REFERENCES `master_jenis_identitas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_jenispermohonan`;
                ALTER TABLE `members` ADD CONSTRAINT `members_jenispermohonan` FOREIGN KEY (`JenisPermohonan_id`) REFERENCES `jenis_permohonan` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_jurusan`;
                ALTER TABLE `members` ADD CONSTRAINT `members_jurusan` FOREIGN KEY (`Jurusan_id`) REFERENCES `master_jurusan` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_kelassiswa`;
                ALTER TABLE `members` ADD CONSTRAINT `members_kelassiswa` FOREIGN KEY (`Kelas_id`) REFERENCES `kelas_siswa` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_programstudi`;
                ALTER TABLE `members` ADD CONSTRAINT `members_programstudi` FOREIGN KEY (`ProgramStudi_id`) REFERENCES `master_program_studi` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_status_perkawinan`;
                ALTER TABLE `members` ADD CONSTRAINT `members_status_perkawinan` FOREIGN KEY (`MaritalStatus_id`) REFERENCES `master_status_perkawinan` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `members` DROP FOREIGN KEY `members_statusanggota`;
                ALTER TABLE `members` ADD CONSTRAINT `members_statusanggota` FOREIGN KEY (`StatusAnggota_id`) REFERENCES `status_anggota` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
            ")->execute();
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }

        //issue #810 koleksi cascade
        try {

            $command = Yii::$app->db->createCommand("
    
                ALTER TABLE `collections` DROP FOREIGN KEY `collections_media`;
                ALTER TABLE `collections` ADD CONSTRAINT `collections_media` FOREIGN KEY (`Media_id`) REFERENCES `collectionmedias` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `collections` DROP FOREIGN KEY `collections_partner`;
                ALTER TABLE `collections` ADD CONSTRAINT `collections_partner` FOREIGN KEY (`Partner_id`) REFERENCES `partners` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `collections` DROP FOREIGN KEY `collections_currency`;
                ALTER TABLE `collections` ADD CONSTRAINT `collections_currency` FOREIGN KEY (`Currency`) REFERENCES `currency` (`Currency`) ON DELETE NO ACTION ON UPDATE CASCADE;
    
                ALTER TABLE `collections` DROP FOREIGN KEY `collections_category`;
                ALTER TABLE `collections` ADD CONSTRAINT `collections_category` FOREIGN KEY (`Category_id`) REFERENCES `collectioncategorys` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
                
            ")->execute();
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }

        //issue nomor indux
        try {

            $command = Yii::$app->db->createCommand("
    
                INSERT  INTO settingparameters
                SELECT * FROM
                (SELECT @ID AS ID,'FormatNomorIndukx' AS `Name`,'0|2|0|3|5|3|1|2|0' AS `Value`, NULL AS CreateBy, NULL AS CreateDate, NULL AS CreateTerminal, NULL AS UpdateBy, NULL AS UpdateDate, NULL AS UpdateTerminal) AS test
                WHERE NOT EXISTS (
                    SELECT settingparameters.Name FROM settingparameters WHERE settingparameters.Name = 'FormatNomorIndukx'
                )
            
            ")->execute();
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }

        //issue cardformat
        try {

            $command = Yii::$app->db->createCommand("
    
                INSERT  INTO cardformats
                SELECT * FROM
                (SELECT NULL AS ID,'6. Semua' AS `Name`,528 AS `Width`,288 AS `Height`,'Consolas' AS `FontName`,11 AS `FontSize`,
                '<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n<082,a>\r\n <100|110|111,a,1, StartPosition:0, Length:3, FontMode:uppercase>   <100|110|111,a,1>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>        <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.><440,a,1, RuasDelimiter: (, EndChar:).>\r\n  <300,a|b|c,1, EndChar:.>\r\n\r\n  <310,a,1>\r\n  <362>\r\n  <500>\r\n  <502,a,1>\r\n  <504,a,1>\r\n  <505,a,1>\r\n  <508>\r\n\r\n  <020,a,0, RuasDelimiter:ISBN >\r\n  <022,a,0, RuasDelimiter:ISSN >\r\n\r\n  <600|610|650|651,-,0, NumberingMode:ordernumeric, RuasRepeatableDelimiter:   , SubRuasDelimiter: -- >\r\n  <700|710|711|740,-,0, NumberingMode:orderromawi, NumberingFirstValue:I. Judul, RuasRepeatableDelimiter:   >\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n       <245,a,1, WordTruncateCount:4>\r\n\r\n<082,a>\r\n <100|110|111,a,1, StartPosition:0, Length:3, FontMode:uppercase>   <100|110|111,a,1>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>        <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.><440,a,1, RuasDelimiter: (, EndChar:).>\r\n  <300,a|b|c,1, EndChar:.>\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n       <600|610|650|651,-,0, IsSplitCard:1, SubRuasDelimiter: -- , FontMode:uppercase>\r\n\r\n<082,a>\r\n <100|110|111,a,1, StartPosition:0, Length:3, FontMode:uppercase>   <100|110|111,a,1>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>        <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.><440,a,1, RuasDelimiter: (, EndChar:).>\r\n  <300,a|b|c,1, EndChar:.>\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n       <100|110|111|700|710|711,-,0, IsSplitCard:1, IsDontShowCardIfTagNotExist:1>\r\n\r\n<082,a>\r\n <100|110|111,a,1, StartPosition:0, Length:3, FontMode:uppercase>   <100|110|111,a,1>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>        <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.><440,a,1, RuasDelimiter: (, EndChar:).>\r\n  <300,a|b|c,1, EndChar:.>\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n       <440,-,1>\r\n\r\n<082,a>\r\n <100|110|111,a,1, StartPosition:0, Length:3, FontMode:uppercase>   <100|110|111,a,1>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>        <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.>\r\n  <300,a|b|c,1, EndChar:.>\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n' AS `FormatTeks`,
                '<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n<082,a>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>   <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.><440,a,1, RuasDelimiter: (, EndChar:).>\r\n<300,a|b|c,1, EndChar:.>\r\n\r\n<310,a,1>\r\n<362>\r\n<440>\r\n<500>\r\n<502,a,1>\r\n<504,a,1>\r\n<505,a,1>\r\n<508>\r\n\r\n<020,a,0, RuasDelimiter:ISBN >\r\n<022,a,0, RuasDelimiter:ISBN >\r\n\r\n<600|610|650|651,-,0, NumberingMode:ordernumeric, RuasRepeatableDelimiter:   , SubRuasDelimiter: -- >\r\n<700|710|711|740,-,0, NumberingMode:orderromawi, NumberingFirstValue:I. Judul, RuasRepeatableDelimiter:   >\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n       <245,a,1, WordTruncateCount:4>\r\n\r\n<082,a>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>   <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.><440,a,1, RuasDelimiter: (, EndChar:).>\r\n<300,a|b|c,1, EndChar:.>\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n       <600|610|650|651,-,0, IsSplitCard:1, SubRuasDelimiter: -- , FontMode:uppercase>\r\n\r\n<082,a>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>   <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.><440,a,1, RuasDelimiter: (, EndChar:).>\r\n<300,a|b|c,1, EndChar:.>\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n       <700|710|711,-,0, IsSplitCard:1, IsDontShowCardIfTagNotExist:1>\r\n\r\n<082,a>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>   <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.><440,a,1, RuasDelimiter: (, EndChar:).>\r\n<300,a|b|c,1, EndChar:.>\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n<090,a,0, Alignment:right, RuasRepeatableDelimiter: -- >\r\n       <440,-,1>\r\n\r\n<082,a>\r\n  <245,a,1, StartPosition:0, Length:1, FontMode:lowercase>   <245,a|b|c,1, HangingIndent:9, EndChar:.><250,-,1, RuasDelimiter: -- , EndChar:.><260|264,a|b|c,1, RuasDelimiter: -- , EndChar:.>\r\n<300,a|b|c,1, EndChar:.>\r\n\r\n<990,-,0, HangingIndent:0, RuasRepeatableDelimiter:br>\r\n<999>\r\n\r\n' AS `FormatTeksNoAuthor`,
                33 AS `CreateBy`,'2016-10-28 11:15:52' AS `CreateDate`,'::1' AS `CreateTerminal`,33 AS `UpdateBy`,'2016-10-28 11:35:11' AS `UpdateDate`,'::1' AS `UpdateTerminal`) AS test
                WHERE NOT EXISTS (
                    SELECT cardformats.Name FROM cardformats WHERE cardformats.Name LIKE '%6. semua%'
                )
            
            ")->execute();
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }

        //issue statistik rombongan jumlah anggota
        try {

            $command = Yii::$app->db->createCommand("
    
                CREATE OR REPLACE VIEW v_pertumb_jml_kunjungan_bulanan (kriteria,tahun,bulan,jumlah) AS SELECT 'ANGGOTA' AS `kriteria`,
                  YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,'%Y-%m-%d')) AS `tahun`,
                  MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,'%Y-%m-%d')) AS `bulan`,
                  COUNT(0)  AS `jumlah`
                FROM `memberguesses`
                WHERE (`memberguesses`.`NoAnggota` IS NOT NULL)
                GROUP BY YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,'%Y-%m-%d')),MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,'%Y-%m-%d'))
                UNION ALL SELECT
                'NONANGGOTA' AS `kriteria`,
                 YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,'%Y-%m-%d')) AS `tahun`,
                 MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,'%Y-%m-%d')) AS `bulan`,
                 COUNT(0)     AS `jumlah`
                FROM `memberguesses`
                WHERE ISNULL(`memberguesses`.`NoAnggota`)
                GROUP BY YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,'%Y-%m-%d')),MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,'%Y-%m-%d'))
                UNION ALL SELECT
                'ROMBONGAN'   AS `kriteria`,
                 YEAR(STR_TO_DATE(`groupguesses`.`CreateDate`,'%Y-%m-%d')) AS `tahun`,
                 MONTH(STR_TO_DATE(`groupguesses`.`CreateDate`,'%Y-%m-%d')) AS `bulan`,
                 SUM(`groupguesses`.`CountPersonel`) AS `jumlah`
                FROM `groupguesses`
                GROUP BY YEAR(STR_TO_DATE(`groupguesses`.`CreateDate`,'%Y-%m-%d')),MONTH(STR_TO_DATE(`groupguesses`.`CreateDate`,'%Y-%m-%d'))
                ORDER BY `tahun`
            
            ")->execute();
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }

        //issue salin katalog physucaldescription from katalog ruas tag 300
        try {

            $command = Yii::$app->db->createCommand("
    
                UPDATE catalogs 
                LEFT JOIN catalog_ruas ON catalog_ruas.`CatalogId` = catalogs.`ID`
                SET catalogs.`PhysicalDescription` = REPLACE(REPLACE(REPLACE((REPLACE(REPLACE(REPLACE(catalog_ruas.`Value`,'\$c ', ''),'\$b ',''),'\$a ','')),'\$a',''),'\$b',' '),'\$c',' ')
                WHERE catalog_ruas.`Tag` = '300' AND catalogs.`PhysicalDescription` = ''
            
            ")->execute();
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }

        //update struktur table collection_loan
        try {

            $cekCLoan = OpacHelpers::columnExist('collectionloans','LocationLibrary_id');
            if($cekCLoan == 0){
            $command = Yii::$app->db->createCommand("
    
                ALTER TABLE `collectionloans`
                ADD COLUMN `LocationLibrary_id` INT(11) NULL DEFAULT NULL AFTER `KIILastUploadDate`,
                ADD CONSTRAINT `FK_collectionloans_location_library` FOREIGN KEY (`LocationLibrary_id`) REFERENCES `location_library` (`ID`) ON UPDATE CASCADE ON DELETE SET NULL;
    
            ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
        //collection loan hari
        try {

            $cekCLoanHari = OpacHelpers::tableExist('collectioncategorysloanhari');
            if($cekCLoanHari == 0){
            $command = Yii::$app->db->createCommand("
                DROP TABLE IF EXISTS `collectioncategorysloanhari`;
                CREATE TABLE `collectioncategorysloanhari` (
                `DataID` int(11) NOT NULL AUTO_INCREMENT,
                `Category_id` int(11) DEFAULT NULL,
                `Peminjaman_hari_id` int(11) DEFAULT NULL,
                `CreateBy` int(11) DEFAULT NULL,
                `CreateDate` datetime DEFAULT NULL,
                `CreateTerminal` varchar(100) DEFAULT NULL,
                `UpdateBy` int(11) DEFAULT NULL,
                `UpdateDate` datetime DEFAULT NULL,
                `UpdateTerminal` varchar(100) DEFAULT NULL,
                PRIMARY KEY (`DataID`),
                KEY `collectioncategorysloanhari_category_id` (`Category_id`),
                KEY `collectioncategorysloanhari_peminjaman_hari_id` (`Peminjaman_hari_id`),
                KEY `collectioncategorysloanhari_createby` (`CreateBy`),
                KEY `collectioncategorysloanhari_updateby` (`UpdateBy`),
                CONSTRAINT `collectioncategorysloanhari_category_id` FOREIGN KEY (`Category_id`) REFERENCES `collectioncategorys` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `collectioncategorysloanhari_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
                CONSTRAINT `collectioncategorysloanhari_peminjaman_hari_id` FOREIGN KEY (`Peminjaman_hari_id`) REFERENCES `peraturan_peminjaman_hari` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `collectioncategorysloanhari_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
            ")->execute();

            $command = Yii::$app->db->createCommand("
                INSERT INTO `collectioncategorysloanhari` (`DataID`,`Category_id`,`Peminjaman_hari_id`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateDate`,`UpdateTerminal`) VALUES (61,7,1,33,'2016-10-18 12:32:39','192.168.0.1',33,'2016-10-18 12:32:39','192.168.0.1');
            ")->execute();

            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
        //master jenjang pndidikan
        try {

            $masterJenjang = OpacHelpers::tableExist('master_jenjang_pendidikan');
            if($masterJenjang == 0){
            $command = Yii::$app->db->createCommand("
                DROP TABLE IF EXISTS `master_jenjang_pendidikan`;
                CREATE TABLE `master_jenjang_pendidikan` (
                `ID` int(11) NOT NULL AUTO_INCREMENT,
                `jenjang_pendidikan` varchar(255) NOT NULL,
                `CreateBy` int(11) DEFAULT NULL,
                `CreateDate` datetime DEFAULT NULL,
                `CreateTerminal` varchar(100) DEFAULT NULL,
                `UpdateBy` int(11) DEFAULT NULL,
                `UpdateDate` date DEFAULT NULL,
                `UpdateTerminal` varchar(100) DEFAULT NULL,
                PRIMARY KEY (`ID`),
                KEY `masjer_jenjang_pendidikan_create_by` (`CreateBy`),
                KEY `masjer_jenjang_pendidikan_create_date` (`CreateDate`),
                KEY `masjer_jenjang_pendidikan_update_by` (`UpdateBy`),
                KEY `masjer_jenjang_pendidikan_update_date` (`UpdateDate`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;   
            ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
        //update table members
        //update photoUrl
        try {

            $photoUrl = OpacHelpers::columnExist('members','PhotoUrl');
            if($photoUrl == 0){
                $command = Yii::$app->db->createCommand("
                    ALTER TABLE `members`   
                    ADD COLUMN `PhotoUrl` VARCHAR(255) NULL AFTER `Branch_id`
                ")->execute();
                $command = Yii::$app->db->createCommand("
                    UPDATE members SET PhotoUrl = Concat(ID,'.jpg') WHERE ID > 0
                ")->execute();
            }
            //update jenjang pendidikan
            $JenjangP = OpacHelpers::columnExist('members','JenjangPendidikan_id');
            if($JenjangP == 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `members`   
                ADD COLUMN `JenjangPendidikan_id` INT(11) NULL AFTER `ProgramStudi_id`, 
                ADD  INDEX `members_jenjang_pendidikan` (`JenjangPendidikan_id`),
                ADD CONSTRAINT `members_jenjang_pendidikan` FOREIGN KEY (`JenjangPendidikan_id`) REFERENCES `master_jenjang_pendidikan`(`ID`) ON UPDATE CASCADE ON DELETE SET NULL;
    
                ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
        //opaclogs keyword
        try {

            $cekOpacLogKeyword = OpacHelpers::tableExist('opaclogs_keyword');
            if($cekOpacLogKeyword == 0){
                //create table opaclogs
                $command = Yii::$app->db->createCommand("
                    CREATE TABLE `opaclogs_keyword` (
                      `Id` int(11) NOT NULL AUTO_INCREMENT,
                      `OpaclogsId` int(11) NOT NULL,
                      `Field` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
                      `Keyword` text CHARACTER SET utf8,
                      PRIMARY KEY (`Id`),
                      KEY `opaclogs_keyword_field_idx` (`Field`),
                      KEY `opaclogs_keyword_opaclogsid_idx` (`OpaclogsId`),
                      KEY `opaclogs_keyword_keyword_idx` (`Keyword`(240)),
                      CONSTRAINT `opaclogs_keyword_opaclogsid` FOREIGN KEY (`OpaclogsId`) REFERENCES `opaclogs` (`ID`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                ")->execute();
            } else

            {
                //if exist alter opaclogs keyword
                $command = Yii::$app->db->createCommand("
                    ALTER TABLE opaclogs_keyword 
                    DROP INDEX opaclogs_keyword_keyword_idx,
                    ADD INDEX opaclogs_keyword_keyword_idx (Keyword(240));
                ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
        //reference
        try {
            $data = Yii::$app->db->createCommand("select * from refferences where ID = 150")->execute();
            if($data==0){
                $command = Yii::$app->db->createCommand("
                insert into `refferences` (`ID`, `Name`, `Format_id`, `CreateBy`, `CreateDate`, `CreateTerminal`, `UpdateBy`, `UpdateDate`, `UpdateTerminal`) values('150','Relator Term Kreator','1',NULL,'2016-08-25 17:11:47','192.168.0.1',NULL,'2016-08-25 17:11:47','192.168.0.1');
                insert into `refferenceitems` (`RefferenceItemsID`, `Refference_id`, `Code`, `Name`, `CreateBy`, `CreateDate`, `CreateTerminal`, `UpdateBy`, `UpdateDate`, `UpdateTerminal`) values(NULL,'150','01','Pengarang',NULL,'2016-08-25 17:11:47','192.168.0.1',NULL,'2016-08-25 17:11:47','192.168.0.1');
                insert into `refferenceitems` (`RefferenceItemsID`, `Refference_id`, `Code`, `Name`, `CreateBy`, `CreateDate`, `CreateTerminal`, `UpdateBy`, `UpdateDate`, `UpdateTerminal`) values(NULL,'150','02','Penyunting',NULL,'2016-08-25 17:11:47','192.168.0.1',NULL,'2016-08-25 17:11:47','192.168.0.1');
                insert into `refferenceitems` (`RefferenceItemsID`, `Refference_id`, `Code`, `Name`, `CreateBy`, `CreateDate`, `CreateTerminal`, `UpdateBy`, `UpdateDate`, `UpdateTerminal`) values(NULL,'150','03','Penerjemah',NULL,'2016-08-25 17:11:47','192.168.0.1',NULL,'2016-08-25 17:11:47','192.168.0.1');
                insert into `refferenceitems` (`RefferenceItemsID`, `Refference_id`, `Code`, `Name`, `CreateBy`, `CreateDate`, `CreateTerminal`, `UpdateBy`, `UpdateDate`, `UpdateTerminal`) values(NULL,'150','04','Ilustrator',NULL,'2016-08-25 17:11:47','192.168.0.1',NULL,'2016-08-25 17:11:47','192.168.0.1');
                insert into `refferenceitems` (`RefferenceItemsID`, `Refference_id`, `Code`, `Name`, `CreateBy`, `CreateDate`, `CreateTerminal`, `UpdateBy`, `UpdateDate`, `UpdateTerminal`) values(NULL,'150','05','Komposer',NULL,'2016-08-25 17:11:47','192.168.0.1',NULL,'2016-08-25 17:11:47','192.168.0.1');
                insert into `refferenceitems` (`RefferenceItemsID`, `Refference_id`, `Code`, `Name`, `CreateBy`, `CreateDate`, `CreateTerminal`, `UpdateBy`, `UpdateDate`, `UpdateTerminal`) values(NULL,'150','06','Badan Penanggungjawab',NULL,'2016-06-16 13:55:45','192.168.0.1',NULL,'2016-06-16 13:55:45','192.168.0.1');
    
                ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
        //nonaktifkan superuser
        try {
            $command = Yii::$app->db->createCommand("UPDATE users SET IsActive = 0 WHERE username IN ('denisyahreza','superadmin')")->execute();

        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }

        //update role dan kawan kawan
        try {

            $command = Yii::$app->db->createCommand("
                        -- MySQL Administrator dump 1.4
                --
                -- ------------------------------------------------------
                -- Server version   5.5.5-10.1.11-MariaDB-1~trusty-log
    
    
                /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
                /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
                /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
                /*!40101 SET NAMES utf8 */;
    
                /*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
                /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
                /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
    
                --
                -- Definition of table `auth_item`
                --
    
                DROP TABLE IF EXISTS `auth_item`;
                CREATE TABLE `auth_item` (
                  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                  `type` int(11) NOT NULL,
                  `description` text COLLATE utf8_unicode_ci,
                  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `data` text COLLATE utf8_unicode_ci,
                  `CreateBy` int(11) DEFAULT NULL,
                  `CreateDate` datetime DEFAULT NULL,
                  `CreateTerminal` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `UpdateBy` int(11) DEFAULT NULL,
                  `UpdateDate` datetime DEFAULT NULL,
                  `UpdateTerminal` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `created_at` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `updated_at` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
                  PRIMARY KEY (`name`),
                  KEY `rule_name` (`rule_name`),
                  KEY `idx-auth_item-type` (`type`),
                  KEY `auth_item_createby` (`CreateBy`),
                  KEY `auth_item_updateby` (`UpdateBy`),
                  CONSTRAINT `auth_item_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
                  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE,
                  CONSTRAINT `auth_item_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    
                --
                -- Dumping data for table `auth_item`
                --
    
                /*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
                INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateDate`,`UpdateTerminal`,`created_at`,`updated_at`) VALUES 
                 ('/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/admin/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/admin/assignment/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/assignment/assign',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/assignment/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/assignment/revoke',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/admin/assignment/search',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/assignment/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/menu/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/menu/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/menu/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/menu/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/menu/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/menu/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240458','1451240458'),
                 ('/admin/permission/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/permission/assign',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/permission/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/permission/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/permission/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/permission/remove',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/admin/permission/search',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/permission/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/permission/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/role/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/role/assign',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/role/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/role/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/role/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/role/remove',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/admin/role/search',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/role/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/role/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/route/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/route/assign',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/route/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/route/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/route/refresh',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/route/remove',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/admin/route/search',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/rule/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/admin/rule/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/admin/rule/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/admin/rule/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240459','1451240459'),
                 ('/admin/rule/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/admin/rule/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/admin/user/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/activate',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/change-password',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/login',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/logout',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/request-password-reset',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/reset-password',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/signup',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/admin/user/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/akuisisi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/kardeks-terbitan-berkala/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/kardeks-terbitan-berkala/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi-import/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/koleksi-import/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/koleksi-import/proses',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/koleksi-jilid/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/koleksi-jilid/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/delete-serial-collection',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/fill-serial-collection',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/flash-message',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/remove-jilid',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/remove-jilid-all',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/save',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/save-nopanggil',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/show-serial-collection',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/show-serial-collection-view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-jilid/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/koleksi-jilid/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi-karantina/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/koleksi-karantina/flash-message',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/koleksi-karantina/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/koleksi-karantina/restore',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/koleksi-karantina/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/koleksi-usulan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/koleksi-usulan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/koleksi-usulan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/akuisisi/koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi/bibliografis-input',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/bind-no-induk',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi/bind-partners',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi/cetak-label-proses',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/akuisisi/koleksi/checkbox-process',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/create-taglist',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi/entry-advance',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi/entry-bib-by-worksheet',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/entry-simple',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/flash-message',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/akuisisi/koleksi/get-datetime-now-str',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/get-dropdown',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/get-dropdown-labelmodel',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/akuisisi/koleksi/get-dropdown-ruang',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/akuisisi/koleksi/get-message-checkbox-process',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/get-ruang',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/akuisisi/koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/karantina',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463717425','1463717425'),
                 ('/akuisisi/koleksi/karantina-proses',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/akuisisi/koleksi/keranjang',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463717875','1463717875'),
                 ('/akuisisi/koleksi/keranjang-reset',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/akuisisi/koleksi/restore',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729696','1463729696'),
                 ('/akuisisi/koleksi/save',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/save-catalog-ruas',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/save-catalog-sub-ruas',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/save-entry-mode',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi/save-partners',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi/set-ruas',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240461','1451240461'),
                 ('/akuisisi/koleksi/test-bibid',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/test-controlnumber',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/validate-simple-bibliografis',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240460','1451240460'),
                 ('/akuisisi/koleksi/viewkarantina',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/view-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/pengiriman-koleksi-cetak',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/hapus-item',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/delete-pengiriman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/akuisisi/pengiriman-koleksi/print-pengiriman-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/bacaditempat/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/bacaditempat/koleksi-dibaca/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/bacaditempat/koleksi-dibaca/anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/bacaditempat/koleksi-dibaca/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/bacaditempat/koleksi-dibaca/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/bacaditempat/koleksi-dibaca/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/bacaditempat/koleksi-dibaca/nonanggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/bacaditempat/koleksi-dibaca/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/bacaditempat/koleksi-dibaca/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/bacaditempat/pengembalian-koleksi-baca-ditempat/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/bacaditempat/pengembalian-koleksi-baca-ditempat/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/bacaditempat/pengembalian-koleksi-baca-ditempat/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/bacaditempat/pengembalian-koleksi-baca-ditempat/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/bacaditempat/pengembalian-koleksi-baca-ditempat/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/bacaditempat/pengembalian-koleksi-baca-ditempat/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/backuprestore/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/backuprestore/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/backuprestore/default/clean',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/backuprestore/default/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/backuprestore/default/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/backuprestore/default/download',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/backuprestore/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/backuprestore/default/restore',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/backuprestore/default/syncdown',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/backuprestore/default/upload',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/datecontrol/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/datecontrol/parse/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/datecontrol/parse/convert',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/debug/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/debug/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/debug/default/db-explain',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/debug/default/download-mail',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/debug/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/debug/default/toolbar',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/debug/default/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/deposit/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/deposit/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/deposit/transaction',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/deposit/transaction/list-deposit',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/deposit/terima-kasih',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/gii/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/gii/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/gii/default/action',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/gii/default/diff',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/gii/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/gii/default/preview',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/gii/default/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/gridview/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/gridview/export/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/gridview/export/download',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/laporan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/laporan/anggota/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/anggota/bebas-pustaka',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/laporan/anggota/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/laporan/anggota/kinerja-user',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/laporan/anggota/load-filter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/laporan/anggota/load-selecter-bebas-pustaka',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/load-selecter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/load-selecter-perpanjangan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/load-selecter-sumbangan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/perpanjangan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/laporan/anggota/perpendaftaran',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/laporan/anggota/render-kinerja-user-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/render-kinerja-user-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/anggota/render-pdf-angg-sumbangan-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/render-pdf-bebas-pustaka',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/anggota/render-pdf-bebas-pustaka-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/render-pdf-perpanjangan-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/render-pdf-perpanjangan-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/render-pdf-perpendaftaran-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/render-pdf-sumbangan-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/render-perpendaftaran-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/show-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729718','1463729718'),
                 ('/laporan/anggota/sumbangan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/laporan/baca-ditempat/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/anggota-sering-baca-ditempat',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1471923904','1471923904'),
                 ('/laporan/baca-ditempat/berdasarkan-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/koleksi-sering-baca-ditempat',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/load-filter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/load-selecter-berdasarkan-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/non-anggota-sering-baca-ditempat',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1472441301','1472441301'),
                 ('/laporan/baca-ditempat/render-berdasarkan-koleksi-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/render-berdasarkan-koleksi-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/sering-baca-ditempat-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/sering-baca-ditempat-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/baca-ditempat/show-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/buku-tamu/anggota-sering-berkunjung',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1472616634','1472616634'),
                 ('/laporan/buku-tamu/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/kunjungan-khusus-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/kunjungan-periodik',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/load-filter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/load-filter-kriteria-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/load-selecter-kriteria-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/load-selecter-kriteria-lokasi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/render-khusus-anggota-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/render-khusus-anggota-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/buku-tamu/render-kunjungan-periodik-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/buku-tamu/render-kunjungan-periodik-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/buku-tamu/show-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729719','1463729719'),
                 ('/laporan/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/per-group',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/jenis-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/wajib-serah',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/wajib-serah-detail',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/penerbit',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/penerbit-wilayah',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/terima-kasih',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/cardex',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/serial',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/deposit/aset',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),

                 ('/laporan/katalog/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240487','1451240487'),
                 ('/laporan/katalog/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/katalog/katalog-perkriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/katalog/kinerja-user',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/katalog/load-filter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/katalog/load-selecter-kinerja-user',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/katalog/load-selecter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/katalog/mpdf-demo1',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/laporan/katalog/render-kinerja-user-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/katalog/render-pdf-katalog-perkriteria-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/katalog/render-pdf-katalog-perkriteria-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/katalog/show-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/koleksi/accession-list',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/buku-induk',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/kinerja-user',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/koleksi/load-filter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/load-selecter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/load-selecter-kriteria-usulan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/periodik',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/render-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/render-pdf-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/render-pdf-data-accession-list',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/render-pdf-data-buku-induk',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/render-pdf-data-kinerja-user',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/koleksi/render-pdf-data-usulan-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/render-pdf-frekuensi-kinerja-user',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/koleksi/render-pdf-frekuensi-usulan-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/render-pdf-ucapan-terimakasih',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/show-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/ucapan-terimakasih',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/koleksi/usulan-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729720','1463729720'),
                 ('/laporan/loker/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/laporan-periodik',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/laporan-sangsi-pelanggaran-loker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/load-filter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/load-selecter-loker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/load-selecter-sangsi-pelanggaran-loker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/render-laporan-periodik-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/render-laporan-periodik-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/render-laporan-sangsi-pelanggaran-loker-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/render-laporan-sangsi-pelanggaran-loker-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/loker/show-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/opac/export',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/opac/export-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/export-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/opac/export-pdf-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/export-word',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/opac/export-word-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/laporan-periodik',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/load-filter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/load-selecter-laporan-periodik',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/render-laporan-periodik-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/render-laporan-periodik-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/opac/show-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729721','1463729721'),
                 ('/laporan/sirkulasi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sirkulasi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/kinerja-user-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/kinerja-user-pengembalian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/koleksi-sering-dipinjam',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/laporan-anggota-sering-meminjam',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/laporan-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/load-filter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/load-filter-kriteria-dipinjam',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/load-selecter-anggota-sering-meminjam',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/load-selecter-kriteria-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/load-selecter-laporan-dipinjam',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/load-selecter-laporan-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/load-selecter-peminjaman-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/load-selecter-perpanjangan-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/load-selecter-sangsi-pelanggaran-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/pengembalian-terlambat',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/perpanjangan-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/render-anggota-sering-meminjam-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/render-anggota-sering-meminjam-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sirkulasi/render-kinerja-user-peminjaman-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sirkulasi/render-kinerja-user-pengembalian-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sirkulasi/render-koleksi-sering-dipinjam-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/render-koleksi-sering-dipinjam-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sirkulasi/render-laporan-peminjaman-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/render-laporan-peminjaman-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/render-pengembalian-terlambat-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/render-pengembalian-terlambat-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sirkulasi/render-perpanjangan-peminjaman-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/render-perpanjangan-peminjaman-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sirkulasi/render-sangsi-pelanggaran-peminjaman-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/sangsi-pelanggaran-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sirkulasi/show-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729722','1463729722'),
                 ('/laporan/sms/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sms/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sms/laporan-periodik',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sms/load-filter-kriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sms/load-selecter-laporan-periodik',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sms/render-laporan-periodik-data',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sms/render-laporan-periodik-frekuensi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/laporan/sms/show-pdf',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729723','1463729723'),
                 ('/lkd/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/history/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/history/browse/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/history/browse/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/history/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/history/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/history/pencarian-lanjut/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/history/pencarian-lanjut/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/history/pencarian-sederhana/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/lkd/history/pencarian-sederhana/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/loker/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/loker/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/locker/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/locker/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/locker/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/locker/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/locker/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/locker/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/master-uang-jaminan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/master-uang-jaminan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/master-uang-jaminan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/master-uang-jaminan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/master-uang-jaminan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/master-uang-jaminan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/masterpelanggaran/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/masterpelanggaran/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/masterpelanggaran/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/masterpelanggaran/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/masterpelanggaran/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/masterpelanggaran/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/settings/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/settings/createlocker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/settings/deletelocker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/settings/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/loker/settings/locker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/settings/uang-jaminan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/settings/updatelocker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/settings/viewlocker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/loker/transaksi/ceknomorpengembalian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/cekpelanggaran',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/cetak',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/loker/transaksi/cetak-bukti-pelanggaran',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/loker/transaksi/cetak-pelanggaran',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/loker/transaksi/check-barcode-loker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/loker/transaksi/checkmembership',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/loker/transaksi/daftar-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463718441','1463718441'),
                 ('/loker/transaksi/daftar-pengembalian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463718441','1463718441'),
                 ('/loker/transaksi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/loker/transaksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/locker-outoforder-by-id',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/locker-ready-by-id',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/locker-used-by-id',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/pengembalian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/pinjamanbynomember',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/loker/transaksi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/loker/transaksi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/loker/transaksi/viewpeminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240466','1451240466'),
                 ('/member/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/member/daftar-pengunjung/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/daftar-pengunjung/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/daftar-pengunjung/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/member/member/bind-penduduk',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/member/member/checkbox-process',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/member/member/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/crop',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/member/member/crop-profile-image',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/member/member/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/detail-histori',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/member/detail-kependudukan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/member/member/get-biaya-pendaftaran',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/hapus-foto',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/member/import-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/member/member/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/jurusan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/kabupaten-list',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/kartu-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/member/member/keranjang-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/member/member/masa-berlaku',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/member/prodi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/member/member/progress',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/member/member/province-list',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/reset-password',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/member/member/save-foto',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/testing',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/member/upload-foto-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/member/member/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/member/pdf/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/member/pdf/cetak-bebas-pustaka',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/pdf/kartu-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240465','1451240465'),
                 ('/member/pdf/kartu-anggota-all',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/pdf/kartu-anggota-satuan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/pdf/render-kartu-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/pdf/render-kartu-anggota-a4',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/perpanjang/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/perpanjang/check-membership',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/perpanjang/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/perpanjang/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/perpanjang/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/perpanjang/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/perpanjang/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/sumbangan-koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/member/sumbangan-koleksi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/member/sumbangan-koleksi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/member/sumbangan-koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/member/sumbangan-koleksi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/member/sumbangan-koleksi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/member/sumbangan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/member/sumbangan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/sumbangan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/sumbangan/hapus-item',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/member/sumbangan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/sumbangan/pilih-judul',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/sumbangan/pilih-judul-proses',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/member/sumbangan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/member/sumbangan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729700','1463729700'),
                 ('/mimin/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/role/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/role/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729694','1463729694'),
                 ('/mimin/role/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729694','1463729694'),
                 ('/mimin/role/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729694','1463729694'),
                 ('/mimin/role/permission',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/role/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729694','1463729694'),
                 ('/mimin/role/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729694','1463729694'),
                 ('/mimin/route/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/route/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/route/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/route/generate',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/route/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/route/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/route/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/user/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/user/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/user/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/user/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/user/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/mimin/user/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729695','1463729695'),
                 ('/opac/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729126','1463729126'),
                 ('/opac/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729125','1463729125'),
                 ('/opac/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726568','1463726568'),
                 ('/opac/history/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726670','1463726670'),
                 ('/opac/history/browse/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726669','1463726669'),
                 ('/opac/history/browse/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726669','1463726669'),
                 ('/opac/history/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726669','1463726669'),
                 ('/opac/history/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726669','1463726669'),
                 ('/opac/history/pencarian-lanjut/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726670','1463726670'),
                 ('/opac/history/pencarian-lanjut/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726670','1463726670'),
                 ('/opac/history/pencarian-sederhana/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726656','1463726656'),
                 ('/opac/history/pencarian-sederhana/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463726654','1463726654'),
                 ('/pengkatalogan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/pengkatalogan/katalog-cetak-kartu/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog-cetak-kartu/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog-cetak-kartu/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog-cetak-kartu/proses',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog-cetak-label/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog-cetak-label/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog-cetak-label/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog-export-data-tag/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog-export-data-tag/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog-export-data-tag/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog-karantina/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-karantina/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-keranjang/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-keranjang/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-konten-digital/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/pengkatalogan/katalog-konten-digital/checkbox-process',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-konten-digital/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/pengkatalogan/katalog-konten-digital/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-konten-digital/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/pengkatalogan/katalog-salin/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/pengkatalogan/katalog-salin/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/pengkatalogan/katalog-salin/get-dropdown-salinkatalog',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-salin/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240464','1451240464'),
                 ('/pengkatalogan/katalog-salin/records',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-salin/save-records',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-salin/sru',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog-salin/upload',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/artikel/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/artikel/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/artikel/bind-catalogs-article',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/artikel/save-catalogs-article',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/artikel/bind-catalogs-digital-article',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/artikel/upload-konten-digital-artikel',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/artikel/detail-histori-article',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/add-tag',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/auto-suggest-call-number',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/bibliografis-input',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog/bind-catalogs-collection',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/bind-no-induk',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/bind-partners',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/cetak-kartu-proses',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/checkbox-process',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog/checkbox-process-konten-digital',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/convert-to-catalog-fields',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/create-model-bib-from-catalog',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/create-tag-simple',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/create-taglist-advance',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/create-taglist-clean',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/create-taglist-from-catalog',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/create-taglist-simple',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/create-taglist-to-biblio',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/create&for=cat&rda=0',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451242691','1451242691'),
                 ('/pengkatalogan/katalog/create&for=cat&rda=1',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463728190','1463728190'),
                 ('/pengkatalogan/katalog/create&for=coll&rda=0',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463716863','1463716863'),
                 ('/pengkatalogan/katalog/create&for=coll&rda=1',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463717082','1463717082'),
                 ('/pengkatalogan/katalog/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/delete-cover',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/delete-konten-digital',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/detail',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/detail-collection',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/detail-histori',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog/download',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/entry-advance',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/entry-bib-by-worksheet',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/entry-simple',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/flash-message',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/get-datetime-now-str',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog/get-dropdown',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog/get-dropdown-konten-digital',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/get-dropdown-salinkatalog',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/get-message-checkbox-process',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog/karantina',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/karantina-proses',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/keranjang',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/keranjang-reset',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/pilih-judul',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/pilih-judul-proses',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/reset-catalogs-collection',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/reset-konten-digital',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/restore',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/salin-katalog',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/salin-katalog-proses',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog/salin-katalog-sru',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/salin-katalog-upload',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729699','1463729699'),
                 ('/pengkatalogan/katalog/save',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/save-catalog-ruas',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog/save-catalog-sub-ruas',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog/save-catalogs-collection',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/save-collection',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/save-entry-mode',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/save-partners',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/save-ruas',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/set-indicator1',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/set-indicator2',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/set-ruas',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/set-ruas-fixed',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463');
                INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateDate`,`UpdateTerminal`,`created_at`,`updated_at`) VALUES 
                 ('/pengkatalogan/katalog/tajuk-ddc',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/tajuk-pengarang',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/tajuk-pengarang-dollar',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/tajuk-subyek',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/tajuk-subyek-dollar',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/test-bibid',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/test-controlnumber',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/upload-cover',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/upload-konten-digital',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729697','1463729697'),
                 ('/pengkatalogan/katalog/validate-required-simple-form',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/pengkatalogan/katalog/validate-simple-bibliografis',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240463','1451240463'),
                 ('/pengkatalogan/katalog/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240462','1451240462'),
                 ('/pengkatalogan/katalog/viewkarantina',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729698','1463729698'),
                 ('/setting/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/akuisisi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/akuisisi/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/kategori-koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/kategori-koleksi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/kategori-koleksi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/kategori-koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/kategori-koleksi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/kategori-koleksi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/lembar-kerja-akuisisi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/lembar-kerja-akuisisi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/lembar-kerja-akuisisi/is-akuisisi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/lokasi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/lokasi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/lokasi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/lokasi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/lokasi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/lokasi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/master-djkn/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/akuisisi/master-djkn/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/akuisisi/master-djkn/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/akuisisi/master-djkn/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/akuisisi/master-djkn/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/akuisisi/master-djkn/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/akuisisi/mata-uang/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/mata-uang/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/mata-uang/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/mata-uang/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240469','1451240469'),
                 ('/setting/akuisisi/mata-uang/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/mata-uang/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/media-koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/media-koleksi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/media-koleksi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/media-koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/media-koleksi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/media-koleksi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/nomor-induk/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/nomor-induk/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/rekanan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/akuisisi/rekanan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/rekanan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/akuisisi/rekanan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/rekanan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/rekanan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240470','1451240470'),
                 ('/setting/akuisisi/sumber-koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/akuisisi/sumber-koleksi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/akuisisi/sumber-koleksi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/akuisisi/sumber-koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/akuisisi/sumber-koleksi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/akuisisi/sumber-koleksi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/audio/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/audio/audio-bukutamu/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/audio/audio-bukutamu/delete-audio',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/audio/audio-bukutamu/file-setting',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/audio/audio-bukutamu/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/audio/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/audio/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/checkpoint/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/locations/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/locations/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/locations/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/locations/file-upload',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/locations/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/locations/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/locations/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/memberguesses/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/memberguesses/anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463728968','1463728968'),
                 ('/setting/checkpoint/memberguesses/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/memberguesses/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/memberguesses/delete-group',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/checkpoint/memberguesses/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/memberguesses/nonanggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463728968','1463728968'),
                 ('/setting/checkpoint/memberguesses/rombongan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463728968','1463728968'),
                 ('/setting/checkpoint/memberguesses/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/memberguesses/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/checkpoint/tujuan-kunjungan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1467182034','1467182034'),
                 ('/setting/checkpoint/tujuan-kunjungan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1467181905','1467181905'),
                 ('/setting/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/deposit/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1538027102','1538027102'),
                 ('/setting/deposit/deposit-bahan-pustaka/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1538027928','1538027928'),
                 ('/setting/deposit/deposit-group-ws/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1538027438','1538027438'),
                 ('/setting/deposit/deposit-kode-wilayah/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1538027762','1538027762'),
                 ('/setting/deposit/deposit-ws/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1538027596','1538027596'),
                 ('/setting/deposit/lembar-kerja-deposit/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1538027596','1538027596'),
                 ('/setting/digitalcollection/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/digitalcollection/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/digitalcollection/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/digitalcollection/faced-setting/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/faced-setting/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/digitalcollection/history-digital-collection/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/history-digital-collection/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/history-opac/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/history-opac/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/koleksi-sering-didownload/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1465400717','1465400717'),
                 ('/setting/digitalcollection/koleksi-sering-didownload/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1465400716','1465400716'),
                 ('/setting/digitalcollection/koleksi-sering-dipinjam/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/koleksi-sering-dipinjam/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/koleksi-terbaru/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/koleksi-terbaru/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/koleksi-unggulan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/digitalcollection/koleksi-unggulan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/koleksi-unggulan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/digitalcollection/koleksi-unggulan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/koleksi-unggulan/pilih-judul',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/digitalcollection/koleksi-unggulan/tambah',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/digitalcollection/koleksi-unggulan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729713','1463729713'),
                 ('/setting/digitalcollection/usulan-koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/digitalcollection/usulan-koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/katalog/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/katalog/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/katalog/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/katalog/entri-form/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/katalog/entri-form/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/katalog/format-kartu/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/format-kartu/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/katalog/format-kartu/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/katalog/format-kartu/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/katalog/format-kartu/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/katalog/format-kartu/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240471','1451240471'),
                 ('/setting/katalog/kata-sandang/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/katalog/kata-sandang/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/katalog/kata-sandang/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/katalog/kata-sandang/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/katalog/kata-sandang/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/katalog/kata-sandang/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729704','1463729704'),
                 ('/setting/katalog/kelas-besar/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/katalog/kelas-besar/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/katalog/kelas-besar/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/katalog/kelas-besar/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/katalog/kelas-besar/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/katalog/kelas-besar/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/katalog/lembar-kerja/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/lembar-kerja/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/lembar-kerja/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/lembar-kerja/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/lembar-kerja/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/lembar-kerja/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/parameter-katalog-detail/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/parameter-katalog-detail/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/parameter-katalog-detail/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/parameter-katalog/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/parameter-katalog/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/penyedia-katalog/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/penyedia-katalog/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/penyedia-katalog/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/penyedia-katalog/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240472','1451240472'),
                 ('/setting/katalog/penyedia-katalog/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/penyedia-katalog/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/referensi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/referensi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/referensi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/referensi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/referensi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/referensi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/tag/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/katalog/tag/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/tag/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/katalog/tag/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/tag/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/katalog/tag/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240473','1451240473'),
                 ('/setting/katalog/warna-ddc/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/katalog/warna-ddc/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/katalog/warna-ddc/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/katalog/warna-ddc/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/katalog/warna-ddc/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/katalog/warna-ddc/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/loker/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/loker/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/jaminan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/jaminan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-loker/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-loker/createlocker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-loker/deletelocker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-loker/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-loker/load-selecter-locations',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-loker/uang-jaminan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-loker/updatelocker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-loker/viewlocker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-uang-jaminan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/loker/master-uang-jaminan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-uang-jaminan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/loker/master-uang-jaminan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/master-uang-jaminan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/loker/master-uang-jaminan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729715','1463729715'),
                 ('/setting/loker/masterpelanggaran/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/loker/masterpelanggaran/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/loker/masterpelanggaran/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/loker/masterpelanggaran/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/loker/masterpelanggaran/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/loker/masterpelanggaran/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/member/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/member/agama/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/agama/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/member/agama/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/member/agama/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/member/agama/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/member/agama/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240474','1451240474'),
                 ('/setting/member/biaya-pendaftaran/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-pendaftaran/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-pendaftaran/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-pendaftaran/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-pendaftaran/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-pendaftaran/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-perpanjangan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-perpanjangan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-perpanjangan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-perpanjangan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-perpanjangan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/biaya-perpanjangan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/cetak-bebas-pustaka/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/cetak-bebas-pustaka/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/cetak-bebas-pustaka/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/cetak-bebas-pustaka/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/cetak-bebas-pustaka/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/cetak-bebas-pustaka/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240475','1451240475'),
                 ('/setting/member/entri-anggota/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/entri-anggota/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/fakultas/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/fakultas/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/fakultas/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/fakultas/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/fakultas/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/fakultas/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/jenis-anggota/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jenis-anggota/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/jenis-anggota/default-kategori',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/jenis-anggota/default-lokasi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/jenis-anggota/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/jenis-anggota/detail-histori',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/jenis-anggota/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/jenis-anggota/save-kategori',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jenis-anggota/save-lokasi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jenis-anggota/test',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/jenis-anggota/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/jenis-anggota/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240476','1451240476'),
                 ('/setting/member/jenis-kelamin/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jenis-kelamin/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jenis-kelamin/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jenis-kelamin/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jenis-kelamin/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jenis-kelamin/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jurusan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jurusan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jurusan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jurusan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jurusan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/jurusan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/kartu-anggota/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kartu-anggota/aktifkan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/kartu-anggota/ambil',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kartu-anggota/detail',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kartu-anggota/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240477','1451240477'),
                 ('/setting/member/kartu-anggota/upload',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kartu-anggota/upload-kartu-belakang',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kelas/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/kelas/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kelas/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kelas/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kelas/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kelas/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240478','1451240478'),
                 ('/setting/member/kelompok-umur/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/kelompok-umur/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/kelompok-umur/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/kelompok-umur/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/kelompok-umur/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/kelompok-umur/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/kependudukan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/kependudukan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/kependudukan/custom',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/kependudukan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/kependudukan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729705','1463729705'),
                 ('/setting/member/kependudukan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/kependudukan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/masa-berlaku-anggota/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/masa-berlaku-anggota/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/masa-berlaku-anggota/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/masa-berlaku-anggota/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/masa-berlaku-anggota/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/masa-berlaku-anggota/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240479','1451240479'),
                 ('/setting/member/master-jenis-identitas/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-jenis-identitas/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-jenis-identitas/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-jenis-identitas/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-jenis-identitas/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-jenis-identitas/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-pekerjaan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-pekerjaan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-pekerjaan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-pekerjaan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-pekerjaan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/master-pekerjaan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/pendidikan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/pendidikan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/pendidikan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/pendidikan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/pendidikan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/pendidikan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/program-studi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/program-studi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/program-studi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/program-studi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/program-studi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/program-studi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/member/redaksi-keanggotaan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/member/redaksi-keanggotaan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/member/redaksi-keanggotaan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/member/redaksi-keanggotaan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/member/redaksi-keanggotaan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/member/redaksi-keanggotaan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240480','1451240480'),
                 ('/setting/opac/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/opac/booking-setting/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/opac/booking-setting/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/opac/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/opac/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/opac/faced-setting/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/opac/faced-setting/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/opac/history-opac/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/opac/history-opac/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/opac/koleksi-sering-dipinjam/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/opac/koleksi-sering-dipinjam/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/opac/koleksi-terbaru/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/opac/koleksi-terbaru/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/opac/koleksi-unggulan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/opac/koleksi-unggulan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/opac/koleksi-unggulan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/opac/koleksi-unggulan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/opac/koleksi-unggulan/pilih-judul',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/opac/koleksi-unggulan/tambah',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/opac/koleksi-unggulan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240483','1451240483'),
                 ('/setting/opac/usulan-koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/opac/usulan-koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/sirkulasi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/holiday/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/holiday/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/holiday/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/holiday/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/holiday/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/holiday/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-bahan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/sirkulasi/jenis-bahan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/sirkulasi/jenis-bahan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/sirkulasi/jenis-bahan/detail-histori',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/sirkulasi/jenis-bahan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/sirkulasi/jenis-bahan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/sirkulasi/jenis-bahan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/sirkulasi/jenis-denda/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-denda/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-denda/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-denda/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-denda/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-denda/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-pelanggaran/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-pelanggaran/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-pelanggaran/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-pelanggaran/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-pelanggaran/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/jenis-pelanggaran/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/kelompok-pelanggaran/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/kelompok-pelanggaran/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/kelompok-pelanggaran/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/kelompok-pelanggaran/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240484','1451240484'),
                 ('/setting/sirkulasi/kelompok-pelanggaran/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/kelompok-pelanggaran/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/lokasi-peminjaman/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/lokasi-peminjaman/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/lokasi-peminjaman/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/lokasi-peminjaman/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/lokasi-peminjaman/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/lokasi-peminjaman/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/peraturan-peminjaman-hari/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-hari/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-hari/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-hari/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/peraturan-peminjaman-hari/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-hari/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-tanggal/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-tanggal/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-tanggal/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-tanggal/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-tanggal/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman-tanggal/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240486','1451240486'),
                 ('/setting/sirkulasi/peraturan-peminjaman/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/peraturan-peminjaman/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/peraturan-peminjaman/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/peraturan-peminjaman/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/peraturan-peminjaman/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/peraturan-peminjaman/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240485','1451240485'),
                 ('/setting/sirkulasi/setting-transaksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/sirkulasi/setting-transaksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729714','1463729714'),
                 ('/setting/sms/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/setting/sms/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/sms/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/sms/history-sms/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/sms/history-sms/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/sms/sms-belum-jatuh-tempo/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/sms/sms-belum-jatuh-tempo/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/sms/sms-jatuh-tempo/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/setting/sms/sms-jatuh-tempo/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729716','1463729716'),
                 ('/setting/sms/sms-manual/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/setting/sms/sms-manual/bind-penduduk',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/setting/sms/sms-manual/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/setting/sms/sms-manual/detail-kependudukan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/setting/sms/sms-manual/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729717','1463729717'),
                 ('/setting/umum/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/umum/data-perpustakaan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/umum/data-perpustakaan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/umum/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/historydata/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729707','1463729707'),
                 ('/setting/umum/historydata/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729707','1463729707'),
                 ('/setting/umum/historydata/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729707','1463729707'),
                 ('/setting/umum/historydata/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/umum/historydata/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729707','1463729707'),
                 ('/setting/umum/historydata/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729706','1463729706'),
                 ('/setting/umum/jam-buka/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729707','1463729707'),
                 ('/setting/umum/jam-buka/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729707','1463729707'),
                 ('/setting/umum/jam-buka/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729707','1463729707'),
                 ('/setting/umum/jam-buka/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729707','1463729707'),
                 ('/setting/umum/jenis-perpustakaan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/jenis-perpustakaan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/jenis-perpustakaan/custom',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/jenis-perpustakaan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/jenis-perpustakaan/formdaftaranggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/formeditanggotaonline',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/formentrianggotaonline',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/formentripeminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/formentripengembalian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/forminfoanggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/jenis-perpustakaan/save-custom',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/jenis-perpustakaan/save-daftar-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/save-edit-anggota-online',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/save-entri-anggota-online',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/save-entri-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/save-entri-pengembalian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/save-info-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/jenis-perpustakaan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/jenis-perpustakaan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/layanan-sabtu-dan-minggu/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/layanan-sabtu-dan-minggu/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/mail-server/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/mail-server/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/mail-server/custom',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/mail-server/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/mail-server/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/mail-server/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/mail-server/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729708','1463729708'),
                 ('/setting/umum/menu/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/menu/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/menu/custom',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/menu/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/menu/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/menu/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/menu/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/perpustakaan-daerah/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/perpustakaan-daerah/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/perpustakaan-daerah/custom',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/perpustakaan-daerah/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/perpustakaan-daerah/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/perpustakaan-daerah/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/perpustakaan-daerah/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729709','1463729709'),
                 ('/setting/umum/role/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/role/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/role/custom',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/role/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/role/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/role/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/role/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/setting-update/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/setting-update/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/sms-gateway/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/sms-gateway/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/unit-kerja/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/umum/unit-kerja/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/unit-kerja/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240482','1451240482'),
                 ('/setting/umum/unit-kerja/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/unit-kerja/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/unit-kerja/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240481','1451240481'),
                 ('/setting/umum/user/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729712','1463729712'),
                 ('/setting/umum/user/change-password',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/custom',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/delete-history',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/history',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/user/my-history',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/pencarian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/reset-password',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729711','1463729711'),
                 ('/setting/umum/user/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/backup-data/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/setting/umum/backup-data/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729710','1463729710'),
                 ('/sirkulasi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451239629','1451239629'),
                 ('/sirkulasi/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240265','1451240265'),
                 ('/sirkulasi/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240264','1451240264'),
                 ('/sirkulasi/koleksi-dipesan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/koleksi-dipesan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/koleksi-dipesan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/pelanggaran/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/pelanggaran/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/pelanggaran/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/pelanggaran/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/pelanggaran/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/pelanggaran/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/peminjaman/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240265','1451240265'),
                 ('/sirkulasi/peminjaman/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240265','1451240265'),
                 ('/sirkulasi/peminjaman/create-susulan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/peminjaman/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240265','1451240265'),
                 ('/sirkulasi/peminjaman/detail-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/peminjaman/hapus-item',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/peminjaman/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240265','1451240265'),
                 ('/sirkulasi/peminjaman/print-kuitansi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/peminjaman/simpan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729701','1463729701'),
                 ('/sirkulasi/peminjaman/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240265','1451240265'),
                 ('/sirkulasi/peminjaman/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240265','1451240265'),
                 ('/sirkulasi/peminjaman/view-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/create-susulan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/detail-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/hapus-item',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/simpan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/simpan-pelanggaran',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/pengembalian/view-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/perpanjangan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1471527609','1471527609'),
                 ('/sirkulasi/perpanjangan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1471527906','1471527906'),
                 ('/sirkulasi/perpanjangan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1471527612','1471527612'),
                 ('/sirkulasi/print/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/print/cetak-slip-pelanggaran',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/print/cetak-slip-pengembalian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/print/print-kuitansi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/read-onlocation/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/read-onlocation/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/stockopname/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/stockopname/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/stockopname/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/stockopname/detail',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/stockopname/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/stockopname/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702');
                INSERT INTO `auth_item` (`name`,`type`,`description`,`rule_name`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateDate`,`UpdateTerminal`,`created_at`,`updated_at`) VALUES 
                 ('/sirkulasi/stockopname/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729702','1463729702'),
                 ('/sirkulasi/stockopname/view-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/stockopnamedetail/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/stockopnamedetail/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/stockopnamedetail/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/stockopnamedetail/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/stockopnamedetail/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/stockopnamedetail/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/penerimaan-koleksi/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/penerimaan-koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/penerimaan-koleksi/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/penerimaan-koleksi/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/penerimaan-koleksi/detail',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/penerimaan-koleksi/view-koleksi',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/penerimaan-koleksi/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/sirkulasi/penerimaan-koleksi/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/site/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/site/error',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/site/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451241335','1451241335'),
                 ('/site/login',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/site/logout',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729724','1463729724'),
                 ('/survey/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/data/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/data/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/data/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/data/detail-histori',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/survey/data/hasil-survey',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729703','1463729703'),
                 ('/survey/data/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/data/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/data/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/default/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/default/pertanyaan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/survey-isian/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/survey-isian/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/survey-isian/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/survey-isian/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/survey-isian/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/survey-isian/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/survey-pertanyaan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pertanyaan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pertanyaan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pertanyaan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240467','1451240467'),
                 ('/survey/survey-pertanyaan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pertanyaan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pilihan/*',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pilihan/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pilihan/delete',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pilihan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pilihan/update',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('/survey/survey-pilihan/view',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451240468','1451240468'),
                 ('akuisisi',1,'role untuk akuisisi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463729548','1463729548'),
                 ('Baca Ditempat',1,'Role untuk layanan konten digital',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1474600463','1474600463'),
                 ('Buku Tamu',1,'role untuk buku tamu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1474538438','1474538438'),
                 ('Function CatColl',1,'Fungsi Yang digunaan untuk modul Koleksi dan Katalog',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463730897','1463730897'),
                 ('Ganti Password Sendiri',1,'untuk ganti password sendiri',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1477643978','1477646555'),
                 ('katalog',1,'Rule untuk pengkatalogan',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463975667','1463975785'),
                 ('Keanggotaan',1,'role untuk keanggotaan',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1463730531','1463730594'),
                 ('Laporan',1,'Role untuk laporan\r\n',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1474600623','1474600623'),
                 ('Layanan Konten Digital',1,'Role untuk layanan konten digital',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1474538879','1474538879'),
                 ('Locker',1,'role untuk locker',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1474538021','1474538021'),
                 ('Opac',1,'role untuk opac',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1474538756','1474538756'),
                 ('Sirkulasi',1,'Peminjaman dan Pengembalian',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1464079673','1464079961'),
                 ('superadmin',1,'Role untuk superadmin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1451238381','1451240737'),
                 ('Survey',1,'role untuk survey',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1474538156','1474538156'),
                ('/setting/umum/bahasa/index', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1510908843', '1510908843'),
                ('/setting/member/perpanjangan-keanggotaan-mandiri/*', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('/setting/member/perpanjangan-keanggotaan-mandiri/index', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('/laporan/sms/sms-terkirim', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1463729723', '1463729723'),
                ('/laporan/anggota/raport-siswa', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1463729717', '1463729717'),
                ('/sirkulasi/pengembalian/create?for=ep', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1463729702', '1463729702'),
                ('/setting/umum/record-indexing/index', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1517892778', '1517892778'),
                ('/setting/umum/registrasi/index', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('/setting/umum/harvest-tajuk/*', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('/setting/umum/harvest-tajuk/import', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('/setting/umum/harvest-tajuk/index', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('/setting/umum/registrasi/*', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('/sirkulasi/pengembalian/create?for=epm', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1463729702', '1463729702'),
                ('/setting/umum/record-indexing/*', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1517892778', '1517892778'),
                ('/laporan/artikel/*', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1463729717', '1463729717'),             
                ('/laporan/artikel/index', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1463729717', '1463729717'),             
                ('/laporan/artikel/artikel-logdownload', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1463729717', '1463729717'),             
                ('/laporan/opac/opac-logdownload', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1527623081', '1527623081');
                /*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
    
    
                --
                -- Definition of table `auth_item_child`
                --
    
                DROP TABLE IF EXISTS `auth_item_child`;
                CREATE TABLE `auth_item_child` (
                  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                  `CreateBy` int(11) DEFAULT NULL,
                  `CreateDate` datetime DEFAULT NULL,
                  `CreateTerminal` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                  `UpdateBy` int(11) DEFAULT NULL,
                  `UpdateDate` datetime DEFAULT NULL,
                  `UpdateTerminal` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
                  PRIMARY KEY (`parent`,`child`),
                  KEY `child` (`child`),
                  KEY `auth_item_child_createby` (`CreateBy`),
                  KEY `auth_item_child_updateby` (`UpdateBy`),
                  CONSTRAINT `auth_item_child_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
                  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
                  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
                  CONSTRAINT `auth_item_child_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    
                --
                -- Dumping data for table `auth_item_child`
                --
    
                /*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
                INSERT INTO `auth_item_child` (`parent`,`child`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateDate`,`UpdateTerminal`) VALUES 
                 ('akuisisi','/akuisisi/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('akuisisi','/gridview/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('akuisisi','/laporan/koleksi/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('akuisisi','/pengkatalogan/katalog/create&for=coll&rda=0',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('akuisisi','/pengkatalogan/katalog/create&for=coll&rda=1',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('akuisisi','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Baca Ditempat','/bacaditempat/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Baca Ditempat','/bacaditempat/koleksi-dibaca/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Baca Ditempat','/bacaditempat/pengembalian-koleksi-baca-ditempat/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Baca Ditempat','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Buku Tamu','/gridview/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Buku Tamu','/setting/checkpoint/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Buku Tamu','/setting/checkpoint/memberguesses/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Buku Tamu','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/artikel/index',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/artikel/bind-catalogs-article',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/artikel/save-catalogs-article',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/artikel/bind-catalogs-digital-article',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/artikel/upload-konten-digital-artikel',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/artikel/detail-histori-article',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/add-tag',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/auto-suggest-call-number',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/bibliografis-input',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/bind-catalogs-collection',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/bind-no-induk',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/bind-partners',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/cetak-kartu-proses',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/checkbox-process',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/convert-to-catalog-fields',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/create',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/create-model-bib-from-catalog',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/create-tag-simple',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/create-taglist-advance',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/create-taglist-clean',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/create-taglist-from-catalog',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/create-taglist-simple',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/create-taglist-to-biblio',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/entry-advance',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/entry-bib-by-worksheet',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/entry-simple',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/flash-message',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/get-datetime-now-str',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/get-dropdown',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/get-dropdown-konten-digital',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/get-dropdown-salinkatalog',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/get-message-checkbox-process',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/pilih-judul',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/pilih-judul-proses',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/reset-catalogs-collection',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/reset-konten-digital',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/salin-katalog',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/salin-katalog-proses',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/salin-katalog-sru',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/salin-katalog-upload',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/save',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/save-catalog-ruas',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/save-catalog-sub-ruas',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/save-catalogs-collection',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/save-collection',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/save-entry-mode',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/save-partners',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/save-ruas',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/set-indicator1',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/set-indicator2',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/set-ruas',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/set-ruas-fixed',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/tajuk-ddc',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/tajuk-pengarang',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/tajuk-pengarang-dollar',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/tajuk-subyek',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/tajuk-subyek-dollar',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/test-bibid',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/test-controlnumber',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/update',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/validate-required-simple-form',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Function CatColl','/pengkatalogan/katalog/validate-simple-bibliografis',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Ganti Password Sendiri','/setting/umum/user/change-password',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Ganti Password Sendiri','/setting/umum/user/my-history',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('katalog','/gridview/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('katalog','/laporan/katalog/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('katalog','/pengkatalogan/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('katalog','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Keanggotaan','/member/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/anggota/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/baca-ditempat/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/buku-tamu/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/deposit/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/katalog/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/koleksi/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/loker/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/opac/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/sirkulasi/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/sms/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','/laporan/artikel/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Laporan','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Layanan Konten Digital','/gridview/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Layanan Konten Digital','/lkd/history/pencarian-lanjut/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Layanan Konten Digital','/lkd/history/pencarian-sederhana/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Layanan Konten Digital','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Locker','/gridview/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Locker','/loker/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Locker','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Opac','/gridview/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Opac','/opac/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Opac','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/gridview/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/default/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/default/index',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/koleksi-dipesan/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/koleksi-dipesan/index',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pelanggaran/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pelanggaran/create',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pelanggaran/index',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pelanggaran/view',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/peminjaman/create',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/peminjaman/detail-anggota',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/peminjaman/hapus-item',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/peminjaman/index',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/peminjaman/print-kuitansi',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/peminjaman/simpan',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/peminjaman/view',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/peminjaman/view-koleksi',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pengembalian/create',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pengembalian/detail-anggota',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pengembalian/index',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pengembalian/simpan',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pengembalian/simpan-pelanggaran',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pengembalian/view',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/pengembalian/view-koleksi',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/perpanjangan/create',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/perpanjangan/index',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/print/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/print/cetak-slip-pelanggaran',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/print/cetak-slip-pengembalian',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/print/print-kuitansi',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/read-onlocation/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/read-onlocation/index',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','/sirkulasi/stockopname/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/admin/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/akuisisi/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/bacaditempat/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/backuprestore/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/datecontrol/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/deposit/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/gridview/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/laporan/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/lkd/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/loker/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/loker/transaksi/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/member/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/opac/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/pengkatalogan/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/setting/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/sirkulasi/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/site/index',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('superadmin','/survey/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Survey','/gridview/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Survey','/survey/*',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Survey','Function CatColl',NULL,NULL,NULL,NULL,NULL,NULL),
                 ('Sirkulasi', '/sirkulasi/pengembalian/create?for=epm', NULL, NULL, NULL, NULL, NULL, NULL);
                /*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
    
    
                --
                -- Definition of table `menu`
                --
    
                DROP TABLE IF EXISTS `menu`;
                CREATE TABLE `menu` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(128) NOT NULL,
                  `parent` int(11) DEFAULT NULL,
                  `route` varchar(256) DEFAULT NULL,
                  `order` int(11) DEFAULT NULL,
                  `data` text,
                  `CreateBy` int(11) DEFAULT NULL,
                  `CreateDate` datetime DEFAULT NULL,
                  `CreateTerminal` varchar(100) DEFAULT NULL,
                  `UpdateBy` int(11) DEFAULT NULL,
                  `UpdateTerminal` varchar(100) DEFAULT NULL,
                  `UpdateDate` datetime DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `parent` (`parent`),
                  KEY `menu_createby` (`CreateBy`),
                  KEY `menu_updateby` (`UpdateBy`),
                  CONSTRAINT `menu_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
                  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
                  CONSTRAINT `menu_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
                --
                -- Dumping data for table `menu`
                --
    
                /*!40000 ALTER TABLE `menu` DISABLE KEYS */;
                INSERT INTO `menu` (`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`) VALUES 
                 (2,'Dashboard',NULL,'/site/index',0,'fa-dashboard',NULL,NULL,NULL,NULL,NULL,NULL),
                 (3,'Akuisisi',NULL,'/akuisisi/default/index',1,'fa-book',NULL,NULL,NULL,NULL,NULL,NULL),
                 (4,'Entri Koleksi',3,'/pengkatalogan/katalog/create&for=coll&rda=0',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (5,'Daftar Koleksi',3,'/akuisisi/koleksi/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (6,'Jilid Koleksi',3,'/akuisisi/koleksi-jilid/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (7,'Kardeks Terbitan Berkala',3,'/akuisisi/kardeks-terbitan-berkala/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (8,'Daftar Usulan Koleksi',3,'/akuisisi/koleksi-usulan/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (9,'Import Data dari Excel',3,'/akuisisi/koleksi-import/index',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (10,'Karantina Koleksi',3,'/akuisisi/koleksi/karantina',10,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (11,'Katalog',NULL,'/pengkatalogan/katalog/index',3,'fa-book',NULL,NULL,NULL,NULL,NULL,NULL),
                 (12,'Entri Katalog',11,'/pengkatalogan/katalog/create&for=cat&rda=0',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (13,'Salin Katalog',11,'/pengkatalogan/katalog-salin/create',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (14,'Daftar Katalog',11,'/pengkatalogan/katalog/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (15,'Export Data Tag Katalog',11,'/pengkatalogan/katalog-export-data-tag/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (18,'Daftar Konten Digital',11,'/pengkatalogan/katalog-konten-digital/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (19,'Keranjang Katalog',11,'/pengkatalogan/katalog/keranjang',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (20,'Karantina Katalog',11,'/pengkatalogan/katalog/karantina',9,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (21,'Keanggotaan',NULL,'/member/member/index',4,'fa-user',NULL,NULL,NULL,NULL,NULL,NULL),
                 (22,'Entri Anggota',21,'/member/member/create',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (23,'Daftar Anggota',21,'/member/member/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (24,'Import Data dari Excel',21,'/member/member/import-anggota',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (26,'Daftar Sumbangan',21,'/member/sumbangan/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (27,'Daftar Perpanjangan',21,'/member/perpanjang/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (28,'Keranjang Anggota',21,'/member/member/keranjang-anggota',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (29,'Sirkulasi',NULL,'/sirkulasi/peminjaman/index',5,'fa-refresh',NULL,NULL,NULL,NULL,NULL,NULL),
                 (30,'Entri Peminjaman',29,'/sirkulasi/peminjaman/create',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (31,'Entri Peminjaman Susulan',29,'/sirkulasi/peminjaman/create-susulan',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (33,'Daftar Peminjaman',29,'/sirkulasi/peminjaman/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (34,'Entri Pengembalian',29,'/sirkulasi/pengembalian/create?for=ep',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (35,'Entri Pengembalian Susulan',29,'/sirkulasi/pengembalian/create-susulan',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (37,'Daftar Pengembalian',29,'/sirkulasi/pengembalian/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (38,'Stock Opname',29,'/sirkulasi/stockopname/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (39,'Data Pelanggaran',29,'/sirkulasi/pelanggaran/index',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (41,'Locker',NULL,'/loker/transaksi/index',6,'fa-key',NULL,NULL,NULL,NULL,NULL,NULL),
                 (42,'Peminjaman',41,'/loker/transaksi/peminjaman',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (43,'Pengembalian',41,'/loker/transaksi/pengembalian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (45,'Survey',NULL,'/survey/data/index',7,'fa-users',NULL,NULL,NULL,NULL,NULL,NULL),
                 (46,'Data Survey',45,'/survey/data/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (47,'Laporan',NULL,'/laporan/default/index',12,'fa-files-o',NULL,NULL,NULL,NULL,NULL,NULL),
                 (48,'Katalog',47,'/laporan/katalog/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (49,'Laporan Katalog Per Kriteria',48,'/laporan/katalog/katalog-perkriteria',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (50,'Laporan Kinerja User',48,'/laporan/katalog/kinerja-user',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (51,'Administrasi',NULL,'/setting/default/index',13,'fa-gears',NULL,NULL,NULL,NULL,NULL,NULL),
                 (52,'Pengaturan Akuisisi',51,'/setting/akuisisi/default/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (54,'Kategori Koleksi',52,'/setting/akuisisi/kategori-koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (55,'Keranjang Koleksi',3,'/akuisisi/koleksi/keranjang',9,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (59,'Daftar Transaksi',41,'/loker/default/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (60,'Daftar Peminjaman',59,'/loker/transaksi/daftar-peminjaman',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (61,'Daftar Pengembalian',59,'/loker/transaksi/daftar-pengembalian',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (64,'Opac',NULL,'/setting/opac/default/index',9,'fa fa-gears',NULL,NULL,NULL,NULL,NULL,NULL),
                 (65,'Riwayat Pencarian Sederhana',64,'/opac/history/pencarian-sederhana/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (66,'Riwayat Pencarian Browse',64,'/opac/history/browse/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (67,'Buku Tamu',NULL,'/setting/checkpoint/memberguesses/index',8,'fa fa-check-square',NULL,NULL,NULL,NULL,NULL,NULL),
                 (68,'Anggota',67,'/setting/checkpoint/memberguesses/anggota',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (69,'Non Anggota',67,'/setting/checkpoint/memberguesses/nonanggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (70,'Rombongan',67,'/setting/checkpoint/memberguesses/rombongan',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (71,'Baca Ditempat',NULL,'/bacaditempat/koleksi-dibaca/index',11,'fa fa-rss-square',NULL,NULL,NULL,NULL,NULL,NULL),
                 (72,'Layanan Koleksi Digital',NULL,'/lkd/default/index',10,'fa fa-gears',NULL,NULL,NULL,NULL,NULL,NULL),
                 (75,'Daftar Nama Sumber Perolehan',3,'/setting/akuisisi/rekanan/index',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (76,'Entri Koleksi (RDA)',3,'/pengkatalogan/katalog/create&for=coll&rda=1',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (78,'Entri Katalog (RDA)',11,'/pengkatalogan/katalog/create&for=cat&rda=1',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (79,'Daftar Koleksi Dipesan',29,'/sirkulasi/koleksi-dipesan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (80,'Anggota',71,'/bacaditempat/koleksi-dibaca/anggota',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (81,'Non Anggota',71,'/bacaditempat/koleksi-dibaca/nonanggota',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (82,'Pengembalian Koleksi Baca Ditempat',71,'/bacaditempat/pengembalian-koleksi-baca-ditempat/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (83,'Koleksi',47,'/laporan/koleksi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (84,'Laporan Koleksi Per Periodik',83,'/laporan/koleksi/periodik',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (85,'Laporan Buku Induk',83,'/laporan/koleksi/buku-induk',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (86,'Laporan Accesion List',83,'/laporan/koleksi/accession-list',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (87,'Laporan Ucapan Terima Kasih',83,'/laporan/koleksi/ucapan-terimakasih',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (88,'Laporan Usulan Koleksi',83,'/laporan/koleksi/usulan-koleksi',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (89,'Laporan Kinerja User',83,'/laporan/koleksi/kinerja-user',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (90,'Anggota',47,'/laporan/anggota/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (91,'Sirkulasi',47,'/laporan/sirkulasi/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (92,'Buku Tamu',47,'/laporan/buku-tamu/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (93,'Baca Ditempat',47,'/laporan/baca-ditempat/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (94,'Loker',47,'/laporan/loker/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (95,'Opac',47,'/laporan/opac/index',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (96,'SMS',47,'/laporan/sms/index',9,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (97,'Laporan Per Pendaftaran',90,'/laporan/anggota/perpendaftaran',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (98,'Laporan Per Perpanjangan',90,'/laporan/anggota/perpanjangan',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (100,'Laporan Sumbangan Anggota',90,'/laporan/anggota/sumbangan',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (101,'Laporan Bebas Pustaka',90,'/laporan/anggota/bebas-pustaka',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (102,'Laporan Kinerja User',90,'/laporan/anggota/kinerja-user',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (103,'Laporan Peminjaman',91,'/laporan/sirkulasi/laporan-peminjaman',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (104,'Laporan Perpanjangan Peminjaman',91,'/laporan/sirkulasi/perpanjangan-peminjaman',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (105,'Laporan Sangsi Pelanggaran Peminjaman',91,'/laporan/sirkulasi/sangsi-pelanggaran-peminjaman',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (106,'Laporan Koleksi Sering Dipinjam',91,'/laporan/sirkulasi/koleksi-sering-dipinjam',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (107,'Laporan Anggota Sering Meminjam',91,'/laporan/sirkulasi/anggota-sering-meminjam',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (108,'Laporan Kinerja User Peminjaman',91,'/laporan/sirkulasi/kinerja-user-peminjaman',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (109,'Laporan Kinerja User Pengembalian',91,'/laporan/sirkulasi/kinerja-user-pengembalian',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (110,'Laporan Pengembalian Terlambat',91,'/laporan/sirkulasi/pengembalian-terlambat',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (111,'Laporan Kunjungan Periodik',92,'/laporan/buku-tamu/kunjungan-periodik',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (112,'Laporan Kunjungan Khusus Anggota',92,'/laporan/buku-tamu/kunjungan-khusus-anggota',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (113,'Laporan Berdasarkan Koleksi',93,'/laporan/baca-ditempat/berdasarkan-koleksi',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (114,'Laporan Koleksi Sering Dibaca Ditempat',93,'/laporan/baca-ditempat/koleksi-sering-baca-ditempat',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (115,'Laporan Periodik',94,'/laporan/loker/laporan-periodik',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (116,'Laporan Sangsi Pelanggaran Loker',94,'/laporan/loker/laporan-sangsi-pelanggaran-loker',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (117,'Laporan Periodik',95,'/laporan/opac/laporan-periodik',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (118,'Laporan Periodik',96,'/laporan/sms/laporan-periodik',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (119,'Pengaturan Katalog',51,'/setting/katalog/default/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (120,'Pengaturan Keanggotaan',51,'/setting/member/default/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (121,'Pengaturan Sirkulasi',51,'/setting/sirkulasi/default/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (122,'Pengaturan Locker',51,'/setting/loker/default/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (123,'Pengaturan Opac',51,'/setting/opac/default/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (124,'Pengaturan LKD',51,'/setting/digitalcollection/default/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (125,'Pengaturan Umum',51,'/setting/umum/default/index',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (126,'Pengaturan SMS Gatway',51,'/setting/sms/default/index',10,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (127,'Pengaturan Audio',51,'/setting/audio/default/index',9,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (128,'Ruas Data Bibliografis',52,'/setting/akuisisi/lembar-kerja-akuisisi/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (129,'Jenis Sumber',52,'/setting/akuisisi/sumber-koleksi/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (130,'Bentuk Fisik',52,'/setting/akuisisi/media-koleksi/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (131,'Mata Uang',52,'/setting/akuisisi/mata-uang/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (132,'Master DJKN',52,'/setting/akuisisi/master-djkn/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (133,'Penomoran Koleksi',52,'/setting/akuisisi/nomor-induk/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (134,'Tag',119,'/setting/katalog/tag/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (135,'Refrensi',119,'/setting/katalog/referensi/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (136,'Klas Besar (DDC)',119,'/setting/katalog/kelas-besar/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (137,'Kata Sandang',119,'/setting/katalog/kata-sandang/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (138,'Jenis Bahan Pustaka',119,'/setting/katalog/lembar-kerja/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (139,'Format Kartu',119,'/setting/katalog/format-kartu/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (140,'Pengaturan Detail Katalog',119,'/setting/katalog/parameter-katalog-detail/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (141,'Penyedia Katalog',119,'/setting/katalog/penyedia-katalog/index',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (143,'Form Entri',119,'/setting/katalog/entri-form/index',9,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (144,'Pengaturan Lainnya',119,'/setting/katalog/parameter-katalog/index',10,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (145,'Kartu Anggota',120,'/setting/member/kartu-anggota/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (146,'Redaksi Keanggotaan',120,'/setting/member/redaksi-keanggotaan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (147,'Jenis Anggota',120,'/setting/member/jenis-anggota/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (148,'Jenis Identitas',120,'/setting/member/master-jenis-identitas/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (149,'Pekerjaan',120,'/setting/member/master-pekerjaan/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (150,'Pendidikan',120,'/setting/member/pendidikan/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (151,'Kelompok Umur',120,'/setting/member/kelompok-umur/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (152,'Kelas',120,'/setting/member/kelas/index',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (153,'Fakultas',120,'/setting/member/fakultas/index',9,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (154,'Jurusan',120,'/setting/member/jurusan/index',10,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (155,'Program Studi',120,'/setting/member/program-studi/index',11,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (156,'Jenis Kelamin',120,'/setting/member/jenis-kelamin/index',12,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (157,'Agama',120,'/setting/member/agama/index',13,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (158,'Data Kependudukan',120,'/setting/member/kependudukan/index',14,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (159,'Entri Keanggotaan',120,'/setting/member/entri-anggota/index',15,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (160,'Hari Libur',125,'/setting/sirkulasi/holiday/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (161,'Jenis Bahan',121,'/setting/sirkulasi/jenis-bahan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (162,'Kelompok Pelanggaran',121,'/setting/sirkulasi/kelompok-pelanggaran/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (163,'Jenis Denda',121,'/setting/sirkulasi/jenis-denda/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (164,'Jenis Pelanggaran',121,'/setting/sirkulasi/jenis-pelanggaran/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (165,'Jenis Akses',121,'/setting/sirkulasi/peraturan-peminjaman/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (166,'Peraturan Peminjaman (Tgl)',121,'/setting/sirkulasi/peraturan-peminjaman-tanggal/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (167,'Peraturan Peminjaman (Hari)',121,'/setting/sirkulasi/peraturan-peminjaman-hari/index',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (168,'Setting Transaksi',121,'/setting/sirkulasi/setting-transaksi/index',9,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (169,'Locker',122,'/setting/loker/master-loker/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (170,'Jaminan Peminjaman',122,'/setting/loker/jaminan/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (171,'Denda Pelanggaran',122,'/setting/loker/masterpelanggaran/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (172,'Koleksi Unggulan',123,'/setting/opac/koleksi-unggulan/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (173,'Koleksi Terbaru',123,'/setting/opac/koleksi-terbaru/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (174,'Koleksi Sering Di Pinjam',123,'/setting/opac/koleksi-sering-dipinjam/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (175,'Pemesanan Koleksi',123,'/setting/opac/booking-setting/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (176,'Pengaturan Faset',123,'/setting/opac/faced-setting/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (177,'Pengusulan Koleksi',123,'/setting/opac/usulan-koleksi/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (178,'Koleksi Unggulan',124,'/setting/digitalcollection/koleksi-unggulan/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (179,'Koleksi Terbaru',124,'/setting/digitalcollection/koleksi-terbaru/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (181,'Pengaturan Faset',124,'/setting/digitalcollection/faced-setting/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (182,'Pengusulan Koleksi',124,'/setting/digitalcollection/usulan-koleksi/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (183,'Jenis Perpustakaan',125,'/setting/umum/jenis-perpustakaan/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (184,'Unit Kerja',125,'/setting/umum/unit-kerja/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (185,'Mail Server',125,'/setting/umum/mail-server/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (186,'Menu',125,'/admin/menu/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (187,'Hak Akses',125,'/admin/assignment/index',5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (188,'Setting User',125,'/setting/umum/user/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (189,'Nama Perpustakaan',125,'/setting/umum/data-perpustakaan/index',7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (190,'Lokasi Ruang',125,'/setting/akuisisi/lokasi/index',8,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (191,'Lokasi Perpustakaan',125,'/setting/sirkulasi/lokasi-peminjaman/index',9,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (192,'Setting Update',125,'/setting/umum/setting-update/index',10,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (193,'Layanan Sabtu dan Minggu',125,'/setting/umum/layanan-sabtu-dan-minggu/index',11,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (194,'Jam Operasional Layanan',125,'/setting/umum/jam-buka/index',12,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (195,'Buku Tamu',127,'/setting/audio/audio-bukutamu/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (196,'Peminjaman Akan Jatuh Tempo',126,'/setting/sms/sms-belum-jatuh-tempo/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (197,'Peminjaman Setelah Jatuh Tempo',126,'/setting/sms/sms-jatuh-tempo/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (198,'Sms Manual',126,'/setting/sms/sms-manual/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (199,'History Sms',126,'/setting/sms/history-sms/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (200,'Riwayat Pencarian Lanjut',64,'/opac/history/pencarian-lanjut/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (201,'Riwayat Pencarian Sederhana',72,'/lkd/history/pencarian-sederhana/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (202,'Riwayat Pencarian Browse',72,'/lkd/history/browse/index',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (203,'Riwayat Pencarian Lanjut',72,'/lkd/history/pencarian-lanjut/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (204,'Role',125,'/admin/role/index',6,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (206,'Koleksi Sering Di Download',124,'/setting/digitalcollection/koleksi-sering-didownload/index',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (210,'Tujuan Kunjungan',211,'/setting/checkpoint/tujuan-kunjungan/index',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (211,'Pengaturan Buku Tamu',51,'/setting/checkpoint/tujuan-kunjungan/index',10,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (212,'Entri Perpanjangan',29,'/sirkulasi/perpanjangan/create',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (213,'Daftar Perpanjangan',29,'/sirkulasi/perpanjangan/index',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (214,'Laporan Anggota Sering Baca Ditempat',93,'/laporan/baca-ditempat/anggota-sering-baca-ditempat',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (215,'Laporan Non Anggota Sering Baca Ditempat',93,'/laporan/baca-ditempat/non-anggota-sering-baca-ditempat',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (216,'Laporan  Anggota Sering Berkunjung',92,'/laporan/buku-tamu/anggota-sering-berkunjung',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (217,'Backup Data',125,'/setting/umum/backup-data/index',13,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                 (218,'Harumin',NULL,NULL,-1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
                ('219', 'Entri Pengembalian Gabungan', '29', '/sirkulasi/pengembalian/create?for=epm', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('220', 'Laporan Raport Siswa', '90', '/laporan/anggota/raport-siswa', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('221', 'Laporan SMS Terkirim', '96', '/laporan/sms/sms-terkirim', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('222', 'Perpanjangan Keanggotaan Mandiri', '120', '/setting/member/perpanjangan-keanggotaan-mandiri/index', '16', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('223', 'Salin Tajuk', '125', '/setting/umum/harvest-tajuk/index', '16', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('224', 'Registrasi Aplikasi', '125', '/setting/umum/registrasi/index', '17', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('225', 'Pengaturan Bahasa', '125', '/setting/umum/bahasa/index', '18', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('226', 'Record Indexing', '125', '/setting/umum/record-indexing/index', '19', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('227', 'Artikel', '47', '/laporan/artikel/index', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('228', 'Laporan Artikel Download', '227', '/laporan/artikel/artikel-logdownload', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('229', 'Laporan OPAC Download', '95', '/laporan/opac/opac-logdownload', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('230', 'Setting Buku Tamu', '211', '/setting/checkpoint/memberguesses/setting-buku-tamu', '4', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('231', 'Daftar Koleksi Artikel', '11', '/pengkatalogan/artikel/index', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('232', 'Pengiriman Koleksi', '3', '/akuisisi/pengiriman-koleksi/create', '11', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('233', 'Verifikasi Koleksi Siap Layan', '29', '/sirkulasi/penerimaan-koleksi', '9', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
				('234', 'Modul SSKCKR', '125', '/setting/umum/module-sskckr/index', '20', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
                ('235', 'Clean Assets', '125', '/setting/umum/setting-update/clean-assets', '21', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

        
                /*!40000 ALTER TABLE `menu` ENABLE KEYS */;
    
    
    
    
                /*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
                /*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
                /*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
                /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
                /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
                /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
                /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
            ")->execute();

        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
        self::update31();
        self::update32();

        if ($err) {
            $this->getView()->registerJs('
                swal("Terjadi Kesalahan saat update database.");                   
                
            ');
            return $this->renderAjax('error', [
                'err' => $err]);
        }



        Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 500,
                    'icon' => 'fa fa-info-circle',
                    'message' => Yii::t('app','berhasil terupdate'),
                    'title' => 'Info',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);

        return $this->redirect('index');
    }

    /**
     * Script start for updating to version 3.1.
     */
    function update31(){
        //set default setting paramater if not exist
        if (!Yii::$app->config->get('FacedBahasaMax')){
            Yii::$app->config->set('FacedBahasaMax', 10);
        }
        if (!Yii::$app->config->get('FacedBahasaMin')){
            Yii::$app->config->set('FacedBahasaMin', 5);
        }
        if (!Yii::$app->config->get('FacedBahasaMaxLKD')){
            Yii::$app->config->set('FacedBahasaMaxLKD', 10);
        }
        if (!Yii::$app->config->get('FacedBahasaMinLKD')){
            Yii::$app->config->set('FacedBahasaMinLKD', 5);
        }
        if (!Yii::$app->config->get('OpacIndexer')){
            Yii::$app->config->set('OpacIndexer', 0);
        }
        if (!Yii::$app->config->get('FacedAuthorMaxArticle')){
            Yii::$app->config->set('FacedAuthorMaxArticle', 10);
        }
        if (!Yii::$app->config->get('FacedAuthorMinArticle')){
            Yii::$app->config->set('FacedAuthorMinArticle', 5);
        }
        if (!Yii::$app->config->get('FacedPublisherMaxArticle')){
            Yii::$app->config->set('FacedPublisherMaxArticle', 10);
        }
        if (!Yii::$app->config->get('FacedPublisherMinArticle')){
            Yii::$app->config->set('FacedPublisherMinArticle', 5);
        }
        if (!Yii::$app->config->get('FacedPublishLocationMaxArticle')){
            Yii::$app->config->set('FacedPublishLocationMaxArticle', 10);
        }
        if (!Yii::$app->config->get('FacedPublishLocationMinArticle')){
            Yii::$app->config->set('FacedPublishLocationMinArticle', 5);
        }
        if (!Yii::$app->config->get('FacedPublishYearMaxArticle')){
            Yii::$app->config->set('FacedPublishYearMaxArticle', 10);
        }
        if (!Yii::$app->config->get('FacedPublishYearMinArticle')){
            Yii::$app->config->set('FacedPublishYearMinArticle', 5);
        }
        if (!Yii::$app->config->get('FacedSubjectMaxArticle')){
            Yii::$app->config->set('FacedSubjectMaxArticle', 10);
        }
        if (!Yii::$app->config->get('FacedSubjectMinArticle')){
            Yii::$app->config->set('FacedSubjectMinArticle', 5);
        }
        if (!Yii::$app->config->get('FacedBahasaMaxArticle')){
            Yii::$app->config->set('FacedBahasaMaxArticle', 10);
        }
        if (!Yii::$app->config->get('FacedBahasaMinArticle')){
            Yii::$app->config->set('FacedBahasaMinArticle', 5);
        }
        if (!Yii::$app->config->get('language')){
            Yii::$app->config->set('language', 'idn');
        }




        $trans = Yii::$app->db->beginTransaction();
        try {
            
           //koreksi mas didik
            $command = Yii::$app->db->createCommand("
                -- beresin yang nyangkut2
                -- Delete Auth Assignment where user id not exist
                -- Delete userloclibforcol where location_library not exist
                -- Delete userloclibforloan where location_library not exist
                -- Delete userloclibforcol where user = 43 or user = 46
                -- Delete userloclibforloan where user = 43 or user = 46
                DELETE FROM auth_assignment WHERE user_id NOT IN (SELECT id FROM users);
                DELETE FROM userloclibforcol WHERE loclib_id NOT IN (SELECT id FROM location_library);
                DELETE FROM userloclibforloan WHERE loclib_id NOT IN (SELECT id FROM location_library);
                DELETE FROM userloclibforcol WHERE user_id in (SELECT id FROM users where (id=43 and username='denisyahreza') or (id=46 and username='superadmin'));
                DELETE FROM userloclibforloan WHERE user_id in (SELECT id FROM users where (id=43 and username='denisyahreza') or (id=46 and username='superadmin'));

                -- Set Source_id = 1 (pembelian)
                UPDATE collections SET Source_id = 1 where Source_id IS NULL;
                -- Jika nomor panggil koleksi NULL, update nomor panggil koleksi = nomor panggil katalog 
                UPDATE collections AS col, catalogs AS cat SET col.callnumber=cat.callnumber WHERE col.catalog_id=cat.id AND col.callnumber IS NULL;
                -- Set tanggal pengadaan = sekarang jika NULL
                UPDATE collections SET TanggalPengadaan = NOW() WHERE TanggalPengadaan IS NULL;
                -- Set currency = 'IDR' jika NULL
                UPDATE collections SET currency = 'IDR' where currency IS NULL;
                -- Set Partner_id=1098(Belum Ditentukan) jika NULL
                UPDATE collections SET Partner_id = 1098 WHERE Partner_id IS NULL;
                -- Set Media_id=2(Buku) jika NULL
                UPDATE collections SET Media_id = 2 WHERE Media_id IS NULL;
                -- Set Category_id=7(Koleksi Umum) jika NULL
                UPDATE collections SET Category_id = 7 WHERE Category_id IS NULL;
                -- Set Rule_id=1(Dapat Dipinjam) jika NULL
                UPDATE collections SET Rule_id = 1 WHERE Rule_id IS NULL;
                -- Set Harga=0 jika NULL
                UPDATE collections SET Price = 0 WHERE Price IS NULL;
                ")->execute();


            //tambah data ISBN di module salin katalog sumber: opac perpusnas
            $command = Yii::$app->db->createCommand("
                SET @id = (SELECT MAX(ID) FROM `librarysearchcriteria`) + 1; 
                INSERT INTO `librarysearchcriteria`(`ID`,`LIBRARYID`,`CRITERIANAME`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateDate`,`UpdateTerminal`)
                SELECT * FROM (SELECT @id AS `ID`, 5 AS LIBRARYID,'ISBN' AS CRITERIANAME,33 AS CreateBy,'2016-06-06 11:01:18' AS CreateDate,'::1' AS CreateTerminal,33 AS UpdateBy,'2016-06-06 11:01:18' AS UpdateDate,'::1' AS UpdateTerminal) AS tmp
                WHERE NOT EXISTS (SELECT `CRITERIANAME` FROM `librarysearchcriteria` WHERE librarysearchcriteria.`CRITERIANAME` = 'ISBN' 
                AND librarysearchcriteria.`LIBRARYID` = '5')
                ")->execute();

            //tambah data ISSN di module salin katalog sumber: opac perpusnas
            $command = Yii::$app->db->createCommand("
                SET @id = (SELECT MAX(ID) FROM `librarysearchcriteria`) + 1; 
                INSERT INTO `librarysearchcriteria`(`ID`,`LIBRARYID`,`CRITERIANAME`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateDate`,`UpdateTerminal`)
                SELECT * FROM (SELECT @id AS `ID`, 5 AS LIBRARYID,'ISSN' AS CRITERIANAME,33 AS CreateBy,'2016-06-06 11:01:18' AS CreateDate,'::1' AS CreateTerminal,33 AS UpdateBy,'2016-06-06 11:01:18' AS UpdateDate,'::1' AS UpdateTerminal) AS tmp
                WHERE NOT EXISTS (SELECT `CRITERIANAME` FROM `librarysearchcriteria` WHERE librarysearchcriteria.`CRITERIANAME` = 'ISSN' 
                AND librarysearchcriteria.`LIBRARYID` = '5')
                ")->execute();

            //tambah data fielddatas tag e tag 700 'id 72' di module salin katalog sumber: opac perpusnas
            $command = Yii::$app->db->createCommand("
                INSERT INTO `fielddatas`(`Field_id`,`Code`,`Name`,`Delimiter`,`SortNo`,`Repeatable`,`IsShow`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateDate`,`UpdateTerminal`)
                SELECT * FROM (SELECT 72 AS Field_id,'e' AS `Code`,'Sebagai' AS `Name`,'' AS `Delimiter`,5 AS `SortNo`,'\0' AS `Repeatable`,'' AS `IsShow`,33 AS `CreateBy`,'2018-07-19 13:23:35' AS `CreateDate`,'192.168.0.1' AS `CreateTerminal`,33 AS `UpdateBy`,'2018-07-19 13:23:35' AS `UpdateDate`,'192.168.0.1' AS `UpdateTerminal`) AS tmp
                WHERE NOT EXISTS (SELECT `Code` FROM `fielddatas` WHERE fielddatas.`Code` = 'e' AND fielddatas.`Field_id` = '72')
                ")->execute();

            //add nama_ibu
            $nama_ibu = OpacHelpers::columnExist('master_kependudukan','nama_ibu');
            if($nama_ibu == 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `master_kependudukan`   
                ADD COLUMN `nama_ibu` varchar(100)  COLLATE latin1_swedish_ci NULL after `namalengkap`;    
                ")->execute();
            }
            //add nama_kec
            $nama_kec = OpacHelpers::columnExist('master_kependudukan','nama_kec');
            if($nama_kec == 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `master_kependudukan`   
                ADD COLUMN `nama_kec` varchar(100)  COLLATE latin1_swedish_ci NULL after `kodekel`;
                ")->execute();
            }

            //add nama_kel
            $nama_kel = OpacHelpers::columnExist('master_kependudukan','nama_kel');
            if($nama_kel == 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `master_kependudukan` 
                ADD COLUMN `nama_kel` varchar(100)  COLLATE latin1_swedish_ci NULL after `nama_kec`;
                ")->execute();
            }

            //add nama_kab
            $nama_kab = OpacHelpers::columnExist('master_kependudukan','nama_kab');
            if($nama_kab == 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `master_kependudukan`
                ADD COLUMN `nama_kab` varchar(100)  COLLATE latin1_swedish_ci NULL after `nama_kel`;    
                ")->execute();
            }

            //add nama_prov
            $nama_prov = OpacHelpers::columnExist('master_kependudukan','nama_prov');
            if($nama_prov== 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `master_kependudukan`   
                ADD COLUMN `nama_prov` varchar(100)  COLLATE latin1_swedish_ci NULL after `nama_kab`;    
                ")->execute();
            }

            //master kependudukan
            $command = Yii::$app->db->createCommand("
                ALTER TABLE `master_kependudukan`
                -- ADD COLUMN `nama_ibu` varchar(100)  COLLATE latin1_swedish_ci NULL after `namalengkap`,
                CHANGE `al1` `al1` varchar(255)  COLLATE latin1_swedish_ci NULL after `nama_ibu`,
                CHANGE `rt` `rt` varchar(50)  COLLATE latin1_swedish_ci NULL after `al1`,
                CHANGE `rw` `rw` varchar(50)  COLLATE latin1_swedish_ci NULL after `rt`,
                CHANGE `kodekec` `kodekec` varchar(50)  COLLATE latin1_swedish_ci NULL after `rw`,
                CHANGE `kodekel` `kodekel` varchar(50)  COLLATE latin1_swedish_ci NULL after `kodekec`,
                -- ADD COLUMN `nama_kec` varchar(100)  COLLATE latin1_spanish_ci NULL after `kodekel`,
                -- ADD COLUMN `nama_kel` varchar(100)  COLLATE latin1_swedish_ci NULL after `nama_kec`,
                -- ADD COLUMN `nama_kab` varchar(100)  COLLATE latin1_swedish_ci NULL after `nama_kel`,
                -- ADD COLUMN `nama_prov` varchar(100)  COLLATE latin1_swedish_ci NULL after `nama_kab`,
                CHANGE `alamat` `alamat` varchar(255)  COLLATE latin1_swedish_ci NULL after `nama_prov`,
                CHANGE `lhrtempat` `lhrtempat` varchar(50)  COLLATE latin1_swedish_ci NULL after `alamat`,
                CHANGE `lhrtanggal` `lhrtanggal` varchar(50)  COLLATE latin1_swedish_ci NULL after `lhrtempat`,
                CHANGE `ttl` `ttl` varchar(50)  COLLATE latin1_swedish_ci NULL after `lhrtanggal`,
                CHANGE `umur` `umur` varchar(50)  COLLATE latin1_swedish_ci NULL after `ttl`,
                CHANGE `jk` `jk` int(11)   NULL after `umur`,
                CHANGE `jenis` `jenis` varchar(50)  COLLATE latin1_swedish_ci NULL after `jk`,
                CHANGE `status` `status` int(11)   NULL after `jenis`,
                CHANGE `sts` `sts` varchar(50)  COLLATE latin1_swedish_ci NULL COMMENT 'Status Kawin' after `status`,
                CHANGE `hub` `hub` varchar(50)  COLLATE latin1_swedish_ci NULL after `sts`,
                CHANGE `agama` `agama` int(50)   NULL after `hub`,
                CHANGE `agm` `agm` varchar(50)  COLLATE latin1_swedish_ci NULL after `agama`,
                CHANGE `pendidikan` `pendidikan` varchar(50)  COLLATE latin1_swedish_ci NULL after `agm`,
                CHANGE `pekerjaan` `pekerjaan` varchar(50)  COLLATE latin1_swedish_ci NULL after `pendidikan`,
                CHANGE `klain_fisik` `klain_fisik` varchar(50)  COLLATE latin1_swedish_ci NULL after `pekerjaan`,
                CHANGE `aktalhr` `aktalhr` varchar(50)  COLLATE latin1_swedish_ci NULL after `klain_fisik`,
                CHANGE `aktakawin` `aktakawin` varchar(50)  COLLATE latin1_swedish_ci NULL after `aktalhr`,
                CHANGE `aktacerai` `aktacerai` varchar(50)  COLLATE latin1_swedish_ci NULL after `aktakawin`,
                CHANGE `nocacat` `nocacat` varchar(50)  COLLATE latin1_swedish_ci NULL after `aktacerai`,
                CHANGE `CreateBy` `CreateBy` int(11)   NULL after `nocacat`,
                CHANGE `CreateDate` `CreateDate` datetime   NULL after `CreateBy`,
                CHANGE `CreateTerminal` `CreateTerminal` varchar(100)  COLLATE latin1_swedish_ci NULL after `CreateDate`,
                CHANGE `UpdateBy` `UpdateBy` int(11)   NULL after `CreateTerminal`,
                CHANGE `UpdateDate` `UpdateDate` datetime   NULL after `UpdateBy`,
                CHANGE `UpdateTerminal` `UpdateTerminal` varchar(100)  COLLATE latin1_swedish_ci NULL after `UpdateDate`;

             ")->execute();



            //check if table penduduk is exist
            $cekTablePenduduk = OpacHelpers::tableExist('penduduk');
            if($cekTablePenduduk == 0){
                $command = Yii::$app->db->createCommand("
                    CREATE TABLE `penduduk`(
                        `id` int(11) NOT NULL  auto_increment ,
                        `Nik` varchar(16) COLLATE latin1_swedish_ci NULL  ,
                        `Nama` varchar(255) COLLATE latin1_swedish_ci NULL  ,
                        `Tempat_Lahir` varchar(100) COLLATE latin1_swedish_ci NULL  ,
                        `Provinsi` varchar(50) COLLATE latin1_swedish_ci NULL  ,
                        `Kabupaten_Kota` varchar(100) COLLATE latin1_swedish_ci NULL  ,
                        `Kecamatan` varchar(100) COLLATE latin1_swedish_ci NULL  ,
                        `Kelurahan_desa` varchar(100) COLLATE latin1_swedish_ci NULL  ,
                        `TPS` varchar(100) COLLATE latin1_swedish_ci NULL  ,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET='latin1';
                ")->execute();
            }

            //check if table master_edisi_serial is exist
            $cekTableMaster_edisi_serial = OpacHelpers::tableExist('master_edisi_serial');
            if($cekTableMaster_edisi_serial == 0){
                $command = Yii::$app->db->createCommand("
                    CREATE TABLE `master_edisi_serial` (
                      `id` double NOT NULL AUTO_INCREMENT,
                      `tgl_edisi_serial` date DEFAULT NULL,
                      `no_edisi_serial` varchar(111) DEFAULT NULL,
                      `Catalog_id` double DEFAULT NULL,
                      `CreateBy` int(11) DEFAULT NULL,
                      `UpdateBy` int(11) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `unique` (`no_edisi_serial`),
                      KEY `edisiSerial_catalog` (`Catalog_id`),
                      CONSTRAINT `edisiSerial_catalog` FOREIGN KEY (`Catalog_id`) REFERENCES `catalogs` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                ")->execute();
            }

            //add kolom master_edisi_serial
            $master_edisi_serial = OpacHelpers::columnExist('master_edisi_serial','Catalog_id');
            if($master_edisi_serial== 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `master_edisi_serial` 
                ADD COLUMN `Catalog_id` double  COLLATE latin1_swedish_ci NULL AFTER `no_edisi_serial`;
                
                ALTER TABLE `master_edisi_serial`
                ADD CONSTRAINT `edisiSerial_catalog`
                FOREIGN KEY (`Catalog_id`) REFERENCES `catalogs` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;  
                ")->execute();
            }

            //check if table serial_articlefiles is exist
            $cekTableSerialArticleFiles = OpacHelpers::tableExist('serial_articlefiles');
            if($cekTableSerialArticleFiles == 0){
                $command = Yii::$app->db->createCommand("
                    CREATE TABLE `serial_articlefiles` (
                      `ID` int(11) NOT NULL AUTO_INCREMENT,
                      `Articles_id` double NOT NULL,
                      `FileURL` varchar(255) DEFAULT NULL,
                      `FileFlash` varchar(255) DEFAULT NULL,
                      `sizeFile` varchar(255) DEFAULT NULL,
                      `IsPublish` tinyint(4) DEFAULT '1',
                      `CreateBy` int(11) DEFAULT NULL,
                      `CreateDate` datetime DEFAULT NULL,
                      `CreateTerminal` varchar(100) DEFAULT NULL,
                      `UpdateBy` int(11) DEFAULT NULL,
                      `UpdateDate` datetime DEFAULT NULL,
                      `UpdateTerminal` varchar(100) DEFAULT NULL,
                      `IsFromMember` bit(1) DEFAULT b'0',
                      `Member_id` double DEFAULT NULL,
                      PRIMARY KEY (`ID`),
                      KEY `Articles_id` (`Articles_id`),
                      CONSTRAINT `serial_articlefiles_ibfk_1` FOREIGN KEY (`Articles_id`) REFERENCES `serial_articles` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                ")->execute();
            }

            //add kolom sizeFile
            $sizeFile = OpacHelpers::columnExist('serial_articlefiles','sizeFile');
            if($sizeFile== 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `serial_articlefiles` 
                ADD COLUMN `sizeFile` VARCHAR(255)  COLLATE latin1_swedish_ci NULL AFTER `FileFlash`;  
                ")->execute();
            }

            //check if table serial_articles is exist
            $cekTableSerialArticles = OpacHelpers::tableExist('serial_articles');
            if($cekTableSerialArticles == 0){
                $command = Yii::$app->db->createCommand("
                    CREATE TABLE `serial_articles`(
                        `id` double NOT NULL  auto_increment ,
                        `Article_type` varchar(255) COLLATE latin1_swedish_ci NULL  ,
                        `Title` varchar(700) COLLATE latin1_swedish_ci NOT NULL  ,
                        `Content` text COLLATE latin1_swedish_ci NULL  ,
                        `Creator` varchar(700) COLLATE latin1_swedish_ci NULL  ,
                        `Contributor` varchar(700) COLLATE latin1_swedish_ci NULL  ,
                        `StartPage` int(11) NULL  ,
                        `Pages` int(11) NULL  ,
                        `Subject` varchar(700) COLLATE latin1_swedish_ci NULL  ,
                        `DDC` varchar(255) COLLATE latin1_swedish_ci NULL  ,
                        `Call_Number` varchar(255) COLLATE latin1_swedish_ci NULL  ,
                        `EDISISERIAL` varchar(255) COLLATE latin1_swedish_ci NULL  ,
                        `TANGGAL_TERBIT_EDISI_SERIAL` date DEFAULT NULL,
                        `Catalog_id` double NULL  ,
                        `CreateBy` int(11) NULL  ,
                        `CreateDate` datetime NULL  ,
                        `CreateTerminal` varchar(100) COLLATE latin1_swedish_ci NULL  ,
                        `UpdateBy` int(11) NULL  ,
                        `UpdateDate` datetime NULL  ,
                        `UpdateTerminal` varchar(100) COLLATE latin1_swedish_ci NULL  ,
                        `ISOPAC` bit(1) NULL  DEFAULT b'0' ,
                        `Abstract` text,
                        PRIMARY KEY (`id`) ,
                        KEY `idx_title`(`Title`) ,
                        KEY `articles_catalog`(`Catalog_id`) ,
                        KEY `articles_createby`(`CreateBy`) ,
                        KEY `articles_updateby`(`UpdateBy`)
                    ) ENGINE=InnoDB DEFAULT CHARSET='latin1';
                ")->execute();
            }

            //check if serial_articles columnDataType TANGGAL_TERBIT_EDISI_SERIAL is date
            $DataType = OpacHelpers::columnDataType('serial_articles','TANGGAL_TERBIT_EDISI_SERIAL','date');
            if($DataType== 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE serial_articles MODIFY TANGGAL_TERBIT_EDISI_SERIAL DATE; 
                ")->execute();
            }

            //check if table logsdownload_article is exist
            $cekTablelogsdownload_article = OpacHelpers::tableExist('logsdownload_article');
            if($cekTablelogsdownload_article == 0){
                $command = Yii::$app->db->createCommand("
                    CREATE TABLE `logsdownload_article` (
                      `id` BIGINT(30) NOT NULL AUTO_INCREMENT,
                      `User_id` VARCHAR(50) DEFAULT NULL,
                      `ip` VARCHAR(15) DEFAULT NULL,
                      `articlefilesID` INT(11) DEFAULT NULL,
                      `waktu` DATETIME DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `fk_logsDownload_u_idx` (`User_id`),
                      KEY `fk_logsDownload_articlefiles_idx` (`articlefilesID`),
                      CONSTRAINT `fk_logsDownload_articlefiles` FOREIGN KEY (`articlefilesID`) REFERENCES `serial_articlefiles` (`ID`) ON DELETE SET NULL ON UPDATE NO ACTION,
                      CONSTRAINT `fk_logsDownload_memberid` FOREIGN KEY (`User_id`) REFERENCES `members` (`MemberNo`) ON DELETE NO ACTION ON UPDATE NO ACTION
                    ) ENGINE=INNODB DEFAULT CHARSET=latin1;
                ")->execute();
            }

            //check if table serial_articles is exist
            $cekTableSerialArticles = OpacHelpers::tableExist('serial_articles_repeatable');
            if($cekTableSerialArticles == 0){
                $command = Yii::$app->db->createCommand("
                CREATE TABLE `serial_articles_repeatable`(
                `ID` bigint(20) NOT NULL auto_increment , 
                `serial_article_ID` double NOT NULL , 
                `article_field` varchar(50) COLLATE latin1_swedish_ci NULL , 
                `value` varchar(111) COLLATE latin1_swedish_ci NULL , 
                `CreateBy` int(11) NULL , 
                `UpdateBy` int(11) NULL , 
                PRIMARY KEY (`ID`) , 
                KEY `SERIAL_ARTICLE_ID`(`serial_article_ID`) , 
                KEY `CREATE_USER`(`CreateBy`) , 
                KEY `UPDATE_USER`(`UpdateBy`) , 
                CONSTRAINT `CREATE_USER` 
                FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE , 
                CONSTRAINT `SERIAL_ARTICLE_ID` 
                FOREIGN KEY (`serial_article_ID`) REFERENCES `serial_articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE , 
                CONSTRAINT `UPDATE_USER` 
                FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE 
                ) ENGINE=InnoDB DEFAULT CHARSET='latin1' COLLATE='latin1_swedish_ci';
                ")->execute();
            }

            //alter worksheets

            //check if column is exist
            $cekTableCounterBarcode = OpacHelpers::tableExist('master_counter_barcode');
            if($cekTableCounterBarcode == 0){
                $command = Yii::$app->db->createCommand("
                CREATE TABLE `master_counter_barcode` (
                  `tahun` year(4) DEFAULT NULL,
                  `value` int(111) DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                ")->execute();
            }
            
            //add ISKARTOGRAFI
            $ISKARTOGRAFI = OpacHelpers::columnExist('worksheets','ISKARTOGRAFI');
            if($ISKARTOGRAFI== 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `worksheets`   
                ADD COLUMN `ISKARTOGRAFI` bit(1)   NULL DEFAULT b'0' after `ISMUSIK`;  
                ")->execute();
            }

            //add ISKARTOGRAFI
            $ISKARTOGRAFI = OpacHelpers::columnExist('users','PhotoUrl');
            if($ISKARTOGRAFI== 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `users` 
                ADD COLUMN `PhotoUrl` varchar(255)  COLLATE latin1_swedish_ci NULL after `status` , 
                CHANGE `Fullname` `Fullname` varchar(255)  COLLATE latin1_swedish_ci NOT NULL after `PhotoUrl`;  
                ")->execute();
            }

            //tambah user untuk keanggotaan online
            $command = Yii::$app->db->createCommand("
                INSERT INTO `users` (`ID`,`username`,`password`,`auth_key`,`password_hash`,`password_reset_token`,`status`,`PhotoUrl`,`Fullname`,`EmailAddress`,`IsActive`,`SesID`,`MaxDateSesID`,`ActivationCode`,`LoginAttemp`,`LastSubmtLogin`,`LastSuccess`,`Department_id`,`Branch_id`,`Role_id`,`IsCanResetUserPassword`,`IsCanResetMemberPassword`,`IsAdvanceEntryCatalog`,`IsAdvanceEntryCollection`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateDate`,`UpdateTerminal`,`created_at`,`updated_at`,`KIILastUploadDate`)
                SELECT * FROM (SELECT 1 AS ID,'keanggotaan-online' AS username,'E8D1373B4E7163F3D72BCB51B963ECF61FC2BAAF' AS PASSWORD,NULL AS auth_key,NULL AS password_hash,NULL AS password_reset_token,NULL AS `status`,NULL AS PhotoUrl,'Raminah SE' AS Fullname,'raminah_se@yahoo.com' AS EmailAddress,'' AS IsActive,NULL AS SesID,NULL AS MaxDateSesID,NULL AS ActivationCode,NULL AS LoginAttemp,NULL AS LastSubmtLogin,NULL AS LastSuccess,NULL AS Department_id,NULL AS Branch_id,6 AS Role_id,'' AS IsCanResetUserPassword,'' AS IsCanResetMemberPassword,'\0' AS IsAdvanceEntryCatalog,'\0' AS IsAdvanceEntryCollection,NULL AS CreateBy,'2015-01-26 00:00:00' AS CreateDate,'36.78.223.122',NULL AS UpdateBy,'2015-01-26 00:00:00','36.78.223.122' AS CreateTerminal,NULL AS created_at,NULL AS updated_at,NULL AS KIILastUploadDate) AS tmp
                WHERE NOT EXISTS (SELECT `username` FROM `users` WHERE users.`username` = 'keanggotaan-online' 
                AND users.`ID` = '1')
                ")->execute();

            //add Abstract
            $ISKARTOGRAFI = OpacHelpers::columnExist('serial_articles','Abstract');
            if($ISKARTOGRAFI== 0){
                $command = Yii::$app->db->createCommand("
                ALTER TABLE `serial_articles`   
                ADD COLUMN `Abstract` text NULL after `ISOPAC`;  
                ")->execute();
            }
            

            //query update sequence table fielddatas
            $command = Yii::$app->db->createCommand("
                UPDATE 
                fielddatas
                INNER JOIN
                (SELECT t.Field_id, t.Name, t.Code,
                       (@rn := IF(@f = field_id, @rn + 1,
                                  IF(@f := field_id, 1, 1)
                                 )
                       ) AS seq_no
                FROM (SELECT *
                      FROM fielddatas 
                      ORDER BY fielddatas.Field_id, fielddatas.Code
                    ) t CROSS JOIN
                    (SELECT @f := '', @rn := 0) params ) test
                ON fielddatas.Field_id = test.Field_id AND fielddatas.Code = test.Code
                SET fielddatas.SortNo = test.seq_no
                WHERE fielddatas.Field_id = test.Field_id AND fielddatas.Code = test.Code
            ")->execute();



            $command = Yii::$app->db->createCommand("
                ALTER TABLE `worksheets`
                    CHANGE `ISSERIAL` `ISSERIAL` tinyint(1)   NULL DEFAULT 0 after `DEPOSITFORMAT_CODE`,
                    -- ADD COLUMN `ISKARTOGRAFI` bit(1)   NULL DEFAULT b'0' after `ISMUSIK`,
                    CHANGE `CODE` `CODE` varchar(10)  COLLATE latin1_swedish_ci NOT NULL after `ISKARTOGRAFI`,
                    CHANGE `Keterangan` `Keterangan` varchar(255)  COLLATE latin1_swedish_ci NULL after `CODE`,
                    CHANGE `MaxPinjamKoleksi` `MaxPinjamKoleksi` int(11)   NULL DEFAULT 0 after `Keterangan`,
                    CHANGE `MaxLoanDays` `MaxLoanDays` int(11)   NULL DEFAULT 0 after `MaxPinjamKoleksi`,
                    CHANGE `DendaType` `DendaType` varchar(45)  COLLATE latin1_swedish_ci NULL DEFAULT 'Konstan' after `MaxLoanDays`,
                    CHANGE `DendaTenorJumlah` `DendaTenorJumlah` decimal(10,0)   NULL DEFAULT 0 after `DendaType`,
                    CHANGE `DendaTenorSatuan` `DendaTenorSatuan` varchar(45)  COLLATE latin1_swedish_ci NULL DEFAULT 'Hari' after `DendaTenorJumlah`,
                    CHANGE `DendaPerTenor` `DendaPerTenor` decimal(10,0)   NULL DEFAULT 0 after `DendaTenorSatuan`,
                    CHANGE `DendaTenorMultiply` `DendaTenorMultiply` int(11)   NULL DEFAULT 1 after `DendaPerTenor`,
                    CHANGE `SuspendMember` `SuspendMember` bit(1)   NULL DEFAULT b'0' after `DendaTenorMultiply`,
                    CHANGE `WarningLoanDueDay` `WarningLoanDueDay` int(11)   NULL DEFAULT 0 after `SuspendMember`,
                    CHANGE `SuspendType` `SuspendType` varchar(45)  COLLATE latin1_swedish_ci NULL DEFAULT 'Konstan' after `WarningLoanDueDay`,
                    CHANGE `SuspendTenorJumlah` `SuspendTenorJumlah` double   NULL DEFAULT 0 after `SuspendType`,
                    CHANGE `SuspendTenorSatuan` `SuspendTenorSatuan` varchar(45)  COLLATE latin1_swedish_ci NULL DEFAULT 'Hari' after `SuspendTenorJumlah`,
                    CHANGE `DaySuspend` `DaySuspend` int(11)   NULL DEFAULT 0 after `SuspendTenorSatuan`,
                    CHANGE `SuspendTenorMultiply` `SuspendTenorMultiply` int(11)   NULL DEFAULT 1 after `DaySuspend`,
                    CHANGE `DayPerpanjang` `DayPerpanjang` int(11)   NULL DEFAULT 0 after `SuspendTenorMultiply`,
                    CHANGE `CountPerpanjang` `CountPerpanjang` int(11)   NULL DEFAULT 0 after `DayPerpanjang`;
            ")->execute();


            //check if constrain is exist
            //alter serial serial_articlefiles
            $isExist = OpacHelpers::ConstraintExist('serial_articlefiles','serial_articlefiles_ibfk_1','FOREIGN KEY');
            if($isExist== 0){
                $command = Yii::$app->db->createCommand("
                    ALTER TABLE `serial_articlefiles`
                    ADD CONSTRAINT `serial_articlefiles_ibfk_1`
                    FOREIGN KEY (`Articles_id`) REFERENCES `serial_articles` (`id`);
                ")->execute();
            }


            //check if constrain is exist
            //alter serial serial_articles
            //add CONSTRAINT `articles_catalog`
            $isExist = OpacHelpers::ConstraintExist('serial_articles','articles_catalog','FOREIGN KEY');
            if($isExist== 0){
                $command = Yii::$app->db->createCommand("
                    ALTER TABLE `serial_articles`
                    ADD CONSTRAINT `articles_catalog`
                    FOREIGN KEY (`Catalog_id`) REFERENCES `catalogs` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
                ")->execute();
            }

            //add CONSTRAINT `articles_createby`
            $isExist = OpacHelpers::ConstraintExist('serial_articles','articles_createby','FOREIGN KEY');
            if($isExist== 0){
                $command = Yii::$app->db->createCommand("
                    ALTER TABLE `serial_articles`
                    ADD CONSTRAINT `articles_createby`
                    FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
                ")->execute();
            }

            //add CONSTRAINT `articles_updateby`
            $isExist = OpacHelpers::ConstraintExist('serial_articles','articles_updateby','FOREIGN KEY');
            if($isExist== 0){
                $command = Yii::$app->db->createCommand("
                    ALTER TABLE `serial_articles`
                    ADD CONSTRAINT `articles_updateby`
                    FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
                ")->execute();
            }


            //browse
            $command = Yii::$app->db->createCommand("
                DROP procedure IF EXISTS `BrowseOpac`;
            ")->execute();
            $command = Yii::$app->db->createCommand("              
                CREATE PROCEDURE `BrowseOpac`(
                    IN keyword TEXT,
                    IN keyword2 TEXT,
                    IN keyword3 TEXT,
                    IN isLKD TEXT,
                    IN Location TEXT
                    )
                BEGIN
                    IF isLKD = 1
                     THEN
                            IF keyword = 'Alphabetical' AND keyword2 = '' THEN
                            SET @query_as_string=CONCAT(\"SELECT 'A' AS `A` UNION ALL SELECT 'B' AS `B` UNION ALL SELECT 'C' AS `C` UNION ALL SELECT 'D' AS `D` UNION ALL SELECT 'E' AS `E` UNION ALL SELECT 'F' AS `F` UNION ALL SELECT 'G' AS `G` UNION ALL SELECT 'H' AS `H` UNION ALL SELECT 'I' AS `I` UNION ALL SELECT 'J' AS `J` UNION ALL SELECT 'K' AS `K` UNION ALL SELECT 'L' AS `L` UNION ALL SELECT 'M' AS `M` UNION ALL SELECT 'N' AS `N` UNION ALL SELECT 'O' AS `O` UNION ALL SELECT 'P' AS `P` UNION ALL SELECT 'Q' AS `Q` UNION ALL SELECT 'R' AS `R` UNION ALL SELECT 'S' AS `S` UNION ALL SELECT 'T' AS `T` UNION ALL SELECT 'U' AS `U` UNION ALL SELECT 'V' AS `V` UNION ALL SELECT 'W' AS `W` UNION ALL SELECT 'X' AS `X` UNION ALL SELECT 'Y' AS `Y` UNION ALL SELECT 'Z' AS `Z`\");             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;
                            END IF;
                            IF keyword = 'Alphabetical' AND keyword2 <> '' THEN
                            SET @query_as_string=CONCAT('
                             SELECT distinct CAT.',keyword2,' as name,COUNT(CAT.',keyword2,') as jml FROM catalogs CAT
                                                        INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id
                                                        WHERE CAT.isopac=1
                                                        AND CAT.',keyword2,' like \"',keyword3,'%\"  
                                                        GROUP BY name
                                                        ORDER BY jml desc
                                                        limit 0,20');             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;
                            END IF;
                            IF keyword2 = '' AND keyword <> 'Alphabetical' THEN
                            SET @query_as_string=CONCAT('
                                SELECT distinct CAT.',keyword,'  as name,COUNT(CAT.',keyword,' ) as jml FROM catalogs CAT
                                                        INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id
                                                        WHERE CAT.isopac=1
                                                        GROUP BY name
                                                        ORDER BY jml desc
                                                        limit 0,20 ');             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;
                            END IF;
                            IF keyword2 <> '' AND keyword <> 'Alphabetical' THEN
                            SET @query_as_string=CONCAT('
                                SELECT distinct CAT.',keyword2,' as name,COUNT(CAT.',keyword2,') as jml FROM catalogs CAT
                                                        INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id
                                                        WHERE CAT.isopac=1 AND
                                                        CAT.',keyword,' = \"',keyword3,'\"
                                                        GROUP BY name
                                                        ORDER BY jml desc
                                                        limit 0,20;');             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;    
                            END IF;
                    ELSE
                    IF Location <> 0
                     THEN
                            IF keyword = 'Alphabetical' AND keyword2 = '' THEN
                            SET @query_as_string=CONCAT(\"SELECT 'A' AS `A` UNION ALL SELECT 'B' AS `B` UNION ALL SELECT 'C' AS `C` UNION ALL SELECT 'D' AS `D` UNION ALL SELECT 'E' AS `E` UNION ALL SELECT 'F' AS `F` UNION ALL SELECT 'G' AS `G` UNION ALL SELECT 'H' AS `H` UNION ALL SELECT 'I' AS `I` UNION ALL SELECT 'J' AS `J` UNION ALL SELECT 'K' AS `K` UNION ALL SELECT 'L' AS `L` UNION ALL SELECT 'M' AS `M` UNION ALL SELECT 'N' AS `N` UNION ALL SELECT 'O' AS `O` UNION ALL SELECT 'P' AS `P` UNION ALL SELECT 'Q' AS `Q` UNION ALL SELECT 'R' AS `R` UNION ALL SELECT 'S' AS `S` UNION ALL SELECT 'T' AS `T` UNION ALL SELECT 'U' AS `U` UNION ALL SELECT 'V' AS `V` UNION ALL SELECT 'W' AS `W` UNION ALL SELECT 'X' AS `X` UNION ALL SELECT 'Y' AS `Y` UNION ALL SELECT 'Z' AS `Z`\");             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;
                            END IF;
                            IF keyword = 'Alphabetical' AND keyword2 <> '' THEN
                            SET @query_as_string=CONCAT('
                             SELECT distinct CAT.',keyword2,' as name,COUNT(CAT.',keyword2,') as jml FROM catalogs CAT
                                                        LEFT JOIN collections col ON col.Catalog_id = CAT.ID
                                                        WHERE CAT.isopac=1 
                                                        AND col.Location_id = ',Location,'
                                                        AND CAT.',keyword2,' like \"',keyword3,'%\"  
                                                        GROUP BY name
                                                        ORDER BY jml desc
                                                        limit 0,20');             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;
                            END IF;
                            IF keyword2 = '' AND keyword <> 'Alphabetical' THEN
                            SET @query_as_string=CONCAT('
                                SELECT distinct CAT.',keyword,'  as name,COUNT(CAT.',keyword,' ) as jml FROM catalogs CAT
                                                        LEFT JOIN collections col ON col.Catalog_id = CAT.ID
                                                        WHERE CAT.isopac=1
                                                        AND col.Location_id = ',Location,'
                                                        GROUP BY name
                                                        ORDER BY jml desc
                                                        limit 0,20 ');             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;
                            END IF;
                            IF keyword2 <> '' AND keyword <> 'Alphabetical' THEN
                            SET @query_as_string=CONCAT('
                                SELECT distinct CAT.',keyword2,' as name,COUNT(CAT.',keyword2,') as jml FROM catalogs CAT
                                                        LEFT JOIN collections col ON col.Catalog_id = CAT.ID
                                                        WHERE CAT.isopac=1
                                                        AND col.Location_id = ',Location,'          
                                                        AND
                                                        CAT.',keyword,' = \"',keyword3,'\"
                                                        GROUP BY name
                                                        ORDER BY jml desc
                                                        limit 0,20;');             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;    
                            END IF;
                    ELSE
                            IF keyword = 'Alphabetical' AND keyword2 = '' THEN
                            SET @query_as_string=CONCAT(\"SELECT 'A' AS `A` UNION ALL SELECT 'B' AS `B` UNION ALL SELECT 'C' AS `C` UNION ALL SELECT 'D' AS `D` UNION ALL SELECT 'E' AS `E` UNION ALL SELECT 'F' AS `F` UNION ALL SELECT 'G' AS `G` UNION ALL SELECT 'H' AS `H` UNION ALL SELECT 'I' AS `I` UNION ALL SELECT 'J' AS `J` UNION ALL SELECT 'K' AS `K` UNION ALL SELECT 'L' AS `L` UNION ALL SELECT 'M' AS `M` UNION ALL SELECT 'N' AS `N` UNION ALL SELECT 'O' AS `O` UNION ALL SELECT 'P' AS `P` UNION ALL SELECT 'Q' AS `Q` UNION ALL SELECT 'R' AS `R` UNION ALL SELECT 'S' AS `S` UNION ALL SELECT 'T' AS `T` UNION ALL SELECT 'U' AS `U` UNION ALL SELECT 'V' AS `V` UNION ALL SELECT 'W' AS `W` UNION ALL SELECT 'X' AS `X` UNION ALL SELECT 'Y' AS `Y` UNION ALL SELECT 'Z' AS `Z`\");             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;
                            END IF;
                            IF keyword = 'Alphabetical' AND keyword2 <> '' THEN
                            SET @query_as_string=CONCAT('
                             SELECT distinct CAT.',keyword2,' as name,COUNT(CAT.',keyword2,') as jml FROM catalogs CAT
                                                        
                                                        WHERE CAT.isopac=1
                                                        AND CAT.',keyword2,' like \"',keyword3,'%\"  
                                                        GROUP BY name
                                                        ORDER BY jml desc
                                                        limit 0,20');             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;
                            END IF;
                            IF keyword2 = '' AND keyword <> 'Alphabetical' THEN
                            SET @query_as_string=CONCAT('
                                SELECT distinct CAT.',keyword,'  as name,COUNT(CAT.',keyword,' ) as jml FROM catalogs CAT
                                                        WHERE CAT.isopac=1
                                                        GROUP BY name
                                                        ORDER BY jml desc
                                                        limit 0,20 ');             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;
                            END IF;
                            IF keyword2 <> '' AND keyword <> 'Alphabetical' THEN
                            SET @query_as_string=CONCAT('
                                SELECT distinct CAT.',keyword2,' as name,COUNT(CAT.',keyword2,') as jml FROM catalogs CAT
                                                        WHERE CAT.isopac=1 AND
                                                        CAT.',keyword,' = \"',keyword3,'\"
                                                        GROUP BY name
                                                        ORDER BY jml desc
                                                        limit 0,20;');             
                              PREPARE statement_1 FROM @query_as_string;
                              EXECUTE statement_1;
                              DEALLOCATE PREPARE statement_1;    
                            END IF;
                    END IF;
                    END IF;
                    END
            ")->execute();




            //countPencarianLanjutOpac1
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `countPencarianLanjutOpac1`;
            ")->execute();

            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `countPencarianLanjutOpac1`(
                        IN fAuthor TEXT,
                        IN fPublisher TEXT,
                        IN fPublishLoc TEXT,
                        IN fPublishYear TEXT,
                        IN fSubject TEXT,
                        IN fBahasa TEXT
                        )
                    BEGIN
                        SELECT COUNT(1)
                              FROM tempCariOpac
                              
                              WHERE
                              IF(fAuthor='',1=1,author = fAuthor) AND
                                   IF(fPublisher='',1=1,publisher = fPublisher) AND
                              IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
                              IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
                              IF(fSubject='',1=1,Subject = fSubject) AND
                              IF(fSubject='',1=1,SUBJECT LIKE fSubject);
                              
                                 
                        END
            ")->execute();


            //countPencarianSederhanaOpac1
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `countPencarianSederhanaOpac1`;
            ")->execute();

            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `countPencarianSederhanaOpac1`(
                IN fAuthor TEXT,
                IN fPublisher TEXT,
                IN fPublishLoc TEXT,
                IN fPublishYear TEXT,
                IN fSubject TEXT,
                IN fBahasa TEXT
                )
                BEGIN
                SELECT COUNT(1)
                      FROM tempCariOpac 
                      
                      WHERE 
                        IF(fAuthor='',1=1,author LIKE concat('%',concat(fAuthor,'%'))) AND
                      IF(fPublisher='',1=1,publisher LIKE fPublisher) AND
                      IF(fPublishLoc='',1=1,PublishLocation LIKE fPublishLoc) AND
                      IF(fPublishYear='',1=1,PublishYear LIKE fPublishYear) AND
                      IF(fBahasa='',1=1,bahasa LIKE fBahasa) AND
                      IF(fSubject='',1=1,Subject LIKE fSubject) ;
                      
                         
                END
            ")->execute();


            //facedArticle
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `facedArticle`;
            ")->execute();

            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `facedArticle`(
                    IN facedType TEXT,
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN maxFaced INT
                    )
                BEGIN
                IF facedType = 'Author'
                THEN
                    SELECT COALESCE(Creator_article,'-') Creator_article,COUNT(1) jml
                          FROM tempCariArticle 
                          
                          WHERE 
                          IF(fAuthor='',1=1,Creator_article LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fSubject='',1=1,subject_article = fSubject) AND
                          IF(fBahasa='',1=1,Languages = fBahasa)
                        GROUP BY Creator_article
                        ORDER BY jml DESC
                        LIMIT 0,maxFaced;              
                ELSE             
                IF facedType = 'Bahasa'
                THEN                  
                    SELECT Languages,COUNT(1) jml
                          FROM tempCariArticle 
                          
                          WHERE 
                          IF(fAuthor='',1=1,Creator_article LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fSubject='',1=1,subject_article = fSubject) AND
                          IF(fBahasa='',1=1,Languages = fBahasa)
                        GROUP BY Languages ORDER BY jml DESC
                        LIMIT 0,maxFaced;
                ELSE             
                IF facedType = 'Subject' 
                THEN
                    SELECT COALESCE(subject_article,'-') subject_article,COUNT(1) jml
                          FROM tempCariArticle 
                          
                          WHERE 
                          IF(fAuthor='',1=1,Creator_article LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fSubject='',1=1,subject_article = fSubject) AND
                          IF(fBahasa='',1=1,Languages = fBahasa)
                        GROUP BY subject_article ORDER BY jml DESC
                        LIMIT 0,maxFaced;
                ELSE             
                IF facedType = 'Publisher' 
                THEN
                    SELECT COALESCE(Publisher,'-') Publisher,COUNT(1) jml
                          FROM tempCariArticle 
                          
                          WHERE 
                          IF(fAuthor='',1=1,Creator_article LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fSubject='',1=1,subject_article = fSubject) AND
                          IF(fBahasa='',1=1,Languages = fBahasa)
                          
                        GROUP BY Publisher
                        ORDER BY jml DESC
                        LIMIT 0,maxFaced;
                END IF;
                END IF; 
                END IF; 
                END IF;         
                    END
            ")->execute();

            //facedAuthorOpac1
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `facedAuthorOpac1`;
            ")->execute();

            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `facedAuthorOpac1`(
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN maxFaced INT
                    )
                BEGIN
                    -- call explode_table(';',fAuthor,fPublisher,fPublishLoc,fPublishYear,fSubject);
                                  
                    -- SELECT  COALESCE(Value,'-') Author ,COUNT(1) jml FROM table2 group by COALESCE(Value,'-') order by jml desc LIMIT 0,maxFaced;    
                              
                    SELECT Author,COUNT(1) jml
                          FROM tempCariOpac 
                          
                          WHERE 
                          IF(fAuthor='',1=1,Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
                          IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
                          IF(fSubject='',1=1,SUBJECT = fSubject) AND
                          IF(fBahasa='',1=1,bahasa = fBahasa)
                        GROUP BY Author
                        ORDER BY jml DESC
                        LIMIT 0,maxFaced;    
                    END
            ")->execute();


            //facedBahasaOpac1
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `facedBahasaOpac1`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
               CREATE  PROCEDURE `facedBahasaOpac1`(
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN maxFaced INT
                    )
                BEGIN
                                  
                    SELECT bahasa,COUNT(1) jml
                          FROM tempCariOpac 
                          
                          WHERE 
                          IF(fAuthor='',1=1,Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
                          IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
                          IF(fSubject='',1=1,SUBJECT = fSubject) AND
                          IF(fBahasa='',1=1,bahasa = fBahasa)
                        GROUP BY bahasa ORDER BY jml DESC
                        LIMIT 0,maxFaced;                 
                    END
            ")->execute();

            //facedPublisherOpac1
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `facedPublisherOpac1`;
            ")->execute();

            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `facedPublisherOpac1`(
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN maxFaced INT
                    )
                BEGIN
                                  
                    SELECT Publisher,COUNT(1) jml
                          FROM tempCariOpac 
                          
                          WHERE 
                          IF(fAuthor='',1=1,Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
                          IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
                          IF(fSubject='',1=1,SUBJECT = fPublishYear) AND
                          IF(fBahasa='',1=1,bahasa = fBahasa)
                          
                        GROUP BY Publisher
                        ORDER BY jml DESC
                        LIMIT 0,maxFaced;                        
                    END
            ")->execute();

            //facedPublishLocationOpac1
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `facedPublishLocationOpac1`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `facedPublishLocationOpac1`(
                IN fAuthor TEXT,
                IN fPublisher TEXT,
                IN fPublishLoc TEXT,
                IN fPublishYear TEXT,
                IN fSubject TEXT,
                IN fBahasa TEXT,
                IN maxFaced INT
                )
            BEGIN
                              
                SELECT PublishLocation,COUNT(1) jml
                      FROM tempCariOpac 
                      
                      WHERE 
                      IF(fAuthor='',1=1,Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                      IF(fPublisher='',1=1,publisher = fPublisher) AND
                      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
                      IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
                      IF(fSubject='',1=1,SUBJECT = fSubject) AND
                      IF(fBahasa='',1=1,bahasa = fBahasa)
                      
                    GROUP BY PublishLocation
                    ORDER BY jml DESC
                    LIMIT 0,maxFaced;    
                END
            ")->execute();


            //facedPublishYearOpac1
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `facedPublishYearOpac1`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `facedPublishYearOpac1`(
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN maxFaced INT
                    )
                BEGIN
                                  
                    SELECT PublishYear,COUNT(1) jml
                          FROM tempCariOpac
                          
                          WHERE 
                          IF(fAuthor='',1=1,Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
                          IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
                          IF(fSubject='',1=1,SUBJECT = fSubject) AND
                          IF(fBahasa='',1=1,bahasa = fBahasa)
                        GROUP BY PublishYear
                        ORDER BY jml DESC
                        LIMIT 0,maxFaced;      
                              
                    END
            ")->execute();


            //facedSubjectOpac1
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `facedSubjectOpac1`;
            ")->execute();

            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `facedSubjectOpac1`(
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN maxFaced INT
                    )
                BEGIN
                                  
                    SELECT COALESCE(SUBJECT,'-') SUBJECT,COUNT(1) jml
                          FROM tempCariOpac 
                          
                          WHERE 
                          IF(fAuthor='',1=1,Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
                          IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
                          IF(fSubject='',1=1,SUBJECT = fSubject) AND
                          IF(fBahasa='',1=1,bahasa = fBahasa)
                        GROUP BY SUBJECT ORDER BY jml DESC
                        LIMIT 0,maxFaced;    
                              
                    END
            ")->execute();

            //insertTempLanjutArticle
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `insertTempLanjutArticle`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE PROCEDURE `insertTempLanjutArticle`(
                    IN worksheet TEXT,
                    IN bahasa TEXT,
                    IN targetPembaca TEXT,
                    IN bentukKarya TEXT,
                    IN keyword TEXT,
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN isLKD TEXT
                    )
                BEGIN
                    DECLARE querys,querys2,querys3,bhs,karya,pembaca TEXT;
                    SET querys='';
                    SET querys2='';
                    SET querys3='';
                    SET bhs='';
                    SET karya='';
                    SET pembaca='';
                    DROP TABLE IF EXISTS tempCariArticle;
                    CREATE  TEMPORARY TABLE tempCariArticle
                    (
                Article_type VARCHAR(100),
                Creator_article VARCHAR(200),
                title_article TEXT,
                content_article TEXT,
                subject_article VARCHAR(200),
                EDISISERIAL VARCHAR(200),
                TANGGAL_TERBIT_EDISI_SERIAL VARCHAR(200),
                CatalogId            INT(11),
                title       TEXT,
                bahasa VARCHAR(100),
                author    VARCHAR(200),
                publisher   VARCHAR(100),
                PublishLocation  VARCHAR(100),
                PublishYear VARCHAR(100),
                SUBJECT VARCHAR(200),
                CoverURL VARCHAR(100),
                worksheet_id INT,
                worksheet VARCHAR(100),
                KONTEN_DIGITAL VARCHAR(100)
                    );
                     IF bahasa <> '' THEN SET bhs = CONCAT(bhs,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,36,3) = ''',bahasa,''''); 
                      END IF;
                     IF bentukKarya <> '' THEN SET karya = CONCAT(bhs,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,34,1) = ''',bentukKarya,''''); 
                      END IF;
                     IF targetPembaca <> '' THEN SET pembaca = CONCAT(pembaca,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,23,1) = ''',targetPembaca,''''); 
                      END IF;
                     
                     IF fAuthor <> '' THEN SET querys = CONCAT(querys,' AND CAT.Author = ''',fAuthor,''''); 
                      END IF;
                      IF fPublisher <> ''   THEN SET querys = CONCAT(querys,' AND CAT.Publisher = ''',fPublisher,'''');
                      END IF;
                      IF fPublishLoc <> '' THEN SET querys = CONCAT(querys,' AND CAT.PublishLocation = ''',fPublishLoc,''''); 
                      END IF;
                      IF fPublishYear <> ''  THEN SET querys = CONCAT(querys,' AND CAT.PublishYear = ''',fPublishYear,''''); 
                       END IF;
                      IF fSubject <> ''  THEN SET querys = CONCAT(querys,' AND CAT.Subject = ''',fSubject,''''); 
                       END IF;
                        IF fBahasa <> ''  THEN SET querys = CONCAT(querys,' AND CAT.Languages = ''',fBahasa,''''); 
                       END IF;
                            
                       
                    SET @query_as_string=CONCAT('
                INSERT INTO tempCariArticle
                        SELECT art.Article_type,art.Creator AS Creator_article, art.title AS title_article, art.content AS content_article, art.subject AS subject_article, art.EDISISERIAL, art.TANGGAL_TERBIT_EDISI_SERIAL, CAT.id CatalogId,CAT.title,CAT.Languages, CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
                            w.name worksheet,
                                    (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE(''.'',REVERSE(FileURL)))+2) SEPARATOR '','') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM serial_articles art 
                          JOIN catalogs CAT ON art.Catalog_id = CAT.ID  
                          lEFT JOIN worksheets w ON w.id = CAT.Worksheet_id
                          
                          ',querys3,' 
                       WHERE 
                      
                       ',keyword,' AND CAT.isopac=1 AND art.ISOPAC=1
                            AND w.ISSERIAL = 1',querys,querys2,' ');
                     PREPARE statement_1 
                      FROM @query_as_string ;
                       EXECUTE statement_1;
                      DEALLOCATE PREPARE statement_1;
                    END               
                
            ")->execute();


            //insertTempLanjutArticle0
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `insertTempLanjutArticle0`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE PROCEDURE `insertTempLanjutArticle0`(
                    IN worksheet TEXT,
                    IN bahasa TEXT,
                    IN targetPembaca TEXT,
                    IN bentukKarya TEXT,
                    IN keyword TEXT,
                    IN limit1 TEXT,
                    IN limit2 TEXT,
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN isLKD TEXT
                    )
                BEGIN
                    DECLARE querys,querys2,querys3,bhs,karya,pembaca TEXT;
                    SET querys='';
                    SET querys2='';
                    SET querys3='';
                    SET bhs='';
                    SET karya='';
                    SET pembaca='';
                    DROP TABLE IF EXISTS tempCariArticle;
                    CREATE  TEMPORARY TABLE tempCariArticle
                    (
                Article_type VARCHAR(100),
                Creator_article VARCHAR(200),
                title_article TEXT,
                content_article TEXT,
                subject_article VARCHAR(200),
                EDISISERIAL VARCHAR(200),
                TANGGAL_TERBIT_EDISI_SERIAL VARCHAR(200),
                CatalogId            INT(11),
                title       TEXT,
                bahasa VARCHAR(100),
                author    VARCHAR(200),
                publisher   VARCHAR(100),
                PublishLocation  VARCHAR(100),
                PublishYear VARCHAR(100),
                SUBJECT VARCHAR(200),
                CoverURL VARCHAR(100),
                worksheet_id INT,
                worksheet VARCHAR(100),
                KONTEN_DIGITAL VARCHAR(100)
                    );
                     IF bahasa <> '' THEN SET bhs = CONCAT(bhs,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,36,3) = ''',bahasa,''''); 
                      END IF;
                     IF bentukKarya <> '' THEN SET karya = CONCAT(bhs,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,34,1) = ''',bentukKarya,''''); 
                      END IF;
                     IF targetPembaca <> '' THEN SET pembaca = CONCAT(pembaca,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,23,1) = ''',targetPembaca,''''); 
                      END IF;
                     
                     IF fAuthor <> '' THEN SET querys = CONCAT(querys,' AND CAT.Author = ''',fAuthor,''''); 
                      END IF;
                      IF fPublisher <> ''   THEN SET querys = CONCAT(querys,' AND CAT.Publisher = ''',fPublisher,'''');
                      END IF;
                      IF fPublishLoc <> '' THEN SET querys = CONCAT(querys,' AND CAT.PublishLocation = ''',fPublishLoc,''''); 
                      END IF;
                      IF fPublishYear <> ''  THEN SET querys = CONCAT(querys,' AND CAT.PublishYear = ''',fPublishYear,''''); 
                       END IF;
                      IF fSubject <> ''  THEN SET querys = CONCAT(querys,' AND CAT.Subject = ''',fSubject,''''); 
                       END IF;
                        IF fBahasa <> ''  THEN SET querys = CONCAT(querys,' AND CAT.Languages = ''',fBahasa,''''); 
                       END IF;
                SET @query_as_string=CONCAT('
                INSERT INTO tempCariArticle
                        SELECT art.Article_type,art.Creator AS Creator_article, art.title AS title_article, art.content AS content_article, art.subject AS subject_article, art.EDISISERIAL, art.TANGGAL_TERBIT_EDISI_SERIAL, CAT.id CatalogId,CAT.title,CAT.Languages, CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
                            w.name worksheet,
                                    (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE(''.'',REVERSE(FileURL)))+2) SEPARATOR '','') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM serial_articles art 
                          JOIN catalogs CAT ON art.Catalog_id = CAT.ID  
                          lEFT JOIN worksheets w ON w.id = CAT.Worksheet_id
                          
                          ',querys3,' 
                       WHERE 
                      
                       ',keyword,' AND CAT.isopac=1 AND art.ISOPAC=1
                            AND w.ISSERIAL = 1',querys,querys2,' LIMIT ',limit1,',',limit2,' ');            
                      
                     PREPARE statement_1 
                      FROM @query_as_string ;
                       EXECUTE statement_1;
                      DEALLOCATE PREPARE statement_1;
                    END
            ")->execute();

            //insertTempLanjutOpac
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `insertTempLanjutOpac`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `insertTempLanjutOpac`(
                    IN worksheet TEXT,
                    IN bahasa TEXT,
                    IN targetPembaca TEXT,
                    in bentukKarya TEXT,
                    IN keyword TEXT,
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN isLKD TEXT,
                    IN lokasi TEXT
                    )
                BEGIN
                    DECLARE querys,querys2,querys3,bhs,karya,pembaca,queryloc,querylocjoin TEXT;
                    set querys='';
                    set querys2='';
                    set querys3='';
                    SET queryloc='';
                    SET querylocjoin='';
                    set bhs='';
                    set karya='';
                    set pembaca='';
                    DROP TABLE IF EXISTS tempCariOpac;
                    CREATE  TEMPORARY TABLE tempCariOpac
                    (
                    CatalogId            INT(11),
                    title       TEXT,
                    author    TEXT,
                    publisher   TEXT,
                    PublishLocation  TEXT,
                    PublishYear TEXT,
                    Subject Text,
                    bahasa TEXT,
                    CoverURL TEXT,
                    worksheet_id INT,
                    worksheet TEXT,
                    ISSERIAL TEXT,
                    JML_BUKU INT,
                    ALL_BUKU INT,
                    KONTEN_DIGITAL VARCHAR(100)
                    );
                     IF bahasa <> '' THEN SET bhs = CONCAT(bhs,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,36,3) = ''',bahasa,''''); 
                      END IF;
                     IF bentukKarya <> '' THEN SET karya = CONCAT(bhs,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,34,1) = ''',bentukKarya,''''); 
                      END IF;
                     IF targetPembaca <> '' THEN SET pembaca = CONCAT(pembaca,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,23,1) = ''',targetPembaca,''''); 
                      END IF;
                     IF fAuthor <> '' THEN SET querys = CONCAT(querys,' AND CAT.Author = ''',fAuthor,''''); 
                      END IF;
                      IF fPublisher <> ''   THEN SET querys = CONCAT(querys,' AND CAT.Publisher = ''',fPublisher,'''');
                      END IF;
                      IF fPublishLoc <> '' THEN SET querys = CONCAT(querys,' AND CAT.PublishLocation = ''',fPublishLoc,''''); 
                      END IF;
                      IF fPublishYear <> ''  THEN SET querys = CONCAT(querys,' AND CAT.PublishYear = ''',fPublishYear,''''); 
                       END IF;
                      IF fSubject <> ''  THEN SET querys = CONCAT(querys,' AND CAT.Subject = ''',fSubject,''''); 
                       END IF;
                        IF fBahasa <> ''  THEN SET querys = CONCAT(querys,' AND CAT.Languages = ''',fBahasa,''''); 
                       END IF;
                       
                       IF lokasi <> 0 THEN SET queryloc = CONCAT(queryloc,' AND col.Location_id = ''',lokasi,''''); 
                       END IF;
                       
                       IF lokasi <> 0 THEN SET querylocjoin = CONCAT(querys,' LEFT JOIN collections col ON col.Catalog_id = R.CATALOGID '); 
                       END IF;
                       
                        IF worksheet <> 'Semua Format FIle' and isLKD = 1  THEN SET querys2 = CONCAT(querys2,' HAVING KONTEN_DIGITAL =  ''',worksheet,''''); 
                       END IF;
                        IF isLKD  = 1  THEN SET querys3 = CONCAT(querys3,' INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id '); 
                       END IF;
                       
                       
                    SET @query_as_string=CONCAT('
                        INSERT INTO tempCariOpac
                         SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id, 
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                    (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE(''.'',REVERSE(FileURL)))+2) SEPARATOR '','') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                          -- LEFT JOIN collections col ON col.Catalog_id = CAT.ID
                          
                          ',querys3,' 
                          WHERE EXISTS           
                                   (SELECT  1
                                    FROM catalog_ruas R  ',querylocjoin,' 
                                     WHERE 
                                     
                                    
                                     
                    ',keyword,queryloc,bhs,karya,pembaca,'  AND R.CATALOGID=CAT.ID) AND CAT.isopac=1',querys,querys2);
                     PREPARE statement_1 
                      FROM @query_as_string ;
                       EXECUTE statement_1;
                      DEALLOCATE PREPARE statement_1;
                            
                    END
            ")->execute();

            //insertTempLanjutOpac0
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `insertTempLanjutOpac0`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                            CREATE  PROCEDURE `insertTempLanjutOpac0`(
                IN worksheet TEXT,
                IN bahasa TEXT,
                IN targetPembaca TEXT,
                IN bentukKarya TEXT,
                IN keyword TEXT,
                IN limit1 TEXT,
                IN limit2 TEXT,
                IN fAuthor TEXT,
                IN fPublisher TEXT,
                IN fPublishLoc TEXT,
                IN fPublishYear TEXT,
                IN fSubject TEXT,
                IN fBahasa TEXT,
                IN isLKD TEXT,
                IN lokasi TEXT
                )
            BEGIN
                DECLARE querys,querys2,querys3,bhs,karya,pembaca,queryloc,querylocjoin TEXT;
                SET querys='';
                SET querys2='';
                SET querys3='';
                SET queryloc='';
                SET querylocjoin='';
                SET bhs='';
                SET karya='';
                SET pembaca='';
                DROP TABLE IF EXISTS tempCariOpac;
                CREATE  TEMPORARY TABLE tempCariOpac
                (
                CatalogId            INT(11),
                title       TEXT,
                author    TEXT,
                publisher   TEXT,
                PublishLocation  TEXT,
                PublishYear TEXT,
                SUBJECT TEXT,
                bahasa TEXT,
                CoverURL TEXT,
                worksheet_id INT,
                worksheet TEXT,
                ISSERIAL TEXT,
                JML_BUKU INT,
                ALL_BUKU INT,
                KONTEN_DIGITAL VARCHAR(100)
                );
                 IF bahasa <> '' THEN SET bhs = CONCAT(bhs,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,36,3) = ''',bahasa,''''); 
                  END IF;
                 IF bentukKarya <> '' THEN SET karya = CONCAT(bhs,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,34,1) = ''',bentukKarya,''''); 
                  END IF;
                 IF targetPembaca <> '' THEN SET pembaca = CONCAT(pembaca,' AND R.TAG IN (''008'') AND SUBSTRING(R.VALUE,23,1) = ''',targetPembaca,''''); 
                  END IF;
                 
                 IF fAuthor <> '' THEN SET querys = CONCAT(querys,' AND CAT.Author = ''',fAuthor,''''); 
                  END IF;
                  IF fPublisher <> ''   THEN SET querys = CONCAT(querys,' AND CAT.Publisher = ''',fPublisher,'''');
                  END IF;
                  IF fPublishLoc <> '' THEN SET querys = CONCAT(querys,' AND CAT.PublishLocation = ''',fPublishLoc,''''); 
                  END IF;
                  IF fPublishYear <> ''  THEN SET querys = CONCAT(querys,' AND CAT.PublishYear = ''',fPublishYear,''''); 
                   END IF;
                  IF fSubject <> ''  THEN SET querys = CONCAT(querys,' AND CAT.Subject = ''',fSubject,''''); 
                   END IF;
                    IF fBahasa <> ''  THEN SET querys = CONCAT(querys,' AND CAT.Languages = ''',fBahasa,''''); 
                   END IF;
                   
                   IF lokasi <> 0 THEN SET queryloc = CONCAT(queryloc,' AND col.Location_id = ''',lokasi,''''); 
                   END IF;
                   
                   IF lokasi <> 0 THEN SET querylocjoin = CONCAT(querys,' LEFT JOIN collections col ON col.Catalog_id = R.CATALOGID '); 
                   END IF;
                   
                   
                   
                      IF worksheet <> 'Semua Format FIle' AND isLKD = 1  THEN SET querys2 = CONCAT(querys2,' HAVING KONTEN_DIGITAL =  ''',worksheet,''''); 
                   END IF;
                   
                    IF isLKD  = 1  THEN SET querys3 = CONCAT(querys3,' INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id '); 
                   END IF;
                   
                   
                SET @query_as_string=CONCAT('
                    INSERT INTO tempCariOpac
                     SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id, 
                               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                               (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
                                 (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                 (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE(''.'',REVERSE(FileURL)))+2) SEPARATOR '','') 
                                 FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                      FROM catalogs CAT 
                      -- LEFT JOIN collections col ON col.Catalog_id = CAT.ID
                      
                      ',querys3,' 
                   WHERE EXISTS           
                               (SELECT  1
                                FROM catalog_ruas R  ',querylocjoin,' 
                                 WHERE    
                                
                                 
                ',keyword,queryloc,bhs,karya,pembaca,' AND R.CATALOGID=CAT.ID) AND CAT.isopac=1',querys,querys2,' LIMIT ',limit1,',',limit2,' ');
                 PREPARE statement_1 
                  FROM @query_as_string ;
                   EXECUTE statement_1;
                  DEALLOCATE PREPARE statement_1;
                END
            ")->execute();

            //insertTempSederhanaArticle
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `insertTempSederhanaArticle`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `insertTempSederhanaArticle`(
                    IN keyword TEXT,
                    IN tag TEXT,
                    IN isLimit INT,
                    IN limit1 INT,
                    IN limit2 INT
                    )
                    BEGIN
                    DROP TABLE IF EXISTS tempCariArticle;
                    CREATE  TEMPORARY TABLE tempCariArticle
                    (
                    serial_articles_id INT(11),
                    Article_type VARCHAR(100),
                    Creator_article VARCHAR(200),
                    title_article TEXT,
                    content_article TEXT,
                    subject_article VARCHAR(200),
                    EDISISERIAL VARCHAR(200),
                    TANGGAL_TERBIT_EDISI_SERIAL VARCHAR(200),
                    CatalogId            INT(11),
                    title       TEXT,
                    bahasa VARCHAR(100),
                    author    VARCHAR(200),
                    publisher   VARCHAR(100),
                    PublishLocation  VARCHAR(100),
                    PublishYear VARCHAR(100),
                    SUBJECT VARCHAR(200),
                    CoverURL VARCHAR(100),
                    worksheet_id INT,
                    worksheet VARCHAR(100),
                    KONTEN_DIGITAL VARCHAR(100)
                    );              
                    IF isLimit = 1
                    THEN
                            INSERT INTO tempCariArticle
                            SELECT art.id AS id, art.Article_type,art.Creator AS Creator_article, art.title AS title_article, art.content AS content_article, art.subject AS subject_article, art.EDISISERIAL, art.TANGGAL_TERBIT_EDISI_SERIAL, CAT.id CatalogId,CAT.title,CAT.Languages, CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
                                w.name worksheet,
                                (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') FROM serial_articlefiles WHERE Articles_id = art.id AND art.Catalog_id = CAT.ID) KONTEN_DIGITAL
                              FROM serial_articles art 
                              -- update nambah join ini
                                JOIN serial_articles_repeatable sar ON sar.serial_article_ID = art.id
                              -- batas akhir update 
                              JOIN catalogs CAT ON art.Catalog_id = CAT.ID  
                              lEFT JOIN worksheets w ON w.id = CAT.Worksheet_id
                              WHERE  
                              
                                CASE tag
                                    WHEN 'Judul' THEN art.Title LIKE keyword
                                    -- update filter
                                    WHEN 'Pengarang' THEN (sar.article_field IN ('Kreator','Kontributor') AND sar.value LIKE keyword)
                                    WHEN 'Subyek' THEN (sar.article_field IN ('Subjek') AND sar.value LIKE keyword)
                                    -- batas update filter
                                    /*WHEN 'Pengarang' THEN art.Creator LIKE keyword
                                    WHEN 'Subyek' THEN art.Subject LIKE keyword*/     
                                    WHEN 'Sembarang' THEN 1 = 1
                                END
                              
                                AND CAT.isopac=1 
                                AND art.ISOPAC=1
                                AND w.ISSERIAL = 1
                                GROUP BY art.id
                                LIMIT limit1,limit2;
                                
                    ELSE
                            INSERT INTO tempCariArticle
                            SELECT art.id AS id, art.Article_type,art.Creator AS Creator_article, art.title AS title_article, art.content AS content_article, art.subject AS subject_article, art.EDISISERIAL, art.TANGGAL_TERBIT_EDISI_SERIAL, CAT.id CatalogId,CAT.title,CAT.Languages, CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
                                w.name worksheet,
                                (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') FROM serial_articlefiles WHERE Articles_id = art.id AND art.Catalog_id = CAT.ID) KONTEN_DIGITAL
                              FROM serial_articles art 
                              -- update nambah join ini
                                JOIN serial_articles_repeatable sar ON sar.serial_article_ID = art.id
                              -- batas akhir update 
                              JOIN catalogs CAT ON art.Catalog_id = CAT.ID  
                              lEFT JOIN worksheets w ON w.id = CAT.Worksheet_id
                              WHERE  
                              
                                CASE tag
                                    WHEN 'Judul' THEN art.Title LIKE keyword
                                    -- update filter
                                    WHEN 'Pengarang' THEN (sar.article_field IN ('Kreator','Kontributor') AND sar.value LIKE keyword)
                                    WHEN 'Subyek' THEN (sar.article_field IN ('Subjek') AND sar.value LIKE keyword)
                                    -- batas update filter
                                    /*WHEN 'Pengarang' THEN art.Creator LIKE keyword
                                    WHEN 'Subyek' THEN art.Subject LIKE keyword*/     
                                    WHEN 'Sembarang' THEN 1 = 1
                                END
                              
                                AND CAT.isopac=1 
                                AND art.ISOPAC=1
                                AND w.ISSERIAL = 1
                                GROUP BY art.id;  
                            
                        END IF;
                              
                    END
            ")->execute();

            //insertTempSederhanaOpac
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `insertTempSederhanaOpac`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempSederhanaOpac`(
                    IN keyword TEXT,
                    IN tag TEXT,
                    IN worksheet VARCHAR(255),
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN fromTgl TEXT,
                    IN toTgl TEXT,
                    IN isLKD TEXT,
                    IN Location TEXT
                    )
                    BEGIN
                    DROP TABLE IF EXISTS tempCariOpac;
                    CREATE  TEMPORARY TABLE tempCariOpac
                    (
                    CatalogId            INT(11),
                    title       TEXT,
                    author    TEXT,
                    publisher   TEXT,
                    PublishLocation  TEXT,
                    PublishYear TEXT,
                    SUBJECT TEXT,
                    bahasa TEXT,
                    CoverURL TEXT,
                    CallNumber TEXT,
                    worksheet_id INT,
                    worksheet TEXT,
                    ISSERIAL TEXT,
                    JML_BUKU INT,
                    ALL_BUKU INT,
                    KONTEN_DIGITAL VARCHAR(100)
                    );
                    IF isLKD = 1
                    THEN
                     INSERT INTO tempCariOpac
                     SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.CallNumber ,CAT.Worksheet_id, 
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                   (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < NOW() || BookingExpiredDate IS NULL)) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                             INNER JOIN catalogfiles CF ON  CAT.ID = CF.Catalog_id 
                          
                          WHERE 
                          
                          
                          EXISTS           
                                   (SELECT  1
                                    FROM catalog_ruas R  
                                     WHERE 
                         
                            CASE tag
                            WHEN 'Judul' THEN R.TAG IN ('240','245','246','440','740')
                            WHEN 'Pengarang' THEN R.TAG IN ('100','110','111','700','710','711','800','810','811')
                            WHEN 'Penerbit' THEN R.TAG IN ('260','264')
                            WHEN 'Subyek' THEN R.TAG IN ('600','610','611','650','651')
                            WHEN 'Nomor Panggil' THEN R.TAG IN ('090','084')
                            WHEN 'ISBN' THEN R.TAG IN ('020')
                            WHEN 'ISSN' THEN R.TAG IN ('022')
                            WHEN 'ISMN' THEN R.TAG IN ('024')        
                            WHEN 'Semua Ruas' THEN 1 = 1
                            END
                                    AND  R.Value LIKE keyword 
                            AND R.CATALOGID=CAT.ID
                        
                                    
                                    ) 
                                
                            AND
                            IF(fAuthor='',1=1,CAT.Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                            IF(fPublisher='',1=1,CAT.Publisher LIKE fPublisher) AND
                            IF(fPublishLoc='',1=1,CAT.PublishLocation LIKE fPublishLoc) AND
                            IF(fPublishYear='',1=1,CAT.PublishYear LIKE fPublishYear) AND
                            IF(fSubject='',1=1,CAT.Subject LIKE fSubject) AND
                            IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            
                            
                            CAT.isopac=1 
                            
                            
                        HAVING CASE worksheet
                            WHEN 'pdf' THEN KONTEN_DIGITAL = worksheet
                            WHEN 'doc' THEN KONTEN_DIGITAL = worksheet
                            WHEN 'xls' THEN KONTEN_DIGITAL = worksheet
                            WHEN 'rar' THEN KONTEN_DIGITAL = worksheet
                            WHEN 'Semua Format File' THEN 1 = 1  
                        END
                            ;  
                    ELSE             
                    IF worksheet='4' THEN   
                    INSERT INTO tempCariOpac
                    SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.CallNumber ,CAT.Worksheet_id, 
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                   (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < NOW() || BookingExpiredDate IS NULL)) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                                WHERE EXISTS           
                                   (SELECT  1
                                    FROM catalog_ruas R  LEFT JOIN collections col ON col.Catalog_id = R.CATALOGID
                                     WHERE 
                                                            
                            CASE tag
                            WHEN 'Judul' THEN R.TAG IN ('240','245','246','440','740')
                            WHEN 'Pengarang' THEN R.TAG IN ('100','110','111','700','710','711','800','810','811')
                            WHEN 'Penerbit' THEN R.TAG IN ('260','264')
                            WHEN 'Subyek' THEN R.TAG IN ('600','610','611','650','651')
                            WHEN 'Nomor Panggil' THEN R.TAG IN ('090','084')
                            WHEN 'ISBN' THEN R.TAG IN ('020')
                            WHEN 'ISSN' THEN R.TAG IN ('022')
                            WHEN 'ISMN' THEN R.TAG IN ('024')        
                            WHEN 'Semua Ruas' THEN 1 = 1
                            END
                                    
                            AND  R.Value LIKE keyword 
                             AND IF(fromTgl!='' AND toTgl!='' ,col.TANGGAL_TERBIT_EDISI_SERIAL BETWEEN fromTgl AND toTgl,1=1)
                                                    AND R.CATALOGID=CAT.ID) 
                            AND IF(worksheet='Semua Jenis bahan',1=1,CAT.Worksheet_id = worksheet)
                            AND
                            IF(fAuthor='',1=1,CAT.Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                            IF(fPublisher='',1=1,CAT.Publisher LIKE fPublisher) AND
                            IF(fPublishLoc='',1=1,CAT.PublishLocation LIKE fPublishLoc) AND
                            IF(fPublishYear='',1=1,CAT.PublishYear LIKE fPublishYear) AND
                            IF(fSubject='',1=1,CAT.Subject LIKE fSubject) AND
                            IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            
                            CAT.isopac=1 
                            ;    
                    ELSE
                    IF location<>0 THEN   
                    INSERT INTO tempCariOpac
                    SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.CallNumber ,CAT.Worksheet_id, 
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                   (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < NOW() || BookingExpiredDate IS NULL)) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                                WHERE EXISTS           
                                   (SELECT  1
                                    FROM catalog_ruas R  LEFT JOIN collections col ON col.Catalog_id = R.CATALOGID
                                     WHERE 
                                                            
                            CASE tag
                            WHEN 'Judul' THEN R.TAG IN ('240','245','246','440','740')
                            WHEN 'Pengarang' THEN R.TAG IN ('100','110','111','700','710','711','800','810','811')
                            WHEN 'Penerbit' THEN R.TAG IN ('260','264')
                            WHEN 'Subyek' THEN R.TAG IN ('600','610','611','650','651')
                            WHEN 'Nomor Panggil' THEN R.TAG IN ('090','084')
                            WHEN 'ISBN' THEN R.TAG IN ('020')
                            WHEN 'ISSN' THEN R.TAG IN ('022')
                            WHEN 'ISMN' THEN R.TAG IN ('024')        
                            WHEN 'Semua Ruas' THEN 1 = 1
                            END
                                    
                            AND  R.Value LIKE keyword 
                            AND col.Location_id = Location AND R.CATALOGID=CAT.ID) 
                            AND IF(worksheet='Semua Jenis bahan',1=1,CAT.Worksheet_id = worksheet)
                            AND
                            IF(fAuthor='',1=1,CAT.Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                            IF(fPublisher='',1=1,CAT.Publisher LIKE fPublisher) AND
                            IF(fPublishLoc='',1=1,CAT.PublishLocation LIKE fPublishLoc) AND
                            IF(fPublishYear='',1=1,CAT.PublishYear LIKE fPublishYear) AND
                            IF(fSubject='',1=1,CAT.Subject LIKE fSubject) AND
                            IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            
                            CAT.isopac=1 
                            ;    
                    ELSE
                    INSERT INTO tempCariOpac
                    SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.CallNumber ,CAT.Worksheet_id, 
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                   (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < NOW() || BookingExpiredDate IS NULL)) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                                WHERE EXISTS           
                                   (SELECT  1
                                    FROM catalog_ruas R  
                                                     WHERE 
                                                            
                            CASE tag
                            WHEN 'Judul' THEN R.TAG IN ('240','245','246','440','740')
                            WHEN 'Pengarang' THEN R.TAG IN ('100','110','111','700','710','711','800','810','811')
                            WHEN 'Penerbit' THEN R.TAG IN ('260','264')
                            WHEN 'Subyek' THEN R.TAG IN ('600','610','611','650','651')
                            WHEN 'Nomor Panggil' THEN R.TAG IN ('090','084')
                            WHEN 'ISBN' THEN R.TAG IN ('020')
                            WHEN 'ISSN' THEN R.TAG IN ('022')
                            WHEN 'ISMN' THEN R.TAG IN ('024')        
                            WHEN 'Semua Ruas' THEN 1 = 1
                            END
                                    
                            AND  R.Value LIKE keyword 
                                                   AND R.CATALOGID=CAT.ID) 
                            AND IF(worksheet='Semua Jenis bahan',1=1,CAT.Worksheet_id = worksheet)
                            AND
                            IF(fAuthor='',1=1,CAT.Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                            IF(fPublisher='',1=1,CAT.Publisher LIKE fPublisher) AND
                            IF(fPublishLoc='',1=1,CAT.PublishLocation LIKE fPublishLoc) AND
                            IF(fPublishYear='',1=1,CAT.PublishYear LIKE fPublishYear) AND
                            IF(fSubject='',1=1,CAT.Subject LIKE fSubject) AND
                            IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            
                            CAT.isopac=1 
                            ;  
                    END IF;
                    END IF;
                    END IF;
                    END
            ")->execute();

            //insertTempSederhanaOpac0
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `insertTempSederhanaOpac0`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE PROCEDURE `insertTempSederhanaOpac0`(
                    IN keyword TEXT,
                    IN tag TEXT,
                    IN worksheet VARCHAR(255),
                    IN limit1 INT,
                    IN limit2 INT,
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT,
                    IN fromTgl TEXT,
                    IN toTgl TEXT,
                    IN isLKD TEXT,
                    IN Location TEXT
                    )
                    BEGIN
                    DROP TABLE IF EXISTS tempCariOpac;
                    CREATE  TEMPORARY TABLE tempCariOpac
                    (
                    CatalogId            INT(11),
                    title       TEXT,
                    author    TEXT,
                    publisher   TEXT,
                    PublishLocation  TEXT,
                    PublishYear TEXT,
                    SUBJECT TEXT,
                    bahasa TEXT,
                    CoverURL TEXT,
                    worksheet_id INT,
                    worksheet TEXT,
                    ISSERIAL TEXT,
                    JML_BUKU INT,
                    ALL_BUKU INT,
                    KONTEN_DIGITAL VARCHAR(100)
                    );
                    IF isLKD = 1
                    THEN
                     INSERT INTO tempCariOpac
                     SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id, 
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                   (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < NOW() || BookingExpiredDate IS NULL)) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                             INNER JOIN catalogfiles CF ON  CAT.ID = CF.Catalog_id 
                          
                          WHERE 
                          
                          
                          EXISTS           
                                   (SELECT  1
                                    FROM catalog_ruas R  
                                     WHERE 
                         
                            CASE tag
                            WHEN 'Judul' THEN R.TAG IN ('240','245','246','440','740')
                            WHEN 'Pengarang' THEN R.TAG IN ('100','110','111','700','710','711','800','810','811')
                            WHEN 'Penerbit' THEN R.TAG IN ('260','264')
                            WHEN 'Subyek' THEN R.TAG IN ('600','610','611','650','651')
                            WHEN 'Nomor Panggil' THEN R.TAG IN ('090','084')
                            WHEN 'ISBN' THEN R.TAG IN ('020')
                            WHEN 'ISSN' THEN R.TAG IN ('022')
                            WHEN 'ISMN' THEN R.TAG IN ('024')        
                            WHEN 'Semua Ruas' THEN 1 = 1
                            END
                                AND  R.Value LIKE keyword 
                                AND R.CATALOGID=CAT.ID
                        
                                    
                                    ) 
                                
                            AND
                            IF(fAuthor='',1=1,CAT.Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                            IF(fPublisher='',1=1,CAT.Publisher LIKE fPublisher) AND
                            IF(fPublishLoc='',1=1,CAT.PublishLocation LIKE fPublishLoc) AND
                            IF(fPublishYear='',1=1,CAT.PublishYear LIKE fPublishYear) AND
                            IF(fSubject='',1=1,CAT.Subject LIKE fSubject) AND
                            IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            
                            CAT.isopac=1
                            
                            
                         HAVING CASE worksheet
                            WHEN 'pdf' THEN KONTEN_DIGITAL = worksheet
                            WHEN 'doc' THEN KONTEN_DIGITAL = worksheet
                            WHEN 'xls' THEN KONTEN_DIGITAL = worksheet
                            WHEN 'rar' THEN KONTEN_DIGITAL = worksheet
                            WHEN 'Semua Format File' THEN 1 = 1
                            
                                END
                            LIMIT limit1,limit2;  
                    ELSE             
                    IF worksheet='4' THEN   
                    INSERT INTO tempCariOpac
                    SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id, 
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                   (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < NOW() || BookingExpiredDate IS NULL)) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                                WHERE EXISTS           
                                   (SELECT  1
                                    FROM catalog_ruas R  LEFT JOIN collections col ON col.Catalog_id = R.CATALOGID
                                     WHERE 
                                                            
                            CASE tag
                            WHEN 'Judul' THEN R.TAG IN ('240','245','246','440','740')
                            WHEN 'Pengarang' THEN R.TAG IN ('100','110','111','700','710','711','800','810','811')
                            WHEN 'Penerbit' THEN R.TAG IN ('260','264')
                            WHEN 'Subyek' THEN R.TAG IN ('600','610','611','650','651')
                            WHEN 'Nomor Panggil' THEN R.TAG IN ('090','084')
                            WHEN 'ISBN' THEN R.TAG IN ('020')
                            WHEN 'ISSN' THEN R.TAG IN ('022')
                            WHEN 'ISMN' THEN R.TAG IN ('024')        
                            WHEN 'Semua Ruas' THEN 1 = 1
                            END
                                    
                            AND  R.Value LIKE keyword 
                             AND IF(fromTgl!='' AND toTgl!='' ,col.TANGGAL_TERBIT_EDISI_SERIAL BETWEEN fromTgl AND toTgl,1=1)
                                                    AND R.CATALOGID=CAT.ID) 
                            AND IF(worksheet='Semua Jenis bahan',1=1,CAT.Worksheet_id = worksheet)
                            AND
                            IF(fAuthor='',1=1,CAT.Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                            IF(fPublisher='',1=1,CAT.Publisher LIKE fPublisher) AND
                            IF(fPublishLoc='',1=1,CAT.PublishLocation LIKE fPublishLoc) AND
                            IF(fPublishYear='',1=1,CAT.PublishYear LIKE fPublishYear) AND
                            IF(fSubject='',1=1,CAT.Subject LIKE fSubject) AND
                            IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            
                            CAT.isopac=1 
                            LIMIT limit1,limit2;    
                    ELSE
                    IF location<>0 THEN  
                    INSERT INTO tempCariOpac
                    SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id, 
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                   (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < NOW() || BookingExpiredDate IS NULL)) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                                WHERE EXISTS           
                                   (SELECT  1
                                    FROM catalog_ruas R  LEFT JOIN collections col ON col.Catalog_id = R.CATALOGID
                                     WHERE 
                                                            
                            CASE tag
                            WHEN 'Judul' THEN R.TAG IN ('240','245','246','440','740')
                            WHEN 'Pengarang' THEN R.TAG IN ('100','110','111','700','710','711','800','810','811')
                            WHEN 'Penerbit' THEN R.TAG IN ('260','264')
                            WHEN 'Subyek' THEN R.TAG IN ('600','610','611','650','651')
                            WHEN 'Nomor Panggil' THEN R.TAG IN ('090','084')
                            WHEN 'ISBN' THEN R.TAG IN ('020')
                            WHEN 'ISSN' THEN R.TAG IN ('022')
                            WHEN 'ISMN' THEN R.TAG IN ('024')        
                            WHEN 'Semua Ruas' THEN 1 = 1
                            END
                                    
                            AND  R.Value LIKE keyword 
                             AND  R.Value LIKE keyword 
                            AND col.Location_id = Location
                                                    AND R.CATALOGID=CAT.ID) 
                            AND IF(worksheet='Semua Jenis bahan',1=1,CAT.Worksheet_id = worksheet)
                            AND
                            IF(fAuthor='',1=1,CAT.Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                            IF(fPublisher='',1=1,CAT.Publisher LIKE fPublisher) AND
                            IF(fPublishLoc='',1=1,CAT.PublishLocation LIKE fPublishLoc) AND
                            IF(fPublishYear='',1=1,CAT.PublishYear LIKE fPublishYear) AND
                            IF(fSubject='',1=1,CAT.Subject LIKE fSubject) AND
                            IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            
                            CAT.isopac=1 
                            LIMIT limit1,limit2;    
                    ELSE
                    INSERT INTO tempCariOpac
                    SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id, 
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                   (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < NOW() || BookingExpiredDate IS NULL)) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                                WHERE EXISTS           
                                   (SELECT  1
                                    FROM catalog_ruas R  
                                                     WHERE 
                                                            
                            CASE tag
                            WHEN 'Judul' THEN R.TAG IN ('240','245','246','440','740')
                            WHEN 'Pengarang' THEN R.TAG IN ('100','110','111','700','710','711','800','810','811')
                            WHEN 'Penerbit' THEN R.TAG IN ('260','264')
                            WHEN 'Subyek' THEN R.TAG IN ('600','610','611','650','651')
                            WHEN 'Nomor Panggil' THEN R.TAG IN ('090','084')
                            WHEN 'ISBN' THEN R.TAG IN ('020')
                            WHEN 'ISSN' THEN R.TAG IN ('022')
                            WHEN 'ISMN' THEN R.TAG IN ('024')        
                            WHEN 'Semua Ruas' THEN 1 = 1
                            END
                                    
                            AND  R.Value LIKE keyword 
                                                   AND R.CATALOGID=CAT.ID) 
                            AND IF(worksheet='Semua Jenis bahan',1=1,CAT.Worksheet_id = worksheet)
                            AND
                            IF(fAuthor='',1=1,CAT.Author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
                            IF(fPublisher='',1=1,CAT.Publisher LIKE fPublisher) AND
                            IF(fPublishLoc='',1=1,CAT.PublishLocation LIKE fPublishLoc) AND
                            IF(fPublishYear='',1=1,CAT.PublishYear LIKE fPublishYear) AND
                            IF(fSubject='',1=1,CAT.Subject LIKE fSubject) AND
                            IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            
                            CAT.isopac=1 
                            LIMIT limit1,limit2;  
                    END IF;
                    END IF;
                    END IF;
                    END                    
            ")->execute();


            //insertTempTelusurArticle
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `insertTempTelusurArticle`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `insertTempTelusurArticle`(
                        IN tag TEXT,
                        IN findby TEXT,
                        IN fquery TEXT,
                        IN fquery2 TEXT,
                        IN fAuthor TEXT,
                        IN fPublisher TEXT,
                        IN fPublishLoc TEXT,
                        IN fPublishYear TEXT,
                        IN FSubject TEXT,
                        IN fBahasa TEXT,
                        IN isLKD TEXT
                        )
                    BEGIN
                        DROP TABLE IF EXISTS tempCariArticle;
                        CREATE  TEMPORARY TABLE tempCariArticle
                        (
                        CatalogId            INT(11),
                        title       TEXT,
                        author    TEXT,
                        publisher   TEXT,
                        PublishLocation  TEXT,
                        PublishYear TEXT,
                        SUBJECT TEXT,
                        bahasa TEXT,
                        CoverURL TEXT,
                        worksheet_id INT,
                        worksheet TEXT,
                        ISSERIAL TEXT,
                        JML_BUKU INT,
                        ALL_BUKU INT,
                        KONTEN_DIGITAL VARCHAR(100)
                        );              
                        IF isLKD = 1 THEN
                                INSERT INTO tempCariArticle
                        SELECT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.Subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id,
                                       (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                        (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                        (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND BookingExpiredDate < NOW()) JML_BUKU,
                                         (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                         (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                         FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                              FROM catalogs CAT 
                              INNER JOIN catalogfiles CF ON  CAT.ID = CF.Catalog_id
                              WHERE CAT.isopac=1 AND
                                         IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
                                 IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
                                 IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
                                 IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
                                 IF(fSubject='',1=1,CAT.SUBJECT = fSubject) AND
                                 IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                                CASE tag
                                WHEN 'Author' THEN CAT.Author = fquery2
                                WHEN 'Subject' THEN CAT.subject = fquery2
                                WHEN 'Publisher' THEN CAT.Publisher = fquery2
                                WHEN 'PublishLocation' THEN CAT.PublishLocation = fquery2
                                WHEN 'PublishYear' THEN CAT.PublishYear = fquery2
                                END
                                 AND
                                CASE findBy
                                WHEN 'Alphabetical'  THEN  1=1
                                WHEN 'Author' THEN CAT.Author = fquery
                                WHEN 'Subject' THEN CAT.subject = fquery
                                WHEN 'Publisher' THEN CAT.Publisher = fquery
                                WHEN 'PublishLocation' THEN CAT.PublishLocation = fquery
                                WHEN 'PublishYear' THEN CAT.PublishYear = fquery
                                END;
                                  
                        ELSE
                            INSERT INTO tempCariArticle
                        SELECT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.Subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id,
                                       (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                        (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                        (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND BookingExpiredDate < NOW()) JML_BUKU,
                                         (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                         (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                         FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                              FROM catalogs CAT 
                              WHERE CAT.isopac=1 AND
                                 IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
                                 IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
                                 IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
                                 IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
                                 IF(fSubject='',1=1,CAT.SUBJECT = fSubject) AND
                                 IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                                CASE tag
                                WHEN 'Author' THEN CAT.Author = fquery2
                                WHEN 'Subject' THEN CAT.subject = fquery2
                                WHEN 'Publisher' THEN CAT.Publisher = fquery2
                                WHEN 'PublishLocation' THEN CAT.PublishLocation = fquery2
                                WHEN 'PublishYear' THEN CAT.PublishYear = fquery2
                                END
                                 AND
                                CASE findBy
                                WHEN 'Alphabetical'  THEN  1=1
                                WHEN 'Author' THEN CAT.Author = fquery
                                WHEN 'Subject' THEN CAT.subject = fquery
                                WHEN 'Publisher' THEN CAT.Publisher = fquery
                                WHEN 'PublishLocation' THEN CAT.PublishLocation = fquery
                                WHEN 'PublishYear' THEN CAT.PublishYear = fquery
                                END;
                                  
                        END IF;
                        END
            ")->execute();



            //insertTempTelusurOpac
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `insertTempTelusurOpac`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `insertTempTelusurOpac`(
                    IN tag TEXT,
                    IN findby TEXT,
                    IN fquery TEXT,
                    IN fquery2 TEXT,
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN FSubject TEXT,
                    IN fBahasa TEXT,
                    IN isLKD TEXT
                    )
                BEGIN
                    DROP TABLE IF EXISTS tempCariOpac;
                    CREATE  TEMPORARY TABLE tempCariOpac
                    (
                    CatalogId            INT(11),
                    title       TEXT,
                    author    TEXT,
                    publisher   TEXT,
                    PublishLocation  TEXT,
                    PublishYear TEXT,
                    Subject TEXT,
                    bahasa TEXT,
                    CoverURL TEXT,
                    worksheet_id INT,
                    worksheet TEXT,
                    ISSERIAL TEXT,
                    JML_BUKU INT,
                    ALL_BUKU INT,
                    KONTEN_DIGITAL VARCHAR(100)
                    );              
                    if isLKD = 1 then
                            INSERT INTO tempCariOpac
                    SELECT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.Subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id,
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                    (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND BookingExpiredDate < now()) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                          INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id
                          WHERE CAT.isopac=1 AND
                                     IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
                             IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
                             IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
                             IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
                             IF(fSubject='',1=1,CAT.SUBJECT = fSubject) AND
                             IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            CASE tag
                            WHEN 'Author' THEN CAT.Author = fquery2
                            WHEN 'Subject' THEN CAT.subject = fquery2
                            WHEN 'Publisher' THEN CAT.Publisher = fquery2
                            WHEN 'PublishLocation' THEN CAT.PublishLocation = fquery2
                            WHEN 'PublishYear' THEN CAT.PublishYear = fquery2
                            END
                             AND
                            CASE findBy
                            When 'Alphabetical'  THEN  1=1
                            WHEN 'Author' THEN CAT.Author = fquery
                            WHEN 'Subject' THEN CAT.subject = fquery
                            WHEN 'Publisher' THEN CAT.Publisher = fquery
                            WHEN 'PublishLocation' THEN CAT.PublishLocation = fquery
                            WHEN 'PublishYear' THEN CAT.PublishYear = fquery
                            END;
                              
                    else
                        INSERT INTO tempCariOpac
                    SELECT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.Subject,CAT.Languages,CAT.CoverURL ,CAT.Worksheet_id,
                                   (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                                    (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                                    (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND BookingExpiredDate < now()) JML_BUKU,
                                     (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                                     (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                                     FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                          FROM catalogs CAT 
                          WHERE CAT.isopac=1 AND
                             IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
                             IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
                             IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
                             IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
                             IF(fSubject='',1=1,CAT.SUBJECT = fSubject) AND
                             IF(fBahasa='',1=1,CAT.Languages LIKE fBahasa) AND
                            CASE tag
                            WHEN 'Author' THEN CAT.Author = fquery2
                            WHEN 'Subject' THEN CAT.subject = fquery2
                            WHEN 'Publisher' THEN CAT.Publisher = fquery2
                            WHEN 'PublishLocation' THEN CAT.PublishLocation = fquery2
                            WHEN 'PublishYear' THEN CAT.PublishYear = fquery2
                            END
                             AND
                            CASE findBy
                            When 'Alphabetical'  THEN  1=1
                            WHEN 'Author' THEN CAT.Author = fquery
                            WHEN 'Subject' THEN CAT.subject = fquery
                            WHEN 'Publisher' THEN CAT.Publisher = fquery
                            WHEN 'PublishLocation' THEN CAT.PublishLocation = fquery
                            WHEN 'PublishYear' THEN CAT.PublishYear = fquery
                            END;
                              
                    end if;
                    END                 
            ")->execute();


            //pencarianSederhanaOpacLimit1
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `pencarianSederhanaOpacLimit1`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE  PROCEDURE `pencarianSederhanaOpacLimit1`(
                IN limit1 INT, 
                IN limit2 INT,
                IN fAuthor TEXT,
                IN fPublisher TEXT,
                IN fPublishLoc TEXT,
                IN fPublishYear TEXT,
                IN fSubject TEXT,
                IN fBahasa TEXT
                )
                BEGIN
                SELECT *
                      FROM tempCariOpac 
                      
                      WHERE 
                     IF(fAuthor='',1=1,author LIKE concat('%',concat(fAuthor,'%'))) AND
                           IF(fPublisher='',1=1,publisher LIKE fPublisher) AND
                      IF(fPublishLoc='',1=1,PublishLocation LIKE fPublishLoc) AND
                      IF(fPublishYear='',1=1,PublishYear LIKE fPublishYear) AND
                      IF(fBahasa='',1=1,bahasa LIKE fBahasa) AND
                       IF(fSubject='',1=1,Subject LIKE fSubject)
                      
                      
                      LIMIT limit1,limit2;    
                END
            ")->execute();


            //pencarianLanjutLimitOpac
            $command = Yii::$app->db->createCommand("
                DROP PROCEDURE IF EXISTS `pencarianLanjutLimitOpac`;
            ")->execute();
            $command = Yii::$app->db->createCommand("
                CREATE PROCEDURE `pencarianLanjutLimitOpac`(
                    IN limit1 INT, 
                    IN limit2 INT,
                    IN fAuthor TEXT,
                    IN fPublisher TEXT,
                    IN fPublishLoc TEXT,
                    IN fPublishYear TEXT,
                    IN fSubject TEXT,
                    IN fBahasa TEXT
                    )
                BEGIN
                    SELECT CatalogId,title kalimat2,author,publisher,PublishLocation,PublishYear,Subject,bahasa,CoverURL,
                                   worksheet_id,worksheet,
                                    JML_BUKU,
                                    ALL_BUKU,
                                    KONTEN_DIGITAL
                          FROM tempCariOpac
                          
                          WHERE 
                                IF(fAuthor='',1=1,author = fAuthor) AND
                          IF(fPublisher='',1=1,publisher = fPublisher) AND
                          IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
                          IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
                          IF(fBahasa='',1=1,bahasa LIKE fBahasa) AND
                          IF(fSubject='',1=1,Subject = fSubject) 
                          
                          
                          LIMIT limit1,limit2;    
                    END
            ")->execute();

            //delete auth_item
            $command = Yii::$app->db->createCommand("
                DELETE FROM `auth_item`  WHERE (`name` = '/sirkulasi/pengembalian/create') ;
            ")->execute();

            //delete auth_item_child
            $command = Yii::$app->db->createCommand("
               DELETE FROM `auth_item_child`  WHERE (`parent` = 'Sirkulasi' AND `child` = '/sirkulasi/pengembalian/create') ; 
            ")->execute();    

            $command = Yii::$app->db->createCommand("
                UPDATE settingparameters SET settingparameters.Value = 'Manual' WHERE settingparameters.Name = 'NomorInduk';
            ")->execute();   

            $command = Yii::$app->db->createCommand("
                UPDATE refferenceitems SET refferenceitems.`Code` = '700' WHERE refferenceitems.`Refference_id` = '150';

                UPDATE refferenceitems SET refferenceitems.`Code` = '710' WHERE refferenceitems.`Refference_id` = '150' AND refferenceitems.`Name` = 'Badan Penanggungjawab';
            ")->execute();     

        }catch (Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
            $trans->rollback();
        }
    }
    /**
     * Script end for updating to version 3.1.
     */


    /**
     * Script start for updating to version 3.2.
     */

    function update32()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            /************************* Setting Parameter ****************************/
            $command = Yii::$app->db->createCommand("
                SET @id = (SELECT MAX(ID) FROM settingparameters) + 1; 
                INSERT  INTO settingparameters
                SELECT * FROM
                (SELECT @id AS ID, 'IsHitCounterOpac' AS `Name`,'0' AS `Value`, NULL AS CreateBy, NULL AS CreateDate, NULL AS CreateTerminal, NULL AS UpdateBy, NULL AS UpdateDate, NULL AS UpdateTerminal) AS sinkronisasiLocaltoServer
                WHERE NOT EXISTS (
                    SELECT settingparameters.Name FROM settingparameters WHERE settingparameters.Name = 'IsHitCounterOpac'
                );

                SET @id = (SELECT MAX(ID) FROM settingparameters) + 1;
                INSERT  INTO settingparameters
                SELECT * FROM
                (SELECT @id AS ID, 'ModuleDeposit' AS `Name`,'0' AS `Value`, NULL AS CreateBy, NULL AS CreateDate, NULL AS CreateTerminal, NULL AS UpdateBy, NULL AS UpdateDate, NULL AS UpdateTerminal) AS deposit
                WHERE NOT EXISTS (
                    SELECT settingparameters.Name FROM settingparameters WHERE settingparameters.Name = 'ModuleDeposit'
                );
                
                SET @id = (SELECT MAX(ID) FROM settingparameters) + 1;
                INSERT  INTO settingparameters
                SELECT * FROM
                (SELECT @id AS ID, 'SinkronisasiLocaltoServer' AS `Name`,'' AS `Value`, NULL AS CreateBy, NULL AS CreateDate, NULL AS CreateTerminal, NULL AS UpdateBy, NULL AS UpdateDate, NULL AS UpdateTerminal) AS sinkronisasiLocaltoServer
                WHERE NOT EXISTS (
                    SELECT settingparameters.Name FROM settingparameters WHERE settingparameters.Name = 'SinkronisasiLocaltoServer'
                );

                SET @id = (SELECT MAX(ID) FROM settingparameters) + 1;
                INSERT  INTO settingparameters
                SELECT * FROM
                (SELECT @id AS ID, 'SinkronisasiServertoLocal' AS `Name`,'' AS `Value`, NULL AS CreateBy, NULL AS CreateDate, NULL AS CreateTerminal, NULL AS UpdateBy, NULL AS UpdateDate, NULL AS UpdateTerminal) AS sinkronisasiLocaltoServer
                WHERE NOT EXISTS (
                    SELECT settingparameters.Name FROM settingparameters WHERE settingparameters.Name = 'SinkronisasiServertoLocal'
                );
            ")->execute();

            /************************* Batas Setting Parameter ********************************/

            /************************* Create New SP ********************************/
            //get_stat_jenis_pendidikan_bulan -- statistik
            $command = Yii::$app->db->createCommand("
                DROP procedure IF EXISTS `get_stat_jenis_pendidikan_bulan`;
            ")->execute();
            $command = Yii::$app->db->createCommand("              
                CREATE PROCEDURE `get_stat_jenis_pendidikan_bulan`(
                    IN `Bfrom` TEXT, 
                    IN `Bto` TEXT
                    )
                BEGIN
                    SELECT 
                        `mp`.`Nama` AS `Keterangan`,
                        COUNT(`mem`.`ID`) AS `Jumlah`,
                        DATE(`mem`.`CreateDate`) AS `Tanggal`
                     
                    FROM
                        (`master_pendidikan` `mp`
                        LEFT JOIN `members` `mem` ON ((`mem`.`EducationLevel_id` = `mp`.`id`)))
                    WHERE
                        (`mem`.`EducationLevel_id` IS NOT NULL)
                        
                        AND   
                        (CAST(`mem`.`CreateDate`
                            AS DATE) BETWEEN Bfrom AND Bto )
                        
                        GROUP BY `mp`.`Nama` , DATE_FORMAT(`mem`.`CreateDate`, '%M')
                    ORDER BY `mp`.`id` , `mem`.`CreateDate`;
                    END
            ")->execute();

            //get_stat_jenis_pendidikan_tahun -- statistik
            $command = Yii::$app->db->createCommand("
                DROP procedure IF EXISTS `get_stat_jenis_pendidikan_tahun`;
            ")->execute();
            $command = Yii::$app->db->createCommand("              
                CREATE PROCEDURE `get_stat_jenis_pendidikan_tahun`(
                    IN `Bfrom` TEXT, 
                    IN `Bto` TEXT
                    )
                BEGIN
                    SELECT 
                        `mp`.`Nama` AS `Keterangan`,
                        COUNT(`mem`.`ID`) AS `Jumlah`,
                        DATE(`mem`.`CreateDate`) AS `Tanggal`
                     
                    FROM
                        (`master_pendidikan` `mp`
                        LEFT JOIN `members` `mem` ON ((`mem`.`EducationLevel_id` = `mp`.`id`)))
                    WHERE
                        (`mem`.`EducationLevel_id` IS NOT NULL)
                        
                        AND   
                        (CAST(`mem`.`CreateDate`
                            AS DATE) BETWEEN Bfrom AND Bto )
                        
                        GROUP BY `mp`.`Nama` , DATE_FORMAT(`mem`.`CreateDate`, '%Y')
                    ORDER BY `mp`.`id` , `mem`.`CreateDate`;
                    END
            ")->execute();


            /************************* Batas Create New SP ********************************/


            /************************* Create Tabel ********************************/
            //check if table opac_counter is exist
            $cekTableOpacCounter = OpacHelpers::tableExist('opac_counter');
            if($cekTableOpacCounter == 0){
                $command = Yii::$app->db->createCommand("
                    DROP TABLE IF EXISTS `opac_counter`;
                    
                    CREATE TABLE `opac_counter` (
                      `hit_id` int(11) NOT NULL AUTO_INCREMENT,
                      `ip_address` varchar(30) NOT NULL,
                      `city` varchar(100) DEFAULT NULL,
                      `region_name` varchar(100) DEFAULT NULL,
                      `country` varchar(100) DEFAULT NULL,
                      `lat` varchar(100) DEFAULT NULL,
                      `long` varchar(100) DEFAULT NULL,
                      `create_at` datetime DEFAULT NULL,
                      `update_at` datetime DEFAULT NULL,
                      PRIMARY KEY (`hit_id`)
                    ) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
                ")->execute();
            }
            
            //check if table pengiriman is exist
            $cekTablePengiriman = OpacHelpers::tableExist('pengiriman');
            if($cekTablePengiriman == 0){
                $command = Yii::$app->db->createCommand("
                    DROP TABLE IF EXISTS `pengiriman`;

                    CREATE TABLE `pengiriman` (
                      `ID` int(11) NOT NULL AUTO_INCREMENT,
                      `JudulKiriman` text NOT NULL,
                      `PenanggungJawab` varchar(100) NOT NULL,
                      `NipPenanggungJawab` varchar(50) DEFAULT NULL,
                      `FromDate` date DEFAULT NULL,
                      `ToDate` date DEFAULT NULL,
                      `CreateBy` int(11) DEFAULT NULL,
                      `CreateDate` datetime DEFAULT NULL,
                      `CreateTerminal` varchar(100) DEFAULT NULL,
                      `UpdateBy` int(11) DEFAULT NULL,
                      `UpdateDate` datetime DEFAULT NULL,
                      `UpdateTerminal` varchar(100) DEFAULT NULL,
                      PRIMARY KEY (`ID`),
                      KEY `pengiriman_createby` (`CreateBy`),
                      KEY `pengiriman_updateby` (`UpdateBy`),
                      CONSTRAINT `pengiriman_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
                      CONSTRAINT `pengiriman_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                ")->execute();
            }else{
                $cekdataPengiriman = \common\models\Pengiriman::find()->count();
                if($cekdataPengiriman == 0){
                    $command = Yii::$app->db->createCommand("
                        DROP TABLE IF EXISTS `pengiriman`;

                        CREATE TABLE `pengiriman` (
                          `ID` int(11) NOT NULL AUTO_INCREMENT,
                          `JudulKiriman` text NOT NULL,
                          `PenanggungJawab` varchar(100) NOT NULL,
                          `NipPenanggungJawab` varchar(50) DEFAULT NULL,
                          `FromDate` date DEFAULT NULL,
                          `ToDate` date DEFAULT NULL,
                          `CreateBy` int(11) DEFAULT NULL,
                          `CreateDate` datetime DEFAULT NULL,
                          `CreateTerminal` varchar(100) DEFAULT NULL,
                          `UpdateBy` int(11) DEFAULT NULL,
                          `UpdateDate` datetime DEFAULT NULL,
                          `UpdateTerminal` varchar(100) DEFAULT NULL,
                          PRIMARY KEY (`ID`),
                          KEY `pengiriman_createby` (`CreateBy`),
                          KEY `pengiriman_updateby` (`UpdateBy`),
                          CONSTRAINT `pengiriman_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
                          CONSTRAINT `pengiriman_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                    ")->execute();
                }
            }
            
            //check if table pengiriman_koleksi is exist
            $cekTablePengirimanKoleksi = OpacHelpers::tableExist('pengiriman_koleksi');
            if($cekTablePengirimanKoleksi == 0){
                $command = Yii::$app->db->createCommand("
                    CREATE TABLE `pengiriman_koleksi` (
                      `ID` int(11) NOT NULL AUTO_INCREMENT,
                      `Collection_id` double NOT NULL,
                      `BIBID` varchar(50) DEFAULT NULL,
                      `JUDUL` varchar(4000) DEFAULT NULL,
                      `TAHUNTERBIT` varchar(20) DEFAULT NULL,
                      `CALLNUMBER` varchar(255) DEFAULT NULL,
                      `NOBARCODE` varchar(100) DEFAULT NULL,
                      `NOINDUK` varchar(255) DEFAULT NULL,
                      `QUANTITY` int(10) DEFAULT NULL,
                      `TANGGALKIRIM` date DEFAULT NULL,
                      `PengirimanID` int(11) DEFAULT NULL,
                      `IsCheck` bit(1) NOT NULL DEFAULT b'0',
                      `CreateBy` int(11) DEFAULT NULL,
                      `CreateDate` datetime DEFAULT NULL,
                      `CreateTerminal` varchar(50) DEFAULT NULL,
                      `UpdateBy` int(11) DEFAULT NULL,
                      `UpdateDate` datetime DEFAULT NULL,
                      `UpdateTerminal` varchar(50) DEFAULT NULL,
                      PRIMARY KEY (`ID`),
                      KEY `pengirimankoleksi_createby` (`CreateBy`),
                      KEY `pengirimankoleksi_updateby` (`UpdateBy`),
                      KEY `pengiriman_cetak_id` (`PengirimanID`),
                      KEY `pengirimankoleksi_collectionid` (`Collection_id`),
                      CONSTRAINT `pengiriman_cetak_id` FOREIGN KEY (`PengirimanID`) REFERENCES `pengiriman` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
                      CONSTRAINT `pengirimankoleksi_collectionid` FOREIGN KEY (`Collection_id`) REFERENCES `collections` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
                      CONSTRAINT `pengirimankoleksi_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
                      CONSTRAINT `pengirimankoleksi_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                ")->execute();
            }

            try {

                $command = Yii::$app->db->createCommand("

                    ALTER TABLE pengiriman_koleksi MODIFY Collection_id DOUBLE NULL;
    
                    ALTER TABLE pengiriman_koleksi
                    DROP FOREIGN KEY pengirimankoleksi_collectionid;
        
                    ALTER TABLE pengiriman_koleksi ADD CONSTRAINT pengirimankoleksi_collectionid FOREIGN KEY pengirimankoleksi_collectionid (Collection_id)
                    REFERENCES collections(ID)
                    ON DELETE SET NULL
                    ON UPDATE Cascade;
                
                ")->execute();
            } catch (\Exception $e) {
                if ($e->errorInfo[2]) {
                    array_push($err, $e->errorInfo[2]);
                }
            }                


                //check if table deposit_ws is exist
                $cekTableDepositKodeWilayah = OpacHelpers::tableExist('deposit_ws');
                if($cekTableDepositKodeWilayah == 0){
                    $command = Yii::$app->db->createCommand("
                        CREATE TABLE `deposit_ws` (
                          `ID` int(11) NOT NULL AUTO_INCREMENT,
                          `jenis_penerbit` varchar(55) COLLATE latin1_spanish_ci DEFAULT NULL,
                          `id_group_deposit_group_ws` int(11) DEFAULT NULL,
                          `id_deposit_kelompok_penerbit_ws` int(11) DEFAULT NULL,
                          `nama_penerbit` varchar(55) COLLATE latin1_spanish_ci DEFAULT NULL,
                          `alamat1` varchar(65) COLLATE latin1_spanish_ci DEFAULT NULL,
                          `alamat2` varchar(65) COLLATE latin1_spanish_ci DEFAULT NULL,
                          `alamat3` varchar(65) COLLATE latin1_spanish_ci DEFAULT NULL,
                          `kabupaten` varchar(65) COLLATE latin1_spanish_ci DEFAULT NULL,
                          `ID_deposit_kode_wilayah` int(11) DEFAULT NULL,
                          `kode_pos` int(11) DEFAULT NULL,
                          `no_telp1` int(11) DEFAULT NULL,
                          `no_telp2` int(11) DEFAULT NULL,
                          `no_telp3` int(11) DEFAULT NULL,
                          `no_fax` int(11) DEFAULT NULL,
                          `email` char(30) COLLATE latin1_spanish_ci DEFAULT NULL,
                          `contact_person` char(50) COLLATE latin1_spanish_ci DEFAULT NULL,
                          `no_contact` int(11) DEFAULT NULL,
                          `koleksi_per_tahun` int(11) DEFAULT NULL,
                          `keterangan` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
                          `status` int(11) DEFAULT NULL,
                          `CreateBy` int(11) DEFAULT NULL,
                          `CreateDate` datetime DEFAULT NULL,
                          `UpdateBy` int(11) DEFAULT NULL,
                          `UpdateDate` datetime DEFAULT NULL,
                          PRIMARY KEY (`ID`),
                          KEY `(id_group)group-ws` (`id_group_deposit_group_ws`),
                          KEY `(ID)deposit_kode_wilayah` (`ID_deposit_kode_wilayah`),
                          KEY `(ID)deposit_kelompok_penerbit` (`id_deposit_kelompok_penerbit_ws`),
                          CONSTRAINT `(ID)deposit_kelompok_penerbit` FOREIGN KEY (`id_deposit_kelompok_penerbit_ws`) REFERENCES `deposit_kelompok_penerbit` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
                          CONSTRAINT `(ID)deposit_kode_wilayah` FOREIGN KEY (`ID_deposit_kode_wilayah`) REFERENCES `deposit_kode_wilayah` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
                          CONSTRAINT `(id_group)group-ws` FOREIGN KEY (`id_group_deposit_group_ws`) REFERENCES `deposit_group_ws` (`id_group`) ON DELETE NO ACTION ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
                    ")->execute();
                }

                //check if table deposit_group_ws is exist
                $cekTableDepositGroupWs = OpacHelpers::tableExist('deposit_group_ws');
                if($cekTableDepositGroupWs == 0){
                    $command = Yii::$app->db->createCommand("
                        CREATE TABLE `deposit_group_ws`(
                            `id_group` int(11) NOT NULL  auto_increment ,
                            `group_name` varchar(255) COLLATE latin1_swedish_ci NULL  ,
                            `CreateBy` int(11) COLLATE latin1_swedish_ci NULL  ,
                            `CreateDate` datetime COLLATE latin1_swedish_ci NULL  ,
                            `CreateTerminal` varchar(50) COLLATE latin1_swedish_ci NULL  ,
                            `UpdateBy` int(11) COLLATE latin1_swedish_ci NULL  ,
                            `UpdateDate` datetime COLLATE latin1_swedish_ci NULL  ,
                            `UpdateTerminal` varchar(50) COLLATE latin1_swedish_ci NULL  ,
                            PRIMARY KEY (`id_group`),
                            KEY `deposit_group_ws_createby` (`CreateBy`),
                            KEY `deposit_group_ws_updateby` (`UpdateBy`),
                            CONSTRAINT `deposit_group_ws_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
                            CONSTRAINT `deposit_group_ws_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE
                            
                        ) ENGINE=InnoDB DEFAULT CHARSET='latin1';
                    ")->execute();
                }

                //check if table deposit_kode_wilayah is exist
                $cekTableDepositKodeWilayah = OpacHelpers::tableExist('deposit_kode_wilayah');
                if($cekTableDepositKodeWilayah == 0){
                    $command = Yii::$app->db->createCommand("
                        CREATE TABLE `deposit_kode_wilayah`(
                            `ID` int(11) NOT NULL  auto_increment ,
                            `nama_wilayah` varchar(255) COLLATE latin1_swedish_ci NULL  ,
                            `kode_wilayah` varchar(255) COLLATE latin1_swedish_ci NULL  ,
                            `CreateBy` int(11) COLLATE latin1_swedish_ci NULL  ,
                            `CreateDate` datetime COLLATE latin1_swedish_ci NULL  ,
                            `CreateTerminal` varchar(50) COLLATE latin1_swedish_ci NULL  ,
                            `UpdateBy` int(11) COLLATE latin1_swedish_ci NULL  ,
                            `UpdateDate` datetime COLLATE latin1_swedish_ci NULL  ,
                            `UpdateTerminal` varchar(50) COLLATE latin1_swedish_ci NULL  ,
                            PRIMARY KEY (`ID`),
                            KEY `deposit_kode_wilayah_createby` (`CreateBy`),
                            KEY `deposit_kode_wilayah_updateby` (`UpdateBy`),
                            CONSTRAINT `deposit_kode_wilayah_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
                            CONSTRAINT `deposit_kode_wilayah_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE
                            
                        ) ENGINE=InnoDB DEFAULT CHARSET='latin1';
                    ")->execute();
                }

                //check if table deposit_kode_wilayah is exist
                $cekTableLetter = OpacHelpers::tableExist('letter');
                if($cekTableLetter == 0){
                    $command = Yii::$app->db->createCommand("
                        CREATE TABLE `letter` (
                          `ID` int(11) NOT NULL AUTO_INCREMENT,
                          `TYPE_OF_DELIVERY` varchar(21) DEFAULT NULL,
                          `LETTER_DATE` date DEFAULT NULL,
                          `LETTER_NUMBER` varchar(35) DEFAULT NULL,
                          `ACCEPT_DATE` date DEFAULT NULL,
                          `SENDER` varchar(155) DEFAULT NULL,
                          `PHONE` int(11) DEFAULT NULL,
                          `INTENDED_TO` varchar(155) DEFAULT NULL,
                          `IS_PRINTED` int(4) DEFAULT NULL,
                          `CreateDate` datetime DEFAULT NULL,
                          `CreateBy` int(11) DEFAULT NULL,
                          `CreateTerminal` varchar(111) DEFAULT NULL,
                          `UpdateDate` datetime DEFAULT NULL,
                          `UpdateBy` int(11) DEFAULT NULL,
                          `UpdateTerminal` varchar(111) DEFAULT NULL,
                          `PUBLISHER_ID` int(11) DEFAULT NULL,
                          `LETTER_NUMBER_UT` varchar(45) DEFAULT NULL,
                          `IS_SENDEDEMAIL` int(4) DEFAULT NULL,
                          `IS_NOTE` int(4) DEFAULT NULL,
                          `LANG` varchar(20) DEFAULT NULL,
                          PRIMARY KEY (`ID`),
                          KEY `(ID)deposit_ws` (`PUBLISHER_ID`),
                          KEY `letter_createby` (`CreateBy`),
                          KEY `letter_updateby` (`UpdateBy`),
                          CONSTRAINT `(ID)deposit_ws` FOREIGN KEY (`PUBLISHER_ID`) REFERENCES `deposit_ws` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
                          CONSTRAINT `letter_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
                          CONSTRAINT `letter_updateby` FOREIGN KEY (`UpdateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                    ")->execute();
                }

                //check if table deposit_kode_wilayah is exist
                $cekTableLetterDetail = OpacHelpers::tableExist('letter_detail');
                if($cekTableLetterDetail == 0){
                    $command = Yii::$app->db->createCommand("
                        CREATE TABLE `letter_detail` (
                          `ID` int(11) NOT NULL AUTO_INCREMENT,
                          `SUB_TYPE_COLLECTION` int(11) DEFAULT NULL,
                          `TITLE` varchar(255) DEFAULT NULL,
                          `QUANTITY` int(11) DEFAULT NULL,
                          `COPY` int(11) DEFAULT NULL,
                          `PRICE` varchar(100) DEFAULT NULL,
                          `LETTER_ID` int(111) DEFAULT NULL,
                          `COLLECTION_TYPE_ID` int(11) DEFAULT NULL,
                          `REMARK` varchar(255) DEFAULT NULL,
                          `AUTHOR` varchar(155) DEFAULT NULL,
                          `PUBLISHER` varchar(50) DEFAULT NULL,
                          `PUBLISHER_ADDRESS` varchar(255) DEFAULT NULL,
                          `ISBN` varchar(25) DEFAULT NULL,
                          `PUBLISH_YEAR` varchar(15) DEFAULT NULL,
                          `PUBLISHER_CITY` varchar(25) DEFAULT NULL,
                          `ISBN_STATUS` varchar(55) DEFAULT NULL,
                          `KD_PENERBIT_DTL` varchar(25) DEFAULT NULL,
                          PRIMARY KEY (`ID`),
                          KEY `(ID)letter` (`LETTER_ID`),
                          KEY `(ID)collectiomedias` (`SUB_TYPE_COLLECTION`),
                          CONSTRAINT `(ID)collectiomedias` FOREIGN KEY (`SUB_TYPE_COLLECTION`) REFERENCES `collectionmedias` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
                          CONSTRAINT `(ID)letter` FOREIGN KEY (`LETTER_ID`) REFERENCES `letter` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                    ")->execute();
                }

                //check if table deposit_taksiran_harga is exist
                $cekTableTaksiranHarga = OpacHelpers::tableExist('deposit_taksiran_harga');
                if($cekTableTaksiranHarga == 0){
                    $command = Yii::$app->db->createCommand("
                        CREATE TABLE `deposit_taksiran_harga` (
                          `ID` int(11) NOT NULL AUTO_INCREMENT,
                          `ID_collections` double DEFAULT NULL,
                          `cover` varchar(65) DEFAULT NULL,
                          `muka_buku` varchar(65) DEFAULT NULL,
                          `hard_cover` varchar(65) DEFAULT NULL,
                          `penjilidan` varchar(65) DEFAULT NULL,
                          `jumlah_halaman` int(11) DEFAULT NULL,
                          `jenis_kertas_buku` varchar(25) DEFAULT NULL,
                          `ukuran_buku` varchar(6) DEFAULT NULL,
                          `kondisi_buku` varchar(10) DEFAULT NULL,
                          `kondisi_usang` varchar(25) DEFAULT NULL,
                          `full_color` varchar(9) DEFAULT NULL,
                          PRIMARY KEY (`ID`),
                          KEY `colections_id` (`ID_collections`),
                          CONSTRAINT `colections_id` FOREIGN KEY (`ID_collections`) REFERENCES `collections` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                    ")->execute();
                }

                $IsDeposit = OpacHelpers::columnExist('collections','IsDeposit');
                    if($IsDeposit== 0){
                        $command = Yii::$app->db->createCommand("
                        ALTER TABLE `collections`   
                        ADD COLUMN `IsDeposit` int(1) DEFAULT NULL after `BookingExpiredDate`;  
                        ")->execute();
                    }

                $NomorDeposit = OpacHelpers::columnExist('collections','NomorDeposit');
                    if($NomorDeposit== 0){
                        $command = Yii::$app->db->createCommand("
                        ALTER TABLE `collections`   
                        ADD COLUMN `NomorDeposit` varchar(21) DEFAULT NULL after `IsDeposit`;  
                        ")->execute();
                    }

                $ThnTerbitDeposit = OpacHelpers::columnExist('collections','ThnTerbitDeposit');
                    if($ThnTerbitDeposit== 0){
                        $command = Yii::$app->db->createCommand("
                        ALTER TABLE `collections`   
                        ADD COLUMN `ThnTerbitDeposit` int(11) DEFAULT NULL after `NomorDeposit`;  
                        ")->execute();
                    }

                $deposit_ws_ID = OpacHelpers::columnExist('collections','deposit_ws_ID');
                    if($deposit_ws_ID== 0){
                        $command = Yii::$app->db->createCommand("
                        ALTER TABLE `collections`   
                        ADD COLUMN `deposit_ws_ID` int(11) DEFAULT NULL after `ThnTerbitDeposit`;  
                        ")->execute();
                    }
                $isExist = OpacHelpers::ConstraintExist('collections','collections_deposit_ws_ID','FOREIGN KEY');
                    if($isExist== 0){
                        $command = Yii::$app->db->createCommand("
                            ALTER TABLE `collections`
                            ADD CONSTRAINT `collections_deposit_ws_ID`
                            FOREIGN KEY (`deposit_ws_ID`) REFERENCES `deposit_ws` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
                        ")->execute();
                    }                

                $deposit_kode_wilayah_ID = OpacHelpers::columnExist('collections','deposit_kode_wilayah_ID');
                    if($deposit_kode_wilayah_ID== 0){
                        $command = Yii::$app->db->createCommand("
                        ALTER TABLE `collections`   
                        ADD COLUMN `deposit_kode_wilayah_ID` int(11) DEFAULT NULL after `deposit_ws_ID`;  
                        ")->execute();
                    }
                $isExist = OpacHelpers::ConstraintExist('collections','collection_deposit_kode_wilayah_ID','FOREIGN KEY');
                    if($isExist== 0){
                        $command = Yii::$app->db->createCommand("
                            ALTER TABLE `collections`
                            ADD CONSTRAINT `collection_deposit_kode_wilayah_ID`
                            FOREIGN KEY (`deposit_kode_wilayah_ID`) REFERENCES `deposit_kode_wilayah` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
                        ")->execute();
                    }  

                $Nomor_Regis = OpacHelpers::columnExist('collections','Nomor_Regis');
                    if($Nomor_Regis== 0){
                        $command = Yii::$app->db->createCommand("
                        ALTER TABLE `collections`   
                        ADD COLUMN `Nomor_Regis` varchar(11) DEFAULT NULL after `deposit_kode_wilayah_ID`;  
                        ")->execute();
                    }

                //tambah menu deposit
                $command = Yii::$app->db->createCommand("
                DROP TABLE IF EXISTS `deposit_kelompok_penerbit`;

                CREATE TABLE `deposit_kelompok_penerbit` (
                  `ID` int(11) NOT NULL AUTO_INCREMENT,
                  `Name` varchar(111) DEFAULT NULL,
                  `CreateBy` int(11) DEFAULT NULL,
                  `CreateDate` datetime DEFAULT NULL,
                  PRIMARY KEY (`ID`),
                  KEY `deposit_kelompok_penerbit_createby` (`CreateBy`),
                  CONSTRAINT `deposit_kelompok_penerbit_createby` FOREIGN KEY (`CreateBy`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
                ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

                /*Data for the table `deposit_kelompok_penerbit` */

                insert  into `deposit_kelompok_penerbit`(`ID`,`Name`,`CreateBy`,`CreateDate`) values 
                (1,'Anggota IKAPI',33,'2018-10-16 12:43:57'),
                (2,'Non Anggota IKAPI',33,'2018-10-16 12:44:15'),
                (3,'Anggota SPS',33,'2018-10-16 12:44:31'),
                (4,'Non Anggota SPS',33,'2018-10-16 12:44:47'),
                (5,'Anggota ASIRI',33,'2018-10-16 12:44:59'),
                (6,'Non Anggota ASIRI',33,'2018-10-16 12:45:10');
                ")->execute();

            

            //check if module deposit active
            if(Yii::$app->config->get('ModuleDeposit') == '1'){             

                $command = Yii::$app->db->createCommand("
                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Pengaturan SSKCKR' AS `name`,51 AS `parent`,'/setting/default/index' AS `route`,2 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Pengaturan SSKCKR');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'Pengaturan SSKCKR');
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Master Group Wajib Serah' AS `name`,@id_parent AS `parent`,'/setting/deposit/deposit-group-ws/index' AS `route`,1 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Master Group Wajib Serah');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'Pengaturan SSKCKR');
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Master Wajib Serah' AS `name`,@id_parent AS `parent`,'/setting/deposit/deposit-ws/index' AS `route`,2 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Master Wajib Serah');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'Pengaturan SSKCKR');
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Master Kode Wilayah' AS `name`,@id_parent AS `parent`,'/setting/deposit/deposit-kode-wilayah/index' AS `route`,3 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Master Kode Wilayah');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'Pengaturan SSKCKR');
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Master Kode Jenis Koleksi SSKCKR' AS `name`,@id_parent AS `parent`,'/setting/deposit/deposit-bahan-pustaka/index' AS `route`,6 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Master Kode Jenis Koleksi SSKCKR');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'Pengaturan SSKCKR');
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Master Lembar Kerja SSKCKR' AS `name`,@id_parent AS `parent`,'/setting/deposit/lembar-kerja-deposit/index' AS `route`,6 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Master Lembar Kerja SSKCKR');



                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'SSKCKR' AS `name`,NULL AS `parent`,'/deposit/default/index' AS `route`,3 AS `order`,'fa-book' AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'SSKCKR');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR');
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Registrasi SSKCKR' AS `name`,@id_parent AS `parent`,'/deposit/transaction' AS `route`,1 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Registrasi SSKCKR');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR');
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Daftar Koleksi SSKCKR' AS `name`,@id_parent AS `parent`,'/deposit/transaction/list-deposit' AS `route`,2 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Daftar Koleksi SSKCKR');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR');
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Ucapan Terima Kasih' AS `name`,@id_parent AS `parent`,'/deposit/terima-kasih' AS `route`,3 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Ucapan Terima Kasih');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'Laporan');
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'SSKCKR' AS `name`,@id_parent AS `parent`,'/laporan/deposit/index' AS `route`,11 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Laporan Per Group' AS `name`,@id_parent AS `parent`,'/laporan/deposit/per-group' AS `route`,1 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Laporan Per Group');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Jumlah Jenis Koleksi' AS `name`,@id_parent AS `parent`,'/laporan/deposit/jenis-koleksi' AS `route`,2 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Jumlah Jenis Koleksi');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Jumlah Koleksi Diserahkan' AS `name`,@id_parent AS `parent`,'/laporan/deposit/wajib-serah' AS `route`,3 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Jumlah Koleksi Diserahkan');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Koleksi Wajib Serah Detail' AS `name`,@id_parent AS `parent`,'/laporan/deposit/wajib-serah-detail' AS `route`,4 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Koleksi Wajib Serah Detail');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Laporan Penerbit' AS `name`,@id_parent AS `parent`,'/laporan/deposit/penerbit' AS `route`,5 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Laporan Penerbit');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Laporan Penerbit Per Wilayah' AS `name`,@id_parent AS `parent`,'/laporan/deposit/penerbit-wilayah' AS `route`,6 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Laporan Penerbit Per Wilayah');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Laporan Terima Kasih' AS `name`,@id_parent AS `parent`,'/laporan/deposit/terima-kasih' AS `route`,7 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Laporan Terima Kasih');
                
                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Cardex' AS `name`,@id_parent AS `parent`,'/laporan/deposit/cardex' AS `route`,8 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Cardex');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Serial' AS `name`,@id_parent AS `parent`,'/laporan/deposit/serial' AS `route`,9 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Serial');

                SET @id = (SELECT MAX(ID) FROM `menu`) + 1; 
                SET @id_parent = (SELECT menu.`id` FROM menu WHERE menu.`name` = 'SSKCKR' AND menu.`parent` IS NOT NULL);
                INSERT INTO `menu`(`id`,`name`,`parent`,`route`,`order`,`data`,`CreateBy`,`CreateDate`,`CreateTerminal`,`UpdateBy`,`UpdateTerminal`,`UpdateDate`)
                SELECT * FROM (SELECT @id AS `id`,'Aset' AS `name`,@id_parent AS `parent`,'/laporan/deposit/aset' AS `route`,10 AS `order`,NULL AS `data`,NULL AS `CreateBy`,NULL AS `CreateDate`,NULL AS `CreateTerminal`,NULL AS `UpdateBy`,NULL AS `UpdateTerminal`,NULL AS `UpdateDate`) AS tmp
                WHERE NOT EXISTS (SELECT `name` FROM `menu` WHERE menu.`name` = 'Aset');
                ")->execute();
            }

            //update FOREIGN_KEY_CHECKS 1
            
            $command = Yii::$app->db->createCommand("
               SET FOREIGN_KEY_CHECKS = 1;
            ")->execute();
            /************************* Batas Create Tabel ********************************/

        }catch (Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
            $trans->rollback();
    }
    /**
     * Script end for updating to version 3.2.


     */

    $err=[];
    $err2=[];
    
    // format kode deposit bahan pustaka
    try {
        $cekKodeBahanPustaka = OpacHelpers::columnExist('collectionmedias','KodeBahanPustaka');
        if($cekKodeBahanPustaka == 0){

        $command = Yii::$app->db->createCommand("
            ALTER TABLE `collectionmedias` 
            ADD COLUMN `KodeBahanPustaka` VARCHAR(100) NULL AFTER `Worksheet_id`;

        ")->execute();
        }
    } catch (\Exception $e) {
        if ($e->errorInfo[2]) {
            array_push($err, $e->errorInfo[2]);
        }
    }

    // artikel bebas di worksheet
    try {
        $cekArtikelBebasWorksheet = OpacHelpers::columnExist('worksheets','IsBerisiArtikel');
        if($cekArtikelBebasWorksheet == 0){

        $command = Yii::$app->db->createCommand("
            ALTER TABLE `worksheets`   
            ADD COLUMN `IsBerisiArtikel` bit(1)   NULL DEFAULT b'0' after `ISKARTOGRAFI`;  

        ")->execute();
        }

		//karantina katalog 3.2
		//IsDeposit
        try {
            $cekIsDeposit = OpacHelpers::columnExist('quarantined_collections','IsDeposit');
            if($cekIsDeposit == 0){
            $command = Yii::$app->db->createCommand("
                ALTER TABLE `quarantined_collections` 
                ADD `IsDeposit` INT(1) DEFAULT NULL;
            ")->execute();
            }

        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
		//NomorDeposit
		try {
            $cekNomorDeposit = OpacHelpers::columnExist('quarantined_collections','NomorDeposit');
            if($cekNomorDeposit == 0){
            $command = Yii::$app->db->createCommand("
                ALTER TABLE `quarantined_collections` 
                ADD `NomorDeposit` VARCHAR(21) DEFAULT NULL;
            ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
		//ThnTerbitDeposit
		try {
            $cekThnTerbitDeposit = OpacHelpers::columnExist('quarantined_collections','ThnTerbitDeposit');
            if($cekThnTerbitDeposit == 0){
            $command = Yii::$app->db->createCommand("
                ALTER TABLE `quarantined_collections` 
                ADD `ThnTerbitDeposit` INT(11) DEFAULT NULL;
            ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
		//deposit_ws_ID
		try {
            $cekdeposit_ws_ID = OpacHelpers::columnExist('quarantined_collections','deposit_ws_ID');
            if($cekdeposit_ws_ID == 0){
            $command = Yii::$app->db->createCommand("
                ALTER TABLE `quarantined_collections` 
                ADD `deposit_ws_ID` INT(11) DEFAULT NULL;
            ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
		//deposit_kode_wilayah_ID
		try {
            $cekdeposit_kode_wilayah_ID = OpacHelpers::columnExist('quarantined_collections','deposit_kode_wilayah_ID');
            if($cekdeposit_kode_wilayah_ID == 0){
            $command = Yii::$app->db->createCommand("
                ALTER TABLE `quarantined_collections` 
                ADD `deposit_kode_wilayah_ID` INT(11) DEFAULT NULL;
            ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
		//Nomor_Regis
		try {
            $cekNomor_Regis = OpacHelpers::columnExist('quarantined_collections','Nomor_Regis');
            if($cekNomor_Regis == 0){
            $command = Yii::$app->db->createCommand("
                ALTER TABLE `quarantined_collections` 
                ADD `Nomor_Regis` VARCHAR(11) DEFAULT NULL;
            ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
		
		// collection rule
		try {
            $data = Yii::$app->db->createCommand("select * from collections where ID = 6")->execute();
            if($data==0){
                $command = Yii::$app->db->createCommand("
                insert into `collectionrules` (`ID`, `Name`, `CreateBy`, `CreateDate`, `CreateTerminal`, `UpdateBy`, `UpdateDate`, `UpdateTerminal`) values('6','Deposit',NULL,'2021-02-08 00:00:00','192.168.0.1',NULL,'2021-02-08 00:00:00','192.168.0.1');
                ")->execute();
            }
        } catch (\Exception $e) {
            if ($e->errorInfo[2]) {
                array_push($err, $e->errorInfo[2]);
            }
        }
		
		
    $this->RmdirRuntime();
    } catch (\Exception $e) {
        if ($e->errorInfo[2]) {
            array_push($err, $e->errorInfo[2]);
        }
    }
		
		// $path = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
        
  //       $start_php = dirname(dirname($_SERVER['MIBDIRS'])).'/php.exe';
  //       $exec = exec($path. '/yii clean-assets');
	}

}
