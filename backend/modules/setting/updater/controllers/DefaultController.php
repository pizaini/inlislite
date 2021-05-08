<?php

namespace backend\modules\setting\updater\controllers;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `update` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

//insertTempSederhanaOpac
      $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `insertTempSederhanaOpac`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	
		CREATE  PROCEDURE `insertTempSederhanaOpac`(
		IN keyword TEXT,
		IN tag TEXT,
		IN worksheet VARCHAR(255),
		IN fAuthor TEXT,
		IN fPublisher TEXT,
		IN fPublishLoc TEXT,
		IN fPublishYear TEXT,
		IN fSubject TEXT,
		IN fromTgl TEXT,
		IN toTgl TEXT,
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
		CoverURL TEXT,
		worksheet_id INT,
		worksheet TEXT,
		JML_BUKU INT,
		ALL_BUKU INT,
		KONTEN_DIGITAL VARCHAR(100)
		);
		IF isLKD = 1
		then
		 INSERT INTO tempCariOpac
		 SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
		               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
		                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
		                 (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
		                 (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2) SEPARATOR ', ') 
		                 FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
		      FROM catalogs CAT 
		      	 INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id 
		      
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
				IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
				IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
				IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
				IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
		        IF(fSubject='',1=1,CAT.Subject = fSubject) AND
				
		        CAT.isopac=1 
		        
				
			 HAVING CASE worksheet
				  WHEN 'Semua Format File' THEN 1 = 1
				  WHEN worksheet THEN KONTEN_DIGITAL like  worksheet        
				 END
		        ;  
		else             
		IF worksheet='4' THEN   
		INSERT INTO tempCariOpac
		SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
		               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
		                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
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
		        IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
				IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
				IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
				IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
		        IF(fSubject='',1=1,CAT.Subject = fSubject) AND
				
		        CAT.isopac=1 
				;    
		else
		INSERT INTO tempCariOpac
		SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
		               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
		                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
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
		        IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
				IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
				IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
				IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
		        IF(fSubject='',1=1,CAT.Subject = fSubject) AND
				
		        CAT.isopac=1 
				;  
		end if;
		end if;
		END
    ");
    $command->execute();

//insertTempSederhanaOpac0
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `insertTempSederhanaOpac0`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
		CREATE PROCEDURE `insertTempSederhanaOpac0`(
		IN keyword TEXT,
		IN tag TEXT,
		IN worksheet VARCHAR(255),
		IN limit1 int,
		IN limit2 int,
		IN fAuthor TEXT,
		IN fPublisher TEXT,
		IN fPublishLoc TEXT,
		IN fPublishYear TEXT,
		IN fSubject TEXT,
		IN fromTgl TEXT,
		IN toTgl TEXT,
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
		CoverURL TEXT,
		worksheet_id INT,
		worksheet TEXT,
		JML_BUKU INT,
		ALL_BUKU INT,
		KONTEN_DIGITAL VARCHAR(100)
		);
		IF isLKD = 1
		then
		 INSERT INTO tempCariOpac
		 SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
		               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
		                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
		                 (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
		                 (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2) SEPARATOR ', ') 
		                 FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
		      FROM catalogs CAT 
		      	 INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id 
		      
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
		        IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
				IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
				IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
				IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
		        IF(fSubject='',1=1,CAT.Subject = fSubject) AND
				
		        CAT.isopac=1
		        
				
			 HAVING CASE worksheet
				 WHEN 'pdf' THEN KONTEN_DIGITAL = worksheet
				 WHEN 'doc' THEN KONTEN_DIGITAL = worksheet
				 WHEN 'xls' THEN KONTEN_DIGITAL = worksheet
				 WHEN 'rar' THEN KONTEN_DIGITAL = worksheet
		         WHEN 'Semua Format File' THEN 1 = 1
		        
					END
		        LIMIT limit1,limit2;  
		else             
		IF worksheet='4' THEN   
		INSERT INTO tempCariOpac
		SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
		               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
		                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
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
		        IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
				IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
				IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
				IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
		        IF(fSubject='',1=1,CAT.Subject = fSubject) AND
				
		        CAT.isopac=1 
				LIMIT limit1,limit2;    
		else
		INSERT INTO tempCariOpac
		SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
		               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
		                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
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
		        IF(fAuthor='',1=1,CAT.Author = fAuthor) AND
				IF(fPublisher='',1=1,CAT.Publisher = fPublisher) AND
				IF(fPublishLoc='',1=1,CAT.PublishLocation = fPublishLoc) AND
				IF(fPublishYear='',1=1,CAT.PublishYear = fPublishYear) AND
		        IF(fSubject='',1=1,CAT.Subject = fSubject) AND
				
		        CAT.isopac=1 
				LIMIT limit1,limit2;  
		end if;
		end if;
		END    		
    ");
    $command->execute();

