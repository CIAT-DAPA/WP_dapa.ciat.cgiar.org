<?php
class SlideDeckSource_Youtube extends SlideDeck {
    var $label = "YouTube Videos";
    var $name = "youtube";
    var $default_lens = "tool-kit";
    var $taxonomies = array( 'videos' );
    
    var $default_options = array(
        'cache_duration' => 1800 // seconds
    );
    
    var $options_model = array(
        'Setup' => array(
            'search_or_user' => array(
                'type' => 'radio',
                'data' => "string",
                'value' => 'user',
            ),
            'youtube_username' => array(
                'value' => 'TEDtalksDirector'
            ),
            'youtube_q' => array(
                'value' => 'parkour'
            ),
            'youtube_playlist' => array(
                'value' => 'recent'
            )
        )
    );
            
    function add_hooks() {
        add_action( 'wp_ajax_update_youtube_playlists', array( &$this, 'wp_ajax_update_youtube_playlists' ) );
        add_action( 'wp_ajax_update_video_thumbnail', array( &$this, 'wp_ajax_update_video_thumbnail' ) );
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
    }
    
    /**
     * Ajax function to get the user's playlists
     * 
     * @return string A <select> element containing the playlists.
     */
    function wp_ajax_update_youtube_playlists() {
        $youtube_username = $_REQUEST['youtube_username'];
        
        echo $this->get_youtube_playlists_from_username( $youtube_username );
        exit;
    }
    
    /**
     * Ajax function to get the video's thumbnail
     * 
     * @return string an image URL.
     */
    function wp_ajax_update_video_thumbnail() {
        $video_url = $_REQUEST['video_url'];
        
        echo $this->get_video_thumbnail( $video_url );
        exit;
    }
	
    function get_youtube_playlists_from_username( $user_id = false, $slidedeck = null ){
        $playlists = false;
        
        $args = array(
            'sslverify' => false
        );
	//changes
        /* Get youtube api key which is passed as query parameter to get the response */
        //$feed_url = "https://gdata.youtube.com/feeds/api/users/{$user_id}/playlists?alt=json&orderby=updated&max-results=50";
        $last_used_youtube_api_key = get_option( $this->namespace . '_last_saved_youtube_api_key' );
        
        /* 
         * Added by Ranjith
         * Build the channel url to retrieve all channels id's asociated with the user 
         */
        
        $channel_url = 'https://www.googleapis.com/youtube/v3/channels?part=id&forUsername='.$user_id.'&key='.$last_used_youtube_api_key;
        
            
        $response = wp_remote_get( $channel_url, $args );
        
        if( !is_wp_error( $response ) ) {
			$response_json = json_decode( $response['body'] );
			$channel_count = count($response_json->items);
			
		/* Loop through the channels to get the playlist */
				
	    for( $j=0;$j<$channel_count;$j++ ){
			$channel_id = $response_json->items[$j]->id;    

		/* 
		 * Added by Ranjith
		 * Build playlist url to retrieve all the playlists available in channel 
		 */	
        if( isset( $channel_id ) && !empty( $channel_id ) ){
       		 $feed_url = 'https://www.googleapis.com/youtube/v3/playlists?part=snippet&channelId='.$channel_id.'&maxResults=50&key='.$last_used_youtube_api_key;
        }
        if( isset( $user_id ) && !empty( $user_id ) ){
            // Create a cache key
            $cache_key = $slidedeck['id'] . $feed_url;
            
            // Attempt to read the cache (no cache)
            $playlists = false;
            
            // If cache doesn't exist
            if( !$playlists ){
                $playlists = array();
                
                $response = wp_remote_get( $feed_url, $args );
                if( !is_wp_error( $response ) ) {
                    $response_json = json_decode( $response['body'] );
                    $item_count = count( $response_json->items) ;
		    //changes
                    /**
                     * If this is empty, the user probably has no playlists
                     */
                    //if( isset( $response_json->feed->entry ) && !empty( $response_json->feed->entry )){
                      //  foreach( (array) $response_json->feed->entry as $key => $entry ){
                                
                        //    $playlist_feed = $entry->{'yt$playlistId'}->{'$t'}; 
                       if( $item_count ){
					/* Build playlist items url to retreive items from playlist */     
                            for( $i=0; $i< $item_count; $i++){
					$playlist_id = $response_json->items[$i]->id;
					$playlists[] = array(
							'href' => 'https://www.googleapis.com/youtube/v3/playlistItems?playlistId='.$playlist_id,
							'title' => $response_json->items[$i]->snippet->title,
							'created' => $response_json->items[$i]->snippet->publishedAt,
							'updated' => $response_json->items[$i]->snippet->publishedAt
                            );
                        }
                        
                    }
                }else{
                    return false;
                }
            }
        }
}	
	}
        // YouTube User playlists Call
        $playlists_select = array( 
            'recent' => __( 'Recent Uploads', $this->namespace )
        );
        
        if( $playlists ){
            foreach( $playlists as $playlist ){
                $playlists_select[ $playlist['href'] ] = $playlist['title'];
            }
        }
        
        $html_input = array(
            'type' => 'select',
            'label' => "YouTube Playlist",
            'attr' => array( 'class' => 'fancy' ),
            'values' => $playlists_select
        );

        return slidedeck2_html_input( 'options[youtube_playlist]', $slidedeck['options']['youtube_playlist'], $html_input, false ); 
    }

