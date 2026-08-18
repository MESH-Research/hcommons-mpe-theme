<?php
/**
 * Standalone behaviour tests for hcommons_bp_docs_page_title().
 *
 * Run with: php tests/test-bp-docs-title.php
 *
 * Deliberately framework-free, following tests/test-works-url.php: the
 * function under test is pure, so we assert over a table of
 * (view, directory title) -> expected title cases. Tests exercise the
 * returned value (behaviour), never the implementation.
 *
 * @package HCommons
 */

require_once __DIR__ . '/../inc/bp-docs-title.php';

$cases = array(
	// The doc-creation screen always gets the "Create a Doc" title, matching
	// the title BuddyPress Docs itself puts on its theme-compat dummy post.
	array( 'create', '', 'Create a Doc' ),
	// A configured directory title never leaks onto the create screen.
	array( 'create', 'Our Documents', 'Create a Doc' ),
	// Directory views use the configured directory title when one exists.
	array( 'directory', 'Our Documents', 'Our Documents' ),
	// With no configured title, fall back to the plugin's default.
	array( 'directory', '', 'Docs Directory' ),
	// Whitespace-only configured titles are treated as unset.
	array( 'directory', '   ', 'Docs Directory' ),
	// Unknown views behave like directory views rather than being blank.
	array( 'mygroups', 'Our Documents', 'Our Documents' ),
	array( 'mygroups', '', 'Docs Directory' ),
);

$failures = 0;

foreach ( $cases as $case ) {
	list( $view, $directory_title, $expected ) = $case;

	$actual = hcommons_bp_docs_page_title( $view, $directory_title );

	if ( $actual !== $expected ) {
		$failures++;
		printf(
			"FAIL: view '%s' with directory title '%s' -> expected '%s', got '%s'\n",
			$view,
			$directory_title,
			$expected,
			var_export( $actual, true )
		);
	}
}

// The result is always a non-empty string, whatever the inputs.
$edge = hcommons_bp_docs_page_title( '', '' );
if ( ! is_string( $edge ) || '' === trim( $edge ) ) {
	$failures++;
	printf( "FAIL: empty view/title must still produce a non-empty title, got %s\n", var_export( $edge, true ) );
}

if ( $failures > 0 ) {
	printf( "%d test(s) failed.\n", $failures );
	exit( 1 );
}

echo "All hcommons_bp_docs_page_title() tests passed.\n";
exit( 0 );
