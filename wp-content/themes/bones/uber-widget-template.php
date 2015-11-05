<?php
/*
 * Widget Template
*/

extract( $args );
$title = apply_filters('widget_title', $instance['title']);
$num_items = $instance['num_items'] ? $instance['num_items'] : 3;
$widget_stream_id = $instance['widget_stream'];
				
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
        
        //var_dump( $rbody );

?>
<div class="hentry">
	<div class="parallax__layer">
		<div class="section-container slick-container">
			<div class="slick-slider" data-slick='{"slidesToShow": <?php echo $num_items; ?>, "slidesToScroll": <?php echo $num_items; ?>'>
		
				<?php
				for ($i = 0; $i < count( $rbody ); $i++ ) {
					echo '<div class="entry-content animate-repeat">';
						echo '<img src="' . $rbody[$i]->HubItem->thumbnail_url . '" />';
					echo '</div>';
				}
				?>
			</div>
		</div>
	</div>
</div>