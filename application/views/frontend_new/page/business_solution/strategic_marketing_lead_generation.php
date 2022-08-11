<!-- Masthead -->
<header class="banner text-white bg-cover" style="background: url('aset/img/banner-strategic-marketing.jpg') top">
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
            <p>Are you developing a strategic marketing plan for your company with a focus on generating top quality business?  
Strategic planning with actionable sales and marketing plan requires reliable data-driven insights and means to execute effectively. A sound marketing and sales strategy with well qualified sales leads can produce better sales results and higher profitability.</p>
            <p>Customer profiling is usually a key action step in planning, but  you need the ability to search and acquire matching customer profiles as prospects for your business. There are many tools available, some driven by AI for efficient sales lead generation. However, the quality of data eventually will determine the ROI of your target marketing and sales efforts.</p>
          </div>
          
        </div>
        <div class="div-title p-3r pt-zero">
          <h2 class="title title-small bold text-left" >How To Qualify Your Sales And Marketing Leads?</h2>              
        </div>
        <div class="content p-3r mb-3r pt-zero" >
          <ul class="ul-check-blue">
            <li class="item">An  essential first step is to conduct market analysis and customer profiling to establish the primary and secondary criterion that may be used to identify and generate leads and convert them into high-value customers.</li>
            <li class="item"><p>CRM is as useful as the data it contains. To enable adoption and improve usefulness of such customer solutions, you need good quality data to maximise the CRM’s effectiveness.</p>
              <b>You can generate quality data from:</b>
              <ul>
                <li class="item">External data sources such as public registry, information providers, lead generations providers</li>
                <li class="item">Internal data sources such as historical sales data, customer profiles generated from sales, operations and customer services data</li>  
              </ul>
            </li>
            <li class="item"><p>The criteria to determine the ideal customer profile need to be sufficiently broad to optimise the opportunities in the target market segment. Excessively strict rules can limit access to prospects pool and cause you to miss business opportunities</p>
            <b>Customer profiling and identifying the right criterion for leads can be based on the following:</b>
            <ul class="">
              <li class="item">What is the Industry classification to be targeted</li>
              <li class="item">What is the revenue size of the targeted companies?</li>
              <li class="item">How long must the company be established?</li>
              <li class="item">What other financial indicators or criterion best match your ideal customer profile and internal policies?</li>
              <li class="item">How well does the targeted prospect list match your ideal customer profile?</li>
              <li class="item">What's the perfect use case for this prospect?</li>
            </ul>
            </li>
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
              At BizInsights we offer very user-friendly database solutions covering Singapore Registered companies. Our customise data services can help you improve the effectiveness of your marketing and sales teams by generating better quality leads.
            </p>
          </div>
        </div>
        <div class="col-lg-6 p-lr-3r">
          <div class="div-right" >
            <h2 class="title title-smallx bold" >Bizinsights Advantages : </h2>
            <ul class="list-o text-left li-pb-10" >
              <li>Data that can help you analyse and identify the best prospect profile for targeted marketing improve the quality of leads</li>
              <li>Provide relevant customise data and information support for better pre-sales preparation and deliver insights for effective proposal and customer engagements.</li>
              <li>Provide company and financial information on your prospects to pre-qualify credit terms and facilitate smart negotiation.</li>
              <li>Unearth corporate linkages to help identify upsell and cross-sell opportunities in the group</li>
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