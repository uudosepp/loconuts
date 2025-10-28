

<?php
/**
 * Template Name: Home Page
 */
get_header();
$hero_bg = get_field('hero_desktop_background');
$hero_mobile_bg = get_field('hero_mobile_background');
$hero_logo = get_field('hero_logo');
$info_text = get_field('info_text');

?>
<!-- CSS -->
<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600&display=swap" rel="stylesheet">
<!-- Glacial Indifference font -->

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
<!-- JS -->
<script>const themeDirectoryUri = "<?php echo get_template_directory_uri(); ?>";</script>


<script src="<?php echo get_template_directory_uri(); ?>/js/contact-modal.js"></script>



<!----------------------------
   Section 1 SLOGAN (Fikseeritud hero)
----------------------------->
<style>
.hero-section::before {
    background-image: url('<?php echo esc_url($hero_bg['url']); ?>');
}

@media (max-width: 900px) {
    .hero-section::before {
        background-image: url('<?php echo esc_url($hero_mobile_bg['url']); ?>');
    }
}
</style>

<section class="hero-section">
    <img src="<?php echo esc_url($hero_logo['url']); ?>" alt="Logo keskel" class="logo">

    <div class="bottom-elements">
            <?php 
$link = get_field('cta_button');
if( $link ): 
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
?>
    <a href="<?php echo esc_url($link_url); ?>" class="cta-button" target="<?php echo esc_attr($link_target); ?>">
        <?php echo esc_html($link_title); ?>
    </a>
<?php endif; ?>
        <div class="chevron" aria-hidden="true">
            <svg viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 15L21 26L32 15" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>
</section>



<!----------------------------
   Section 2 MEIST
----------------------------->

<section id="meist" class="section meist">
  <div class="meist-container">
    <div class="meist-content">
      <h2 class="meist-title">
        <?php
        $title = get_field('meist_title');
        $parts = explode('|', $title);
        $is_bold = false;

        foreach ($parts as $part) {
          $safe_part = wp_kses_post($part);
          if ($is_bold) {
            echo '<strong>' . $safe_part . '</strong>';
          } else {
            echo $safe_part;
          }
          $is_bold = !$is_bold;
        }
        ?>
      </h2>

      <p class="meist-subtitle">
        <?php
        $subtitle = get_field('meist_subtitle');
        $parts = explode('|', $subtitle);
        $is_bold = false;

        foreach ($parts as $part) {
          $safe_part = wp_kses_post($part);
          if ($is_bold) {
            echo '<strong>' . $safe_part . '</strong>';
          } else {
            echo $safe_part;
          }
          $is_bold = !$is_bold;
        }
        ?>
      </p>
    </div>

    <div class="meist-images">
    <?php 
$link = get_field('cta_button');
if( $link ): 
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
?>
    <a href="<?php echo esc_url($link_url); ?>" class="cta-button" target="<?php echo esc_attr($link_target); ?>">
        <?php echo esc_html($link_title); ?>
    </a>
<?php endif; ?>


      <?php 
      $logo_image = get_field('meist_logo_image'); 
      if ($logo_image): ?>
        <img src="<?php echo esc_url($logo_image['url']); ?>" alt="<?php echo esc_attr($logo_image['alt']); ?>" />
      <?php endif; ?>
    </div>
  </div>

  <!-- Galerii alumine rida -->
  <div class="meist-gallery-banner">
    <?php
    // Võta kõik galerii postitused
    $args = array(
      'post_type' => 'gallery',
      'posts_per_page' => -1,
      'orderby' => 'date',
      'order' => 'DESC',
    );
    $gallery_query = new WP_Query($args);
    $all_gallery_ids = array();
    if ($gallery_query->have_posts()):
      while ($gallery_query->have_posts()): $gallery_query->the_post();
        $blocks = parse_blocks(get_the_content());
        $ids = loconuts_get_gallery_image_ids($blocks);
        $all_gallery_ids = array_merge($all_gallery_ids, $ids);
      endwhile;
      wp_reset_postdata();
    endif;
    $all_gallery_ids = array_unique($all_gallery_ids);
    ?>
    <div class="meist-gallery-grid">
      <?php foreach ($all_gallery_ids as $id): 
        $img_url = wp_get_attachment_image_url($id, 'thumbnail'); ?>
        <div class="meist-gallery-item">
          <img src="<?php echo esc_url($img_url); ?>" alt="" />
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>




