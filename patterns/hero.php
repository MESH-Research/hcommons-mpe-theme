<?php
/**
 * Title: Hero Section
 * Slug: hcommons/hero
 * Categories: hcommons
 * Keywords: hero, banner, header
 * Block Types: core/group
 */
?>
<!-- wp:group {"tagName":"section","className":"hero pt-100 pb-80","lock":{"move":true,"remove":true},"layout":{"type":"default"}} -->
<section class="wp-block-group hero pt-100 pb-80">

	<!-- wp:group {"className":"hero-bg","lock":{"move":true,"remove":true},"layout":{"type":"default"}} -->
	<div class="wp-block-group hero-bg">
		<!-- wp:image {"className":"hero-bg-img","sizeSlug":"full","lock":{"move":true,"remove":true}} -->
		<figure class="wp-block-image size-full hero-bg-img"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/hero-bg.png" alt=""/></figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"hero-container px-40","lock":{"move":true,"remove":true},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group hero-container px-40">

		<!-- wp:group {"className":"hero-row","lock":{"move":true,"remove":true},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
		<div class="wp-block-group hero-row">

			<!-- wp:group {"className":"hero-content","lock":{"move":true,"remove":true},"style":{"layout":{"selfStretch":"fixed","flexSize":"66%"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group hero-content">

				<!-- wp:group {"className":"hero-box p-60 rounded","lock":{"move":true,"remove":true},"backgroundColor":"white","layout":{"type":"default"}} -->
				<div class="wp-block-group hero-box p-60 rounded has-white-background-color has-background">

					<!-- wp:group {"className":"hero-badge py-20 px-40 rounded-pill","lock":{"move":true,"remove":true},"backgroundColor":"green-faint","layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group hero-badge py-20 px-40 rounded-pill has-green-faint-background-color has-background">
						<!-- wp:html {"lock":{"move":true,"remove":true}} -->
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="2" y1="12" x2="22" y2="12"></line>
							<path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
						</svg>
						<!-- /wp:html -->
						<!-- wp:paragraph {"lock":{"move":true,"remove":false},"className":"text-base font-medium","textColor":"teal"} -->
						<p class="has-teal-color has-text-color text-base font-medium">Open access, open source, open to all</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->

					<!-- wp:heading {"level":1,"className":"hero-title leading-tight","lock":{"move":true,"remove":true}} -->
					<h1 class="wp-block-heading hero-title leading-tight"><span class="hero-line"><span class="text-teal">Resist</span> <span class="text-gold">enclosure.</span></span><span class="hero-line bold"><span class="text-teal">Join the</span> <span class="text-gold">Commons.</span></span></h1>
					<!-- /wp:heading -->

				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"hero-description mt-50 py-40 px-50 rounded-sm","lock":{"move":true,"remove":true},"backgroundColor":"white","layout":{"type":"default"}} -->
				<div class="wp-block-group hero-description mt-50 py-40 px-50 rounded-sm has-white-background-color has-background">

					<!-- wp:paragraph {"lock":{"move":true,"remove":false},"className":"text-lg leading-relaxed","textColor":"black"} -->
					<p class="has-black-color has-text-color text-lg leading-relaxed">Knowledge Commons hosts a growing coalition of open knowledge creators across the disciplines and around the world. Discover, connect, and share in a community that believes knowledge should be free.</p>
					<!-- /wp:paragraph -->

					<!-- wp:buttons {"className":"hero-buttons mt-60","lock":{"move":true,"remove":true}} -->
					<div class="wp-block-buttons hero-buttons mt-60">
						<!-- wp:button {"className":"btn-primary","lock":{"move":true,"remove":false}} -->
						<div class="wp-block-button btn-primary"><a class="wp-block-button__link wp-element-button" href="https://hcommons.org/membership/">Create an Account</a></div>
						<!-- /wp:button -->

						<!-- wp:button {"className":"btn-primary","lock":{"move":true,"remove":false}} -->
						<div class="wp-block-button btn-primary"><a class="wp-block-button__link wp-element-button" href="https://works.hcommons.org">Explore Works</a></div>
						<!-- /wp:button -->

						<!-- wp:button {"className":"btn-primary","lock":{"move":true,"remove":false}} -->
                        <div class="wp-block-button btn-primary"><a class="wp-block-button__link wp-element-button" href="https://about.hcommons.org/kc-champions/">Become a Champion</a></div>
                        <!-- /wp:button -->
					</div>
					<!-- /wp:buttons -->

				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->

			<!-- wp:group {"className":"fundraising-box p-60 rounded border-left-gold","lock":{"move":true,"remove":true},"style":{"layout":{"selfStretch":"fixed","flexSize":"320px"}},"backgroundColor":"white","layout":{"type":"default"}} -->
			<div class="wp-block-group fundraising-box p-60 rounded border-left-gold has-white-background-color has-background">

				<!-- wp:heading {"level":2,"className":"fundraising-title text-2xl","lock":{"move":true,"remove":false},"textColor":"teal"} -->
				<h2 class="wp-block-heading fundraising-title text-2xl has-teal-color has-text-color">Support the Commons</h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"className":"fundraising-text text-base leading-normal mt-30 mb-50","lock":{"move":true,"remove":false},"textColor":"teal-light"} -->
				<p class="has-black-color has-text-color text-lg leading-relaxed">Help us keep scholarly resources free and accessible to everyone. Your contribution sustains a community-owned platform that resists commercialization of academic work.</p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons {"lock":{"move":true,"remove":true}} -->
				<div class="wp-block-buttons">
					<!-- wp:button {"className":"btn-donate bg-gold-gradient","lock":{"move":true,"remove":false}} -->
					<div class="wp-block-button btn-donate"><a class="wp-block-button__link has-background wp-element-button bg-gold-gradient" href="https://givingto.msu.edu/causes-to-support/crowdpower/knowledge-commons">Contribute Now</a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</section>
<!-- /wp:group -->
