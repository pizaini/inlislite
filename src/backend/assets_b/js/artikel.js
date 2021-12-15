// model article
function FormArticle(id,catalogid,type,edit) {
    // alert(edit)
    

    isLoading = false;
    if($.ajax({
            type     :"POST",
            cache    : false,
            url  : $("#hdnAjaxUrlFormCollectionArticle").val()+'?id='+id+'&catalogid='+catalogid+'&type='+type,
            beforeSend : function(){
                $("#modalArticle").html("<center>Loading form...</center>");
            },
            success  : function(response) {
                
                $("#modalArticle").html(response);
                $("#modalArticle").css({
                    'height':  '500px',
                    'overflow-y': 'auto'
                });
            }
        }))
    {
        $("#article-modal").modal("show");
        $('#article-modal').removeAttr('tabindex');
        $('#article-modal').css('overflow','hidden');
        $("#article-callnumber" ).autocomplete( "option", "appendTo", ".eventInsForm" );
        
    }
    
}

$("#btnAddArticles").click(function(e) {
    FormArticle(0,0,0,0);
});

$("#btnAddArticlesTerbitan").click(function(e) {
    FormArticle(0,0,1,0);
});

$("#btnAddDigitalArticles").click(function(e) {
	// alert($('#Articles_id').val())
    window.location.href = $('#hdnAjaxUrlFormDigitalArticle').val()+'?id=0&catalogId=0';
});

