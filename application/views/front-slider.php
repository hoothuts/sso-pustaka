<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

 <!-- Slider Revolution Start -->
      <div class="rev_slider_wrapper">
        <div class="rev_slider" data-version="5.0">
          <ul>
			<?php if($slide):?>
				<?php 
				$count=0;
				foreach($slide as $sl): 
				$count+=1;
				$media=$this->Md_media->getMediaById($sl->media_id);
				?>
					<li data-index="rs-<?php echo $count;?>" data-transition="slidingoverlayhorizontal" data-slotamount="default" data-easein="default" data-easeout="default" data-masterspeed="default" data-rotate="0" data-saveperformance="off" data-title="Slide <?php echo $count;?>" data-description="">
				  <!-- MAIN IMAGE -->
				  <img src="<?php echo base_url();?>uploads/<?php echo $media[0]['judul'];?>"  alt=""  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-bgparallax="10" data-no-retina>
				  <!-- LAYERS -->

				  <!-- LAYER NR. 1 -->
                                  <?php 
                                  if($sl->judul!=''){?>
                                  <div class="tp-caption tp-resizeme text-uppercase text-white font-raleway"
					id="rs-1-layer-1"

					data-x="['left']"
					data-hoffset="['30']"
					data-y="['middle']"
					data-voffset="['-110']" 
					data-fontsize="['100']"
					data-lineheight="['110']"
					data-width="none"
					data-height="none"
					data-whitespace="nowrap"
					data-transform_idle="o:1;s:500"
					data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
					data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
					data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
					data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
					data-start="1000" 
					data-splitin="none" 
					data-splitout="none" 
					data-responsive_offset="on"
					style="z-index: 7; white-space: nowrap; font-weight:700;"><?php echo $sl->judul;?>
				  </div>    
                                  <?php
                                  }
                                  ?>
				  

				  <!-- LAYER NR. 2 -->
                                  <?php 
                                  if($sl->keterangan!='<br>'){?>
                                  <div class="tp-caption tp-resizeme text-uppercase text-white font-raleway bg-theme-colored-transparent border-left-theme-color-2-6px pl-20 pr-20"
					id="rs-1-layer-2"

					data-x="['left']"
					data-hoffset="['35']"
					data-y="['middle']"
					data-voffset="['-25']" 
					data-fontsize="['35']"
					data-lineheight="['54']"
					data-width="none"
					data-height="none"
					data-whitespace="nowrap"
					data-transform_idle="o:1;s:500"
					data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
					data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
					data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
					data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
					data-start="1000" 
					data-splitin="none" 
					data-splitout="none" 
					data-responsive_offset="on"
					style="z-index: 7; white-space: nowrap; font-weight:600;"><?php echo $sl->keterangan;?>
				  </div>    
                                  <?php
                                  }
                                  ?>
				</li>
				<?php endforeach;?>
				<script>
        $(document).ready(function(e) {
          $(".rev_slider").revolution({
            sliderType:"standard",
            sliderLayout: "auto",
            dottedOverlay: "none",
            delay: 5000,
            navigation: {
                keyboardNavigation: "off",
                keyboard_direction: "horizontal",
                mouseScrollNavigation: "off",
                onHoverStop: "off",
                touch: {
                    touchenabled: "on",
                    swipe_threshold: 75,
                    swipe_min_touches: 1,
                    swipe_direction: "horizontal",
                    drag_block_vertical: false
                },
              arrows: {
                style:"zeus",
                enable:true,
                hide_onmobile:true,
                hide_under:600,
                hide_onleave:true,
                hide_delay:200,
                hide_delay_mobile:1200,
                tmp:'<div class="tp-title-wrap">    <div class="tp-arr-imgholder"></div> </div>',
                left: {
                  h_align:"left",
                  v_align:"center",
                  h_offset:30,
                  v_offset:0
                },
                right: {
                  h_align:"right",
                  v_align:"center",
                  h_offset:30,
                  v_offset:0
                }
              },
              bullets: {
                enable:false,
                hide_onmobile:true,
                hide_under:600,
                style:"metis",
                hide_onleave:true,
                hide_delay:200,
                hide_delay_mobile:1200,
                direction:"horizontal",
                h_align:"center",
                v_align:"bottom",
                h_offset:0,
                v_offset:30,
                space:5,
                tmp:'<span class="tp-bullet-img-wrap">  <span class="tp-bullet-image"></span></span><span class="tp-bullet-title">{{title}}</span>'
              }
            },
            responsiveLevels: [1240, 1024, 778],
            visibilityLevels: [1240, 1024, 778],
            gridwidth: [1170, 1024, 778, 480],
            gridheight: [650, 768, 960, 720],
            lazyType: "none",
            parallax: {
                origo: "slidercenter",
                speed: 1000,
                levels: [5, 10, 15, 20, 25, 30, 35, 40, 45, 46, 47, 48, 49, 50, 100, 55],
                type: "scroll"
            },
            shadow: 0,
            spinner: "off",
            stopLoop: "on",
            stopAfterLoops: 0,
            stopAtSlide: -1,
            shuffle: "off",
            autoHeight: "off",
            fullScreenAutoWidth: "off",
            fullScreenAlignForce: "off",
            fullScreenOffsetContainer: "",
            fullScreenOffset: "0",
            hideThumbsOnMobile: "off",
            hideSliderAtLimit: 0,
            hideCaptionAtLimit: 0,
            hideAllCaptionAtLilmit: 0,
            debugMode: false,
            fallbacks: {
                simplifyAll: "off",
                nextSlideOnWindowFocus: "off",
                disableFocusListener: false,
            }
          });
        });
      </script>
			<?php endif;?>
          </ul>
        </div>
        <!-- end .rev_slider -->
      </div>
      <!-- end .rev_slider_wrapper -->
      
      <!-- Slider Revolution Ends -->
