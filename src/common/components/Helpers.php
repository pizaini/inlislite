<?php
/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2015
 * @package helpers
 * @version 1.0.0
 * @author Henry <alvin_vna@yahoo.com>
 */

namespace common\components;
use yii;

class Helpers
{
    const DATE_FORMAT = 'php:Y-m-d';
    const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const TIME_FORMAT = 'php:H:i:s';

    public static function terbilang($x=1){
            if($x>2147483647) return $x;
            $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
            if ($x < 12)    return " " . $abil[$x];
            elseif ($x < 20)    return self::terbilang($x - 10) . " Belas";
            elseif ($x < 100)   return self::terbilang($x / 10) . " Puluh" . self::terbilang($x % 10);
            elseif ($x < 200)   return " Seratus" . self::terbilang($x - 100);
            elseif ($x < 1000)  return self::terbilang($x / 100) . " Ratus" . self::terbilang($x % 100);
            elseif ($x < 2000)  return " Seribu" . self::terbilang($x - 1000);
            elseif ($x < 1000000)   return self::terbilang($x / 1000) . " Ribu" . self::terbilang($x % 1000);
            elseif ($x < 1000000000)    return self::terbilang($x / 1000000) . " Juta" . self::terbilang($x % 1000000);
            elseif ($x < 1000000000000) return self::terbilang($x / 1000000000) . " Milyar" . self::terbilang($x % 1000000000);
        }

    public static function convertDate($dateStr, $type='date', $format = null) {
        if ($type === 'datetime') {
              $fmt = ($format == null) ? self::DATETIME_FORMAT : $format;
        }
        elseif ($type === 'time') {
              $fmt = ($format == null) ? self::TIME_FORMAT : $format;
        }
        else {
              $fmt = ($format == null) ? self::DATE_FORMAT : $format;
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }

    public static function collapseSpaces($value)
    {
        return preg_replace('/\s+/', ' ',$value);
    }

    public static function DateToMysqlFormat($tipe = '-',$dates){
        $date2 = explode($tipe, "$dates");
        return $date2[2]."-".$date2[1]."-".$date2[0];
    }

    public static function DateTimeToViewFormat($dates){
        $date = date_create($dates);
        return date_format($date, 'd-m-Y');
    }
    
    /**
     * Format DateTime database ke format datetime indonesia.
     * @param type $dates
     * @return type
     */
    public static function DateTimeIndonesiaFormat($dates){
        $date = date_create($dates);
        return date_format($date, 'd-m-Y H:m:s');
    }

    public static function addDayswithdate($date,$days){

        if($days == ''):
          $days = '0';
        endif;
        
        $data2 = Yii::$app->db->createCommand("SELECT DATE_ADD('".$date."', INTERVAL ".$days." DAY) AS 'hasil'")->queryOne(); 
        //$date = strtotime("+".$days." days", strtotime($date));
        // $date2 = strtotime($date. ' +'.$days.' days');
        // print_r($date);die;
        return  $data2['hasil'];
        // return  date("Y-m-d", $date2);

    }

    public static function addMonthWithDate($date,$months){

        $date = strtotime("+".$months." months", strtotime($date));
        return  date("Y-m-d", $date);

    }

    public static function addYearWithDate($date,$year){

        $date = strtotime("+".$year." years", strtotime($date));
        return  date("Y-m-d", $date);

    }

    public static function getDays($sStartDate, $sEndDate){  
      // Firstly, format the provided dates.  
      // This function works best with YYYY-MM-DD  
      // but other date formats will work thanks  
      // to strtotime().  
      $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));  
      $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));  
      
      // Start the variable off with the start date  
      $aDays[] = $sStartDate;  
      
      // Set a 'temp' variable, sCurrentDate, with  
      // the start date - before beginning the loop  
      $sCurrentDate = $sStartDate;  
      
      // While the current date is less than the end date  
      while($sCurrentDate < $sEndDate){  
        // Add a day to the current date  
        $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  
      
        // Add this new day to the aDays array  
        $aDays[] = $sCurrentDate;  
      }  
      
      // Once the loop has finished, return the  
      // array of days.  
      return $aDays;  
    }  

    /**
     *
     * @create a roman numeral from a number
     *
     * @param int $num
     *
     * @return string
     *
     */
    public static function romanNumerals($num) {
        $n = intval($num);
        $res = '';

        /*         * * roman_numerals array  ** */
        $roman_numerals = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1);

        foreach ($roman_numerals as $roman => $number) {
            /*             * * divide to get  matches ** */
            $matches = intval($n / $number);

            /*             * * assign the roman char * $matches ** */
            $res .= str_repeat($roman, $matches);

            /*             * * substract from the number ** */
            $n = $n % $number;
        }

        /*         * * return the res ** */
        return $res;
    }

    /**
     *
     * @create a roman numeral to a number
     *
     * @param string $num
     *
     * @return int
     *
     */
    public static function numeralRomans($roman) {
        $romans = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1,
        );

        $result = 0;

        foreach ($romans as $key => $value) {
            while (strpos($roman, $key) === 0) {
                $result += $value;
                $roman = substr($roman, strlen($key));
            }
        }
        return $result;
    }

    public static function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public static function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    public static function changeRulesDateAdvanceSearch($rules)
    {
      foreach ($rules as $key => $value) {
          if($key=='rules')
          {
              foreach ($value as $index => $data) {
                  if($data['type']=='date')
                  {
                      if(is_array($data['value']))
                      {
                          foreach ($data['value'] as $keydate => $valuedate) {
                              $time = strtotime($valuedate);
                              $newformat = date('Y-m-d',$time);
                              $rules['rules'][$index]['value'][$keydate]=$newformat;
                          }
                      }else{ 
                          $time = strtotime($data['value']);
                          $newformat = date('Y-m-d',$time);
                          $rules['rules'][$index]['value']=$newformat;
                      }
                  }
              }
          }
      }
      return $rules;
    }


    

}
?>