<!----------------------------
   Section 3 — Galerii 
----------------------------->

<section id="galerii" class="main-gallery-section">
  <div class="container">
    <?php 
$title = get_field('gallery_title');
if ( $title ) : ?>
    <h2 class="main-gallery-title"><?php echo esc_html( $title ); ?></h2>
<?php endif; ?>


    <?php
    $args = array(
      'post_type' => 'gallery',
      'posts_per_page' => -1,
      'orderby' => 'date',
      'order' => 'DESC',
    );

    $gallery_query = new WP_Query($args);

    if ($gallery_query->have_posts()):
      while ($gallery_query->have_posts()): $gallery_query->the_post();
        $blocks = parse_blocks(get_the_content());
        $gallery_ids = loconuts_get_gallery_image_ids($blocks);

        $gallery_ids = array_unique($gallery_ids);
        $gallery_ids = array_slice($gallery_ids, 0, 20);

        if (!empty($gallery_ids)):
    ?>
    <div style="height: 900px; overflow: hidden; position: relative;">
      <div class="main-gallery-grid">
        <?php foreach ($gallery_ids as $id): 
          $img_url = wp_get_attachment_image_url($id, 'medium');
          $img_full = wp_get_attachment_image_url($id, 'full'); ?>
          <div class="main-gallery-item" data-full-url="<?php echo esc_url($img_full); ?>">
            <img src="<?php echo esc_url($img_url); ?>" alt="">
          </div>
        <?php endforeach; ?>
      </div>
    </div>



    <?php
        endif;
      endwhile;
      wp_reset_postdata();
    endif;
    ?>
    <?php 
$button = get_field('gallery_button'); // või 'option', kui globaalne
if ( $button ) :

    $button_url = $button['url'];
    $button_title = $button['title'];
    $button_target = $button['target'] ? $button['target'] : '_self';

    // Kui kasutad Polylangi ja URL on inglise keeles, tõmba õige keele versioon
    if ( function_exists('pll_current_language') && $button_url ) {
        $current_lang = pll_current_language(); // 'et', 'en', jne

        // Otsime Polylangi abil sisu samast lehe ID-st õige keele URL
        $page_id = url_to_postid($button_url); // Algsest URL-st ID
        if ( $page_id ) {
            $translated_id = pll_get_post($page_id, $current_lang); // Tagastab vastava keele ID
            if ( $translated_id ) {
                $button_url = get_permalink($translated_id);
            }
        }
    }
?>
    <a href="<?php echo esc_url( $button_url ); ?>" class="main-gallery-btn" target="<?php echo esc_attr( $button_target ); ?>">
        <?php echo esc_html( $button_title ); ?>
    </a>
<?php endif; ?>

  </div>
</section>

<!-- Modaalaken -->
<div id="main-gallery-modal" class="main-gallery-modal">
  <div class="main-gallery-modal-content">
    <span class="main-gallery-close">&times;</span>
    <img id="main-gallery-img" class="main-gallery-modal-img" src="" alt="">
    <div class="main-gallery-nav">
      <button id="main-gallery-prev">‹</button>
      <button id="main-gallery-next">›</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('main-gallery-modal');
  const modalImg = document.getElementById('main-gallery-img');
  const closeBtn = document.querySelector('.main-gallery-close');
  const prevBtn = document.getElementById('main-gallery-prev');
  const nextBtn = document.getElementById('main-gallery-next');

  let galleryItems = Array.from(document.querySelectorAll('.main-gallery-item'));
  let currentIndex = 0;

  function openModal(index) {
    currentIndex = index;
    modalImg.src = galleryItems[currentIndex].dataset.fullUrl;
    modal.classList.add('active');
    updateNavButtons();
  }

  function closeModal() {
    modal.classList.remove('active');
  }

  function updateNavButtons() {
    prevBtn.disabled = currentIndex === 0;
    nextBtn.disabled = currentIndex === galleryItems.length - 1;
  }

  galleryItems.forEach((item, index) => {
    item.addEventListener('click', () => openModal(index));
  });

  closeBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

  prevBtn.addEventListener('click', () => { if (currentIndex > 0) openModal(currentIndex - 1); });
  nextBtn.addEventListener('click', () => { if (currentIndex < galleryItems.length - 1) openModal(currentIndex + 1); });

  document.addEventListener('keydown', e => {
    if (!modal.classList.contains('active')) return;
    if (e.key === 'ArrowLeft' && currentIndex > 0) openModal(currentIndex - 1);
    if (e.key === 'ArrowRight' && currentIndex < galleryItems.length - 1) openModal(currentIndex + 1);
    if (e.key === 'Escape') closeModal();
  });
});
</script>





