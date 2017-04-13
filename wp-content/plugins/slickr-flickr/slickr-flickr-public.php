<?php
/**
 * Slickr Flickr
 *
 * Display a Flickr slideshow or a gallery in a post or widget
 *
 * @param tag -> identifies what photos to select(required)
 * @param tagmode -> set to ANY for fetching photos with different tags - default is ALL (optional)
 * @param id -> the Flickr ID of user (optional)
 * @param group -> set to y if the Flickr ID is the id of a group and not a user (optional)
 * @param use_key -> set to y to force use of API key - default is n(optional)
 * @param api_key -> 32 character alphanumeric API key (optional)
 * @param set -> used in searching sets (optional)
 * @param search -> photos, groups, friends, favorites, sets  (optional)
 * @param items -> maximum number photos to display in the gallery or slideshow (optional)
 * @param type -> gallery, galleria or slideshow (optional)
 * @param size -> default is medium, m640, small, large, original (optional)
 * @param orientation -> default is landscape or portrait (optional)
 * @param captions -> whether captions are on or off (optional)
 * @param delay -> delay in seconds between each image in the slideshow (optional)
 * @param autoplay -> on or off - default is on (optional)
 * @param pause -> on or off - default is off - only applies to slideshow (optional)
 * @param width -> width of slideshow (optional)
 * @param height -> height of slideshow (optional)
 * @param start -> first slide in the slideshow (optional)
 * @param link -> url to visit on clicking slideshow (optional)
 * @param attribution -> credit the photographer (optional)
 * @param sort -> sort order of photos (optional)
 * @param direction -> sort order of photos (optional)
 * @param descriptions -> show descriptions beneath title caption - on or off (optional)
 * @param flickr_link -> include a link to the photo on Flickr on the lightbox - on or off (optional)
 * @param photos_per_row -> maximum number number of thumbnails in a gallery row (optional)
 * @param thumbnail_size -> default is square 75x75px (optional)
 * @param thumbnail_scale -> default 100% (optional)
 * @param border -> whether slideshow border is on or off (optional)
*/
require_once(dirname(__FILE__).'/slickr-flickr-photo.php');

