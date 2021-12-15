/*!
 * @package   yii2-activeresponse
 * @author    Sergey Rusakov <srusakov@gmail.com>
 * @copyright Copyright &copy; Sergey Rusakov, 2015
 * @version   1.0
 *
 * Yii2 ajax module with active control from server side
 *
 * Built for Yii Framework 2.0
 */


/**
 * Call ActiveResponse PHP controller
 * @param {string} controller/action to call
 * @param {string|object} value $('#'+form_name).serialize() or serializeObject
 * @param {function} callback
 */
function callAR(href, value, callback) {
	// try to autodiscover current PHP file
	if (href === null) {
		href = location.href;
		if ((i = href.indexOf('?')) > 0 ) href = href.substring(0, i);
		if ((i = href.indexOf('&')) > 0 ) href = href.substring(0, i);
	}
    if (value === null || typeof(value) === 'undefined') {
        value = 'callAR=1';
    } else if ( typeof(value) === "object") {
        value.callAR = 1;
    } else {
		value = 'callAR=1&' + value;
	}
    
	// Do AJAX request
	$.ajax({
		type: 'POST',
		url: href,
		cache: false,
		dataType: 'json',
		data: value,
		success: function(json, textStatus) {
			if(json.error !== null ) { 
				alert('Error:' + json.error);
			} else {	/* success response. Interpret it */
				$.each(json.actions, function(i,action) {
					if      (action.act === 'alert')    {alert(action.msg);}
					else if (action.act === 'val')      {$('#'+action.item).val(action.val);}
					else if (action.act === 'html')     {$('#'+action.item).html(action.val);}
                    else if (action.act === 'data')     {$('#'+action.item).data(action.val);}
					else if (action.act === 'attr')     {$('#'+action.item).attr(action.attr, action.val);}
					else if (action.act === 'redirect') {location.href = action.href;}
					else if (action.act === 'script')   {eval(action.script);}
					else if (action.act === 'css')      {$('#'+action.item).acc(action.cssattr, action.val);}
					else if (action.act === 'notify')   {$.notify.add(action.msg, action.addClass, action.seconds);}
					else {alert('Unknown action: '+action.act);}
				});
				if (typeof(callback) === 'function') {
                    callback( json.return2callback );
                } else {
                    eval(callback);
                }
			}
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {


			alert('response:' + strip_tags(XMLHttpRequest.responseText)+' error:'+textStatus + '. thrown:' + errorThrown);
		}
	});
}


/**
 * Remove HTML tags from string
 * @param {string} str html string
 */
function  strip_tags(str) {
    return str.replace(/<\/?[^>]+>/gi, '');
}


/**
* $('#theForm').seralizeObject()
 * @return {object} object with form data
 */
$.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if (o[this.name]) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};