<!----------------------------
   Section 4 VIDEOD
----------------------------->

<section id="videod" class="section videos">
  <div class="video-container">
   <?php 
$title = get_field('videos_title');
if ( $title ) : ?>
    <h2 style="text-align:center;"><?php echo esc_html( $title ); ?></h2>
<?php endif; ?>


    <div class="video-slider-container">
      <button class="slider-btn prev" id="prev-video">‹</button>

      <div class="video-slider">
        <?php
        $args = array(
          'post_type'      => 'videos',
          'posts_per_page' => 6,
          'orderby'        => 'date',
          'order'          => 'DESC',
        );

        $video_query = new WP_Query($args);

        if ($video_query->have_posts()) :
          $video_index = 0;
          while ($video_query->have_posts()) : $video_query->the_post();

            $raw_content = trim(strip_tags(preg_replace('/<!--.*?-->/s', '', get_the_content())));

            if (!empty($raw_content) && wp_oembed_get($raw_content)) {
                $video_embed = wp_oembed_get($raw_content);

                // Lisa enablejsapi=1 alati õigesti
                $video_embed = preg_replace_callback(
                    '/src="([^"]+)"/',
                    function ($matches) {
                        $src = $matches[1];
                        if (strpos($src, 'enablejsapi=1') === false) {
                            $src .= (strpos($src, '?') !== false ? '&' : '?') . 'enablejsapi=1';
                        }
                        return 'src="' . esc_url($src) . '"';
                    },
                    $video_embed
                );

                // Lisa unikaalne ID iframe’ile
                $video_embed = preg_replace('/<iframe/', '<iframe id="ytplayer-' . $video_index . '"', $video_embed);

                echo '<div class="video-card">' . $video_embed . '</div>';
                $video_index++;

            } else {
                echo '<div class="video-card"><p style="color:#999;">Videot ei saa kuvada. Lisa YouTube link.</p></div>';
            }

          endwhile;
          wp_reset_postdata();
        else :
          echo '<p style="text-align:center;">Ühtegi videot pole veel lisatud.</p>';
        endif;
        ?>
      </div>

      <button class="slider-btn next" id="next-video">›</button>
    </div>
  </div>
</section>

<!-- Lisa YouTube Iframe API -->
<script src="https://www.youtube.com/iframe_api"></script>

<script>
// YouTube mängijate haldamine
let players = [];

function onYouTubeIframeAPIReady() {
  const iframes = document.querySelectorAll('#videod iframe');
  iframes.forEach((iframe, index) => {
    players[index] = new YT.Player(iframe.id, {
      events: {
        'onStateChange': onPlayerStateChange
      }
    });
  });
}

function onPlayerStateChange(event) {
  if (event.data === YT.PlayerState.PLAYING) {
    players.forEach(player => {
      if (player !== event.target) {
        try {
          player.pauseVideo();
        } catch (e) {}
      }
    });
  }
}

