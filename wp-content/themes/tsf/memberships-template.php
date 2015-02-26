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
                <div id="bronze" data-target="1" class="panel bronze">
                    <h2>Bronze package</h2>
                    <p class="price value">£765.00</p>
                    <p>Perfect for junior/entry level vacancies, or vacancies that require candidates to have little previous experience.</p>
                    <div class="large-5 columns"><p>Select No. of posts:</p></div>
                    <div class="large-7 columns package-select">
                        <select autocomplete="off" class="cs_package" data="1">
                            <option>3</option>
                            <option>5</option>
                            <option>10</option>
                        </select>
                        <b class="value">£765.00</b>
                    </div>
                    <br class="clear">
                    <br class="clear">
                    <?php if(!is_user_logged_in()):?>
                        <a class="btn popup bundle" href="#" data-reveal-id="main-pop" data="bronze_posts">Purchase</a>
                    <?php else:?>
                        <a class="btn bundle" data="gold_posts" href="http://www.thesalesfloor.co.uk/jobs/employer/membership/purchase/8/">Purchase</a>
                    <?php endif;?>
                    
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
                <div id="silver" data-target="2" class="panel silver" >
                    <section class="popular">
                        <p>Most Popular</p>
                    </section>
                    <h2>Silver package</h2>
                    <p class="price value">£1065.00</p>
                    <p>Need someone with previous experience? Don’t Just rely on an advert- Let us proactively source for you.</p>
                    <div class="large-5 columns"><p>Select No. of posts:</p></div>
                    <div class="large-7 columns package-select">
                        <select autocomplete="off" class="cs_package" data="2">
                            <option>3</option>
                            <option>5</option>
                            <option>10</option>
                        </select>
                        <b class="value">£1065.00</b>
                    </div>
                    <br class="clear">
                    <br class="clear">
                    <?php if(!is_user_logged_in()):?>
                        <a class="btn popup bundle" href="#" data-reveal-id="main-pop" data="silver_posts">Purchase</a>
                    <?php else:?>
                        <a class="btn bundle" data="gold_posts" href="http://www.thesalesfloor.co.uk/jobs/employer/membership/purchase/11/">Purchase</a>
                    <?php endif;?>
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
                <div id="gold" data-target="3" class="panel gold" >
                    <h2>Gold package</h2>
                    <p class="price value">£1365.00</p>
                    <p>Take control of who see’s your adverts- Reach passive audiences across social media based on industry, job title, location and even company.</p>
                    <div class="large-5 columns"><p>Select No. of posts:</p></div>
                    <div class="large-7 columns package-select">
                        <select autocomplete="off" class="cs_package" data="3">
                            <option>3</option>
                            <option>5</option>
                            <option>10</option>
                        </select>
                        <b class="value">£1365.00</b>
                    </div>
                    <br class="clear">
                    <br class="clear">
                    <?php if(!is_user_logged_in()):?>
                        <a class="btn popup bundle" data-reveal-id="main-pop" data="gold_posts" href="#">Purchase</a>
                    <?php else:?>
                        <a class="btn bundle" data="gold_posts" href="http://www.thesalesfloor.co.uk/jobs/employer/membership/purchase/14/">Purchase</a>
                    <?php endif;?>
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

    <div id="main-pop" class="reveal-modal" data-reveal>
        <h3 class="text-center">Please Register in-order to purchase a package</h3>
        <div class="pop-content"></div>
        <a class="close-reveal-modal">×</a>
        <div class="msgs">
            <div class="error text-center">
                <p class="reg"></p><p class="app"></p><p class="err"></p>
            </div>
            <div class="success text-center">
                <p class="reg"></p><p class="app"></p>
            </div>
        </div>
    </div>

    <section id="sng" data="toggle" class="large-12 columns">

        <div class="all_packages row">
            <div class="large-4 medium-4 columns">
                <div id="br_pak" class="panel bronze">
                    <h2>Bronze</h2>
                    <p class="price">£299</p>
                    <p>Perfect for junior/entry level vacancies, or vacancies that require candidates to have little previous experience.</p>
                    <?php if(!is_user_logged_in()):?>
                        <a class="btn popup" data-reveal-id="main-pop" data="bronze" href="#">Purchase</a>
                    <?php else:?>
                        <a class="btn" data="gold_posts" href="http://www.thesalesfloor.co.uk/jobs/employer/membership/purchase/19/">Purchase</a>
                    <?php endif;?>
                    
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
                <div id="sl_pak" class="panel silver">
                    <section class="popular">
                        <p>Most Popular</p>
                    </section>
                    <h2>Silver</h2>
                    <p class="price">£399</p>
                    <p>Need someone with previous experience? Don’t Just rely on an advert- Let us proactively source for you.</p>
                    <?php if(!is_user_logged_in()):?>
                        <a class="btn popup" data-reveal-id="main-pop" data="silver"  href="#">Purchase</a>
                    <?php else:?>
                        <a class="btn" data="gold_posts" href="http://www.thesalesfloor.co.uk/jobs/employer/membership/purchase/20/">Purchase</a>
                    <?php endif;?>
                    
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
                <div id="gd_pak" class="panel gold">
                    <h2>Gold</h2>
                    <p class="price">£499</p>
                    <p>Take control of who see’s your adverts- Reach passive audiences across social media based on industry, job title, location and even company.</p>
                    <?php if(!is_user_logged_in()):?>
                        <a class="btn popup" data-reveal-id="main-pop" data="gold"  href="#">Purchase</a>
                    <?php else:?>
                        <a class="btn" data="gold_posts" href="http://www.thesalesfloor.co.uk/jobs/employer/membership/purchase/21/">Purchase</a>
                    <?php endif;?>
                    
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
                    <a class="btn" href="mailto:info@thesalesfloor.co.uk?subject=Bronze Subscription Enquiry">Contact us</a>
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
                    <a class="btn" href="mailto:info@thesalesfloor.co.uk?subject=Silver Subscription Enquiry">Contact us</a>
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
                    <a class="btn" href="mailto:info@thesalesfloor.co.uk?subject=Gold Subscription Enquiry">Contact us</a>
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
var num_posts;
$(".cs_package").val('3').change();

