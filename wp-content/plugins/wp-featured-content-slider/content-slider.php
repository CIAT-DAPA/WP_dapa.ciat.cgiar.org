<?php
$c_slider_direct_path = get_bloginfo('wpurl') . "/wp-content/plugins/wp-featured-content-slider";
$c_slider_class = c_slider_get_dynamic_class();
?>
<script type="text/javascript">
  jQuery('#featured_slider ul').cycle({
    fx: 'scrollHorz',
    prev: '.feat_prev',
    next: '.feat_next',
    speed: 800,
    timeout: <?php $c_slider_timeout = get_option('timeout');
if (!empty($c_slider_timeout)) {
  echo $c_slider_timeout;
} else {
  echo 4000;
} ?>,
    pager: 'null'
  });
</script>
<style>
  #featured_slider {
    float: left;
    position: relative;
    margin: 20px 132px;
    /*background-color: #<?php $c_slider_bg = get_option('feat_bg');
if (!empty($c_slider_bg)) {
  echo $c_slider_bg;
} else {
  echo "FFF";
} ?>;
    border: 1px solid #<?php $c_slider_border = get_option('feat_border');
if (!empty($c_slider_border)) {
  echo $c_slider_border;
} else {
  echo "CCC";
} ?>;*/
    width: <?php $c_slider_width = get_option('feat_width');
if (!empty($c_slider_width)) {
  echo $c_slider_width;
} else {
  echo "1000";
} ?>px;
    background-color: #e1e2e2;
  }
  #featured_slider ul, #featured_slider ul li {
    list-style: none !important;
    border: none !important;
    float: left;
    margin-left: 0px;
    margin-top: 0px;
    width: <?php $c_slider_width = get_option('feat_width');
if (!empty($c_slider_width)) {
  echo $c_slider_width;
} else {
  echo "1000";
} ?>px;
    height: <?php $c_slider_height = get_option('feat_height');
if (!empty($c_slider_height)) {
  echo $c_slider_height;
} else {
  echo "275";
} ?>px;
  }
  #featured_slider .img_right {
    float: left;
    width: <?php $c_slider_img_width = get_option('img_width');
if (!empty($c_slider_img_width)) {
  echo $c_slider_img_width;
} else {
  echo "360";
} ?>px;
    height: <?php $c_slider_img_height = get_option('img_height');
if (!empty($c_slider_img_height)) {
  echo $c_slider_img_height;
} else {
  echo "270";
} ?>px;
  }
  #featured_slider .img_right img {
    padding: 4px;
    width: <?php $c_slider_img_width = get_option('img_width');
if (!empty($c_slider_img_width)) {
  echo $c_slider_img_width;
} else {
  echo "360";
} ?>px;
    height: <?php $c_slider_img_height = get_option('img_height');
if (!empty($c_slider_img_height)) {
  echo $c_slider_img_height;
} else {
  echo "270";
} ?>px;
  }
  #featured_slider .content_left {
    float: right;
    color: #<?php $c_slider_text_color = get_option('text_color');
if (!empty($c_slider_text_color)) {
  echo $c_slider_text_color;
} else {
  echo "9c9c9c";
} ?>;
    width: <?php $c_slider_text_width = get_option('text_width');
if (!empty($c_slider_text_width)) {
  echo $c_slider_text_width;
} else {
  echo "486";
} ?>px;
  }
  #featured_slider .content_left p {
    margin-top: -5px;
    margin-left:30px;
    margin-right: 45;
    font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
    font-size: 12px;
    line-height: 19px;
    text-align: justify;
    color: #<?php $c_slider_text_color = get_option('text_color');
