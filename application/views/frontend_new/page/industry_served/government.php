<!-- Masthead -->
<header class="banner text-white bg-cover" style="background: url('aset/img/banner-goverment.jpg') top">
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
          <h2 class="title title-small bold text-left" >How To Protect Public Interest With Purchases Based On Reliable Vendor Information</h2>              
        </div>
        <div class="content p-3r mb-3r pt-zero">
          <p>To effectively manage public projects, government bodies need access to reliable and official information on Singapore companies as well as from across the globe. This is especially so in vendor assessment before awarding contract and in monitoring changes in the vendor’s business throughout the performance of the tender, to mitigate the risk of project failure due to the vendor’s business changes.
          <h5 class="blue">How can Bizinsights help you?</h5>
          <b>In Vendor/Bidder Risk Management</b>
          <p>When entities bid for critical government projects, you need to understand the bidders’ relationships across various department in your own organisation. This helps you to optimise  procurement process and enhance your negotiating power that can result in significant savings.</p>
           
          <p>Uncovering risks and dependencies on vendors is a vital factor especially in sensitive areas such as defence, energy, health etc. While there is existing policies and processes in place, it is critical to be vigilant  as company changes may have happened during long tender evaluation and post contract awarding. Do the following before and after awarding tender:</p>



          <ul class="ul-check-blue mb-3r">
            <li class="item">Verify information and document provided by bidder against the latest from official sources.</li>
            <li class="item">Review company linkages at corporate and individual level to identify potential conflict of interests.</li>
            <li class="item">Independent compliance check to avoid dealing with questionable entities.</li>
            <li class="item">Monitor significant company profile and financial information changes and be alert to potential supply or project failure risks after tender is awarded. This is imperative for project of national interest.</li>
          </ul>

          <p class="mb-2r">Besides our capabilities in all the above areas for Singapore companies, we have access to over 150 company registries worldwide. This means that you can better manage procurement risk locally and globally.</p>

          <b>In Regulating Programs for Businesses and Compliance to Policies  </b>
          <p>Government grants, subsidies and business assistance program users need to be bonafide and adhere to the rules of engagement.  Information, customise data and corporate linkage capabilities from BizInsights can helps you to maintain a vigilant oversight by verifying information about beneficiaries of the government agencies’ services. Relationships amongst applicants and company information changes can be monitored to alert various agencies on potential fraud. These insights can facilitate agencies to identify and take action on errant applicants.</p>         
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
          <p class="text-section">Streamline Your Business Process With Us Now !</p>
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