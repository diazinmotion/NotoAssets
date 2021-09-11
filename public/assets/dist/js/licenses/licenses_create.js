$(document).ready(function(){  
  _reInitialize();
  _show_popup();
});

$(document).on('click', '.btn-submit', function(){
  $('.main-form').submit();
});

$(document).on('click', '.btn-seat-delete', function(){
  var data_id       = $(this).data('id');
  var index         = $('.btn-license-delete').index(this);
  var cur_installed = parseInt($('#meta-installed').text());

  Swal.fire({
      title: "Are you sure for this action?",
      text: "This action cannot be undone.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: "Yes",
  }).then((r) => {
    if(r.isConfirmed) {
      $.post(module_url + '/ajax_delete_seat', { id: data_id }, function (d) {
        if (d.status) {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'The item has been deleted.',
            showConfirmButton: true,
            timer: 3000
          });
          
          // delete table
          $('#table-seats').find('tr').eq(index).remove();
          cur_installed--;

          $('#meta-installed').text(cur_installed);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Cant delete this item at this moment.',
            showConfirmButton: true,
            timer: 3000
          });
        }
      });
    }
  });
});

function _check_seat_table(){
  if($('#table-seats > tr').length == 0){
    $('#table-seats').html('<tr><td colspan="5"><center>This license has not been installed on any assets.</center></td></tr>');
  }
}

function _show_popup(){
  // hanya tampilkan pesan apabila telah di save
  if(has_save){
    if(popup_msg != ''){
      Swal.fire({
        icon: 'error',
        title: 'Error',
        html: popup_msg,
        showConfirmButton: true,
        timer: 3000
      });
    }else{
      Swal.fire({
        icon: 'success',
        title: 'Success',
        html: 'Your data has been saved.',
        showConfirmButton: true,
        timer: 3000
      });
    }
  }
}

function _reInitialize(){
  $('.select2-general').select2({
    width:'100%'
  });

  $('.dtp').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'dd-mm-yyyy'
  });

  $('.dtp-max-today').datepicker({
    autoclose: true,
    endDate: new Date(),
    todayHighlight: true,
    format: 'dd-mm-yyyy'
  });

  $('.software_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'master/software/ajax_get_software',
      dataType: 'json',
      type: "POST",
      data: function (params) {
        var queryParameters = {
          param: params.term,
        }

        return queryParameters;
      },
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              id: item.id,
              text: item.text
            }
          })
        };
      }
    }
  });

  $('select[name="is_bulk_license"]').select2({
    tags: false,
    width: '100%',
  }).on('select2:select', function(){
    if($(this).val() == 0){
      // SINGLE
      $('input[name="universal_product_key"]').val('').prop('disabled', true);
    }else{
      // BULK
      $('input[name="universal_product_key"]').val('').prop('disabled', false);
    }
  });

  $('select[name="flag_permanent"]').select2({
    tags: false,
    width: '100%',
  }).on('select2:select', function(){
    if($('select[name="is_bulk_license"]').val() == 1 && $(this).val() == 0){
      // SUBS
      $('input[name="universal_expired_at"]').val('').prop('disabled', false);
    }else{
      // PERMANENT
      $('input[name="universal_expired_at"]').val('').prop('disabled', true);
    }
  });

  if(! is_edit){
    $('select[name="is_bulk_license"], select[name="flag_permanent"]').trigger('select2:select');
  }
}