<?php
/*
  Template Name: CIAT Blogs Feed Category
 */

function yoast_rss_date($timestamp = null) {
	$timestamp = ($timestamp == null) ? time() : $timestamp;
	echo date(DATE_RSS, $timestamp);
}

function yoast_rss_text_limit($string, $length, $replacer = '') {
	$string = strip_tags($string);
	if (strlen($string) > $length)
		return (preg_match('/^(.*)\W.*$/', substr($string, 0, $length + 1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;
	return $string;
}
$categoria = $_GET["category"];
$args = array(
        	 'cat'                 => $categoria,
	         'orderby'             => 'date',
                 'post_type' => 'post',
                 'post_status'=> 'publish',
	         'posts_per_page'      => 6,
         );

$popPosts = new WP_Query($args);
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
echo '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" ';
echo 'xmlns:wfw="http://wellformedweb.org/CommentAPI/" ';
echo 'xmlns:dc="http://purl.org/dc/elements/1.1/" ';
echo 'xmlns:atom="http://www.w3.org/2005/Atom" ';
echo 'xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" ';
echo 'xmlns:slash="http://purl.org/rss/1.0/modules/slash/" version="2.0">';
echo '<channel>';
echo '<title>CIAT Blogs Feeds</title>';
echo '<atom:link href="'.self_link().'" rel="self" type="application/rss+xml" />';
echo '<description>The CIAT feed blogs</description>';
echo '<language>en-us</language>';
?>
		<pubDate><?php yoast_rss_date(strtotime($ps[$lastpost]->post_date_gmt)); ?></pubDate>
		<lastBuildDate><?php yoast_rss_date(strtotime($ps[$lastpost]->post_date_gmt)); ?></lastBuildDate>
		<managingEditor>cruzitorc@gmail.com</managingEditor>
	<?php
        while ($popPosts->have_posts()) : $popPosts->the_post();
	?>
        <item>
	<title><?php the_title(); ?></title>
        <link><?php the_permalink(); ?></link>
        <?php
           $post_categories = wp_get_post_categories($post->ID);
           foreach($post_categories as $c){
           $cat = get_category( $c );
           echo "<category><![CDATA[".'<a href="'.get_category_link($cat->term_id).'">'.$cat->name.'</a>'."]]></category>";
             }
         ?>
        <?php $urlimg = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
	<description>
          <?php
            $descrip = get_the_excerpt();
            $urlpos = get_permalink($post->ID);
	    echo '<![CDATA['.'<p>'.substr($descrip,0,255);
            echo '<a class="more-link" href="'.$urlpos.'" target="_blank" rel="nofollow">Read More ...</a></p>';
  	    echo	']]>'; ?>
        </description>
	<guid><?php the_guid(); ?></guid>
        <?php echo '<image>'.$urlimg.'</image>'; ?>
	</item>
	<?php
	  endwhile;
		?>
	</channel>
</rss>
