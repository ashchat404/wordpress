<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package wpjobboard_theme
 * @since wpjobboard_theme 1.0
 */
?>
<div id="side" class="large-3 medium-3 columns" class="" role="complementary">
    <?php do_action('before_sidebar'); ?>
    <?php if (!dynamic_sidebar('sidebar-1')) : ?>
    <?php endif; // end sidebar widget area ?>
</div><!-- #secondary .widget-area -->
<?php echo $theme->after_widget ?>
<script type="text/javascript">

	$(".expnd").click(function(event) {
		event.preventDefault();
		var a = $(this).parent();
		console.log(a.attr("class"));
		if(a.hasClass("widget_wpjb-search")){
			console.log("kd");
		    a.animate({
		        height: (a.height() == 780) ? 35 : 780
		    }, 400);		
		}
		else{
			console.log("lol");
		    a.animate({
		        height: (a.height() == 300) ? 35 : 300
		    }, 400);			
		}
	});

	$("#adv").load("http://www.thesalesfloor.co.uk/jobs/advanced-search/ #wpjb-main");
</script>