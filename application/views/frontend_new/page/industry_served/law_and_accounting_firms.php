<!-- Masthead -->
<header class="banner text-white bg-cover" style="background: url('aset/img/banner-law-and-accounting.jpg') top">
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
      <li><a href="<?= site_url("industry-served"); ?>">Industry Served</a></li>
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
          <h2 class="title title-small bold text-left" >How To Guide Businesses On Crucial Decisions</h2>              
        </div>
        <div class="content p-3r mb-3r pt-zero">
          <p>Law firms and accounting firms guide businesses on crucial decisions and need official, updated and unbiased information to base its recommendations and extend impactful business consultancy for their clients.</p>
          <p>There are strict regulation, policies and industry practices governing such professional service providers. Compliance check at customer onboarding stage is key to avoid regulatory infringement and reputational risks. BizInsights provide daily updated information from the Singapore company registry ( ACRA) and access similar official sources to serve the needs of all professional service providers.</p> 
          <h5 class="blue mt-3r">How can Bizinsights help you ?</h5>
          <b>In Conflict of Interest, KYC Compliance and Corporate Due Diligence </b>
          <p>Both law and accounting firms need to perform KYC checks, conflict of interest and other checks before accepting any client assignment. </p>
          <p>To be effective in such KYC compliance and CCD checks, they require reliable first party and counterparty  assessment with corporate linkages  information.</p>   
        </div>
      </div>
      <div class="col-lg-3">
        <?php $this->load->view("frontend/side_menu",array("side_menu" => "business_solution")); ?>           
      </div>
    </div>
  </div>
</section>
<section class="showcase bg-banner">
  <div class="container-fluid p-0">
    <div class="row no-gutters">
      <div class="col-lg-8 aos-init aos-animate" data-aos="fade-right">
        <div class="div-content">
          <h2 class="section-title">The Best Solution for Your Business Projects</h2>
          <p class="text-section">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
        </div>
      </div>
      <div class="col-lg-4 aos-init aos-animate" data-aos="fade-down">
        <div class="div-content">
          <div class="div-btn p-0 ">
            <a href="<?= site_url("contact-us"); ?>" class="btn btn-black pull-right">CONTACT US</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>