    /**
     * Load all slides associated with this SlideDeck
     * 
     * @param integer $slidedeck_id The ID of the SlideDeck being loaded
     * 
     * @uses WP_Query
     * @uses get_the_title()
     * @uses maybe_unserialize()
     */
    function get_slides_nodes( $slidedeck ) {
        $args = array(
            'sslverify' => false
        );
        $slidedeck_id = $slidedeck['id'];
        
	$last_used_youtube_api_key = get_option( $this->namespace . '_last_saved_youtube_api_key' );
	
        if( isset( $slidedeck['options']['youtube_playlist'] ) && !empty( $slidedeck['options']['youtube_playlist'] ) ){
            switch( $slidedeck['options']['search_or_user'] ){
                case 'user':
                    switch( $slidedeck['options']['youtube_playlist'] ){
                        case 'recent':
                            // Feed of the user's recent Videos
			    //changes
                            //$feed_url = 'https://gdata.youtube.com/feeds/api/users/' . $slidedeck['options']['youtube_username'] . '/uploads?alt=json&max-results=' . $slidedeck['options']['total_slides'];
			    $feed_url = 'https://www.googleapis.com/youtube/v3/playlists?part=snippet&id='.$slidedeck['options']['youtube_username'].'&key='.$last_used_youtube_api_key;
                        break;
                        default:
                            // Feed of the Playlist's Videos
			    //changes
                            //$feed_url = $slidedeck['options']['youtube_playlist'] . '?alt=json&max-results=' . $slidedeck['options']['total_slides'];
			    $feed_url = $slidedeck['options']['youtube_playlist'] .'&part=snippet&maxResults='. $slidedeck['options']['total_slides'].'&key='.$last_used_youtube_api_key;
                        break;
                    }
                break;
                case 'search':
			//changes
                    //$feed_url = 'https://gdata.youtube.com/feeds/api/videos?alt=json&max-results=' . $slidedeck['options']['total_slides'] . '&q=' . urlencode( $slidedeck['options']['youtube_q'] );
		    $feed_url = 'https://www.googleapis.com/youtube/v3/search/?q='.urlencode( $slidedeck['options']['youtube_q']).'&part=snippet&maxResults='.$slidedeck['options']['total_slides'].'&key='.$last_used_youtube_api_key;
                break;
            }
            /* 
             * Added by Ranjith
             * Get video id's if videos from selected is username 
             */
             
            if( $slidedeck['options']['search_or_user'] != 'search' ){
            	$response = wp_remote_get( $feed_url, $args );
            	$video_ids = array();
            	if( !is_wp_error( $response ) ) {
            		$response_json = json_decode( $response['body'] );
            		$respons_item_count = count( $response_json->items );
            		for( $i=0;$i<$respons_item_count;$i++ )
            		{
            			$video_ids[] = $response_json->items[$i]->snippet->resourceId->videoId;
            		}	
            		$video_ids_string = implode(',',$video_ids);
            		
            		/* 
            		 * Added by Ranjith
            		 * Build url to get all videos from playlist 
            		 */
            		
            		$feed_url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.$video_ids_string.'&maxResults=5&key='.$last_used_youtube_api_key;
            	}
            }
            // Create a cache key
            $cache_key = $slidedeck_id . $feed_url . $slidedeck['options']['cache_duration'] . $this->name;
			
            $response = slidedeck2_cache_read( $cache_key );
            
            if( !$response ) {
                $response = wp_remote_get( $feed_url, $args );
                
                if( !is_wp_error( $response ) ) {
                    // Write the cache if a valid response
                    if( !empty( $response ) ) {
                        slidedeck2_cache_write( $cache_key, $response, $slidedeck['options']['cache_duration'] );
                    }
                }
            }
            
            // Fail if an error occured
            if( is_wp_error( $response ) ) {
                return false;
            }
            
            $response_json = json_decode( $response['body'] );
            
            // Fallback fail if response was empty
            if( empty( $response_json ) ) {
                return false;
            }
            
            $videos = array();
            $count = 0;
	    $response_count = count($response_json->items);
            /*
             * Changed by Ranjith to retreive values from response json 
                     */
		 for($i=0;$i<$response_count;$i++){
               	 if( $count < $slidedeck['options']['total_slides'] ) {
                	if($slidedeck['options']['search_or_user']!='search'){
                		$video_id = $response_json->items[$i]->id;
                        }
			else{
                		$video_id = $response_json->items[$i]->id->videoId;
                    }
                    $url = 'http://www.youtube.com/watch?v='.$video_id;
                        $videos[$i]['author_username'] = $slidedeck['options']['youtube_username'];
                        $videos[$i]['author_name'] = $slidedeck['options']['youtube_username'];
                        $videos[$i]['author_url'] = "http://www.youtube.com/user/" . $slidedeck['options']['youtube_username'];
					
			$videos[$i]['created_at'] = strtotime( $response_json->items[$i]->snippet->publishedAt );
                        $videos[$i]['video_meta'] = $this->get_video_meta_from_url( $url );		
			$videos[$i]['created_at'] = $videos[$i]['video_meta']['created_at'];
                }
                
                $count++;
            }
        }
        
        
        return $videos;
    }

