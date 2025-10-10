<?php
/**
 * Theme fallback template.
 */

global $wp_query;

get_header();
?>

<main class="wp-default-content" id="primary">
  <div class="default-wrapper">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : ?>
        <?php the_post(); ?>
        <article <?php post_class(); ?>>
          <header class="entry-header">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
          </header>
          <div class="entry-content">
            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
      <article class="no-results">
        <h1><?php esc_html_e( 'Nothing found', 'trygo' ); ?></h1>
        <p><?php esc_html_e( 'Create content in WordPress admin to see it here.', 'trygo' ); ?></p>
      </article>
    <?php endif; ?>
  </div>
</main>

<?php
get_footer();
