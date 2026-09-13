<?php
/**
 * Regression tests for the group-creation settings template.
 *
 * Run with: php tests/test-group-create-template.php
 *
 * The settings step of the creation wizard used to carry the pre-bbPress-2.x
 * "legacy forums" section, gated on bp_is_active( 'forums' ). Since bbPress
 * registers itself as the 'forums' BuddyPress component, that gate is always
 * open, its bb_forums_is_installed_correctly() check can never pass, and
 * super admins were shown a spurious "Attention Site Admin: Group forums
 * require the correct setup and configuration of a bbPress installation"
 * notice (knowledge-commons-wordpress#113). Group forums are created on
 * bbPress's own wizard step, so the legacy section must stay gone.
 *
 * @package HCommons
 */

$template = file_get_contents( __DIR__ . '/../buddypress/groups/create.php' );

$forbidden = array(
	// The legacy admin nag and its settings screen.
	'bb-forums-setup',
	// The legacy BP-forums installation checks.
	'bb_forums_is_installed_correctly',
	'bp_forums_is_installed_correctly',
	// The legacy forum checkbox (bbPress renders its own on the forum step).
	'group-show-forum',
);

$failures = 0;

foreach ( $forbidden as $needle ) {
	if ( false !== strpos( $template, $needle ) ) {
		echo "FAIL: groups/create.php still references '{$needle}'\n";
		$failures++;
	} else {
		echo "ok: no reference to '{$needle}'\n";
	}
}

if ( $failures ) {
	echo "{$failures} failure(s)\n";
	exit( 1 );
}

echo "All group-create template checks passed.\n";
