<?php
/**
 * RSS 0.92 Feed Template for displaying RSS 0.92 Posts feed.
 *
 * @package WordPress
 */
$postCount = 200; // The number of posts to show in the feed
$limit = 0;
$count = 0;
if ($_GET['limit'] && $_GET['limit'] != 0) $limit = $_GET['limit'];     
$posts = query_posts('showposts=' . $postCount);
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
echo "<?xml version='1.0' encoding='" . get_option('blog_charset') . "'?" . ">";
?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/" 
     <?php do_action('rss2_ns'); ?>>
    <channel>
        <title><?php bloginfo_rss('name'); ?> - Feed</title>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
        <link><?php bloginfo_rss('url') ?></link>
        <description><?php bloginfo_rss('description') ?></description>
        <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
        <language><?php echo get_option('rss_language'); ?></language>
        <sy:updatePeriod><?php echo apply_filters('rss_update_period', 'hourly'); ?></sy:updatePeriod>
        <sy:updateFrequency><?php echo apply_filters('rss_update_frequency', '1'); ?></sy:updateFrequency>
        <?php do_action('rss2_head'); ?>
        <?php while (have_posts()) : the_post(); ?>
          <?php if($limit != 0 && $count == $limit) break; ?>
            <item>
                <title><?php the_title_rss(); ?></title>
                <link><?php the_permalink_rss(); ?></link>
                <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
                <dc:creator><?php the_author(); ?></dc:creator>
                <?php
                // Categories
                $post_categories = wp_get_post_categories(get_the_ID());
                foreach ($post_categories as $c) {
                    $cat = get_category($c);
                    $category_link = get_category_link($cat->cat_ID);
                    ?>
                    <category><?php echo $category_link; ?></category>
                    <?php
                }
                ?>
                <?php
                // Featured image:
                $attachments = get_children(array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order'));                
                if (get_the_post_thumbnail(get_the_ID) != '') {
                    $featuredImage = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID), 'single-post-thumbnail');
                    echo '<image>';
                    echo '<url>'.$featuredImage[0].'</url>';
                    echo '</image>';
                } else {                    
                    $img = array_shift($attachments);
                    echo '<image>';
                    echo '<title>'.$img->post_title.'</title>';
                    echo '<url>'.$img->guid.'</url>';
                    echo '</image>';
                }
                ?>
                <guid isPermaLink="false"><?php the_guid(); ?></guid>
                <description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
                <content:encoded><![CDATA[<?php the_excerpt_rss() ?>]]></content:encoded>
                <?php rss_enclosure(); ?>
                <?php $img = img($tamanio = 'portada_url'); 
                  if ($img): ?>
                    <enclosure url="<?php echo $img?>" type="image/jpg" length="5000"/>               
                <?php endif;?>
                <?php do_action('rss2_item'); ?>
            </item>
            <?php $count++; ?>
        <?php endwhile; ?>
    </channel>
</rss>