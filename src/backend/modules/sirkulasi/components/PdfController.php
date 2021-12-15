<?php
/**
 * @link https://www.inlislite.perpusnas.go.id/
 * @copyright Copyright (c) 2015 Perpustakaan Nasional Republik Indonesia
 * @license https://www.inlislite.perpusnas.go.id/licences
 */

namespace backend\modules\sirkulasi\components;

use Yii;
use yii\web\Controller;
use PHPPdf\Core\FacadeBuilder;
use PHPPdf\DataSource\DataSource;
use Zend\Barcode\Barcode as Zend_Barcode;


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

    public function afterAction($action,$result)
    {
        return parent::afterAction($action,$result);
    }

    public function renderPdf($view, $data = null, $return = false)
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
            //$content = $facade->render($this->renderPartial($viewPath, $data, true), $this->styleSheets ? DataSource::fromString($this->renderPartial($this->styleSheets, $data, true)) : null);
            $content = $facade->render($this->renderFile("{$viewPath}/$view.xml", $data, true), $this->styleSheets ? DataSource::fromString($this->renderFile("$viewPath/{$this->styleSheets}.xml", $data, true)) : null);
        } catch (Exception $e) {
            throw $e;
        }
        if (!$return) {

            header('Content-Type: application/pdf');
//            header('Content-Disposition: attachment; filename="' . $this->action->id . '?' . Yii::app()->request->queryString . '"');
            echo $content;
            return;
        }
        return $content;
    }

    

}

?>
