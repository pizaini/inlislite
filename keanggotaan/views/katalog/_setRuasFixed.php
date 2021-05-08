<?php 
use yii\helpers\Html;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var common\models\Fields $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="modal-header" >
<b>Tag <?=(string)$tag?> - <?=(string)$tagdesc?></b>
</div>


<div class="modal-body" >
<?php 

//echo '<pre>'; print_r($values); 
$CatalogID =$catalogid;
$MaxRow = 2;
$Counter = -1;
$ValueCounter = 0;
$Counter=0;

$html = "<div id=\"ruasfixed-grid\" style=\"width:100%\">";
foreach ($model as $key => $value) {
    if($values[$ValueCounter] != '')
    {
        $itemValue = str_replace('*','#',str_replace(' ','#',$values[$ValueCounter]));
    }else{
        $itemValue=null;
    }
    if ($Counter == 0)
    {
        $html .= "<div class=\"row\" style=\"margin-bottom:10px\">";
    }else if($Counter > $MaxRow){

        $html .= "<div class=\"row\" style=\"margin-bottom:10px\">";
        $html .= "</div>";
        $Counter=0;
    }

    $Counter++;
    $html .= "<div class=\"col-md-4\" >";
    $html .= "<div style=\"border: 1px solid #CCCCCC; padding:10px; background-color:#faffe1\">";
    if ($value["Name"] != "Belum ditetapkan")
    {
        $html .= "<b>" .$value["Name"]."</b>";
        $html .= " : ";
    }
    else
    {
        $html .= "&nbsp;";
    }
    if(empty($itemValue))
    {
        $itemValue = $value["DefaultValue"];
    }

    if ($value["RefferenceMode"] == "Helper")
    {
        $html .= "<br><input name=\"ValueTextBox\" class=\"form-control\" type=\"text\" name=\"ValueTextBox\" size=\"".((int)$value["Length"] + 1)."\" maxlength=\"".((int)$value["Length"])."\" value=\"";
        $html .= $itemValue;
        $html .= "\"/>";
        $html .= "<input type=\"button\" value=\"...\" onclick=\"OpenFixedFieldRefferenceHelper('".$value["Refference_id"]."', '".$value["Name"]."');return false;\"/>";
    }
    else if ($value["RefferenceMode"] == "Dropdown")
    {
        if ($ValueCounter == 1 && $itemValue == $value["DefaultValue"] && !empty($CatalogID))
        {
            $itemValue = "s";
        }
        $html .= "<br><input name=\"ValueTextBox\" class=\"form-control\" type=\"text\" size=\"4\" maxlength=\"".(int)$value["Length"]."\" value=\"";
        $html .= $itemValue;
        $html .= "\" />";
        $html .= "<br><select name=\"ddlRefference\" class=\"form-control\" onclick=\"$(this).prev().prev().val(this.options[this.selectedIndex].value);\" >";
        $html .= $itemValue;
        $html .= "\">";
        $refitems =  null;
        $refitems = array();
        $refid = $value["Refference_id"];
        foreach ($refValues as $key => $valueref) {
            if($valueref['Refference_id']==$refid)
            {
                array_push($refitems,$valueref);
            }
        }
        if (count($refitems) > 0)
        {

            foreach ($refitems as $key => $value) {
                if (str_replace("#","",$value["Code"]) == str_replace("#","",$itemValue))
                {
                    $html .= "<option value=\"".$value["Code"]."\" selected>";
                }
                else
                {
                    $html .= "<option value=\"".$value["Code"]."\">";
                }
                $html .= $value["Code"]." - ".$value["Name"];
                $html .= "</option>";
            }
        }
        $html .= "</select>";
    }
    else if ($value["Name"] != "Belum ditetapkan")
    {
        $html .= "<br><input name=\"ValueTextBox\" class=\"form-control\" type=\"text\" size=\"".((int)$value["Length"] + 1)."\" maxlength=\"".(int)$value["Length"]."\" value=\"";
        if ($ValueCounter == 0 && $itemValue == $value["DefaultValue"])
        {
            if (empty($CatalogID))
            {
                $date = new DateTime('NOW');
            }
            else
            {
                if (!empty($createdate))
                {
                    $date = new DateTime($createdate);
                }
                else
                {
                    $date = new DateTime('NOW');
                }
            }
            $html .= $date->format('ymd');
        }
        else if ($ValueCounter == 2 && $itemValue == $value["DefaultValue"])
        {
            if (!empty($CatalogID))
            {
                if (strlen($Publishyear) >= 4)
                {
                    $Publishyear = substr($Publishyear,0,4);
                    $html .= $Publishyear; //Tahun 1
                }
                else
                {
                    $html .= "####"; //Tahun 1
                }
            }
            else
            {
                $html .= $itemValue;
            }
        }
        else
        {
            $html .= $itemValue;
        }
        $html .= "\"";
        $html .= "/>";
    }
    else
    {
        $html .= "<br><input name=\"ValueTextBox\" type=\"hidden\" value=\"\" />";
    }
    if (!empty($value["IdemTag"]))
    {
        $html .= " <br><i>Sama dengan Tag ".$value["IdemTag"]." Posisi ".$value["IdemStartPosition"].". Panjang : ".$value["Length"]."</i>";
    }
    $html .= "</div>";
    $html .= "</div>";
    $ValueCounter++;
}
$html .= "</div>";
$html .= "<div>";

echo $html;
?>
</div>

<div class="modal-footer" >
  <?php 

    if($sort != ''){
        $idtext = (string)$tag.'_'.(string)$sort;
    }else{
        $idtext = (string)$tag;
    }
  echo Html::a(Yii::t('app', 'OK'), '#', 
    [
      'id' => "ok-ruas-config-modal",
      'class' => 'btn btn-success',
      'onclick' => 'js:SendRuasFixed(TagsValue_'.$idtext.');',

    ]);

    ?>

    <?=Html::a(Yii::t('app', 'Cancel'), 'javascript:void()', 
        [
            'id' => "cancel-ruas-config-modal",
            'class' => 'btn btn-warning',
            'data-dismiss' => 'modal'

        ])?>
</div>


</div>