function slickr_flickr_display ($attr) {
  $params = shortcode_atts( slickr_flickr_get_options(), $attr ); //apply plugin defaults
  if (($params['type']=="gallery") && ($attr['captions']!="on")) $params['captions'] = "off";
  if (empty($params['id'])) return "<p>Please specify a Flickr ID for this ".$params['type']."</p>";
  if (empty($params['api_key']) && ($params['use_key'] == "y")) return "<p>Please add your Flickr API Key in Slickr Flickr Admin settings to fetch more than 20 photos.</p>";
  if ( (!empty($params['tagmode'])) && empty($params['tag']) && ($params['search']=="photos")) return "<p>Please set up a Flickr tag for this slideshow</p>";

  $rand_id = rand(1,1000);
  $divid = "flickr_".strtolower(str_replace(array(" ","`","-",","),"",$params['tag'])).'_'.$rand_id; //strip spaces, backticks, dashes and commas
  $divclear = '<div style="clear:both"></div>';
  $attribution = empty($params['attribution'])?"":('<p class="slickr-flickr-attribution align'.$params['align'].'">'.$params['attribution'].'</p>');
  switch ($params['type']) {
    case "slideshow": {
        $scriptdelay = '<script type="text/javascript">jQuery("#'.$divid.'").data("delay","'.$params['delay'].'");jQuery("#'.$divid.'").data("autoplay","'.$params['autoplay'].'");</script>';
        $link = empty($params['link'])?($params['pause'] == "on"?"slickr_flickr_toggle_slideshows()":"slickr_flickr_next_slide(this)"):("window.location='".$params['link']."'");
        $border = $params['border']=='on'?' class="border"':'';
        $style="";
        if (slickr_flickr_set_slideshow_size($params)) {
            $width = $params['width'] ? (' width:'.$params['width'].'px;'):'';
            $height = $params['height'] ? (' height:'.$params['height'].'px;'):'';
            $overflow='overflow-y:hidden;overflow-x:hidden';
            $style = ' style="'.$width.$height.$overflow.'"';
        }
        $divstart = $attribution.'<div id="'.$divid.'"'.$style.' class="slickr-flickr-slideshow '.$params['orientation'].' '.$params['size'].($params['descriptions']=="on" ? " descriptions" : "").'" onClick="'.$link.';">';
        $divend = '</div>'.$divclear.$scriptdelay;
        break;
        }
   case "galleria": {
        $scriptdelay = '<script type="text/javascript">jQuery("#'.$divid.'").data("delay","'.$params['delay'].'");jQuery("#'.$divid.'").data("autoplay","'.$params['autoplay'].'");jQuery("#'.$divid.'").data("captions","'.$params['captions'].'");jQuery("#'.$divid.'").data("descriptions","'.$params['descriptions'].'");</script>';
        $nav = <<<NAV
<p class="nav {$params['size']}"><a href="#" class="prevSlide">&laquo; previous</a> | <a href="#" class="startSlide">start</a> | <a href="#" class="stopSlide">stop</a> | <a href="#" class="nextSlide">next &raquo;</a></p>
NAV;
        $divstart = '<div id="'.$divid.'" class="slickr-flickr-galleria '.$params['orientation'].' '.$params['size'].'">'.$attribution.$nav.'<ul>';
        $divend = '</ul>'.$divclear.$attribution.$nav.'</div>'.$scriptdelay;
        break;
        }
   default: {
        $thumb_rescale= false;
        switch ($params["thumbnail_size"]) {
          case "thumbnail": $thumb_width = 100; $thumb_height = 75; $thumb_rescale = true; break;
          case "small": $thumb_width = 240; $thumb_height = 180; $thumb_rescale = true; break;
          default: $thumb_width = 75; $thumb_height = 75;
        }
        if ($params["orientation"]=="portrait" ) { $swp = $thumb_width; $thumb_width = $thumb_height; $thumb_height = $swp; }

        if ($params["thumbnail_scale"] != 100) {
          $thumb_rescale = true;
          $thumb_width = round($thumb_width * $params["thumbnail_scale"] / 100);
          $thumb_height = round($thumb_height * $params["thumbnail_scale"] / 100);
        }
        $thumb_scale = $thumb_rescale ? (' width="'.$thumb_width.'" height="'.$thumb_height.'"') : '';

        $gallery_style = "";
        $li_style = "";
        if ($params['photos_per_row'] > 0) {
            $li_width = ($thumb_width + 15);
            $gallery_width = 1 + ($li_width *  $params['photos_per_row']);
            $gallery_style = ' style="width:'.$gallery_width.'px"';
            $li_style = ' style="width:'.$li_width.'px"';
            }

        $divstart = '<div id="'.$divid.'" class="slickr-flickr-gallery">'. $attribution . '<ul'.$gallery_style.'>';
        $scriptdelay = '<script type="text/javascript">jQuery("#'.$divid.'").data("delay","'.$params['delay'].'");jQuery("#'.$divid.'").data("autoplay","'.$params['autoplay'].'");</script>';
        $divend = '</ul></div>'.$divclear.($params['lightbox'] == "sf-lbox-auto" ? $scriptdelay : "");
        switch ($params['lightbox']) {
          case "shadowbox": $lightboxrel = 'rel="shadowbox['.$rand_id.']"'; break;
          case "thickbox": $lightboxrel = 'rel="thickbox['.$rand_id.']" class="thickbox" '; break;
          case "colorbox":
          case "slimbox":
          case "shutter":   $lightboxrel = 'rel="lightbox['.$rand_id.']"';  break;
          default: $lightboxrel = 'rel="'.$params['lightbox'].'"';
          }
        }
  }
  $photos = slickr_flickr_feed($params);
  if (! is_array($photos)) return $photos; //return error message if an array of photos is not returned
  $r = -1;
  $numitems = count($photos);
  if ($numitems > 1) {
     if ($params['start'] == "random")
        $r = rand(1,$numitems);
     else
        $r = is_numeric($params['start']) && ($params['start'] < $numitems) ? $params['start'] : $numitems;
     }
  $s = "";
  $i = 0;
  foreach ( $photos as $photo ) {
    $i++;
    $title = $photo->get_title();
    $description = $photo->get_description();
    $link= $photo->get_link();
    $oriented = $photo->get_orientation();
    $full_url = $params['size']=="original" ? $photo->get_original() : $photo->resize($params['size']);
    $thumb_url = $photo->resize($params['thumbnail_size']);
    $captiontitle = $params["flickr_link"]=="on"?("<a title='Click to see photo on Flickr' href='". $link . "'>".$title."</a>"):$title;
    $alt = $params["descriptions"]=="on"? $description : "";
    $imgsize="";
    if ($oriented != $params['orientation']) $imgsize = $oriented=="landscape"?'width="80%"':'height="90%"';
    switch ($params['type']) {
       case "slideshow": {
            $caption = $params['captions']=="off"?"":('<p'.$border.'>'.$captiontitle.'</p>'.$alt);
            $s .=  '<div' . ($r==$i?' class="active"':'') .'><img '.$imgsize.$border.' src="'.$full_url.'" alt="'.$alt.'" title="'.$title.'" />'.$caption.'</div>';
            break;
        }
       case "galleria": {
            $s .= '<li' . ($r==$i?' class="active"':'') .'><img '.$imgsize.' src="'.$full_url.'" alt="'.$alt.'" title="'.$title.'" /></li>';
            break;
        }
        default: {
            $thumbcaption = $params['captions']=="off"?"":('<br/><span class="slickr-flickr-caption">'.$title.'</span>');
            $lightbox_title = $captiontitle . ($params["descriptions"]=="on" ? $description : "");
            $s .= '<li'.$li_style.'><a '.$lightboxrel.' href="'.$full_url.'" title="'.$lightbox_title.'"><img src="'.$thumb_url.'"'.$thumb_scale.' alt="'.$alt.'" title="'.$title.'" />'.$thumbcaption.'</a></li>';
        }
    }
  }
  return $divstart . $s . $divend;
}


