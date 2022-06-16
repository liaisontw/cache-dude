<?php
$controls = new HyperCacheControls();

class HyperCacheControls {

    var $options = null;
    var $errors = null;
    var $messages = null;

    function is_action($action = null) {
        if ($action == null)
            return !empty($_REQUEST['act']);
        if (empty($_REQUEST['act']))
            return false;
        if ($_REQUEST['act'] != $action)
            return false;
        if (check_admin_referer('save'))
            return true;
        die('Invalid call');
    }

    function text($name, $size = 20) {
        if (!isset($this->options[$name]))
            $this->options[$name] = '';
        $value = $this->options[$name];
        if (is_array($value))
            $value = implode(',', $value);
        echo '<input name="options[' . $name . ']" type="text" size="' . $size . '" value="';
        echo htmlspecialchars($value);
        echo '"/>';
    }

    function checkbox($name, $label = '') {
        if (!isset($this->options[$name]))
            $this->options[$name] = '';
        $value = $this->options[$name];
        echo '<label><input class="panel_checkbox" name="options[' . $name . ']" type="checkbox" value="1"';
        if (!empty($value))
            echo ' checked';
        echo '>';
        echo $label;
        echo '</label>';
    }

    function textarea($name) {
        if (!isset($this->options[$name]))
            $value = '';
        else
            $value = $this->options[$name];
        if (is_array($value))
            $value = implode("\n", $value);
        echo '<textarea name="options[' . $name . ']" style="width: 100%; heigth: 120px;">';
        echo htmlspecialchars($value);
        echo '</textarea>';
    }

    function select($name, $options) {
        if (!isset($this->options[$name]))
            $this->options[$name] = '';
        $value = $this->options[$name];

        echo '<select name="options[' . $name . ']">';
        foreach ($options as $key => $label) {
            echo '<option value="' . $key . '"';
            if ($value == $key)
                echo ' selected';
            echo '>' . htmlspecialchars($label) . '&nbsp;&nbsp;</option>';
        }
        echo '</select>';
    }

    function button($action, $label, $message = null) {
        if ($message == null) {
            echo '<input class="button-primary" type="submit" value="' . $label . '" onclick="this.form.act.value=\'' . $action . '\'"/>';
        } else {
            echo '<input class="button-primary" type="submit" value="' . $label . '" onclick="this.form.act.value=\'' . $action . '\';return confirm(\'' .
            htmlspecialchars($message) . '\')"/>';
        }
    }

    function init() {
        echo '<script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery("textarea").focus(function() {
                    jQuery(this).css("height", "400px");
                });
                jQuery("textarea").blur(function() {
                    jQuery(this).css("height", "120px");
                });
            });
            </script>
            ';
        echo '<input name="act" type="hidden" value=""/>';
        wp_nonce_field('save');
    }

    function show() {
        if (!empty($this->errors)) {
            echo '<div class="error"><p>';
            echo $this->errors;
            echo '</p></div>';
        }

        if (!empty($this->messages)) {
            echo '<div class="updated"><p>';
            echo $this->messages;
            echo '</p></div>';
        }
    }

}
?>

<script>
    jQuery(document).ready(function() {
        jQuery(function() {
            tabs = jQuery("#tabs").tabs({
                cookie: {
                    expires: 30
                }
            });
        });
    });
