<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view('layouts/cms/_header') ?>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<!-- TOPBAR -->
			<?php $this->load->view('layouts/cms/_topbar') ?>
			<!-- END TOPBAR -->
			<!-- SIDEBAR -->
			<?php $this->load->view('layouts/cms/_sidebar') ?>	
			<!-- END SIDEBAR -->
			
			<!-- MAIN WRAPPER -->
			<div class="content-wrapper">
				<!-- CONTENTS -->
				<section class="content">
					<?php if(isset($page_view)) { ?>
						<?php $this->load->view($page_view) ?>
					<?php }else{ ?>
						<center style="padding-top: 30vh;">
							<h2 class="text-bold text-danger">Oops, Terjadi Kesalahan</h2>
							<span class="clreafix">Halaman yang Anda minta tidak dapat ditemukan, ini dapat terjadi karena halaman telah dihapus, atau dipindahkan.</span>
							<p><a href="/">Kembali Ke Halaman Awal</a></p>
						</center>
					<?php } ?>
				</section>
				<!-- END CONTENTS -->
			</div>
			<!-- MAIN WRAPPER -->
	
			<!-- FOOTER -->
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<span><?= APP_VERSION ?></span>&nbsp;
          <small class="label label-danger"><?= ENVIRONMENT ?></small>
				</div>
				<strong>&copy; <?= date('Y').' - '.APP_COMPANY ?></strong>
			</footer>
			<!-- END FOOTER -->
		</div>
		
		<!-- SCRIPTS -->
		<?php $this->load->view('layouts/cms/_footer') ?>
		<!-- END SCRIPTS -->
	</body>
</html>