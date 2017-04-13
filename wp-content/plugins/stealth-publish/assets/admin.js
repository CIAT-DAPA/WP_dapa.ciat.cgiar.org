(function($) {

	// TODO: Get column and field names via localized variables.

	// Copy of the WP inline edit post function.
	var $wp_inline_edit = inlineEditPost.edit;

	// Overwrite the function.
	inlineEditPost.edit = function( id ) {

		// Invoke the original function.
		$wp_inline_edit.apply( this, arguments );

		var $post_id = 0;
		if ( typeof( id ) == 'object' ) {
			$post_id = parseInt( this.getId( id ) );
		}

		if ( $post_id > 0 ) {
			// Define the edit row.
			var $edit_row = $( '#edit-' + $post_id );
			var $post_row = $( '#post-' + $post_id );

			// Get the data.
			var $stealth_publish = !! $('.column-date .stealth_publish', $post_row ).size();

			// Populate the data.
			$( ':input[name="stealth_publish"]', $edit_row ).prop('checked', $stealth_publish );
		}
	};

})(jQuery);
