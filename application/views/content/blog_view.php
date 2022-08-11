<div class="page page-data content" data-modul="transaction_route">
	<div class="col-md-6 box no-padding">
		<div class="col-sm-12 left no-padding">
			<?php if($top["1"] != null):
				$Image = '';
				$url = $this->content->relpace_root($top["1"]->ContentID, $top["1"]->Name);
				if($top["1"]->Image != null):
					$Image = '<img src="'.base_url().$top["1"]->Image.'" />';
				endif;
				echo '<a href="'.$url.'">
						'.$Image.'
						<h3 class="box-title">'.$top["1"]->Name.'</h3>
					</a>';
			endif; ?>
		</div>
		<div class="col-sm-6 left-1 no-padding">
			<?php if($top["2"] != null):
				$Image = '';
				$url = $this->content->relpace_root($top["2"]->ContentID, $top["2"]->Name);
				if($top["2"]->Image != null):
					$Image = '<img src="'.base_url().$top["2"]->Image.'" />';
				endif;
				echo '<a href="'.$url.'">
						'.$Image.'
						<h3 class="box-title">'.$top["2"]->Name.'</h3>
					</a>';
			endif; ?>
		</div>
		<div class="col-sm-6 left-1 no-padding">
			<?php if($top["3"] != null):
				$Image = '';
				$url = $this->content->relpace_root($top["3"]->ContentID, $top["3"]->Name);
				if($top["3"]->Image != null):
					$Image = '<img src="'.base_url().$top["3"]->Image.'" />';
				endif;
				echo '<a href="'.$url.'">
						'.$Image.'
						<h3 class="box-title">'.$top["3"]->Name.'</h3>
					</a>';
			endif; ?>
		</div>
	</div>
	<div class="col-md-6 box box-1 no-padding">
		<?php if($top["4"] != null):
			$Image = '';
			$url = $this->content->relpace_root($top["4"]->ContentID, $top["4"]->Name);
			if($top["4"]->Image != null):
				$Image = '<img src="'.base_url().$top["4"]->Image.'" />';
			endif;
			echo '<a class="rigth" href="'.$url.'">
					'.$Image.'
					<h3 class="box-title">'.$top["4"]->Name.'</h3>
				</a>';
		endif; ?>
	</div>
	<div class="col-sm-12 box-list no-padding">
		<h3 style="margin-left:20px">Article</h3>
		<div id="item-list">
			
		</div>
	</div>
    <div id="page-selection" style="text-align: center;"></div>
</div>
<script src="<?= base_url("aset"); ?>/js/jquery.bootpag.min.js"></script>
<script src="<?= base_url("aset"); ?>/js/page/blog.js"></script>
