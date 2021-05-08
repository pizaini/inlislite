<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */

namespace backend\modules\member\controllers;

use Yii;
use yii\web\Controller;
use PHPPdf\Core\FacadeBuilder;
use PHPPdf\DataSource\DataSource;
use Zend\Barcode\Barcode as Zend_Barcode;
use common\components\MemberHelpers;

use common\models\Members;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PdfController
 *
 * @author Henry <alvin_vna@yahoo.com>
 */
class PdfController extends Controller
{

    public $styleSheets;

    /*public function afterAction($action)
    {
        return parent::afterAction($action);
    }*/

    public function renderPdf($view, $data = null, $return = false)
    {
        //$loader = new \PHPPdf\Core\Configuration\LoaderImpl();
        //$loader->setFontFile(Yii::getAlias('@uploaded_files').'/aplikasi/fonts/fonts.xml'); //there are setFontFile, setNodeFile, setComplexAttributeFile and setColorFile methods

        $facade = FacadeBuilder::create()
            ->setEngineType('pdf')
            ->setEngineOptions(array(
                'format' => 'jpg',
                'quality' => 120,
                'engine' => 'gd',
            ))
            ->build();
        
             
    
        $viewPath = $this->viewPath;
        try {
            $content = $facade->render($this->renderFile("{$viewPath}/$view.xml", $data, true), $this->styleSheets ? DataSource::fromString($this->renderFile("$viewPath/{$this->styleSheets}.xml", $data, true)) : null);
        } catch (Exception $e) {
            throw $e;
        }
        if (!$return) {

            header('Content-Type: application/pdf');
            //header('Content-Disposition: attachment; filename='.$filename);
            //header('Content-Disposition: attachment; filename="' . $this->action->id . '?' . Yii::$app->request->queryString . '"');
            echo $content;
            return;
        }
        return $content;
    }


    public function renderPdfDownload($view,$filename, $data = null, $return = false)
    {
        $facade = FacadeBuilder::create()
            ->setEngineType('pdf')
            ->setEngineOptions(array(
                'format' => 'jpg',
                'quality' => 120,
                'engine' => 'gd',
            ))
            ->build();
        $viewPath = $this->viewPath;
        try {
             $content = $facade->render($this->renderFile("{$viewPath}/$view.xml", $data, true), $this->styleSheets ? DataSource::fromString($this->renderFile("$viewPath/{$this->styleSheets}.xml", $data, true)) : null);
        } catch (Exception $e) {
            throw $e;
        }
        if (!$return) {

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename='.$filename);
            echo $content;
            return;
        }
        return $content;
    }

    /**
     * For Report Member Card
     * @param int $id
     */
    public function actionKartuAnggota($id)
    {
        

        $layout_kartu = '';
        $model = $this->loadModel($id);
        $this->styleSheets = '';
        $separator = DIRECTORY_SEPARATOR;
        $backImage = Yii::getAlias('@uploaded_files') . "{$separator}settings{$separator}kartu_anggota{$separator}bg_cardmember".Yii::$app->config->get('KartuAnggota').".png";
        //$image = Yii::getAlias('@uploaded_files') . "{$separator}foto_anggota{$separator}temp{$separator}$id.jpg";
        $image = Yii::getAlias('@uploaded_files') . "{$separator}foto_anggota{$separator}$id.jpg";

        if (!realpath($image)) {
		$image=Yii::getAlias('@uploaded_files') . "{$separator}foto_anggota{$separator}temp{$separator}nophoto.jpg";
        }
	  

       if(Yii::$app->config->get('KartuAnggota') == 1){
         $layout_kartu = 'kartuAnggota';
       }elseif(Yii::$app->config->get('KartuAnggota') == 2){
          $layout_kartu = 'kartuAnggota2';
       }elseif(Yii::$app->config->get('KartuAnggota') == 3){
          $layout_kartu = 'kartuAnggota3';
       }elseif(Yii::$app->config->get('KartuAnggota') == 4){
          $layout_kartu = 'kartuAnggota4';
       }else{

       }    
         $layout_kartu = 'kartuAnggota';
         $data = array(
                'backImage' => $backImage,
                'imageMember' => $image,
            );
	   $this->renderPdf($layout_kartu, compact('model', 'data'));

    }



