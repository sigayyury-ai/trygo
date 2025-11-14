<?php
/**
 * Template Name: Bloge page
 */

get_header();
?>

<main>
  <div class="blog-header">
    <h1>Blog</h1>
    <p>Practical articles and resources on marketing, business, and sales.</p>
  </div>
      <section class="featured" aria-label="Editor picks">
        <div class="section-header">
          <h2>Latest highlights</h2>
          <p>Two cornerstone pieces on go-to-market strategy to read first.</p>
        </div>
        <div class="featured-grid" id="featured-list">
          <?php
            $features_query = new WP_Query(
              [
                'post_type'      => 'blog',
                'posts_per_page' => 2,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
              ]
            );
          ?>

          <?php if ( $features_query->have_posts() ) : ?>
            <?php while ( $features_query->have_posts() ) : $features_query->the_post(); ?>
              <?php
                $image_id  = get_post_thumbnail_id();
                $image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80';
                $image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';

                if ( ! $image_alt ) {
                    $image_alt = get_the_title();
                }
              ?>
              <?php
                // short_description может быть добавлено через ACF, поэтому пробуем сначала get_field().
                $short_description = function_exists( 'get_field' ) ? get_field( 'short_description', get_the_ID() ) : '';

                if ( ! $short_description ) {
                    $short_description = get_post_meta( get_the_ID(), 'short_description', true );
                }

                $card_text = $short_description ? wp_strip_all_tags( $short_description ) : wp_trim_words( get_the_excerpt(), 26 );
              ?>
              <article class="featured-card" data-placeholder>
              <a class="featured-link" href="<?php the_permalink(); ?>">
                <div class="featured-media">
                  <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" />
                </div>
                  <div class="featured-body">
                    <div class="meta">
                      <span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
                      <span></span>
                    </div>
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo esc_html( $card_text ); ?></p>
              </div>
              </a>
            </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <div class="category-card empty-card">
              <div class="category-card__body">
                <h3><?php esc_html_e( 'Content coming soon', 'trygo' ); ?></h3>
                <p><?php esc_html_e( 'Add onboarding features in the WordPress admin to populate this section.', 'trygo' ); ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <section class="feed" aria-label="All posts">
        <div class="section-header">
          <h2>All posts</h2>
          <p>The feed updates automatically — keep scrolling to explore more stories.</p>
        </div>
        <script>
