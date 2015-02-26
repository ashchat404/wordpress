<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package wpjobboard_theme
 */
get_header();
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
<div id="content_wrapper" class="row <?php echo sanitize_title_with_dashes(get_the_title($ID)); ?>">
    <div class="large-9 medium-9 columns" role="main">
        <?php if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb('<p id="breadcrumbs">','</p>');
            } ?>
            <?php while (have_posts()) : the_post(); ?>

            <?php get_template_part('content', 'page'); ?>

            <?php
            // If comments are open or we have at least one comment, load up the comment template
            if (comments_open() || '0' != get_comments_number())
            comments_template();
            ?>

        <?php endwhile; // end of the loop. ?>

    </div>


<?php get_footer(); ?>
