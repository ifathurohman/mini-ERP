<div class="data-page" data-modul="list-product" data-id="<?= $ID; ?>"></div>
<section class="section-normal bg-light text-center pt-3r" style="min-height: 0px;">
  <div class="container">
	<div class="row list-category justify-content-center">
		<?php foreach($this->main->Category('list_active') as $key => $a): $active = ""; if($a->Link == $ID): $active = "active"; endif; ?>
			<div class="col-sm-3 col-xs-6">
				<a href="javascript:;" onclick="choose_category(this,'choose_category')" data-id="<?= $a->Link; ?>">
					<div class="item div-<?= $a->Link; ?> <?= $active; ?>">
						<i class="icons <?= $a->Icon; ?>"></i>
						<p class="title"><?= $a->Name; ?></p>
					</div>
				</a>
			</div>		
  		<?php endforeach;  ?>
	</div>
  </div>
</section>
<section class="section-normal bg-light text-center" style="min-height: 0px;">
  <div class="container">
  	<div class="loading-item">	
	  	<div class="row list-product justify-content-center loading-list">
			<?php $i = 1; foreach(range(1,4) as $a): ?>
				<div class="col-sm-6 col-xs-6">
					<a href="#">
						<div class="item width-100">
							<div class="left">
								<ul class="ul-none p-zero">
	                               <li class="lef m-10 w50 mt-30"></li>
	                               <li class="lef"></li>
	                               <li class="lef"></li>
	                               <li class="lef"></li>
	                               <li class="lef m-10 w70"></li>
	                            </ul>		
							</div>
							<div class="right">
								<ul class="ul-none p-20 pt-zero">
	                               <li class="lef img m-10 h200"></li>
	                            </ul>
							</div>
						</div>
					</a>
				</div>		
	  		<?php endforeach;  ?>
		</div>
  	</div>
	<div class="row list-product list-product-data ">
	
	</div>
  </div>
</section>