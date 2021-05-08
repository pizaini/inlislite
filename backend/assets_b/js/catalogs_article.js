function SaveCollection() {
    isLoading = false;
    $('#form-collection-modal').data('yiiActiveForm').submitting = true;
    $('#form-collection-modal').yiiActiveForm('validate');
}

function SaveArticle() {
    isLoading = false;
    $('#form-article-modal').data('yiiActiveForm').submitting = true;
    $('#form-article-modal').yiiActiveForm('validate');
}

function SaveSerial() {
    isLoading = false;
    $('#form-edisi-serial').data('yiiActiveForm').submitting = true;
    $('#form-edisi-serial').yiiActiveForm('validate');
}

// modelstart edisi serial
function FormEdisiSerial(id,catalogid,refer) {
  isLoading = false;
     if($.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlEdisiSerial").val()+'?id='+id+'&catalogid='+catalogid+'&refer='+refer,
        beforeSend : function(){
          $("#modalEdisiSerial").html("<center>Loading form12...</center>");
        },
        success  : function(response) {
            $("#modalEdisiSerial").html(response);
            if(id == 0)
            {
                $("#collections-jumlaheksemplar").val(1);
                isLoading = false;
                $.ajax({
                    type     :"POST",
                    cache    :false,
                    url  :  $("#hdnAjaxUrlNoInduk").val()+"?tglpengadaan=&eks=1",
                    success  : function(response) {
                        $("#listNoIndukCatColl").html(response);
                    }
                });
            }
        }
    }))
    {
      $("#edisi_serial-modal").modal("show");
      $('#edisi_serial-modal').removeAttr('tabindex');
      $("#collections-callnumber" ).autocomplete( "option", "appendTo", ".eventInsForm" );
    }
}

$("#btnEdisiSerial").click(function(e) {
    FormEdisiSerial(0,$("#hdnCatalogId").val(),$('#hdnReferUrl').val());
});

// modelend edisi serial

// model article
function FormArticle(id,catalogid,refer) {
    isLoading = false;
    if($.ajax({
            type     :"POST",
            cache    : false,
            url  : $("#hdnAjaxUrlFormCollectionArticle").val()+'?id='+id+'&catalogid='+catalogid+'&refer='+refer,
            beforeSend : function(){
                $("#modalArticle").html("<center>Loading form...</center>");
            },
            success  : function(response) {
                $("#modalArticle").html(response);
            }
        }))
    {
        $("#article-modal").modal("show");
        $('#article-modal').removeAttr('tabindex');
        $("#article-callnumber" ).autocomplete( "option", "appendTo", ".eventInsForm" );
    }
}

function FormKontenDigitalArticle(id,catalogid,refer) {
    isLoading = false;
    if($.ajax({
            type     :"POST",
            cache    : false,
            url  : $("#hdnAjaxUrlFormCollectionArticle").val()+'?id='+id+'&catalogid='+catalogid+'&refer='+refer,
            beforeSend : function(){
                $("#modalKontenDigitalArticle").html("<center>Loading form...</center>");
            },
            success  : function(response) {
                $("#modalKontenDigitalArticle").html(response);
            }
        }))
    {
        $("#KontenDigitalArticle-modal").modal("show");
        $('#KontenDigitalArticle-modal').removeAttr('tabindex');
        $("#KontenDigitalArticle-callnumber" ).autocomplete( "option", "appendTo", ".eventInsForm" );
    }
}

$("#btnAddArticles").click(function(e) {
    FormArticle(0,$("#hdnCatalogId").val(),$('#hdnReferUrl').val());
});
$("#btnAddKontenDigitalArticles").click(function(e) {
    FormKontenDigitalArticle(0,$("#hdnCatalogId").val(),$('#hdnReferUrl').val());
});


$('#rekanan-modal-catcoll').on('hidden.bs.modal', function (event) {
  $("body").addClass("skin-blue modal-open");
});
// modelend article


function AddPartnerCatColl() {
  var catId = $("#hdnCatalogId").val();
  var collId = $("#hdnCollectionId").val();
  isLoading = false;
  if($.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlPartner").val()+"?id=&edit=0&catcoll=1&catid="+catId+"&collid="+collId,
      beforeSend : function(){
        $("#modalPartnersCatColl").html("<center>Loading form...</center>");
      },
      success  : function(response) {
          $("#modalPartnersCatColl").html(response);
      }
  }))
  {
    $("#rekanan-modal-catcoll").modal("show");
    $('#rekanan-modal-catcoll').removeAttr('tabindex');
  }
}

function TestAjax() {
    $.pjax.reload({container:"#pjax-collection-partners-catcoll"});
}

function EditPartnerCatColl() {
var pId = $("#collections-partner_id").val();
var catId = $("#hdnCatalogId").val();
var collId = $("#hdnCollectionId").val();
isLoading = false;
    if(pId != "")
    {
      $.ajax({
          type     :"POST",
          cache    : false,
          url  : $("#hdnAjaxUrlPartner").val()+"?id="+pId+"&edit=1&catcoll=1&catid="+catId+"&collid="+collId,
          beforeSend : function(){
            $("#modalPartnersCatColl").html("<center>Loading form...</center>");
          },
          success  : function(response) {
              $("#modalPartnersCatColl").html(response);
          }
      });
      $("#rekanan-modal-catcoll").modal("show");
      $('#rekanan-modal-catcoll').removeAttr('tabindex');
    }
}


function RenderNoIndukCatColl(){
     var jumlahEks = $("#collections-jumlaheksemplar").val();
     var tglPengadaan = $("#collections-tanggalpengadaan").val();
     isLoading = false;
     $.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlNoInduk").val()+"?tglpengadaan="+tglPengadaan+"&eks="+jumlahEks,
        beforeSend : function(){
          $("#listNoIndukCatColl").html("<center>Loading form...</center>");
        },
        success  : function(response) {
            $("#listNoIndukCatColl").html(response);
        }
    });
}







    