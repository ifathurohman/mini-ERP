<!-- Masthead -->
<header class="banner text-white bg-cover" style="background: url('aset/img/banner-financial-service.jpg') center">
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
          <h2 class="title title-small bold text-left" >Reliable Information for Credit Management And Risk Assessment In Banks</h2>              
        </div>
        <div class="content p-3r mb-3r pt-zero" >
          <p>The financial industry is fraught with many risks, be it banks, insurance company, investment firms, brokerage firms and non-bank lending and credit institutions. Efficient credit management in banks and financial institutions requires credit risk management expertise in multiple disciplines. Besides credit risk management, financial institution also need to know everything it can about its clients. KYC Risk management for Banks and financial institutions involves knowing and understanding source of wealth and where different money trails leads.</p>

          <p>Whether extending loans, opening a corporate accounts or taking deposit from a customer, access to quality information will help you:</p>
          <ul class="ul-check-blue mb-3r">
            <li class="item">Profile your customers and understand behaviour</li>
            <li class="item">Understand potential credit risks</li>
            <li class="item">Gain unbiased insights to client’s financial health and potential for offering other banking facilities</li>
            <li class="item">Stay compliance with regulators and protect reputation from dubious relationships</li>
            <li class="item">Adjust or design  financial risk management and customer policies</li>
          </ul>

          <h5 class="blue">How can Bizinsights help you?</h5>


          <b>In Credit Risk Management</b>
          <p>We can help you verify, monitor and update both financial and non-financial information provided by borrowers to build a complete and accurate picture about the total risk exposure. This can be done at account opening, and with on-going live monitoring of risky accounts or periodic review of customers.</p>


          <b>In KYC, AML and Other Compliance assessment</b>
          <p>With official and updated information supported by technology, KYC and AML Compliance work can be more automated to reduce errors or fraud and speed up customer approval. You need information that runs deeper than merely a corporate customer’s name and company number. We can support your information needs from account opening to live KYC with monitoring of significant changes in the corporate account and its key personnel throughout the customers’ lifecycle.</p>


          <b>In Designing and Implementing Policies</b>
          <p>At a macro level, our customised data and comprehensive coverage of data can be used in analytics and Artificial Intelligence (AI) engines to derive actionable insights for policy design and to help you fine-tune credit and compliance policies and processes.</p>

          <b>In Marketing and Customer Onboarding</b>
          <p>The current level of business with a customer is not an indicator of how big a potential they really offer. Uncovering vital company linkage information about your most important customers helps you in cross-selling and upselling. On the flip side, understanding corporate linkages can enhance business insights and avoid acquiring customers with potential compliance and AML risks.</p>
          <p>An improvement in customer engagement can be achieved with reliable pre-sales information for amicable negotiation and built deep customer relationships. Beyond securing the deal, fast and easier client onboarding procedures can be your winning edge to a great customer experience.</p>
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