<?php

namespace common\components;

use Yii;
use yii\helpers\Html;
use yii\base\ErrorException;
use yii\web\HttpException;


class BackupHelpers 
{
    public static function backupHelp() {

            $model = ' /* TRIGGER */
DELIMITER $$

DROP TRIGGER IF EXISTS `inbox_timestamp`$$

CREATE
    /*[DEFINER = { user | CURRENT_USER }]*/
    TRIGGER `inbox_timestamp` BEFORE INSERT ON `inbox` 
    FOR EACH ROW BEGIN
    IF NEW.ReceivingDateTime = \'0000-00-00 00:00:00\' THEN
        SET NEW.ReceivingDateTime = CURRENT_TIMESTAMP();
    END IF;
END;
$$

DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS `outbox_timestamp`$$

CREATE
    /*[DEFINER = { user | CURRENT_USER }]*/
    TRIGGER `outbox_timestamp` BEFORE INSERT ON `outbox` 
    FOR EACH ROW BEGIN
    IF NEW.InsertIntoDB = \'0000-00-00 00:00:00\' THEN
        SET NEW.InsertIntoDB = CURRENT_TIMESTAMP();
    END IF;
    IF NEW.SendingDateTime = \'0000-00-00 00:00:00\' THEN
        SET NEW.SendingDateTime = CURRENT_TIMESTAMP();
    END IF;
    IF NEW.SendingTimeOut = \'0000-00-00 00:00:00\' THEN
        SET NEW.SendingTimeOut = CURRENT_TIMESTAMP();
    END IF;
END;
$$

DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS `phones_timestamp`$$

CREATE
    /*[DEFINER = { user | CURRENT_USER }]*/
    TRIGGER `phones_timestamp` BEFORE INSERT ON `phones` 
    FOR EACH ROW BEGIN
    IF NEW.InsertIntoDB = \'0000-00-00 00:00:00\' THEN
        SET NEW.InsertIntoDB = CURRENT_TIMESTAMP();
    END IF;
    IF NEW.TimeOut = \'0000-00-00 00:00:00\' THEN
        SET NEW.TimeOut = CURRENT_TIMESTAMP();
    END IF;
END;
$$

DELIMITER ;


DELIMITER $$

DROP TRIGGER IF EXISTS `sentitems_timestamp`$$

CREATE
    /*[DEFINER = { user | CURRENT_USER }]*/
    TRIGGER `sentitems_timestamp` BEFORE INSERT ON `sentitems` 
    FOR EACH ROW BEGIN
    IF NEW.InsertIntoDB = \'0000-00-00 00:00:00\' THEN
        SET NEW.InsertIntoDB = CURRENT_TIMESTAMP();
    END IF;
    IF NEW.SendingDateTime = \'0000-00-00 00:00:00\' THEN
        SET NEW.SendingDateTime = CURRENT_TIMESTAMP();
    END IF;
END;
$$

DELIMITER ;


/* Function */

DELIMITER $$

DROP FUNCTION IF EXISTS `get_next_no_pemijaman_id`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_next_no_pemijaman_id`() RETURNS VARCHAR(20) CHARSET latin1
BEGIN
 DECLARE next_no_pemijaman VARCHAR(20);
 DECLARE last_no_pemijaman BIGINT(20) DEFAULT 1;
 DECLARE pemijaman_count BIGINT(20);
 
 SELECT COUNT(No_pinjaman) INTO pemijaman_count FROM lockers WHERE LEFT(No_pinjaman, 6) = DATE_FORMAT(NOW(),\'%Y%m\') ;
 IF pemijaman_count > 0 THEN
 
   SELECT RIGHT(No_pinjaman, 7) INTO last_no_pemijaman FROM lockers  WHERE LEFT(No_pinjaman, 6) = DATE_FORMAT(NOW(),\'%Y%m\')  ORDER BY no_pinjaman DESC LIMIT 0, 1;
   SET last_no_pemijaman = last_no_pemijaman+ 1;
 ELSE SET last_no_pemijaman =1;
 END IF;
 SET next_no_pemijaman = CONCAT(DATE_FORMAT(NOW(),\'%Y%m\'), REPEAT(0, 7 - LENGTH(last_no_pemijaman)), last_no_pemijaman);
 RETURN CONCAT(DATE_FORMAT(NOW(),\'%Y%m\'),REPEAT(0, 7 - LENGTH(last_no_pemijaman)),last_no_pemijaman);
END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `GetWarningLoanDueDay`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `GetWarningLoanDueDay`( JenisAnggotaID DOUBLE, WorksheetID INT(11)) RETURNS INT(11)
BEGIN
DECLARE WarningLoanDueDay INT(11);
SET WarningLoanDueDay = 0;
SET WarningLoanDueDay = (SELECT WarningLoanDueDay FROM jenis_anggota WHERE jenis_anggota.id = JenisAnggotaID);
IF WarningLoanDueDay = 0 THEN SET WarningLoanDueDay = (SELECT WarningLoanDueDay FROM worksheets WHERE worksheets.id = WorksheetID); END IF;
RETURN WarningLoanDueDay;
END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `hasil35dec`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `hasil35dec`(param1 VARCHAR(3)) RETURNS VARCHAR(11) CHARSET utf8
BEGIN
    DECLARE maxpengunjung VARCHAR(11) ;
    DECLARE pengunjung VARCHAR(11) ;
    SET maxpengunjung =\'\';
    SET pengunjung =\'\';
    IF param1 = \'GST\' THEN
      SELECT MAX(nopengunjung) INTO maxpengunjung FROM memberguesses WHERE SUBSTR(nopengunjung,1,7)= CONCAT(param1,YEAR(NOW()));
    ELSE
      SELECT MAX(nopengunjung) INTO maxpengunjung FROM groupguesses WHERE SUBSTR(nopengunjung,1,7)= CONCAT(param1,YEAR(NOW()));
    END IF;
IF maxpengunjung IS NULL THEN 
    SET maxpengunjung=CONCAT(param1,YEAR(NOW()),\'0001\');
ELSE
    SET pengunjung =SUBSTR(maxpengunjung,8,4);
    SET pengunjung=CONV(pengunjung,36,10);
    SET pengunjung=pengunjung+1;
    SET pengunjung=CONV(pengunjung,10,36);
    SET maxpengunjung=CONCAT(param1,YEAR(NOW()),REPEAT(0, 4 - LENGTH(pengunjung)),pengunjung);
END IF;
RETURN maxpengunjung;
    END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `jaro_winkler_similarity`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `jaro_winkler_similarity`(
in1 TEXT,
in2 TEXT
) RETURNS FLOAT
    DETERMINISTIC
BEGIN
DECLARE finestra, curString, curSub, maxSub, trasposizioni, prefixlen, maxPrefix INT;
DECLARE char1, char2 CHAR(1);
DECLARE common1, common2, old1, old2 TEXT;
DECLARE trovato BOOLEAN;
DECLARE returnValue, jaro FLOAT;
SET maxPrefix=6; SET common1=\"\";
SET common2=\"\";
SET finestra=(LENGTH(in1)+LENGTH(in2)-ABS(LENGTH(in1)-LENGTH(in2))) DIV 4
+ ((LENGTH(in1)+LENGTH(in2)-ABS(LENGTH(in1)-LENGTH(in2)))/2) MOD 2;
SET old1=in1;
SET old2=in2;
SET curString=1;
WHILE curString<=LENGTH(in1) AND (curString<=(LENGTH(in2)+finestra)) DO
SET curSub=curstring-finestra;
IF (curSub)<1 THEN
SET curSub=1;
END IF;
SET maxSub=curstring+finestra;
IF (maxSub)>LENGTH(in2) THEN
SET maxSub=LENGTH(in2);
END IF;
SET trovato = FALSE;
WHILE curSub<=maxSub AND trovato=FALSE DO
IF SUBSTR(in1,curString,1)=SUBSTR(in2,curSub,1) THEN
SET common1 = CONCAT(common1,SUBSTR(in1,curString,1));
SET in2 = CONCAT(SUBSTR(in2,1,curSub-1),CONCAT(\"0\",SUBSTR(in2,curSub+1,LENGTH(in2)-curSub+1)));
SET trovato=TRUE;
END IF;
SET curSub=curSub+1;
END WHILE;
SET curString=curString+1;
END WHILE;
SET in2=old2;
SET curString=1;
WHILE curString<=LENGTH(in2) AND (curString<=(LENGTH(in1)+finestra)) DO
SET curSub=curstring-finestra;
IF (curSub)<1 THEN
SET curSub=1;
END IF;
SET maxSub=curstring+finestra;
IF (maxSub)>LENGTH(in1) THEN
SET maxSub=LENGTH(in1);
END IF;
SET trovato = FALSE;
WHILE curSub<=maxSub AND trovato=FALSE DO
IF SUBSTR(in2,curString,1)=SUBSTR(in1,curSub,1) THEN
SET common2 = CONCAT(common2,SUBSTR(in2,curString,1));
SET in1 = CONCAT(SUBSTR(in1,1,curSub-1),CONCAT(\"0\",SUBSTR(in1,curSub+1,LENGTH(in1)-curSub+1)));
SET trovato=TRUE;
END IF;
SET curSub=curSub+1;
END WHILE;
SET curString=curString+1;
END WHILE;
SET in1=old1;
IF LENGTH(common1)<>LENGTH(common2)
THEN SET jaro=0;
ELSEIF LENGTH(common1)=0 OR LENGTH(common2)=0
THEN SET jaro=0;
ELSE
SET trasposizioni=0;
SET curString=1;
WHILE curString<=LENGTH(common1) DO
IF(SUBSTR(common1,curString,1)<>SUBSTR(common2,curString,1)) THEN
SET trasposizioni=trasposizioni+1;
END IF;
SET curString=curString+1;
END WHILE;
SET jaro=
(
LENGTH(common1)/LENGTH(in1)+
LENGTH(common2)/LENGTH(in2)+
(LENGTH(common1)-trasposizioni/2)/LENGTH(common1)
)/3;
END IF; SET prefixlen=0;
WHILE (SUBSTRING(in1,prefixlen+1,1)=SUBSTRING(in2,prefixlen+1,1)) AND (prefixlen<6) DO
SET prefixlen= prefixlen+1;
END WHILE;
RETURN jaro+(prefixlen*0.1*(1-jaro));
END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `KATEGORY_UMUR`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `KATEGORY_UMUR`( tglLahir_prm DATETIME ) RETURNS VARCHAR(200) CHARSET latin1 COLLATE latin1_general_ci
BEGIN
    DECLARE umuranggota INTEGER;
    DECLARE katagory_umur_param VARCHAR(200);
    
    SET umuranggota =YEAR(NOW())-YEAR(tglLahir_prm);
    
    IF DATE_FORMAT(tglLahir_prm,\'%m%d\')>DATE_FORMAT(NOW(),\'%m%d\') THEN SET umuranggota =umuranggota -1; END IF;
    SELECT master_range_umur.`Keterangan` INTO katagory_umur_param FROM master_range_umur WHERE umuranggota BETWEEN master_range_umur.`umur1` AND master_range_umur.`umur2`;
    RETURN katagory_umur_param;
    END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `levenshtein`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `levenshtein`( s1 TEXT, s2 TEXT) RETURNS INT(11)
    DETERMINISTIC
BEGIN 
    DECLARE s1_len, s2_len, i, j, c, c_temp, cost INT; 
    DECLARE s1_char CHAR; 
    DECLARE cv0, cv1 TEXT; 
    SET s1_len = CHAR_LENGTH(s1), s2_len = CHAR_LENGTH(s2), cv1 = 0x00, j = 1, i = 1, c = 0; 
    IF s1 = s2 THEN 
      RETURN 0; 
    ELSEIF s1_len = 0 THEN 
      RETURN s2_len; 
    ELSEIF s2_len = 0 THEN 
      RETURN s1_len; 
    ELSE 
      WHILE j <= s2_len DO 
        SET cv1 = CONCAT(cv1, UNHEX(HEX(j))), j = j + 1; 
      END WHILE; 
      WHILE i <= s1_len DO 
        SET s1_char = SUBSTRING(s1, i, 1), c = i, cv0 = UNHEX(HEX(i)), j = 1; 
        WHILE j <= s2_len DO 
          SET c = c + 1; 
          IF s1_char = SUBSTRING(s2, j, 1) THEN  
            SET cost = 0; ELSE SET cost = 1; 
          END IF; 
          SET c_temp = CONV(HEX(SUBSTRING(cv1, j, 1)), 16, 10) + cost; 
          IF c > c_temp THEN SET c = c_temp; END IF; 
            SET c_temp = CONV(HEX(SUBSTRING(cv1, j+1, 1)), 16, 10) + 1; 
            IF c > c_temp THEN  
              SET c = c_temp;  
            END IF; 
            SET cv0 = CONCAT(cv0, UNHEX(HEX(c))), j = j + 1; 
        END WHILE; 
        SET cv1 = cv0, i = i + 1; 
      END WHILE; 
    END IF; 
    RETURN c; 
  END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `levenshtein_ratio`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `levenshtein_ratio`( s1 TEXT, s2 TEXT ) RETURNS INT(11)
    DETERMINISTIC
BEGIN 
    DECLARE s1_len, s2_len, max_len INT; 
    SET s1_len = LENGTH(s1), s2_len = LENGTH(s2); 
    IF s1_len > s2_len THEN  
      SET max_len = s1_len;  
    ELSE  
      SET max_len = s2_len;  
    END IF; 
    RETURN ROUND((1 - LEVENSHTEIN(s1, s2) / max_len) * 100); 
  END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `regex_replace_master`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `regex_replace_master`(pattern VARCHAR(1000),replacement VARCHAR(1000),original VARCHAR(1000)) RETURNS VARCHAR(1000) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE temp VARCHAR(1000);
DECLARE ch VARCHAR(1);
DECLARE i INT;
DECLARE j INT;
DECLARE qbTemp VARCHAR(1000);
SET i = 1;
SET j = 1;
SET temp = \'\';
SET qbTemp = \'\';
IF original REGEXP pattern THEN
loop_label: LOOP
IF i>CHAR_LENGTH(original) THEN
LEAVE loop_label;
END IF;
SET ch = SUBSTRING(original,i,1);
IF NOT ch REGEXP pattern THEN
SET temp = CONCAT(temp,ch);
ELSE
SET temp = CONCAT(temp,replacement);
END IF;
SET i=i+1;
END LOOP;
ELSE
SET temp = original;
END IF;
SET temp = TRIM(BOTH replacement FROM temp);
SET temp = REPLACE(REPLACE(REPLACE(temp , CONCAT(replacement,replacement),CONCAT(replacement,\'#\')),CONCAT(\'#\',replacement),\'\'),\'#\',\'\');
RETURN SUBSTRING(temp,1,1);
END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `regexp_replace`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `regexp_replace`(pattern VARCHAR(1000),replacement VARCHAR(1000),original VARCHAR(1000)) RETURNS VARCHAR(1000) CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE temp VARCHAR(1000);
DECLARE ch VARCHAR(1);
DECLARE i INT;
DECLARE j INT;
DECLARE qbTemp VARCHAR(1000);
SET i = 1;
SET j = 1;
SET temp = \'\';
SET qbTemp = \'\';
IF original REGEXP pattern THEN
loop_label: LOOP
IF i>CHAR_LENGTH(original) THEN
LEAVE loop_label;
END IF;
SET ch = SUBSTRING(original,i,1);
IF NOT ch REGEXP pattern THEN
SET temp = CONCAT(temp,ch);
ELSE
SET temp = CONCAT(temp,replacement);
END IF;
SET i=i+1;
END LOOP;
ELSE
SET temp = original;
END IF;
SET temp = TRIM(BOTH replacement FROM temp);
SET temp = REPLACE(REPLACE(REPLACE(temp , CONCAT(replacement,replacement),CONCAT(replacement,\'#\')),CONCAT(\'#\',replacement),\'\'),\'#\',\'\');
RETURN temp;
END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `SPLIT_STR`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `SPLIT_STR`(
X VARCHAR(255),
delim VARCHAR(12),
pos INT
) RETURNS VARCHAR(255) CHARSET latin1
RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(X, delim, pos),
LENGTH(SUBSTRING_INDEX(X, delim, pos -1)) + 1),
delim, \'\')$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `terbilang`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `terbilang`(angka BIGINT) RETURNS VARCHAR(5000) CHARSET utf8
BEGIN
 DECLARE sString VARCHAR(30);
 DECLARE Bil1 VARCHAR(255);
 DECLARE Bil2 VARCHAR(255);
 DECLARE STot VARCHAR(255);
 DECLARE X INT;
 DECLARE Y INT;
 DECLARE Z INT;
 DECLARE Urai VARCHAR(5000);
 SET sString = CAST(angka AS CHAR);
 SET Urai = \'\';
 SET X = 0;
 SET Y = 0;
 WHILE X <> LENGTH(sString) DO
SET X = X + 1;
SET sTot = MID(sString, X, 1);
SET Y = Y + CAST(sTot AS UNSIGNED);
SET Z = LENGTH(sString) - X + 1;
CASE CAST(sTot AS UNSIGNED)
WHEN 1 THEN
 BEGIN
  IF (Z = 1 OR Z = 7 OR Z = 10 OR Z = 13) THEN
   SET Bil1 = \'SATU \';
  ELSEIF (z = 4) THEN
   IF (X = 1) THEN
    SET Bil1 = \'SE\';
   ELSE
    SET Bil1 = \'SATU\';
   END IF;
  ELSEIF (Z = 2 OR Z = 5 OR Z = 8 OR Z = 11 OR Z = 14) THEN
   SET X = X + 1;
   SET sTot = MID(sString, X, 1);
   SET Z = LENGTH(sString) - X + 1;
   SET Bil2 = \'\';
   CASE CAST(sTot AS UNSIGNED)
    WHEN 0 THEN SET Bil1 = \'SEPULUH \';
    WHEN 1 THEN SET Bil1 = \'SEBELAS \';
    WHEN 2 THEN SET Bil1 = \'DUA BELAS \';
    WHEN 3 THEN SET Bil1 = \'TIGA BELAS \';
    WHEN 4 THEN SET Bil1 = \'EMPAT BELAS \';
    WHEN 5 THEN SET Bil1 = \'LIMA BELAS \';
    WHEN 6 THEN SET Bil1 = \'ENAM BELAS \';
    WHEN 7 THEN SET Bil1 = \'TUJUH BELAS \';
    WHEN 8 THEN SET Bil1 = \'DELAPAN BELAS \';
    WHEN 9 THEN SET Bil1 = \'SEMBILAN BELAS \';
   ELSE BEGIN END;
   END CASE;
  ELSE
   SET Bil1 = \'SE\';
  END IF;
 END;
WHEN 2 THEN SET Bil1 = \'DUA \';
WHEN 3 THEN SET Bil1 = \'TIGA \';
WHEN 4 THEN SET Bil1 = \'EMPAT \';
WHEN 5 THEN SET Bil1 = \'LIMA \';
WHEN 6 THEN SET Bil1 = \'ENAM \';
WHEN 7 THEN SET Bil1 = \'TUJUH \';
WHEN 8 THEN SET Bil1 = \'DELAPAN \';
WHEN 9 THEN SET Bil1 = \'SEMBILAN \';
ELSE SET Bil1 = \'\';
END CASE;
IF CAST(sTot AS UNSIGNED) > 0 THEN
IF (Z = 2 OR Z = 5 OR Z = 8 OR Z = 11 OR Z = 14) THEN
 SET Bil2 = \'PULUH \';
ELSEIF (Z = 3 OR Z = 6 OR Z = 9 OR Z = 12 OR Z = 15) THEN
 SET Bil2 = \'RATUS \';
ELSE
 SET Bil2 = \'\';
END IF;
ELSE
SET Bil2 = \'\';
END IF;
IF Y > 0 THEN
CASE Z
 WHEN 4 THEN BEGIN SET Bil2 = CONCAT(Bil2, \'RIBU \'); SET Y = 0; END;
 WHEN 7 THEN BEGIN SET Bil2 = CONCAT(Bil2, \'JUTA \'); SET Y = 0; END;
 WHEN 10 THEN BEGIN SET Bil2 = CONCAT(Bil2, \'MILYAR \'); SET Y = 0; END;
 WHEN 13 THEN BEGIN SET Bil2 = CONCAT(Bil2, \'TRILYUN \'); SET Y = 0; END;
 ELSE BEGIN END;
END CASE;
END IF;
SET Urai = CONCAT(Urai, Bil1, Bil2);
END WHILE;
RETURN Urai;
END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `TO_MARC`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `TO_MARC`( CATID INT) RETURNS TEXT CHARSET latin1
    DETERMINISTIC
BEGIN
DECLARE v_ID INT(10);
DECLARE v_CatalogId DOUBLE;
DECLARE v_Tag VARCHAR(3);
DECLARE v_Indicator1 CHAR(1);
DECLARE v_Indicator2 CHAR(1);
DECLARE v_Value VARCHAR(4000);
DECLARE done INT DEFAULT FALSE;
DECLARE curCR CURSOR FOR SELECT ID,CatalogId,Tag,Indicator1,Indicator2,VALUE FROM catalog_ruas WHERE CatalogId=CATID;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
SET @FD = \'^\';
SET @WORKSHEET_ID = (SELECT Worksheet_id FROM catalogs WHERE ID=CATID);
SET @LEADER = \'[LEN]c\';
SET @TypeOfRecord = \'a\';
IF @WORKSHEET_ID = 1  THEN SET @TypeOfRecord = \'a\'; END IF;
IF @WORKSHEET_ID = 3  THEN SET @TypeOfRecord = \'g\'; END IF;
IF @WORKSHEET_ID = 5  THEN SET @TypeOfRecord = \'e\'; END IF;
IF @WORKSHEET_ID = 7  THEN SET @TypeOfRecord = \'g\'; END IF;
IF @WORKSHEET_ID = 8  THEN SET @TypeOfRecord = \'j\'; END IF;
IF @WORKSHEET_ID = 9  THEN SET @TypeOfRecord = \'p\'; END IF;
IF @WORKSHEET_ID = 10  THEN SET @TypeOfRecord = \'j\'; END IF;
IF @WORKSHEET_ID = 11  THEN SET @TypeOfRecord = \'g\'; END IF;
IF @WORKSHEET_ID = 12  THEN SET @TypeOfRecord = \'f\'; END IF;
IF @WORKSHEET_ID = 13  THEN SET @TypeOfRecord = \'a\'; END IF;
IF @WORKSHEET_ID = 41  THEN SET @TypeOfRecord = \'a\'; END IF;
IF @WORKSHEET_ID = 21  THEN SET @TypeOfRecord = \'k\'; END IF;
IF @WORKSHEET_ID = 20  THEN SET @TypeOfRecord = \'m\'; END IF;
IF @WORKSHEET_ID = 102  THEN SET @TypeOfRecord = \'a\'; END IF;
SET @LEADER = CONCAT(@LEADER, @TypeOfRecord);
SET @BibLevel = \'m\';
IF @WORKSHEET_ID = 13  THEN SET @TypeOfRecord = \'s\'; END IF;
IF @WORKSHEET_ID = 102  THEN SET @TypeOfRecord = \'a\'; END IF;
SET @LEADER = CONCAT(@LEADER, @BibLevel, \'  22[BAD]   4500\');
SET @RESULT = \'\';
SET @DIRVAL = \'\';
SET @DIRNUM = 0;
SET @RECVAL = \'\';
OPEN curCR;
REPEAT
FETCH curCR INTO v_ID, v_CatalogId, v_Tag, v_Indicator1, v_Indicator2, v_Value;
IF NOT done AND LENGTH(v_Value) > 0 THEN
IF v_Tag = \'035\' THEN
IF LENGTH(v_Indicator1) = 0 THEN
SET v_Indicator1 = \'#\';
END IF;
IF LENGTH(v_Indicator2) = 0 THEN
SET v_Indicator2 = \'#\';
END IF;
IF SUBSTR(v_Value, 1, 2) <> \'$a\' THEN
SET v_Value = CONCAT(\'$a \', v_Value);
END IF;
END IF;
SET @LEN = 0;
SET @VAL3 = LPAD(@DIRNUM, 5, \'0\');
SET @DATAVAL = \'\';
SET @v_Indicator1 = v_Indicator1;
SET @v_Indicator2 = v_Indicator2;
SET @v_Value = v_Value;
IF v_Tag <= \'010\' THEN
SET @LEN = LENGTH(@v_Value) + 1;
SET @DIRNUM = @DIRNUM + @LEN;
SET @DATAVAL = @v_Value;
ELSE
SET @LEN = LENGTH(@v_Indicator1) + LENGTH(@v_Indicator2) + LENGTH(@v_Value) + 1;
SET @DIRNUM = @DIRNUM + @LEN;
SET @DATAVAL = CONCAT(@v_Indicator1, @v_Indicator2, @v_Value);
END IF;
SET @VAL1 = v_Tag;
SET @VAL2 = LPAD(@LEN, 4, \'0\');
SET @DIRVAL = CONCAT(@DIRVAL, @VAL1, @VAL2, @VAL3);
SET @RECVAL = CONCAT(@RECVAL, @FD, @DATAVAL);
END IF;
UNTIL done END REPEAT;
CLOSE curCR;
SET @RESULT = CONCAT(@LEADER, @DIRVAL);
SET @IndexBAD = LENGTH(@RESULT);
SET @RESULT = REPLACE(@RESULT, \'[BAD]\', LPAD(@IndexBAD, 5, \'0\'));
SET @RESULT = CONCAT(@RESULT, @RECVAL);
SET @RESULT = REPLACE(@RESULT, \'[LEN]\', LPAD(LENGTH(@RESULT), 5, \'0\'));
RETURN @RESULT;
END$$

DELIMITER ;


DELIMITER $$

DROP FUNCTION IF EXISTS `umur`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `umur`( tglLahir_prm DATETIME ) RETURNS INT(11)
BEGIN
DECLARE umuranggota INTEGER;
SET umuranggota =YEAR(NOW())-YEAR(tglLahir_prm);
IF DATE_FORMAT(tglLahir_prm,\'%m%d\')>DATE_FORMAT(NOW(),\'%m%d\') THEN SET umuranggota =umuranggota -1; END IF;
RETURN umuranggota;
END$$

DELIMITER ;

/* Views */

DELIMITER $$

DROP VIEW IF EXISTS `item_koleksi`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `item_koleksi` AS 
SELECT
  CONCAT(YEAR(`collections`.`CreateDate`),LPAD(MONTH(`collections`.`CreateDate`),2,0)) AS `periode`,
  MONTH(`collections`.`CreateDate`) AS `bulan`,
  YEAR(`collections`.`CreateDate`) AS `tahun`,
  COUNT(\'*\') AS `jumlah_eksemplar`
FROM `collections`
GROUP BY MONTH(`collections`.`CreateDate`),YEAR(`collections`.`CreateDate`)$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_abjadopac`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_abjadopac` AS 
SELECT \'A\' AS `A` UNION ALL SELECT \'B\' AS `B` UNION ALL SELECT \'C\' AS `C` UNION ALL SELECT \'D\' AS `D` UNION ALL SELECT \'E\' AS `E` UNION ALL SELECT \'F\' AS `F` UNION ALL SELECT \'G\' AS `G` UNION ALL SELECT \'H\' AS `H` UNION ALL SELECT \'I\' AS `I` UNION ALL SELECT \'J\' AS `J` UNION ALL SELECT \'K\' AS `K` UNION ALL SELECT \'L\' AS `L` UNION ALL SELECT \'M\' AS `M` UNION ALL SELECT \'N\' AS `N` UNION ALL SELECT \'O\' AS `O` UNION ALL SELECT \'P\' AS `P` UNION ALL SELECT \'Q\' AS `Q` UNION ALL SELECT \'R\' AS `R` UNION ALL SELECT \'S\' AS `S` UNION ALL SELECT \'T\' AS `T` UNION ALL SELECT \'U\' AS `U` UNION ALL SELECT \'V\' AS `V` UNION ALL SELECT \'W\' AS `W` UNION ALL SELECT \'X\' AS `X` UNION ALL SELECT \'Y\' AS `Y` UNION ALL SELECT \'Z\' AS `Z`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_lap_kriteria_anggota`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_lap_kriteria_anggota` AS 
SELECT DISTINCT \'Pekerjaan\' AS `kriteria`,`master_pekerjaan`.`Pekerjaan` AS `id_dtl_anggota`,`master_pekerjaan`.`Pekerjaan` AS `dtl_anggota` FROM `master_pekerjaan` UNION ALL SELECT DISTINCT \'Pendidikan\' AS `kriteria`,`master_pendidikan`.`Nama` AS `id_dtl_anggota`,`master_pendidikan`.`Nama` AS `dtl_anggota` FROM `master_pendidikan` UNION ALL SELECT DISTINCT \'Status_Anggota\' AS `kriteria`,`status_anggota`.`Nama` AS `id_dtl_anggota`,`status_anggota`.`Nama` AS `dtl_anggota` FROM `status_anggota` UNION ALL SELECT DISTINCT \'Jenis_Anggota\' AS `kriteria`,`jenis_anggota`.`jenisanggota` AS `id_dtl_anggota`,`jenis_anggota`.`jenisanggota` AS `dtl_anggota` FROM `jenis_anggota` UNION ALL SELECT DISTINCT \'Kelas\' AS `kriteria`,`kelas_siswa`.`namakelassiswa` AS `id_dtl_anggota`,`kelas_siswa`.`namakelassiswa` AS `dtl_anggota` FROM `kelas_siswa` UNION ALL SELECT DISTINCT \'Fakultas\' AS `kriteria`,`master_fakultas`.`Nama` AS `id_dtl_anggota`,`master_fakultas`.`Nama` AS `dtl_anggota` FROM `master_fakultas` UNION ALL SELECT DISTINCT \'Jurusan\' AS `kriteria`,`master_jurusan`.`Nama` AS `id_dtl_anggota`,`master_jurusan`.`Nama` AS `dtl_anggota` FROM `master_jurusan`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_lap_kriteria_koleksi`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_lap_kriteria_koleksi` AS 
SELECT DISTINCT \'PublishLocation\' AS `kriteria`,`catalogs`.`PublishLocation` AS `id_dtl_kriteria`,`catalogs`.`PublishLocation` AS `dtl_kriteria` from `catalogs` union all select distinct \'Publisher\' AS `kriteria`,`catalogs`.`Publisher` AS `id_dtl_kriteria`,`catalogs`.`Publisher` AS `dtl_kriteria` from `catalogs` union all select distinct \'PublishYear\' AS `kriteria`,`catalogs`.`PublishYear` AS `id_dtl_kriteria`,`catalogs`.`PublishYear` AS `dtl_kriteria` from `catalogs` union all select distinct \'SUBJECT\' AS `kriteria`,`catalogs`.`Subject` AS `id_dtl_kriteria`,`catalogs`.`Subject` AS `dtl_kriteria` from `catalogs` union all select \'location_library\' AS `kriteria`,`location_library`.`ID` AS `id_dtl_kriteria`,`location_library`.`Name` AS `dtl_kriteria` from `location_library` union all select \'locations\' AS `kriteria`,`locations`.`ID` AS `id_dtl_kriteria`,`locations`.`Name` AS `dtl_kriteria` from `locations` union all select \'collectionsources\' AS `kriteria`,`collectionsources`.`ID` AS `id_dtl_kriteria`,`collectionsources`.`Name` AS `dtl_kriteria` from `collectionsources` union all select \'partners\' AS `kriteria`,`partners`.`ID` AS `id_dtl_kriteria`,`partners`.`Name` AS `dtl_kriteria` from `partners` union all select \'currency\' AS `kriteria`,`currency`.`Currency` AS `id_dtl_kriteria`,concat(`currency`.`Currency`,\' - \',`currency`.`Description`) AS `dtl_kriteria` from `currency` union all select \'collectioncategorys\' AS `kriteria`,`collectioncategorys`.`ID` AS `id_dtl_kriteria`,`collectioncategorys`.`Name` AS `dtl_kriteria` from `collectioncategorys` union all select \'collectionrules\' AS `kriteria`,`collectionrules`.`ID` AS `id_dtl_kriteria`,`collectionrules`.`Name` AS `dtl_kriteria` from `collectionrules` union all select \'worksheets\' AS `kriteria`,`worksheets`.`ID` AS `id_dtl_kriteria`,`worksheets`.`Name` AS `dtl_kriteria` from `worksheets` union all select \'collectionmedias\' AS `kriteria`,`collectionmedias`.`ID` AS `id_dtl_kriteria`,`collectionmedias`.`Name` AS `dtl_kriteria` from `collectionmedias` union all select \'no_klas\' AS `kriteria`,`master_kelas_besar`.`kdKelas` AS `id_dtl_kriteria`,`master_kelas_besar`.`namakelas` AS `dtl_kriteria` from `master_kelas_besar` union all select \'createby\' AS `kriteria`,`users`.`ID` AS `id_dtl_kriteria`,`users`.`username` AS `dtl_kriteria` from `users` union all select \'Members\' AS `kriteria`,`members`.`ID` AS `id_dtl_kriteria`,concat(`members`.`MemberNo`,\' - \',`members`.`Fullname`) AS `dtl_kriteria` from `members`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_pertumb_jml_kunjungan`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pertumb_jml_kunjungan` AS 
SELECT
  \'ANGGOTA\' AS `kriteria`,
  YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `tahun`,
  COUNT(0)  AS `jumlah`
FROM `memberguesses`
WHERE (`memberguesses`.`NoAnggota` IS NOT NULL)
GROUP BY YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\'))UNION ALL SELECT
       \'NONANGGOTA\' AS `kriteria`,
       YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `tahun`,
       COUNT(0)     AS `jumlah`
         FROM `memberguesses`
         WHERE ISNULL(`memberguesses`.`NoAnggota`)
         GROUP BY YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\'))UNION ALL SELECT
            \'ROMBONGAN\'   AS `kriteria`,
            YEAR(STR_TO_DATE(`groupguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `tahun`,
            COUNT(0)      AS `jumlah`
            FROM `groupguesses`
            GROUP BY YEAR(STR_TO_DATE(`groupguesses`.`CreateDate`,\'%Y-%m-%d\'))$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_pertumb_jml_kunjungan_bulanan`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pertumb_jml_kunjungan_bulanan` AS 
SELECT
  \'ANGGOTA\' AS `kriteria`,
  YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `tahun`,
  MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `bulan`,
  COUNT(0)  AS `jumlah`
FROM `memberguesses`
WHERE (`memberguesses`.`NoAnggota` IS NOT NULL)
GROUP BY YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\')),MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\'))UNION ALL SELECT
           \'NONANGGOTA\' AS `kriteria`,
            YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `tahun`,
            MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `bulan`,
            COUNT(0)     AS `jumlah`
             FROM `memberguesses`
             WHERE ISNULL(`memberguesses`.`NoAnggota`)
             GROUP BY YEAR(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\')),MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\'))UNION ALL SELECT
                            \'ROMBONGAN\'   AS `kriteria`,
                            YEAR(STR_TO_DATE(`groupguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `tahun`,
                            MONTH(STR_TO_DATE(`groupguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `bulan`,
                            SUM(`groupguesses`.`CountPersonel`) AS `jumlah`
                            FROM `groupguesses`
                            GROUP BY YEAR(STR_TO_DATE(`groupguesses`.`CreateDate`,\'%Y-%m-%d\')),MONTH(STR_TO_DATE(`groupguesses`.`CreateDate`,\'%Y-%m-%d\'))
ORDER BY `tahun`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_anggota`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_anggota` AS 
SELECT
  YEAR(STR_TO_DATE(`members`.`CreateDate`,\'%Y-%m-%d\')) AS `tahun`,
  MONTH(STR_TO_DATE(`members`.`CreateDate`,\'%Y-%m-%d\')) AS `bulan`,
  COUNT(0)                       AS `jumlah`,
  `jenis_anggota`.`jenisanggota` AS `jenisanggota`,
  `jenis_anggota`.`id`           AS `IDjenisanggota`
FROM (`members`
   LEFT JOIN `jenis_anggota`
     ON ((`jenis_anggota`.`id` = `members`.`JenisAnggota_id`)))
GROUP BY YEAR(STR_TO_DATE(`members`.`CreateDate`,\'%Y-%m-%d\')),MONTH(STR_TO_DATE(`members`.`CreateDate`,\'%Y-%m-%d\')),`jenis_anggota`.`id`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_colectionloan`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_colectionloan` AS 
SELECT
  2015 AS `tahun`,
  2    AS `bulan`,
  10   AS `jumlah_judul`,
  100  AS `jumlah_eksemplar`,
  20   AS `jumlah_dijital`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_jenis_pendidikan`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_jenis_pendidikan` AS 
SELECT
  `mp`.`Nama` AS `Keterangan`,
  COUNT(`mem`.`ID`) AS `Jumlah`,
  DATE_FORMAT(`mem`.`CreateDate`,\'%Y\') AS `Tahun`,
  MONTH(STR_TO_DATE(`mem`.`CreateDate`,\'%Y-%m-%d\')) AS `bulan`
FROM (`master_pendidikan` `mp`
   LEFT JOIN `members` `mem`
     ON ((`mem`.`EducationLevel_id` = `mp`.`id`)))
WHERE (`mem`.`EducationLevel_id` IS NOT NULL)
GROUP BY `mp`.`Nama`,DATE_FORMAT(`mem`.`CreateDate`,\'%Y\'),MONTH(STR_TO_DATE(`mem`.`CreateDate`,\'%Y-%m-%d\'))
ORDER BY `mp`.`id`,`mem`.`CreateDate`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_jumlah_koleksi`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_jumlah_koleksi` AS 
SELECT
  `item_koleksi`.`bulan`            AS `bulan`,
  `item_koleksi`.`tahun`            AS `tahun`,
  (SELECT
     COUNT(0)
   FROM `catalogs`
   WHERE (CONCAT(YEAR(`catalogs`.`CreateDate`),LPAD(MONTH(`catalogs`.`CreateDate`),2,0)) = `item_koleksi`.`periode`)) AS `jumlah_judul`,
  `item_koleksi`.`jumlah_eksemplar` AS `jumlah_eksemplar`,
  (SELECT
     COUNT(0)
   FROM `catalogfiles`
   WHERE (CONCAT(YEAR(`catalogfiles`.`CreateDate`),LPAD(MONTH(`catalogfiles`.`CreateDate`),2,0)) = `item_koleksi`.`periode`)) AS `jumlah_dijital`
FROM `item_koleksi`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_kelas_subjek`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_kelas_subjek` AS 
SELECT
  `master_kelas_besar`.`namakelas` AS `Keterangan`,
  `master_kelas_besar`.`ID`        AS `Jumlah`,
  2015                             AS `Tahun`
FROM `master_kelas_besar`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_koleksi_dipinjam_eksemplar`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_koleksi_dipinjam_eksemplar` AS 
SELECT
  YEAR(STR_TO_DATE(`colit`.`CreateDate`,\'%Y-%m-%d\')) AS `tahun`,
  MONTH(STR_TO_DATE(`colit`.`CreateDate`,\'%Y-%m-%d\')) AS `bulan`,
  COUNT(0) AS `jumlah_eksemplar`
FROM `collectionloanitems` `colit`
WHERE (`colit`.`CreateDate` BETWEEN(NOW() + INTERVAL - (12)MONTH)
       AND (NOW() + INTERVAL - (1)MONTH))
GROUP BY YEAR(STR_TO_DATE(`colit`.`CreateDate`,\'%Y-%m-%d\')),MONTH(STR_TO_DATE(`colit`.`CreateDate`,\'%Y-%m-%d\'))$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_koleksi_dipinjam_judul`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_koleksi_dipinjam_judul` AS 
SELECT
  YEAR(STR_TO_DATE(`colit`.`CreateDate`,\'%Y-%m-%d\')) AS `tahun`,
  MONTH(STR_TO_DATE(`colit`.`CreateDate`,\'%Y-%m-%d\')) AS `bulan`,
  COUNT(DISTINCT `colit`.`Collection_id`) AS `jumlah_judul`
FROM `collectionloanitems` `colit`
WHERE (`colit`.`CreateDate` BETWEEN(NOW() + INTERVAL - (12)MONTH)
       AND (NOW() + INTERVAL - (1)MONTH))
GROUP BY YEAR(STR_TO_DATE(`colit`.`CreateDate`,\'%Y-%m-%d\')),MONTH(STR_TO_DATE(`colit`.`CreateDate`,\'%Y-%m-%d\'))$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_pendidikan`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_pendidikan` AS 
SELECT
  `master_pendidikan`.`Nama` AS `Nama`,
  `master_pendidikan`.`id`   AS `Jumlah`,
  2015                       AS `Tahun`
FROM `master_pendidikan`$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_rangeumur_kunj`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_rangeumur_kunj` AS 
SELECT
  `KATEGORY_UMUR`(
`members`.`DateOfBirth`)  AS `Keterangan`,
  DATE_FORMAT(`memberguesses`.`CreateDate`,\'%Y\') AS `Tahun`,
  COUNT(`memberguesses`.`ID`) AS `Jumlah`
FROM (`memberguesses`
   JOIN `members`
     ON ((`memberguesses`.`NoAnggota` = `members`.`MemberNo`)))
WHERE (CAST(`memberguesses`.`CreateDate` AS DATE) BETWEEN \'2010-01-01\'
       AND \'2016-12-31\')
GROUP BY `KATEGORY_UMUR`(`members`.`DateOfBirth`),DATE_FORMAT(`memberguesses`.`CreateDate`,\'%Y\')
ORDER BY DATE_FORMAT(`memberguesses`.`CreateDate`,\'%Y\')DESC,COUNT(`memberguesses`.`ID`)DESC$$

DELIMITER ;


DELIMITER $$

DROP VIEW IF EXISTS `v_stat_rangeumur_kunj_bulanan`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stat_rangeumur_kunj_bulanan` AS 
SELECT
  `KATEGORY_UMUR`(
`members`.`DateOfBirth`)  AS `Keterangan`,
  DATE_FORMAT(`memberguesses`.`CreateDate`,\'%Y\') AS `Tahun`,
  MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\')) AS `bulan`,
  COUNT(`memberguesses`.`ID`) AS `Jumlah`
FROM (`memberguesses`
   JOIN `members`
     ON ((`memberguesses`.`NoAnggota` = `members`.`MemberNo`)))
GROUP BY `KATEGORY_UMUR`(`members`.`DateOfBirth`),DATE_FORMAT(`memberguesses`.`CreateDate`,\'%Y\'),MONTH(STR_TO_DATE(`memberguesses`.`CreateDate`,\'%Y-%m-%d\'))
ORDER BY DATE_FORMAT(`memberguesses`.`CreateDate`,\'%Y\')DESC,COUNT(`memberguesses`.`ID`)DESC$$

DELIMITER ;

';

            $modelStore = " /* Stored Proc */ 

DELIMITER $$

DROP PROCEDURE IF EXISTS `20newCollectionOpac`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `20newCollectionOpac`()
BEGIN
SELECT c.id AS id, c.title AS title, c.author AS author, c.PublishLocation AS PublishLocation, 
                c.publishyear AS YEAR, CONCAT(c.PublishLocation, '  ' , c.Publisher, '  ' ,c.publishyear) AS penerbitan, 
                c.publisher AS publisher,  c.publishyear AS YEAR, CONCAT(c.publisher, ' : ', c.publishyear) AS penerbitan,
                s.CreateDate, w.name AS wn, TRIM(c.coverurl) AS coverurl, c.callnumber AS callnumber
                FROM collections s 
               INNER JOIN catalogs c ON c.id = s.catalog_id 
                              LEFT JOIN worksheets w ON w.ID = c.Worksheet_id 
               WHERE c.isopac =1 
               GROUP BY c.id 
               ORDER BY s.createdate DESC LIMIT 0,20;
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `BrowseOpac`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BrowseOpac`(
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
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `countPencarianLanjutOpac1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `countPencarianLanjutOpac1`(
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
          IF(fSubject='',1=1,SUBJECT = fSubject) AND
          IF(fSubject='',1=1,SUBJECT LIKE fSubject);
          
             
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `countPencarianSederhanaOpac1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `countPencarianSederhanaOpac1`(
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
        IF(fAuthor='',1=1,author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
      IF(fPublisher='',1=1,publisher LIKE fPublisher) AND
      IF(fPublishLoc='',1=1,PublishLocation LIKE fPublishLoc) AND
      IF(fPublishYear='',1=1,PublishYear LIKE fPublishYear) AND
      IF(fBahasa='',1=1,bahasa LIKE fBahasa) AND
      IF(fSubject='',1=1,SUBJECT LIKE fSubject) ;
      
         
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `countTelusurOpac1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `countTelusurOpac1`(
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
          
             
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `detailCatalogOpac`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `detailCatalogOpac`(IN `catID` INT
)
BEGIN
SELECT  C.Worksheet_id,
(SELECT C.CoverURL FROM catalogs C WHERE C.ID = catID) AS CoverURL , 
(SELECT W.name FROM worksheets W WHERE W.ID = C.worksheet_id) AS JENIS_BAHAN,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (245) GROUP BY CR.CatalogId) AS JUDUL,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (240) GROUP BY CR.CatalogId) AS JUDUL_SERAGAM,
(
     SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
      FROM
        (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR ' ') AS RTRIM1
        FROM
          (SELECT *
          FROM (
            (SELECT CS.sequence,
              CS.Ruasid,
              CASE
                WHEN CS.SUBRUAS = 'e'
                THEN CONCAT ( '(' , CS.Value , ') |' )
                ELSE CS.Value
              END AS VAL1
            FROM catalog_subruas CS
            LEFT JOIN catalog_ruas CR
            ON CS.RuasID       = CR.ID
            WHERE CR.CatalogId = catID
            AND CR.Tag        IN (100,110,111,700,710,711,800,810,811)
            AND CS.Subruas    IN ('a', 'd' ,'e')
            GROUP BY CS.Ruasid,
              CS.SUBRUAS,
              CS.Value,
              CS.Sequence
            )) X
          ORDER BY ruasid,
            sequence
          ) Y
        GROUP BY Ruasid
        ) Z
) AS PENGARANG,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (250) GROUP BY CR.CatalogId) AS EDISI,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (490) GROUP BY CR.CatalogId) AS PERNYATAAN_SERI,
(
  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
  FROM
    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
    FROM
      (SELECT *
      FROM (
        (SELECT CS.sequence,
          CS.Ruasid, CS.Value AS VAL1
        FROM catalog_subruas CS
        LEFT JOIN catalog_ruas CR
        ON CS.RuasID       = CR.ID
        WHERE CR.CatalogId = catID
        AND CR.Tag        IN (260,264)
        GROUP BY CS.Ruasid,
          CS.SUBRUAS,
          CS.Value,
          CS.Sequence
        )) X
      ORDER BY ruasid,
        sequence
      ) Y
    GROUP BY Ruasid
    ) Z
) AS PENERBITAN,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (300) GROUP BY CR.CatalogId) AS DESKRIPSI_FISIK,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (336) AND CS.SubRuas <> 2 GROUP BY CR.CatalogId) AS KONTEN,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (337) AND CS.SubRuas <> 2 GROUP BY CR.CatalogId) AS MEDIA,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (338) AND CS.SubRuas <> 2 GROUP BY CR.CatalogId) AS PENYIMPANAN_MEDIA,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (538) GROUP BY CR.CatalogId) AS INFORMASI_TEKNIS,
(
  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
  FROM
    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
    FROM
      (SELECT *
      FROM (
        (SELECT CS.sequence,
          CS.Ruasid, CS.Value AS VAL1
        FROM catalog_subruas CS
        LEFT JOIN catalog_ruas CR
        ON CS.RuasID       = CR.ID
        WHERE CR.CatalogId = catID
        AND CR.Tag        IN (020)
        GROUP BY CS.Ruasid,
          CS.SUBRUAS,
          CS.Value,
          CS.Sequence
        )) X
      ORDER BY ruasid,
        sequence
      ) Y
    GROUP BY Ruasid
    ) Z
) AS ISBN,
(
  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
  FROM
    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
    FROM
      (SELECT *
      FROM (
        (SELECT CS.sequence,
          CS.Ruasid, CS.Value AS VAL1
        FROM catalog_subruas CS
        LEFT JOIN catalog_ruas CR
        ON CS.RuasID       = CR.ID
        WHERE CR.CatalogId = catID
        AND CR.Tag        IN (022)
        GROUP BY CS.Ruasid,
          CS.SUBRUAS,
          CS.Value,
          CS.Sequence
        )) X
      ORDER BY ruasid,
        sequence
      ) Y
    GROUP BY Ruasid
    ) Z
) AS ISSN,
(
  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
  FROM
    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
    FROM
      (SELECT *
      FROM (
        (SELECT CS.sequence,
          CS.Ruasid, CS.Value AS VAL1
        FROM catalog_subruas CS
        LEFT JOIN catalog_ruas CR
        ON CS.RuasID       = CR.ID
        WHERE CR.CatalogId = catID
        AND CR.Tag        IN (024)
        GROUP BY CS.Ruasid,
          CS.SUBRUAS,
          CS.Value,
          CS.Sequence
        )) X
      ORDER BY ruasid,
        sequence
      ) Y
    GROUP BY Ruasid
    ) Z
) AS ISMN,
(
SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
  FROM
    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
    FROM
      (SELECT *
      FROM (
        (SELECT CS.sequence,
          CS.Ruasid,
            CASE
            WHEN CS.SubRuas <> 'a'
            THEN
             CONCAT( (SELECT DISTINCT(fd.Delimiter)
              FROM FIELDS f
              LEFT JOIN fielddatas fd
              ON fd.Field_id = f.ID
              WHERE f.Tag    =CR.TAG
              AND fd.Code    = CS.SubRuas
              AND Format_ID  =1
              ), CS.Value)
            ELSE CS.Value
          END AS VAL1
        FROM catalog_subruas CS
        LEFT JOIN catalog_ruas CR
        ON CS.RuasID       = CR.ID
        WHERE CR.CatalogId = catID
        AND CR.Tag        IN (600,610,611,650,651)
        GROUP BY 
          CS.Ruasid,
          CS.SUBRUAS,
          CS.Value,
          CS.Sequence
        )) X
      ORDER BY ruasid,
        sequence
      ) Y
    GROUP BY Ruasid
    ) Z
) AS SUBJEK,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (520) GROUP BY CR.CatalogId) AS ABSTRAK,
(
  SELECT GROUP_CONCAT(Z.RTRIM1 SEPARATOR '|') AS RTRIM2 
  FROM
    (SELECT GROUP_CONCAT(Y.VAL1 SEPARATOR '') AS RTRIM1
    FROM
      (SELECT *
      FROM (
        (SELECT CS.sequence,
          CS.Ruasid, CS.Value AS VAL1
        FROM catalog_subruas CS
        LEFT JOIN catalog_ruas CR
        ON CS.RuasID       = CR.ID
        WHERE CR.CatalogId = catID
        AND CR.Tag        IN (500,501,502,504,505,533,550)
        GROUP BY CS.Ruasid,
          CS.SUBRUAS,
          CS.Value,
          CS.Sequence
        )) X
      ORDER BY ruasid,
        sequence
      ) Y
    GROUP BY Ruasid
    ) Z
) AS CATATAN,
(SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,36,3) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 5) AS BAHASA,
(SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,34,1) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 17) AS BENTUK_KARYA,
(SELECT RI.Name FROM catalog_ruas CR LEFT JOIN refferenceitems RI ON TRIM(RI.Code) = SUBSTRING(CR.VALUE,23,1) WHERE CR.CatalogId = C.ID AND CR.Tag = 008 AND RI.Refference_id = 2) AS TARGET_PEMBACA,
(SELECT GROUP_CONCAT(CS.Value ORDER BY CR.Tag,CS.Sequence ASC SEPARATOR ' ') FROM catalog_subruas CS LEFT JOIN catalog_ruas CR ON CS.RuasID = CR.ID WHERE CR.CatalogId = C.ID AND CR.Tag IN (856) GROUP BY CR.CatalogId) AS LOKASI_AKSES_ONLINE
FROM catalogs C WHERE C.ID = catID AND isopac = 1;
      
         
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `duplikat_katalog`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `duplikat_katalog`()
BEGIN
SELECT update_collection (separtorVar) FROM
(
SELECT  * FROM
(
SELECT GROUP_CONCAT(id SEPARATOR ',') separtorVar,COUNT(1) AS JUM  FROM `catalogs` GROUP BY `Title`,`Author`,`Edition`,`Publisher`,`PublishLocation`,`PublishYear`
) katalogdouble
WHERE  JUM>1
) update_collection;
DELETE FROM catalogs WHERE id NOT IN (SELECT catalog_id FROM collections);
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `explode_table`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `explode_table`(
bound VARCHAR(255),
IN fAuthor TEXT,
IN fPublisher TEXT,
IN fPublishLoc TEXT,
IN fPublishYear TEXT,
IN fSubject TEXT
)
BEGIN
DECLARE id INT DEFAULT 0;
DECLARE VALUE TEXT;
DECLARE occurance INT DEFAULT 0;
DECLARE i INT DEFAULT 0;
DECLARE splitted_value VARCHAR(255);
DECLARE done INT DEFAULT 0;
DECLARE cur1 CURSOR FOR SELECT  REPLACE(author, SUBSTRING(author, LOCATE('(', author), LENGTH(author) - LOCATE(')', REVERSE(author)) - LOCATE('(', author) + 2), '') AS author  FROM tempCariOpac
 WHERE 
      -- IF(fAuthor='',1=1,author LIKE concat('%',concat(fAuthor,'%'))) AND
       IF(fAuthor='',1=1,author LIKE fAuthor) AND
      IF(fPublisher='',1=1,publisher LIKE fPublisher) AND
      IF(fPublishLoc='',1=1,PublishLocation LIKE fPublishLoc) AND
      IF(fPublishYear='',1=1,PublishYear LIKE fPublishYear) AND
      IF(fSubject='',1=1,SUBJECT LIKE fSubject);
                                     
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
DROP TEMPORARY TABLE IF EXISTS table2;
CREATE TEMPORARY TABLE table2(
`value` VARCHAR(255) NOT NULL
) ENGINE=MEMORY;
OPEN cur1;
  read_loop: LOOP
    FETCH cur1 INTO  VALUE;
    IF done THEN
      LEAVE read_loop;
    END IF;
    SET occurance = (SELECT LENGTH(VALUE)
                             - LENGTH(REPLACE(VALUE, bound, ''))
                             +1);
    SET i=1;
    WHILE i <= occurance DO
      SET splitted_value =
      TRIM((SELECT REPLACE(SUBSTRING(SUBSTRING_INDEX(VALUE, bound, i),
      LENGTH(SUBSTRING_INDEX(VALUE, bound, i - 1)) + 1), ';', '')));
      INSERT INTO table2 VALUES ( splitted_value);
      SET i = i + 1;
    END WHILE;
  END LOOP;
 CLOSE cur1;
 
 END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `facedArticle`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `facedArticle`(
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
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `facedAuthorOpac1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `facedAuthorOpac1`(
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
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `facedBahasaOpac1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `facedBahasaOpac1`(
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
              
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `facedPublisherOpac1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `facedPublisherOpac1`(
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
        
          
              
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `facedPublishLocationOpac1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `facedPublishLocationOpac1`(
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
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `facedPublishYearOpac1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `facedPublishYearOpac1`(
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
              
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `facedSubjectOpac1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `facedSubjectOpac1`(
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
              
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `get_koleksi_terimakasih`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_koleksi_terimakasih`(
IN Partner_id TEXT,
IN Source_id TEXT,
IN TanggalPengadaan TEXT
)
BEGIN
SET @Eks = 0;
SELECT 
cat.Worksheet_id AS WORKSHEETID, 
w.Name AS WorksheetName,(@Eks := COUNT(*)) AS Eksemplar,
terbilang(@Eks) AS Terbilang,
 Title AS Judul,
 '' AS AnakJudul,
 Author AS Pengarang,
 '' AS PengarangTambahan,
 '' AS BadanKoperasi, 
 Publisher AS Penerbit, 
 PublishLocation AS TempatTerbit,
 PublishYear AS TahunTerbit,
 CONCAT(PublishLocation,' ',Publisher,' ',PublishYear) AS Penerbitan,
 Edition AS Edisi,
 DeweyNo AS NoKlas, 
 PhysicalDescription AS DeskripsiFisik,
 ISBN AS ISBN, Note AS Catatan,
 '' AS Skala, cs.Name AS Sumber,
 Keterangan_Sumber AS KeteranganSumber,
 Currency AS Currency,
 Price AS Harga, 
 u.UserName AS ReportedBy,
 DATE_FORMAT(col.CreateDate,'%d %b %Y') AS ReportedDate, 
 '' AS STATUS, 
 u.FullName AS NamaPenerima
 
 FROM collections col 
 INNER JOIN catalogs cat ON col.Catalog_id = cat.id 
 INNER JOIN worksheets w ON cat.Worksheet_id = w.id 
 INNER JOIN users u ON col.createby = u.id 
 INNER JOIN collectionsources cs ON col.Source_id = cs.id 
 
 WHERE Partner_id = Partner_id
 AND Source_id = Source_id
 AND DATE(col.TanggalPengadaan) = TanggalPengadaan 
 
 GROUP BY cat.ID;
         
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `get_stat_jenis_pendidikan`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_stat_jenis_pendidikan`(
IN `Bfrom` TEXT, 
IN `Bto` TEXT
)
BEGIN
    SELECT 
        `mp`.`Nama` AS `Keterangan`,
        COUNT(`mem`.`ID`) AS `Jumlah`,
        DATE_FORMAT(`mem`.`CreateDate`, '%Y') AS `Tahun`
     
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
    
    
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `get_stat_kelas_subject_koleksi`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_stat_kelas_subject_koleksi`(
IN `Bfrom` TEXT, 
IN `Bto` TEXT
)
BEGIN
SELECT 
COUNT(*) AS CountEksemplar, 
namakelas AS NAME, 
COUNT(DISTINCT catalogs.ID) AS CountJudul 
FROM collections  
INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID  
INNER JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) 
WHERE DATE(collections.TanggalPengadaan) BETWEEN Bfrom AND Bto 
GROUP BY 
kdKelas 
ORDER BY 
kdKelas;
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `get_stat_range_umur`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_stat_range_umur`(IN `Bfrom` TEXT, IN `Bto` TEXT
)
BEGIN
 SELECT 
        KATEGORY_UMUR(`members`.`DateOfBirth`) AS `Keterangan`,
        COUNT(`memberguesses`.`ID`) AS `Jumlah`
    FROM
        (`memberguesses`
        JOIN `members` ON ((`memberguesses`.`NoAnggota` = `members`.`MemberNo`)))
    WHERE
        (CAST(`memberguesses`.`CreateDate`
            AS DATE) BETWEEN Bfrom AND Bto )
    GROUP BY KATEGORY_UMUR(`members`.`DateOfBirth`)
    ORDER BY DATE_FORMAT(`memberguesses`.`CreateDate`,
            '%Y') DESC , COUNT(`memberguesses`.`ID`) DESC;
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `getStatKlasSubjekKoleksiDibaca`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getStatKlasSubjekKoleksiDibaca`(
    IN `Bfrom` TEXT, 
    IN `Bto` TEXT
    
    )
BEGIN
    
    
    SELECT 
    COUNT(*) AS CountEksemplar, 
    namakelas AS NAME, 
    COUNT(DISTINCT catalogs.ID) AS CountJudul 
    FROM bacaditempat  
    INNER JOIN collections ON collections.ID = bacaditempat.collection_id  
    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID  
    INNER JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) 
    WHERE DATE(collections.TanggalPengadaan) BETWEEN Bfrom AND Bto
    GROUP BY 
    kdKelas 
    ORDER BY 
    kdKelas ;
    
    
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `getStatKlasSubjekKoleksiDipinjam`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getStatKlasSubjekKoleksiDipinjam`(
    IN `Bfrom` TEXT, 
    IN `Bto` TEXT
    )
BEGIN
    
    SELECT 
    COUNT(*) AS CountEksemplar, 
    namakelas AS NAME, 
    COUNT(DISTINCT catalogs.ID) AS CountJudul 
    FROM collectionloanitems  
    INNER JOIN collections ON collections.ID = collectionloanitems.Collection_id  
    INNER JOIN catalogs ON collections.Catalog_id = catalogs.ID  
    INNER JOIN master_kelas_besar ON SUBSTR(catalogs.DeweyNo,1,1) = SUBSTR(master_kelas_besar.kdKelas,1,1) 
    WHERE DATE(collections.TanggalPengadaan) BETWEEN Bfrom AND Bto
    GROUP BY 
    kdKelas 
    ORDER BY 
    kdKelas ;
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempLanjutArticle`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempLanjutArticle`(
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
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempLanjutArticle0`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempLanjutArticle0`(
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
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempLanjutOpac`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempLanjutOpac`(
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
                     
                    
                     
    ',keyword,queryloc,bhs,karya,pembaca,'  AND R.CATALOGID=CAT.ID) AND CAT.isopac=1',querys,querys2);
     PREPARE statement_1 
      FROM @query_as_string ;
       EXECUTE statement_1;
      DEALLOCATE PREPARE statement_1;
            
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempLanjutOpac0`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempLanjutOpac0`(
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
    sbahasa TEXT,
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
                    FROM catalog_ruas R  
                     WHERE    
                    
                     
    ',keyword,bhs,karya,pembaca,'AND R.CATALOGID=CAT.ID) AND CAT.isopac=1',querys,querys2,' LIMIT ',limit1,',',limit2,' ');
     PREPARE statement_1 
      FROM @query_as_string ;
       EXECUTE statement_1;
      DEALLOCATE PREPARE statement_1;
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempOpacSederhana`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempOpacSederhana`(
IN keyword TEXT,
IN tag TEXT,
IN worksheet VARCHAR(255),
IN fAuthor TEXT,
IN fPublisher TEXT,
IN fPublishLoc TEXT,
IN fPublishYear TEXT,
IN fSubject TEXT,
IN fromTgl DATE,
IN toTgl DATE
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
CoverURL TEXT,
worksheet_id INT,
worksheet TEXT,
JML_BUKU INT,
ALL_BUKU INT,
KONTEN_DIGITAL VARCHAR(100)
);              
   
INSERT INTO tempCariOpac
SELECT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND BookingExpiredDate < NOW()) JML_BUKU,
                 (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                 (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                 FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
      FROM catalogs CAT JOIN collections col ON col.Catalog_id = CAT.ID
      WHERE EXISTS           
               (SELECT  1
                FROM catalog_ruas R  
                 WHERE 
                 IF(fAuthor='',1=1,CAT.Author LIKE fAuthor) AND
         IF(fPublisher='',1=1,CAT.Publisher LIKE fPublisher) AND
         IF(fPublishLoc='',1=1,CAT.PublishLocation LIKE fPublishLoc) AND
         IF(fPublishYear='',1=1,CAT.PublishYear LIKE fPublishYear) AND
         IF(fSubject='',1=1,CAT.Subject LIKE fSubject) AND
        
        CASE tag
        WHEN 'Judul' THEN R.TAG IN ('240','245','246','440','740')
        WHEN 'Pengarang' THEN R.TAG IN ('100','110','700','710')
        WHEN 'Penerbit' THEN R.TAG IN ('260')
        WHEN 'Tahun Terbit' THEN R.TAG IN ('260')
        WHEN 'Subyek' THEN R.TAG LIKE '6%'
        WHEN 'Nomor Panggil' THEN R.TAG IN ('090','082','084','080')
        WHEN 'BIB-ID' THEN R.TAG IN ('035')
        WHEN 'ISBN/ISSN/ISMN' THEN R.TAG IN ('020','022')
        WHEN 'Semua Ruas' THEN R.TAG IN ('240','245','740','100','110','700','710','260','600','610','611','630','650','651','080','082','084','096','022','020')
        END
                
                AND  R.Value LIKE keyword 
                AND IF(worksheet='4',col.TANGGAL_TERBIT_EDISI_SERIAL BETWEEN fromTgl AND toTgl,1=1)
                AND R.CATALOGID=CAT.ID) 
        AND IF(worksheet='Semua Jenis bahan',1=1,CAT.Worksheet_id = worksheet)
    
        
         AND CAT.isopac=1 
        ;  
  
          
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempSederhanaArticle`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempSederhanaArticle`(
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
            (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
          FROM serial_articles art 
          JOIN catalogs CAT ON art.Catalog_id = CAT.ID  
          LEFT JOIN worksheets w ON w.id = CAT.Worksheet_id
          WHERE  
          
            CASE tag
                WHEN 'Judul' THEN art.Title LIKE keyword
                WHEN 'Pengarang' THEN art.Creator LIKE keyword
                WHEN 'Subyek' THEN art.Subject LIKE keyword      
                WHEN 'Sembarang' THEN 1 = 1
            END
          
            AND CAT.isopac=1 
            AND art.ISOPAC=1
            AND w.ISSERIAL = 1
            
            LIMIT limit1,limit2;
            
ELSE
        INSERT INTO tempCariArticle
        SELECT art.id AS id, art.Article_type,art.Creator AS Creator_article, art.title AS title_article, art.content AS content_article, art.subject AS subject_article, art.EDISISERIAL, art.TANGGAL_TERBIT_EDISI_SERIAL, CAT.id CatalogId,CAT.title,CAT.Languages, CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
            w.name worksheet,
            (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
          FROM serial_articles art 
          JOIN catalogs CAT ON art.Catalog_id = CAT.ID  
          LEFT JOIN worksheets w ON w.id = CAT.Worksheet_id
          WHERE  
          
            CASE tag
                WHEN 'Judul' THEN art.Title LIKE keyword
                WHEN 'Pengarang' THEN art.Creator LIKE keyword
                WHEN 'Subyek' THEN art.Subject LIKE keyword      
                WHEN 'Sembarang' THEN 1 = 1
            END
          
            AND CAT.isopac=1 
            AND art.ISOPAC=1
            AND w.ISSERIAL = 1;  
        
    END IF;
          
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempSederhanaOpac`$$

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
        ;  
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
        ;    
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
        ;  
END IF;
END IF;
END IF;
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempSederhanaOpac0`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempSederhanaOpac0`(
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
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempSederhanaOpac123`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempSederhanaOpac123`(
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
SUBJECT TEXT,
CoverURL TEXT,
worksheet_id INT,
worksheet TEXT,
JML_BUKU INT,
ALL_BUKU INT,
KONTEN_DIGITAL VARCHAR(100)
);
IF isLKD = 1
THEN
 INSERT INTO tempCariOpac
 SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
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
        WHEN 'Pengarang' THEN R.TAG IN ('100','110','700','710')
        WHEN 'Penerbit' THEN R.TAG IN ('260')
        WHEN 'Tahun Terbit' THEN R.TAG IN ('260')
        WHEN 'Subyek' THEN R.TAG IN ('600','610','611','650','651')
        WHEN 'Nomor Panggil' THEN R.TAG IN ('090','082','084','080')
        WHEN 'BIB-ID' THEN R.TAG IN ('035')
        WHEN 'ISBN/ISSN/ISMN' THEN R.TAG IN ('020','022')
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
SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
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
        WHEN 'Pengarang' THEN R.TAG IN ('100','110','700','710')
        WHEN 'Penerbit' THEN R.TAG IN ('260')
        WHEN 'Tahun Terbit' THEN R.TAG IN ('260')
        WHEN 'Subyek' THEN R.TAG LIKE '6%'
        WHEN 'Nomor Panggil' THEN R.TAG IN ('090','082','084','080')
        WHEN 'BIB-ID' THEN R.TAG IN ('035')
        WHEN 'ISBN/ISSN/ISMN' THEN R.TAG IN ('020','022')
        WHEN 'Semua Ruas' THEN R.TAG IN ('240','245','740','100','110','700','710','260','600','610','611','630','650','651','080','082','084','096','022','020')
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
        
        CAT.isopac=1 
        ;    
ELSE
INSERT INTO tempCariOpac
SELECT DISTINCT CAT.id CatalogId,CAT.title,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
               (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
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
        WHEN 'Pengarang' THEN R.TAG IN ('100','110','700','710')
        WHEN 'Penerbit' THEN R.TAG IN ('260')
        WHEN 'Tahun Terbit' THEN R.TAG IN ('260')
        WHEN 'Subyek' THEN R.TAG LIKE '6%'
        WHEN 'Nomor Panggil' THEN R.TAG IN ('090','082','084','080')
        WHEN 'BIB-ID' THEN R.TAG IN ('035')
        WHEN 'ISBN/ISSN/ISMN' THEN R.TAG IN ('020','022')
        WHEN 'Semua Ruas' THEN R.TAG IN ('240','245','740','100','110','700','710','260','600','610','611','630','650','651','080','082','084','096','022','020')
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
        
        CAT.isopac=1 
        ;  
END IF;
END IF;
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempTelusurArticle`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertTempTelusurArticle`(
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
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `insertTempTelusurOpac`$$

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
            INSERT INTO tempCariOpac
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
        INSERT INTO tempCariOpac
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
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `marcCatalogOpac`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `marcCatalogOpac`(
IN catID INT
)
BEGIN
SELECT * FROM catalog_ruas WHERE catalogId = catID ORDER BY Tag ASC;
      
         
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `newCollectionOpac`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `newCollectionOpac`()
BEGIN
SELECT c.id AS id, c.title AS title, c.author AS author, c.PublishLocation AS PublishLocation, 
                c.publishyear AS YEAR, CONCAT(c.PublishLocation, '  ' , c.Publisher, '  ' ,c.publishyear) AS penerbitan, 
                c.publisher AS publisher,  c.publishyear AS YEAR, CONCAT(c.publisher, ' : ', c.publishyear) AS penerbitan,
                s.CreateDate, w.name AS wn, TRIM(c.coverurl) AS coverurl, c.callnumber AS callnumber
                FROM collections s 
               INNER JOIN catalogs c ON c.id = s.catalog_id 
                              LEFT JOIN worksheets w ON w.ID = c.Worksheet_id 
               WHERE c.isopac =1 
               GROUP BY c.id 
               ORDER BY s.createdate DESC;
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `pencarianLanjutLimitOpac`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pencarianLanjutLimitOpac`(
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
    SELECT CatalogId,title kalimat2,author,publisher,PublishLocation,PublishYear,SUBJECT,bahasa,CoverURL,
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
          IF(fSubject='',1=1,SUBJECT = fSubject) 
          
          
          LIMIT limit1,limit2;    
    END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `pencarianSederhanaOpacLimit1`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pencarianSederhanaOpacLimit1`(
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
     IF(fAuthor='',1=1,author LIKE CONCAT('%',CONCAT(fAuthor,'%'))) AND
           IF(fPublisher='',1=1,publisher LIKE fPublisher) AND
      IF(fPublishLoc='',1=1,PublishLocation LIKE fPublishLoc) AND
      IF(fPublishYear='',1=1,PublishYear LIKE fPublishYear) AND
      IF(fBahasa='',1=1,bahasa LIKE fBahasa) AND
       IF(fSubject='',1=1,SUBJECT LIKE fSubject)
      
      
      LIMIT limit1,limit2;    
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `showCollectionOpac`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `showCollectionOpac`(IN keyword TEXT)
BEGIN
              
SELECT c.id,c.`Catalog_id`, m.`Name` media,c.`NomorBarcode`, c.`NoInduk`, c.`CallNumber`, r.`Name` akses, l.`Name` lokasi, s.`Name` ketersediaan, loc.`Name` namaperpus, c.BookingMemberID, c.BookingExpiredDate
            FROM collections c 
            LEFT JOIN collectionmedias m ON c.`Media_id`=m.`ID` 
            LEFT JOIN collectionrules r ON c.`Rule_id`=r.`ID` 
            LEFT JOIN collectionstatus s ON c.`Status_id`=s.`ID`   
            LEFT JOIN locations l ON c.`Location_id`=l.`ID`
            LEFT JOIN location_library loc ON c.`Location_Library_id` = loc.`id`
            WHERE c.`Catalog_id`=keyword;   
          
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `showKontenDigital`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `showKontenDigital`(IN keyword TEXT)
BEGIN
              
SELECT C.ID,C.Catalog_id,C.FileURL,C.FileFlash,C.IsPublish, (
                                SELECT  SUBSTRING(FileURL,(LENGTH(FileURL)-LOCATE('.',REVERSE(FileURL)))+2))  AS FormatFile
                                 FROM catalogfiles C WHERE IsPublish <> 0 AND Catalog_id =keyword;   
          
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `TO_MARC`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TO_MARC`(
IN CatID INT
)
BEGIN
SELECT TO_MARC(CatID);
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `topCollectionOpac`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `topCollectionOpac`()
BEGIN
SELECT COUNT(collection_id) entry, c.title AS title, c.id AS id, c.author AS author,
                c.PublishLocation AS PublishLocation, c.publishyear AS YEAR, 
                CONCAT(c.PublishLocation, '  ',  c.Publisher, '  ' ,c.publishyear) AS penerbitan, w.name AS wn,
                c.publisher AS publisher, c.publishyear AS YEAR, CONCAT(c.publisher, ' : ', c.publishyear) AS penerbitan, 
                TRIM(c.coverurl) AS coverurl,  c.callnumber AS callnumber
                
FROM collectionloanitems h 
               INNER JOIN collections s ON s.id = h.collection_id 
               INNER JOIN catalogs c ON c.id = s.catalog_id AND c.ISOPAC =1 
                LEFT JOIN worksheets w ON w.ID = c.Worksheet_id 
               GROUP BY c.id ORDER BY entry DESC;
    END$$

DELIMITER ;

/* Stored Proc */

DELIMITER $$

CREATE DEFINER=`root`@`localhost` EVENT `kriteria_koleksi_add` ON SCHEDULE EVERY 1 WEEK STARTS '2016-02-01 16:41:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN    
SET SQL_SAFE_UPDATES =0;
DELETE FROM kriteria_koleksi WHERE jns_kriteria='koleksi_terbaru' AND jns_kriteria='koleksi_sering_dipinjam';
    
INSERT INTO kriteria_koleksi (title,catalog_id,author,PublishYear,alamat_image,jns_kriteria,worksheet_name)
SELECT   c.title AS title,c.id AS id, c.author AS author, 
                c.publishyear AS YEAR,  TRIM(c.coverurl) AS coverurl, 'koleksi_terbaru' AS jns_kriteria
                , w.Name worksheet_name         
            
                 FROM collections s 
                INNER JOIN catalogs c ON c.id = s.catalog_id 
                LEFT JOIN worksheets w ON w.ID = c.Worksheet_id                  
                 
                WHERE c.isopac =1 
                GROUP BY c.id 
                ORDER BY s.TanggalPengadaan DESC
                LIMIT 0,20;
INSERT INTO kriteria_koleksi (Jumlah,title,catalog_id,author,PublishYear,alamat_image,jns_kriteria,worksheet_name) 
SELECT  COUNT(collection_id) jumlah, c.title AS title, c.id AS id, c.author AS author,
                 c.publishyear AS YEAR, 
                TRIM(c.coverurl) AS coverurl,'koleksi_sering_dipinjam' AS jns_kriteria
                , w.Name worksheet_name
                
                 FROM collectionloanitems h 
                INNER JOIN collections s ON s.id = h.collection_id 
                INNER JOIN catalogs c ON c.id = s.catalog_id
                LEFT JOIN worksheets w ON w.ID = c.Worksheet_id 
                
                WHERE c.ISOPAC =1 
                GROUP BY c.id 
                ORDER BY jumlah DESC
                LIMIT 0,20;
                
        
    END$$
DELIMITER ;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` EVENT `latian` ON SCHEDULE EVERY 1 DAY STARTS '2015-11-16 12:00:00' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO `outbox` (`DestinationNumber`,`TextDecoded`) 
    SELECT members.NoHp, CONCAT('Peminjaman untuk buku : ',
    IF(LENGTH(catalogs.title > 30),CONCAT(SUBSTR(catalogs.title,1,30),'...'),
    SUBSTR(catalogs.title,1,30)),' jatuh tempo dalam ',
    DATEDIFF(duedate, NOW()),' hari') AS IsiSMS  
    FROM collectionloanitems
    LEFT JOIN collections ON collectionloanitems.Collection_id = collections.id
    INNER JOIN catalogs ON collections.catalog_id = catalogs.id
    INNER JOIN members ON collectionloanitems.member_id = members.id
    WHERE loanstatus = 'Loan'
    AND DATEDIFF(duedate, NOW()) BETWEEN 0 AND @JedaHari$$
DELIMITER ;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` EVENT `myevent` ON SCHEDULE EVERY 1 DAY STARTS '2015-11-16 12:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE myschema.mytable SET mycol = mycol + 1$$
DELIMITER ;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` EVENT `SmsWarningBelumJatuhTempo` ON SCHEDULE EVERY 1 DAY STARTS '2015-11-16 11:30:00' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO `outbox` (`DestinationNumber`,`TextDecoded`) 
                         SELECT members.NoHp, CONCAT('Yth. Anggota ',members.MemberNo,', pinjaman koleksi \" ',
                        IF(LENGTH(catalogs.title > 35),CONCAT(SUBSTR(catalogs.title,1,35),'...\"'),
                        SUBSTR(catalogs.title,1,35)),' akan jatuh tempo pada ',DATE(duedate),'. Harap segera mengembalikan ') AS IsiSMS  
                        , DATEDIFF(duedate, NOW()) AS terlambat
                        FROM collectionloanitems
                        LEFT JOIN collections ON collectionloanitems.Collection_id = collections.id
                        INNER JOIN catalogs ON collections.catalog_id = catalogs.id
                        INNER JOIN members ON collectionloanitems.member_id = members.id
                        WHERE loanstatus = 'Loan'
                        AND DATEDIFF(duedate, NOW()) BETWEEN 0 AND 1$$
DELIMITER ;


DELIMITER $$

CREATE DEFINER=`root`@`localhost` EVENT `SmsWarningJatuhTempo` ON SCHEDULE EVERY 1 DAY STARTS '2015-11-16 11:30:00' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO `outbox` (`DestinationNumber`,`TextDecoded`) 
                         SELECT members.NoHp, CONCAT('Yth. Anggota ',members.MemberNo,', pinjaman koleksi \" ',
                        IF(LENGTH(catalogs.title > 30),CONCAT(SUBSTR(catalogs.title,1,30),'...\"'),
                        SUBSTR(catalogs.title,1,30)),' sudah lewat jatuh tempo ( ',DATE(duedate),'). Harap segera mengembalikan ') AS IsiSMS  
                        , DATEDIFF(duedate, NOW()) AS terlambat
                        FROM collectionloanitems
                        LEFT JOIN collections ON collectionloanitems.Collection_id = collections.id
                        INNER JOIN catalogs ON collections.catalog_id = catalogs.id
                        INNER JOIN members ON collectionloanitems.member_id = members.id
                        WHERE loanstatus = 'Loan'
                        AND DATEDIFF(duedate, NOW()) BETWEEN  -3 AND -1$$
DELIMITER ;
";
        $model =  $model.$modelStore;

            return $model;

    }
    
}
