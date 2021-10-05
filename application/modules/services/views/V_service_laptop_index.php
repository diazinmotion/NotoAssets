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
                <i class="fa fa-plus fa-fw"></i> New Service
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
                    <label class="col-sm-2 control-label">Entity</label>
                    <div class="col-sm-10">
                      <select name="entity_id" class='entity_id'></select>
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
                  <th class="bg-primary text-center" style="width:30%">Laptop Name / Code</th>
                  <th class="bg-primary">Purposes</th>
                  <th class="bg-primary text-center">Duration</th>
                  <th class="bg-primary text-center">JIRA Ticket</th>
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
        <h4 class="modal-title text-bold">New Service</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal main-form">
          <div class="form-group row">
            <label class="col-sm-4 control-label">Laptop</label>
            <div class="col-sm-8">
              <input type="hidden" name="id">
              <select name="laptop_id" class="laptop_id"></select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 control-label">Purposes</label>
            <div class="col-sm-8">
              <input type="text" name="purposes" class="form-control">
              <small class="clearfix">The purposes for this service</small>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 control-label">Service Location</label>
            <div class="col-sm-8">
              <textarea name="location" class="form-control"></textarea>
              <small class="clearfix">Address detail where this asset is being serviced</small>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 control-label">PIC Name</label>
            <div class="col-sm-8">
              <input type="text" name="pic_name" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 control-label">PIC Contact</label>
            <div class="col-sm-8">
              <input type="text" name="pic_contact" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 control-label">JIRA Ticket (IT)</label>
            <div class="col-sm-8">
              <input type="text" name="ticket_it" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 control-label">JIRA Ticket (GA)</label>
            <div class="col-sm-8">
              <input type="text" name="ticket_ga" class="form-control">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 control-label">Start Date</label>
            <div class="col-sm-8">
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="service_start" readonly class="dtp-max-today form-control" placeholder="Input data">
              </div>
            </div>
          </div>
          <div class="form-group row end-date-box" style="display:none">
            <label class="col-sm-4 control-label">End Date</label>
            <div class="col-sm-8">
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="service_end" readonly class="dtp form-control" placeholder="Input data">
              </div>
              <small class="clearfix">Update end date if the service has been done. System will mark this service as DONE.</small>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 control-label">Description</label>
            <div class="col-sm-8">
              <textarea name="description" class="form-control"></textarea>
              <small class="clearfix">Describe the problem about this asset (optional)</small>
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
  var table_columns   = ['laptop', 'purposes', 'duration', 'ticket', 'action'];
  var table_url       = module_url + "/ajax_module_index";
</script>
<!-- END SCRIPTS -->