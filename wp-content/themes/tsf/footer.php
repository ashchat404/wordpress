<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package wpjobboard_theme
 * @since wpjobboard_theme 1.0
 */
?>

<?php get_sidebar(); ?>
</div>
  </div>
</div>
<footer id="foot" >
	<div class="row" id="footer_wrapper">
		<div class="large-12">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer_alert_widget") ) : ?>
				<?php endif; ?>
		</div>
	</div>
</footer><!-- #colophon .site-footer -->

<script src="<?php echo get_template_directory_uri(); ?>/bower_components/foundation/js/foundation.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/foundation.offcanvas.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/foundation.reveal.js"></script>

<script>
jQuery.noConflict();
jQuery(document).foundation();
</script> 
     
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-21451860-69', 'auto');
  ga('send', 'pageview');

</script>
<?php wp_footer(); ?>
</body>
</html>
