<!-- Masthead -->
<header class="banner text-white bg-cover" style="background: url('aset/img/banner-about-us.jpg') top">
  <!-- <div class="overlay"></div> -->
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
      </div>
    </div>
  </div>
</header>
<div class="banner-content banner-bottom banner-bottomx container-fluid">
  <div class="text-left mb-20">
    <div class="border-bottom-blue"></div>
  </div>
  <div class="title text-white ">
Frequently Asked Questions
  </div>
  <div class="div-link link-white" data-aos="fade-right">
    <ul>
      <li><a href="<?= site_url(); ?>">Home</a></li>
      <li class="line-up-white"><span class="visible">i</span></li>
      <li><a href="<?= current_url(); ?>">Generally Asked Questions</a></li>
    </ul>
  </div>
</div>
<!-- Icons Grid -->
<section class="section  pt-zero text-left">
  <div class="container-fluid ">
    <div class="div-content p-lr-zero">
      <div class="row"> 
        <div class="col-lg-9">
          <div class="div-title">
            <h2 class="title title-small bold text-left pb-3r" data-aos="fade-down">General Questions</h2>
            <div class="panel-group" id="accordion">
              <?php $list = $this->api->faq(); ?>
              <?php foreach($list as $a):?>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $a->ContentID; ?>"><?= $a->Name; ?></a>
                  </h4>
                </div>
                <div id="collapse<?= $a->ContentID; ?>" class="panel-collapse collapse">
                  <div class="panel-body"><?= $a->Description; ?></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="col-lg-3">
        <?php $this->load->view("frontend/side_menu",array("side_menu" => "home")); ?>                       
        </div>
      </div>
    </div>
  </div>
</section>
<?php $this->load->view("frontend/section_custom",array("section"=>"contact_us")); ?>

