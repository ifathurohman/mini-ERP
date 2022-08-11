<!-- Masthead -->
    <header class="banner text-white bg-cover" style="background: url('aset/img/banner-news-and-events.jpg') top">
      <!-- <div class="overlay"></div> -->
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
          </div>
        </div>
      </div>
    </header>
    <div class="banner-content banner-bottom container-fluid page-data" data-category="news" data-limit_post="<?= $this->main->set_("PostLimitNews"); ?>">
      <div class="text-left mb-20">
        <div class="border-bottom-blue"></div>
      </div>
      <div class="title text-white ">
        News & Events
      </div>
      <div class="div-link link-white" data-aos="fade-right">
        <ul>
          <li><a href="<?= site_url(); ?>">Home</a></li>
          <li class="line-up-white"><span class="visible">i</span></li>
          <li><a href="<?= current_url(); ?>">News & Events</a></li>
        </ul>
      </div>
    </div>
    <!-- Icons Grid -->
    <section class="section pb-zero pt-zero text-left">
      <div class="container-fluid ">
        <div class="div-content p-lr-zero">
          <div class="row"> 
            <div class="col-lg-12">
              <div class="div-title">
                <h2 class="title title-small bold text-left pb-3r">Featured Article</h2>              
              </div>
            </div>
            <div class="col-lg-12">
              <ul class="list-post row">
              <?php 
                foreach($this->api->content_list("news_main") as $a):
                  $Link   = $a->ContentID."-".$this->main->link($a->Name);
                  $Link   = site_url("post/detail/".$Link);
                  $Author = $a->Author." - ".$this->main->time_elapsed_string($a->Date);
                  $item = '<div class="col-sm-4">';
                  $item .= '<div class="item-2 bg-white bg-shadow">';
                  $item .= '<div class="image">';
                  $item .= '<img src="'.$a->Image.'">';
                  $item .= '</div>';
                  $item .= '<div class="content">';
                  $item .= '<b class="title" title="'.$a->Name.'">'.$a->Name.'</b>';
                  $item .= '<div class="date" title="'.$Author.'">'.$Author.'</div>';
                  $item .= '<div class="summary">';
                  $item .= '<p class="text">'.$a->Summary.'</p>';
                  $item .= '</div>';
                  $item .= '<div class="link text-right"><a href="'.$Link.'" class="blue" title="'.$a->Name.'" target="_blank">Read More</a></div>';
                  $item .= '</div>';
                  $item .= '</div>';
                  $item .= '</div>';
                  echo $item;
                endforeach;
                ?>
              </ul>
            </div>            
          </div>
        </div>
      </div>
    </section>
    <section class="section  pt-zero text-left">
      <div class="container-fluid ">
        <div class="div-content p-lr-zero">
          <div class="row"> 
            <div class="col-lg-12">
              <div class="div-title">
                <h2 class="title title-small bold text-left pb-3r">You Might Also Want To Read</h2>              
              </div>
            </div>
            <div class="col-lg-12">
             <div class="row list-post" id="list-post">
              </div>
              <div id="page-selection" style="text-align: center;"></div>
            </div>             
          </div>
        </div>
      </div>
    </section>
  <script src="<?= base_url("aset/js/frontend/content.js"); ?>" type="text/javascript"></script>