//pencarianSederhanaOpacLimit1
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `pencarianSederhanaOpacLimit1`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
		CREATE  PROCEDURE `pencarianSederhanaOpacLimit1`(
		IN limit1 INT, 
		IN limit2 INT,
		IN fAuthor TEXT,
		IN fPublisher TEXT,
		IN fPublishLoc TEXT,
		IN fPublishYear TEXT,
		IN fSubject TEXT
		)
		BEGIN
		SELECT CatalogId,title kalimat2,author,publisher,PublishLocation,PublishYear,CoverURL,worksheet_id,
		               worksheet,
		                JML_BUKU,
		                ALL_BUKU,
		                KONTEN_DIGITAL, Subject
		      FROM tempCariOpac 
		      
		      WHERE 
			 IF(fAuthor='',1=1,author = fAuthor) AND
		           IF(fPublisher='',1=1,publisher = fPublisher) AND
		      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
		      IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
		       IF(fSubject='',1=1,Subject = fSubject)
		      
		      
		      LIMIT limit1,limit2;    
		END
    ");
    $command->execute();
//countPencarianSederhanaOpac1
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `countPencarianSederhanaOpac1`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE  PROCEDURE `countPencarianSederhanaOpac1`(
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	IN fSubject TEXT
	)
	BEGIN
	SELECT COUNT(1)
	      FROM tempCariOpac 
	      
	      WHERE 
	      	IF(fAuthor='',1=1,author = fAuthor) AND
	      IF(fPublisher='',1=1,publisher = fPublisher) AND
	      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
	      IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
	      IF(fSubject='',1=1,Subject = fSubject) ;
	      
	         
	END
    ");
    $command->execute();

//facedAuthorOpac1
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `facedAuthorOpac1`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE  PROCEDURE `facedAuthorOpac1`(
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	IN fSubject TEXT,
	IN maxFaced INT
	)
	BEGIN
	-- call explode_table(';',fAuthor,fPublisher,fPublishLoc,fPublishYear,fSubject);
	              
	-- SELECT  COALESCE(Value,'-') Author ,COUNT(1) jml FROM table2 group by COALESCE(Value,'-') order by jml desc LIMIT 0,maxFaced;    
	          
	SELECT Author,COUNT(1) jml
	      FROM tempCariOpac 
	      
	      WHERE 
	      IF(fAuthor='',1=1,author = fAuthor) AND
	      IF(fPublisher='',1=1,publisher = fPublisher) AND
	      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
	      IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
		  IF(fSubject='',1=1,Subject = fSubject)
		GROUP BY Author
		ORDER BY jml DESC
		LIMIT 0,maxFaced;    


	END
    ");
    $command->execute();
//facedPublisherOpac1
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `facedPublisherOpac1`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE PROCEDURE `facedPublisherOpac1`(
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	in fSubject TEXT,
	IN maxFaced INT
	)
	BEGIN
	              
	SELECT Publisher,COUNT(1) jml
	      FROM tempCariOpac 
	      
	      WHERE 
	      IF(fAuthor='',1=1,author = fAuthor) AND
	      IF(fPublisher='',1=1,publisher = fPublisher) AND
	      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
	      IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
		  IF(fSubject='',1=1,Subject = fSubject)
		GROUP BY Publisher
		ORDER BY jml DESC
		LIMIT 0,maxFaced;    
		
		  
	          
	END
    ");
    $command->execute();
