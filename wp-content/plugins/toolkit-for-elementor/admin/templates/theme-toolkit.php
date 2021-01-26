<?php
if( !function_exists('theme_toolkit_settings_display')) {
    function theme_toolkit_settings_display(){
        ob_start();
        if( is_toolkit_for_elementor_activated() ){
            $header_code = get_option('theme_disable_header_code', '');
            $footer_code = get_option('theme_disable_footer_code', '');
            $bodytag_code = get_option('theme_disable_bodytag_code', '');  ?>
            <div class="" id="theme-toolkit-themeless">
                <div class="tabs-holder">
                    <div class="tab-nav">
                        <ul>
                            <li class="active-tab" data-tabid="inshdft-tab">
                                <span class="title-text">
                                    <?php _e('Code Manager'); ?><br>
                                    <small><?php _e('Insert Code Into Header & Footer'); ?></small>
                                </span>
                                <img alt="" src="<?php echo TOOLKIT_FOR_ELEMENTOR_URL . "admin/images/icon-ihf.png"; ?>">
                            </li>
                            <li data-tabid="custom-tab">
                                <span class="title-text">
                                    <?php _e('Manage Custom Code'); ?><br>
                                    <small><?php _e('Using Code Snippets + Themeless'); ?></small>
                                </span>
                                <img alt="" src="<?php echo TOOLKIT_FOR_ELEMENTOR_URL . "admin/images/icon-settings.png"; ?>">
                            </li>
                        </ul>
                    </div>
                    <div class="content-tab">
                        <div class="single-tab" id="inshdft-tab">
                            <table class="widefat">
                                <tr>
                                    <td>
                                        <label for="theme_disable_header"><b><?php _e('Insert'.' Code Into Header'); ?></b></label>
                                        <br>
                                        <textarea rows="8" class="wd-100" id="theme_disable_header"><?php echo str_replace('\"','"', $header_code); ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="right-align">
                                        <button type="button" class="button toolkit-btn save-toolkit"><?php _e('Save Code'); ?></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="theme_disable_bodytag"><b><?php _e('Insert'.' Code After Opening Body Tag'); ?></b></label>
                                        <br>
                                        <textarea rows="8" class="wd-100" id="theme_disable_bodytag"><?php echo str_replace('\"','"', $bodytag_code); ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="right-align">
                                        <button type="button" class="button toolkit-btn save-toolkit"><?php _e('Save Code'); ?></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="theme_disable_footer"><b><?php _e('Insert'.' Code Into Footer'); ?></b></label>
                                        <br>
                                        <textarea rows="8" class="wd-100" id="theme_disable_footer"><?php echo str_replace('\"','"', $footer_code); ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="right-align">
                                        <button type="button" class="button toolkit-btn save-toolkit"><?php _e('Save Code'); ?></button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="single-tab" id="custom-tab" style="display:none;">
                            <table class="widefat">
                                <tr>
                                    <td>
                                        <h3>For users looking to implement custom code- especially when Themeless is enabled, we recommend using a code manager like the popular <a href="https://wordpress.org/plugins/code-snippets/">Code Snippets plugin</a>.
                                        <br /><br />This will allow you to easily insert, manage, and prioritize your custom code instead of editing your theme's function.php file (which if enabling Themeless, will no longer load).</h3>
					<br /><center><a href="http://bit.ly/codesnippets-toolkit"><img src="https://toolkitforelementor.com/download/tkcodesnippets.png" width="80%" title="ToolKit with Code Snippets" alt="Toolkit with Code Snippets"></center></a>
				   </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class='tab-fade'></div>
                </div>
            </div>
            <?php
        } else { ?>
            <div class="not-active-notice">
                <?php _e('Oops, looks like you do not have an active license yet, please activate your license first in the My License tab'); ?>
            </div>
        <?php }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
