var afterValidateSubruas, appendIndikator, appendSubruas, moveIndikator, normalizeInputAttribute, removeIndikator, validateIndikator;

validateIndikator = function(form, errors, hasError) {
  var input;
  if (!hasError) {
    input = $('input[type=text]', form);
    appendIndikator(form, input.serializeArray());
    input.val('');
  }
  return false;
};

validateRuas = function(form, errors, hasError) {
  var input;
  if (!hasError) {
    input = $('input[type=text],input[type=checkbox]', form);
    console.log(input.serializeArray());
    appendRuas(form, input.serializeArray());
    input.val('');
    input.removeAttr('checked');
  }
  return false;
};


afterValidateSubruas = function(form, errors, hasError) {
  if (!hasError) {
    appendSubruas(form);
  }
  return false;
};

appendIndikator = function(form, data) {
  var countRow, empty, i, input, len, obj, target, td, tr;
  $(form).parent().parent().modal('hide');
  target = $(form).data('target');
  if (typeof target === "undefined") {
    throw new ReferenceError("jquery data target untuk #" + $(form).attr('id') + " tidak ditemukan.");
  }
  target = document.getElementById(target);
  empty = $('.empty', target);
  if (empty.length > 0) {
    empty.parents('tr').remove();
  }
  target = target.getElementsByTagName('tbody');
  countRow = target[0].getElementsByTagName('tr').length;
  tr = document.createElement('tr');
  td = document.createElement('td');
  td.setAttribute('class', 'span3');
  for (i = 0, len = data.length; i < len; i++) {
    obj = data[i];
    console.log(obj);
    input = document.createElement('input');
    input.setAttribute('name', obj.name.replace('[--n--]', '[' + countRow + ']'));
    input.setAttribute('type', 'text');
    input.setAttribute('class', 'form-control');
    input.setAttribute('value', input.value = obj.value);
    td.appendChild(input);
    tr.appendChild(td);
    td = document.createElement('td');
    td.setAttribute('class', 'span9');
  }
  tr.appendChild($('<td style="text-align:center"><a onclick="js:removeIndikator(this,event);" title="Remove" class ="btn-danger btn-sm" rel="tooltip" href="javascript:void()"><span class="glyphicon glyphicon-remove"></span></a></td>')[0]);
  target[0].appendChild(tr);
  return false;
};

appendRuas = function(form, data) {
  var countRow, empty, i, input, len, obj, target, td, tr, typeinput;
  $(form).parent().parent().modal('hide');
  target = $(form).data('target');
  if (typeof target === "undefined") {
    throw new ReferenceError("jquery data target untuk #" + $(form).attr('id') + " tidak ditemukan.");
  }
  target = document.getElementById(target);
  empty = $('.empty', target);
  if (empty.length > 0) {
    empty.parents('tr').remove();
  }
  target = target.getElementsByTagName('tbody');
  countRow = target[0].getElementsByTagName('tr').length;
  tr = document.createElement('tr');
  td = document.createElement('td');
  for (i = 0, len = 3; i < len; i++) {
    obj = data[i];
    if(obj.name == 'Fielddatas[--n--][Repeatable]' || obj.name == 'Fielddatas[--n--][IsShow]')
    {
        typeinput='checkbox';
    }else{
        typeinput='text';
    }
    input = document.createElement('input');
    input.setAttribute('name', obj.name.replace('[--n--]', '[' + countRow + ']'));
    input.setAttribute('type', typeinput);
    if(typeinput == 'text')
    {
      input.setAttribute('class', 'form-control');
    }
    input.setAttribute('value', input.value = obj.value);
    if(typeinput == 'checkbox')
    {
        if(obj.value == '1')
        {
          input.setAttribute('checked', 'checked');
        }
    }
    td.appendChild(input);
    tr.appendChild(td);
    td = document.createElement('td');
    td.setAttribute('style', 'text-align:center');
  }

 
  //SerializeArray doesn't invoke the checkbox unchecked
  if(data.length < 4)
  {
        input = document.createElement('input');
        input.setAttribute('name', 'Fielddatas[' + countRow + '][Repeatable]');
        input.setAttribute('type',  'checkbox');
        input.setAttribute('value', input.value = '0');
        td.appendChild(input);
        tr.appendChild(td);
        td = document.createElement('td');
        td.setAttribute('style', 'width: 75px;text-align:center');

        input = document.createElement('input');
        input.setAttribute('name', 'Fielddatas['+ countRow + '][IsShow]');
        input.setAttribute('type',  'checkbox');
        input.setAttribute('value', input.value = '0');
        td.appendChild(input);
        tr.appendChild(td);
        td = document.createElement('td');
        td.setAttribute('style', 'width: 75px;text-align:center');
    
  }else if(data.length > 3) {
        obj = data[3];
         console.log(obj);
        if(obj.name == 'Fielddatas[--n--][Repeatable]')
        {
          input = document.createElement('input');
          input.setAttribute('name', 'Fielddatas[' + countRow + '][Repeatable]');
          input.setAttribute('type',  'checkbox');
          input.setAttribute('value', input.value = obj.value);
          if(obj.value == '1')
          {
            input.setAttribute('checked', 'checked');
          }
          td.appendChild(input);
          tr.appendChild(td);
          td = document.createElement('td');
          td.setAttribute('style', 'width: 75px;text-align:center');

          input = document.createElement('input');
          input.setAttribute('name', 'Fielddatas['+ countRow + '][IsShow]');
          input.setAttribute('type',  'checkbox');
          input.setAttribute('value', input.value = '0');
          td.appendChild(input);
          tr.appendChild(td);
          td = document.createElement('td');
          td.setAttribute('style', 'width: 75px;text-align:center');
        }else{
          input = document.createElement('input');
          input.setAttribute('name', 'Fielddatas[' + countRow + '][Repeatable]');
          input.setAttribute('type',  'checkbox');
          input.setAttribute('value', input.value = '0');
          td.appendChild(input);
          tr.appendChild(td);
          td = document.createElement('td');
          td.setAttribute('style', 'width: 75px;text-align:center');

          input = document.createElement('input');
          input.setAttribute('name', 'Fielddatas['+ countRow + '][IsShow]');
          input.setAttribute('type',  'checkbox');
          input.setAttribute('value', input.value = obj.value);
          if(obj.value == '1')
          {
            input.setAttribute('checked', 'checked');
          }
          td.appendChild(input);
          tr.appendChild(td);
          td = document.createElement('td');
          td.setAttribute('style', 'width: 75px;text-align:center');

        }

  }

  tr.appendChild($('<td style="width: 50px;text-align:center"><a onclick="js:removeIndikator(this,event);" title="Remove"  class ="btn-danger btn-sm" rel="tooltip" href="#"><span class="glyphicon glyphicon-remove"></span></a></td>')[0]);
  $('<td style="width: 90px;text-align:center"><a onclick="js:moveIndikator(1,this,event);" title="Up" class ="btn-success btn-sm" rel="tooltip" href="#"><span class="glyphicon glyphicon-arrow-up"></span></a>&nbsp;<a onclick="js:moveIndikator(-1,this,event);" title="Down" class ="btn-success btn-sm" rel="tooltip" href="#"><span class="glyphicon glyphicon-arrow-down"></a></td>').insertBefore($('td:eq(0)', tr));
  target[0].appendChild(tr);
  return false;
};

