<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

get_header(); ?>

		<?php while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'single', get_post_format() );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop. ?>

<?php get_footer(); ?>