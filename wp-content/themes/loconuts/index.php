<?php
/**
 * The main template file
 *
 * Custom clean version — no blog posts or search results.
 *
 * @package Loconuts
 */

get_header();
?>

<main id="primary" class="site-main">

  <?php
  // Kui tegemist on esilehega, näita ainult sisuplokke (ACF sektsioonid vms)
  if ( is_front_page() ) :

      // Siin saad lisada oma sektsioonid eraldi failidena või otse
      get_template_part('template-parts/section', 'hero');
      get_template_part('template-parts/section', 'meist');
      get_template_part('template-parts/section', 'galerii');
      get_template_part('template-parts/section', 'kontakt');

  else :

      // Kui mingi muu leht, siis kuva ainult sisu (nt page.php sarnane)
      if ( have_posts() ) :
          while ( have_posts() ) :
              the_post();
              the_content();
          endwhile;
      endif;

  endif;
  ?>

</main><!-- #main -->

<?php
get_footer();
