<?php
/*
Template Name: Recruiter's page
*/
?>
<?php
get_header();
?>
<?php
$all_null = true;

foreach (array("query", "category", "type") as $p) {
    $ls_default[$p] = "";
    if (!isset($param[$p])) {
        $param[$p] = null;
    } else {
        $all_null = false;
    }
}

if (get_option('permalink_structure')) {
    $spoiler = "?";
} else {
    $spoiler = "&";
}

if ($all_null) {
    $spoiler2 = "";
} else {
    $spoiler2 = $spoiler;
}

$search_url = wpjb_link_to("search");

$current_category = null;
$current_type = null;

if ($param["type"] > 0) {
    $current_type = new Wpjb_Model_Tag($param["type"]);
    if (!$current_type->exists() || $current_type->type != "type") {
        $current_type = null;
    }
}

if ($param["category"] > 0) {
    $current_category = new Wpjb_Model_Tag($param["category"]);
    if (!$current_category->exists() || $current_category->type != "category") {
        $current_category = null;
    }
}
?>
<div id="banner" class="row">
    <div class="quotes">
        <p class="one qt"><i class="start">“</i>The Sales Floor is a great new addition to the marketplace - <span><em>British Gas</em></span><i class="end">”</i></p>
        <p class="two qt"><i class="start">“</i>We’d recommend them to anyone else looking for a new approach to finding sales people<i class="end">”</i> - <span><em>ESI International</em></span></p>
        <p class="three qt"><i class="start">“</i>Professional, reliable and very affordable<i class="end">”</i> - <span><em> FOUR Magazine</em></span></p>
        <p class="four qt"><i class="start">“</i>We wouldn’t hesitate to call upon them for our next sales requirement.<i class="end">”</i> - <span><em>Pleasecycle</em></span></p>
        <p class="five qt"><i class="start">“</i>A highly proactive, and cost effective alternative to traditional agencies and job boards!<i class="end">”</i> - <span><em>Journeycall</em></span></p>
    </div>
    <div class="large-4 medium-4 columns large-centered medium-centered">
        <div class="emp_bannerblock">
            <div class="large-6 medium-6 columns">
                Looking to advertise a sales job?
            </div>
            <div class="large-6 medium-6 columns">
                <a href="#" class="btn">Adversite now</a>
            </div>
            <div class="clear"></div>
            <p style="font-size:12px;text-align:center">Alternatively, speak to an Account Manager on 02023 714 1155</p>
        </div>
    </div>
</div>

<div id="content_wrapper" class="row <?php echo sanitize_title_with_dashes(get_the_title($ID)); ?>">
    <section class="solutions large-12 columns">
        <div class="wrap">
            <div class="large-3 medium-3 small-3 columns">
                <a data-target="chlng" class="active" href="">
                        <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/problem-icon.png">
                    <div class="arrow_box"></div>
                </a>
            </div>
            <div class="large-3 medium-3 small-3 columns">
                <a data-target="sol" href="">
                    <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/sol-icon.png">
                    <div class="arrow_box"></div>
                </a>
            </div>
            <div class="large-3 medium-3 small-3 columns">
                <a data-target="diff" href="">
                    <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/diff-icon.png">
                    <div class="arrow_box"></div>
                </a>
            </div>
            <div class="large-3 medium-3 small-3 columns">
                <a data-target="chill" href="">
                    <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/result-icon.png">
                    <div class="arrow_box"></div>
                </a>
            </div>
        </div>
    </section>

    <section class="large-12 columns">
        <div class="content">
            <section id="chlng" class="show" data="toggle-content">
                <h2>Your challenges</h2>
                <p>
At The Sales Floor we understand the role of a modern HR / Recruitment professional. The one thing you lack is time, which is also something that traditional boards fail to give you. Ask most employers what their biggest frustrations with job boards are, and no doubt the following would feature:<br><br>
 
-          Too many irrelevant applications<br>

