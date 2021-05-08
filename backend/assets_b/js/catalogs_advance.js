var rda = $('#rdas').val();
function RemoveTag(tag,index)
{
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : tag,
                index : index
            },
      success  : function(response) {
          if(index !== '')
          {
            $("table#tblAdv tr#"+tag+"_"+index).remove();
          }else{
            $("table#tblAdv tr#"+tag).remove();
          }
      }
  });
  
}

function AddTag() {
  isLoading = true;
  if( $('#tag-body').is(':empty') ) {
    $.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlAddTag").val(),
        beforeSend : function(){
          $("#tag-body").html("<center>Loading form...</center>");
        },
        success  : function(response) {
            $("#tag-body").html(response);
        },
        async: true
    });
  }
  $("#tag-modal").modal("show");
    
}

function SendTag(id,code,desc,fixed,enabled,panjang,mandatory,iscustomable,repeatable) {
  
    var newRow = "";
    var classGroup = "";
    var buttonOnclick = "";
    var buttonInd1Onclick = "";
    var buttonInd2Onclick = "";
    var txtLenght = "";
    var txtDisabled = "";
    var txtValue = "$a ";
    var sort="";

    var tags = [];
    var sameTags = [];
    $("#tblAdv").find("input.item").each(function(index){
       var myData = $(this).val();
       tags.push(myData);
      });

    var search_term = code; // search term
    var search = new RegExp(search_term , "i");
    var sameTags = $.grep(tags, function (value) {
                          return search.test(value);
                        }
                     );
    if(sameTags.length > 0)
    {
      sort = String(parseInt(sameTags[sameTags.length -1].substr(4,1)) + 1);
    }else{
      if(repeatable==1)
      {
        sort="0";
      }
    }

    if(fixed == 1)
    {
      if(panjang != -1)
      {
        txtLenght = " maxlength=\""+panjang+"\"";
      }
    }

    if(enabled != 1)
    {
      txtDisabled= " readonly";
      txtValue="";
    }else{
      classGroup="class=\"input-group\"";
    }

    if(iscustomable == 1)
    {
      buttonOnclick = "js:PickRuasFixed(\'"+id+"\',\'"+code.trim()+"\',\'"+sort+"\')";
    }else{
      if(rda == 1){
        buttonOnclick = "js:PickRuas(\'"+id+"\',\'"+code.trim()+"\',\'"+sort+"\',\'"+rda+"\')";
      }else{
        buttonOnclick = "js:PickRuas(\'"+id+"\',\'"+code.trim()+"\',\'"+sort+"\')";
      }
      
    }

    buttonInd1Onclick = "js:PickIndicator1(\'"+id+"\',\'"+code.trim()+"\',\'"+sort+"\')";
    buttonInd2Onclick = "js:PickIndicator2(\'"+id+"\',\'"+code.trim()+"\',\'"+sort+"\')";

    if(sort != "")
    {
      sort = "["+sort+"]";
    }
    var tagcode="";
    var sortJs="";
    var tagcodeJs="";
    var classtajuk="";
    var onkeyuptajuk="";
    var onfocuscallnumber="";

    if(code=='100' || code=='110' || code=='700' || code=='710' || code=='600')
    {
      classtajuk=" tajukpengarang";
      onkeyuptajuk=" onkeyup = \"AutoCompleteOn(this,'pengarang');\"";
    }else if(code=='600' || code=='650' || code=='651')
    {
      classtajuk=" tajuksubyek";
      onkeyuptajuk=" onkeyup = \"AutoCompleteOn(this,'subyek');\"";
    }

    if(code == '084')
    {
      onfocuscallnumber = " onfocus=\"AutoCopyCallNumberAdvance(this);\"";
    }

    //untuk validasi empty pda tag mandatory yang enabled=true dan bukan tag fixed
    var classvalidatemandatory="";
    if(mandatory == 1 && enabled == 1 && fixed == 0)
    {
      classvalidatemandatory = "required";
    } 

    //console.log(code+sort);
    if ($.inArray(code, tags) > -1 && repeatable==0){ 
      alertSwal("Data tag "+code+" sudah ada");
    }else{
      tagcode = "["+code+"]";
      sortJs =  sort.replace("[","_").replace("]","");
      tagcodeJs =  tagcode.replace("[","_").replace("]","");
      
      newRow += "<tr id=\""+code+sortJs+"\">";
        newRow += "<td>";
        if(mandatory != 1)
        {
          newRow += "<button class=\"btn btn-danger\" type=\"button\" onclick=\"$(\'table#tblAdv tr#"+code+sortJs+"\').remove();\"><i class=\"glyphicon glyphicon-trash\"></i></button>";
        }
        newRow += "</td>";
        newRow += "<td>"+code+"</td>";
        newRow += "<td>"+desc+"</td>";
        newRow += "<td>";
        if(fixed != 1)
        {
          newRow += "<div class=\"input-group\">";
            newRow += "<input type=\"text\" class=\"form-control\" id=\"Indicator1"+tagcodeJs+sortJs+"\" name=\"Indicator1"+tagcode+sort+"\" value=\"#\" maxlength=\"1\">";
            newRow += "<span class=\"input-group-btn\">";
              newRow += "<a class=\"btn bg-purple\" href=\"javascript:void(0)\" title=\"Pick\" data-toggle=\"modal\" data-target=\"#helper-modal\" onclick=\""+buttonInd1Onclick+"\">...</a>";
            newRow += "</span>";
          newRow += "</div>";
        }
        newRow += "</td>";
        newRow += "<td>";
        if(fixed != 1)
        {
          newRow += "<div class=\"input-group\">";
            newRow += "<input type=\"text\" class=\"form-control\" id=\"Indicator2"+tagcodeJs+sortJs+"\" name=\"Indicator2"+tagcode+sort+"\" value=\"#\" maxlength=\"1\">";
            newRow += "<span class=\"input-group-btn\">";
              newRow += "<a class=\"btn bg-purple\" href=\"javascript:void(0)\" title=\"Pick\" data-toggle=\"modal\" data-target=\"#helper-modal\" onclick=\""+buttonInd2Onclick+"\">...</a>";
            newRow += "</span>";
          newRow += "</div>";
        }
        newRow += "</td>";
        newRow += "<td>";
          newRow += "<input type=\"hidden\" id=\"Tags"+tagcodeJs+sortJs+"\" name=\"Tags"+tagcode+sort+"\" value=\""+code+sort+"\" class=\"item\">";
          newRow += "<span class=\""+classvalidatemandatory+"\"  id=\"status"+tagcodeJs+sortJs+"\">";
            newRow += "<input type=\"hidden\" id=\"message"+tagcodeJs+sortJs+"\" value=\""+desc+" tidak boleh kosong!\">";
            newRow += "<div "+classGroup+">";
              newRow += "<input type=\"text\" class=\"form-control"+classtajuk+"\" "+onkeyuptajuk+onfocuscallnumber+" id=\"TagsValue"+tagcodeJs+sortJs+"\" name=\"TagsValue"+tagcode+sort+"\"  "+txtLenght+" value=\""+txtValue+"\" "+txtDisabled+">";
              if(enabled == 1)
              {
              newRow += "<span class=\"input-group-btn\">";
                newRow += "<a class=\"btn bg-purple\" href=\"javascript:void(0)\" title=\"Pick\" data-toggle=\"modal\" data-target=\"#helper-modal\" onclick=\""+buttonOnclick+"\">...</a>";
              newRow += "</span>";
              }
            newRow += "</div>";
            newRow += "<div id=\"error"+tagcodeJs+sortJs+"\" class=\"help-block\"></div>";
          newRow += "</span>";
        newRow += "</td>";
      newRow += "</tr>";
      $('#tag-modal').modal('hide');
      $(".modal-backdrop").hide();
      $("#tblAdv tr:last").after(newRow);
      $("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
      $("body").css("overflow","auto");
  }
}    

//buat load grid sub ruas dalam modal (INDICATOR1)
function PickIndicator1(id,tag,sort) {
    sort = sort.replace("_", "");
    isLoading = false;
    $.ajax({
        type     :"POST",
        cache    : false,
        url  :  $("#hdnAjaxUrlIndicator1").val(),
        data: {
          id: id,
          tag: tag,
          sort: sort
        },
        beforeSend : function(){
          $("#helper-body").html("<center>Loading form...</center>");
        },
        success  : function(response) {
            $("#helper-body").html(response);
            
        }
    });
}

//buat load grid sub ruas dalam modal (INDICATOR2)
function PickIndicator2(id,tag,sort) {
    sort = sort.replace("_", "");
    isLoading = false;
    $.ajax({
        type     :"POST",
        cache    : false,
        url  :  $("#hdnAjaxUrlIndicator2").val(),
        data: {
          id: id,
          tag: tag,
          sort: sort
        },
        beforeSend : function(){
          $("#helper-body").html("<center>Loading form...</center>");
        },
        success  : function(response) {
            $("#helper-body").html(response);
            
        }
    });
}

//kirim value dari grid sub ruas ke text input ruas(INDICATOR 1 & 2)
function SendIndicator(tag,code) {
  $("#"+tag).val(code);
  $("#helper-modal").modal("hide");
}
    
//buat load grid sub ruas dalam modal(RUAS)
function PickRuas(id,tag,sort,rda) {
    var v = "";
    
    if(sort !=  "")
    {
        v = $("#TagsValue_"+tag+"_"+sort).val();
    }else{
        v = $("#TagsValue_"+tag).val();
    }
    
    isLoading = false;
    $.ajax({
        type     :"POST",
        cache    : false,
        url  :  $("#hdnAjaxUrlRuas").val(),
        data: {
          id: id,
          tag: tag,
          v: v,
          sort: sort,
          rda: rda
        },
        beforeSend : function(){
          $("#helper-body").html("<center>Loading form...</center>");
        },
        success  : function(response) {
            $("#helper-body").html(response);
            SetValueRuas(tag,v);
            
        }
    });
}

//buat load grid sub ruas dalam modal(RUAS FIXED)
function PickRuasFixed(id,tag,sort) {
    var v = "";
    if(sort !=  "")
    {
        v = $("#TagsValue_"+tag+"_"+sort).val();
    }else{
        v = $("#TagsValue_"+tag).val();
    }
    var worksheetid = $("#catalogs-worksheet_id").val();
    var catalogid = $("#catalogid").val();
    isLoading = false;
    $.ajax({
        type     :"POST",
        cache    : false,
        url  :  $("#hdnAjaxUrlRuasFixed").val(),
        data: {
          id: id,
          tag: tag,
          v: v,
          sort: sort,
          worksheetid: worksheetid,
          catalogid: catalogid
        },
        success  : function(response) {
            $("#tagfixed-body").html(response);
        }
    });

    
}

//buat load isi data sub ruas grid modal dari text input ruas(RUAS)
function SetValueRuas(tag,v)
{
  
    var key,item;
    var ruas = v.split("$");
    for(var i = 0; i < ruas.length; i++)
    {
        if(ruas[i])
        {
           key=  ruas[i].substring(0,1);          
           item = ruas[i].substring(1,ruas[i].length);
           $("#FieldDatas_"+key).val(item.trim());
        }

    }
}

//kirim value dari grid sub ruas ke text input ruas(RUAS)
function SendRuas(tag, isAdvanceEntryCatalog) {
    var item;
    var result = "";
    var elementsInput = document.getElementById("fielddatas-grid").getElementsByTagName("input");
    for(var i = 0; i < elementsInput.length; i++)
    {
        key=  elementsInput[i].name.replace("FieldDatas_","");          
        item = elementsInput[i].value;
        if(item)
        {
          if(isAdvanceEntryCatalog == '1'){
            result +="$"+ key + " " +item + " ";
          }else{
            result +="$"+ key + " " +item + " ";
          }
             
        }

    }
    $("#"+tag.id).val(result);
    $("#helper-modal").modal("hide");
}

//kirim value dari grid sub ruas ke text input ruas(RUAS)
function SendRuasFixed(tag) {
    var item;
    var result = "";
    var elementsInput = document.getElementById("ruasfixed-grid").getElementsByTagName("input");
    for(var i = 0; i < elementsInput.length; i++)
    {      
        item = elementsInput[i].value;
        maxlength =  elementsInput[i].maxLength;
        if(item !== '')
        {
          if(item.length < maxlength)
          {
            var slice = maxlength - item.length;
            for (var x = 0; x < slice; x++) {
              item += '#';
            }
            result +=item;
          }else{
            result +=item;
          }
        }else{
          result += '#';
        }

    }
  $("#"+tag.id).val(result);
  $("#tagfixed-modal").modal("hide");
}

//////////////////////////////////////////////////////////////////
/////////jquery untuk men disable tag 100 or 110 or 111 ///////////
//////////////////////////////////////////////////////////////////
$('input').click(function() {
  var naste = $(this).attr('id');

  if(naste == 'TagsValue_100'){
  $( '#Indicator1_110' ).val('#');
  $( '#Indicator1_111' ).val('#');


    $('#'+naste).on('keyup',function(){
      var input = $(this).val().length;

      if(input > '3'){
      console.log(input);
      $( '#TagsValue_110' ).val('\$a ');$('#110').find('input,a').attr('disabled',true).removeAttr('data-toggle');
      $( '#TagsValue_111' ).val('\$a ');$('#111').find('input,a').attr('disabled',true).removeAttr('data-toggle');
      }else{
      console.log('100');
      $('#110').find('input,a').attr({'disabled': false, 'data-toggle': 'modal'});
      $('#111').find('input,a').attr({'disabled': false, 'data-toggle': 'modal'});
      }

    });
  } else if (naste == 'TagsValue_110') {
  $( '#Indicator1_100' ).val('#');
  $( '#Indicator1_111' ).val('#');

    $('#'+naste).on('keyup',function(){
      var input = $(this).val().length;

      if(input > '3'){
      console.log(input);
      $( '#TagsValue_100' ).val('\$a ');$('#100').find('input,a').attr('disabled',true).removeAttr('data-toggle');
      $( '#TagsValue_111' ).val('\$a ');$('#111').find('input,a').attr('disabled',true).removeAttr('data-toggle');
      }else{
      console.log('110');
      $('#100').find('input,a').attr({'disabled': false, 'data-toggle': 'modal'});
      $('#111').find('input,a').attr({'disabled': false, 'data-toggle': 'modal'});
      }

    });
  } else if (naste == 'TagsValue_111') {
  $( '#Indicator1_100' ).val('#');
  $( '#Indicator1_110' ).val('#');

    $('#'+naste).on('keyup',function(){
      var input = $(this).val().length;

      if(input > '3'){
      console.log(input);
      $( '#TagsValue_100' ).val('\$a ');$('#100').find('input,a').attr('disabled',true).removeAttr('data-toggle');
      $( '#TagsValue_110' ).val('\$a ');$('#110').find('input,a').attr('disabled',true).removeAttr('data-toggle');
      }else{
      console.log('111');
      $('#100').find('input,a').attr({'disabled': false, 'data-toggle': 'modal'});
      $('#110').find('input,a').attr({'disabled': false, 'data-toggle': 'modal'});
      }

    });
  }

});   
   