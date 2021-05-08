<?php

namespace opac\controllers;

use common\components\DirectoryHelpers;
use common\models\elastic\Catalogruas;
use Yii;
use common\models\Worksheets;
use common\models\Bookinglogs;
use common\models\Favorite;
use common\models\Requestcatalog;
use common\models\CollectionSearchKardeks;
use common\models\SerialArticlesSearch;
use yii\elasticsearch\Query;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use common\components\ElasticHelper;
use common\components\OpacHelpers;

use common\models\OpacCounter;
use yii\httpclient\Client;

$session = Yii::$app->session;
$session->open();

class SearchController extends \yii\web\Controller {
    public $layout = 'main-sederhana-search';
    public $location;
    public function actionIndex() {
        

        $location = Yii::$app->request->cookies->getValue('location_opac_id');
        $jmlBookMaks = Yii::$app->config->get('JumlahBookingMaksimal');
        $bookExp = Yii::$app->config->get('BookingExpired');
        $UsulanKoleksi = Yii::$app->config->get('UsulanKoleksi');
        $dateNow = new \DateTime("now");
        $noAnggota= (Yii::$app->user->isGuest ? null : \Yii::$app->user->identity->NoAnggota );
        $booking = OpacHelpers::jumlahBooking($noAnggota);

        $alert = false;
        $session = Yii::$app->session;
        $url = Yii::$app->request->absoluteUrl;
        $waktu = date('m-d-Y H:i:s');
        $action = ( isset($_GET['action']) ) ? addslashes(urldecode($_GET['action'])) : "pencarianSederhana";
        //get max faced
        $FacedAuthorMax = Yii::$app->config->get('FacedAuthorMax');
        $FacedPublisherMax = Yii::$app->config->get('FacedPublisherMax');
        $FacedPublishLocationMax = Yii::$app->config->get('FacedPublishLocationMax');
        $FacedPublishYearMax = Yii::$app->config->get('FacedPublishYearMax');
        $FacedSubjectMax = Yii::$app->config->get('FacedSubjectMax');
        $FacedBahasaMax = Yii::$app->config->get('FacedBahasaMax');

        //get min faced
        $FacedAuthorMin = Yii::$app->config->get('FacedAuthorMin');
        $FacedPublisherMin = Yii::$app->config->get('FacedPublisherMin');
        $FacedPublishLocationMin = Yii::$app->config->get('FacedPublishLocationMin');
        $FacedPublishYearMin = Yii::$app->config->get('FacedPublishYearMin');
        $FacedSubjectMin = Yii::$app->config->get('FacedSubjectMin');
        $FacedBahasaMin = Yii::$app->config->get('FacedBahasaMin');

        $request = Yii::$app->request;
        if ($request->isAjax && $_GET['action'] === "favourite") {
            if (Yii::$app->user->isGuest) {
                return $this->redirect('../keanggotaan/site/login');
            }
            $model = new favorite;
            (int) $count = favorite::find()
                    ->where(['Member_Id' => \Yii::$app->user->identity->NoAnggota, 'Catalog_Id' => addslashes($_GET['catID'])])
                    ->count();


            if ($count == 0) {
                $model->Member_Id = \Yii::$app->user->identity->NoAnggota;
                $model->Catalog_Id = addslashes($_GET['catID']);
                //$model->CreateDate = new Expression('NOW()');
                $model->save();

                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'info',
                    'duration' => 2500,
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'message' => Yii::t('app', '  Telah Di Simpan ke-dalam daftar Favorite'),
                    'title' => 'success',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'danger',
                    'delay' => 2500,
                    'icon' => 'glyphicon glyphicon-remove',
                    'message' => Yii::t('app', ' Katalog ini sudah ada di dalam daftar Favorite anda'),
                    'title' => 'Gagal',
                    'body' => 'This is a successful growling alert.',
                    'positonY' => Yii::$app->params['flashMessagePositionY'],
                    'positonX' => Yii::$app->params['flashMessagePositionX']
                ]);
            }