//facedPublishLocationOpac1
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `facedPublishLocationOpac1`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE  PROCEDURE `facedPublishLocationOpac1`(
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	in fSubject TEXT,
	IN maxFaced INT
	)
	BEGIN
	              
	SELECT PublishLocation,COUNT(1) jml
	      FROM tempCariOpac 
	      
	      WHERE 
	      IF(fAuthor='',1=1,author = fAuthor) AND
	      IF(fPublisher='',1=1,publisher = fPublisher) AND
	      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
	      IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
		  IF(fSubject='',1=1,Subject = fSubject)
		GROUP BY PublishLocation
		ORDER BY jml DESC
		LIMIT 0,maxFaced;    
	END
    ");
    $command->execute();
//facedPublishYearOpac1
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `facedPublishYearOpac1`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE  PROCEDURE `facedPublishYearOpac1`(
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	IN fSubject TEXT,
	IN maxFaced INT
	)
	BEGIN
	              
	SELECT PublishYear,COUNT(1) jml
	      FROM tempCariOpac
	      
	      WHERE 
	      IF(fAuthor='',1=1,author = fAuthor) AND
	      IF(fPublisher='',1=1,publisher = fPublisher) AND
	      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
	      IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
		  IF(fSubject='',1=1,Subject = fSubject)
		GROUP BY PublishYear
		ORDER BY jml DESC
		LIMIT 0,maxFaced;      
	          
	END
    ");
    $command->execute();
//facedSubjectOpac1
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `facedSubjectOpac1`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE  PROCEDURE `facedSubjectOpac1`(
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	IN fSubject TEXT,
	IN maxFaced INT
	)
	BEGIN
	              
	SELECT COALESCE(Subject,'-') Subject,COUNT(1) jml
	      FROM tempCariOpac 
	      
	      WHERE 
	      IF(fAuthor='',1=1,author = fAuthor) AND
	      IF(fPublisher='',1=1,publisher = fPublisher) AND
	      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
	      IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
		  IF(fSubject='',1=1,Subject = fSubject)
		GROUP BY Subject ORDER BY jml DESC
		LIMIT 0,maxFaced;    
	          
	END
    ");
    $command->execute();

