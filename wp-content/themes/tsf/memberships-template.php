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
                <a href="#" data-target="sng" class="active packages large-4 columns">
                    <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/post-icon.png">
                    <p>Single job postings</p>
                </a>
                <a href="#" data-target="pk" class="single_sus large-4 columns">
                    <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/package-icon.png">
                    <p>Packages</p>
                </a>
                <a href="#" data-target="susc" class="sus large-4 columns">
                    <img src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/sus-icon.png">
                    <p>Subscriptions</p>
                </a>
            </div>
        </div>
    </section>

    <section id="pk" data="toggle" class="large-12 columns">

        <div class="all_packages row">
            <div class="large-4 columns">
                <div class="panel bronze">
                    <h2>Package Bronze</h2>
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

            <div class="large-4 columns">
                <div class="panel silver">
                    <section class="popular">
                        <p>Most Popular</p>
                    </section>
                    <h2>Package Silver</h2>
                    <p class="price">£399</p>
                    <p>Need someone with previous experience? Just rely on an advert- Let us proactively source for you.</p>
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
    </section>

    <section id="sng" data="toggle" class="large-12 columns">

        <div class="all_packages row">
            <div class="large-4 columns">
                <div class="panel bronze">
                    <h2>Single Bronze</h2>
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

            <div class="large-4 columns">
                <div class="panel silver">
                    <section class="popular">
                        <p>Most Popular</p>
                    </section>
                    <h2>Single Silver</h2>
                    <p class="price">£399</p>
                    <p>Need someone with previous experience? Just rely on an advert- Let us proactively source for you.</p>
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
                    <h2>Single Gold</h2>
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
            <div class="large-4 columns">
                <div class="panel bronze">
                    <h2>Subscription Bronze</h2>
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

            <div class="large-4 columns">
                <div class="panel silver">
                    <section class="popular">
                        <p>Most Popular</p>
                    </section>
                    <h2>Subscription Silver</h2>
                    <p class="price">£399</p>
                    <p>Need someone with previous experience? Just rely on an advert- Let us proactively source for you.</p>
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
                    <h2>Subscription Gold</h2>
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
<script type="text/javascript">
$(".tabs_wrapper a").click(function(event){
    event.preventDefault();
    $(".tabs_wrapper a").removeClass("active");
    $(this).addClass("active");
    var target = $(this).attr("data-target");
    $("[data=toggle]").css("display","none");
    $("#"+target+"").css("display","block");
    console.log(target);
});
</script>

<?php get_footer() ?>