            return $this->renderAjax('_favorite', [
                        'catID' => addslashes($_GET['catID']),
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "requestCatalog") {
            $model = new requestcatalog;
            $model->MemberID = 1;
            $model->WorksheetID = 1;
            $model->Title = 1;
            $model->Author = 1;
            $model->PublishLocation = 1;
            $model->PublishYear = 1;
            $model->Comments = 1;
            $model->save();
        }

        if (Yii::$app->request->get() && $_GET['action'] === "pencarianSederhana") {              
            $bahan = addslashes($_GET['bahan']);
            
            if ($bahan != 'Semua Jenis Bahan') {
                $tmp = worksheets::find()
                    ->where('id = :bahan', [':bahan' => $bahan])
                    ->one();
                $bahan = $tmp['Name'];  
                $bahanid = $tmp['ID'];  
            }
            $bahan1 = $bahan;
   
            $Keyword = urldecode($_GET['katakunci']);
            $ruas = addslashes($_GET['ruas']);
            $ip = OpacHelpers::getIP();
            //catat history pencarian di session
            if (isset($_SESSION['RiwayatPencarian'])) {
                $temp = $_SESSION['RiwayatPencarian'];
                $_SESSION['RiwayatPencarian'] = array_merge($temp, array(
                    array(
                        "ip" =>  $ip,
                        "url" => $url,
                        "action" => addslashes($_GET['action']),
                        "keyword" => $ruas . " = " . $Keyword,
                        "bahan" => $bahan,
                        "time" => $waktu,
                    )
                ));
            } else {
                $temp = array(
                    array(
                        "ip" =>  $ip,
                        "url" => $url,
                        "action" => addslashes($_GET['action']),
                        "keyword" => $ruas . " = " . $Keyword,
                        //"ruas" => $ruas,
                        "bahan" => $bahan,
                        "time" => $waktu,
                    )
                );
                $_SESSION['RiwayatPencarian'] = $temp;
            }


            $logs=[
                'user_id' => $noAnggota,
                'ip'      => $ip,
                'jenis_pencarian' => addslashes($_GET['action']),
                'keyword' => $ruas . " = " . $Keyword,
                'jenis_bahan' => $bahan,
                'url' => $url,
                'isLKD' => 0,
                'Field' => $ruas,
            ];
            //helper buat mencatat history pencarian di db
            OpacHelpers::opacLogs($logs);
            $fAuthor = ( isset($_GET['fAuthor']) ) ? addslashes(urldecode($_GET['fAuthor'])) : '';
            $fPublisher = ( isset($_GET['fPublisher']) ) ? addslashes(urldecode($_GET['fPublisher'])) : '';
            $fPublishLoc = ( isset($_GET['fPublishLoc']) ) ? addslashes(urldecode($_GET['fPublishLoc'])) : '';
            $fPublishYear = ( isset($_GET['fPublishYear']) ) ? addslashes(urldecode($_GET['fPublishYear'])) : '';
            $fSubject = ( isset($_GET['fSubject']) ) ? addslashes(urldecode($_GET['fSubject'])) : '';
            $fBahasa = ( isset($_GET['fBahasa']) ) ? addslashes(urldecode($_GET['fBahasa'])) : '';



            //ElasticHelper::CreateAllIndex();
            $query = new Query();
            $query->source('*');
            $query->from('*');
            //$query->query(['nested' => ['path' => 'subruas','query' => ['match' => ['subruas.Tag' => '245'],'match' => ['subruas.Tag' => '035'],'match' => ['subruas.RuasID' => '299784'],  ]]]);

            //$query->andfilterWhere(['IsOPAC' => 0]);

            //faced
            $query->addAggregation("Author", "terms", array("field"=>"Author",'size' => $FacedAuthorMax));
            $query->addAggregation("Publisher", "terms", array("field"=>"Publisher",'size' => $FacedPublisherMax));
            $query->addAggregation("PublishLocation", "terms", array("field"=>"PublishLocation",'size' => $FacedPublishLocationMax));
            $query->addAggregation("PublishYear", "terms", array("field"=>"PublishYear",'size' => $FacedPublisherMax));
            $query->addAggregation("Subject", "terms", array("field"=>"Subject",'size' => $FacedSubjectMax));
            $query->addAggregation("Languages", "terms", array("field"=>"Languages",'size' => $FacedBahasaMax));


            if ($bahan1 != 'Semua Jenis Bahan'){
                $query->andFilterWhere(['Worksheet_id' => $bahanid]);
            }
            // echo'<pre>';print_r($_GET);die;
            //check if user put some keyword, otherwise igonore it
            if ($Keyword){
                //check ruas
                if ($_GET['ruas']){
                    switch ($_GET['ruas']){
                        case 'Judul' :
                            $query->query([
                                'nested' =>
                                    [
                                        'path' => 'subruas',
                                        'query' => [
                                            'bool' => [
                                                //'minimum_should_match' => 0,
                                                'must' => [
                                                    'terms' => [
                                                        'subruas.Tag' => [240,245,246,440,740],
                                                    ],
                                                ],
                                                'filter' => [
                                                    'wildcard' => [
                                                        'subruas.Value' => '*'.strtolower($Keyword).'*',
                                                    ],
                                                ]
                                            ],

                                        ]
                                    ]
                            ]);
                            break;
                        case 'Pengarang' :
                            $query->query([
                                'nested' =>
                                    [
                                        'path' => 'subruas',
                                        'query' => [
                                            'bool' => [
                                                'must' => [
                                                    'terms' => [
                                                        'subruas.Tag' => [100,110,111,700,710,711,800,810,811],
                                                    ],
                                                ],
                                                'filter' => [
                                                    'wildcard' => [
                                                        'subruas.Value' => '*'.strtolower($Keyword).'*',
                                                    ],
                                                ]
                                            ],

                                        ]
                                    ]
                            ]);
                            break;
                        case 'Penerbit' :
                            $query->query([
                                'nested' =>
                                    [
                                        'path' => 'subruas',
                                        'query' => [
                                            'bool' => [
                                                'must' => [
                                                    'terms' => [
                                                        'subruas.Tag' => [260,264],
                                                    ],
                                                ],
                                                'filter' => [
                                                    'wildcard' => [
                                                        'subruas.Value' => '*'.strtolower($Keyword).'*',
                                                    ],
                                                ]
                                            ],

                                        ]
                                    ]
                            ]);
                            break;
                        case 'Subyek' :
                            $query->query([
                                'nested' =>
                                    [
                                        'path' => 'subruas',
                                        'query' => [
                                            'bool' => [
                                                'must' => [
                                                    'terms' => [
                                                        'subruas.Tag' => [600,610,611,650,651],
                                                    ],
                                                ],
                                                'filter' => [
                                                    'wildcard' => [
                                                        'subruas.Value' => '*'.strtolower($Keyword).'*',
                                                    ],
                                                ]
                                            ],

                                        ]
                                    ]
                            ]);
                            break;
                        case 'Nomor Panggil' :
                            $query->query([
                                'nested' =>
                                    [
                                        'path' => 'subruas',
                                        'query' => [
                                            'bool' => [
                                                'must' => [
                                                    'terms' => [
                                                        'subruas.Tag' => ['090','084'],
                                                    ],
                                                ],
                                                'filter' => [
                                                    'wildcard' => [
                                                        'subruas.Value' => '*'.strtolower($Keyword).'*',
                                                    ],
                                                ]
                                            ],

                                        ]
                                    ]
                            ]);
                            break;
                        case 'ISBN' :
                            $query->query([
                                'nested' =>
                                    [
                                        'path' => 'subruas',
                                        'query' => [
                                            'bool' => [
                                                'must' => [
                                                    'terms' => [
                                                        'subruas.Tag' => ['020'],
                                                    ],
                                                ],
                                                'filter' => [
                                                    'wildcard' => [
                                                        'subruas.Value' => '*'.strtolower($Keyword).'*',
                                                    ],
                                                ]
                                            ],

                                        ]
                                    ]
                            ]);
                            break;
                        case 'ISSN' :
                            $query->query([
                                'nested' =>
                                    [
                                        'path' => 'subruas',
                                        'query' => [
                                            'bool' => [
                                                'must' => [
                                                    'terms' => [
                                                        'subruas.Tag' => ['022'],
                                                    ],
                                                ],
                                                'filter' => [
                                                    'wildcard' => [
                                                        'subruas.Value' => '*'.strtolower($Keyword).'*',
                                                    ],
                                                ]
                                            ],

                                        ]
                                    ]
                            ]);
                            break;
                        case 'ISMN' :
                            $query->query([
                                'nested' =>
                                    [
                                        'path' => 'subruas',
                                        'query' => [
                                            'bool' => [
                                                'must' => [
                                                    'terms' => [
                                                        'subruas.Tag' => ['024'],
                                                    ],
                                                ],
                                                'filter' => [
                                                    'wildcard' => [
                                                        'subruas.Value' => '*'.strtolower($Keyword).'*',
                                                    ],
                                                ]
                                            ],

                                        ]
                                    ]
                            ]);
                            break;
                    }
                }
            }


            if($fAuthor){
                $query->andFilterWhere(['Author' => $fAuthor]);
            }
            if($fPublisher){
                $query->andFilterWhere(['Publisher' => $fPublisher]);
            }
            if($fPublishLoc){
                $query->andFilterWhere(['PublishLocation' => $fPublishLoc]);
            }
            if($fPublishYear){
                $query->andFilterWhere(['PublishYear' => $fPublishYear]);
            }
            if($fSubject){
                $query->andFilterWhere(['Subject' => $fSubject]);
            }
            if($fBahasa){
                $query->andFilterWhere(['Languages' => $fBahasa]);
            }

            $query->highlight(
                [
                    "pre_tags" => ['<em>'],
                    "post_tags" => ['</em>'],
                    'fields'=>[
                        'Title'=> new \stdClass()  //create empty object
                    ]
                ]
            );

            $elascount = $query->count();
            $pagination = new Pagination(['totalCount' => $elascount]);
            $pagination->setPageSize(10);
            $query->offset($pagination->offset)->limit($pagination->limit);



            $command = $query->createCommand();
            $rows = $command->search();

            // OpacHelpers::print__r($rows);
            //faced
            $aggregations =  $rows['aggregations'];


            foreach ($rows['hits']['hits'] as $key => $value){
                // echo'<pre>';print_r($value);die;
                $q = Yii::$app->db->createCommand("             SELECT  (SELECT ISSERIAL FROM worksheets WHERE id=CAT.Worksheet_id) ISSERIAL,
                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < NOW() || BookingExpiredDate IS NULL)) JML_BUKU,
                 (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                 (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                 FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL FROM catalogs CAT WHERE CAT.`ID`=".$value['_source']['ID'].";")->queryAll();


                $elasRes[$key]['CatalogId'] = $value['_source']['ID'];
                $elasRes[$key]['Publikasi'] = $value['_source']['Publikasi'];
                $elasRes[$key]['title'] = $value['_source']['Title'];
                $elasRes[$key]['author'] = $value['_source']['Author'];
                $elasRes[$key]['publisher'] = $value['_source']['Publisher'];
                $elasRes[$key]['PublishLocation'] = $value['_source']['PublishLocation'];
                $elasRes[$key]['PublishYear'] = $value['_source']['PublishYear'];
                $elasRes[$key]['SUBJECT'] = $value['_source']['Subject'];
                $elasRes[$key]['bahasa'] = $value['_source']['Languages'];
                $elasRes[$key]['CoverURL'] = $value['_source']['CoverURL'];
                $elasRes[$key]['CallNumber'] = $value['_source']['CallNumber'];
                $elasRes[$key]['worksheet_id'] = $value['_source']['Worksheet_id'];
                //$elasRes[$key]['worksheet'] = DirectoryHelpers::GetDirWorksheet($value['_source']['Worksheet_id']);
                $elasRes[$key]['worksheet'] = $value['_source']['worksheet_name'];
                $elasRes[$key]['ISSERIAL'] = $value['_source']['ISSERIAL'];
                $elasRes[$key]['JML_BUKU'] = $q[0]['JML_BUKU'];
                $elasRes[$key]['ALL_BUKU'] = $q[0]['ALL_BUKU'];
                $elasRes[$key]['KONTEN_DIGITAL'] = $q[0]['KONTEN_DIGITAL'];

                $elasRes[$key]['authOriginal'] =  array_values(array_filter(explode("|",OpacHelpers::sqlDetailOpac('PENGARANG',$elasRes[$key]['CatalogId']))));
                $elasRes[$key]['authModif'] = preg_replace("/\([^)]+\)/","",$elasRes[$key]['authOriginal']);
                $elasRes[$key]['keyword']=urldecode($_GET['katakunci']);

                $elasRes[$key]['title'] =  OpacHelpers::highlight($elasRes[$key]['title'],$elasRes[$key]['keyword']);

                //replace authoriginal with highlighed string
                foreach ($elasRes[$key]['authOriginal'] as $keys => &$values){
                    $values = OpacHelpers::highlight($values,$elasRes[$key]['keyword']);
                }

            }
            $dataFacedAuthor = $aggregations['Author']['buckets'];
            $dataFacedPublisher = $aggregations['Publisher']['buckets'];
            $dataFacedPublishLocation = $aggregations['PublishLocation']['buckets'];
            $dataFacedPublishYear = $aggregations['PublishYear']['buckets'];
            $dataFacedSubject = $aggregations['Subject']['buckets'];
            $dataFacedBahasa = $aggregations['Languages']['buckets'];


            //buat nyimpen session keranjang
            if (!isset($_SESSION['catID']) || $_SESSION['catID'] == '') {
                $_SESSION['catID'] = NULL;
            };
            if (!isset($_SESSION['catIDmerge']) || $_SESSION['catIDmerge'] == '') {
                $_SESSION['catIDmerge'] = NULL;
            };
            if (!isset($_POST['catID']) || $_POST['catID'] == '') {
                $_POST['catID'] = NULL;
            };
            if (!isset($_SESSION['catID']) || $_SESSION['catID'] == '') {
                $_SESSION['catID'] = NULL;
            };
            if (!isset($_SESSION['catIDmerge']) || $_SESSION['catIDmerge'] == '') {
                $_SESSION['catIDmerge'] = NULL;
            };
            if (!isset($_POST['catID']) || $_POST['catID'] == '') {
                $_POST['catID'] = NULL;
            };

            if (isset($_POST['action']) && $_POST['action'] == "keranjang" && isset($_POST['catID'])) {
                if (isset($_SESSION['catID'])) {

                    $temp = (is_array($_SESSION['catID']) ? $_SESSION['catID'] : array($_SESSION['catID']));
                    $duplicated = 0;
                    for ($i = 0; $i < sizeof($_POST['catID']); $i++) {
                        if (in_array($_POST['catID'][$i], $temp)) {
                            $duplicated+=1;
                        }
                    }
                    //menggabungkan catID di session dengan catID dari post//
                    $_SESSION['catID'] = array_unique(array_merge($temp, $_POST['catID']));

                    //pesan  ketika semua catalogID gagal dimasukkan ke keranjang
                    if (sizeof($_POST['catID']) == $duplicated) {
                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'danger',
                            'duration' => 3500,
                            'icon' => 'glyphicon glyphicon-ok-sign',
                            'message' => Yii::t('app', ' Katalog Gagal disimpan, Katalog sudah ada di dalam keranjang'),
                            'title' => 'Error',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                        $alert = TRUE;
                    } else
                    //pesan  ketika sebagian catalogID gagal dimasukkan ke keranjang
                    if ($duplicated != 0) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'info',
                            'duration' => 2500,
                            'icon' => 'glyphicon glyphicon-ok-sign',
                            'message' => Yii::t('app', (sizeof($_POST['catID']) - $duplicated) . ' Katalog berhasil disimpan di dalam keranjang ' . $duplicated . ' Katalog gagal disimpan'),
                            'title' => 'success',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                        $alert = TRUE;
                    }
                    //pesan ketika semua catalogID berhasil di masukkan ke keranjang
                    else {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'info',
                            'duration' => 2500,
                            'icon' => 'glyphicon glyphicon-ok-sign',
                            'message' => Yii::t('app', sizeof($_POST['catID']) . ' Katalog berhasil disimpan di dalam keranjang'),
                            'title' => 'success',
                            'positonY' => Yii::$app->params['flashMessagePositionY'],
                            'positonX' => Yii::$app->params['flashMessagePositionX']
                        ]);
                        $alert = TRUE;
                    }
                } else {
                    $_SESSION['catID'] = $_POST['catID'];
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 2500,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => Yii::t('app', sizeof($_POST['catID']) . ' Katalog berhasil disimpan di dalam keranjang'),
                        'title' => 'success',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
                    $alert = TRUE;
                }
                $gabung = implode(",", $_SESSION['catID']);
                $_SESSION['catIDmerge'] = $gabung;
            }

