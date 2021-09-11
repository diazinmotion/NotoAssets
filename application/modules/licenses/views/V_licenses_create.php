<div class="row">
  <div class="col-lg-10 col-lg-offset-1">
    <form method="POST" action="<?= current_url() ?>" class="form-horizontal main-form">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <? if($id) { ?>
          <li><a href="#software" data-toggle="tab">Seats</a></li>
          <? } ?>
          <li class="active"><a href="#details" data-toggle="tab">Details</a></li>
          <li class="pull-left header">
            <b class="text-bold text-primary">
              <i class="fa fa-<?= ($id) ? 'edit' : 'plus-circle' ?> fa-fw"></i> <?= $page_title ?>
            </b>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="details">
            <div class="row">
              <div class="col-lg-4">
                <h4 class="text-bold text-primary">Details</h4>
                <hr>
                <b>HOW TO USE:</b>
                <p>
                  This section list all details for this license for this particular software. 
                </p>
              </div>
              <div class="col-lg-8">
                <? if($id && ($db && $db[0]->universal_expired_at && \Carbon\Carbon::now() > \Carbon\Carbon::parse($db[0]->universal_expired_at))) { ?>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="callout callout-danger">
                      <span>This software license has expired.</span>
                    </div>
                  </div>
                </div>
                <? } ?>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Software</label>
                  <div class="col-lg-9">
                    <?= form_dropdown('software_id', ($db) ? [$db[0]->software_id => $db[0]->software_name] : [], ($db) ? $db[0]->software_id : null, 'class="software_id"') ?>
                  </div>
                  <div class="col-lg-12 text-right">
                    <a href="<?= base_url('master/software') ?>" target="_blank"><i class="fa fa-plus-circle fa-fw"></i> Add New</a>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">License Name</label>
                  <div class="col-sm-9">
                    <input type="text" name="name" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->name : null ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">License Type</label>
                  <div class="col-lg-9">
                    <?= form_dropdown('is_bulk_license', ['1' => 'BULK LICENSE', '0' => 'SINGLE / UNIQUE LICENSE'], ($db) ? $db[0]->is_bulk_license : null, 'class="select2-general"') ?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">License Category</label>
                  <div class="col-lg-9">
                    <?= form_dropdown('flag_permanent', ['1' => 'PERMANENT LICENSE', '0' => 'SUBSCRIPTION LICENSE'], ($db) ? $db[0]->flag_permanent : null, 'class="select2-general"') ?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Purchase Date</label>
                  <div class="col-sm-9">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" name="purchased_at" class="dtp-max-today form-control" placeholder="Input data"  value="<?= ($db) ? (($db[0]->purchased_at) ? \Carbon\Carbon::parse($db[0]->purchased_at)->format('d-m-Y') : null) : null ?>">
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Purchase Location</label>
                  <div class="col-sm-9">
                    <textarea name="purchased_place" class="form-control" placeholder="Input data"><?= ($db) ? $db[0]->purchased_place : null ?></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Bulk Expiration</label>
                  <div class="col-sm-9">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" name="universal_expired_at" class="dtp form-control" placeholder="Input data" value="<?= ($db) ? (($db[0]->universal_expired_at) ? \Carbon\Carbon::parse($db[0]->universal_expired_at)->format('d-m-Y') : null) : null ?>">
                    </div>
                    <small class="clearfix">Leave blank if this license type has unique expiration date per device.</small>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Bulk Product Key</label>
                  <div class="col-sm-9">
                    <input type="text" name="universal_product_key" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->universal_product_key : null ?>">
                    <small class="clearfix">Bulk means all devices will use similar product key. Leave blank if this license type has unique product key per device.</small>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Quota</label>
                  <div class="col-sm-9">
                    <input type="number" name="quota" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->quota : 0 ?>">
                    <small class="clearfix">Set as 0 if license does not have quota.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="software">
            <div class="row">
              <div class="col-lg-4">
                <h4 class="text-bold text-primary">Software &amp; Licenses</h4>
                <hr>
                <b>HOW TO USE:</b>
                <p>
                  This section list all software that installed on this assets. This also including their licenses for each software, any software with no any licenses might be a freeware. 
                  Any licenses without expiration date mean it valid for lifetime (one time payment), otherwise it marked as subscription.
                </p>
              </div>
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-lg-12">
                    <b>SOFTWARE PACKAGES</b>
                    <p>You can choose multiple software at once using software packages that have already been definded. Want to define new software packages? <a href="<?= base_url('master/software_package') ?>" target="_blank" rel="noopener noreferrer">Create a new one here</a>.</p>
                    <div class="form-group row">
                      <div class="col-lg-10">
                        <select class='software_package_id'></select>
                      </div>
                      <div class="col-lg-2">
                        <a href="javascript:void(0)" class="btn btn-sm btn-block btn-primary btn-package-apply">Apply</a>
                      </div>
                    </div>
                    <hr>
                  </div>
                  <div class="col-lg-12">
                    <?= $t_software ?>
                  </div>
                  <div class="col-lg-12 text-right">
                    <a href="javascript:void(0)" class="btn btn-primary btn-xs btn-software-add">Add Software</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="maintenance">
            TBA
          </div>
        </div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-lg-12 text-right">
              <div class="btn-group">
                <button type="button" class="btn btn-success btn-submit">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- START SCRIPTS -->
<script>
  var is_edit     = <?= ($id) ? 'true' : 'false' ?>;
  var has_save    = <?= ($has_save) ? 'true' : 'false' ?>;
  var popup_msg   = '<?= ($message) ? implode(',', $message) : '' ?>';
  var module_url  = '<?= (isset($module_url)) ? $module_url : '' ?>';
  var table_url   = module_url + "/ajax_module_index";
</script>
<!-- END SCRIPTS -->