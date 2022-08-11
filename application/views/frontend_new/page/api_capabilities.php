<!-- Masthead -->
<header class="banner text-white bg-cover" style="background: url('aset/img/banner-api-capabilities.jpg') center">
  <!-- <div class="overlay"></div> -->
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
    API Capabilities
  </div>
  <div class="div-link link-white" data-aos="fade-right">
    <ul>
      <li><a href="<?= site_url(); ?>">Home</a></li>
      <li class="line-up-white"><span class="visible">i</span></li>
      <li><a href="<?= current_url(); ?>">API Capabilities</a></li>
    </ul>
  </div>
</div>
<!-- Icons Grid -->
<section class="section  pt-zero text-left">
  <div class="container-fluid">
    <div class="div-content">
      <div class="row">
        <div class="col-lg-9">
          <div class="div-title">
            <h2 class="title title-small bold text-left pb-3r p-l-3r" data-aos="fade-down">API Access For Users Across Functions, Applications And Processes</h2>              
          </div>
          <div class="content p-3r mb-3r" data-aos="fade-down">
            <p>
              We offer access to data from our database through API. This is a more efficient approach to acquiring information and data for business solutions and proprietary SW platform. Our API is designed for ease of integration with customer management software and is a great convenience for automating data and information acquisition for Business Risk Management, On-Boarding, KYC and Compliance operations.
            </p>
            <b>Information Services For Banks Via API </b>
            <p>
              We can support data needs of bank’s corporate account opening SW applications and credit risk management systems. Designed for banks and clients who need official company information regularly, our API serves existing or new process and platform with data in XML/PDF format that is consistent, accurate, complete and up to date (with daily refresh from ACRA) to all users.  
            </p>
            <p>Here are some of the benefits of using our API.</p>
            <div class="box-top-blue">
              <b>Access to full official Singapore company information universe</b>
              <ul class="ul-check-blue">
                <li class="item">PDF reports available are co-branded and approved by ACRA with their logo</li>
                <li class="item">Structured data products with PDF report and XML report API available via link and/or streaming.</li>
                <li class="item">Product data content coverage : 
                    <ul>
                      <li>• Corporate Profiles</li>
                      <li>• Financial filings</li>
                      <li>• Directors and Shareholders</li>
                      <li>• Ultimate Business Owner & Corporate linkages</li>
                      <li>• Compliance information</li>
                    </ul>
                </li>
              </ul>
            </div>
            <div class="box-top-blue">
              <b>Enable internal and external application development</b>
              <ul class="ul-check-blue ul-half">
                <li class="item">Clear and consistent XML structure</li>
                <li class="item">Ease of data integration and maintenance</li>
                <li class="item">Ease of use and consumption with simple data perimeters</li>
                <li class="item">Simple API End User Licensing model, ease of implementation.</li>
                <li class="item">Secured and continuous connection and uptime</li>
                <li class="item">Scalable infrastructure to match number of users</li>
              </ul>
            </div>
            <div class="box-top-blue">
              <b>Near real-time information</b>
              <ul class="ul-check-blue ul-half">
                <li class="item">Data freshness within 24 hours from official source</li>
                <li class="item">Service availability 24 by 7.</li>
              </ul>
            </div>

            <h2 id="international-information">International Information from Company Registries</h2>


            <p>Our customers can now get access to information on companies worldwide. We have access to a global network of official registry and commercial information sources via API with updated data.</p>
             
            <p>We partner with the best-in-class registry information providers with access to information from  over 150 countries’ company registries. We can offer reports and products with fast delivery and very attractive pricing worldwide, to serve basic company verification to risk assessment needs for all our customer segments.  A big saving compared to other international information providers.</p>
            <p>
              <b class="blue">Access</b> International Company Registry reports.
            </p>




            <h2 class="blue" id="uses-case">Use Cases</h2>
            <p><b>Improving corporate / investment banking new customer onboarding and KYC experience.and KYC experience.</b></p>
            <p>
            Improving corporate / investment banking new customer onboarding and KYC experience.and KYC experience.
            Verifying legal status of companies, its shareholdings and directorships, business relationships and authenticating information from customers is a tedious process. Customer’s information about himself or his business also needs supporting document from official sources as proof. Records and documents provided are sometimes incomplete, outdated and not from official sources.
            </p>
            <p>
            In order to satisfy onboarding and compliance needs, banks often need to seek out various information sources to verify source of wealth on the customer. Requiring the customer to provide all required and latest supporting document can results in unpleasant UX, lost of business and affects the bank’s market position.
            </p>  
            <p>               
            With access to official information sources with API capabilities, the latest official documents required can be obtained directly via API by the bank’s Makers and Checkers and in XML/PDF format for direct use and  to feed onboarding or risk management system. This automates some data capture processes and reduces human error in data entries, help to speed up approval process and delight customers.
            </p>

            <p><b>
            Improving fraud prevention</b></p>
            <p>
            Documents provided by customer may not be genuine, fresh and official. Banks are exposed to possible fraud and non-compliance if they only rely on such supporting documents.  Manual data handling also increase opportunities for fraud.
            </p>
            <p>
            Direct access and feed from official source via BizInsights’ API reduces reliance on data manually keyed in or acquired from hard copies documents from customers which may be outdated or even from questionable sources.
            </p>
            <p><b>
            Live KYC - Monitoring changes for on-going credit/compliance risk</b></p>
            <p>
            While periodic manual credit risk or KYC compliance review are usually practised at banks, significant changes affecting the customer’s standing can happen in between reviews or missed during review. Managing updates via periodic review method on a large volume of accounts is tedious and time-consuming. Customers are unlikely to volunteer such changes proactively. Failure in ongoing KYC compliance may means credit, AML and other risks that the bank did not detect in time and can lead to losses, penalties from regulators and affects bank’s reputation.
            </p>
            <p>
            Monitoring of an account can be set for a cluster of risk-significant data element, like change in directors, shareholders, registered charges, capital, revenue, profit etc. BizInsights can provide data on these changes for all companies in Singapore with daily refresh from ACRA. This monitoring capability helps to automate the accounts review and alert the bank to significant changes.
            </p>
          </div>
        </div>
        <div class="col-lg-3">
          <?php $this->load->view("frontend/side_menu",array("side_menu" => "business_solution")); ?>                       
        </div>
      </div>
    </div>
  </div>
</section>
<?php $this->load->view("frontend/section_custom",array("section"=>"contact_us")); ?>