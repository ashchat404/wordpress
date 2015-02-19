<?php
/*
Template Name: Contact Us template
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
<div id="common_banner" class="row">
    <div class="search_wrapper large-6 medium-6 medium-centered large-centered columns">
        <div class="">
            <form action="<?php esc_attr_e($search_url) ?>" method="get">
                <?php if (!get_option('permalink_structure')): ?>
                    <input type="hidden" name="page_id" value="<?php echo Wpjb_Project::getInstance()->conf("link_jobs") ?>" />
                    <input type="hidden" name="job_board" value="find" />
                <?php endif; ?>
                <div class="large-6 columns">
                    <input type="text" name="query" class="wpjb-ls-query" placeholder="<?php _e('Keyword, location, company', 'jobeleon'); ?>" value="<?php esc_attr_e($param["query"]) ?>" />
                </div>
                <div class="large-6 columns">
                    <select id="category" name="category" class="">
                        <option value=""><?php _e('All categories', 'jobeleon'); ?></option>
                        <?php
                        $job_categories = wpjb_form_get_categories();
                        foreach ($job_categories as $cat) :
                            ?>

                            <option value="<?php echo $cat['value']; ?>" <?php selected($cat['value'], $param["category"]); ?>><?php echo $cat['description']; ?></option>  

                        <?php endforeach; ?>    
                    </select>
                </div>
                <div style="clear:both"></div>
                <input type="submit" class="btn" value="<?php _e("Search", "jobeleon") ?>" />
            </form>
        </div><!-- /search -->        
    </div>

</div>
<div id="content_wrapper" class="<?php echo sanitize_title_with_dashes(get_the_title($ID)); ?>">
 <h1 class="text-center">Contact Us</h1>
 <h2 class="text-center">Get in touch with us to get the ball rolling</h2>

 <section class="row">
    <div class="large-12 columns list-icon-items" id="contactus">
        <div class="large-3 medium-3 small-6 columns"><a href="mailto:info@thesalesfloor.co.uk?subject=Query"><i class="circ fi-mail"><i class="circ pulse"></i></i><p>Mail</p><i>info@thesalesfloor.co.uk</i></a></div>
        <div class="large-3 medium-3 small-6 columns"><a href="tel:0203 714 1155"><i class="circ fi-telephone"><i class="circ pulse"></i></i><p>Phone</p><i>0203 714 1155</i></a></div>
        <div class="large-3 medium-3 small-6 columns"><a href="skype:thesalesfloor?call"><i class="circ fi-social-skype"><i class="circ pulse"></i></i><p>Skype</p><i>Call us</i></a></div>
        <div class="large-3 medium-3 small-6 columns"><a href="https://twitter.com/thesalesfloor"><i class="circ fi-social-twitter"><i class="circ pulse"></i></i><p>Twitter</p><i>Follow us</i></a></div>
    </div>
    <div class="clear"><br></div>
    <div class="large-6 large-centered medium-centered small-centered medium-6 small-6 columns">
        <p class="text-center">The Old Studio, 26-32 Voltaire Road, London, SW4 6DH</p>
        <img src="http://maps.google.com/maps/api/staticmap?center=51.4657437,-0.1333171&zoom=16&size=600x400&maptype=roadmap&sensor=false&language=&markers=color:red|label:none|51.4657437,-0.1333171" title="sw46dh" width="600" height="400" style="border:1px solid #CECECE;">
    </div>
    <div style="width:90px;margin:0 auto;">
        <a target="_blank" href="https://www.facebook.com/Thesalesfloor"><i class="fi-social-facebook i"></i></a>
        <a target="_blank" href="https://plus.google.com/111836940371173250719/posts"><i class="fi-social-google-plus i"></i></a>
        <a target="_blank" href="https://www.linkedin.com/company/the-sales-floor-ltd"><i class="fi-social-linkedin i"></i></a>
    </div>
 </section>

<?php get_footer() ?>