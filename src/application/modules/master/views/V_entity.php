<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-lg-6" style="padding-top:2px;">
            <b class="panel-header-title text-bold text-primary">
              <i class="fa fa-edit fa-fw"></i> <?= $page_title ?>
            </b>
          </div>
          <div class="col-lg-6 text-right">
            <div class="btn-group">
              <a href="javascript:void(0)" class="btn btn-success btn-sm btn-item-add">
                <i class="fa fa-plus fa-fw"></i> New Item
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <!-- START DATA FILTER -->
          <div class="col-lg-7">
            <div class="row">
              <div class="col-lg-12" style="padding-bottom:20px;">
                <h4 class='text-bold' style='margin:0;'>DATA FILTER</h4>
                <small class="clearfix">You can use this filter form below to get specific criteria of the available data.</small>
              </div>
              <div class="col-lg-12">
                <form id="filter-form" class="form-horizontal">
                  <div class="form-group row">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10">
                      <select name="status" class="select2-general">
                        <option value="1">ACTIVE</option>
                        <option value="0">INACTIVE</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 text-right">
                      <button type="button" class="btn btn-primary btn-sm btn-item-filter">Filter</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- END DATA FILTER -->
          <div class="col-lg-5">
            <b>HOW TO USE:</b>
            <p>
              - For add a new item on this module, use the actions button above.
              <br>- You can add as many as items you want. A popup dialog might be opened for you.
              <br>- To delete an item, use the button on the table below. A confirmation dialog might appear on your screen, click confirm to delete the selected item permanently.
            </p>
          </div>
          <div class="col-lg-12"><hr></div>
          <div class="col-lg-12">
            <!-- MAIN TABLE -->
            <table id="table-content" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class="bg-primary text-center" style="width:15%">Code</th>
                  <th class="bg-primary">Name</th>
                  <th class="bg-primary text-center" style="width:15%">Status</th>
                  <th class="bg-primary text-center" style="width:10%">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <!-- MAIN TABLE -->
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-lg-12 text-right">
            <span>Loaded in {elapsed_time} seconds</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- START MODAL -->
<div class="modal fade" id="modal-add-edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="color:white;">&times;</span>
        </button>
        <h4 class="modal-title text-bold">New Item</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal main-form">
          <div class="form-group row">
            <label class="col-sm-2 control-label">Code</label>
            <div class="col-sm-10">
              <input type="hidden" name="id">
              <input type="text" name="code" class="form-control" placeholder="Input data">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
              <input type="text" name="name" class="form-control" placeholder="Input data">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 control-label">Status</label>
            <div class="col-sm-10">
              <select name="status" class="select2-general">
                <option value="1">ACTIVE</option>
                <option value="0">INACTIVE</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-lg-12">
            <div class="btn-group">
              <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-success btn-sm btn-item-submit">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL -->

<!-- START SCRIPTS -->
<script>
  var module_url      = '<?= (isset($module_url)) ? $module_url : '' ?>';
  var table_columns   = ['code', 'name', 'status', 'action'];
  var table_url       = module_url + "/ajax_module_index";
</script>
<!-- END SCRIPTS -->