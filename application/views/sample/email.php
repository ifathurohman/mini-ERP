<!DOCTYPE html>
<html>
<head>
	<title>Email</title>
	<link rel="stylesheet" type="text/css" href="<?= base_url("aset/css/email.css"); ?>">
</head>
<body>
<div class="div-email">
	<div class="header">
		<span class="title">PIPESYS</span>
	</div>
	<div class="content">
		<?php $this->load->view($page); ?>
	</div>
	<div class="footer">
		<span class="alamat">Copyright © 2017 CV. RCElectronic. All Rights Reserved.</span>
		<span class="alamat">Jalan Indrayasa No. 158 - 160, Bandung, Cibaduyut, Bojongloa Kidul, Bandung, West Java 40236</span>
		<span></span>
	</div>
	<div class="footer-bot">
		
	</div>
</div>
</body>
</html>