<div class="wrap">
<div class="icon32" id="icon-edit-pages"></div>
<h2>WP Post URL</h2>
<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div id="post-body">
			<div id="post-body-content" class="form-wrap">
                <form name="post_form" method="post" action="" enctype="multipart/form-data">
				<div id="titlediv">
					<div class="form-field">
					<label for="title"><?php _e('Old URL') ?></label>
					<input name="old_url" type="text" />
					</div>
					Eg. http://example.com or http://www.example.com
				</div>
                <div id="titlediv">
					<div class="form-field">
					<label for="title"><?php _e('New URL') ?></label>
				<input name="new_url" type="text" />
					</div>
					Eg. http://example2.com or http://www.example2.com
				</div>
                <div style="margin-top:15px;">
                <input type="submit" name="submit" value="Submit" class="button" />
                <input type="hidden" name="act" value="save" />
                </form>
			</div>
		</div>
		<strong>Note:</strong> Please take database backup before change the url.
	</div>  
</div>