<?php
/**
 * Standalone behaviour tests for hcommons_bp_docs_template().
 *
 * Run with: php tests/test-bp-docs-template.php
 *
 * Regression guard for MESH-Research/knowledge-commons-wordpress#121: the
 * template_include filter used to swap in the theme's standalone
 * bp-docs-template.php for EVERY page where bp_docs_is_docs_component() is
 * true. That function is also true on a group's docs tab
 * (/groups/{slug}/docs/), so group docs lost the group header and nav — and
 * with them, any way back to the group. Group-context docs pages must be left
 * to BuddyPress, which renders them inside the group's own page like any
 * other group tab.
 *
 * Deliberately framework-free, like the other tests here: we stub the WP/BP
 * functions the filter consults, include functions.php, and assert on the
 * returned template path (behaviour), never the implementation. Because the
 * stubs differ per scenario and PHP functions cannot be redefined, each
 * scenario runs in its own subprocess, driven by the default 'driver'
 * invocation.
 *
 * @package HCommons
 */

$scenario = $argv[1] ?? 'driver';

// ---------------------------------------------------------------------------
// Driver: run each scenario in a subprocess and aggregate the results.
// ---------------------------------------------------------------------------

if ( 'driver' === $scenario ) {
	$scenarios = array(
		'group-docs',
		'global-docs-directory',
		'single-doc',
		'non-docs-page',
		'no-buddypress-docs',
	);

	$failures = 0;
	foreach ( $scenarios as $name ) {
		$cmd = escapeshellarg( PHP_BINARY ) . ' ' . escapeshellarg( __FILE__ ) . ' ' . escapeshellarg( $name );
		passthru( $cmd, $exit_code );
		if ( 0 !== $exit_code ) {
			$failures++;
		}
	}

	echo "\n";
	if ( $failures > 0 ) {
		echo "RESULT: {$failures} failing scenario(s).\n";
		exit( 1 );
	}

	echo "RESULT: all scenarios passed.\n";
	exit( 0 );
}

// ---------------------------------------------------------------------------
// Shared stubs: the minimum needed to load functions.php outside WordPress.
// ---------------------------------------------------------------------------

define( 'ABSPATH', sys_get_temp_dir() . '/' );

function add_action( ...$args ) {}
function add_filter( ...$args ) {}
function add_shortcode( ...$args ) {}

function get_template_directory() {
	return dirname( __DIR__ );
}

// ---------------------------------------------------------------------------
// Per-scenario stubs for the conditional tags the filter consults.
// ---------------------------------------------------------------------------

switch ( $scenario ) {
	case 'group-docs':
		// A group's docs tab: /groups/{slug}/docs/. BP Docs reports its
		// component as active here, but we are inside a group.
		function bp_docs_is_docs_component() {
			return true;
		}
		function bp_is_group() {
			return true;
		}
		function is_post_type_archive( $post_type = '' ) {
			return false;
		}
		break;

	case 'global-docs-directory':
		// The sitewide docs directory: /docs/.
		function bp_docs_is_docs_component() {
			return true;
		}
		function bp_is_group() {
			return false;
		}
		function is_post_type_archive( $post_type = '' ) {
			return false;
		}
		break;

	case 'single-doc':
		// A single doc at its global URL: /docs/{slug}. Not group context.
		function bp_docs_is_docs_component() {
			return true;
		}
		function bp_is_group() {
			return false;
		}
		function is_post_type_archive( $post_type = '' ) {
			return false;
		}
		break;

	case 'non-docs-page':
		// Any ordinary page with BuddyPress Docs active.
		function bp_docs_is_docs_component() {
			return false;
		}
		function bp_is_group() {
			return false;
		}
		function is_post_type_archive( $post_type = '' ) {
			return false;
		}
		break;

	case 'no-buddypress-docs':
		// BuddyPress Docs (and BuddyPress) not loaded at all: none of the
		// bp_* conditionals exist.
		function is_post_type_archive( $post_type = '' ) {
			return false;
		}
		break;

	default:
		fwrite( STDERR, "Unknown scenario '{$scenario}'.\n" );
		exit( 1 );
}

// ---------------------------------------------------------------------------
// Load the theme's functions.php and exercise the filter callback.
// ---------------------------------------------------------------------------

require dirname( __DIR__ ) . '/functions.php';

$original_template = '/wp-core/resolved/block-template.php';
$docs_template     = get_template_directory() . '/bp-docs-template.php';

try {
	$returned = hcommons_bp_docs_template( $original_template );
	$error    = null;
} catch ( \Throwable $e ) {
	$returned = null;
	$error    = $e;
}

$checks = array(
	'runs without a fatal error' => null === $error,
);

switch ( $scenario ) {
	case 'group-docs':
		// The crux of #121: group docs must be left to BuddyPress so the
		// page keeps the group header/nav (the way back to the group).
		$checks['leaves the group docs tab to BuddyPress'] = $returned === $original_template;
		break;

	case 'global-docs-directory':
	case 'single-doc':
		// Non-group docs pages still need the theme's standalone template
		// (FSE archive resolution mishandles the bp_doc post type).
		$checks['uses the theme docs template outside group context'] = $returned === $docs_template;
		break;

	case 'non-docs-page':
	case 'no-buddypress-docs':
		$checks['leaves unrelated pages untouched'] = $returned === $original_template;
		break;
}

$failures = 0;
foreach ( $checks as $label => $passed ) {
	if ( $passed ) {
		printf( "PASS  [%s] %s\n", $scenario, $label );
	} else {
		$failures++;
		printf( "FAIL  [%s] %s\n", $scenario, $label );
		if ( 'runs without a fatal error' === $label && $error ) {
			printf( "      -> %s\n", $error->getMessage() );
		}
	}
}

exit( $failures > 0 ? 1 : 0 );
