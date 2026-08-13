<?php
/**
 * Standalone behaviour tests for the group admin "Settings" template
 * (buddypress/groups/single/admin.php).
 *
 * Run with: php tests/test-group-admin-settings.php
 *
 * Regression guard for issue #18: BuddyPress 12+ removed the legacy forums
 * component, so bp_forums_is_installed_correctly() no longer exists — but
 * bp_is_active( 'forums' ) can still return true on installs whose
 * bp-active-components option predates the upgrade. The template must render
 * the settings screen without fataling in that state.
 *
 * Deliberately framework-free, like the other tests here: we stub the
 * template tags the group-settings branch calls, include the template, and
 * assert on the rendered output (behaviour), never the implementation.
 *
 * The stubs model BuddyPress 14.5: bp_forums_is_installed_correctly() is NOT
 * defined. A second scenario (run as a subprocess with the 'legacy-forums'
 * argument) models an old install where it exists and returns true, and
 * expects the forum checkbox to render.
 *
 * @package HCommons
 */

$scenario = $argv[1] ?? 'modern';

// --- Stub the template tags used by the group-settings branch ---------------

function bp_group_admin_tabs() {
	echo '<li>tabs</li>';
}

function bp_group_admin_form_action() {
	echo 'https://example.org/groups/bp-test-group/admin/group-settings/';
}

function do_action( ...$args ) {}

function bp_is_group_admin_screen( $screen ) {
	return 'group-settings' === $screen;
}

function _e( $text, $domain = 'default' ) {
	echo $text;
}

// The stale bp-active-components option still lists 'forums' after the
// BuddyPress upgrade, so this returns true even though the component's code
// (including bp_forums_is_installed_correctly()) is gone.
function bp_is_active( $component ) {
	return 'forums' === $component;
}

function bp_group_show_forum_setting( $group = false ) {
	echo ' checked="checked"';
}

function bp_group_show_status_setting( $setting, $group = false ) {
	if ( 'public' === $setting ) {
		echo ' checked="checked"';
	}
}

function bp_group_show_invite_status_setting( $setting, $group = false ) {
	if ( 'members' === $setting ) {
		echo ' checked="checked"';
	}
}

function bp_get_current_group_slug() {
	return 'bp-test-group';
}

function esc_js( $text ) {
	return $text;
}

function wp_nonce_field( $action = -1 ) {
	echo '<input type="hidden" name="_wpnonce" value="nonce" />';
}

function bp_group_id() {
	echo '42';
}

if ( 'legacy-forums' === $scenario ) {
	// Old BuddyPress with the legacy forums component installed.
	function bp_forums_is_installed_correctly() {
		return true;
	}
}

// --- Render the template ----------------------------------------------------

$failures = 0;

ob_start();
try {
	require __DIR__ . '/../buddypress/groups/single/admin.php';
	$output = ob_get_clean();
	$error  = null;
} catch ( \Throwable $e ) {
	ob_end_clean();
	$output = '';
	$error  = $e;
}

$checks = array(
	'renders without a fatal error' => null === $error,
	'renders the settings form'     => str_contains( $output, 'group-settings-form' ),
	'renders the privacy options'   => str_contains( $output, 'Privacy Options' ),
	'renders the invitation options' => str_contains( $output, 'Group Invitations' ),
);

if ( 'legacy-forums' === $scenario ) {
	$checks['renders the forum checkbox when legacy forums are installed'] =
		str_contains( $output, 'group-show-forum' );
} else {
	$checks['omits the forum checkbox when legacy forums are absent'] =
		! str_contains( $output, 'group-show-forum' );
}

foreach ( $checks as $label => $passed ) {
	if ( $passed ) {
		printf( "PASS  [%s] %s\n", $scenario, $label );
	} else {
		$failures++;
		printf( "FAIL  [%s] %s\n", $scenario, $label );
		if ( 'renders without a fatal error' === $label && $error ) {
			printf( "      -> %s\n", $error->getMessage() );
		}
	}
}

// The modern scenario also drives the legacy scenario in a subprocess, since
// the conditional function definition cannot be undone within one process.
if ( 'modern' === $scenario ) {
	$cmd = escapeshellarg( PHP_BINARY ) . ' ' . escapeshellarg( __FILE__ ) . ' legacy-forums';
	passthru( $cmd, $legacy_exit );
	if ( 0 !== $legacy_exit ) {
		$failures++;
	}
}

echo "\n";
if ( $failures > 0 ) {
	echo "RESULT: {$failures} failing case(s) in scenario '{$scenario}'.\n";
	exit( 1 );
}

echo "RESULT: scenario '{$scenario}' passed.\n";
exit( 0 );
