<!-- Masthead -->
    <header class="banner banner-full text-white bg-cover" style="background: url('../aset/img/bg-office.jpg') top">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
          </div>
        </div>
      </div>
    </header>
   
    <section class="bg-light">
     <div class="container-fluid p-0 aos-init aos-animate" data-aos="fade-down">
        <div class="row list-box-product no-gutters">
        <?php foreach($array_product1 as $key => $a): ?>
         <div class="col-lg-3  aos-animate" data-aos="fade-right">
            <div class="box-product-1">
                <div class="column image">
                    <img src="<?= $a['img'];?>">
                </div>
                <div class="column text">
		            <p class="title-line-1 mt-30"></p>
                    <p><?= $a['title']; ?></p>
                </div>
            </div>
         </div>
         <?php endforeach; ?>   
        </div>
      </div>
    </section>
    <section class="buy-report showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters bg-img-1" style="background-image: linear-gradient(rgba(0, 0, 0, 0.57), rgba(0, 0, 0, 0.62)), url(../aset/img/bg-building.jpg);background-size: cover;background-position: center;height: 500px;" >
         <div class="div-container">
            <h1 class="section-title text-white mb-2r">Smart Office</h1>
            <h2 class="section-title text-white mb-2r">Smart Lighting & Energy System fot Enterprise</h2>
            <h2 class="section-title text-white">More Energy-saving</h2>
            <h2 class="section-title text-white mb-2r">More Efficient</h2>
          </div>
        </div>
      </div>
    </section>
    <section class="buy-report showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters bg-img-1" style="background:#314c5d;min-height: 500px;max-height: 500px" >
         <div class="container div-container color-1 text-left">
            <p class="title mt-80 f-size-28 ">SYSTEM INTRODUCTION</p>
            <p class="title-line-1"></p>
            <p class="f-size-16">Office smart lighting and energy system is designed for joint office, small and medium enterprise. It is an
energy management system that enables the remote control of lights, air conditioners and other devices, and 
monitors the power consumption as well.</p>
            <p class="f-size-16">This office system can make and optimize kinds of energy saving strategies to save the power, shorten 
