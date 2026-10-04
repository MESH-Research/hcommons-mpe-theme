<?php
/**
 * Minimal CSS cascade helpers for stylesheet behaviour tests.
 *
 * Just enough of the cascade to ask a real stylesheet "what colour does this
 * element end up, and on what background?": selector matching, specificity,
 * source order, !important, inheritance of `color`, alpha compositing of
 * backgrounds, group opacity, plus the WCAG 2.x contrast maths.
 *
 * This is deliberately not a browser. Supported selectors are compounds of
 * type, #id and .class joined by descendant (space) and child (>)
 * combinators. Rules using anything else (pseudo-classes, attributes, etc.)
 * never match, and at-rule blocks such as @media are skipped, so tests see the
 * default, non-interactive, wide-viewport cascade.
 *
 * @package HCommons
 */

/**
 * Describe an element for use in a selector-matching chain.
 *
 * @param string   $tag     Element name.
 * @param string   $id      Optional id attribute.
 * @param string[] $classes Optional class list.
 * @return array
 */
function hc_test_css_el( $tag, $id = '', $classes = array() ) {
	return array(
		'tag'     => strtolower( $tag ),
		'id'      => $id,
		'classes' => array_values( $classes ),
	);
}

/**
 * Parse a stylesheet into a flat, source-ordered rule list.
 *
 * Each entry is array( 'selector' => string, 'specificity' => array|null,
 * 'parts' => array|null, 'declarations' => array, 'order' => int ).
 *
 * @param string $css Raw stylesheet text.
 * @return array[]
 */
function hc_test_css_parse( $css ) {
	$css   = preg_replace( '~/\*.*?\*/~s', '', $css );
	$rules = array();
	$len   = strlen( $css );
	$pos   = 0;
	$order = 0;

	while ( $pos < $len ) {
		$brace = strpos( $css, '{', $pos );
		if ( false === $brace ) {
			break;
		}
		$prelude = trim( substr( $css, $pos, $brace - $pos ) );

		// Find the matching closing brace for this block.
		$depth = 0;
		$end   = $brace;
		for ( $i = $brace; $i < $len; $i++ ) {
			if ( '{' === $css[ $i ] ) {
				$depth++;
			} elseif ( '}' === $css[ $i ] ) {
				$depth--;
				if ( 0 === $depth ) {
					$end = $i;
					break;
				}
			}
		}
		$body = substr( $css, $brace + 1, $end - $brace - 1 );
		$pos  = $end + 1;

		// A statement at-rule (@import/@charset) may precede the prelude; drop it.
		$semi = strrpos( $prelude, ';' );
		if ( false !== $semi ) {
			$prelude = trim( substr( $prelude, $semi + 1 ) );
		}
		if ( '' === $prelude || '@' === $prelude[0] ) {
			continue; // Skip @media, @font-face, @supports, etc.
		}

		$declarations = hc_test_css_parse_declarations( $body );
		foreach ( explode( ',', $prelude ) as $selector ) {
			$selector = trim( preg_replace( '/\s+/', ' ', $selector ) );
			if ( '' === $selector ) {
				continue;
			}
			$parts   = hc_test_css_parse_selector( $selector );
			$rules[] = array(
				'selector'     => $selector,
				'parts'        => $parts,
				'specificity'  => $parts ? hc_test_css_specificity( $parts ) : null,
				'declarations' => $declarations,
				'order'        => $order,
			);
		}
		$order++;
	}

	return $rules;
}

/**
 * Parse a declaration block body.
 *
 * @param string $body Text between the braces.
 * @return array<string, array{value: string, important: bool}>
 */
function hc_test_css_parse_declarations( $body ) {
	$out = array();
	foreach ( explode( ';', $body ) as $decl ) {
		$colon = strpos( $decl, ':' );
		if ( false === $colon ) {
			continue;
		}
		$prop  = strtolower( trim( substr( $decl, 0, $colon ) ) );
		$value = trim( substr( $decl, $colon + 1 ) );
		if ( '' === $prop ) {
			continue;
		}
		$important = false;
		if ( preg_match( '/!\s*important\s*$/i', $value ) ) {
			$important = true;
			$value     = trim( preg_replace( '/!\s*important\s*$/i', '', $value ) );
		}
		$out[ $prop ] = array(
			'value'     => $value,
			'important' => $important,
		);
	}
	return $out;
}

