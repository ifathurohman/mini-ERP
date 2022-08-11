<?php if($side_menu == "business_solution"): ?>
<div class="div-btn mb-3r side-menu">
  <div class="panel-group panel-group-btn" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#btn-one">
         Business Solutions</a>
        </h4>
      </div>
      <div id="btn-one" class="panel-collapse collapse">
        <div class="panel-body">
          <div class="list-link">
            <?php foreach($this->main->list_link("business_solutions") as $a): echo '<a class="item" href="'.$a->link.'" title="'.$a->name.'">'.$a->name.'</a>'; endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="accordion-toggle"  data-toggle="collapse" data-parent="#accordion" href="#btn-two">
          Industry Served</a>
        </h4>
      </div>
      <div id="btn-two" class="panel-collapse collapse">
        <div class="panel-body">
          <div class="list-link">
            <?php foreach($this->main->list_link("industri_served") as $a): echo '<a class="item" href="'.$a->link.'" title="'.$a->name.'">'.$a->name.'</a>'; endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#btn-tree">
          API Capabilities</a>
        </h4>
      </div>
      <div id="btn-tree" class="panel-collapse collapse">
        <div class="panel-body">
          <div class="list-link">
            <?php foreach($this->main->list_link("api_capabilities") as $a): echo '<a class="item" href="'.$a->link.'" title="'.$a->name.'">'.$a->name.'</a>'; endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view("frontend/side_menu",array("side_menu" => "x")); ?>                       


<?php elseif($side_menu == "home"): ?>
<div class="div-btn mb-3r side-menu">
  <div class="panel-group panel-group-btn" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="link" href="<?= site_url();?>">Home</a>
        </h4>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#btn-one">Our Solutions</a>
        </h4>
      </div>
      <div id="btn-one" class="panel-collapse collapse">
        <div class="panel-body">
          <div class="list-link">
            <?php foreach($this->main->list_link("our_solutions") as $a): echo '<a class="item" href="'.$a->link.'" title="'.$a->name.'">'.$a->name.'</a>'; endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="link" href="<?= site_url("faq");?>">Faqs</a>
        </h4>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="link" href="<?= site_url("about-us");?>">About Us</a>
        </h4>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a class="link" href="<?= site_url("blog");?>">Blog</a>
        </h4>
      </div>
    </div>
  </div>
</div>
<?php else: ?>
  <div class="div-btn p-lr-zero">
    <ul>
      <li data-aos="fade-right">
        <a href="<?= site_url("products-and-pricing"); ?>" class="btn btn-default btn-full btn-blue w-260 mb-20"><span class="label-btn">BUY REPORTS</span> <span class="icon-btn icon-btn-right"><i class="fa fa-arrow-right"></i></span></a>
      </li>
      <li data-aos="fade-right">
        <a href="<?= site_url("products-and-pricing"); ?>" class="btn btn-default btn-full btn-blue w-260 mb-20"><span class="label-btn">PRODUCTS & PRICING</span> <span class="icon-btn icon-btn-right"><i class="fa fa-arrow-right"></i></span></a>
      </li>
      <li data-aos="fade-right">
        <a href="<?= site_url("contact-us"); ?>" class="btn btn-default btn-full btn-blue w-260 mb-20"><span class="label-btn">CONTACT US NOW</span> <span class="icon-btn icon-btn-right"><i class="fa fa-arrow-right"></i></span></a>
      </li>
      <li data-aos="fade-right">
        <a href="<?= site_url("news-and-events"); ?>" class="btn btn-default btn-full btn-blue w-260 mb-20"><span class="label-btn">NEWS AND EVENTS</span> <span class="icon-btn icon-btn-right"><i class="fa fa-arrow-right"></i></span></a>
      </li>
    </ul>                
  </div>  
<?php endif; ?>