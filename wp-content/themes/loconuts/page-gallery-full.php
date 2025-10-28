<?php
/**
 * Template Name: Full Gallery
 * Description: Displays every gallery image from all gallery posts.
 */

get_header();

$args = array(
    'post_type'      => 'gallery',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$gallery_query = new WP_Query( $args );
$gallery_ids   = array();

if ( $gallery_query->have_posts() ) :
    while ( $gallery_query->have_posts() ) :
        $gallery_query->the_post();

        // Parse Gutenberg blocks to pull image IDs stored in gallery/image blocks.
        $blocks = parse_blocks( get_the_content() );
        $ids    = loconuts_get_gallery_image_ids( $blocks );

        if ( ! empty( $ids ) ) {
            $gallery_ids = array_merge( $gallery_ids, $ids );
        }
    endwhile;
    wp_reset_postdata();
endif;

$gallery_ids = array_unique( array_map( 'intval', $gallery_ids ) );
?>



<style>
  .gallery-section {
    position: relative;
    padding: 60px 0;
    text-align: center;
    background: rgba(255, 255, 255, 0.9);
    z-index: 10;
  }

   .gallery-section h2 {
  font-family: 'Norwester', Arial, sans-serif;
  font-size: 2rem;
  font-weight: 700;
  letter-spacing: 0.04em;
  margin-top: 50px;
  margin-bottom: 10px;
  text-align: center;
}

  .gallery-grid {
    column-count: 2;
    column-gap: 8px;
    margin: 0 auto;
    position: relative;
    z-index: 10;
    max-width: 1200px;
  }

  @media (min-width: 600px) {
    .gallery-grid {
      column-count: 3;
    }
  }

  @media (min-width: 992px) {
    .gallery-grid {
      column-count: 4;
    }
  }

  .gallery-item {
    display: inline-block;
    width: 100%;
    margin-bottom: 4px;
    break-inside: avoid;
  }

  .gallery-item img {
    width: 100%;
    height: auto;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: block;
    position: relative;
    z-index: 10;
  }

  .gallery-item img:hover {
    transform: scale(1.03);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
  }

    /* Regular */
@font-face {
  font-family: 'Glacial Indifference';
  src: url("<?php echo get_template_directory_uri(); ?>/fonts/GlacialIndifference-Regular.otf") format('opentype');
  font-weight: 400;
  font-style: normal;
}

/* Bold */
@font-face {
  font-family: 'Glacial Indifference';
  src: url("<?php echo get_template_directory_uri(); ?>/fonts/GlacialIndifference-Bold.otf") format('opentype');
  font-weight: 700;
  font-style: normal;
}

  .btn-contact {
    display:inline-block;
  background: #c7a263;
  color: #fff;
  padding: 10px 24px;
  font-size: 0.9rem;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  transition: transform 0.2s ease, background-color 0.2s ease;
  text-decoration: none;
  font-family: 'Glacial Indifference', sans-serif;
  font-weight: 700;
  margin-top: 40px;
  letter-spacing: 0.03em;
}

  .btn-contact:hover {
  background: #a8863c;
  transform: translateY(-2px);
}


  .gallery-empty {
    margin-top: 20px;
    font-size: 1.1rem;
  }
</style>

<section id="galeriid" class="gallery-section">
  <div class="container">
   <h2><?php the_title(); ?></h2>

    <?php if ( ! empty( $gallery_ids ) ) : ?>
      <div class="gallery-grid">
        <?php foreach ( $gallery_ids as $id ) :
          $image_html = wp_get_attachment_image( $id, 'large', false, array( 'loading' => 'lazy' ) );
          if ( ! $image_html ) {
            continue;
          }
          ?>
          <div class="gallery-item">
            <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p class="gallery-empty"><?php esc_html_e( 'Galerii on hetkel tühi.', 'loconuts' ); ?></p>
    <?php endif; ?>

<?php 
$button = get_field('cta_button');
if ( $button ) :
    $button_url = $button['url'];
    $button_title = $button['title'];
    $button_target = $button['target'] ? $button['target'] : '_self';

    // Eemalda võimalik alamlehe osa ja lisa #kontakt
    $home_url = home_url('/');
    $button_url = trailingslashit($home_url) . '#kontakt';
?>
    <a href="<?php echo esc_url( $button_url ); ?>" class="btn-contact" target="<?php echo esc_attr( $button_target ); ?>">
        <?php echo esc_html( $button_title ); ?>
    </a>
<?php endif; ?>


  </div>
</section>

<?php
get_footer();
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const galleryItems = document.querySelectorAll('.gallery-item img');
  const modal = document.createElement('div');
  modal.classList.add('gallery-modal');
modal.innerHTML = `
  <div class="gallery-modal-content">
    <button class="close-modal">&times;</button>
    <button class="chevron left">&#10094;</button>
    <img class="modal-image" src="" alt="">
    <button class="chevron right">&#10095;</button>
    <div class="thumbnail-row"></div>
  </div>
`;

  document.body.appendChild(modal);
const closeBtn = modal.querySelector('.close-modal');
closeBtn.addEventListener('click', () => modal.classList.remove('open'));

  const modalImage = modal.querySelector('.modal-image');
  const thumbnailRow = modal.querySelector('.thumbnail-row');
  const leftBtn = modal.querySelector('.chevron.left');
  const rightBtn = modal.querySelector('.chevron.right');

  let currentIndex = 0;
  let imageSources = Array.from(galleryItems).map(img => img.src);

  // Lisa pisipildid
  imageSources.forEach((src, i) => {
    const thumb = document.createElement('img');
    thumb.src = src;
    thumb.classList.add('thumb');
    thumb.addEventListener('click', () => showImage(i));
    thumbnailRow.appendChild(thumb);
  });

  const thumbs = thumbnailRow.querySelectorAll('.thumb');

  galleryItems.forEach((img, i) => {
    img.addEventListener('click', () => {
      showImage(i);
      modal.classList.add('open');
    });
  });

  function showImage(index) {
    currentIndex = index;
    modalImage.src = imageSources[index];
    thumbs.forEach((t, j) => t.classList.toggle('active', j === index));
  }

  leftBtn.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + imageSources.length) % imageSources.length;
    showImage(currentIndex);
  });

  rightBtn.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % imageSources.length;
    showImage(currentIndex);
  });

  modal.addEventListener('click', e => {
    if (e.target === modal) modal.classList.remove('open');
  });

  document.addEventListener('keydown', e => {
    if (!modal.classList.contains('open')) return;
    if (e.key === 'ArrowLeft') leftBtn.click();
    if (e.key === 'ArrowRight') rightBtn.click();
    if (e.key === 'Escape') modal.classList.remove('open');
  });
});


