$(document).ready(function(){
  _reInitialize();
});

function _reInitialize() {
  $('#table-content').bootstrapTable('destroy').bootstrapTable({
    height: 550,
    stickyHeader: true,
    undefinedText: '',
    exportTypes: ['csv', 'excel', 'doc', 'txt','json', 'xml', 'pdf'],
    columns: [
      {
        title: 'User',
        field: 'user',
        valign: 'middle',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'Cubical',
        field: 'cubical',
        valign: 'middle',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'Barcode / Name',
        field: 'laptop',
        align: 'center',
        valign: 'middle',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'Model',
        field: 'model',
        align: 'center',
        valign: 'middle',
        searchable: true,
        sortable: true,
      }, 
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
        title: 'Date',
        field: 'date',
        valign: 'middle',
        align: 'center',
        searchable: true,
        sortable: true,
      }, 
      {
        title: 'Status',
        field: 'status',
        valign: 'middle',
        align: 'center',
        searchable: false,
        sortable: true,
      }, 
      {
        title: 'Initiator',
        field: 'initiator',
        valign: 'middle',
        align: 'center',
        searchable: true,
        sortable: true,
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