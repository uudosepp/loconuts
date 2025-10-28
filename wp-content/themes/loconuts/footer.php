<?php
/**
 * The template for displaying the footer
 *
 * @package Loconuts
 */
?>
<style>
.gold-footer {
  position: relative; /* lisatud */
  z-index: 999;      /* lisatud */
  background-color: #c4a75a; /* kuldne */
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.footer-icons {
  display: flex;
  gap: 30px;
}

.footer-icons a img {
  width: 24px;
  height: 24px;
  transition: transform 0.3s ease, opacity 0.3s ease;
}

.footer-icons a:hover img {
  transform: scale(1.2);
  opacity: 0.8;
}
</style>

<footer class="gold-footer">
  <div class="footer-icons">
    <?php 
    $social_platforms = ['instagram', 'facebook', 'youtube'];

    foreach ($social_platforms as $platform) : 
        $link = get_theme_mod("gold_footer_{$platform}_link", '#');
        $icon = get_theme_mod("gold_footer_{$platform}_icon", get_template_directory_uri() . "/assets/icons/{$platform}.svg");
    ?>
      <a href="<?php echo esc_url($link); ?>" target="_blank" aria-label="<?php echo ucfirst($platform); ?>">
        <img src="<?php echo esc_url($icon); ?>" alt="<?php echo ucfirst($platform); ?>">
      </a>
    <?php endforeach; ?>
  </div>
</footer>


<?php wp_footer(); ?>
</body>
</html>
