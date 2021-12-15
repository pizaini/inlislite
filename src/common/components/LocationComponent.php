<?php

/**
 * @author    Henry <alvin_vna@yahoo.com>
 * @copyright Copyright &copy; Perpustakaan Nasional Republik Indonesia, 2016
 * @version   1.0
 */

namespace common\components;

use yii\base\Component;
    
class LocationComponent extends Component{

        // membuat variabel private untuk membuka dan menutup session
        private $session;

        // fungsi init akan dijalankan secara otomatis pada saat component dipanggil
        public function init(){

            // membuat objek yii session 
            $this->session = \Yii::$app->session;

            // cek apakah aplikasi sudah memiliki session atau belum
            if(!$this->session->has('location')){
                // jika belum memiliki session location, buat session baru dengan nilai awal berupa array
                $this->session->set('location','');
            }

            // buka session
            $this->session->open();  

        }

        // fungsi mendapatkan data session 

        public function get(){
            return $this->session->get('location');
        }

        // fungsi menambahkan data ke dalam session
        public function set($data){


            $this->session->set('location', $data);

        }

        // fungsi destroy data session 
        public function remove(){
            return $this->session->remove('location');
        }


}

?>