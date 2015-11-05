<?php
/* Welcome to Bones :)
This is the core Bones file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/bones/

  - head cleanup (remove rsd, uri links, junk css, ect)
  - enqueueing scripts & styles
  - theme support functions
  - custom menu output & fallbacks
  - related post function
  - page-navi function
  - removing <p> from around images
  - customizing the post excerpt
  - custom google+ integration
  - adding custom fields to user profiles

*/

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

function bones_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	add_filter( 'style_loader_src', 'bones_remove_wp_ver_css_js', 9999 );
	// remove Wp version from scripts
	add_filter( 'script_loader_src', 'bones_remove_wp_ver_css_js', 9999 );

} /* end bones head cleanup */

// A better title
// http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
function rw_title( $title, $sep, $seplocation ) {
  global $page, $paged;

  // Don't affect in feeds.
  if ( is_feed() ) return $title;

  // Add the blog's name
  if ( 'right' == $seplocation ) {
    $title .= get_bloginfo( 'name' );
  } else {
    $title = get_bloginfo( 'name' ) . $title;
  }

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );

  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title .= " {$sep} {$site_description}";
  }

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 ) {
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
  }

  return $title;

} // end better title

// remove WP version from RSS
function bones_rss_version() { return ''; }

// remove WP version from scripts
function bones_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

// remove injected CSS for recent comments widget
function bones_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

// remove injected CSS from recent comments widget
function bones_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}

// remove injected CSS from gallery
function bones_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}


/*********************
SCRIPTS & ENQUEUEING
*********************/

// Adapted from https://gist.github.com/toscho/1584783
function defer_enqueued_js( $url ) {
  if ( !is_admin() ) {
    if ( strpos($url, '.js') === false ) {
      // Not a JS file, return url unmodified
      return $url;
    } else if ( strpos($url, 'dropins') !== false ) {
      return "$url' id='dropboxjs' data-app-key='xklwe72xtq0qv9v' defer='defer";
    } else {
      return "$url' defer='defer";
    }
  }
  return $url;
}

add_filter( 'clean_url', 'defer_enqueued_js', 11, 1 );

