<?php

/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package helpers
 * @version 1.0.0
 * @author Henry <alvin_vna@yahoo.com>
 */

namespace common\components;


class HtmlString {
    //put your code here

    /**
     * 
     * @param type $FieldToShow data field yang ditampilkan 
     * @param type $Href tujuan href daril link
     * @param type $FieldToBInd memanggil data berdasar kan field apa
     * @return type string
     */
    public static function RowDetil($FieldToShow, $Href, $FieldToBInd) {
        return 'CHtml::link($data->' . $FieldToShow . ',array("' . $Href . '",' . $FieldToBInd . '))';
    }
    
    /**
     * 
     * @param type $format format hasil tanggal yang diinginkan ex: d-m-Y.
     * @param type $data , data tanggal di cgridview yang ingin diubah formatnya.
     * @return type string.
     */
    public static function ToDate($format,$data){
        return 'Yii::app()->dateFormatter->format("'.$format.'",strtotime('.$data.'))';
    }
    
    public static function DateNow($format){
//        $now  = new CDbExpression("NOW()");
//        $dates   = new DateTime();
//        $dateNow = $dates->format('Ymd');
//        return 'Yii::app()->dateFormatter->format("'.$format.'",strtotime('.$dateNow.'))';
        return '"dsd"';
    }
    
    /**
     * create ajax error result;
     * @return string
     */
    public static function AjaxErrorResponse($divId,$addScript=""){
          return 'function (xhr, ajaxOptions, thrownError) {
            $("#'.$divId.'").html("<span style=\'background:#F2DEDE;padding:10px;position:relative;display:block;\'><b><font color=\'red\'>Error Code : "+xhr.status+" "+thrownError+"</font><br/>Reason : </b>"+xhr.responseText+"</span>");'.$addScript.'
            $("#DivKoleksi").hide();
            clearInterval(syncRfid);
            $("#BtStopRFID").hide();
            $("#BtSaveRFID").hide();
            $("#BtRFIDActivate").attr("disabled", "disabled");
            $("#BtRFIDActivate").show();

            }';
    }
    
    /**
     * create write single cookie
     * @return yii_cookie_void_method Write New Cookie
     */
    public static function WriteCookie($name,$value){
        $expire     = time()+60*60*24*1;
//        $expire     = time()+(-1*(60*60*24*1));
        $options    = array('expire'=>$expire,);
        Yii::app()->request->cookies[$name] = new CHttpCookie($name, $value, $options);
    }
    
    /**
     * create an array cookies
     * @example : $data = array("1"=>"Satu","2"=>"Dua","3"=>"Tiga"); HtmlString::WriteCookies("Tes", $data);
     * @return yii_cookie_void_method Write New Cookies With Array
     */
    public static function WriteCookies($name, $data){
        reset($data);
        while (list($id, $val) = each($data))
        {
            HtmlString::WriteCookie($name."[$id]", $val);
        }
    }
    
    
    
    /**
     * read a single cookies
     * @return string
     */
    public static function ReadCookie($name){
        $value = Yii::app()->request->cookies->contains($name) ?
        Yii::app()->request->cookies[$name]->value : '';
        return $value;
    }
    
    /**
     * read an array cookies
     * @example $data = HtmlString::ReadCookies("Tes"); echo $data['a'];
     * @return array
     */
    public static function ReadCookies($CookieName,$fillEmpty=true){
        if (Yii::app()->request->cookies->contains($CookieName)) {
            foreach (Yii::app()->request->cookies[$CookieName]->value as $name => $value) {
                $return[$name]=$value;
            }
        }
        else {
            if ($fillEmpty===true) $return=NULL;
            else throw new CHttpException(404, 'Cookie Belum DiSet.');
        }
        return $return;
    }
    
    public static function DateToMysqlFormat($dates){
        $date2 = explode("-", "$dates");
        return $date2[2]."-".$date2[1]."-".$date2[0];
    }
    
    public static function DateRange($today,$lastday){
        return  (strtotime($today)-strtotime($lastday))/(60*60*24) < 0 ? 0 : (strtotime($today)-strtotime($lastday))/(60*60*24);
        
    }
    
    public static function RedCell($c){
        $color = (strtotime(date("Y-m-d"))-strtotime($c))/(60*60*24) > 0 ? "red" : "";
        return $color;
    }

     public static function DateToDisplayFormat($dates){
        //YYYY-MM-DD
        $date2 = explode("-", "$dates");
        return $date2[2]."-".$date2[1]."-".$date2[0];
    }
}

?>  
