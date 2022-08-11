<div class="swiper-container swiper-horizontal <?= $device; ?>">
   <div class="swiper-wrapper">
      <div class="swiper-slide">
         <div class="slideshow">
            <?php 
               $slideshow = $this->api->slideshow("list_data");
               ?>
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <!-- <?php 
                foreach($slideshow as $key => $a): 
                  $active = "";
                  if($key == 0):
                    $active = "active";
                  endif;
                  echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$key.'" class="'.$active.'"></li>';
                endforeach;
                ?> -->
              </ol>
              <div class="carousel-inner" role="listbox">
                <?php 
                foreach($slideshow as $key => $a): 
                  $active = "";
                  if($key == 0):
                    $active = "active";
                  endif;
                  $list_btn = $a->ButtonLink;
                  $list_btn = json_decode($list_btn);

                ?>
                <div class="carousel-item <?= $active; ?>">
                  <img src="<?= $a->Patch; ?>" class="banner-1" >
                  <div class="carousel-caption custom1 d-md-block <?= $a->Position; ?>">
                    <h3 style="color:<?= $a->NameColor." !important";?>"><?= $a->Name; ?></h3>
                    <p style="color:<?= $a->NameColor." !important";?>">
                      <?= $a->Description; ?>
                    </p>
                    <div class="div-btn">
                      <ul>
                      <?php 
                      if(count($list_btn) > 0):
                        foreach ($list_btn as $b):
                          if($b->BtnName):
                            echo '<li><a href="'.$b->BtnLink.'" class="btn btn-'.$b->BtnColor.' w-260 mb-20"><span class="label-btn">'.$b->BtnName.'</span> <span class="icon-btn icon-btn-right"><i class="fa fa-arrow-right"></i></span></a></li>';
                          endif;
                        endforeach;
                      endif;
                      ?>
                      </ul>                
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
               <span class="carousel-control-prev-icon" aria-hidden="true"></span>
               <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
               <span class="carousel-control-next-icon" aria-hidden="true"></span>
               <span class="sr-only">Next</span>
              </a>
            </div>
         </div>
      </div>
      <?php if($device == "web"): ?>
      <div class="swiper-slide">
         <div class="section-normal bg-light text-center wb-min-height-100vh" style="min-height: 0px;">
            <div class="container container-gallery">
               <div class="section-title"><?= $this->lang->line('featuresx'); ?></div>
               <div class="text-center">
                  <div class="border-bottom-blue"></div>
               </div>
               <div class="row justify-content-center mt-3r gallery-img list-experience list-fitur ">
                  <?php foreach($this->frontend->list_link("fitur_one") as $a): ?>
                  <div class="col-sm-4  pointer  padding-20">
                     <div class="item"> 
                        <img alt="" src="<?= $a->img; ?>" class="img">
                     </div>
                     <b style="font-size: 20px;"><?= $a->title; ?></b>
                     <p style="font-size: 17px;"><?= $a->text; ?></p>
                  </div>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </div>
      <div class="swiper-slide">
         <div class="section-normal bg-light text-center wb-min-height-100vh" style="min-height: 0px;">
            <div class="container container-gallery">
               <div class="section-title"><?= $this->lang->line('featuresx'); ?></div>
               <div class="text-center">
                  <div class="border-bottom-blue"></div>
               </div>
               <div class="row justify-content-center mt-3r gallery-img list-experience list-fitur ">
                  <?php foreach($this->frontend->list_link("fitur_two") as $a): ?>
                  <div class="col-sm-4  pointer  padding-20">
                     <div class="item"> 
                        <img alt="" src="<?= $a->img; ?>" class="img">
                     </div>
                     <b style="font-size: 20px;"><?= $a->title; ?></b>
                     <p style="font-size: 17px;"><?= $a->text; ?></p>
                  </div>
                  <?php endforeach; ?>
               </div>
            </div>
         </div>
      </div>
      <div class="swiper-slide">
         <div class="bg-harga section-normal bg-light text-center wb-min-height-100vh">
            <div class="container-fluid container-small">
               <div class="section-title" ><?= $this->lang->line('price'); ?></div>
               <div class="text-center">
                  <div class="border-bottom-blue"></div>
               </div>
               <div class="row justify-content-center mt-4r list-product list-product-data list-package">
                  <?php foreach($this->frontend->list_link("harga") as $key => $a): ?>
                  <div class="col-sm-3">
                     <div class="item item-price pointer" onclick="module_modal();">
                        <div class="header bg-<?= ($key + 1);?>">
                           <p class="title"><?= $a->name; ?></p>
                        </div>
                        <div class="body">
                           <!-- <p class="name"></p> -->
                           <div class="price">
                              <span class=""><?= $a->harga; ?></span>
                              <span class="text-price"> <?= $a->text; ?></span>
                           </div>
                        </div>
                        <!-- <div class="footer">
                           <ul>
                             <?php foreach($a->footer as $b): ?>
                               <li><?= $b; ?></li>
                             <?php endforeach; ?>
                           </ul>
                           </div> -->
                     </div>
                  </div>
                  <?php endforeach; ?>
                  <div class="col-sm-12">
                     <div class="text-center mb-1r">
                        <a href="javascript:;" onclick="module_modal();" class="btn btn-custom btn-radius"><?= $this->lang->line('module_detail'); ?></a>
                     </div>
                     <div class="text-center">
                        <a href="<?= site_url('buy-voucher-app') ?>" class="btn btn-custom btn-radius"><?= $this->lang->line('buy_voucher'); ?></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php else: ?>
      <?php foreach($this->frontend->list_link("fitur") as $a): ?>
      <div class="swiper-slide">
         <div class="section-normal bg-light text-center wb-min-height-100vh" style="min-height: 0px;">
            <div class="container container-gallery">
               <div class="section-title"><?= $this->lang->line('featuresx'); ?></div>
               <div class="text-center">
                  <div class="border-bottom-blue"></div>
               </div>
               <div class="row justify-content-center mt-3r gallery-img list-experience list-fitur ">
                  <div class="col-sm-4  pointer  padding-20">
                     <div class="item"> 
                        <img alt="" src="<?= $a->img; ?>" class="img">
                     </div>
                     <b style="font-size: 20px;"><?= $a->title; ?></b>
                     <p style="font-size: 17px;"><?= $a->text; ?></p>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php endforeach; ?>
      <?php foreach($this->frontend->list_link("harga") as $key => $a): ?>
      <div class="swiper-slide">
         <div class="bg-harga section-normal bg-light text-center wb-min-height-100vh">
            <div class="container-fluid container-small">
               <div class="section-title" ><?= $this->lang->line('price'); ?></div>
               <div class="text-center">
                  <div class="border-bottom-blue"></div>
               </div>
               <div class="row justify-content-center mt-4r list-product list-product-data list-package">
                  <div class="col-sm-6">
                     <div class="item item-price pointer" onclick="module_modal();">
                        <div class="header bg-<?= ($key + 1);?>">
                           <p class="title"><?= $a->name; ?></p>
                        </div>
                        <div class="body">
                           <!-- <p class="name"></p> -->
                           <div class="price">
                              <span class=""><?= $a->harga; ?></span>
                              <span class="text-price"> <?= $a->text; ?></span>
                           </div>
                        </div>
                     </div>
                     <div class="text-center mb-1r">
                        <a href="javascript:;" onclick="module_modal();" class="btn btn-custom btn-radius"><?= $this->lang->line('module_detail'); ?></a>
                     </div>
                     <div class="text-center">
                        <a href="<?= site_url('buy-voucher-app') ?>" class="btn btn-custom btn-radius"><?= $this->lang->line('buy_voucher'); ?></a>                    
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <div class="div-table-module">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
      <div class="swiper-slide">
         <div class="section-normal bg-light text-center" style="min-height: 0px;padding-top: 30vh">
            <div class="container-fluid">
               <div class="section-title" ><?= $this->lang->line('try_free_now'); ?></div>
               <div class="text-center">
                  <div class="border-bottom-blue"></div>
               </div>
               <div class="mt-3r ">
                  <a href="#" class="btn btn-custom btn-radius"><?= $this->lang->line('try_free'); ?></a>
               </div>
            </div>
         </div>
      </div>
      <div class="swiper-slide">
         <div class="footer bg-footer section-normal bg-light text-center" style="min-height: 0px;padding-top: 8rem;">
            <div class="container container-small">
               <div class="row no-gutters">
                  <div class="col-lg-6 text-left">
                     <h3 class="title"><?= $this->lang->line('another_product'); ?></h3>
                     <ul class="list-link">
                        <?php 
                           foreach($this->frontend->list_link("another_product") as $a):
                             echo '<li><a href="'.$a->link.'" class="link">'.$a->name.'</a></li>';
                           endforeach; ?>
                     </ul>
                  </div>
                  <div class="col-lg-6 text-left">
                     <h3 class="title"><?= $this->lang->line('contact'); ?></h3>
                     <table class="list-link list-link-icon front_contact">
                     </table>
                  </div>
               </div>
               <div class="row mt-3r footer-bottom">
                  <div class="col-lg-12 h-100 text-center my-auto">
                     <ul class="list-inline">
                        <li class="item dropdown">
                           <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)" data-animation="scale-up" aria-expanded="false" role="button">
                           <?php 
                              if($this->session->bahasa == "indonesia"): 
                                echo '<span class="flag-icon flag-icon-id mr-10"></span>Bahasa Indonesia';
                              else:
                                echo '<span class="flag-icon flag-icon-us mr-10"></span>English';
                              endif;
                              ?>
                           </a>
                           <ul class="list-bahasa dropdown-menu" role="menu">
                              <?php foreach($this->frontend->list_link('bahasa') as $a): ?>
                              <li role="presentation">
                                 <a href="<?= $a->link;  ?>" role="menuitem">
                                 <span class="<?= $a->icon; ?>"></span> <?= $a->name; ?></a>
                              </li>
                              <?php endforeach; ?>
                           </ul>
                        </li>
                     </ul>
                     <ul class="list-inline mb-20">
                        <li class="list-inline-item">
                           <a href="<?= site_url('privacy-policy.html'); ?>" target="_blank">Privacy Policy</a>
                        </li>
                        <li class="list-inline-item">|</li>
                        <li class="list-inline-item">
                           <a href="<?= site_url('terms-of-service'); ?>" target="_blank">Terms Of Service</a>
                        </li>
                     </ul>
                     <p class="text-muted small mb-4 mb-lg-0"><a href="https://rcelectronic.co.id" target="_blank">Copyright &copy; since 2019 - RC Electronic, Cv</a></p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--       <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div> -->
   <div class="swiper-pagination"></div>
