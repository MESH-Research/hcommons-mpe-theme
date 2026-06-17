<?php
/**
 * Standalone behaviour tests for hcommons_works_url_from_host().
 *
 * Run with: php tests/test-works-url.php
 *
 * Deliberately framework-free: the function under test is pure, so we only
 * need assert() over a table of (host -> expected URL) cases. Tests exercise
 * the returned value (behaviour), never the implementation.
 *
 * @package HCommons
 */

require_once __DIR__ . '/../inc/works-url.php';

$cases = array(
	// Production apex domain.
	'hcommons.org'                 => 'https://works.hcommons.org',
	// Named non-production environments from the requirement.
	'hcommons-test.org'            => 'https://works.hcommons-test.org',
	'hcommons-dev.org'             => 'https://works.hcommons-dev.org',
	// A leading www. is not part of the base domain.
	'www.hcommons.org'             => 'https://works.hcommons.org',
	'www.hcommons-test.org'        => 'https://works.hcommons-test.org',
	// Host comparison is case-insensitive.
	'WWW.HCommons.ORG'             => 'https://works.hcommons.org',
	// Idempotent: a works. host stays a single works. host.
	'works.hcommons.org'           => 'https://works.hcommons.org',
	// Local Lando development host.
	'commons-wordpress.lndo.site'  => 'https://works.commons-wordpress.lndo.site',
	// Empty/invalid host falls back to the production Works URL.
	''                             => 'https://works.hcommons.org',
);

$failures = 0;
foreach ( $cases as $host => $expected ) {
	try {
		$actual = hcommons_works_url_from_host( $host );
	} catch ( \Throwable $e ) {
		$actual = 'EXCEPTION: ' . $e->getMessage();
	}

	if ( $actual === $expected ) {
		printf( "PASS  %-32s -> %s\n", "'{$host}'", $actual );
	} else {
		$failures++;
		printf( "FAIL  %-32s -> got %s, expected %s\n", "'{$host}'", var_export( $actual, true ), var_export( $expected, true ) );
	}
}

// --- hcommons_rewrite_works_links() behaviour -------------------------------

$nav = '<a href="/members/">Members</a><a href="https://works.hcommons.org">Works</a>';

$rewrite_cases = array(
	// Desktop/mobile nav link gets pointed at the dev environment.
	array(
		'content'  => $nav,
		'works'    => 'https://works.hcommons-dev.org',
		'expected' => '<a href="/members/">Members</a><a href="https://works.hcommons-dev.org">Works</a>',
	),
	// The same content on test.
	array(
		'content'  => $nav,
		'works'    => 'https://works.hcommons-test.org',
		'expected' => '<a href="/members/">Members</a><a href="https://works.hcommons-test.org">Works</a>',
	),
	// On production the Works URL is already canonical: leave content alone.
	array(
		'content'  => $nav,
		'works'    => 'https://works.hcommons.org',
		'expected' => $nav,
	),
	// Multiple occurrences (e.g. hero + features) are all rewritten.
	array(
		'content'  => 'a https://works.hcommons.org b https://works.hcommons.org c',
		'works'    => 'https://works.hcommons-dev.org',
		'expected' => 'a https://works.hcommons-dev.org b https://works.hcommons-dev.org c',
	),
	// An http:// Works link is upgraded to the (https) environment URL.
	array(
		'content'  => '<a href="http://works.hcommons.org">Works</a>',
		'works'    => 'https://works.hcommons-dev.org',
		'expected' => '<a href="https://works.hcommons-dev.org">Works</a>',
	),
	// Content with no Works link is untouched.
	array(
		'content'  => '<a href="/groups/">Groups</a>',
		'works'    => 'https://works.hcommons-dev.org',
		'expected' => '<a href="/groups/">Groups</a>',
	),
);

foreach ( $rewrite_cases as $i => $case ) {
	try {
		$actual = hcommons_rewrite_works_links( $case['content'], $case['works'] );
	} catch ( \Throwable $e ) {
		$actual = 'EXCEPTION: ' . $e->getMessage();
	}

	if ( $actual === $case['expected'] ) {
		printf( "PASS  rewrite#%d -> %s\n", $i, $actual );
	} else {
		$failures++;
		printf( "FAIL  rewrite#%d -> got %s, expected %s\n", $i, var_export( $actual, true ), var_export( $case['expected'], true ) );
	}
}

$total = count( $cases ) + count( $rewrite_cases );

echo "\n";
if ( $failures > 0 ) {
	echo "RESULT: {$failures} failing case(s).\n";
	exit( 1 );
}

echo "RESULT: all {$total} cases passed.\n";
exit( 0 );
