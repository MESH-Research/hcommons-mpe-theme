<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package HCommons
 */

get_header(); ?>

<main class="search-results-page pt-header pb-80">
	<div class="search-results-container">

		<?php if ( have_posts() ) : ?>

			<header class="search-results-header">
				<h1 class="search-results-title">
					<?php
					printf(
						/* translators: %s: search query */
						esc_html__( 'Search Results for: %s', 'flavor' ),
						'<span class="search-query">' . esc_html( get_search_query() ) . '</span>'
					);
					?>
				</h1>
				<p class="search-results-count">
					<?php
					global $wp_query;
					printf(
						/* translators: %d: number of results */
						_n( '%d result found', '%d results found', $wp_query->found_posts, 'flavor' ),
						$wp_query->found_posts
					);
					?>
				</p>
			</header>

			<!-- Search again form -->
			<div class="search-again-form">
				<form role="search" method="get" action="<?php echo esc_url( home_url( '/search/' ) ); ?>">
					<div class="search-form-wrapper">
						<svg class="search-form-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="11" cy="11" r="8"></circle>
							<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
						</svg>
						<input type="search" name="q" class="search-form-input" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search...', 'flavor' ); ?>" />
						<button type="submit" class="search-form-submit"><?php esc_html_e( 'Search', 'flavor' ); ?></button>
					</div>
				</form>
			</div>

			<div class="search-results-list">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result-item' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="search-result-thumbnail">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'medium' ); ?>
								</a>
							</div>
						<?php endif; ?>

						<div class="search-result-content">
							<div class="search-result-meta">
								<span class="search-result-type"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
								<span class="search-result-date"><?php echo get_the_date(); ?></span>
							</div>

							<h2 class="search-result-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<?php if ( has_excerpt() || get_the_content() ) : ?>
								<div class="search-result-excerpt">
									<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>

							<a href="<?php the_permalink(); ?>" class="search-result-link">
								<?php esc_html_e( 'View', 'flavor' ); ?> →
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<nav class="search-pagination">
				<?php
				the_posts_pagination( array(
					'mid_size'  => 2,
					'prev_text' => '← ' . __( 'Previous', 'flavor' ),
					'next_text' => __( 'Next', 'flavor' ) . ' →',
				) );
				?>
			</nav>

		<?php else : ?>

			<header class="search-results-header">
				<h1 class="search-results-title">
					<?php
					printf(
						/* translators: %s: search query */
						esc_html__( 'Search Results for: %s', 'flavor' ),
						'<span class="search-query">' . esc_html( get_search_query() ) . '</span>'
					);
					?>
				</h1>
			</header>

			<div class="search-no-results">
				<div class="search-no-results-icon">
					<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
						<line x1="8" y1="8" x2="14" y2="14"></line>
						<line x1="14" y1="8" x2="8" y2="14"></line>
					</svg>
				</div>
				<h2 class="search-no-results-title"><?php esc_html_e( 'No Results Found', 'flavor' ); ?></h2>
				<p class="search-no-results-message">
					<?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'flavor' ); ?>
				</p>

				<!-- Search again form -->
				<div class="search-again-form">
					<form role="search" method="get" action="<?php echo esc_url( home_url( '/search/' ) ); ?>">
						<div class="search-form-wrapper">
							<svg class="search-form-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="11" cy="11" r="8"></circle>
								<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
							</svg>
							<input type="search" name="q" class="search-form-input" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Try a different search...', 'flavor' ); ?>" />
							<button type="submit" class="search-form-submit"><?php esc_html_e( 'Search', 'flavor' ); ?></button>
						</div>
					</form>
				</div>

				<div class="search-suggestions">
					<h3><?php esc_html_e( 'Suggestions:', 'flavor' ); ?></h3>
					<ul>
						<li><?php esc_html_e( 'Make sure all words are spelled correctly', 'flavor' ); ?></li>
						<li><?php esc_html_e( 'Try different keywords', 'flavor' ); ?></li>
						<li><?php esc_html_e( 'Try more general keywords', 'flavor' ); ?></li>
					</ul>
				</div>
			</div>

		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
