function AddPublication(tag) {
  var html = [];
  var sort = $("#PublicationAddCount").val();
  if(sort != '')
  {
    sort = parseInt(sort)+1;
  }
  $("#PublicationAddCount").val(sort);
  html.push("<div id='DivPublication"+sort+"' style='margin-top:15px;'>");
  html.push("<input type='hidden' id='Ruasid_"+tag+"_"+sort+"' name='Ruasid["+tag+"]["+sort+"]' value='' size='3'>");
        
        html.push("<div class='form-group kv-fieldset-inline'>");
          html.push("<div class='col-sm-12'>");
              html.push("<div class='form-group'>");
                  html.push("<label class='control-label col-sm-2' for='email'><label for='collectionbiblio-publishlocation'>Tempat</label></label>");
                  html.push("<div class='col-sm-6'>");
                    html.push("<input type='text' id='collectionbiblio-publishlocation-"+sort+"' class='form-control' name='CollectionBiblio[PublishLocation]["+sort+"]'  style='width:100%' placeholder='Masukan Tempat Publikasi...'>");
                  html.push("</div>");
                  html.push("<div class='col-sm-4'>");
                    html.push("<button class='btn btn-danger pull-right' type='button' tabindex='-1' onclick='RemovePublication("+sort+","+tag+")'><i class='glyphicon glyphicon-trash'></i></button>");
                  html.push("</div>");
                html.push("</div>");
          html.push("</div>");
        html.push("</div>");

        html.push("<div class='form-group kv-fieldset-inline'>");
          html.push("<div class='col-sm-12'>");
              html.push("<div class='form-group'>");
                  html.push("<label class='control-label col-sm-2' for='email'><label for='collectionbiblio-publisher'>Nama</label></label>");
                  html.push("<div class='col-sm-6'>");
                    html.push("<input type='text' id='collectionbiblio-publisher-"+sort+"' class='form-control' name='CollectionBiblio[Publisher]["+sort+"]'  style='width:100%' placeholder='Masukan Nama Penerbit/Distributor/Percetakan...'>");
                  html.push("</div>");;
                html.push("</div>");
          html.push("</div>");
        html.push("</div>");

        html.push("<div class='form-group kv-fieldset-inline' style='border-bottom: solid 2px #EEE'>");
          html.push("<div class='col-sm-12'>");
              html.push("<div class='form-group'>");
                  html.push("<label class='control-label col-sm-2' for='email'><label for='collectionbiblio-publishyear'>Tahun</label></label>");
                  html.push("<div class='col-sm-4'>");
                    html.push("<input type='text' id='collectionbiblio-publishyear-"+sort+"' class='form-control' name='CollectionBiblio[PublishYear]["+sort+"]'  style='width:100%' placeholder='Masukan Tahun Publikasi/Hak cipta...'>  <div>&nbsp;</div>");
                  html.push("</div>");
                html.push("</div>");
          html.push("</div>");
        html.push("</div>");

  html.push("</div>");
  $("#PublicationAddList").append(html.join(''));
  $("#collectionbiblio-publication-"+sort).focus();
}

