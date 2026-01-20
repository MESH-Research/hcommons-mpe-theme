<?php
/**
 * The header template for PHP-based templates.
 *
 * Renders the header block template part for compatibility with
 * PHP templates in this block theme.
 *
 * @package HCommons
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="wp-site-blocks">
<?php
// Render header template part and process any shortcodes within it
ob_start();
block_template_part( 'header' );
echo do_shortcode( ob_get_clean() );
?>
