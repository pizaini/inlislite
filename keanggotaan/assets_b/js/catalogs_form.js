function AutoCompleteOn(id,type)
{
  isLoading = false;
  var url;
  if(type=='pengarang')
  {
    url=$("#hdnAjaxUrlTajukPengarang").val();
  }else{
    url=$("#hdnAjaxUrlTajukSubyek").val();
  }
  $(id).autocomplete({
        source: function (request, response) {
              $.ajax({
                  dataType: "json",
                  data: {
                      term: request.term,
                  },
                  type: 'GET',
                  contentType: 'application/json; charset=utf-8',
                  xhrFields: {
                      withCredentials: true
                  },
                  crossDomain: true,
                  cache: true,
                  url: url,
                  success: function (data) {
                      var array = data;

                      //call the filter here
                      var results = $.ui.autocomplete.filter(array, request.term);

                      //limit 10
                      //response(results.slice(0, 10));

                      response(results);
                  },
                  error: function (data) {

                  }
              });
          }
    }).data("uiAutocomplete")._renderItem = function( ul, item ) {
      var t = String(item.value).replace(new RegExp(this.term, "gi"),"<span style='font-weight:bold; text-decoration:underline; background-color:#EBEDED'>$&</span>");
      return $( "<li></li>" )
          .data( "item.autocomplete", item )
          .append( "<a style='background-color:#F0F2F2'>"+ t + "</a>" )
          .appendTo( ul );
    };
}

function AutoCompleteDDC(id)
{
  isLoading = false;
  var url;
  if($("#collectionbiblio-subject").length > 0)
  {
    subject = $("#collectionbiblio-subject").val();
  }else if($("#collectionbiblio-Subject-0").length > 0){
    subject = $("#collectionbiblio-Subject-0").val();
  }
  url=$("#hdnAjaxUrlDDC").val()+'?subject='+subject;

  if(subject != '')
  {


    $(id).autocomplete({
          minLength: 0,
          source: function (request, response) {
                $.ajax({
                    dataType: "json",
                    data: {
                        term: request.term,
                    },
                    type: 'GET',
                    contentType: 'application/json; charset=utf-8',
                    xhrFields: {
                        withCredentials: true
                    },
                    crossDomain: true,
                    cache: true,
                    url: url,
                    success: function (data) {
                        var array = data;

                        //call the filter here
                        var results = $.ui.autocomplete.filter(array, request.term);

                        //limit 10
                        //response(results.slice(0, 10));

                        response(results);
                    },
                    error: function (data) {

                    }
                });
            }
      }).focus(function () {
        $(id).autocomplete("search", "");
      }).click(function () {
          $(id).autocomplete("search", "");
      }).data("uiAutocomplete")._renderItem = function( ul, item ) {
        var t = String(item.value).replace(new RegExp(this.term, "gi"),"<span style='font-weight:bold; text-decoration:underline; background-color:#EBEDED'>$&</span>");
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append( "<a style='background-color:#F0F2F2'>"+ t + "</a>" )
            .appendTo( ul );
    };
  }
}