$('.cs_package').on('change', function() {
    var tr = $(this).attr("data");
    console.log(tr);
    if(this.value == "3"){
        var parent = $(this).parent().parent().attr("id");
        if(parent === "bronze"){
            $("#"+parent+"[data-target="+tr+"] .value").html("£765.00");
            num_posts = "3_brz";
            console.log(num_posts);
            <?php if(is_user_logged_in()):?>
                $("#bronze .bundle").attr("href","http://thesalesfloor.co.uk/jobs/employer/membership/purchase/8/");
            <?php endif;?>
        }
        else if(parent === "silver"){
            num_posts = "3_sil";
            $("#"+parent+"[data-target="+tr+"] .value").html("£1065.00");
            <?php if(is_user_logged_in()):?>
                $("#silver .bundle").attr("href","http://thesalesfloor.co.uk/jobs/employer/membership/purchase/11/");
            <?php endif;?>
        }
        else if(parent === "gold"){
            num_posts = "3_gld";
            $("#"+parent+"[data-target="+tr+"] .value").html("£1365.00");
            <?php if(is_user_logged_in()):?>
                $("#gold .bundle").attr("href","http://thesalesfloor.co.uk/jobs/employer/membership/purchase/14/");
            <?php endif;?>
        }

    }else if(this.value == "5"){
        var parent = $(this).parent().parent().attr("id");
        
        if(parent === "bronze"){
            $("#"+parent+"[data-target="+tr+"] .value").html("£1150.00");
            num_posts = "5_brz";
            <?php if(is_user_logged_in()):?>
                $("#bronze .bundle").attr("href","http://thesalesfloor.co.uk/jobs/employer/membership/purchase/9/");
            <?php endif;?>
        }
        else if(parent === "silver"){
            $("#"+parent+"[data-target="+tr+"] .value").html("£1650.00");
            num_posts = "5_sil";
            <?php if(is_user_logged_in()):?>
                $("#silver .bundle").attr("href","http://thesalesfloor.co.uk/jobs/employer/membership/purchase/12/");
            <?php endif;?>
        }
        else if(parent === "gold"){
            $("#"+parent+"[data-target="+tr+"] .value").html("£2150.00");
            num_posts = "5_gld";
            <?php if(is_user_logged_in()):?>
                $("#gold .bundle").attr("href","http://thesalesfloor.co.uk/jobs/employer/membership/purchase/15/");
            <?php endif;?>
        }

    }else if(this.value == "10"){
        var parent = $(this).parent().parent().attr("id");
        $("#"+parent+"[data-target="+tr+"] .value").html("£4000");
        if(parent === "bronze"){
            $("#"+parent+"[data-target="+tr+"] .value").html("£1990.00");
            num_posts = "10_brz";
            <?php if(is_user_logged_in()):?>
                $("#bronze .bundle").attr("href","http://thesalesfloor.co.uk/jobs/employer/membership/purchase/10/");
            <?php endif;?>
        }
        else if(parent === "silver"){
            $("#"+parent+"[data-target="+tr+"] .value").html("£2990.00");
            num_posts = "10_sil";
            <?php if(is_user_logged_in()):?>
                $("#silver .bundle").attr("href","http://thesalesfloor.co.uk/jobs/employer/membership/purchase/13/");
            <?php endif;?>
        }
        else if(parent === "gold"){
            $("#"+parent+"[data-target="+tr+"] .value").html("£3990.00");
            num_posts = "10_gld";
            <?php if(is_user_logged_in()):?>
                $("#gold .bundle").attr("href","http://thesalesfloor.co.uk/jobs/employer/membership/purchase/16/");
            <?php endif;?>
        }
    }
});
var purchase_url;

