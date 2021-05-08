<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

use common\components\CatalogHelpers;
use common\components\Helpers;

class ImportAkuisisiForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

   /* public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx','checkExtensionByMimeType' => false],
        ];
    }*/
    
    public function upload()
    {
        $path = Yii::getAlias('@uploaded_files') . '/temporary/imported_data_sheet/imported/';

        if ($this->validate()) {
            $this->file->saveAs($path. $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }

    }

    public function import(){
        try{
                    $path = Yii::getAlias('@uploaded_files') . '/temporary/imported_data_sheet/imported/'.$this->file->baseName . '.' . $this->file->extension;

                    $data = \moonland\phpexcel\Excel::widget([
                            'mode' => 'import', 
                            'fileName' => $path, 
                            'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel. 
                            'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric. 
                            'getOnlySheet' => 'Sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
                        ]);
                    $trans = Yii::$app->db->beginTransaction();
                    /*echo '<pre>'; print_r($data); echo '</pre>';die;*/
                   $success=0;
                   $recno=0;
                    foreach ($data as $row) {
                        $recno++;
                        $collections = new \common\models\Collections;

                        //Worksheet
                        $worksheetId = \common\models\Worksheets::find()->where(['Name' => Helpers::collapseSpaces($row['JENIS BAHAN'])])->one();
                        if(is_null($worksheetId)){
                            // INSERT JENIS BAHAN
                            $masterWorksheet = new \common\models\Worksheets;
                            $masterWorksheet->Name = Helpers::collapseSpaces($row['JENIS BAHAN']);
                            $masterWorksheet->save();
                            $worksheetId =  $masterWorksheet->getPrimaryKey();
                        }else{
                            $worksheetId = $worksheetId->ID;
                        }

                        //Catalogs
                        $catalogId = \common\models\Catalogs::find()->where([
                            'Worksheet_id' => $worksheetId,
                            'Title' =>  Helpers::collapseSpaces($row['JUDUL']),
                            'Author' =>  Helpers::collapseSpaces($row['PENGARANG']),
                            'Edition' =>  Helpers::collapseSpaces($row['EDISI']),
                            'Publisher' =>  Helpers::collapseSpaces($row['PENERBIT']),
                            'PublishLocation' =>  Helpers::collapseSpaces($row['KOTA TERBIT']),
                            'PublishYear' =>  Helpers::collapseSpaces($row['TAHUN TERBIT']),
                            ])->one();

                        $noinduk=Helpers::collapseSpaces($row['NO INDUK']);

                        if(is_null($catalogId)){
                            // INSERT KATALOG BARU
                            $masterCatalog = new \common\models\Catalogs;
                            $controlnumber=CatalogHelpers::getControlNumber(1); 
                            $bibid=CatalogHelpers::getBibId(1); 
                            $title=Helpers::collapseSpaces($row['JUDUL']);
                            $author=Helpers::collapseSpaces($row['PENGARANG']);
                            $edition=Helpers::collapseSpaces($row['EDISI']);
                            $publishlocation=Helpers::collapseSpaces($row['KOTA TERBIT']);
                            $publisher=Helpers::collapseSpaces($row['PENERBIT']);
                            $publishyear=Helpers::collapseSpaces($row['TAHUN TERBIT']);
                            $physicaldescription=Helpers::collapseSpaces($row['DESKRIPSI FISIK']);
                            $isbn=Helpers::collapseSpaces($row['ISBN']);
                            $issn=Helpers::collapseSpaces($row['ISSN']);
                            $callnumber=Helpers::collapseSpaces($row['NOMOR PANGGIL']);
                            $note=Helpers::collapseSpaces($row['CATATAN UMUM']);
                            $languages=Helpers::collapseSpaces($row['BAHASA']);
                            $deweyno=Helpers::collapseSpaces($row['NO DEWEY']);
                            $subject=Helpers::collapseSpaces($row['SUBJEK']);
                            $masterCatalog->Worksheet_id = $worksheetId;
                            $masterCatalog->ControlNumber = $controlnumber; 
                            $masterCatalog->BIBID = $bibid;
                            $masterCatalog->Title = $title;
                            $masterCatalog->Author = $author;
                            $masterCatalog->Edition = $edition;
                            $masterCatalog->PublishLocation = $publishlocation;
                            $masterCatalog->Publisher = $publisher;
                            $masterCatalog->PublishYear = $publishyear;
                            $masterCatalog->PhysicalDescription = $physicaldescription;
                            $masterCatalog->ISBN = $isbn;
                            $masterCatalog->CallNumber = $callnumber;
                            $masterCatalog->Note = $note;
                            $masterCatalog->Languages = $languages;
                            $masterCatalog->DeweyNo = $deweyno;
                            $masterCatalog->Subject = $subject;
                            if($masterCatalog->save())
                            {
                                $catalogId =  $masterCatalog->getPrimaryKey();
                                $seq = 1;
                                //Saving controlnumber
                                CatalogHelpers::saveCatalogRuas($catalogId,NULL,NULL,'001',$controlnumber,$seq++);
                                //Saving bibid
                                if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','035','$a '.$bibid,$seq++))
                                {
                                    //save catalog sub ruas records
                                    CatalogHelpers::saveCatalogSubRuas('a',trim($bibid),1);
                                }
                                //Saving title
                                if($title!='')
                                {
                                    $titles=explode(CatalogHelpers::getTandaBaca('245','b'),$title);
                                    $a='';
                                    $b='';
                                    $c='';
                                    $t245='';
                                    if(count($titles) > 0)
                                    {
                                        $a=trim($titles[0]);
                                        $t245 .= '$a '.$a.' ';
                                        if(count($titles) > 1)
                                        {
                                            $titles2=explode(CatalogHelpers::getTandaBaca('245','c'),$titles[1]);
                                            if(count($titles2) > 0)
                                            {
                                                $b=trim($titles2[0]);
                                                $t245 .= '$b '.$b.' ';
                                                if(count($titles2) > 1)
                                                {
                                                    $c=trim($titles2[1]);
                                                    $t245 .= '$c '.$c.' ';
                                                }
                                            }
                                        }
                                    }
                                    if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','245',$t245,$seq++))
                                    {
                                        //save catalog sub ruas records
                                        if($a!='')
                                        {
                                            CatalogHelpers::saveCatalogSubRuas('a',trim($a),1);
                                        }
                                        if($b!='')
                                        {
                                            CatalogHelpers::saveCatalogSubRuas('b',trim($b),2);
                                        }
                                        if($c!='')
                                        {
                                            CatalogHelpers::saveCatalogSubRuas('c',trim($c),3);
                                        }
                                    }
                                }
                                //Saving author
                                if($author!='')
                                {
                                    $authors=explode(';',$author);
                                    if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','100','$a '.trim($authors[0]),$seq++))
                                    {
                                        //save catalog sub ruas records
                                        CatalogHelpers::saveCatalogSubRuas('a',trim($authors[0]),1);
                                    }
                                    if(count($authors)>1)
                                    {
                                        for ($i=1; $i < count($authors); $i++) { 
                                            if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','700','$a '.trim($authors[$i]),$seq++))
                                            {
                                                //save catalog sub ruas records
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($authors[$i]),1);
                                            }
                                        }
                                    }
                                }
                                

                                //Saving edition
                                if($edition!='' && CatalogHelpers::saveCatalogRuas($catalogId,'#','#','250','$a '.$edition,$seq++))
                                {
                                    //save catalog sub ruas records
                                    CatalogHelpers::saveCatalogSubRuas('a',trim($edition),1);
                                }

                                //Saving publishment
                                if($publishlocation!='' || $publisher!=''|| $publishyear!='')
                                {
                                    $a='';
                                    $b='';
                                    $c='';
                                    $t260='';
                                    if($publishlocation!='')
                                    {
                                        $a=$publishlocation;
                                        $t260 .= '$a '.$a.' ';
                                    }
                                    if($publisher!='')
                                    {
                                        $b=$publisher;
                                        $t260 .= '$b '.$b.' ';
                                    }
                                    if($publishyear!='')
                                    {
                                        $c=$publishyear;
                                        $t260 .= '$c '.$c.' ';
                                    }
                                    if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','260',$t260,$seq++))
                                    {
                                        //save catalog sub ruas records
                                        if($a!='')
                                        {
                                            CatalogHelpers::saveCatalogSubRuas('a',trim($a),1);
                                        }
                                        if($b!='')
                                        {
                                            CatalogHelpers::saveCatalogSubRuas('b',trim($b),2);
                                        }
                                        if($c!='')
                                        {
                                            CatalogHelpers::saveCatalogSubRuas('c',trim($c),3);
                                        }
                                    }
                                }

                                //Saving physical desc
                                if($physicaldescription!='')
                                {
                                    $physicaldescriptions=explode(CatalogHelpers::getTandaBaca('300','b'),$physicaldescription);
                                    $a='';
                                    $b='';
                                    $c='';
                                    $t300='';
                                    if(count($physicaldescriptions) > 0)
                                    {
                                        $a=trim($physicaldescriptions[0]);
                                        $t300 .= '$a '.$a.' ';
                                        if(count($physicaldescriptions) > 1)
                                        {
                                            $physicaldescriptions2=explode(trim(CatalogHelpers::getTandaBaca('300','c')),$physicaldescriptions[1]);
                                            if(count($physicaldescriptions2) > 0)
                                            {
                                                $b=trim($physicaldescriptions2[0]);
                                                $t300 .= '$b '.$b.' ';
                                                if(count($physicaldescriptions2) > 1)
                                                {
                                                    $c=trim($physicaldescriptions2[1]);
                                                    $t300 .= '$c '.$c.' ';
                                                }
                                            }
                                        }
                                    }
                                    if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','300',$t300,$seq++))
                                    {
                                        //save catalog sub ruas records
                                        if($a!='')
                                        {
                                            CatalogHelpers::saveCatalogSubRuas('a',trim($a),1);
                                        }
                                        if($b!='')
                                        {
                                            CatalogHelpers::saveCatalogSubRuas('b',trim($b),2);
                                        }
                                        if($c!='')
                                        {
                                            CatalogHelpers::saveCatalogSubRuas('c',trim($c),3);
                                        }
                                    }
                                }

                                //Saving isbn
                                if($isbn!='' )
                                {
                                    $isbns=explode(';',$isbn);
                                    for ($i=0; $i < count($isbns) ; $i++) { 
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','020','$a '.trim($isbns[$i]),$seq++))
                                        {
                                            //save catalog sub ruas records
                                            CatalogHelpers::saveCatalogSubRuas('a',trim($isbns[$i]),1);
                                        }
                                    }
                                    
                                }

                                //Saving issn

                                if($issn!='' )
                                {
                                    $issns=explode(';',$issn);
                                    for ($i=0; $i < count($issns) ; $i++) { 
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','022','$a '.trim($issns[$i]),$seq++))
                                        {
                                            //save catalog sub ruas records
                                            CatalogHelpers::saveCatalogSubRuas('a',trim($issns[$i]),1);
                                        }
                                    }
                                    
                                }


                                //Saving callnumber

                                if($callnumber!='' )
                                {
                                    $callnumbers=explode(';',$callnumber);
                                    for ($i=0; $i < count($callnumbers) ; $i++) { 
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','084','$a '.trim($callnumbers[$i]),$seq++))
                                        {
                                            //save catalog sub ruas records
                                            CatalogHelpers::saveCatalogSubRuas('a',trim($callnumbers[$i]),1);
                                        }
                                    }
                                    
                                }

                                //Saving note
                                if($note!='' )
                                {
                                    $notes=explode(';',$note);
                                    for ($i=0; $i < count($notes) ; $i++) { 
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','500','$a '.trim($notes[$i]),$seq++))
                                        {
                                            //save catalog sub ruas records
                                            CatalogHelpers::saveCatalogSubRuas('a',trim($notes[$i]),1);
                                        }
                                    }
                                    
                                }

                                //Saving deweyno
                                if($deweyno!='' && CatalogHelpers::saveCatalogRuas($catalogId,'#','#','082','$a '.$deweyno,$seq++))
                                {
                                    //save catalog sub ruas records
                                    CatalogHelpers::saveCatalogSubRuas('a',trim($deweyno),1);
                                }

                                //Saving subject
                                if($subject!='' )
                                {
                                    $subjects=explode(';',$subject);
                                    for ($i=0; $i < count($subjects) ; $i++) { 
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','650','$a '.trim($subjects[$i]),$seq++))
                                        {
                                            //save catalog sub ruas records
                                            CatalogHelpers::saveCatalogSubRuas('a',trim($subjects[$i]),1);
                                        }
                                    }
                                    
                                }

                                //Saving no induk
                                if($noinduk!='' )
                                {
                                    if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','990','$a '.$noinduk,$seq++))
                                    {
                                        //save catalog sub ruas records
                                        CatalogHelpers::saveCatalogSubRuas('a',$noinduk,1);
                                    }
                                }

                            }
                            else
                            {
                            //print_r($members->getErrors());
                            if($masterCatalog->hasErrors()){
                              echo \yii\helpers\Html::errorSummary($masterCatalog);
                            }
                            echo "Simpan data bibliografis Gagal.";
                            return false;
                        }
                            
                        }else{
                            $catalogId = $catalogId->ID;

                            //Saving no induk
                            
                            if($noinduk!='' )
                            {
                                $catruas = \common\models\CatalogRuas::find()
                                ->addSelect(['MAX(Sequence) + 1 AS Sequence'])
                                ->where(['CatalogId'=>$catalogId])
                                ->one();
                                if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','990','$a '.$noinduk,(int)$catruas->Sequence))
                                {
                                    //save catalog sub ruas records
                                    CatalogHelpers::saveCatalogSubRuas('a',$noinduk,1);
                                }
                            }
                        }

                        //Rekanan
                        $partnerId = \common\models\Partners::find()->where(['Name' => Helpers::collapseSpaces($row['REKANAN'])])->one();
                        if(is_null($partnerId)){
                            // INSERT REKANAN BARU
                            $masterPartner = new \common\models\Partners;
                            $masterPartner->Name = Helpers::collapseSpaces($row['REKANAN']);
                            $masterPartner->save();
                            $partnerId =  $masterPartner->getPrimaryKey();
                        }else{
                            $partnerId = $partnerId->ID;
                        }

                        //Lokasi
                        $locationId = \common\models\Locations::find()->where(['Name' => Helpers::collapseSpaces($row['LOKASI'])])->one();
                        if(is_null($locationId)){
                            // INSERT LOKASI BARU
                            $masterLocation = new \common\models\Locations;
                            $masterLocation->Name = Helpers::collapseSpaces($row['LOKASI']);
                            $masterLocation->save();
                            $locationId =  $masterLocation->getPrimaryKey();
                        }else{
                            $locationId = $locationId->ID;
                        }

                        //Akses
                        $rulesId = \common\models\Collectionrules::find()->where(['Name' => Helpers::collapseSpaces($row['AKSES'])])->one();
                        if(is_null($rulesId)){
                            // INSERT AKSES BARU
                            $masterRules = new \common\models\Collectionrules;
                            $masterRules->Name = Helpers::collapseSpaces($row['AKSES']);
                            $masterRules->save();
                            $rulesId =  $masterRules->getPrimaryKey();
                        }else{
                            $rulesId = $rulesId->ID;
                        }

                        //Kategori
                        $categoryId = \common\models\Collectioncategorys::find()->where(['Name' => Helpers::collapseSpaces($row['KATEGORI'])])->one();
                        if(is_null($categoryId)){
                            // INSERT KATEGORI BARU
                            $masterCategory = new \common\models\Collectioncategorys;
                            $masterCategory->Code = Helpers::collapseSpaces($row['KATEGORI']);
                            $masterCategory->Name = Helpers::collapseSpaces($row['KATEGORI']);
                            $masterCategory->save();
                            $categoryId =  $masterCategory->getPrimaryKey();
                        }else{
                            $categoryId = $categoryId->ID;
                        }

                        //Media
                        $mediaId = \common\models\Collectionmedias::find()->where(['Name' => Helpers::collapseSpaces($row['MEDIA'])])->one();
                        if(is_null($mediaId)){
                            // INSERT MEDIA BARU
                            $masterMedia = new \common\models\Collectionmedias;
                            $masterMedia->Code = Helpers::collapseSpaces($row['MEDIA']);
                            $masterMedia->Name = Helpers::collapseSpaces($row['MEDIA']);
                            $masterMedia->save();
                            $mediaId =  $masterMedia->getPrimaryKey();
                        }else{
                            $mediaId = $mediaId->ID;
                        }

                        //Sumber
                        $sourceId = \common\models\Collectionsources::find()->where(['Name' => Helpers::collapseSpaces($row['SUMBER'])])->one();
                        if(is_null($sourceId)){
                            // INSERT MEDIA BARU
                            $masterSource = new \common\models\Collectionsources;
                            $masterSource->Code = Helpers::collapseSpaces($row['SUMBER']);
                            $masterSource->Name = Helpers::collapseSpaces($row['SUMBER']);
                            $masterSource->save();
                            $sourceId =  $masterSource->getPrimaryKey();
                        }else{
                            $sourceId = $sourceId->ID;
                        }

                        //Status
                        $statusId = \common\models\Collectionstatus::find()->where(['Name' => Helpers::collapseSpaces($row['KETERSEDIAAN'])])->one();
                        if(is_null($statusId)){
                            // INSERT MEDIA BARU
                            $masterStatus = new \common\models\Collectionstatus;
                            $masterStatus->Name = Helpers::collapseSpaces($row['KETERSEDIAAN']);
                            $masterStatus->save();
                            $statusId =  $masterStatus->getPrimaryKey();
                        }else{
                            $statusId = $statusId->ID;
                        }

                        //Lokasi Perpustakaan
                       /* $locationLibraryId = \common\models\LocationLibrary::find()->where(['Name' => Helpers::collapseSpaces($row['LOKASI PERPUSTAKAAN'])])->one();
                        if(is_null($locationLibraryId)){
                            // INSERT MEDIA BARU
                            $masterlocationLibrary = new \common\models\LocationLibrary;
                            $masterlocationLibrary->Name = Helpers::collapseSpaces($row['LOKASI PERPUSTAKAAN']);
                            $masterlocationLibrary->save();
                            $statusId =  $masterStatus->getPrimaryKey();
                        }else{
                            $locationLibraryId = $locationLibraryId->ID;
                        }*/

                        
                        $collections->ID = NULL;
                        if(Helpers::collapseSpaces($row['ITEM ID']) != '')
                        {
                            $nomorbarcode=Helpers::collapseSpaces($row['ITEM ID']);
                        }else{
                            $nomorbarcode=str_pad((int)\common\models\Collections::find()->select('MAX(ID) AS ID')->one()->ID +1 , 11, '0', STR_PAD_LEFT);
                        }

                        if (trim(str_replace(' ','',strtolower(Yii::$app->config->get('FormatNomorBarcode'))))=='no.induk')
                        {
                            $nomorbarcode = Helpers::collapseSpaces($row['NO INDUK']);
                        }

                        if (trim(str_replace(' ','',strtolower(Yii::$app->config->get('FormatNomorRFID'))))=='no.induk')
                        {
                            $nomorrfid = Helpers::collapseSpaces($row['NO INDUK']);
                        }else{
                            $nomorrfid = $nomorbarcode;
                        }
                        $collections->JumlahEksemplar = 1;
                        $collections->NomorBarcode = $nomorbarcode;
                        $collections->RFID = $nomorrfid;
                        $collections->NoInduk = Helpers::collapseSpaces($row['NO INDUK']);
                        $collections->Currency = Helpers::collapseSpaces($row['MATA UANG']);
                        $collections->Price = Helpers::collapseSpaces($row['HARGA']);
                        //$collections->PriceType = Helpers::collapseSpaces($row['HARGA']);
                        //$collections->Keterangan_Sumber = Helpers::collapseSpaces($row['NO TELP INSTITUSI']);
                        if($row['TGL PENGADAAN']!='')
                        {
                            $dates=explode('-',$row['TGL PENGADAAN']);
                            $year=$dates[2];
                            $day=$dates[1];
                            $month=$dates[0];
                            $tglpengadaan= '20'.$year.'-'.$month.'-'.$day;
                            $collections->TanggalPengadaan   = $tglpengadaan;
                        }
                        $collections->CallNumber = Helpers::collapseSpaces($row['NOMOR PANGGIL']);
                        $collections->Branch_id = 37;
                        $collections->Catalog_id = $catalogId;
                        //$collections->Location_Library_id = $locationLibraryId;
                        $collections->Location_Library_id = 1;
                        $collections->Status_id = $statusId;
                        $collections->Source_id = $sourceId;
                        $collections->Media_id = $mediaId;
                        $collections->Category_id = $categoryId;
                        $collections->Rule_id = $rulesId;
                        $collections->Location_id = $locationId;
                        $collections->Partner_id = $partnerId;
                        $collections->CreateBy = Helpers::collapseSpaces($row['DIBUAT OLEH']);
                        $collections->IsVerified = 0;
                        $collections->ISREFERENSI = Helpers::collapseSpaces($row['REFERENSI']);
                        $collections->EDISISERIAL = Helpers::collapseSpaces($row['EDISI SERIAL']);
                        if($row['TGL TERBIT EDISI SERIAL']!='')
                        {
                            $dates=explode('-',$row['TGL TERBIT EDISI SERIAL']);
                            $year=$dates[2];
                            $day=$dates[1];
                            $month=$dates[0];
                            $tgledisiserial= '20'.$year.'-'.$month.'-'.$day;
                            $collections->TANGGAL_TERBIT_EDISI_SERIAL   = $tgledisiserial;
                        }
                        $collections->BAHAN_SERTAAN = Helpers::collapseSpaces($row['BAHAN SERTAAN (SERIAL)']);
                        $collections->KETERANGAN_LAIN = Helpers::collapseSpaces($row['KETERANGAN LAIN (SERIAL)']);

                        if($collections->save()){
                            $success++;
                        }else{
                            //print_r($members->getErrors());
                            $trans->rollback();
                            if($collections->hasErrors()){
                              echo \yii\helpers\Html::errorSummary($collections);
                            }
                            echo "Import Data Koleksi Gagal.";
                            return false;  
                        }
                        

                    }
                    if($success==$recno)
                    {
                        $trans->commit();
                        echo "Import Data Anggota Selesai.";
                        return true;  
                    }else{
                        $trans->rollback();
                        echo "Import Data Anggota Gagal.";
                        return false; 
                    }
                    
                }catch(ErrorException $e){
                    $trans->rollback();
                    Yii::warning($e);
                    echo $e;
                    return false;
                }
    } 

    public function import_aacr(&$error){
        try{
                    $path = Yii::getAlias('@uploaded_files') . '/temporary/imported_data_sheet/imported/'.$this->file->baseName . '.' . $this->file->extension;

                    $data = \moonland\phpexcel\Excel::widget([
                            'mode' => 'import', 
                            'fileName' => $path, 
                            'setFirstRecordAsKeys' => false, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel. 
                            'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric. 
                            'getOnlySheet' => 'Sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
                        ]);
                    $trans = Yii::$app->db->beginTransaction();
                    /*echo '<pre>'; print_r($data); echo '</pre>';die;*/
                   $success=0;
                   $recno=0;
                   $recexcel=0;
                    foreach ($data as $row) {
                        $recexcel++;
                        if($recexcel < 3)
                        {
                            continue;
                        }
                        if(Helpers::collapseSpaces($row['R'])!='')
                        {
                            $recno++;
                            $collections = new \common\models\Collections;

                            //Worksheet
                            $worksheetId = \common\models\Worksheets::find()->where(['Name' => Helpers::collapseSpaces($row['Q'])])->one();
                            if(is_null($worksheetId)){
                                // INSERT JENIS BAHAN
                                $masterWorksheet = new \common\models\Worksheets;
                                $masterWorksheet->Name = Helpers::collapseSpaces($row['Q']);
                                $masterWorksheet->save();
                                $worksheetId =  $masterWorksheet->getPrimaryKey();
                            }else{
                                $worksheetId = $worksheetId->ID;
                            }


                            //Catalogs
                            $controlnumber=CatalogHelpers::getControlNumber(1); 
                            $bibid=CatalogHelpers::getBibId(1); 
                            $title_a=Helpers::collapseSpaces($row['R']);
                            $title_b=Helpers::collapseSpaces($row['S']);
                            $title_c=Helpers::collapseSpaces($row['T']);
                            $titlemix ='';
                            $tandabaca245b=CatalogHelpers::getTandaBaca('245','b');
                            $tandabaca245c=CatalogHelpers::getTandaBaca('245','c');
                            if($title_a !='')
                            {

                                $titlemix .= CatalogHelpers::cleanLastChar($tandabaca245b,$title_a).' ';
                            }
                            if($title_b !='')
                            {
                                $t245b=CatalogHelpers::cleanFirstChar($tandabaca245b, $title_b);
                                $t245b=CatalogHelpers::cleanLastChar($tandabaca245c, $t245b);
                                $titlemix .= $tandabaca245b.' '.$t245b.' ';
                                
                            }
                            if($title_c !='')
                            {
                                $titlemix .= $tandabaca245c.' '.CatalogHelpers::cleanFirstChar($tandabaca245c, $title_c);
                            }
                            $author=Helpers::collapseSpaces($row['U']);
                            $badankooperasi=Helpers::collapseSpaces($row['V']);
                            $authoradded=Helpers::collapseSpaces($row['W']);
                            $authoraddedbadan=Helpers::collapseSpaces($row['X']);
                            $edition=Helpers::collapseSpaces($row['Y']);
                            $publishlocation=Helpers::collapseSpaces($row['Z']);
                            $publisher=Helpers::collapseSpaces($row['AA']);
                            $publishyear=Helpers::collapseSpaces($row['AB']);
                            $jumlahhalaman=Helpers::collapseSpaces($row['AC']);
                            //$illustrasi=Helpers::collapseSpaces($row['S']);
                            $dimensi=Helpers::collapseSpaces($row['AD']);
                            $physicaldescription ='';
                            $tandabaca300b=CatalogHelpers::getTandaBaca('300','b');
                            $tandabaca300c=CatalogHelpers::getTandaBaca('300','c');
                            if($jumlahhalaman !='')
                            {

                                $physicaldescription .= CatalogHelpers::cleanLastChar($tandabaca300b,$jumlahhalaman).' ';
                            }
                            /*if($illustrasi !='')
                            {
                                $t300b=CatalogHelpers::cleanFirstChar($tandabaca300b,$illustrasi);
                                $t300b=CatalogHelpers::cleanLastChar($tandabaca300c, $t300b);
                                $physicaldescription .= $tandabaca300b.' '.$t300b.' ';
                                
                            }*/
                            if($dimensi !='')
                            {
                                $physicaldescription .= $tandabaca300c.' '.CatalogHelpers::cleanFirstChar($tandabaca300c, $dimensi);
                            }
                            $isbn=Helpers::collapseSpaces($row['AE']);
                            $issn=Helpers::collapseSpaces($row['AF']);
                            $ismn=Helpers::collapseSpaces($row['AG']);
                            $isbnmix='';
                            if($isbn!='')
                            {
                                $isbnmix .= ($isbnmix!='') ? ' ; ' :'';
                                $isbnmix .= $isbn;
                            }
                            if($issn!='')
                            {
                                $isbnmix .= ($isbnmix!='') ? ' ; ' :'';
                                $isbnmix .= $issn;
                            }
                            if($ismn!='')
                            {
                                $isbnmix .= ($isbnmix!='') ? ' ; ' :'';
                                $isbnmix .= $ismn;
                            }
                            $deweyno=Helpers::collapseSpaces($row['AH']);
                            $callnumber=Helpers::collapseSpaces($row['AI']);
                            //$note=Helpers::collapseSpaces($row['CATATAN UMUM']);
                            $abstrak=Helpers::collapseSpaces($row['AJ']);
                            $languages=Helpers::collapseSpaces($row['AK']);
                            $subject=Helpers::collapseSpaces($row['AL']);
                            $edisiserial=Helpers::collapseSpaces($row['AM']);
                            $tgledisiserial=Helpers::collapseSpaces($row['AN']);
                            $bahansertaan=Helpers::collapseSpaces($row['AO']);
                            $keteranganlain=Helpers::collapseSpaces($row['AP']);

                            $catalogId = \common\models\Catalogs::find()->where([
                                'Worksheet_id' => $worksheetId,
                                'Title' =>  $titlemix,
                                'Author' =>  Helpers::collapseSpaces($row['U']),
                                'Edition' =>  Helpers::collapseSpaces($row['Y']),
                                'Publisher' =>  Helpers::collapseSpaces($row['AA']),
                                'PublishLocation' =>  Helpers::collapseSpaces($row['Z']),
                                'PublishYear' =>  Helpers::collapseSpaces($row['AB']),
                                ])->one();

                            $noinduk=Helpers::collapseSpaces($row['C']);

                            if(is_null($catalogId)){
                                // INSERT KATALOG BARU
                                $masterCatalog = new \common\models\Catalogs;
                                $masterCatalog->ControlNumber = $controlnumber; 
                                $masterCatalog->BIBID = $bibid;
                                $masterCatalog->Title = $titlemix;
                                $masterCatalog->Author = $author;
                                $masterCatalog->Edition = $edition;
                                $masterCatalog->PublishLocation = $publishlocation;
                                $masterCatalog->Publikasi = preg_replace('/[^a-zA-Z0-9 ]/','',$publishlocation).' : '.preg_replace('/[^a-zA-Z0-9 ]/','',$publisher).', '.preg_replace('/[^a-zA-Z0-9 ]/','',$publishyear);
                                $masterCatalog->Publisher = $publisher;
                                $masterCatalog->PublishYear = $publishyear;
                                $masterCatalog->PhysicalDescription = $physicaldescription;
                                $masterCatalog->ISBN = $isbnmix;
                                $masterCatalog->CallNumber = $callnumber;
                                $masterCatalog->Note = $abstrak;
                                $masterCatalog->Languages = $languages;
                                $masterCatalog->DeweyNo = $deweyno;
                                $masterCatalog->Subject = $subject;
                                $masterCatalog->Worksheet_id = $worksheetId;
                                /*echo '<pre>'; print_r($masterCatalog); echo '</pre>';die;*/
                                if($masterCatalog->save())
                                {
                                    $catalogId =  $masterCatalog->getPrimaryKey();
                                    $seq = 1;
                                    //Saving controlnumber
                                    CatalogHelpers::saveCatalogRuas($catalogId,NULL,NULL,'001',$controlnumber,$seq++);

                                    //Saving language
                                    if($languages!='' && CatalogHelpers::saveCatalogRuas($catalogId,'#','#','008','###################################'.$languages.'##',$seq++))
                                    {
                                        //save catalog sub ruas records
                                        CatalogHelpers::saveCatalogSubRuas('a',trim('###################################'.$languages.'##'),1);
                                    }


                                    //Saving bibid
                                    if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','035','$a '.$bibid,$seq++))
                                    {
                                        //save catalog sub ruas records
                                        CatalogHelpers::saveCatalogSubRuas('a',trim($bibid),1);
                                    }
                                    //Saving title
                                    if($titlemix!='')
                                    {
                                        $t245='';
                                        if($title_a !='')
                                        {

                                            $t245 .= '$a '.CatalogHelpers::cleanLastChar($tandabaca245b,$title_a).' ';
                                        }
                                        if($title_b !='')
                                        {
                                            $t245b=CatalogHelpers::cleanFirstChar($tandabaca245b, $title_b);
                                            $t245b=CatalogHelpers::cleanLastChar($tandabaca245c, $t245b);
                                            $t245 .= $tandabaca245b.' $b '.$t245b.' ';
                                            
                                        }
                                        if($title_c !='')
                                        {
                                            $t245 .= $tandabaca245c.' $c '.CatalogHelpers::cleanFirstChar($tandabaca245c, $title_c);
                                        }
                                            
                                        
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','245',trim($t245),$seq++))
                                        {
                                            //save catalog sub ruas records
                                            if($title_a!='')
                                            {
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($title_a),1);
                                            }
                                            if($title_b!='')
                                            {
                                                CatalogHelpers::saveCatalogSubRuas('b',trim($title_b),2);
                                            }
                                            if($title_c!='')
                                            {
                                                CatalogHelpers::saveCatalogSubRuas('c',trim($title_c),3);
                                            }
                                        }
                                    }

                                    //Saving author
                                    if($author!='' && CatalogHelpers::saveCatalogRuas($catalogId,'#','#','100','$a '.$author,$seq++))
                                    {
                                        //save catalog sub ruas records
                                        CatalogHelpers::saveCatalogSubRuas('a',trim($author),1);
                                    }

                                    //Saving badan kooperasi
                                    if($badankooperasi!='' && CatalogHelpers::saveCatalogRuas($catalogId,'#','#','110','$a '.$badankooperasi,$seq++))
                                    {
                                        //save catalog sub ruas records
                                        CatalogHelpers::saveCatalogSubRuas('a',trim($badankooperasi),1);
                                    }

                                    //Saving author added
                                    if($authoradded!='')
                                    {
                                        $authoraddeds=explode(';',$authoradded);
                                        for ($i=1; $i < count($authoraddeds); $i++) { 
                                            if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','700','$a '.trim($authoraddeds[$i]),$seq++))
                                            {
                                                //save catalog sub ruas records
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($authoraddeds[$i]),1);
                                            }
                                        } 
                                    }

                                    //Saving author added badan 
                                    if($authoraddedbadan!='')
                                    {
                                        $authoraddedbadans=explode(';',$authoraddedbadan);
                                        for ($i=1; $i < count($authoraddedbadans); $i++) { 
                                            if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','710','$a '.trim($authoraddedbadans[$i]),$seq++))
                                            {
                                                //save catalog sub ruas records
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($authoraddedbadans[$i]),1);
                                            }
                                        } 
                                    }
                                    

                                    //Saving edition
                                    if($edition!='' && CatalogHelpers::saveCatalogRuas($catalogId,'#','#','250','$a '.$edition,$seq++))
                                    {
                                        //save catalog sub ruas records
                                        CatalogHelpers::saveCatalogSubRuas('a',trim($edition),1);
                                    }

                                    //Saving publishment
                                    if($publishlocation!='' || $publisher!=''|| $publishyear!='')
                                    {
                                        $a='';
                                        $b='';
                                        $c='';
                                        $t260='';
                                        if($publishlocation!='')
                                        {
                                            $a=$publishlocation;
                                            $t260 .= '$a '.$a.' ';
                                        }
                                        if($publisher!='')
                                        {
                                            $b=$publisher;
                                            $t260 .= '$b '.$b.' ';
                                        }
                                        if($publishyear!='')
                                        {
                                            $c=$publishyear;
                                            $t260 .= '$c '.$c.' ';
                                        }
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','260',$t260,$seq++))
                                        {
                                            //save catalog sub ruas records
                                            if($a!='')
                                            {
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($a),1);
                                            }
                                            if($b!='')
                                            {
                                                CatalogHelpers::saveCatalogSubRuas('b',trim($b),2);
                                            }
                                            if($c!='')
                                            {
                                                CatalogHelpers::saveCatalogSubRuas('c',trim($c),3);
                                            }
                                        }
                                    }

                                    //Saving physical desc
                                    if($physicaldescription!='')
                                    {
                                        $t300='';
                                        if($jumlahhalaman !='')
                                        {

                                            $t300 .= '$a '.CatalogHelpers::cleanLastChar($tandabaca300b,$jumlahhalaman).' ';
                                        }
                                        /*if($illustrasi !='')
                                        {
                                            $t300b=CatalogHelpers::cleanFirstChar($tandabaca300b,$illustrasi);
                                            $t300b=CatalogHelpers::cleanLastChar($tandabaca300c, $t300b);
                                            $physicaldescription .= $tandabaca300b.' '.$t300b.' ';
                                            
                                        }*/
                                        if($dimensi !='')
                                        {
                                            $t300 .= $tandabaca300c.' $c '.CatalogHelpers::cleanFirstChar($tandabaca300c, $dimensi);
                                        }
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','300',$t300,$seq++))
                                        {
                                            //save catalog sub ruas records
                                            if($jumlahhalaman!='')
                                            {
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($jumlahhalaman),1);
                                            }
                                            /*if($b!='')
                                            {
                                                CatalogHelpers::saveCatalogSubRuas('b',trim($b),2);
                                            }*/
                                            if($dimensi!='')
                                            {
                                                CatalogHelpers::saveCatalogSubRuas('c',trim($dimensi),2);
                                            }
                                        }
                                    }

                                    //Saving isbn
                                    if($isbn!='' )
                                    {
                                        $isbns=explode(';',$isbn);
                                        for ($i=0; $i < count($isbns) ; $i++) { 
                                            if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','020','$a '.trim($isbns[$i]),$seq++))
                                            {
                                                //save catalog sub ruas records
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($isbns[$i]),1);
                                            }
                                        }
                                        
                                    }

                                    //Saving issn

                                    if($issn!='' )
                                    {
                                        $issns=explode(';',$issn);
                                        for ($i=0; $i < count($issns) ; $i++) { 
                                            if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','022','$a '.trim($issns[$i]),$seq++))
                                            {
                                                //save catalog sub ruas records
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($issns[$i]),1);
                                            }
                                        }
                                        
                                    }

                                    //Saving ismn

                                    if($ismn!='' )
                                    {
                                        $ismns=explode(';',$ismn);
                                        for ($i=0; $i < count($ismns) ; $i++) { 
                                            if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','024','$a '.trim($ismns[$i]),$seq++))
                                            {
                                                //save catalog sub ruas records
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($ismns[$i]),1);
                                            }
                                        }
                                        
                                    }


                                    //Saving callnumber

                                    if($callnumber!='' )
                                    {
                                        $callnumbers=explode(';',$callnumber);
                                        for ($i=0; $i < count($callnumbers) ; $i++) { 
                                            if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','084','$a '.trim($callnumbers[$i]),$seq++))
                                            {
                                                //save catalog sub ruas records
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($callnumbers[$i]),1);
                                            }
                                        }
                                        
                                    }

                                    //Saving note
                                    if($abstrak!='' )
                                    {
                                        $abstraks=explode(';',$abstrak);
                                        for ($i=0; $i < count($abstraks) ; $i++) { 
                                            if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','520','$a '.trim($abstraks[$i]),$seq++))
                                            {
                                                //save catalog sub ruas records
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($abstraks[$i]),1);
                                            }
                                        }
                                        
                                    }

                                    //Saving deweyno
                                    if($deweyno!='' && CatalogHelpers::saveCatalogRuas($catalogId,'#','#','082','$a '.$deweyno,$seq++))
                                    {
                                        //save catalog sub ruas records
                                        CatalogHelpers::saveCatalogSubRuas('a',trim($deweyno),1);
                                    }

                                    //Saving subject
                                    if($subject!='' )
                                    {
                                        $subjects=explode(';',$subject);
                                        for ($i=0; $i < count($subjects) ; $i++) { 
                                            if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','650','$a '.trim($subjects[$i]),$seq++))
                                            {
                                                //save catalog sub ruas records
                                                CatalogHelpers::saveCatalogSubRuas('a',trim($subjects[$i]),1);
                                            }
                                        }
                                        
                                    }

                                    //Saving edisi serial
                                    if($edisiserial!='' )
                                    {
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','863','$a '.$edisiserial,$seq++))
                                        {
                                            //save catalog sub ruas records
                                            CatalogHelpers::saveCatalogSubRuas('a',$edisiserial,1);
                                        }
                                    }

                                    //Saving no induk
                                    if($noinduk!='' )
                                    {
                                        if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','990','$a '.$noinduk,$seq++))
                                        {
                                            //save catalog sub ruas records
                                            CatalogHelpers::saveCatalogSubRuas('a',$noinduk,1);
                                        }
                                    }

                                }
                                else
                                {
                                    //print_r($members->getErrors());
                                    if($masterCatalog->hasErrors()){
                                        foreach ($masterCatalog->getErrors() as $key => $value) {
                                           foreach ($value as $key2 => $value2) {
                                                $error[] = "Rec No [".$recno."] ".$value2;
                                            }
                                        }
                                    }
                                    //echo "Simpan data bibliografis Gagal.";
                                    return false;
                                }
                                
                            }else{
                                $catalogId = $catalogId->ID;

                                //Saving no induk
                                
                                if($noinduk!='' )
                                {
                                    $catruas = \common\models\CatalogRuas::find()
                                    ->addSelect(['MAX(Sequence) + 1 AS Sequence'])
                                    ->where(['CatalogId'=>$catalogId])
                                    ->one();
                                    if(CatalogHelpers::saveCatalogRuas($catalogId,'#','#','990','$a '.$noinduk,(int)$catruas->Sequence))
                                    {
                                        //save catalog sub ruas records
                                        CatalogHelpers::saveCatalogSubRuas('a',$noinduk,1);
                                    }
                                }
                            }

                            //Rekanan
                            $partnerId = \common\models\Partners::find()->where(['Name' => Helpers::collapseSpaces($row['G'])])->one();
                            if(is_null($partnerId)){
                                // INSERT REKANAN BARU
                                $masterPartner = new \common\models\Partners;
                                $masterPartner->Name = Helpers::collapseSpaces($row['G']);
                                $masterPartner->save();
                                $partnerId =  $masterPartner->getPrimaryKey();
                            }else{
                                $partnerId = $partnerId->ID;
                            }

                            //Lokasi
                            $locationId = \common\models\Locations::find()->where(['Code' => Helpers::collapseSpaces($row['K'])])->one();
                            if(is_null($locationId)){
                                // INSERT LOKASI BARU
                                $masterLocation = new \common\models\Locations;
                                $masterLocation->Code = Helpers::collapseSpaces($row['K']);
                                $masterLocation->Name = Helpers::collapseSpaces($row['K']);
                                $masterLocation->save();
                                $locationId =  $masterLocation->getPrimaryKey();
                            }else{
                                $locationId = $locationId->ID;
                            }

                            //Akses
                            $rulesId = \common\models\Collectionrules::find()->where(['Name' => Helpers::collapseSpaces($row['L'])])->one();
                            if(is_null($rulesId)){
                                // INSERT AKSES BARU
                                $masterRules = new \common\models\Collectionrules;
                                $masterRules->Name = Helpers::collapseSpaces($row['L']);
                                $masterRules->save();
                                $rulesId =  $masterRules->getPrimaryKey();
                            }else{
                                $rulesId = $rulesId->ID;
                            }

                            //Kategori
                            $categoryId = \common\models\Collectioncategorys::find()->where(['Name' => Helpers::collapseSpaces($row['M'])])->one();
                            if(is_null($categoryId)){
                                // INSERT KATEGORI BARU
                                $masterCategory = new \common\models\Collectioncategorys;
                                $masterCategory->Code = Helpers::collapseSpaces($row['M']);
                                $masterCategory->Name = Helpers::collapseSpaces($row['M']);
                                $masterCategory->save();
                                $categoryId =  $masterCategory->getPrimaryKey();
                            }else{
                                $categoryId = $categoryId->ID;
                            }

                            //Media
                            $mediaId = \common\models\Collectionmedias::find()->where(['Name' => Helpers::collapseSpaces($row['N'])])->one();
                            if(is_null($mediaId)){
                                // INSERT MEDIA BARU
                                $masterMedia = new \common\models\Collectionmedias;
                                $masterMedia->Code = Helpers::collapseSpaces($row['N']);
                                $masterMedia->Name = Helpers::collapseSpaces($row['N']);
                                $masterMedia->save();
                                $mediaId =  $masterMedia->getPrimaryKey();
                            }else{
                                $mediaId = $mediaId->ID;
                            }

                            //Sumber
                            $sourceId = \common\models\Collectionsources::find()->where(['Name' => Helpers::collapseSpaces($row['F'])])->one();
                            if(is_null($sourceId)){
                                // INSERT MEDIA BARU
                                $masterSource = new \common\models\Collectionsources;
                                $masterSource->Code = Helpers::collapseSpaces($row['F']);
                                $masterSource->Name = Helpers::collapseSpaces($row['F']);
                                $masterSource->save();
                                $sourceId =  $masterSource->getPrimaryKey();
                            }else{
                                $sourceId = $sourceId->ID;
                            }

                            //Status
                            $statusId = \common\models\Collectionstatus::find()->where(['Name' => Helpers::collapseSpaces($row['O'])])->one();
                            if(is_null($statusId)){
                                // INSERT MEDIA BARU
                                $masterStatus = new \common\models\Collectionstatus;
                                $masterStatus->Name = Helpers::collapseSpaces($row['O']);
                                $masterStatus->save();
                                $statusId =  $masterStatus->getPrimaryKey();
                            }else{
                                $statusId = $statusId->ID;
                            }

                            //Lokasi Perpustakaan
                            $locationLibraryId = \common\models\LocationLibrary::find()->where(['Code' => Helpers::collapseSpaces($row['J'])])->one();
                            if(is_null($locationLibraryId)){
                                // INSERT MEDIA BARU
                                $masterlocationLibrary = new \common\models\LocationLibrary;
                                $masterlocationLibrary->Code = Helpers::collapseSpaces($row['J']);
                                $masterlocationLibrary->Name = Helpers::collapseSpaces($row['J']);
                                $masterlocationLibrary->Address = '-';
                                $masterlocationLibrary->save();
                                $locationLibraryId =  $masterlocationLibrary->getPrimaryKey();
                            }else{
                                $locationLibraryId = $locationLibraryId->ID;
                            }

                            
                            $collections->ID = NULL;
                            if(Helpers::collapseSpaces($row['D']) != '')
                            {
                                $nomorbarcode=Helpers::collapseSpaces($row['D']);
                            }else{
                                $nomorbarcode=str_pad((int)\common\models\Collections::find()->select('MAX(ID) AS ID')->one()->ID +1 , 11, '0', STR_PAD_LEFT);
                            }

                            if(Helpers::collapseSpaces($row['E']) != '')
                            {
                                $nomorrfid=Helpers::collapseSpaces($row['E']);
                            }else{
                                $nomorrfid=str_pad((int)\common\models\Collections::find()->select('MAX(ID) AS ID')->one()->ID +1 , 11, '0', STR_PAD_LEFT);
                            }

                            if (trim(str_replace(' ','',strtolower(Yii::$app->config->get('FormatNomorBarcode'))))=='no.induk')
                            {
                                if(Helpers::collapseSpaces($row['C']) != '')
                                {
                                    $nomorbarcode = Helpers::collapseSpaces($row['C']);
                                }
                            }

                            if (trim(str_replace(' ','',strtolower(Yii::$app->config->get('FormatNomorRFID'))))=='no.induk')
                            {
                                if(Helpers::collapseSpaces($row['C']) != '')
                                {
                                    $nomorrfid = Helpers::collapseSpaces($row['C']);
                                }
                            }

                            $collections->JumlahEksemplar = 1;
                            $collections->NomorBarcode = $nomorbarcode;
                            $collections->RFID = $nomorrfid;
                            $collections->NoInduk = Helpers::collapseSpaces($row['C']);
                            $collections->Currency = Helpers::collapseSpaces($row['H']);
                            $collections->Price = Helpers::collapseSpaces($row['I']);
                            //$collections->PriceType = Helpers::collapseSpaces($row['HARGA']);
                            //$collections->Keterangan_Sumber = Helpers::collapseSpaces($row['NO TELP INSTITUSI']);
                            if($row['B']!='')
                            {
                                $dates=explode('-',$row['B']);
                                $year=$dates[2];
                                $day=$dates[0];
                                $month=$dates[1];
                                $tglpengadaan= $year.'-'.$month.'-'.$day;
                                $collections->TanggalPengadaan   = $tglpengadaan;
                            }
                            $collections->CallNumber = Helpers::collapseSpaces($row['P']);
                            //$collections->Branch_id = 37;
                            $collections->Catalog_id = $catalogId;
                            $collections->Location_Library_id = $locationLibraryId;
                            $collections->Status_id = $statusId;
                            $collections->Source_id = $sourceId;
                            $collections->Media_id = $mediaId;
                            $collections->Category_id = $categoryId;
                            $collections->Rule_id = $rulesId;
                            $collections->Location_id = $locationId;
                            $collections->Partner_id = $partnerId;
                            $collections->IsVerified = 0;
                            //$collections->ISREFERENSI = Helpers::collapseSpaces($row['REFERENSI']);
                            $collections->EDISISERIAL = Helpers::collapseSpaces($row['AM']);
                            if($row['AN']!='')
                            {
                                $dates=explode('-',$row['AN']);
                                $year=$dates[2];
                                $day=$dates[0];
                                $month=$dates[1];
                                $tgledisiserial= $year.'-'.$month.'-'.$day;
                                $collections->TANGGAL_TERBIT_EDISI_SERIAL   = $tgledisiserial;
                            }
                            $collections->BAHAN_SERTAAN = Helpers::collapseSpaces($row['AO']);
                            $collections->KETERANGAN_LAIN = Helpers::collapseSpaces($row['AP']);
                            //echo '<pre>'; print_r($collections); die;
                            if($collections->save()){
                                $success++;
                            }else{
                                //print_r($members->getErrors());
                                $trans->rollback();
                                if($collections->hasErrors()){
                                    foreach ($collections->getErrors() as $key => $value) {
                                       foreach ($value as $key2 => $value2) {
                                            $error[] = "Rec No [".$recno."] ".$value2;
                                        }
                                    }
                                }

                                //echo "{error : Import Data Koleksi Gagal.}";
                                return false;  
                            }
                        }

                    }
                    if($success==$recno)
                    {
                        $trans->commit();
                        //echo "Import Data Koleksi Selesai.";
                        return true;  
                    }else{
                        $trans->rollback();
                        $error[]= "Import Data Koleksi Gagal.";
                        return false; 
                    }
                    
                }catch(ErrorException $e){
                    $trans->rollback();
                    Yii::warning($e);
                    echo $e;
                    return false;
                }
    } 
   
    /**
    * Process deletion of file imported
    *
    * @return boolean the status of deletion
    */
    public function deleteFile() {
        $path = Yii::getAlias('@uploaded_files') . '/temporary/imported_data_sheet/imported/';
        $file = $path. $this->file->baseName . '.' . $this->file->extension;
        chmod($file, 0666);
        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }
 
        // check if uploaded file can be deleted on server
        if (!unlink($file)) {
            return false;
        }
 
        return true;
    }
}