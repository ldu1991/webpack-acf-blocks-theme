<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('col-xs-12'); ?>>

    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

    <?php $picture = get_field('picture'); if ($picture) : ?>
        <img class="picture" src="<?php echo $picture['url']; ?>" alt="<?php the_title(); ?>">
    <?php endif ?>

    <div class="page-content">
        <?php the_content(); ?>
    </div>

</article><!-- .post-## -->