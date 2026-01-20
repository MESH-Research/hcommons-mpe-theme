<?php
/**
 * Title: Community Section
 * Slug: hcommons/community
 * Categories: hcommons
 * Keywords: community, fediverse, mastodon
 * Block Types: core/group
 */
?>
<!-- wp:group {"tagName":"section","className":"community py-80","lock":{"move":true,"remove":true},"gradient":"community-gradient","layout":{"type":"constrained"}} -->
<section class="wp-block-group community py-80 has-community-gradient-gradient-background has-background">

	<!-- wp:group {"className":"community-grid","lock":{"move":true,"remove":true},"layout":{"type":"grid","columnCount":2,"minimumColumnWidth":null}} -->
	<div class="wp-block-group community-grid">

		<!-- wp:group {"className":"community-content","lock":{"move":true,"remove":true},"layout":{"type":"default"}} -->
		<div class="wp-block-group community-content">

			<!-- wp:paragraph {"className":"community-badge text-base font-semibold py-20 px-40 mb-40 rounded-pill","lock":{"move":true,"remove":false},"backgroundColor":"gold-faint","textColor":"gold"} -->
			<p class="community-badge text-base font-semibold py-20 px-40 mb-40 rounded-pill has-green-faint-background-color has-text-color has-background">Join the Fediverse</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"className":"community-title text-4xl mb-40","lock":{"move":true,"remove":false},"textColor":"teal"} -->
			<h2 class="wp-block-heading community-title text-4xl mb-40 has-teal-color has-text-color">Looking for your place in the Fediverse?</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"community-description text-lg leading-relaxed mb-60","lock":{"move":true,"remove":false},"textColor":"teal-light"} -->
			<p class="community-description text-lg leading-relaxed mb-60 has-teal-light-color has-text-color">Connect with the Knowledge Commons community on Mastodon. Share ideas, discuss research, and be part of a decentralized social network that values openness and academic freedom.</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"lock":{"move":true,"remove":true}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"btn-primary","lock":{"move":true,"remove":false}} -->
				<div class="wp-block-button btn-primary"><a class="wp-block-button__link wp-element-button" href="https://hcommons.social" target="_blank" rel="noopener">Check out hcommons.social</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"community-card p-70 rounded-lg","lock":{"move":true,"remove":true},"backgroundColor":"white","layout":{"type":"default"}} -->
		<div class="wp-block-group community-card p-70 rounded-lg has-white-background-color has-background">

			<!-- wp:group {"className":"mastodon-icon p-50 mb-50 rounded","lock":{"move":true,"remove":true},"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-group mastodon-icon p-50 mb-50 rounded">
				<!-- wp:html {"lock":{"move":true,"remove":true}} -->
				<svg width="48" height="48" viewBox="0 0 24 24" fill="white">
					<path d="M23.268 5.313c-.35-2.578-2.617-4.61-5.304-5.004C17.51.242 15.792 0 11.813 0h-.03c-3.98 0-4.835.242-5.288.309C3.882.692 1.496 2.518.917 5.127.64 6.412.61 7.837.661 9.143c.074 1.874.088 3.745.26 5.611.118 1.24.325 2.47.62 3.68.55 2.237 2.777 4.098 4.96 4.857 2.336.792 4.849.923 7.256.38.265-.061.527-.132.786-.213.585-.184 1.27-.39 1.774-.753a.057.057 0 0 0 .023-.043v-1.809a.052.052 0 0 0-.02-.041.053.053 0 0 0-.046-.01 20.282 20.282 0 0 1-4.709.545c-2.73 0-3.463-1.284-3.674-1.818a5.593 5.593 0 0 1-.319-1.433.053.053 0 0 1 .066-.054c1.517.363 3.072.546 4.632.546.376 0 .75 0 1.125-.01 1.57-.044 3.224-.124 4.768-.422.038-.008.077-.015.11-.024 2.435-.464 4.753-1.92 4.989-5.604.008-.145.03-1.52.03-1.67.002-.512.167-3.63-.024-5.545zm-3.748 9.195h-2.561V8.29c0-1.309-.55-1.976-1.67-1.976-1.23 0-1.846.79-1.846 2.35v3.403h-2.546V8.663c0-1.56-.617-2.35-1.848-2.35-1.112 0-1.668.668-1.668 1.977v6.218H4.822V8.102c0-1.31.337-2.35 1.011-3.12.696-.77 1.608-1.164 2.74-1.164 1.311 0 2.302.5 2.962 1.498l.638 1.06.638-1.06c.66-.999 1.65-1.498 2.96-1.498 1.13 0 2.043.395 2.74 1.164.675.77 1.012 1.81 1.012 3.12z"/>
				</svg>
				<!-- /wp:html -->
			</div>
			<!-- /wp:group -->

			<!-- wp:heading {"textAlign":"center","level":3,"className":"mastodon-title text-2xl mb-20","lock":{"move":true,"remove":false},"textColor":"teal"} -->
			<h3 class="wp-block-heading has-text-align-center mastodon-title text-2xl mb-20 has-teal-color has-text-color">hcommons.social</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","className":"mastodon-subtitle","lock":{"move":true,"remove":false},"textColor":"teal-light"} -->
			<p class="has-text-align-center mastodon-subtitle has-teal-light-color has-text-color">Your academic home in the Fediverse</p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</section>
<!-- /wp:group -->