function slickr_flickr_feed($params) {
  $photos = array();
  if ($params['cache']=='off') slickr_flickr_check_clear_cache();
  $multi_fetch = slickr_flickr_set_fetch_mode($params);
  $striptag = strtolower(str_replace(" ","",$params['tag']));
  $tags = empty($striptag) ? "" : ("&tags=".$striptag);
  $group = strtolower(substr($params['group'],0,1));
  if ($params['use_key'] == 'y') {
        switch($params['search']) {
           case "favorites": {
                $flickr_feed = "http://api.flickr.com/services/rest/?method=flickr.favorites.getPublicList&lang=en-us&format=feed-rss_200&api_key=".$params['api_key']."&user_id=".$params['id'];
                break;
          }
           case "friends": {
                return '<p>Api key based search is not available for friends photos in this release of Slickr Flickr</p>';
                break;
           }
           case "groups": {
                $flickr_feed = "http://api.flickr.com/services/rest/?method=flickr.groups.pools.getPhotos&lang=en-us&format=feed-rss_200&api_key=".$params['api_key']."&group_id=".$params['id'];
                break;
           }
           case "galleries": {
                $flickr_feed = "http://api.flickr.com/services/rest/?method=flickr.galleries.getPhotos&lang=en-us&format=feed-rss_200&api_key=".$params['api_key']."&gallery_id=".$params['id'];
                break;
           }
           case "sets": {
                return '<p>Api key based search is not available for photosets in this release of Slickr Flickr</p>';
                break;
           }
          default: {
                $tagmode = empty($params['tagmode']) ? "" : ("&tag_mode=".($params['tagmode']=="all"?"all":"any"));
                $id = $group=="y" ? "group_id" : "user_id" ;
                $flickr_feed = "http://api.flickr.com/services/rest/?method=flickr.photos.search&lang=en-us&format=feed-rss_200&api_key=".$params['api_key']."&".$id."=".$params['id'].$tagmode.$tags;
          }
       }
   } else {
        switch($params['search']) {
           case "favorites": {
                $flickr_feed = "http://api.flickr.com/services/feeds/photos_faves.gne?lang=en-us&format=rss_200&nsid=".$params['id']; break;
           }
           case "groups": {
                $flickr_feed = "http://api.flickr.com/services/feeds/groups_pool.gne?lang=en-us&format=feed-rss_200&id=".$params['id'];  break;
                break;
           }
           case "friends": {
                $flickr_feed = "http://api.flickr.com/services/feeds/photos_friends.gne?lang=en-us&format=feed-rss_200&user_id=".$params['id']."&display_all=1";  break;
                break;
           }
           case "sets": {
                $set = empty($params['set']) ? $params['tag'] : $params['set'];
                $flickr_feed = "http://api.flickr.com/services/feeds/photoset.gne?lang=en-us&format=feed-rss_200&nsid=".$params['id']."&set=".$set;  break;
                break;
           }
           default: {
                $tagmode = empty($params['tagmode']) ? "" : ("&tagmode=".($params['tagmode']=="any"?"any":"all"));
                $id = $group=="y" ? "g" : "id" ;
                $flickr_feed = "http://api.flickr.com/services/feeds/photos_public.gne?lang=en-us&format=feed-rss_200&".$id."=".$params['id'].$tagmode.$tags;
           }
        }
   }

  if ($multi_fetch) {
    $more_photos = true;
    $total_photos = 0;
    $page=0;
    $per_page=min(50,$params['items']);
    $flickr_feed .= "&per_page=".$per_page."&page=##PAGE##";
    while ($more_photos) {
        $page++;
        $rss = fetch_feed(str_replace('##PAGE##',$page,$flickr_feed));
        if ( is_wp_error($rss) ) return "<p>Error fetching Flickr photos: ".$rss->get_error_message()."</p>";  //exit if cannot fetch the feed
        $numitems = $rss->get_item_quantity($per_page);
        if ($numitems == 0)  {
            if ($total_photos == 0) return '<p>No photos available right now.</p><p>Please verify your settings, clear your RSS cache on the Slickr Flickr Admin page and check your <a target="_blank" href="'.$flickr_feed.'">Flickr feed</a></p>';
            $more_photos = false;
        }
        if ($numitems < $per_page) $more_photos = false;
        $rss_items = $rss->get_items(0, $numitems);
        foreach ( $rss_items as $item ) {
            $photos[] = new slickr_flickr_photo($item);
            $total_photos++;
            if ($total_photos >= $params['items']) { $more_photos = false;  break; }
        }
    }
  } else {
    $rss = fetch_feed($flickr_feed);
    if ( is_wp_error($rss) ) return "<p>Error fetching Flickr photos: ".$rss->get_error_message()."</p>";  //exit if cannot fetch the feed

    $numitems = $rss->get_item_quantity($params['items']);
    if ($numitems == 0)  return '<p>No photos available right now.</p><p>Please verify your settings, clear your RSS cache on the Slickr Flickr Admin page and check your <a target="_blank" href="'.$flickr_feed.'">Flickr feed</a></p>';
    $rss_items = $rss->get_items(0, $numitems);
    foreach ( $rss_items as $item ) {
        $photos[] = new slickr_flickr_photo($item);
    }
  }
  if (!empty($params['sort'])) $photos = slickr_flickr_sort ($photos, $params['sort'], $params['direction']);
  return $photos; //return array of photos
}

