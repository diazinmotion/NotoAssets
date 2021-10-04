$(document).ready(function(){
  _reInitialize();
});

$(document).on('click', '.btn-laptop-delete', function(){
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

          $('#table-content').bootstrapTable('refresh');
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

$(document).on('click', '.btn-laptop-clone', function(){
  var data_id = $(this).data('id');

  Swal.fire({
      title: "Are you sure for this action?",
      text: "All this asset information will be clone.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: "Yes",
  }).then((r) => {
    if(r.isConfirmed) {
      $.post(module_url + '/ajax_clone_item', { id: data_id }, function (d) {
        if (d.status) {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'The item has been cloned.',
            showConfirmButton: true,
            timer: 3000
          });

          $('#table-content').bootstrapTable('refresh');
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: d.msg,
            showConfirmButton: true,
            timer: 3000
          });
        }
      });
    }
  });
});

function _reInitialize() {
  $('#table-content').bootstrapTable('destroy').bootstrapTable({
    height: 550,
    stickyHeader: true,
    undefinedText: '',
    exportTypes: ['csv', 'excel', 'doc', 'txt','json', 'xml', 'pdf'],
    columns: [
      {
        title: 'Entity',
        field: 'entity',
        valign: 'middle',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'Location',
        field: 'location',
        valign: 'middle',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'Barcode',
        field: 'code',
        align: 'center',
        valign: 'middle',
        searchable: true,
        sortable: true,
      },
      {
        title: 'Laptop Name',
        field: 'name',
        valign: 'middle',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'Model',
        field: 'model',
        valign: 'middle',
        align: 'center',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'Serial Number',
        field: 'sn',
        valign: 'middle',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'Operating System',
        field: 'os',
        valign: 'middle',
        align: 'center',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'OS Product Key',
        field: 'os_key',
        valign: 'middle',
        searchable: true,
        sortable: true,
      },
      {
        title: 'Storage Type',
        field: 'storage_type',
        align: 'center',
        valign: 'middle',
        searchable: true,
        sortable: true,
      },
      {
        title: 'Storage Size (GB)',
        field: 'storage_size',
        align: 'right',
        valign: 'middle',
        searchable: true,
        sortable: true,
      },
      {
        title: 'Memory Type',
        field: 'memory_type',
        align: 'center',
        valign: 'middle',
        searchable: true,
        sortable: true,
      },
      {
        title: 'Memory Size (GB)',
        field: 'memory_size',
        valign: 'middle',
        searchable: true,
        align: 'right',
        sortable: true,
      },
      {
        title: 'Software Installed',
        field: 'software_installed',
        align: 'center',
        valign: 'middle',
        searchable: true,
        sortable: true,
      },
      {
        title: 'Status',
        field: 'status',
        align: 'center',
        valign: 'middle',
        searchable: true,
        sortable: true,
      },
      {
        title: 'Action Item',
        field: 'action',
        valign: 'middle',
        align: 'center',
        searchable: true,
        sortable: false,
      },
    ]
  });
}

function _responseHandler(res) {
  $.each(res.rows, function (i, row) {
    // row.state = $.inArray(row.id, selections) !== -1
    row.state = row.code
  })
  return res
}