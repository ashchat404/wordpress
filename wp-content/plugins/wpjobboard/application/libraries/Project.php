<?php
/**
 * Description of Project
 *
 * @author greg
 * @package 
 */

class Wpjb_Project extends Daq_ProjectAbstract
{
    protected static $_instance = null;
    
    /**
     *
     * @var Wpjb_Utility_HelpScreen
     */
    public $helpScreen = null;
    
    /**
     *
     * @var Wpjb_Payment_Factory
     */
    public $payment = null;

    /**
     * Version is modified by build script.
     */
    const VERSION = "4.2.1";

    /**
     * Returns instance of self
     *
     * @return Wpjb_Project
     */
    public static function getInstance()
    {
        if(self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function run()
    {
        add_filter("no_texturize_tags", array($this, "nonoTags"));
        add_filter("wp_title", array($this, "injectTitle"));
        add_filter("single_post_title", array($this, "injectTitle"));
        add_filter('rewrite_rules_array', array($this, "rewrite"));
        add_action('generate_rewrite_rules', array($this, "generateRewriteRules"));
        add_filter('the_title', array($this, "theTitle"), 10, 2);
        add_filter('wp', array($this, "execute"));
        add_filter('query_vars', array($this, "queryVars"));
        add_filter('redirect_canonical', array($this, "redirectCanonical"));
        add_action('wp_enqueue_scripts', array($this, "addScriptsFront"), 20);
        add_action("admin_bar_menu", array($this, "adminBarMenu"), 1000 );
        add_filter("template_redirect", array($this, "templateRedirect"));
        
        add_action('deleted_user', array($this, "deletedUser"));
        
        add_filter("init", array($this, "init"));
        add_filter('init', array($this, "actions"), 15);
        add_action("admin_menu", array($this, "addAdminMenu"));
        add_action("admin_enqueue_scripts", array($this, "adminEnqueueScripts"));
        add_action("admin_print_scripts", array($this, "addScripts"));
        add_action('edit_post', array($this, "editPost"));
       
        // shortcodes 
        add_shortcode('wpjb_jobs_search', 'wpjb_jobs_search');
        add_shortcode('wpjb_jobs_list', 'wpjb_jobs_list');
        add_shortcode('wpjb_jobs_map', 'wpjb_jobs_map');
        add_shortcode('wpjb_resumes_search', 'wpjb_resumes_search');
        add_shortcode('wpjb_resumes_list', 'wpjb_resumes_list');
        add_shortcode('wpjb_apply_form', 'wpjb_apply_form');
        add_shortcode('wpjb_title', 'wpjb_title');
        
        // @depracated shortcodes !!!
        add_shortcode('wpjb_search', 'wpjb_jobs_search');
        
        // events
        add_action("wpjb_event_import", "wpjb_event_import");
        add_action("wpjb_event_expiring_jobs", "wpjb_event_expiring_jobs");
        add_action("wpjb_event_subscriptions_daily", "wpjb_event_subscriptions_daily");
        add_action("wpjb_event_subscriptions_weekly", "wpjb_event_subscriptions_weekly");
        
        /* add_action('wp_dashboard_setup', array(self::$_instance, "addDashboardWidgets")); */
        
        add_action("plugins_loaded", array("Wpjb_Utility_Yoast", "connect"), 20);
        add_action("plugins_loaded", array("Wpjb_Utility_Genesis", "connect"), 20);

        if(is_admin()) {
            Wpjb_Utility_WPSuperCache::connect();
            Wpjb_Utility_W3TotalCache::connect();
            
            Wpjb_Upgrade_Manager::connect(self::VERSION);
            
            if(wpjb_conf("version")) {
                Wpjb_Upgrade_Manager::update();
            }
            
            if(wpjb_conf("activation_message_hide", 0) == 0) {
                add_action("admin_notices", "wpjb_activation_message");
            }
        }
        
        // workarounds
        add_filter("wp_list_pages_excludes", array($this, "theTitleDisable"));
        add_filter("wp_list_pages", array($this, "theTitleEnable"));
        
        $this->_init();
        
        $this->getAdmin()->getView()->slot("logo", "settings.png");
        
        $this->payment = new Wpjb_Payment_Factory(array(
            new Wpjb_Payment_Credits,
            new Wpjb_Payment_PayPal,
            new Wpjb_Payment_Stripe
        ));
        $this->payment->sort();
        
        if(!is_admin()) {
            foreach((array)$this->conf("front_recaptcha_enabled") as $hook) {
                add_filter($hook, array($this, "recaptcha"));
            }
        }
        
        $linkedin_share = $this->conf("linkedin_share");
        $linkedin_apply = $this->conf("linkedin_apply");
        
        if($this->conf("posting_tweet")) {
            add_filter("wpjb_job_published", array("Wpjb_Service_Twitter", "tweet"));
        }
        if($this->conf("facebook_share")) {
            add_filter("wpjb_job_published", array("Wpjb_Service_Facebook", "share"));
        }
        if(isset($linkedin_share[0]) && $linkedin_share[0]==1) {
            add_filter("wpjb_job_published", array("Wpjb_Service_Linkedin", "share"));
        }
        if(isset($linkedin_apply[0]) && $linkedin_apply[0]==1) {
            add_action("wpjb_tpl_single_actions", array("Wpjb_Service_Linkedin", "apply"), 5);
            add_filter("wpjb_select_template", array("Wpjb_Service_Linkedin", "dispatch"));
            add_action("wpja_minor_section_apply", array("Wpjb_Service_Linkedin", "sectionApply"));
        }
        
        add_action("wpjb_job_published", "wpjb_mobile_notification_jobs");

    }
    
    public function theTitleDisable($x = null)
    {
        $this->_doTitle = false;
        return $x;
    }
    
    public function theTitleEnable($x = null)
    {
        $this->_doTitle = true;
        return $x;
    }

    public function init()
    {   
        global $wp, $wp_rewrite;

        if(!$this->conf("front_hide_bookmarks") && current_user_can("manage_resumes")) {
            add_action("wpjb_tpl_single_actions", array("Wpjb_Model_Shortlist", "displaySingleJob"), 5);
        }

        if($this->conf("count_date") != date_i18n("Y-m-d")) {
            $this->scheduleEvent();
        }
        
        $r = Daq_Request::getInstance();
        if($r->get("page")=="wpjb-config" && $r->get("action")=="edit" && $r->get("form")=="facebook" && !session_id()) {
            session_start();
        }
        if($this->conf("facebook_share") && $r->get("page")=="wpjb-job" && $r->get("action")=="add"  && !session_id()) {
            session_start();
        }
        
        if(!is_user_logged_in()) {
            wpjb_transient_id();
        }

        load_plugin_textdomain("wpjobboard", false, "wpjobboard/languages");
        
        wp_register_script('wpjb-js', plugins_url().'/wpjobboard/public/js/frontend.js', array("jquery"), self::VERSION );
        wp_register_style( 'wpjb-css', plugins_url()."/wpjobboard/public/css/frontend.css", array('wpjb-glyphs'), self::VERSION );
        wp_register_style( 'wpjb-glyphs', plugins_url()."/wpjobboard/public/css/wpjb-glyphs.css", array() );
        
        wp_register_script( "wpjb-suggest", plugins_url()."/wpjobboard/public/js/wpjb-suggest.js", array("jquery") );
        wp_register_script( "wpjb-color-picker", plugins_url()."/wpjobboard/application/views/jquery.colorPicker.js" );
        
        wp_register_script("wpjb-vendor-plupload", plugins_url()."/wpjobboard/application/vendor/plupload/plupload.full.js", array(), null, true);

        wp_register_script("wpjb-vendor-datepicker", plugins_url()."/wpjobboard/application/vendor/date-picker/js/datepicker.js");
        wp_register_style("wpjb-vendor-datepicker", plugins_url()."/wpjobboard/application/vendor/date-picker/css/datepicker.css");

        wp_register_script("wpjb-admin", plugins_url()."/wpjobboard/application/views/admin.js", array("jquery"));
        wp_register_script("wpjb-admin-job", plugins_url()."/wpjobboard/public/js/admin-job.js");
        wp_register_script("wpjb-admin-resume", plugins_url()."/wpjobboard/public/js/admin-resume.js");
        wp_register_script("wpjb-plupload", plugins_url()."/wpjobboard/public/js/wpjb-plupload.js", array("wpjb-vendor-plupload"), null, true);
    
        wp_register_script("wpjb-vendor-selectlist", plugins_url()."/wpjobboard/application/vendor/select-list/jquery.selectlist.pack.js", array("jquery"), null, true);
        
        wp_register_style("wpjb-admin-css", plugins_url()."/wpjobboard/application/views/admin.css");
        
        wp_register_script("wpjb-vendor-ve", plugins_url()."/wpjobboard/application/vendor/visual-editor/visual-editor.js", array("jquery"));
        wp_register_style("wpjb-vendor-ve-css", plugins_url()."/wpjobboard/application/vendor/visual-editor/visual-editor.css");
        
        wp_register_script("wpjb-vendor-stripe", "https://js.stripe.com/v2/");
        wp_register_script("wpjb-stripe", plugins_url()."/wpjobboard/public/js/wpjb-stripe.js", array("jquery", "wpjb-vendor-stripe"));
    }
    
    public static function scheduleEvent()
    {
        $select = new Daq_Db_Query();
        $select = $select->select("t2.tag_id AS `id`, COUNT(*) AS `cnt`");
        $select->from("Wpjb_Model_Job t1");
        $select->join("t1.tagged t2", "object = 'job'");
        $select->where("t1.is_active = 1");
        $select->where("t1.job_expires_at >= ?", date("Y-m-d"));
        $select->group("t2.tag_id");

        $all = array();
        
        foreach($select->fetchAll() as $r) {
            $all[$r->id] = $r->cnt;
        }

        $conf = self::getInstance();
        $conf->setConfigParam("count", $all);
        $conf->setConfigParam("count_date", date_i18n("Y-m-d"));
        $conf->saveConfig();
    }

    public function deletedUser($id)
    {
        foreach(array("Wpjb_Model_Company", "Wpjb_Model_Resume") as $class) {
            $query = new Daq_Db_Query();
            $result = $query->select()
                ->from("Wpjb_Model_Company t")
                ->where("user_id = ?", $id)
                ->limit(1)
                ->execute();

            if(isset($result[0])) {
                $object = $result[0];
                $object->delete();
            }
        }

    }

    public function addAdminMenu()
    {
        $ini = Daq_Config::parseIni(
            $this->path("app_config")."/admin-menu.ini",
            $this->path("user_config")."/admin-menu.ini",
            true
        );

        $ini = apply_filters("wpjb_admin_menu", $ini);
        
        $jLogo = plugins_url()."/wpjobboard/public/images/admin-icons/job_board_16x16_color.png";
        $cLogo = plugins_url()."/wpjobboard/public/images/admin-icons/settings_16x16px_color.png";
        
        $list = new Daq_Db_Query();
        $list->select("COUNT(*) as `cnt`");
        $list->from("Wpjb_Model_Application t");
        $list->where("status = 1");
        $applications = $list->fetchColumn();
        if(isset($ini["applications"]["page_title"])) {
            $warning = __("new applications", "wpjobboard");
            $ini["applications"]["menu_title"]  = $ini["applications"]["page_title"];
            $ini["applications"]["menu_title"] .= " <span class='update-plugins wpjb-bubble-applications count-$applications' title='$warning'><span class='update-count'>".$applications."</span></span>";
        }
        
        $pending = wpjb_find_jobs(array("filter"=>"awaiting", "count_only"=>true));
        if(isset($ini["jobs"]["page_title"])) {
            $warning = __("jobs awaiting approval", "wpjobboard");
            $ini["jobs"]["menu_title"]  = $ini["jobs"]["page_title"];
            $ini["jobs"]["menu_title"] .= " <span class='update-plugins wpjb-bubble-jobs count-$pending' title='$warning'><span class='update-count'>".$pending."</span></span>";
        }
        
        
        $query = new Daq_Db_Query();
        $query->select();
        $query->from("Wpjb_Model_Company t")->join("t.user u")->select("COUNT(*) AS cnt")->limit(1);
        $pending = $query->where("t.is_verified=?", Wpjb_Model_Company::ACCESS_PENDING)->fetchColumn();
        if(isset($ini["companies"]["page_title"])) {
            $warning = __("employers requesting approval", "wpjobboard");
            $ini["companies"]["menu_title"]  = $ini["companies"]["page_title"];
            $ini["companies"]["menu_title"] .= " <span class='update-plugins wpjb-bubble-companies count-$pending' title='$warning'><span class='update-count'>".$pending."</span></span>";
        }
         
         
        /*
        $query = new Daq_Db_Query();
        $query->select()->from("Wpjb_Model_Resume t")->join("t.users t2")->order("t.updated_at DESC");
        $query->select("COUNT(*) AS cnt")->limit(1);
        $pending = $query->where("t.is_approved=?", Wpjb_Model_Resume::RESUME_PENDING)->fetchColumn();
        if(isset($ini["resumes_manage"]["page_title"])) {
            $warning = __("resumes pending approval", "wpjobboard");
            $ini["resumes_manage"]["menu_title"]  = $ini["resumes_manage"]["page_title"];
            $ini["resumes_manage"]["menu_title"] .= "<span class='update-plugins wpjb-bubble-resumes count-$pending' title='$warning'><span class='update-count'>".$pending."</span></span>";
        }
        */
        
        //$this->helpScreen = new Wpjb_Utility_HelpScreen;
        
        foreach($ini as $key => $conf) {
            
            if(isset($conf['parent'])) {
                
                if(isset($conf["menu_title"])) {
                    $menu_title = $conf["menu_title"];
                } else {
                    $menu_title = $conf["page_title"];
                }
                
                $id = add_submenu_page(
                    "wpjb-".ltrim($ini[$conf['parent']]['handle'], "/"),
                    $conf['page_title'],
                    $menu_title,
                    $conf['access'],
                    "wpjb-".ltrim($conf['handle'], "/"),
                    array($this, "dispatch")
                );

                //$this->helpScreen->addPage($key, $id);
                // for future use (maybe 4.1)
                // add_action("load-$id", array($this->helpScreen, "load_".$key));
                
            } else {
                
                if($key == "job_board") {
                    $logo = $jLogo;
                } else {
                    $logo = $cLogo;
                }
                
                add_menu_page(
                    $conf['page_title'],
                    $conf['page_title'],
                    $conf['access'],
                    "wpjb-".ltrim($conf['handle'], "/"),
                    array($this, "dispatch"),
                    $logo,
                    $conf['order']
                );
            }
        }
    }

    public function adminEnqueueScripts($hook) 
    {
        if(!stripos($hook, "_wpjb-")) {
            return;
        }
        
        $js_date_max = wpjb_date(WPJB_MAX_DATE);
        $js_date_format = str_replace(array("J"), array("B"), wpjb_date_format());
        
        wp_enqueue_script("wpjb-admin");
        wp_enqueue_script("wpjb-vendor-selectlist");
        
        wp_enqueue_style("wpjb-admin-css");
        wp_localize_script("wpjb-plupload", "wpjb_plupload_lang", array(
            "dispose_message" => __("Click here to dispose this message", "wpjobboard"),
            "x_more_left" => __("%d more left", "wpjobboard"),
            "preview" => __("Preview", "wpjobboard"),
            "delete_file" => __("Delete", "wpjobboard")
        ));
        wp_localize_script("wpjb-admin", "wpjb_admin_lang", array(
            "date_format" => $js_date_format,
            "max_date" => $js_date_max,
            "confirm_item_delete" => __("Are you sure you want to delete this item?", "wpjobboard")
        ));
        wp_localize_script("wpjb-vendor-selectlist", "daq_selectlist_lang", array(
            "hint" => __("Select options ...", "wpjobboard")
        ));
        
        list($x, $page) = explode("_wpjb-", $hook);
        
        $request = Daq_Request::getInstance();
        $action = $request->get("action");
        
        if($page == "job" && in_array($action, array("add", "edit"))) {
            $lang = array(
                "date_format" => $js_date_format,
                "max_date" => $js_date_max,
                "free_listing" => __("None (free listing)", "wpjobboard"),
                "yesterday" => __("yesterday", "wpjobboard"),
                "immediately" => __("immediately", "wpjobboard"),
                "tomorrow" => __("tomorrow", "wpjobboard"),
                "day" => __("%d day", "wpjobboard"),
                "days" => __("%d days", "wpjobboard")
            );
            wp_enqueue_script("wpjb-admin-job");
            wp_enqueue_script("wpjb-vendor-datepicker");
            
            wp_enqueue_style("wpjb-vendor-datepicker");
            
            wp_localize_script("wpjb-admin-job", "wpjb_admin_job_lang", $lang);

        } elseif($page == "custom" && $action == "edit") {
            
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-draggable');
            wp_enqueue_script('jquery-ui-droppable');
            wp_enqueue_script('thickbox');
            
            wp_enqueue_script("wpjb-vendor-ve");
            wp_enqueue_style("wpjb-vendor-ve-css");
        } elseif($page == "jobType") {
            wp_enqueue_script("wpjb-color-picker", null, null, null, true);
        } elseif($page == "resumes") {
            $lang = array(
                "date_format" => $js_date_format,
                "max_date" => $js_date_max,
            );
            
            wp_enqueue_script("wpjb-admin-resume");
            wp_enqueue_script("wpjb-vendor-datepicker");
            wp_enqueue_style("wpjb-vendor-datepicker");
            
            wp_localize_script("wpjb-admin-resume", "wpjb_admin_resume_lang", $lang);
            
        } elseif($page == "import" && $action == "xml") {
            wp_enqueue_script("wpjb-vendor-plupload");
        } elseif($page == "memberships" || $page == "discount") {
            wp_enqueue_script("wpjb-vendor-datepicker");
            wp_enqueue_style("wpjb-vendor-datepicker");
        } 
    }
    
    public function addScripts()
    {
        $l10n = array(
            "slug_save" => __("save", "wpjobboard"),
            "slug_cancel" => __("cancel", "wpjobboard"),
            "slug_change" => __("change", "wpjobboard"),
            "remove" => __("Do you really want to delete", "wpjobboard"),
            "selectAction" => __("Select action first", "wpjobboard"),
            
        );
        
        wp_localize_script("wpjb-admin", "WpjbAdminLang", $l10n);

    }   
    
    public function enqueueScripts()
    {
        if(!is_wpjb() && !is_wpjr()) {
            return;
        }

        
 
    }
    
    public function addScriptsFront()
    {
        $js_date_max_o = new DateTime(WPJB_MAX_DATE);
        $js_date_max = $js_date_max_o->format(wpjb_date_format());
        $js_date_format = str_replace(array("J"), array("B"), wpjb_date_format());
        $object = array(
            "AjaxRequest" => Wpjb_Project::getInstance()->getUrl()."/plain/discount",
            "Protection" => Wpjb_Project::getInstance()->conf("front_protection", "pr0t3ct1on"),
            "no_jobs_found" => __('No job listings found', 'wpjobboard'),
            "no_resumes_found" => __('No resumes found', 'wpjobboard'),
            "load_x_more" => __('Load %d more', 'wpjobboard'),
            "date_format" => $js_date_format,
            "max_date" => $js_date_max
        );
        
        wp_localize_script("wpjb-js", "WpjbData", $object);
        wp_enqueue_style('wpjb-css');
        
        wp_localize_script("wpjb-vendor-selectlist", "daq_selectlist_lang", array(
            "hint" => __("Select options ...", "wpjobboard")
        ));
        
        wp_localize_script("wpjb-stripe", "wpjb_stripe", array(
            "payment_accepted" => __("Payment completed successfully.", "wpjobboard")
        ));
        
        wp_localize_script("wpjb-plupload", "wpjb_plupload_lang", array(
            "dispose_message" => __("Click here to dispose this message", "wpjobboard"),
            "x_more_left" => __("%d more left", "wpjobboard"),
            "preview" => __("Preview", "wpjobboard"),
            "delete_file" => __("Delete", "wpjobboard")
        ));

        if(!is_wpjb() && !is_wpjr()) {
            return;
        }
        
        wp_enqueue_script('wpjb-js');
        wp_enqueue_script("wpjb-vendor-selectlist");
        
        if(is_wpjb() && $this->router()->isRoutedTo("addJob.add")) {            
            wp_enqueue_script("wpjb-suggest");
        }
        

    }

    public function addDashboardWidgets()
    {
        if(!current_user_can("edit_dashboard")) {
            return;
        }

        wp_add_dashboard_widget('wpjb_dashboard_stats', __("Job Board Stats", "wpjobboard"), array("Wpjb_Dashboard_Stats", "render"));
    }

    public function install()
    {
        global $wpdb, $wp_rewrite, $wp_roles;
        
        if(stripos(PHP_OS, "win")!==false || true) {
            $mods = explode(",", $wpdb->get_var("SELECT @@session.sql_mode"));
            $mods = array_map("trim", $mods);
            $invalid = array(
                "STRICT_TRANS_TABLES", "STRICT_ALL_TABLES", "TRADITIONAL"
            );
            foreach($invalid as $m) {
                if(in_array($m, $mods)) {
                    $wpdb->query("SET @@session.sql_mode='' ");
                    break;
                }
            }
        }
        
        $db = Daq_Db::getInstance();
        if($db->getDb() === null) {
            $db->setDb($wpdb);
        }

        global $wp_roles;
        remove_role("employer");
        
        add_role("employer", "Employer", array("read"=>true, "manage_jobs"=>true));
        $wp_roles->add_cap("administrator", "manage_jobs");
        $wp_roles->add_cap("administrator", "manage_resumes");
        $wp_roles->add_cap("subscriber", "manage_resumes");
        
        wp_clear_scheduled_hook("wpjb_event_expiring_jobs");
        wp_schedule_event(current_time('timestamp'), "daily", "wpjb_event_expiring_jobs");
        
        wp_clear_scheduled_hook("wpjb_event_subscriptions_daily");
        wp_schedule_event(current_time('timestamp'), "hourly", "wpjb_event_subscriptions_daily");
        
        wp_clear_scheduled_hook("wpjb_event_subscriptions_weekly");
        wp_schedule_event(current_time('timestamp'), "hourly", "wpjb_event_subscriptions_weekly");

        $instance = self::getInstance();
        $appj = $instance->getApplication("frontend");
        $appr = $instance->getApplication("resumes");

        $config = $instance;
        
        if($appj->getPage() === null) {
            // link new page
            /* @var $appj Wpjb_Application_Frontend */
            $jId = wp_insert_post(array(
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_title' => 'Jobs',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_content' => $appj->getOption("shortcode")
            ));
            $config->setConfigParam("link_jobs", $jId);
            $config->saveConfig();
        }
        if($appr->getPage() === null) {
            // link new page
            $rId = wp_insert_post(array(
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_title' => 'Resumes',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_content' => $appr->getOption("shortcode")
            ));
            $config->setConfigParam("link_resumes", $rId);
            $config->saveConfig();
        }
        
        /* @var $wp_rewrite wp_rewrite */
        $wp_rewrite->flush_rules();

        if($this->conf("first_run")!==null) {
            return true;
        }

        $config->setConfigParam("first_run", 0);
        $config->setConfigParam("front_show_related_jobs", 1);
        $config->setConfigParam("show_maps", 1);
        $config->setConfigParam("cv_enabled", 1);
        $config->saveConfig();

        $file = $this->path("install") . "/install.sql";
        $queries = explode("; --", file_get_contents($file));

        foreach($queries as $query) {
            $query = str_replace('{$wpdb->prefix}', $wpdb->prefix, $query);
            $query = str_replace('{$wpjb->prefix}', $wpdb->prefix, $query);
            $wpdb->query($query);
        }

        $email = get_option("admin_email");
        $query =  new Daq_Db_Query();
        $result = $query->select("*")->from("Wpjb_Model_Email t")->execute();
        foreach($result as $r) {
            if($r->mail_from == "") {
                $r->mail_from = $email;
                $r->save();
            }
        }

        $config = Wpjb_Project::getInstance();
        $config->saveConfig();

        Wpjb_Upgrade_Manager::update();
        
        $manager = new Wpjb_Upgrade_Manager;
        $manager->version = self::VERSION;
        $manager->remote("version");

        return true;
    }

    public static function uninstall()
    {
        return true;
    }

    public function deactivate()
    {
        
    }


    public function adminBarMenu()
    {
        global $wp_admin_bar;
        
        if (!is_super_admin() || !is_admin_bar_showing()) {
            return;
        }

        if(is_wpjb() || is_wpjr()) {
            $wp_admin_bar->remove_menu("edit");
            $wp_admin_bar->remove_menu("comments");
        }
        
        if(is_wpjb() && $this->router()->isRoutedTo("index.single")) {
            $object = $this->getApplication("frontend")->controller;
            if(is_object($object)) {
                $object = $object->getObject();
                $wp_admin_bar->add_menu(array(
                    'id' => 'edit-job',
                    'title' => __("Edit Job", "wpjobboard"),
                    'href' => wpjb_admin_url("job", "edit", $object->id)
                ));
            }
        }
        
        if(is_wpjr() && $this->router("resumes")->isRoutedTo("index.view")) {
            $object = $this->getApplication("resumes")->controller;
            if(is_object($object)) {
                $object = $object->getObject();
                $wp_admin_bar->add_menu(array(
                    'id' => 'edit-resume',
                    'title' => __("Edit Resume", "wpjobboard"),
                    'href' => wpjb_admin_url("resumes", "edit", $object->id)
                ));
            }
        }

    }
    
    public function editPost($post_id)
    {
        global $wp_rewrite;
        
        foreach($this->_apps() as $app) {
            /* @var $app Daq_Application */
            $id = $this->conf($app->getOption("link_name"));
            if($id == $post_id) {
                $wp_rewrite->flush_rules();
            }
        }
    }
    
    public function recaptcha($form)
    {
        $form->addGroup("recaptcha", __("Captcha", "wpjobboard"), 1000);
        
        $e = $form->create("recaptcha_response_field");
        $e->setRequired(true);
        $e->addValidator(new Daq_Validate_Callback("wpjb_recaptcha_check"));
        $e->setRenderer("wpjb_recaptcha_form");
        $e->setLabel(__("Captcha", "wpjobboard"));
        
        $form->addElement($e, "recaptcha");
        
        if($form instanceof Wpjb_Form_Apply) {
            $form->removeElement("protection");
        }
        
        return $form;
    }

    public function generateRewriteRules() 
    {
        global $wp_rewrite;
        
        $non_wp_rules = array(
            '([_0-9a-zA-Z-]+/)?uploads/wpjobboard/application/(.+)' => 'wp-content/plugins/wpjobboard/restrict.php?url=application/$2'
        );
        $wp_rewrite->non_wp_rules = $non_wp_rules + $wp_rewrite->non_wp_rules;

    }
    
    public function templateRedirect($template)
    {
        $wpjb = get_query_var("wpjobboard");

        if($wpjb) {
            $this->getApplication("api")->dispatch($wpjb);
            exit;
        }

        return $template;
    }
    
    public function actions()
    {

        if(!isset($_POST["_wpjb_action"]) || !is_string($_POST["_wpjb_action"])) {
            return;
        }
        
        
        switch($_POST["_wpjb_action"]) {
            case "login":
                
                $form = new Wpjb_Form_Login();
                $user = $form->isValid(Daq_Request::getInstance()->post());
                $flash = new Wpjb_Utility_Session();
                
                if($user instanceof WP_Error) {
                    foreach($user->get_error_messages() as $error) {
                        $flash->addError($error);
                    }
                } elseif($user === false) {
                    $flash->addError(__("Incorrect username or password", "wpjobboard"));
                } else {
                    $flash->addInfo(__("You have been logged in.", "wpjobboard"));

                    $r = trim($form->value("redirect_to"));
                    if(!empty($r)) {
                        $redirect = $r;
                    } else {
                        $redirect = site_url();
                    }

                    // @todo: apply some filters maybe??
                    
                    $flash->save();
                    wp_redirect($redirect);
                    exit;
                }
                break;
            
            default:
                // do nothing
        }
    }
    
}





?>
