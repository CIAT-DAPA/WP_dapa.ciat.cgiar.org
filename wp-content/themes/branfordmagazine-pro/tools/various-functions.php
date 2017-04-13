<?php

///////////////////////////////////////////////////////////////////////////////////////////
/////////   					VARIOUS FUNCTIONS    					//////////////////
//////// This file contains various functions that are needed in the theme //////////////
////////////////////////////////////////////////////////////////////////////////////////
// DER PRiNZ Dashboard Widget (Just for fun)
add_action('wp_dashboard_setup', 'custom_dashboard_widgets');

function custom_dashboard_widgets() {
    //first parameter is the ID of the widget (the div holding the widget will have that ID)
    //second parameter is title (shown in the header of the widget) -> see picture below
    //third parameter is the function name we are calling to get the content of our widget
    wp_add_dashboard_widget('my_custom_widget_id', 'PRiNZ BranfordMagazine Setup', 'my_custom_widget');
}

function my_custom_widget() {
    //the content of our custom widget
    echo '<p>Do not forget to go to the ';
    echo '<a href="';
    echo bloginfo('wpurl');
    echo '/wp-admin/themes.php?page=functions.php">BranfordMagazine Options Page</a> to make the initial settings for the theme.</p>';
}

// Function to get WordPress image attachment and use tim thumb to rezize and crob
// usage: < ?php postimage(300,200); ? > or whatever dimensions you want
// requires tim thumb in your themes /scripts folder and at least WordPress 2.9