normalizeInputAttribute = function(input, count) {
  var id, name;
  //name = $(input).attr('name').replace('--n--', count);
  //id = input.attr('id').replace('--n--', count);
  console.log(input);
  name = $(input).attr('name').replace('--n--', count);
  id = input.attr('id').replace('--n--', count);
  input.attr('name', name);
  return input.attr('id', id);
};

appendSubruas = function(form, data) {
  var countRow, empty, target, td, tr;
  $(form).parent().parent().modal('hide');
  target = $(form).data('target');
  if (typeof target === "undefined") {
    throw new ReferenceError("jquery data target untuk #" + $(form).attr('id') + " tidak ditemukan.");
  }
  target = document.getElementById(target);
  empty = $('.empty', target);
  target = target.getElementsByTagName('tbody');
  if (empty.length > 0) {
    empty[0].parentElement.remove();
  }
  countRow = target[0].getElementsByTagName('tr').length;
  tr = document.createElement('tr');
  td = document.createElement('td');
  $.each($('.controls', form), function(k, v) {
    var container, i, input, len, obj, realinput;
    realinput = $(v).find('input');
    input = realinput.clone();
    if (input.length > 1) {
      for (i = 0, len = input.length; i < len; i++) {
        obj = input[i];
        normalizeInputAttribute($(obj), countRow);
      }
      container = document.createElement('div');
      input.appendTo(container);
      input = container;
    } else {
      normalizeInputAttribute(input, countRow);
      input.attr('class', 'span12');
    }
    $(input).appendTo(td);
    tr.appendChild(td);
    td = document.createElement('td');
    return realinput.val('');
  });
  tr.appendChild($('<td style="text-align:center"><a onclick="js:removeIndikator(this,event);" title="Remove"  class ="btn-danger btn-sm" rel="tooltip" href="#"><span class="glyphicon glyphicon-remove"></span></a></td>')[0]);
  $('<td style="text-align:center"><a onclick="js:moveIndikator(1,this,event);" title="Up" class ="btn-success btn-sm" rel="tooltip" href="#"><span class="glyphicon glyphicon-arrow-up"></span></a>&nbsp;<a onclick="js:moveIndikator(-1,this,event);" title="Down" class ="btn-success btn-sm" rel="tooltip" href="#"><span class="glyphicon glyphicon-arrow-down"></a></td>').insertBefore($('td:eq(0)', tr));
  target[0].appendChild(tr);
  return false;
};

removeIndikator = function(context, e) {
  e.preventDefault();
  $(context).parents('tr').remove();
  return false;
};

moveIndikator = function(pos, context, e) {
  var next, prev, tr;
  e.preventDefault();
  tr = $(context).parents('tr');
  prev = tr.prev();
  next = tr.next();
  if (pos === 1 && prev.length !== 0) {
    tr.insertBefore(prev);
  } else if (pos === -1 && next.length !== 0) {
    tr.insertAfter(next);
  }
};