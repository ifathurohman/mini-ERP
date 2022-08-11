<div class="page page-data content" data-modul="transaction_route">
	<div class="col-sm-12 box no-padding">
		<?php if($data->Image != null): 
		echo '<img src="'.base_url().$data->Image.'">';
		endif; ?>
	</div>
	<div class="col-sm-12 box-detail no-padding">
		<header>
			<div class="nav-title">
				<span>
					<a href="<?= site_url('blog') ?>">
						<span property="name">Blog</span>
					</a>
				</span>
				<span class="site-menu-arrow"></span>
				<span>
					<span property="name"><?= $data->Name ?></span>
				</span>
			</div>
			<h2><?= $data->Name ?></h2>
			<span><?= $this->main->konversi_tanggal("d M Y", $data->Date) ?></span> | 
			<?php foreach ($Category as $d) {
				echo '<span class="info-primary">'.$d.'</span>';
			} ?>
		</header>
		<div class="description">
			<?= $data->Description ?>
		</div>
	</div>
</div>