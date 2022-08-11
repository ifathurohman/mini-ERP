    <?php if($data->Image && $data->ImageStatus == 1): $IMG = "url('".$data->Image."')"; ?> 
    <header class="banner banner-full text-white bg-cover" style="background: <?= $IMG; ?> top">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
          </div>
        </div>
      </div>
    </header>
    <?php endif; ?>
    <section class="section  pt-zero text-left bg-light aos-init product-detail" data-aos="fade-down">
      <?php if($data->Template == "custom"): ?>
      <div class="container-fluid p-0">
        <?= $data->Description; ?>
      </div>
      <?php else: ?>
      <div class="container-fluid container-small">
        <div class="ul-link">
          <ul class="ul-link-inline">
            <li><a href="<?= site_url(); ?>">Home</a></li> 
            <li><a href="<?= site_url('product/list/'.$data->CategoryLink); ?>"><?= $data->CategoryName; ?></a></li> 
            <li><?= $data->Name; ?></li> 
          </ul>
        </div>
        <div class="slider-product row">
          <div class="col-sm-6">
            <div id='carousel-custom' class='carousel slide' data-ride='carousel'>
              <div class='carousel-outer'>
                  <div class='carousel-inner'>
                  <?php foreach($data_image as $key=>$a): $active = ""; if($key == 0): $active = "active"; endif; ?>
                      <div class='item <?= $active; ?>'>
                          <img src='<?= base_url($a->Image); ?>' alt=''id="zoom_05"  data-zoom-image="<?= base_url($a->Image); ?>"/>
                      </div>
                   <?php endforeach; ?>             
                  </div>  
                  <a class='left carousel-control' href='#carousel-custom' data-slide='prev'>
                      <span class='glyphicon glyphicon-chevron-left fa fa-chevron-left'></span>
                  </a>
                  <a class='right carousel-control' href='#carousel-custom' data-slide='next'>
                      <span class='glyphicon glyphicon-chevron-right fa fa-chevron-right'></span>
                  </a>
              </div>
              <ol class='carousel-indicators mCustomScrollbar meartlab'>
                  <?php foreach($data_image as $key=>$a): $active = ""; if($key == 0): $active = "active"; endif; ?>
                    <li data-target='#carousel-custom' data-slide-to='<?= $key; ?>' class='<?= $active; ?>'>
                      <img src='<?= base_url($a->Image); ?>' alt='' />
                    </li>
                  <?php endforeach; ?>
              </ol>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="detail-info padding-20">
              <h3 class="weight-normal mb-1r"><?= $data->Name; ?></h3>
              <p class="">
                <a href="<?= site_url('product/list/'.$data->CategoryLink); ?>" class="label-category">
                <i class="<?= $data->CategoryIcon; ?> pr-10"></i> <?= $data->CategoryName; ?>
              </a>
              </p>
              <p><?= $data->Summary; ?></p>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="row">
            <div class="col-lg-12">
              <div class="div-title">
                <?php if($data->Type == "content"):?>
                <div class="content-header pb-3r">
                  <h2 class="title title-small bold text-left weight-normal" data-aos="fade-down"><?= $data->Name; ?></h2>
                  <span class="author"><?= $Author = $data->UserName." - ".date("D, d M Y H:i",strtotime($data->Date)); ?></span>                
                </div>
                <?php endif; ?>
                
                <ul class="nav nav-tabs nav-tabs-custom">
                    <li class="nav-item active">
                      <a href="#description" data-toggle="tab" aria-expanded="false" class="nav-link">
                        <i class="fa fa-folder-open pr-10"></i>  Description
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#summary" data-toggle="tab" aria-expanded="false" class="nav-link">
                          Summary
                      </a>
                    </li>
                </ul>
                <div class="tab-content nav-tabs-custom padding-20">
                  <div class="tab-pane active" id="description">
                    <div class="content-body <?= $data->Template; ?>">
                      <?= $data->Description; ?>
                    </div>
                  </div>
                  <div class="tab-pane" id="summary">
                    <div class="content-body <?= $data->Template; ?>">
                      <?= $data->Summary; ?>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </section>
<script src="<?= base_url('aset/js/bootstrap.3.min.js'); ?>"></script>
<script src="<?= base_url('aset/js/product.carausel.js'); ?>"></script>