function AutoSuggestOn(id,type)
{
  isLoading = false;
  var url;
  if(type=='callnumber')
  {
    pilihjudul = $("#hdnPilihJudul").val();
    inputid = $("#hdnCatalogId").val();
    inputfor = $("#hdnFor").val();
    if(pilihjudul)
    {
      url=$("#hdnAjaxUrlAutosuggestCallnumber").val()+'?id='+pilihjudul+'&refer=catalog';
    }else{
      if(inputid)
      {
        if(inputfor === 'cat')
        {
          referinput = 'catalog';
        }
        else
        {
          referinput = 'collection';
        }
        url=$("#hdnAjaxUrlAutosuggestCallnumber").val()+'?id='+inputid+'&refer='+referinput;
      }else{
         return;
      }
    }
  }

  $(id).autocomplete({
        minLength: 0,
        source: function (request, response) {
              $.ajax({
                  dataType: "json",
                  data: {
                      term: request.term,
                  },
                  type: 'GET',
                  contentType: 'application/json; charset=utf-8',
                  xhrFields: {
                      withCredentials: true
                  },
                  crossDomain: true,
                  cache: true,
                  url: url,
                  success: function (data) {
                      var array = data;

                      //call the filter here
                      var results = $.ui.autocomplete.filter(array, request.term);

                      //limit 10
                      //response(results.slice(0, 10));

                      response(results);
                  },
                  error: function (data) {
                  }
              });
          }
    }).focus(function () {
        $(id).autocomplete("search", "");
    }).click(function () {
        $(id).autocomplete("search", "");
    }).data("uiAutocomplete")._renderItem = function( ul, item ) {
      var t = String(item.value).replace(new RegExp(this.term, "gi"),"<span style='font-weight:bold; text-decoration:underline; background-color:#FAE1B6'>$&</span>");
      return $( "<li></li>" )
          .data( "item.autocomplete", item )
          .append( "<a style='background-color:#FCEBCC'>" + t + "</a>" )
          .appendTo( ul );
    };
}


$('#pilihsalin-modal').on('show.bs.modal', function (event) {
        isLoading = false;
        var button = $(event.relatedTarget)
        var modal = $(this)
        var title = button.data('title') 
        var href = button.attr('href') 
        var forform = $('#hdnFor').val();
        var params = {};
        params['for'] = forform;
        modal.find('.modal-title').html(title)
        modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
        $.get(href,params)
            .done(function( data ) {
                modal.removeAttr('tabindex');
                modal.find('.modal-body').html(data)
            });
});


function BibliografisToogleForm(mode) {
  var wksid= $("#catalogs-worksheet_id").val();
  isLoading = true;
  if(mode == "simple")
  {
    $("#modeform").val("0");
    $("#btn-change-simple").hide();
    $("#btn-change-advance").show();
    $("#simple").show();
    $("#advance").hide();
    $.ajax({
          type     :"POST",
          cache    : false,
          url  : $("#hdnAjaxUrlFormSimple").val()+"?worksheetid="+wksid+"&for="+$("#hdnFor").val()+"&rda="+$("#hdnRda").val(),
          data: $("#entryBibliografi :input").serialize(),
          success  : function(response) {
              $("#entryBibliografi").html(response);
          }
      });
  }
  else
  {
    $("#modeform").val("1");
    $("#btn-change-simple").show();
    $("#btn-change-advance").hide();
    $("#simple").hide();
    $("#advance").show();
    $.ajax({
          type     :"POST",
          cache    : false,
          url  : $("#hdnAjaxUrlFormAdvance").val()+"?worksheetid="+wksid+"&for="+$("#hdnFor").val()+"&rda="+$("#hdnRda").val(),
          data: $("#entryBibliografi :input").serialize(),
          success  : function(response) {
              $("#entryBibliografi").html(response);
          }
      });
  } 
}

function isArrayEmpty(array) {
    return array.every(function(el) {
        return jQuery.isEmptyObject(el);
    });
}

