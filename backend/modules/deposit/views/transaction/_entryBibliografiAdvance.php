
<?php 
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use common\widgets\MaskedDatePicker;
?>
<?php /*$form = ActiveForm::begin([
                          'type'=>ActiveForm::TYPE_HORIZONTAL,
                          'id'=>'yiiActiveForm',
                          'formConfig'=>['deviceSize'=>ActiveForm::SIZE_SMALL]
                          ]);*/ 
                          ?>
<style type="text/css">
#tag-modal .modal-dialog { min-width: 95%; z-index: 2147483647; }
#tag-modal .modal-body {
    max-height: 550px;
    overflow-y: auto;
}

#tagfixed-modal .modal-dialog { min-width: 80%; }
#tagfixed-modal .modal-body {
    overflow-y: auto;
    overflow-x: hidden;
}

/* .floating-topright2{
  position: fixed;
  top:295px;
  right: 44px;
  z-index: 9999;
} */
</style>
<?php
 //echo '<pre>'; print_r($taglist); echo '</pre>'; 
$divclass='';
if (!$model->isNewRecord && $for == 'coll')
{
  $divclass='disabled';
}

?>
<div id="entryBibliografiPanel" class="<?=$divclass?>">

  <div class="box-group" id="accordion">
  <div class="panel panel-default">
    <div class="box-header with-border">
          <div class="col-xs-6 col-sm-6" >
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                      Data Bibliografis
                    </a>
                </h4>
          </div>
                              <div class="col-xs-6 col-sm-6">
                                  <small><?php echo Html::a("<i class='glyphicon glyphicon-th-large'></i>". yii::t('app',' Tampilkan Sederhana '), '#', ['id'=>'btn-change-simple','class' => 'btn bg-navy floating-topright2  pull-right btn-sm' ,'onclick'=>'js:BibliografisToogleForm("simple")']); ?></small>
                                  </div>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse in">
                          <div class="box-body">

                              <div id="advance"> 
                              <?php 
                             /* echo '<pre>'; print_r($taglist); echo '</pre>'; die;*/
                              echo Html::hiddenInput('catalogid',(isset($_GET['id']) && isset($_GET['edit'])) ? $_GET['id'] : '',['id'=>'catalogid']); 
                              echo Html::hiddenInput('modeform',(string)$isAdvanceEntry,['id'=>'modeform']); 
                              if($for=='cat')
                              {
                                echo Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Tambah Tag'), 'javascript:void(0)', ['id'=>'btnAddTag','onclick'=>'js:AddTag();','class' => 'AddTag btn bg-purple btn-sm pull-left','data-toggle' => 'tooltip']);
                              }
                              ?>
                              <table id="tblAdv" class="table table-hover table-striped">
                                <thead>
                                  <tr>
                                    
                                    <?php 
                                    if($for=='cat')
                                    {
                                    ?>
                                      <th width="5%">&nbsp;</th>
                                      <th width="5%">Tag</th>
                                      <th width="30%"><?= yii::t('app','Nama')?></th>
                                      <th width="9%"><?= yii::t('app','Indikator1')?></th>
                                      <th width="9%"><?= yii::t('app','Indikator2')?></th>
                                      <th width="42%"><?= yii::t('app','Isi')?></th>
                                    <?php 
                                    }else{
                                    ?>
                                      <th width="10%">Tag</th>
                                      <th width="30%"><?= yii::t('app','Nama')?></th>
                                      <th width="60%"><?= yii::t('app','Isi')?></th>
                                    <?php 
                                    }
                                    ?>
                                  </tr>
                                </thead>
                                <tbody>
                                  

                                 <?php 
                                 if(!empty($taglist['inputvalue']))
                                 {
                                    ksort($taglist['inputvalue']);
                                 }
                                 //echo var_dump($taglist['indicator']).'<br><br>';
                                

                                 //echo var_dump($taglist['tagname']);
                                 $htmlrecord='';
                                 $htmlrecord863='';
                                 foreach ($taglist['inputvalue'] as $tag => $value) {
                                    // $value = str_replace(array('"', '$ '), array("&#34;", '&#x24; '), $value);
                                    $value = str_replace('"', "&#34;", $value);
                                    $tagid = isset($taglist['tagid'][$tag]) ? $taglist['tagid'][$tag] : 0;
                                    $tagmandatory = isset($taglist['tagmandatory'][$tag]) ? $taglist['tagmandatory'][$tag] : 0;
                                    $tagfixed = isset($taglist['tagfixed'][$tag]) ? $taglist['tagfixed'][$tag] : 0;
                                    $taglength = isset($taglist['taglength'][$tag]) ? $taglist['taglength'][$tag] : 0;
                                    $tagenabled = isset($taglist['tagenabled'][$tag]) ? $taglist['tagenabled'][$tag] : 0;
                                    $tagiscustomable = isset($taglist['tagiscustomable'][$tag]) ? $taglist['tagiscustomable'][$tag] : 0;
                                    $tagrepeatable = isset($taglist['tagrepeatable'][$tag]) ? $taglist['tagrepeatable'][$tag] : 0;

                                    $classtajuk='';
                                    $onkeyuptajuk='';
                                    if($tag=='100' || $tag=='110' || $tag=='700' || $tag=='710' || $tag=='600') 
                                    {
                                      $classtajuk=' tajukpengarang';
                                      $onkeyuptajuk = ' onkeyup = "AutoCompleteOn(this,\'pengarang\');"';
                                    }else if($tag=='600' || $tag=='650'  || $tag=='651'){
                                      $classtajuk=' tajuksubyek';
                                      $onkeyuptajuk = ' onkeyup = "AutoCompleteOn(this,\'subyek\');"';
                                    }

                                    $onfocuscallnumber='';
                                    if($tag == '084')
                                    {
                                      $onfocuscallnumber = ' onfocus="AutoCopyCallNumberAdvance(this);"';
                                    }

                                    //untuk validasi empty pda tag mandatory yang enabled=true dan bukan tag fixed
                                    $classvalidatemandatory='';
                                    if($tagmandatory == 1 && $tagenabled == 1 && $tagfixed == 0)
                                    {
                                      $classvalidatemandatory = 'required';
                                    } 

                                    if(is_array($value))
                                    {
                                      $i=0;
                                       foreach ($value as $tag2 => $value2) {
                                           //Tag Code
                                            $var = '<tr id="'.$tag.'_'.$i.'" class="item">';
                                            if($isSerial ==1 && $for=='coll' && $tag=='863')
                                            {
                                                $htmlrecord863 .= $var;
                                            }else{
                                                $htmlrecord .= $var;
                                            }

                                            if($for=='cat')
                                            {
                                              $var = '<td>';
                                              if($isSerial ==1 && $for=='coll' && $tag=='863')
                                              {
                                                  $htmlrecord863 .= $var;
                                              }else{
                                                  $htmlrecord .= $var;
                                              }
                                              if($tagmandatory == 0)
                                              {
                                                $var = '<button class="btn btn-danger" type="button" onclick="RemoveTag(\''.$tag.'\',\''.$i.'\');"><i class="glyphicon glyphicon-trash"></i></button>';
                                                if($isSerial ==1 && $for=='coll' && $tag=='863')
                                                {
                                                    $htmlrecord863 .= $var;
                                                }else{
                                                    $htmlrecord .= $var;
                                                }
                                                
                                              }
                                              $var = '</td>';
                                              if($isSerial ==1 && $for=='coll' && $tag=='863')
                                              {
                                                  $htmlrecord863 .= $var;
                                              }else{
                                                  $htmlrecord .= $var;
                                              }
                                            }
                                            $var = '<td>'.$tag.'</td>';
                                            if($isSerial ==1 && $for=='coll' && $tag=='863')
                                            {
                                                $htmlrecord863 .= $var;
                                            }else{
                                                $htmlrecord .= $var;
                                            }

                                            $tagruasid = isset($taglist['ruasid'][$tag][$i]) ? $taglist['ruasid'][$tag][$i] : '';
                                            $htmlruasid =  '<input type="hidden" value="'.$tagruasid.'" id="Ruasid_'.$tag.'_'.$i.'" name="Ruasid['.$tag.']['.$i.']" size="3" />';
                                            $tagdesc = isset($taglist['tagname'][$tag]) ? $taglist['tagname'][$tag] : 'Unknown description';
                                            
                                            //Tag Desc
                                            $var = '<td>'.$htmlruasid.$tagdesc.'</td>';
                                            if($isSerial ==1 && $for=='coll' && $tag=='863')
                                            {
                                                $htmlrecord863 .= $var;
                                            }else{
                                                $htmlrecord .= $var;
                                            }

                                            $tagind = isset($taglist['indicator'][$tag]) ? $taglist['indicator'][$tag] : 'Unknown indicator';

                                            $indvalue1 = $tagind[$i]['ind1'];
                                            $indvalue2 = $tagind[$i]['ind2'];

                                            if($for=='cat')
                                            {
                                              if((int)$tagfixed != 1)
                                              {
                                                $var = '<td>
                                                                <div class="input-group">
                                                                  <input type="text" class="form-control" id="Indicator1_'.$tag.'_'.$i.'" name="Indicator1['.$tag.']['.$i.']" value="'.$indvalue1.'"  maxlength="1">
                                                                  <span class="input-group-btn">'.
                                                                  Html::a('...', 'javascript:void(0)', [
                                                                                                  'title' => Yii::t('app', 'Pick'),
                                                                                                  'class' => 'btn bg-purple',
                                                                                                  'data-toggle' => 'modal', 
                                                                                                  'data-target' => '#helper-modal',
                                                                                                  'onclick' => 'js:PickIndicator1("'.$tagid.'","'.$tag.'","'.$i.'")'
                                                                                                ]).'
                                                                 </span>
                                                                 </div>
                                                                </td>';
                                                $htmlrecord .= $var;
                                                
                                                $var = '<td>
                                                                <div class="input-group">
                                                                  <input type="text" class="form-control" id="Indicator2_'.$tag.'_'.$i.'" name="Indicator2['.$tag.']['.$i.']" value="'.$indvalue2.'"  maxlength="1">
                                                                  <span class="input-group-btn">'.
                                                                  Html::a('...', 'javascript:void(0)', [
                                                                                                  'title' => Yii::t('app', 'Pick'),
                                                                                                  'class' => 'btn bg-purple',
                                                                                                  'data-toggle' => 'modal', 
                                                                                                  'data-target' => '#helper-modal',
                                                                                                  'onclick' => 'js:PickIndicator2("'.$tagid.'","'.$tag.'","'.$i.'")'
                                                                                                ]).'
                                                                 </span>
                                                                 </div>
                                                                </td>';
                                                $htmlrecord .= $var;
                                                
                                               }
                                            }

                                            //Tag Value
                                            
                                            $textlength='';
                                            if((int)$tagfixed == 1)
                                            {
                                              if((int)$taglength != -1)
                                              {
                                                $textlength = 'maxlength="'.$taglength.'"';
                                              }
                                            }

                                            $textdisabled='';
                                            $classgroup='';
                                            if((int)$tagenabled != 1)
                                            {
                                              $textdisabled='readonly';
                                              //$value2='';
                                              
                                            }else{
                                              $classgroup='class="input-group"';
                                            }

                                            $textonlick='';
                                            $modalname='';
                                            if((int)$tagiscustomable == 1)
                                            {
                                              $modalname='tagfixed-modal';
                                              $textonlick='js:PickRuasFixed("'.$tagid.'","'.$tag.'","'.$i.'")';
                                              $classgroup='class="input-group"';
                                            }else{
                                              $modalname='helper-modal';
                                              $textonlick='js:PickRuas("'.$tagid.'","'.$tag.'","'.$i.'","'.$rda.'")';
                                            }

                                            $var = '<td>
                                                            <input type="hidden" id="Tags_'.$tag.'_'.$i.'" name="Tags['.$tag.']['.$i.']" value="'.$tag.'_'.$i.'" class="item" >';
                                            if($isSerial ==1 && $for=='coll' && $tag=='863')
                                            {
                                                $htmlrecord863 .= $var;
                                            }else{
                                                $htmlrecord .= $var;
                                            }
                                            
                                            if($for!='cat')
                                            {
                                            $var  = '<input type="hidden" id="Indicator1_'.$tag.'_'.$i.'" name="Indicator1['.$tag.']['.$i.']" value="'.$indvalue1.'" >
                                                            <input type="hidden" id="Indicator2_'.$tag.'_'.$i.'" name="Indicator2['.$tag.']['.$i.']" value="'.$indvalue2.'" >';
                                              if($isSerial ==1 && $for=='coll' && $tag=='863')
                                              {
                                                  $htmlrecord863 .= $var;
                                              }else{
                                                  $htmlrecord .= $var;
                                              }
                                            }

                                            $tagvalidate = '_'.$tag.'_'.$i;  

                                            $var = '<span class="'.$classvalidatemandatory.'"  id="status'.$tagvalidate.'">
                                                      <input type="hidden" id="message'.$tagvalidate.'" value="'.$tagdesc.Yii::t('app', ' cannot be empty!').'">
                                                      <div '.$classgroup.'>
                                                              <input type="text" class="form-control'.$classtajuk.'" '.$onkeyuptajuk.$onfocuscallnumber.' '.$textdisabled.' '.$textlength.' id="TagsValue_'.$tag.'_'.$i.'" name="TagsValue['.$tag.']['.$i.']" value="'.$value2.'">';
                                            if($isSerial ==1 && $for=='coll' && $tag=='863')
                                            {
                                                $htmlrecord863 .= $var;
                                            }else{
                                                $htmlrecord .= $var;
                                            }
                                            if(((int)$tagfixed == 0 && (int)$tagiscustomable == 0 && (int)$tagenabled == 1) || ((int)$tagfixed == 1 && (int)$tagiscustomable == 1))
                                            {
                                              $var = '<span class="input-group-btn">'.
                                                                Html::a('...', 'javascript:void(0)', [
                                                                                                'title' => Yii::t('app', 'Pick'),
                                                                                                'class' => ($for == 'cat') ? 'btn bg-purple' : 'btn bg-maroon',
                                                                                                'data-toggle' => 'modal', 
                                                                                                'data-target' => '#'.$modalname,
                                                                                                'onclick' => $textonlick
                                                                                              ]).'
                                                               </span>';
                                              if($isSerial ==1 && $for=='coll' && $tag=='863')
                                              {
                                                  $htmlrecord863 .= $var;
                                              }else{
                                                  $htmlrecord .= $var;
                                              }
                                            }
                                            $var = '</div>
                                                      <div id="error'.$tagvalidate.'" class="help-block"></div>
                                                    </span>
                                                    </td>
                                                    </tr>';
                                            if($isSerial ==1 && $for=='coll' && $tag=='863')
                                            {
                                                $htmlrecord863 .= $var;
                                            }else{
                                                $htmlrecord .= $var;
                                            }
                                            $i++;
                                       }
                                    }else{
                                      $value = str_replace('"', "&#34;", $value);
                                        //Tag Code
                                        $index='';
                                        if((int)$tagrepeatable == 1)
                                        {
                                          $index='[0]';
                                        }
                                        $tagcode = "[".$tag."]";
                                        $indexJs =  str_replace(']','',str_replace('[','_',$index));
                                        $indexClean = str_replace(']','',str_replace('[','',$index));
                                        $tagcodeJs =  str_replace(']','',str_replace('[','_',$tagcode));

                                        $var = '<tr  id="'.$tag.$indexJs.'">';
                                        if($isSerial ==1 && $for=='coll' && $tag=='863')
                                        {
                                            $htmlrecord863 .= $var;
                                        }else{
                                            $htmlrecord .= $var;
                                        }
                                        if($for=='cat')
                                        {
                                          $var = '<td>';
                                          if($isSerial ==1 && $for=='coll' && $tag=='863')
                                          {
                                              $htmlrecord863 .= $var;
                                          }else{
                                              $htmlrecord .= $var;
                                          }
                                          $tagmandatory = isset($taglist['tagmandatory'][$tag]) ? $taglist['tagmandatory'][$tag] : 0;
                                          if($tagmandatory == 0)
                                          {
                                            $var = '<button class="btn btn-danger" type="button" onclick="RemoveTag(\''.$tag.'\',\''.$indexJs.'\');"><i class="glyphicon glyphicon-trash"></i></button>';
                                            if($isSerial ==1 && $for=='coll' && $tag=='863')
                                            {
                                                $htmlrecord863 .= $var;
                                            }else{
                                                $htmlrecord .= $var;
                                            }
                                          }
                                          $var = '</td>';
                                          if($isSerial ==1 && $for=='coll' && $tag=='863')
                                          {
                                              $htmlrecord863 .= $var;
                                          }else{
                                              $htmlrecord .= $var;
                                          }
                                        }
                                        $var = '<td>'.$tag.'</td>';
                                        if($isSerial ==1 && $for=='coll' && $tag=='863')
                                        {
                                            $htmlrecord863 .= $var;
                                        }else{
                                            $htmlrecord .= $var;
                                        }

                                        $tagruasid = isset($taglist['ruasid'][$tag]) ? $taglist['ruasid'][$tag] : '';
                                        $htmlruasid =  '<input type="hidden" value="'.$tagruasid.'" id="Ruasid_'.$tag.'" name="Ruasid['.$tag.']" size="3" />';

                                        $tagdesc = isset($taglist['tagname'][$tag]) ? $taglist['tagname'][$tag] : 'Unknown description';
                                            
                                        //Tag Desc
                                        $var = '<td>'.$htmlruasid.$tagdesc.'</td>';
                                        if($isSerial ==1 && $for=='coll' && $tag=='863')
                                        {
                                            $htmlrecord863 .= $var;
                                        }else{
                                            $htmlrecord .= $var;
                                        }

                                        $tagind = isset($taglist['indicator'][$tag]) ? $taglist['indicator'][$tag] : 'Unknown indicator';

                                        if((int)$tagfixed != 1)
                                        {
                                          //Tag Ind
                                          if((int)$tagrepeatable == 1)
                                          {
                                            $indvalue1 = $tagind[0]['ind1'];
                                            $indvalue2 = $tagind[0]['ind2'];
                                          }else{
                                            $indvalue1 = $tagind['ind1'];
                                            $indvalue2 = $tagind['ind2'];
                                          }
                                        }
                                        

                                        if($for=='cat')
                                        {
                                          
                                            $var = '<td>';
                                            $htmlrecord .= $var;
                                            if((int)$tagfixed != 1)
                                            {
                                              $var = '<div class="input-group">
                                                                <input type="text" class="form-control" id="Indicator1'.$tagcodeJs.''.$indexJs.'" name="Indicator1'.$tagcode.$index.'" value="'.$indvalue1.'"  maxlength="1">
                                                                <span class="input-group-btn">'.
                                                                Html::a('...', 'javascript:void(0)', [
                                                                                                'title' => Yii::t('app', 'Pick'),
                                                                                                'class' => 'btn bg-purple',
                                                                                                'data-toggle' => 'modal', 
                                                                                                'data-target' => '#helper-modal',
                                                                                                'onclick' => 'js:PickIndicator1("'.$tagid.'","'.$tag.'","'.$indexClean.'")'
                                                                                              ]).'
                                                               </span>
                                                               </div>';
                                              $htmlrecord .= $var;
                                            }
                                            $var = '</td>';
                                            $htmlrecord .= $var;
                                            

                                            $var = '<td>';
                                            $htmlrecord .= $var;

                                            if((int)$tagfixed != 1)
                                            {
                                              $var = '<div class="input-group">
                                                                <input type="text" class="form-control" id="Indicator2'.$tagcodeJs.$indexJs.'" name="Indicator2'.$tagcode.$index.'" value="'.$indvalue2.'"  maxlength="1">
                                                                <span class="input-group-btn">'.
                                                                Html::a('...', 'javascript:void(0)', [
                                                                                                'title' => Yii::t('app', 'Pick'),
                                                                                                'class' => 'btn bg-purple',
                                                                                                'data-toggle' => 'modal', 
                                                                                                'data-target' => '#helper-modal',
                                                                                                'onclick' => 'js:PickIndicator2("'.$tagid.'","'.$tag.'","'.$indexClean.'")'
                                                                                              ]).'
                                                               </span>
                                                               </div>';
                                              $htmlrecord .= $var;
                                              
                                            }
                                            $var = '</td>';
                                            $htmlrecord .= $var;
                                            
                                          
                                        }

                                        $textlength='';
                                        if((int)$tagfixed == 1)
                                        {
                                          if((int)$taglength != -1)
                                          {
                                            $textlength = 'maxlength="'.$taglength.'"';
                                          }
                                        }

                                        $textdisabled='';
                                        $classgroup='';
                                        if((int)$tagenabled != 1)
                                        {
                                          $textdisabled='readonly';
                                          if((int)$tag > 10)
                                          {
                                              $value='';
                                          }
                                        }else{
                                          $classgroup='class="input-group"';
                                        }

                                        $textonlick='';
                                        $modalname='';
                                        if((int)$tagiscustomable == 1)
                                        {
                                          $modalname='tagfixed-modal';
                                          $textonlick='js:PickRuasFixed("'.$tagid.'","'.$tag.'","'.$indexClean.'")';
                                          $classgroup='class="input-group"';
                                          //$value='';
                                        }else{
                                          $modalname='helper-modal';
                                          $textonlick='js:PickRuas("'.$tagid.'","'.$tag.'","'.$indexClean.'")';
                                        }

                                        //Tag Value
                                        $var = '<td>
                                                        <input type="hidden" id="Tags'.$tagcodeJs.$indexJs.'" name="Tags'.$tagcode.$index.'" value="'.$tag.$indexJs.'" class="item">';
                                        if($isSerial ==1 && $for=='coll' && $tag=='863')
                                        {
                                            $htmlrecord863 .= $var;
                                        }else{
                                            $htmlrecord .= $var;
                                        }
                                        if($for!='cat' and (int)$tagfixed != 1)
                                        {
                                        $var = '<input type="hidden" id="Indicator1'.$tagcodeJs.$indexJs.'" name="Indicator1'.$tagcode.$index.'" value="'.$indvalue1.'">
                                                        <input type="hidden" id="Indicator2'.$tagcodeJs.$indexJs.'" name="Indicator2'.$tagcode.$index.'" value="'.$indvalue2.'">';
                                          if($isSerial ==1 && $for=='coll' && $tag=='863')
                                          {
                                              $htmlrecord863 .= $var;
                                          }else{
                                              $htmlrecord .= $var;
                                          }
                                        }
                                        
                                        $tagvalidate = $tagcodeJs.$indexJs;  

                                        $var = '<span class="'.$classvalidatemandatory.'"  id="status'.$tagvalidate.'">
                                                  <input type="hidden" id="message'.$tagvalidate.'" value="'.$tagdesc.Yii::t('app', ' cannot be empty!').'">
                                                  <div '.$classgroup.'>
                                                          <input type="text" class="form-control'.$classtajuk.'" '.$onkeyuptajuk.$onfocuscallnumber.' '.$textdisabled.' '.$textlength.' id="TagsValue'.$tagcodeJs.$indexJs.'" name="TagsValue'.$tagcode.$index.'" value="'.$value.'">';
                                        if($isSerial ==1 && $for=='coll' && $tag=='863')
                                        {
                                            $htmlrecord863 .= $var;
                                        }else{
                                            $htmlrecord .= $var;
                                        }
                                        if(((int)$tagfixed == 0 && (int)$tagiscustomable == 0 && (int)$tagenabled == 1) || ((int)$tagfixed == 1 && (int)$tagiscustomable == 1))
                                        {
                                            $var = '<span class="input-group-btn">'.
                                                              Html::a('...', 'javascript:void(0)', [
                                                                                              'title' => Yii::t('app', 'Pick'),
                                                                                              'class' => ($for == 'cat') ? 'btn bg-purple' : 'btn bg-maroon',
                                                                                              'data-toggle' => 'modal', 
                                                                                              'data-target' => '#'.$modalname,
                                                                                              'onclick' => $textonlick
                                                                                            ]).'
                                                             </span>';
                                            if($isSerial ==1 && $for=='coll' && $tag=='863')
                                            {
                                                $htmlrecord863 .= $var;
                                            }else{
                                                $htmlrecord .= $var;
                                            }
                                          
                                        }

                                        $var='  </div>
                                                <div id="error'.$tagvalidate.'" class="help-block"></div>
                                              </span>
                                              </td>
                                              </tr>';
                                        if($isSerial ==1 && $for=='coll' && $tag=='863')
                                        {
                                            $htmlrecord863 .= $var;
                                        }else{
                                            $htmlrecord .= $var;
                                        }
                                        
                                    }



                                 }
                                 echo $htmlrecord;
                                 ?>
                                </tbody>
                              </table>
                              </div>
                            </div>
                        </div>
                      </div>
          </div>
  <?php //ActiveForm::end(); 

  if($for=='cat')
  {
    echo Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Tambah Tag'), 'javascript:void(0)', ['id'=>'btnAddTag2','onclick'=>'js:AddTag();','class' => 'AddTag btn bg-purple btn-sm pull-left','data-toggle' => 'tooltip'])."<br><br>";
  }
  ?>