function postimage($width, $height) {
    $scriptpath = get_bloginfo('template_directory');

    /* $argsTest = array(
      'numberposts' => 1,
      'order'=> 'ASC',
      'post_mime_type' => 'image',
      'post_parent' => get_the_ID(),
      'post_status' => null,
      'post_type' => 'attachment'
      ); */

    $attachments = get_children(array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order'));
    //$attachments = get_children($argsTest);
    if (empty($attachments)) {
        echo '';
    } else if (has_post_thumbnail($post->ID)) {
        $featuredImage = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
        echo '<a class="alignleft" href="';
        echo the_permalink();
        echo '" rel="bookmark" title="Permanent Link to ';
        echo the_title();
        echo '">';
        echo '<img src="/image-thumbs/' . $height . '/' . $width . '/' . str_ireplace('http://', '', $featuredImage[0]) . '" alt="';
        echo the_title();
        echo '" />';
        echo '</a>';
    } else {
        $img = array_shift($attachments);        
        $imagelink = wp_get_attachment_image_src($img->ID, 'full');
        $image = $imagelink[0];
        echo '<a class="alignleft" href="';
        echo the_permalink();
        echo '" rel="bookmark" title="Permanent Link to ';
        echo the_title();
        echo '">';
//        echo '<img src="'.$scriptpath.'/scripts/timthumb.php?src='.$image.'&amp;w='.$width.'&amp;h='.$height.'&amp;zc=1" alt="';
        echo '<img src="/image-thumbs/' . $height . '/' . $width . '/' . str_ireplace('http://', '', $image) . '" alt="';
        echo the_title();
        echo '" />';
        echo '</a>';
    }
}

?>
<?php

//modify the excerpt lenght in the "featured articles" section on the homepage
add_filter('excerpt_length', 'my_excerpt_length');

function my_excerpt_length($length) {
    if (in_category(4)) { // put here the ID (4 by default) of the category used for the "featured" articles
        return 25; // put the number of words (default 25)
    } else {
        return 55; // show 55 words for any other excerpt (or change this number also)
    }
}

// modify the excerpt "more" appearance an make it clickable
function new_excerpt_more($more) {
    return '<br /><span class="excerpt_more"><a href="' . get_permalink() . '">' . __('[continue reading...]', 'branfordmagazine') . '</a></span>';
}

add_filter('excerpt_more', 'new_excerpt_more');


// Automatically add rel=prettyPhoto to image links
define("IMAGE_EXTENSIONS", 'jpg|jpeg|png|gif|bmp|ico');  // checking for extension, divided by pipes (no leading or trailing pipe!!)

function prinz_auto_prettyPhoto($content) {
    preg_match_all('/<a(.*?)href=[\s\"\'\`](.*?)[\"\'\`](.*?)>/i', $content, $matches);

    foreach ($matches[0] as $match) {
        if (preg_match('/href=[\s\"\'\`]+.*(' . IMAGE_EXTENSIONS . ')[\"\'\`]/i', $match)) {

            if (preg_match('/rel=[\s\"\'\`]prettyPhoto(.*?)[\"\'\`]/i', $match) == false) {
                $addrel = str_replace('>', ' rel=\'prettyPhoto\'>', $match);

                $content = str_replace($match, $addrel, $content);
            }
        }
    }
    return $content;
}

add_filter('the_content', 'prinz_auto_prettyPhoto');

/**
 * Nicer page navigation links
 * @author Sergej M�ller
 * @param  integer  $range  Links um aktuelle Seite [optional]
 */
function the_paging_bar($range = 4) {
    /* Init */
    $count = @$GLOBALS['wp_query']->max_num_pages;
    $page = @$GLOBALS['paged'];
    $ceil = ceil($range / 2);

    /* No Paging? */
    if ($count <= 1) {
        return false;
    }

    /* First page? */
    if (!$page) {
        $page = 1;
    }

    /* Calculate limit */
    if ($count > $range) {
        if ($page <= $range) {
            $min = 1;
            $max = $range + 1;
        } elseif ($page >= ($count - $ceil)) {
            $min = $count - $range;
            $max = $count;
        } elseif ($page >= $range && $page < ($count - $ceil)) {
            $min = $page - $ceil;
            $max = $page + $ceil;
        }
    } else {
        $min = 1;
        $max = $count;
    }

    /* Output */
    if (!empty($min) && !empty($max)) {
        for ($i = $min; $i <= $max; $i++) {
            echo sprintf(
                    '<a href="%s"%s>%d</a>', get_pagenum_link($i), ($i == $page ? ' class="active"' : ''), $i
            );
        }
    }
}

function img($tamanio = '') {
   if ( $images = get_children( array (
     'post_parent'    => get_the_ID(),
     'post_type'      => 'attachment',
     'numberposts'    => 1,
     'post_mime_type' => 'image'
    )));
   {
     if(!empty($images)) { //Solo añadí esta línea
         foreach( $images as $image ) {
           if($tamanio == 'llistat_url') {
           $imagen = wp_get_attachment_image_src( $image->ID, 'thumbnail' );
           return $imagen[0];
           } if($tamanio == 'destacat_url') {
           $imagen = wp_get_attachment_image_src( $image->ID, 'medium' );
           return $imagen[0];
           } if($tamanio == 'portada_url') {
           $imagen = wp_get_attachment_image_src( $image->ID, 'full' );
           return $imagen[0];
           }
           if($tamanio == 'llistat') {
           $imagen = wp_get_attachment_image( $image->ID, 'thumbnail' );
           return $imagen;
           } if($tamanio == 'destacat') {
           $imagen = wp_get_attachment_image( $image->ID, 'medium' );
           return $imagen;
           } if($tamanio == 'portada') {
           $imagen = wp_get_attachment_image( $image->ID, 'full' );
           return $imagen;
           }
         }
     } // y su respectivo cierre
   }
 }

// the following useful functions were taken from here: http://yoast.com/wordpress-functions-supercharge-theme/
// Remove Really simple discovery link
remove_action('wp_head', 'rsd_link');
// Remove Windows Live Writer link
remove_action('wp_head', 'wlwmanifest_link');
// Remove the version number
remove_action('wp_head', 'wp_generator');

// allow html in user profiles
remove_filter('pre_user_description', 'wp_filter_kses');
?>