function ValidateSimpleForm(forinput,status)
{
  //for simple form
   var no=0;
  var prefixText = "collectionbiblio-";
  var compareEmpty = "";
  if($("#hdnRda").val() === "1")
  {
    var tags = ["245","246","740","100","700","260","300","336","337","338","250","082","084","020","022","650","520"];
  }else{
    var tags = ["245","246","740","100","700","260","300","250","082","084","020","022","650","520"];
  }

  var textfocus = "";
  var arrayLength = tags.length;
  for (var i = 0; i < arrayLength; i++) {

    if($("#status-"+tags[i]).hasClass("required"))
    {
      if(tags[i]=='260' || tags[i]=='300')
      {
        var data = $("#status-"+tags[i]+" input[type=text]").map(function(){return this.value;}).get();
        if(isArrayEmpty(data))
        {
            $("#status-"+tags[i]).removeClass().addClass("required has-error");
            $("#error-"+tags[i]).html($("#message-"+tags[i]).val());
            no++;
            if(textfocus=="")
            {
              textfocus="#status-"+tags[i]+" :input[type=text]";
            }
        }
      }else{
        if($("#status-"+tags[i]+" :input[type=text]").val().trim() === compareEmpty)
        {
            $("#status-"+tags[i]).removeClass().addClass("required has-error");
            $("#error-"+tags[i]).html($("#message-"+tags[i]).val());
            no++;
            if(textfocus=="")
            {
              textfocus="#status-"+tags[i]+" :input[type=text]";
            }
        }else{
            $("#status-"+tags[i]).removeClass().addClass("required");
            $("#error-"+tags[i]).html("");
        }
      }
      
    }else{
      $("#status-"+tags[i]).removeClass();
      $("#error-"+tags[i]).html("");
    }
    
  }

  
  
  if(no==0)
  {
    if(forinput != 'coll' && status != 'update')
    {
      ValidateDuplicateSimpleForm();
    }else{
      $("#mainForm").submit(); 
    }
  }else{
    var strLength= $(textfocus).val().length;
    $(textfocus).focus();
    $(textfocus)[0].setSelectionRange(strLength, strLength);
  }
}

function ValidateAdvanceForm(forinput,status)
{
  //for advance form
  var no=0;
  var prefixText = "TagsValue_";
  var compareEmpty = "";
  var textfocus = "";
  $.each($('span[id^="status_"]'), function() {             
      status = $(this).attr('id');
      tag = status.substr(7);
      if($("#"+status).hasClass("required"))
      {
        $.each($(':input[id^="'+prefixText+tag+'"]'), function() {
          textid = $(this).attr('id');
          if($("#"+textid).length > 0){
            //regex for bypass $a,$b,$c, etc
            if($.trim($("#"+textid).val().replace(/(\$\w)(.*?)(\$?)/, '')) === compareEmpty)
            {
                $("#"+status).removeClass().addClass("required has-error");
                $("#error_"+tag).html($("#message_"+tag).val());
                no++;
                if(textfocus=="")
                {
                  textfocus="#"+textid;
                }
            }else{
                $("#"+status).removeClass().addClass("required");
                $("#error_"+tag).html("");
            }
          }
        });
      }else{
        $("#"+status).removeClass();
        $("#error_"+tag).html("");
      }
  });

  if(no==0)
  {
    if(forinput != 'coll' && status != 'update')
    {
      ValidateDuplicateAdvanceForm();
    }else{
      $("#mainForm").submit(); 
    }
    
  }else{
    var strLength= $(textfocus).val().length;
    $(textfocus).focus();
    $(textfocus)[0].setSelectionRange(strLength, strLength);
  }
}


function ValidateDuplicateSimpleForm()
{
  var t245a = $('#collectionbiblio-title').val();
  var t260a = $('#collectionbiblio-publishlocation-0').val();
  var t260b = $('#collectionbiblio-publisher-0').val();
  var t260c = $('#collectionbiblio-publishyear-0').val();
  var catalogid= $("#hdnCatalogId").val();
  if($.isEmptyObject(catalogid))
  {
    catalogid = '';
  }
  $.ajax({
      type     :"POST",
      cache    : false,
      beforeSend : function(){
        startLoading();
      },
      url  : $("#hdnAjaxUrlCheckDuplicate").val(),
      data: {
                catalogid : catalogid,
                t245a : t245a,
                t260a : t260a,
                t260b : t260b,
                t260c : t260c,
            },
      success  : function(response) {
          endLoading();
          if(response != 'success')
          {
            $("#msgform").html('Terdapat duplikasi pada Judul utama dan Penerbitan!');
            $("#msgform").show();
            $('html, body').animate({
                scrollTop: 0
            }, 500);
            $("#msgform").fadeOut('slow', function(){
                $(this).fadeIn('slow', function(){
                    $(this).fadeOut('slow', function(){
                        $(this).fadeIn('slow');
                    });
                });
            });
            return;
          }else{
            $("#mainForm").submit(); 
          }
      },
      error : function(){
        endLoading();
      },
  });
}

