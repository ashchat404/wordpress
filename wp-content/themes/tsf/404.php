<?php
/*
404 page
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
        <h2 class="text-center">Oops...Page not found. Try our Home Page.</h2>
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