function AddAuthorAdded() {
  var html = [];
  var sort = $("#AuthorAddCount").val();
  if(sort != '')
  {
    sort = parseInt(sort)+1;
  }
  var relatorterm = $("#collectionbiblio-authoraddedrelatorterm-0").html();
  
  $("#AuthorAddCount").val(sort);
  html.push("<div id='DivAuthorAdded"+sort+"'>");
    if (relatorterm === undefined || relatorterm === null) {
    // aacr mode
    html.push("<div style='margin-top:5px' class='input-group'>")
      html.push("<input type='text' id='collectionbiblio-authoradded-"+sort+"' class='form-control' name='CollectionBiblio[AuthorAdded]["+sort+"]' style='width:100%' placeholder='Masukan Tambahan...'>");
      html.push("<span class='input-group-btn'>");
      html.push("<button class='btn btn-danger' type='button' onclick='RemoveAuthorAdded("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
      html.push("</span>");
    html.push("</div>");
    }else{
    // rda mode
    html.push("<div style='margin-top:5px' class='row'>");
      html.push("<div class='col-sm-3' style='padding-right: 0px'>");
        html.push("<select id='collectionbiblio-authoraddedrelatorterm-"+sort+"' class='form-control' name='CollectionBiblio[AuthorAddedRelatorTerm]["+sort+"]'>"+relatorterm+"</select>");
      html.push("</div>");
      html.push("<div class='col-sm-9' style='padding-left: 0px'>");
        html.push("<div class='input-group'>")
          html.push("<input type='text' id='collectionbiblio-authoradded-"+sort+"' class='form-control' name='CollectionBiblio[AuthorAdded]["+sort+"]' style='width:100%' placeholder='Masukan Tambahan...'>");
          html.push("<span class='input-group-btn'>");
          html.push("<button class='btn btn-danger' type='button' onclick='RemoveAuthorAdded("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
          html.push("</span>");
        html.push("</div>");
      html.push("</div>");
    html.push("</div>");
    }
    html.push("<div class='btm-add-on' style='text-align:left'>");
      html.push("<input type='hidden' name='CollectionBiblio[AuthorAddedType]["+sort+"]' value=''>");
        html.push("<div id='collectionbiblio-authoraddedtype-"+sort+"'>");
        html.push("<label><input  checked type='radio' name='CollectionBiblio[AuthorAddedType]["+sort+"]' value='0'> Nama Depan</label>");
        html.push("&nbsp;<label><input type='radio' name='CollectionBiblio[AuthorAddedType]["+sort+"]' value='1'> Nama Belakang</label>");
        html.push("&nbsp;<label><input type='radio' name='CollectionBiblio[AuthorAddedType]["+sort+"]' value='3'> Nama Keluarga</label>");
        html.push("&nbsp;<label><input type='radio' name='CollectionBiblio[AuthorAddedType]["+sort+"]' value='#'> Badan Korporasi</label>");
        html.push("&nbsp;<label><input type='radio' name='CollectionBiblio[AuthorAddedType]["+sort+"]' value='##'> Nama Pertemuan</label>");
        html.push("</div>");
    html.push("</div>");
  html.push("</div>");
  $("#AuthorAddList").append(html.join(''));
  $("#collectionbiblio-authoradded-"+sort).focus();
  AutoCompleteOn("#collectionbiblio-authoradded-"+sort,'pengarang');
}
  
function AddISBN() {
  var html = [];
  var sort = $("#ISBNCount").val();
  if(sort != '')
  {
    sort = parseInt(sort)+1;
  }
  $("#ISBNCount").val(sort);
  html.push("<div id='DivISBN"+sort+"'>")
    html.push("<div style='margin-top:5px' class='input-group'>");
      html.push("<input type='text' id='collectionbiblio-isbn-"+sort+"' class='form-control' name='CollectionBiblio[ISBN]["+sort+"]' style='width:100%' placeholder='Masukan ISBN...'>");
      html.push("<span class='input-group-btn'>");
      html.push("<button class='btn btn-danger' type='button' onclick='RemoveISBN("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
      html.push("</span>");
    html.push("</div>");
  html.push("</div>");
  $("#ISBNList").append(html.join(''));
  $("#collectionbiblio-isbn-"+sort).focus();
}

function AddISSN() {
  var html = [];
  var sort = $("#ISSNCount").val();
  if(sort != '')
  {
    sort = parseInt(sort)+1;
  }
  $("#ISSNCount").val(sort);
  html.push("<div id='DivISSN"+sort+"'>")
    html.push("<div style='margin-top:5px' class='input-group'>");
      html.push("<input type='text' id='collectionbiblio-issn-"+sort+"' class='form-control' name='CollectionBiblio[ISSN]["+sort+"]' style='width:100%' placeholder='Masukan ISSN...'>");
      html.push("<span class='input-group-btn'>");
      html.push("<button class='btn btn-danger' type='button' onclick='RemoveISSN("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
      html.push("</span>");
    html.push("</div>");
  html.push("</div>");
  $("#ISSNList").append(html.join(''));
  $("#collectionbiblio-issn-"+sort).focus();
}

function AddNote() {
  var html = [];
  var sort = $("#NoteCount").val();
  if(sort != '')
  {
    sort = parseInt(sort)+1;
  }
  $("#NoteCount").val(sort);
  html.push("<div id='DivNote"+sort+"'>")
    html.push("<div style='margin-top:5px' class='input-group'>");
      html.push("<textarea id='collectionbiblio-note-"+sort+"' rows='2' cols='20'  class='form-control' name='CollectionBiblio[Note]["+sort+"]' style='resize: vertical;height:34px;width:100%' placeholder='Masukan Catatan...'></textarea>");
      html.push("<span class='input-group-btn' style='vertical-align:bottom'>");
      html.push("<button class='btn btn-danger' type='button' onclick='RemoveNote("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
      html.push("</span>");
    html.push("</div>");
    html.push("<div class='btm-add-on' style='text-align:left'>");
      html.push("<input type='hidden' name='CollectionBiblio[NoteTag]["+sort+"]' value=''>");
        html.push("<div id='collectionbiblio-notetag-"+sort+"'>");
        html.push("<label><input  checked type='radio' name='CollectionBiblio[NoteTag]["+sort+"]' value='520'> Abstrak / Anotasi</label>");
        html.push("&nbsp;<label><input type='radio' name='CollectionBiblio[NoteTag]["+sort+"]' value='502'> Catatan Disertasi</label>");
        html.push("&nbsp;<label><input type='radio' name='CollectionBiblio[NoteTag]["+sort+"]' value='504'> Catatan Bibliografi</label>");
        html.push("&nbsp;<label><input type='radio' name='CollectionBiblio[NoteTag]["+sort+"]' value='505'> Rincian Isi</label>");
        html.push("&nbsp;<label><input type='radio' name='CollectionBiblio[NoteTag]["+sort+"]' value='500'> Catatan Umum</label>");
        html.push("&nbsp;<label><input type='radio' name='CollectionBiblio[NoteTag]["+sort+"]' value='542'> Informasi Hak Cipta</label>");
        html.push("</div>");
    html.push("</div>");
  html.push("</div>");
  $("#NoteList").append(html.join(''));
  $("#collectionbiblio-note-"+sort).focus();
}

function AddSubject() {
  var html = [];
  var sort = $("#SubjectCount").val();
  if(sort != '')
  {
    sort = parseInt(sort)+1;
  }

  $("#SubjectCount").val(sort);
  html.push("<div id='DivSubject"+sort+"'>")
    html.push("<div class='row' style='margin-top:5px' >");
      html.push("<div class='col-sm-3' style='padding-right: 0px'>");
        html.push("<select id='collectionbiblio-subjecttag-"+sort+"' class='form-control' name='CollectionBiblio[SubjectTag]["+sort+"]' onchange='ShowOptionSubject("+sort+");'>");
          html.push("<option value='650'>Topikal</option>");
          html.push("<option value='600'>Nama Orang</option>");
          html.push("<option value='651'>Nama Geografis</option>");
        html.push("</select>");
      html.push("</div>");
      html.push("<div class='col-sm-9' style='padding-left: 0px'>");
        html.push("<div class='input-group'>");
          html.push("<input type='text' id='collectionbiblio-subject-"+sort+"' class='form-control' name='CollectionBiblio[Subject]["+sort+"]' style='width:100%' placeholder='Masukan Subject...'>");
          html.push("<span class='input-group-btn'>");
          html.push("<button class='btn btn-danger' type='button' onclick='RemoveSubject("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
          html.push("</span>");
        html.push("</div>");
      html.push("</div>");
    html.push("</div>");
    html.push("<div class='btm-add-on' style='text-align:left'>");
      html.push("<input type='hidden' name='CollectionBiblio[SubjectInd]["+sort+"]' value=''>");
      html.push("<div id='collectionbiblio-subjectind-"+sort+"'>");
      html.push("<label id='opt#_"+sort+"'><input checked type='radio' id='subjectind_X_"+sort+"' name='CollectionBiblio[SubjectInd]["+sort+"]' value='#'> Tdk Ada Info Tambahan</label>");
      html.push("&nbsp;<label id='opt0_"+sort+"' style='display: none'><input type='radio' id='subjectind_0_"+sort+"' name='CollectionBiblio[SubjectInd]["+sort+"]' value='0'> Nama Depan</label>");
      html.push("&nbsp;<label id='opt1_"+sort+"' style='display: none'><input type='radio' id='subjectind_1_"+sort+"' name='CollectionBiblio[SubjectInd]["+sort+"]' value='1'> Nama Belakang</label>");
      html.push("&nbsp;<label id='opt3_"+sort+"' style='display: none'><input type='radio' id='subjectind_3_"+sort+"' name='CollectionBiblio[SubjectInd]["+sort+"]' value='3'> Nama Keluarga</label>");
      html.push("</div>");
    html.push("</div>");
  html.push("</div>");
  $("#SubjectList").append(html.join(''));
  $("#collectionbiblio-subject-"+sort).focus();
  AutoCompleteOn("#collectionbiblio-subject-"+sort,'subyek');
}

function AddCallNumber() {
  var html = [];
  var sort = $("#CallNumberCount").val();
  if(sort != '')
  {
    sort = parseInt(sort)+1;
  }
  $("#CallNumberCount").val(sort);
  html.push("<div id='DivCallNumber"+sort+"'>")
    html.push("<div style='margin-top:5px' class='input-group'>");
      html.push("<input type='text' id='collectionbiblio-callnumber-"+sort+"' class='form-control' name='CollectionBiblio[CallNumber]["+sort+"]' style='width:100%' placeholder='Masukan No. Panggil...' onfocus='AutoCopyCallNumber(this)'>");
      html.push("<span class='input-group-btn'>");
      html.push("<button class='btn btn-danger' type='button' onclick='RemoveCallNumber("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
      html.push("</span>");
    html.push("</div>");
  html.push("</div>");
  $("#CallNumberList").append(html.join(''));
  $("#collectionbiblio-callnumber-"+sort).focus();
}

function AddTitleVarian() {
  var html = [];
  var sort = $("#TitleVarianCount").val();
  if(sort != '')
  {
    sort = parseInt(sort)+1;
  }
  $("#TitleVarianCount").val(sort);
  html.push("<div id='DivTitleVarian"+sort+"'>")
    html.push("<div style='margin-top:5px' class='input-group'>");
      html.push("<input type='text' id='collectionbiblio-titlevarian-"+sort+"' class='form-control' name='CollectionBiblio[TitleVarian]["+sort+"]' style='width:100%' placeholder='Masukan Variasi Bentuk Judul...'>");
      html.push("<span class='input-group-btn'>");
      html.push("<button class='btn btn-danger' type='button' onclick='RemoveTitleVarian("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
      html.push("</span>");
    html.push("</div>");
  html.push("</div>");
  $("#TitleVarianList").append(html.join(''));
  $("#collectionbiblio-titlevarian-"+sort).focus();
} 

function AddTitleOriginal() {
  var html = [];
  var sort = $("#TitleOriginalCount").val();
  if(sort != '')
  {
    sort = parseInt(sort)+1;
  }
  $("#TitleOriginalCount").val(sort);
  html.push("<div id='DivTitleOriginal"+sort+"'>")
    html.push("<div style='margin-top:5px' class='input-group'>");
      html.push("<input type='text' id='collectionbiblio-titleoriginal-"+sort+"' class='form-control' name='CollectionBiblio[TitleOriginal]["+sort+"]' style='width:100%' placeholder='Masukan Variasi Bentuk Judul...'>");
      html.push("<span class='input-group-btn'>");
      html.push("<button class='btn btn-danger' type='button' onclick='RemoveTitleOriginal("+sort+")'><i class='glyphicon glyphicon-trash'></i></button>");
      html.push("</span>");
    html.push("</div>");
  html.push("</div>");
  $("#TitleOriginalList").append(html.join(''));
  $("#collectionbiblio-titleoriginal-"+sort).focus();
}  

function RemovePublication(id,tag) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : tag,
                index : id
            },
      success  : function(response) {
         $("#DivPublication"+id).remove();
         var sort = $("#PublicationCount").val();
         if(sort != '')
         {
           sort = parseInt(sort)-1;
         }
         $("#PublicationCount").val(sort);
      }
  });
   
}

function RemoveAuthorAdded(id,tag,index) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : tag,
                index : index
            },
      success  : function(response) {
         $("#DivAuthorAdded"+id).remove();
         var sort = $("#AuthorAddCount").val();
         if(sort != '')
         {
           sort = parseInt(sort)-1;
         }
         $("#AuthorAddCount").val(sort);
      }
  });
}