-          Low quality response<br><br>
Too often this just causes more stress for you- meaning you spend more time sifting through irrelevant CV’s and less time speaking to strong candidates. It all points to a lack of control, and an untargeted approach, both of which will cause you issues. Luckily we know exactly how to overcome those issues…
                </p>
            </section>
            <section id="sol" class="hide" data="toggle-content">
                <h2>Our solutions</h2>
                <p>
The Sales Floor has been completely designed around saving employers time, and money when recruiting sales professionals. We give you back control, allowing you to define exactly who can see your advert, and hence, who can apply to your advert. There’s no value in having millions of people see your advert if 95% of them are completely irrelevant.<br><br>
 
We also realise that sometimes an advert just isn’t enough. The only people applying to adverts are active candidates. Employers want to tap into the passive marketplace- those people currently in work and hitting their targets. They require a more proactive approach. That’s exactly why our team of ex-sales recruiters will go out within 24 hours of you posting your advert, and produce a shortlist of strong, relevant candidates for you to engage with directly, saving you crucial resourcing time, and more importantly removing the need to enlist the help of costly recruitment agencies. <br><br>
 
So how do we differ?...
                </p>
            </section>
            <section id="diff" class="hide" data="toggle-content">
                <h2>The Hybrid Approach</h2>
                <p>
As a job board, we can advertise your role in exactly the same way other traditional boards do, catering perfectly for entry, or junior level roles that may require a more volume focussed approach, but our hybrid approach means that for mid to senior level roles, we are perfectly positioned to combine traditional advertising with proactive sourcing and targeted advertising, as well as CV filtering which means you have access not only to large volumes of active job seekers, but you are also provided with strong, relevant sales talent that you would traditionally pay an agency for.<br><br>
 
Our Sourcing service works to a 24 hour SLA, meaning we save you crucial time by not having to resource on your role.
Our CV Filtering service removes irrelevant applicants, meaning you are sent only those relevant ones, again, saving you having to spend the time doing it yourself.<br>
Our targeted adverts mean you are 100% guaranteed there are relevant, passive candidates seeing your job advert across social media

                </p>
            </section>
            <section id="chill" class="hide" data="toggle-content">
                <h2>Satisfaction</h2>
                <p>
