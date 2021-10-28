var table_content;

$(document).ready(function(){
  _reInitialize();
});

$(document).on('click', '.btn-item-add', function(){
  $('#modal-add-edit').modal('show');
  _empty_form(false);
});

$(document).on('click', '.btn-item-filter', function(){
  table_content.ajax.reload();
});

$(document).on('click', '.btn-item-submit', function(){
  var form_data = $('.main-form').serialize();

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
      $('.modal-title').find('b').text('Edit Item');
      
      $('input[name="id"]').val(d.data.id);
      $('input[name="name"]').val(d.data.name);
      
      // append item to the selectbox
      $('.main-form').find('.entity_id').html(`<option value="${d.data.entity_id}">(${d.data.entity_code}) ${d.data.entity_name}</option>`);
      $('.main-form').find('.entity_id').val(d.data.entity_id).change();
      
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

function _empty_form(auto_close = true) {
  $('.main-form').find('.select2-general, .entity_id').val('').trigger('change');
  $('.main-form').find(':input').val('');

  if (auto_close) {
    $('#modal-add-edit').modal('hide');
  }
}

function _reInitialize(){
  $('.select2-general').select2({
    theme: 'bootstrap',
    width:'100%'
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