// loading modernizr and jquery, and reply script
function bones_scripts_and_styles() {

  global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

  if (!is_admin()) {

		// modernizr (without media query polyfill)
		wp_register_script( 'bones-modernizr', get_stylesheet_directory_uri() . '/library/js/libs/modernizr.custom.min.js', array(), '2.5.3', false );

		// register main stylesheet
		wp_register_style( 'bones-stylesheet', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' );
		
		wp_register_style( 'style2', get_stylesheet_directory_uri() . '/library/css/style2.css', array(), '', 'all' );
		wp_register_style( 'slick', get_stylesheet_directory_uri() . '/library/css/slick/slick.css', array(), '', 'all' );
		wp_register_style( 'slick-theme', get_stylesheet_directory_uri() . '/library/css/slick/slick-theme.css', array(), '', 'all' );
		// ie-only style sheet
		wp_register_style( 'bones-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );

    // comment reply script for threaded comments
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
		  wp_enqueue_script( 'comment-reply' );
    }
    
    /*
     Sticky Elements
    */
    wp_register_script( 'sticky-all', get_stylesheet_directory_uri() . '/library/js/jq-sticky-anything.js', array( 'jquery' ), '', true );
    
    wp_register_script( 'bstrap-affix', get_stylesheet_directory_uri() . '/library/js/bootstrap/affix.js', array( 'jquery' ), '', true );
    
    wp_register_script( 'bstrap-trans', get_stylesheet_directory_uri() . '/library/js/bootstrap/transition.js', array( 'jquery' ), '', true );
    
    wp_register_script( 'bstrap-carousel', get_stylesheet_directory_uri() . '/library/js/bootstrap/carousel.js', array( 'jquery', 'bstrap-trans' ), '', true );
    
    wp_register_script( 'bstrap-modal', get_stylesheet_directory_uri() . '/library/js/bootstrap/modal.js', array( 'jquery' ), '', true );
    
    wp_register_script( 'scroll-to', get_stylesheet_directory_uri() . '/library/js/libs/jquery.scrollTo.js', array( 'jquery' ), '', true );
    
    wp_register_script( 'local-scroll', get_stylesheet_directory_uri() . '/library/js/libs/jquery.localScroll.js', array( 'jquery', 'scroll-to' ), '', true );
    
    /*
	    Slick
	  */
	  wp_register_script( 'slick', get_stylesheet_directory_uri() . '/library/js/libs/slick/slick.min.js', array( 'jquery' ), '', true );
	  
	  /*
		  Lazy Loading
		*/
		wp_register_script( 'stalactite', get_stylesheet_directory_uri() . '/library/js/libs/lazy/jquery.stalactite.min.js', array( 'jquery' ), '', true );
		//wp_register_script( 'unveil-lib', get_stylesheet_directory_uri() . '/library/js/libs/lazy/jquery.unveil.js', array( 'jquery' ), '', true );
		//wp_register_script( 'unveil', get_stylesheet_directory_uri() . '/library/js/libs/lazy/lazy_unveil.js', array( 'jquery', 'unveil-lib' ), '', true );
		wp_register_script( 'hoverintent', '//cdnjs.cloudflare.com/ajax/libs/jquery.hoverintent/1.8.1/jquery.hoverIntent.min.js', array( 'jquery' ), '', true );
		wp_register_script( 'lazy-fade', get_stylesheet_directory_uri() . '/library/js/libs/lazy/lazy_fade.js', array( 'jquery', 'hoverintent' ), '', true );
    /*
      Angular
    */
    wp_register_script( 'angular-core', '//ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js', array( 'jquery' ), '', true );
    wp_register_script( 'angular-sanitize', '//code.angularjs.org/1.3.5/angular-sanitize.min.js', array( 'jquery', 'angular-core' ), '', true );
    wp_register_script( 'angular-routing', '//code.angularjs.org/1.3.5/angular-route.min.js', array( 'jquery', 'angular-core' ), '', true );
    wp_register_script( 'angular-animate', '//code.angularjs.org/1.3.5/angular-animate.min.js', array( 'angular-core' ), '', true );
    /*
      Angular UI
    */
    wp_register_script( 'angular-ui', get_stylesheet_directory_uri() . '/library/js/ui-bootstrap-tpls-0.10.0.min.js', array( 'angular-core' ), '', true );
    
    /*
      Angular App
    */
    wp_register_script( 'app', get_stylesheet_directory_uri() . '/library/js/uberController.js', 
      array( 'jquery', 'bstrap-carousel', 'bstrap-modal', 'angular-core', 'angular-animate', 'angular-sanitize', 'angular-routing', 'angular-ui' ), '', true 
    );
    
    /*wp_register_script( 'carousel-app', get_stylesheet_directory_uri() . '/library/js/carousel.js',
    	array( 'jquery', 'angular-core', 'angular-animate', 'angular-sanitize' ), '', true 
    );*/
    /*
      Angular Partials
    */
    // we need to create a JavaScript variable to store our API endpoint...   
    wp_localize_script( 'angular-core', 'AppAPI', array( 'url' => get_bloginfo('wpurl').'/wp-json/') ); // this is the API address of the JSON API plugin
    // ... and useful information such as the theme directory and website url
    wp_localize_script( 'angular-core', 'BlogInfo', array( 
      'uber_site_url' => trailingslashit( get_bloginfo( 'template_directory' ) ),
      'uber_site_library' => trailingslashit( get_bloginfo( 'template_directory' ) ) . 'library/',
      'uber_site_partials' => trailingslashit( get_bloginfo( 'template_directory' ) ) . 'library/partials/',
      'uber_site_site' => get_bloginfo( 'wpurl' ),
      'uber_site_uploads' => wp_upload_dir() )
    );
     
		wp_register_script( 'bones-js', get_stylesheet_directory_uri() . '/library/js/scripts.js', array( 'jquery', 'sticky-all', 'scroll-to', 'local-scroll', 'slick' ), '', true );

		// enqueue styles and scripts
		wp_enqueue_script( 'bones-modernizr' );
		wp_enqueue_style( 'bones-stylesheet' );
		wp_enqueue_style( 'bones-ie-only' );
		wp_enqueue_style( 'style2' );
		wp_enqueue_style( 'slick' );
		wp_enqueue_style( 'slick-theme' );
		$wp_styles->add_data( 'bones-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet

		//wp_register_script( 'dbx_dropin', '//www.dropbox.com/static/api/2/dropins.js', array( 'jquery' ), '', true );

		//wp_register_script( 'dropin_chooser', get_stylesheet_directory_uri() . '/library/js/dbx.chooser.js', array( 'jquery', 'dbx_dropin' ), '', true );

		/*
		I recommend using a plugin to call jQuery
		using the google cdn. That way it stays cached
		and your site will load faster.
		*/
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'sticky-all' );
		wp_enqueue_script( 'bstrap-affix' );
		//wp_enqueue_script( 'dropin_chooser');
		wp_enqueue_script( 'app' );
		//wp_enqueue_script( 'carousel-app' );
		wp_enqueue_script( 'lazy-fade' );
		wp_enqueue_script( 'bones-js' );

	}
}

/*
  Hook into wp_footer for modals
*/
function bones_initialize_modal() {
	ob_start();
  ?>
  <div class="modal fade" id="cardModal" tabindex="-1" role="dialog" aria-labelledby="cardModalLabel" ng-controller="ModalCtrl">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="exampleModalLabel">New message</h4>
        </div>

        <div class="modal-body">
					<div class="container-fluid no-padd">

            <div class="row">

                <form>

                  <div class="main-card col-md-8">

                    <div id="player"></div>

                    <div class="main-card-content"></div>

                  </div>

                  <div class="related-cards scroll-pane col-md-4"></div>

                  <input type="hidden" id="modal-card-id" name="modal-card-id" value="" />

                  <input type="hidden" id="modal-stream-id" name="modal-stream-id" value="" />

                </form>

            </div>

          </div>
        </div>

        <div class="modal-footer">
					<div class="col-md-7 fb-container">
  					<div id="fb-root"></div>
  					<div class="fb-like" data-href="#" 
      				data-layout="standard" 
      				data-action="like" 
      				data-show-faces="false" 
      				data-share="true">
  					</div>
      				
					</div>
					<div class="col-md-2">
						<span class="modalEnlarge">
							<a href="javascript:void(0)" class="glyphicon align-right" id="cardModalEnlarge"></a>
						</span>
					</div>
					<div class="text-right col-md-3">
          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
        </div>
      </div>
    </div>
  </div>
<?php
	ob_end_flush();
}

function bones_initialize_loader() {
	?>
	<div id="ajax-loader"><img src="<?php echo trailingslashit( get_stylesheet_directory_uri() ) . 'library/images/ajax-loader.gif' ?>" /></div>
	<?php
}

add_action( 'wp_footer', 'bones_initialize_modal' );
//add_action( 'wp_footer', 'bones_initialize_loader' );
/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function bones_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	// wp custom background (thx to @bransonwerner for update)
	add_theme_support( 'custom-background',
	    array(
	    'default-image' => '',    // background image default
	    'default-color' => '',    // background color default (dont add the #)
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			'gallery',           // gallery of images
			'link',              // quick link to other site
			'image',             // an image
			'quote',             // a quick quote
			'status',            // a Facebook like status update
			'video',             // video
			'audio',             // audio
			'chat'               // chat transcript
		)
	);

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'main-nav' => __( 'The Main Menu', 'bonestheme' ),   // main nav in header
			'footer-links' => __( 'Footer Links', 'bonestheme' ) // secondary nav in footer
		)
	);
} /* end bones theme support */


