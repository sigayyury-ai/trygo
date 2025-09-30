<?php
/**
 * Feature archive template.
 */

global $wp_query;

get_header();
?>

<header class="site-header">
  <p class="eyebrow"><?php esc_html_e( 'TRYGO feature library', 'trygo' ); ?></p>
  <h1><?php esc_html_e( 'Explore everything the co-pilot can do', 'trygo' ); ?></h1>
  <p class="intro">
    <?php esc_html_e( 'Browse AI-powered modules by capability and discover how they accelerate your go-to-market workflow.', 'trygo' ); ?>
  </p>
</header>

<main>
  <?php
    $feature_terms = get_terms(
      [
        'taxonomy'   => 'feature_type',
        'hide_empty' => true,
      ]
    );
  ?>

  <?php if ( ! empty( $feature_terms ) && ! is_wp_error( $feature_terms ) ) : ?>
    <nav class="feature-archive__filters" aria-label="<?php esc_attr_e( 'Feature categories', 'trygo' ); ?>">
      <a class="feature-filter" href="<?php echo esc_url( get_post_type_archive_link( 'feature' ) ); ?>">
        <?php esc_html_e( 'All', 'trygo' ); ?>
      </a>
      <?php foreach ( $feature_terms as $term ) : ?>
        <a class="feature-filter" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
          <?php echo esc_html( $term->name ); ?>
        </a>
      <?php endforeach; ?>
    </nav>
  <?php endif; ?>

  <section class="category-grid">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <a class="category-card" href="<?php the_permalink(); ?>">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ] ); ?>
          <?php else : ?>
            <img src="https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
          <?php endif; ?>
          <div class="category-card__body">
            <h3><?php the_title(); ?></h3>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
          </div>
        </a>
      <?php endwhile; ?>
    <?php else : ?>
      <p><?php esc_html_e( 'No feature records yet. Create your first feature to populate this page.', 'trygo' ); ?></p>
    <?php endif; ?>
  </section>

  <?php if ( get_next_posts_link() ) : ?>
    <div class="feed-load-more feature-archive__pagination">
      <?php next_posts_link( __( 'Load more features', 'trygo' ) ); ?>
    </div>
  <?php endif; ?>
</main>

<?php
get_footer();
