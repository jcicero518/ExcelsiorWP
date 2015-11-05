<?php
class Client_Rest_Widget extends WP_Widget {
 	
 	/**
	 * Class constructor - registers widget with Wordpress.
	*/	 	
  public function __construct() {

  	parent::__construct( 
  		'Client_Rest_Widget', // ID
  		__( 'Uberflip Carousel Widget', 'bones' ), // Name
  		array( 'description' => __( 'Carousel Widget', 'bones' ), ) // Addl Args
  	);
  	//wp_enqueue_script( 'jquery' );
  	//add_action( 'wp_enqueue_scripts', array( &$this, 'js' ) );
  	//wp_enqueue_script( 'widgetsjs' );
  	
  }
  
  /*
   @ param string YYYY-MM-DD (Y-m-d)
   @ param string YYYY-MM-DD
  */
  public function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ) {
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);
  }

  public function timestampDate( $format = 'Y-m-d', $timestamp = NULL ) {
    if ( $timestamp ) {
      return date( $format, $timestamp );
    }
    return date( $format );
  }
  
  public function getStreamItemDetails( $itemId ) {
    $streamItemId = $itemId;
    $rget = array();
    $rbody = array();
    
    $endPoint = 'https://api.uberflip.com';
    $hubID = '57003';
    $method = 'GetHubItemData';
    
    $api = array(
      'APIKey' => 'NGZjZGQzZGFjMWQ1ODAwNzU1N2ZiZjlhYzFjZWZkMGI=',
      'Signature' => 'MWI2MjUyYTFhMTY1ODYzMmQzZmJhMTMzNjY4ZWQzMmJlN2FhYjExYQ==',
      'Method' => $method,
      'Version' => '0.1',
      'ResponseType' => 'JSON'
    );

    // Custom stream needs hubid
    $api['HubId'] = $hubID;
    $api['ItemId'] = $streamItemId;
    
    $params = http_build_query( $api );
    $rget = wp_remote_get( $endPoint . '?' . $params );
    $rbody = json_decode( $rget['body'] );

    //var_dump( $rbody );
    $hubData = $rbody[0]->HubItem;
    
    $data = $hubData->HubItemContent->data;
    
    return $data;
    
  }
	
  /**
	 * Front end display of widget
	 *
	 * @param array $args  Widget arguments
	 * @param array $instance  Values from DB
	 */
  public function widget($args, $instance) {	
  	
  	// check for template in parent / child theme
  	$widget_template = locate_template( array( 'uber-widget-template.php' ) );
  	
  	if ( $widget_template == '' ) {
	  	
	  	include( $widget_template );
	  
	  } else {
  		
	  	extract( $args );
	    $title = apply_filters('widget_title', $instance['title']);
	    $num_items = $instance['num_items'] ? $instance['num_items'] : 3;
	    $widget_stream_id = $instance['widget_stream'];
	    
	    $todayDate = $this->timestampDate();
	    
	    wp_localize_script( 'widgetsjs', 'WidgetInfo', array(
		    'carouselItems' => $num_items
		  ));
	    wp_enqueue_script( 'widgetsjs', plugin_dir_url( __FILE__ ) . 'js/widget.js', array( 'jquery' ), '', true );
	    wp_enqueue_script( 'widgetsutil', plugin_dir_url( __FILE__ ) . 'js/widget.util.js', array( 'jquery', 'widgetsjs' ), '', true );
      wp_enqueue_script( 'widgetsmodal', plugin_dir_url( __FILE__ ) . 'js/widget.modal.js', array( 'jquery', 'widgetsjs' ), '', true );
	    wp_enqueue_style( 'widgetscss', plugin_dir_url( __FILE__ ) . 'css/widget.css', array(), '', 'all' );
	    wp_enqueue_style( 'widgetscss2', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), '', 'all' );
	    
	    $rget = array();
			$rbody = array();
      
      $endPoint = 'https://api.uberflip.com';
      $hubID = '57003';
      $method = 'GetHubStreams';
      
      $api = array(
        'APIKey' => 'NGZjZGQzZGFjMWQ1ODAwNzU1N2ZiZjlhYzFjZWZkMGI=',
        'Signature' => 'MWI2MjUyYTFhMTY1ODYzMmQzZmJhMTMzNjY4ZWQzMmJlN2FhYjExYQ==',
        'Method' => $method,
        'Version' => '0.1',
        'ResponseType' => 'JSON'
      );
      
      // Custom stream needs hubid
      $api['HubId'] = $hubID;
      
      $api['HubStreamId'] = $widget_stream_id;
      //$api['Limit'] = 22;
      $api['Method'] = 'GetHubItems';
      $params = http_build_query( $api );
      
      $rget = wp_remote_get( $endPoint . '?' . $params );
      
      $rbody = json_decode( $rget['body'] );
			
			$max_width = $num_items * 250;
			
			?>
			<div class="hentry" style="height:495px !important;background:#ccc;overflow:hidden !important">
				<div class="parallax__layer">
					<header class="article-header">
						<h1 class="page-title" itemprop="headline"><?php echo $title; ?></h1>
    			</header>
					<div class="section-container slick-container">
						<div class="slick-slider" style="height: 495px !important;width: <?php echo $max_width; ?>px" data-slick='{"slidesToShow": <?php echo $num_items; ?>, "slidesToScroll": <?php echo $num_items; ?>, "dots": true}'>
							
							<?php
  						$twitter_id = array();
  						$twitter_email = array();
							for ($i = 0; $i < count( $rbody ); $i++ ) {
									$default_type_classes = array( 'item_links' );
									$stream_type = $rbody[$i]->HubItem->type;
									if ( !empty( $stream_type ) ) {
										$default_type_classes[] = $stream_type;
									} else {
										$default_type_classes[] = 'links_no_icon';
									}
									
									if ($stream_type == 'twitter') {
									  $stream_item_id = $rbody[$i]->HubItem->id;
                    $itemDetails = $this->getStreamItemDetails( $stream_item_id );
                    //print '<pre style="display:none" class="thestrpre">';var_dump( $itemDetails );print '</pre>';
                    $twitter_id[$i] = $itemDetails->id;
                  }
									
									$more_text = '';
									switch ( $stream_type ) {
  									case 'youtube':
                      $more_text = 'View';
                      break;
                    case 'video':
                      $more_text = 'View';
                      break;
                    case 'vimeo':
                      $more_text = 'View';
                      break;
                    case 'brightcove':
                      $more_text = 'View';
                      break;
                    default:
                      $more_text = 'Read';
                  }
                  
									$thumbnail_url = '';
									if ( isset( $rbody[$i]->HubItem->mediaproxy_thumbnail_url ) ) {
  								  $thumbnail_url = $rbody[$i]->HubItem->mediaproxy_thumbnail_url;
  								} else {
    								$thumbnail_url = $rbody[$i]->HubItem->thumbnail_url;
                  }
                  
                  $cardDate = $rbody[$i]->HubItem->modified;
                  $dateDiff = $this->dateDifference( $todayDate, $cardDate );
                  $cardDaysAgo = '';
                  if ( $dateDiff == 1 ) {
                    $cardDaysAgo = $dateDiff . ' day ago';
                  } else if ( $dateDiff > 1 ) {
                    $cardDaysAgo = $dateDiff . ' days ago';
                  } else {
	                  $dateDiffHours = $this->dateDifference( $todayDate, $cardDate, '%h' );
	                  $cardDaysAgo = $dateDiffHours . ' hours ago';
	                }
                  
                  //print '<pre>';var_dump($rbody[$i]->HubItem);print '</pre>';
									
									echo '<div class="entry-content uber-card fade" >';
											echo '<div class="image-wrapper">';
												echo '<img src="' . $thumbnail_url . '" />';
											echo '</div>';
											echo '<div class="description-wrapper">';
											echo '<div class="date text-left"><abbr>' . $cardDaysAgo . '</abbr></div>';
												echo '<img class="avatar" alt="Excelsior College" src="' . plugin_dir_url( __FILE__ ) . 'images/excel_favicon.jpeg" />';
												echo '<div class="title">' . $rbody[$i]->HubItem->title . '</div>';
												echo '<div class="desc">' . $rbody[$i]->HubItem->description . '</div>';
											echo '</div>';
											
											if ( $stream_type == 'twitter' ):
											  $twitter_email['subject'][$i] = 'A Tweet from Excelsior College Hub';
											  $twitter_email['body'][$i] = 'Check out what\'s happening on Excelsior\'s College Hub';
											  $twitter_email['body'][$i] .= '\r\n' . $rbody[$i]->HubItem->description;
											  $email_string = 'mailto:?to=&amp;subject=' . $twitter_email['subject'][$i] . '&amp;body=' . $twitter_email['body'][$i];
											  ?>
											  <ul class="share-wrap" data-twitter-id="<?php echo $twitter_id[$i]; ?>">
  											  <li>
  											    <a class="reply hooked" data-share="twitter" href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $twitter_id[$i]; ?>" title="Reply">Reply</a>
  											  </li>
  											  <li>
  											    <a class="retweet hooked" data-share="twitter" href="https://twitter.com/intent/retweet?tweet_id=<?php echo $twitter_id[$i]; ?>" title="Retweet">Retweet</a>
  											  </li>
  											  <li>
  											    <a class="fav hooked" data-share="twitter" href="https://twitter.com/intent/favorite?tweet_id=<?php echo $twitter_id[$i]; ?>" title="Favorite">Favorite</a>
  											  </li>
  											  <li>
  											    <a class="email hooked" data-share="email" href="<?php echo $email_string; ?>" title="Email">Email</a>
  											  </li>
											  </ul>
                      <?php endif; ?>
                        <?php
											  //echo '<div class="card-type">' . $rbody[$i]->HubItem->type . '</div>';
                        echo '<a class="' . implode( " ", $default_type_classes ) . '" href="' . $rbody[$i]->HubItem->url . '" 
												  data-page-title="' . $rbody[$i]->HubItem->title . '" 
                          data-internal="' . $rbody[$i]->HubItem->type . '" 
                          draggable="false">' . $rbody[$i]->HubItem->type . '</a>';
                        echo '<a class="link_overlay"  
                          data-card-id="' . $rbody[$i]->HubItem->id . '" 
                          data-card-stream-id="' . $widget_stream_id . '" 
                          data-card-title="' . $rbody[$i]->HubItem->title . '" 
                          data-card-type="' . $rbody[$i]->HubItem->type . '" 
                          data-card-date="' . $rbody[$i]->HubItem->modified . '"><span class="more">' . $more_text . '</span></a>';
									echo '</div>';
							}
							?>
							
						</div>
					</div>
				</div>
			</div>
	  <?php
		}
  }
	
	/*
	 * Generate back-end form controls
   *
   * @param array $isnstance  Previously saved values from DB
  */
  public function form($instance) {	
		
		//var_dump( $instance );
    $title = esc_attr( $instance['title'] );
    $num_items = esc_attr( $instance['num_items'] );
    $widget_stream = $instance['widget_stream'];
    
    $stream_avail = false;
    
    if ( $stream_avail === get_transient( 'get_hubstream_data' ) ) {
	    $stream_avail = true;
    	$stream_transient_data = get_transient( 'uber_hubstream_data' );
    }
	  
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    
    <p>
	    <label for="<?php echo $this->get_field_id( 'num_items' ); ?>"><?php _e( 'Items to display' ); ?></label>
	    <input class="widefat" id="<?php echo $this->get_field_id( 'num_items' ); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="number" value="<?php echo $num_items; ?>" />
    </p>
     
     <?php if ( $stream_avail ): ?>
     	<p>
	     <label for="stream"><?php _e( 'Stream' ); ?></label>
	     <select name="<?php echo $this->get_field_name( 'widget_stream' ); ?>" id="<?php echo $this->get_field_name( 'widget_stream' ); ?>">
		     <option value="">--Select--</option>
		     <?php foreach ( $stream_transient_data as $data ): ?>
				   <?php if ( $widget_stream == $data['id'] ): ?>
				   		<option value="<?php echo $data['id']; ?>" selected="selected"><?php echo substr($data['title'], 0, 40); ?></option>
				   <?php else: ?>  
				   	<option value="<?php echo $data['id']; ?>"><?php echo substr($data['title'], 0, 40); ?></option>
				   <?php endif; ?>
				 <?php endforeach; ?>
				 
	     </select>
     	</p>
     <?php endif; ?>
  <?php 
  }
	
  /**
	 * Sanitize widget form values before save
	 *
	 * @param array $new_instance  Values just sent to be saved
	 * @param array $old_instance  Previously saved values from DB
	 *
	 * @return array Sanitized values to be saved to DB   
	*/
  public function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['num_items'] = strip_tags($new_instance['num_items']);
		$instance['widget_stream'] = strip_tags( $new_instance['widget_stream'] );
    
    return $instance;
  }
 
    
 
}

?>