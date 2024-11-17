<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}


$comments = get_comments(array(
			'post_id' => $post->ID
		));

    print_r($comments);
?>




<div id="comments" class="comments-area col-xs-12">
    <?php if ( have_comments() ) : ?>

        <ul class="comment-list">
        	  <?php wp_list_comments( array(
              'type'        => 'comment',
              //'callback'    => 'ld_comments'
              ) ); ?>
        </ul><!-- .comment-list -->

    <?php endif; ?>


<?php
$args = array(
	'fields' => array(
	'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' /></p>',

	'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' . '<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></p>',

	'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label> ' . '<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',

 'rating'    => '<p class="comment-form-rating">
			<label for="rating">' . __( 'Rating' ) . '</label>
			<input id="rating" name="rating" ' . ( $html5 ? 'type="rating"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['rating'] ) . '" size="30" />
		</p>',
	),


	'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8"  aria-required="true" required="required"></textarea></p>',

	'must_log_in'   => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',

	'logged_in_as'  => '<p class="logged-in-as">' . sprintf( __( '<a href="%1$s" aria-label="Logged in as %2$s. Edit your profile.">Logged in as %2$s</a>. <a href="%3$s">Log out?</a>' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',

	'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . __( 'Your email address will not be published.' ) . '</span>'. ( $req ? $required_text : '' ) . '</p>',

	'comment_notes_after'  => '',
	'id_form'              => 'commentform',
	'id_submit'            => 'submit',
	'class_form'           => 'comment-form',
	'class_submit'         => 'submit',
	'name_submit'          => 'submit',
	'title_reply'          => __( 'Leave a Reply' ),
	'title_reply_to'       => __( 'Leave a Reply to %s' ),
	'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
	'title_reply_after'    => '</h3>',
	'cancel_reply_before'  => ' <small>',
	'cancel_reply_after'   => '</small>',
	'cancel_reply_link'    => __( 'Cancel reply' ),
	'label_submit'         => __( 'Post Comment' ),
	'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
	'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
	'format'               => 'xhtml',
);

comment_form( $args );
?>

</div>
