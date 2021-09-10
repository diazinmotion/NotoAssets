<aside class="main-sidebar">
  <section class="sidebar">
    <div class="row">
      <div class="col-lg-12" style="padding: 10px 20px;">
        <div class="row">
          <div class="col-lg-2" style="margin-top:5px">
            <i class="fa fa-user-circle fa-fw fa-2x" style="color:white"></i>
          </div>
          <div class="col-lg-10">
            <b style="color:white"><?= current_user_session('full_name') ?></b>
            <small class="clearfix" style="color:white">Last Login: <?= \Carbon\Carbon::parse(current_user_session('last_login'))->format('d M Y - H:i') ?></small>
          </div>
        </div>
      </div>
    </div>
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li>
        <a href="<?= base_url() ?>">
          <i class="fa fa-home"></i> <span>Home</span>
        </a>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-desktop"></i> <span>Assets</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?= base_url('assets/laptop') ?>"><i class="fa fa-circle-o"></i> Laptops</a></li>
          <li><a href="#"><i class="fa fa-circle-o"></i> General Assets</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-key"></i> <span>License</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?= base_url('license') ?>"><i class="fa fa-circle-o"></i> Manage License</a></li>
          <li><a href="<?= base_url('license') ?>"><i class="fa fa-circle-o"></i> License Seat</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-database"></i> <span>Master Data</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?= base_url('master/entity') ?>"><i class="fa fa-circle-o"></i> Entity</a></li>
          <li><a href="<?= base_url('master/location') ?>"><i class="fa fa-circle-o"></i> Location</a></li>
          <li><a href="<?= base_url('master/account') ?>"><i class="fa fa-circle-o"></i> Account ID Type</a></li>
          <li class="treeview">
            <a href="#"><i class="fa fa-circle-o"></i> Software
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?= base_url('master/os_type') ?>"><i class="fa fa-circle-o"></i> Operating System</a></li>
              <li><a href="<?= base_url('master/software') ?>"><i class="fa fa-circle-o"></i> Software List</a></li>
              <li><a href="<?= base_url('master/software_package') ?>"><i class="fa fa-circle-o"></i> Software Package</a></li>
            </ul>
          </li>
          <li class="treeview">
            <a href="#"><i class="fa fa-circle-o"></i> Hardware
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?= base_url('master/model') ?>"><i class="fa fa-circle-o"></i> Models</a></li>
              <li><a href="<?= base_url('master/brand') ?>"><i class="fa fa-circle-o"></i> Laptop Brand</a></li>
              <li><a href="<?= base_url('master/memory_type') ?>"><i class="fa fa-circle-o"></i> Memory Type</a></li>
              <li><a href="<?= base_url('master/storage_type') ?>"><i class="fa fa-circle-o"></i> Storage Type</a></li>
            </ul>
          </li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-cogs"></i> <span>Administration</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?= base_url('auth/users') ?>"><i class="fa fa-circle-o"></i> Users</a></li>
        </ul>
      </li>
    </ul>
  </section>
</aside>