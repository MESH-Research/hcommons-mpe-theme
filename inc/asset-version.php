<?php
/**
 * Cache-busting version strings for the theme's own assets.
 *
 * @package HCommons
 */

/**
 * Version string for an enqueued theme asset.
 *
 * The theme version only changes on a release, so a stylesheet fix shipped
 * between releases would be requested under the same `?ver=` as the file it
 * replaced and browsers would keep the stale copy. Keying the version to the
 * file's modification time makes every deployed change a new URL, while an
 * unchanged file keeps its URL and stays cached.
 *
 * @param string $file     Absolute path to the asset on disk.
 * @param string $fallback Version to use when the file cannot be read.
 * @return string
 */
function hcommons_asset_version( $file, $fallback ) {
	$mtime = is_readable( $file ) ? filemtime( $file ) : false;
	if ( false === $mtime ) {
		return (string) $fallback;
	}
	return (string) $mtime;
}
