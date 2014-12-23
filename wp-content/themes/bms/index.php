<?php get_header(); ?>
<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>

            <div class="row">

              <div class="large-12 columns">

                
        <?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail(); ?>
        <?php the_content(); ?>
        <?php edit_post_link(); ?>
        <?php wp_link_pages(); ?>

<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>

<?php else : ?>
<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>