    function slidedeck_form_content_source( $slidedeck, $source ) {
        // Fail silently if the SlideDeck is not this type or source
        if( !$this->is_valid( $source ) ) {
            return false;
        }
        
        $playlists_select = $this->get_youtube_playlists_from_username( $slidedeck['options']['youtube_username'], $slidedeck );
        
        include( dirname( __FILE__ ) . '/views/show.php' );
    }
    
    /**
     * Hook into slidedeck_get_source_file_basedir filter
     * 
     * Modifies the source's basedir value for relative file referencing
     * 
     * @param string $basedir The defined base directory
     * @param string $source_slug The slug of the source being requested
     * 
     * @uses SlideDeck::is_valid()
     * 
     * @return string
     */
    function slidedeck_get_source_file_basedir( $basedir, $source_slug ) {
        if( $this->is_valid( $source_slug ) ) {
            $basedir = dirname( __FILE__ );
        }
        
        return $basedir;
    }
    
    /**
     * Hook into slidedeck_get_source_file_baseurl filter
     * 
     * Modifies the source's basedir value for relative file referencing
     * 
     * @param string $baseurl The defined base directory
     * @param string $source_slug The slug of the source being requested
     * 
     * @uses SlideDeck::is_valid()
     * 
     * @return string
     */
    function slidedeck_get_source_file_baseurl( $baseurl, $source_slug ) {
        if( $this->is_valid( $source_slug ) ) {
           $baseurl = SLIDEDECK2_URLPATH . '/sources/' . basename( dirname( __FILE__ ) );
        }
        
        return $baseurl;
    }
    
