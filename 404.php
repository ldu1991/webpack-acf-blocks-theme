<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

get_header(); ?>

<div class="error-404">
    <h1><?php _e('SORRY, NO POSTS WERE FOUND!'); ?></h1>
    <p>404</p>
    <p><?php _e('The page you were looking for is not here. Maybe you want to perform a search?'); ?></p>

    <form class="search-error" role="search" method="get" action="<?php echo home_url('/'); ?>">
        <input class="search-input-error" name="s" maxlength="50" type="text" value="" placeholder="Search and hit enter..." />
        <input class="search-submit-error" type="submit" value="">
    </form>
</div>

<?php get_footer(); ?>