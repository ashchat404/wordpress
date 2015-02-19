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
        <blockquote class="one qt"><p>The Sales Floor is a great new addition to the marketplace - <span> <em>British Gas</em> </span></p></blockquote> 
        <blockquote class="two qt"><p>We’d recommend them to anyone else looking for a new approach to finding sales people - <span><em>ESI International</em></span></p></blockquote>
        <blockquote class="three qt"><p>Professional, reliable and very affordable - <span><em> FOUR Magazine</em></span></p></blockquote>
        <blockquote class="four qt"><p>We wouldn’t hesitate to call upon them for our next sales requirement. - <span><em>Pleasecycle</em></span></p></blockquote>
        <blockquote class="five qt"><p>A highly proactive, and cost effective alternative to traditional agencies and job boards! - <span><br><em>Journeycall</em></span></p></blockquote>
    </div>
    <div class="large-4 medium-6 columns large-centered medium-centered">
        <div class="emp_bannerblock">
            <div class="large-12 text-center columns">
                <p class="find">Got a sales role to fill?</p>
                <a href="http://www.thesalesfloor.co.uk/jobs/add/" class="btn">Advertise</a>
            </div>
            <div class="clear"></div>
            <p style="font-size:12px;text-align:center;padding: 5px 20px;margin: 0px;">or call our sales team on 0203 714 1155</p>
        </div>
    </div>
</div>

<div id="content_wrapper" class="row <?php echo sanitize_title_with_dashes(get_the_title($ID)); ?>">
    <section class="solutions large-12 columns">
        <div class="wrap">
            <div class="large-3 medium-3 small-3 columns">
                <a data-target="chlng" class="active" href="">
                        <img alt="problem-icon" src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/problem-icon.png">
                    <div class="arrow_box"></div>
                </a>
            </div>
            <div class="large-3 medium-3 small-3 columns">
                <a data-target="sol" href="">
                    <img alt="solution icon" src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/sol-icon.png">
                    <div class="arrow_box"></div>
                </a>
            </div>
            <div class="large-3 medium-3 small-3 columns">
                <a data-target="diff" href="">
                    <img alt="difference icon" src="<?php bloginfo('template_directory'); ?>/wpjobboard/images/diff-icon.png">
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
                <div class="list-icon-items para-1">
                    <div class="large-2 columns"><a href="javascript:void(0)"><i class="circ fi-clock"><i class="circ pulse"></i></i><p>Time?</p></a></div>
                    <div class="large-2 columns"><a href="javascript:void(0)"><i class="circ fi-dollar"><i class="circ pulse"></i></i><p>Budget ?</p></a></div>
                    <div class="large-3 columns"><a href="javascript:void(0)"><i class="circ fi-torsos-male-female"><i class="circ pulse"></i></i><p>Scarce Talent ?</p></a></div>
                    <div class="large-3 columns"><a href="javascript:void(0)"><i class="circ fi-torso-business"><i class="circ pulse"></i></i><p>Quality Talent ?</p></a></div>
                    <div class="large-2 columns"><a href="javascript:void(0)"><i class="circ fi-star"><i class="circ pulse"></i></i><p>Brand ?</p></a></div>                </div>
                <div class="clear"></div>
                <p class="text-center">Are any of these true for your company? We’ve found a <a href="" class="hints" data-target="sol">solution</a></p>
            </section>
            <section id="sol" class="hide" data="toggle-content">
                <h2>Our solutions</h2>
                <p>
The Sales Floor has been designed to combat the ever present challenges that most companies face when it comes to recruiting talent…
<br><br>
<b>TIME</b> – We post your vacancies, filter your applications, and find passive talent for you to connect with and talent pool for the future. No more man hours spend resourcing or sifting through irrelevant applications.
<br><br>
<b>SCARCE TALENT-</b> Ever find yourself having to utilise agencies to find you scarce talent? Our platform finds you the same talent agencies find you, and helps you reach out and connect with them too.
<br><br>
<b>QUALITY TALENT-</b> If you post a job advert to millions of jobseekers, then of course quality of applications will drop. Our targeted social media advertising means your adverts will be shown only to relevant candidates across the likes of LinkedIn, Facebook and Twitter depending on their Location, Industry, Job Title and Company.
<br><br>
<b>BRAND-</b> All our job adverts are branded free of charge. We help you get your brand out there to passive candidates across social media, and allow candidates to engage with your brand better than ever.
<br><br>
Want to see how we do this?.. <a href="#" class="hints" data-target="diff">Hybrid Approach</a>
                </p>
            </section>
            <section id="diff" class="hide" data="toggle-content">
                <h2>The Hybrid Approach</h2>
                <p>
                    For us, it’s all about helping you do everything you possibly can to fill that job. Chucking a job advert out on the internet just doesn’t work sometimes. We combine traditional advertising with targeted advertising and sourcing to give you more access to the talent you need.
                    <br><br>
                    <b>Targeted Advertising</b>
                    <br><br>
                    <div class="large-6 columns no-padding">
                    Are your jobs being posted on social media? If so, are they reaching relevant candidates, or are they just being posted to your network? Our team will set up your branded adverts to be shown only to relevant candidates across social media, meaning relevant applications from a stronger talent pool.<br><br>
                    <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/linked-cand.jpg">
                    </div>
                    <div class="large-6 columns">
                        <img alt="target sourcing" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/target-sourcing.jpg">
                    </div>
                    <div class="clear"><br><hr style="border: 1px dotted #E8EAEB;"><br></div>
                    <b>Proactive Sourcing</b>
                    <br><br>
                    <div class="large-6 columns no-padding">
                        The wise amongst you know that you can’t always rely just on an job advert. Our Founders are ex-sales recruiters, and firmly believe that the proactive approach is vital in any recruitment process. We also realise however that you don’t always have the time to find people yourself, hence the need for agencies.
                        <br><br>
                        But what if that sourcing was done for you?
                         <br><br>
                        Within 24 hours of your role going live, our team will map out, and come back to you with relevant talent to reach out to and connect with directly, saving you crucial resourcing time, but giving you strong passive talent to engage with. The best part is, you have this service at your fingertips the whole time your advert is live.
                        <br><br>
                        Like what you’re seeing? So do our clients… <a href="#" class="hints" data-target="chill">Satisfaction</a>
                    </div>
                    <div class="large-6 columns">
                        <h2 class="text-left no-padding">Your LinkedIn Candidates!</h2>
                        <h3>Hand-picked by The Sales Floor</h3>
                        <img alt="linkedin Candidates" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/candidates-linkedin.jpg" style="border: 1px solid #E1E1E1;">
                    </div>
                    <div class="clear"></div>
               </p>
            </section>
            <section id="chill" class="hide" data="toggle-content">
                <h2>Satisfaction</h2>
                <p>
