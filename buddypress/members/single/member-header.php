<?php
/**
 * BuddyPress - User Header
 *
 * @package HCommons
 */
?>

<?php do_action( 'bp_before_member_header' ); ?>

<div id="cover-image-container">
	<?php
	$cover_image_url = bp_attachments_get_attachment( 'url', array(
		'object_dir' => 'members',
		'item_id'    => bp_displayed_user_id(),
	) );
	$cover_style = '';
	if ( ! empty( $cover_image_url ) ) {
		$cover_style = ' style="background-image: url(' . esc_url( $cover_image_url ) . '); background-size: cover; background-position: center;"';
	}
	?>
	<div id="header-cover-image"<?php echo $cover_style; ?>></div>

	<div id="item-header-cover-image">
		<div id="item-header-avatar">
			<a href="<?php bp_displayed_user_link(); ?>">
				<?php bp_displayed_user_avatar( 'type=full' ); ?>
			</a>
		</div><!-- #item-header-avatar -->

		<div id="item-header-content">
			<?php do_action( 'bp_before_member_header_meta' ); ?>

			<div id="item-meta">
				<?php
				// Get profile field data
				$title = function_exists( 'xprofile_get_field_data' ) ? xprofile_get_field_data( 'Title', bp_displayed_user_id() ) : '';
				$affiliation = function_exists( 'xprofile_get_field_data' ) ? xprofile_get_field_data( 'Institutional or Other Affiliation', bp_displayed_user_id() ) : '';
				$mastodon = function_exists( 'xprofile_get_field_data' ) ? xprofile_get_field_data( 'Mastodon handle', bp_displayed_user_id() ) : '';
				$orcid = function_exists( 'xprofile_get_field_data' ) ? xprofile_get_field_data( '<em>ORCID</em> iD', bp_displayed_user_id() ) : '';
				$website = function_exists( 'xprofile_get_field_data' ) ? xprofile_get_field_data( 'Website', bp_displayed_user_id() ) : '';
				$tier = get_user_tier( bp_displayed_user_id() );
				?>

				<h2 class="member-name"><?php echo esc_html( bp_get_displayed_user_fullname() ); ?></h2>
				<a href="<?php echo esc_url( bp_get_displayed_user_link() ); ?>" class="member-username">@<?php echo esc_html( bp_get_displayed_user_username() ); ?></a>

				<?php if ( ! empty( $title ) ) : ?>
					<h4 class="member-title"><?php echo esc_html( $title ); ?></h4>
				<?php endif; ?>

				<?php if ( ! empty( $affiliation ) ) : ?>
					<h4 class="member-affiliation">
						<a href="<?php echo esc_url( home_url( '/search/?q=' . urlencode( $affiliation ) . '&post_type%5B0%5D=user' ) ); ?>" rel="nofollow">
							<?php echo esc_html( $affiliation ); ?>
						</a>
					</h4>
				<?php endif; ?>

                <?php if ( $tier ) : ?>
                    <h4 class="member-affiliation">
                        <a href="https://about.hcommons.org/kc-champions/"><span>KC Champion:</span>
                        <?php display_user_tier_badge( bp_displayed_user_id() ); ?></a>
                    </h4>
                <?php endif; ?>

				<div class="member-social-links">
					<?php
					// Commons profile link
					$commons_url = bp_is_active( 'messages' ) && is_user_logged_in() && ! bp_is_my_profile()
						? bp_get_send_private_message_link()
						: bp_get_displayed_user_link();
					$commons_title = bp_is_active( 'messages' ) && is_user_logged_in() && ! bp_is_my_profile()
						? sprintf( esc_attr__( 'Message @%s', 'flavor' ), bp_get_displayed_user_username() )
						: sprintf( esc_attr__( '@%s on Commons', 'flavor' ), bp_get_displayed_user_username() );
					?>
					<a href="<?php echo esc_url( $commons_url ); ?>" class="social-icon-btn commons" title="<?php echo $commons_title; ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
					</a>

					<?php if ( ! empty( $mastodon ) ) :
						// Parse Mastodon handle to create proper URL
						$mastodon_clean = ltrim( $mastodon, '@' );
						if ( strpos( $mastodon_clean, '@' ) !== false ) {
							$parts = explode( '@', $mastodon_clean );
							$mastodon_url = 'https://' . $parts[1] . '/@' . $parts[0] . '/';
							$mastodon_display = '@' . $mastodon_clean;
						} else {
							$mastodon_url = $mastodon;
							$mastodon_display = $mastodon;
						}
					?>
						<a href="<?php echo esc_url( $mastodon_url ); ?>" class="social-icon-btn mastodon" title="<?php echo esc_attr( $mastodon_display ); ?>" rel="me" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21.327 8.566c0-4.339-2.843-5.61-2.843-5.61-1.433-.658-3.894-.935-6.451-.956h-.063c-2.557.021-5.016.298-6.45.956 0 0-2.843 1.272-2.843 5.61 0 .993-.019 2.181.012 3.441.103 4.243.778 8.425 4.701 9.463 1.809.479 3.362.579 4.612.51 2.268-.126 3.541-.809 3.541-.809l-.075-1.646s-1.621.511-3.441.449c-1.804-.062-3.707-.194-3.999-2.409a4.523 4.523 0 0 1-.04-.621s1.77.432 4.014.535c1.372.063 2.658-.08 3.965-.236 2.506-.299 4.688-1.843 4.962-3.254.434-2.223.398-5.424.398-5.424zm-3.353 5.59h-2.081V9.057c0-1.075-.452-1.62-1.357-1.62-1 0-1.501.647-1.501 1.927v2.791h-2.069V9.364c0-1.28-.501-1.927-1.502-1.927-.905 0-1.357.546-1.357 1.62v5.099H6.026V8.903c0-1.074.273-1.927.823-2.558.566-.631 1.307-.955 2.228-.955 1.065 0 1.872.41 2.405 1.228l.518.869.519-.869c.533-.818 1.34-1.228 2.405-1.228.92 0 1.662.324 2.228.955.549.631.822 1.484.822 2.558v5.253z"/></svg>
						</a>
					<?php endif; ?>

					<?php if ( ! empty( $orcid ) ) :
						$orcid_id = preg_replace( '/^https?:\/\/orcid\.org\//', '', $orcid );
					?>
						<a href="<?php echo esc_url( 'https://orcid.org/' . $orcid_id ); ?>" class="social-icon-btn orcid" title="<?php echo esc_attr( 'ORCID: ' . $orcid_id ); ?>" rel="me" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.372 0 0 5.372 0 12s5.372 12 12 12 12-5.372 12-12S18.628 0 12 0zM7.369 4.378c.525 0 .947.431.947.947s-.422.947-.947.947a.95.95 0 0 1-.947-.947c0-.525.422-.947.947-.947zm-.722 3.038h1.444v10.041H6.647V7.416zm3.562 0h3.9c3.712 0 5.344 2.653 5.344 5.025 0 2.578-2.016 5.025-5.325 5.025h-3.919V7.416zm1.444 1.303v7.444h2.297c3.272 0 4.022-2.484 4.022-3.722 0-2.016-1.284-3.722-4.097-3.722h-2.222z"/></svg>
						</a>
					<?php endif; ?>

					<?php if ( ! empty( $website ) ) :
						$website_display = preg_replace( '/^https?:\/\//', '', rtrim( $website, '/' ) );
					?>
						<a href="<?php echo esc_url( $website ); ?>" class="social-icon-btn website" title="<?php echo esc_attr( $website_display ); ?>" rel="nofollow" target="_blank">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
						</a>
					<?php endif; ?>
				</div>

				<span class="activity"><?php bp_last_activity( bp_displayed_user_id() ); ?></span>

                <?php if ( bp_is_my_profile() || current_user_can( 'manage_options' ) ) : ?>
                    <div class="member-change-links">
                        <?php
                        $is_editing = ( bp_current_action() === 'edit' || bp_current_action() === 'change-avatar' || bp_current_action() === 'change-cover-image' );
                        ?>
                        <?php if ( $is_editing ) : ?>
                            <a href="<?php echo esc_url( bp_displayed_user_domain() ); ?>" class="change-link view-profile">
                                <?php esc_html_e( 'View Your Profile', 'flavor' ); ?>
                            </a><br/>
                        <?php else : ?>
                            <a href="<?php echo esc_url( bp_displayed_user_domain() . 'profile/edit/' ); ?>" class="change-link edit-profile">
                                <?php esc_html_e( 'Edit Your Profile', 'flavor' ); ?>
                            </a><br/>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( bp_displayed_user_domain() . 'profile/change-avatar/' ); ?>" class="change-link change-avatar">
                            <?php esc_html_e( 'Change Profile Photo', 'flavor' ); ?>
                        </a><br/>
                    </div>
                <?php endif; ?>

				<?php if ( function_exists( 'bp_follow_total_follow_counts' ) ) : ?>
					<?php $follow_counts = bp_follow_total_follow_counts( array( 'user_id' => bp_displayed_user_id() ) ); ?>
					<span class="following-count">
						<?php printf( esc_html__( 'Following %d members', 'flavor' ), (int) $follow_counts['following'] ); ?>
					</span>
				<?php endif; ?>

				<?php if ( is_user_logged_in() && ! bp_is_my_profile() ) : ?>
					<div class="member-message-links">
						<?php if ( bp_is_active( 'activity' ) && function_exists( 'bp_get_send_public_message_link' ) ) : ?>
							<a href="<?php echo esc_url( bp_get_send_public_message_link() ); ?>" class="message-link public-message" title="<?php esc_attr_e( 'Send a public message', 'flavor' ); ?>">
								<?php esc_html_e( 'Public Message', 'flavor' ); ?>
							</a>
						<?php endif; ?>

						<?php if ( bp_is_active( 'messages' ) && function_exists( 'bp_get_send_private_message_link' ) ) : ?>
							<a href="<?php echo esc_url( bp_get_send_private_message_link() ); ?>" class="message-link private-message" title="<?php esc_attr_e( 'Send a private message', 'flavor' ); ?>">
								<?php esc_html_e( 'Private Message', 'flavor' ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div><!-- #item-meta -->

			<div id="item-actions">
				<?php if ( bp_is_active( 'friends' ) && function_exists( 'friends_get_total_friend_count' ) ) : ?>
					<h2><?php esc_html_e( 'Friends', 'flavor' ); ?></h2>
					<p><?php echo (int) friends_get_total_friend_count( bp_displayed_user_id() ); ?></p>
				<?php endif; ?>
			</div><!-- #item-actions -->
		</div><!-- #item-header-content -->
	</div><!-- #item-header-cover-image -->
</div><!-- #cover-image-container -->

<?php do_action( 'bp_profile_header_meta' ); ?>

<?php do_action( 'bp_after_member_header' ); ?>

<?php do_action( 'template_notices' ); ?>
