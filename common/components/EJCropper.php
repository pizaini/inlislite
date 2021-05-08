<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package helpers
 * @version 1.0.0
 * @author Henry <alvin_vna@yahoo.com>
 */

namespace common\components;
/**
 * Base class.
 */
class EJCropper
{
	/**
	 * @var integer JPEG image quality
	 */
	public $jpeg_quality = 100;
	/**
	 * @var integer PNG compression level (0 = no compression).
	 */
	public $png_compression = 5;
	/**
	 * @var integer The thumbnail width
	 */
	public $targ_w = 100;
	/**
	 * @var integer The thumbnail height
	 */
	public $targ_h = 100;
	/**
	 * @var string The path for saving thumbnails
	 */
	public $thumbPath;

	/**
	 * Get the cropping coordinates from post.
	 * 
	 * @param type $attribute The model attribute name used.
	 * @return array Cropping coordinates indexed by : x, y, h, w
	 */
	public function getCoordsFromPost($attribute)
	{
		$coords = array('x' => null, 'y' => null, 'h' => null, 'w' => null);
		foreach ($coords as $key => $value) {
			$coords[$key] = $_POST[$attribute . '_' . $key];
		}
		return $coords;
	}

	/**
	 * Crop an image and save the thumbnail.
	 * 
	 * @param string $src Source image's full path.
	 * @param array $coords Cropping coordinates indexed by : x, y, h, w
	 * @return string $thumbName Path of thumbnail.
	 */
	public function crop($src, array $coords)
	{
		if (!$this->thumbPath) {
			throw new CException(__CLASS__ . ' : thumbpath is not specified.');
		}
		$file_type = pathinfo($src, PATHINFO_EXTENSION);
		$thumbName = $this->thumbPath . '/' . pathinfo($src, PATHINFO_BASENAME);

		if ($file_type == 'jpg' || $file_type == 'jpeg') {
			$img = imagecreatefromjpeg($src);
		}
		elseif ($file_type == 'png') {
			$img = imagecreatefrompng($src);
		}
		else {
			return false;
		}
		
		$dest_r = imagecreatetruecolor($coords['w'], $coords['h']);
		if (!imagecopyresampled($dest_r, $img, 0, 0, $coords['x'], $coords['y'], $coords['w'], $coords['h'], $coords['w'], $coords['h'])) {
			return false;
		}
		
		//$dest_r = imagecreatetruecolor($this->targ_w, $this->targ_h);
		//if (!imagecopyresampled($dest_r, $img, 0, 0, $coords['x'], $coords['y'], $this->targ_w, $this->targ_h, $coords['w'], $coords['h'])) {
		//	return false;
		//}
		// save only png or jpeg pictures
		if ($file_type == 'jpg' || $file_type == 'jpeg') {
			imagejpeg($dest_r, $thumbName, $this->jpeg_quality);
		}
		elseif ($file_type == 'png') {
			imagepng($dest_r, $thumbName, $this->png_compression);
		}

		/*$targ_w = $targ_h = 150;
		$jpeg_quality = 90;

		//$source = $src;
		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
		$targ_w,$targ_h,$_POST['w'],$_POST['h']);

		//header('Content-type: image/jpeg');
		imagejpeg($dst_r,$thumbName,$jpeg_quality);*/

		//exit;
		return $thumbName;
	}

}