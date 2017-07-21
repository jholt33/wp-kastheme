<?php get_header(); ?>

		<div class="content-wrap">

				<div class="container clearfix">

					<div class="row topmargin-md">

						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

							<?php the_content(); ?>

						<?php endwhile; else : ?>
								<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
						<?php endif; ?>

					</div>

				</div>

		</div>

		<section id="content">
			<div class="promo promo-full">
				<div class="container clearfix">
					<h3>Let's Talk about getting more out of your DAY!!! and growing your IT business</h3>
					<span>Find out why thousands of IT departments and Managed Service Providers (MSPs) use Kaseya solutions every day to get more IT done.</span>
					<a href="#" class="button button-3d button-large button-rounded button-amber lets-talk-btn"><i class="icon-comments-alt"></i> Let's Talk</a>
				</div>
			</div>
		</section>

<?php get_footer(); ?>