</div>

<?php
//Khusus jenis bahan terbitan berkala (serial)
if($isSerial ==1 && $for=='coll')
{
?>
<div class="box-group" id="cardexbox">
                    <div class="panel panel-default">
                      <div class="box-header with-border">
                            <div class="col-xs-12 col-sm-12" >
                                  <h4 class="box-title">
                                      <a data-toggle="collapse" data-parent="#cardexbox" href="#collapseTwo2">
                                        Cardex (Edisi Serial)
                                      </a>
                                  </h4>
                            </div>
                      </div>
                      <div id="collapseTwo2" class="panel-collapse collapse in">
                        <div class="box-body">
                          <table id="tblAdv2" style="margin-top: -20px"  class="table table-hover table-striped">
                             <thead>
                              <tr>
                                  <th width="10%"></th>
                                  <th width="30%"></th>
                                  <th width="60%"></th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php echo $htmlrecord863; ?>
                            </tbody>
                          </table>
                          <br>
                          <div class="form-group kv-fieldset-inline">
                                    <div class="col-sm-8">
                                         <div class="form-group">
                                            <label class="control-label col-sm-4" for="email"><?php echo Html::activeLabel($model,'TANGGAL_TERBIT_EDISI_SERIAL'); ?></label>
                                            <div class="col-sm-8">
                                              <?php 
                                              echo MaskedDatePicker::widget(
                                              [
                                                'model' => $model, 
                                                'attribute' => 'TANGGAL_TERBIT_EDISI_SERIAL',
                                                'enableMaskedInput' => true,
                                                'maskedInputOptions' => [
                                                    'mask' => '99-99-9999',
                                                    'pluginEvents' => [
                                                        'complete' => "function(){console.log('complete');}"
                                                    ]
                                                ],
                                               'removeButton' => false,
                                               'options'=>[
                                                                'style'=>'width:170px',
                                                            ],
                                                'pluginOptions' => [
                                                              'autoclose' => true,
                                                              'todayHighlight' => true,
                                                              'format'=>'dd-mm-yyyy',
                                                            ]
                                              ]);
                                              ?>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-sm-4">
                                        &nbsp;
                                    </div>
                                  </div>

                                  <div class="form-group kv-fieldset-inline">
                                    <div class="col-sm-8">
                                         <div class="form-group">
                                            <label class="control-label col-sm-4" for="email"><?php echo Html::activeLabel($model,'BAHAN_SERTAAN'); ?></label>
                                            <div class="col-sm-8">
                                              <?php echo Html::activeTextInput($model,'BAHAN_SERTAAN',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'coll_Bahan  Sertaan').'...']); ?>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-sm-4">
                                        &nbsp;
                                    </div>
                                  </div>

                                  <div class="form-group kv-fieldset-inline">
                                    <div class="col-sm-8">
                                         <div class="form-group">
                                            <label class="control-label col-sm-4" for="email"><?php echo Html::activeLabel($model,'KETERANGAN_LAIN'); ?></label>
                                            <div class="col-sm-8">
                                              <?php echo Html::activeTextInput($model,'KETERANGAN_LAIN',['class'=>'form-control','style'=>'width:100%','placeholder'=>Yii::t('app', 'Enter').' '.Yii::t('app', 'coll_Keterangan  Lain').'...']); ?>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-sm-4">
                                        &nbsp;
                                    </div>
                                  </div>
                          </div>
                      </div>
                    </div>
