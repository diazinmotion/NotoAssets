var table_content;

$(document).ready(function(){
  _reInitialize();
});

$(document).on('click', '.btn-item-filter', function(){
  table_content.ajax.reload();
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
}