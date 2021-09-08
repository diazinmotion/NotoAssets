<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view('layouts/landing/_header') ?>
	</head>
	<body class="hold-transition">
		<!-- CONTENTS -->
		<?php if(isset($page_view)) { ?>
			<?php $this->load->view($page_view) ?>
		<?php }else{ ?>
			<center style="padding-top: 38vh;">
				<h2 class="text-bold text-danger">Oops, Terjadi Kesalahan</h2>
				<span class="clreafix">Halaman yang Anda minta tidak dapat ditemukan, ini dapat terjadi karena halaman telah dihapus, atau dipindahkan.</span>
				<p><a href="/">Kembali Ke Halaman Awal</a></p>
			</center>
		<?php } ?>
		<!-- END CONTENTS -->
		
		<!-- SCRIPTS -->
		<?php $this->load->view('layouts/landing/_footer') ?>
		<!-- END SCRIPTS -->
	</body>
</html>