<?php
$unloadOpts = get_option('toolkit_unload_options', array());
?>
<div class="row">
    <div class="col-md-12">
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="toolkit_disable_emojis" <?php echo(isset($unloadOpts['disable_emojis']) && $unloadOpts['disable_emojis'] == 'on' ? 'checked' : ''); ?>>
                <?php _e('<b>Disable Emojis Site-Wide</b><br />Normally about 12KB, WordPress 4.2 and higher began loading CSS and JS for the new Emoji framework. If you do not use any emojis on your site, you can disable this and reduce your server requests and site size a bit more.'); ?>
            </label>
        </div>
        <br>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="toolkit_disable_gutenberg" <?php echo(isset($unloadOpts['disable_gutenberg']) && $unloadOpts['disable_gutenberg'] == 'on' ? 'checked' : ''); ?>>
                <?php _e('<b>Disable Gutenberg CSS Block Library Site-Wide</b><br />For Elementor users that do not use Gutenberg at all, you can disable this. Disabling this will dequeue /wp-includes/css/dist/block-library/style.min.css (saves approx 25 KB) .'); ?>
            </label>
        </div>
        <br>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="toolkit_disable_commentreply" <?php echo(isset($unloadOpts['disable_commentreply']) && $unloadOpts['disable_commentreply'] == 'on' ? 'checked' : ''); ?>>
                <?php _e('<b>Disable Comment Reply Site-Wide</b><br />If you have disabled comments site-wide, you can dequeue the comment reply JS which is normally loaded by WordPress by default. If you are using Facebook or Discus for comments, you can also disable the default WP comment reply JS (/wp-includes/js/comment-reply(.min).js) '); ?>
            </label>
        </div>
        <br>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="toolkit_disable_jqmigrate" <?php echo(isset($unloadOpts['disable_jqmigrate']) && $unloadOpts['disable_jqmigrate'] == 'on' ? 'checked' : ''); ?>>
                <?php _e('<b>Disable  jQuery Migrate Site-Wide</b><br />This is a library file that allows old versions of jQuery (up to v1.9) to work with the newer versions, and fixing many compatibility issues. Unless you are using an older plugin with out-of-date jQuery, or are using an older theme/builder which is still using older versions of jQuery, this file is most likely not needed.'); ?>
            </label>
        </div>
        <br/>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="toolkit_disable_woohomeajax" <?php echo(isset($unloadOpts['disable_woohomeajax']) && $unloadOpts['disable_woohomeajax'] == 'on' ? 'checked' : ''); ?>>
                <?php _e('<b>Dequeue WooCommerce Ajax on Home Page</b><br />If you are using WooCommerce do not need AJAX on your home page, consider dequeuing this file to increase site load times.'); ?>
            </label>
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <button type="button" class="button toolkit-btn" id="save-unload-options"><?php _e('Save and Apply Options'); ?></button>
</div>