Since launching in 2014, over 1000 different employers have posted jobs on The Sales Floor. We’re proud to have a number of satisfied clients across a number of industries who would be only too happy to act as references for how much time and money our services have saved them.
<br><br>
Why not check out some of our other clients <a href="http://www.thesalesfloor.co.uk/testimonials/">here</a>?
<br><br>
If you want to find out more, or discuss your requirements in more depth with one of our management team, then call us on 0203 714 1155.
<br><br>
                 </p>
            </section>
        </div>
    </section>

    <section id="pks" class="large-12 columns">
        <h2 class="text-center">Our Packages</h2>
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
                        <a class="btn bundle" data="gold_posts" href="#">Purchase</a>
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
                        <a class="btn bundle" data="gold_posts" href="#">Purchase</a>
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
                        <a class="btn bundle" data="gold_posts" href="#">Purchase</a>
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
        <br><br>
    </section>
    
    <section id="testmonials" class="large-12 columns">
        <h2 class="text-center"><img alt="chat" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/chat-icon.png"> Testmonials</h2>
        <p class="sub-title text-center">Some kind words from our clients…</p>
        <div class="reviews">
            <div>  
                <a href="http://thesalesfloor.co.uk/testimonials/">
                    <img alt="" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/pls.png">
                        <p>““We started working with The Sales Floor team not long after their launch, and found them
personal, professional and very different to other job board offerings out there.
For a start they actually are a Sales Specialist...</p>
                    <b>- Ronan Carter, Director</b>
                </a>
            </div>
            <div> 
                <a href="http://thesalesfloor.co.uk/testimonials/">
                    <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/fr.jpg">
                    <p> “I initially contacted The Sales Floor to help us with an urgent need for a quick hire, I was very impressed with the results. Within hours I had been provided with a substantial list of potential target candidates...</p>
                    <b>- James Harpum, Financial Controller</b>
                </a>
            </div>
            <div>
                <a href="http://thesalesfloor.co.uk/testimonials/">
                    <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/esi.gif">
                    <p>
                    “We decided to partner with The Sales Floor for a UK Sales requirement we had early in 

                    2014. We found them straightforward, incredibly helpful, and quick to respond when we 

                    needed them. They combine traditional ...
                    </p>
                    <b>- Mary Johnson, Talent Acquisition Manager (ESI International, part of Informa Group)</b>
                </a>

            </div>
            <div>
                <a href="http://thesalesfloor.co.uk/testimonials/">
                    <img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/bg.png">
                    <p>“Our relationship with The Sales Floor has so far resulted in us hiring three candidates into call centre and field sales roles. We are also still considering candidates for a couple...</p>
                    <b>- Helen Butterworth, Group Attraction and Diversity.</b>
                </a>
            </div>

        </div>

    </section>

    <section id="discovered" class="large-12 columns">
        <h2 class="text-center"><img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/mag-icon.png"> Discovered by</h2>
        <p class="sub-title text-center">Come and see who else has discovered us…</p>

        <div class="row">
            <div class="large-12 columns">
                <a href="http://localhost:8888/wordpress/testimonials/#more">
                    <div class="large-3 medium-3 small-6 columns">
                    <img class="dis_img" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/harrods_2.png">

                    </div>
                    <div class="large-3 medium-3 small-6 columns">
                    <img class="dis_img" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/manutd.png">

                    </div>
                    <div class="large-3 medium-3 small-6 columns">
                    <img class="dis_img" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/carlsberg.png">

                    </div>
                    <div class="large-3 medium-3 small-6 columns">
                    <img class="dis_img" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/bgas.png">

                    </div>
                </a>
            </div>
        </div>
    </section>

<script type="text/javascript">

$(".tabs_wrapper a").click(function(event){
    event.preventDefault();
    $(".tabs_wrapper a").removeClass("active");
    $(this).addClass("active");
    var target = $(this).attr("data-target");
    console.log(target);
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
        var target = $(this).attr("data-target");
        $("a[data-target="+target+"]").addClass("active");
        $("[data=toggle-content]").removeClass("show").addClass("hide");
        $("#"+target+"").removeClass("hide").addClass("show");        
    }

$('.solutions a').click(hoverclick).hover(hoverclick);
$(".hints").click(hoverclick);

$(".hints[data-target=chill]").click(function() {
    $('html, body').animate({
        scrollTop: $(".emp_bannerblock").offset().top
    }, 1000);
    
});
})();

</script>

<?php get_footer() ?>