function RemoveISBN(id) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : '020',
                index : id
            },
      success  : function(response) {
         $("#DivISBN"+id).remove();
         var sort = $("#ISBNCount").val();
         if(sort != '')
         {
           sort = parseInt(sort)-1;
         }
         $("#ISBNCount").val(sort);
      }
  });
   
}

function RemoveISSN(id) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : '022',
                index : id
            },
      success  : function(response) {
         $("#DivISSN"+id).remove();
         var sort = $("#ISSNCount").val();
         if(sort != '')
         {
           sort = parseInt(sort)-1;
         }
         $("#ISSNCount").val(sort);
      }
  });
}

function RemoveNote(id,tag,index) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : tag,
                index : index
            },
      success  : function(response) {
         $("#DivNote"+id).remove();
         var sort = $("#NoteCount").val();
         if(sort != '')
         {
           sort = parseInt(sort)-1;
         }
         $("#NoteCount").val(sort);
      }
  });
   
}

function RemoveSubject(id,tag,index) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : tag,
                index : index
            },
      success  : function(response) {
         $("#DivSubject"+id).remove();
         var sort = $("#SubjectCount").val();
         if(sort != '')
         {
           sort = parseInt(sort)-1;
         }
         $("#SubjectCount").val(sort);
      }
  });
}