$(".popup").click(function(){
    var pak_parent = "#"+$(this).parent().attr("id")+"";
    var pak = $(this).attr("data");
    purchase_url = pak;
    console.log(purchase_url);
    return purchase_url;
});

if($("#main-pop .pop-content").length){
    $("#main-pop .pop-content").load("<?php echo site_url(); ?>/jobs/employer/register/ #wpjb-main",function(){
        $(".wpjb-form").attr("action","<?php echo site_url(); ?>/jobs/employer/register/");
        $(".wpjb-element-name-company_website,.wpjb-element-name-company_exerpt,.wpjb-element-name-company_info,.wpjb-element-name-is_public,.wpjb-element-name-facebook_link,.wpjb-element-name-twitter_username,.wpjb-element-name-linkedin_link,.wpjb-element-name-company_logo,.wpjb-element-name-company_zip_code").remove();
            $(".wpjb-submit").click(function(e){
                event.preventDefault();
                var uname = $("#wpjb-resume input#user_login").val();
                var email = $("#wpjb-resume input#user_email").val();
                var pass = $("#wpjb-resume input#user_password").val(); 
                var pass2 = $("#wpjb-resume input#user_password2").val(); 
                var comp_name = $("#wpjb-resume input#company_name").val(); 
                var loc = $("#wpjb-resume input#company_location").val(); 
                

                if(uname.length === 0 || email.length === 0 || pass.length === 0 || pass2.length === 0 || comp_name.length === 0 || loc.length === 0){
                    $(".error p.err").html("Please fill all the fields which are marked with *");
                    proceed = false;
                    return false;
                }
                else if(pass != pass2){
                    $(".error p.err").html("Passwords do not match");
                    proceed = false;
                    return false;
                }
                else{
                    var data = 'email-address='+email+'&usname='+uname;
                    $(".success p.app").show();
                    $(".success p.app").html("<img src='<?php bloginfo('template_url'); ?>/wpjobboard/images/spinner-2x.gif'>")
                    $.ajax({
                            type:"post",
                            url:"<?php bloginfo('template_url'); ?>/wpjobboard/job-board/check.php",
                            data:data,
                            success:function(result){
                                if(result=="emailTakenunTaken"){
                                    $(".success p.app").hide();
                                    $(".error").show();
                                    $(".error p.err").html("Email used for registration and username are already taken, please try again or click <a href='http://localhost:8888/wordpress/wp-login.php?action=lostpassword'>Forgot Password</a>");
                                    proceed = false;
                                    return false;
                                    
                                }
                                else if(result=="emailAvailableunTaken"){
                                    $(".success p.app").hide();
                                    $(".error").show();
                                    $(".error p.err").html("Username already taken");
                                    proceed = false;
                                    return false;
                                    
                                }
                                else if(result=="emailTakenunAvailable"){
                                    $(".success p.app").hide();
                                    $(".error").show();
                                    $(".error p.err").html("Email used for registration is already taken, please use a different email or click <a href='<?php echo site_url(); ?>/wp-login.php?action=lostpassword'>Forgot Password</a>");
                                    proceed = false;
                                    return false;
                                    
                                }else{
                                    proceed = true;
                                    if (purchase_url == "bronze"){
                                        url = "http://www.thesalesfloor.co.uk/jobs/employer/membership/purchase/19/";
                                    }
                                    else if (purchase_url == "silver"){
                                        url = "http://www.thesalesfloor.co.uk/jobs/employer/membership/purchase/20/";
                                    }
                                    else if (purchase_url == "gold"){
                                        url = "http://www.thesalesfloor.co.uk/jobs/employer/membership/purchase/21/"
                                    }
                                    else if (purchase_url == "bronze_posts"){
                                        if(num_posts == "3_brz"){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/8/";
                                        }
                                        if(num_posts == "5_brz"){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/9/";
                                        }
                                        if(num_posts == "10_brz"){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/10/";
                                        }
                                        if(!num_posts){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/8/";
                                        }
                                    }
                                    else if (purchase_url == "silver_posts"){
                                        if(num_posts == "3_sil"){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/11/";
                                        }
                                        if(num_posts == "5_sil"){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/12/";
                                        }
                                        if(num_posts == "10_sil"){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/13/";
                                        }
                                        if(!num_posts){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/11/";
                                        }
                                    }
                                    else if (purchase_url == "gold_posts"){
                                        if(num_posts == "3_gld"){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/14/";
                                        }
                                        if(num_posts == "5_gld"){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/15/";
                                        }
                                        if(num_posts == "10_gld"){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/16/";
                                            console.log(url+","+num_posts);
                                        }
                                        if(!num_posts){
                                            url = "http://thesalesfloor.co.uk/jobs/employer/membership/purchase/14/";
                                        }
                                    }
                                    $("#wpjb-resume").submit(function (event){
                                        console.log(proceed);
                                        if(proceed == true){
                                            var postData = $(this).serializeArray();
                                            var formURL = $(this).attr("action");
                                            $.ajax(
                                            {
                                                url : formURL,
                                                type: "POST",
                                                data : postData,
                                                success:function(data, textStatus, jqXHR) 
                                                {
                                                    $(".error").hide();
                                                    $(".success p.app").show();
                                                    $(".success p.app").html("Registration complete, please click<a href='"+url+"'> <b>here</b></a> to purchase your selected membership");
                                                },
                                                error: function(jqXHR, textStatus, errorThrown) 
                                                {
                                                    $(".error p.app").html("Something went wrong"); 
                                                }
                                            });
                                            event.preventDefault();
                                            $(this).unbind(event);            
                                        }
                                    });
                                    $("#wpjb-resume").submit();
                                }
                            }
                     }); 
                }
            });
    });
}

</script>
<?php get_footer() ?>


