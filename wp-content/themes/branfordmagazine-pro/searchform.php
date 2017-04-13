<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" class="searchfield" />
<input type="submit" id="searchsubmit" value="<?php _e('Search','branfordmagazine');?>" class="button" />
</div>
</form>
