<?php
$format = trim(strtolower($formatnoinduk));
    if($format == 'manual')
    {

        $noinduk='';
        $nobarcode='';
        $norfid='';
        if ($jumlahEksemplar == 1 || $jumlahEksemplar == 0) {
            /*$noinduk=$result[0]['NoInduk'];
            $nobarcode=$result[0]['NomorBarcode'];
            $norfid=$result[0]['RFID'];*/
            echo "<div class=\"form-group field-collections-noinduk\">";
            echo "<label class=\"control-label col-sm-4\" for=\"collections-noinduk\">&nbsp;</label>";
            echo "<div class=\"col-sm-3\">";
            echo "<label class=\"control-label\">No Induk </label><br><input type=\"text\" id=\"Collections_NoInduk_0\" class=\"form-control\" name=\"Collections[NoInduk][0]\" value=\"".$noinduk."\" >";
            echo "</div>";
            echo "<div class=\"col-sm-3\" style=\"padding-left:0px\">";
            echo "<label class=\"control-label\">No Barcode </label><br><input type=\"text\" id=\"Collections_NomorBarcode_0\" class=\"form-control\" name=\"Collections[NomorBarcode][0]\" value=\"".$nobarcode."\" >";
            echo "</div>";
            echo "<div class=\"col-sm-2\" style=\"padding-left:0px\">";
            echo "<label class=\"control-label\">No RFID </label><br><input type=\"text\" id=\"Collections_RFID_0\" class=\"form-control\" style=\"width:160px;\" name=\"Collections[RFID][0]\" value=\"".$norfid."\" >";
            echo "</div>";
            echo "<div class=\"col-sm-offset-4 col-sm-8\"></div>";
            echo "<div class=\"col-sm-offset-4 col-sm-8\"><div class=\"help-block\"></div></div>";
            echo "</div>";
        } else {
            for ($int = 0; $jumlahEksemplar > $int; $int++) {
                /*$noinduk=$result[$int]['NoInduk'];
                $nobarcode=$result[$int]['NomorBarcode'];
                $norfid=$result[$int]['RFID'];*/
                echo "<div class=\"form-group field-collections-noinduk\">";

                echo "<label class=\"control-label col-sm-4\" for=\"collections-noinduk\">&nbsp;</label>";
                echo "<div class=\"col-sm-3\">";
                echo "<label class=\"control-label\">No Induk </label><br><input type=\"text\" id=\"Collections_NoInduk_".$int."\" class=\"form-control\" name=\"Collections[NoInduk][".$int."]\" value=\"".$noinduk."\" >";
                echo "</div>";
                echo "<div class=\"col-sm-3\" style=\"padding-left:0px\">";
                echo "<label class=\"control-label\">No Barcode </label><br><input type=\"text\" id=\"Collections_NomorBarcode_".$int."\" class=\"form-control\" name=\"Collections[NomorBarcode][".$int."]\" value=\"".$nobarcode."\" >";
                echo "</div>";
                echo "<div class=\"col-sm-2\" style=\"padding-left:0px\">";
                echo "<label class=\"control-label\">No RFID </label><br><input type=\"text\" id=\"Collections_RFID_".$int."\" class=\"form-control\" style=\"width:160px;\" name=\"Collections[RFID][".$int."]\" value=\"".$norfid."\" >";
                echo "</div>";

                echo "<div class=\"col-sm-offset-4 col-sm-8\"></div>";
                echo "<div class=\"col-sm-offset-4 col-sm-8\"><div class=\"help-block\"></div></div>";
                echo "</div>";
            }
        }
    }else{
        $noinduk='-AUTO-';
        $nobarcode='-AUTO-';
        $norfid='-AUTO-';
        echo "<div class=\"form-group field-collections-noinduk\">";
        echo "<label class=\"control-label col-sm-4\" for=\"collections-noinduk\">&nbsp;</label>";
        echo "<div class=\"col-sm-3\">";
        echo "<label class=\"control-label\">No Induk </label><br><input type=\"text\" id=\"Collections_NoInduk_0\" class=\"form-control\" name=\"Collections[NoInduk][0]\" value=\"".$noinduk."\" disabled >";
        echo "</div>";
        /*echo "<div class=\"col-sm-3\" style=\"padding-left:0px\">";
        echo "<label class=\"control-label\">No Barcode </label><br><input type=\"text\" id=\"Collections_NomorBarcode_0\" class=\"form-control\" name=\"Collections[NomorBarcode][0]\" value=\"".$nobarcode."\" disabled >";
        echo "</div>";
        echo "<div class=\"col-sm-2\" style=\"padding-left:0px\">";
        echo "<label class=\"control-label\">No RFID </label><br><input type=\"text\" id=\"Collections_RFID_0\" class=\"form-control\" style=\"width:160px;\" name=\"Collections[RFID][0]\" value=\"".$norfid."\" disabled >";
        echo "</div>";*/
        echo "<div class=\"col-sm-offset-4 col-sm-8\"></div>";
        echo "<div class=\"col-sm-offset-4 col-sm-8\"><div class=\"help-block\"></div></div>";
        echo "</div>";
    }
    ?>



