var html_software_table = null;
var selected_software   = [];
var package_list        = [];
var package_item        = [];

$(document).ready(function(){
  // add row of software table to variable
  html_software_table = $('#table-software').find('tr:last').clone();
  $('#table-software').find('tr:last').remove();

  _reInitialize();
  _show_popup();
});

$(document).on('click', '.btn-show-password', function(){

  var index = $('.btn-show-password').index(this);
  
  // ubah icon menjadi cross
  if($(this).html().trim() == '<i class="fa fa-eye fa-fw"></i>'){
    $(this).html('<i class="fa fa-eye-slash fa-fw"></i>');
    $('.show-password').eq(index).attr('type', 'password');
  }else{
    $(this).html('<i class="fa fa-eye fa-fw"></i>');
    $('.show-password').eq(index).attr('type', 'text');
  }
});

$(document).on('click', '.btn-submit', function(){
  $('.main-form').submit();
});

$(document).on('click', '.btn-software-add', function(){
  var new_row     = html_software_table.html();

  new_id          = 'new_' + _make_id(3);
  html_table_new  = new_row.replace(/uid/g, new_id);

  $('#table-software').append(`<tr>${html_table_new}</tr>`);
  _reInitialize();
});

$(document).on('click', '.btn-software-delete', function(){
  var index = $('.btn-software-delete').index(this);
  $('#table-software').find('tr').eq(index).remove();

  selected_software = [];
  $.each($('.software_id'), function(){
    // tambahkan software2 yang telah dipilih
    selected_software.push($(this).val());
  });
});

$(document).on('click', '.btn-package-apply', function(){
  if(package_list.length > 0){
    Swal.fire({
      title: "Confirmation",
      text: "This action will replace the list on the table below. Continue?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: "Yes",
    }).then((r) => {
      if(r.isConfirmed) {
        
        $('#table-software').empty();

        // rebuild table for selected software list
        $.each(package_list, function(){
          var new_row     = html_software_table.html();

          new_id          = 'new_' + _make_id(3);
          html_table_new  = new_row.replace(/uid/g, new_id);

          $('#table-software').append(`<tr>${html_table_new}</tr>`);
        });

        // set value for each software
        $.each($('.software_id'), function(i){
          $(this).html(`<option value="${package_list[i].id}">${package_list[i].name}</option>`);
          $(this).val(package_list[i].id).change();
        });

        // masukkan data package_list sebagai software yang tidak dapat dipilih
        selected_software = $.map(package_list, function(v){
          return v.id
        });

        _reInitialize();
      }
    });
  }
});

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

  $('.location_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'master/location/ajax_get_location',
      dataType: 'json',
      type: "POST",
      data: function (params) {
        var queryParameters = {
          param: params.term,
          entity_id: $('.entity_id').val()
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

  $('.model_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'master/model/ajax_get_model',
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

  $('.os_type_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'master/os_type/ajax_get_os',
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

  $('.storage_type_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'master/storage_type/ajax_get_storage',
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

  $('.memory_type_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'master/memory_type/ajax_get_memory',
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

  $('.software_package_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'master/software_package/ajax_get_package',
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
              text: item.text,
              list: item.list
            }
          })
        };
      }
    }
  }).on('change', function(){
    

    if($(this).select2('data')[0].list !== undefined){
      package_item[$(this).val()] = $(this).select2('data')[0].list;
      package_list = package_item[$(this).val()];
    }else{
      package_list = package_item[$(this).val()];
    }
  });

  $('.account_type_id').select2({
    tags: false,
    width: '100%',
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'master/account/ajax_get_account',
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
          exclude: selected_software
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
  }).on('select2:select', function(){
    selected_software = [];
    $.each($('.software_id'), function(){
      // tambahkan software2 yang telah dipilih
      selected_software.push($(this).val());
    });
  });
}