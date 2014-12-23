<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package wpjobboard_theme
 * @since wpjobboard_theme 1.0
 */
?>
<div id="side" class="large-3 medium-3 columns" class="" role="complementary">
<!-- 	<div id="adv">

	</div> -->
    <?php do_action('before_sidebar'); ?>
    <?php if (!dynamic_sidebar('sidebar-1')) : ?>

    <?php endif; // end sidebar widget area ?>
</div><!-- #secondary .widget-area -->
<script type="text/javascript">

	$(".expnd").click(function() {
		event.preventDefault();
		var a = $(this).parent();
		console.log(a.attr("class"));
		if(a.attr("class") == "widget widget_wpjb-search"){
			console.log("kd");
		    a.animate({
		        height: (a.height() == 520) ? 35 : 520
		    }, 400);			
		}
		else{
		    a.animate({
		        height: (a.height() == 300) ? 35 : 300
		    }, 400);			
		}

	});

	$("#adv").load("http://testing.thesalesfloor.co.uk/new/wordpress/jobs/advanced-search/ #wpjb-main");
</script>