</script>
<div class="wrap">

    <h2>Cache Dude</h2>


    <form method="post" action="">

        <div id="tabs">
            <ul>
                <li><a href="#tabs-general"><?php _e('General', 'hyper-cache'); ?></a></li>
                <li><a href="#tabs-rejects"><?php _e('Bypasses', 'hyper-cache'); ?></a></li>
                <li><a href="#tabs-mobile"><?php _e('Mobile', 'hyper-cache'); ?></a></li>
                <li><a href="#tabs-advanced"><?php _e('Advanced', 'hyper-cache'); ?></a></li>
                <li><a href="#tabs-cdn"><?php _e('CDN', 'hyper-cache'); ?></a></li>
            </ul>

            <div id="tabs-cdn">
                <p><?php _e('It works only with images, css, scripts.', 'hyper-cache'); ?></p>
                <table class="form-table">
                    <tr>
                        <th>&nbsp;</th>
                        <td>
                            <?php $controls->checkbox('cdn_enabled', __('Enable', 'hyper-cache')); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>CDN URL</th>
                        <td>
                            <?php $controls->text('cdn_url', 50); ?>
                            <p class="description">
                                <?php _e('Write here the CDN URL.', 'hyper-cache'); ?>
                                <?php _e('For example a MaxCDN URL is something like', 'hyper-cache'); ?>
                                <code>http://foo.bar.netdna-cdn.com</code>.
                                <?php _e('You should usually create a pull zone in your CDN panel and they will give your an URL.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <p>
                    I'm actually testing it with <a href="https://www.satollo.net/affiliate/maxcdn" target="_blank">MaxCDN</a> and
                    <a href="https://www.satollo.net/affiliate/keycdn" target="_blank">KeyCDN</a>.
                </p>
            </div>

            <div id="tabs-general">

                <table class="form-table">
                    <tr>
                        <th><?php _e('Cached pages will be valid for', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->text('max_age'); ?><?php _e('hours', 'hyper-cache'); ?>
                            <p class="description"><?php _e('0 means forever.', 'hyper-cache'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Enable compression', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('gzip'); ?>

                            <p class="description">
                                <?php _e('If you note odd characters when enabled, disable it since your server is already compressing the pages.', 'hyper-cache'); ?>
                                <?php _e('If your server has mod_pagespeed, leave the compression disabled otherwise the module cannot optimize the page.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    

                    <tr>
                        <th><?php _e('When a post is edited', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('clean_archives_on_post_edit'); ?> clean archives (categories, tags, ..., but not the home)
                            <br>
                            <?php $controls->checkbox('clean_home_on_post_edit'); ?> clean the home
                            <p class="description">

                            </p>
                        </td>
                    </tr>

                 

                   
                    <tr valign="top">
                        <th><?php _e('Allow browser caching', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('browser_cache', __('Enable', 'hyper-cache')); ?>

                            with an expire time of <?php $controls->text('browser_cache_hours', 5); ?> hours
                            <p class="description">
                                <?php _e('Lets browser to use a local copy of the page if newer than specified.', 'hyper-cache'); ?>
                                <?php _e('Attention: the browser may not reload a page from the blog showing not updated content. ', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                   

                    <tr valign="top">
                        <th><?php _e('HTTPS', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->select('https', array(0 => __('Bypass the cache', 'hyper-cache'),
                                1 => __('Build a separated cache', 'hyper-cache'),
                                2 => __('Use the standard cache (I have HTTP/HTTPS aware pages)', 'hyper-cache'))); ?>
                            <p class="description">
                                <?php _e('Pages are usually different when served in HTTP and HTTPS.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>

                   
                    <tr>
                        <th><?php _e('Serve expired pages to bots', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('serve_expired_to_bots', __('Enable', 'hyper-cache')); ?>
                            <p class="description">
                                <?php _e('Serve a cache page even if expired when requested by bots.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                </table>

            </div>

            <div id="tabs-rejects">
                <table class="form-table">
                    <tr>
                        <th><?php _e('Do not cache the home page', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_home'); ?>
                            <p class="description">
                                <?php _e('When active, the home page and its subpages are not cached.', 'hyper-cache'); ?>
                                <?php _e('Works even with a static home page.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Do not cache the "404 - Not found" page', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_404'); ?>
                            <p class="description">
                                <?php _e('When active, Hyper Cache does not serve a cached "404 not found" page.', 'hyper-cache'); ?>
                                <?php _e('Requests which lead to a 404 not found page overload you blog since WordPress must generate a full page', 'hyper-cache'); ?>
                                <?php _e('Caching it help in reduce that overload.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Do not cache the blog main feeds', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_feeds'); ?>
                            <p class="description">
                                <?php printf(__('When active, the main blog feed %s is not cached.', 'hyper-cache'),
                                        '(<code>' . get_option('home') . '/feed</code>)'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Do not cache single post comment feed', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_comment_feeds'); ?>
                            <p class="description">
                                <?php _e('When active, the single post comment feeds are not cached.', 'hyper-cache'); ?>
                                <?php _e('Usually I enable this bypass since it saves disk space and comment feed on single posts are not usually used.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Do not cache pages with URIs', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_uris_exact_enabled', __('Enable', 'hyper-cache')); ?><br>
                            <?php $controls->textarea('reject_uris_exact'); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('Those URIs are exactly matched.', 'hyper-cache'); ?>
                                <?php _e('For example if you add the <code>/my-single-post</code> URI and a request is received for <code>http://youblog.com<strong>/my-single-post</strong></code> that page IS NOT cached.', 'hyper-cache'); ?>
                                <?php _e('A request for <code>http://youblog.com<strong>/my-single-post-something</strong></code> IS cached.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Do not cache pages with URIs starting with', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_uris_enabled', __('Enable', 'hyper-cache')); ?><br>
                            <?php $controls->textarea('reject_uris'); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('Those URIs match if a requested URI starts with one of them.', 'hyper-cache'); ?>
                                <?php _e('For example if you add the <code>/my-single-post</code> URI and a request is received for <code>http://youblog.com<strong>/my-single-post</strong></code> that page IS NOT cached.', 'hyper-cache'); ?>

                                <?php _e('A request for <code>http://youblog.com<strong>/my-single-post-something</strong></code> IS NOT cached as well.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Bypass the cache for readers with cookies', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_cookies_enabled', __('Enable', 'hyper-cache')); ?><br>
                            <?php $controls->textarea('reject_cookies'); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('If the visitor has a cookie named as one of the listed values, the cache is bypassed.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Bypass the cache for readers with devices (user agents)', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_agents_enabled', __('Enable', 'hyper-cache')); ?><br>
                            <?php $controls->textarea('reject_agents'); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('If the visitor has a device with a user agent named as one of the listed values, the cache is bypassed.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th><?php _e('Bypass the cache for readers which are commenters', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('reject_comment_authors', __('Enable', 'hyper-cache')); ?>

                            <p class="description">
                                <?php _e('Hyper Cache is able to work with users who left a comment and completes the comment form with
                                user data even on cached page', 'hyper-cache'); ?>
                                <?php _e('(with a small JavaScript added at the end of the pages).', 'hyper-cache'); ?>
                                <?php _e('But the "awaiting moderation" message cannot be shown.', 'hyper-cache'); ?>
                                <?php _e('If you have few readers who comment you can disable this feature to get back the classical WordPress comment flow.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Do not cache posts older than', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->text('reject_old_posts', 5); ?> <?php _e('days', 'hyper-cache'); ?>
                            <p class="description">
                                <?php _e('Older posts won\'t be cached and stored resulting in a lower disk space usage.', 'hyper-cache'); ?>
                                <?php _e('Useful when older posts have low traffic.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div id="tabs-advanced">
                <table class="form-table">
                    <tr>
                        <th><?php _e('Enable on-the-fly compression', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('gzip_on_the_fly'); ?>

                            <p class="description">
                                <?php _e('Enable on the fly compression for non cached pages.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                <tr>
                    <tr>
                        <th><?php _e('When a post receives a comment', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('clean_archives_on_comment'); ?> clean archives (categories, tags, ..., but not the home)
                            <br>
                            <?php $controls->checkbox('clean_home_on_comment'); ?> clean the home
                            <p class="description">

                            </p>
                        </td>
                    </tr>
                    <tr>

                        <th><?php _e('When the home is refreshed, refresh even the', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->text('clean_last_posts', 5); ?> <?php _e('latest post', 'hyper-cache'); ?>
                            <p class="description">
                                <?php _e('The number of latest posts to invalidate when the home is invalidated.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                     
                    <tr>
                        <th><?php _e('Next autoclean will run in', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->checkbox('autoclean', 'enable it'); ?>

                            (<?php _e('will run again in', 'hyper-cache'); ?> <?php echo (int)((wp_next_scheduled('hyper_cache_clean')-time())/60) ?> <?php _e('minutes', 'hyper-cache'); ?>)
                            <p class="description">
                                <?php _e('The autoclean process removes old files to save disk space.', 'hyper-cache'); ?>
                                <?php _e('If you enable the "serve expired pages to bots", you should disable the auto clean.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><?php _e('Cache folder', 'hyper-cache'); ?></th>
                        <td>
                            <?php if (defined('HYPER_CACHE_FOLDER')) { ?>
                                <?php _e('A custom cache folder is deinfed in wp-config.php', 'hyper-cache'); ?>: <code><?php echo esc_html(HYPER_CACHE_FOLDER)?></code>
                            <?php } else { ?>
                                <?php _e('A custom cache folder can be defined in wp-config.php', 'hyper-cache'); ?>
                                <code>define('HYPER_CACHE_FOLDER', '/path/to/cache/folder');</code>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="tabs-mobile">
                <table class="form-table">
                    <tr>
                        <th><?php _e('For mobile devices', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->select('mobile', array(0 => __('Use the main cache', 'hyper-cache'),
                                1 => __('Use a separated cache', 'hyper-cache'),
                                2 => __('Bypass the cache', 'hyper-cache'))); ?>

                            <p class="description">
                                <?php _e('Choose "cache separately" if you produce different content for mobile devices', 'hyper-cache'); ?><br>
                                <?php _e('See for example my <a href="http://www.satollo.net/plugins/header-footer" target="_blank">Header and Footer</a> plugin for different desktop/mobile ads injection in posts.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Mobile theme', 'hyper-cache'); ?></th>
                        <td>
                            <?php
                            $themes = wp_get_themes();
                            //var_dump($themes);
                            $list = array('' => __('Use the active blog theme', 'hyper-cache'));
                            foreach ($themes as $theme)
                                $list[$theme->stylesheet] = $theme->name;
                            ?>
                            <?php $controls->select('theme', $list); ?>
                            <p class="description">
                                <?php _e('If you have plugins which produce different content for desktop and mobile devices, you should use a separate cache for mobile.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Mobile user agents', 'hyper-cache'); ?></th>
                        <td>
                            <?php $controls->textarea('mobile_agents'); ?>
                            <?php $controls->button('reset_mobile_agents', __('Reset', 'hyper-cache' )); ?>
                            <p class="description">
                                <?php _e('One per line.', 'hyper-cache'); ?>
                                <?php _e('A "user agent" is a text which identify the kind of device used to surf the site.', 'hyper-cache'); ?>
                                <?php _e('For example and iPhone has <code>iphone</code> as user agent.', 'hyper-cache'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>


        </div>
        <p>
            <?php $controls->button('save', __('Save', 'hyper-cache')); ?>

            <?php if ($_SERVER['HTTP_HOST'] == 'www.satollo.net' || $_SERVER['HTTP_HOST'] == 'www.satollo.com') { ?>
            <?php $controls->button('delete', 'Delete options'); ?>
            <?php $controls->button('autoclean', 'Autoclean'); ?>
            <?php } ?>
        </p>

    </form>
</div>
