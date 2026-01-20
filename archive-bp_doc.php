<?php
/**
 * BuddyPress Docs Archive Template
 *
 * This template handles the /docs/ archive page for BuddyPress Docs.
 * It delegates rendering to the BuddyPress Docs plugin.
 *
 * @package HCommons
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php block_template_part( 'header' ); ?>

<main class="pt-header pb-80" style="background-color: var(--wp--preset--color--cream, #f5f5f0);">
	<div style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
		<div id="buddypress">
			<?php do_action( 'template_notices' ); ?>
			<?php
			// Try BP Docs specific content loading
			if ( function_exists( 'bp_docs_locate_template' ) ) {
				bp_docs_locate_template( 'docs-loop.php', true );
			} elseif ( function_exists( 'bp_get_template_part' ) ) {
				bp_get_template_part( 'docs/docs-loop' );
			} else {
				// Fallback - trigger standard content hooks
				do_action( 'bp_before_directory_docs_content' );
				do_action( 'bp_template_content' );
				do_action( 'bp_after_directory_docs_content' );
			}
			?>
		</div>
	</div>
</main>

<?php block_template_part( 'footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
