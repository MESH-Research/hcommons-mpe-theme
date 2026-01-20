<?php
/**
 * Title: CTA Section
 * Slug: hcommons/cta
 * Categories: hcommons
 * Keywords: cta, call to action, newsletter
 * Block Types: core/group
 */
?>
<!-- wp:group {"tagName":"section","className":"cta py-80","lock":{"move":true,"remove":true},"gradient":"teal-gradient","textColor":"white","layout":{"type":"constrained"}} -->
<section class="wp-block-group cta py-80 has-white-color has-teal-gradient-gradient-background has-text-color has-background">

	<!-- wp:group {"className":"cta-content mb-80","lock":{"move":true,"remove":true},"layout":{"type":"constrained","contentSize":"48rem"}} -->
	<div class="wp-block-group cta-content mb-80">

		<!-- wp:heading {"textAlign":"center","className":"cta-title text-4xl mb-40","lock":{"move":true,"remove":false},"textColor":"white"} -->
		<h2 class="wp-block-heading has-text-align-center cta-title text-4xl mb-40 has-white-color has-text-color">Ready to Join the Commons?</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"cta-description text-lg leading-relaxed mb-60","lock":{"move":true,"remove":false},"textColor":"white"} -->
		<p class="has-text-align-center cta-description text-lg leading-relaxed mb-60 has-white-color has-text-color">Create your free account today and become part of a global community dedicated to open knowledge and collaborative scholarship.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"lock":{"move":true,"remove":true},"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"btn-white","lock":{"move":true,"remove":false},"backgroundColor":"white","textColor":"teal"} -->
			<div class="wp-block-button btn-white"><a class="wp-block-button__link has-teal-color has-white-background-color has-text-color has-background wp-element-button" href="/membership/">Create an Account</a></div>
			<!-- /wp:button -->

			<!-- wp:button {"className":"btn-outline-white border-2","lock":{"move":true,"remove":false},"borderColor":"white"} -->
			<div class="wp-block-button btn-outline-white"><a class="wp-block-button__link has-border-color has-white-border-color wp-element-button border-2" href="https://sustaining.hcommons.org">Learn More</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->

	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"newsletter pt-70 border-top-white-20","lock":{"move":true,"remove":true},"layout":{"type":"constrained","contentSize":"32rem"}} -->
	<div class="wp-block-group newsletter pt-70 border-top-white-20">

		<!-- wp:heading {"textAlign":"center","level":3,"className":"newsletter-title text-xl mb-20","lock":{"move":true,"remove":false},"textColor":"white"} -->
		<h3 class="wp-block-heading has-text-align-center newsletter-title text-xl mb-20 has-white-color has-text-color">Subscribe to Our Newsletter</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":"newsletter-description text-base mb-50","lock":{"move":true,"remove":false},"textColor":"white"} -->
		<p class="has-text-align-center newsletter-description text-base mb-50 has-white-color has-text-color">Stay updated with the latest news, features, and community highlights.</p>
		<!-- /wp:paragraph -->

		<!-- wp:html {"lock":{"move":true,"remove":true}} -->
		<form action="https://hcommons.us9.list-manage.com/subscribe/post?u=db7b80b27372be53e3cc37dfb&amp;id=ab124b16b0&amp;f_id=003611e1f0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate newsletter-form" target="_self" novalidate="">
			<input class="newsletter-input" type="email" name="EMAIL" id="mce-EMAIL" required="" value="" placeholder="Enter your email address" />
			<input type="submit" name="subscribe" id="mc-embedded-subscribe" class="btn-gold" value="Subscribe" />
			<div hidden=""><input type="hidden" name="tags" value="12758209"></div>
			<div id="mce-responses" class="clear">
				<div class="response" id="mce-error-response" class="hidden"></div>
				<div class="response" id="mce-success-response" class="hidden"></div>
			</div>
		</form>
		<!-- /wp:html -->

	</div>
	<!-- /wp:group -->

</section>
<!-- /wp:group -->