function ValidateDuplicateAdvanceForm()
{
  var t245 = $('#TagsValue_245').val();
  var t260 = $('#TagsValue_260_0').val();
  if($.isEmptyObject(t260))
  {
    t260 = $('#TagsValue_264_0').val();
  }

  t245_all = t245.split("$");
  t245a= '';
  for (var i = 0; i < t245_all.length; i++) {
    if($.trim(t245_all[i]).substr(0,1) === "a")
    {
      t245a=t245_all[i].replace(/^\s+/,"").substr(1,t245_all[i].replace(/^\s+/,"").length - 1);
    }
  }

  t260_all = t260.split("$");
  t260a= '';
  t260b= '';
  t260c= '';
  for (var i = 0; i < t260_all.length; i++) {
    if($.trim(t260_all[i]).substr(0,1) === "a")
    {
      t260a=t260_all[i].replace(/^\s+/,"").substr(1,t260_all[i].replace(/^\s+/,"").length - 1);
    }

    if($.trim(t260_all[i]).substr(0,1) === "b")
    {
      t260b=t260_all[i].replace(/^\s+/,"").substr(1,t260_all[i].replace(/^\s+/,"").length - 1);
    }

    if($.trim(t260_all[i]).substr(0,1) === "c")
    {
      t260c=t260_all[i].replace(/^\s+/,"").substr(1,t260_all[i].replace(/^\s+/,"").length - 1);
    }
  }

  var catalogid= $("#hdnCatalogId").val();
  if($.isEmptyObject(catalogid))
  {
    catalogid = '';
  }

  $.ajax({
      type     :"POST",
      cache    : false,
      beforeSend : function(){
        startLoading();
      },
      url  : $("#hdnAjaxUrlCheckDuplicate").val(),
      data: {
                catalogid : catalogid,
                t245a : t245a.replace(/^\s+/,""),
                t260a : t260a.replace(/^\s+/,""),
                t260b : t260b.replace(/^\s+/,""),
                t260c : t260c.replace(/^\s+/,""),
            },
      success  : function(response) {
          endLoading();
          if(response != 'success')
          {
            $("#msgform").html('Terdapat duplikasi pada Tag 245 ( a ) dan Tag 260 / 264  ( a,b,c )!');
            $("#msgform").show();
            $('html, body').animate({
                scrollTop: 0
            }, 500);
            $("#msgform").fadeOut('slow', function(){
                $(this).fadeIn('slow', function(){
                    $(this).fadeOut('slow', function(){
                        $(this).fadeIn('slow');
                    });
                });
            });
            return;
          }else{
            $("#mainForm").submit(); 
          }
      },
      error : function(){
        endLoading();
      },
  });
}

function ValidationBibliografis()
{
    if($("#hdnPilihJudul").val() !== "")
    {
      $("#mainForm").submit(); 
    }else{
      if($("#modeform").val() === "1")
      {
        ValidateAdvanceForm($("#hdnFor").val(),$("#hdnCrudmode").val());
      }else{
        ValidateSimpleForm($("#hdnFor").val(),$("#hdnCrudmode").val());
      }
      
    }
      
}

$("#listNoInduk").ready(function(){
    $("#collections-jumlaheksemplar").val(1);
    isLoading = false;
    $.ajax({
        type     :"POST",
        cache    : false,
        url  :  $("#hdnAjaxUrlNoInduk").val()+"?tglpengadaan=&eks=1",
        success  : function(response) {
            $("#listNoInduk").html(response);
        }
    });
});

