<?php
/**
 * Template for the "Remind Me" page.
 *
 * Allows users to request an email with their login method information.
 * WordPress automatically uses this template for a Page with slug "remind-me".
 *
 * @package HCommons
 */

get_header(); ?>

<main class="remind-me-page pt-header pb-80">
	<div class="remind-me-container">

		<?php if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['rm_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rm_nonce'] ) ), 'remind-me-nonce' ) ) : ?>

			<?php
			$username_email = isset( $_POST['username_email'] ) ? sanitize_text_field( wp_unslash( $_POST['username_email'] ) ) : '';
			$req_method     = isset( $_POST['req_method'] ) ? sanitize_text_field( wp_unslash( $_POST['req_method'] ) ) : '';

			switch ( $req_method ) {
				case 'email':
					$user = get_user_by( 'email', sanitize_email( $username_email ) );
					break;
				case 'username':
					$user = get_user_by( 'login', $username_email );
					break;
				default:
					$user = false;
			}

			if ( false !== $user ) {
				$user_login_methods = implode( '<br />', Humanities_Commons::hcommons_get_user_login_methods( $user->data->ID ) );

				if ( ! empty( $user_login_methods ) ) {
					wp_mail(
						$user->data->user_email,
						'Your Knowledge Commons Login Method Request',
						'<p>You currently log in to <em>Knowledge Commons</em> using: </p> <h3>' . $user_login_methods . '</h3>',
						'From: Knowledge Commons <hello@hcommons.org>'
					);
				} elseif ( '2016-11-29' >= $user->data->user_registered ) {
					wp_mail(
						$user->data->user_email,
						'Your Knowledge Commons Login Method Request',
						'<p>Looks like the last time you logged in you used the Legacy <em>MLA Commons</em> login method.</p>',
						'From: Knowledge Commons <hello@hcommons.org>'
					);
				} else {
					wp_mail(
						$user->data->user_email,
						'Your Knowledge Commons Login Method Request',
						'<p>Looks like you have never logged into <em>Knowledge Commons</em>. Please get in touch.</p>',
						'From: Knowledge Commons <hello@hcommons.org>'
					);
				}
			}
			?>

			<div class="remind-me-result">
				<p class="remind-me-message">
					<?php esc_html_e( 'If we have this username or e-mail address on file, we will send you a message detailing how you have previously logged in to Knowledge Commons. Please check your inbox.', 'flavor' ); ?>
				</p>
			</div>

		<?php else : ?>

			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<div class="remind-me-form-wrapper">
						<p class="remind-me-description">
							<?php esc_html_e( 'Send me an e-mail with my login information!', 'flavor' ); ?>
						</p>

						<form action="" id="remindMeForm" method="POST" class="remind-me-form">
							<fieldset class="remind-me-options">
								<legend class="screen-reader-text"><?php esc_html_e( 'Identify yourself by', 'flavor' ); ?></legend>
								<label class="remind-me-radio-label">
									<input type="radio" id="email_choice" name="req_method" value="email" checked />
									<?php esc_html_e( "I'll identify myself with my registered e-mail", 'flavor' ); ?>
								</label>
								<label class="remind-me-radio-label">
									<input type="radio" id="username_choice" name="req_method" value="username" />
									<?php echo wp_kses_post( __( "I'll identify myself with my <em>Knowledge Commons</em> username", 'flavor' ) ); ?>
								</label>
							</fieldset>

							<div class="remind-me-input-group">
								<input type="text" id="rm_username_email" name="username_email" class="remind-me-input" required />
								<button type="submit" class="remind-me-submit"><?php esc_html_e( 'Submit', 'flavor' ); ?></button>
							</div>

							<?php wp_nonce_field( 'remind-me-nonce', 'rm_nonce' ); ?>
						</form>
					</div>

					<footer class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'flavor' ), '<span class="edit-link">', '</span>' ); ?>
					</footer>

				</article>
			<?php endwhile; ?>

		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>