<?php
/*
Template Name: Thank you external
*/
?>
<?php
get_header();
?>

<div id="content_wrapper" class="row <?php echo sanitize_title_with_dashes(get_the_title($ID)); ?>">
	<section class="large-12 columns">

	</section>
<p>
This job requires you to complete your application on the Employers careers website. We are re-directing you now. If you are not re-directed in <span class="countdown">&nbsp;</span> seconds, please click on the button below.</p><a class="moreinfo" href="">Next</a>
</p>

<script type="text/javascript">
var link = localStorage.getItem("_External_link");
localStorage.removeItem("_External_link");
console.log(link);

  var count = 10;
  var countdown = setInterval(function(){
    $(".countdown").html(count);
    if (count == 0) {
      clearInterval(countdown);
      window.open(link, "_self");

    }
    count--;
  }, 1000);


$(".moreinfo").attr("href",link);
</script>

<?php get_footer() ?>