function RenderNoInduk(){
     isLoading = true;
     var jumlahEks = $("#collections-jumlaheksemplar").val();
     var tglPengadaan = $("#collections-tanggalpengadaan").val();
     $.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlNoInduk").val()+"?tglpengadaan="+tglPengadaan+"&eks="+jumlahEks,
        success  : function(response) {
            $("#listNoInduk").html(response);
        }
    });
}

function AddPartners(){
    isLoading = false;
    if($.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlPartner").val()+"?id=&edit=0&catcoll=0",
        success  : function(response) {
            $("#modalPartners").html(response);
        }
    }))
    {
      $("#rekanan-modal").modal("show");
      $('#rekanan-modal').removeAttr('tabindex');
    }
}

function EditPartners(){
    isLoading = false;
    var pId = $("#collections-partner_id").val();
    if(pId != "")
    {
      $.ajax({
          type     :"POST",
          cache    : false,
          url  : $("#hdnAjaxUrlPartner").val()+"?id="+pId+"&edit=1&catcoll=0",
          success  : function(response) {
              $("#modalPartners").html(response);
          }
      });
      $("#rekanan-modal").modal("show");
      $('#rekanan-modal').removeAttr('tabindex');
    }
}


function AutoCopyCallNumber(id)
{
  title =  $("#collectionbiblio-title").val();
  author =  $("#collectionbiblio-author").val();
  ddc = $("#collectionbiblio-class").val();

  title_a = title;
  title_cek = title_a.substr(0,1);

  var j245 = title_cek;
  switch (title_cek) {
      case '1':
          if(author == ''){
              j245 = 'SAT';    
          }else{
              j245 = 's';
          }
          break;
      case '2':
          if(author == ''){
              j245 = 'DUA';    
          }else{
              j245 = 'd';
          }
          break;
      case '3':
          if(author == ''){
              j245 = 'TIG';    
          }else{
              j245 = 't';
          }
          break;
      case '4':
          if(author == ''){
              j245 = 'EMP';    
          }else{
              j245 = 'e';
          }
          break;
      case '5':
          if(author == ''){
              j245 = 'LIM';    
          }else{
              j245 = 'l';
          }
          break;
      case '6':
          if(author == ''){
              j245 = 'ENA';    
          }else{
              j245 = 'e';
          }
          break;
      case '7':
          if(author == ''){
              j245 = 'TUJ';    
          }else{
              j245 = 't';
          }
          break;
      case '8':
          if(author == ''){
              j245 = 'DEL';    
          }else{
              j245 = 'd';
          }
          break;
      case '9':
          if(author == ''){
              j245 = 'SEM';    
          }else{
              j245 = 's';
          }
          break;
  }

  callnumber = $(id).val();


  result='';
  title1char ='';
  author3char ='';

  
  if(callnumber==="" && ddc !== "")
  {
    result += ddc;
    if(author !== "" && author.length > 2){
        author3char =  author.substr(0,3).toUpperCase();
        result += " "+author3char

        if(title_cek !== j245){
            title1char =  j245;
            result += " "+title1char
        }else{
            title1char =  title.substr(0,1).toLowerCase();
            result += " "+title1char
        }
    }else{
        author3char =  '';
        result += " "+author3char

        if(title_cek !== j245 && title_cek.length > 0){
            title1char =  j245;
            result += ""+title1char
        }else{
            title1char =  title.substr(0,3).toUpperCase();
            result += ""+title1char
        }
    }
    // if(author !=="" && author.length > 2)
    // {
    //     author3char =  author.substr(0,3).toUpperCase();
    //     result += " "+author3char

    //     if(title !=="" && title.length > 0)
    //     {
    //         title1char =  title.substr(0,1).toLowerCase();
    //         result += " "+title1char
    //     }
    // }
    $(id).val(result);
  }

}

