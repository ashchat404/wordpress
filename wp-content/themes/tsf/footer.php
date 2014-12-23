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
<footer id="foot" >
	<div class="row" id="footer_wrapper">
		<div class="large-12">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer_alert_widget") ) : ?>
				<?php endif; ?>
		</div>
	</div>
</footer><!-- #colophon .site-footer -->



<?php wp_footer(); ?>
</body>
</html>
