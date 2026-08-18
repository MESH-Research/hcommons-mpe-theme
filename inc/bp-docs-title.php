<?php
/**
 * Pure helpers for resolving the page title on BuddyPress Docs screens.
 *
 * These functions are deliberately WordPress-independent so they can be
 * exercised by the standalone tests in tests/test-bp-docs-title.php.
 *
 * @package HCommons
 */

if ( ! function_exists( 'hcommons_bp_docs_page_title' ) ) {
	/**
	 * Resolve the display title for a BuddyPress Docs page.
	 *
	 * @param string $view            The current Docs view: 'create' for the
	 *                                doc-creation screen, anything else is
	 *                                treated as a directory view.
	 * @param string $directory_title The configured Docs directory title, if any.
	 * @return string The title to display.
	 */
	function hcommons_bp_docs_page_title( $view, $directory_title = '' ) {
		if ( 'create' === $view ) {
			return function_exists( '__' ) ? __( 'Create a Doc', 'buddypress-docs' ) : 'Create a Doc';
		}

		$directory_title = trim( (string) $directory_title );

		if ( '' !== $directory_title ) {
			return $directory_title;
		}

		return function_exists( '__' ) ? __( 'Docs Directory', 'buddypress-docs' ) : 'Docs Directory';
	}
}
