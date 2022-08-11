<a href="#" class="scrollToTop"><span class="fa fa-arrow-up"></span></a>
    <footer class="footer bg-footer ">
      <div class="container-fluid container-small">
        <div class="row no-gutters" >

          <div class="col-lg-6" data-aos="fade-up">
            <h3 class="title">Product Lainnya</h3>
            <ul class="list-link">
            <?php 
            foreach($this->frontend->list_link("another_product") as $a):
              echo '<li><a href="'.$a->link.'" class="link">'.$a->name.'</a></li>';
            endforeach; ?>
            </ul>
          </div>
          <div class="col-lg-6" data-aos="fade-up">
            <h3 class="title">Contact</h3>
            <table class="list-link list-link-icon front_contact">
              
            </table>
          </div>
        </div>
        <div class="row footer-bottom" data-aos="fade-up">
          <div class="col-lg-12 h-100 text-center my-auto">
            <ul class="list-inline mb-20">
              <li class="list-inline-item">
                <a href="<?= site_url('privacy-policy.html'); ?>" target="_blank">Privacy Policy</a>
              </li>
              <li class="list-inline-item">|</li>
              <li class="list-inline-item">
                <a href="<?= site_url('refund-and-shipping-policy.html'); ?>" target="_blank">Refund Policy</a>
              </li>
             <!--  <li class="list-inline-item">|</li>
              <li class="list-inline-item">
                <a href="<?= site_url('terms-of-service'); ?>" target="_blank">Terms Of Service</a>
              </li> -->
            </ul>
            <p class="text-muted small mb-4 mb-lg-0"><a href="https://rcelectronic.co.id" target="_blank">Copyright &copy; since 2019 - RC Electronic, Cv</a></p>
          </div>
        </div>
      </div>
    </footer>