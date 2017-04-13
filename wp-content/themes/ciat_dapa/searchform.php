<form class="art-search" method="get" name="searchform" action="<?php bloginfo('url'); ?>/">
  <input class="art-search-text" name="s" type="text" value="<?php echo esc_attr(get_search_query()); ?>" />
  <input class="art-search-button" type="submit" value="" />       
</form>