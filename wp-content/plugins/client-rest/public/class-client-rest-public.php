<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Client_Rest
 * @subpackage Client_Rest/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Client_Rest
 * @subpackage Client_Rest/public
 * @author     Your Name <email@example.com>
 */
class Client_Rest_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/client-rest-public.css', array(), $this->version, 'all' );
	}

  public function crest_ga_display() {
    $ga = get_option( 'crest_ga_options' );

    if ( !empty( $ga['crest_ga_tracking_code'] ) && !is_admin() ) {
      $code = $ga['crest_ga_tracking_code'];
      ?>
      <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '<?php echo $code; ?>', 'auto');
      ga('send', 'pageview');
      </script>
      <?php
    }
  }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/client-rest-public.js', array( 'jquery' ), $this->version, true );

		wp_register_script( 'widgetsjs', plugin_dir_url( __FILE__ ) . 'js/widget.js', array( 'jquery' ), $this->version, true );

		 // Create a nonce first to ensure this can be validated as a legitimate request
    /*$title_nonce = wp_create_nonce( 'cr_ajax_obj_wp_nonce' );

    wp_localize_script( $this->plugin_name, 'cr_ajax_obj', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce' => $title_nonce
      )
    );*/

    $title_nonce = wp_create_nonce( 'cr_ajax_obj_wp_nonce' );
    //if ( !wp_verify_nonce( '_ajax_nonce', 'cr_ajax_obj_wp_nonce' );

		wp_localize_script( $this->plugin_name, 'cr_ajax_obj', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce' => $title_nonce
      )
    );

	}

	public function crest_get_stream_detail() {
		$nonce = wp_verify_nonce( '_ajax_nonce', 'cr_ajax_obj_wp_nonce' );
		/*switch ( $nonce ) {

    case 1:
        echo 'Nonce is less than 12 hours old';
    break;

    case 2:
        echo 'Nonce is between 12 and 24 hours old';
    break;

    default:
        exit( 'Nonce is invalid' );
		}*/
		//check_ajax_referer( 'cr_ajax_obj_wp_nonce', '_ajax_nonce' );
		check_ajax_referer( 'cr_ajax_obj_wp_nonce' );
  	$itemId = $_POST['card_id'];

  	$rbody = '';

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
    $api['ItemId'] = $itemId;

    $params = http_build_query( $api );
    $rget = wp_remote_get( $endPoint . '?' . $params );
    $rbody = json_decode( $rget['body'] );

    //var_dump( $rbody );
    $hubData = $rbody[0]->HubItem;

    $hub = array();
    $hub['title'] = $hubData->title;
    $hub['desc'] = $hubData->description;
    $hub['type'] = $hubData->type;
    $hub['service_data'] = $hubData->service_data;

    $content = '';

		if ( defined( 'DOING_AJAX') && DOING_AJAX ) {
			// video
	    if ( $hub['type'] == 'youtube' ) {
	      $hub['duration'] = $hub['service_data']->duration;
	      $hub['content'] = $hubData->HubItemContent->data;

				$youtube_id = $hubData->HubItemContent->data->id;
				//$video = "<script> YT_Videos.push( '$youtube_id' ); </script>";
				$player = '<script>
									var tag = document.createElement("script");
									tag.src = "https://www.youtube.com/iframe_api";

									var firstScriptTag = document.getElementsByTagName("script")[0];
									firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

									var player;
									function onYouTubeIframeAPIReady() {
										player = new YT.Player( "player", {
											height: "328",
											width: "360",
											videoId: "' . $youtube_id . '",
											events: {
												"onReady": onPlayerReady,
												"onStateChange": onPlayerStateChange
											}
										});
									}

									function onPlayerReady(event) {
        			    	event.target.playVideo();
        			    }

        			    var done = false;
        			    function onPlayerStateChange(event) {
          			  	if (event.data == YT.PlayerState.PLAYING && !done) {
            			  	//setTimeout(stopVideo, 6000);
            			    done = true;
            			  }
            			}

            			function stopVideo() {
              			player.stopVideo();
              		}
									</script>';

				$hub['embed'] = $hub['content']->embedHtml;
				echo $player;

			} else if ( $hub['type'] == 'blogpost' ) {
	    $data = $hubData->HubItemContent->data;
	    $id = $data->id;
			$title = $data->title;
			$created_at = $data->created_at;
			$author = $data->author;
			$content = $data->content;
			//print '<pre>';print_r($data);print '</pre>';
			?>
			<h5><strong>Author:</strong> <?php echo $author; ?></h5>
			<p><?php echo date( 'F d, Y', strtotime( $created_at ) ); ?></p>
			<p><?php echo $content; ?></p>
			<p class="text-right"><a target="_blank" title="<?php echo $title; ?>" href="<?php echo $id; ?>">Read more >></a></p>
			<?php
		} else if ( $hub['type'] == 'twitter' ) {
			$data = $hubData->HubItemContent->data;
			$userData = $hubData->HubItemUsers->data;

			$id = $data->id;
			$id_str = $data->id_str;
			$created_at = $data->created_at;
			$twitter_text = $data->text;
			$source = $data->source;
			/*
			[truncated] =>
	 		[in_reply_to_status_id] =>
	 		[in_reply_to_status_id_str] =>
			[in_reply_to_user_id] =>
			[in_reply_to_user_id_str] =>
			[in_reply_to_screen_name] =>
			[geo] =>
			[coordinates] =>
			[place] =>
			[contributors] =>
			[is_quote_status] =>
			[retweet_count] => 0
			[favorite_count] => 0
			*/

						
			$user_name = $userData->name;
			$screen_name = $userData->screen_name;
			$profile_image_url = $userData->profile_image_url;
			?>
			<div class="meta-top">
				<span class="name"><img alt="<?php echo $user_name; ?>" src="<?php echo $profile_image_url; ?>" /><?php echo $user_name; ?></span>
				<a class="screen-name" href="https://twitter.com/<?php echo $screen_name; ?>">@<?php echo $screen_name; ?></a>
			</div>
			<div class="inner">
  			<p><?php echo $twitter_text; ?></p>

        <p class="date"><?php echo date( 'F d, Y', strtotime( $created_at ) ); ?></p>
 
			</div>
			<div class="meta-bottom">
  			<ul class="share-wrap">
    			<li><a class="reply hooked" data-share="twitter" href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $id; ?>" title="Reply">Reply</a></li>
    			<li><a class="retweet hooked" data-share="twitter" href="https://twitter.com/intent/retweet?tweet_id=<?php echo $id; ?>" title="Retweet">Retweet</a></li>
    			<li><a class="fav hooked" data-share="twitter" href="https://twitter.com/intent/favorite?tweet_id=<?php echo $id; ?>" title="Favorite">Favorite</a></li>
    			<li><a class="email hooked" data-share="email" href="javascript:void(0)" title="Email">Email</a></li>
  			</ul>
			</div>
			<?php
			//print '<pre>';print_r($userData);print '</pre>';
		} else
		if ( $hub['type'] == 'facebook' ) {
			$data = $hubData->HubItemContent->data;

			$id = $data->id;
			/* [from] => stdClass Object
				name, category, category list, id
			*/
			$from = $data->from;
			$fromName = $from->name;
			$fromCat = $from->category;
			$fromID = $from->id;

			/* [category_list] => Array
						[0] => stdClass Object
							id
							name
			*/
			$category_list = $data->from->category_list;
			$catID = $category_list[0]->id;
			$catName = $category_list[0]->name;

			$message = $data->message;
			$pic = $data->picture;
			$link = $data->link;
			$name_sub = $data->name;
			$caption = $data->caption;
			$desc = $data->description;
			$icon = $data->icon;

			/* [privacy] => stdClass Object
        (
            [value] =>
            [description] =>
            [friends] =>
            [allow] =>
            [deny] =>
        )
			*/
			$privacy = $data->privacy;
			$type = $data->type;
			$status_type = $data->status_type;

			/* [application] => stdClass Object
        (
            [category] => Utilities
            [link] => http://hootsuite.com/
            [name] => Hootsuite
            [namespace] => hootsuiteprod
            [id] => 183319479511
        )
			*/
			$application = $data->application;
			$appCat = $application->category;
			$appLink = $application->link;
			$appName = $application->name;

			$created_time = $data->created_time;
			$updated_time = $data->updated_time;
			$is_hidden = $data->is_hidden;
			$is_expired = $data->is_expired;

			/* [likes] => stdClass Object
        (
            [data] => Array
                (
                    [0] => stdClass Object
                        (
                            [id] => 1618006658472753
                            [name] => Mondale Lucas
                        )

                )
			*/
			$likes = $data->likes;
			$extractPic = preg_match( '/url=(.*)&/i', urldecode($pic), $pic_matches );
			$matchedPic = $pic_matches[1];

			$fromIcon = '//graph.facebook.com/' . $fromID . '/picture';
			?>
			<div class="meta-top">
				<span class="name"><img alt="<?php echo $fromName; ?>" src="<?php echo $fromIcon; ?>" /><?php echo $fromName; ?></span>
				<div class="date" style="margin-left:10px" data-updated-date="<?php echo $updated_time; ?>"><?php echo date( 'F d, Y', strtotime( $updated_time ) ); ?></div>
			</div>
			<div class="inner">
				<p class="caption"><?php echo $message; ?></p>
				<p class="text-center">
					<a href="<?php echo $link; ?>">
						<img alt="<?php echo $fromName; ?>" src="<?php echo $matchedPic; ?>" />
					</a>
				</p>
				<div class="inner-entry">
				  <p class="first"><strong><a target="_blank" href="<?php echo $link; ?>"><?php echo $name_sub; ?></a></strong></p>
          <?php echo $desc; ?>
				  <br /><a href="http://<?php echo $caption; ?>"><?php echo $caption; ?></a>
				</div>
			</div>
			<?php

			//print '<pre>';print_r($data);print '</pre>';
		}

	}
		// Exit immediately, so WP doesn't try to tack on a 0
		exit;
  }

	/*public function crest_get_transient_data() {
    $transients = array();
    if ( false !== get_transient( 'uber_hubstream_data' ) ) {
      $transients = get_transient( 'uber_hubstream_data' );
      //print '<pre>';var_dump($transients);print '</pre>';
			//var_dump( $_POST );
      $stream_id = $_POST['stream_id'];
			$card_type = $_POST['card_type'];
			//echo json_encode( $transients );
			print '<pre>';var_dump($transients);print '</pre>';
			$counter = 0;
      foreach ( $transients as $data ): ?>
        <?php if ( $counter > 3 ) continue; ?>
        <div class="panel panel-default">
          <div class="panel-heading"><?php echo substr($data['title'], 0, 40); ?></div>
          <div class="panel-body">
            <a class="pull-left thumbnail"><img src="<?php echo $data['thumbnail_url']; ?>" /></a>
            <?php echo $data['type']; ?>
          </div>
        </div>
        <?php $counter++; ?>
      <?php endforeach;
    }
    exit;
  }*/

	public function crest_get_transient_data() {
		check_ajax_referer( 'cr_ajax_obj_wp_nonce', '_ajax_nonce' );

		$stream_id = $_POST['stream_id'];

		$rget = '';
		$rbody = '';

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
		$api['HubStreamId'] = $stream_id;
		//$api['Limit'] = 22;
		$api['Method'] = 'GetHubItems';
		$params = http_build_query( $api );

		$rget = wp_remote_get( $endPoint . '?' . $params );

		$rbody = json_decode( $rget['body'] );
	/*	print '<pre>';
		print_r( $rbody );
		print '</pre>';*/

		for ($i = 0; $i < count( $rbody ); $i++ ) {

			?>
			<div class="panel panel-default"
				data-item-id="<?php echo $rbody[$i]->HubItem->id; ?>"
				data-item-title="<?php echo $rbody[$i]->HubItem->title; ?>" 
  		  data-card-type="<?php echo $rbody[$i]->HubItem->type; ?>">
				<div class="panel-heading"><?php echo substr($rbody[$i]->HubItem->title, 0, 40); ?></div>
				<div class="panel-body">
					<a class="thumbnail"><img src="<?php echo $rbody[$i]->HubItem->thumbnail_url; ?>" /></a>
					<?php echo $rbody[$i]->HubItem->type; ?>
				</div>
			</div>
		<?php
		}
		exit;
	}

	public function crest_get_page() {
  	check_ajax_referer( 'cr_ajax_obj_wp_nonce' );

  	$paged = $_POST['page']; // page number
  	$curr_page = 0;
  	$output = '';

  	if ( filter_var( intval( $paged ), FILTER_VALIDATE_INT ) ):
    	$curr_page = $paged;
    	$args = array(
      	'post_type' => 'event',
      	'paged' => $curr_page,
      	'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_key' => 'date',
      	'posts_per_page' => 5
      );

      $loop = new WP_Query( $args );

      if ( $loop->have_posts() ):
        while ( $loop->have_posts() ):
          $loop->the_post();

          $articleID = get_the_ID();
          $articleClass = post_class( 'cf' );
          $perma = get_permalink();
          $title = get_the_title();
          $excerpt = get_excerpt( '<span class="read-more">' . __( 'Read more &raquo;', 'bonestheme' ) . '</span>' );

          $d = get_field('date');
          $d2 = date( 'F d, Y', strtotime( $d ) );
          ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
            <header class="article-header">
						  <h3 class="search-title entry-title">
  						  <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
  						</h3>
  						<?php $d = get_field('date'); ?>
              <?php $d2 = date( 'F d, Y', strtotime( $d ) ); ?>
              <div class="date"><?php echo $d2; ?></div>
            </header>
            <section class="entry-content cf" itemprop="articleBody">
              <?php the_excerpt( '<span class="read-more">' . __( 'Read more &raquo;', 'bonestheme' ) . '</span>' ); ?>
            </section>
          </article>
        <?php
        endwhile;
        wp_reset_query();
      endif;

    endif;

  	exit;
  }

}
