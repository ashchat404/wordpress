<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package wpjobboard_theme
 * @since wpjobboard_theme 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width" />
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/slick.js"></script>
        <!--[if lt IE 9]>
                <link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/stylesheets/ie8.css' type='text/css' media='all' />
                <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
        <![endif]-->

        <?php wp_head(); ?>

    </head>

    <body <?php body_class(); ?>  id="<?php echo sanitize_title_with_dashes(get_the_title()); ?>">
        <?php
        $is_wpjb = '';
        if (function_exists('is_wpjb') && function_exists('is_wpjr')) {
            $is_wpjb = ( is_wpjb() || is_wpjr() ) ? 'wpjb' : '';
        }
        ?>
        <header id="tsf_header">
            <div class="cont">
                <div class="tsf_top">
                        <a href="https://www.facebook.com/Thesalesfloor"><i class="fi-social-facebook"></i></a>
                        <a href="https://twitter.com/thesalesfloor"><i class="fi-social-twitter"></i></a>
                        <a href="https://plus.google.com/111836940371173250719/posts"><i class="fi-social-google-plus"></i></a>
                        <a href="https://www.linkedin.com/company/the-sales-floor-ltd"><i class="fi-social-linkedin"></i></a>
                        <a href="http://testing.thesalesfloor.co.uk/new/wordpress/recruiters/" class="btn_orange button">Recruiter</a>
                        <a href="<?php esc_attr_e(wpjr_link_to("login")) ?>" class="btn_green button">Sign in</a>
                </div>
            </div>

            <hr>

            <div class="row">
                <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
            </div>
    
        </header>
