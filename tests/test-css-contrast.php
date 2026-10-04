<?php
/**
 * Behaviour tests for text contrast on the BuddyPress directory screens.
 *
 * Run with: php tests/test-css-contrast.php
 *
 * Framework-free like the other tests here. The subject is the shipped
 * stylesheet: we resolve the cascade for the real element structure of the
 * groups directory (filter tabs with their count badges, list item meta,
 * pagination count) and assert the resulting text/background pairs meet
 * WCAG 2.1 AA for normal text (4.5:1). The theme's stylesheet is enqueued
 * after BuddyPress's own bp-legacy stylesheet, which is reproduced below as a
 * fixture because its more specific selectors are part of what the theme has
 * to beat; the tests therefore exercise the colours a visitor actually sees,
 * not the colours the theme merely declares.
 *
 * @package HCommons
 */

require_once __DIR__ . '/css-cascade.php';

$theme_dir = dirname( __DIR__ );

// bp-legacy rules (BuddyPress 14.x, bp-templates/bp-legacy/css/buddypress.css)
// that style the same elements and precede the theme's stylesheet in the
// cascade. Reproduced verbatim so the test models the live cascade.
$bp_legacy = <<<'CSS'
#buddypress div.item-list-tabs ul li a,
#buddypress div.item-list-tabs ul li span {
	display: block;
	padding: 5px 10px;
	text-decoration: none;
}

#buddypress div.item-list-tabs ul li a span {
	background: #eee;
	border-radius: 50%;
	border: 1px solid #ccc;
	color: #6c6c6c;
	display: inline;
	font-size: 70%;
	margin-left: 2px;
	padding: 3px 6px;
	text-align: center;
	vertical-align: middle;
}

#buddypress div.item-list-tabs ul li.selected a,
#buddypress div.item-list-tabs ul li.current a {
	background-color: #eee;
	color: #555;
	opacity: 0.9;
	font-weight: 700;
}

#buddypress div.item-list-tabs ul li.selected a span,
#buddypress div.item-list-tabs ul li.current a span,
#buddypress div.item-list-tabs ul li a:hover span {
	background-color: #eee;
}

#buddypress div.item-list-tabs ul li.selected a span,
#buddypress div.item-list-tabs ul li.current a span {
	background-color: #fff;
}
CSS;

// bp-legacy's `background: #eee` shorthand is a background-color for our purposes.
$bp_legacy = str_replace( 'background: #eee;', 'background-color: #eee;', $bp_legacy );

$theme_css = file_get_contents( $theme_dir . '/assets/css/buddypress.css' );
$rules     = hc_test_css_parse( $bp_legacy . "\n" . $theme_css );

// The page background comes from theme.json global styles (a palette preset).
$theme_json = json_decode( file_get_contents( $theme_dir . '/theme.json' ), true );
$page_bg    = null;
if ( preg_match( '/--wp--preset--color--([\w-]+)/', $theme_json['styles']['color']['background'], $m ) ) {
	foreach ( $theme_json['settings']['color']['palette'] as $swatch ) {
		if ( $swatch['slug'] === $m[1] ) {
			$page_bg = hc_test_css_color( $swatch['color'] );
		}
	}
}
if ( null === $page_bg ) {
	fwrite( STDERR, "Could not resolve the page background from theme.json\n" );
	exit( 1 );
}

$el = 'hc_test_css_el';

// Groups directory structure as rendered by bp-legacy's groups/index.php and
// groups/groups-loop.php inside the theme's BuddyPress wrapper.
$page = array( $el( 'body' ), $el( 'div', 'buddypress' ) );
$tabs = array_merge( $page, array( $el( 'form', 'groups-directory-form', array( 'dir-form' ) ), $el( 'div', '', array( 'item-list-tabs' ) ), $el( 'ul' ) ) );
$list = array_merge( $page, array( $el( 'ul', 'groups-list', array( 'item-list' ) ), $el( 'li', '', array( 'is-member' ) ), $el( 'div', '', array( 'item' ) ) ) );
$pag  = array_merge( $page, array( $el( 'div', 'pag-top', array( 'pagination' ) ) ) );

$aa = 4.5;

$cases = array(
	array(
		'Filter tab label (not selected)',
		array_merge( $tabs, array( $el( 'li', 'groups-personal' ), $el( 'a' ) ) ),
	),
	array(
		'Filter tab count badge (not selected)',
		array_merge( $tabs, array( $el( 'li', 'groups-personal' ), $el( 'a' ), $el( 'span' ) ) ),
	),
	array(
		'Filter tab label (selected)',
		array_merge( $tabs, array( $el( 'li', 'groups-all', array( 'selected' ) ), $el( 'a' ) ) ),
	),
	array(
		'Filter tab count badge (selected)',
		array_merge( $tabs, array( $el( 'li', 'groups-all', array( 'selected' ) ), $el( 'a' ), $el( 'span' ) ) ),
	),
	array(
		'List item meta (relative activity time)',
		array_merge( $list, array( $el( 'div', '', array( 'item-meta' ) ), $el( 'span', '', array( 'activity' ) ) ) ),
	),
	array(
		'Pagination count',
		array_merge( $pag, array( $el( 'div', 'group-dir-count-top', array( 'pag-count' ) ) ) ),
	),
);

$failures = 0;
foreach ( $cases as $case ) {
	list( $label, $chain ) = $case;
	$colors = hc_test_css_effective_colors( $rules, $chain, $page_bg );
	$ratio  = hc_test_css_contrast( $colors['color'], $colors['background'] );
	$detail = sprintf(
		'%s on %s = %.2f:1',
		hc_test_css_hex( $colors['color'] ),
		hc_test_css_hex( $colors['background'] ),
		$ratio
	);
	if ( $ratio >= $aa ) {
		printf( "PASS  %-42s %s\n", $label, $detail );
	} else {
		$failures++;
		printf( "FAIL  %-42s %s (need %.1f:1)\n", $label, $detail, $aa );
	}
}

// The selected tab must read as selected: its background must differ clearly
// from an unselected tab's, otherwise the count badge recolouring above is
// applied to the wrong backdrop. Compare the two tab link backgrounds.
$unselected = hc_test_css_effective_colors( $rules, array_merge( $tabs, array( $el( 'li', 'groups-personal' ), $el( 'a' ) ) ), $page_bg );
$selected   = hc_test_css_effective_colors( $rules, array_merge( $tabs, array( $el( 'li', 'groups-all', array( 'selected' ) ), $el( 'a' ) ) ), $page_bg );
$ratio      = hc_test_css_contrast( $unselected['background'], $selected['background'] );
$label      = 'Selected vs unselected tab background';
$detail     = sprintf( '%s vs %s = %.2f:1', hc_test_css_hex( $selected['background'] ), hc_test_css_hex( $unselected['background'] ), $ratio );
if ( $ratio >= 3 ) {
	printf( "PASS  %-42s %s\n", $label, $detail );
} else {
	$failures++;
	printf( "FAIL  %-42s %s (need 3.0:1)\n", $label, $detail );
}

if ( $failures ) {
	printf( "\n%d failure(s)\n", $failures );
	exit( 1 );
}
echo "\nAll contrast checks passed\n";
