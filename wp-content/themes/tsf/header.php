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
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/stylesheets/app.css">

        <script src="<?php echo get_template_directory_uri(); ?>/bower_components/jquery/dist/jquery.min.js"></script>
  

        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/slick.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/modernizr.custom.87302.js"></script>
        <!--[if lt IE 9]>
                <link rel='stylesheet' href='<?php echo get_template_directory_uri(); ?>/stylesheets/ie8.css' type='text/css' media='all' />
                <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
        <![endif]-->
        <script type="text/javascript" src="http://gtcslt-di2.com/js/32306.js"></script>
        <noscript><img src="http://gtcslt-di2.com/32306.png" style="display:none;" /></noscript>
<?php if ( is_user_logged_in() ) { ?>
    <style  type="text/css" media="screen">
        html{margin-top:32px}
        
      @media only screen and (max-width: 64em) {
        html{
            margin-top:46px;
        }
        #wpadminbar{
            position: fixed!important;
        }
        .left-off-canvas-toggle{
            display: block;
        }
      }
    </style>
<?php } ?>
        <?php wp_head(); ?>

    </head>

    <body <?php body_class(); ?>  id="<?php echo sanitize_title_with_dashes(get_the_title()); ?>">
        <?php
        $is_wpjb = '';
        if (function_exists('is_wpjb') && function_exists('is_wpjr')) {
            $is_wpjb = ( is_wpjb() || is_wpjr() ) ? 'wpjb' : '';
        }
        ?>
        <div class="off-canvas-wrap" data-offcanvas>
          <div class="inner-wrap">
            <!-- Off Canvas Menu -->
            <aside class="left-off-canvas-menu mobile">
                    <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("mobile_nav") ) : ?>
                    <?php endif; ?>
            </aside>

            <!-- main content goes here -->
                <header id="tsf_header">
                    <a class="left-off-canvas-toggle" href="#" ><i class="fi-list"></i></a>
                    <div class="cont">
                        <div class="tsf_top">
                                <a target="_blank" href="https://www.facebook.com/Thesalesfloor"><i class="fi-social-facebook"></i></a>
                                <a target="_blank" href="https://twitter.com/thesalesfloor"><i class="fi-social-twitter"></i></a>
                                <a target="_blank" href="https://plus.google.com/111836940371173250719/posts"><i class="fi-social-google-plus"></i></a>
                                <a target="_blank" href="https://www.linkedin.com/company/the-sales-floor-ltd"><i class="fi-social-linkedin"></i></a>
                                <a target="_blank" href="http://thesalesfloor.co.uk/recruiters/" class="btn_orange button">Recruiter</a>

                                <?php if(is_user_logged_in()):?>
                                    <a href="<?php echo wpjr_link_to("logout") ?>" class="btn_green button"><?php _e("Logout", "jobeleon") ?></a>
                                <?php else: ?>
                                <div style="position:relative;display: inline-block;">
                                    <a href="#" class="btn_green sign_in button">
                                        Sign in
                                    </a>
                                    <ul class="sign_dropdown">
                                        <li><a href="<?php echo wpjb_link_to("employer_login") ?>">Employer login</a></li>
                                        <li><a href="<?php esc_attr_e(wpjr_link_to("login")) ?>">Candidate login</a></li>
                                    </ul>
                                </div>
                                <?php endif; ?>
                        </div>
                    </div>

                    <hr>

                    <div class="row desktop">
                        <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
                    </div>
            
                </header>
                <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" class="small-12 columns logo" rel="home"><img src="<?php echo get_theme_mod('wpjobboard_theme_logo'); ?>" alt="<?php bloginfo('name'); ?> logo" class="logo" /></a>
                <h1 class="main-title text-center">A sales specialist job board created by sales people, for sales people</h1>
          <!-- close the off-canvas menu -->
          <a class="exit-off-canvas"></a>



        <script type="text/javascript">

            $("#open_nav").click(function(){
                $(".contt").toggleClass("open");
                return false;
            });
            $(".sign_in").click(function(event){
                event.preventDefault();
                $(".sign_dropdown").animate({
                    overflow:"auto",
                    height:"100px"
                },200);
            })
        </script>
