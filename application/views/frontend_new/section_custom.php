<?php if($section == "product_pricing"): ?>
<section class="showcase bg-banner">
  <div class="container-fluid p-0">
    <div class="row no-gutters">
      <div class="col-lg-8" data-aos="fade-right">
        <div class="div-content">
          <h2 class="section-title">The Best Solution for Your Business Projects</h2>
          <p class="text-section">Streamline Your Business Process With Us Now !</p>
        </div>
      </div>
      <div class="col-lg-4" data-aos="fade-down">
        <div class="div-content">
          <div class="div-btn p-0 ">
            <a href="<?= site_url('product-and-pricing'); ?>" class="btn btn-black pull-right">PRODUCTS AND PRICING</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php elseif($section == "contact_us"): ?>
<section class="showcase bg-banner">
  <div class="container-fluid p-0">
    <div class="row no-gutters">
      <div class="col-lg-8" data-aos="fade-right">
        <div class="div-content">
          <h2 class="section-title">The Best Solution for Your Business Projects</h2>
          <p class="text-section">Streamline Your Business Process With Us Now !</p>
        </div>
      </div>
      <div class="col-lg-4" data-aos="fade-down">
        <div class="div-content">
          <div class="div-btn p-0 ">
            <a href="<?= site_url('contact-us'); ?>" class="btn btn-black pull-right">CONTACT US</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>