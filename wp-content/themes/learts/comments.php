<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package learts
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$_comment_count = get_comments_number();
			if ( $_comment_count > 1 ) {
				echo esc_html__( 'Comments',
						'learts' ) . ' <span class="comment-count">(' . $_comment_count . ')</span>';
			} else {
				echo esc_html__( 'Comment',
						'learts' ) . ' <span class="comment-count">(' . $_comment_count . ')</span>';
			}
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'learts' ); ?></h2>
				<div class="nav-links">

					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments',
							'learts' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'learts' ) ); ?></div>

				</div>
			</nav>
		<?php endif; // Check for comment navigation. ?>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ul',
				'avatar_size' => 90,
				'short_ping'  => true,
				'callback'    => 'Learts_Templates::comments',
				'max_depth'   => 5,
				'type'        => 'all',
			) );
			?>
		</ol>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'learts' ); ?></h2>
				<div class="nav-links">

					<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments',
							'learts' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'learts' ) ); ?></div>

				</div>
			</nav>
			<?php
		endif; // Check for comment navigation.

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'learts' ); ?></p>
	<?php endif; ?>

	<?php
	$commenter     = wp_get_current_commenter();
	$req           = get_option( 'require_name_email' );
	$aria_req      = ( $req ? " aria-required='true'" : '' );
	$fields        = array(
		'author' => '<div class="col-md-6">' . '<input id="author" class="required" placeholder="' . esc_attr__( 'Your name (*)',
				'learts' ) . '" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
		'email'  => '<div class="col-md-6">' . '<input id="email" class="required" placeholder="' . esc_attr__( 'Mail (*)',
				'learts' ) . '" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
	);
	$comments_args = array(
		'class_form'           => 'comment-form row',
		// change the title of send button
		'label_submit'         => esc_html__( 'Submit', 'learts' ),
		// change the title of the reply section
		'title_reply'          => esc_html__( 'Leave your thought here', 'learts' ),
		'title_reply_before'   => '<div id="reply-title" class="comment-reply-title big-heading"><span>',
		'title_reply_after'    => '</span></div>',
		'comment_notes_after'  => '',
		'comment_notes_before' => '',
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field'        => '<div class="col-xs-12"><textarea id="comment" class="required" rows="8" placeholder="' . esc_html__( 'Message',
				'learts' ) . '" name="comment" aria-required="true"></textarea></div>',
		'submit_field'         => '<p class="form-submit col-xs-12">%1$s %2$s</p>',
		'must_log_in'          => '<p class="must-log-in col-xs-12">' . sprintf( wp_kses( __( 'You must be <a href="%s">logged in</a> to post a comment.',
				'learts' ),
				array( 'a' => array( 'href' => array() ) ) ),
				wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as col-xs-12">' . sprintf( wp_kses( __( '<a href="%1$s" aria-label="Logged in as %2$s. Edit your profile.">Logged in as %2$s</a>. <a href="%3$s">Log out?</a>',
				'learts' ),
				array(
					'a' => array(
						'href'       => array(),
						'aria-label' => array(),
					),
				) ),
				get_edit_user_link(),
				$user_identity,
				wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
	);
	comment_form( $comments_args ); ?>

</div><!-- #comments -->
