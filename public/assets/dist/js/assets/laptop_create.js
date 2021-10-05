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

$(document).on('click', '.checklist-delete', function(){
  var data_id = $(this).data('id');
  Swal.fire({
    title: "Confirmation",
    text: "This action will replace the list on the table below. Continue?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: "Yes",
  }).then((r) => {
    if(r.isConfirmed) {
      $.post(module_url + '/ajax_delete_checklist', {id: data_id}, function(d){
        if(d.status){
          Swal.fire({
            icon: 'success',
            title: 'Success',
            html: 'The checklist has been deleted.',
            showConfirmButton: true,
            timer: 3000
          }).then(() => {
            window.location.reload();
          });
        }else{
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

          window.location.href = module_url;
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
          
          window.location.href = module_url;
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

  $.each(['.location_id, .ho_location_id'], function(_, v){
    
    $(v).select2({
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
            entity_id: ((v == '.location_id') ? $('.entity_id').val() : $('.ho_entity_id').val())
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
  });

  $('.entity_id, .ho_entity_id').select2({
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

  $('.checklist_id').select2({
    tags: false,
    width: '100%',
    multiple: true,
    placeholder: 'Search an item',
    ajax: {
      url: base_url + 'checklists/ajax_get_checklist',
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

  $.each($('.license_id'), function(){
    var index = $('.license_id').index(this);

    $(this).select2({
      tags: false,
      width: '100%',
      placeholder: 'Search a license',
      ajax: {
        url: base_url + 'licenses/ajax_get_licenses',
        dataType: 'json',
        type: "POST",
        data: function (params) {
          var queryParameters = {
            param: params.term,
            id: $('.software_id').eq(index).val()
          }
  
          return queryParameters;
        },
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                id: item.id,
                text: item.text,
                extra: item.extra
              }
            })
          };
        }
      }
    }).on('select2:select', function(){
      var data = $(this).select2('data')[0].extra;

      if(data !== undefined){
        var prop_exp  = true;
        var prop_key  = true;
        var val_exp   = '';
        var val_key   = '';

        // bila bulk license, maka disable semua isian untuk baris ini
        if(data.is_bulk_license == 1){
          prop_exp = true;
          prop_key = true;
          val_exp   = data.universal_expired_at;
          val_key   = data.universal_product_key;
        
          $('.software_expired_at').eq(index).datepicker('destroy');
        }else{
          prop_exp = false;
          prop_key = false;
          val_exp   = '';
          val_key   = '';
        
          $('.software_expired_at').eq(index).datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
          });
        }

        $('.software_expired_at').eq(index).val(val_exp).prop('readonly', prop_exp);
        $('.software_product_key').eq(index).val(val_key).prop('readonly', prop_key);
      }
    });
  });
}