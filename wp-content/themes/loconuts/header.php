<?php
/**
 * Header with centered menu, right-aligned socials, and responsive hamburger for Loconuts theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">

<?php wp_head(); ?>

<style>
@font-face {
  font-family: 'Norwester';
  src: url('<?php echo get_template_directory_uri(); ?>/fonts/norwester_regular.otf') format('opentype');
  font-weight: normal;
  font-style: normal;
}

/* ====== HEADER ====== */
.site-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    padding: 0;
    background-color: rgba(255,255,255);
    backdrop-filter: blur(24px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

    z-index: 1000;
}

/* Logo vasakul */
.site-branding {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    transition: transform 0.2s ease;
    cursor: pointer;
}
.site-branding:hover { transform: translateY(-3px); }
.site-branding img {
    max-height: 40px;
    width: auto;
    display: block;
    padding: 5px;
}

/* Keskmine menüü */
.main-navigation {
    display: flex;
    justify-content: center;
    position: relative;
}
.main-navigation ul {
    display: flex !important;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 25px;
}
.main-navigation a {
    color: #1b1b1b;
    display: inline-block;
    text-decoration: none;
    font-weight: 500;
    font-family: 'Norwester', sans-serif;
    transition: transform 0.2s ease, color 0.2s ease;
}
.main-navigation a:hover { transform: translateY(-3px); }

/* Sotsiaalid paremal */
.header-socials {
    position: fixed;
    right: calc(12px + env(safe-area-inset-right, 0px));
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    gap: 10px;
    z-index: 1100;
}
.header-socials a svg {
    width: 24px;
    height: 24px;
    stroke: #c7a263;
    transition: transform 0.2s ease, stroke 0.2s ease;
}
.header-socials a:hover svg {
    transform: scale(1.2);
    stroke: #000;
}

.header-socials {
  display: flex;
  align-items: center;
  gap: 20px;
}

.header-socials a img {
  width: 24px;
  height: 24px;
  transition: transform 0.3s ease, opacity 0.3s ease;

}

.header-socials a:hover img {
  transform: scale(1.2);
  opacity: 0.8;
}

/* Hamburger */
.menu-toggle {
    display: none;
    flex-direction: column;
    justify-content: space-around;
    width: 30px;
    height: 24px;
    cursor: pointer;
    z-index: 1100;
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
}
.menu-toggle span {
    display: block;
    height: 3px;
    width: 100%;
    background: #c7a263;
    border-radius: 2px;
    transition: all 0.3s ease;
}
.menu-toggle.open span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
.menu-toggle.open span:nth-child(2) { opacity: 0; }
.menu-toggle.open span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }

/* MOBIILISTIILID */
@media (max-width: 1024px) {
    .main-navigation ul {
        gap: 5px;
    }
}

@media (max-width: 768px) {
    .site-header {
        grid-template-columns: auto auto;
        justify-content: space-between;
    }

    .header-socials a img {
  width: 28px;
  height: 28px;

}

    .menu-toggle {
        display: flex;
    }

    .menu-wrapper {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        height: 100vh;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(50px);
        -webkit-backdrop-filter: blur(50px);
        padding-top: 20px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        overflow-y: auto;
        transform: translateY(-100%);
        transition: transform 0.3s ease;
        z-index: 1059;
    }

    .main-navigation.toggled .menu-wrapper {
        transform: translateY(0);
    }

    .main-navigation ul {
        flex-direction: column !important;
        align-items: center;
    }

    .main-navigation a {
        font-size: 1.3rem;
        padding: 10px 0;
        color: #000;
    }

    .header-socials {
        position: fixed;
        left: 50%;
        top: 370px;
        transform: translateX(-50%);
        display: flex;
        justify-content: center;
        gap: 10px;
        z-index: 1100;
    }
}

/* Full-bleed for page content */
html, body, #page, .site {
    margin: 0;
    padding: 0;
    width: 100%;
    max-width: 100%;
}
.container {
    max-width: 100% !important;
    margin: 0 !important;
    padding-left: calc(20px + env(safe-area-inset-left, 0px));
    padding-right: calc(20px + env(safe-area-inset-right, 0px));
    box-sizing: border-box;
}

.custom-lang-flag img {
  width: 60px; /* suurenda vabalt */
  height: 60px;
  object-fit: contain;
  transition: transform 0.3s ease, opacity 0.3s ease;
  cursor: pointer;
}

.custom-lang-flag img:hover {
  transform: scale(1.25);
  opacity: 0.9;
}



</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.getElementById('site-navigation');

    menuToggle.addEventListener('click', () => {
        nav.classList.toggle('toggled');
        menuToggle.classList.toggle('open');
    });

    const closeMenuLinks = document.querySelectorAll('#primary-menu a');
    closeMenuLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                nav.classList.remove('toggled');
                menuToggle.classList.remove('open');
            }
        });
    });
});
</script>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'loconuts'); ?></a>

<header id="masthead" class="site-header">
    <div class="site-branding">
        <?php the_custom_logo(); ?>
    </div>

    <nav id="site-navigation" class="main-navigation">
        <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="menu-wrapper">
            <?php
            wp_nav_menu([
                'theme_location' => 'menu-1',
                'menu_id'        => 'primary-menu',
            ]);
            ?>

<div class="header-socials">
<?php
if ( function_exists('pll_the_languages') ) :
    $languages = pll_the_languages( [ 'raw' => 1 ] );
    if ( ! empty( $languages ) ) :
        $current_lang = pll_current_language();

        // Leia teise keele URL (nt kui on ainult 2 keelt)
        foreach ( $languages as $slug => $lang ) {
            if ( $slug !== $current_lang ) {
                $switch_url = $lang['url'];
                $switch_lang = $slug;
                break;
            }
        }
    endif;
endif;
?>

<?php if ( isset( $switch_url ) ) : ?>
    <a href="<?php echo esc_url( $switch_url ); ?>" class="custom-lang-flag" title="<?php echo esc_attr( strtoupper( $switch_lang ) ); ?>">
        <?php if ( $switch_lang === 'en' ) : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/flags/english-flag.svg" alt="English">
        <?php elseif ( $switch_lang === 'et' ) : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/flags/estonian-flag.svg" alt="Eesti">
        <?php endif; ?>
    </a>
<?php endif; ?>




    <!-- Sotsiaalide ikoonid -->
    <?php 
    $social_platforms = ['instagram', 'facebook', 'youtube'];

    foreach ($social_platforms as $platform) :
        $link = get_theme_mod("loconuts_header_{$platform}_link", '#');
        $icon = get_theme_mod("loconuts_header_{$platform}_icon", get_template_directory_uri() . "/assets/icons/{$platform}.svg");
    ?>
      <a href="<?php echo esc_url($link); ?>" target="_blank" aria-label="<?php echo ucfirst($platform); ?>">
        <img src="<?php echo esc_url($icon); ?>" alt="<?php echo ucfirst($platform); ?>">
      </a>
    <?php endforeach; ?>
</div>



        </div>
    </nav>
</header>
