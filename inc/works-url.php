<?php
/**
 * Works (KCWorks) site URL derivation.
 *
 * Pure, WordPress-independent helpers so they can be exercised in isolation.
 *
 * @package HCommons
 */

if ( ! function_exists( 'hcommons_works_url_from_host' ) ) {
	/**
	 * Derive the environment-appropriate Works site URL from a host name.
	 *
	 * The KCWorks site always lives at the `works.` subdomain of the same base
	 * domain that is serving the current request, so:
	 *   hcommons.org       -> https://works.hcommons.org
	 *   hcommons-test.org  -> https://works.hcommons-test.org
	 *   hcommons-dev.org   -> https://works.hcommons-dev.org
	 *
	 * A leading `www.` is dropped and the lookup is case-insensitive. If the
	 * host is empty or already a `works.` host the function still returns a
	 * sane, canonical Works URL.
	 *
	 * @param string $host Host name, e.g. "hcommons-test.org" (no scheme/port).
	 * @return string Fully-qualified Works site URL including scheme.
	 */
	function hcommons_works_url_from_host( $host ) {
		// Normalise: hosts are case-insensitive and may carry surrounding whitespace.
		$host = strtolower( trim( (string) $host ) );

		// Without a usable host, fall back to the canonical production Works site.
		if ( '' === $host ) {
			return 'https://works.hcommons.org';
		}

		// Drop a leading "www." — it is not part of the base domain.
		$host = preg_replace( '/^www\./', '', $host );

		// Already a "works." host: keep it as a single works. host (idempotent).
		if ( 0 === strpos( $host, 'works.' ) ) {
			return 'https://' . $host;
		}

		// The Works site is shared across the whole HCommons network, so any
		// network subdomain (e.g. stemedplus.hcommons.org) must resolve to the
		// network's base Works site, not a works.<subdomain> host. Reduce the
		// host to the HCommons base domain (hcommons[-env].org) when it ends in
		// one. Non-HCommons hosts (e.g. local *.lndo.site) are left untouched.
		if ( preg_match( '/(?:^|\.)(hcommons(?:-[a-z0-9-]+)?\.org)$/', $host, $matches ) ) {
			return 'https://works.' . $matches[1];
		}

		return 'https://works.' . $host;
	}
}

if ( ! function_exists( 'hcommons_rewrite_works_links' ) ) {
	/**
	 * Rewrite canonical Works links in a block of HTML to an environment URL.
	 *
	 * Replaces occurrences of the canonical production Works URL
	 * (https://works.hcommons.org, and its http:// form) with the supplied
	 * environment-specific Works URL. When the environment URL is already the
	 * canonical production URL the content is returned untouched.
	 *
	 * @param string $content   HTML that may contain Works links.
	 * @param string $works_url Environment-appropriate Works URL to link to.
	 * @return string HTML with Works links pointed at $works_url.
	 */
	function hcommons_rewrite_works_links( $content, $works_url ) {
		// Cheap guard: skip blocks that do not reference the Works site at all.
		if ( false === strpos( $content, 'works.hcommons.org' ) ) {
			return $content;
		}

		// On production the canonical URL is already correct; nothing to do.
		if ( 'https://works.hcommons.org' === $works_url ) {
			return $content;
		}

		return str_replace(
			array( 'https://works.hcommons.org', 'http://works.hcommons.org' ),
			$works_url,
			$content
		);
	}
}
