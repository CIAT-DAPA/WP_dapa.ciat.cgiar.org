<?php if (function_exists('wp_list_comments')) { ?>
<?php include(TEMPLATEPATH . '/includes/comments2.php'); ?>
<?php } else { include(TEMPLATEPATH . '/includes/comments1.php'); } ?>