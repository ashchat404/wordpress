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
<?php if (!( is_wpjb() || is_wpjr() )) : ?>
    <div class="where-am-i">
        <h2><?php the_title(); ?></h2> 
    </div><!-- .where-am-i -->
<?php endif; ?>

<div id="content" class="site-content" role="main">
    <?php while (have_posts()) : the_post(); ?>

        <?php get_template_part('content', 'page'); ?>

        <?php
        // If comments are open or we have at least one comment, load up the comment template
        if (comments_open() || '0' != get_comments_number())
            comments_template();
        ?>

    <?php endwhile; // end of the loop. ?>

</div><!-- #content -->

<?php get_footer(); ?>