function RemoveCallNumber(id) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : '084',
                index : id
            },
      success  : function(response) {
         $("#DivCallNumber"+id).remove();
         var sort = $("#CallNumberCount").val();
         if(sort != '')
         {
           sort = parseInt(sort)-1;
         }
         $("#CallNumberCount").val(sort);
      }
  });
}

function RemoveTitleVarian(id) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : '246',
                index : id
            },
      success  : function(response) {
         $("#DivTitleVarian"+id).remove();
         var sort = $("#TitleVarianCount").val();
         if(sort != '')
         {
           sort = parseInt(sort)-1;
         }
         $("#TitleVarianCount").val(sort);
      }
  });
}

function RemoveTitleOriginal(id) {
  $.ajax({
      type     :"POST",
      cache    : false,
      url  : $("#hdnAjaxUrlRemoveTag").val(),
      data: {
                tag : '740',
                index : id
            },
      success  : function(response) {
         $("#DivTitleOriginal"+id).remove();
         var sort = $("#TitleOriginalCount").val();
         if(sort != '')
         {
           sort = parseInt(sort)-1;
         }
         $("#TitleOriginalCount").val(sort);
      }
  });
}

function ShowOptionSubject(id){
   if($("#collectionbiblio-subjecttag-"+id).val()=="600")
   {
      $("#opt0_"+id).show();
      $("#opt1_"+id).show();
      $("#opt3_"+id).show();
   }else{
      $("#opt0_"+id).hide();
      $("#opt1_"+id).hide();
      $("#opt3_"+id).hide();
      $("#subjectind_X_"+id).prop("checked",true);
   }
                                                                          
}
