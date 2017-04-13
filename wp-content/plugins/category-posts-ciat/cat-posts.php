<?php
/*
  Plugin Name: Category Posts CIAT Widget
  Plugin URI:
  Description: Modificado por C. Saa
  Author: James Lao
  Version: 3.3
  Author URI:
 */

// Register thumbnail sizes.
if (function_exists('add_image_size')) {
  $sizes = get_option('jlao_cat_post_thumb_sizes');
  if ($sizes) {
    foreach ($sizes as $id => $size)
      add_image_size('cat_post_thumb_size' . $id, $size[0], $size[1], true);
  }
}

class CategoryPosts extends WP_Widget {

  function __construct() {
    parent:: __construct(false, $name = 'Category Posts');
  }

  /**
   * Displays category posts widget on blog.
   */
  function widget($args, $instance) {
    global $post;
    $post_old = $post; // Save the post object.

    extract($args);

    $sizes = get_option('jlao_cat_post_thumb_sizes');

    // If not title, use the name of the category.
    if (!$instance["title"]) {
      $category_info = get_category($instance["cat"]);
      $instance["title"] = $category_info->name;
    }

    $valid_sort_orders = array('date', 'title', 'comment_count', 'rand');
    if (in_array($instance['sort_by'], $valid_sort_orders)) {
      $sort_by = $instance['sort_by'];
      $sort_order = isset($instance['asc_sort_order']) ? 'ASC' : 'DESC';
    } else {
      // by default, display latest first
      $sort_by = 'date';
      $sort_order = 'DESC';
    }

    // Get array of post info.
    $cat_posts = new WP_Query(
            "showposts=" . $instance["num"] .
            "&cat=" . $instance["cat"] .
            "&orderby=" . $sort_by .
            "&order=" . $sort_order
    );
    // echo '<div id="'.$instance["cat"].'">';
    echo '<h4>' . '<a href="' . get_category_link($instance["cat"]) . '">' . $instance["title"] . '</a>' . '</h4>';
    // Excerpt length filter
    $new_excerpt_length = create_function('$length', "return " . $instance["excerpt_length"] . ";");

    if ($instance["excerpt_length"] > 0)
      add_filter('excerpt_length', $new_excerpt_length);

    echo $before_widget;

    // Widget title
    echo $before_title;
    if ($instance["title_link"])
      echo '<a href="' . get_category_link($instance["cat"]) . '">' . $instance["title"] . '</a>';
    else
      echo $instance["title"];
    echo $after_title;

    // Post list
    $borrarc = array("[", ".", "]", "…", "...", "&nbsp;");
    while ($cat_posts->have_posts()) {
      $cat_posts->the_post();
      echo single_cat_title("", false);
      ?>
      <?php
      /* La linea de codigo con http://localhost debe quedar comentada en el momento de implementar este plugin para el sitio de DAPA */
      $url_imagen1 = img($tamanio = 'portada_url');
      $url_imagen2 = str_replace("http://", "", $url_imagen1);
      $c_slider_thumb = postimage($cat_posts->ID);
      $c_slider_thumb = str_replace('http://', '', $c_slider_thumb);
      $url_dapa_img = site_url() . "/image-thumbs/136/258/" . $c_slider_thumb;
      $url_imagen = content_url() . "/themes/ciat_dapa/scripts/tthumb.php?src=" . img($tamanio = 'portada_url') . "&h=136&w=258";
      ?>
      <img id="img-category" src="<?php echo $url_dapa_img; ?>">
      <p></p>
      <a class="post-title" href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></br>

      <?php if ($instance['date']) : ?>
        </br><p><span  class="post-date"><?php the_time("j M Y"); ?></span><span class="cate-autor">&nbsp;by&nbsp;<?php the_author(); ?></span></p>
      <?php
      endif;
      $resumen = get_the_excerpt();
      $resumen = preg_replace('/\s+?(\S+)?$/', '', substr($resumen, 0, 120));
      $resumen = str_replace($borrarc, "", $resumen);
      ?>

      <p><?php echo trim($resumen); ?></p>
      <br>
      <div style="position: absolute;bottom: 5px;"><a class="ciat-mas" href="<?php the_permalink(); ?>">Read more..</a></div>
      <?php if (isset($instance['comment_num']) && $instance['comment_num']) : ?>
        <p class="comment-num">(<?php comments_number(); ?>)</p>
      <?php endif; ?>
      <?php
    }

    // echo '</div>';
    echo $after_widget;

    remove_filter('excerpt_length', $new_excerpt_length);

    $post = $post_old; // Restore the post object.
  }