Since launching in 2014, over 1000 different employers have posted jobs on The Sales Floor. We’re proud to have a number of satisfied clients across a number of industries who would be only too happy to act as references for how much time and money our services have saved them. If you want to find out more, or discuss your requirements in more depth with one of our management team, then call us on 0203 714 1155.<br><br>
                 </p>
            </section>
        </div>
    </section>

    <section id="pk" data="toggle" class="large-12 columns">
        <h2 class="text-center">Our Packages</h2>
        <div class="all_packages row">
            <div class="large-4 columns">
                <div class="panel bronze">
                    <h2>Package Bronze</h2>
                    <p class="price">£299</p>
                    <p>Perfect for junior/entry level vacancies, or vacancies that require little previous experience.</p>
                    <a class="btn" href="#">Purchase</a>
                    <hr>
                    <ul class="details">
                        <li><p>4 week branded advert on The Sales Floor</p></li>
                        <li><p>4 week sponsored advert on Indeed.com </p></li>
                        <li><p>4 week adverts on other job boards within our partner network</p></li>
                        <li><p>Daily updates and notifications to our communities of followers across Facebook, LinkedIn, and Twitter</p></li>
                        <li><p class="underline">Proactive Sourcing</p></li>
                        <li><p class="underline">CV filtering</p></li>
                        <li><p class="underline">Targeted adverts</p></li>
                    </ul>
                </div>
            </div>

            <div class="large-4 columns">
                <div class="panel silver">
                    <section class="popular">
                        <p>Most Popular</p>
                    </section>
                    <h2>Package Silver</h2>
                    <p class="price">£399</p>
                    <p>Need someone with previous experience? Don’t just rely on an advert- Let us proactively source for you.</p>
                    <a class="btn" href="#">Purchase</a>
                    <hr>
                    <ul class="details">
                        <li><p>4 week branded advert on The Sales Floor</p></li>
                        <li><p>4 week sponsored advert on Indeed.com </p></li>
                        <li><p>4 week adverts on other job boards within our partner network</p></li>
                        <li><p>Daily updates and notifications to our communities of followers across Facebook, LinkedIn, and Twitter</p></li>
                        <li><p>Proactive Sourcing</p></li>
                        <li><p>CV filtering</p></li>
                        <li><p class="underline">Targeted adverts</p></li>
                    </ul>
                </div>
            </div>

            <div class="large-4 columns">
                <div class="panel gold">
                    <h2>Package Gold</h2>
                    <p class="price">£499</p>
                    <p>Take control of who see’s your adverts- Reach passive audiences across social media based on industry, job title, location and even company.</p>
                    <a class="btn" href="#">Purchase</a>
                    <hr>
                    <ul class="details">
                        <li><p>4 week branded advert on The Sales Floor</p></li>
                        <li><p>4 week sponsored advert on Indeed.com </p></li>
                        <li><p>4 week adverts on other job boards within our partner network</p></li>
                        <li><p>Daily updates and notifications to our communities of followers across Facebook, LinkedIn, and Twitter</p></li>
                        <li><p>Proactive Sourcing</p></li>
                        <li><p>CV filtering</p></li>
                        <li><p>Targeted adverts</p></li>
                    </ul>
                </div>
            </div>
        </div>

        <center><a class="btn" href="http://testing.thesalesfloor.co.uk/new/wordpress/employer-packages/">See more</a></center>
        <br><br>
    </section>

    <section id="testmonials" class="large-12 columns">
        <h2 class="text-center"><img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/chat-icon.png"> Testmonials</h2>
        <p class="sub-title text-center">Here's what people are saying about us</p>
        <div class="reviews">
            <div>  
                <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/harrods.png">
                <p>We’d recommend them to anyone else looking for a new approach to finding sales people”</p>
                <b>- John Deo</b>
            </div>
            <div>  
                <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/bytes.png">
                <p>We’d recommend them to anyone else looking for a new approach to finding sales people”</p>
                <b>- John Deo</b>
            </div>

        </div>

    </section>

    <section id="discovered" class="large-12 columns">
        <h2 class="text-center"><img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/mag-icon.png"> Discovered by</h2>
        <p class="sub-title text-center">Why not take a look at a selection of our clients?</p>

        <div class="row">
            <div class="large-12 columns">
                <center><img class="dis_img" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/discovered-by.png"></center>
            </div>
        </div>
    </section>

<script type="text/javascript">
$(document).ready(function(){
    $('.reviews').slick({
        dots: true,
        autoplay: true,
        autoplaySpeed: 4000,
        responsive: [
        {
          breakpoint: 768,
          settings: {
            arrows: false,
            centerPadding: '40px',
            slidesToShow: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            arrows: false,
            centerPadding: '40px',
            slidesToShow: 1
          }
        }
      ]
    });
});

(function() {

    var quotes = $(".qt");
    var quoteIndex = -1;
    
    function showNextQuote() {
        ++quoteIndex;
        quotes.eq(quoteIndex % quotes.length)
            .fadeIn(2000)
            .delay(2000)
            .fadeOut(2000, showNextQuote);
    }
    
    showNextQuote();

    var hoverclick = function(event){
        event.preventDefault();
        $(".solutions a").removeClass("active");
        $(this).addClass("active");
        var target = $(this).attr("data-target");
        $("[data=toggle-content]").removeClass("show").addClass("hide");
        $("#"+target+"").removeClass("hide").addClass("show");        
    }

$('.solutions a').click(hoverclick).hover(hoverclick);
})();
</script>

<?php get_footer() ?>