the electricity cost. With the current protection and electricity usage warning, it provides users the safe,
convenient and energy-saving working places.</p>
          </div>
        </div>
      </div>
    </section>
    <section class="buy-report showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters bg-white">
        	<div class="col-lg-12">
        		<img src="<?= site_url('aset/img/bg-management-office.jpg'); ?>" class="width-100">
        	</div>
        </div>
      </div>
    </section>
    <section class="buy-report showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters bg-white">
        	<div class="col-lg-12">
        		<img src="<?= site_url('aset/img/bg-common-issue.jpg'); ?>" class="width-100">
        	</div>
        </div>
      </div>
    </section>
    <section class="buy-report showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters bg-white">
        	<div class="col-lg-12">
        		<img src="<?= site_url('aset/img/bg-smart-switch.jpg'); ?>" class="width-100 wb-height-500p cover">
        	</div>
        </div>
      </div>
    </section>
    <section class="our-solutions bg-white text-center" style="min-height: 0px;">
      <div class="container mb-4r">
        <div class="section-title" data-aos="fade-down">Smart Switch</div>
        <div class="text-center">
          <div class="border-bottom-blue"></div>
        </div>
      </div>
      <div class="container p-0">
        <div class="row list-box-product no-gutters text-left">
        <?php foreach($array_product2 as $key => $a): ?>
         <div class="col-lg-6  aos-animate" data-aos="fade-right">
            <div class="box-product-2">
                <div class="column width-40 image">
                    <img src="<?= $a['img'] ?>">
                </div>
                <div class="column width-60 text">
                    <p class="mt-30"><?= $a['title']; ?></p>
		            <p class="title-line-full"></p>
                    <p><?= $a['text']; ?></p>
                </div>
            </div>
         </div>
         <?php endforeach; ?>   
        </div>
      </div>
    </section>
    <section class="buy-report showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters bg-white">
        	<div class="col-lg-12">
        		<img src="<?= site_url('aset/img/bg-smart-controller.jpg'); ?>" class="width-100 wb-height-500p cover">
        	</div>
        </div>
      </div>
    </section>
    <section class="our-solutions bg-white text-center" style="min-height: 0px;">
      <div class="container mb-4r">
        <div class="section-title" data-aos="fade-down">Smart Controller</div>
        <div class="text-center">
          <div class="border-bottom-blue"></div>
        </div>
      </div>
      <div class="container p-0 mb-4r">
        <div class="row list-box-product no-gutters text-left">
        <?php foreach($array_product3 as $key => $a): ?>
         <div class="col-lg-6  aos-animate" data-aos="fade-right">
            <div class="box-product-2 ">
                <div class="column width-40 image">
                    <img src="<?= $a['img'] ?>">
                </div>
                <div class="column width-60 text">
                    <p class="mt-30"><?= $a['title']; ?></p>
		            <p class="title-line-full"></p>
                    <p><?= $a['text']; ?></p>
                </div>
            </div>
         </div>
         <?php endforeach; ?>   
        </div>
      </div>
    </section>
    <section class="buy-report showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters bg-white">
        	<div class="col-lg-12">
        		<img src="<?= site_url('aset/img/bg-smart-socket.jpg'); ?>" class="width-100 wb-height-500p cover">
        	</div>
        </div>
      </div>
    </section>
    <section class="our-solutions bg-white text-center" style="min-height: 0px;">
      <div class="container mb-4r">
        <div class="section-title" data-aos="fade-down">Smart Socket</div>
        <div class="text-center">
          <div class="border-bottom-blue"></div>
        </div>
      </div>
      <div class="container p-0">
        <div class="row list-box-product no-gutters text-left">
        <?php foreach($array_product4 as $key => $a): ?>
         <div class="col-lg-6  aos-animate" data-aos="fade-right">
            <div class="box-product-2">
                <div class="column width-40 image">
                    <img src="<?= $a['img'] ?>">
                </div>
                <div class="column width-60 text">
                    <p class="mt-30"><?= $a['title']; ?></p>
		            <p class="title-line-full"></p>
                    <p><?= $a['text']; ?></p>
                </div>
            </div>
         </div>
         <?php endforeach; ?>   
        </div>
      </div>
    </section>
    <section class="buy-report showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters bg-white">
        	<div class="col-lg-12">
        		<img src="<?= site_url('aset/img/bg-smart-sensor.jpg'); ?>" class="width-100 wb-height-500p cover">
        	</div>
        </div>
      </div>
    </section>
    <section class="our-solutions bg-white text-center" style="min-height: 0px;">
      <div class="container mb-4r">
        <div class="section-title" data-aos="fade-down">Smart Sensor</div>
        <div class="text-center">
          <div class="border-bottom-blue"></div>
        </div>
      </div>
      <div class="container p-0 mb-4r">
        <div class="row list-box-product no-gutters text-left">
        <?php foreach($array_product5 as $key => $a): ?>
         <div class="col-lg-6  aos-animate" data-aos="fade-right">
            <div class="box-product-2">
                <div class="column width-40 image">
                    <img src="<?= $a['img'] ?>">
                </div>
                <div class="column width-60 text">
                    <p class="mt-30"><?= $a['title']; ?></p>
		            <p class="title-line-full"></p>
                    <p><?= $a['text']; ?></p>
                </div>
            </div>
         </div>
         <?php endforeach; ?>   
        </div>
      </div>
    </section>
     <section class="buy-report showcase">
      <div class="container-fluid p-0" data-aos="fade-down">
        <div class="row no-gutters bg-white">
        	<div class="col-lg-12">
        		<img src="<?= site_url('aset/img/bg-santai.jpg'); ?>" class="width-100">
        	</div>
        </div>
      </div>
    </section>