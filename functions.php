<?php
/**
 * Knowledge Commons Theme Functions
 *
 * @package HCommons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup
 */
function hcommons_setup() {
	// Add support for block styles
	add_theme_support( 'wp-block-styles' );

	// Add support for block template parts (required for FSE themes in multisite)
	add_theme_support( 'block-template-parts' );

	// Add support for editor styles
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/theme.css' );

	// Add support for responsive embeds
	add_theme_support( 'responsive-embeds' );

	// Add support for custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 32,
		'width'       => 32,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// Register navigation menus
	register_nav_menus( array(
		'primary'         => __( 'Primary Navigation', 'hcommons' ),
		'footer-resources' => __( 'Footer Resources', 'hcommons' ),
		'footer-community' => __( 'Footer Community', 'hcommons' ),
		'footer-about'     => __( 'Footer About', 'hcommons' ),
	) );
}
add_action( 'after_setup_theme', 'hcommons_setup' );

/**
 * Enqueue theme styles and scripts
 */
function hcommons_enqueue_assets() {
	// Font Awesome 4.7 (matches BuddyPress template class names)
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
		array(),
		'4.7.0'
	);

	// Main theme styles
	wp_enqueue_style(
		'hcommons-theme',
		get_template_directory_uri() . '/assets/css/theme.css',
		array( 'font-awesome' ),
		wp_get_theme()->get( 'Version' )
	);

	// Inject logo URL as CSS variable
	$logo_url = get_template_directory_uri() . '/assets/images/logo-icon.png';
	wp_add_inline_style( 'hcommons-theme', ':root { --hcommons-logo-url: url("' . esc_url( $logo_url ) . '"); }' );

	// Google Fonts
	wp_enqueue_style(
		'hcommons-fonts',
		'https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400;1,700&display=swap',
		array(),
		null
	);

	// Theme scripts
	wp_enqueue_script(
		'hcommons-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);

	// BuddyPress styles (load if BuddyPress is active)
	if ( function_exists( 'buddypress' ) || class_exists( 'BuddyPress' ) ) {
		wp_enqueue_style(
			'hcommons-buddypress',
			get_template_directory_uri() . '/assets/css/buddypress.css',
			array( 'hcommons-theme' ),
			wp_get_theme()->get( 'Version' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'hcommons_enqueue_assets' );

/**
 * Enqueue editor assets
 */
function hcommons_enqueue_editor_assets() {
	wp_enqueue_style(
		'hcommons-editor',
		get_template_directory_uri() . '/assets/css/theme.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);

	// Inject logo URL as CSS variable for editor
	$logo_url = get_template_directory_uri() . '/assets/images/logo-icon.png';
	wp_add_inline_style( 'hcommons-editor', ':root { --hcommons-logo-url: url("' . esc_url( $logo_url ) . '"); }' );
}
add_action( 'enqueue_block_editor_assets', 'hcommons_enqueue_editor_assets' );

/**
 * Register block patterns
 */
function hcommons_register_block_patterns() {
	// Register pattern category
	register_block_pattern_category( 'hcommons', array(
		'label' => __( 'Knowledge Commons', 'hcommons' ),
	) );
}
add_action( 'init', 'hcommons_register_block_patterns' );

/**
 * Hide admin bar for non-logged-in users
 */
function hcommons_hide_admin_bar_for_guests( $show ) {
	if ( ! is_user_logged_in() ) {
		return false;
	}
	return $show;
}
add_filter( 'show_admin_bar', 'hcommons_hide_admin_bar_for_guests' );

/**
 * Get theme asset URL
 */
function hcommons_asset_url( $path ) {
	return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
}

/**
 * Get theme asset path
 */
function hcommons_asset_path( $path ) {
	return get_template_directory() . '/assets/' . ltrim( $path, '/' );
}

/**
 * Load custom template for BuddyPress Docs
 *
 * BuddyPress Docs uses a custom post type that conflicts with FSE archive templates.
 * This filter ensures the correct template is loaded for BP Docs pages.
 */
function hcommons_bp_docs_template( $template ) {
	// Check if BuddyPress Docs is active and we're on a docs page
	if ( function_exists( 'bp_docs_is_docs_component' ) && bp_docs_is_docs_component() ) {
		$custom_template = get_template_directory() . '/bp-docs-template.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}

	// Also check for bp_doc post type archive
	if ( is_post_type_archive( 'bp_doc' ) ) {
		$custom_template = get_template_directory() . '/archive-bp_doc.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'hcommons_bp_docs_template', 99 );

/**
 * Get the blog ID for the "about" site based on environment
 *
 * @return int|false Blog ID or false if not found
 */
function hcommons_get_about_blog_id() {
	if ( ! is_multisite() ) {
		return false;
	}

	// Determine which domain to look for based on environment
	$site_url = get_site_url();

	if ( strpos( $site_url, 'lndo.site' ) !== false ) {
		// Local environment
		$about_domain = 'about.commons-wordpress.lndo.site';
	} elseif ( strpos( $site_url, 'hcommons-dev.org' ) !== false ) {
		// Staging environment
		$about_domain = 'about.hcommons-dev.org';
	} else {
		// Production environment
		$about_domain = 'about.hcommons.org';
	}

	// Get blog ID by domain
	$blog = get_blog_details( array( 'domain' => $about_domain ), false );

	if ( $blog ) {
		return $blog->blog_id;
	}

	// Fallback: try to find by path if domain lookup fails
	$blogs = get_sites( array(
		'search' => 'about',
		'number' => 1,
	) );

	if ( ! empty( $blogs ) ) {
		return $blogs[0]->blog_id;
	}

	return false;
}

/**
 * Render blog posts from the about site
 *
 * This shortcode safely fetches posts from the about site using switch_to_blog()
 * during render time only, avoiding issues with theme loading.
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function hcommons_about_blog_posts_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'count' => 3,
	), $atts );

	$about_blog_id = hcommons_get_about_blog_id();

	// If we can't find the about blog, fall back to current site posts
	if ( ! $about_blog_id ) {
		return hcommons_render_blog_posts_html( $atts['count'], false );
	}

	// Switch to the about blog to query posts
	switch_to_blog( $about_blog_id );

	$output = hcommons_render_blog_posts_html( $atts['count'], true );

	// Always restore the current blog
	restore_current_blog();

	return $output;
}
add_shortcode( 'hcommons_about_blog_posts', 'hcommons_about_blog_posts_shortcode' );

/**
 * Get the about site base URL based on environment
 *
 * @return string About site URL
 */
function hcommons_get_about_site_url() {
	$site_url = get_site_url();

	if ( strpos( $site_url, 'lndo.site' ) !== false ) {
		return 'https://about.commons-wordpress.lndo.site';
	} elseif ( strpos( $site_url, 'hcommons-dev.org' ) !== false ) {
		return 'https://about.hcommons-dev.org';
	}

	return 'https://about.hcommons.org';
}

/**
 * Filter to inject blog posts from about site into blog section
 *
 * This filter runs at render time, ensuring switch_to_blog() doesn't
 * interfere with theme loading.
 *
 * @param string $block_content The block content
 * @param array  $block         The block data
 * @return string Modified block content
 */
function hcommons_render_blog_section( $block_content, $block ) {
	// Check if this is the blog posts placeholder
	if ( isset( $block['attrs']['className'] ) && strpos( $block['attrs']['className'], 'blog-posts-placeholder' ) !== false ) {
		$about_blog_id = hcommons_get_about_blog_id();

		if ( $about_blog_id ) {
			switch_to_blog( $about_blog_id );
			$posts_html = hcommons_render_blog_posts_html( 3, true );
			restore_current_blog();
		} else {
			$posts_html = hcommons_render_blog_posts_html( 3, false );
		}

		return $posts_html;
	}

	// Fix the View All Posts link
	if ( isset( $block['attrs']['className'] ) && strpos( $block['attrs']['className'], 'blog-section-view-all' ) !== false ) {
		$about_url = hcommons_get_about_site_url();
		$block_content = preg_replace(
			'/href="[^"]*"/',
			'href="' . esc_url( $about_url . '/news/' ) . '"',
			$block_content
		);
	}

	return $block_content;
}
add_filter( 'render_block', 'hcommons_render_blog_section', 10, 2 );

/**
 * Render header account area shortcode
 *
 * Shows user avatar, name, and notifications when logged in,
 * or login/register links when logged out.
 *
 * @return string HTML output
 */
function hcommons_header_account_shortcode() {
	ob_start();

	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( $user_id ) : get_author_posts_url( $user_id );
		$user_name = function_exists( 'bp_core_get_user_displayname' ) ? bp_core_get_user_displayname( $user_id ) : wp_get_current_user()->display_name;
		$avatar = function_exists( 'bp_core_fetch_avatar' )
			? bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb', 'width' => 32, 'height' => 32 ) )
			: get_avatar( $user_id, 32 );
		?>
		<div class="header-account">
			<?php if ( function_exists( 'bp_is_active' ) && bp_is_active( 'notifications' ) ) : ?>
				<?php
				$notifications_link = trailingslashit( $user_link . 'notifications' );
				$notification_count = function_exists( 'bp_notifications_get_unread_notification_count' )
					? bp_notifications_get_unread_notification_count( $user_id )
					: 0;
				?>
				<div class="header-notifications">
					<a class="notification-link" href="<?php echo esc_url( $notifications_link ); ?>" title="<?php esc_attr_e( 'Notifications', 'flavor' ); ?>">
						<i class="fa fa-bell"></i>
						<?php if ( $notification_count > 0 ) : ?>
							<span class="notification-count"><?php echo esc_html( $notification_count ); ?></span>
						<?php endif; ?>
					</a>
					<?php
					// Notifications dropdown
					if ( $notification_count > 0 && function_exists( 'bp_notifications_get_notifications_for_user' ) ) :
						$notifications = bp_notifications_get_notifications_for_user( $user_id, 'object' );
						$notifications = array_slice( $notifications, 0, 5 ); // Limit to 5
						if ( ! empty( $notifications ) ) :
					?>
						<div class="notifications-dropdown">
							<?php foreach ( $notifications as $notification ) :
								$notification_content = isset( $notification->content ) ? $notification->content : '';
								$notification_href = isset( $notification->href ) ? $notification->href : $notifications_link;
							?>
								<a href="<?php echo esc_url( $notification_href ); ?>" class="notification-item">
									<?php echo wp_kses_post( $notification_content ); ?>
								</a>
							<?php endforeach; ?>
							<a href="<?php echo esc_url( $notifications_link ); ?>" class="notification-item view-all">
								<?php esc_html_e( 'View all notifications', 'flavor' ); ?>
							</a>
						</div>
					<?php
						endif;
					endif;
					?>
				</div>
			<?php endif; ?>

			<div class="header-account-login">
				<a class="user-link" href="<?php echo esc_url( $user_link ); ?>">
					<span class="user-avatar"><?php echo $avatar; ?></span>
					<span class="user-name user-name-body"><?php echo esc_html( $user_name ); ?></span>
				</a>
				<div class="account-dropdown">
					<?php if ( function_exists( 'bp_core_get_user_domain' ) ) : ?>
						<a href="<?php echo esc_url( $user_link ); ?>"><?php esc_html_e( 'My Profile', 'flavor' ); ?></a>
						<a href="<?php echo esc_url( trailingslashit( $user_link . 'settings' ) ); ?>"><?php esc_html_e( 'Settings', 'flavor' ); ?></a>
					<?php endif; ?>
					<?php if ( current_user_can( 'edit_posts' ) ) : ?>
						<a href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Dashboard', 'flavor' ); ?></a>
					<?php endif; ?>
					<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="logout-link"><?php esc_html_e( 'Log Out', 'flavor' ); ?></a>
				</div>
			</div>
		</div>
		<?php
	} else {
		// Logged out: show login/register links
		$login_url = wp_login_url();
		$register_url = function_exists( 'bp_get_signup_page' ) ? bp_get_signup_page() : wp_registration_url();
		?>
		<div class="header-account logged-out">
			<a href="<?php echo esc_url( $login_url ); ?>" class="btn-text"><?php esc_html_e( 'Log In', 'flavor' ); ?></a>
			<a href="<?php echo esc_url( $register_url ); ?>" class="btn-primary-sm"><?php esc_html_e( 'Register', 'flavor' ); ?></a>
		</div>
		<?php
	}

	return ob_get_clean();
}
add_shortcode( 'hcommons_header_account', 'hcommons_header_account_shortcode' );

/**
 * Ensure shortcodes are processed in block template parts
 *
 * This filter processes shortcodes in the core/shortcode block content,
 * which is needed for PHP templates that use block_template_part().
 *
 * @param string $block_content The block content.
 * @param array  $block         The full block, including name and attributes.
 * @return string Modified block content with shortcodes processed.
 */
function hcommons_process_shortcodes_in_blocks( $block_content, $block ) {
	if ( 'core/shortcode' === $block['blockName'] ) {
		$block_content = do_shortcode( $block_content );
	}
	return $block_content;
}
add_filter( 'render_block', 'hcommons_process_shortcodes_in_blocks', 10, 2 );

/**
 * Render blog posts HTML
 *
 * @param int  $count Number of posts to display
 * @param bool $is_external Whether posts are from external blog (affects link handling)
 * @return string HTML output
 */
function hcommons_render_blog_posts_html( $count, $is_external = false ) {
	$posts = get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => intval( $count ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );

	if ( empty( $posts ) ) {
		return '<p class="has-black-color has-text-color">No posts yet. Check back soon!</p>';
	}

	$output = '<div class="wp-block-query blog-grid"><ul class="wp-block-post-template is-layout-grid">';

	foreach ( $posts as $post ) {
		$permalink = get_permalink( $post->ID );
		$date = get_the_date( 'F j, Y', $post->ID );
		$title = get_the_title( $post->ID );
		$excerpt = wp_trim_words( $post->post_excerpt ? $post->post_excerpt : $post->post_content, 20 );

		$output .= '<li class="wp-block-post">';
		$output .= '<div class="wp-block-group blog-card p-50 rounded has-white-background-color has-background">';

		// Date
		$output .= '<time datetime="' . esc_attr( get_the_date( 'c', $post->ID ) ) . '" class="wp-block-post-date blog-date text-base font-medium has-teal-light-color has-text-color">' . esc_html( $date ) . '</time>';

		// Title with link
		$output .= '<h3 class="wp-block-post-title blog-title text-lg mt-20 mb-30"><a href="' . esc_url( $permalink ) . '">' . esc_html( $title ) . '</a></h3>';

		// Excerpt
		$output .= '<div class="wp-block-post-excerpt blog-excerpt text-base leading-normal has-teal-light-color has-text-color"><p>' . esc_html( $excerpt ) . '</p>';
		$output .= '<a href="' . esc_url( $permalink ) . '" class="wp-block-post-excerpt__more-link">Read more →</a>';
		$output .= '</div>';

		$output .= '</div>';
		$output .= '</li>';
	}

	$output .= '</ul></div>';

	return $output;
}