function AutoCopyCallNumberAdvance(id)
{
  
  callnumber = $(id).val().split("$");
  callnumber_a= '';
  for (var i = 0; i < callnumber.length; i++) {
    if($.trim(callnumber[i]).substr(0,1) === "a")
    {
      callnumber_a=$.trim($.trim(callnumber[i]).substr(1,callnumber[i].length - 1));
    }
  }


  ddc = $("#TagsValue_082_0").val().split("$");
  ddc_a= '';
  for (var i = 0; i < ddc.length; i++) {
    if($.trim(ddc[i]).substr(0,1) === "a")
    {
      ddc_a=$.trim($.trim(ddc[i]).substr(1,ddc[i].length - 1));
    }
  }


  author =  $("#TagsValue_100").val().split("$");
  author_a= '';
  for (var i = 0; i < author.length; i++) {
    if($.trim(author[i]).substr(0,1) === "a")
    {
      author_a=$.trim($.trim(author[i]).substr(1,author[i].length - 1));
    }
  }

  title =  $("#TagsValue_245").val().split("$");
  title_a= '';
  for (var i = 0; i < title.length; i++) {
    if($.trim(title[i]).substr(0,1) === "a")
    {
      title_a=$.trim($.trim(title[i]).substr(1,title[i].length - 1));
    }
  }

  title_a = title_a;
  title_cek = title_a.substr(0,1);
  
  var j245 = title_cek;
  switch (title_cek) {
      case '1':
          if(author_a == ''){
              j245 = ' SAT';    
          }else{
              j245 = ' s';
          }
          break;
      case '2':
          if(author_a == ''){
              j245 = ' DUA';    
          }else{
              j245 = ' d';
          }
          break;
      case '3':
          if(author_a == ''){
              j245 = ' TIG';    
          }else{
              j245 = ' t';
          }
          break;
      case '4':
          if(author_a == ''){
              j245 = ' EMP';    
          }else{
              j245 = ' e';
          }
          break;
      case '5':
          if(author_a == ''){
              j245 = ' LIM';    
          }else{
              j245 = ' l';
          }
          break;
      case '6':
          if(author_a == ''){
              j245 = ' ENA';    
          }else{
              j245 = ' e';
          }
          break;
      case '7':
          if(author_a == ''){
              j245 = ' TUJ';    
          }else{
              j245 = ' t';
          }
          break;
      case '8':
          if(author_a == ''){
              j245 = ' DEL';    
          }else{
              j245 = ' d';
          }
          break;
      case '9':
          if(author_a == ''){
              j245 = ' SEM';    
          }else{
              j245 = ' s';
          }
          break;
  }

  result='';
  title1char ='';
  author3char ='';

  
  if(callnumber_a==="" && ddc_a !== "")
  {
    result += "$a "+ddc_a;
    if(author_a !== "" && author_a.length > 2){
        author3char =  author_a.substr(0,3).toUpperCase();
        result += " "+author3char

        if(title_cek !== j245){
            title1char =  j245;
            result += " "+title1char
        }else{
            title1char =  title_a.substr(0,1).toLowerCase();
            result += " "+title1char
        }
    }else{
        author3char =  '';
        result += " "+author3char

        if(title_cek !== j245 && title_cek.length > 0){
            title1char =  j245;
            result += " "+title1char
        }else{
            title1char =  title_a.substr(0,3).toUpperCase();
            result += ""+title1char
        }
    }
    // result += "$a "+ddc_a;
    // if(author_a !=="" && author_a.length > 2)
    // {
    //     author3char =  author_a.substr(0,3).toUpperCase();
    //     result += " "+author3char

    //     if(title_a !=="" && title_a.length > 0)
    //     {
    //         title1char =  title_a.substr(0,1).toLowerCase();
    //         result += " "+title1char
    //     }
    // }
    $(id).val(result);
  }

}
