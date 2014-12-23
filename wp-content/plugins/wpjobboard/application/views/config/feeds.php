<div class="wrap">
    <h2><?php _e("Aggregators and RSS Feeds", "wpjobboard") ?> </h2>

</div>

<div id="dashboard-widgets-wrap">
    

<div class="clear">&nbsp;</div>

    <div class="metabox-holder columns-2" id="dashboard-widgets">
        <div class="postbox-container">
            
            <div class="meta-box-sortables ui-sortable">
                <div class="postbox " id="">
                    <h3 class="hndle"><span><?php esc_html_e("Available Feeds", "wpjobboard") ?></span></h3>
                    <div class="inside" style="overflow:hidden">
                        
                        <div style="clear:both">
                        <div style="line-height: 30px"><?php esc_html_e("All", "wpjobboard") ?></div>
                        <input style="width:450px" type="text" value="<?php esc_attr_e(wpjb_link_to("feed", null, array("slug"=>"all"))) ?>" class="wpjb-rss-select" />
                        </div>
                        
                        <?php foreach(wpjb_get_categories() as $category): ?>
                        <div style="clear:both">
                        <div style="line-height: 30px"><?php echo $category->title ?></div>
                        <input style="width:450px" type="text" value="<?php esc_attr_e(wpjb_link_to("feed", $category)) ?>" class="wpjb-rss-select" />
                        </div>
                        <?php endforeach; ?>
  
                    </div>
                </div>
            </div>	
            

            
        </div>
          
        <div class="clear"></div>
        
        <div class="postbox-container">
            
            <div class="meta-box-sortables ui-sortable">
                <div class="postbox " id="">
                    <h3 class="hndle"><span><?php esc_html_e("Job Aggregators", "wpjobboard") ?></span></h3>
                    <div class="inside" style="overflow:hidden">
                        
                        <?php foreach($agg as $k => $v): ?>
                        <div style="clear:both">
                        <div style="float:left; width:150px; line-height: 30px"><?php echo esc_html($v) ?></div>
                        <input style="float:left; width:450px" type="text" value="<?php esc_attr_e(wpjb_link_to("api", null, array("engine"=>$k))) ?>" class="wpjb-rss-select" />
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>	
        </div>


    </div>

    <div class="clear"></div>
    
</div>

<script type="text/javascript">
jQuery(function($) {
    $("input.wpjb-rss-select").click(function() {
        this.select();
    });
});  
</script>