//insertTempLanjutOpac
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `insertTempLanjutOpac`;");
    $command->execute();

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
	IN isLKD TEXT
	)
	BEGIN
	DECLARE querys,querys2,querys3,bhs,karya,pembaca TEXT;
	set querys='';
	set querys2='';
	set querys3='';
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
	CoverURL TEXT,
	worksheet_id INT,
	worksheet TEXT,
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
	   
	   
	    IF worksheet <> 'Semua Format FIle' and isLKD = 1  THEN SET querys2 = CONCAT(querys2,' HAVING KONTEN_DIGITAL =  ''',worksheet,''''); 
	   END IF;
	    IF isLKD  = 1  THEN SET querys3 = CONCAT(querys3,' INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id '); 
	   END IF;
	   
	   
	SET @query_as_string=CONCAT('
		INSERT INTO tempCariOpac
		 SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
	               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
	                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
	                 (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
	                 (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE(''.'',REVERSE(FileURL)))+2) SEPARATOR '','') 
	                 FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
	      FROM catalogs CAT 
	      -- LEFT JOIN collections col ON col.Catalog_id = CAT.ID
		  
	      ',querys3,' 
	      WHERE EXISTS           
	               (SELECT  1
	                FROM catalog_ruas R  
	                 WHERE 
	                 
	                
	                 
	',keyword,bhs,karya,pembaca,'  AND R.CATALOGID=CAT.ID) AND CAT.isopac=1',querys,querys2);
	 PREPARE statement_1 
	  FROM @query_as_string ;
	   EXECUTE statement_1;
	  DEALLOCATE PREPARE statement_1;
			
	END
    ");
    $command->execute();
//insertTempLanjutOpac0
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `insertTempLanjutOpac0`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE  PROCEDURE `insertTempLanjutOpac0`(
	IN worksheet TEXT,
	IN bahasa TEXT,
	IN targetPembaca TEXT,
	in bentukKarya TEXT,
	IN keyword TEXT,
	IN limit1 TEXT,
	IN limit2 TEXT,
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	IN fSubject TEXT,
	IN isLKD TEXT
	)
	BEGIN
	DECLARE querys,querys2,querys3,bhs,karya,pembaca TEXT;
	set querys='';
	set querys2='';
	set querys3='';
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
	CoverURL TEXT,
	worksheet_id INT,
	worksheet TEXT,
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
	   
	   
	   
	      IF worksheet <> 'Semua Format FIle' and isLKD = 1  THEN SET querys2 = CONCAT(querys2,' HAVING KONTEN_DIGITAL =  ''',worksheet,''''); 
	   END IF;
	   
	    IF isLKD  = 1  THEN SET querys3 = CONCAT(querys3,' INNER JOIN catalogfiles CF on  CAT.ID = CF.Catalog_id '); 
	   END IF;
	   
	   
	SET @query_as_string=CONCAT('
		INSERT INTO tempCariOpac
		 SELECT distinct CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
	               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
	                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
	                 (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
	                 (SELECT GROUP_CONCAT(DISTINCT SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE(''.'',REVERSE(FileURL)))+2) SEPARATOR '','') 
	                 FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
	      FROM catalogs CAT 
	      -- LEFT JOIN collections col ON col.Catalog_id = CAT.ID
		  
	      ',querys3,' 
	   WHERE EXISTS           
	               (SELECT  1
	                FROM catalog_ruas R  
	                 WHERE    
	                
	                 
	',keyword,bhs,karya,pembaca,'AND R.CATALOGID=CAT.ID) AND CAT.isopac=1',querys,querys2,' LIMIT ',limit1,',',limit2,' ');
	 PREPARE statement_1 
	  FROM @query_as_string ;
	   EXECUTE statement_1;
	  DEALLOCATE PREPARE statement_1;
	END
    ");
    $command->execute();

//pencarianLanjutLimitOpac
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `pencarianLanjutLimitOpac`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE PROCEDURE `pencarianLanjutLimitOpac`(
	IN limit1 INT, 
	IN limit2 INT,
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	IN fSubject TEXT
	)
	BEGIN
	SELECT CatalogId,title kalimat2,author,publisher,PublishLocation,PublishYear,Subject,CoverURL,
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
	      IF(fSubject='',1=1,Subject = fSubject) 
	      
	      
	      LIMIT limit1,limit2;    
	END
    ");
    $command->execute();
//countPencarianLanjutOpac1
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `countPencarianLanjutOpac1`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE  PROCEDURE `countPencarianLanjutOpac1`(
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	IN fSubject TEXT
	)
	BEGIN
	SELECT COUNT(1)
	      FROM tempCariOpac
	      
	      WHERE
	      IF(fAuthor='',1=1,author = fAuthor) AND
	           IF(fPublisher='',1=1,publisher = fPublisher) AND
	      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
	      IF(fPublishYear='',1=1,PublishYear = fPublishYear) AND
		  IF(fSubject='',1=1,Subject = fSubject);
	      
	         
	END
    ");
    $command->execute();

//insertTempTelusurOpac
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `insertTempTelusurOpac`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempTelusurOpac`(
	IN tag TEXT,
	IN findby TEXT,
	IN fquery TEXT,
	IN fquery2 TEXT,
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT,
	IN FSubject TEXT,
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
	CoverURL TEXT,
	worksheet_id INT,
	worksheet TEXT,
	JML_BUKU INT,
	ALL_BUKU INT,
	KONTEN_DIGITAL VARCHAR(100)
	);              
	if isLKD = 1 then
			INSERT INTO tempCariOpac
	SELECT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.Subject,CAT.CoverURL ,CAT.Worksheet_id,
	               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
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
	SELECT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.Subject,CAT.CoverURL ,CAT.Worksheet_id,
	               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
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
    ");
    $command->execute();
//countTelusurOpac1
    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `countTelusurOpac1`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	CREATE  PROCEDURE `countTelusurOpac1`(
	IN fAuthor TEXT,
	IN fPublisher TEXT,
	IN fPublishLoc TEXT,
	IN fPublishYear TEXT
	)
	BEGIN
	SELECT COUNT(1)
	      FROM tempCariOpac
	      
	      WHERE 
	      IF(fAuthor='',1=1,author = fAuthor) AND
	      IF(fPublisher='',1=1,publisher = fPublisher) AND
	      IF(fPublishLoc='',1=1,PublishLocation = fPublishLoc) AND
	      IF(fPublishYear='',1=1,PublishYear = fPublishYear);
	      
	         
	END
    ");
    $command->execute();
//BrowseOpac

    $command = Yii::$app->db->createCommand("DROP procedure IF EXISTS `BrowseOpac`;");
    $command->execute();

    $command = Yii::$app->db->createCommand("
	
	CREATE  PROCEDURE `BrowseOpac`(
	IN keyword TEXT,
	IN keyword2 TEXT,
	IN keyword3 TEXT,
	IN isLKD TEXT
	)
	BEGIN
	IF isLKD = 1
	 then
			IF keyword = 'Alphabetical' AND keyword2 = '' THEN
			SET @query_as_string=CONCAT(\"SELECT 'A' AS `A` UNION ALL SELECT 'B' AS `B` UNION ALL SELECT 'C' AS `C` UNION ALL SELECT 'D' AS `D` UNION ALL SELECT 'E' AS `E` UNION ALL SELECT 'F' AS `F` UNION ALL SELECT 'G' AS `G` UNION ALL SELECT 'H' AS `H` UNION ALL SELECT 'I' AS `I` UNION ALL SELECT 'J' AS `J` UNION ALL SELECT 'K' AS `K` UNION ALL SELECT 'L' AS `L` UNION ALL SELECT 'M' AS `M` UNION ALL SELECT 'N' AS `N` UNION ALL SELECT 'O' AS `O` UNION ALL SELECT 'P' AS `P` UNION ALL SELECT 'Q' AS `Q` UNION ALL SELECT 'R' AS `R` UNION ALL SELECT 'S' AS `S` UNION ALL SELECT 'T' AS `T` UNION ALL SELECT 'U' AS `U` UNION ALL SELECT 'V' AS `V` UNION ALL SELECT 'W' AS `W` UNION ALL SELECT 'X' AS `X` UNION ALL SELECT 'Y' AS `Y` UNION ALL SELECT 'Z' AS `Z`\");             
			  PREPARE statement_1 FROM @query_as_string;
			  EXECUTE statement_1;
			  DEALLOCATE PREPARE statement_1;
			END IF;
			IF keyword = 'Alphabetical' and keyword2 <> '' then
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
			IF keyword2 = '' AND keyword <> 'Alphabetical' then
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
			IF keyword2 <> '' AND keyword <> 'Alphabetical' then
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
	else
			IF keyword = 'Alphabetical' AND keyword2 = '' THEN
			SET @query_as_string=CONCAT(\"SELECT 'A' AS `A` UNION ALL SELECT 'B' AS `B` UNION ALL SELECT 'C' AS `C` UNION ALL SELECT 'D' AS `D` UNION ALL SELECT 'E' AS `E` UNION ALL SELECT 'F' AS `F` UNION ALL SELECT 'G' AS `G` UNION ALL SELECT 'H' AS `H` UNION ALL SELECT 'I' AS `I` UNION ALL SELECT 'J' AS `J` UNION ALL SELECT 'K' AS `K` UNION ALL SELECT 'L' AS `L` UNION ALL SELECT 'M' AS `M` UNION ALL SELECT 'N' AS `N` UNION ALL SELECT 'O' AS `O` UNION ALL SELECT 'P' AS `P` UNION ALL SELECT 'Q' AS `Q` UNION ALL SELECT 'R' AS `R` UNION ALL SELECT 'S' AS `S` UNION ALL SELECT 'T' AS `T` UNION ALL SELECT 'U' AS `U` UNION ALL SELECT 'V' AS `V` UNION ALL SELECT 'W' AS `W` UNION ALL SELECT 'X' AS `X` UNION ALL SELECT 'Y' AS `Y` UNION ALL SELECT 'Z' AS `Z`\");             
			  PREPARE statement_1 FROM @query_as_string;
			  EXECUTE statement_1;
			  DEALLOCATE PREPARE statement_1;
			END IF;
			IF keyword = 'Alphabetical' and keyword2 <> '' then
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
			IF keyword2 = '' AND keyword <> 'Alphabetical' then
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
			IF keyword2 <> '' AND keyword <> 'Alphabetical' then
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
	end if;
	END
    ");
    $command->execute();
//end



        return $this->render('index');
    }
}

