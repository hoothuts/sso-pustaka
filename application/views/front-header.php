<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<link rel="shortcut icon" href="<?= base_url(); ?>assets/demo/demo2/media/img/logo/favicon.ico" />
<!-- Header -->
<header id="header" class="header">
  <div class="header-top bg-theme-color-2 sm-text-center">
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <div class="widget no-border m-0">
            <ul class="list-inline">
              <li class="m-0 pl-10 pr-10"> <i class="fa fa-phone text-white"></i> <a class="text-white" href="#">(0761)36581</a> </li>
<!--              <li class="text-white m-0 pl-10 pr-10"> <i class="fa fa-clock-o text-white"></i> Senin - Jumat 08:00 to 17:00 </li>-->
              <li class="m-0 pl-10 pr-10"> <i class="fa fa-envelope-o text-white"></i> <a class="text-white" href="mailto:librarypolkesri@pkr.ac.id">Perpustakaan poltekkes kemenkes riau</a> </li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
          <div class="widget no-border m-0">
            <ul class="list-inline pull-right flip sm-pull-none sm-text-center ">
              <?php
              if ($mediasosial) :
                foreach ($mediasosial as $ms) : ?>

                  <li class="wow fadeInLeft" data-wow-duration="1.5s" data-wow-delay=".1s" data-wow-offset="10"><a href="<?php echo $ms->user_mediasosial ?>" data-bg-color="#3B5998" class="text-white"><i class="<?php echo $ms->icon ?>"></i></a></li>


              <?php endforeach;
              endif; ?>
              <li> <span style="color:#ffffff;"> | </span></li>
              <li> <a class="text-white" href="<?= base_url(); ?>home/loginform"><i class="fa fa-sign-in text-white"></i> Sign In</a> </li>
              <?php if ($this->session->has_userdata('member')) { ?>
                <li> <a class="text-white" href="<?= base_url(); ?>home/logoutmember"><i class="fa fa-sign-in text-white"></i> LogOut Member</a> </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="header-nav">
    <div class="header-nav-wrapper navbar-scrolltofixed bg-white">
      <div class="container">
        <nav id="menuzord-right" class="menuzord default" style="width:110%">
          <a class="menuzord-brand pull-left flip" href="<?= base_url(); ?>">
            <?php
            $logoHome = get_logo();
            ?>
            <img src="<?= base_url(); ?>uploads/<?php echo $logoHome->judul ?>" alt="logo pustaka PKR">
          </a>
          <ul class="menuzord-menu">

            <?php
            //var_dump(get_menus());die;
            foreach (get_menus() as $key => $value) : ?>

              <?php if (count($value->children) == 0) : ?>
                <li class="<?= $page_content == $value->nama_menu ? 'current active' : '' ?>">
                  <a href="<?= $value->link ?>" <?= $value->is_new_tab == 1 ? 'target="_blank"' : '' ?>>
                    <?= ucwords(strtolower($value->nama_menu)) ?>
                  </a>
                </li>

              <?php else : ?>
                <li>
                  <a href="#"><?= ucwords(strtolower($value->nama_menu)) ?></a>
                  <ul class="dropdown">
                    <?php foreach ($value->children as $child) : ?>
                      <li>
                        <a href="<?= $child->link ?>" <?= $child->is_new_tab == 1 ? 'target="_blank"' : '' ?>>
                          <?= ucwords(strtolower($child->nama_menu)) ?>
                        </a>
                        <?php
                        if (count($child->child) > 0) :
                        ?>
                          <ul class="dropdown">
                            <?php
                            foreach ($child->child as $value2) :
                            ?>
                              <li>
                                <a href="<?= $value2->link ?>" <?= $value2->is_new_tab == 1 ? 'target="_blank"' : '' ?>>
                                  <?= ucwords(strtolower($value2->nama_menu)) ?>
                                </a>
                              </li>
                            <?php
                            endforeach;
                            ?>
                          </ul>
                        <?php
                        endif;
                        ?>

                      </li>
                    <?php endforeach; ?>
                  </ul>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</header>