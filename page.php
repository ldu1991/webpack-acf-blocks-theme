<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

get_header();

?>

<?php while (have_posts()) : the_post();

    the_content();

endwhile; ?>

<?php get_footer(); ?>