// Video slider buttons
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.video-slider');
    const prevBtn = document.getElementById('prev-video');
    const nextBtn = document.getElementById('next-video');

    if (!slider || !prevBtn || !nextBtn) return;

    function getCardDimensions() {
        const card = slider.querySelector('.video-card');
        if (!card) return null;
        return card.offsetWidth + 20;
    }

    prevBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const cardDim = getCardDimensions();
        if (cardDim) {
            slider.scrollBy({ left: -cardDim, behavior: 'smooth' });
        }
    });

    nextBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const cardDim = getCardDimensions();
        if (cardDim) {
            slider.scrollBy({ left: cardDim, behavior: 'smooth' });
        }
    });
});
</script>




<!----------------------------
   Section 5 TAGASISIDE
----------------------------->
<section id="tagasiside" class="section feedback">
  <div class="feedback-container">
    <?php 
$title = get_field('feedback_title');
if ( $title ) : ?>
    <h2 style="text-align:center; margin-bottom:40px;"><?php echo esc_html( $title ); ?></h2>
<?php endif; ?>

    <div class="feedback-slider-container">
      <button class="slider-btn prev" id="prev">‹</button>

      <div class="feedback-slider">

        <?php

        $args = array(
          'post_type'      => 'feedback',
          'posts_per_page' => 6,
          'orderby'        => 'date',
          'order'          => 'DESC',
        );

        $feedback_query = new WP_Query($args);

        if ($feedback_query->have_posts()) :
          while ($feedback_query->have_posts()) : $feedback_query->the_post();

   
            $content = strip_tags(
              preg_replace('/<!--.*?-->/s', '', get_the_content())
            );
        ?>
            <div class="feedback-card">
              <p><?php echo esc_html($content); ?></p>
            </div>

        <?php
          endwhile;
          wp_reset_postdata();
        else :
          echo '<p style="text-align:center;">Ühtegi tagasisidet pole veel lisatud.</p>';
        endif;
        ?>

      </div>

      <button class="slider-btn next" id="next">›</button>
    </div>
  </div>
</section>

<script src="<?php echo get_template_directory_uri(); ?>/js/feedback-slider.js" defer></script>


<!----------------------------
   Section 6 Esinemised
----------------------------->

<section id="esinemised" class="section performances">
  <div class="performances-container">
<?php 
// Pealkiri
$performances_title = get_field('performances_title');
if ( $performances_title ) : ?>
    <h2><?php echo esc_html( $performances_title ); ?></h2>
<?php endif; ?>
<?php
// Alapealkiri
$performances_subtitle = get_field('performances_subtitle');
if ( $performances_subtitle ) : ?>
    <p class="performances-subtitle"><?php echo esc_html( $performances_subtitle ); ?></p>
<?php endif; ?>


    <div class="performances-slider-container">
      <button class="slider-btn prev" id="prev-performance">‹</button>

      <div class="performances-slider">
        <?php
        $args = array(
          'post_type'      => 'performances',
          'posts_per_page' => 6,
          'orderby'        => 'date',
          'order'          => 'DESC',
        );

        $perf_query = new WP_Query($args);

        if ($perf_query->have_posts()) :
          while ($perf_query->have_posts()) : $perf_query->the_post();

            $date = get_post_meta(get_the_ID(), '_performance_date', true);
            $place = get_post_meta(get_the_ID(), '_performance_place', true);
            $info = get_post_meta(get_the_ID(), '_performance_info', true);
        ?>
            <div class="performance-card">
              <?php if ($date): ?>
                <p><?php echo esc_html($date); ?></p>
              <?php endif; ?>
              <?php if ($place): ?>
                <h4><?php echo esc_html($place); ?></h4>
              <?php endif; ?>
 <?php if ($info): ?>
  <a href="<?php echo esc_url($info); ?>" target="_blank">
    <?php echo esc_html($info_text); ?>
  </a>