</div>
<div id="modal-module" class="modal modal-module modal-primary fade modal-fade-in-scale-up modal-70" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
   <div class="modal-dialog" style="border-radius: .3rem;">
      <div class="modal-content" style="border:none;">
         <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
               </button> -->
            <h4 class="modal-title"><?= $this->lang->line('module_detail'); ?></h4>
         </div>
         <div class="modal-body p-1r">
            <table class="table table-bordered table-module text-left" style="margin-top: 0px !important;margin-bottom: 0px !important">
               <tbody>
                  <tr class="head">
                     <td><?= $this->lang->line("module"); ?></td>
                     <td>AR</td>
                     <td>AP</td>
                     <td>WH</td>
                     <td>ACC</td>
                  <tr>
                  <tr>
                     <td>Business Partner</td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                  </tr>
                  <tr>
                     <td>Item Product</td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                  </tr>
                  <tr>
                     <td>Sales & Employee</td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                  </tr>
                  <tr>
                     <td>Chart of Account</td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                  </tr>
                  <tr>
                     <td>Sales Order</td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Item Delivery</td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Sales Return</td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Receivable Invoice</td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Receivable Correction</td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Receivable Payment</td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Sales Payment</td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Purchase Order</td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Good Receipt</td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Purchase Return</td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Payable Invoice</td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Payable Correction</td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Payable Payment</td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Stock Correction</td>
                     <td></td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Stock Opname</td>
                     <td></td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Stock Mutation</td>
                     <td></td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Cash / Bank</td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                  </tr>
                  <tr>
                     <td>Journal Manual</td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td><i class="fas fa-check"></i></td>
                  </tr>
                  <tr>
                     <td>Report</td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                     <td><i class="fas fa-check"></i></td>
                  </tr>
               </tbody>
            </table>
            <div class="note-div">
               <p>Note : </p>
               <?php 
                  $modul_txt  = $this->lang->line('module');
                  $max_txt    = $this->lang->line('max');
                  $person_txt = $this->lang->line('person');
                  $month_txt  = $this->lang->line('month');
                  $year_txt   = $this->lang->line('year');
                  $user_txt   = $this->lang->line('user');
                  $footer     = array('1 '.$modul_txt.' : '.$max_txt.' 1 '.$person_txt,'2 '.$modul_txt.' : '.$max_txt.' 3 '.$person_txt,'3 '.$modul_txt.' : '.$max_txt.' 5 '.$person_txt, ' 4 '.$modul_txt.' : '.$max_txt.' 7 '.$person_txt);
                  ?>
               <ul>
                  <?php foreach($footer as $b): ?>
                  <li><?= $b; ?></li>
                  <?php endforeach; ?>
               </ul>
            </div>
         </div>
         <div class="modal-footer">
            <div class="btn-group">
               <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
</div>