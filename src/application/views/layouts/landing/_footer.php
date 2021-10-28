<script src="<?= base_url(THEME_PATH);?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/jquery-ui/jquery-ui.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?= base_url(THEME_PATH);?>bower_components/moment/moment.js"></script>
<script src="<?= base_url(THEME_PATH) ?>dist/js/statics/sweetalert2.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>plugins/iCheck/icheck.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>dist/js/adminlte.min.js"></script>
<script src="<?= base_url(THEME_PATH);?>plugins/mcustomscrollbar/js/jquery.mCustomScrollbar.min.js"></script>

<script>var base_url = "<?= base_url();?>";</script>
<?php if(isset($extraJs)):?>
    <?php foreach($extraJs as $key => $js):?>
        <script type="text/javascript" src="<?php echo base_url();?>assets/dist/js/<?=$js;?>"></script>
    <?php endforeach;?>
<?php endif;?>