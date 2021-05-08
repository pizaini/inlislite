yii.confirm = function (message, okCallback, cancelCallback) {
   swal({
       title: message,
       type: 'warning',
       showCancelButton: true,
       closeOnConfirm: true,
       allowOutsideClick: true
   }, okCallback);
};
