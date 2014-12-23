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

</div><!-- #main .site-main -->
</div><!-- #primary .primary -->
<?php get_sidebar(); ?>
</div><!-- .table-row -->
<footer id="footer" role="contentinfo">
    <nav class="footer-navigation">
        <?php wp_nav_menu(array('theme_location' => 'footer')); ?>
    </nav>
    <div class="footer-content">
        <p>Powered by <a href="http://wordpress.org/" target="_blank">WordPress</a> and <a href="http://wpjobboard.net/" target="_blank">WPJobBoard</a></p>
    </div>
</footer><!-- #colophon .site-footer -->



</div><!-- .wrapper -->
<?php wp_footer(); ?>
</body>
</html>
