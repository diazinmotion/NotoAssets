<div class="row">
  <div class="col-lg-10 col-lg-offset-1">
    <form method="POST" action="<?= current_url() ?>" class="form-horizontal main-form">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <? if(str_replace(base_url(), null, current_url()) != 'assets/laptop/create'){ ?>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              Actions <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-copy fa-fw"></i> Clone</a></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="text-red btn-laptop-delete" data-id="<?= ($db) ? $db[0]->id : null ?>"><i class="fa fa-trash fa-fw"></i> Delete</a></li>
            </ul>
          </li>
          <li><a href="#history" data-toggle="tab">Asset Logs</a></li>
          <li><a href="#todo" data-toggle="tab">Checklists</a></li>
          <? } ?>
          <li><a href="#software" data-toggle="tab">Software &amp; Licenses</a></li>
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
                  This section list all details for this asset. 
                  Please fill these form as complete as possible to input laptop assets.
                </p>
                <? if($db){ ?>
                <small>
                  <ul class="list-unstyled">    
                    <? if($db[0]->created_name){ ?>
                      <li><b>Created:</b> <?= $db[0]->created_name ?> at <?= \Carbon\Carbon::parse($db[0]->created_at)->format('d M Y H:i') ?></li>
                    <? } ?>
                    <? if($db[0]->updated_name){ ?>
                      <li><b>Updated:</b> <?= $db[0]->updated_name ?> at <?= \Carbon\Carbon::parse($db[0]->updated_at)->format('d M Y H:i') ?></li>
                    <? } ?>
                  </ul>
                </small>
                <? } ?>
              </div>
              <div class="col-lg-8">
                <? if($id && ($db && $db[0]->warranty_expired && \Carbon\Carbon::now() > \Carbon\Carbon::parse($db[0]->warranty_expired))) { ?>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="callout callout-warning">
                      <p>This warranty of this assets already expired, please review the warranty expiration below. Ignore this message if you don't want to extend the asset's warranty.</p>
                    </div>
                  </div>
                </div>
                <? } ?>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Entity</label>
                  <div class="col-lg-9">
                    <? 
                      if($db){
                        $opt = [$db[0]->entity_id => $db[0]->entity_name];
                      }else{
                        $opt = [];
                      } 
                    ?>
                    <?= form_dropdown('entity_id', $opt, ($db) ? $db[0]->entity_id : null, 'class="entity_id"') ?>
                  </div>
                  <div class="col-lg-12 text-right">
                    <a href="<?= base_url('master/entity') ?>" target="_blank"><i class="fa fa-plus-circle fa-fw"></i> Add New</a>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Location</label>
                  <div class="col-sm-9">
                    <? 
                      if($db){
                        $opt = [$db[0]->location_id => $db[0]->location_name];
                      }else{
                        $opt = [];
                      } 
                    ?>
                    <?= form_dropdown('location_id', $opt, ($db) ? $db[0]->location_id : null, 'class="location_id"') ?>
                  </div>
                  <div class="col-lg-12 text-right">
                    <a href="<?= base_url('master/location') ?>" target="_blank"><i class="fa fa-plus-circle fa-fw"></i> Add New</a>
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Tag / Barcode</label>
                  <div class="col-sm-9">
                    <input type="hidden" name="code_original" value="<?= ($db) ? $db[0]->code : null ?>">
                    <input type="text" name="code" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->code : null ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Model</label>
                  <div class="col-sm-9">
                    <? 
                      if($db){
                        $opt = [$db[0]->model_id => $db[0]->brand_name.' '.$db[0]->model_name];
                      }else{
                        $opt = [];
                      } 
                    ?>
                    <?= form_dropdown('model_id', $opt, ($db) ? $db[0]->location_id : null, 'class="model_id"') ?>
                  </div>
                  <div class="col-lg-12 text-right">
                    <a href="<?= base_url('master/model') ?>" target="_blank"><i class="fa fa-plus-circle fa-fw"></i> Add New</a>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Assets Name</label>
                  <div class="col-sm-9">
                    <input type="text" name="name" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->name : null ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Serial Number</label>
                  <div class="col-sm-9">
                    <input type="text" name="serial_number" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->serial_number : null ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Purchase Date</label>
                  <div class="col-sm-9">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" name="purchased_at" class="dtp-max-today form-control" placeholder="Input data" value="<?= ($db && $db[0]->purchased_at) ? \Carbon\Carbon::parse($db[0]->purchased_at)->format('d-m-Y') : null ?>">
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Warranty Expiration</label>
                  <div class="col-sm-9">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" name="warranty_expired" class="dtp form-control" placeholder="Input data" value="<?= ($db && $db[0]->warranty_expired) ? \Carbon\Carbon::parse($db[0]->warranty_expired)->format('d-m-Y') : null ?>">
                    </div>
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Operating System</label>
                  <div class="col-sm-9">
                    <? 
                      if($db){
                        $opt = [$db[0]->os_type_id => $db[0]->os_name];
                      }else{
                        $opt = [];
                      } 
                    ?>
                    <?= form_dropdown('os_type_id', $opt, ($db) ? $db[0]->os_type_id : null, 'class="os_type_id"') ?>
                  </div>
                  <div class="col-lg-12 text-right">
                    <a href="<?= base_url('master/os_type') ?>" target="_blank"><i class="fa fa-plus-circle fa-fw"></i> Add New</a>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">OS Product Key</label>
                  <div class="col-sm-9">
                    <input type="text" name="os_product_key" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->os_product_key : null ?>">
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Storage Type</label>
                  <div class="col-sm-9">
                    <? 
                      if($db){
                        $opt = [$db[0]->storage_type_id => '('.$db[0]->storage_code.') '.$db[0]->storage_name];
                      }else{
                        $opt = [];
                      } 
                    ?>
                    <?= form_dropdown('storage_type_id', $opt, ($db) ? $db[0]->storage_type_id : null, 'class="storage_type_id"') ?>
                  </div>
                  <div class="col-lg-12 text-right">
                    <a href="<?= base_url('master/storage_type') ?>" target="_blank"><i class="fa fa-plus-circle fa-fw"></i> Add New</a>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Storage Brand</label>
                  <div class="col-sm-9">
                    <input type="text" name="storage_type_brand" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->storage_type_brand : null ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Storage Size (GB)</label>
                  <div class="col-sm-9">
                    <input type="number" name="storage_size" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->storage_size : null ?>">
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Memory Type</label>
                  <div class="col-sm-9">
                    <? 
                      if($db){
                        $opt = [$db[0]->memory_type_id => '('.$db[0]->memory_code.') '.$db[0]->memory_name];
                      }else{
                        $opt = [];
                      } 
                    ?>
                    <?= form_dropdown('memory_type_id', $opt, ($db) ? $db[0]->memory_type_id : null, 'class="memory_type_id"') ?>
                  </div>
                  <div class="col-lg-12 text-right">
                    <a href="<?= base_url('master/memory_type') ?>" target="_blank"><i class="fa fa-plus-circle fa-fw"></i> Add New</a>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Memory Brand</label>
                  <div class="col-sm-9">
                    <input type="text" name="memory_brand" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->memory_brand : null ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Memory Size (GB)</label>
                  <div class="col-sm-9">
                    <input type="number" name="memory_size" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->memory_size : null ?>">
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Account Type</label>
                  <div class="col-sm-9">
                    <? 
                      if($db){
                        $opt = [$db[0]->account_type_id => $db[0]->account_name];
                      }else{
                        $opt = [];
                      } 
                    ?>
                    <?= form_dropdown('account_type_id', $opt, ($db) ? $db[0]->account_type_id : null, 'class="account_type_id"') ?>
                  </div>
                  <div class="col-lg-12 text-right">
                    <a href="<?= base_url('master/account') ?>" target="_blank"><i class="fa fa-plus-circle fa-fw"></i> Add New</a>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Account Email</label>
                  <div class="col-sm-9">
                    <input type="email" name="account_email" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->account_email : null ?>">
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">PKI Email</label>
                  <div class="col-sm-9">
                    <input type="email" name="pki_email" class="form-control" placeholder="Input data" value="<?= ($db) ? $db[0]->pki_email : null ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">PKI Password</label>
                  <div class="col-sm-9">
                    <div class="input-group">
                      <input type="password" name="pki_password" class="form-control show-password" placeholder="Input data" value="<?= ($db) ? $db[0]->pki_password : null ?>">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-primary btn-flat btn-show-password">
                          <i class="fa fa-eye-slash fa-fw"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Encryption Password</label>
                  <div class="col-sm-9">
                    <div class="input-group">
                      <input type="password" name="encryption_password" class="form-control show-password" placeholder="Input data" value="<?= ($db) ? $db[0]->encryption_password : null ?>">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-primary btn-flat btn-show-password">
                          <i class="fa fa-eye-slash fa-fw"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Encryption File</label>
                  <div class="col-sm-9">
                    <input type="file" name="encryption_recovery_file" class="form-control">
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-9">
                    <input type="hidden" name="flag_status_original" value="<?= ($db) ? $db[0]->flag_status : null ?>">
                    <?= form_dropdown('flag_status', $l_status, (($db) ? $db[0]->flag_status : null), 'class="select2-general"') ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="software">
            <div class="row">
              <div class="col-lg-12">
                <h4 class="text-bold text-primary">Software &amp; Licenses</h4>
                <b>HOW TO USE:</b>
                <p>
                  This section list all software that installed on this assets. This also including their licenses for each software, any software with no any licenses might be a freeware. 
                  Any licenses without expiration date mean it valid for lifetime (one time payment), otherwise it marked as subscription.
                </p>
                <hr>
              </div>
              <div class="col-lg-12">
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
          <div class="tab-pane" id="todo">
            <div class="row">
              <div class="col-lg-4">
                <h4 class="text-bold text-primary">Checklists</h4>
                <hr>
                <b>HOW TO USE:</b>
                <p>
                  This section provide information about checklist that should be done for this asset.
                  Please check or uncheck for each task provided.
                </p>
              </div>
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-lg-12">
                    <b>CHECKLIST</b>
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <select name="checklist_id[]" class='checklist_id'></select>
                      </div>
                    </div>
                    <hr>
                  </div>
                  <? if($db_c){ ?>
                  <? foreach ($db_c as $v) { ?>
                  <div class="col-lg-12 checklist-table">
                    <h5 class="text-bold">
                      <?= $v->checklist_name ?>
                      <span class="pull-right"><a href="javascript:void(0)" class="text-red checklist-delete" data-id="<?= $v->id ?>">Delete</a></span>
                    </h5>
                    <div class="table-responsive">
                      <table class="table table-bordered tabled-stripped table-condensed">
                        <thead>
                          <th class="bg-primary">Tasks</th>
                          <th class="bg-primary text-center" style="width:20%">Status</th>
                        </thead>
                        <tbody>
                          <? 
                            $ex   = explode('$', $v->task_name); 
                            if($ex){
                              foreach ($ex as $exv) {
                                $has_checked  = false;
                                $ex_r         = explode('|', $exv);
                                $index        = (($ex_r[1] == 0) ? 'new_'.rand(0,9999) : $ex_r[1]);
                                if($ex_r){
                                  if($ex_r[4] == 'Y'){
                                    $has_checked = true;
                                  }
                                }
                                echo '<tr>
                                        <td>'.$ex_r[3].'
                                          <input type="hidden" name="checklist_item_id['.$index.']" value="'.$ex_r[0].'">
                                          <input type="hidden" name="checklist_laptop_id['.$index.']" value="'.$ex_r[2].'">
                                        </td>
                                        <td class="text-center"><input type="checkbox" name="checklist_has_done['.$index.']" value="Y" '.(($has_checked) ? 'checked' : null).'/></td>
                                      </tr>';
                              }
                            }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <? } ?>
                  <? }else{ ?>
                    <center><b>This asset has no checklist yet</b></center>
                  <? } ?>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="history">
            <div class="row">
              <div class="col-lg-4">
                <h4 class="text-bold text-primary">Asset Logs</h4>
                <hr>
                <b>HOW TO USE:</b>
                <p>
                  This section lists all action and logs for this specific asset.
                </p>
              </div>
              <div class="col-lg-8">
                <?= $t_logs ?>
              </div>
            </div>
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
  var has_save    = <?= ($has_save) ? 'true' : 'false' ?>;
  var popup_msg   = '<?= ($message) ? implode(',', $message) : '' ?>';
  var module_url  = '<?= (isset($module_url)) ? $module_url : '' ?>';
  var table_url   = module_url + "/ajax_module_index";
</script>
<!-- END SCRIPTS -->