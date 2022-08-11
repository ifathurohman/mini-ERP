<!-- Masthead -->
<header class="banner text-white bg-cover" style="background: url('aset/img/banner-kyc.jpg') top">
  <!-- <div class="overlay"></div> -->
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
      </div>
    </div>
  </div>
</header>
<div class="banner-content banner-bottom container-fluid mb-3r">
  <div class="text-left mb-20">
    <div class="border-bottom-blue"></div>
  </div>
  <div class="title text-white ">
    <?= $page_name; ?>
  </div>
  <div class="div-link link-white" data-aos="fade-right">
    <ul>
      <li><a href="<?= site_url(); ?>">Home</a></li>
      <li class="line-up-white"><span class="visible">i</span></li>
      <li><a href="<?= site_url("business-solutions"); ?>">Business Solutions</a></li>
      <li class="line-up-white"><span class="visible">i</span></li>
      <li><a href="<?= current_url(); ?>"><?= $page_name; ?></a></li>
    </ul>
  </div>
</div>
<!-- Icons Grid -->
<section class="section  pt-zero text-left pb-zero">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-9">
        <div class="div-title p-3r pt-zero">
          <h2 class="title title-small bold text-left" ><?= $page_name; ?></h2>              
        </div>
        <div class="content p-3r mb-3r pt-zero" >
          <b>Businesses and institutions conduct KYC compliance checks on customers to:</b>
          <ul class="ul-check-blue">
            <li class="item">Ascertain the identity and verify information of the customer as part of the customer Onboarding process.</li>
            <li class="item">Ascertain that the source of wealth and the nature of the customer’s business is legitimate and assess AML and fraud risk.</li>
            <li class="item">Conduct monitoring and track changes as part of the live KYC compliance process.</li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3">
        <?php $this->load->view("frontend/side_menu",array("side_menu" => "business_solution")); ?>           
      </div>
    </div>
  </div>
</section>
<section class="section bg-grey ptb-3r text-center">
  <div class="container-fluid p-zero">
    <div class="section-title title title-small bold" >How Can BizInsights Help You ?</div>
    <div class="div-content">
      <div class="row">
        <div class="col-lg-6 p-lr-3r">
          <h2 class="title title-smallx bold text-left" >At BizInsights</h2>
          <div class="content text-justify" >  
            <p>
              We offer very user-friendly database solutions covering all Singapore Registered companies and information from global official data sources. Our reports are used in business verification and customer onboarding process as well as monitoring of important changes throughout the customer lifecycle as part of live KYC.
            </p>
          </div>
        </div>
        <div class="col-lg-6 p-lr-3r">
          <div class="div-right" >
            <h2 class="title title-smallx bold" >Bizinsights Advantages : </h2>
            <ul class="list-o text-left li-pb-10" >
              <li>Our range of reports has the necessary content and official branding.</li> 
              <li>API connection capabilities reduce manual information compilation work and can facilitate users’ need to build technical interface for direct data feed into proprietary system and business solutions for more efficient onboarding process.</li>
              <li>Reduces errors from manual data entry and perform live KYC with monitoring of changes and auto data updating.</li>
              <li>Access to a 1- stop global official registries source</li>
            </ul>
          </div>              
        </div>
        <div class="col-lg-12">
          <div class="div-btn pt-3r" >
            <a href="<?= site_url("contact-us"); ?>" class="btn btn-default btn-white w-260 mb-20 btn-large" ><span class="label-btn">CONTACT US</span> <span class="icon-btn icon-btn-grey icon-btn-right"><i class="fa fa-arrow-right"></i></span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>