            if (!isset($dataSearch)) {
                $dataSearch = "";
            }
            // OpacHelpers::print__r($elasRes);
            return $this->render('resultListOpac', [
                'pages' => $pagination,
                'countResult' => count($elasRes),
                'dataResult' => $elasRes,
                'totalCountResult' => $elascount,
                'dataFacedAuthor' => $dataFacedAuthor,
                'dataFacedPublisher' => $dataFacedPublisher,
                'dataFacedPublishYear' => $dataFacedPublishYear,
                'dataFacedPublishLocation' => $dataFacedPublishLocation,
                'dataFacedSubject' => $dataFacedSubject,
                'dataFacedBahasa' => $dataFacedBahasa,
                'noAnggota' => $noAnggota,
                'alert' => $alert,
                'UsulanKoleksi' => $UsulanKoleksi,
                'booking' => $booking,
                'FacedAuthorMax' => $FacedAuthorMax,
                'FacedAuthorMin' => $FacedAuthorMin,
                'FacedPublisherMax' => $FacedPublisherMax,
                'FacedPublisherMin' => $FacedPublisherMin,
                'FacedPublishLocationMax' => $FacedPublishLocationMax,
                'FacedPublishLocationMin' => $FacedPublishLocationMin,
                'FacedPublishYearMax' => $FacedPublishYearMax,
                'FacedPublishYearMin' => $FacedPublishYearMin,
                'FacedSubjectMax' => $FacedSubjectMax,
                'FacedSubjectMin' => $FacedSubjectMin,
                'FacedBahasaMax' => $FacedBahasaMax,
                'FacedBahasaMin' => $FacedBahasaMin,
                'fAuthor' => $fAuthor,
                'fPublisher' => $fPublisher,
                'fPublishLoc' => $fPublishLoc,
                'fPublishYear' => $fPublishYear,
                'fSubject' => $fSubject,
                'fBahasa' => $fBahasa,
                'action' => $action,
                'bases' => Yii::$app->homeUrl,
            ]);
        }
        
        if (!isset($_SESSION['catID']) || $_SESSION['catID'] == '') {
            $_SESSION['catID'] = NULL;
        };
        if (!isset($_SESSION['catIDmerge']) || $_SESSION['catIDmerge'] == '') {
            $_SESSION['catIDmerge'] = NULL;
        };
        if (!isset($_POST['catID']) || $_POST['catID'] == '') {
            $_POST['catID'] = NULL;
        };

        if (isset($_POST['catID'])) {
            if (isset($_SESSION['catID'])) {
                $temp = $_SESSION['catID'];
                //menggabungkan catID di session dengan catID dari post//
                $_SESSION['catID'] = array_unique(array_merge($temp, $_POST['catID']));
            } else {
                $_SESSION['catID'] = $_POST['catID'];
            }

            $gabung = implode(",", $_SESSION['catID']);
            $_SESSION['catIDmerge'] = $gabung;
        }
        
        if ($request->isAjax && $_GET['action'] === "showCollection") {

            $catID = $_GET['catID'];
            if ($_GET['serial'] == 1) {
                $searchModel = new CollectionSearchKardeks;
                $params['CatalogId'] = $_GET['catID'];
                $dataProvider = $searchModel->search2($params);
                return $this->renderAjax('_serial', [
                            'catID' => $catID,
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                ]);
            }


            $sqlCollectionList = "CALL showCollectionOpac(" . $catID . ");";

            $dataProviderCollectionList = new SqlDataProvider([
                'sql' => $sqlCollectionList,
                'pagination' => false,
                    //'pagination' => [ 'pageSize' => 20,],
            ]);

            $modelCollectionList = $dataProviderCollectionList->getModels();
            $countCollectionList = $dataProviderCollectionList->getCount();
            $temp = 1;
            foreach ($modelCollectionList as $value) {
                $dataCollectionList[$temp] = $value;
                $temp++;
            }
            if (!isset($dataCollectionList)) {
                $dataCollectionList = "";
            }


            return $this->renderAjax('_collectionlist', [

                        'dataProviderCollectionList' => $dataProviderCollectionList,
                        'countCollectionList' => $countCollectionList,
                        'dataCollectionList' => $dataCollectionList,
                        'noAnggota' => $noAnggota,
                        'catID' => $catID
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "showArticle") {
            /*$catID = $_GET['catID'];
            $hasilSearch = Yii::$app->db->createCommand("SELECT * FROM serial_articles WHERE Catalog_id =".$catID." ")->queryAll();

            return $this->renderAjax('_articleList', [

                'hasilSearch' => $hasilSearch,
                'noAnggota' => $noAnggota,
                'catID' => $catID
            ]);*/
            $catID = $_GET['catID'];
            $searchModel = new SerialArticlesSearch;
            $params['Catalog_id'] = $_GET['catID'];
            $dataProvider = $searchModel->advancedSearchByCatalogId($params,$rules=null);
            return $this->renderAjax('_serialArticle', [
                'catID' => $catID,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "logDownload") {

            OpacHelpers::logsDownload($_GET['ID'],$noAnggota,'0');          
        }
        if ($request->isAjax && $_GET['action'] === "showKontenDigital") {
            $catID = $_GET['catID'];
            $sqlCollectionList = "CALL showKontenDigital(" . $catID . "); ";

            $dataProviderCollectionList = new SqlDataProvider([
                'sql' => $sqlCollectionList,
                //'pagination'=> false,
                'pagination' => [ 'pageSize' => 1,],
            ]);

            $modelCollectionList = $dataProviderCollectionList->getModels();
            $countCollectionList = $dataProviderCollectionList->getCount();
            $temp = 1;
            foreach ($modelCollectionList as $value) {
                $dataCollectionList[$temp] = $value;
                $temp++;
            }
            if (!isset($dataCollectionList)) {
                $dataCollectionList = "";
            }

            return $this->renderAjax('_kontendigitallist', [

                        'dataProviderCollectionList' => $dataProviderCollectionList,
                        'countCollectionList' => $countCollectionList,
                        'dataCollectionList' => $dataCollectionList,
                        'noAnggota' => $noAnggota,
                        'catID' => $catID,
            ]);
        }
        if ($request->isAjax && $_GET['action'] === "boooking") {

            if (Yii::$app->user->isGuest) {
                return $this->redirect('../keanggotaan/site/login');
            }
            $colID = $_GET['colID'];
            $cekBooking = OpacHelpers::cekBooking($noAnggota,$colID);       
            $noAnggota = \Yii::$app->user->identity->NoAnggota;
            $dateNow = new \DateTime("now");
            $dateAdd = new \DateTime("now");
            $bookingTime=OpacHelpers::SetBookingTime($bookExp);
            /*$tambahJam= explode(":",$bookExp);


            $dateAdd->modify("+".$tambahJam[0]." hours +".$tambahJam[1]." minutes +".$tambahJam[2]." seconds");*/

            if (!$cekBooking) {
                
                    $modelLogs = new Bookinglogs;
                    $modelLogs->memberId = $noAnggota;
                    $modelLogs->collectionId = $colID;
                    $modelLogs->bookingDate = $dateNow->format("Y-m-d H:i:sO");
                    $modelLogs->bookingExpired = $bookingTime->format("Y-m-d H:i:sO");
                    $modelLogs->save();
                    
                    $params2 = [':ID' => $colID, ':BookingMemberID' => $noAnggota, ':BookingExpiredDate' => $bookingTime->format("Y-m-d H:i:sO")];
                    $command = Yii::$app->db->createCommand("UPDATE collections SET BookingMemberID=:BookingMemberID, BookingExpiredDate=:BookingExpiredDate WHERE ID=:ID;");
                    $command->bindValues($params2);
                    $command->execute();

                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'info',
                        'duration' => 2500,
                        'icon' => 'glyphicon glyphicon-ok-sign',
                        'message' => Yii::t('app', 'Berhasil Booking'),
                        'title' => 'success',
                        'positonY' => Yii::$app->params['flashMessagePositionY'],
                        'positonX' => Yii::$app->params['flashMessagePositionX']
                    ]);
            } else {
                $pesan=implode(",", $cekBooking);
                    Yii::$app->getSession()->setFlash('error', [
                    'type' => 'danger',
                    'delay' => 3500,
                    'icon' => 'glyphicon glyphicon-remove',
                    'message' => Yii::t('app', '  Gagal Booking, '.$pesan),
                    'title' => 'Gagal',
                    'body' => 'This is a successful growling alert.',
                    'positonY' => 'top',
                    'positonX' => 'right'
                ]);
                
            }

            
            return $this->renderAjax('alert', [
                        'booking' => $booking,

            ]);
        }
        if ($request->isAjax && $_GET['action'] === "search") {
            $catID = $_GET['catID'];
            $pos  = $_GET['pos'];
            $sqlSearch = "
                SELECT CAT.id CatalogId,CAT.title kalimat2,CAT.author,CAT.publisher,CAT.PublishLocation,CAT.PublishYear,CAT.subject,CAT.CoverURL ,CAT.Worksheet_id, 
                (SELECT NAME FROM worksheets WHERE id=CAT.Worksheet_id) worksheet,
                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID AND STATUS_ID=1 AND (BookingExpiredDate < now() || BookingExpiredDate is null)) JML_BUKU,
                (SELECT COUNT(1) FROM collections WHERE CATALOG_ID=CAT.ID) ALL_BUKU,
                (SELECT GROUP_CONCAT(DISTINCT SUBSTR(fileURL,INSTR(fileURL, '.')+1) SEPARATOR ', ') 
                FROM catalogfiles WHERE Catalog_id = CAT.ID) KONTEN_DIGITAL
                
                FROM catalogs CAT JOIN collections col ON col.Catalog_id = CAT.ID
                 WHERE 
                   CAT.isopac=1 AND
                    CAT.ID=" . $catID . ";


                ";
            
            $dataProviderSearch = new SqlDataProvider([
                'sql' => $sqlSearch,
                'pagination' => false,
            ]);

            $modelSearch = $dataProviderSearch->getModels();
            $countSearch = $dataProviderSearch->getCount();

            $temp = 1;
            foreach ($modelSearch as $value) {
                $dataSearch[$temp] = $value;
                $dataTagRDA        = OpacHelpers::getTaginfo($dataSearch[$temp]['CatalogId'],'336,338','a');
                $jenisBahanRDA     = OpacHelpers::jenisBahanRDA($dataTagRDA);
                $jenis_bahanold    = $dataSearch[$temp]['worksheet'];
                $dataSearch[$temp]['worksheet'] = $jenis_bahanold." ".$jenisBahanRDA;
                $temp++;
            }

            $dateNow = new \DateTime("now");

            return $this->renderAjax('_search', [
                        'dataResult' => $dataSearch,
                        'booking' => $booking,
                        'i' => $pos,
            ]);
        }
function is_connected()
{
    $connected = @fsockopen("ip-api.com", 80); 
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;

}

if(is_connected())
{	
          $ip = \common\components\OpacHelpers::getip();		
        $client = new Client(['baseUrl' => 'http://ip-api.com/json/']);
		  $response = $client->get($ip)->send();
		  
		  if ($response->isOk) {
              $data = json_decode($response->content, true);
              if(Yii::$app->config->get('IsHitCounterOpac') == '0'){
                  $check = OpacCounter::find()->where(['ip_address' => $data['query'], 'DATE(create_at)' => date('Y-m-d')])->count();
				  if(empty($check)){
                      $model = new OpacCounter();
                      $model->ip_address = $data['query'];
                      $model->city = $data['city'];
                      $model->region_name = $data['regionName'];
                      $model->country = $data['country'];
                      $model->lat = $data['lat'];
                      $model->long = $data['lon'];
                      $model->save(false);
                  }
                  return $this->render('index', [
                              'modelTC' => $modelTC,
                              'modelNC' => $modelNC,
                              'modelUnggul' => $modelUnggul,
                              'settingNC' => $settingNC,
                              'settingTC' => $settingTC,
                              'settingUnggul' => $settingUnggul,
                  ]);
              }
			  
              $checkOpacCounter = \common\components\OpacHelpers::tableExist('opac_counter');
			  if($checkOpacCounter !== 0){
                  $model = new OpacCounter();
                  $model->ip_address = $data['query'];
                  $model->city = $data['city'];
                  $model->region_name = $data['regionName'];
                  $model->country = $data['country'];
                  $model->lat = $data['lat'];
                  $model->long = $data['lon'];
                  $model->save(false);
              
			  }
              
              // $model->create_at = $data['lon'];
          }
}		

        return $this->render('index');
    }

    public function actionUsulan() {
        if (Yii::$app->user->isGuest) {
            $noAnggota = $_POST['formData']['NomorAnggota'];
        } else {
            $noAnggota = \Yii::$app->user->identity->NoAnggota;
        }


        $model = new requestcatalog;
        //$model->MemberID = $noAnggota;
        //$model->WorksheetID = 1;
        $model->WorksheetID = $_POST['formData']['JenisBahan'];
        $model->Title = $_POST['formData']['Judul'];
        $model->Author = $_POST['formData']['Pengarang'];
        $model->PublishLocation = $_POST['formData']['KotaTerbit'];
        $model->Publisher = $_POST['formData']['Penerbit'];
        $model->PublishYear = $_POST['formData']['TahunTerbit'];
        $model->Comments = $_POST['formData']['Keterangan'];
        $model->save(false);


        Yii::$app->getSession()->setFlash('success', [
            'type' => 'info',
            'delay' => 2500,
            'icon' => 'glyphicon glyphicon-remove',
            'message' => Yii::t('app', '  Data Berhasil Disimpan '),
            'title' => 'Sukses',
            'body' => 'This is a successful growling alert.',
            'positonY' => Yii::$app->params['flashMessagePositionY'],
            'positonX' => Yii::$app->params['flashMessagePositionX']
        ]);
        return $this->renderAjax('_usulan', [
                        
        ]);
    }


}
