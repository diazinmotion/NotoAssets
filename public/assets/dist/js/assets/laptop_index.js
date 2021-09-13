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
      field: 'id',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'Location',
      field: 'id',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'Barcode',
      field: 'id',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Laptop Name',
      field: 'id',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'Serial Number',
      field: 'id',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'Operating System',
      field: 'id',
      valign: 'middle',
      sortable: true,
    }, 
    {
      title: 'OS Product Key',
      field: 'id',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Storage Type',
      field: 'id',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Storage Size (GB)',
      field: 'id',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Memory Type',
      field: 'id',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Memory Size (GB)',
      field: 'id',
      valign: 'middle',
      sortable: true,
    },
    {
      title: 'Status',
      field: 'id',
      valign: 'middle',
      sortable: true,
    },
  ]
});

function responseHandler(res) {
  $.each(res.rows, function (i, row) {
    // row.state = $.inArray(row.id, selections) !== -1
    row.state = row.id
  })
  return res
}