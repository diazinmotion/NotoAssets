$('#table-content').bootstrapTable('destroy').bootstrapTable({
  height: 550,
  columns: [
    {
      field: 'state',
      checkbox: true,
      align: 'center',
      valign: 'middle'
    }, 
    {
      title: 'Entity',
      field: 'entity',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'Location',
      field: 'location',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'Barcode',
      field: 'code',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Laptop Name',
      field: 'name',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'Model',
      field: 'model',
      valign: 'middle',
      align: 'center',
      sortable: true,
    }, 
    {
      title: 'Serial Number',
      field: 'sn',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'Operating System',
      field: 'os',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'OS Product Key',
      field: 'os_key',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Storage Type',
      field: 'storage_type',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Storage Size (GB)',
      field: 'storage_size',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Memory Type',
      field: 'memory_type',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Memory Size (GB)',
      field: 'memory_type',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Status',
      field: 'status',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Action',
      field: 'action',
      valign: 'middle',
      align: 'center',
      sortable: false,
    },
  ]
});

function responseHandler(res) {
  $.each(res.rows, function (i, row) {
    // row.state = $.inArray(row.id, selections) !== -1
    row.state = row.code
  })
  return res
}