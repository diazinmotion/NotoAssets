var table_content;

$(document).ready(function(){
  _reInitialize();
});

$(document).on('click', '.btn-item-add', function(){
  $('#modal-add-edit').modal('show');
  
  $('.main-form').find(':input').prop('disabled', false);
  $('.main-form').find('.laptop_id').prop('disabled', false);
  $('.btn-item-submit').show();

  _empty_form(false);
});

$(document).on('click', '.btn-item-filter', function(){
  table_content.ajax.reload();
});

$(document).on('click', '.btn-item-submit', function(){
  var form_data = $('.main-form').serialize();
  var has_done  = $('input[name="service_end"]').val();

  // bila tanggal selesai diisi maka tampilkan konfirmasi
  if(has_done != '' && has_done != null){
    Swal.fire({
      title: "Are you sure for this action?",
      text: "End date has been filled mean the service proccess has been done. System will close this service.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: "Yes",
    }).then((r) => {
      if(r.isConfirmed) {
        _form_submit(form_data);
      }
    });
  }else{
    _form_submit(form_data);
  }
});

$(document).on('click', '.btn-item-edit', function () {
  var data_id = $(this).data('id');

  $.blockUI({
    message: '<i class="fa fa-spin fa-spinner fa-fw"></i> Processing Request. Please wait.',
    css: {
      border: 'none',
      backgroundColor: 'none',
      color: '#fff'
    }
  });

  $.post(module_url + '/ajax_get_item', { id: data_id }, function (d) {
    if (d.status) {      
      $('.modal-title').find('b').text('Update Service');
      
      $('input[name="id"]').val(d.data.id);
      $('input[name="purposes"]').val(d.data.purposes);
      $('textarea[name="location"]').val(d.data.service_location);
      $('input[name="pic_name"]').val(d.data.pic_name);
      $('input[name="pic_contact"]').val(d.data.pic_contact);
      $('input[name="ticket_it"]').val(d.data.ticket_it);
      $('input[name="ticket_ga"]').val(d.data.ticket_ga);
      $('input[name="service_start"]').val(d.data.service_start);
      $('input[name="service_end"]').val(d.data.service_end);
      $('textarea[name="description"]').val(d.data.description);
      
      // show end date
      $('.end-date-box').show();
      
      // append item to the selectbox
      $('.main-form').find('.laptop_id').html(`<option value="${d.data.laptop_id}">${d.data.laptop_code} / ${d.data.laptop_name} (${d.data.brand_name} ${d.data.model_name})</option>`);
      $('.main-form').find('.laptop_id').val(d.data.laptop_id).change();

      if($('input[name="service_end"]').val() != '' && $('input[name="service_end"]').val() != null){
        // hide submit and save for this item
        $('.main-form').find(':input').prop('disabled', true);
        $('.main-form').find('.laptop_id').prop('disabled', true);
        $('.btn-item-submit').hide();
      }else{
        $('.main-form').find(':input').prop('disabled', false);
        $('.main-form').find('.laptop_id').prop('disabled', false);
        $('.btn-item-submit').show();
      }
      
      $.unblockUI();
      $('#modal-add-edit').modal('show');
    }
  });
});

$(document).on('click', '.btn-item-delete', function () {
  var data_id = $(this).data('id');

  Swal.fire({
      title: "Are you sure for this action?",
      text: "This action cannot be undone.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: "Yes",
  }).then((r) => {
    if(r.isConfirmed) {
      $.post(module_url + '/ajax_delete_item', { id: data_id }, function (d) {
        if (d.status) {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'The item has been deleted.',
            showConfirmButton: true,
            timer: 3000
          });
          table_content.ajax.reload();
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

function _form_submit(form_data = null){
  $.ajax({
    url: module_url + "/ajax_post_form",
    type: "POST",
    data: form_data,
    dataType: "json",
    success: function (d) {
      if (d.status) {
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'The item has been saved.',
          showConfirmButton: true,
          timer: 3000
        });

        _empty_form();

        table_content.ajax.reload();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          html: d.msg,
          showConfirmButton: true,
          timer: 3000
        });
      }
    },
    error: function (e) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        html: 'Unexpected Error',
        showConfirmButton: true,
      });
      console.log('ERROR', e.responseText);
    }
  });
}

function _empty_form(auto_close = true) {
  $('.main-form').find('.select2-general, .laptop_id').val('').trigger('change');
  $('.main-form').find(':input').val('');

  if (auto_close) {
    $('#modal-add-edit').modal('hide');
  }
}

function _reInitialize(){
  $('.select2-general').select2({
    width:'100%'
  });

  $('.dtp, .software_expired_at').datepicker({
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

  $('.laptop_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'assets/laptop/ajax_get_laptop',
      dataType: 'json',
      type: "POST",
      data: function (params) {
        var queryParameters = {
          param: params.term
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

  // dapatkan kolom tabel
  var columns = $.map(table_columns, function (v) {
    return { "data": v };
  });

  table_content = $('#table-content').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "oLanguage": {
      "sSearch": "Keyword&nbsp;&nbsp;"
    },
    "ajax": {
      "url": table_url,
      "type": "POST",
      "data": (d) => {
        d.filter = $('#filter-form').serialize();
      }
    },
    "columns": columns,
  });

  $('.entity_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'master/entity/ajax_get_entity',
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
}