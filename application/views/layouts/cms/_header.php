<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<title><?php echo (isset($page_title)) ? $page_title.' - '.APP_NAME : APP_NAME ?></title>

<link rel="icon" href="<?= base_url(THEME_PATH) ?>favicon.ico">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>bower_components/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>dist/css/animate.css">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>dist/css/statics/sweetalert2.min.css">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>plugins/select2/css/select2-bootstrap.min.css">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?= base_url(THEME_PATH) ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

<!-- require online -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
    table.table-bordered.dataTable tbody td { vertical-align: middle; }

    .skin-red .main-header .navbar .dropdown-menu li a {
        color: #000!important;
    }

    .swal2-popup { font-size: 1.4rem !important; }
    .panel-header-title{
        font-size: 18px;
    }

    .form-horizontal .control-label {
        padding-top: 7px;
        margin-bottom: 0;
        text-align: left;
    }
</style>