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
              <a href="<?= base_url('licenses/create') ?>" class="btn btn-success btn-sm btn-item-add">
                <i class="fa fa-plus fa-fw"></i> New License
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
                    <label class="col-sm-2 control-label">Software</label>
                    <div class="col-sm-10">
                      <select name="software_id" class='software_id'></select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 control-label">Keyword</label>
                    <div class="col-sm-10">
                      <input type="text" name="keyword" class="form-control">
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
                  <th class="bg-primary">Software Name</th>
                  <th class="bg-primary text-center">Purchase</th>
                  <th class="bg-primary text-center">Expiration</th>
                  <th class="bg-primary text-center">License Type</th>
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

<!-- START SCRIPTS -->
<script>
  var module_url      = '<?= (isset($module_url)) ? $module_url : '' ?>';
  var table_columns   = ['name', 'purchase', 'expiration', 'type', 'action'];
  var table_url       = module_url + "/ajax_module_index";
</script>
<!-- END SCRIPTS -->