/**
 * Parse a complex selector into compounds and combinators.
 *
 * @param string $selector Single (comma-free) selector.
 * @return array[]|null Null when the selector uses unsupported syntax.
 */
function hc_test_css_parse_selector( $selector ) {
	$selector = trim( preg_replace( '/\s*>\s*/', ' > ', $selector ) );
	$parts    = array();
	$next     = ' ';
	foreach ( preg_split( '/\s+/', $selector ) as $token ) {
		if ( '>' === $token ) {
			$next = '>';
			continue;
		}
		$compound = hc_test_css_parse_compound( $token );
		if ( null === $compound ) {
			return null;
		}
		$parts[] = array(
			'compound'   => $compound,
			'combinator' => $next, // Relationship to the compound on its left.
		);
		$next = ' ';
	}
	return $parts ? $parts : null;
}

/**
 * Parse one compound selector such as `div.item-list-tabs` or `#buddypress`.
 *
 * @param string $token Compound selector text.
 * @return array|null Null when the compound uses unsupported syntax.
 */
function hc_test_css_parse_compound( $token ) {
	if ( ! preg_match( '/^(\*|[a-zA-Z][\w-]*)?((?:[#.][\w-]+)*)$/', $token, $m ) ) {
		return null;
	}
	$compound = array(
		'tag'     => isset( $m[1] ) && '' !== $m[1] ? strtolower( $m[1] ) : null,
		'id'      => null,
		'classes' => array(),
	);
	if ( isset( $m[2] ) && '' !== $m[2] ) {
		preg_match_all( '/([#.])([\w-]+)/', $m[2], $bits, PREG_SET_ORDER );
		foreach ( $bits as $bit ) {
			if ( '#' === $bit[1] ) {
				$compound['id'] = $bit[2];
			} else {
				$compound['classes'][] = $bit[2];
			}
		}
	}
	return $compound;
}

/**
 * Specificity of a parsed selector as array( ids, classes, types ).
 *
 * @param array[] $parts Output of hc_test_css_parse_selector().
 * @return int[]
 */
function hc_test_css_specificity( $parts ) {
	$ids = 0;
	$cls = 0;
	$typ = 0;
	foreach ( $parts as $part ) {
		$c = $part['compound'];
		if ( null !== $c['id'] ) {
			$ids++;
		}
		$cls += count( $c['classes'] );
		if ( null !== $c['tag'] && '*' !== $c['tag'] ) {
			$typ++;
		}
	}
	return array( $ids, $cls, $typ );
}

/**
 * Whether one compound selector matches one element.
 *
 * @param array $compound Parsed compound.
 * @param array $element  Element from hc_test_css_el().
 * @return bool
 */
function hc_test_css_compound_matches( $compound, $element ) {
	if ( null !== $compound['tag'] && '*' !== $compound['tag'] && $compound['tag'] !== $element['tag'] ) {
		return false;
	}
	if ( null !== $compound['id'] && $compound['id'] !== $element['id'] ) {
		return false;
	}
	foreach ( $compound['classes'] as $class ) {
		if ( ! in_array( $class, $element['classes'], true ) ) {
			return false;
		}
	}
	return true;
}

/**
 * Whether a parsed selector matches the last element of an ancestor chain.
 *
 * @param array[] $parts Parsed selector parts.
 * @param array[] $chain Elements from the root down to the target.
 * @return bool
 */
function hc_test_css_selector_matches( $parts, $chain ) {
	return hc_test_css_match_from( $parts, count( $parts ) - 1, $chain, count( $chain ) - 1 );
}

