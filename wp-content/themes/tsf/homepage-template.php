<?php
/*
Template Name: My Custom Page
*/
?>
<?php
get_header();
?>
<?php
$all_null = true;

foreach (array("query", "category", "type") as $p) {
    $ls_default[$p] = "";
    if (!isset($param[$p])) {
        $param[$p] = null;
    } else {
        $all_null = false;
    }
}

if (get_option('permalink_structure')) {
    $spoiler = "?";
} else {
    $spoiler = "&";
}

if ($all_null) {
    $spoiler2 = "";
} else {
    $spoiler2 = $spoiler;
}

$search_url = wpjb_link_to("search");

$current_category = null;
$current_type = null;

if ($param["type"] > 0) {
    $current_type = new Wpjb_Model_Tag($param["type"]);
    if (!$current_type->exists() || $current_type->type != "type") {
        $current_type = null;
    }
}

if ($param["category"] > 0) {
    $current_category = new Wpjb_Model_Tag($param["category"]);
    if (!$current_category->exists() || $current_category->type != "category") {
        $current_category = null;
    }
}
?>
<section id="banner">
    <div class="large-6 medium-6 small-6 columns left">
        <div class="panel">
            <h1>Find Your New Sales Job Here</h1>
            <a title="Find a job" class="button" href="<?php echo wpjb_link_to("advsearch")?>" ><?php _e("Find a job", "jobeleon") ?></a>
        </div>
    </div>

    <div class="large-6 medium-6 columns small-6 right">
        <div class="panel">
            <h1>Advertise Your Sales Job Today</h1>
           <a title="Post a job" class="button" href="<?php echo wpjb_link_to("step_add") ?>" ><?php _e("Post a job", "jobeleon") ?></a>
        </div>
    </div>
</section>
<div id="content_wrapper" class="<?php echo sanitize_title_with_dashes(get_the_title($ID)); ?>">

    <section id="srch" class="large-12 columns">
        <h2 class="text-center">Search for Jobs</h2>
        <div class="row">
            <div class="large-12 columns">
                <form action="<?php esc_attr_e($search_url) ?>" method="get">
                    <?php if (!get_option('permalink_structure')): ?>
                        <input type="hidden" name="page_id" value="<?php echo Wpjb_Project::getInstance()->conf("link_jobs") ?>" />
                        <input type="hidden" name="job_board" value="find" />
                    <?php endif; ?>
                    <div class="large-6 medium-6 columns">
                        <input type="text" name="query" class="wpjb-ls-query" placeholder="<?php _e('Keyword', 'jobeleon'); ?>" value="<?php esc_attr_e($param["query"]) ?>" />
                    </div>
                    <div class="large-6 medium-6 columns">
                        <input id="location" placeholder="<?php _e('Location, Postcode', 'jobeleon'); ?>" name="location" type="text">
                    </div>
                    <div style="clear:both"></div>
                    <br>
                    <div class="large-4 medium-4 columns large-centered medium-centered">
                        <div class="range">                            
                        </div>
                        <div class="rad_drop">
                        </div>                 
                    </div>
                    <div style="clear:both"></div>
                    <input type="submit" class="btn" value="<?php _e("Search", "jobeleon") ?>" />
                </form>
            </div><!-- /search -->
        </div>
    </section>

    <section id="featured_themes" class="large-12 columns">
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Hompage_widgets") ) : ?>
    <?php endif; ?>
    </section>

    <section id="testmonials" class="large-12 columns row">
        <h2 class="text-center"><img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/chat-icon.png"> Testmonials</h2>
        <p class="sub-title text-center">Some kind words from our clients…</p>
        <div class="reviews">
            <div>  
                <a href="http://localhost:8888/wordpress/testimonials/">
                    <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/pls.png">
                        <p>“We started working with The Sales Floor team not long after their launch, and found them
                        personal, professional and very different to other job board offerings out there.
                        For a start they actually are a Sales Specialist...</p>
                    <b>- Ronan Carter, Director</b>
                </a>
            </div>
            <div> 
                <a href="http://localhost:8888/wordpress/testimonials/">
                    <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/fr.jpg">
                    <p> “I initially contacted The Sales Floor to help us with an urgent need for a quick hire, I was very impressed with the results. Within hours I had been provided with a substantial list of potential target candidates...</p>
                    <b>- James Harpum, Financial Controller</b>
                </a>
            </div>
            <div>
                <a href="http://localhost:8888/wordpress/testimonials/">
                    <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/esi.gif">
                    <p>
                    “We decided to partner with The Sales Floor for a UK Sales requirement we had early in 

                    2014. We found them straightforward, incredibly helpful, and quick to respond when we 

                    needed them. They combine traditional ...
                    </p>
                    <b>- Mary Johnson, Talent Acquisition Manager (ESI International, part of Informa Group)</b>
                </a>

            </div>
            <div>
                <a href="http://localhost:8888/wordpress/testimonials/">
                    <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/bg.png">
                    <p>“Our relationship with The Sales Floor has so far resulted in us hiring three candidates into call centre and field sales roles. We are also still considering candidates for a couple...</p>
                    <b>- Helen Butterworth, Group Attraction and Diversity.</b>
                </a>
            </div>

        </div>

    </section>

    <section id="discovered" class="large-12 columns">
        <h2 class="text-center"><img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/mag-icon.png"> Discovered by</h2>
        <p class="sub-title text-center">Come and see who else has discovered us…</p>

        <div class="row">
            <div class="large-12 columns">
                <a href="http://localhost:8888/wordpress/testimonials/#more">
                    <div class="large-3 medium-3 small-6 columns">
                    <img class="dis_img" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/harrods_2.png">

                    </div>
                    <div class="large-3 medium-3 small-6 columns">
                    <img class="dis_img" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/manutd.png">

                    </div>
                    <div class="large-3 medium-3 small-6 columns">
                    <img class="dis_img" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/carlsberg.png">

                    </div>
                    <div class="large-3 medium-3 small-6 columns">
                    <img class="dis_img" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/bgas.png">

                    </div>
                </a>
            </div>
        </div>
    </section>

<script type="text/javascript">
$(document).ready(function(){
    $('.reviews').slick({
        dots: true,
        autoplay: true,
        autoplaySpeed: 4000,
        responsive: [
        {
          breakpoint: 768,
          settings: {
            arrows: false,
            centerPadding: '40px',
            slidesToShow: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            arrows: false,
            centerPadding: '40px',
            slidesToShow: 1
          }
        }
      ]
    });
});

if (!Modernizr.inputtypes.range) {
  // no native support for <input type="date"> :(
  $(".range").remove();
  $(".rad_drop").append("<select id='radius' name='radius' class='hasCustomSelect'><option value=''>&nbsp;</option><option value='5 km'>5 km</option><option value='10 km'>10 km</option><option value='25 km'>25 km</option><option value='50 km'>50 km</option><option value='100 km'>100 km</option><option value='200 km'>200 km</option><option value='500 km'>500 km</option></select>");
}else{
    $(".range").append("<label class='large-12 columns'>Within: <span class='range_val'>0 Km</span></label><input type='range' name='radius' id='radius' min='0' value='0' max='160' list='radius_km' oninput='updateTextInput(this.value)' onchange='updateTextInput(this.value)' step='20' /><datalist id='radius_km'><option>0</option><option>20</option><option>40</option><option>60</option><option>80</option><option>100</option></datalist>");
    $(".rad_drop").remove();  
}



function updateTextInput(val) {
  $(".range_val").html(val+" Km"); 
}
</script>



<?php get_footer() ?>