</div>
<?php
}
?>
<input type="hidden" id="hdnAjaxUrlAddTag" value="<?=Yii::$app->urlManager->createUrl(['pengkatalogan/katalog/add-tag'])?>">
<input type="hidden" id="hdnAjaxUrlIndicator1" value="<?=Yii::$app->urlManager->createUrl(['pengkatalogan/katalog/set-indicator1'])?>">
<input type="hidden" id="hdnAjaxUrlIndicator2" value="<?=Yii::$app->urlManager->createUrl(['pengkatalogan/katalog/set-indicator2'])?>">
<input type="hidden" id="hdnAjaxUrlRuas" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/set-ruas"])?>">
<input type="hidden" id="hdnAjaxUrlRuasFixed" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/set-ruas-fixed"])?>">
<input type="hidden" id="hdnAjaxUrlTajukPengarang" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/tajuk-pengarang-dollar"])?>">
<input type="hidden" id="hdnAjaxUrlTajukSubyek" value="<?=Yii::$app->urlManager->createUrl(["pengkatalogan/katalog/tajuk-subyek-dollar"])?>">

<?php 
/*$taglistnya = \Yii::$app->session['taglist'];  
echo '<pre>'; print_r($taglistnya['inputvalue']); echo '</pre>'; */

$this->registerJsFile( 
    Yii::$app->request->baseUrl.'/assets_b/js/catalogs_advance.js'
);
?>