/**
 * Recursive right-to-left matcher with backtracking for descendant combinators.
 *
 * @param array[] $parts Parsed selector parts.
 * @param int     $pi    Index of the compound being matched.
 * @param array[] $chain Element chain.
 * @param int     $ei    Index of the element it must match.
 * @return bool
 */
function hc_test_css_match_from( $parts, $pi, $chain, $ei ) {
	if ( $ei < 0 ) {
		return false;
	}
	if ( ! hc_test_css_compound_matches( $parts[ $pi ]['compound'], $chain[ $ei ] ) ) {
		return false;
	}
	if ( 0 === $pi ) {
		return true;
	}
	if ( '>' === $parts[ $pi ]['combinator'] ) {
		return hc_test_css_match_from( $parts, $pi - 1, $chain, $ei - 1 );
	}
	for ( $k = $ei - 1; $k >= 0; $k-- ) {
		if ( hc_test_css_match_from( $parts, $pi - 1, $chain, $k ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Cascaded value of a property on the last element of a chain.
 *
 * Resolves by !important, then specificity, then source order. Returns null
 * when no matching rule declares the property (no inheritance here).
 *
 * @param array[] $rules    Parsed rules.
 * @param array[] $chain    Element chain.
 * @param string  $property Property name.
 * @return string|null
 */
function hc_test_css_cascaded( $rules, $chain, $property ) {
	$best     = null;
	$best_key = null;
	foreach ( $rules as $rule ) {
		if ( null === $rule['parts'] || ! isset( $rule['declarations'][ $property ] ) ) {
			continue;
		}
		if ( ! hc_test_css_selector_matches( $rule['parts'], $chain ) ) {
			continue;
		}
		$decl = $rule['declarations'][ $property ];
		$key  = array_merge( array( $decl['important'] ? 1 : 0 ), $rule['specificity'], array( $rule['order'] ) );
		if ( null === $best_key || $key >= $best_key ) {
			$best_key = $key;
			$best     = $decl['value'];
		}
	}
	return $best;
}

/**
 * Parse a CSS colour into array( r, g, b, a ) with 0-255 channels and 0-1 alpha.
 *
 * @param string|null $value CSS colour value.
 * @return float[]|null Null when unparseable.
 */
function hc_test_css_color( $value ) {
	if ( null === $value ) {
		return null;
	}
	$value = strtolower( trim( $value ) );
	$named = array(
		'white'       => array( 255, 255, 255, 1 ),
		'black'       => array( 0, 0, 0, 1 ),
		'transparent' => array( 0, 0, 0, 0 ),
	);
	if ( isset( $named[ $value ] ) ) {
		return $named[ $value ];
	}
	if ( preg_match( '/^#([0-9a-f]{3,8})$/', $value, $m ) ) {
		$hex = $m[1];
		if ( 3 === strlen( $hex ) || 4 === strlen( $hex ) ) {
			$hex = preg_replace( '/(.)/', '$1$1', $hex );
		}
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
		$a = 8 === strlen( $hex ) ? hexdec( substr( $hex, 6, 2 ) ) / 255 : 1;
		return array( $r, $g, $b, $a );
	}
	if ( preg_match( '/^rgba?\(\s*([^)]+)\)$/', $value, $m ) ) {
		$inner = str_replace( '/', ' ', $m[1] );
		$bits  = preg_split( '/[\s,]+/', trim( $inner ) );
		if ( count( $bits ) < 3 ) {
			return null;
		}
		$chan = array();
		foreach ( array_slice( $bits, 0, 3 ) as $b ) {
			$chan[] = '%' === substr( $b, -1 ) ? (float) $b * 2.55 : (float) $b;
		}
		$a = 1;
		if ( isset( $bits[3] ) ) {
			$a = '%' === substr( $bits[3], -1 ) ? (float) $bits[3] / 100 : (float) $bits[3];
		}
		return array( $chan[0], $chan[1], $chan[2], $a );
	}
	return null;
}

/**
 * Composite a (possibly translucent) colour over an opaque backdrop.
 *
 * @param float[] $fg Colour with alpha.
 * @param float[] $bg Opaque backdrop.
 * @return float[] Opaque result (alpha 1).
 */
function hc_test_css_over( $fg, $bg ) {
	$a = isset( $fg[3] ) ? $fg[3] : 1;
	return array(
		$fg[0] * $a + $bg[0] * ( 1 - $a ),
		$fg[1] * $a + $bg[1] * ( 1 - $a ),
		$fg[2] * $a + $bg[2] * ( 1 - $a ),
		1,
	);
}

/**
 * Effective text colour and backdrop for the last element of a chain.
 *
 * Walks the chain from the root compositing each element's background-color
 * over what is behind it; the text colour is the nearest declared `color`
 * (inherited otherwise) composited over that backdrop. Elements with
 * `opacity` below 1 blend both their text and background with the backdrop
 * outside them, innermost first, as a browser does for an opacity group.
 *
 * @param array[] $rules   Parsed rules.
 * @param array[] $chain   Element chain, root first.
 * @param float[] $root_bg Opaque colour behind the root element.
 * @return array{color: float[], background: float[]}
 */
function hc_test_css_effective_colors( $rules, $chain, $root_bg ) {
	$n         = count( $chain );
	$outside   = array(); // Backdrop immediately outside element i (before its own bg).
	$backdrop  = $root_bg;
	$color     = null;
	for ( $i = 0; $i < $n; $i++ ) {
		$sub          = array_slice( $chain, 0, $i + 1 );
		$outside[ $i ] = $backdrop;
		$bg           = hc_test_css_color( hc_test_css_cascaded( $rules, $sub, 'background-color' ) );
		if ( null !== $bg && $bg[3] > 0 ) {
			$backdrop = hc_test_css_over( $bg, $backdrop );
		}
		$c = hc_test_css_color( hc_test_css_cascaded( $rules, $sub, 'color' ) );
		if ( null !== $c ) {
			$color = $c;
		}
	}
	if ( null === $color ) {
		$color = array( 0, 0, 0, 1 );
	}
	$color = hc_test_css_over( $color, $backdrop );

	// Apply opacity groups from the target outwards.
	for ( $i = $n - 1; $i >= 0; $i-- ) {
		$sub     = array_slice( $chain, 0, $i + 1 );
		$opacity = hc_test_css_cascaded( $rules, $sub, 'opacity' );
		if ( null === $opacity || (float) $opacity >= 1 ) {
			continue;
		}
		$o        = max( 0, (float) $opacity );
		$color    = hc_test_css_over( array( $color[0], $color[1], $color[2], $o ), $outside[ $i ] );
		$backdrop = hc_test_css_over( array( $backdrop[0], $backdrop[1], $backdrop[2], $o ), $outside[ $i ] );
	}

	return array(
		'color'      => $color,
		'background' => $backdrop,
	);
}

/**
 * WCAG 2.x relative luminance of an opaque sRGB colour.
 *
 * @param float[] $rgb Colour with 0-255 channels.
 * @return float
 */
function hc_test_css_luminance( $rgb ) {
	$lin = array();
	foreach ( array_slice( $rgb, 0, 3 ) as $c ) {
		$c     = $c / 255;
		$lin[] = $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
	}
	return 0.2126 * $lin[0] + 0.7152 * $lin[1] + 0.0722 * $lin[2];
}

/**
 * WCAG 2.x contrast ratio between two opaque colours.
 *
 * @param float[] $a First colour.
 * @param float[] $b Second colour.
 * @return float Ratio from 1 to 21.
 */
function hc_test_css_contrast( $a, $b ) {
	$la = hc_test_css_luminance( $a );
	$lb = hc_test_css_luminance( $b );
	return ( max( $la, $lb ) + 0.05 ) / ( min( $la, $lb ) + 0.05 );
}

/**
 * Format a colour for test output.
 *
 * @param float[] $rgb Colour.
 * @return string
 */
function hc_test_css_hex( $rgb ) {
	return sprintf( '#%02x%02x%02x', (int) round( $rgb[0] ), (int) round( $rgb[1] ), (int) round( $rgb[2] ) );
}
