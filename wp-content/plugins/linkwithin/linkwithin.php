<?php
/*
Plugin Name: LinkWithin
Plugin URI: http://www.linkwithin.com/
Description: Displays recommended stories and associated thumbnails from your blog
Version: 0.85
Author: LinkWithin
*/

$linkwithin_code_start = "//LinkWithinCodeStart";
$linkwithin_code_end   = "//LinkWithinCodeEnd";

function linkwithin_add_hook($content) {
    if (!is_feed() && !is_page() && is_okplacefor_linkwithin()) {
        global $post, $wp_query, $linkwithin_code_start, $linkwithin_code_end;

        $permalink = get_permalink($post->ID);
        $content .= '<div class="linkwithin_hook" id="'.$permalink.'"></div>';
        $content = linkwithin_add_code($content);
    }
    return $content;
}

function linkwithin_add_code($content) {
    if (!is_feed() && !is_page() && is_okplacefor_linkwithin()) {
        global $post, $wp_query, $linkwithin_code_start, $linkwithin_code_end;

        if ($wp_query->current_post + 1 == $wp_query->post_count) {
            $embed_code = '<script>
<!-- //LinkWithinCodeStart
var linkwithin_site_id = 644149;
var linkwithin_div_class = "linkwithin_hook";
//LinkWithinCodeEnd -->
</script>
<script src="http://www.linkwithin.com/widget.js"></script>
<a href="http://www.linkwithin.com/"><img src="http://www.linkwithin.com/pixel.png" alt="Related Posts Plugin for WordPress, Blogger..." style="border: 0" /></a>';
            $content .= $embed_code;
        }
    }
    return $content;
}

function is_okplacefor_linkwithin() {
    // show the widget for all single posts, or if the user has elected to show it elsewhere
    return (is_single() || ! get_option('linkwithin_single_only'));
}

// add the linkwithin options page to the settings area
function linkwithin_add_options_page() {
    add_options_page('LinkWithin', 'LinkWithin', 'activate_plugins', 'linkwithin', 'linkwithin_options');
}

// handle/display the options page
function linkwithin_options() {
    if ($_POST['action'] == 'update') {
        update_option('linkwithin_single_only', $_POST['linkwithin_single_only']);
        echo "<div id='linkwithin-notice' class='updated fade'><p>LinkWithin settings updated.</p></div>";
    }
    $linkwithin_single_only = get_option('linkwithin_single_only');
    ?>
    <div class="wrap">
    <h2>LinkWithin Settings</h2>
    <form action="?page=linkwithin" method="POST">
    <input type="hidden" name="action" value="update" />
    <?php wp_nonce_field('update-options'); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Display</th>
            <td>
                <fieldset>
                <legend class="hidden">Placement</legend>
                <input type="hidden" name="linkwithin_single_only" value="0" id="linkwithin_single_only_false">
                <input type="checkbox" name="linkwithin_single_only" value="1" id="linkwithin_single_only" <?php if ($linkwithin_single_only) { echo 'checked="checked"'; } ?>> Show LinkWithin only on Single Posts
                </fieldset>
            </td>
        </tr>
    </table>
    <p>
    <input name="Submit" value="<?php echo __('Save Changes'); ?>" type="submit">
    </p>
    </form>
    <?php
}

// don't show widget code in excerpts
function linkwithin_display_excerpt($content){
    global $linkwithin_code_start, $linkwithin_code_end;
    $posStart = strpos($content, $linkwithin_code_start);
    $posEnd = strpos($content, $linkwithin_code_end);
    if ($posStart){
        if ($posEnd == false){
            $content = str_replace(substr($content,$posStart,strlen($content)),'',$content);
        } else {
            $content = str_replace(substr($content,$posStart,$posEnd-$posStart+strlen($linkwithin_code_end)),'',$content);
        }
    }
    $content = $content . linkwithin_add_code('');
    return $content;
}

add_filter('the_excerpt', 'linkwithin_display_excerpt');
add_filter('the_content', 'linkwithin_add_hook');
add_action('admin_menu',  'linkwithin_add_options_page');

?>
