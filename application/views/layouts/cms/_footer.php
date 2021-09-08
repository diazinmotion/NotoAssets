<script src="<?= base_url(THEME_PATH);?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/jquery-ui/jquery-ui.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/moment/moment.js"></script>
<script src="<?= base_url(THEME_PATH);?>plugins/iCheck/icheck.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>dist/js/adminlte.min.js"></script>
<script src="<?= base_url(THEME_PATH) ?>dist/js/statics/sweetalert2.min.js"></script>
<script src="<?= base_url(THEME_PATH) ?>plugins/blockui/js/blockui.min.js"></script>
<script src="<?= base_url(THEME_PATH) ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script>var base_url = "<?= base_url();?>";</script>
<?php if(isset($extraJs)):?>
    <?php foreach($extraJs as $key => $js):?>
        <script type="text/javascript" src="<?php echo base_url();?>assets/dist/js/<?=$js;?>"></script>
    <?php endforeach;?>
<?php endif;?>