function slickr_flickr_check_clear_cache() {
  if (slickr_flickr_check_validity()) slickr_flickr_clear_cache();
}

function slickr_flickr_set_fetch_mode($params) {
  return ($params['use_key']=='y') && ($params['items'] > 50) && (slickr_flickr_check_validity()) ;
}

function slickr_flickr_set_slideshow_size($params) {
  return (($params['width']) || ($params['height'])) && (slickr_flickr_check_validity()) ;
}


function slickr_flickr_sort ($items, $sort, $direction) {
	$do_sort = ($sort=="date") || ($sort=="title") || ($sort=="description");
    $direction = strtolower(substr($direction,0,3))=="des"?"descending":"ascending";
    if ($sort=="date") { foreach ($items as $item) { if (!$item->get_date()) { $do_sort = false; break; } } }
    if ($sort=="description") { foreach ($items as $item) { if (!$item->get_description()) { $do_sort = false; break; } } }
    $ordered_items = $items;
    if ($do_sort) usort($ordered_items, 'sort_by_'.$sort.'_'.$direction);
    return $ordered_items;
}

function sort_by_description_descending($a, $b) { return strcmp($b->get_description(),$a->get_description()); }
function sort_by_description_ascending($a, $b) { return strcmp($a->get_description(),$b->get_description()); }
function sort_by_title_descending($a, $b) { return strcmp($b->get_title(),$a->get_title()); }
function sort_by_title_ascending($a, $b) { return strcmp($a->get_title(),$b->get_title()); }
function sort_by_date_ascending($a, $b) { return ($a->get_date() <= $b->get_date()) ? -1 : 1; }
function sort_by_date_descending($a, $b) { return ($a->get_date() > $b->get_date()) ? -1 : 1; }

function slickr_flickr_header() {

    $path = SLICKR_FLICKR_PLUGIN_URL;

    $options = slickr_flickr_get_options();

    switch ($options['lightbox']) {
     case 'sf-lbox-manual': {
        wp_enqueue_style('lightbox', $path."/lightbox/css/jquery.lightbox-0.5.css");
        wp_enqueue_script('lightbox', $path."/lightbox/js/jquery.lightbox-0.5.js", array('jquery'));
        }
     case 'sf-lbox-auto': {
        wp_enqueue_style('lightbox', $path."/lightbox-slideshow/lightbox.css");
        wp_enqueue_script('lightbox', $path."/lightbox-slideshow/lightbox-slideshow.js", array('jquery'));
        break;
        }
     case 'shadowbox': {
        wp_enqueue_style('shadowbox', $path."/shadowbox/shadowbox.css");
        wp_enqueue_script('shadowbox', $path."/shadowbox/shadowbox.js", array('jquery'));
        }
    case 'thickbox': { //preinstalled by wordpress but needs to be activated
       wp_enqueue_style('thickbox');
       wp_enqueue_script('thickbox');
       break;
    }
    default: { break; } //use another lightbox plugin such as fancybox, shutter, colorbox
    }
    wp_enqueue_style('galleria', $path."/galleria/galleria.css");
    wp_enqueue_style('slickr-flickr', $path."/slickr-flickr.css");
    wp_enqueue_script('galleria', $path."/galleria/galleria.noconflict.js", array('jquery'));
    wp_enqueue_script('slickr-flickr', $path."/slickr-flickr.js", array('jquery','galleria'));
}

add_shortcode('slickr-flickr', 'slickr_flickr_display');
add_action('init', 'slickr_flickr_header');
add_filter('widget_text', 'do_shortcode', 11);
?>