/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using bones_related_posts(); )
function bones_related_posts() {
	echo '<ul id="bones-related-posts">';
	global $post;
	$tags = wp_get_post_tags( $post->ID );
	if($tags) {
		foreach( $tags as $tag ) {
			$tag_arr .= $tag->slug . ',';
		}
		$args = array(
			'tag' => $tag_arr,
			'numberposts' => 5, /* you can change this to show more */
			'post__not_in' => array($post->ID)
		);
		$related_posts = get_posts( $args );
		if($related_posts) {
			foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
				<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; }
		else { ?>
			<?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'bonestheme' ) . '</li>'; ?>
		<?php }
	}
	wp_reset_postdata();
	echo '</ul>';
} /* end bones related posts function */

/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function bones_page_navi() {
  global $wp_query;
  $bignum = 999999999;
  if ( $wp_query->max_num_pages <= 1 )
    return;
  echo '<nav class="pagination">';
  echo paginate_links( array(
    'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
    'format'       => '',
    'current'      => max( 1, get_query_var('paged') ),
    'total'        => $wp_query->max_num_pages,
    'prev_text'    => '&larr;',
    'next_text'    => '&rarr;',
    'type'         => 'list',
    'end_size'     => 3,
    'mid_size'     => 3
  ) );
  echo '</nav>';
} /* end page navi */

/*********************
RANDOM CLEANUP ITEMS
*********************/

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function bones_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link
function bones_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'bonestheme' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'bonestheme' ) .'</a>';
}



?>
