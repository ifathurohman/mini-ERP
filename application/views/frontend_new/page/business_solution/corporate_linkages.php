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
        <div class="content p-3r pt-zero">
          <div class="bg-ea p-1r radius-3">
            Tracing and identifying the relationships between various entities is critical to determining risks and opportunities
          </div>
          
        </div>
        <div class="div-title p-3r pt-zero">
          <h2 class="title title-small bold text-left" >Important Insights From Corporate Linkages</h2>              
        </div>
        <div class="content p-3r mb-3r pt-zero" >
          <p>Effective account management requires comprehensive view of an entity and its corporate linkages. This is especially so for key account management to upsell, cross-sell and provide consistent pricing model and UI & UX across the group. Failure to conduct a thorough linkage check can results in embarrassing mixed treatment, conflict of interest issues, penalty from regulators and affects reputation. This is especially for legal and professional firms who unknowingly engage in legal action against a related company of a client or offering consultancy service to an  audit client.
          </p>
          <b>Establishing corporate linkages lets you:</b>
          <ul class="ul-check-blue">
            <li class="item">Identify new business opportunities and grow sales across your customers’ related companies with upsell and cross-sell effort.</li>
            <li class="item">Increase buying power by aggregating purchases from multiple suppliers belonging to the same group</li>
            <li class="item">Determine and manage the total risk or credit exposure across the business group and not just individual entities.</li>
            <li class="item">Prevent conflict of interests in your business transactions and inconsistent treatment across a group.</li>
            <li class="item">Enhance the effectiveness of your KYC function and avoid business relationships that can affect your reputation</li>
            <li class="item">Better customer and market analysis for strategic decision making</li>
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
              We offer very user-friendly database solutions covering all Singapore Registered companies. This means that we can help you to identify and establish corporate linkages amongst different stakeholders you deal with.
            </p>
          </div>
        </div>
        <div class="col-lg-6 p-lr-3r">
          <div class="div-right" >
            <h2 class="title title-smallx bold" >Bizinsights Advantages : </h2>
            <ul class="list-o text-left li-pb-10" >
              <li>Identifying the Ultimate Business Owner and establish corporate linkages which helps you mitigate group level credit supplier risks.</li>
              <li>Our reports seek to expose hidden business opportunities, provide information to enable better negotiation and secure group business opportunities.</li>
              <li>Early identification of conflict of interests for regulatory compliance and fraud detection purposes</li>
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