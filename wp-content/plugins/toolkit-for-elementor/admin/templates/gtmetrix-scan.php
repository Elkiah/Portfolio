<?php
if ( ! function_exists('gt_metrix_settings_display') ) {
function gt_metrix_settings_display(){
    ob_start();
    if (is_toolkit_for_elementor_activated()) {
        global $wpdb;
        $obj = new Lazy_load_Settings();
        $scanHistory = $wpdb->get_row("SELECT" . " `test_id`,`scan_url`, `load_time`, `page_speed`, `yslow`,`browser`, `region`,`resources`,`response_log`, `created` FROM {$wpdb->prefix}toolkit_gtmetrix ORDER BY id desc", ARRAY_A);
        $region = 'N/A';
        $pageSpeed = 0;
        $ySlow = 0;
        $loadTime = 0;
        $requests = 0;
        $lastReportTime = 0;
        $browser = 0;
        $screenshot = 0;
        $scan_url = site_url();
        $pageSize = '0KB';
        $pageSpeedCode = array();
        $ySlowCode = array();
        $scanResult = array();
        $screenshotDefault = TOOLKIT_FOR_ELEMENTOR_URL . 'admin/images/gtscreen.png';
        $toolkit_uploads = WP_CONTENT_DIR . '/toolkit-reports/';
        $toolkit_uploads_url = get_option( 'siteurl' ) . '/wp-content/toolkit-reports/';
        if ($scanHistory) {
            $pageSpeed = $scanHistory['page_speed'];
            $loadTime = round($scanHistory['load_time'] / 1000, 2);
            $ySlow = $scanHistory['yslow'];
            $scanResult = json_decode($scanHistory['response_log'], true);
            $requests = $scanResult['page_elements'];
            $region = $scanHistory['region'];
            $browser = $scanHistory['browser'];
            $lastReportTime = $scanHistory['created'];
            $pageSize = $obj->formatSizeUnits($scanResult['page_bytes']);
            $scan_url = $scanHistory['scan_url'];
            $pageSpeedCode = $obj->gtmetrix_code($pageSpeed);
            $ySlowCode = $obj->gtmetrix_code($ySlow);
            if ( file_exists($toolkit_uploads."screenshot-{$scanHistory['test_id']}.jpg") ) {
                $screenshot = $toolkit_uploads_url . 'screenshot-' . $scanHistory['test_id'] . '.jpg';
            }
        }

        $offset = !empty($_REQUEST['page_no']) ? (($_REQUEST['page_no'] - 1) * $obj->limit) : 0;
        $lazySetting = get_option('toolkit_elementor_settings', array());
        $settingServer = get_option('toolkit_elementor_tweaks', array());
        $serverTweaks = get_option('toolkit_webserver_tweaks', array());
        $getHQAdMsg = get_option('toolkit_hq_my_ad_msg', ''); ?>

        <div class="wrap toolkit-performance">
            <div class="updated toolkit-message" style="display:none;"><p><?php _e('Test Completed Successfully!'); ?></p></div>
            <div class="tabs-holder">
                <div class="tab-nav">
                    <ul class="">
                        <li class="active" data-tabid="gtmetrix-tab">
                            <img src="<?php echo TOOLKIT_FOR_ELEMENTOR_URL; ?>admin/images/Asset-7.png" style="float: right;" width="40">
                            <span><?php _e('RUN GTMETRIX SCAN'); ?></span>
                            <p class="margin0">
                                <medium><?php _e('Simply Input a URL to Test'); ?></medium>
                            </p>
                        </li>
                        <li data-tabid="minification-tab">
                            <img src="<?php echo TOOLKIT_FOR_ELEMENTOR_URL; ?>admin/images/icon-minify.png" style="float: right;" width="40">
                            <span><?php _e('MINIFICATION'); ?></span>
                            <p class="margin0">
                                <medium><?php _e('MINIFY CSS & JS'); ?></medium>
                            </p>
                        </li>
                        <li data-tabid="lazy-load">
                            <img src="<?php echo TOOLKIT_FOR_ELEMENTOR_URL; ?>admin/images/Asset-2.png" style="float: right;" width="40">
                            <span><?php _e('APPLY LAZY LOAD'); ?></span>
                            <p class="margin0">
                                <medium><?php _e('LAZYLOAD MEDIA'); ?></medium>
                            </p>
                        </li>
                        <li data-tabid="additional-tweaks">
                            <img src="<?php echo TOOLKIT_FOR_ELEMENTOR_URL; ?>admin/images/Asset-3.png" style="float: right;" width="40">
                            <span><?php _e('SERVER TWEAKS'); ?></span>
                            <p class="margin0">
                                <medium><?php _e('GZIP, LBC, ETAGS & MORE'); ?></medium>
                            </p>
                        </li>
                        <li data-tabid="unload-options">
                            <img src="<?php echo TOOLKIT_FOR_ELEMENTOR_URL; ?>admin/images/unload-icon.png" style="float: right;" width="40">
                            <span><?php _e('UNLOAD WP BLOAT'); ?></span>
                            <p class="margin0">
                                <medium><?php _e('DEQUEUE COMMON BLOAT'); ?></medium>
                            </p>
                        </li>
						<li data-tabid="widget-manager">
                            <img src="<?php echo TOOLKIT_FOR_ELEMENTOR_URL; ?>admin/images/widgetmanager-icon.png" style="float: right;" width="40">
                            <span><?php _e('WIDGET MANAGER'); ?></span>
                            <p class="margin0">
                                <medium><?php _e('DISABLE UNNEEDED WIDGETS'); ?></medium>
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="content-tab">
                    <div class="single-tab" id="minification-tab">
                        <div class="row">
                            <div class="col-md-12 minification-setting-section">
                                <h4><?php _e('MINIFICATION SETTINGS'); ?></h4>
                                <p><?php _e('Minification removes unnecessary characters and spaces from a file to reduce its total size, thus improving load times. When a WP file is minified, comments and unneeded white space characters (space, newline, and tab) are removed.<br /><br />ToolKit For Elementor uses an amazing, popular, minify script by Matthias Mullie. View the <a href="https://github.com/matthiasmullie/minify" target="_blank">full project and source code here</a>.
							<br /><br />If you are using other Performance Plugins such as WP Rocket, Asset Cleanup Manager, SWIFT, or WP Fastest Cache, please use their minifcation settings instead as having multiple plugins performing the same optimization features can cause issues.'); ?></p>
                                <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce($obj->nonce_key); ?>"/>
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="css_minify" <?php echo(isset($settingServer['css_minify']) && $settingServer['css_minify'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>Minify CSS</b><br />Minifying CSS can save many bytes of data and speed up downloading, parsing, and execution time.'); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="css_combine" <?php echo(isset($settingServer['css_combine']) && $settingServer['css_combine'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>Combine CSS</b><br />Combine CSS can save many bytes of data and speed up downloading, parsing, and execution time.'); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="js_minify" <?php echo(isset($settingServer['js_minify']) && $settingServer['js_minify'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>Minify Javascript</b><br />Minifying JS can save many bytes of data and speed up downloading, parsing, and execution time.'); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="js_combine" <?php echo(isset($settingServer['js_combine']) && $settingServer['js_combine'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>Combine Javascript</b><br />Combine JS can save many bytes of data and speed up downloading, parsing, and execution time.<br />If for some reason you run into issues loading the Elementor Editor, try disabling this option and try loading the editor again.'); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <p><?php _e('As WordPress core already includes minified CSS, ToolKit ignores CSS and JS files that are already minified such as <code>/wp-includes/css/admin-bar.min.css</code>, and <code>/wp-includes/js/jquery-migrate.min.js</code>'); ?></p>
                                </div>
                                <br/>
                                <div class="form-group">
                                    <button type="button" class="button toolkit-btn" id="save-minification-settings"><?php _e('Save and Apply Tweaks'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single-tab" id="lazy-load">
                        <div class="row">
                            <div class="col-md-12 lazyload-setting-section">
                                <h4><?php _e('LAZYLOAD SETTINGS'); ?></h4>
                                <p><?php _e('LazyLoad is a lightweight, super useful script that speeds up your WP site by loading your media (images, videos and iframes) only as they enter the browser viewport. For ToolKit, our Lazy Load functionality uses the popular, opensource LazyLoad script by Andrea Verlicchi. <br /><br />You can view the full project + <a href="https://github.com/verlok/lazyload" target="_blank">Lazy Load source code here</a>.'); ?></p>
                                <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce($obj->nonce_key); ?>"/>
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="image" <?php echo(!empty($lazySetting) && $lazySetting['image'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>LazyLoad Images</b><br />Normally, every image loaded on a page is an additional server request (not to mention that images are normally the largest factors in page size and load time). Enabling this means that every image loaded "below the fold" loads as it enters the browser viewport instead of loading everything upfront. This drastically reduces the number of server requests as well as total page size and load time. '); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="1" name="iframe_video" <?php echo(!empty($lazySetting) && $lazySetting['iframe_video'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>LazyLoad Iframe & Videos</b><br />Perfect for reducing loading of javascript and the number of server requests associated with iframes and videos from Youtube and Vimeo. '); ?>
                                        </label>
                                    </div>
                                    <br/>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="button toolkit-btn" id="save-lazyload-setting"><?php _e('Save and Apply Tweaks'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single-tab" id="additional-tweaks">
                        <div class="row">
                            <div class="col-md-12 server-tweaks-section">
                                <h4><?php _e('ADDITIONAL SERVER TWEAKS'); ?></h4>
                                <p><?php _e('We have included a few extra server-level tweaks for users that are using Apache servers. For users on NGINX & LiteSpeed servers, most of these features are natively enabled already, however we have included some useful resources and links in the event you need to customize these server settings.'); ?></p>
                                <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce($obj->nonce_key); ?>"/>
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="toolkit_gzip_compression" <?php echo(isset($serverTweaks['gzip_compression']) && $serverTweaks['gzip_compression'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>GZip Compression:</b><br />Reduces the size of files sent from your server to a browser.<br /><a href="https://toolkitforelementor.com/gzip-compression/" target="_blank">Learn about GZip Here</a> | <a href="https://toolkitforelementor.com/gzip-compression/#nginx" target="_blank">For NGINX Users</a> | <a href="https://toolkitforelementor.com/gzip-compression/#litespeed" target="_blank">For LiteSpeed Users</a> . '); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="1" name="toolkit_keep_alive" <?php echo(isset($serverTweaks['keep_alive']) && $serverTweaks['keep_alive'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>Enable Keep-Alive Connections:</b><br />Keep-Alive or HTTP persistent connections allow the same initial server connection to send and receive multiple requests, thus reducing the lag for subsequent requests.<br /><a href="https://toolkitforelementor.com/keep-alive-connections/" target="_blank">Learn about Keep-Alive</a> | <a href="https://toolkitforelementor.com/keep-alive-connections/#nginx" target="_blank">For NGINX Users</a> | <a href="https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:config:keep_alive" target="_blank">For LiteSpeed Users</a>.'); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="toolkit_ninja_etags" <?php echo(isset($serverTweaks['ninja_etags']) && $serverTweaks['ninja_etags'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>Enable Entity Tags (ETags):</b><br />ETags is a mechanism that servers and browsers use to determine whether a component in the browser cache matches one on the original server.<br /><a href="https://toolkitforelementor.com/configure-entity-tags/" target="_blank">Learn about ETags</a> | <a href="https://toolkitforelementor.com/configure-entity-tags/#nginx" target="_blank">For NGINX Users</a> | <a href="https://www.litespeedtech.com/docs/webserver/config/tuning#fileETag" target="_blank">For LiteSpeed Users</a>.'); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="toolkit_expire_headers" <?php echo(isset($serverTweaks['expire_headers']) && $serverTweaks['expire_headers'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>Leverage Browser Caching & Expires Headers</b><br />LBC reduces server load and load times by marking and storing certain pages, or parts of pages in the browser. Then it marks the files as being needed to be updated at various internals. Expires Headers let the browser know whether to serve a cached version of a page or file, or to request a fresh version from the server.<br /><a href="https://gtmetrix.com/add-expires-headers.html" target="_blank">Learn about Expires Headers</a> | <a href="https://toolkitforelementor.com/leverage-browser-caching/" target="_blank">Leverage Browser Caching</a> | <a href="https://toolkitforelementor.com/leverage-browser-caching/#nginx" target="_blank">For NGINX Users</a> | <a href="https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:cache:lscwp:browser_cache" target="_blank">For LiteSpeed Users</a>.'); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="toolkit_combine_gfonts" <?php echo(isset($serverTweaks['combine_gfonts']) && $serverTweaks['combine_gfonts'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>Combine Google Fonts API Requests (For Both Apache + NGINX)</b><br />This option combines multiple font.googleapis.com requests into 1 request for much faster loading.<br /><a href="https://developers.google.com/fonts/docs/getting_started" target="_blank">Learn about Google Fonts</a>.'); ?>
                                        </label>
                                    </div>
                                    <br/>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="toolkit_encoding_header" <?php echo(isset($serverTweaks['encoding_header']) && $serverTweaks['encoding_header'] == 'on' ? 'checked' : ''); ?>><?php _e('<b>Specify a Vary: Accept-Encoding Header</b><br />Bugs or hiccups in some public proxies can lead to compressed versions of your resources being served to users that do not support compression. This option instructs the proxy to store both a compressed and uncompressed version of the resource. <br /><a href="https://kinsta.com/knowledgebase/specify-vary-accept-encoding-header/" target="_blank">Learn about Specify a Vary from Kinsta</a> | <a href="https://gtmetrix.com/specify-a-vary-accept-encoding-header.html" target="_blank">from GTMetrix</a>.'); ?>
                                        </label>
                                    </div>
                                    <br/>
                                </div>
                                <br/>
                                <div class="form-group">
                                    <button type="button" class="button toolkit-btn" id="save-server-tweaks"><?php _e('Save and Apply Tweaks'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single-tab" id="unload-options">
                        <div class="unload-options-section">
                            <?php include 'unload-options.php'; ?>
                        </div>
                    </div>
					<div class="single-tab" id="widget-manager">
                        <div class="widget-manager-section">						
                           <?php 
						   include 'widget-manager.php';						  
							?>
                        </div>
                    </div>
                    <div class="single-tab active" id="gtmetrix-tab">
                        <div class="row">
                            <div class="col-sm-8 gtmetrix-result">
                                <?php if ($scanHistory) { ?>
                                    <section class="gtmetrix-report">
                                        <div class="row report-head">
                                            <div class="col-sm-5 padding0">
                                                <img class="gtmetrix-scrshot" src="<?php echo($screenshot ? $screenshot : $screenshotDefault); ?>" alt="<?php _e('Screenshot'); ?>">
                                            </div>
                                            <div class="col-sm-7">
                                                <h3><?php _e('Performance Report for:'); ?></h3>
                                                <p><a href="<?php echo $scan_url; ?>" target="_blank" rel="nofollow noopener noreferrer" class="no-external"><?php echo $scan_url; ?></a></p>
                                                <div class="row">
                                                    <div class="col-sm-4 padding0">
                                                        <b><?php _e('Report Date:'); ?></b>
                                                    </div>
                                                    <div class="col-sm-8 padding0 text-right">
                                                        <?php echo($lastReportTime ? date('D, M d, Y, h:i A', strtotime($lastReportTime)) : 'N/A'); ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 padding0">
                                                        <b><?php _e('Test Location:'); ?></b>
                                                    </div>
                                                    <div class="col-sm-8 padding0 text-right">
                                                        <?php echo $region; ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-5 padding0">
                                                        <b><?php _e('Browser Type:'); ?></b>
                                                    </div>
                                                    <div class="col-sm-7 padding0 text-right">
                                                        <?php echo ($browser ? $browser : 'N/A'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5 report-left">
						    <h3><?php _e('Performance Scores'); ?></h3>
						    <div class="row">
                                                    <div class="col-sm-6 padding0">
                                                        <h3><?php _e('PageSpeed'); ?></h3>
                                                        <span class="report-score-grade color-grade-<?php echo($pageSpeedCode ? $pageSpeedCode['code'] : 'E'); ?>"><span><?php echo($pageSpeedCode ? $pageSpeedCode['code'] : 'E'); ?></span><span class="report-score-percent">(<?php echo $pageSpeed; ?>%)</span></span>
                                                    </div>
                                                    <div class="col-sm-6 padding0">
                                                        <h3><?php _e('YSlow'); ?></h3>
                                                        <span class="report-score-grade color-grade-<?php echo($ySlowCode ? $ySlowCode['code'] : 'E'); ?>">
                                                            <span><?php echo($ySlowCode ? $ySlowCode['code'] : 'E'); ?></span>
                                                            <span class="report-score-percent">(<?php echo $ySlow; ?>%)</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7 report-right">
                                                <h3><?php _e('Page Details'); ?></h3>
                                                <div class="row">
                                                    <div class="col-sm-4 padding0">
                                                        <h3><?php _e('Load Time'); ?></h3>
                                                        <span class="report-page-detail-value"><?php echo $loadTime; ?>s</span>
                                                        <i class="site-average sprite-average-below hover-tooltip tooltipstered" data-tooltip-interactive=""></i>
                                                    </div>
                                                    <div class="col-sm-5 padding0">
                                                        <h3><?php _e('Page Size'); ?></h3>
                                                        <span class="report-page-detail-value"><?php echo $pageSize; ?></span>
                                                        <i class="site-average sprite-average-below hover-tooltip tooltipstered" data-tooltip-interactive=""></i>
                                                    </div>
                                                    <div class="col-sm-3 padding0">
                                                        <h3><?php _e('Requests'); ?></h3>
                                                        <span class="report-page-detail-value"><?php echo $requests; ?></span>
                                                        <i class="site-average sprite-average-below hover-tooltip tooltipstered" data-tooltip-interactive=""></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $pageData = array();
                                        $yslowData = array();
                                        $pagespeedPath = $toolkit_uploads . "pagespeed-{$scanHistory['test_id']}.txt";
                                        if ( file_exists($pagespeedPath) ) {
                                            $myfile = fopen($pagespeedPath, "r");
                                            $pagespeedJson = fread( $myfile, filesize($pagespeedPath) );
                                            fclose($myfile);
                                            if( $pagespeedJson ){
                                                $pagespeed = json_decode($pagespeedJson, true);
                                                if( isset($pagespeed['rules']) ){
                                                    uasort($pagespeed['rules'], 'toolkit_sort_page_descending');
                                                    $pageData = $pagespeed['rules'];
                                                }
                                            }
                                        }
                                        $yslowPath = $toolkit_uploads . "yslow-{$scanHistory['test_id']}.txt";
                                        /*if ( file_exists($yslowPath) ) {
                                            $ySlwFile = fopen($yslowPath, "r");
                                            $yslowJson = fread( $ySlwFile, filesize($yslowPath) );
                                            fclose($ySlwFile);
                                            if( $yslowJson ){
                                                $yslowJson = json_decode($yslowJson, true);
                                                if( isset($yslowJson['g']) ){
                                                    foreach( $yslowJson['g'] as $key => $value ){
                                                        if( ! isset($value['score']) ){
                                                            unset($yslowJson['g'][$key]);
                                                        }
                                                    }
                                                    uasort($yslowJson['g'], 'toolkit_sort_yslow_descending');
                                                    $yslowData = $yslowJson['g'];
                                                }
                                            }
                                        }*/ ?>
                                        <div class="gtmetrix-stats">
                                            <div class="stats-tabs">
                                                <button type="button" class="button button-primary" data-target="pagespeed-table">
                                                    <?php _e("PageSpeed"); ?>
                                                </button>
                                                <?php /* <button type="button" class="button button-primary" data-target="yslow-table">
                                                    <?php _e("YSlow"); ?>
                                                </button> */ ?>
                                            </div>
                                            <div class="table-responsive showme" id="pagespeed-table">
                                                <?php if( $pageData ){ ?>
                                                    <table class="pagespeed-table">
                                                        <thead>
                                                        <tr>
                                                            <th><?php _e('Recommendation'); ?></th>
                                                            <th width="100"><?php _e('Grade'); ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($pageData as $i => $pageDatum) {
                                                            $css = toolkit_get_css_color_class($pageDatum['score']);
                                                            $width = ($pageDatum['score'] > 49) ? 'width:'.$pageDatum['score'].'%;' : '';
                                                            echo "<tr><td><span class='collapse-lbl' data-target='collapsed".($i+1)."'>".$pageDatum['name']."</span></td>";
                                                            echo "<td><span class='metrix-score'><span style='".$width."' class='metrix-fill ".$css['cls']."'>".$css['lbl']." (".$pageDatum['score'].")</span></span></td></tr>";
                                                            if($pageDatum['warnings']){
                                                                echo "<tr><td colspan='2'><span class='collapse-content' id='collapsed".($i+1)."'>".$pageDatum['warnings']."</span></td></tr>";
                                                            } else {
                                                                echo "<tr><td colspan='2'><span class='collapse-content' id='collapsed".($i+1)."'>".__("Your score is good, nothing here.")."</span></td></tr>";
                                                            }
                                                        } ?>
                                                        </tbody>
                                                    </table>
                                                <?php } else {
                                                    echo "<p>".__("No data found.")."</p>";
                                                } ?>
                                            </div>
                                            <div class="table-responsive" id="yslow-table">
                                                <?php if( $yslowData ){
                                                    $labels = toolkit_get_yslow_labels(); ?>
                                                    <table class="pagespeed-table">
                                                        <thead>
                                                        <tr>
                                                            <th><?php _e('Recommendation'); ?></th>
                                                            <th width="100"><?php _e('Grade'); ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($yslowData as $i => $pageDatum) {
                                                            if( isset($pageDatum['score']) ){
                                                                $css = toolkit_get_css_color_class($pageDatum['score']);
                                                                $width = (isset($pageDatum['score']) && $pageDatum['score'] > 49) ? 'width:'.$pageDatum['score'].'%;' : '';
                                                                $label = isset($labels[$i]) ? $labels[$i] : $i;
                                                                echo "<tr><td><span class='collapse-lbl' data-target='collapsed-".($i)."'>".$label."</span></td>";
                                                                echo "<td><span class='metrix-score'><span style='".$width."' class='metrix-fill ".$css['cls']."'>".$css['lbl']." (".$pageDatum['score'].")</span></span></td></tr>";
                                                                if($pageDatum['message'] || $pageDatum['components']){
                                                                    echo "<tr><td colspan='2'><span class='collapse-content' id='collapsed-".($i)."'>";
                                                                    echo $pageDatum['message'];
                                                                    if($pageDatum['components']){
                                                                        $components = array();
                                                                        foreach ($pageDatum['components'] as $val){
                                                                            $components[] = str_replace('<script>', 'script', $val);
                                                                        }
                                                                        echo "<ul><li>".implode("</li><li>", $components)."</li></ul>";
                                                                    }
                                                                    echo "</span></td></tr>";
                                                                } else {
                                                                    echo "<tr><td colspan='2'><span class='collapse-content' id='collapsed-".($i)."'>".__("Your score is good, nothing here.")."</span></td></tr>";
                                                                }
                                                            }
                                                        } ?>
                                                        </tbody>
                                                    </table>
                                                <?php }  else {
                                                    echo "<p>".__("No data found.")."</p>";
                                                } ?>
                                            </div>
                                        </div>
                                    </section>
                                <?php } else { ?>
                                    <h4 class="text-center" style="display: table-cell;vertical-align: middle;"><?php _e('Please Run Your First Scan To See Your Results.'); ?></h4>
                                <?php } ?>
                                <div class="gtmetrix-history" id="gtmetrix-history">
                                    <?php echo $obj->getGtmetrixScanHistory($obj->limit, $offset); ?>
                                </div>
                            </div>
                            <div class="col-sm-4 gtmetrix-form">
                                <div class="toolkit-gtmetrix-section" style="background:#F9F9F9;margin-left:0;">
                                    <h3><?php _e('PERFORM GTMETRIX SCAN'); ?></h3>
                                    <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce($obj->nonce_key); ?>"/>
                                    <div class="form-group">
                                        <label for="sel1"><?php _e('Enter a URL To Test Here'); ?></label>
                                        <input class="form-control" type="text" name="scan_url" placeholder="https://yourdomain.com" value="<?php echo site_url(); ?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="sel1"><?php _e('Select Testing Location'); ?></label>
                                        <select class="form-control" name="location">
                                            <?php if ($obj->gtmetrix_location) {
                                                foreach ($obj->gtmetrix_location as $gtmetrix) {
                                                    echo '<option value="' . $gtmetrix['id'] . '" ' . ($gtmetrix['default'] ? 'selected' : '') . '>' . $gtmetrix['name'] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="1">Vancouver, Canada</option>';
                                                echo '<option value="2">London, UK</option>';
                                                echo '<option value="3">Sydney, Australia</option>';
                                                echo '<option value="4">Dallas, USA</option>';
                                                echo '<option value="5">Mumbai, India</option>';
                                                echo '<option value="6">São Paulo, Brazil</option>';
                                                echo '<option value="7">Hong Kong, China</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="sel1"><?php _e('Select Browser Type'); ?></label>
                                        <select class="form-control" name="browser">
                                            <?php if ($obj->gtmetrix_browsers) {
                                                foreach ($obj->gtmetrix_browsers as $browsers) {
                                                    echo '<option value="' . $browsers['id'] . '" ' . (strpos($browsers['browser'], 'chrome') !== false ? 'selected' : '') . '>' . $browsers['name'] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="1">Firefox (Desktop)</option>';
                                                echo '<option value="3">Chrome (Desktop)</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="button" class="button toolkit-btn"><?php _e('Perform GTMetrix Scan!'); ?></button>
                                    </div>
                                </div>
                                <div class="row" style="background:#F9F9F9;margin-top:10px;margin-left:0;">
                                    <div class="row">
                                        <div class="col-md-12 advertisement-section">
                                            <?php echo(!empty($getHQAdMsg) ? $getHQAdMsg : '<img src="https://toolkitforelementor.com/wp-content/uploads/sites/21/2019/06/tutorial-1.png"  width="330" height="" class="" />'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="not-active-notice">
            <?php _e('Oops, looks like you do not have an active license yet, please activate your license first in My License'); ?>
        </div>
    <?php }
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
}
if( ! function_exists('toolkit_sort_page_descending') ){
    function toolkit_sort_page_descending($a, $b) {
        return ($a['impact'] > $b['impact']) ? -1 : 1;
    }
}
if( ! function_exists('toolkit_sort_yslow_descending') ){
    function toolkit_sort_yslow_descending($a, $b) {
        return ($a['score'] < $b['score']) ? -1 : 1;
    }
}

if( ! function_exists('toolkit_get_css_color_class') ){
    function toolkit_get_css_color_class($value) {
        if( $value > 89 ){
            $class = array('cls'=>'metrix-acolor', 'lbl'=>'A');
        } elseif( $value > 79 ){
            $class = array('cls'=>'metrix-bcolor', 'lbl'=>'B');
        } elseif( $value > 69 ){
            $class = array('cls'=>'metrix-ccolor', 'lbl'=>'C');
        } elseif( $value > 59 ){
            $class = array('cls'=>'metrix-dcolor', 'lbl'=>'D');
        } elseif( $value > 49 ){
            $class = array('cls'=>'metrix-ecolor', 'lbl'=>'E');
        } else {
            $class = array('cls'=>'metrix-fcolor', 'lbl'=>'F');
        }
        return $class;
    }
}
