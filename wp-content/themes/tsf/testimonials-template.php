<?php
/*
Template Name: Testimonials template
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

    <section id="featured_themes" class="main-testimonials">
        <div id="jobs" class="row widget_wpjb-featured-jobs">
            <h2 class="text-center">What our client's say</h2>
            <div>
                <li class="large-4 columns">
                    <a data-target="pleasecycle" class="row block" title="read more" href="#">
                            <div class="featured_logo">
                                <div class="chld">
                                    <img alt="pleasecycle logo" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/pls.png">
                                </div>
                            </div>
                        <div class="row featured_content">
                            <h3 class="job-title">
                                <p class="job-company">“We started working with The Sales Floor team not long after their launch, and found them
personal, professional and very different to other job board offerings out there.
For a start they actually are a Sales Specialist...<i>more</i></p>
                            </h3>
                        </div>

                        <div class="featured_extra">
                            <div class="large-12 columns location">
                                <p class=""><i class="fi-marker"></i>London</p>
                            </div>
                        </div>
                    </a>
                </li>
<div class="full_testimonial pleasecycle">
<a class="close" href="#"><img alt="close" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/close-icon.png"></a>
    <img alt="pleasecycle logo" class="cl_logo" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/pls.png">
<div class="clear"></div>
<b>Background</b>
Pleasecycle is a small, growing tech start-up business, providing tools to organisations to help their staff start cycling more, thereby reducing carbon and also cutting down sick-days through employee engagement. Due to their size and growth curve Pleasecycle were looking for an Account Manager to join their team in London.<br>
<b>What had they tried?</b>
Pleasecycle had already tried advertising on LinkedIn, but had found no success through that means, and were keen to look at a more proactive and niche approach, which they felt The Sales Floor could offer.<br>
<b>What We Provided</b>
The Sales Floor set up Pleasecycle with Logins within 10 minutes of the initial phone call. Due to their small size Pleasecycle also asked whether The Sales Floor could manage the entire process; uploading the vacancy to the site, filtering applications etc., and we duly obliged. Once the vacancy was uploaded, Pleasecycle’s vacancy was posted across LinkedIn, LinkedIn Groups, our own groups, and Twitter 5-10 times a day. We also provided them a shortlist of individuals from our network to contact should they see fit.<br>
<b>The Result</b>
Within a week the soon-to-be successful candidate applied to the role, found through our social media campaign. Pleasecycle have since taken him on board.<br>
<b>What they had to say</b>
“We started working with The Sales Floor team not long after their launch, and found them personal, professional and very different to other job board offerings out there.<br>
For a start they actually are a Sales Specialist board- helping us focus on some of the key criteria we looked for in a sales person.<br>
Despite their size and early days, we found a relevant application within 5 days with 1 in 3 applications being relevant to our role.<br>
One of the relevant applications turned into a success for us, which we were incredibly pleased with given The Sales Floor’s early days as a job board.<br>
However we found them easy to use, always there when we needed them, and we wouldn’t hesitate to call upon them for our next sales requirement.”<br>
Ronan Carter, Director
</div>

                <li class="large-4 columns">
                    <a data-target="four" class="row block" href="#">
                            <div class="featured_logo">
                                <img alt="four logo" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/fr.jpg">
                            </div>
                        <div class="row featured_content">
                            <h3 class="job-title">
                                <p class="job-company"> “I initially contacted The Sales Floor to help us with an urgent need for a quick hire, I was 

very impressed with the results

Within hours I had been provided with a substantial list of potential target candidates...<i>more</i></p>
                            </h3>
                        </div>

                        <div class="featured_extra">
                            <div class="large-12 columns location">
                                <p class=""><i class="fi-marker"></i>London</p>
                            </div>
                        </div>
                    </a>
                </li>
<style type="text/css">
b{
    margin:10px 0px;
    display: block;
}
</style>
<div class="full_testimonial four">
<a class="close" href="#"><img alt="close" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/close-icon.png"></a>
    <img alt="four logo" class="cl_logo" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/fr.jpg">
<div class="clear"></div>
<b>Background</b>

FOUR magazine is part of a growing publishing house in London. They are a food magazine, 

with high standards, and they also sell advertising space to high-end luxury brands. They 

had a growing requirement for high volumes of sales executives in their London office. <br>

<b>What had they tried?</b>

FOUR had previously tried using recruitment agencies, but had found them poor at 

delivering the right calibre of candidate, and too expensive given they were dealing with 

junior hires. They wanted an option that would be more cost effective. <br>

<b>What We Provided</b>

Within an hour of our initial discussion we had FOUR set up on the site, and their vacancy 

posted across our social media platforms; LinkedIn, LinkedIn groups, our own groups, and 

Twitter. We provided a shortlist of hand-picked profiles from people in our LinkedIn network 

for them to contact, which they duly did. <br>

<b>The Result</b>

Within a few days 4-5 suitable candidates had applied, and were invited to interview. One 

was hired after their first interview, with a further 3 kept in the process for additional 

headcount they had, meaning a possibility of 4 hires from just one advert. <br>

<b>What they had to say</b>

“I initially contacted The Sales Floor to help us with an urgent need for a quick hire, I was 

very impressed with the results

Within hours I had been provided with a substantial list of potential target candidates. On top 

of that the responses from our placed advertisement were almost all suitable for the role, 

with all but one being called for interview. All of the candidates were of high calibre and we 

ended up hiring one on the spot.

We managed to fill our role within two weeks from introduction to The Sales Floor to the 

applicant accepting the position.<br>

Professional, reliable and very affordable”<br>

 

James Harpum, Financial Controller
</div>
                <li class="large-4 columns">
                    <a data-target="informa" class="row block" href="#">
                            <div class="featured_logo">
                                <img alt="informa logo" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/esi.gif">
                            </div>
                        <div class="row featured_content">
                            <h3 class="job-title">
                                <p class="job-company"> “We decided to partner with The Sales Floor for a UK Sales requirement we had early in 

2014. We found them straightforward, incredibly helpful, and quick to respond when we 

needed them. They combine traditional ...<i>more</i></p>
                            </h3>
                        </div>

                        <div class="featured_extra">
                            <div class="large-12 columns location">
                                <p class=""><i class="fi-marker"></i>London</p>
                            </div>
                        </div>
                    </a>
                </li>
<div class="full_testimonial informa">
<a class="close" href="#"><img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/close-icon.png"></a>
    <img alt="informa logo" class="cl_logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/esi.gif">
<div class="clear"></div>

<b>Background</b>

ESI International, part of the Informa Group, is a world renowned provider of Training 

solutions in the corporate world. Their Project Management courses are used by over 35% 

of the world’s largest companies, in over 100 countries. ESI had a requirement in the UK for 

a Business Development Manager to win new business in that region. <br>

<b>What had they tried?</b>

ESI tried a number of traditional methods, as well as solutions such as LinkedIn posts, and 

adverts, along with their recruiter license. However they found they were not receiving the 

right applications, and needed a solution that was going to give wider reach, and cover all 

avenues. <br>

<b>What We Provided</b>

The Sales Floor had ESI International up and running within hours, ensuring their role went 

live immediately, and then set about setting up traditional campaigns, but also social media 

campaigns for their vacancy. Crucially, within 24 hours The Sales Floor returned a list of 

potential ‘passive’ candidates to ESI, for them to reach out to directly. <br>

<b>The Result</b>

The Sales Floor provided a steady stream of active candidates through the application 

process, and from the various campaigns set up on the likes of Indeed.com, and 

Adzuna.co.uk, as well as their Partner network of over 100 other job boards, however ESI 

eventually hired someone from the list of ‘passive’ candidates sent by The Sales Floor, 

saving them costly agency fee’s, and ensuring they were able to reach out to the strongest, 

most desirable candidates on the market. <br>

<b>What they had to say</b>

“We decided to partner with The Sales Floor for a UK Sales requirement we had early in 

2014. We found them straightforward, incredibly helpful, and quick to respond when we 

needed them. They combine traditional job board methods with some great unique services, 

and it was from these additional services that we actually ended up hiring someone. We’d 

recommend them to anyone else looking for a new approach to finding sales people, and 

look forward to working with them in the future. “<br>

 

Mary Johnson, Talent Acquisition Manager (ESI International, part of Informa Group)
</div>
                <li class="large-4 columns">
                    <a data-target="bg" class="row block" href="#">
                        <div class="featured_logo">
                            <img alt="british gas logo" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/bg.png">
                        </div>
                        <div class="row featured_content">
                            <h3 class="job-title">
                                <p class="job-company"> “Our relationship with The Sales Floor has so far resulted in us hiring three candidates into call centre 

and field sales roles. 

We are also still considering candidates for a couple...<i>more</i></p>
                            </h3>
                        </div>

                        <div class="featured_extra">
                            <div class="large-12 columns location">
                                <p class=""><i class="fi-marker"></i>London</p>
                            </div>
                        </div>
                    </a>
                </li>
<div class="full_testimonial bg">
    <a class="close" href="#"><img src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/close-icon.png"></a>
        <img alt="british gas logo" class="cl_logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/bg.png">
    <div class="clear"></div>
    <b>Background</b>

    British Gas have a cyclical pattern of high levels of recruitment throughout the year, which see them 

    needing to run high volume campaigns across the country on a regular basis. Using recruitment 

    agencies would ultimately prove too costly for the volumes they have to deal with, and therefore direct 

    advertising is usually their preferred option. <br>

    <b>What had they tried?</b>

    British Gas have used many forms of advertising in the past; LinkedIn, as well as most job boards in 

    the generalist and specialist space, however they have never found much success with other niche 

    sales boards, and the generalist boards were not performing for their sales requirements, and hence 

    they needed to try something new. <br>

    <b>What We Provided</b>

    The Sales Floor provided British Gas with a branded campaign, including multiple roles advertised 

    across the country, ranging from low-level, high volume hires to more senior professional hires. British 

    Gas were set up with tailored, targeted campaigns across Google, Indeed.com and Adzuna, ensuring 

    a high level of traffic to the volume roles they needed to fill. For the professional hires, The Sales 

    Floor set up social media campaigns, run across LinkedIn and Twitter, targeting a more senior, 

    passive audience, as well as providing shortlists of passive candidates from our own networks for 

    British Gas to reach out to. <br>

    <b>The Result</b>

    Within 3 weeks of running the campaign over the Christmas period, British Gas had received on 

    average 200 applications per week to their jobs. They set up numerous interviews across all their 

    roles, and have so far hired 3 candidates, with more in the interview process still being considered 

    too. <br>

    <b>What they had to say</b>

    “Our relationship with The Sales Floor has so far resulted in us hiring three candidates into call centre 

    and field sales roles. 

    We are also still considering candidates for a couple of senior sales roles, so there may be more 

    success to follow! <br>

    The team were extremely helpful from day one, and have worked hard behind the scenes to generate 

    interest in our roles from a relevant candidate audience. 

    If you are looking for a new sales specific job board, then The Sales Floor is a great new addition to 

    the marketplace.”<br>

    Helen Butterworth, Group Attraction and Diversity.
</div>
                <li class="large-4 columns">
                    <a data-target="Paymentsense" class="row block" href="#">
                            <div class="featured_logo">
                                    <img alt="Paymentsense logo" class="cl_logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/PaymentSense-Logo.png">
                            </div>
                        <div class="row featured_content">
                            <h3 class="job-title">
                                <p class="job-company">“We’ve been using The Sales Floor since 2014 and their job adverts consistently turn in to hires for us. They’ve helped us reduce our cost-per-hire and I highly recommend them to anyone looking to ...<i>more</i></p>
                            </h3>
                        </div>

                        <div class="featured_extra">
                            <div class="large-12 columns location">
                                <p class=""><i class="fi-marker"></i>London</p>
                            </div>
                        </div>
                    </a>
                </li> 
<div class="full_testimonial Paymentsense">
    <a class="close" href="#"><img alt="close" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/close-icon.png"></a>
        <img <img alt="Paymentsense logo" class="cl_logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/PaymentSense-Logo.png">
    <div class="clear"></div>
<b>Background</b>
Paymentsense is a leader in the Financial Services and Merchant Provider space. They have clients all over the UK both big and small, and their sales team is heavily field based. They traditionally used to use recruitment agencies, but the volume of recruitment they have gone through in the past year has meant they needed to focus on reducing their cost-per-hire.
<br>
<b>What had they tried?</b>
Paymentsense already used a number of other job boards to start fulfilling their requirements, which was working well enough for them, but they were still looking to increase their volume of hires on a monthly basis, and also wanted to improve their brand awareness across social media.
<br>
 
<b>What we provided</b>
<br>
The Sales Floor provides regular monthly campaigns across the likes of Indeed.com, Facebook and our partner network of job boards to ensure a strong volume of applications to their field sales positions. We set up targeted, branded adverts across specific locations on the likes of Facebook and LinkedIn, which attracts a different calibre of candidate to the more traditional job board, and helps Paymentsense increase their brand awareness at the same time.
 <br>
<b>The Result</b>
Since 2014 Paymentsense have hired multiple Field Sales Executives every single month off The Sales Floor. They have steadily increased their volume of posts each month since first being introduced and are now working with us to further increase their volume of hires into 2015.
 <br>
 
<b>What they had to say</b>
“We’ve been using The Sales Floor since 2014 and their job adverts consistently turn in to hires for us. They’ve helped us reduce our cost-per-hire and I highly recommend them to anyone looking to advertise for sales people”
 
</div>
                <li class="large-4 columns">
                    <a class="row block" data-target="esp" href="#">
                            <div class="featured_logo">
                                        <img class="cl_logo" alt="esp logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/esp.png">

                            </div>
                        <div class="row featured_content">
                            <h3 class="job-title">
                                <p class="job-company">“We used The Sales Floor for the first time after struggling to find someone through our usual channels, and it proved immensely effective. Their targeted focus on Social Media provided us with a selection ...<i>more</i></p>
                            </h3>
                        </div>

                        <div class="featured_extra">
                            <div class="large-12 text-center columns location">
                                <p class=""><i class="fi-marker"></i>London</p>
                            </div>
                        </div>
                    </a>
                </li> 

                <li class="large-4 columns">
                    <a class="row block" data-target="channel" href="#">
                            <div class="featured_logo">
                                        <img class="cl_logo" alt="channel assist logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/channel.jpg">

                            </div>
                        <div class="row featured_content">
                            <h3 class="job-title">
                                <p class="job-company">“The Sales Floor supported us in a recent recruitment campaign. Their approach to advertising was tailored to our requirements (a refreshing approach!) and their additional ‘behind the scenes’ ...<i>more</i></p>
                            </h3>
                        </div>

                        <div class="featured_extra">
                            <div class="large-12 text-center columns location">
                                <p class=""><i class="fi-marker"></i>London</p>
                            </div>
                        </div>
                    </a>
                </li> 

                <li class="large-4 columns">
                    <a class="row block" data-target="sortrefer" href="#">
                            <div class="featured_logo">
                                        <img class="cl_logo" alt="sortrefer logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/sortrefer.jpg">

                            </div>
                        <div class="row featured_content">
                            <h3 class="job-title">
                                <p class="job-company">“I was sceptical of spending the money on The Sales Floor initially but I am glad that I took the chance as we have made an excellent appointment thanks to their services.  Next time we are recruiting we will be  ...<i>more</i></p>
                            </h3>
                        </div>

                        <div class="featured_extra">
                            <div class="large-12 text-center columns location">
                                <p class=""><i class="fi-marker"></i>London</p>
                            </div>
                        </div>
                    </a>
                </li> 
                <li class="large-4 columns">
                </li> 
            </div>

<div class="full_testimonial sortrefer">
    <a class="close" href="#"><img alt="close" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/close-icon.png"></a>
        <img alt="sortrefer logo"  class="cl_logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/sortrefer.jpg">
    <div class="clear"></div>
<b>Background</b>
SortRefer is a Derby-based company that works with financial advisers and mortgage brokers throughout the UK, helping them meet the evolving needs of a diverse range of clients. Because of the markets they operate in, when they recruit, they require sales people with very specific experience selling into specific markets.<br>
 
<b>What had they tried?</b>

SortRefer were already using a number of other channels to advertise their role, including industry magazines, but needed to improve the volume of quality applications they were receiving, given they were recruiting for a few positions.<br>
<b>What we provided</b>
The Sales Floor set up branded campaigns across multiple job boards, aside from our own, and due to the specific nature of the search parameters on our site, were able to set up the role to ensure it was searchable based on the industries that applicants had been selling into- Mortgage Brokers, IFAs and Estate Agents.
<b>The Result</b>
SortRefer whittled applications from The Sales Floor down to 3 interviews, 1 of which resulted in a successful hire for them at the start of 2015. <br>
 
<b>What they had to say</b>
“I was sceptical of spending the money on The Sales Floor initially but I am glad that I took the chance as we have made an excellent appointment thanks to their services.  Next time we are recruiting we will be using them again – very good value.”<br>
James Boyles, Director
 
</div>

<div class="full_testimonial channel">
    <a class="close" href="#"><img alt="close" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/close-icon.png"></a>
        <img alt="channel assist logo"  class="cl_logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/channel.jpg">
    <div class="clear"></div>
<b>Background</b>
Channel Assist is a sales and marketing agency with a proven track record of maximising product sales, both in-store and online, for some of the leading technology and consumer electronics brands in the UK. When they recruit they either have to run very high volume campaigns or very specific campaigns around more senior hires.<br>
<b>What had they tried?</b>
Due to their high volumes of recruitment throughout the year, Channel Assist do need to utilise a number of different avenues, but given the specific nature of the roles they were trying to fill when we first spoke, their usual channels weren’t proving enough.<br>
 
<b>What we provided</b>
The Sales Floor set up a branded campaign, run across our own site, partner sites, including LinkedIn, as well as targeted Social Media advertising to sales people within the Field Marketing industry. We ran proactive sourcing for them on all their vacancies, meaning within 24 hours, they had a large number of passive candidates to connect and engage with. <br>
<b>The Result</b>
Applications were whittled down to 4 interviews, 1 of which turned into a successful hire for Channel Assist. As an added bonus, through the proactive sourcing work we provided, the Recruitment Manager was left with some very strong candidates he was able to talent pool for future roles.
 <br>
 
<b>What they had to say</b>
“The Sales Floor supported us in a recent recruitment campaign. Their approach to advertising was tailored to our requirements (a refreshing approach!) and their additional ‘behind the scenes’ work has meant we can talent-pool individuals for the future. We have already made a hire as a direct result and will certainly use them again!”<br>
Rob Lipscombe, Recruitment Manager
 
</div>


<div class="full_testimonial esp">
    <a class="close" href="#"><img alt="close" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/close-icon.png"></a>
        <img alt="esp logo"  class="cl_logo"  src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/esp.png">
    <div class="clear"></div>
<b>Background</b>
Journeycall, part of the ESP Group is a contact centre expert in the travel and smartcard industries. Given their expertise within this space, when they do hire, they look for very specific skills and experience with regards to which markets candidates have sold into; namely transportation and rail. They enlisted the help of The Sales Floor in 2014 looking to hire a Business Development Manager.
<br>
<b>What had they tried?</b>
Journeycall had tried posting an advert on LinkedIn, with very little success, as well as trying to fill their role via usual methods, however they found that there weren’t receiving the right quality of candidates for the role in question. They needed a more proactive, targeted approach to finding applicants.
<br>
 
<b>What we provided</b>
Under our Gold Package, Journeycall received a branded advert on The Sales Floor’s own platform, as well as distribution of their job to other key job boards and aggregators in the UK. Within 24 hours, The Sales Floor carried out their proactive sourcing service for Journeycall, returning between 25-30 candidates from social media, hand-picked by our team, for Journeycall to reach out to and engage with directly. We also provided targeted advertising across LinkedIn- Setting up a branded advert only to be shown to Sales people, within London, who had sold into transportation and rail.
 <br>
<b>The Result</b>
Within a few weeks, Journeycall had contact all the candidates provided via the proactive sourcing service, and off the back of that, had set up a number of interviews. One candidate was successful in being offered the role, and started soon afterwards, saving Journeycall thousands of pounds in recruitment fees.

 <br>
 
<b>What they had to say</b>
“We used The Sales Floor for the first time after struggling to find someone through our usual channels, and it proved immensely effective. Their targeted focus on Social Media provided us with a selection of high calibre candidates and more importantly, their proactive sourcing service proved the ultimate difference, with us hiring one of the people they found for us, soon after our campaign went live. A highly proactive, and cost effective alternative to traditional agencies and job boards!  I would definitely use The Sales Floor again in the future.”
<br>
Lesley Stewart- Business Development Director
 
</div>
            <div class="clear"></div>
        </div>

    </section>

    <section id="more" class="more_clients large-12 columns">
        <h2 class="text-center">Some more clients </h2>
        <div class="filters large-12 columns">
            <div class="wpjb-filters">
                <ul class="wpjb-filter-list">
                    <li class="wpjb-top-filter">
                        <a data-filter="all" class="" href="#">
                             All &#32;&#x25BE;
                        </a>
                        <ul class="wpjb-sub-filters">
                            <li><a href="" data-filter="recruitment">Recruitment</a></li>
                            <li><a href="" data-filter="media">Media</a></li>
                            <li><a href="" data-filter="automotive">Automotive</a></li>
                            <li><a href="" data-filter="retail">Retail</a></li>
                            <li><a href="" data-filter="energy">Energy / Environmental</a></li>
                            <li><a href="" data-filter="it">IT</a></li>
                            <li><a href="" data-filter="food">FMCG</a></li>
                            <li><a href="" data-filter="industrial">Industrial / Facilities</a></li>
                            <li><a href="" data-filter="training">Training / Qualifications</a></li>
                            <li><a href="" data-filter="is">Information Services</a></li>
                            <li><a href="" data-filter="finance">Finance</a></li>
                            <li><a href="" data-filter="events">Conferencing/Events</a></li>
                            <li><a href="" data-filter="logistics">Logistics</a></li>
                            <li><a href="" data-filter="other">Other</a></li>

                        </ul><!-- .wpjb-sub-filters -->
                    </li><!-- .wpjb-top-filter .wpjb-all-jobs-filter -->
                </ul><!-- .wpjb-filter-list -->
            </div><!-- .wpjb-filters -->
        </div>
    

        <div class="clients_imgs large-12 columns">
            <img data-target="recruitment" alt="saleslogic" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/saleslogic.png">
            <img data-target="recruitment" alt="hs" class="large-2 medium-2 small-4 columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/hs.png">
            <img data-target="recruitment" alt="mp" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/mp.png">
            <img data-target="recruitment" alt="ha" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ha.png">
            <img data-target="recruitment" alt="ha" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/cd.png">

            <img data-target="automotive" alt="jaguar logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/jag.jpg">
            <img data-target="automotive" alt="bmw logo" class="large-2 medium-2 small-4 columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/bmw.jpg">
            <img data-target="automotive" alt="innout logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/innout.jpg">


            <img data-target="media" alt="sky logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/sky.png">
            <img data-target="media" alt="hm" class="large-2  medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/hm.png">
            <img data-target="media" alt="incisive media logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/icm.jpg">
            <img data-target="media" alt="contagious logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/con.jpg">
            <img data-target="media" alt="macmillan logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/mac.jpg">
            <img data-target="media" alt="informa logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/infor.jpg">
            <img data-target="media" alt="taylor and francis logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/tf.jpg">
            <img data-target="media" alt="taboola logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/tab.jpg">
            <img data-target="media" alt="aptitude media logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/apm.jpg">
            <img data-target="media" alt="hibu logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/hibu.jpg">
            <img data-target="media" alt="emap logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/emap.jpg">


            <img data-target="it" alt="fibaro logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/fib.jpg">
            <img data-target="it" alt="epsilon logo" class="large-2  medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/eps.jpg">
            <img data-target="media" alt="modcomp logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/mod.jpg">
            <img data-target="it" alt="navisite logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/nav.jpg">
            <img data-target="it" alt="efolder logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/efol.jpg">
            <img data-target="it" alt="bytes logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/bytes.jpg">
            <img data-target="it" alt="version1 logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ve.jpg">
            <img data-target="it" alt="claranet logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/cla.jpg">
            <img data-target="it" alt="ntt com security media logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ntt.jpg">
            <img data-target="it" alt="pleasecycle logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/please.jpg">
            <img data-target="it" alt="journeycall logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/journ.jpg">
            <img data-target="it" alt="emis logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/emis.jpg">
            <img data-target="it" alt="hp logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/hp.jpg">
            <img data-target="it" alt="verizon logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/verizon.jpg">
            <img data-target="it" alt="genesys logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/gen.jpg">
            <img data-target="it" alt="workday logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/wok.jpg">
            <img data-target="it" alt="ans logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ns.jpg">
            <img data-target="it" alt="ntware logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ntw.jpg">
            <img data-target="it" alt="netabse logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/net.jpg">
            <img data-target="it" alt="zaizi logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/zaizi.jpg">
            <img data-target="it" alt="Ensighten logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ens.jpg">
            <img data-target="it" alt="tectrade logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/tectrade.jpg">



            <img data-target="retail" alt="harrods logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/harrods.jpg">
            <img data-target="retail" alt="dfs logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/dfs.jpg">
            <img data-target="retail" alt="sofa and chair company logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/sfc.jpg">
            <img data-target="retail" alt="thomas cook logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/tcg.jpg">

            <img data-target="energy" alt="british gas logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/bg.jpg">
            <img data-target="energy" alt="mi generation logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/mi.jpg">
            <img data-target="energy" alt="compact and bale logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/cb.jpg">
            <img data-target="energy" alt="tameside energy logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/tes.jpg">

            <img data-target="food" alt="ringtons logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/rgt.jpg">
            <img data-target="food" alt="peters logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ptr.jpg">
            <img data-target="food" alt="united biscuits logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ub.jpg">
            <img data-target="food" alt="molson coors logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/mco.jpg">
            <img data-target="food" alt="hello fresh logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/hlf.jpg">
            <img data-target="food" alt="mondelez international logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/mdi.jpg">
            <img data-target="food" alt="diageo logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/dia.jpg">
            <img data-target="food" alt="bic logo" class="large-2  medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/bic.jpg">
            <img data-target="food" alt="britvic logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/brtv.jpg">
            <img data-target="food" alt="carlsberg logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/carl.jpg">
            <img data-target="food" alt="electrolux logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/electrolux.png">
            <img data-target="food" alt="cbg logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/cbg.jpg">


            <img data-target="industrial" alt="tfc logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/tfc.jpg">
            <img data-target="industrial" alt="hilti logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/hilti.jpg">
            <img data-target="industrial" alt="jewson logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/jew.jpg">
            <img data-target="industrial" alt="emerson logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/epm.jpg">
            <img data-target="industrial" alt="ncco logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ncco.jpg">
            <img data-target="industrial" alt="johnson controls logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/jc.jpg">
            <img data-target="industrial" alt="regus logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/regus.jpg">

            <img data-target="training" alt="lifetimee logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/life.jpg">
            <img data-target="training" alt="esi logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/esi.jpg">
            <img data-target="training" alt="digital marketing institute logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/dmi.jpg">
            <img data-target="training" alt="cipd logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/cipd.jpg">
            <img data-target="training" alt="bureau veritas logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/bv.jpg">
            <img data-target="training" alt="desire 2 learn logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/d2l.jpg">

            <img data-target="is" alt="gartner logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/gartner.jpg">
            <img data-target="is" alt="forres logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/forres.jpg">
            <img data-target="is" alt="thomson reuters logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/tr.jpg">
            <img data-target="is" alt="informa logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/informa.jpg">
            <img data-target="is" alt="markit logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/markit.jpg">
            <img data-target="is" alt="toluna logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/toluna.jpg">


            <img data-target="finance" alt="santander logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/santender.jpg">
            <img data-target="finance" alt="world first logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/wf.jpg">
            <img data-target="finance" alt="payment sense logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/pys.jpg">
            <img data-target="finance" alt="grouptrader logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/gpt.jpg">
            <img data-target="finance" alt="access pay logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/acp.jpg">
            <img data-target="finance" alt="experian logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/exp.jpg">
            <img data-target="finance" alt="sortrefer logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/sortrefer.jpg">


            <img data-target="events" alt="iqpc logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/iqpc.jpg">
            <img data-target="events" alt="lbcg logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/lbcg.jpg">
            <img data-target="events" alt="incisive media logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/im.jpg">
            <img data-target="events" alt="etc venues logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/etc.jpg">

            <img data-target="logistics" alt="ups logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ups.jpg">
            <img data-target="logistics" alt="ch robinson logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ch.jpg">
            <img data-target="logistics" alt="samskip logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/sam.jpg">
            <img data-target="logistics" alt="Kuehne + Nagel logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/kue.jpg">

            <img data-target="other" alt="office depot logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ofd.jpg">
            <img data-target="other" alt="manchester united logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/manutd.jpg">
            <img data-target="other" alt="img logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/img.jpg">
            <img data-target="other" alt="workplace giving uk logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/workplace.jpg">
            <img data-target="other" alt="ignition one logo" class="large-2 medium-2 small-4  columns" src="<?php echo get_template_directory_uri(); ?>/wpjobboard/images/clients/ignition.jpg">





        </div>

    </section>

<script type="text/javascript">
    $(".wpjb-filters a").click(function(event){
        event.preventDefault();
        var target = $(this).attr("data-filter");
        $(".wpjb-filters a").removeClass("active");
        $(this).addClass("active");
        if(target == "all"){
            $('.clients_imgs img').fadeIn(500);
        }else{
            $('.clients_imgs img').fadeOut(500).promise().done(function(){
                $("[data-target="+target+"]").fadeIn(500);
            });
        }

    });

    $(".block").click(function(event){
        event.preventDefault();
        event.stopPropagation(); // This is the preferred method.
        var tar = $(this).attr("data-target");
        $('.full_testimonial').fadeOut(500).promise().done(function(){
            $("."+tar+"").fadeIn(500);
        });
    });
    function hide(){
        $('.full_testimonial').fadeOut(500);
    };
    $(".close").click(function(event){
        event.preventDefault();
        hide();
    })
    $(document).mouseup(function (e)
    {
        var container = $(".full_testimonial");

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            hide();
        }
    });
    $(document).keyup(function(e) {

        if (e.keyCode == 27) { hide(); }   // esc
    });
</script>
<?php get_footer() ?>