<?php endif; ?>
            </div>

        <?php
          endwhile;
          wp_reset_postdata();
        else :
          echo '<p style="text-align:center;">Ühtegi esinemist pole veel lisatud.</p>';
        endif;
        ?>
      </div>

      <button class="slider-btn next" id="next-performance">›</button>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.performances-slider');
    const prevBtn = document.getElementById('prev-performance');
    const nextBtn = document.getElementById('next-performance');

    if (!slider || !prevBtn || !nextBtn) return;

    function getCenterCard() {
        const cards = slider.querySelectorAll('.performance-card');
        const sliderRect = slider.getBoundingClientRect();
        const sliderCenter = sliderRect.left + sliderRect.width / 2;

        let closestCard = cards[0];
        let closestDistance = Math.abs(closestCard.getBoundingClientRect().left + closestCard.offsetWidth / 2 - sliderCenter);

        cards.forEach(card => {
            const cardCenter = card.getBoundingClientRect().left + card.offsetWidth / 2;
            const distance = Math.abs(cardCenter - sliderCenter);
            if (distance < closestDistance) {
                closestDistance = distance;
                closestCard = card;
            }
        });

        return closestCard;
    }

    function scrollToCenter(direction) {
        const cards = slider.querySelectorAll('.performance-card');
        const currentCard = getCenterCard();
        const currentIndex = Array.from(cards).indexOf(currentCard);

        let targetCard;
        if (direction === 'next' && currentIndex < cards.length - 1) {
            targetCard = cards[currentIndex + 1];
        } else if (direction === 'prev' && currentIndex > 0) {
            targetCard = cards[currentIndex - 1];
        } else {
            return;
        }

        targetCard.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }

    prevBtn.addEventListener('click', function(e) {
        e.preventDefault();
        scrollToCenter('prev');
    });

    nextBtn.addEventListener('click', function(e) {
        e.preventDefault();
        scrollToCenter('next');
    });
});
</script>



<!----------------------------
   Section 7 KONTAKT
----------------------------->

<section id="kontakt" class="section contact">
  <h2><?php echo esc_html( get_field('contact_title') ?: 'Kontakt' ); ?></h2>
  
  <div class="contact-container">
    <p><?php echo esc_html( get_field('contact_text') ?: 'Täida vorm ja me vastame peagi.' ); ?></p>

    <div class="contact-form-wrapper">
      <?php
      // Saa lühikood ACF-ist (keeleversioonilt sõltuvalt)
      $form_shortcode = get_field('contact_form_shortcode');

      if ($form_shortcode) {
          echo do_shortcode($form_shortcode);
      } else {
          echo '<p>Kontaktivorm pole määratud.</p>';
      }
      ?>
      
      <button id="contactModalBtn" class="contact-info-btn">
        <?php echo esc_html( get_field('contact_title') ?: 'Kontakt' ); ?>
      </button>
    </div>

    <div class="contact-logo">
      <?php
      $contact_logo = get_field('contact_logo');
      if ($contact_logo) {
          echo '<img src="' . esc_url($contact_logo['url']) . '" alt="' . esc_attr($contact_logo['alt']) . '">';
      } else {
          the_custom_logo();
      }
      ?>
    </div>
  </div>
<!-- Kontaktmodaal -->
<div id="contactModalUnique" class="kontakt-modal">
    <div class="kontakt-modal-content">
        <span class="kontakt-modal-close">&times;</span>
        <h3><?php echo esc_html( get_field('contact_title') ?: 'Kontakt' ); ?></h3>

        <div class="kontakt-info-block">
            <label>Email</label>
            <a href="mailto:<?php echo esc_attr( get_theme_mod('kontakt_email') ); ?>">
                <?php echo esc_html( get_theme_mod('kontakt_email') ); ?>
            </a>
        </div>

        <div class="kontakt-info-block">
            <label>
                <?php 
                    // Kontrollime keelt
                    if ( function_exists('pll_current_language') && pll_current_language() == 'en' ) {
                        echo 'Phone';
                    } else {
                        echo 'Telefon';
                    }
                ?>
            </label>
            <a href="tel:<?php echo esc_attr( get_theme_mod('kontakt_phone') ); ?>">
                <?php echo esc_html( get_theme_mod('kontakt_phone') ); ?>
            </a>
        </div>
    </div>
</div>

</section>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('contactModalUnique');
    const btn = document.getElementById('contactModalBtn');
    const close = modal.querySelector('.kontakt-modal-close');

    // Avamine ainult nupu vajutamisel
    btn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    // Sulgemine X nupuga
    close.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Sulgemine klikiga taustal
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>


<?php get_footer(); ?>