  /**
   * Form processing... Dead simple.
   */
  function update($new_instance, $old_instance) {
    /**
     * Save the thumbnail dimensions outside so we can
     * register the sizes easily. We have to do this
     * because the sizes must registered beforehand
     * in order for WP to hard crop images (this in
     * turn is because WP only hard crops on upload).
     * The code inside the widget is executed only when
     * the widget is shown so we register the sizes
     * outside of the widget class.
     */
    if (function_exists('the_post_thumbnail')) {
      $sizes = get_option('jlao_cat_post_thumb_sizes');
      if (!$sizes)
        $sizes = array();
      $sizes[$this->id] = array($new_instance['thumb_w'], $new_instance['thumb_h']);
      update_option('jlao_cat_post_thumb_sizes', $sizes);
    }

    return $new_instance;
  }

  /**
   * The configuration form.
   */
  function form($instance) {
    ?>
    <p>
      <label for="<?php echo $this->get_field_id("title"); ?>">
        <?php _e('Title'); ?>:
        <input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
      </label>
    </p>

    <p>
      <label>
        <?php _e('Category'); ?>:
        <?php wp_dropdown_categories(array('name' => $this->get_field_name("cat"), 'selected' => $instance["cat"])); ?>
      </label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id("num"); ?>">
        <?php _e('Number of posts to show'); ?>:
        <input style="text-align: center;" id="<?php echo $this->get_field_id("num"); ?>" name="<?php echo $this->get_field_name("num"); ?>" type="text" value="<?php echo absint($instance["num"]); ?>" size='3' />
      </label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id("sort_by"); ?>">
        <?php _e('Sort by'); ?>:
        <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>">
          <option value="date"<?php selected($instance["sort_by"], "date"); ?>>Date</option>
          <option value="title"<?php selected($instance["sort_by"], "title"); ?>>Title</option>
          <option value="comment_count"<?php selected($instance["sort_by"], "comment_count"); ?>>Number of comments</option>
          <option value="rand"<?php selected($instance["sort_by"], "rand"); ?>>Random</option>
        </select>
      </label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id("asc_sort_order"); ?>">
        <input type="checkbox" class="checkbox" 
               id="<?php echo $this->get_field_id("asc_sort_order"); ?>" 
               name="<?php echo $this->get_field_name("asc_sort_order"); ?>"
               <?php checked((bool) $instance["asc_sort_order"], true); ?> />
               <?php _e('Reverse sort order (ascending)'); ?>
      </label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id("title_link"); ?>">
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("title_link"); ?>" name="<?php echo $this->get_field_name("title_link"); ?>"<?php checked((bool) $instance["title_link"], true); ?> />
        <?php _e('Make widget title link'); ?>
      </label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id("excerpt"); ?>">
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("excerpt"); ?>" name="<?php echo $this->get_field_name("excerpt"); ?>"<?php checked((bool) $instance["excerpt"], true); ?> />
        <?php _e('Show post excerpt'); ?>
      </label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id("excerpt_length"); ?>">
        <?php _e('Excerpt length (in words):'); ?>
      </label>
      <input style="text-align: center;" type="text" id="<?php echo $this->get_field_id("excerpt_length"); ?>" name="<?php echo $this->get_field_name("excerpt_length"); ?>" value="<?php echo $instance["excerpt_length"]; ?>" size="3" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id("comment_num"); ?>">
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("comment_num"); ?>" name="<?php echo $this->get_field_name("comment_num"); ?>"<?php checked((bool) $instance["comment_num"], true); ?> />
        <?php _e('Show number of comments'); ?>
      </label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id("date"); ?>">
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("date"); ?>" name="<?php echo $this->get_field_name("date"); ?>"<?php checked((bool) $instance["date"], true); ?> />
        <?php _e('Show post date'); ?>
      </label>
    </p>

    <?php if (function_exists('the_post_thumbnail') && current_theme_supports("post-thumbnails")) : ?>
      <p>
        <label for="<?php echo $this->get_field_id("thumb"); ?>">
          <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("thumb"); ?>" name="<?php echo $this->get_field_name("thumb"); ?>"<?php checked((bool) $instance["thumb"], true); ?> />
          <?php _e('Show post thumbnail'); ?>
        </label>
      </p>
      <p>
        <label>
          <?php _e('Thumbnail dimensions'); ?>:<br />
          <label for="<?php echo $this->get_field_id("thumb_w"); ?>">
            W: <input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_w"); ?>" name="<?php echo $this->get_field_name("thumb_w"); ?>" value="<?php echo $instance["thumb_w"]; ?>" />
          </label>

          <label for="<?php echo $this->get_field_id("thumb_h"); ?>">
            H: <input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_h"); ?>" name="<?php echo $this->get_field_name("thumb_h"); ?>" value="<?php echo $instance["thumb_h"]; ?>" />
          </label>
        </label>
      </p>
    <?php endif; ?>

    <?php
  }

}

add_action('widgets_init', create_function('', 'return register_widget("CategoryPosts");'));
?>
