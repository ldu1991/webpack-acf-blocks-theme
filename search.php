<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
  exit('Direct script access denied.');
}

get_header(); ?>

<?php if (have_posts()) :

  /* Start the Loop */
  while (have_posts()) : the_post();

    /**
     * Run the loop for the search to output the results.
     * If you want to overload this in a child theme then include a file
     * called content-search.php and that will be used instead.
     */
    get_template_part('template-parts/content', 'search');

  endwhile;

else :

  get_template_part('template-parts/content', 'none');

endif; ?>

<?php get_footer(); ?>