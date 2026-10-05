<?php
/**
 * Behaviour tests for hcommons_asset_version().
 *
 * Run with: php tests/test-asset-version.php
 *
 * Framework-free like the other tests here. The theme's stylesheets were
 * enqueued with the static theme version, so a CSS fix shipped under the
 * same `?ver=` as the file it replaced and browsers kept serving the stale
 * copy. The version string therefore has to change whenever the file does,
 * and still be usable when the file is missing.
 *
 * @package HCommons
 */

require_once __DIR__ . '/../inc/asset-version.php';

$failures = 0;

/**
 * Record a check.
 *
 * @param bool   $ok    Whether it passed.
 * @param string $label What was checked.
 * @param string $extra Detail shown on failure.
 */
function check( $ok, $label, $extra = '' ) {
	global $failures;
	if ( $ok ) {
		echo "PASS: {$label}\n";
	} else {
		$failures++;
		echo "FAIL: {$label}" . ( '' !== $extra ? ": {$extra}" : '' ) . "\n";
	}
}

$dir  = sys_get_temp_dir() . '/hc-asset-version-' . getmypid();
mkdir( $dir );
$file = $dir . '/style.css';
file_put_contents( $file, 'body { color: red; }' );

// A readable file yields a non-empty version string.
$v1 = hcommons_asset_version( $file, '1.0.0' );
check( is_string( $v1 ) && '' !== $v1, 'returns a non-empty string for an existing file', var_export( $v1, true ) );

// The same unchanged file yields the same version, so caches stay warm.
check( hcommons_asset_version( $file, '1.0.0' ) === $v1, 'is stable while the file is unchanged' );

// Editing the file changes the version even when the theme version does not.
file_put_contents( $file, 'body { color: blue; }' );
touch( $file, filemtime( $file ) + 60 );
clearstatcache();
$v2 = hcommons_asset_version( $file, '1.0.0' );
check( $v2 !== $v1, 'changes when the file is modified', "{$v1} then {$v2}" );

// The fallback is used verbatim when the file cannot be read.
check( hcommons_asset_version( $dir . '/missing.css', '1.0.0' ) === '1.0.0', 'falls back to the given version for a missing file' );

unlink( $file );
rmdir( $dir );

if ( $failures > 0 ) {
	echo "\n{$failures} failure(s).\n";
	exit( 1 );
}

echo "\nAll tests passed.\n";
