<?php

/**
 * @copyright Copyright &copy; Perpustakaan Nasional RI, 2017
 * @package helpers
 * @version 1.0.0
 * @author Rico <rico.ulul@gmail.com>
 */
namespace common\components;
 
use Yii;
use yii\base\Component;
 
class ReadHttpHeader extends Component {
 
    public  function RealIP()
    {
        $ip = false;
 
        $seq = array('HTTP_CLIENT_IP',
                  'HTTP_X_FORWARDED_FOR'
                  , 'HTTP_X_FORWARDED'
                  , 'HTTP_X_CLUSTER_CLIENT_IP'
                  , 'HTTP_FORWARDED_FOR'
                  , 'HTTP_FORWARDED'
                  , 'REMOTE_ADDR');
 
        foreach ($seq as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
 
}

?>