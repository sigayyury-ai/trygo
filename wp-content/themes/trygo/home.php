<?php
/**
 * Blog index template.
 */

global $wp_query;

get_header();

$filters = [
    [ 'label' => __( 'All', 'trygo' ), 'slug' => 'all' ],
    [ 'label' => __( 'Launch', 'trygo' ), 'slug' => 'launch' ],
    [ 'label' => __( 'Marketing', 'trygo' ), 'slug' => 'marketing' ],
    [ 'label' => __( 'Business', 'trygo' ), 'slug' => 'business' ],
    [ 'label' => __( 'Sales', 'trygo' ), 'slug' => 'sales' ],
];

$is_first_page = ! is_paged();
$featured_posts = null;
$featured_ids   = [];

if ( $is_first_page ) {
    $featured_posts = new WP_Query(
        [
            'posts_per_page'      => 2,
            'post_type'           => 'post',
            'ignore_sticky_posts' => true,
        ]
    );

    $featured_ids = wp_list_pluck( (array) $featured_posts->posts, 'ID' );
}

$paged = max( 1, absint( get_query_var( 'paged' ) ), absint( get_query_var( 'page' ) ) );

$feed_posts = new WP_Query(
    [
        'post_type'      => 'post',
        'posts_per_page' => 9,
        'post__not_in'   => $featured_ids,
        'paged'          => $paged,
        'ignore_sticky_posts' => true,
    ]
);
?>

<header class="site-header">
  <p class="eyebrow"><?php esc_html_e( 'TRYGO Blog — your marketing co-pilot', 'trygo' ); ?></p>
  <h1><?php esc_html_e( 'Marketing insights from practitioners to accelerate launches', 'trygo' ); ?></h1>
  <p class="intro">
    <?php esc_html_e( 'Discover frameworks, playbooks, and experiments from product marketers so you can validate ideas and ship traction faster.', 'trygo' ); ?>
  </p>
</header>

<main>
  <?php if ( $is_first_page && $featured_posts ) : ?>
    <section class="featured" aria-label="<?php esc_attr_e( 'Editor picks', 'trygo' ); ?>">
      <div class="section-header">
        <h2><?php esc_html_e( 'Latest highlights', 'trygo' ); ?></h2>
        <p><?php esc_html_e( 'Two cornerstone pieces on go-to-market strategy to read first.', 'trygo' ); ?></p>
      </div>
      <div class="featured-grid" id="featured-list">
        <?php if ( $featured_posts->have_posts() ) : ?>
          <?php while ( $featured_posts->have_posts() ) : $featured_posts->the_post(); ?>
          <article class="featured-card">
            <a class="featured-link" href="<?php the_permalink(); ?>">
              <div class="featured-media">
                <?php if ( has_post_thumbnail() ) : ?>
                  <?php the_post_thumbnail( 'large', [ 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ] ); ?>
                <?php else : ?>
                  <img src="https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1200&q=80" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
                <?php endif; ?>
              </div>
              <div class="featured-body">
                <div class="meta">
                  <span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
                  <span><?php echo esc_html( trygo_estimated_reading_time( get_the_ID() ) ); ?></span>
                </div>
                <h3><?php the_title(); ?></h3>
                <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
                <span class="featured-cta"><?php esc_html_e( 'Read more', 'trygo' ); ?></span>
              </div>
            </a>
          </article>
          <?php endwhile; ?>
        <?php else : ?>
          <p><?php esc_html_e( 'Create posts to populate the highlights section.', 'trygo' ); ?></p>
        <?php endif; ?>
      </div>
    </section>
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

  <section class="feed" aria-label="<?php esc_attr_e( 'All posts', 'trygo' ); ?>">
    <div class="section-header">
      <h2><?php esc_html_e( 'All posts', 'trygo' ); ?></h2>
      <p><?php esc_html_e( 'The feed updates automatically — keep scrolling to explore more stories.', 'trygo' ); ?></p>
    </div>
    <div class="feed-filters" id="feed-filters" aria-label="<?php esc_attr_e( 'Post categories', 'trygo' ); ?>">
      <?php foreach ( $filters as $index => $filter ) : ?>
        <button
          class="feed-filter<?php echo 0 === $index ? ' is-active' : ''; ?>"
          type="button"
          data-category="<?php echo esc_attr( $filter['slug'] ); ?>"
          aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>"
        >
          <?php echo esc_html( $filter['label'] ); ?>
        </button>
      <?php endforeach; ?>
    </div>
    <div class="feed-grid" id="feed-grid">
      <?php if ( $feed_posts->have_posts() ) : ?>
        <?php while ( $feed_posts->have_posts() ) : $feed_posts->the_post(); ?>
          <?php
            $post_categories = get_the_category();
            $category_slugs  = wp_list_pluck( $post_categories, 'slug' );
            $category_labels = wp_list_pluck( $post_categories, 'name' );
          ?>
          <article class="feed-card" data-categories="<?php echo esc_attr( implode( ',', $category_slugs ) ); ?>">
            <a class="feed-link" href="<?php the_permalink(); ?>">
              <div class="feed-media">
                <?php if ( has_post_thumbnail() ) : ?>
                  <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ] ); ?>
                <?php else : ?>
                  <img src="https://images.unsplash.com/photo-1523475472560-d2df97ec485c?auto=format&fit=crop&w=1200&q=80" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
                <?php endif; ?>
              </div>
              <div class="feed-body">
                <div class="meta">
                  <span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
                  <span><?php echo esc_html( trygo_estimated_reading_time( get_the_ID() ) ); ?></span>
                </div>
                <h3><?php the_title(); ?></h3>
                <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
                <?php if ( ! empty( $category_labels ) ) : ?>
                  <div class="tags" aria-label="<?php esc_attr_e( 'Article tags', 'trygo' ); ?>">
                    <?php foreach ( $category_labels as $label ) : ?>
                      <span class="tag"><?php echo esc_html( $label ); ?></span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            </a>
          </article>
        <?php endwhile; ?>
      <?php else : ?>
        <p><?php esc_html_e( 'No posts yet — publish your first story to see it here.', 'trygo' ); ?></p>
      <?php endif; ?>
    </div>
    <?php if ( $feed_posts->max_num_pages > 1 ) : ?>
      <div class="feed-load-more" data-feed-pagination>
        <?php
          $trygo_original_query = $wp_query;
          $wp_query             = $feed_posts; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
          next_posts_link( __( 'Scroll down to load more', 'trygo' ) );
          $wp_query = $trygo_original_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        ?>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php
wp_reset_postdata();

get_footer();
