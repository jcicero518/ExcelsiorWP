<?php
/*
 Template Name: Home
*/
//$API->makeCurlRequest();
//do_action( 'make_api_request', 'GetTitles' );
?>

<?php get_header(); ?>

			<div id="content">
            
				    <div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-3of3 d-7of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
            
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
              
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
									
								<!--<ng-view></ng-view>-->
									<div class="parallax">
										<div class="parallax__group" >
											<?php if ( is_active_sidebar( 'sidebar1' ) ) : ?>
									
												<?php dynamic_sidebar( 'sidebar1' ); ?>
									
											<?php endif; ?>
										</div>
										<!--<div class="parallax__group">
                      <uber-carousel></uber-carousel>
  									</div>-->
  									<div class="parallax__group">
                      <who-we-are></who-we-are>
  									</div>
  									<div class="parallax__group">
    									<what-we-do></what-we-do>
  									</div>
  									<div class="parallax__group">
    									<services-centers></services-centers>
  									</div>
  									<div class="parallax__group">
    									<why-excelsior></why-excelsior>
  									</div>
  									<div class="parallax__group">
    									<peer-perspectives></peer-perspectives>
  									</div>
  									<!--<div class="parallax__group">-->
    									<!--<engagement-hub></engagement-hub>-->
    									<!--<uber-social></uber-social>
  									</div>-->
									</div>
									<?php the_content(); ?>

								<!--<footer class="article-footer">

                  <?php the_tags( '<p class="tags"><span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', '</p>' ); ?>

								</footer>-->

							</article>

							<?php endwhile; else : ?>

									<article id="post-not-found" class="hentry cf">
											<header class="article-header">
												<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the page-custom.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</main>

						<?php //get_sidebar(); ?>

				  </div> <!-- ./inner-content -->

			</div>


<?php get_footer(); ?>
