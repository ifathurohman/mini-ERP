<!-- Masthead -->
<header class="banner text-white bg-cover" style="background: url('aset/img/banner-business-solutions.jpg') top">
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
          <h2 class="title title-small bold text-left" data-aos="fade-down">Corporate Business Decisions and Credit Risk Solutions Are Driven By Data</h2>              
        </div>
        <div class="content p-3r mb-3r pt-zero" data-aos="fade-down">
          <div class="row">
            <div class="col-lg-6">
              <div class="box-left-blue">
                <h5>Data may disappoint, but it never lies.</h5>
                <p>-Jay Samit</p>
              </div>
            </div>
          </div>
          <p>
            Whether it is just verifying information of a customer, selecting a business partner, awarding a contract, granting credit, choosing a supplier or acquiring a company, every business decision you make must be supported by information. Gone are the days when mere gut-feel or experience was enough to drive your decisions. Today, you need data-driven insights to back your business acumen.
          </p>
          <b>At BizInsights, we offer data-driven business solutions for you to:</b>
          <ul class="ul-check-blue">
            <li class="item">Assess business risks and monitor changes of new and existing clients, suppliers and competitors to mitigate risks and built a profitable business.</li>
            <li class="item">Identify and optimise new business opportunities quickly, effectively negotiate favourable terms, and gain an advantage over your competitions.</li>
            <li class="item">Maximise your purchasing power and built a reliable and sustainable network of suppliers.</li>
            <li class="item">Acquire comprehensive and relevant data needed in analytical tools for strategic planning.</li>
          </ul>
          <p>Our easy-to-use data solutions are designed to benefit both traditional and digital-age users. Customise data and reports are available to you fresh from official sources and on 24/7 to meet your information needs. </p>
          <div class="row row-fluid box">
            <div class="col-lg-6 column">
              <div class="box-top-blue height-100">
                <h4 class="title">Effective Corporate Risk Management</h4>
                <ul class="ul-check-blue">
                  <li class="item">Sound decision making from fresh and official data sources.</li>
                  <li class="item">Fast and consistent information for credit risk assessment available 24/7.</li>
                  <li class="item">Be alerted to significant information changes affecting initial assessment with monitoring of accounts and portfolio management.</li>
                  <li class="item">Deeper analytics and insights for business planning with quality data that are fresh, complete, consistent and accurate</li>
                </ul>
              </div>                    
            </div>
            <div class="col-lg-6 column">
              <div class="box-top-blue height-100">
                <h4 class="title">Efficient KYC Compliance, Onboarding & Customer Due Diligence</h4>                    
                <ul class="ul-check-blue">
                  <li class="item">Technology enabled business verification for fast onboarding of new corporate customers and grow satisfaction. </li>
                  <li class="item">Monitor risky account and conduct live KYC to reduce ongoing compliance risks.</li>
                  <li class="item">Quick Identification of Conflict of Interest by establishing personal and business linkages</li>
                  <li class="item">Improved and support automation in supplier verification and registration process</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="row row-fluid box">
            <div class="col-lg-6 column">
              <div class="box-top-blue height-100">
                <h4 class="title">Identifying Market Opportunities</h4>                    
                <ul class="ul-check-blue">
                  <li class="item">Effective prospecting and lead generation with customised data search and acquisition.</li>
                  <li class="item">Identification of linkages for cross-selling and upselling opportunities</li>
                  <li class="item">Facilitate implementation and adoption of CRM, ERP, SCM and various business solutions through customised data with API</li>
                  <li class="item">Enhance Strategic Planning capabilities through Market analysis, benchmarking, competitive analysis</li>
                </ul>
              </div>
            </div>
            <div class="col-lg-6 column">
              <div class="box-top-blue height-100">
                <h4 class="title">Managing Risks And Opportunities In Import & Export Business And Global Expansion</h4>                    
                <ul class="ul-check-blue">
                  <li class="item">Access to official information globally and instantly to facilitate fast decision making.</li>
                  <li class="item">Be alerted to changes with online and near real-time updates from official data sources across multiple countries.</li>
                  <li class="item">Build and maintain quality databases for data analytics and planning.</li>
                </ul>
              </div>
            </div>
          </div>

          <h4>Ready to explore more ? Check our services</h4>
          <ul class="list-link">
            <?php 
            foreach($this->main->list_link("business_solutions") as $a):
              echo '<li><a href="'.$a->link.'" class="blue"><i class="fa fa-angle-right"></i> '.$a->name.'</a></li>';
            endforeach;
            ?>
<!--             <li><a href="#">Corporate Business Risk</a></li>
            <li><a href="#">KYC and Corporate Due Diligence</a></li>
            <li><a href="#">Corporate Linkages</a></li>
            <li><a href="#">Strategic Marketing Lead Generation</a></li>
            <li><a href="#">Company Database and Data Enhancement</a></li>
            <li><a href="#">Information Monitoring</a></li>
            <li><a href="#">API - Report and data</a></li>
            <li><a href="#">International Information - Company Registries</a></li> -->
          </ul>
        </div>
      </div>
      <div class="col-lg-3">
        <?php $this->load->view("frontend/side_menu",array("side_menu" => "business_solution")); ?>           
      </div>
    </div>
  </div>
</section>