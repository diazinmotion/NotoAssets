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

function responseHandler(res) {
  $.each(res.rows, function (i, row) {
    // row.state = $.inArray(row.id, selections) !== -1
    row.state = row.code
  })
  return res
}