</script>

<style>
  .close-modal {
    position: fixed;
    top: 50px;
    right: 50px;
    color: #fff;
    font-size: 32px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s ease;
    z-index: 10000;
    background: none;
    border: none;
    outline: none;
  }

.close-modal:hover {
  color: #c7a263;
  transform: scale(1.1);
}

.gallery-modal {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.9);
  justify-content: center;
  align-items: center;
  z-index: 9999;
  flex-direction: column;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.gallery-modal.open {
  display: flex;
  opacity: 1;
}

.gallery-modal-content {
  position: relative;
  max-width: 90%;
  max-height: 90%;
  text-align: center;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.97); }
  to { opacity: 1; transform: scale(1); }
}

.modal-image {
  max-width: 100%;
  max-height: 80vh;
  margin: 0 auto;
  display: block;
  border-radius: 6px;
  transition: opacity 0.3s ease;
}

.chevron {
  position: fixed; /* Hoidke ekraani suhtes fikseeritud */
  top: 50%;
  transform: translateY(-50%);
  background: none;
  color: #fff;
  font-size: 1rem;
  border: none;
  cursor: pointer;
  padding: 10px 16px;
  opacity: 1; /* alati nähtav */
  transition: transform 0.2s ease, background 0.2s ease;
  z-index: 10000;
  border-radius: 50%; /* ümmargune nupp */
}



.chevron.left { left: 50px; }
.chevron.right { right: 50px; }

/* Thumbnail-rida */
.thumbnail-row {
  display: flex;
  justify-content: center;
  gap: 6px;
  margin-top: 15px;
  flex-wrap: nowrap;
  overflow-x: auto;
  scroll-behavior: smooth;
}

/* Peidab kerimisriba, aga jätab libistamise toimima */
.thumbnail-row {
  scrollbar-width: thin; /* Firefox */
  scrollbar-color: transparent transparent; /* Firefox */
}
.thumbnail-row::-webkit-scrollbar {
  height: 8px;
  background: transparent;
}
.thumbnail-row::-webkit-scrollbar-thumb {
  background: transparent;
  border-radius: 4px;
}

.thumb {
  width: 60px;
  height: 60px;
  object-fit: cover;
  opacity: 0.6;
  border-radius: 4px;
  cursor: pointer;
  transition: transform 0.2s, opacity 0.2s;
}
.thumb:hover {
  opacity: 0.9;
  transform: scale(1.05);
}
.thumb.active {
  opacity: 1;
  outline: 2px solid #c7a263;
}

/* Peida pisipildid mobiilis */
@media (max-width: 768px) {
  .thumbnail-row {
    display: none;
  }
  .chevron.left, .chevron.right {
    font-size: 1rem;

  }
    .close-modal {

    right: 20px;

  }
  .chevron.left { left: 10px; }
.chevron.right { right: 10px; }
}

</style>
