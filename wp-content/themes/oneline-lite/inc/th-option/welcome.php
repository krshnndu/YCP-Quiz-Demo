
<!--- tab first -->
<div class="theme_link">
    <h3><?php _e('1. Install Recommended Plugins','oneline-lite'); ?></h3>
    <p><?php _e('We highly Recommend to install Themehunk Customizer plugin to get all customization options in Oneline Lite theme. Also install recommended plugins available in recommended tab.','oneline-lite'); ?></p>
</div>
<div class="theme_link">
    <h3><?php _e('2. Setup Home Page','oneline-lite'); ?><!-- <php echo $theme_config['plugin_title']; ?> --></h3>
        <p><?php _e('To set up the HomePage in Oneline Lite theme, Just follow the below given Instructions.','oneline-lite'); ?> </p>
<p><?php _e('Go to Wp Dashboard > Pages > Add New > Create a Page using “Home Page Template” available in Page attribute.','oneline-lite'); ?> </p>
<p><?php _e('Now go to Settings > Reading > Your homepage displays > A static page (select below) and set that page as your homepage.','oneline-lite'); ?> </p>
     <p>
        <?php
		if($this->_check_homepage_setup()){
            $class = "activated";
            $btn_text = __("Home Page Activated",'oneline-lite');
            $Bstyle = "display:none;";
            $style = "display:inline-block;";
        }else{
            $class = "default-home";
             $btn_text = __("Set Home Page",'oneline-lite');
             $Bstyle = "display:inline-block;";
            $style = "display:none;";


        }
        ?>
        <button style="<?php echo $Bstyle; ?>"; class="button activate-now <?php echo esc_attr($class); ?>"><?php echo esc_html($btn_text); ?></button>
		
         </p>
		 	 
		 
    <p>
        <a target="_blank" href="https://themehunk.com/docs/oneline-lite/#setup-homepage" class="button"><?php _e('Go to Doc','oneline-lite'); ?></a>
    </p>
</div>

<!--- tab third -->





<!--- tab second -->
<div class="theme_link">
    <h3><?php _e('3. Customize Your Website','oneline-lite'); ?><!-- <php echo $theme_config['plugin_title']; ?> --></h3>
    <p><?php _e('Oneline Lite theme support live customizer for home page set up. Everything visible at home page can be changed through customize panel','oneline-lite'); ?></p>
    <p>
    <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary"><?php _e("Start Customize","oneline-lite"); ?></a>
    </p>
</div>
<!--- tab third -->

  <div class="theme_link">
    <h3><?php _e("4. Customizer Links","oneline-lite"); ?></h3>
    <div class="card-content">
        <div class="columns">
                <div class="col">
                    <a href="<?php echo admin_url('customize.php?autofocus[control]=custom_logo'); ?>" class="components-button is-link"><?php _e("Upload Logo","oneline-lite"); ?></a>
                    <hr><a href="<?php echo admin_url('customize.php?autofocus[section]=section_hide'); ?>" class="components-button is-link"><?php _e("Section On/Off","oneline-lite"); ?></a><hr>
                    <a href="<?php echo admin_url('customize.php?autofocus[panel]=woocommerce'); ?>" class="components-button is-link"><?php _e("Woocommerce","oneline-lite"); ?></a><hr>

                </div>

               <div class="col">
                <a href="<?php echo admin_url('customize.php?autofocus[panel]=theme_optn'); ?>" class="components-button is-link"><?php _e("Theme Options","oneline-lite"); ?></a>
                <hr>

                <a href="<?php echo admin_url('customize.php?autofocus[panel]=services_panel'); ?>" class="components-button is-link"><?php _e("Service Section","oneline-lite"); ?></a><hr>


                 <a href="<?php echo admin_url('customize.php?autofocus[section]=blog_setting'); ?>" class="components-button is-link"><?php _e("Latest Post Section","oneline-lite"); ?></a><hr>
            </div>

        </div>
    </div>

</div>
<!--- tab fourth -->