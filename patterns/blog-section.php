<?php
/**
 * Title: Blog Section
 * Slug: hcommons/blog-section
 * Categories: hcommons
 * Keywords: blog, posts, news
 * Block Types: core/group
 */
?>
<!-- wp:group {"tagName":"section","className":"blog-section py-80","lock":{"move":true,"remove":true},"backgroundColor":"cream","layout":{"type":"constrained"}} -->
<section class="wp-block-group blog-section py-80 has-cream-background-color has-background">

	<!-- wp:separator {"className":"section-divider mb-80","lock":{"move":true,"remove":true}} -->
	<hr class="wp-block-separator has-alpha-channel-opacity section-divider mb-80"/>
	<!-- /wp:separator -->

	<!-- wp:group {"className":"section-header-row","lock":{"move":true,"remove":true},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group section-header-row">
		<!-- wp:group {"lock":{"move":true,"remove":true},"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"className":"section-label gold text-base font-medium uppercase tracking-wide","lock":{"move":true,"remove":false},"textColor":"gold"} -->
			<p class="section-label gold text-base font-medium uppercase tracking-wide has-gold-color has-text-color">From the Blog</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"className":"section-title text-3xl mt-20","lock":{"move":true,"remove":false},"textColor":"teal"} -->
			<h2 class="wp-block-heading section-title text-3xl mt-20 has-teal-color has-text-color">Latest Updates</h2>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->

		<!-- wp:buttons {"lock":{"move":true,"remove":true}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"btn-outline-sm blog-section-view-all","lock":{"move":true,"remove":false}} -->
			<div class="wp-block-button btn-outline-sm blog-section-view-all"><a class="wp-block-button__link wp-element-button" href="/news/">View All Posts</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"blog-posts-placeholder","lock":{"move":true,"remove":true},"layout":{"type":"default"}} -->
	<div class="wp-block-group blog-posts-placeholder"></div>
	<!-- /wp:group -->

</section>
<!-- /wp:group -->
