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

function FormCollection(id,catalogid,refer) {
     isLoading = false;
     if($.ajax({
        type     :"POST",
        cache    : false,
        url  : $("#hdnAjaxUrlFormCollection").val()+'?id='+id+'&catalogid='+catalogid+'&refer='+refer,
        beforeSend : function(){
          $("#modalCollection").html("<center>Loading form12...</center>");
        },
        success  : function(response) {
            $("#modalCollection").html(response);
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
      $("#collection-modal").modal("show");
      $('#collection-modal').removeAttr('tabindex');
      $("#collections-callnumber" ).autocomplete( "option", "appendTo", ".eventInsForm" );
    }
}

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
        $("#article-modal").modal("show");
        $('#article-modal').removeAttr('tabindex');
        $("#article-callnumber" ).autocomplete( "option", "appendTo", ".eventInsForm" );
    }
}

$("#btnAddCollection").click(function(e) {
    FormCollection(0,$("#hdnCatalogId").val(),$('#hdnReferUrl').val());
});

$("#btnAddArticles").click(function(e) {
    FormArticle(0,$("#hdnCatalogId").val(),$('#hdnReferUrl').val());
});
$("#btnAddKontenDigitalArticles").click(function(e) {
    FormArticle(0,$("#hdnCatalogId").val(),$('#hdnReferUrl').val());
});



$('#rekanan-modal-catcoll').on('hidden.bs.modal', function (event) {
  $("body").addClass("skin-blue modal-open");
});


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







    