    /**
     * Render slides for SlideDecks of this type
     * 
     * Loads the slides associated with this SlideDeck if it matches this Deck type and returns
     * a string of HTML markup.
     * 
     * @param array $slides_arr Array of slides
     * @param object $slidedeck SlideDeck object
     * 
     * @global $SlideDeckPlugin
     * 
     * @uses SlideDeckPlugin::process_slide_content()
     * @uses Legacy::get_slides_nodes()
     * 
     * @return string
     */
    function slidedeck_get_slides( $slides, $slidedeck ) {
        global $SlideDeckPlugin;
        
        // Fail silently if not this Deck type
        if( !$this->is_valid( $slidedeck['source'] ) ) {
            return $slides;
        }
        
        // How many decks are on the page as of now.
        $deck_iteration = 0;
        if( isset( $SlideDeckPlugin->SlideDeck->rendered_slidedecks[ $slidedeck['id'] ] ) )
        	$deck_iteration = $SlideDeckPlugin->SlideDeck->rendered_slidedecks[ $slidedeck['id'] ];
        
        // Slides associated with this SlideDeck
        $slides_nodes = $this->get_slides_nodes( $slidedeck );
        
        $slide_counter = 1;
        foreach( (array) $slides_nodes as $slide_nodes ) {
            $slide = array(
                'source' => $this->name,
                'title' => $slide_nodes['video_meta']['title'],
                'thumbnail' => (string) $slide_nodes['video_meta']['thumbnail'],
                'created_at' => $slide_nodes['created_at'],
                'classes' => array( 'has-image' ),
                'type' => 'video'
            );
            $slide = array_merge( $this->slide_node_model, $slide );
            
            $slide_nodes['source'] = $slide['source'];
            $slide_nodes['type'] = $slide['type'];
            
            // In-line styles to apply to the slide DD element
            $slide_styles = array();
            $slide_nodes['slide_counter'] = $slide_counter;
            $slide_nodes['deck_iteration'] = $deck_iteration;
            
            $slide['title'] = $slide_nodes['title'] = slidedeck2_stip_tags_and_truncate_text( $slide_nodes['video_meta']['title'], $slidedeck['options']['titleLengthWithImages'] );
            $slide_nodes['permalink'] = $slide_nodes['video_meta']['permalink'];
            $slide_nodes['excerpt'] = slidedeck2_stip_tags_and_truncate_text( $slide_nodes['video_meta']['description'], $slidedeck['options']['excerptLengthWithImages'] );
            $slide_nodes['image'] = $slide_nodes['video_meta']['full_image'];
            
            // Build an in-line style tag if needed
            if( !empty( $slide_styles ) ) {
                foreach( $slide_styles as $property => $value ) {
                    $slide['styles'] .= "{$property}:{$value};";
                }
            }
            
			if( !empty( $slide['title'] ) ) {
				$slide['classes'][] = "has-title";
			} else {
				$slide['classes'][] = "no-title";
			}
			
			if( !empty( $slide_nodes['video_meta']['description'] ) ) {
				$slide['classes'][] = "has-excerpt";
			} else {
				$slide['classes'][] = "no-excerpt";
			}
			
            // Set link target node
            $slide_nodes['target'] = $slidedeck['options']['linkTarget'];
            
            $slide['content'] = $SlideDeckPlugin->Lens->process_template( $slide_nodes, $slidedeck );
            
            $slide_counter++;
            
            $slides[] = $slide;
        }
        
        return $slides;
    }
}
?>
