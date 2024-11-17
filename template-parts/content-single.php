<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

    <div class="entry-content">
        <?php the_content(); ?>
    </div>

    <?php
    $categories = get_the_category();
    if($categories){
    	foreach($categories as $category) {
    		$out .= '<a href="'.get_category_link($category->term_id ).'">'.$category->name.'</a>, ';
    	}
    	echo trim($out, ', ');
    }
    ?>

</article><!-- #post-## -->
