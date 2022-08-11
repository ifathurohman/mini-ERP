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
    <div class="banner-content banner-bottom container-fluid">
      <div class="text-left mb-20">
        <div class="border-bottom-blue"></div>
      </div>
      <div class="title text-white ">
        Contact US
      </div>
      <div class="div-link link-white" data-aos="fade-right">
        <ul>
          <li><a href="<?= site_url(); ?>">Home</a></li>
          <li class="line-up-white"><span class="visible">i</span></li>
          <li><a href="<?= current_url(); ?>">Contact Us</a></li>
        </ul>
      </div>
    </div>
    <!-- Icons Grid -->
    <section class="section  pt-zero text-left">
      <div class="container-fluid">
        <div class="div-content">
          <div class="row">
            <div class="col-lg-12">
              <div class="div-title">
                <h2 class="section-title">Get In Touch With Us</h2>
                <div class="border-bottom-blue"></div>      
              </div>
            </div>
            <div class="col-lg-6">
              <!-- <div>
                <form class="form">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <input type="text" name="" class="form-control" placeholder="Name">
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <input type="text" name="" class="form-control" placeholder="Email">
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <input type="text" name="" class="form-control" placeholder="Contact Number">
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <textarea placeholder="message"></textarea>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <button class="btn btn-blue">Submit</button>
                    </div>
                  </div>
                </form>
              </div> -->
              <div class="div showcase-text" data-aos="fade-right">
                <div class="box-form box-shadow p-2r">
                  <p>Still have questions ? Drop us your enquiry, we'll be sure to attend to you shortly !</p>
                  <?php $this->load->view('frontend/form/contact_us'); ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <table class="table-contact">
                <tr>
                  <td></td>
                  <td class="blue">Elixir Technology Pte. Ltd.</td>
                </tr>
                <?php $CompanyAddress = $this->main->set_('CompanyAddress'); if($CompanyAddress): ?>
                <tr>
                  <td class="text-right blue">Address : </td>
                  <td><?= $CompanyAddress; ?></td>
                </tr>
                <?php endif; ?>
                <?php $EmailGeneral = $this->main->set_('EmailGeneral'); if($EmailGeneral): ?>
                <tr>
                  <td class="text-right blue">Email : </td>
                  <td><a href="mailto:<?= $EmailGeneral; ?>" class="blue"><?= $EmailGeneral; ?></a></td>
                </tr>
                <?php endif; ?>
                <?php $EmailTechnical = $this->main->set_('EmailTechnical'); if($EmailTechnical): ?>
                <tr>
                  <td class="text-right blue <?php if($EmailGeneral): echo 'invisible'; endif;?>">Email : </td>
                  <td><a href="mailto:<?= $EmailTechnical; ?>" class="blue"><?= $EmailTechnical; ?></a></td>
                </tr>
                <?php endif; ?>
                <?php $CompanyTelephone = $this->main->set_('CompanyTelephone'); if($CompanyTelephone): ?>
                <tr>
                  <td class="text-right blue">Contact : </td>
                  <td>Tel : <a href="tel:<?= $CompanyTelephone; ?>" class="blue"><?= $CompanyTelephone; ?></a></td>
                </tr>
                <?php endif; ?>
                <?php $CompanyFax = $this->main->set_('CompanyFax'); if($CompanyFax): ?>
                <tr>
                  <td class="text-right blue <?php if($CompanyTelephone): echo 'invisible'; endif;?>">Contact : </td>
                  <td>Fax : <a href="tel:<?= $CompanyFax; ?>" class="blue"><?= $CompanyFax; ?></a></td>
                </tr>
                <?php endif; ?>
                <?php $CompanyWebsite = $this->main->set_('CompanyWebsite'); if($CompanyWebsite): ?>
                <tr>
                  <td class="text-right blue">Website : </td>
                  <td><a href="<?= $CompanyWebsite; ?>" class="blue"><?= $CompanyWebsite; ?></a></td>
                </tr>
                <?php endif; ?>
                <?php $CompanyBusinessHours = $this->main->set_('CompanyBusinessHours'); if($CompanyBusinessHours): ?>
                <tr>
                  <td class="text-right blue">Business Hours :</td>
                  <td><?= $CompanyBusinessHours; ?></td>
                </tr>
                <?php endif; ?>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section>
      <div class="container-fluid p-zero">
        <div class="map">
          <?php $CompanyLocation = $this->main->set_('CompanyLocation'); if($CompanyLocation): echo $CompanyLocation; endif; ?>
        </div>
      </div>
    </section>