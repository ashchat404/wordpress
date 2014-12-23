<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Foundation</title>
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/stylesheets/app.css" />
    <script src="<?php bloginfo('template_directory'); ?>/bower_components/modernizr/modernizr.js"></script>
  </head>
  <body>
    <div class="row">
      <header>
        <div class="logo large-4 medium-6 small-12 columns">
          <img alt="BMS logo" src="<?php bloginfo('template_directory'); ?>/img/bms_logo.png">
        </div>
        
      </header>

      <div class="large-12 columns">
        <section id="container" class="">
          <div class="row">
              <nav class="large-12 columns">
                <div class="bg">
                  <?php wp_nav_menu( array( 'container_class' => 'main-nav', 'theme_location' => 'primary' ) ); ?>
                </div>
              </nav>
          </div>
