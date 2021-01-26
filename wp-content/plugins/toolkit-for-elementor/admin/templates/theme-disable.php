<?php
if( !function_exists('theme_disable_settings_display')) {
    function theme_disable_settings_display(){
        ob_start();
        if( is_toolkit_for_elementor_activated() ){
            $disable = get_option('theme_disable_themeless', 'no');
            ?>
            <div class="bg-white" id="theme-disable-themeless">
                <div class="tab-holder">
                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-4 text-center">
                            <img alt="<?php _e('Disable Theme Framework'); ?>" src="<?php echo TOOLKIT_FOR_ELEMENTOR_URL . "admin/images/themeless-logo.png"; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <p><?php _e("When THEMELESS is enabled, the WP Theme Framework is disabled and your theme's CSS & JS files are not loaded on the frontend.
							<br /><br />As of Elementor v2.9, Theme Styling was introduced as a new framework for Global Styles. Themeless works beautifully with Elementor's Global Styling and Theme Builder- while dequeuing your Theme's CSS & JS files and reducing bloat. As Elementor continues to improve their Global Styling framework, going Themeless becomes easier and easier!"); ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-4 text-center">
                            <p><b><?php _e('Disable Theme Framework'); ?></b></p>
                            <div class="switch-container">
                                <label class="sw-outside"><span><?php _e('No'); ?></span></label>
                                <div class="switch">
                                    <?php $checked = ($disable == 'yes') ? 'checked' : ''; ?>
                                    <input id="theme_disable_themeless" name="theme_disable_themeless" type="checkbox" value="yes" <?php echo $checked; ?>>
                                    <label for="theme_disable_themeless"></label>
                                </div>
                                <label class="sw-outside"><span><?php _e('Yes'); ?></span></label>
                            </div>
                            <br><br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-center">
                            <p><b><?php _e('Designed for Elementor Pro'); ?></b></p>
                            <p><?php _e("Elementor Pro users will be able to get started right away by using Elementor's powerful Theme Builder framework."); ?></p>
                            <p><?php _e("ToolKit also works with Elementor Free but is not recomended as Free users do not have access to Elementor's Theme Builder."); ?></p>
                        </div>
                        <div class="col-sm-4 text-center">
                            <p><b><?php _e('How Do I Make CSS Changes?'); ?></b></p>
                            <p><?php _e("Elementor Free users do not have access the Custom CSS functionality, but may still use third party CSS managers with ToolKit."); ?></p>
                            <p><?php _e("Pro users may continue to use Elementor's Custom CSS areas, however for Free users, one of our favorite advanced CSS managers for WordPress (and compatible with Elementor + ToolKit) is CSSHero."); ?></p>
                            <p><a href="http://bit.ly/csshero-toolkit" target="_blank"><u><b><?php _e("Learn More about CSSHero"); ?></b></u><br></a></p>
                        </div>
                        <div class="col-sm-4 text-center">
                            <p><b><?php _e('How to Add/Manage Code Snippets?'); ?></b></p>
                            <p><?php _e("When you go THEMELESS, adding custom code to your theme's function.php file won't work as your theme files are not loaded."); ?></p>
                            <p><?php _e("Though there are quite a few Code Manager plugins out there, our team recommends using the amazing Code Snippets plugin as it works great with Elementor!"); ?></p>
                            <p><a href="http://bit.ly/codesnippets-toolkit" target="_blank"><u><b><?php _e("Learn about CodeSnippets"); ?></b></u><br></a></p>
                        </div>
                    </div>
                    <div class='tab-fade'></div>
                </div>
            </div>
            <?php
        } else { ?>
            <div class="not-active-notice">
                <?php _e('Oops, looks like you do not have an active license yet, please activate your license first in My License'); ?>
            </div>
        <?php }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
