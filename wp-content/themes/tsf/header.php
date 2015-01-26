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
        <!-- For IE 9 and below. ICO should be 32x32 pixels in size -->
        <!--[if IE]><link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico"><![endif]-->

        <!-- Touch Icons - iOS and Android 2.1+ 180x180 pixels in size. --> 
        <link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-precomposed.png">

        <!-- Firefox, Chrome, Safari, IE 11+ and Opera. 196x196 pixels in size. -->
        <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/slick.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/modernizr.custom.87302.js"></script>
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

                        <?php if(is_user_logged_in()):?>
                            <a href="<?php echo wpjr_link_to("logout") ?>" class="btn_green button"><?php _e("Logout", "jobeleon") ?></a>
                        <?php else: ?>
                            <a href="<?php esc_attr_e(wpjr_link_to("login")) ?>" class="btn_green button">Sign in</a>
                        <?php endif; ?>
                </div>
            </div>

            <hr>

            <div class="row desktop">
                <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
            </div>
            <div class="row mobile">
                <a href="#" class="small-2 columns open_main">
                    <i class="fi-list-thumbnails"></i>
                </a>
                <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" class="small-10 columns" rel="home"><img src="<?php echo get_theme_mod('wpjobboard_theme_logo'); ?>" alt="<?php bloginfo('name'); ?> logo" class="logo" /></a>
                <div class="contt">
                    <h2>Main menu</h2>
                    <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("mobile_nav") ) : ?>
                    <?php endif; ?>
                </div>

            </div>
    
        </header>
        <script type="text/javascript">
            var oddClick = true;
            $(".open_main").click(function() {
                $(".mobile .contt").animate({
                    left: oddClick ? 0 : "-50%"
                },500);
                oddClick = !oddClick;
            });
        </script>
