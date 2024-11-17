<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

    <?php the_content() ?>
</article><!-- #post-## -->
