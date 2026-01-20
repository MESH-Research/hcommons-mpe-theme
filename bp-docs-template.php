<?php
/**
 * BuddyPress Docs Template
 *
 * This template handles all BuddyPress Docs pages including
 * directory, create, edit, and single doc views.
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
			if ( function_exists( 'bp_docs_locate_template' ) ) {
				// Check for create/edit page
				if ( function_exists( 'bp_docs_is_doc_create' ) && bp_docs_is_doc_create() ) {
					bp_docs_locate_template( 'single/edit.php', true );
				} elseif ( function_exists( 'bp_docs_is_doc_edit' ) && bp_docs_is_doc_edit() ) {
					// Show document title when editing
					$doc_id = get_queried_object_id();
					if ( $doc_id ) {
						echo '<h2 class="doc-title">' . esc_html( get_the_title( $doc_id ) ) . '</h2>';
					}
					bp_docs_locate_template( 'single/edit.php', true );
				} elseif ( function_exists( 'bp_docs_is_single_doc' ) && bp_docs_is_single_doc() ) {
					// Show document title when viewing
					$doc_id = get_queried_object_id();
					if ( $doc_id ) {
						echo '<h2 class="doc-title">' . esc_html( get_the_title( $doc_id ) ) . '</h2>';
					}
					bp_docs_locate_template( 'single/index.php', true );
				} else {
					// Default to docs directory/list
					bp_docs_locate_template( 'docs-loop.php', true );
				}
			} else {
				// Fallback
				do_action( 'bp_template_content' );
			}
			?>
		</div>
	</div>
</main>

<?php block_template_part( 'footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
