<?php
/**
 * Behaviour tests for the BuddyPress Docs editor toolbars.
 *
 * Run with: php tests/test-bp-docs-editor-toolbar.php
 *
 * Framework-free like the other tests here. The subject is the shipped
 * stylesheet, resolved against the real markup WordPress emits for
 * wp_editor() on the Docs create/edit screen.
 *
 * Background: WordPress renders the Quicktags (Code tab) toolbar with the
 * class `hide-if-no-js` and hides it in Visual mode with
 * `.tmce-active .quicktags-toolbar { display: none }` in wp-includes/css/editor.css.
 * BuddyPress Docs' bp-docs.js then runs `$('.hide-if-no-js').show()` on
 * load, which leaves an inline `display: block` on that toolbar. An inline
 * declaration outranks every selector, so the Code toolbar ends up stacked
 * above the TinyMCE toolbar. Only an !important declaration in a stylesheet
 * can win back control, so that is what these tests look for: the resolved
 * display of the Quicktags toolbar, inline style included, in each editor
 * mode.
 *
 * @package HCommons
 */

require_once __DIR__ . '/css-cascade.php';

$theme_dir = dirname( __DIR__ );
$theme_css = file_get_contents( $theme_dir . '/assets/css/buddypress.css' );

// The core rules that act on the same element (WordPress 6.x,
// wp-includes/css/editor.css). editor.css is enqueued after the theme's
// stylesheet on the Docs edit screen, so it follows the theme rules here.
$core_editor_css = <<<'CSS'
.tmce-active .quicktags-toolbar {
	display: none;
}

.quicktags-toolbar {
	padding: 3px;
	position: relative;
	border-bottom: 1px solid #dcdcde;
	background: #f6f7f7;
	min-height: 30px;
}
CSS;

$rules = hc_test_css_parse( $theme_css . "\n" . $core_editor_css );

/**
 * Model an inline style attribute as a cascade entry.
 *
 * An inline declaration beats any selector but loses to !important, which
 * is exactly where it sits once given a specificity no selector can reach
 * and the latest source order.
 *
 * @param string $property Property name.
 * @param string $value    Property value.
 * @return array
 */
function hc_test_inline_style( $property, $value ) {
	return array(
		'selector'     => 'style=""',
		'specificity'  => array( PHP_INT_MAX, 0, 0 ),
		'parts'        => array(
			array(
				'combinator' => ' ',
				'compound'   => array( 'tag' => '*', 'id' => null, 'classes' => array() ),
			),
		),
		'declarations' => array(
			$property => array( 'value' => $value, 'important' => false ),
		),
		'order'        => PHP_INT_MAX,
	);
}

$el = 'hc_test_css_el';

/**
 * The element chain WordPress and BuddyPress Docs produce around the
 * Quicktags toolbar inside the theme's docs template.
 *
 * @param string $mode_class 'tmce-active' (Visual) or 'html-active' (Code).
 * @return array[]
 */
function hc_test_quicktags_chain( $mode_class ) {
	$el = 'hc_test_css_el';
	return array(
		$el( 'main' ),
		$el( 'div', 'buddypress' ),
		$el( 'form', 'doc-form', array( 'standard-form' ) ),
		$el( 'div', '', array( 'doc-content-wrapper' ) ),
		$el( 'div', 'doc-content-textarea' ),
		$el( 'div', 'editor-toolbar' ),
		$el( 'div', 'wp-doc_content-wrap', array( 'wp-core-ui', 'wp-editor-wrap', $mode_class ) ),
		$el( 'div', 'wp-doc_content-editor-container', array( 'wp-editor-container' ) ),
		$el( 'div', 'qt_doc_content_toolbar', array( 'quicktags-toolbar', 'hide-if-no-js' ) ),
	);
}

$cases = array(
	// Visual mode, after bp-docs.js has forced the toolbar open: the theme
	// must put it away again.
	array(
		'label'    => 'Visual mode hides the Quicktags toolbar even with an inline display:block',
		'mode'     => 'tmce-active',
		'inline'   => 'block',
		'expected' => 'none',
	),
	// Visual mode with no interference behaves as core intends.
	array(
		'label'    => 'Visual mode hides the Quicktags toolbar without an inline style',
		'mode'     => 'tmce-active',
		'inline'   => null,
		'expected' => 'none',
	),
	// Code mode must keep its toolbar: the fix must not hide Quicktags
	// outright.
	array(
		'label'    => 'Code mode keeps the Quicktags toolbar visible',
		'mode'     => 'html-active',
		'inline'   => 'block',
		'expected' => 'block',
	),
);

$failures = 0;

foreach ( $cases as $case ) {
	$case_rules = $rules;
	if ( null !== $case['inline'] ) {
		$case_rules[] = hc_test_inline_style( 'display', $case['inline'] );
	}

	$actual = hc_test_css_cascaded( $case_rules, hc_test_quicktags_chain( $case['mode'] ), 'display' );

	if ( $actual === $case['expected'] ) {
		echo "PASS: {$case['label']}\n";
	} else {
		$failures++;
		$shown = null === $actual ? '(unset)' : $actual;
		echo "FAIL: {$case['label']}: expected display {$case['expected']}, got {$shown}\n";
	}
}

if ( $failures > 0 ) {
	echo "\n{$failures} failure(s).\n";
	exit( 1 );
}

echo "\nAll tests passed.\n";
