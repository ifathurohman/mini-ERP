<!-- Masthead -->
<!--     <header class="banner text-white bg-cover" style="background: url('aset/img/banner-about-us.jpg') top">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
          </div>
        </div>
      </div>
    </header>
    <div class="banner-content banner-bottom container-fluid">
      <div class="text-left mb-20">
        <div class="border-bottom-blue"></div>
      </div>
      <div class="title text-white ">
        About Us
      </div>
      <div class="div-link link-white" data-aos="fade-right">
        <ul>
          <li><a href="<?= site_url(); ?>">Home</a></li>
          <li class="line-up-white"><span class="visible">i</span></li>
          <li><a href="<?= current_url(); ?>">About Us</a></li>
        </ul>
      </div>
    </div> -->
    <section class="buy-report div-contact showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters" >
        <!-- <div class="row no-gutters"> -->
          <div class="col-lg-5 my-auto bg-white" data-aos="fade-right">
            <div class="div">
              <h2 class="title section-title">Contact Us</h2>
            </div>
            <ul class="ul-section list-link abous-us-list-contact">
              <li data-aos="fade-right">
                <a href="JavaScript:;" class="active" title="Buiness profile search" 
                data-url="<?= site_url("products-and-pricing#business-profile-search"); ?>" 
                data-email="<?= $this->main->set_('ContactUsJakartaEmail'); ?>"
                data-desc="<?= $this->main->set_('ContactUsJakartaAddress'); ?>"
                data-contact='<?= $this->main->set_('ContactUsJakartaList'); ?>'
                >ID.IOT Jakarta</a>
              </li>
              <li data-aos="fade-right">
                <a href="JavaScript:;" title="Corporate information search" 
                data-url="<?= site_url("products-and-pricing#corporate-information-search"); ?>" 
                data-email="<?= $this->main->set_('ContactUsBandungEmail'); ?>"
                data-desc="<?= $this->main->set_('ContactUsBandungAddress'); ?>"\
                data-contact='<?= $this->main->set_('ContactUsBandungList'); ?>'
                >ID.IOT Bandung</a>
              </li>
              <li data-aos="fade-right">
                <a href="JavaScript:;" title="Corporate financial information search" 
                data-url="<?= site_url("products-and-pricing#corporate-financial-information"); ?>" 
                data-email="<?= $this->main->set_('ContactUsSemarangEmail'); ?>"
                data-desc="<?= $this->main->set_('ContactUsSemarangAddress'); ?>"
                data-contact='<?= $this->main->set_('ContactUsSemarangList'); ?>'>ID.IOT Semarang</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-7 text-white buy-report-right" data-aos="fade-up" style="background-image: url('aset/img/bg-customer-service.jpg');background-size: cover;background-position: top;" >
            <div class="div-sub">
              <h2 class="section-title"></h2>
              <p class="lead text-section"></p>
              <p class="lead email"></p>
              <p class="lead contact"></p>
              <!-- <a href="#" class="link blue">Read More</a> -->
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="our-solutions bg-light text-center">
        <div class="container">
            <div class="section-title aos-init aos-animate" data-aos="fade-down">Vision & Mission</div>
            <div class="text-center">
                <div class="border-bottom-blue"></div>
            </div>
            <div class="div-content" style="padding-top: 40px;">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="div-left aos-init aos-animate" data-aos="fade-right">
                            <h3 class="blue-title">VISION</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="div-right widthx-80 aos-init aos-animate" data-aos="fade-right">
                           <?= $this->main->set_('CompanyVision');?>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-lg-6">
                        <div class="div-left aos-init aos-animate" data-aos="fade-right">
                            <h3 class="blue-title">MISSION</h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="div-right widthx-80 aos-init aos-animate" data-aos="fade-right">
                           <?= $this->main->set_('CompanyMission');?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="container-fluid p-0">
            <div class="row no-gutters">
                <div class="col-lg-4 aos-init" data-aos="fade-right">
                    <div class="box-solution box-shadow bg-white">
                        <div class="number">1</div>
                        <h3 class="title">Business Solutions</h3>
                        <div class="content">At BizInsights, we offer data-driven business solutions for you to assess business risk while identifying and optimising new business opportunities.</div>
                        <div class="div-link"><a href="business-solutions" class="blue">Find Out More</a></div>
                    </div>
                </div>
                <div class="col-lg-4 aos-init" data-aos="fade-right">
                    <div class="box-solution box-shadow bg-white">
                        <div class="number">2</div>
                        <h3 class="title">Industry Served</h3>
                        <div class="content">We serve various industry verticles and help them to minimise risk and maximise opportunities with our data-driven solutions.</div>
                        <div class="div-link"><a href="traders-importers-exporters" class="blue">Find Out More</a></div>
                    </div>
                </div>
                <div class="col-lg-4 aos-init" data-aos="zoom-in">
                    <div class="box-solution box-shadow bg-white">
                        <div class="number">3</div>
                        <h3 class="title">API Capabilities</h3>
                        <div class="content">Our API is designed for ease of integration with customer management software and is a great convenience for automating data and information acquisition.</div>
                        <div class="div-link"><a href="api-capabilities" class="blue">Find Out More</a></div>
                    </div>
                </div>
            </div>
        </div> -->
    </section>
    <section class="bg-light text-center mt-zero section-experience" style="display: none;">
        <div class="container mb-3r ">
            <div class="section-title aos-init aos-animate" data-aos="fade-down">Experience</div>
            <div class="text-center">
                <div class="border-bottom-blue"></div>
            </div>
        </div>
        <div class="container-fluid container-gallery p-0">
            <div class="row gallery-img list-experience">
            </div>
            <div class="row">
              <div class="col-sm-12 btn-list-experience" style="display: none;">
                <div class="mt-3r mb-3r">
                  <a href="javascript:;" class="btn btn-custom with-100p" onclick="GetListExperience('load');">See More</a>                
                </div>
              </div>
            </div>
        </div>
    </section>