    public function loadModel($id)
    {
        $model = Members::findOne($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * For Report Member Card
     * @param int $id
     */
    public function actionRenderKartuAnggota($id)
    {

        $LocID = \common\models\LocationLibrary::findOne(Yii::$app->location->get());
         $data = array(
            'id'=>$id,
            'LocID'=>['0'=>$LocID->Name],
        );
        // $data = array(
        //     'idxx'=>$id,
        // );

       $layout_kartu = 'kartuAnggotaAll';
       if(Yii::$app->config->get('KartuAnggota') == 1){
         $layout_kartu = 'kartuAnggotaAll';
       }elseif(Yii::$app->config->get('KartuAnggota') == 2){
          $layout_kartu = 'kartuAnggotaAll_2';
       }elseif(Yii::$app->config->get('KartuAnggota') == 3){
          $layout_kartu = 'kartuAnggotaAll_3';
       }elseif(Yii::$app->config->get('KartuAnggota') == 4){
          $layout_kartu = 'kartuAnggotaAll_4';
       }   
       
        $this->renderPdfDownload($layout_kartu,'KartuAnggota.pdf', compact('data'));

    }

    /**
     * For Report Member Card
     * @param int $id
     */
    public function actionKartuBelakangAnggota()
    {

     $this->renderPdfDownload('kartuBelakangAnggota','KartuBelakangAnggota.pdf', compact('data'));
       
        // $this->renderPdfDownload($layout_kartu,'KartuAnggota.pdf', compact('data'));

    }

     /**
     * For Report Member Card
     * @param int $id
     */
    public function actionRenderKartuAnggotaA4($id)
    {

        $data = array(
            'id'=>$id,
        );

        $this->renderPdfDownload('kartuAnggotaA4','KartuAnggota.pdf', compact('data'));

    }

    public function actionKartuAnggotaAll($tipe)
    {
        $session = Yii::$app->session;
        $LocID = \common\models\LocationLibrary::findOne(Yii::$app->location->get());

        $id = $session->get('cetak-kartu-all');
        if($tipe == 1){
            $this->actionRenderKartuAnggota($id);
        }else{

            $barcode = $this->renderAjax('_barcode',['nomor'=>'123456789011']);
            

             if(Yii::$app->config->get('KartuAnggota') == 1){
                $layout_kartu = '_pdf';
                }elseif(Yii::$app->config->get('KartuAnggota') == 2){
                  $layout_kartu = '_pdf2';
               }elseif(Yii::$app->config->get('KartuAnggota') == 3){
                  $layout_kartu = '_pdf3';
               }elseif(Yii::$app->config->get('KartuAnggota') == 4){
                  $layout_kartu = '_pdf4';
               }  

            $html = $this->renderAjax($layout_kartu,['id'=>$id,'LocID'=>$LocID->Name]);
            
            $mpdf=new \mPDF('c','A4','','' , 10 , 10 , 10 , 10 , 10 , 10);  
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->list_number_suffix = ')';
            //$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
            $mpdf->WriteHTML($html);
            //$mpdf->SetJS('print();');
            //$mpdf->Output();
            $mpdf->Output('KartuAnggota.pdf','D');

            //return $html;
            //return $this->renderPartial('_pdf',['id'=>$id]);
            exit;
            //$this->actionRenderKartuAnggotaA4($id);
        }
        //Hapus setelah dicetak
        //$session->remove('cetak-kartu-all');

       return true;
    }


    public function actionKartuAnggotaSatuan($tipe,$id)
    {
        //$session = Yii::$app->session;
        $LocID = \common\models\LocationLibrary::findOne(Yii::$app->location->get());
         $data = array(
            'id'=>['0'=>$id],
            'LocID'=>['0'=>$LocID->Name],
        );

        
        if($tipe == 1){
            $this->actionRenderKartuAnggota($id);
        }else{

            $barcode = $this->renderAjax('_barcode',['nomor'=>'123456789011']);
            

               $layout_kartu = 'kartuAnggotaAll';
               if(Yii::$app->config->get('KartuAnggota') == 1){
                 $layout_kartu = 'kartuAnggotaAll';
               }elseif(Yii::$app->config->get('KartuAnggota') == 2){
                  $layout_kartu = 'kartuAnggotaAll_2';
               }elseif(Yii::$app->config->get('KartuAnggota') == 3){
                  $layout_kartu = 'kartuAnggotaAll_3';
               }elseif(Yii::$app->config->get('KartuAnggota') == 4){
                  $layout_kartu = 'kartuAnggotaAll_4';
               }   

            $this->renderPdfDownload($layout_kartu,'KartuAnggota.pdf', compact('data'));
        }
        //Hapus setelah dicetak
        //$session->remove('cetak-kartu-all');

       return true;
    }

    public function actionCetakBebasPustaka($id,$tipe){


        $model = \common\models\Members::findOne($id);
        if($model->StatusAnggota_id == 6){
            // Initalize the TBS instance
            $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
            if($tipe == '1'){
                $template = Yii::getAlias('@uploaded_files').'/templates/surat_bebas_pustaka/model1.odt';
            }else{
                $template = Yii::getAlias('@uploaded_files').'/templates/surat_bebas_pustaka/model2.odt';
            }

            $OpenTBS->LoadTemplate($template);
            $data = [];
            $no=1;
            //foreach ($id as $key => $value){
              

                $data[] = [
                    'no'=>$no++,
                    'nama'=>$model->Fullname,
                    'pekerjaan'=>is_null($model->job->Pekerjaan)? ' ': $model->job->Pekerjaan,
                    'identity'=>is_null($model->identityType->Nama) ? ' ' :  $model->identityType->Nama,
                    'identityno'=>is_null($model->IdentityNo)? ' ' : $model->IdentityNo,
                    'fakultas'=>is_null($model->fakultas->Nama)? ' ' : $model->fakultas->Nama,
                    'kelas'=>is_null($model->kelas->namakelassiswa) ? ' ' : $model->kelas->namakelassiswa,
                    'address'=>is_null($model->Address)? ' ' : $model->Address,
                    'propkab'=>$model->Province . ', '. $model->City,
                    'nama_perpustakaan'=>Yii::$app->config->get('NamaPerpustakaan'),
                    'lokasi_perpustakaan'=>is_null($model->job->Pekerjaan)? ' ': $model->job->Pekerjaan,
                    'now'=>date('d-M-Y'),

                ];
                                       
            //}
            $OpenTBS->MergeBlock('a,b', $data);

            $OpenTBS->Show(OPENTBS_DOWNLOAD, 'surat_bebas_pustaka_'.$model->Fullname.'.odt');

        }else{

            Yii::$app->getSession()->setFlash('error', [
                                'type' => 'warning',
                                'duration' => 3000,
                                'icon' => 'fa fa-info-circle',
                                'message' => Yii::t('app','Status Anggota '. $model->Fullname .' Belum Menjadi Bebas Pustaka, Mohon dirubah terlebih dahulu.'),
                                'title' => 'Info',
                                'positonY' => Yii::$app->params['flashMessagePositionY'],
                                'positonX' => Yii::$app->params['flashMessagePositionX']]);

            $this->redirect(['/member/member/update','id'=>$id]);
        }

        return true;
    }

    

}

?>