document.addEventListener('DOMContentLoaded', function () {
  const filtersEl = document.getElementById('feed-filters');
  const gridEl    = document.getElementById('feed-grid');
  if (!filtersEl || !gridEl) return;

  const buttons = Array.from(filtersEl.querySelectorAll('.feed-filter'));
  const cards   = Array.from(gridEl.querySelectorAll('.feed-card'));

  const slugify = s => String(s).toLowerCase().trim()
    .replace(/[^\w\s-]/g, '')   // убрать спецсимволы
    .replace(/\s+/g, '-');      // пробелы -> дефис

  // Получаем слаг из кнопки (data-category или текст)
  const getBtnSlug = btn =>
    btn.dataset.category ? slugify(btn.dataset.category) : slugify(btn.textContent);

  function applyFilter(slug) {
    const showAll = slug === 'all';

    cards.forEach(card => {
      // Важно: читаем именно атрибут, а не dataset.tags (потому что data-tags => dataset.tags ОК,
      // но здесь избежим любых несоответствий)
      const tagStr = card.getAttribute('data-tags') || '';
      const tags   = tagStr.split(/\s+/).map(slugify).filter(Boolean);

      const match = showAll || tags.includes(slug);
      card.hidden = !match;                // для a11y
      card.style.display = match ? '' : 'none';
    });

    // Обновим URL (?tag=marketing) — можно закомментировать
    const params = new URLSearchParams(location.search);
    if (showAll) params.delete('tag'); else params.set('tag', slug);
    history.replaceState({}, '', location.pathname + (params.toString() ? '?' + params.toString() : ''));
  }

  // Навешиваем клики
  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const slug = getBtnSlug(btn);

      buttons.forEach(b => { b.classList.remove('is-active'); b.setAttribute('aria-pressed','false'); });
      btn.classList.add('is-active');
      btn.setAttribute('aria-pressed','true');

      applyFilter(slug);
    });
  });

  // Инициализация: ?tag=... или активная кнопка, или первая
  const urlSlug = slugify(new URLSearchParams(location.search).get('tag') || '');
  const initialBtn =
      buttons.find(b => getBtnSlug(b) === urlSlug) ||
      filtersEl.querySelector('.feed-filter.is-active') ||
      buttons[0];

  if (initialBtn) {
    initialBtn.classList.add('is-active');
    initialBtn.setAttribute('aria-pressed','true');
    applyFilter(getBtnSlug(initialBtn));
  }
});
</script>

        <div class="feed-filters" id="feed-filters" aria-label="Post categories">
          <button class="feed-filter is-active" type="button" data-category="All" aria-pressed="true">All</button>
          <button class="feed-filter" type="button" data-category="Launch" aria-pressed="false">Launch</button>
          <button class="feed-filter" type="button" data-category="Marketing" aria-pressed="false">Marketing</button>
          <button class="feed-filter" type="button" data-category="Business" aria-pressed="false">Business</button>
          <button class="feed-filter" type="button" data-category="Sales" aria-pressed="false">Sales</button>
        </div>
        <div class="feed-grid" id="feed-grid">
  <?php
    // теги, которые допускаем (из кнопок)
    $allowed_tag_slugs = array_map('sanitize_title', ['Launch','Marketing','Business','Sales']);

    // ЕСЛИ сверху у тебя есть блок с 2 последними — передай сюда их ID:
    // $exclude_ids = [123, 456];

    $features_query = new WP_Query([
      'post_type'           => 'blog',
      'posts_per_page'      => -1,
      // 'post__not_in'      => $exclude_ids ?? [], // раскомментируй при исключении первых 2
      'post_status'         => 'publish',
      'orderby'             => 'date',
      'order'               => 'DESC',
      'ignore_sticky_posts' => true,
      'no_found_rows'       => true,
      'tax_query'           => [[
        'taxonomy' => 'post_tag',      // стандартные теги WP
        'field'    => 'slug',
        'terms'    => $allowed_tag_slugs,
        'operator' => 'IN',
      ]],
    ]);
  ?>

  <?php if ( $features_query->have_posts() ) : ?>
    <?php while ( $features_query->have_posts() ) : $features_query->the_post(); ?>
      <?php
        $image_id  = get_post_thumbnail_id();
        $image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80';
        $image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : get_the_title();

        // короткое описание
        $short_description = function_exists('get_field') ? get_field('short_description', get_the_ID()) : '';
        if (!$short_description) $short_description = get_post_meta(get_the_ID(), 'short_description', true);
        $card_text = $short_description ? wp_strip_all_tags($short_description) : wp_trim_words(get_the_excerpt(), 16);

        // пригодится для фронтенд-фильтров
        $tags = get_the_terms(get_the_ID(), 'post_tag');
        $tag_slugs = $tags ? wp_list_pluck($tags, 'slug') : [];
      ?>
      <article class="feed-card" data-tags="<?php echo esc_attr(implode(' ', $tag_slugs)); ?>">
        <a class="feed-link" href="<?php the_permalink(); ?>">
          <img src="<?php echo esc_url($image_src); ?>" alt="<?php echo esc_attr($image_alt); ?>" loading="lazy" />
          <div class="feed-body">
            <div class="meta">
              <span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
            </div>
            <h3><?php the_title(); ?></h3>
            <p><?php echo esc_html($card_text); ?></p>
          </div>
        </a>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  <?php else : ?>
    <p>No posts found.</p>
  <?php endif; ?>
</div>

        <div class="feed-loader" id="feed-loader" role="status" aria-live="polite" data-state="idle">
          <span class="loader-dot"></span>
          <span>Scroll down to load more</span>
        </div>
        <div id="feed-sentinel" aria-hidden="true"></div>
      </section>

      <?php get_template_part('cta-section'); ?>
</main>
 
          
        

<?php
get_footer();
