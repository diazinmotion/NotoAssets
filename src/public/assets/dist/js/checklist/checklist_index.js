var table_content;
var html_task_input;

$(document).ready(function(){
  html_task_input = $('.tasks-box').clone();
  $('.tasks-box').empty();
  
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
      $('.modal-title').find('b').text('Edit Checklist');

      $('input[name="id"]').val(d.data[0].checklist_id);
      $('input[name="name"]').val(d.data[0].checklist_name);

      // append item to the selectbox
      $('.tasks-box').empty();

      // loop data task dan append ke dalam tasks box
      $.each(d.data, function(_, v){
        var row = html_task_input;
        row.find('.task_name').attr('name', `task_name[${v.id}]`);
        row.find('.task_name').attr('value', v.name);

        $('.tasks-box').append(row.html());
      });

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

// START TASK
$(document).on('click', '.btn-task-add', function(){
  var new_row = html_task_input;
  new_id      = 'new_' + _make_id(3);
  new_row.find('.task_name').attr('name', `task_name[${new_id}]`);
  new_row.find('.task_name').attr('value', '');

  $('.tasks-box').append(new_row.html());
});

$(document).on('click', '.btn-task-delete', function(){
  var index = $('.btn-task-delete').index(this);
  $('.tasks').eq(index).remove();
});
// END TASK

function _empty_form(auto_close = true) {
  $('.main-form').find('.select2-general').val('').trigger('change');
  $('.main-form').find('#software_list').val('').trigger('change');
  $('.main-form').find(':input').val('');
  $('.tasks-box').empty();

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

  // bila tabel sudah ada, destroy
  if ($.fn.DataTable.isDataTable('#table-content')) {
    table_content.destroy();
}

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

  $('#software_list').select2({
    tags: false,
    width: '100%',
    multiple: true,
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
}