if (!empty($c_slider_text_color)) {
  echo $c_slider_text_color;
} else {
  echo "9c9c9c";
} ?>;
  }
  a.more-link {
    display: block;
    margin-left: 425px;
    margin-top: 40px;
    text-decoration: none;
    text-align: center;
    width: 100px;
    color: #FFF;
    letter-spacing: 1px;
    height: 20px;
    padding-top: 5px;
    padding-bottom: 5px;
    background: #51a77c;
  }
  a.more-link:hover {
    text-decoration: none;
    background-color: #626262;
  }
  #featured_slider .content_left h2 {
    margin: 0;
    padding: 30px 50px 30px 30px;
    font-size: 12px;
    color: #ccc;
    text-decoration: none;
    font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
    font-weight: bold;
    line-height: 15px;
    text-align: justify;
  }
  #featured_slider .content_left h2 a:link, #featured_slider .content_left h2 a:hover {
    text-decoration: none;
  }
  #featured_slider .content_left h2 a {
    font-size: 13px;
    line-height: 17px;
    color: #515151;
  }
  #featured_slider .feat_prev {
    background: transparent url(<?php echo $c_slider_direct_path; ?>/images/slider-previous.png) no-repeat;
    clear: both;
    cursor: pointer;
    display: block;
    height: 60px;
    margin-top: 110px;
    margin-left: 10px;
    overflow: hidden;
    position: absolute;
    width: 30px;
    z-index: 11;
  }
  #featured_slider .feat_prev:hover {
    background-position: -30px 0;
  }
  #featured_slider .feat_next {
    background: transparent url(<?php echo $c_slider_direct_path; ?>/images/slider-next.png) no-repeat;
    clear: both;
    cursor: pointer;
    display: block;
    height: 60px;
    margin-top:110px;
    margin-left: 960px;
    overflow: hidden;
    position: absolute;
    width: 30px;
    z-index: 11;
  }
  #featured_slider .feat_next:hover {
    background-position: -30px 0;
  }
  .<?php echo $c_slider_class; ?> {
    font-size: 10px;
    float: right;
    clear: both;
    position: relative;
    top: -10px;
    background-color: #<?php $c_slider_border = get_option('feat_border');
if (!empty($c_slider_border)) {
  echo $c_slider_border;
} else {
  echo "CCC";
} ?>;
    padding: 3px 3px;
    line-height: 10px !important;
  }
</style>
<div id="featured_slider">
  <ul id="slider">
<?php
$c_slider_sort = get_option('sort');
if (empty($c_slider_sort)) {
  $c_slider_sort = "post_date";
}
$c_slider_order = get_option('order');
if (empty($c_slider_order)) {
  $c_slider_order = "DESC";
}
$c_slider_limit = get_option('limit');
if (empty($c_slider_limit)) {
  $c_slider_limit = 250;
}
$c_slider_points = get_option('points');
if (empty($c_slider_points)) {
  $c_slider_points = "";
}
$c_slider_post_limit = get_option('limit_posts');
if (empty($c_slider_limit_posts)) {
  $c_slider_limit_posts = "7";
}
global $wpdb;
global $post;
$args = array('meta_query' => array(array('key' => 'feat_slider','value' => '1',)), 'suppress_filters' => 0, 'post_type' => array('post', 'page'), 'orderby' => $c_slider_sort, 'order' => $c_slider_order, 'numberposts' => $c_slider_post_limit);
$myposts = get_posts($args);
foreach ($myposts as $key => $post) : setup_postdata($post);
  $c_slider_thumb = postimage($post->ID)
  /* La linea de codigo con http://localhost debe quedar comentada en el momento de implementar este plugin para el sitio de DAPA */
  /* $c_slider_thumb = content_url()."/themes/ciat_dapa/scripts/tthumb.php?src=".$c_slider_thumb."&h=270&w=500"; */
  /* La linea de codigo que sigue debe descomentarce para el sitio de dapa */
//  $c_slider_thumb = site_url() . "/image-thumbs/270/500/" . str_replace("http://", "", $c_slider_thumb1);
  /* $c_slider_thumb = "http://dapa.ciat.cgiar.org/image-thumbs/270/500/".str_replace("http://","",$c_slider_thumb1); */
  ?>	
      <li><div class="img_right"><a href="<?php the_permalink(); ?>"><img src="<?php echo $c_slider_thumb; ?>" /></a></div><div class="content_left"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo c_slider_cut_text(get_the_content(), $c_slider_limit); ?>
            <a class="more-link" href="<?php the_permalink(); ?>"><?php echo $c_slider_points; ?></a>
          </p>
        </div>
      </li>	
<?php endforeach; ?>
  </ul>
  <div class="feat_next"></div>
  <div class="feat_prev"></div>
</div>
<?php //echo "<pre>CCC".print_r($myposts,true)."</pre>";?>
