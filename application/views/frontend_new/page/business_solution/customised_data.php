<!-- Masthead -->
<header class="banner text-white bg-cover" style="background: url('aset/img/banner-customised-data.jpg') top">
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
            <p>Access to comprehensive Singapore Company Data enables you to select and build data-driven insights  across a company’s whole business cycle. From business planning, sales and marketing, designing policy, inventory, managing finance, debts and cash flow, you need information. Such information must be customised to users’ needs to produce relevant insights that are truly actionable. </p>
          </div>
          
        </div>
        <div class="div-title p-3r pt-zero">
          <h2 class="title title-small bold text-left" >Acquiring Customised Data for Business Solutions</h2>              
        </div>
        <div class="content p-3r mb-3r pt-zero" >
          <p>Most business and corporations large or small, adopt CRM, ERP, SCM and similar business solutions. To be effective, such solutions need quality data inputs from multiple sources. Third party database marketing companies can provide data that can be integrated into such business solutions to derive Business Intelligence and used across all the functions in the whole business cycle, for applications such as:</p>
          <ul class="ul-check-blue">
            <li class="item">Strategic planning</li>
            <li class="item">Sales and marketing</li>
            <li class="item">Operations</li>
            <li class="item">Credit assessment</li>
            <li class="item">Business policy setting</li>
            <li class="item">Supply chain management</li>
            <li class="item">Finance and debt management.</li>
          </ul>
          <b>Looking For A Reliable And Customised Singapore Company Data?</b>
          <p>When you are dealing with Singapore companies regularly, it makes sense to invest in a reliable Singapore company database comprising well-maintained company information.</p>
          <p>Preferably, such business profiles and company information data comes from official sources and can be easily integrated into business solutions. Manual data entry work is very tedious and fraught with errors. Manual work is also unproductive and affects adoption and use of the business solution and ROI. Customise data, especially via API is the most practical approach to acquiring Singapore Company Data.</p>
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
              We offer very user-friendly customised database solutions covering all Singapore Registered companies to facilitate database development, data enhancement and data quality maintenance.
            </p>
          </div>
        </div>
        <div class="col-lg-6 p-lr-3r">
          <div class="div-right" >
            <h2 class="title title-smallx bold" >Bizinsights Advantages : </h2>
            <ul class="list-o text-left li-pb-10" >
              <li>Access to the official and most current database (updated daily) for information needed throughout the business cycle</li>
              <li>Assist you in setting data search criterion and provide you with customise dataset.</li>
              <li>Our Data can be used to pre-populate or update CRM, ERP and SCM system to ease data entry work and keep data updated regularly.</li>
              <li>You can monitor company information changes to improve database freshness</li>
              <li>Customised data acquisition with specific search criterion and acquire only relevant data for different applications and requirements.</li>
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