<?php
/*
Template Name: Memberships
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
<div id="common_banner" class="row">
    <div class="search_wrapper large-6 medium-6 medium-centered large-centered columns">
        <div class="">
            <form action="<?php esc_attr_e($search_url) ?>" method="get">
                <?php if (!get_option('permalink_structure')): ?>
                    <input type="hidden" name="page_id" value="<?php echo Wpjb_Project::getInstance()->conf("link_jobs") ?>" />
                    <input type="hidden" name="job_board" value="find" />
                <?php endif; ?>
                <div class="large-6 columns">
                    <input type="text" name="query" class="wpjb-ls-query" placeholder="<?php _e('Keyword, location, company', 'jobeleon'); ?>" value="<?php esc_attr_e($param["query"]) ?>" />
                </div>
                <div class="large-6 columns">
                    <select id="category" name="category" class="">
                        <option value=""><?php _e('All categories', 'jobeleon'); ?></option>
                        <?php
                        $job_categories = wpjb_form_get_categories();
                        foreach ($job_categories as $cat) :
                            ?>

                            <option value="<?php echo $cat['value']; ?>" <?php selected($cat['value'], $param["category"]); ?>><?php echo $cat['description']; ?></option>  

                        <?php endforeach; ?>    
                    </select>
                </div>
                <div style="clear:both"></div>
                <input type="submit" class="btn" value="<?php _e("Search", "jobeleon") ?>" />
            </form>
        </div><!-- /search -->        
    </div>

</div>

<div id="content_wrapper" class="row <?php echo sanitize_title_with_dashes(get_the_title($ID)); ?>">
    <section class="large-12 columns">
        <p class="intro">
            At The Sales Floor we understand that different sales jobs require different types of people. Don’t make the mistake of trying to pick a ‘one-size-fits-all’ job post that simply goes out to countless job seekers who are irrelevant for your role. For this reason we have 3 different types of package to pick from depending on the type of role you’re recruiting for. Whether it’s a junior role that requires advertising to a mass audience, or senior, or niche role that requires a more targeted, or proactive approach, we’ve got you covered.
        </p>

        <div class="tabs row">
            <div class="tabs_wrapper large-6 columns large-centered medium-6 medium-centered">
                <a href="#" data-target="sng" class="active packages large-4 small-4 columns">
                    <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/post-icon.png">
                    <p>Single job postings</p>
                </a>
                <a href="#" data-target="pk" class="single_sus large-4 small-4  columns">
                    <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/package-icon.png">
                    <p>Bundled posts</p>
                </a>
                <a href="#" data-target="susc" class="sus large-4  small-4 columns">
                    <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/sus-icon.png">
                    <p>Subscriptions</p>
                </a>
            </div>
        </div>
    </section>

    <section id="pk" data="toggle" class="large-12 columns">

        <div class="all_packages row">
            <div class="large-4 medium-4 columns">
                <div id="panel" data-target="1" class="panel bronze">
                    <h2>Bronze package</h2>
                    <p class="price value">£299</p>
                    <p>Perfect for junior/entry level vacancies, or vacancies that require candidates to have little previous experience.</p>
                    <div class="large-5 columns"><p>Select No. of posts:</p></div>
                    <div class="large-7 columns package-select">
                        <select class="cs_package" data="1">
                            <option>3</option>
                            <option>5</option>
                            <option>7</option>
                            <option>10</option>
                        </select>
                        <b class="value">£1000</b>
                    </div>
                    <br class="clear">
                    <br class="clear">
                    <a class="btn custom_link" href="#">Purchase</a>
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

            <div class="large-4 medium-4 columns">
                <div id="panel" data-target="2" class="panel silver" >
                    <section class="popular">
                        <p>Most Popular</p>
                    </section>
                    <h2>Silver package</h2>
                    <p class="price value">£399</p>
                    <p>Need someone with previous experience? Don’t Just rely on an advert- Let us proactively source for you.</p>
                    <div class="large-5 columns"><p>Select No. of posts:</p></div>
                    <div class="large-7 columns package-select">
                        <select class="cs_package" data="2">
                            <option>3</option>
                            <option>5</option>
                            <option>7</option>
                            <option>10</option>
                        </select>
                        <b class="value">£1000</b>
                    </div>
                    <br class="clear">
                    <br class="clear">
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

            <div class="large-4 medium-4 columns">
                <div id="panel" data-target="3" class="panel gold" >
                    <h2>Gold package</h2>
                    <p class="price value">£499</p>
                    <p>Take control of who see’s your adverts- Reach passive audiences across social media based on industry, job title, location and even company.</p>
                    <div class="large-5 columns"><p>Select No. of posts:</p></div>
                    <div class="large-7 columns package-select">
                        <select class="cs_package" data="3">
                            <option>3</option>
                            <option>5</option>
                            <option>7</option>
                            <option>10</option>
                        </select>
                        <b class="value">£1000</b>
                    </div>
                    <br class="clear">
                    <br class="clear">
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
    </section>

    <section id="sng" data="toggle" class="large-12 columns">

        <div class="all_packages row">
            <div class="large-4 medium-4 columns">
                <div class="panel bronze">
                    <h2>Bronze</h2>
                    <p class="price">£299</p>
                    <p>Perfect for junior/entry level vacancies, or vacancies that require candidates to have little previous experience.</p>
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

            <div class="large-4 medium-4 columns">
                <div class="panel silver">
                    <section class="popular">
                        <p>Most Popular</p>
                    </section>
                    <h2>Silver</h2>
                    <p class="price">£399</p>
                    <p>Need someone with previous experience? Don’t Just rely on an advert- Let us proactively source for you.</p>
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

            <div class="large-4 medium-4 columns">
                <div class="panel gold">
                    <h2>Gold</h2>
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
    </section>

    <section id="susc" data="toggle" class="large-12 columns">

        <div class="all_packages row">
            <div class="large-4 medium-4 columns">
                <div class="panel bronze">
                    <h2>Bronze subscription</h2>
                    <em>Pricing per post starts at -</em>
                    <p class="price">£75</p>
                    <p>Looking for a long term option that keeps costs as low as possible? Our Bronze subscription gives you as many posts a month as you want, from as little as 3 months..</p>
                    <p>Call our team now to discuss options, and set up your perfect package today.</p>
                    <a href="tel:">0203 714 1155</a><br>
                    <a href="#">info@thesalesfloor.co.uk</a>
                    <br><br>
                    <a class="btn" href="mailto:info@thesalesfloor.co.uk?subject=Bronze suscription enquire">Contact us</a>
                    <hr>
                </div>
            </div>

            <div class="large-4 medium-4 columns">
                <div class="panel silver">
                    <section class="popular">
                        <p>Most Popular</p>
                    </section>
                    <h2>Silver subscription</h2>
                    <em>Pricing per post starts at -</em>
                    <p class="price">£99</p>
                    <p>Looking for a long term option where we provide sourcing and talent pooling for all your roles? Our Silver subscription gives you as many posts a month as you want, from as little as 3 months.</p>
                    <p>Call our team now to discuss options, and set up your perfect package today.</p>
                    <a href="tel:">0203 714 1155</a><br>
                    <a href="#">info@thesalesfloor.co.uk</a>
                    <br><br>
                    <a class="btn" href="mailto:info@thesalesfloor.co.uk?subject=Silver suscription enquire">Contact us</a>
                    <hr>
                </div>
            </div>

            <div class="large-4 medium-4 columns">
                <div class="panel gold">
                    <h2>Gold subscription</h2>
                    <em>Pricing per post starts at -</em>
                    <p class="price">£149</p>
                    <p>Looking for the whole package in your sales hires? Our Gold subscription gives you the whole package to help you find that talent, with as
many posts a month as you want, from as little as 3 months.</p>
                    <p>Call our team now to discuss options, and set up your perfect package today.</p>
                    <a href="tel:">0203 714 1155</a><br>
                    <a href="#">info@thesalesfloor.co.uk</a>
                    <br><br>
                    <a class="btn" href="mailto:info@thesalesfloor.co.uk?subject=Gold suscription enquire">Contact us</a>
                    <hr>
                </div>
            </div>
        </div>
    </section>
<script type="text/javascript">
$(".tabs_wrapper a").click(function(event){
    event.preventDefault();
    $(".tabs_wrapper a").removeClass("active");
    $(this).addClass("active");
    var target = $(this).attr("data-target");
    $("[data=toggle]").css("display","none");
    $("#"+target+"").css("display","block");
});

$('.cs_package').on('change', function() {
    var tr = $(this).attr("data");
    console.log(tr);
    if(this.value == "3"){
        var parent = $(this).parent().parent().attr("id");
        $("#"+parent+"[data-target="+tr+"] .value").html("£1000");
    }else if(this.value == "5"){
        var parent = $(this).parent().parent().attr("id");
        $("#"+parent+"[data-target="+tr+"] .value").html("£2000");
    }else if(this.value == "7"){
        var parent = $(this).parent().parent().attr("id");
        $("#"+parent+"[data-target="+tr+"] .value").html("£3000");
    }else if(this.value == "10"){
        var parent = $(this).parent().parent().attr("id");
        $("#"+parent+"[data-target="+tr+"] .value").html("£4000");
    }
});
</script>

<?php get_footer() ?>


